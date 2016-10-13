<?php
// +----------------------------------------------------------------------
// | tpadmin [a web admin based ThinkPHP5]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 tianpian
// +----------------------------------------------------------------------
// | Author: tianpian <tianpian0805@gmail.com>
// +----------------------------------------------------------------------

//------------------------
// 公开不授权控制器
//-------------------------

namespace app\admin\controller;
use think\Controller;
use think\Session;

class Common extends Controller{
    // 检查用户是否登录
    protected function checkUser() {
        if(!Session::has(config('rbac.user_auth_key'))) {
            if (request()->isAjax()){
                ajax_return_adv_error("登录超时，请先登陆","","","current",url("Common/loginFrame"));
            } else {
                $this->error("登录超时，请先登录",config('rbac.user_auth_gateway'));
            }
        }
    }

    // 用户登录页面
    public function login() {
        if(Session::has(config('rbac.user_auth_key'))) {
            $this->redirect('Index/index');
        }else{
            return $this->fetch();
        }
    }

    //小窗口登录页面
    public function loginFrame() {
        return $this->fetch();
    }

    public function index()
    {
        //如果通过认证跳转到首页
        $this->redirect("Index/index");
    }

    // 用户登出
    public function logout()
    {
        if(Session::has(config('rbac.user_auth_key'))) {
            Session::clear();
            $this->success('登出成功！',config('rbac.user_auth_gateway'));
        }else {
            $this->error('已经登出！',config('rbac.user_auth_gateway'));
        }
    }

    // 登录检测
    public function checkLogin() {
        if (request()->isAjax() && request()->isPost()){
            $data = input("post.");
            $result = $this->validate($data,[
                'account|帐号' => 'require',
                'password|密码' => 'require',
//                'captcha|验证码' => 'require|captcha',
            ]);
            if ($result !== true){
                ajax_return_adv_error($result);
            }

            $map['account']	= $data['account'];
            $map['status'] = 1;
            $auth_info = \Rbac::authenticate($map);

            //使用用户名、密码和状态的方式进行认证
            if(NULL === $auth_info) {
                ajax_return_adv_error('帐号不存在或已禁用！');
            }else {
                if($auth_info['password'] != password_hash_my(trim($data['password']))) {
                    ajax_return_adv_error('密码错误！');
                }

                //生成session信息
                Session::set(config('rbac.user_auth_key'),$auth_info['id']);
                Session::set('user_name',$auth_info['account']);
                Session::set('real_name',$auth_info['realname']);
                Session::set('email',$auth_info['email']);
                Session::set('last_login_ip',$auth_info['last_login_ip']);
                Session::set('last_login_time',$auth_info['last_login_time']);
                Session::set('login_count',$auth_info['login_count']);

                if ($auth_info['id'] == 1){
                    Session::set(config('rbac.admin_auth_key'),true);
                }

                //保存登录信息
                $update['last_login_time']	=	time();
                $update['login_count']	=	array('exp','login_count+1');
                $update['last_login_ip']	=	request()->ip();
                db("AdminUser")->where('id',$auth_info['id'])->update($update);

                //记录登录日志
                $log['uid'] = $auth_info['id'];
                $log['login_ip'] = request()->ip();
                $log['login_location'] = implode(" ",\Ip::find($log['login_ip']));
                $log['login_browser'] = \Agent::getBroswer();
                $log['login_os'] = \Agent::getOs();
                db("LoginLog")->insert($log);

                // 缓存访问权限
                \Rbac::saveAccessList();
                ajax_return_adv('登录成功！');
            }
        } else {
            exception("非法请求");
        }
    }

    // 修改密码
    public function password()
    {
        $this->checkUser();
        if (request()->isPost()){
            $data = input('post.');
            //数据校验
            $result = $this->validate($data,[
                'oldpassword|旧密码' => 'require',
                'password|新密码' => 'require',
                'repassword|重复密码' => 'require',
            ]);
            if ($result !== true){
                ajax_return_adv_error($result);
            }

            //查询旧密码进行比对
            $db = db("AdminUser");
            $info = $db->where("id",Session::get(config('rbac.user_auth_key')))->field("password")->find();
            if ($info['password'] != password_hash_my(trim($data['oldpassword']))){
                ajax_return_adv_error("旧密码错误");
            }

            //写入新密码
            $password_hash = password_hash_my(trim($data['password']));
            if ($db->where("id",Session::get(config('rbac.user_auth_key')))->update(['password'=>$password_hash]) === false){
                ajax_return_adv_error("密码修改失败");
            }
            ajax_return_adv("密码修改成功");
        } else {
            return $this->fetch();
        }
    }

    /**
     * 查看用户信息|修改资料
     */
    public function profile() {
        $this->checkUser();
        if (request()->isPost()){ //修改资料
            $data = request()->only(['realname','email','mobile','remark'],'post');
            if (db("AdminUser")->where("id",Session::get(config('rbac.user_auth_key')))->update($data) === false){
                ajax_return_adv_error("信息修改失败");
            }
            ajax_return_adv("信息修改成功");
        } else { //查看用户信息
            $vo	=	db("AdminUser")->field('realname,email,mobile,remark')->where("id",Session::get(config('rbac.user_auth_key')))->find();
            $this->assign('vo',$vo);
            return $this->fetch();
        }
    }
}