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
// 自动生成代码
//-------------------------

namespace app\admin\controller;

use think\Controller;
use think\Loader;
use think\Url;

class Generate extends Controller
{
    /**
     * 首页
     * @return mixed
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * 生成方法
     */
    public function generate()
    {
        $model = Loader::model('Generate');
        //检查目录是否可写
        if (!$model::checkWritable()) {
            return ajax_return_adv_error("目录没有权限不可写，请执行一下命令修改权限：<br>sudo chmod -R 777 " . realpath(APP_PATH . $this->request->module()));
        }

        $model->build();
        //Url::build方法有bug，url("one.two.Three")会生成 one.two._three的链接，正确的应该是one.two.three
        $controllers = explode(".", $this->request->post('controller_name'));
        $real_controller = array_pop($controllers);
        array_push($controllers, lcfirst($real_controller));

        return ajax_return_adv('生成成功', '', false, '', '', ['action' => Url::build(implode(".", $controllers) . "/index")]);
//        sleep(3);
//        return ajax_return_adv_error('错误信息');
    }
}