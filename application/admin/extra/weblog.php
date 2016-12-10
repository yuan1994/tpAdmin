<?php
/**
 * tpAdmin [a web admin based ThinkPHP5]
 *
 * @author yuan1994 <tianpian0805@gmail.com>
 * @link http://tpadmin.yuan1994.com/
 * @copyright 2016 yuan1994 all rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

return [
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
