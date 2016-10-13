<?php
// +----------------------------------------------------------------------
// | tpadmin [a web admin based ThinkPHP5]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 tianpian
// +----------------------------------------------------------------------
// | Author: tianpian <tianpian0805@gmail.com>
// +----------------------------------------------------------------------

//------------------------
// 登录日志控制器
//-------------------------

namespace app\admin\controller;
use app\admin\Controller;

class LoginLog extends Controller{
    protected $isdelete = false; //禁用该字段

    protected function _filter(&$map){
        if (input("param.login_location")) $map['login_location'] = array("like","%".input("param.login_location")."%");

        //关联筛选
        if (input("param.account")) $map['admin_user.account'] = input("param.account");
        if (input("param.name")) $map['admin_user.realname'] = array("like","%".input("param.name")."%");

        //设置属性
        $map['_table'] = "login_log";
        $map['_relation'] = "user";
        $map['_order_by'] = false;
    }
}