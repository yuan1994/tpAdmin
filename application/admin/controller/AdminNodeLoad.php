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
// 节点快速导入控制器
//-------------------------

namespace app\admin\controller;

use app\admin\Controller;

class AdminNodeLoad extends Controller
{
    use \app\admin\traits\controller\Controller;

    protected static $isdelete = false;

    protected static $blacklist = ['delete', 'recycle'];

    protected function filter(&$map)
    {
        if ($this->request->param('title')) $map['title'] = ["like", "%" . $this->request->param('title') . "%"];
        if ($this->request->param('name')) $map['name'] = ["like", "%" . $this->request->param('name') . "%"];
    }
}