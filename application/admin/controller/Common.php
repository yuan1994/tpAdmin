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

use think\Loader;
use think\Session;
use think\Db;
use think\Config;
use think\Exception;
use think\View;
use think\Request;

class Common
{
    use \traits\controller\Jump;

    // 视图类实例
    protected $view;
    // Request实例
    protected $request;

    // 黑名单方法，禁止访问某

    public function __construct()
    {
        if (null === $this->view) {
            $this->view = View::instance(Config::get('template'), Config::get('view_replace_str'));
        }
        if (null === $this->request) {
            $this->request = Request::instance();
        }

        //用户ID
        defined('UID') or define('UID', Session::get(Config::get('rbac.user_auth_key')));
        //是否是管理员
        defined('ADMIN') or define('ADMIN', true === Session::get(Config::get('rbac.admin_auth_key')));
    }

    /**
     * 检查用户是否登录
     */
    protected function checkUser()
    {
        if (null === UID) {
            if ($this->request->isAjax()) {
                ajax_return_adv_error("登录超时，请先登陆", "", "", "current", url("Common/loginFrame"))->send();
            } else {
                $this->error("登录超时，请先登录", Config::get('rbac.user_auth_gateway'));
            }
        }

        return true;
    }

    /**
     * 用户登录页面
     * @return mixed
     */
    public function login()
    {
        if (Session::has(Config::get('rbac.user_auth_key'))) {
            $this->redirect('Index/index');
        } else {
            return $this->view->fetch();
        }
    }

    /**
     * 小窗口登录页面
     * @return mixed
     */
    public function loginFrame()
    {
        return $this->view->fetch();
    }

    /**
     * 首页
     */
    public function index()
    {
        //如果通过认证跳转到首页
        $this->redirect("Index/index");
    }

    /**
     * 用户登出
     */
    public function logout()
    {
        if (UID) {
            Session::clear();
            $this->success('登出成功！', Config::get('rbac.user_auth_gateway'));
        } else {
            $this->error('已经登出！', Config::get('rbac.user_auth_gateway'));
        }
    }

    /**
     * 登录检测
     * @return \think\response\Json
     */
    public function checkLogin()
    {
        if ($this->request->isAjax() && $this->request->isPost()) {
            $data = input("post.");
            $result = $this->validate($data, [
                'account|帐号'  => 'require',
                'password|密码' => 'require',
                'captcha|验证码' => 'require|captcha',
            ]);
            if ($result !== true) {
                return ajax_return_adv_error($result);
            }

            $map['account'] = $data['account'];
            $map['status'] = 1;
            $auth_info = \Rbac::authenticate($map);

            //使用用户名、密码和状态的方式进行认证
            if (null === $auth_info) {
                return ajax_return_adv_error('帐号不存在或已禁用！');
            } else {
                if ($auth_info['password'] != password_hash_my($data['password'])) {
                    return ajax_return_adv_error('密码错误！');
                }

                //生成session信息
                Session::set(Config::get('rbac.user_auth_key'), $auth_info['id']);
                Session::set('user_name', $auth_info['account']);
                Session::set('real_name', $auth_info['realname']);
                Session::set('email', $auth_info['email']);
                Session::set('last_login_ip', $auth_info['last_login_ip']);
                Session::set('last_login_time', $auth_info['last_login_time']);
                Session::set('login_count', $auth_info['login_count']);

                //超级管理员标记
                if ($auth_info['id'] == 1) {
                    Session::set(Config::get('rbac.admin_auth_key'), true);
                }

                //保存登录信息
                $update['last_login_time'] = time();
                $update['login_count'] = ['exp', 'login_count+1'];
                $update['last_login_ip'] = $this->request->ip();
                Db::name("AdminUser")->where('id', $auth_info['id'])->update($update);

                //记录登录日志
                $log['uid'] = $auth_info['id'];
                $log['login_ip'] = $this->request->ip();
                $log['login_location'] = implode(" ", \Ip::find($log['login_ip']));
                $log['login_browser'] = \Agent::getBroswer();
                $log['login_os'] = \Agent::getOs();
                Db::name("LoginLog")->insert($log);

                // 缓存访问权限
                \Rbac::saveAccessList();

                return ajax_return_adv('登录成功！');
            }
        } else {
            throw new Exception("非法请求");
        }
    }

    // 修改密码
    public function password()
    {
        $this->checkUser();
        if ($this->request->isPost()) {
            $data = input('post.');
            //数据校验
            $result = $this->validate($data, [
                'oldpassword|旧密码' => 'require',
                'password|新密码'    => 'require',
                'repassword|重复密码' => 'require',
            ]);
            if ($result !== true) {
                return ajax_return_adv_error($result);
            }

            //查询旧密码进行比对
            $db = Db::name("AdminUser");
            $info = $db->where("id", UID)->field("password")->find();
            if ($info['password'] != password_hash_my($data['oldpassword'])) {
                return ajax_return_adv_error("旧密码错误");
            }

            //写入新密码
            if (false === Loader::model('AdminUser')->updatePassword(UID, $data['password'])) {
                return ajax_return_adv_error("密码修改失败");
            }

            return ajax_return_adv("密码修改成功");
        } else {
            return $this->view->fetch();
        }
    }

    /**
     * 查看用户信息|修改资料
     */
    public function profile()
    {
        $this->checkUser();
        if ($this->request->isPost()) { //修改资料
            $data = $this->request->only(['realname', 'email', 'mobile', 'remark'], 'post');
            if (Db::name("AdminUser")->where("id", UID)->update($data) === false) {
                return ajax_return_adv_error("信息修改失败");
            }

            return ajax_return_adv("信息修改成功");
        } else { //查看用户信息
            $vo = Db::name("AdminUser")->field('realname,email,mobile,remark')->where("id", UID)->find();
            $this->view->assign('vo', $vo);

            return $this->view->fetch();
        }
    }
}
