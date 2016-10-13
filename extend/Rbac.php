<?php
// +----------------------------------------------------------------------
// | tpadmin [a web admin based ThinkPHP5]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 tianpian
// +----------------------------------------------------------------------
// | Author: tianpian <tianpian0805@gmail.com>
// +----------------------------------------------------------------------

/**
 +------------------------------------------------------------------------------
 * 基于角色的数据库方式验证类
 +------------------------------------------------------------------------------
 */
// 配置文件增加设置
//'user_auth_on' 是否开启认证
//'user_auth_type' 默认认证类型 1 登录认证 2 实时认证
//'user_auth_key' 用户认证SESSION标记
//'admin_auth_key' 超级管理员认证标记
//'user_auth_model' 默认验证数据表模型
//'user_auth_gateway' 默认认证网关
//'not_auth_controller' 默认无需认证模块，逗号隔开
//'require_auth_controller' 默认需要认证模块，逗号隔开
//'not_auth_action' 默认无需认证操作，逗号隔开
//'require_auth_action' 默认需要认证操作，逗号隔开
//'common_auth_name' 公共授权控制器名称和方法名称
//'guest_auth_on' 是否开启游客授权访问
//'guest_auth_id'=>0 游客的用户ID

//'role_table'=>'admin_role', //角色表名称，不包含表前缀
//'user_table'=>'admin_role_user', //用户角色关系表名称，不包含表前缀
//'access_table'=>'admin_access', //权限表名称，不包含表前缀
//'node_table'=>'admin_node', //节点表名称，不包含表前缀

use think\Session;

class Rbac {
    static private $config_prefix = "rbac."; //配置信息前缀

    // 认证方法
    static public function authenticate($map,$model='') {
        if(empty($model)) $model =  config(self::$config_prefix.'user_auth_model');
        //使用给定的Map进行认证
        return db($model)->where($map)->find();
    }

    //用于检测用户权限的方法,并保存到Session中
    static function saveAccessList($authId=null) {
        if(null===$authId)   $authId = Session::get(config(self::$config_prefix.'user_auth_key'));
        // 如果使用普通权限模式，保存当前用户的访问权限列表
        // 对管理员开发所有权限
        if(config(self::$config_prefix.'user_auth_type') !=2 && !Session::has(config(self::$config_prefix.'admin_auth_key')) )
            Session::set('_access_list',self::getAccessList($authId));
        return ;
    }

    //检查当前操作是否需要认证
    static function checkAccess() {
        //如果项目要求认证，并且当前模块需要认证，则进行权限认证
        if( config(self::$config_prefix.'user_auth_on') ){
			$_controller	=	array();
			$_action	=	array();
            if("" != config(self::$config_prefix.'require_auth_controller')) {
                //需要认证的模块
                $_controller['yes'] = explode(',',strtoupper(config(self::$config_prefix.'require_auth_controller')));
            }else {
                //无需认证的模块
                $_controller['no'] = explode(',',strtoupper(config(self::$config_prefix.'not_auth_controller')));
            }
            //检查当前模块是否需要认证
            if((!empty($_controller['no']) && !in_array(strtoupper(request()->controller()),$_controller['no'])) || (!empty($_controller['yes']) && in_array(strtoupper(request()->controller()),$_controller['yes']))) {
				if("" != config(self::$config_prefix.'require_auth_action')) {
					//需要认证的操作
					$_action['yes'] = explode(',',strtoupper(config(self::$config_prefix.'require_auth_action')));
				}else {
					//无需认证的操作
					$_action['no'] = explode(',',strtoupper(config(self::$config_prefix.'not_auth_action')));
				}
				//检查当前操作是否需要认证
				if((!empty($_action['no']) && !in_array(strtoupper(request()->action()),$_action['no'])) || (!empty($_action['yes']) && in_array(strtoupper(request()->action()),$_action['yes']))) {
					return true;
				}else {
					return false;
				}
            }else {
                return false;
            }
        }
        return false;
    }

	// 登录检查
	static public function checkLogin() {
        //检查当前操作是否需要认证
        if(self::checkAccess()) {
            //检查认证识别号
            if(!Session::has(config(self::$config_prefix.'user_auth_key'))) {
                if(config(self::$config_prefix.'guest_auth_on')) {
                    // 开启游客授权访问
                    if(!Session::has('_access_list'))
                        // 保存游客权限
                        self::saveAccessList(config(self::$config_prefix.'guest_auth_id'));
                }else{
                    // 禁止游客访问跳转到认证网关
                    redirect(config(self::$config_prefix.'user_auth_gateway'));
                }
            }
        }
        return true;
	}

    //权限认证的过滤器方法
    static public function AccessDecision($moduleName=null,$controllerName=null,$actionName=null) {
        //检查是否需要认证
        if(self::checkAccess()) {
            //存在认证识别号，则进行进一步的访问决策
            if(!Session::has(config(self::$config_prefix.'admin_auth_key'))) {
                if ($moduleName === null) $moduleName = request()->module();
                if ($controllerName === null) $controllerName = request()->controller();
                if ($actionName === null) $actionName = request()->action();

                $module = strtoupper($moduleName);
                $controller = strtoupper($controllerName);
                $action = strtoupper($actionName);
                $common = strtoupper(self::$config_prefix."common_auth_name");

                if(config(self::$config_prefix.'user_auth_type') == 2) {
                    //加强验证和即时验证模式 更加安全 后台权限修改可以即时生效
                    //通过数据库进行访问检查
                    $accessList = self::getAccessList(Session::get(config(self::$config_prefix.'user_auth_key')));
                }else {
                    //登录验证模式，比较登录后保存的权限访问列表
                    $accessList = Session::get('_access_list');
                }

                //验证全名或者包含common控制器或方法的名称
                $node = explode(".",$controller);
                array_unshift($node,$module);
                array_push($node,$action);

                if (self::keyExist($accessList,$node))
                    return true;
                for ($i=1;$i<count($node)-1;$i++){
                    $tmp = $node;
                    $tmp[$i] = $common;
                    if (self::keyExist($accessList,$tmp))
                        return true;
                }
                return false;
            }else{
                //管理员无需认证
				return true;
			}
        }
        return true;
    }

    //判断多维数组是否存在$key中的key
    static private function keyExist($multi,$key){
        $tmp = $multi;
        while ($k = array_shift($key)){
            if (isset($tmp[$k])){
                $tmp = $tmp[$k];
            } else {
                return false;
            }
        }
        return true;
    }

    /**
     +----------------------------------------------------------
     * 取得当前认证号的所有权限列表
     +----------------------------------------------------------
     * @param integer $authId 用户ID
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     */
    static public function getAccessList($authId) {
        // Db方式权限数据
        $db = db();
        $table_prefix = config('database.prefix');
        $table = array(
            'role'=>$table_prefix.config(self::$config_prefix.'role_table'),
            'user'=>$table_prefix.config(self::$config_prefix.'user_table'),
            'access'=>$table_prefix.config(self::$config_prefix.'access_table'),
            'node'=>$table_prefix.config(self::$config_prefix.'node_table')
        );
        $sql = "select node.id,node.name,node.pid from ".
                $table['role']." as role,".
                $table['user']." as user,".
                $table['access']." as access ,".
                $table['node']." as node ".
                "where user.user_id='{$authId}' and user.role_id=role.id and access.role_id=role.id and role.status=1 and access.node_id=node.id and node.status=1";
        $apps =  $db->query($sql);
        //转化为树
        $tree = list_to_tree($apps);
        //递归生成权限树
        return tree_to_multi_array($tree,"name",["id"],"_child","strtoupper");
    }
}