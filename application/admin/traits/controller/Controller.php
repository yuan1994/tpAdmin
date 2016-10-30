<?php
/**
 * tpAdmin [a web admin based ThinkPHP5]
 *
 * @author yuan1994 <tianpian0805@gmail.com>
 * @link http://tpadmin.yuan1994.com/
 * @copyright 2016 yuan1994 all rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
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

        //列表过滤器，生成查询Map对象
        $map = $this->search($model);
        if ($this::$isdelete !== false) {
            $map['isdelete'] = $this::$isdelete;
        }

        //自定义过滤器
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

        if ($this->request->isPost()) {
            //插入

            $data = $this->request->post();
            unset($data['id']);

            //验证
            if (class_exists("\\app\\admin\\validate\\{$controller}")) {
                $validate = Loader::validate($controller);
                if (!$validate->check($data)) {
                    return ajax_return_adv_error($validate->getError());
                }
            }

            //写入数据
            Db::startTrans();
            try {
                if (class_exists("\\app\\admin\\model\\{$controller}")) {
                    //使用模型写入，可以在模型中定义更高级的操作
                    $model = Loader::model($controller);
                    $ret = $model->save($data);
                } else {
                    //简单的直接使用db写入
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
            //添加
            return $this->view->fetch('edit');
        }
    }

    /**
     * 编辑
     * @return mixed
     */
    public function edit()
    {
        $controller = $this->request->controller();

        if ($this->request->isPost()) {
            //更新
            $data = $this->request->post();
            if (!$data['id']) {
                return ajax_return_adv_error("缺少参数ID");
            }

            //验证
            if (class_exists("\\app\\admin\\validate\\{$controller}")) {
                $validate = Loader::validate($controller);
                if (!$validate->check($data)) {
                    return ajax_return_adv_error($validate->getError());
                }
            }

            //更新数据
            Db::startTrans();
            try {
                if (class_exists("\\app\\admin\\model\\{$controller}")) {
                    //使用模型更新，可以在模型中定义更高级的操作
                    $model = Loader::model($controller);
                    $ret = $model->isUpdate(true)->save($data, ['id' => $data['id']]);
                } else {
                    //简单的直接使用db更新
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
        } else { //编辑
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
        return $this->update("isdelete", 1, "移动到回收站成功");
    }

    /**
     * 从回收站恢复
     */
    public function recycle()
    {
        return $this->update("isdelete", 0, "恢复成功");
    }

    /**
     * 默认禁用操作
     */
    public function forbid()
    {
        return $this->update("status", 0, "禁用成功");
    }


    /**
     * 默认恢复操作
     */
    public function resume()
    {
        return $this->update("status", 1, "恢复成功");
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
        if ($model->where($where)->delete() === false) {
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
        if ($model->where($where)->delete() === false) {
            return ajax_return_adv_error($model->getError());
        }

        return ajax_return_adv("清空回收站成功");
    }

    /**
     * 过滤禁止操作某些主键
     * @param $filterData
     * @param string $error
     * @param string $method
     * @param string $key
     */
    protected function filterId($filterData, $error = '该记录不能执行此操作', $method = 'in_array', $key = 'id')
    {
        $data = $this->request->param();
        if (!isset($data[$key])) {
            throw new Exception('缺少必要参数');
        }
        $ids = is_array($data[$key]) ? $data[$key] : explode(",", $data[$key]);
        foreach ($ids as $id) {
            switch ($method) {
                case '<':
                case 'lt':
                    $ret = $id < $filterData;
                    break;
                case '>':
                case 'gt':
                    $ret = $id < $filterData;
                    break;
                case '=':
                case 'eq':
                    $ret = $id == $filterData;
                    break;
                case '!=':
                case 'neq':
                    $ret = $id != $filterData;
                    break;
                default:
                    $ret = call_user_func_array($method, [$id, $filterData]);
                    break;
            }
            if ($ret) {
                throw new Exception($error);
            }
        }
    }

    /**
     * 根据表单生成查询条件
     * 进行列表过滤
     * @param object $model  数据对象
     * @param array $map     过滤条件 $map['_table']可强制设置表名前缀,$map['_relation']可强制设置关联模型预载入(需在模型里定义),$map['_field']可强制设置字段,$map['_order_by']可强制设置排序字段(field asc|desc[,filed2 asc|desc...]或者false)
     * @param string $field  查询的字段
     * @param string $sortBy 排序
     * @param boolean $asc   是否正序
     */
    protected function datalist($model, $map, $field = '*', $sortBy = '', $asc = false)
    {
        //排序字段 默认为主键名
        $order = $this->request->param('_order') ?: (empty($sortBy) ? $model->getPk() : $sortBy);

        //接受 sort参数 0 表示倒序 非0都 表示正序
        $sort = $this->request->param('_sort') !== '' ?
            ($this->request->param('_sort') == 'asc' ? 'asc' : 'desc') :
            ($asc ? 'asc' : 'desc');

        //每页数据数量
        $listRows = $this->request->param('numPerPage') ?: Config::get("paginate.list_rows");

        //设置关联预载入
        if (isset($map['_relation'])) {
            $model = $model::with($map['_relation']);
        }

        //设置字段
        if (isset($map['_field'])) {
            $field = $map['_field'];
        }

        //设置有$map['_controller']表示存在关联模型
        if (isset($map['_table'])) {
            //给排序字段强制加上表名前缀
            if (strpos($order, ".") === false) {
                $order = $map['_table'] . "." . $order;
            }
            //给字段强制加上表名前缀
            $_field = is_array($field) ? $field : explode(",", $field);
            foreach ($_field as &$v) {
                if (strpos($v, ".") === false) {
                    $v = preg_replace("/([^\s\(\)]*[a-z0-9\*])/", $map['_table'] . '.$1', $v, 1);
                }
            }
            $field = implode(",", $_field);
        }

        //设置排序字段
        //防止表无主键报错
        $order_by = $order ? "{$order} {$sort}" : false;
        if (isset($map['_order_by'])) {
            $order_by = $map['_order_by'];
        }

        //删除设置属性的字段
        unset($map['_table'], $map['_relation'], $map['_field'], $map['_order_by']);

        //分页查询
        $list = $model->field($field)->where($map)->order($order_by)->paginate($listRows, false, ['query' => $this->request->get()]);

        //模板赋值显示
        $this->view->assign('list', $list);
        $this->view->assign("page", $list->render());
        $this->view->assign("count", $list->total());
        $this->view->assign('numPerPage', $listRows);
    }
}
