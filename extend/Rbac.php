<?php
// +----------------------------------------------------------------------
// | tpadmin [a web admin based ThinkPHP5]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 tianpian
// +----------------------------------------------------------------------
// | Author: tianpian <tianpian0805@gmail.com>
// +----------------------------------------------------------------------

//------------------------
// 基于角色的数据库方式验证类
//-------------------------

use think\Db;

class Rbac
{
    /**
     * 认证方法
     * @param $map
     * @param string $model
     * @return array|false|PDOStatement|string|\think\Model
     */
    public static function authenticate($map, $model = '')
    {
        if (!$model) {
            $model = config('rbac.user_auth_model');
        }
        //使用给定的Map进行认证
        return Db::name($model)->where($map)->find();
    }

    /**
     * 用于检测用户权限的方法,并保存到Session中
     * @param null $authId
     */
    private static function saveAccessList($authId = null)
    {
        if (null === $authId) {
            $authId = session(config('rbac.user_auth_key'));
        }

        // 如果使用普通权限模式，保存当前用户的访问权限列表
        if (2 != config('rbac.user_auth_type') && !session(config('rbac.admin_auth_key'))) {
            session('_access_list', self::getAccessList($authId));
        }
        return;
    }

    /**
     * 检查当前操作是否需要认证
     * @return bool
     */
    public static function checkAccess()
    {
        //如果项目要求认证，并且当前模块需要认证，则进行权限认证
        if (config('rbac.user_auth_on')) {
            $controller = [];
            $action = [];
            if ('' != config('rbac.require_auth_controller')) {
                //需要认证的模块
                $controller['yes'] = explode(',', strtoupper(config('rbac.require_auth_controller')));
            } else {
                //无需认证的模块
                $controller['no'] = explode(',', strtoupper(config('rbac.not_auth_controller')));
            }
            //检查当前模块是否需要认证
            if (
                ($controller['no'] && !in_array(strtoupper(request()->controller()), $controller['no']))
                ||
                ($controller['yes'] && in_array(strtoupper(request()->controller()), $controller['yes']))
            ) {
                if (config('rbac.require_auth_action')) {
                    //需要认证的操作
                    $action['yes'] = explode(',', strtoupper(config('rbac.require_auth_action')));
                } else {
                    //无需认证的操作
                    $action['no'] = explode(',', strtoupper(config('rbac.not_auth_action')));
                }
                //检查当前操作是否需要认证
                if (
                    ($action['no'] && !in_array(strtoupper(request()->action()), $action['no']))
                    ||
                    ($action['yes'] && in_array(strtoupper(request()->action()), $action['yes']))
                ) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * 登录检查
     * @return bool
     */
    public static function checkLogin()
    {
        //检查当前操作是否需要认证
        if (self::checkAccess()) {
            //检查认证识别号
            if (!session('?' . config('rbac.user_auth_key'))) {
                if (config('rbac.guest_auth_on')) {
                    // 开启游客授权访问
                    if (session('?' . '_access_list'))
                        // 保存游客权限
                        self::saveAccessList(config('rbac.guest_auth_id'));
                } else {
                    // 禁止游客访问
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * 权限认证的过滤器方法
     * @param null $moduleName
     * @param null $controllerName
     * @param null $actionName
     * @return bool
     */
    public static function AccessDecision($moduleName = null, $controllerName = null, $actionName = null)
    {
        //检查是否需要认证
        if (self::checkAccess()) {
            //存在认证识别号，则进行进一步的访问决策
            if (!session('?' . config('rbac.admin_auth_key'))) {
                if (null === $moduleName) {
                    $moduleName = request()->module();
                }
                if (null === $controllerName) {
                    $controllerName = request()->controller();
                }
                if (null === $actionName) {
                    $actionName = request()->action();
                }

                $module = strtoupper($moduleName);
                $controller = strtoupper($controllerName);
                $action = strtoupper($actionName);
                $common = strtoupper('rbac.common_auth_name');

                if (2 == config('rbac.user_auth_type')) {
                    //加强验证和即时验证模式 更加安全 后台权限修改可以即时生效
                    $accessList = self::getAccessList(session(config('rbac.user_auth_key')));
                } else {
                    //登录验证模式，比较登录后保存的权限访问列表
                    $accessList = session('_access_list');
                }

                //验证全名或者包含common控制器或方法的名称
                $node = explode('.', $controller);
                array_unshift($node, $module);
                array_push($node, $action);

                //检查当前节点是否直接授权
                if (self::keyExist($accessList, $node)) {
                    return true;
                }

                //检查是否有公共节点授权
                for ($i = 1; $i < count($node) - 1; $i++) {
                    $tmp = $node;
                    $tmp[$i] = $common;
                    if (self::keyExist($accessList, $tmp)) {
                        return true;
                    }
                }
                return false;
            } else {
                //管理员无需认证
                return true;
            }
        }
        return true;
    }

    /**
     * 判断多维数组是否存在$key中的key
     * @param $multi
     * @param $key
     * @return bool
     */
    private static function keyExist($multi, $key)
    {
        $tmp = $multi;
        while ($k = array_shift($key)) {
            if (isset($tmp[$k])) {
                $tmp = $tmp[$k];
            } else {
                return false;
            }
        }
        return true;
    }

    /**
     * 取得当前认证号的所有权限列表
     * @param $authId
     * @return array
     */
    public static function getAccessList($authId)
    {
        // 表前缀
        $table_prefix = config('database.prefix');
        $table = [
            'role'   => $table_prefix . config('rbac.role_table'),
            'user'   => $table_prefix . config('rbac.user_table'),
            'access' => $table_prefix . config('rbac.access_table'),
            'node'   => $table_prefix . config('rbac.node_table')
        ];
        $sql = "SELECT node.id,node.name,node.pid FROM " .
            $table['role'] . " AS role," .
            $table['user'] . " AS user," .
            $table['access'] . " AS access ," .
            $table['node'] . " AS node " .
            "WHERE " .
            "user.user_id='{$authId}' " .
            "AND user.role_id=role.id " .
            "AND access.role_id=role.id " .
            "AND role.status=1 " .
            "AND access.node_id=node.id " .
            "AND node.status=1 " .
            "AND (" .
                "role.pid = 0 " .
                "OR (role.pid <> 0 " .
                "AND node.id IN (" .
                    "SELECT node_id FROM {$table['access']} WHERE role_id = role.pid" .
                    ")" .
                ")" .
            ")";
        $apps = Db::query($sql);
        //转化为树
        $tree = list_to_tree($apps);
        //递归生成权限树
        return tree_to_multi_array($tree, "name", ["id"], "_child", "strtoupper");
    }
}
