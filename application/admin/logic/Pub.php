<?php
/**
 * tpAdmin [a web admin based ThinkPHP5]
 *
 * @author yuan1994 <tianpian0805@gmail.com>
 * @link http://tpadmin.yuan1994.com/
 * @copyright 2016 yuan1994 all rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace app\admin\logic;

use think\Config;
use think\exception\HttpResponseException;
use think\Request;
use think\Response;
use think\response\Redirect;
use think\Url;

class Pub
{
    /**
     * 未登录处理
     */
    public static function notLogin()
    {
        // 登录框地址
        $loginFrame = Config::get('site.login_frame');
        // 登录地址
        $loginUrl = Config::get('rbac.user_auth_gateway');
        if (Request::instance()->isAjax()) {
            $response = ajax_return_adv_error("登录超时，请先登陆", 400, "", "", false, "", Url::build($loginFrame));
            throw new HttpResponseException($response);
        } else {
            if (strtolower(Request::instance()->controller()) == 'index' && strtolower(Request::instance()->action()) == 'index') {
                throw new HttpResponseException(new Redirect($loginUrl));
            } else {
                // 判断是弹出登录框还是直接跳转到登录页
                $ret = '<script>' .
                    'if(window.parent.frames.length == 0) ' .
                    'window.location = "' . Url::build($loginUrl) . '?callback=' . urlencode(Request::instance()->url(true)) . '";' .
                    ' else ' .
                    'parent.login("' . Url::build($loginFrame) . '");' .
                    '</script>';
                throw new HttpResponseException(new Response($ret));
            }
        }
    }
}