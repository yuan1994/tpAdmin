<?php
/**
 * tpAdmin [a web admin based ThinkPHP5]
 *
 * @author yuan1994 <tianpian0805@gmail.com>
 * @link http://tpadmin.yuan1994.com/
 * @copyright 2016 yuan1994 all rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace app\common\model;

use think\Model;

class File extends Model
{
    /**
     * 写入文件上传记录
     * @param $data
     * @param $cate
     * @return mixed
     */
    public function insertRecord($data, $cate)
    {
        return $this->insert(
            [
                "cate"     => $cate,
                "name"     => $data['key'],
                "original" => isset($data['name']) ? $data['name'] : '',
                "type"     => isset($data['type']) ? $data['type'] : '',
                "size"     => isset($data['size']) ? $data['size'] : 0,
                "mtime"    => time(),
            ]
        );
    }
}
