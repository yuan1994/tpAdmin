<?php

/**
 * 示例配置文件
 *
 * 可以配置在 mail.php 或 config.php 文件中, 但要保证能通过 mail.driver, mail.host 访问到配置信息
 */
return [
    'driver'          => 'smtp', // 邮件驱动, 支持 smtp|sendmail|mail 三种驱动
    'host'            => 'smtp.qq.com', // SMTP服务器地址
    'port'            => 465, // SMTP服务器端口号,一般为25
    'addr'            => '', // 发件邮箱地址
    'pass'            => '', // 发件邮箱密码
    'name'            => '', // 发件邮箱名称
    'content_type'    => 'text/html', // 默认文本内容 text/html|text/plain
    'charset'         => 'utf-8', // 默认字符集
    'security'        => 'ssl', // 加密方式 null|ssl|tls, QQ邮箱必须使用ssl
    'sendmail'        => '/usr/sbin/sendmail -bs', // 不适用 sendmail 驱动不需要配置
    'debug'           => true, // 开启debug模式会直接抛出异常, 记录邮件发送日志
    'left_delimiter'  => '{', // 模板变量替换左定界符, 可选, 默认为 {
    'right_delimiter' => '}', // 模板变量替换右定界符, 可选, 默认为 }
    'log_driver'      => '', // 日志驱动类, 可选, 如果启用必须实现静态 public static function write($content, $level = 'debug') 方法
    'log_path'        => '', // 日志路径, 可选, 不配置日志驱动时启用默认日志驱动, 默认路径是 /path/to/tp-mailer/log, 要保证该目录有可写权限, 最好配置自己的日志路径
    'embed'           => 'embed:', // 邮件中嵌入图片元数据标记
];
