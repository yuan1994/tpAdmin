<?php
// +----------------------------------------------------------------------
// | tpadmin [a web admin based ThinkPHP5]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 tianpian
// +----------------------------------------------------------------------
// | Author: tianpian <tianpian0805@gmail.com>
// +----------------------------------------------------------------------

//------------------------
// 节点控制器
//-------------------------

namespace app\admin\controller;
use app\admin\Controller;

class AdminNode extends Controller{
    protected function _filter(&$map){
        if (request()->action() == 'index'){
            $map['pid'] = input("param.pid",0);
        }

        if (input("param.title")) $map['title'] = array("like","%".input("param.title")."%");
        if (input("param.name")) $map['name'] = array("like","%".input("param.name")."%");
    }

    protected function _before_index() {
        $group_list	=	db("AdminGroup")->field('id,name')->where("isdelete=0 AND status=1")->select();
        $this->assign('group_list',resetByKey($group_list,"id"));
    }

    protected function _before_recyclebin() {
        $group_list	=	db("AdminGroup")->field('id,name')->where("isdelete=0 AND status=1")->select();
        $this->assign('group_list',resetByKey($group_list,"id"));
    }

    protected function _before_add() {
        //分组
        $group_list	=	db("AdminGroup")->field('id,name')->where("isdelete=0 AND status=1")->select();
        $this->assign('group_list',$group_list);

        //父节点和层级
        $node = db("AdminNode")->where("id",input("param.pid/d"))->field("id,level")->find();
        $vo['pid'] = $node['id'];
        $vo['level'] = intval($node['level'])+1;
        $this->assign('vo',$vo);
    }

    /**
     * 节点快速导入
     */
    public function load(){
        if (request()->isPost()){
            $data = input("post.");
            $validate = validate("AdminNode");
            $insert_all = [];
            $error = []; //错误信息
            $nodes = isset($data['node']) ? $data['node'] : [];
            $node_name = isset($data['node_name']) ? $data['node_name'] : [];
            unset($data['node'],$data['node_name']);
            //有选择模板
            if ($nodes){
                $nodes = db("AdminNodeLoad")->where("id","in",$data['node'])->field("name,title")->select();
                foreach ($nodes as $node){
                    $insert = array_merge($data,$node);
                    //数据校验
                    if (!$validate->check($insert)){
                        $error[] = ["data"=>$node,"error"=>$validate->getError()];
                        continue;
                    }
                    $insert_all[] = $insert;
                }
            }
            //有选择自动探测到的节点
            if ($node_name){
                foreach ($node_name as $name){
                    list($data['name'],$data['title']) = explode("###",$name);
                    //数据校验
                    if (!$validate->check($data)){
                        $error[] = ["data"=>$data,"error"=>$validate->getError()];
                        continue;
                    }
                    $insert_all[] = $data;
                }
            }
            if ($insert_all){
                db("AdminNode")->insertAll($insert_all);
            }
            if ($error){
                //拼接错误信息
                $errormsg = "部分节点导入失败：";
                foreach ($error as $err){
                    $errormsg .= "<br>{$err['data']['title']}({$err['data']['name']})：{$err['error']}";
                }
                ajax_return_adv_error($errormsg);
            }
            ajax_return_adv("批量导入成功");
        } else {
            //分组
            $group_list	=	db("AdminGroup")->field('id,name')->where("isdelete=0 AND status=1")->select();
            $this->assign('group_list',$group_list);

            //父节点和层级
            $db_node = db("AdminNode");
            $node = $db_node->where("id",input("param.pid/d"))->field("id,pid,name,level")->find();
            $vo['pid'] = $node['id'];
            $vo['level'] = intval($node['level'])+1;
            $this->assign('vo',$vo);

            //模板库
            $node_template = db("AdminNodeLoad")->field("id,name,title")->where("status=1")->select();
            $this->assign("node_template",$node_template);

            //公共方法
            $node_public = \ReadClass::method("\\app\\admin\\Controller");
            $this->assign("node_public",$node_public?:[]);

            //当前方法
            //递归获取所有父级节点
            $parent_node = "";
            $pid = $node['pid'];
            while ($pid>1){
                if ($current_node = $db_node->where("id",$pid)->field("id,pid,name")->find()){
                    $parent_node = "\\".$current_node['name'].$parent_node;
                    $pid = $current_node['pid'];
                } else {
                    break;
                }
            }
            //方法生成
            $node_current_name = "\\app\\admin\\controller".strtolower($parent_node)."\\".\think\Loader::parseName($node['name'],1);
            $node_current = \ReadClass::method($node_current_name);
            $this->assign("node_current",$node_current?:[]);

            return $this->fetch();
        }
    }

    protected function _before_edit() {
        //分组
        $group_list	=	db("AdminGroup")->field('id,name')->where("isdelete=0 AND status=1")->select();
        $this->assign('group_list',$group_list);
    }

    /**
     * 禁用限制
     */
    protected function _before_forbid(){
        //禁止禁用Admin模块,权限设置节点
        $this->_filter_id([1,2,3,4,5,6],'该记录不能被禁用');
    }

    /**
     * 删除限制
     */
    protected function _before_delete(){
        //禁止删除Admin模块,权限设置节点
        $this->_filter_id([1,2,3,4,5,6],'该节点不能被删除');
    }

    /**
     * 不允许清空
     */
    public function clear()
    {
        ajax_return_adv_error('非法请求');
    }

    /**
     * 不允许永久删除
     */
    public function deleteForever()
    {
        ajax_return_adv_error('非法请求');
    }
}