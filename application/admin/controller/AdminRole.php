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
// 角色控制器
//-------------------------

namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path') , EXT);

use app\admin\Controller;
use think\Exception;
use think\Db;
use think\Loader;

class AdminRole extends Controller
{
    use \app\admin\traits\controller\Controller;

//    protected static $blacklist = ['recyclebin', 'delete', 'recycle', 'deleteforever', 'clear'];

    protected function filter(&$map)
    {
        if ($this->request->param('name')) {
            $map['name'] = ["like", "%" . $this->request->param('name') . "%"];
        }
    }

    /**
     * 用户列表
     */
    public function user()
    {
        $role_id = $this->request->param('id/d');
        if ($this->request->isPost()) {
            // 提交
            if (!$role_id) {
                return ajax_return_adv_error("缺少必要参数");
            }

            $db_role_user = Db::name("AdminRoleUser");
            //删除之前的角色绑定
            $db_role_user->where("role_id", $role_id)->delete();
            //写入新的角色绑定
            $data = $this->request->post();
            if (isset($data['user_id']) && !empty($data['user_id']) && is_array($data['user_id'])) {
                $insert_all = [];
                foreach ($data['user_id'] as $v) {
                    $insert_all[] = [
                        "role_id" => $role_id,
                        "user_id" => intval($v),
                    ];
                }
                $db_role_user->insertAll($insert_all);
            }
            return ajax_return_adv("分配角色成功", '');
        } else {
            // 编辑页
            if (!$role_id) {
                throw new Exception("缺少必要参数");
            }
            // 读取系统的用户列表
            $list_user = Db::name("AdminUser")->field('id,account,realname')->where('status=1 AND id > 1')->select();

            // 已授权权限
            $list_role_user = Db::name("AdminRoleUser")->where("role_id", $role_id)->select();
            $checks = filter_value($list_role_user, "user_id", true);

            $this->view->assign('list', $list_user);
            $this->view->assign('checks', $checks);

            return $this->view->fetch();
        }
    }

    /**
     * 授权
     * @return mixed
     */
    public function access()
    {
        $role_id = $this->request->param('id/d');
        if ($this->request->isPost()) {
            if (!$role_id) {
                return ajax_return_adv_error("缺少必要参数");
            }

            if (true !== $error = Loader::model('AdminAccess', 'logic')->insertAccess($role_id, $this->request->post())) {
                return ajax_return_adv_error($error);
            }
            return ajax_return_adv("权限分配成功", '');
        } else {
            if (!$role_id) {
                throw new Exception("缺少必要参数");
            }

            $tree = Loader::model('AdminRole', 'logic')->getAccessTree($role_id);
            $this->view->assign("tree", json_encode($tree));

            return $this->view->fetch();
        }
    }
}
