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
// 节点图控制器
//-------------------------

namespace app\admin\controller;

use app\admin\Controller;
use think\Db;
use think\Loader;

class NodeMap extends Controller
{
    use \app\admin\traits\controller\Controller;

    protected static $isdelete = false;

    protected static $blacklist = ['resume', 'recycle', 'recycleBin', 'delete', 'forbid', 'clear'];

    protected function filter(&$map)
    {
        if ($this->request->param("map")) $map['map'] = ["like", "%" . $this->request->param("map") . "%"];
        if ($this->request->param("comment")) $map['comment'] = ["like", "%" . $this->request->param("comment") . "%"];
    }

    /**
     * 自动导入
     */
    public function load()
    {
        Loader::model('NodeMap', 'logic')->load('admin', ['Ueditor', 'Generate', 'Error']);

        return ajax_return_adv('导入成功', 'current');
    }
}