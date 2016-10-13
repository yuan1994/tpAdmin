<?php
// +----------------------------------------------------------------------
// | tpadmin [a web admin based ThinkPHP5]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 tianpian
// +----------------------------------------------------------------------
// | Author: tianpian <tianpian0805@gmail.com>
// +----------------------------------------------------------------------

//------------------------
// tpadmin 公共控制器
//-------------------------

namespace app\admin;

use think\Session;

class Controller extends \think\Controller{
    protected $isdelete = 0; //是否删除标志，0-正常|1-删除|false-不包含该字段

    protected function _initialize(){
        // 用户权限检查
        if (config('rbac.user_auth_on' ) && !in_array(request()->module(),explode(',',config('rbac.not_auth_module')))) {
            if (! \Rbac::AccessDecision ()) {
                //检查认证识别号
                //未登录抛出错误并且跳转登录页
                if (!Session::has(config('rbac.user_auth_key'))) {
                    //跳转到认证网关
                    if (request()->isAjax()){
                        ajax_return_adv_error("登录超时，请先登陆","","","current",url("Common/loginFrame"));
                    } else {
                        if(strtolower(request()->controller()) == 'index' && strtolower(request()->action()) == 'index'){
                            $this->redirect(config('rbac.user_auth_gateway'));
                        } else {
                            echo '<script>if(window.parent.frames.length == 0) window.location = "'.url('Common/login').'?callback='.urlencode(request()->url(true)).'"; else parent.login("'.url('Common/loginFrame').'");</script>';
                        }
                    }
                    exit;
                }

                //已登录直接抛出错误
                if (request()->isAjax()){
                    ajax_return_adv_error('没有权限');
                } else {
                    exception("没有权限");
                }
            }

        }

        //前置方法
        $before_action = "_before_".request()->action();
        if(method_exists($this,$before_action)){
            $this->$before_action();
        }
    }
    /**
     * 首页
     * @return mixed
     */
    public function index() {
        $model = $this->_getModel();

        //列表过滤器，生成查询Map对象
        $map = $this->_search($model);
        if ($this->isdelete !== false){
            $map['isdelete'] = $this->isdelete;
        }

        if (method_exists ( $this, '_filter' )) {
            $this->_filter ( $map );
        }

        $this->_list ( $model, $map );
        return $this->fetch();
    }

    /**
     * 回收站
     * @return mixed
     */
    public function recyclebin(){
        $this->isdelete = 1;
        return $this->index();
    }

    /**
     * 自动搜索查询字段
     */
    protected function _search($model){
        //自动给模型字段过滤
        $map = [];
        $table_info = $model->getTableInfo();
        foreach (input("param.") as $key => $val){
            if ($val !== "" && in_array($key,$table_info['type'])) $map[$key] = $val;
        }
        return $map;
    }

    /**
     * 获取模型
     * @param string $controller
     * @return \think\db\Query|\think\Model
     */
    protected function _getModel($controller = ''){
        $controller = $this->_getRealController($controller);
        if (class_exists("\\app\\".request()->module()."\\model\\{$controller}")){
            return model($controller);
        } else {
            return db($controller);
        }
    }

    /**
    +----------------------------------------------------------
     * 根据表单生成查询条件
     * 进行列表过滤
    +----------------------------------------------------------
     * @access protected
    +----------------------------------------------------------
     * @param object $model 数据对象
     * @param array $map 过滤条件 $map['_table']可强制设置表名前缀,$map['_relation']可强制设置关联模型预载入(需在模型里定义),$map['_field']可强制设置字段,$map['_order_by']可强制设置排序字段(field asc|desc[,filed2 asc|desc...]或者false)
     * @param string $field 查询的字段
     * @param string $sortBy 排序
     * @param boolean $asc 是否正序
    +----------------------------------------------------------
     */
    protected function _list($model, $map, $field = '*',$sortBy = '', $asc = false) {
        //排序字段 默认为主键名
        $order = input('param._order') ?: (empty($sortBy) ? $model->getPk() : $sortBy);

        //接受 sort参数 0 表示倒序 非0都 表示正序
        $sort = input("param._sort") !== '' ? (input("param._sort") == 'asc' ? 'asc' : 'desc') : ($asc ? 'asc' : 'desc');

        //每页数据数量
        $listRows = input("param.numPerPage") ?: config("paginate.list_rows");

        //设置关联预载入
        if (isset($map['_relation'])){
            $model = $model::with($map['_relation']);
        }

        //设置字段
        if (isset($map['_field'])){
            $field = $map['_field'];
        }

        //设置有$map['_controller']表示存在关联模型
        if (isset($map['_table'])){
            //给排序字段强制加上表名前缀
            if (strpos($order,".")===false){
                $order = $map['_table'].".".$order;
            }
            //给字段强制加上表名前缀
            $_field = is_array($field) ? $field : explode(",",$field);
            foreach ($_field as &$v){
                if (strpos($v,".")===false){
                    $v = preg_replace("/([^\s\(\)]*[a-z0-9\*])/",$map['_table'].'.$1',$v,1);
                }
            }
            $field = implode(",",$_field);
        }

        //设置排序字段
        //防止表无主键报错
        $order_by = $order ? "{$order} {$sort}" : false;
        if (isset($map['_order_by'])){
            $order_by = $map['_order_by'];
        }

        //删除设置属性的字段
        unset($map['_table'],$map['_relation'],$map['_field'],$map['_order_by']);

        //分页查询
        $list = $model->field($field)->where($map)->order($order_by)->paginate($listRows,false,['query' => input("get.")]);

        //模板赋值显示
        $this->assign ( 'list', $list );
        $this->assign ( "page", $list->render() );
        $this->assign ( "count", $list->total() );
        $this->assign ( 'numPerPage', $listRows );
    }

    /**
     * 添加
     * @return mixed
     */
    public function add($template = "edit") {
        $module = request()->module();
        $controller = $this->_getRealController();

        if (request()->isPost()){ //插入
            $data = input("post.");
            unset($data['id']);

            //验证
            if (class_exists("\\app\\{$module}\\validate\\{$controller}")){
                $validate = validate($controller);
                if (!$validate->check($data)){
                    ajax_return_adv_error($validate->getError());
                }
            }
            //写入数据
            if (class_exists("\\app\\{$module}\\model\\{$controller}")){
                //使用模型写入，可以在模型中定义更高级的操作
                $model = model($controller);
                $ret = $model->save($data);
            } else {
                //简单的直接使用db写入
                $model = db($controller);
                $ret = $model->insert($data);
            }
            if (!$ret){
                ajax_return_adv_error($model->getError());
            }

            ajax_return_adv("添加成功");
        } else { //添加
            return $this->fetch($template);
        }
    }

    /**
     * 编辑
     * @return mixed
     */
    public function edit() {
        $module = request()->module();
        $controller = $this->_getRealController();

        if (request()->isPost()){ //更新
            $data = input("post.");
            if (!$data['id']){
                ajax_return_adv_error("缺少参数ID");
            }

            //验证
            if (class_exists("\\app\\{$module}\\validate\\{$controller}")){
                $validate = validate($controller);
                if (!$validate->check($data)){
                    ajax_return_adv_error($validate->getError());
                }
            }
            //更新数据
            if (class_exists("\\app\\{$module}\\model\\{$controller}")){
                //使用模型更新，可以在模型中定义更高级的操作
                $model = model($controller);
                $ret = $model->isUpdate(true)->save($data,['id'=>$data['id']]);
            } else {
                //简单的直接使用db更新
                $model = db($controller);
                $ret = $model->where('id',$data['id'])->update($data);
            }
            if ($ret === false){
                ajax_return_adv_error($model->getError());
            }

            ajax_return_adv("编辑成功");
        } else { //编辑
            $id = input("param.id");
            if (!$id){
                exception("缺少参数ID");
            }
            $vo = db($controller)->find($id);
            if (!$vo){
                exception("缺少参数ID");
            }

            $this->assign("vo",$vo);
            return $this->fetch();
        }
    }

    /**
     * 默认删除操作
     */
    public function delete() {
        $this->_update("isdelete", 1, $msg = "移动到回收站成功");
    }

    /**
     * 从回收站恢复
     */
    public function recycle() {
        $this->_update("isdelete", 0, $msg = "恢复成功");
    }

    /**
     * 默认禁用操作
     */
    public function forbid() {
        $this->_update("status", 0, $msg = "禁用成功");
    }


    /**
     * 默认恢复操作
     */
    public function resume() {
        $this->_update("status", 1, $msg = "恢复成功");
    }


    /**
     * 永久删除
     */
    public function deleteForever() {
        $model = $this->_getModel();
        $ids = input("id");
        $where["id"] = is_array($ids) ? array("in",explode(",",$ids)) : $ids;
        if ($model->where($where)->delete() === false){
            ajax_return_adv_error($model->getError());
        }
        ajax_return_adv("删除成功");
    }

    /**
     * 清空回收站
     */
    public function clear() {
        $model = $this->_getModel();
        $where["isdelete"] = 1;
        if ($model->where($where)->delete() === false){
            ajax_return_adv_error($model->getError());
        }
        ajax_return_adv("清空回收站成功");
    }

    /**
     * 默认更新字段方法
     * @param string $field 更新的字段
     * @param string|int $value 更新的值
     * @param string $msg 操作成功提示信息
     * @param string $pk 主键，默认为id
     * @param string $input 接收参数，默认为id
     */
    protected function _update($field, $value, $msg = "操作成功", $pk = "id", $input = "id"){
        $model = $this->_getModel();
        $ids = input($input);
        $where[$pk] = array("in",$ids);
        if ($model->where($where)->update([$field=>$value]) === false){
            ajax_return_adv_error($model->getError());
        }
        ajax_return_adv($msg);
    }

    /**
     * 过滤禁止操作某些主键
     * @param $filter_array
     * @param string $error_msg
     * @param string $key
     */
    protected function _filter_id($filter_array,$error_msg="该记录不能执行此操作",$key = "id"){
        $data = input("param.");
        if (!isset($data[$key])){
            ajax_return_adv_error("缺少必要参数");
        }
        $ids = is_array($data[$key]) ? $data[$key] : explode(",",$data[$key]);
        foreach ($ids as $id){
            if(in_array($id,$filter_array)){
                ajax_return_adv_error($error_msg);
            }
        }
    }

    /**
     * 获取实际的控制器名称(应用于多层控制器的场景)
     * @param $controller
     * @return mixed
     */
    protected function _getRealController($controller = ''){
        if (!$controller){
            $controllers = explode(".",request()->controller());
            $controller = array_pop($controllers);
        }
        return $controller;
    }
}