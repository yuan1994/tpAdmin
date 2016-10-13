<?php
// +----------------------------------------------------------------------
// | tpadmin [a web admin based ThinkPHP5]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 tianpian
// +----------------------------------------------------------------------
// | Author: tianpian <tianpian0805@gmail.com>
// +----------------------------------------------------------------------

//------------------------
// 邮件信息配置
//-------------------------

return [
    'driver'    => 'fsock', //驱动 fsock|phpmailer
    'smtp_pc'   => '',    //发信计算机名 可随意填写
    'smtp_host' => 'smtp.mxhichina.com',    //发信SMTP服务器地址
    'smtp_port' => 25,    //发信SMTP服务器端口号
    'smtp_addr' => '',    //发信帐号名
    'smtp_pass' => '',    //发信帐号密码
    'smtp_name' => '',    //发信用户名
    'content_type' => 'text/html',      //文本类型  text/html 或 text/plain
    'charset' => 'utf-8',                   //字符编码
    'line_break' => "\r\n",
];