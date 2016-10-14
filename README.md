#tpadmin是什么？
tpadmin是一个基于ThinkPHP5.0正式版和Hui.admin v2.5的管理后台，简化管理后台的开发流程，简化代码的编写，提高代码复用率

在线文档：[http://www.kancloud.cn/yuan1994/tpadmin](http://www.kancloud.cn/yuan1994/tpadmin)

在线体验：[http://tpadmin.demo.tianpian.net.cn](http://tpadmin.demo.tianpian.net.cn) 默认管理员帐号：admin，默认管理员密码：123456

#使用方法
##git克隆：
git clone git@github.com:yuan1994/tpadmin.git
##直接下载（建议使用git克隆，确保取到的代码最新）：
tar.gz格式：[https://github.com/yuan1994/tpadmin/archive/v1.0.tar.gz](https://github.com/yuan1994/tpadmin/archive/v1.0.tar.gz)

zip格式：[https://github.com/yuan1994/tpadmin/archive/v1.0.zip](https://github.com/yuan1994/tpadmin/archive/v1.0.zip)

#部署
参考[ThinkPHP5 - 部署](http://www.kancloud.cn/manual/thinkphp5/129745)
部署成功后，建立新建数据库tpadmin，导入项目根目录的tpadmin.sql文件，默认管理员帐号：admin，默认管理员密码：123456

#特性
####后端
* 模板、控制器、模型、验证器、数据表自动生成
* RBAC权限管理
* 完美支持多级控制器及多级控制器权限管理
* 支持前置方法_before_
* 支持模板主题
* 节点自动扫描与添加
* 七牛上传及与百度编辑器(Ueditor)结合使用
* Excel一键导出
* 邮件发送(Fsock和phpMailer两种驱动)
* ID加密解密
####前端
* 表单校验
* 无限层级菜单，完美与后端多级控制器兼容
* 基于layer的丰富弹层
* ajax请求处理封装，直接后台返回数据控制前端页面处理
* 多窗口办公
* 随机字符串生成
* 表格溢出处理

#本平台使用了如下框架或插件、源码：
* ThinkPHP 5.0正式版
* Hui.admin v2.5 
* layer
* jQuery Validform
* 七牛
* ...

# 注意
为了确保代码自动生成可用，请在Linux/MacOS系统上使用项目时保证application文件夹有可写权限，本地开发可用将文件夹的权限改为777，线上部署请注意修改成安全的权限

ThinkPHP5.0正式版有一些不适合管理后台需求，做了如下修改：
1. 为了支持include标签引入文件模板自动定位，修改了文件thinkphp/library/think/Template.php 第1057行：
在``$template = $this->parseTemplateFile($templateName);``前加上：
~~~
//解决模板include标签不支持自动定位当前控制器的问题
if (!preg_match("/(\/|\:)/",$templateName)){
    $templateName = str_replace(".",DS,\think\Loader::parseName(request()->controller()))."/".$templateName;
}
~~~
2. 为了支持多级控制器，Url::build方法有bug，url("one.two.Three")会生成 one.two._three的链接，正确的应该是one.two.three，修改了文件thinkphp/library/think/Loader.php第498行的parseName方法：
~~~
public static function parseName($name, $type = 0)
    {
        if ($type) {
//            return ucfirst(preg_replace_callback('/_([a-zA-Z])/', function ($match) {
//                return strtoupper($match[1]);
//            }, $name));
            return preg_replace_callback(['/\_([a-zA-Z])/','/([^.][a-zA-Z]*$)/'], function ($match){
                return ucfirst($match[1]);
            }, $name);
        } else {
//            return strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $name), "_"));
            return strtolower(preg_replace('/((?<=[a-z])(?=[A-Z]))/', '_', $name));
        }
    }
~~~

前端代码也做了一些修改，Validform插件注释了几行代码，Hui.admin.js和Hui.js两个文件也做了相应修改，请使用tpadmin提供的代码