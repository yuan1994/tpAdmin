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
use think\Config;
use think\Loader;

class AdminNode
{
    /**
     * 首页列表生成菜单项
     */
    public function getMenu()
    {
        if (ADMIN) {
            $nodes = Db::name("AdminNode")->where("status=1 AND group_id > 0")->field("id,pid,name,group_id,title,type")->select();
        } else {
            $prefix = Config::get("database.prefix");
            $sql = "SELECT node.id,node.name,node.pid,node.group_id,node.title,node.type from "
                . "{$prefix}admin_role AS role,"
                . "{$prefix}admin_role_user AS user,"
                . "{$prefix}admin_access AS access ,"
                . "{$prefix}admin_node AS node "
                . "WHERE user.user_id='" . UID . "' "
                . "AND user.role_id=role.id "
                . "AND access.role_id=role.id "
                . "AND role.status=1 "
                . "AND access.node_id=node.id "
                . "AND node.status=1 "
                . "AND node.group_id > 0 "
                . "ORDER BY node.sort ASC";
            $nodes = Db::query($sql);
        }

        return $nodes;
    }

    /**
     * 插入批量导入的节点
     *
     * @param array $node_template 节点模板
     * @param array $node_detect   代码中探测到的节点
     * @param array $data          其他数据
     *
     * @return array 错误信息
     */
    public function insertLoad($node_template, $node_detect, $data)
    {
        $error = [];
        $insert_all = [];
        $validate = Loader::validate("AdminNode");

        // 有选择模板
        if ($node_template) {
            $nodes = Db::name("AdminNodeLoad")->where("id", "in", $node_template)->field("title,name")->select();
            foreach ($nodes as $node) {
                $insert = array_merge($data, $node);
                // 数据校验
                if (!$validate->check($insert)) {
                    $error[] = ["data" => $node, "error" => $validate->getError()];
                    continue;
                }
                $insert_all[] = $insert;
            }
        }
        // 有选择自动探测到的节点
        if ($node_detect) {
            foreach ($node_detect as $node) {
                list($data['name'], $data['title']) = explode("###", $node);
                // 数据校验
                if (!$validate->check($data)) {
                    $error[] = ["data" => $data, "error" => $validate->getError()];
                    continue;
                }
                $insert_all[] = $data;
            }
        }
        //TODO 对两种方式产生重复数据的校验
        if ($insert_all) {
            Db::name("AdminNode")->insertAll($insert_all);
        }

        return $error;
    }
}