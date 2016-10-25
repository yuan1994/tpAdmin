<?php
// +----------------------------------------------------------------------
// | tpadmin [a web admin based ThinkPHP5]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 tianpian All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: tianpian <tianpian0805@gmail.com>
// +----------------------------------------------------------------------

return [
    'max_rows'              => 2000000, // 单表最大纪录值
    'not_record_controller' => ['Index'], // 不记录的控制器
    'not_record_map'        => ['AdminGroup/index'], // 不记录的节点图
    'web_log_table'         => 'web_log', // 操作日志存储表
];
