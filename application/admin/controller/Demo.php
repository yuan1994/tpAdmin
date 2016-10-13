<?php
// +----------------------------------------------------------------------
// | tpadmin [a web admin based ThinkPHP5]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 tianpian
// +----------------------------------------------------------------------
// | Author: tianpian <tianpian0805@gmail.com>
// +----------------------------------------------------------------------

//------------------------
// 演示示例
//-------------------------

namespace app\admin\controller;
use think\Controller;

class Demo extends Controller{
    /**
     * Excel一键导出
     */
    public function excel(){
        if (request()->isPost()){
            $header = ['用户ID','登录IP','登录地点','登录浏览器','登录操作系统','登录时间'];
            $data = db("LoginLog")->field("id",true)->order("id asc")->limit(20)->select();
            if ($error = export_excel($header,$data,"示例Excel导出")){
                exception($error);
            }
        } else {
            return $this->fetch();
        }
    }

    /**
     * 下载文件
     * @return mixed
     */
    public function download(){
        if (input("param.file")){
            return download("../build.php");
        } else {
            return $this->fetch();
        }
    }

    /**
     * 下载远程图片
     * @return mixed
     */
    public function download_image(){
        if (request()->isPost()){
            $url = input("post.url");
            if (substr($url,0,4) != "http"){
                ajax_return_adv_error("url非法");
            }
            $name = "./tmp/".get_random();
            $filename = \File::downloadImage($url,$name);
            if (!$filename){
                ajax_return_adv_error($filename);
            } else {
                $url = request()->domain().substr($filename,1);
                ajax_return_adv("下载成功","图片下载成功，<a href='{$url}' target='_blank' class='c-blue'>点击查看</a><br>{$url}");
            }
        } else {
            return $this->fetch();
        }
    }

    /**
     * 发送邮件
     * @return mixed
     */
    public function mail(){
        if (request()->isPost()){
            $receive = input("post.receiver");
            $result = $this->validate(
                ['receiver' => $receive],
                ['receiver|收件人' => 'require|email']
            );
            if ($result !== true){
                ajax_return_adv_error($result);
            }
            $html = "<p>这是一封来自tpadmin的测试邮件，请勿回复</p><p><br></p><p>该邮件由访问发送，本站不承担任何责任，如有骚扰请屏蔽此邮件地址</p>";
            $result = \Mail::instance()->mail($receive,$html,"测试邮件");
            if ($result !== true){
                ajax_return_adv_error($result);
            } else {
                ajax_return_adv("邮件发送成功，请注意查收");
            }
        } else {
            return $this->fetch();
        }
    }

    /**
     * 七牛上传
     * @return mixed
     */
    public function qiniu(){
        if (request()->isPost()){
            return '<script>parent.layer.alert("仅做演示")</script>';
            /*$result = \Qiniu::instance()->upload();
            p($result);*/
        } else {
            return $this->fetch();
        }
    }

    /**
     * ID加密
     * @return mixed
     */
    public function hashids(){
        if (request()->isPost()){
            $id = input("post.id");
            $hashids = hashids(8,"tpadmin");
            $encode_id = $hashids->encode($id); //加密
            $decode_id = $hashids->decode($encode_id); //解密
            ajax_return_adv("操作成功",'',false,'','',['encode'=>$encode_id,'decode'=>$decode_id]);
        } else {
            return $this->fetch();
        }
    }

    /**
     * 丰富弹层
     */
    public function layer(){
        return $this->fetch();
    }

    /**
     * 表格溢出
     */
    public function table_fixed(){
        return $this->fetch();
    }
}