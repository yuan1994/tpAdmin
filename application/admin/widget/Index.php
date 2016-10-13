<?php
// +----------------------------------------------------------------------
// | tpadmin [a web admin based ThinkPHP5]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 tianpian
// +----------------------------------------------------------------------
// | Author: tianpian <tianpian0805@gmail.com>
// +----------------------------------------------------------------------

//------------------------
// Widget组件
//-------------------------

namespace app\admin\widget;

class Index{
    private $menu_html;

    /**
     * 生成无限级授权菜单
     * @param $list
     * @return string
     */
    public function menu($list){
        $this->menu_html = "";
        $this->menu_html($list);
        return $this->menu_html;
    }

    /**
     * 生成无限级授权菜单
     * @param $list
     * @param string $controller
     */
    private function menu_html($list,$controller = ""){
        $this->menu_html .= "<li>";
        if (isset($list['_child'])){
            $controller .= $list['name'].".";
            $this->menu_html .= '<a class="sub-menu-title" data-title="'.$list['title'].'" href="javascript:;"><b>+</b>'.$list['title'].'</a>';
            $this->menu_html .= '<ul class="sub-menu-list">';
            foreach ($list['_child'] as $sub){
                $this->menu_html .= $this->menu_html($sub,$controller);
            }
            $this->menu_html .= '</ul>';
        } else {
            //添加控制器前缀
            if ($list['type']==1){
                //控制器类型自动补充默认方法index
                $list['name'] = $controller.$list['name']."/index";
            } else {
                $list['name'] = ($controller?substr($controller,0,-1)."/":"").$list['name'];
            }
            $this->menu_html .= '<a _href="'.url($list['name']).'" data-title="'.$list['title'].'" href="javascript:;">'.$list['title'].'</a>';
        }
        $this->menu_html .= "</li>";
    }
}