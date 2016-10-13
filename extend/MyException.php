<?php
// +----------------------------------------------------------------------
// | tpadmin [a web admin based ThinkPHP5]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 tianpian
// +----------------------------------------------------------------------
// | Author: tianpian <tianpian0805@gmail.com>
// +----------------------------------------------------------------------

//------------------------
// 自定义异常处理
//-------------------------

use think\exception\Handle;
use think\exception\HttpException;
class MyException extends Handle
{
    public function render(\Exception $e)
    {
        if ($e instanceof HttpException) {
            $statusCode = $e->getStatusCode();
            http_response_code($statusCode);
        }
        //可以在此交由系统处理
        if (request()->isAjax()){
            ajax_return_adv_error($this->getMessage($e));
        }
        return parent::render($e);
    }

}