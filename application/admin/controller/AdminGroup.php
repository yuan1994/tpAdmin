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
}