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
// 节点控制器
//-------------------------

namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path'), EXT);

use app\admin\Controller;
use think\Db;
use think\Loader;

class AdminNode extends Controller
{
    use \app\admin\traits\controller\Controller {
        \app\admin\traits\controller\Controller::index as indexOld;
    }

//    protected static $blacklist = ['recyclebin', 'delete', 'deleteforever', 'clear', 'recycle'];

    protected function filter(&$map)
    {
        if ($this->request->action() == 'index') {
            $map['pid'] = $this->request->param('pid', 0);
        }

        if ($this->request->param('title')) {
            $map['title'] = ["like", "%" . $this->request->param('title') . "%"];
        }
        if ($this->request->param('name')) {
            $map['name'] = ["like", "%" . $this->request->param('name') . "%"];
        }
    }

    /**
     * 首页
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            try {
                $moduleId = $this->request->param('module_id');
                $groupId = $this->request->param('group_id');

                if ($this->request->param('type') == 'group') {
                    // 查询分组

                    // 查询二级节点下分组信息
                    $node = Db::name('AdminNode')
                        ->where("isdelete=0 AND level=2 AND pid='{$moduleId}'")
                        ->field('group_id')
                        ->select();
                    if (!$node) {
                        return ajax_return_error('该模块下没有任何节点');
                    }
                    // 分组下菜单个数
                    $groupId = [];
                    foreach ($node as $vo) {
                        if (isset($groupId[$vo['group_id']])) {
                            $groupId[$vo['group_id']] += 1;
                        } else {
                            $groupId[$vo['group_id']] = 1;
                        }
                    }

                    // 分组信息
                    $groupList = Db::name('AdminGroup')
                        ->alias('admin_group')
                        ->order('sort asc')
                        ->field('id,name,icon,sort,status')
                        ->where(['isdelete' => 0, 'id' => ['in', array_keys($groupId)]])
                        ->select();

                    return ajax_return(['count' => $groupId, 'list' => $groupList]);
                } else {
                    // 查询节点
                    $list = Db::field('*')
                        ->name('admin_node')
                        ->union(function($query){
                            $query->name('admin_node')->where('isdelete=0 AND level>2');
                        })
                        ->where("isdelete=0 AND level=2 AND pid='{$moduleId}' AND group_id='{$groupId}'")
                        ->select();
                    // 重新组装节点
                    $list2 = [];
                    foreach ($list as $vo) {
                        $list2[] = [ 'name' => '<span class="c-warning">[ ' . ($vo['level'] == 1 ? '模块' : ($vo['type'] ? '控制器' : '方法')) . ' ]</span> '
                            . $vo['title'] . " (" . $vo['name'] . ") "
                            . ' <a></a><span class="c-secondary">[ 层级：' . $vo['level'] . ' ]</span> '
                            . show_status($vo['status'], $vo['id'])
                            . ' <a class="label label-primary radius J_add" data-id="' . $vo['id'] . '" href="javascript:;" title="添加子节点">添加</a>' ,'id' => $vo['id'], 'pid' => $vo['pid']];
                    }
                    $node = list_to_tree($list2, 'id', 'pid', 'children', $moduleId);

                    return ajax_return(['list' => $node]);
                }
            } catch (\Exception $e) {
                return ajax_return_error($e->getMessage());
            }
        } else {
            // 模块
            $modules = Db::name('AdminNode')->order('sort asc')->where('pid=0 AND isdelete=0')->select();
            $this->view->assign('modules', $modules);
            $this->view->assign('node', '');

            return $this->view->fetch();
        }
    }


    /**
     * 首页
     */
    /*public function index()
    {
        $list = Db::name('AdminNode')->order('sort asc')->where('isdelete=0')->select();
        //分组信息
        $list_group = Loader::model('AdminGroup')->getList();
        $group = reset_by_key($list_group, "id");
        $node = [];
        foreach ($list as $vo) {
            $name = '<span class="c-warning">[ ' . ($vo['level'] == 1 ? '模块' : ($vo['type'] ? '控制器' : '方法')) . ' ]</span> '
                . $vo['title'] . " (" . $vo['name'] . ") "
                . (isset($group[$vo['group_id']]) ? '<span style="color:red"> [ ' . $group[$vo['group_id']]['name'] . ' ]</span>' : '')
                . ' <a></a><span class="c-secondary">[ 层级：' . $vo['level'] . ' ]</span> '
                . show_status($vo['status'], $vo['id'])
                . ' <a class="label label-primary radius J_add" data-id="' . $vo['id'] . '" href="javascript:;" title="添加子节点">添加</a>';
            $node[] = [
                'id'   => $vo['id'],
                'pId'  => $vo['pid'],
                'sort' => $vo['sort'],
                'name' => $name,
            ];
        }
        $this->view->assign('node', json_encode($node));
        $this->view->assign('count', count($list));

        return $this->view->fetch();
    }*/

    /**
     * 回收站
     */
    public function recycleBin()
    {
        $list_group = Loader::model('AdminGroup')->getList();
        $this->view->assign('group_list', reset_by_key($list_group, "id"));

        $this::$isdelete = 1;

        return $this->indexOld();
    }

    /**
     * 保存排序
     */
    public function sort()
    {
        $data = $this->request->only(['id', 'pid', 'sort', 'level']);
        Loader::model('AdminNode')->save($data, ['id' => $data['id']]);

        return ajax_return_adv('保存排序成功');
    }

    protected function beforeAdd()
    {
        //分组
        $group_list = Loader::model('AdminGroup')->getList();
        $this->view->assign('group_list', $group_list);

        //父节点和层级
        $node = Db::name("AdminNode")->where("id", $this->request->param('pid/d'))->field("id,level")->find();
        $vo['pid'] = $node['id'];
        $vo['level'] = intval($node['level']) + 1;
        $this->view->assign('vo', $vo);
    }

    protected function beforeEdit()
    {
        // 分组
        $group_list = Loader::model('AdminGroup')->getList();
        $this->view->assign('group_list', $group_list);
    }

    /**
     * 禁用限制
     */
    protected function beforeForbid()
    {
        // 禁止禁用 Admin 模块,权限设置节点
        $this->filterId([1, 2, 3, 4, 5, 6], '该记录不能被禁用');
    }

    /**
     * 删除限制
     */
    protected function beforeDelete()
    {
        // 禁止删除 Admin 模块,权限设置节点
        $this->filterId([1, 2, 3, 4, 5, 6], '该节点不能被删除');
    }

    /**
     * 删除限制
     */
    protected function beforeDeleteForever()
    {
        // 禁止删除 Admin 模块,权限设置节点
        $this->filterId([1, 2, 3, 4, 5, 6], '该节点不能被删除');
    }

    /**
     * 节点快速导入
     */
    public function load()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $node_template = isset($data['node']) ? $data['node'] : [];
            $node_detect = isset($data['node_name']) ? $data['node_name'] : [];
            unset($data['node'], $data['node_name']);

            $error = Loader::model('AdminNode', 'logic')->insertLoad($node_template, $node_detect, $data);

            if ($error) {
                //拼接错误信息
                $errormsg = "部分节点导入失败：";
                foreach ($error as $err) {
                    $errormsg .= "<br>{$err['data']['title']}({$err['data']['name']})：{$err['error']}";
                }
                $errormsg .= "<p class='c-red'>请手动刷新页面</p>";

                return ajax_return_adv('', '', $errormsg);
            }

            return ajax_return_adv("批量导入成功");
        } else {
            // 分组
            $group_list = Loader::model('AdminGroup')->getList();
            $this->view->assign('group_list', $group_list);

            // 父节点和层级
            $db_node = Db::name("AdminNode");
            $node = $db_node->where("id", $this->request->param('pid/d'))->field("id,pid,name,level")->find();
            $vo['pid'] = $node['id'];
            $vo['level'] = intval($node['level']) + 1;
            $this->view->assign('vo', $vo);

            // 模板库
            $node_template = Db::name("AdminNodeLoad")->field("id,name,title")->where("status=1")->select();
            $this->view->assign("node_template", $node_template);

            // 公共方法
            $node_public = \ReadClass::method("\\app\\admin\\Controller");
            $this->view->assign("node_public", $node_public ?: []);

            // 当前方法
            // 递归获取所有父级节点
            $parent_node = "";
            $pid = $node['pid'];
            while ($pid > 1) {
                if ($current_node = $db_node->where("id", $pid)->field("id,pid,name")->find()) {
                    $parent_node = "\\" . $current_node['name'] . $parent_node;
                    $pid = $current_node['pid'];
                } else {
                    break;
                }
            }
            // 方法生成
            $node_current_name = "\\app\\admin\\controller" . strtolower($parent_node) . "\\" .
                \think\Loader::parseName($node['name'], 1);
            $node_current = \ReadClass::method($node_current_name);
            $this->view->assign("node_current", $node_current ?: []);

            return $this->view->fetch();
        }
    }
}
