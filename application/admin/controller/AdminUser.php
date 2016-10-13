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

class AdminUser extends Controller{
    protected function _filter(&$map){
        //不查询管理员
        $map['id'] = array("gt",1);

        if (input("param.realname")) $map['realname'] = array("like","%".input("param.realname")."%");
        if (input("param.account")) $map['account'] = array("like","%".input("param.account")."%");
        if (input("param.email")) $map['email'] = array("like","%".input("param.email")."%");
        if (input("param.mobile")) $map['mobile'] = array("like","%".input("param.mobile")."%");
    }

    /**
     * 修改密码
     */
    public function password(){
        $id = input("param.id/d");
        if (request()->isPost()){
            //禁止修改管理员的密码，管理员id为1
            if ($id < 2){
                ajax_return_adv_error("缺少必要参数");
            }

            $password = trim(input("post.password"));
            if (!$password){
                ajax_return_adv_error("密码不能为空");
            }
            $password_hash = password_hash_my($password);
            if (db("AdminUser")->where("id",$id)->update(['password'=>$password_hash]) === false){
                ajax_return_adv_error("密码修改失败");
            }
            ajax_return_adv("密码已修改为{$password}");
        } else {
            //禁止修改管理员的密码，管理员id为1
            if ($id < 2){
                exception("缺少必要参数");
            }
            return $this->fetch();
        }
    }

    /**
     * 禁用限制
     */
    protected function _before_forbid(){
        //禁止禁用Admin模块,权限设置节点
        $this->_filter_id([1],'该用户不能被禁用');
    }

    /**
     * 不允许删除
     */
    public function delete()
    {
        ajax_return_adv_error('非法请求');
    }

    /**
     * 不允许清空
     */
    public function clear()
    {
        ajax_return_adv_error('非法请求');
    }

    /**
     * 不允许永久删除
     */
    public function foreverDelete()
    {
        ajax_return_adv_error('非法请求');
    }
}