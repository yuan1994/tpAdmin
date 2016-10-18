<?php
// +----------------------------------------------------------------------
// | tpadmin [a web admin based ThinkPHP5]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 tianpian
// +----------------------------------------------------------------------
// | Author: tianpian <tianpian0805@gmail.com>
// +----------------------------------------------------------------------

//------------------------
// 节点模型
//-------------------------

namespace app\admin\model;

use think\Model;
use think\Db;
use think\Config;

class AdminNode extends Model
{
    /**
     * 首页列表生成菜单项
     */
    public function menu()
    {
        if (ADMIN) {
            $nodes = Db::name("AdminNode")->where("status=1 AND group_id > 0")->field("id,pid,name,group_id,title,type")->select();
        } else {
            $prefix = Config::get("database.prefix");
            $sql = "SELECT node.id,node.name,node.pid,node.group_id,node.title,node.type from " .
                "{$prefix}admin_role AS role," .
                "{$prefix}admin_role_user AS user," .
                "{$prefix}admin_access AS access ," .
                "{$prefix}admin_node AS node " .
                "WHERE user.user_id='" . UID . "' AND " .
                "user.role_id=role.id AND " .
                "access.role_id=role.id AND " .
                "role.status=1 AND " .
                "access.node_id=node.id AND " .
                "node.status=1 AND " .
                "node.group_id > 0 AND " .
                "(role.pid = 0 OR (role.pid <> 0 AND node.id IN (SELECT node_id FROM {$prefix}admin_access WHERE role_id = role.pid))) " .
                "ORDER BY node.sort ASC";
            $nodes = Db::query($sql);
        }

        return $nodes;
    }
}