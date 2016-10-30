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
// 邮件发送类
//-------------------------

class Mail
{
    //配置信息
    private static $config = [
        'smtp_pc'      => '',          //发信计算机名 可随意填写
        'smtp_host'    => '',          //发信SMTP服务器地址
        'smtp_port'    => 25,          //发信SMTP服务器端口号
        'smtp_addr'    => '',          //发信帐号名
        'smtp_pass'    => '',          //发信帐号密码
        'smtp_name'    => '',          //发信用户名
        'content_type' => 'text/html', //文本类型  text/html 或 text/plain
        'charset'      => 'utf-8',     //字符编码
        'line_break'   => "\r\n",      //换行符
    ];
    private static $instance;

    public static function instance($config = [])
    {
        self::config($config);
        $driver = self::$config['driver'];
        if (!isset(self::$instance[$driver])) {
            if (!in_array($driver, ["fsock", "phpmailer"])) {
                exception("不存在邮件驱动" . $driver);
            }
            $class = "\\mail\\" . ucfirst($driver);
            self::$instance[$driver] = new $class(self::$config);
        }
        return self::$instance[$driver];
    }

    /**
     * 设置配置信息
     * @param array $config
     */
    public static function config($config = [])
    {
        self::$config = array_merge(config("mail"), $config);
    }
}
