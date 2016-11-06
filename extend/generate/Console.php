<?php
/**
 * tpAdmin [a web admin based ThinkPHP5]
 *
 * @author yuan1994 <tianpian0805@gmail.com>
 * @link http://tpadmin.yuan1994.com/
 * @copyright 2016 yuan1994 all rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace generate;

use think\Console as ThinkConsole;

class Console2 extends ThinkConsole
{
    public function __construct($name = 'UNKNOWN', $version = 'UNKNOWN')
    {
        $cmds = $defaultCmds = [
            "generate\\console\\Test",
        ];
        parent::addDefaultCommands($cmds);
        parent::__construct($name, $version);
    }

    /**
     * 设置默认命令
     * @return Command[] An array of default Command instances
     */
    /*protected function getDefaultCommands()
    {
        $defaultCommands = [];

        foreach (self::$defaultCmd as $classname) {
            if (class_exists($classname) && is_subclass_of($classname, "think\\console\\Command")) {
                $defaultCommands[] = new $classname();
            }
        }

        return $defaultCommands;
    }*/

    /*public static function addDefaultCommands(array $classnames)
    {
        self::$defaultCmd = array_merge(self::$defaultCmd, $classnames);
    }*/
}