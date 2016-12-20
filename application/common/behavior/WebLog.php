<?php
/**
 * tpAdmin [a web admin based ThinkPHP5]
 *
 * @author    yuan1994 <tianpian0805@gmail.com>
 * @link      http://tpadmin.yuan1994.com/
 * @copyright 2016 yuan1994 all rights reserved.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 */

namespace app\common\behavior;

use think\Db;
use think\Config;
use think\Request;
use think\Session;

/**
 * 网站日志记录行为类
 *
 * Class WebLog
 * @package app\common\behavior
 */
class WebLog
{
    /**
     * @var array 配置信息
     */
    protected static $config = [
        /**
         *  单表最大纪录值，大于该值会自动分表
         */
        'max_rows'              => 200000,
        /**
         * 不开启日志记录的控制器，设置后该控制器下所有方法都将不记录日志
         */
        'not_record_controller' => [],
        /**
         * 不记录日单的方法，例如 AdminGroup/add，one.Two/add
         */
        'not_record_map'        => [],
        /**
         * 操作日志存储表，不含表名前缀
         */
        'web_log_table'         => 'web_log',
        /**
         * data字段存储最大数据长度，0不受限制，避免因文章等数据导致字段超长，数据存储被截断而报错
         */
        'max_data_length'       => 200,
        /**
         * 表名前缀，如果为null则为框架配置文件所设表前缀
         */
        'table_prefix'          => null,
    ];

    /**
     * @var array 要写入到数据库的数据
     */
    protected static $param = [];

    /**
     * @var array 请求的数据
     */
    protected static $data = [];

    /**
     * @var bool 是否禁用当前日志记录，禁用后不记录当前日志
     */
    protected static $forbid = false;


    const UID = 'uid'; // 用户id
    const IP = 'ip'; // 用户ip
    const LOCATION = 'location'; // 用户地点，通过ip查询
    const OS = 'os'; // 操作系统
    const BROWSER = 'browser'; // 浏览器
    const URL = 'url'; // 请求地址
    const MODULE = 'module'; // 模块
    const CONTROLLER = 'controller'; // 控制器
    const ACTION = 'action'; // 方法
    const METHOD = 'method'; // 请求方式
    const DATA = 'data'; // 数据
    const CREATE_AT = 'create_at'; // 请求时间


    public function __construct()
    {
        // 加载配置文件
        self::$config = array_merge(self::$config, Config::get('weblog') ?: []);
    }


    /**
     * 模块初始化
     *
     * @param $param
     */
    public function module_init(&$param)
    {
        $this->init();
    }

    /**
     * module_init同名函数，ThinkPHP5.0.4默默的将行为规范为psr-4标准
     *
     * @param $param
     */
    public function moduleInit(&$param)
    {
        $this->init();
    }

    /**
     * 应用执行完
     *
     * @param $param
     */
    public function app_end(&$param)
    {
        $this->record();
    }

    /**
     * app_end同名函数，ThinkPHP5.0.4默默的将行为规范为psr-4标准
     *
     * @param $param
     */
    public function appEnd(&$param)
    {
        $this->record();
    }

    /**
     * 设置配置信息
     *
     * @param string $name
     * @param mixed  $value
     */
    public static function setConfig($name, $value)
    {
        self::$config[$name] = $value;
    }

    /**
     * 禁用日志记录
     */
    public static function forbid()
    {
        self::$forbid = true;
    }

    /**
     * 设置请求数据
     *
     * @param $name
     * @param $value
     */
    public static function setData($name, $value)
    {
        self::$data[$name] = $value;
    }

    /**
     * 获取请求数据，若为null则获取所有，若不存在返回null
     *
     * @param null $name
     *
     * @return array|mixed|null
     */
    public static function getData($name = null)
    {
        return null === $name
            ? self::$data
            : (
            isset(self::$data[$name])
                ? self::$data[$name]
                : null
            );
    }

    /**
     * 删除请求数据，若为null则删除所有
     *
     * @param $name
     */
    public static function removeData($name)
    {
        if (null === $name) {
            self::$data = [];
        } else {
            unset(self::$data[$name]);
        }
    }

    /**
     * 获取日志记录信息
     *
     * @param $name
     *
     * @return mixed|null
     */
    public static function get($name)
    {
        return isset(self::$param[$name]) ? self::$param[$name] : null;
    }

    /**
     * 修改日志记录信息
     *
     * @param $name
     * @param $value
     */
    public static function set($name, $value)
    {
        if (isset(self::$param[$name])) {
            self::$param[$name] = $value;
        }
    }

    /**
     * 初始化，行为标签开始加载需执行此方法
     */
    protected function init()
    {
        $request = Request::instance();
        // 请求数据
        self::$data = $request->param();
        // 其他用户信息数据
        $ip = $request->ip();
        $locationArr = \Ip::find($ip);
        $location = is_array($locationArr) ? implode(' ', $locationArr) : $locationArr;
        self::$param = [
            self::UID        => Session::get(Config::get('rbac.user_auth_key')) ?: 0,
            self::IP         => $ip,
            self::LOCATION   => $location,
            self::OS         => \Agent::getOs(),
            self::BROWSER    => \Agent::getBroswer(),
            self::URL        => $request->url(true),
            self::MODULE     => $request->module(),
            self::CONTROLLER => $request->controller(),
            self::ACTION     => $request->action(),
            self::METHOD     => $request->method(),
            self::DATA       => self::$data,
            self::CREATE_AT  => time(),
        ];
    }

    /**
     * 记录网站日志
     *
     * @return bool
     */
    public function record()
    {
        // 禁用记录日志时不记录
        if (self::$forbid) {
            return true;
        }

        // 不记录已排除的日志
        $request = Request::instance();
        if (
            in_array($request->controller(), self::$config['not_record_controller'])
            || in_array($request->controller() . '/' . $request->action(), self::$config['not_record_map'])
        ) {
            return true;
        }

        // 组装数据
        $log = self::$param;
        $log[self::METHOD] = strtoupper($log[self::METHOD]);
        $data = self::$data;
        // 截取一部分数据，避免数据太大导致存储出错，比如文章发布提交的数据
        if (self::$config['max_data_length'] > 0) {
            foreach ($data as &$v) {
                if (is_string($v)) {
                    $v = mb_substr($v, 0, self::$config['max_data_length']);
                }
            }
        }
        $log[self::DATA] = serialize($data);

        // 写入日志
        $tablePrefix = null === self::$config['table_prefix'] ? Config::get('database.prefix') : self::$config['table_prefix'];
        $table = $tablePrefix . self::$config['web_log_table'] . '_all';
        $logId = Db::table($table)->insertGetId($log);

        // 自动分表
        if ($logId % self::$config['max_rows'] == 0) {
            // 获取建表语句
            $result = Db::query("SHOW CREATE TABLE {$table}");
            $sql = array_pop($result[0]);
            // 获取联合表的所有表名
            preg_match('/UNION=\(([^\)]*)/', $sql, $matches);
            $tables = explode(',', $matches[1]);
            // 取到最后一个表名，作为取 id 的依据
            $tableLast = end($tables);
            $tableLast = trim($tableLast, '`');
            // 表名都是包含零填充的三位整数
            $id = intval(substr($tableLast, -3, 3));
            $tableNew = $tablePrefix . self::$config['web_log_table'] . '_' . sprintf('%03d', $id + 1);
            // 建表并给设置自动递增 id
            self::createTable($tableNew, $tableLast, $id * intval(self::$config['max_rows']) + 1);
            // 更新 merge 表的 union 信息
            array_push($tables, $tableNew);
            Db::execute("ALTER TABLE {$table} UNION = (" . implode(',', $tables) . ")");
        }

        return true;
    }

    /**
     * 创建日志分表
     *
     * @param string $tableNew      新表表名
     * @param string $tableOld      参照表表名
     * @param int    $autoIncrement 自增id
     *
     * @return int
     */
    private function createTable($tableNew, $tableOld, $autoIncrement = 1)
    {
        // 获取建表语句
        $result = Db::query("SHOW CREATE TABLE {$tableOld}");
        $sql = array_pop($result[0]);
        $sql = preg_replace(
            ['/CREATE TABLE `(\w+)`/', '/AUTO_INCREMENT=(\d+)/'],
            ["CREATE TABLE `{$tableNew}`", "AUTO_INCREMENT={$autoIncrement}"],
            $sql
        );

        return Db::execute($sql);
    }
}
