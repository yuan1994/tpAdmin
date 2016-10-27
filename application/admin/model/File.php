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

namespace app\admin\model;

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
