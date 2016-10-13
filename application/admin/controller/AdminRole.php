<?php
// +----------------------------------------------------------------------
// | tpadmin [a web admin based ThinkPHP5]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 tianpian
// +----------------------------------------------------------------------
// | Author: tianpian <tianpian0805@gmail.com>
// +----------------------------------------------------------------------

//------------------------
// 角色控制器
//-------------------------

namespace app\admin\controller;
use app\admin\Controller;

class AdminRole extends Controller{
    protected function _filter(&$map){
        if (input("param.name")) $map['name'] = array("like","%".input("param.name")."%");
    }

    /**
     * 用户列表
     */
    public function user()
    {
        $role_id = input("param.id/d");
        if (request()->isPost()){ //提交
            if (!$role_id){
                ajax_return_adv_error("缺少必要参数");
            }

            $db_role_user = db("AdminRoleUser");
            //删除之前的角色绑定
            $db_role_user->where(["role_id"=>$role_id])->delete();
            //写入新的角色绑定
            $data = input("post.");
            if (isset($data['user_id']) && !empty($data['user_id']) && is_array($data['user_id'])){
                $insert_all = [];
                foreach ($data['user_id'] as $v){
                    $insert_all[] = [
                        "role_id" => $role_id,
                        "user_id" => intval($v)
                    ];
                }
                $db_role_user->insertAll($insert_all);
            }
            ajax_return_adv("分配角色成功");
        } else { //编辑页
            if (!$role_id){
                exception("缺少必要参数");
            }
            //读取系统的用户列表
            $list_user=db("AdminUser")->field('id,account,realname')->where('status=1 AND id > 1')->select();

            $list_role_user = db("AdminRoleUser")->where("role_id",$role_id)->select();
            $checks = filterValue($list_role_user,"user_id",true);

            $this->assign('list',$list_user);
            $this->assign('checks',$checks);

            return $this->fetch();
        }
    }

    /**
     * 授权
     * @return mixed
     */
    public function access(){
        $role_id = input("param.id/d");
        if (request()->isPost()){
            if (!$role_id){
                ajax_return_adv_error("缺少必要参数");
            }

            $db_access = db("AdminAccess");
            //删除之前的权限分配
            $db_access->where(["role_id"=>$role_id])->delete();
            //写入新的权限分配
            $data = input("post.");
            if (isset($data['node_id']) && !empty($data['node_id']) && is_array($data['node_id'])){
                $insert_all = [];
                foreach ($data['node_id'] as $v){
                    $node = explode('_', $v);
                    if (!isset($node[2])){
                        ajax_return_adv_error("参数错误");
                    }

                    $insert_all[] = [
                        "role_id" => $role_id,
                        "node_id" => $node[0],
                        "level" => $node[1],
                        "pid" => $node[2],
                    ];
                }
                $db_access->insertAll($insert_all);
            }
            ajax_return_adv("权限分配成功");
        } else {
            if (!$role_id){
                exception("缺少必要参数");
            }

            //分组信息
            $list_group = db("AdminGroup")->where("status=1 AND isdelete=0")->field("id,name")->order("sort asc")->select();
            $group = resetByKey($list_group,"id");

            //节点信息
            $node = db("AdminNode")->field("id,pid,group_id,name,title,level,type")->select();
            $accesses = db("AdminAccess")->where("role_id",$role_id)->select();
            $accesses_node = filterValue($accesses,"node_id");

            //生成wdTree插件需要的数据格式
            $node_tree = [];
            foreach ($node as $v){
                $node_tree[] = [
                    "id" => $v['id'],
                    "pid" => $v['pid'],
                    "text" => $v['title']." (".$v['name'].") ".(isset($group[$v['group_id']])?'<span style="color:red">[ '.$group[$v['group_id']]['name'].' ]</span>':''),
                    "value" => $v['id']."_".$v['level']."_".$v['pid'],
                    "showcheck" => true,
                    'checkstate' => in_array($v['id'],$accesses_node) ? 1 : 0,
                    'hasChildren' => true,
                    'isexpand' => $v['type'] ? true : false,
                    'complete' => true,
                ];
            }

            //生成树
            $tree = list_to_tree($node_tree,"id","pid","ChildNodes");
            $this->assign("tree",json_encode($tree));

            return $this->fetch();
        }
    }

    /**
     * 三层节点授权
     * @return mixed
     */
    /*public function three_level(){
        //节点转化为树
        $list_node = db("AdminNode")->where("status=1 AND isdelete=0")->field("id,pid,group_id,name,title,level")->select();
        $node = list_to_tree($list_node);
        $this->assign('node',$node);

        //分组
        $list_group = db("AdminGroup")->where("status=1 AND isdelete=0")->field("id,name")->order("sort asc")->select();
        array_push($list_group,["id"=>0,"name"=>"未分组"]);
        $this->assign('list_group',$list_group);

        //已授权权限
        $list_access = db("AdminAccess")->where("role_id",$role_id)->field("node_id")->select();
        $checks = filterValue($list_access,"node_id",true);
        $this->assign('checks',$checks);

        return $this->fetch();
    }*/
}