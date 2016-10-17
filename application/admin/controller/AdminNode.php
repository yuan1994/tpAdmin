<?php
// +----------------------------------------------------------------------
// | tpadmin [a web admin based ThinkPHP5]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 tianpian
// +----------------------------------------------------------------------
// | Author: tianpian <tianpian0805@gmail.com>
// +----------------------------------------------------------------------

//------------------------
// 节点控制器
//-------------------------

namespace app\admin\controller;

use app\admin\Controller;
use think\Db;
use think\Loader;

class AdminNode extends Controller
{
    use \app\admin\traits\controller\Controller;

    protected $blacklist = ['clear', 'deleteForever'];

    protected function filter(&$map)
    {
        if ($this->request->action() == 'index') {
            $map['pid'] = input("param.pid", 0);
        }

        if (input("param.title")) $map['title'] = ["like", "%" . input("param.title") . "%"];
        if (input("param.name")) $map['name'] = ["like", "%" . input("param.name") . "%"];
    }

    protected function _before_index()
    {
        $group_list = Loader::model('AdminGroup')->getList();
        $this->view->assign('group_list', reset_by_key($group_list, "id"));
    }

    protected function _before_recycleBin()
    {
        $group_list = Loader::model('AdminGroup')->getList();
        $this->view->assign('group_list', reset_by_key($group_list, "id"));
    }

    protected function _before_add()
    {
        //分组
        $group_list = Loader::model('AdminGroup')->getList();
        $this->view->assign('group_list', $group_list);

        //父节点和层级
        $node = Db::name("AdminNode")->where("id", input("param.pid/d"))->field("id,level")->find();
        $vo['pid'] = $node['id'];
        $vo['level'] = intval($node['level']) + 1;
        $this->view->assign('vo', $vo);
    }

    /**
     * 节点快速导入
     */
    public function load()
    {
        if ($this->request->isPost()) {
            $data = input("post.");
            $validate = Loader::validate("AdminNode");
            $insert_all = [];
            $error = [];
            $node_ids = isset($data['node']) ? $data['node'] : [];
            $node_name = isset($data['node_name']) ? $data['node_name'] : [];
            unset($data['node'], $data['node_name']);
            // 有选择模板
            if ($node_ids) {
                $nodes = Db::name("AdminNodeLoad")->where("id", "in", $node_ids)->field("title,name")->select();
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
            if ($node_name) {
                foreach ($node_name as $name) {
                    list($data['name'], $data['title']) = explode("###", $name);
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
            if ($error) {
                //拼接错误信息
                $errormsg = "部分节点导入失败：";
                foreach ($error as $err) {
                    $errormsg .= "<br>{$err['data']['title']}({$err['data']['name']})：{$err['error']}";
                }
                return ajax_return_adv_error($errormsg);
            }
            return ajax_return_adv("批量导入成功");
        } else {
            // 分组
            $group_list = Loader::model('AdminGroup')->getList();
            $this->view->assign('group_list', $group_list);

            // 父节点和层级
            $db_node = Db::name("AdminNode");
            $node = $db_node->where("id", input("param.pid/d"))->field("id,pid,name,level")->find();
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

    protected function _before_edit()
    {
        // 分组
        $group_list = Loader::model('AdminGroup')->getList();
        $this->view->assign('group_list', $group_list);
    }

    /**
     * 禁用限制
     */
    protected function _before_forbid()
    {
        // 禁止禁用 Admin 模块,权限设置节点
        $this->filterId([1, 2, 3, 4, 5, 6], '该记录不能被禁用');
    }

    /**
     * 删除限制
     */
    protected function _before_delete()
    {
        // 禁止删除 Admin 模块,权限设置节点
        $this->filterId([1, 2, 3, 4, 5, 6], '该节点不能被删除');
    }
}
