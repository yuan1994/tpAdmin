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

use think\Db;
use think\Model;

class WebLog extends Model
{
    protected $name = 'web_log_001';

    public function user()
    {
        return $this->hasOne('AdminUser', "id", "uid")->setAlias(["id" => "uuid"]);
    }

    public function map()
    {
        return $this->hasOne('NodeMap', "map", "map")->setAlias(["id" => "map_id"]);
    }
}