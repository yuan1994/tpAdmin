<?php
// +----------------------------------------------------------------------
// | tpadmin [a web admin based ThinkPHP5]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 tianpian
// +----------------------------------------------------------------------
// | Author: tianpian <tianpian0805@gmail.com>
// +----------------------------------------------------------------------

//------------------------
// 分组管理验证器
//-------------------------

namespace app\admin\validate;
use think\Validate;

class AdminGroup extends Validate{
    protected $rule = [
        "name|分组名称" => "require|unique:admin_group",
    ];
}