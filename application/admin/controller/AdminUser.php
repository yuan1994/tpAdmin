<?php
// +----------------------------------------------------------------------
// | tpadmin [a web admin based ThinkPHP5]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 tianpian
// +----------------------------------------------------------------------
// | Author: tianpian <tianpian0805@gmail.com>
// +----------------------------------------------------------------------

//------------------------
// 用户控制器
//-------------------------

namespace app\admin\controller;

use app\admin\Controller;
use think\Exception;
use think\Loader;

class AdminUser extends Controller
{
    use \app\admin\traits\controller\Controller;

    protected $blacklist = ['delete', 'clear', 'deleteForever'];

    protected function filter(&$map)
    {
        //不查询管理员
        $map['id'] = ["gt", 1];

        if (input("param.realname")) $map['realname'] = ["like", "%" . input("param.realname") . "%"];
        if (input("param.account")) $map['account'] = ["like", "%" . input("param.account") . "%"];
        if (input("param.email")) $map['email'] = ["like", "%" . input("param.email") . "%"];
        if (input("param.mobile")) $map['mobile'] = ["like", "%" . input("param.mobile") . "%"];
    }

    /**
     * 修改密码
     */
    public function password()
    {
        $id = input("param.id/d");
        if ($this->request->isPost()) {
            //禁止修改管理员的密码，管理员id为1
            if ($id < 2) {
                return ajax_return_adv_error("缺少必要参数");
            }

            $password = input("post.password");
            if (!$password) {
                return ajax_return_adv_error("密码不能为空");
            }
            if (false === Loader::model('AdminUser')->uploadPassword($id, $password)) {
                return ajax_return_adv_error("密码修改失败");
            }
            return ajax_return_adv("密码已修改为{$password}");
        } else {
            //禁止修改管理员的密码，管理员id为1
            if ($id < 2) {
                throw new Exception("缺少必要参数");
            }

            return $this->view->fetch();
        }
    }

    /**
     * 禁用限制
     */
    protected function beforeForbid()
    {
        //禁止禁用Admin模块,权限设置节点
        $this->filterId(1, '该用户不能被禁用', '=');
    }
}