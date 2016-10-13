<?php
// +----------------------------------------------------------------------
// | tpadmin [a web admin based ThinkPHP5]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 tianpian
// +----------------------------------------------------------------------
// | Author: tianpian <tianpian0805@gmail.com>
// +----------------------------------------------------------------------

//------------------------
// 登录日志模型
//-------------------------

namespace app\admin\model;
use think\Model;

class LoginLog extends Model{
    public function user()
    {
        return $this->hasOne('AdminUser',"id","uid")->setAlias(["id"=>"uuid"]);
    }
}