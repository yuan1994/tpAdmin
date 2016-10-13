<?php
// +----------------------------------------------------------------------
// | tpadmin [a web admin based ThinkPHP5]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 tianpian
// +----------------------------------------------------------------------
// | Author: tianpian <tianpian0805@gmail.com>
// +----------------------------------------------------------------------

namespace app\admin\model;
use think\Model;

class File extends Model{
    public function insertRecord($data,$cate,$domain){
        return $this->insert(
            [
                "cate" => $cate,
                "name" => $data['key'],
                "original" => isset($data['name']) ? $data['name'] : '',
                "domain" => $domain,
                "type" => isset($data['type']) ? $data['type'] : '',
                "size" => isset($data['size']) ? $data['size'] : 0,
                "mtime" => time(),
            ]
        );
    }
}
