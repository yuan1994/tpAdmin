<?php
/**
 * tpAdmin [a web admin based ThinkPHP5]
 *
 * @author yuan1994 <tianpian0805@gmail.com>
 * @link http://tpadmin.yuan1994.com/
 * @copyright 2016 yuan1994 all rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

//------------------------
// 分组模型
//-------------------------

namespace app\common\model;

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