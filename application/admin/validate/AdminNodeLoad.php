<?php
// +----------------------------------------------------------------------
// | tpadmin [a web admin based ThinkPHP5]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 tianpian
// +----------------------------------------------------------------------
// | Author: tianpian <tianpian0805@gmail.com>
// +----------------------------------------------------------------------

//------------------------
// 节点快速导入验证器
//-------------------------

namespace app\admin\validate;
use think\Validate;

class AdminNodeLoad extends Validate{
    protected $rule = [
        "name|节点名称" => "require|unique:admin_node_load",
    ];
}