<?php
// +----------------------------------------------------------------------
// | tpadmin [a web admin based ThinkPHP5]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 tianpian
// +----------------------------------------------------------------------
// | Author: tianpian <tianpian0805@gmail.com>
// +----------------------------------------------------------------------

//------------------------
// 自动生成代码
//-------------------------

namespace app\admin\controller;
use think\Controller;

class Generate extends Controller{
    /**
     * 首页
     * @return mixed
     */
    public function index(){
        return $this->fetch();
    }

    /**
     * 生成方法
     */
    public function generate(){
        $model = new \app\admin\model\Generate();
        //检查目录是否可写
        if (!$model->checkWritable()){
            ajax_return_adv_error("目录没有权限不可写，请执行一下命令修改权限：<br>chmod -R 755 ".APP_PATH.request()->module());
        }

        $model->build();
        //Url::build方法有bug，url("one.two.Three")会生成 one.two._three的链接，正确的应该是one.two.three
        $controllers = explode(".",input("post.controller_name"));
        $real_controller = array_pop($controllers);
        array_push($controllers,lcfirst($real_controller));

        ajax_return_adv('生成成功','',false,'','',['action'=>url(implode(".",$controllers)."/index")]);
//        sleep(3);
//        ajax_return_adv_error('错误信息');
    }

    /**
     * 文档
     * @return mixed
     */
    public function doc(){
        return $this->fetch();
    }

}