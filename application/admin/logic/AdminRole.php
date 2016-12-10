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

use think\Loader;
use think\Db;

class AdminRole
{
    /**
     * 生成权限树
     * @param $role_id
     * @return array
     */
    public function getAccessTree($role_id)
    {
        //分组信息
        $list_group = Loader::model('AdminGroup')->getList();
        $group = reset_by_key($list_group, "id");

        //节点信息
        $where_node['status'] = 1;
        $where_node['isdelete'] = 0;
        //对于非超级管理员用户只显示其拥有所有权限的节点
        if (!ADMIN) {
            $access_node = Db::name("AdminAccess")->alias("access")->join("__ADMIN_ROLE_USER__ role_user", "role_user.role_id = access.role_id")->field("access.node_id")->where("role_user.user_id", UID)->select();
            $where_node['id'] = ["in", filter_value($access_node, "node_id")];
        }
        $node = Db::name("AdminNode")->where("status=1 AND isdelete=0")->field("id,pid,group_id,name,title,level,type")->select();
        $accesses = Db::name("AdminAccess")->where("role_id", $role_id)->select();
        $accesses_node = filter_value($accesses, "node_id");

        //生成wdTree插件需要的数据格式
        $node_tree = [];
        foreach ($node as $v) {
            $node_tree[] = [
                "id"          => $v['id'],
                "pid"         => $v['pid'],
                "text"        => $v['title'] . " (" . $v['name'] . ") " . (isset($group[$v['group_id']]) ? '<span style="color:red">[ ' . $group[$v['group_id']]['name'] . ' ]</span>' : ''),
                "value"       => $v['id'] . "_" . $v['level'] . "_" . $v['pid'],
                "showcheck"   => true,
                'checkstate'  => in_array($v['id'], $accesses_node) ? 1 : 0,
                'hasChildren' => $v['type'] ? true : false,
                'isexpand'    => true,
                'complete'    => true,
            ];
        }

        //生成树
        return list_to_tree($node_tree, "id", "pid", "ChildNodes");
    }
}