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

namespace app\admin;

use think\Exception;
use think\Url;
use think\View;
use think\Request;
use think\Session;
use think\Db;
use think\Response;
use think\Config;
use think\Loader;
use think\exception\HttpException;

class Controller
{
    // 视图类实例
    protected $view;
    // Request实例
    protected $request;
    // 黑名单方法，禁止访问某些方法
    protected static $blacklist = [];
    // 是否删除标志，0-正常|1-删除|false-不包含该字段
    protected static $isdelete = 0;

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

        //检查认证识别号
        if (null === UID) {
            $this->notLogin();
        } else {
            $this->auth();
        }

        //黑名单方法
        if (self::$blacklist && in_array($this->request->action(), self::$blacklist)) {
            throw new HttpException(404, 'method not exists:' . (new \ReflectionClass($this))->getName() . '->' . $this->request->action());
        }

        //前置方法
        $before_action = "before" . ucfirst($this->request->action());
        if (method_exists($this, $before_action)) {
            $this->$before_action();
        }
    }

    /**
     * 自动搜索查询字段,给模型字段过滤
     */
    protected function search($model)
    {
        $map = [];
        $table_info = $model->getTableInfo();
        foreach ($this->request->param() as $key => $val) {
            if ($val !== "" && in_array($key, $table_info['type'])) {
                $map[$key] = $val;
            }
        }

        return $map;
    }

    /**
     * 获取模型
     * @param string $controller
     * @return mixed
     */
    protected function getModel($controller = '')
    {
        if (!$controller) {
            $controller = $this->request->controller();
        }
        if (class_exists("\\app\\admin\\model\\" . $this->parseClass($controller))) {
            return Loader::model($this->parseClass($controller));
        } else {
            return Db::name($this->parseTable($controller));
        }
    }

    /**
     * 获取实际的控制器名称(应用于多层控制器的场景)
     * @param $controller
     * @return mixed
     */
    protected function getRealController($controller = '')
    {
        if (!$controller) {
            $controller = $this->request->controller();
        }
        $controllers = explode(".", $controller);
        $controller = array_pop($controllers);

        return $controller;
    }

    /**
     * 默认更新字段方法
     * @param string $field     更新的字段
     * @param string|int $value 更新的值
     * @param string $msg       操作成功提示信息
     * @param string $pk        主键，默认为主键
     * @param string $input     接收参数，默认为主键
     */
    protected function update($field, $value, $msg = "操作成功", $pk = "", $input = "")
    {
        $model = $this->getModel();
        if (!$pk) {
            $pk = $model->getPk();
        }
        if (!$input) {
            $input = $model->getPk();
        }
        $ids = $this->request->param($input);
        $where[$pk] = ["in", $ids];
        if ($model->where($where)->update([$field => $value]) === false) {
            return ajax_return_adv_error($model->getError());
        }

        return ajax_return_adv($msg, '');
    }

    /**
     * 格式化表名，将 /. 转为 _ ，支持多级控制器
     * @param string $name
     * @return mixed
     */
    protected function parseTable($name = '')
    {
        if (!$name) {
            $name = $this->request->controller();
        }

        return str_replace(['/', '.'], '_', $name);
    }

    /**
     * 格式化类名，将 /. 转为 \\
     * @param string $name
     * @return mixed
     */
    protected function parseClass($name = '')
    {
        if (!$name) {
            $name = $this->request->controller();
        }

        return str_replace(['/', '.'], '\\', $name);
    }

    /**
     * 未登录处理
     */
    protected function notLogin()
    {
        //跳转到认证网关
        if ($this->request->isAjax()) {
            ajax_return_adv_error("登录超时，请先登陆", "", "", "current", Url::build("Pub/loginFrame"))->send();
        } else {
            if (strtolower($this->request->controller()) == 'index' && strtolower($this->request->action()) == 'index') {
                Response::create(Url::build('Pub/login'), 'redirect')->send();
            } else {
                //判断是弹出登录框还是直接跳转到登录页
                $ret = '<script>' .
                    'if(window.parent.frames.length == 0) ' .
                    'window.location = "' . Url::build('Pub/login') . '?callback=' . urlencode($this->request->url(true)) . '";' .
                    ' else ' .
                    'parent.login("' . Url::build('Pub/loginFrame') . '");' .
                    '</script>';
                Response::create($ret)->send();
            }
        }
    }

    /**
     * 权限校验
     */
    protected function auth()
    {
        // 用户权限检查
        if (
            Config::get('rbac.user_auth_on') &&
            !in_array($this->request->module(), explode(',', Config::get('rbac.not_auth_module')))
        ) {
            if (!\Rbac::AccessCheck()) {
                throw new Exception("没有权限");
            }
        }
    }
}
