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

//------------------------
// 助手函数
//-------------------------

/**
 * 对ID加密
 * @param null|int $length
 * @param null|string $salt
 * @param null|string $alphabet
 * @return Hashids\Hashids static
 */
function hashids($length = null, $salt = null, $alphabet = null)
{
    return \Hashids\Hashids::instance($length, $salt, $alphabet);
}

/**
 * 一键导出Excel 2007格式
 * @param array $header     Excel头部 ["COL1","COL2","COL3",...]
 * @param array $body       和头部长度相等字段查询出的数据就可以直接导出
 * @param null|string $name 文件名，不包含扩展名，为空默认为当前时间
 * @param string|int $version 版本 2007|2003|ods|pdf
 * @return string
 */
function export_excel($header, $body, $name = null, $version = '2007')
{
    return \Excel::export($header, $body, $name, $version);
}

/**
 * 获取七牛上传token
 * @return mixed
 */
function qiniu_token()
{
    return \Qiniu::token();
}

/**
 * 检查指定节点是否有权限
 * @param null $action
 * @param null $controller
 * @param null $module
 * @return bool
 */
function check_access($action = null, $controller = null, $module = null)
{
    return \Rbac::AccessCheck($action, $controller, $module);
}

/**
 * 文件下载
 * @param $file_path
 * @param string $file_name
 * @param string $file_size
 * @param string $ext
 * @return string
 */
function download($file_path, $file_name = '', $file_size = '', $ext = '')
{
    return \File::download($file_path, $file_name, $file_size, $ext);
}
