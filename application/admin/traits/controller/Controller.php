<?php
/**
 * tpAdmin [a web admin based ThinkPHP5]
 *
 * @author    yuan1994 <tianpian0805@gmail.com>
 * @link      http://tpadmin.yuan1994.com/
 * @copyright 2016 yuan1994 all rights reserved.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 */

namespace app\admin\traits\controller;

use think\Exception;
use think\Db;
use think\Loader;
use think\exception\HttpException;
use think\Config;

trait Controller
{
    /**
     * 首页
     * @return mixed
     */
    public function index()
    {
        $model = $this->getModel();

        // 列表过滤器，生成查询Map对象
        $map = $this->search($model);
        if ($this::$isdelete !== false) {
            $map['isdelete'] = $this::$isdelete;
        }

        // 特殊过滤器，后缀是方法名的
        $actionFilter = 'filter' . $this->request->action();
        if (method_exists($this, $actionFilter)) {
            $this->$actionFilter($map);
        }

        // 自定义过滤器
        if (method_exists($this, 'filter')) {
            $this->filter($map);
        }

        $this->datalist($model, $map);

        return $this->view->fetch();
    }

    /**
     * 回收站
     * @return mixed
     */
    public function recycleBin()
    {
        $this::$isdelete = 1;

        return $this->index();
    }

    /**
     * 添加
     * @return mixed
     */
    public function add()
    {
        $controller = $this->request->controller();
        $module = $this->request->module();

        if ($this->request->isAjax()) {
            // 插入

            $data = $this->request->post();
            unset($data['id']);

            // 验证
            if (class_exists(Loader::parseClass($module, 'validate', $controller))) {
                $validate = Loader::validate($controller);
                if (!$validate->check($data)) {
                    return ajax_return_adv_error($validate->getError());
                }
            }

            // 写入数据
            Db::startTrans();
            try {
                if (class_exists(Loader::parseClass($module, 'model', $controller))) {
                    //使用模型写入，可以在模型中定义更高级的操作
                    $model = Loader::model($controller);
                    $ret = $model->save($data);
                } else {
                    // 简单的直接使用db写入
                    $model = Db::name($this->parseTable($controller));
                    $ret = $model->insert($data);
                }
                // 提交事务
                Db::commit();

                return ajax_return_adv('添加成功');
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();

                return ajax_return_adv_error($e->getMessage());
            }
        } else {
            // 添加
            return $this->view->fetch(isset($this->template) ? $this->template : 'edit');
        }
    }

    /**
     * 编辑
     * @return mixed
     */
    public function edit()
    {
        $controller = $this->request->controller();
        $module = $this->request->module();

        if ($this->request->isAjax()) {
            // 更新
            $data = $this->request->post();
            if (!$data['id']) {
                return ajax_return_adv_error("缺少参数ID");
            }

            // 验证
            if (class_exists(Loader::parseClass($module, 'validate', $controller))) {
                $validate = Loader::validate($controller);
                if (!$validate->check($data)) {
                    return ajax_return_adv_error($validate->getError());
                }
            }

            // 更新数据
            Db::startTrans();
            try {
                if (class_exists(Loader::parseClass($module, 'model', $controller))) {
                    // 使用模型更新，可以在模型中定义更高级的操作
                    $model = Loader::model($controller);
                    $ret = $model->isUpdate(true)->save($data, ['id' => $data['id']]);
                } else {
                    // 简单的直接使用db更新
                    $model = Db::name($this->parseTable($controller));
                    $ret = $model->where('id', $data['id'])->update($data);
                }
                // 提交事务
                Db::commit();

                return ajax_return_adv("编辑成功");
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();

                return ajax_return_adv_error($e->getMessage());
            }
        } else {
            // 编辑
            $id = $this->request->param('id');
            if (!$id) {
                throw new Exception("缺少参数ID");
            }
            $vo = $this->getModel($controller)->find($id);
            if (!$vo) {
                throw new HttpException(404, '该记录不存在');
            }

            $this->view->assign("vo", $vo);

            return $this->view->fetch();
        }
    }

    /**
     * 默认删除操作
     */
    public function delete()
    {
        return $this->updateField("isdelete", 1, "移动到回收站成功");
    }

    /**
     * 从回收站恢复
     */
    public function recycle()
    {
        return $this->updateField("isdelete", 0, "恢复成功");
    }

    /**
     * 默认禁用操作
     */
    public function forbid()
    {
        return $this->updateField("status", 0, "禁用成功");
    }


    /**
     * 默认恢复操作
     */
    public function resume()
    {
        return $this->updateField("status", 1, "恢复成功");
    }


    /**
     * 永久删除
     */
    public function deleteForever()
    {
        $model = $this->getModel();
        $pk = $model->getPk();
        $ids = $this->request->param($pk);
        $where[$pk] = ["in", $ids];
        if (false === $model->where($where)->delete()) {
            return ajax_return_adv_error($model->getError());
        }

        return ajax_return_adv("删除成功");
    }

    /**
     * 清空回收站
     */
    public function clear()
    {
        $model = $this->getModel();
        $where["isdelete"] = 1;
        if (false === $model->where($where)->delete()) {
            return ajax_return_adv_error($model->getError());
        }

        return ajax_return_adv("清空回收站成功");
    }
}
