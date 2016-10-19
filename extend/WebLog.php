<?php
// +----------------------------------------------------------------------
// | tpadmin [a web admin based ThinkPHP5]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 tianpian
// +----------------------------------------------------------------------
// | Author: tianpian <tianpian0805@gmail.com>
// +----------------------------------------------------------------------

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
    ];

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
    }

    /**
     * 记录网站日志
     * @param $insert
     * @return bool
     */
    public function record($insert)
    {
        //不记录已排除的日志
        if (
            in_array(Request::instance()->controller(), $this->config['not_record_controller']) ||
            in_array($insert['map'], $this->config['not_record_map'])
        ) {
            return true;
        }
        $tableWebLog = 'web_log';
        $tableId = Db::name('WebLogRecord')->order('id desc')->value('table_id');
        $logId = Db::name($tableWebLog . '_' . sprintf('%03d', $tableId))->insertGetId($insert);
        // 大于最大单表纪录值自动分表
        if ($logId >= $this->config['max_rows']) {
            $tableIdLast = intval($tableId) + 1;
            if (false === $this->createWebLog($tableWebLog . '_' . sprintf('%03d', $tableIdLast))) {
                //TODO 表创建异常处理
            }

            // 更新记录分表情况的数据表
            Db::name('WebLogRecord')->where('table_id', $tableId)->update(['end_time' => $insert['otime']]);
            Db::name('WebLogRecord')->insert([
                'table_id'   => $tableIdLast,
                'start_time' => $insert['otime'],
            ]);
        }

        return true;
    }

    /**
     * 创建日志分表
     * @param $tableName
     * @return int
     */
    private function createWebLog($tableName)
    {
        $tableName = Config::get('database.prefix') . $tableName;
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
            ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='网站日志';";

        return Db::execute($sql);
    }
}