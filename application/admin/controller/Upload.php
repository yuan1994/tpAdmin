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

class Upload extends Controller
{
    public function index()
    {
        return $this->view->fetch();
    }

    public function upload()
    {
        $file = $this->request->file('file');
        $info = $file->move('./tmp/uploads');
        if ($info) {
            return ajax_return_adv($info->getSaveName());
        } else {
            return ajax_return_adv_error($file->getError());
        }
    }
}