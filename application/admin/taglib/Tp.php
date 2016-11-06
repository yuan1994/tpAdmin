<?php
/**
 * tpAdmin [a web admin based ThinkPHP5]
 *
 * @author    yuan1994 <tianpian0805@gmail.com>
 * @link      http://tpadmin.yuan1994.com/
 * @copyright 2016 yuan1994 all rights reserved.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 */

//------------------------
// 自定义标签库
//-------------------------

namespace app\admin\taglib;

use think\template\TagLib;
use think\Request;
use think\Url;
use think\Template;

class Tp extends Taglib
{

    // 标签定义
    protected $tags = [
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'access' => [],
        'widget' => ['attr' => 'url,param', 'close' => 0],
        'menu'   => ['close' => 0],
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

    /**
     * 菜单扩展
     */
    public function tagMenu($tag)
    {
        $menu = isset($tag['menu']) ?
            (is_array($tag['menu']) ? $tag['menu'] : explode(',', $tag['menu'])) :
            ['add', 'forbid', 'resume', 'delete', 'recyclebin'];
        $titleArr = isset($tag['title']) ?
            (is_array($tag['title']) ? $tag['title'] : explode(',', $tag['title'])) :
            [];
        $urlArr = isset($tag['url']) ?
            (is_array($tag['url']) ? $tag['url'] : explode(',', $tag['url'])) :
            [];
        $parseStr = '';
        foreach ($menu as $k => $m) {
            $m = strtolower($m);
            $url = isset($urlArr[$k]) && $urlArr[$k] ? $urlArr[$k] : (substr($m, 0, 1) == 's' ? substr($m, 1) : $m);
            $urls = explode(":", $url);
            $parseStr .= "<?php if (\Rbac::AccessCheck('" . $urls[0] . "')) : ?>";
            switch ($m) {
                case 'add':
                    $title = isset($titleArr[$k]) && $titleArr[$k] ? $titleArr[$k] : '添加';
                    list($url, $param) = $this->parseUrl($url);
                    $parseStr .= '<a class="btn btn-primary radius" href="javascript:;" onclick="layer_open(\'' . $title . '\',\'<?php echo \think\Url::build(\'' . $url . '\', [' . $param . ']); ?>\')"><i class="Hui-iconfont">&#xe600;</i> ' . $title . '</a>';
                    break;
                case 'forbid':
                    $title = isset($titleArr[$k]) && $titleArr[$k] ? $titleArr[$k] : '禁用';
                    list($url, $param) = $this->parseUrl($url);
                    $parseStr .= '<a href="javascript:;" onclick="forbid_all(\'<?php echo \think\Url::build(\'' . $url . '\', [' . $param . ']); ?>\')" class="btn btn-warning radius ml-5"><i class="Hui-iconfont">&#xe631;</i> ' . $title . '</a>';
                    break;
                case 'resume':
                    $title = isset($titleArr[$k]) && $titleArr[$k] ? $titleArr[$k] : '恢复';
                    list($url, $param) = $this->parseUrl($url);
                    $parseStr .= '<a href="javascript:;" onclick="resume_all(\'<?php echo \think\Url::build(\'' . $url . '\', [' . $param . ']); ?>\')" class="btn btn-success radius ml-5"><i class="Hui-iconfont">&#xe615;</i> ' . $title . '</a>';
                    break;
                case 'delete':
                    $title = isset($titleArr[$k]) && $titleArr[$k] ? $titleArr[$k] : '删除';
                    list($url, $param) = $this->parseUrl($url);
                    $parseStr .= '<a href="javascript:;" onclick="del_all(\'<?php echo \think\Url::build(\'' . $url . '\', [' . $param . ']); ?>\')" class="btn btn-danger radius ml-5"><i class="Hui-iconfont">&#xe6e2;</i> ' . $title . '</a>';
                    break;
                case 'recyclebin':
                    $title = isset($titleArr[$k]) && $titleArr[$k] ? $titleArr[$k] : '回收站';
                    list($url, $param) = $this->parseUrl($url);
                    $parseStr .= '<a href="javascript:;" onclick="open_window(\'' . $title . '\',\'<?php echo \think\Url::build(\'' . $url . '\', [' . $param . ']); ?>\')" class="btn btn-secondary radius ml-5"><i class="Hui-iconfont">&#xe6b9;</i> ' . $title . '</a>';
                    break;
                case 'recycle':
                    $title = isset($titleArr[$k]) && $titleArr[$k] ? $titleArr[$k] : '还原';
                    list($url, $param) = $this->parseUrl($url);
                    $parseStr .= '<a class="btn btn-success radius" href="javascript:;" onclick="recycle_all(\'<?php echo \think\Url::build(\'' . $url . '\', [' . $param . ']); ?>\')"><i class="Hui-iconfont">&#xe610;</i> ' . $title . '</a>';
                    break;
                case 'deleteforever':
                    $title = isset($titleArr[$k]) && $titleArr[$k] ? $titleArr[$k] : '彻底删除';
                    list($url, $param) = $this->parseUrl($url);
                    $parseStr .= '<a href="javascript:;" onclick="del_forever_all(\'<?php echo \think\Url::build(\'' . $url . '\', [' . $param . ']); ?>\')" class="btn btn-danger radius ml-5"><i class="Hui-iconfont">&#xe6e2;</i> ' . $title . '</a>';
                    break;
                case 'clear':
                    $title = isset($titleArr[$k]) && $titleArr[$k] ? $titleArr[$k] : '清空回收站';
                    list($url, $param) = $this->parseUrl($url);
                    $parseStr .= '<a href="javascript:;" onclick="clear_recyclebin(\'<?php echo \think\Url::build(\'' . $url . '\', [' . $param . ']); ?>\')" class="btn btn-danger radius ml-5"><i class="Hui-iconfont">&#xe6e2;</i> ' . $title . '</a>';
                    break;
                case 'edit':
                case 'sedit':
                    $title = isset($titleArr[$k]) && $titleArr[$k] ? $titleArr[$k] : '编辑';
                    list($url, $param) = $this->parseUrl($url, 'id=$vo["id"]');
                    $parseStr .= ' <a title="' . $title . '" href="javascript:;" onclick="layer_open(\'' . $title . '\',\'<?php echo \think\Url::build(\'' . $url . '\', [' . $param . ']); ?>\')" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>';
                    break;
                case 'sdelete':
                    $title = isset($titleArr[$k]) && $titleArr[$k] ? $titleArr[$k] : '删除';
                    list($url, $param) = $this->parseUrl($url);
                    $parseStr .= ' <a title="' . $title . '" href="javascript:;" onclick="del(this,\'{$vo.id}\',\'<?php echo \think\Url::build(\'' . $url . '\', [' . $param . ']); ?>\')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>';
                    break;
                case 'srecycle':
                    $title = isset($titleArr[$k]) && $titleArr[$k] ? $titleArr[$k] : '还原';
                    list($url, $param) = $this->parseUrl($url);
                    $parseStr .= ' <a href="javascript:;" onclick="recycle(this,\'{$vo.id}\',\'<?php echo \think\Url::build(\'' . $url . '\', [' . $param . ']); ?>\')" class="label label-success radius">' . $title . '</a>';
                    break;
                case 'sdeleteforever':
                    $title = isset($titleArr[$k]) && $titleArr[$k] ? $titleArr[$k] : '彻底删除';
                    list($url, $param) = $this->parseUrl($url);
                    $parseStr .= ' <a href="javascript:;" onclick="del_forever(this,\'{$vo.id}\',\'<?php echo \think\Url::build(\'' . $url . '\', [' . $param . ']); ?>\')" class="label label-danger radius">' . $title . '</a>';
                    break;
            }
            $parseStr .= "<?php endif; ?>";
        }

        return $parseStr;
    }

    /**
     * 将参数格式化成url和可用参数
     * @param $url
     * @param string $default
     * @return array
     */
    private function parseUrl($url, $default = '')
    {
        $urls = explode(":", $url);
        $params = explode("&", count($urls) == 1 ? $default : $urls[1]);
        $ret = '';
        foreach ($params as $param) {
            if ($param) {
                list($key, $value) = explode("=", $param);
                if (in_array(substr($value, 0, 1), ['$', ':', '~', '+', '-'])) {
                    $this->tpl->parseVar($value);
                    $ret .= "'{$key}' => {$value}, ";
                } else {
                    $ret .= "'{$key}' => '{$value}', ";
                }
            }
        }

        return [$urls[0], $ret];
    }
}