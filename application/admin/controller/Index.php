<?php
// +----------------------------------------------------------------------
// | tpadmin [a web admin based ThinkPHP5]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 tianpian
// +----------------------------------------------------------------------
// | Author: tianpian <tianpian0805@gmail.com>
// +----------------------------------------------------------------------

//------------------------
// 管理后台首页
//-------------------------

namespace app\admin\controller;
use app\admin\Controller;
use think\Session;

class Index extends Controller{

    public function index(){
        //读取数据库模块列表生成菜单项
        $prefix = config("database.prefix");
        if (Session::has(config("rbac.admin_auth_key"))){
            $nodes = db("AdminNode")->where("status=1 AND group_id > 0")->field("id,name,pid,group_id,title,type")->select();
        } else {
            $auth_id = Session::get(config('rbac.user_auth_key'));
            $sql = "select node.id,node.name,node.pid,node.group_id,node.title,node.type from ".
                "{$prefix}admin_role as role,".
                "{$prefix}admin_role_user as user,".
                "{$prefix}admin_access as access ,".
                "{$prefix}admin_node as node ".
                "where user.user_id='{$auth_id}' and user.role_id=role.id and access.role_id=role.id and role.status=1 and access.node_id=node.id and node.status=1 and node.group_id > 0 order by node.sort asc";
            $nodes =  db()->query($sql);
        }

        //节点转为树
        $tree_node = list_to_tree($nodes);

        //显示菜单项
        $menu = [];
        $groups_id = [];
        foreach ($tree_node as $module){
            if (strtoupper($module['name']) == strtoupper(request()->module())){
                foreach ($module['_child'] as $controller){
                    $group_id = $controller['group_id'];
                    array_push($groups_id,$group_id);
                    $menu[$group_id][] = $controller;
                }
                break;
            }
        }

        //获取授权节点分组信息
        $groups_id = array_unique($groups_id);
        if (!$groups_id){
            exception("没有权限");
        }
        $groups=db("AdminGroup")->where(array('id'=>array('in',$groups_id),'status'=>"1"))->order("sort asc,id asc")->field('id,name,icon')->select();

        $this->assign('groups',$groups);
        $this->assign('menu',$menu);

        return $this->fetch();
    }

    /**
     * 欢迎页
     * @return mixed
     */
    public function welcome(){
        //查询ip地址和登录地点
        $last_login_ip = Session::get('last_login_ip');
        $last_login_loc = \Ip::find($last_login_ip);
        $current_login_ip = request()->ip();
        $current_login_loc = \Ip::find($current_login_ip);

        $this->assign("last_login_ip",$last_login_ip);
        $this->assign("current_login_ip",$current_login_ip);
        $this->assign("last_login_loc",implode(" ",$last_login_loc));
        $this->assign("current_login_loc",implode(" ",$current_login_loc));

        //查询个人信息
        $info = db("AdminUser")->where("id",Session::get(config("rbac.user_auth_key")))->find();
        $this->assign("info",$info);

        return $this->fetch();
    }
}