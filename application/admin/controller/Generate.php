<?php
/**
 * tpAdmin [a web admin based ThinkPHP5]
 *
 * @author    yuan1994 <tianpian0805@gmail.com>
 * @link      http://tpadmin.yuan1994.com/
 * @copyright 2016 yuan1994 all rights reserved.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 */

//------------------------
// 自动生成代码
//-------------------------

namespace app\admin\controller;

use think\Config;
use think\Controller;
use think\Loader;
use think\Url;
use think\Db;

class Generate extends Controller
{
    /**
     * 首页
     * @return mixed
     */
    public function index()
    {
        $tables = Db::getTables();
        $this->view->assign('tables', $tables);
        if ($this->request->param('table')) {
            $table = $this->request->param('table');
            $prefix = Config::get('database.prefix');
            $tableInfo = Db::getTableInfo($table);
            $controller = Loader::parseName(preg_replace('/^(' . $prefix . ')/', '', $table), 1);

            $this->view->assign('table_info', json_encode($tableInfo));
            $this->view->assign('controller', $controller);
        }

        return $this->view->fetch();
    }

    /**
     * 模拟终端
     */
    public function cmd()
    {
        echo "<p style='color: green'>代码开始生成中……</p>\n";
        $config = explode(".", $this->request->param('config', 'generate'));
        $configFile = ROOT_PATH . $config[0] . '.php';
        if (!file_exists($configFile)) {
            echo "<p style='color: red;font-weight: bold'>配置文件不存在：{$configFile}</p>\n";
            exit();
        }

        $data = include $configFile;
        $generate = new \Generate();
        $generate->run($data, $this->request->param('file', 'all'));
        echo "<p style='color: green;font-weight: bold'>代码生成成功！</p>\n";
        exit();
    }

    /**
     * 生成代码
     */
    public function run()
    {
        $generate = new \Generate();
        $data = $this->request->post();
        unset($data['file']);
        $generate->run($data, $this->request->post('file'));

        if (isset($data['delete_file']) && $data['delete_file']) {
            return ajax_return_adv('删除成功', '', false, '', '', ['action' => '']);
        }
        return ajax_return_adv('生成成功', '', false, '', '', ['action' => Url::build($data['module'] . '/' . $data['controller'] . '/index')]);
    }
}
