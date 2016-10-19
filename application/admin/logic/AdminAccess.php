<?php
// +----------------------------------------------------------------------
// | tpadmin [a web admin based ThinkPHP5]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 tianpian All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: tianpian <tianpian0805@gmail.com>
// +----------------------------------------------------------------------

namespace app\admin\logic;

use think\Db;
use think\Exception;

class AdminAccess
{
    /**
     * 写入权限
     * @param $role_id
     * @param $data
     * @return bool|string
     */
    public function insertAccess($role_id, $data)
    {
        Db::startTrans();
        try {
            $db_access = Db::name("AdminAccess");
            //删除之前的权限分配
            $db_access->where("role_id", $role_id)->delete();
            //写入新的权限分配
            if (isset($data['node_id']) && !empty($data['node_id']) && is_array($data['node_id'])) {
                $insert_all = [];
                foreach ($data['node_id'] as $v) {
                    $node = explode('_', $v);
                    if (!isset($node[2])) {
                        throw new Exception('参数错误');
                    }

                    $insert_all[] = [
                        "role_id" => $role_id,
                        "node_id" => $node[0],
                        "level"   => $node[1],
                        "pid"     => $node[2],
                    ];
                }
                $db_access->insertAll($insert_all);
            }

            // 提交事务
            Db::commit();

            return true;
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();

            return $e->getMessage();
        }

    }
}