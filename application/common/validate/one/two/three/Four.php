<?php
namespace app\common\validate\one\two\three;

use think\Validate;

class Four extends Validate
{
    protected $rule = [
        "field1|字段一" => "require",
        "date|日期" => "require",
        "mobile|手机号" => "require",
        "email|邮箱" => "require",
        "sort|排序" => "require",
        "status|状态" => "require",
    ];
}
