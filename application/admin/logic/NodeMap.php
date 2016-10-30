<?php
/**
 * tpAdmin [a web admin based ThinkPHP5]
 *
 * @author yuan1994 <tianpian0805@gmail.com>
 * @link http://tpadmin.yuan1994.com/
 * @copyright 2016 yuan1994 all rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace app\admin\logic;

use think\Db;

class NodeMap
{
    /**
     * 自动导入节点图
     * @param $module
     * @param array $filter
     * @return bool
     */
    public function load($module, $filter = [])
    {
        $mapExist = Db::name('NodeMap')->where('module = :module AND is_ajax = 0')->bind(['module' => $module])->field('map')->select();
        $maps = filter_value($mapExist, 'map');
        $classes = \ReadClass::readDir(APP_PATH . $module . '/controller', $filter, true);
        foreach ($classes as $class) {
            $insertMap = [];
            $controller = str_replace('\\', '.', str_replace('app\\' . $module . '\\controller\\', '', $class['class'])) . '/';
            foreach ($class['method'] as $method) {
                $map = $controller . $method['name'];
                if (!in_array($map, $maps)) {
                    $insertMap[] = [
                        'module'  => $module,
                        'map'     => $map,
                        'is_ajax' => 0,
                        'comment' => $class['class_name'] . ' ' . $method['title'],
                    ];
                }
            }
            $insertMap && Db::name('NodeMap')->insertAll($insertMap);
        }

        return true;
    }
}