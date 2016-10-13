<?php
// +----------------------------------------------------------------------
// | tpadmin [a web admin based ThinkPHP5]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 tianpian
// +----------------------------------------------------------------------
// | Author: tianpian <tianpian0805@gmail.com>
// +----------------------------------------------------------------------

namespace mail;
require 'phpmailer/class.phpmailer.php';

class Phpmailer extends \PHPMailer
{
    private $errormsg = ""; //错误信息

    public function __construct($config)
    {
        parent::__construct(true);
        $this->IsSMTP();
        $this->CharSet = $config['charset']; //设置邮件的字符编码，这很重要，不然中文乱码
        $this->SMTPAuth = true;                  //开启认证
        $this->Port = $config['smtp_port'];
        $this->Host = $config['smtp_host'];
        $this->Username = $config['smtp_addr'];
        $this->Password = $config['smtp_pass'];
        //$this->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could  not execute: /var/qmail/bin/sendmail ”的错误提示
//             $this->AddReplyTo("phpddt1990@163.com","mckee");//回复地址
        $this->From = $config['smtp_addr'];
        $this->FromName = $config['smtp_name'];
        if ($config['content_type'] == 'text/html') $this->IsHTML(true);
    }

    public function mail($receive, $content, $subject = 'No Subject')
    {
        try{
            $this->AddAddress($receive);
            $this->Subject = $subject;
            $this->Body = $content;
            $this->AltBody = "To view the message, please use an HTML compatible email viewer!"; //当邮件不支持html时备用显示，可以省略
//             $this->WordWrap   = 80; // 设置每行字符串的长度
            //$this->AddAttachment("f:/test.png");  //可以添加附件
            $this->Send();
            return true;
        } catch (\phpmailerException $e){
            $this->errormsg = $e->getMessage();
            return false;
        }
    }

    /**
     * 获取错误信息
     */
    public function getError(){
        return $this->errormsg;
    }
}