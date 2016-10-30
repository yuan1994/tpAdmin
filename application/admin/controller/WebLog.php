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
// 网站操作日志
//-------------------------

namespace app\admin\controller;

use app\admin\Controller;
use think\Lang;
use think\Loader;
use think\Exception;

class WebLog extends Controller
{
    /**
     * 列表
     */
    public function index()
    {
        $model = Loader::model('WebLog');

        // 列表过滤器，生成查询Map对象
        $map = $this->search($model);

        // 过滤器
        if ($this->request->param('map')) $map['w.map'] = ['like', '%' . $this->request->param('map') . '%'];
        if ($this->request->param('comment')) $map['m.comment'] = ['like', '%' . $this->request->param('comment') . '%'];

        // 查询字段
        $field = 'w.id,w.data,w.otime,w.map,w.is_ajax,u.realname,m.comment';
        $listRows = 20;

        // 分页查询
        $list = $model->alias('w')->field($field)
            ->join([
                ['__ADMIN_USER__ u', 'u.id=w.uid', 'LEFT'],
                ['__NODE_MAP__ m', 'm.is_ajax=w.is_ajax AND m.module=w.module AND m.map=w.map', 'LEFT']
            ])
            ->where($map)->order('w.id desc')->paginate($listRows, false, ['query' => $this->request->get()]);

        // 数据处理
        foreach ($list as &$item) {
            if (null !== $item['comment']) {
                $comment = $item['comment'];
                $data = unserialize($item['data']);
                $data['__user__'] = $item['realname'];
                try {
                    $item['desc'] = Lang::get($comment, $data);
                } catch (Exception $e) {
                    $item['desc'] = '该节点图统计出错，有文件上传的稍后解决';
                }
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

    /**
     * 详情
     */
    public function detail()
    {
        $id = $this->request->param('id');
        if (!$id) {
            throw new Exception('缺少必要参数ID');
        }

        // 条件
        $map['w.id'] = $id;

        // 查询字段
        $field = 'w.*,account,realname,last_login_time,last_login_ip,login_count,comment';

        // 查询
        $item = Loader::model('WebLog')->alias('w')->field($field)
            ->join([
                ['__ADMIN_USER__ u', 'u.id=w.uid', 'LEFT'],
                ['__NODE_MAP__ m', 'm.is_ajax=w.is_ajax AND m.module=w.module AND m.map=w.map', 'LEFT']
            ])
            ->where($map)->find();

        // 数据处理
        if (null !== $item['comment']) {
            $comment = $item['comment'];
            $data = unserialize($item['data']);
            $data['__user__'] = $item['realname'];
            try {
                $item['desc'] = Lang::get($comment, $data);
            } catch (Exception $e) {
                $item['desc'] = '该节点图统计出错，有文件上传的稍后解决';
            }
        } else {
            $item['desc'] = '<span class="c-red">请先完善节点图</span>';
        }

        $this->view->assign('vo', $item);

        return $this->view->fetch();
    }
}