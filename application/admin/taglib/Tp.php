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
// 自定义标签库
//-------------------------

namespace app\admin\taglib;

use think\template\TagLib;
use think\Request;

class Tp extends Taglib
{

    // 标签定义
    protected $tags = [
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'access' => ['attr' => 'action,controller,module'],
        'widget' => ['attr' => 'url,param', 'close' => 0],
    ];

    /**
     * 权限检测
     * @param $tag
     * @param $content
     * @return string
     */
    public function tagAccess($tag, $content)
    {
        $request = Request::instance();
        $module = isset($tag['module']) ? $tag['module'] : $request->module();
        $controller = isset($tag['controller']) ? $tag['controller'] : $request->controller();
        $action = isset($tag['action']) ? $tag['action'] : $request->action();
        $parseStr = "<?php if (\Rbac::AccessCheck('{$action}', '{$controller}', '{$module}')) : ?>" . $content . "<?php endif; ?>";

        return $parseStr;
    }

    /**
     * 小组件
     * @param $tag
     * @return string
     */
    public function tagWidget($tag)
    {
        $url = $tag['url'] ?: '';
        $param = $tag['param'] ?: [];
        $parseStr = "<?php echo \\think\\Loader::action('{$url}', {$param}, 'widget'); ?>";

        return $parseStr;
    }
}