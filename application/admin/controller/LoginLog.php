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
// 登录日志控制器
//-------------------------

namespace app\admin\controller;

use app\admin\Controller;

class LoginLog extends Controller
{
    use \app\admin\traits\controller\Controller;

    protected $isdelete = false; //禁用该字段

    protected $blacklist = ['add', 'edit', 'delete', 'deleteForever', 'forbid', 'resume', 'recycle', 'recycleBin'];

    protected function filter(&$map)
    {
        if ($this->request->param('login_location')) {
            $map['login_location'] = ["like", "%" . $this->request->param('login_location') . "%"];
        }

        //关联筛选
        if ($this->request->param('account')) $map['admin_user.account'] = $this->request->param('account');
        if ($this->request->param('name')) $map['admin_user.realname'] = ["like", "%" . $this->request->param('name') . "%"];

        //设置属性
        $map['_table'] = "login_log";
        $map['_relation'] = "user";
        $map['_order_by'] = false;
    }
}