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

class AdminGroup extends Controller
{
    use \app\admin\traits\controller\Controller;

    protected function filter(&$map)
    {
        if (input("param.name")) $map['name'] = ["like", "%" . input("param.name") . "%"];
    }

    /**
     * 禁用限制
     */
    protected function _before_forbid()
    {
        //禁止禁用Admin模块,权限设置节点
        $this->filterId([1, 2], '该记录不能被禁用');
    }

    /**
     * 删除限制
     */
    protected function _before_delete()
    {
        //禁止删除Admin模块,权限设置节点
        $this->filterId([1, 2], '该节点不能被删除');
    }

    /**
     * 永久删除限制
     */
    protected function _before_foreverDelete()
    {
        //禁止删除Admin模块,权限设置节点
        $this->filterId([1, 2], '该节点不能被删除');
    }
}
