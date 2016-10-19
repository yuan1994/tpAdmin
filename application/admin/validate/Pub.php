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

namespace app\admin\validate;

use think\Validate;

class Pub extends Validate
{
    protected $rule = [
        'account|帐号'      => 'require',
        'password|密码'     => 'require',
        'captcha|验证码'     => 'require|captcha',
        'oldpassword|旧密码' => 'require',
        'repassword|重复密码' => 'require',
    ];

    protected $scene = [
        'password' => ['password', 'oldpassword', 'repassword'],
        'login'    => ['account', 'password', 'captcha'],
    ];
}