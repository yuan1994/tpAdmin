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
// 读取类文件
//-------------------------

class ReadClass
{
    private static $errormsg;

    /**
     * 根据类名获取public方法
     * @param string $class 类名
     * @param bool $parents 是否获取父类方法，默认false
     * @return array|bool
     */
    public static function method($class, $parents = false)
    {
        if (!class_exists($class)) {
            self::$errormsg = $class . "类不存在";
            return false;
        }

        $reflection = new \ReflectionClass($class);
        $class_name = $reflection->name;
        $staticProperties = $reflection->getStaticProperties();
        // 黑名单方法
        $blacklist = isset($staticProperties['blacklist']) ? $staticProperties['blacklist'] : [];
        // 白名单方法
        $allowList = isset($staticProperties['allowList']) ? $staticProperties['allowList'] : [];
        $ret = [];
        //遍历public方法
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            if ($parents || (!$parents && $method->class == $class_name)) {
                if (substr($method->name, 0, 2) != '__' && !in_array(strtolower($method->name), $blacklist)) {
                    //根据phpDoc获取方法说明
                    $title = '';
                    $docComment = $method->getDocComment();
                    if ($docComment !== false) {
                        $docCommentArr = explode("\n", $docComment);
                        $comment = trim($docCommentArr[1]);
                        $title = trim(substr($comment, strpos($comment, '*') + 1));
                    }
                    $ret[] = ['name' => $method->name, 'title' => $title];
                }
            }
        }
        return $ret;
    }

    /**
     * 读取某个路径的类和方法
     * @param $dir
     * @param bool $parents
     * @return array|bool
     */
    public static function readFile($path, $parents = false)
    {
        if (!file_exists($path)) {
            self::$errormsg = $path . "文件不存在";
            return false;
        }
        $class = str_replace([realpath(APP_PATH), ".php", DS], [config("app_namespace"), "", "\\"], realpath($path));
        $method = self::method($class, $parents);
        if ($method) {
            $class_name = explode("\\", $class);
            return ["class" => $class, "class_name" => end($class_name), "method" => $method];
        }
        return false;
    }

    /**
     * 读取某个文件夹里所有的类与方法
     * @param $dir
     * @param array $filter
     * @param bool $parents
     * @return array|bool
     */
    public static function readDir($dir, $filter = [], $parents = false)
    {
        if (!is_dir($dir)) {
            self::$errormsg = $dir . "路径不存在";
            return false;
        }
        $file_list = self::listDir($dir, true);

        $ret = [];
        foreach ($file_list as $file) {
            $method = self::readFile($file, $parents);
            if ($method && !in_array($method['class_name'], $filter)) {
                $ret[$method['class_name']] = $method;
            }
        }
        return $ret;
    }

    /**
     * 列出某个目录下的文件
     * @param string $dir     目录
     * @param bool $recursion 是否递归
     * @return array
     */
    public static function listDir($dir, $recursion = true)
    {
        $dirInfo = [];
        if (is_dir($dir)) {
            foreach (glob($dir . DS . '*') as $v) {
                if ($recursion && is_dir($v)) {
                    $dirInfo = array_merge($dirInfo, self::listDir($v));
                } else {
                    $dirInfo[] = $v;
                }

            }
        }
        return $dirInfo;
    }

    /**
     * 读取错误信息
     * @return mixed
     */
    public static function getError()
    {
        return self::$errormsg;
    }
}