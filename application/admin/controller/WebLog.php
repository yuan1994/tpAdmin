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

//------------------------
// 网站操作日志
//-------------------------

namespace app\admin\controller;

use app\admin\Controller;
use think\Lang;
use think\Loader;
use think\Exception;

class WebLog extends Controller
{
    public function index()
    {
        $model = Loader::model('WebLog');

        // 列表过滤器，生成查询Map对象
        $map = $this->search($model);

        // 自定义过滤器
        if (method_exists($this, 'filter')) {
            $this->filter($map);
        }

        // 查询字段
        $field = 'w.id,w.data,w.otime,w.map,u.realname,m.comment';
        $listRows = 20;

        // 分页查询
        $list = $model->alias('w')->field($field)
            ->join([
                ['__ADMIN_USER__ u', 'u.id=w.uid', 'LEFT'],
                ['__NODE_MAP__ m', 'm.is_ajax=w.is_ajax AND m.module=w.module AND m.map=w.map', 'LEFT']
            ])
            ->where($map)->paginate($listRows, false, ['query' => $this->request->get()]);

        // 数据处理
        foreach ($list as &$item) {
            if (null !== $item['comment']) {
                $comment = '{:__user__}' . $item['comment'];
                $data = unserialize($item['data']);
                $data['__user__'] = $item['realname'];
                $item['desc'] = Lang::get($comment, $data);
            } else {
                $item['desc'] = '<span class="c-red">请先完善节点图</span>';
            }
            unset($item['data']);
        }

        // 模板赋值显示
        $this->view->assign('list', $list);
        $this->view->assign("page", $list->render());
        $this->view->assign("count", $list->total());
        $this->view->assign('numPerPage', $listRows);

        return $this->view->fetch();
    }

    public function detail()
    {
        $id = $this->request->param('id');
        if (!$id) {
            throw new Exception('缺少必要参数ID');
        }


    }
}