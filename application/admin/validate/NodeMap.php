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
// 节点图验证器
//-------------------------

namespace app\admin\validate;

use think\Validate;
use think\Db;

class NodeMap extends Validate
{
    protected $rule = [
        "module|模块"      => "require",
        "map|节点图"        => "require|checkMap:1",
        "is_ajax|是否AJAX" => "require",
        "comment|备注"     => "require",
    ];

    protected function checkMap($value, $rule, $data)
    {
        if (isset($data['id']) && $data['id']) $where['id'] = ["neq", $data['id']];
        $where['module'] = $data['module'];
        $where['map'] = $data['map'];
        $where['is_ajax'] = $data['is_ajax'];

        return Db::name("NodeMap")->where($where)->find() ? "该节点图已经存在" : true;
    }
}
