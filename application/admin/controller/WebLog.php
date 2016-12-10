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
// 网站操作日志
//-------------------------

namespace app\admin\controller;

use app\admin\Controller;
use think\Loader;
use think\Exception;
use app\common\model\WebLog as ModelWebLog;

class WebLog extends Controller
{
    /**
     * 列表
     */
    public function index()
    {
        $model = new ModelWebLog();

        // 列表过滤器，生成查询Map对象
        $map = [];

        // 过滤器
        if ($this->request->param('controller')) {
            $map['w.controller'] = ['like', '%' . $this->request->param('controller') . '%'];
        }
        if ($this->request->param('action')) {
            $map['w.action'] = ['like', '%' . $this->request->param('action') . '%'];
        }
        if ($this->request->param('comment')) {
            $map['m.comment'] = ['like', '%' . $this->request->param('comment') . '%'];
        }

        // 查询字段
        $field = 'w.id,w.data,w.create_at,w.module,w.controller,w.action,w.method,u.realname,m.comment,md.comment as default_comment';
        $listRows = 20;

        // 分页查询
        $list = $model->alias('w')->field($field)
            ->join([
                ['__ADMIN_USER__ u', 'u.id=w.uid', 'LEFT'],
                ['__NODE_MAP__ m', 'm.method=w.method AND m.module=w.module AND m.controller=w.controller AND m.action=w.action', 'LEFT'],
                ['__NODE_MAP__ md', 'md.method="ALL" AND md.module=w.module AND md.controller=w.controller AND md.action=w.action', 'LEFT'],
            ])
            ->where($map)->order('w.id desc')->paginate($listRows, false, ['query' => $this->request->get()]);

        // 数据处理
        foreach ($list as &$item) {
            if ($item['comment']) {
                $data = unserialize($item['data']);
                $data['__user__'] = $item['realname'];
                $item['desc'] = $this->parseComment($item['comment'], $data);
            } elseif ($item['default_comment']) {
                $data = unserialize($item['data']);
                $data['__user__'] = $item['realname'];
                $item['desc'] = $this->parseComment($item['default_comment'], $data);
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
        $field = 'w.*,u.account,u.realname,u.last_login_time,u.last_login_ip,u.login_count,m.comment,md.comment as default_comment';

        // 查询
        $item = Loader::model('WebLog')->alias('w')->field($field)
            ->join([
                ['__ADMIN_USER__ u', 'u.id=w.uid', 'LEFT'],
                ['__NODE_MAP__ m', 'm.method=w.method AND m.module=w.module AND m.controller=w.controller AND m.action=w.action', 'LEFT'],
                ['__NODE_MAP__ md', 'md.method="ALL" AND md.module=w.module AND md.controller=w.controller AND md.action=w.action', 'LEFT'],
            ])
            ->where($map)->find();

        // 数据处理
        if ($item['comment']) {
            $data = unserialize($item['data']);
            $data['__user__'] = $item['realname'];
            $item['desc'] = $this->parseComment($item['comment'], $data);
        } elseif ($item['default_comment']) {
            $data = unserialize($item['data']);
            $data['__user__'] = $item['realname'];
            $item['desc'] = $this->parseComment($item['default_comment'], $data);
        } else {
            $item['desc'] = '<span class="c-red">请先完善节点图</span>';
        }

        $this->view->assign('vo', $item);

        return $this->view->fetch();
    }


    /**
     * 格式化输出节点描述
     *
     * @param string $comment
     * @param array  $data
     *
     * @return array
     */
    private function parseComment($comment, array $data)
    {
        // 匿名函数递归，将多维数组转为二维数组
        $func = function (&$data) use (&$func) {
            foreach ($data as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $subK => $subV) {
                        $data[$k . '.' . $subK] = $subV;
                    }
                    unset($data[$k]);
                    $func($v);
                }
            }
        };
        $func($data);

        // 取出键值转为占位符
        $replace = array_keys($data);
        foreach ($replace as &$v) {
            $v = "{{$v}}";
        }

        return str_replace($replace, $data, $comment);
    }
}
