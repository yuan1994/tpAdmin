<?php
// +----------------------------------------------------------------------
// | tpadmin [a web admin based ThinkPHP5]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 tianpian
// +----------------------------------------------------------------------
// | Author: tianpian <tianpian0805@gmail.com>
// +----------------------------------------------------------------------

//------------------------
// 分组管理
//-------------------------

namespace app\admin\controller;
use app\admin\Controller;

class AdminGroup extends Controller{
    protected function _filter(&$map){
        if (input("param.name")) $map['name'] = array("like","%".input("param.name")."%");
    }
    public function _empty(){
        return $this->fetch();
    }

    /**
     * 禁用限制
     */
    protected function _before_forbid(){
        //禁止禁用Admin模块,权限设置节点
        $this->_filter_id([1,2],'该记录不能被禁用');
    }

    /**
     * 删除限制
     */
    protected function _before_delete(){
        //禁止删除Admin模块,权限设置节点
        $this->_filter_id([1,2],'该节点不能被删除');
    }

    /**
     * 永久删除限制
     */
    protected function _before_foreverDelete(){
        //禁止删除Admin模块,权限设置节点
        $this->_filter_id([1,2],'该节点不能被删除');
    }
}