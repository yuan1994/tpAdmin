<?php
/**
 * tpAdmin [a web admin based ThinkPHP5]
 *
 * @author yuan1994 <tianpian0805@gmail.com>
 * @link http://tpadmin.yuan1994.com/
 * @copyright 2016 yuan1994 all rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

return [
    // 验证码字符集合
    'codeSet'  => '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY',
    // 验证码字体大小(px)
    'fontSize' => 25,
    // 是否画混淆曲线
    'useCurve' => true,
    // 验证码图片高度
    'imageH'   => 30,
    // 验证码图片宽度
    'imageW'   => 100,
    // 验证码位数
    'length'   => 1,
    // 验证成功后是否重置
    'reset'    => true
];