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
 * Class Config
 * @package mailer\lib
 */
class Config
{
    /**
     * @var array 配置项
     */
    private static $config = [];
    /**
     * @var bool 是否初始化
     */
    private static $isInit = false;

    /**
     * 初始化配置项
     *
     * @param array $config
     */
    public static function init($config = [])
    {
        if ($config) {
            self::$config = array_merge(self::$config, $config);
            self::$isInit = true;
        } elseif (!self::$isInit) {
            self::detect();
            self::$isInit = true;
        }
    }

    /**
     * 获取配置参数 为空则获取所有配置
     *
     * @param string $name    配置参数名
     * @param mixed  $default 默认值
     *
     * @return mixed
     */
    public static function get($name = null, $default = null)
    {
        self::init();

        if (!$name) {
            return self::$config;
        } else {
            if (isset(self::$config[$name])) {
                return self::$config[$name];
            } else {
                return $default;
            }
        }
    }

    /**
     * 设置配置参数
     *
     * @param string|array $name  配置参数名
     * @param mixed        $value 配置值
     */
    public static function set($name, $value)
    {
        self::init();

        self::$config[$name] = $value;
    }

    /**
     * 自动探测配置项
     */
    private static function detect()
    {
        if (class_exists('\\think\\Config')) {
            // thinkphp5自动探测初始化配置项
            self::$config = \think\Config::get('mail');
        } elseif (function_exists('C')) {
            // thinkphp3自动探测初始化配置项
            self::$config = C('mail');
        } else {
            // 其他框架如果未初始化则抛出异常
            throw new InvalidArgumentException('未初始化配置项，请使用 mail\\lib\\Config::init()初始化配置项');
        }
    }
}
