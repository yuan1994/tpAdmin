<?php
/**
 * tpAdmin [a web admin based ThinkPHP5]
 *
 * @author yuan1994 <tianpian0805@gmail.com>
 * @link http://tpadmin.yuan1994.com/
 * @copyright 2016 yuan1994 all rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

//------------------------
// 演示示例
//-------------------------

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Exception;
use think\Request;
use mailer\tp5\Mailer;

class Demo extends Controller
{
    /**
     * Excel一键导出
     */
    public function excel()
    {
        if ($this->request->isPost()) {
            $header = ['用户ID', '登录IP', '登录地点', '登录浏览器', '登录操作系统', '登录时间'];
            $data = Db::name("LoginLog")->field("id", true)->order("id desc")->limit(20)->select();
            if ($error = \Excel::export($header, $data, "示例Excel导出", '2007')) {
                throw new Exception($error);
            }
        } else {
            return $this->view->fetch();
        }
    }

    /**
     * 下载文件
     * @return mixed
     */
    public function download()
    {
        if ($this->request->param('file')) {
            return \File::download("../README.md");
        } else {
            return $this->view->fetch();
        }
    }

    /**
     * 下载远程图片
     * @return mixed
     */
    public function downloadImage()
    {
        if (Request::instance()->isPost()) {
            $url = $this->request->post("url");
            if (substr($url, 0, 4) != "http") {
                return ajax_return_adv_error("url非法");
            }
            $name = "./tmp/" . get_random();
            $filename = \File::downloadImage($url, $name);
            if (!$filename) {
                return ajax_return_adv_error($filename);
            } else {
                $url = $this->request->root() . substr($filename, 1);

                return ajax_return_adv("下载成功", '', "图片下载成功，<a href='{$url}' target='_blank' class='c-blue'>点击查看</a><br>{$url}");
            }
        } else {
            return $this->view->fetch();
        }
    }

    /**
     * 发送邮件
     * @return mixed
     */
    public function mail()
    {
        if ($this->request->isPost()) {
            $receive = $this->request->post("receiver");
            $result = $this->validate(
                ['receiver' => $receive],
                ['receiver|收件人' => 'require|email']
            );
            if ($result !== true) {
                return ajax_return_adv_error($result);
            }
            $mailer = Mailer::instance();
            $result = $mailer->to($receive)
                ->subject('来自tpadmin的测试邮件')
                ->view('mail_template')
                ->send();

            if ($result == 0) {
                return ajax_return_adv_error($mailer->getError());
            } else {
                return ajax_return_adv("邮件发送成功，请注意查收", '');
            }
        } else {
            return $this->view->fetch();
        }
    }

    /**
     * 百度编辑器
     * @return mixed
     */
    public function ueditor()
    {
        return $this->view->fetch();
    }

    /**
     * 七牛上传
     * @return mixed
     */
    public function qiniu()
    {
        if ($this->request->isPost()) {
            return '<script>parent.layer.alert("仅做演示")</script>';
            /*$result = \Qiniu::instance()->upload();
            p($result);*/
        } else {
            return $this->view->fetch();
        }
    }

    /**
     * ID加密
     * @return mixed
     */
    public function hashids()
    {
        if ($this->request->isPost()) {
            $id = $this->request->post("id");
            $hashids = \Hashids\Hashids::instance(8, "tpadmin");
            $encode_id = $hashids->encode($id); //加密
            $decode_id = $hashids->decode($encode_id); //解密
            return ajax_return_adv("操作成功", '', false, '', '', ['encode' => $encode_id, 'decode' => $decode_id]);
        } else {
            return $this->view->fetch();
        }
    }

    /**
     * 丰富弹层
     */
    public function layer()
    {
        return $this->view->fetch();
    }

    /**
     * 表格溢出
     */
    public function tableFixed()
    {
        return $this->view->fetch();
    }

    /**
     * 图片上传回调
     */
    public function imageUpload()
    {
        return $this->view->fetch();
    }

    /**
     * 二维码生成
     */
    public function qrcode()
    {
        return $this->view->fetch();
    }
}