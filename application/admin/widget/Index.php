<?php
/**
 * tpAdmin [a web admin based ThinkPHP5]
 *
 * @author yuan1994 <tianpian0805@gmail.com>
 * @link http://tpadmin.yuan1994.com/
 * @copyright 2016 yuan1994 all rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace app\admin\widget;

class Index
{
    /**
     * 生成无限级授权菜单
     * @param $list
     * @return string
     */
    public function menu($list)
    {
        $html = "";
        $generate = function ($list, $controller = "") use (&$html, &$generate) {
            $html .= "<li>";
            if (isset($list['_child']) && $list['_child']) {
                $controller .= $list['name'] . ".";
                $html .= '<a class="sub-menu-title" data-title="' . $list['title'] . '" href="javascript:;"><b>+</b>' . $list['title'] . '</a>';
                $html .= '<ul class="sub-menu-list">';
                foreach ($list['_child'] as $sub) {
                    // 递归调用
                    $html .= $generate($sub, $controller);
                }
                $html .= '</ul>';
            } else {
                // 添加控制器前缀
                if ($list['type'] == 1) {
                    // 控制器类型自动补充默认方法 index
                    $list['name'] = $controller . $list['name'] . "/index";
                } else {
                    $list['name'] = ($controller ? substr($controller, 0, -1) . "/" : "") . $list['name'];
                }
                $html .= '<a _href="' . url($list['name']) . '" data-title="' . $list['title'] . '" href="javascript:;">' . $list['title'] . '</a>';
            }
            $html .= "</li>";
        };

        $generate($list);

        return $html;
    }
}
