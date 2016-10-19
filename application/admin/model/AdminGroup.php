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
// 分组模型
//-------------------------

namespace app\admin\model;

use think\Model;

class AdminGroup extends Model
{
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    /**
     * 列表
     */
    public function getList($field = 'id,name', $where = 'isdelete=0 AND status=1')
    {
        return $this->field($field)->where($where)->select();
    }
}