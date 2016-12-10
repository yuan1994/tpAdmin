<?php
/**
 * tp-mailer [A powerful and beautiful php mailer for All of ThinkPHP and Other PHP Framework based SwiftMailer]
 *
 * @author    yuan1994 <tianpian0805@gmail.com>
 * @link      https://github.com/yuan1994/tp-mailer
 * @copyright 2016 yuan1994 all rights reserved.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 */

namespace mailer\lib;

/**
 * Class MailerConfig
 * @package mailer\lib
 */
class MailerConfig
{
    /********* 邮件驱动 *********/
    const DRIVER_SMTP = 'smtp';
    const DRIVER_SENDMAIL = 'sendmail';
    const DRIVER_MAIL = 'mail';

    /********* 文本类型 *********/
    const CONTENT_HTML = 'text/html';
    const CONTENT_PLAIN = 'text/plain';

    /********* 优先级 **********/
    const PRIORITY_HIGHEST = 1;
    const PRIORITY_HIGH = 2;
    const PRIORITY_NORMAL = 3;
    const PRIORITY_LOW = 4;
    const PRIORITY_LOWEST = 5;

    /******* 常见图片Mime *******/
    const MIME_JPEG = 'image/jpeg';
    const MIME_PNG = 'image/png';
    const MIME_GIF = 'image/gif';
    const MIME_BMP = 'application/x-bmp';
}
