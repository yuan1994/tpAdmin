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
// 登录日志控制器
//-------------------------

namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path') , EXT);

use app\admin\Controller;
use app\common\model\LoginLog as ModelLoginLog;
use app\common\model\AdminUser as ModelAdminUser;

class LoginLog extends Controller
{
    use \app\admin\traits\controller\Controller;

    protected static $isdelete = false; //禁用该字段

    protected static $blacklist = ['add', 'edit', 'delete', 'deleteforever', 'forbid', 'resume', 'recycle', 'recyclebin', 'clear'];

    protected function filter(&$map)
    {
        if ($this->request->param('login_location')) {
            $map['login_location'] = ["like", "%" . $this->request->param('login_location') . "%"];
        }

        // 关联筛选
        if ($this->request->param('account')) {
            $map['user.account'] = ["like", "%" . $this->request->param('account') . "%"];
        }
        if ($this->request->param('name')) {
            $map['user.realname'] = ["like", "%" . $this->request->param('name') . "%"];
        }

        // 设置属性
        $map['_table'] = "login_log";
        $map['_order_by'] = "login_log.id desc";
        $map['_func'] = function (ModelLoginLog $model) use ($map) {
            $model->alias($map['_table'])->join(ModelAdminUser::getTable() . ' user', 'login_log.uid = user.id');
        };
    }
}
