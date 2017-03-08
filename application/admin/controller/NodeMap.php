<?php
/**
 * tpAdmin [a web admin based ThinkPHP5]
 *
 * @author yuan1994 <tianpian0805@gmail.com>
 * @link http://tpadmin.yuan1994.com/
 * @copyright 2016 yuan1994 all rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

//------------------------
// 节点图控制器
//-------------------------

namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path') , EXT);

use app\admin\Controller;
use think\Loader;
use app\admin\logic\NodeMap as LogicNodeMap;

class NodeMap extends Controller
{
    use \app\admin\traits\controller\Controller;

    protected static $isdelete = false;

    protected static $blacklist = ['resume', 'recycle', 'recycleBin', 'delete', 'forbid', 'clear'];

    protected function filter(&$map)
    {
        if ($this->request->param("map")) {
            $map['action|module|controller'] = ["like", "%" . $this->request->param("map") . "%"];
        }
        if ($this->request->param("comment")) {
            $map['comment'] = ["like", "%" . $this->request->param("comment") . "%"];
        }
    }

    /**
     * 自动导入
     */
    public function load()
    {
        $logicNodeMap = new LogicNodeMap();
        $logicNodeMap->load('admin', LogicNodeMap::METHOD_ALL, ['Ueditor', 'Generate', 'Error']);

        return ajax_return_adv('导入成功', 'current');
    }
}
