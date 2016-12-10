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
 * Class Log
 * @package mailer\lib
 */
class Log
{
    const DEBUG = 'DEBUG';
    const INFO = 'INFO';
    const ERROR = 'ERROR';

    /**
     * @var object 日志驱动
     */
    private static $driver;


    public static function init()
    {
        if (null === self::$driver) {
            if (Config::get('log_driver')) {
                $driver = Config::get('log_driver');
                self::$driver = $driver;
            } else {
                self::$driver = \mailer\lib\log\File::class;
            }
        }
    }

    /**
     * 写入日志
     *
     * @param        $content
     * @param string $level
     */
    public static function write($content, $level = self::DEBUG)
    {
        self::init();

        $driver = self::$driver;
        $driver::write($content, $level);
    }
}
