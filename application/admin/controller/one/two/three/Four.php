<?php
namespace app\admin\controller\one\two\three;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path') , EXT);

use app\admin\Controller;

class Four extends Controller
{
    use \app\admin\traits\controller\Controller;
    // 方法黑名单
    protected static $blacklist = [];

    protected function filter(&$map)
    {
        if ($this->request->param("field1")) {
            $map['field1'] = ["like", "%" . $this->request->param("field1") . "%"];
        }
    }
}
