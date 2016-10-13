<?php
// +----------------------------------------------------------------------
// | tpadmin [a web admin based ThinkPHP5]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 tianpian
// +----------------------------------------------------------------------
// | Author: tianpian <tianpian0805@gmail.com>
// +----------------------------------------------------------------------

return array(
    //模板参数替换
    'view_replace_str'  =>  [
        '__STATIC__'    => '/static/admin',
        '__LIB__'     => '/static/admin/lib',
    ],

    // 异常处理handle类 留空使用 \think\exception\Handle
    'exception_handle'       => '\\MyException',

    'template'               => [
        // 模板引擎类型 支持 php think 支持扩展
        'type'         => 'Think',
        // 模板路径
        'view_path'    => '',
        // 模板后缀
        'view_suffix'  => '.html',
        // 预先加载的标签库
        'taglib_pre_load'     =>    'app\\admin\\taglib\\Tp',
    ],
);