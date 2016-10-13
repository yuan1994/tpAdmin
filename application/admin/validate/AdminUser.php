<?php
// +----------------------------------------------------------------------
// | tpadmin [a web admin based ThinkPHP5]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 tianpian
// +----------------------------------------------------------------------
// | Author: tianpian <tianpian0805@gmail.com>
// +----------------------------------------------------------------------

//------------------------
// 用户验证器
//-------------------------

namespace app\admin\validate;
use think\Validate;

class AdminUser extends Validate{
    protected $rule = [
        "realname|姓名" => "require",
        "account|帐号" => "unique:admin_user",
    ];
}