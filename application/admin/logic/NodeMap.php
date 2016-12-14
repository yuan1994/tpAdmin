<?php
/**
 * tpAdmin [a web admin based ThinkPHP5]
 *
 * @author    yuan1994 <tianpian0805@gmail.com>
 * @link      http://tpadmin.yuan1994.com/
 * @copyright 2016 yuan1994 all rights reserved.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 */

namespace app\admin\logic;

use think\Db;

class NodeMap
{
    const METHOD_ALL = 'ALL';
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_DELETE = 'DELETE';
    const METHOD_PUT = 'PUT';

    /**
     * 自动导入节点图
     *
     * @param string $module
     * @param string $requestMethod
     * @param array  $filter
     *
     * @return bool
     */
    public function load($module, $requestMethod = self::METHOD_ALL, $filter = [])
    {
        // 已存在的节点图
        $mapExist = Db::name('NodeMap')
            ->where('module = :module AND method = :method')
            ->bind(['module' => $module, 'method' => $requestMethod])
            ->field('controller,action')
            ->select();
        $maps = [];
        foreach ($mapExist as $map) {
            $maps[$map['controller']][$map['action']] = 1;
        }
        // 读取指定模块的类
        $classes = \ReadClass::readDir(APP_PATH . $module . '/controller', $filter, true);
        foreach ($classes as $class) {
            $insertMap = [];
            $controller = str_replace('\\', '.', str_replace('app\\' . $module . '\\controller\\', '', $class['class']));
            foreach ($class['method'] as $method) {
                if (!isset($maps[$controller]) || !isset($maps[$controller][$method['name']])) {
                    $insertMap[] = [
                        'module'     => $module,
                        'controller' => $controller,
                        'action'     => $method['name'],
                        'method'     => $requestMethod,
                        'comment'    => $class['class_name'] . ' ' . $method['title'],
                    ];
                }
            }
            $insertMap && Db::name('NodeMap')->insertAll($insertMap);
        }

        return true;
    }
}
