<?php
// +----------------------------------------------------------------------
// | tpadmin [a web admin based ThinkPHP5]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 tianpian
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

    protected $isdelete = false;

    protected function filter(&$map)
    {
        if (input("param.title")) $map['title'] = ["like", "%" . input("param.title") . "%"];
        if (input("param.name")) $map['name'] = ["like", "%" . input("param.name") . "%"];
    }
}