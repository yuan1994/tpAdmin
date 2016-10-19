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

//------------------------
// 自动生成代码模型
//-------------------------
// 请不要随便删除代码中拼接字符故意留下的空格，否则生成的代码将会出现缩进错乱
// 请不要删除 generate 视图文件夹中 template 文件夹下文件扩展名为 .tpl 的文件，否则会导致代码执行错误
//
// build 方法未对异常进行处理，如实际使用中发现错误，请打开控制台查错
//-------------------------

namespace app\admin\model;

use think\Exception;
use think\Log;
use think\Request;
use think\Config;
use think\Db;
use think\Loader;

class Generate
{
    private $module;
    private $name;
    private $dir;
    private $namespace;
    private $nameLower;
    private $post;
    // 控制器黑名单
    private $blacklist_name = ['AdminGroup', 'AdminNode', 'AdminRole', 'AdminUser', 'AdminNodeLoad', 'Pub', 'Demo', 'Generate', 'Index', 'LogLogin', 'Ueditor'];
    // 数据表黑名单
    private $blacklist_table = ['admin_group', 'admin_node', 'admin_role', 'admin_user', 'admin_node_load', 'log_login', 'file'];

    public function build()
    {
        $request = Request::instance();
        $this->post = $request->post();
        $this->module = $request->module();
        $controllers = explode(".", $this->post['controller_name']);
        $this->name = array_pop($controllers);
        $this->nameLower = Loader::parseName($this->name);

        // 分级控制器目录和命名空间
        if ($controllers) {
            $this->dir = strtolower(implode(DS, $controllers) . DS);
            $this->namespace = strtolower(implode("\\", $controllers));
        } else {
            $this->dir = "";
            $this->namespace = "";
        }

        //数据表表名
        $tableName = str_replace(DS, '_', $this->dir) . $this->nameLower;

        //判断是否在黑名单中
        if (in_array($this->post['controller_name'], $this->blacklist_name)) {
            throw new Exception('该控制器不允许创建');
        }

        //判断是否在数据表黑名单中
        if (isset($this->post['table']) && $this->post['table'] && in_array($tableName, $this->blacklist_table)) {
            throw new Exception('该数据表不允许创建');
        }

        //创建目录
        $dir_list = ["view" . DS . $this->dir . $this->nameLower];
        if (isset($this->post['model']) && $this->post['model']) {
            array_push($dir_list, "model" . DS . $this->dir);
        }
        if (isset($this->post['validate']) && $this->post['validate']) {
            array_push($dir_list, "validate" . DS . $this->dir);
        }
        if ($this->dir) {
            array_push($dir_list, "controller" . DS . $this->dir);
        }
        $this->buildDir($dir_list);

        //创建文件
        $pathView = APP_PATH . $this->module . DS . "view" . DS . $this->dir . $this->nameLower . DS;
        $pathTemplate = APP_PATH . $this->module . DS . "view" . DS . "generate" . DS . "template" . DS;
        $fileName = APP_PATH . $this->module . DS . "%NAME%" . DS . $this->dir . $this->name . ".php";
        $this->buildForm($pathView);
        $this->buildTh($pathView);
        $this->buildTd($pathView);
        $this->buildRecyclebin($pathView);
        $this->buildIndex($pathView);
        $this->buildEdit($pathView, $pathTemplate);
        $this->buildController($fileName, $pathTemplate);
        $this->buildModel($fileName, $pathTemplate);
        $this->buildValidate($fileName, $pathTemplate);
        $this->buildTable($tableName);
    }

    /**
     * 检查当前模块目录是否可写
     * @return bool
     */
    public static function checkWritable()
    {
        try {
            $lockfile = APP_PATH . Request::instance()->module() . DS . "bulid.test";
            if (!file_put_contents($lockfile, "test")) {
                return false;
            }
            // 解除锁定
            unlink($lockfile);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 创建目录
     * @param $dir_list
     */
    private function buildDir($dir_list)
    {
        foreach ($dir_list as $dir) {
            $path = APP_PATH . $this->module . DS . $dir;
            if (!is_dir($path)) {
                // 创建目录
                mkdir($path, 0755, true);
            }
        }
    }

    /**
     * 创建 form.html 文件
     * @return int
     */
    private function buildForm($path)
    {
        $el = "";
        foreach ($this->post['form_search'] as $k => $v) {
            //只筛选出选择为表单筛选的字段
            if ($v) {
                $el .= '    <input type="text" class="input-text" style="width:250px" placeholder="' . $this->post['form_title'][$k] . '" name="' . $this->post['form_name'][$k] . '" value="{:\\\\think\\\\Request::instance()->param(\'' . $this->post['form_name'][$k] . '\')}">' . "\n";
            }
        }
        if ($el) {
            $el .= '    <button type="submit" class="btn btn-success"><i class="Hui-iconfont">&#xe665;</i> 搜索</button>' . "\n";
        }
        $content = '<form class="mb-20" method="get" action="{:\\\\think\\\\Url::build(\\\\think\\\\Request::instance()->action())}">' . "\n" . $el . '</form>';

        $file = $path . "form.html";

        return file_put_contents($file, $content);
    }

    /**
     * 创建form.html文件
     * @return int
     */
    private function buildTh($path)
    {
        $el = ['<th width="25"><input type="checkbox"></th>'];
        foreach ($this->post['form_title'] as $k => $v) {
            if ($this->post['form_sort'][$k]) { //带有表单排序的需使用表单排序方法
                array_push($el, '<th width="">' . "{:sort_by('{$v}','{$this->post['form_name'][$k]}')}" . '</th>');
            } else {
                array_push($el, '<th width="">' . $v . '</th>');
            }
        }
        $content = implode("\n", $el);
        $file = $path . "th.html";

        return file_put_contents($file, $content);
    }

    /**
     * 创建td.html文件
     * @return int
     */
    private function buildTd($path)
    {
        $el = ['<td><input type="checkbox" name="id[]" value="{$vo.id}"></td>'];
        foreach ($this->post['form_name'] as $k => $v) {
            if ($this->post['form_search'][$k]) { //带有表单搜索筛选的自动添加关键词高亮方法
                array_push($el, '<td>{$vo.' . $v . "|high_light=\\\\think\\\\Request::instance()->param('" . $v . "')}</td>");
            } else { //对于status字段采用框架提供的get_status方法显示图标
                array_push($el, '<td>{$vo.' . $v . ($v == "status" ? '|get_status' : '') . '}</td>');
            }
        }
        $content = implode("\n", $el);
        $file = $path . "td.html";

        return file_put_contents($file, $content);
    }

    /**
     * 创建 recyclebin.html 文件
     */
    private function buildRecyclebin($path)
    {
        //首页菜单选择了回收站才创建回收站
        if (isset($this->post['menu']) && in_array("recycleBin", $this->post['menu'])) {
            $file = $path . "recyclebin.html";

            //默认直接继承模板
            return file_put_contents($file, '{extend name="template/recyclebin" /}');
        }

        return true;
    }

    /**
     * 创建 index.html 文件
     * @return int
     */
    private function buildIndex($path)
    {
        $file = $path . "index.html";
        //菜单全选的默认直接继承模板
        $this->post['menu'] = isset($this->post['menu']) ? $this->post['menu'] : [];
        if (count($this->post['menu']) == 5) {
            return file_put_contents($file, '{extend name="template/index" /}');
        } else {
            //菜单部分选择的使用模板替换相关参数创建自定义html文件
            $template = file_get_contents(APP_PATH . $this->module . DS . "view" . DS . "template" . DS . "index.html");

            return file_put_contents($file, str_replace('table_menu"', 'table_menu" menu="' . implode(",", $this->post['menu']) . '"', $template));
        }
    }

    /**
     * 创建 edit.html 文件
     * @return int
     */
    private function buildEdit($path, $pathTemplate)
    {
        $el = "";
        $set_checked = [];//radio类型的表单控件编辑状态使用javascript赋值
        $set_selected = [];//select类型的表单控件编辑状态使用javascript赋值
        foreach ($this->post['form_title'] as $k => $v) {
            $type = $this->post['form_type'][$k];
            $name = $this->post['form_name'][$k];

            //像id这种白名单字段不需要自动生成到编辑页
            if (in_array($name, ["id", "create_time", "update_time", "isdelete"])) continue;

            //str_repeat重复输出空格和每行末加换行符是为了生成的模板有缩进，方便二次编辑
            $el .= str_repeat(' ', 8) . '<div class="row cl">' . "\n" .
                str_repeat(' ', 12) . '<label class="form-label col-xs-3 col-sm-3">' .
                ($this->post['form_require'][$k] ? '<span class="c-red">*</span>' : '') .
                $v . '：</label>' . "\n";

            //使用Validform插件前端验证数据格式，生成在表单控件上的验证规则
            $validate = ($this->post['form_validate'][$k] ?
                    ' datatype="' . $this->post['form_validate'][$k] . '"' :
                    '') .
                ($this->post['form_validate_null'][$k] ?
                    ' nullmsg="' . $this->post['form_validate_null'][$k] . '"' :
                    '') .
                ($this->post['form_validate_error'][$k] ?
                    ' errormsg="' . $this->post['form_validate_error'][$k] . '"' :
                    '') .
                (!$this->post['form_require'][$k] && $this->post['form_validate'][$k] ?
                    ' ignore="ignore"' :
                    '');

            //注意checkbox,radio类型控件需要加.skin-minimal，方便iCheck插件对控件进行美化
            $el .= str_repeat(' ', 12) . '<div class="formControls col-xs-6 col-sm-6' .
                (in_array($type, ['radio', 'checkbox']) ? ' skin-minimal' : '') .
                '">' . "\n";
            switch ($type) {
                case "radio":
                case "checkbox":
                    //只对radio类型的控件进行编辑状态赋值，checkbox类型控件请自行根据情况赋值
                    if ($type == "radio") {
                        array_push($set_checked, str_repeat(' ', 8) . '$("[name=\'' . $name . '\'][value=\'{:isset($vo.' . $name . ')?$vo.' . $name . ':\'\'}\']").attr("checked",true);');
                    }
                    //默认只生成一个空的示例控件，请根据情况自行复制编辑
                    $el .= str_repeat(' ', 16) . '<div class="radio-box">' . "\n";
                    $el .= str_repeat(' ', 20) . '<input type="' . $type . '" name="' . $name . ($type == "checkbox" ? '[]' : '') . '" id="' . $name . '-0" value=""' . $validate . '>' . "\n";
                    $el .= str_repeat(' ', 20) . '<label for="' . $name . '-0">选项一</label>' . "\n";
                    $el .= str_repeat(' ', 16) . '</div>' . "\n";
                    break;
                case "select":
                    //select类型的控件进行编辑状态赋值
                    array_push($set_selected, str_repeat(' ', 8) . '$("[name=\'' . $name . '\']").find("[value=\'{:isset($vo.' . $name . ')?$vo.' . $name . ':\'\'}\']").attr("selected",true);');
                    $el .= str_repeat(' ', 16) . '<div class="select-box">' . "\n";
                    $el .= str_repeat(' ', 20) . '<select name="' . $name . '" class="select"' . $validate . '>' . "\n";
                    //默认只生成一个空的示例控件，请根据情况自行复制编辑
                    $el .= str_repeat(' ', 24) . '<option value="">选项一</option>' . "\n";
                    $el .= str_repeat(' ', 20) . '</select>' . "\n";
                    $el .= str_repeat(' ', 16) . '</div>' . "\n";
                    break;
                case "textarea":
                    //默认生成的textarea加入了输入字符长度实时统计，H-ui.admin官方的textarealength方法有问题，请使用tpadmin框架修改后的源码，也可拷贝H-ui.js里相应的方法
                    //如果不需要字符长度实时统计，请在生成代码中删除textarea上的onKeyUp事件和下面p标签那行
                    $el .= str_repeat(' ', 16) . '<textarea class="textarea" placeholder="" name="' . $name . '" onKeyUp="textarealength(this,100)"' . $validate . '>{:isset($vo.' . $name . ')?$vo.' . $name . ':\'\'}</textarea>' . "\n";
                    $el .= str_repeat(' ', 16) . '<p class="textarea-numberbar"><em class="textarea-length">0</em>/100</p>' . "\n";
                    break;
                case "text":
                case "password":
                case "number":
                    //如需要时间选择器，请选择text类型，然后根据WdatePicker插件示例演示代码修改，请注意使用ThinkPHP原样输出标签literal原样输出，否则会出现模板编译错误
                    //literal标签使用方法见：http://www.kancloud.cn/manual/thinkphp5/125010
                default:
                    $el .= str_repeat(' ', 16) . '<input type="' . $type . '" class="input-text" value="{:isset($vo.' . $name . ')?$vo.' . $name . ':\'\'}" placeholder="" name="' . $name . '"' . $validate . '>' . "\n";
            }
            $el .= str_repeat(' ', 12) . '</div>' . "\n";
            $el .= str_repeat(' ', 12) . '<div class="col-xs-3 col-sm-3"></div>' . "\n";
            $el .= str_repeat(' ', 8) . '</div>' . "\n";
        }
        $template = file_get_contents($pathTemplate . "edit.tpl");
        $file = $path . "edit.html";

        return file_put_contents($file, str_replace(["[ROWS]", "[SET_VALUE]"], [$el, implode("\n", array_merge($set_checked, $set_selected))], $template));
    }

    /**
     * 创建控制器文件
     * @return int
     */
    private function buildController($fileName, $pathTemplate)
    {
        $el = "";
        //自动生成表单搜索的模糊查询条件过滤器
        foreach ($this->post['form_search'] as $k => $v) {
            if ($v) {
                $el .= '        if ($this->request->param("' . $this->post['form_name'][$k] . '")) $map[\'' . $this->post['form_name'][$k] . '\'] = ["like", "%" . $this->request->param("' . $this->post['form_name'][$k] . '") . "%"];' . "\n";
            }
        }
        if ($el) {
            $filter = 'protected function filter(&$map)' . "\n" . str_repeat(" ", 4) . '{' . "\n" . $el . '    }';
        } else {
            $filter = '';
        }
        //自动屏蔽查询条件isdelete字段
        if (!isset($this->post['menu']) || (isset($this->post['menu']) && !in_array("delete", $this->post['menu']) && !in_array("recyclebin", $this->post['menu']))) {
            $filter = 'protected $isdelete = false;' . "\n\n" . str_repeat(" ", 4) . $filter;
        }
        $template = file_get_contents($pathTemplate . "Controller.tpl");
        $file = str_replace('%NAME%', 'controller', $fileName);

        return file_put_contents($file, str_replace(
                ["[TITLE]", "[NAME]", "[FILTER]", "[NAMESPACE]"],
                [$this->post['controller_title'], $this->name, $filter, $this->namespace],
                $template)
        );
    }

    /**
     * 创建模型文件
     * @return bool|int
     */
    private function buildModel($fileName, $pathTemplate)
    {
        if (isset($this->post['model']) && $this->post['model']) {
            //直接生成空模板
            $template = file_get_contents($pathTemplate . "Model.tpl");
            $file = str_replace('%NAME%', 'model', $fileName);

            return file_put_contents($file, str_replace(
                    ["[TITLE]", "[NAME]", "[NAMESPACE]"],
                    [$this->post['controller_title'], $this->name, $this->namespace],
                    $template)
            );
        }

        return true;
    }

    /**
     * 创建验证器
     * @return bool|int
     */
    private function buildValidate($fileName, $pathTemplate)
    {
        if (isset($this->post['validate']) && $this->post['validate']) {
            $el = "";
            //根据前端校验规则自动生成验证器校验规则，考虑到二者语法不同，只生成必填字段的规则require，其他规则留空，自己前往验证器完善相关规则
            foreach ($this->post['form_validate'] as $k => $v) {
                if ($v) {
                    $el .= '        "' . $this->post['form_name'][$k] . '|' . $this->post['form_title'][$k] . '" => "' . ($this->post['form_require'][$k] ? "require" : "") . '",' . "\n";
                }
            }
            if ($el) {
                $rule = 'protected $rule = [' . "\n" . $el . '    ];';
            } else {
                $rule = '';
            }
            $template = file_get_contents($pathTemplate . "Validate.tpl");
            $file = str_replace('%NAME%', 'validate', $fileName);

            return file_put_contents($file, str_replace(
                    ["[TITLE]", "[NAME]", "[NAMESPACE]", "[RULE]"],
                    [$this->post['controller_title'], $this->name, $this->namespace, $rule],
                    $template)
            );
        }

        return true;
    }

    /**
     * 创建数据表
     * @return bool|int
     */
    private function buildTable($tableName)
    {
        if (isset($this->post['table']) && $this->post['table']) {
            // 一定别忘记表名前缀
            $tableName = Config::get("database.prefix") . $tableName;
            // 在 MySQL 中，DROP TABLE 语句自动提交事务，因此在此事务内的任何更改都不会被回滚，不能使用事务
            // http://php.net/manual/zh/pdo.rollback.php
            $tableExist = false;
            // 判断表是否存在
            try {
                $ret = Db::query("SHOW CREATE TABLE {$tableName}");
                // 表存在
                if ($ret && isset($ret[0])) {
                    //不 是强制建表但表存在时直接 return
                    if (!isset($this->post['table_force'])) {
                        return true;
                    }
                    Db::execute("RENAME TABLE {$tableName} to tp_build_tmp_bak");
                    $tableExist = true;
                }
            } catch (\Exception $e) {

            }

            // 强制建表和不存在原表执行建表操作
            $el = [];
            $key = [];
            // 自动生成主键id，int(11)，非空无符号自增
            array_push($el, "    `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '{$this->post['controller_title']}主键'");
            foreach ($this->post['table_name'] as $k => $v) {
                if (!in_array($v, ["id", "status", "isdelete", "create_time", "update_time"])) {
                    // 字段属性
                    $field = "    `{$v}` {$this->post['table_type'][$k]}(" . intval($this->post['table_size'][$k]) . ")" .
                        ($this->post['table_null'][$k] ? " NOT NULL" : "") .
                        ($this->post['table_default'][$k] === "" ? "" : " DEFAULT '{$this->post['table_default'][$k]}'") .
                        ($this->post['table_comment'][$k] === "" ? "" : " COMMENT '{$this->post['table_comment'][$k]}'");
                    array_push($el, $field);
                }
                // 索引
                if ($this->post['table_key'][$k]) {
                    array_push($key, "    KEY `{$v}` (`{$v}`)");
                }
            }

            if (isset($this->post['menu'])) {
                // 自动生成 status 字段，防止 resume,forbid 方法报错，如果不需要请到数据库自己删除
                if (in_array("resume", $this->post['menu']) || in_array("forbid", $this->post['menu'])) {
                    if (!in_array("status", $this->post['table_name'])) {
                        array_push($el, "    `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态，1-正常 | 0-禁用'");
                    }
                }
                // 自动生成 isdelete 软删除字段，防止 delete,recycle,deleteForever 方法报错，如果不需要请到数据库自己删除
                if (in_array("delete", $this->post['menu']) || in_array("recyclebin", $this->post['menu'])) {
                    // 修改官方软件删除使用记录时间戳的方式，效率较低，改为枚举类型的 tinyint(1)，相应的 traits 见 thinkphp/library/traits/model/FakeDelete.php，使用方法和官方一样
                    // 软件删除详细介绍见：http://www.kancloud.cn/manual/thinkphp5/189658
                    if (!in_array("isdelete", $this->post['table_name'])) {
                        array_push($el, "    `isdelete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '删除状态，1-删除 | 0-正常'");
                    }
                }
            }

            // 如果创建模型则自动生成 create_time，update_time 字段
            if (isset($this->post['model']) && $this->post['model']) {
                // 自动生成 create_time 字段，相应自动生成的模型也开启自动写入 create_time 和 update_time 时间，并且将类型指定为 int 类型
                // 时间戳使用方法见：http://www.kancloud.cn/manual/thinkphp5/138668
                if (!in_array("create_time", $this->post['table_name'])) {
                    array_push($el, "    `create_time` int(11) unsigned NOT NULL COMMENT '创建时间'");
                }
                if (!in_array("update_time", $this->post['table_name'])) {
                    array_push($el, "    `update_time` int(11) unsigned NOT NULL COMMENT '更新时间'");
                }
            }
            // 默认自动创建主键为id
            array_push($el, '    PRIMARY KEY (`id`)');

            // 会删除之前的表，会清空数据，重新创建表，谨慎操作
            $sql_drop = "DROP TABLE IF EXISTS `{$tableName}`";
            // 默认字符编码为utf8，表引擎默认 InnoDB，其他都是默认
            $sql_create = "CREATE TABLE `{$tableName}` (\n" . implode(",\n", array_merge($el, $key)) . "\n)ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '{$this->post['controller_title']}'";

            //写入执行的 SQL 到日志中，如果不是想要的表结构，请到日志中搜索 BUILD_SQL，找到执行的 SQL 到数据库 GUI 软件中修改执行，修改表结构
            Log::write("BUILD_SQL：\n{$sql_drop};\n{$sql_create};", Log::SQL);
            // execute 和 query 方法都不支持传入分号 (;)，不支持一次执行多条 SQL
            try {
                Db::execute($sql_drop);
                Db::execute($sql_create);
                Db::execute("DROP TABLE IF EXISTS `tp_build_tmp_bak`");
            } catch (\Exception $e) {
                // 模拟事务操作，滚回原表
                if ($tableExist) {
                    Db::execute("RENAME TABLE tp_build_tmp_bak to {$tableName}");
                }

                return $e->getMessage();
            }

            return true;
        }

        return true;
    }
}
