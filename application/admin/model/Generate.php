<?php
// +----------------------------------------------------------------------
// | tpadmin [a web admin based ThinkPHP5]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 tianpian
// +----------------------------------------------------------------------
// | Author: tianpian <tianpian0805@gmail.com>
// +----------------------------------------------------------------------

//------------------------
// 自动生成代码模型
//-------------------------
//!!!请不要随便删除代码中拼接字符故意留下的空格，否则生成的代码将会出现缩进错乱
//!!!请不要删除generate视图文件夹中template文件夹下文件扩展名为.tpl的文件，否则会导致代码执行错误
//!!!ThinkPHP5的作者已明确说明对任何错误是零容忍，甚至模板里未定义变量或方法不存在都会导致错误，请不要随便删除模板中的{:isset($var)?$var:''}类似变量的写法，否则会抛出异常
//
//build方法未对异常进行处理，如实际使用中发现错误，请打开控制台查错
//-------------------------

namespace app\admin\model;

use think\Loader;
use think\Log;

class Generate{
    private $module;
    private $controller;
    private $multi_controller_dir;
    private $controller_lower;
    private $post;

    public function build(){
        $this->post = input("post.");

        $this->module = request()->module();
        $controllers = explode(".",$this->post['controller_name']);
        $this->controller = array_pop($controllers);
        if (in_array($this->controller,["AdminGroup","AdminNode","AdminRole","AdminUser","AdminNodeLoad","Common","Demo","Generate","Index","LogLogin","Ueditor"])){
            ajax_return_adv_error("不允许生成该控制器");
        }
        $this->controller_lower = Loader::parseName($this->controller);
        if (in_array($this->controller_lower,["admin_group","admin_node","admin_role","admin_user","admin_node_load","common","demo","generate","index","log_login","ueditor"])){
            ajax_return_adv_error("不允许生成该控制器");
        }
        //分级控制器目录，仅用于controller和view
        //http://www.kancloud.cn/manual/thinkphp5/118054
        if ($controllers){
            $this->multi_controller_dir = strtolower(implode(DS,$controllers).DS);
        } else {
            $this->multi_controller_dir = "";
        }

        //创建目录
        $dir_list = ["view/".$this->multi_controller_dir.$this->controller_lower];
        if (isset($this->post['model']) && $this->post['model']){
            array_push($dir_list,"model");
        }
        if (isset($this->post['validate']) && $this->post['validate']){
            array_push($dir_list,"validate");
        }
        if ($this->multi_controller_dir){
            array_push($dir_list,"controller".DS.$this->multi_controller_dir);
        }
        $this->buildDir($dir_list);

        //创建文件
        $this->buildForm();
        $this->buildTh();
        $this->buildTd();
        $this->buildRecyclebin();
        $this->buildIndex();
        $this->buildEdit();
        $this->buildController();
        $this->buildModel();
        $this->buildValidate();
        $this->buildTable();
    }

    /**
     * 检查当前模块目录是否可写
     * @return bool
     */
    public function checkWritable(){
        $lockfile = APP_PATH . $this->module."/bulid.test";
        if (!file_put_contents($lockfile,"test")) {
            return false;
        }
        // 解除锁定
        unlink($lockfile);
        return true;
    }

    /**
     * 创建目录
     * @param $dir_list
     */
    private function buildDir($dir_list){
        foreach ($dir_list as $dir) {
            if (!is_dir(APP_PATH.$this->module."/" . $dir)) {
                // 创建目录
                mkdir(APP_PATH.$this->module."/" . $dir, 0755, true);
            }
        }
    }

    /**
     * 创建form.html文件
     * @return int
     */
    private function buildForm(){
        $el = "";
        foreach ($this->post['form_search'] as $k => $v){
            if ($v){ //只筛选出选择为表单筛选的字段
                $el .= '    <input type="text" class="input-text" style="width:250px" placeholder="'.$this->post['form_title'][$k].'" name="'.$this->post['form_name'][$k].'" value="{:input(\'param.'.$this->post['form_name'][$k].'\')}">'."\n";
            }
        }
        if ($el){
            $el .= '    <button type="submit" class="btn btn-success" id="" name=""><i class="Hui-iconfont">&#xe665;</i> 搜索</button>'."\n";
        }
        $content = '<form class="mb-20" method="get" action="{:url(request()->action())}">'."\n".$el.'</form>';
        $file = APP_PATH.$this->module."/view/".$this->multi_controller_dir.$this->controller_lower."/form.html";
        return file_put_contents($file,$content);
    }

    /**
     * 创建form.html文件
     * @return int
     */
    private function buildTh(){
        $el = ['<th width="25"><input type="checkbox" value="" name=""></th>'];
        foreach ($this->post['form_title'] as $k => $v){
            if ($this->post['form_sort'][$k]){ //带有表单排序的需使用表单排序方法
                array_push($el,'<th width="">'."{:sort_by('{$v}','{$this->post['form_name'][$k]}')}".'</th>');
            } else {
                array_push($el,'<th width="">'.$v.'</th>');
            }
        }
        $content = implode("\n",$el);
        $file = APP_PATH.$this->module."/view/".$this->multi_controller_dir.$this->controller_lower."/th.html";
        return file_put_contents($file,$content);
    }

    /**
     * 创建td.html文件
     * @return int
     */
    private function buildTd(){
        $el = ['<td><input type="checkbox" name="id[]" value="{$vo.id}"></td>'];
        foreach ($this->post['form_name'] as $k => $v){
            if ($this->post['form_search'][$k]){ //带有表单搜索筛选的自动添加关键词高亮方法
                array_push($el,'<td>{$vo.'.$v."|high_light=input('param.".$v."')}</td>");
            } else { //对于status字段采用框架提供的get_status方法显示图标
                array_push($el,'<td>{$vo.'.$v.($v=="status"?'|get_status':'').'}</td>');
            }
        }
        $content = implode("\n",$el);
        $file = APP_PATH.$this->module."/view/".$this->multi_controller_dir.$this->controller_lower."/td.html";
        return file_put_contents($file,$content);
    }

    /**
     * 创建recyclebin.html文件
     */
    private function buildRecyclebin(){
        //首页菜单选择了回收站才创建回收站
        if (isset($this->post['menu']) && in_array("recyclebin",$this->post['menu'])){
            $file = APP_PATH.$this->module."/view/".$this->multi_controller_dir.$this->controller_lower."/recyclebin.html";
            //默认直接继承模板
            file_put_contents($file,'{extend name="template/recyclebin" /}');
        }
    }

    /**
     * 创建index.html文件
     * @return int
     */
    private function buildIndex(){
        $file = APP_PATH.$this->module."/view/".$this->multi_controller_dir.$this->controller_lower."/index.html";
        //菜单全选的默认直接继承模板
        $this->post['menu'] = isset($this->post['menu']) ? $this->post['menu'] : [];
        if (count($this->post['menu']) == 5){
            return file_put_contents($file,'{extend name="template/index" /}');
        } else {
            //菜单部分选择的使用模板替换相关参数创建自定义html文件
            $template = file_get_contents(APP_PATH.$this->module."/view/template/index.html");
            return file_put_contents($file,str_replace('table_menu"','table_menu" menu="'.implode(",",$this->post['menu']).'"',$template));
        }
    }

    /**
     * 创建edit.html文件
     * @return int
     */
    private function buildEdit(){
        $el = "";
        $set_checked = [];//radio类型的表单控件编辑状态使用javascript赋值
        $set_selected = [];//select类型的表单控件编辑状态使用javascript赋值
        foreach ($this->post['form_title'] as $k => $v){
            $type = $this->post['form_type'][$k];
            $name = $this->post['form_name'][$k];

            //像id这种白名单字段不需要自动生成到编辑页
            if (in_array($name,["id","create_time","update_time","isdelete"])) continue;

            //str_repeat重复输出空格和每行末加换行符是为了生成的模板有缩进，方便二次编辑
            $el .= str_repeat(' ',8).'<div class="row cl">'."\n".str_repeat(' ',12).'<label class="form-label col-xs-3 col-sm-3">'.($this->post['form_require'][$k]?'<span class="c-red">*</span>':'').$v.'：</label>'."\n";

            //使用Validform插件前端验证数据格式，生成在表单控件上的验证规则
            $validate = ($this->post['form_validate'][$k]?' datatype="'.$this->post['form_validate'][$k].'"':'').($this->post['form_validate_null'][$k]?' nullmsg="'.$this->post['form_validate_null'][$k].'"':'').($this->post['form_validate_error'][$k]?' errormsg="'.$this->post['form_validate_error'][$k].'"':'').(!$this->post['form_require'][$k]&&$this->post['form_validate'][$k]?' ignore="ignore"':'');

            //注意checkbox,radio类型控件需要加.skin-minimal，方便iCheck插件对控件进行美化
            $el .= str_repeat(' ',12).'<div class="formControls col-xs-6 col-sm-6'.(in_array($type,['radio','checkbox'])?' skin-minimal':'').'">'."\n";
            switch ($type){
                case "radio":
                case "checkbox":
                    //只对radio类型的控件进行编辑状态赋值，checkbox类型控件请自行根据情况赋值
                    if ($type == "radio"){
                        array_push($set_checked,str_repeat(' ',8).'$("[name=\''.$name.'\'][value=\'{:isset($vo.'.$name.')?$vo.'.$name.':\'\'}\']").attr("checked",true);');
                    }
                    //默认只生成一个空的示例控件，请根据情况自行复制编辑
                    $el .= str_repeat(' ',16).'<div class="radio-box">'."\n";
                    $el .= str_repeat(' ',20).'<input type="'.$type.'" name="'.$name.($type == "checkbox"?'[]':'').'" id="'.$name.'-0" value=""'.$validate.'>'."\n";
                    $el .= str_repeat(' ',20).'<label for="'.$name.'-0">选项一</label>'."\n";
                    $el .= str_repeat(' ',16).'</div>'."\n";
                    break;
                case "select":
                    //select类型的控件进行编辑状态赋值
                    array_push($set_selected,str_repeat(' ',8).'$("[name=\''.$name.'\']").find("[value=\'{:isset($vo.'.$name.')?$vo.'.$name.':\'\'}\']").attr("selected",true);');
                    $el .= str_repeat(' ',16).'<div class="select-box">'."\n";
                    $el .= str_repeat(' ',20).'<select name="'.$name.'" class="select"'.$validate.'>'."\n";
                    //默认只生成一个空的示例控件，请根据情况自行复制编辑
                    $el .= str_repeat(' ',24).'<option value="">选项一</option>'."\n";
                    $el .= str_repeat(' ',20).'</select>'."\n";
                    $el .= str_repeat(' ',16).'</div>'."\n";
                    break;
                case "textarea":
                    //默认生成的textarea加入了输入字符长度实时统计，H-ui.admin官方的textarealength方法有问题，请使用tpadmin框架修改后的源码，也可拷贝H-ui.js里相应的方法
                    //如果不需要字符长度实时统计，请在生成代码中删除textarea上的onKeyUp事件和下面p标签那行
                    $el .= str_repeat(' ',16).'<textarea class="textarea" placeholder="" name="'.$name.'" onKeyUp="textarealength(this,100)"'.$validate.'>{:isset($vo.'.$name.')?$vo.'.$name.':\'\'}</textarea>'."\n";
                    $el .= str_repeat(' ',16).'<p class="textarea-numberbar"><em class="textarea-length">0</em>/100</p>'."\n";
                    break;
                case "text":
                case "password":
                case "number":
                    //如需要时间选择器，请选择text类型，然后根据WdatePicker插件示例演示代码修改，请注意使用ThinkPHP原样输出标签literal原样输出，否则会出现模板编译错误
                    //literal标签使用方法见：http://www.kancloud.cn/manual/thinkphp5/125010
                default:
                    $el .= str_repeat(' ',16).'<input type="'.$type.'" class="input-text" value="{:isset($vo.'.$name.')?$vo.'.$name.':\'\'}" placeholder="" name="'.$name.'"'.$validate.'>'."\n";
            }
            $el .= str_repeat(' ',12).'</div>'."\n";
            $el .= str_repeat(' ',12).'<div class="col-xs-3 col-sm-3"></div>'."\n";
            $el .= str_repeat(' ',8).'</div>'."\n";
        }
        $template = file_get_contents(APP_PATH.$this->module."/view/generate/template/edit.tpl");
        $file = APP_PATH.$this->module."/view/".$this->multi_controller_dir.$this->controller_lower."/edit.html";
        return file_put_contents($file,str_replace(["[ROWS]","[SET_VALUE]"],[$el,implode("\n",array_merge($set_checked,$set_selected))],$template));
    }

    /**
     * 创建控制器文件
     * @return int
     */
    private function buildController(){
        $el = "";
        //自动生成表单搜索的模糊查询条件过滤器
        foreach ($this->post['form_search'] as $k => $v){
            if ($v){
                $el .= '        if (input("param.'.$this->post['form_name'][$k].'")) $map[\''.$this->post['form_name'][$k].'\'] = array("like","%".input("param.'.$this->post['form_name'][$k].'")."%");'."\n";
            }
        }
        if ($el){
            $filter = 'protected function _filter(&$map){'."\n".$el.'    }';
        } else {
            $filter = '';
        }
        //自动屏蔽查询条件isdelete字段
        if (!isset($this->post['menu']) || (isset($this->post['menu']) && !in_array("delete",$this->post['menu']) && !in_array("recyclebin",$this->post['menu']))){
            $filter = 'protected $isdelete = false;'."\n\n".str_repeat(" ",4).$filter;
        }
        $template = file_get_contents(APP_PATH.$this->module."/view/generate/template/Controller.tpl");
        $file = APP_PATH.$this->module."/controller/".$this->multi_controller_dir.$this->controller.".php";
        return file_put_contents($file,str_replace(["[CONTROLLER_NAME_TITLE]","[CONTROLLER_NAME]","[FILTER]","[CONTROLLER_DIR]"],[$this->post['controller_title'],$this->controller,$filter,$this->multi_controller_dir ? "\\".str_replace(DS,"\\",substr($this->multi_controller_dir,0,-1)) : $this->multi_controller_dir],$template));
    }

    /**
     * 创建模型文件
     * @return bool|int
     */
    private function buildModel(){
        if (isset($this->post['model']) && $this->post['model']){
            //直接生成空模板
            $template = file_get_contents(APP_PATH.$this->module."/view/generate/template/Model.tpl");
            $file = APP_PATH.$this->module."/model/".$this->controller.".php";
            return file_put_contents($file,str_replace(["[CONTROLLER_NAME_TITLE]","[CONTROLLER_NAME]"],[$this->post['controller_title'],$this->controller],$template));
        }
        return true;
    }

    /**
     * 创建验证器
     * @return bool|int
     */
    private function buildValidate(){
        if (isset($this->post['validate']) && $this->post['validate']){
            $el = "";
            //根据前端校验规则自动生成验证器校验规则，考虑到二者语法不同，只生成必填字段的规则require，其他规则留空，自己前往验证器完善相关规则
            foreach ($this->post['form_validate'] as $k => $v){
                if ($v){
                    $el .= '        "'.$this->post['form_name'][$k].'|'.$this->post['form_title'][$k].'" => "'.($this->post['form_require'][$k] ? "require" : "").'",'."\n";
                }
            }
            if ($el){
                $rule = 'protected $rule = ['."\n".$el.'    ];';
            } else {
                $rule = '';
            }
            $template = file_get_contents(APP_PATH.$this->module."/view/generate/template/Validate.tpl");
            $file = APP_PATH.$this->module."/validate/".$this->controller.".php";
            return file_put_contents($file,str_replace(["[CONTROLLER_NAME_TITLE]","[CONTROLLER_NAME]","[RULE]"],[$this->post['controller_title'],$this->controller,$rule],$template));
        }
        return true;
    }

    /**
     * 创建数据表
     * @return bool|int
     */
    private function buildTable(){
        if (isset($this->post['table']) && $this->post['table']){
            //一定别忘记表名前缀
            $table_name = config("database.prefix").$this->controller_lower;
            $db = db();
            //在 MySQL 中，DROP TABLE 语句自动提交事务，因此在此事务内的任何更改都不会被回滚，不能使用事务
            //http://php.net/manual/zh/pdo.rollback.php
            $sql_old = ""; //原来表的创建SQL
            //判断表是否存在
            try{
                $ret = $db->query("SHOW CREATE TABLE {$table_name}");
                //取到原表建表语句，模拟事务操作
                if ($ret && isset($ret[0])){
                    $sql_old = end($ret[0]);
                }
            } catch (\PDOException $e){
                $sql_old = "";
            } catch (\Exception $e){
                $sql_old = "";
            }
            //不是强制建表但表存在时直接return
            if ($sql_old && !isset($this->post['table_force'])){
                return true;
            }

            //强制建表和不存在原表执行建表操作
            $el = [];
            $key = [];
            //自动生成主键id，int(11)，非空无符号自增
            array_push($el,"    `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '{$this->post['controller_title']}主键'");
            foreach ($this->post['table_name'] as $k => $v){
                if (!in_array($v,["id","status","isdelete","create_time","update_time"])){
                    //字段属性
                    $field = "    `{$v}` {$this->post['table_type'][$k]}(".intval($this->post['table_size'][$k]).")".($this->post['table_null'][$k]?" NOT NULL":"").($this->post['table_default'][$k]===""?"":" DEFAULT '{$this->post['table_default'][$k]}'").($this->post['table_comment'][$k]===""?"":" COMMENT '{$this->post['table_comment'][$k]}'");
                    array_push($el,$field);
                }
                //索引
                if ($this->post['table_key'][$k]){
                    array_push($key,"    KEY `{$v}` (`{$v}`)");
                }
            }

            if (isset($this->post['menu'])){
                //自动生成status字段，防止resume,forbid方法报错，如果不需要请到数据库自己删除
                if (in_array("resume",$this->post['menu']) || in_array("forbid",$this->post['menu'])){
                    if (!in_array("status",$this->post['table_name'])){
                        array_push($el,"    `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态，1-正常 | 0-禁用'");
                    }
                }
                //自动生成isdelete软删除字段，防止delete,recycle,deleteForever方法报错，如果不需要请到数据库自己删除
                if (in_array("delete",$this->post['menu']) || in_array("recyclebin",$this->post['menu'])){
                    //修改官方软件删除使用记录时间戳的方式，效率较低，改为枚举类型的tinyint(1)，相应的trait见thinkphp/library/traits/model/FakeDelete.php，使用方法和官方一样
                    //软件删除详细介绍见：http://www.kancloud.cn/manual/thinkphp5/189658
                    if (!in_array("isdelete",$this->post['table_name'])){
                        array_push($el,"    `isdelete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '删除状态，1-删除 | 0-正常'");
                    }
                }
            }

            //如果创建模型则自动生成create_time，update_time字段
            if (isset($this->post['model']) && $this->post['model']){
                //自动生成create_time字段，相应自动生成的模型也开启自动写入create_time和update_time时间，并且将类型指定为int类型
                //时间戳使用方法见：http://www.kancloud.cn/manual/thinkphp5/138668
                if (!in_array("create_time",$this->post['table_name'])){
                    array_push($el,"    `create_time` int(11) unsigned NOT NULL COMMENT '创建时间'");
                }
                if (!in_array("update_time",$this->post['table_name'])){
                    array_push($el,"    `update_time` int(11) unsigned NOT NULL COMMENT '更新时间'");
                }
            }
            //默认自动创建主键为id
            array_push($el,'    PRIMARY KEY (`id`)');

            //会删除之前的表，会清空数据，重新创建表，谨慎操作
            $sql_drop = "DROP TABLE IF EXISTS `{$table_name}`";
            //默认字符编码为utf8，表引擎默认InnoDB，其他都是默认
            $sql_create = "CREATE TABLE `{$table_name}` (\n".implode(",\n",array_merge($el,$key))."\n)ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '{$this->post['controller_title']}'";

            //写入执行的SQL到日志中，如果不是想要的表结构，请到日志中搜索BUILD_SQL，找到执行的SQL到数据库GUI软件中修改执行，修改表结构
            Log::write("\n{$sql_drop};\n{$sql_create};","BUILD_SQL");
            //execute和query方法都不支持传入分号(;)，不支持一次执行多条SQL
            try{
                $db->execute($sql_drop);
                $db->execute($sql_create);
            } catch (\PDOException $e) {
                //模拟事务操作
                if ($sql_old){
                    $db->execute($sql_old);
                    Log::write("\n{$sql_old};","BUILD_SQL");
                }
                return $e->getMessage();
            } catch (\Exception $e){
                //模拟事务操作
                if ($sql_old){
                    $db->execute($sql_old);
                    Log::write("\n{$sql_old};","BUILD_SQL");
                }
                return $e->getMessage();
            }
            return true;
        }
        return true;
    }
}