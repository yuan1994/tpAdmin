<?php
// +----------------------------------------------------------------------
// | tpadmin [a web admin based ThinkPHP5]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 tianpian
// +----------------------------------------------------------------------
// | Author: tianpian <tianpian0805@gmail.com>
// +----------------------------------------------------------------------

//------------------------
// 公共函数
//-------------------------

/**
 * 调试打印函数
 * @param $string
 */
function p($string,$func = null){
    echo '<pre>';
    if ($func === null){
        if (is_bool($string) || is_null($string) || is_object($string)){
            var_dump($string);
        } else {
            print_r($string);
        }
    } else {
        call_user_func($func,$string);
    }
    echo '</pre>';
}

/**
 * flash message
 *
 * flash("?KEY") 判断是否存在flash message KEY 返回bool值
 * flash("KEY") 获取flash message，存在返回具体值，不存在返回null
 * flash("KEY","VALUE") 设置flash message
 * @param string $key
 * @param bool|string $value
 * @return bool|mixed|null
 */
function flash($key, $value = false)
{
    $prefix = "flash_";
    //判断是否存在flash message
    if (preg_match("/^\?(.*)$/", $key, $matches)) {
        return \think\Session::has($prefix . $matches[1]);
    } else {
        $flash_key = $prefix . $key;
        if (false === $value) {
            //获取flash
            if (\think\Session::has($flash_key)) {
                //flash存在
                $ret = unserialize(\think\Session::get($flash_key));
                //删除session
                \think\Session::delete($flash_key);
                return $ret;
            } else {
                //flash不存在返回null
                return null;
            }
        } else {
            //设置flash
            \think\Session::set($flash_key, serialize($value));
        }
    }
}

/**
 * 表格排序筛选
 * @param string $name 单元格名称
 * @param string $field 排序字段
 * @return string
 */
function sort_by($name, $field = '')
{
    $_sort = input("param._sort");
    $param = $_GET;
    $param['_sort'] = ($_sort == 'asc' ? 'desc' : 'asc');
    $param['_order'] = $field;
    $url = url(request()->action(), $param);
    return input('param._order') == $field ? "<a href='{$url}' title='点击排序' class='sorting-box sorting-{$_sort}'>{$name}</a>" : "<a href='{$url}' title='点击排序' class='sorting-box sorting'>{$name}</a>";
}

/**
 * 用于高亮搜索关键词
 * @param string $string 原文本
 * @param string $needle 关键词
 * @param string $class span标签class名
 * @return mixed
 */
function high_light($string, $needle = '', $class = 'c-red')
{
    return $needle !== '' ? str_replace($needle, "<span class='{$class}'>" . $needle . "</span>", $string) : $string;
}

/**
 * 用于显示状态操作按钮
 * @param int $status 0|1|-1状态
 * @param int $id 对象id
 * @param string $field 字段，默认id
 * @param string $controller 默认当前控制器
 * @return string
 */
function show_status($status, $id, $field = 'id', $controller = '')
{
    $controller === '' && $controller = request()->controller();
    switch ($status) {
        case 0 :
            $ret = '<a href="javascript:;" onclick="ajax_req(\'' . url($controller . '/resume', array($field => $id)) . '\',{},change_status,[this,\'resume\'])" class="label label-success radius" title="点击恢复">恢复</a>';
            break;
        case 1 :
            $ret = '<a href="javascript:;" onclick="ajax_req(\'' . url($controller . '/forbid', array($field => $id)) . '\',{},change_status,[this,\'forbid\'])" class="label label-warning radius" title="点击禁用">禁用</a>';
            break;
        case -1 :
            $ret = '<a href="javascript:;" onclick="ajax_req(\'' . url($controller . '/recycle', array($field => $id)) . '\')" class="label label-secondary radius" title="点击还原">还原</a>';
            break;
    }
    return $ret;
}

/**
 * 显示状态
 * @param int $status 0|1|-1
 * @param bool $imageShow true只显示图标|false只显示文字
 * @return string
 */
function get_status($status, $imageShow = true)
{
    switch ($status) {
        case 0 :
            $showText = '禁用';
            $showImg = '<i class="Hui-iconfont c-warning status" title="禁用">&#xe631;</i>';
            break;
        case -1 :
            $showText = '删除';
            $showImg = '<i class="Hui-iconfont c-danger status" title="删除">&#xe6e2;</i>';
            break;
        case 1 :
        default :
            $showText = '正常';
            $showImg = '<i class="Hui-iconfont c-success status" title="正常">&#xe615;</i>';

    }
    return ($imageShow === true) ? $showImg : $showText;
}

/**
 * 框架内部默认ajax返回
 * @param string $info 提示信息
 * @param string $alert 父层弹框信息
 * @param bool $close 是否关闭当前层
 * @param string $redirect 是否重定向
 * @param string $url 重定向地址
 * @param string $data 附加数据
 * @param string $status y|n 状态，y成功，n失败
 */
function ajax_return_adv($info = '操作成功', $alert = '', $close = false, $redirect = '', $url = '', $data = '', $status = "y")
{
    if (1 == $redirect) $redirect = 'current';
    if (2 == $redirect) $redirect = 'parent';
    $ret = array(
        'status' => $status,
        'info' => $info,
        'alert' => $alert,
        'close' => $close,
        'redirect' => $redirect,
        'url' => $url,
        'data' => $data
    );
    exit(json_encode($ret));
}

function ajax_return_adv_error($info = '', $alert = '', $close = false, $redirect = '', $url = '', $data = '', $status = "n")
{
    ajax_return_adv($info, $alert, $close, $redirect, $url, $data, $status);
}

/**
 * ajax数据返回，规范格式
 * @param array $data 返回的数据，默认空数组
 * @param string $msg 信息
 * @param int $code 错误码，0-未出现错误|其他出现错误
 */
function ajax_return($data = [], $msg = "", $code = 0){
    $ret = ["code"=>$code,"msg"=>$msg,"data"=>$data];
    exit(json_encode($ret));
}

function ajax_return_error($msg = "出现错误", $code = 1, $data = []){
    ajax_return($data, $msg, $code);
}

/**
 * 从二维数组中取出自己要的KEY值
 * @param  array $arrData
 * @param string $key
 * @param $im true 返回逗号分隔
 * @return array
 */
function filterValue ($arrData,$key,$im=false) {
    $re = array();
    foreach ($arrData as $k => $v) {
        if(isset($v[$key]))$re[] = $v[$key];
    }
    if(!empty($re)){
        $re = array_flip(array_flip($re));
        sort($re);
    }
    return $im? implode(',',$re) : $re;
}

/**
 * 重设键，转为array(key=>array())
 *
 * @param array $arr
 * @param string $key
 * @return array
 */
function resetByKey ($arr, $key) {
    $re = array();
    foreach ($arr as $v) {
        $re[$v[$key]] = $v;
    }
    return $re;
}

/**
 * 节点遍历
 * @param $list
 * @param string $pk
 * @param string $pid
 * @param string $child
 * @param int $root
 * @return array
 */
function list_to_tree($list, $pk='id',$pid = 'pid',$child = '_child',$root=0)
{
    // 创建Tree
    $tree = array();
    if(is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $list[$key];
            }else{
                if(isset($refer[$parentId])) {
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }
    return $tree;
}

/**
 * 将树递归成多维数组
 * @param array $tree 树
 * @param string $key 放入多维数组里的键名
 * @param string|array $key_default 默认值，如果是数组[VALUE]，则为当前数组子项的键名，如果是其他就是传入的值
 * @param string $key_child 子节点键名
 * @param null|string $callback 回调函数
 * @return array
 */
function tree_to_multi_array($tree,$key="name",$key_default="id",$key_child="_child",$callback=null){
    $return = [];
    if (is_array($tree)){
        foreach ($tree as $v){
            $new_key = $callback===null ? $v[$key] : call_user_func($callback,$v[$key]);
            $return[$new_key] = isset($v[$key_child]) ?
                tree_to_multi_array($v[$key_child],$key,$key_default,$key_child,$callback) :
                (is_array($key_default) ? $v[$key_default[0]] : $key_default) ;
        }
    }
    return $return;
}

/**
 * 一维数组转多维数组
 * @param array $arr
 * @param int $length 数组长度
 * @param int $i 起始位置
 * @return array
 */
function one_to_multi_array($arr, $length, $i = 0){
    $ret = array();
    if ($i == $length - 1) {
        $ret[$arr[$i]] = [];
    } else {
        $ret[$arr[$i]] = one_to_multi_array($arr, $length, $i + 1);
    }
    return $ret;
}

/**
 * 统一密码加密方式，如需变动直接修改此处
 * @param $password
 * @return string
 */
function password_hash_my($password){
    return hash("md5",$password);
}

/**
 * 对ID加密
 * @param null|int $length
 * @param null|string $salt
 * @param null|string $alphabet
 * @return Hashids\Hashids static
 */
function hashids($length=null,$salt=null,$alphabet=null){
    return \Hashids\Hashids::instance($length,$salt,$alphabet);
}

/**
 * 一键导出Excel 2007格式
 * @param array $header Excel头部 ["COL1","COL2","COL3",...]
 * @param array $body 和头部长度相等字段查询出的数据就可以直接导出
 * @param null|string $name 文件名，不包含扩展名，为空默认为当前时间
 * @return string
 */
function export_excel($header,$body,$name = null){
    return \Excel::export($header,$body,$name);
}

/**
 * 获取七牛上传token
 * @return mixed
 */
function qiniu_token(){
    return Qiniu::token();
}

/**
 * CURLFILE兼容性处理 php < 5.5
 */
if (!function_exists('curl_file_create')) {
    function curl_file_create($filename, $mimetype = '', $postname = '') {
        return "@$filename;filename="
        . ($postname ?: basename($filename))
        . ($mimetype ? ";type=$mimetype" : '');
    }
}

/**
 * 生成随机字符串
 * @param string $prefix
 * @return string
 */
function get_random($prefix=''){
    return base_convert(time()*1000,10,36)."_".base_convert(microtime(),10,36).uniqid();
}

/**
 * 检查指定节点是否有权限
 * @param null $action
 * @param null $controller
 * @param null $module
 * @return bool
 */
function check_access($action=null,$controller=null,$module=null){
    return \Rbac::AccessDecision($module,$controller,$action);
}

/**
 * 获取自定义配置
 * @param string|int $name 配置项的key或者value，传key返回value，传value返回key
 * @param string $conf
 * @param bool $key 传递的是否是配置键名，默认是，则返回配置信息
 * @return int|string
 */
function get_conf($name,$conf,$key = true){
    $arr = config("conf.".$conf);
    if ($key) return $arr[$name];
    foreach ($arr as $k=>$v){
        if ($v == $name){
            return $k;
        }
    }
}

/**
 * 文件下载
 * @param $file_path
 * @param string $file_name
 * @param string $file_size
 * @param string $ext
 * @return string
 */
function download($file_path,$file_name = '',$file_size = '',$ext=''){
    return \File::download($file_path,$file_name,$file_size,$ext);
}