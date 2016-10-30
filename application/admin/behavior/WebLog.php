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
// 网站日志
//-------------------------

namespace app\admin\behavior;

use think\Exception;
use think\Session;
use think\Config;
use think\Request;

class WebLog
{
    public function run(&$param)
    {
        // 屏蔽异常
        try {
            $request = Request::instance();
            $requestData = $request->param();
            // 截取一部分数据，避免数据太大导致存储出错，比如文章发布提交的数据
            foreach ($requestData as &$v) {
                if (is_string($v)) {
                    $v = mb_substr($v, 0, 200);
                }
            }
            $data = [
                'uid'      => Session::get(Config::get('rbac.user_auth_key')) ?: 0,
                'ip'       => $request->ip(),
                'location' => implode(' ', \Ip::find($request->ip())),
                'os'       => \Agent::getOs(),
                'browser'  => \Agent::getBroswer(),
                'url'      => $request->url(),
                'module'   => $request->module(),
                'map'      => $request->controller() . DS . $request->action(),
                'is_ajax'  => $request->isAjax() ? 1 : 0,
                'data'     => serialize($requestData),
                'otime'    => time(),
            ];
            \WebLog::instance()->record($data);
        } catch (Exception $e) {

        }
    }
}