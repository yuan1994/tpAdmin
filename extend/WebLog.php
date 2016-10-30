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
// 网站日志
//-------------------------

use think\Db;
use think\Config;
use think\Request;

class WebLog
{
    private static $instance;

    private $config = [
        'max_rows'              => 200000, // 单表最大纪录值
        'not_record_controller' => [], // 不记录的控制器
        'not_record_map'        => [], // 不记录的节点图
        'web_log_table'         => 'web_log', // 操作日志存储表
    ];

    private $prefix; // 表前缀

    public static function instance($config = [])
    {
        if (null === self::$instance) {
            self::$instance = new self($config);
        }

        return self::$instance;
    }

    public function __construct($config = [])
    {
        $this->config = array_merge($this->config, Config::get('weblog') ?: [], $config);
        $this->prefix = Config::get('database.prefix');
    }

    /**
     * 记录网站日志
     * @param $insert
     * @return bool
     */
    public function record($insert)
    {
        // 不记录已排除的日志
        if (
            in_array(Request::instance()->controller(), $this->config['not_record_controller']) ||
            in_array($insert['map'], $this->config['not_record_map'])
        ) {
            return true;
        }
        $table = $this->prefix . $this->config['web_log_table'] . '_all';
        $logId = Db::table($table)->insertGetId($insert);
        // 自动分表
        if ($logId % $this->config['max_rows'] == 0) {
            // 获取建表语句
            $result = Db::query("SHOW CREATE TABLE {$table}");
            $sql = array_pop($result[0]);
            // 获取联合表的所有表名
            preg_match('/UNION=\(([^\)]*)/', $sql, $matches);
            $tables = explode(',', $matches[1]);
            // 取到最后一个表名，作为取 id 的依据
            $tableLast = end($tables);
            // 表名都是包含零填充的三位整数
            $id = intval(substr($tableLast, -4, -1));
            $tableNew = $this->prefix . $this->config['web_log_table'] . '_' . sprintf('%03d', $id + 1);
            // 建表并给设置自动递增 id
            self::createTable($tableNew, $id * intval($this->config['max_rows']) + 1);
            // 更新 merge 表的 union 信息
            array_push($tables, $tableNew);
            Db::execute("ALTER TABLE {$table} UNION = (" . implode(',', $tables) . ")");
        }

        return true;
    }

    /**
     * 创建日志分表
     * @param $tableName
     * @return int
     */
    private function createTable($tableName, $autoIncrement = 1)
    {
        $sql = "CREATE TABLE `{$tableName}` (" .
            "`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志主键'," .
            "`uid` smallint(5) unsigned NOT NULL COMMENT '用户id'," .
            "`ip` char(15) NOT NULL COMMENT '访客ip'," .
            "`location` varchar(255) NOT NULL COMMENT '访客地址'," .
            "`os` varchar(255) NOT NULL COMMENT '操作系统'," .
            "`browser` varchar(255) NOT NULL COMMENT '浏览器'," .
            "`url` varchar(255) NOT NULL COMMENT 'url'," .
            "`module` char(6) NOT NULL COMMENT '模块'," .
            "`map` varchar(255) NOT NULL COMMENT '节点图'," .
            "`is_ajax` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否是ajax请求'," .
            "`data` text NOT NULL COMMENT '请求的param数据，serialize后的'," .
            "`otime` int(10) unsigned NOT NULL COMMENT '操作时间'," .
            "PRIMARY KEY (`id`)," .
            "KEY `uid` (`uid`)," .
            "KEY `ip` (`ip`)," .
            "KEY `map` (`map`)," .
            "KEY `otime` (`otime`)" .
            ") ENGINE=MyISAM AUTO_INCREMENT={$autoIncrement} DEFAULT CHARSET=utf8 COMMENT='网站日志';";

        Db::execute($sql);
    }
}