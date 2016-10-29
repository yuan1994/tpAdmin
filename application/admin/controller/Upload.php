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

namespace app\admin\controller;

use app\admin\Controller;
use think\Db;

class Upload extends Controller
{
    /**
     * 首页
     */
    public function index()
    {
        return $this->view->fetch();
    }

    /**
     * 文件上传
     */
    public function upload()
    {
        $files = $this->request->file('file');
        $insert = [];
        foreach ($files as $file) {
            $path = ROOT_PATH . 'public/tmp/uploads/';
            $info = $file->move($path);
            if ($info) {
                $data[] = $this->request->root() . '/tmp/uploads/' . $info->getSaveName();
                $insert[] = [
                    'cate'     => 3,
                    'name'     => $data[] = $this->request->root() . '/tmp/uploads/' . $info->getSaveName(),
                    'original' => $info->getInfo('name'),
                    'domain'   => '',
                    'type'     => $info->getInfo('type'),
                    'size'     => $info->getInfo('size'),
                    'mtime'    => time(),
                ];
            } else {
                $error[] = $file->getError();
            }
        }
        Db::name('File')->insertAll($insert);

        return ajax_return($data);
    }

    /**
     * 远程图片抓取
     */
    public function remote()
    {
        $url = $this->request->post('url');
        // validate
        $name = ROOT_PATH . 'public/tmp/uploads/' . get_random();
        $name = \File::downloadImage($url, $name);

        $ret = $this->request->root() . '/tmp/uploads/' . basename($name);

        return ajax_return(['url' => $ret], '抓取成功');
    }

    /**
     * 图片列表
     */
    public function listImage()
    {
        $page = $this->request->param('p', 1);
        if ($this->request->param('count')) {
            $ret['count'] = Db::name('File')->where('cate=3')->count();
        }
        $ret['list'] = Db::name('File')->where('cate=3')->field('id,name,original')->page($page, 10)->select();

        return ajax_return($ret);
    }
}