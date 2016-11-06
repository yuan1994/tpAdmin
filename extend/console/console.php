<?php
/**
 * tpAdmin [a web admin based ThinkPHP5]
 *
 * @author yuan1994 <tianpian0805@gmail.com>
 * @link http://tpadmin.yuan1994.com/
 * @copyright 2016 yuan1994 all rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace console;
// 加载基础文件
require __DIR__ . '/../../thinkphp/base.php';

use think\Console;
use think\App;
// 执行应用
App::initCommon();
$cmds = [
    "console\\command\\Generate",
    "console\\command\\Db",
];
Console::addDefaultCommands($cmds);
Console::init();