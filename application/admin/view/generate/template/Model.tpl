<?php
namespace app\admin\model[NAMESPACE];

use think\Model;

class [NAME] extends Model
{
    // 指定表名,不含前缀
    protected $name = '[TABLE]';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
}
