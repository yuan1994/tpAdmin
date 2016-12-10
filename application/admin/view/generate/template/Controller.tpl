<?php
namespace app\[MODULE]\controller[NAMESPACE];

\think\Loader::import('controller/Controller', \think\Config::get('traits_path') , EXT);

use app\[MODULE]\Controller;

class [NAME] extends Controller
{
    use \app\admin\traits\controller\Controller;
    // 方法黑名单
    protected static $blacklist = [];

    [FILTER]
}
