<?php
// +----------------------------------------------------------------------
// | tpadmin [a web admin based ThinkPHP5]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 tianpian All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
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

//    protected static $blacklist = ['recyclebin', 'delete', 'recycle', 'deleteforever', 'clear'];

    protected function filter(&$map)
    {
        if ($this->request->param('name')) $map['name'] = ["like", "%" . $this->request->param('name') . "%"];
    }

    /**
     * 禁用限制
     */
    protected function beforeForbid()
    {
        //禁止禁用Admin模块,权限设置节点
        $this->filterId([1, 2], '该分组不能被禁用');
    }

    /**
     * 删除限制
     */
    protected function beforeDelete()
    {
        //禁止删除Admin模块,权限设置节点
        $this->filterId([1, 2], '该分组不能被删除');
    }

    /**
     * 永久删除限制
     */
    protected function beforeForeverDelete()
    {
        //禁止删除Admin模块,权限设置节点
        $this->filterId([1, 2], '该分组不能被删除');
    }
}
