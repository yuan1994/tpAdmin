/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50631
Source Host           : localhost:3306
Source Database       : tpadmin

Target Server Type    : MYSQL
Target Server Version : 50631
File Encoding         : 65001

Date: 2016-10-27 15:43:03
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for tp_admin_access
-- ----------------------------
DROP TABLE IF EXISTS `tp_admin_access`;
CREATE TABLE `tp_admin_access` (
  `role_id` smallint(6) unsigned NOT NULL,
  `node_id` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) unsigned NOT NULL,
  `pid` smallint(6) unsigned NOT NULL,
  KEY `groupId` (`role_id`),
  KEY `nodeId` (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of tp_admin_access
-- ----------------------------

-- ----------------------------
-- Table structure for tp_admin_group
-- ----------------------------
DROP TABLE IF EXISTS `tp_admin_group`;
CREATE TABLE `tp_admin_group` (
  `id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `icon` varchar(255) NOT NULL COMMENT 'icon小图标',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `remark` varchar(255) NOT NULL,
  `isdelete` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `create_time` int(11) unsigned NOT NULL,
  `update_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of tp_admin_group
-- ----------------------------
INSERT INTO `tp_admin_group` VALUES ('1', '系统管理', '&#xe61d;', '1', '1', '', '0', '1450752856', '1475768760');
INSERT INTO `tp_admin_group` VALUES ('2', '示例', '&#xe616;', '2', '1', '', '0', '1476016712', '1476017769');

-- ----------------------------
-- Table structure for tp_admin_node
-- ----------------------------
DROP TABLE IF EXISTS `tp_admin_node`;
CREATE TABLE `tp_admin_node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `pid` smallint(6) unsigned NOT NULL,
  `group_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `name` varchar(20) NOT NULL,
  `title` varchar(50) NOT NULL,
  `remark` varchar(255) NOT NULL,
  `level` tinyint(1) unsigned NOT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '节点类型，1-控制器 | 0-方法',
  `sort` smallint(6) unsigned NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `isdelete` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `name` (`name`),
  KEY `isdelete` (`isdelete`),
  KEY `sort` (`sort`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=59 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of tp_admin_node
-- ----------------------------
INSERT INTO `tp_admin_node` VALUES ('1', '0', '1', 'Admin', '后台管理', '后台管理，不可更改', '1', '1', '1', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('2', '1', '1', 'AdminGroup', '分组管理', ' ', '2', '1', '1', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('3', '1', '1', 'AdminNode', '节点管理', ' ', '2', '1', '2', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('4', '1', '1', 'AdminRole', '角色管理', ' ', '2', '1', '3', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('5', '1', '1', 'AdminUser', '用户管理', '', '2', '1', '4', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('6', '1', '0', 'Index', '首页', '', '2', '1', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('7', '6', '0', 'welcome', '欢迎页', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('8', '6', '0', 'index', '未定义', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('9', '1', '2', 'Generate', '代码自动生成', '', '2', '1', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('10', '1', '2', 'Demo/excel', 'Excel一键导出', '', '2', '0', '2', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('11', '1', '2', 'Demo/download', '下载', '', '2', '0', '3', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('12', '1', '2', 'Demo/downloadImage', '远程图片下载', '', '2', '0', '4', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('13', '1', '2', 'Demo/mail', '邮件发送', '', '2', '0', '5', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('14', '1', '2', 'Demo/qiniu', '七牛上传', '', '2', '0', '6', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('15', '1', '2', 'Demo/hashids', 'ID加密', '', '2', '0', '7', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('16', '1', '2', 'Demo/layer', '丰富弹层', '', '2', '0', '8', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('17', '1', '2', 'Demo/tableFixed', '表格溢出', '', '2', '0', '9', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('18', '1', '2', 'Demo/ueditor', '百度编辑器', '', '2', '0', '10', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('19', '1', '2', 'Demo/imageUpload', '图片上传', '', '2', '0', '11', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('20', '1', '2', 'Demo/qrcode', '二维码生成', '', '2', '0', '12', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('21', '1', '1', 'NodeMap', '节点图', '', '2', '1', '5', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('22', '1', '1', 'WebLog', '操作日志', '', '2', '1', '6', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('23', '1', '1', 'LoginLog', '登录日志', '', '2', '1', '7', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('24', '23', '0', 'index', '首页', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('25', '22', '0', 'index', '列表', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('26', '22', '0', 'detail', '详情', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('27', '21', '0', 'load', '自动导入', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('28', '21', '0', 'index', '首页', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('29', '21', '0', 'add', '添加', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('30', '21', '0', 'edit', '编辑', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('31', '21', '0', 'deleteForever', '永久删除', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('32', '9', '0', 'index', '首页', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('33', '9', '0', 'generate', '生成方法', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('34', '5', '0', 'password', '修改密码', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('35', '5', '0', 'index', '首页', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('36', '5', '0', 'add', '添加', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('37', '5', '0', 'edit', '编辑', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('38', '4', '0', 'user', '用户列表', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('39', '4', '0', 'access', '授权', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('40', '4', '0', 'index', '首页', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('41', '4', '0', 'add', '添加', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('42', '4', '0', 'edit', '编辑', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('43', '4', '0', 'forbid', '默认禁用操作', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('44', '4', '0', 'resume', '默认恢复操作', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('45', '3', '0', 'load', '节点快速导入', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('46', '3', '0', 'index', '首页', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('47', '3', '0', 'add', '添加', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('48', '3', '0', 'edit', '编辑', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('49', '3', '0', 'forbid', '默认禁用操作', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('50', '3', '0', 'resume', '默认恢复操作', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('51', '2', '0', 'index', '首页', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('52', '2', '0', 'add', '添加', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('53', '2', '0', 'edit', '编辑', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('54', '2', '0', 'forbid', '默认禁用操作', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('55', '2', '0', 'resume', '默认恢复操作', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('56', '1', '2', 'one', '多级菜单演示', '', '2', '1', '13', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('57', '56', '2', 'two', '三级菜单', '', '3', '1', '1', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('58', '57', '2', 'three', '四级菜单', '', '4', '0', '1', '1', '0');

-- ----------------------------
-- Table structure for tp_admin_node_load
-- ----------------------------
DROP TABLE IF EXISTS `tp_admin_node_load`;
CREATE TABLE `tp_admin_node_load` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `name` varchar(255) NOT NULL COMMENT '名称',
  `status` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='节点快速导入';

-- ----------------------------
-- Records of tp_admin_node_load
-- ----------------------------
INSERT INTO `tp_admin_node_load` VALUES ('4', '编辑', 'edit', '1');
INSERT INTO `tp_admin_node_load` VALUES ('5', '添加', 'add', '1');
INSERT INTO `tp_admin_node_load` VALUES ('6', '首页', 'index', '1');
INSERT INTO `tp_admin_node_load` VALUES ('7', '删除', 'delete', '1');

-- ----------------------------
-- Table structure for tp_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `tp_admin_role`;
CREATE TABLE `tp_admin_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `pid` smallint(6) unsigned NOT NULL,
  `name` varchar(20) NOT NULL,
  `remark` varchar(255) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `isdelete` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `create_time` int(11) unsigned NOT NULL,
  `update_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parentId` (`pid`),
  KEY `status` (`status`),
  KEY `isdelete` (`isdelete`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of tp_admin_role
-- ----------------------------
INSERT INTO `tp_admin_role` VALUES ('1', '0', '领导组', ' ', '1', '0', '1208784792', '1254325558');
INSERT INTO `tp_admin_role` VALUES ('2', '0', '网编组', ' ', '1', '0', '1215496283', '1454049929');

-- ----------------------------
-- Table structure for tp_admin_role_user
-- ----------------------------
DROP TABLE IF EXISTS `tp_admin_role_user`;
CREATE TABLE `tp_admin_role_user` (
  `role_id` mediumint(9) unsigned DEFAULT NULL,
  `user_id` char(32) DEFAULT NULL,
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

-- ----------------------------
-- Records of tp_admin_role_user
-- ----------------------------
INSERT INTO `tp_admin_role_user` VALUES ('1', '2');

-- ----------------------------
-- Table structure for tp_admin_user
-- ----------------------------
DROP TABLE IF EXISTS `tp_admin_user`;
CREATE TABLE `tp_admin_user` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `account` char(32) NOT NULL,
  `realname` varchar(255) NOT NULL,
  `password` char(32) NOT NULL,
  `last_login_time` int(11) unsigned NOT NULL DEFAULT '0',
  `last_login_ip` char(15) NOT NULL,
  `login_count` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `email` varchar(50) NOT NULL,
  `mobile` char(11) NOT NULL,
  `remark` varchar(255) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `isdelete` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `create_time` int(11) unsigned NOT NULL,
  `update_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `accountpassword` (`account`,`password`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of tp_admin_user
-- ----------------------------
INSERT INTO `tp_admin_user` VALUES ('1', 'admin', '超级管理员', 'e10adc3949ba59abbe56e057f20f883e', '1477535788', '127.0.0.1', '330', 'tianpian0805@gmail.com', '13121126169', '我是超级管理员', '1', '0', '1222907803', '1451033528');
INSERT INTO `tp_admin_user` VALUES ('2', 'demo', '测试', 'e10adc3949ba59abbe56e057f20f883e', '1477404006', '127.0.0.1', '2', '', '', '', '1', '0', '1476777133', '1477399793');

-- ----------------------------
-- Table structure for tp_file
-- ----------------------------
DROP TABLE IF EXISTS `tp_file`;
CREATE TABLE `tp_file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cate` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '文件类型，1-image | 2-file',
  `name` varchar(255) NOT NULL COMMENT '文件名',
  `original` varchar(255) NOT NULL DEFAULT '' COMMENT '原文件名',
  `domain` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `size` int(10) unsigned NOT NULL DEFAULT '0',
  `mtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_file
-- ----------------------------

-- ----------------------------
-- Table structure for tp_login_log
-- ----------------------------
DROP TABLE IF EXISTS `tp_login_log`;
CREATE TABLE `tp_login_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL,
  `login_ip` char(15) NOT NULL,
  `login_location` varchar(255) NOT NULL,
  `login_browser` varchar(255) NOT NULL,
  `login_os` varchar(255) NOT NULL,
  `login_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=659 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_login_log
-- ----------------------------
INSERT INTO `tp_login_log` VALUES ('1', '2', '127.0.0.1', '本机地址 本机地址  ', 'Chrome(52.0.2743.116)', 'Linux', '2016-10-06 11:16:21');
INSERT INTO `tp_login_log` VALUES ('2', '1', '127.0.0.1', '本机地址 本机地址  ', 'Chrome(52.0.2743.116)', 'Linux', '2016-10-06 11:21:19');
INSERT INTO `tp_login_log` VALUES ('3', '1', '127.0.0.1', '本机地址 本机地址  ', 'Chrome(52.0.2743.116)', 'Linux', '2016-10-07 12:23:27');
INSERT INTO `tp_login_log` VALUES ('4', '1', '127.0.0.1', '本机地址 本机地址  ', 'Chrome(52.0.2743.116)', 'Linux', '2016-10-13 21:00:42');
INSERT INTO `tp_login_log` VALUES ('5', '1', '127.0.0.1', '本机地址 本机地址  ', 'Chrome(52.0.2743.116)', 'Linux', '2016-10-07 13:08:52');
INSERT INTO `tp_login_log` VALUES ('6', '1', '127.0.0.1', '本机地址 本机地址  ', 'Chrome(52.0.2743.116)', 'Linux', '2016-10-07 13:09:18');
INSERT INTO `tp_login_log` VALUES ('7', '1', '127.0.0.1', '本机地址 本机地址  ', 'Chrome(52.0.2743.116)', 'Linux', '2016-10-07 13:10:31');
INSERT INTO `tp_login_log` VALUES ('8', '1', '127.0.0.1', '本机地址 本机地址  ', 'Chrome(52.0.2743.116)', 'Linux', '2016-10-07 13:21:33');
INSERT INTO `tp_login_log` VALUES ('9', '1', '127.0.0.1', '本机地址 本机地址  ', 'Chrome(52.0.2743.116)', 'Linux', '2016-10-07 13:23:38');
INSERT INTO `tp_login_log` VALUES ('10', '2', '127.0.0.1', '本机地址 本机地址  ', 'Chrome(52.0.2743.116)', 'Linux', '2016-10-07 15:29:17');
INSERT INTO `tp_login_log` VALUES ('11', '1', '127.0.0.1', '本机地址 本机地址  ', 'Chrome(52.0.2743.116)', 'Linux', '2016-10-08 19:14:21');
INSERT INTO `tp_login_log` VALUES ('12', '1', '127.0.0.1', '本机地址 本机地址  ', 'Chrome(52.0.2743.116)', 'Linux', '2016-10-09 20:17:26');
INSERT INTO `tp_login_log` VALUES ('13', '1', '127.0.0.1', '本机地址 本机地址  ', 'Chrome(51.0.2704.84)', 'Linux', '2016-10-11 09:09:43');
INSERT INTO `tp_login_log` VALUES ('14', '1', '127.0.0.1', '本机地址 本机地址  ', 'Chrome(51.0.2704.84)', 'Linux', '2016-10-13 09:16:51');
INSERT INTO `tp_login_log` VALUES ('15', '1', '124.193.250.90', '中国 北京 北京 ', 'Chrome(51.0.2704.84)', 'Linux', '2016-10-13 16:25:47');
INSERT INTO `tp_login_log` VALUES ('16', '1', '124.205.212.24', '中国 北京 北京 ', 'Chrome(52.0.2743.116)', 'Linux', '2016-10-13 19:28:22');
INSERT INTO `tp_login_log` VALUES ('17', '1', '124.205.212.24', '中国 北京 北京 ', 'Chrome(52.0.2743.116)', 'Linux', '2016-10-13 19:29:49');
INSERT INTO `tp_login_log` VALUES ('18', '1', '202.105.124.225', '中国 广东 深圳 ', 'IE(11.0)', 'Windows 7', '2016-10-13 19:54:22');
INSERT INTO `tp_login_log` VALUES ('19', '1', '123.120.79.81', '中国 北京 北京 ', '未知浏览器()', '未知操作系统', '2016-10-13 20:13:46');
INSERT INTO `tp_login_log` VALUES ('20', '1', '111.194.246.67', '中国 北京 北京 ', 'IE(8.0)', 'Windows 7', '2016-10-13 20:16:39');
INSERT INTO `tp_login_log` VALUES ('21', '1', '223.21.189.106', '中国 北京 北京 ', 'IE(7.0)', 'Windows 7', '2016-10-13 20:17:25');
INSERT INTO `tp_login_log` VALUES ('22', '1', '115.207.34.53', '中国 浙江 衢州 ', 'Firefox(49.0)', 'Windows 7', '2016-10-13 20:19:05');
INSERT INTO `tp_login_log` VALUES ('23', '1', '171.10.15.203', '中国 河南  ', 'Chrome(37.0.0.0)', 'Linux', '2016-10-13 20:22:46');
INSERT INTO `tp_login_log` VALUES ('24', '1', '119.0.6.101', '中国 贵州 黔东南苗族侗族自治州 ', 'Chrome(50.0.2661.102)', 'Windows 7', '2016-10-13 20:41:29');
INSERT INTO `tp_login_log` VALUES ('25', '1', '118.254.74.183', '中国 湖南 张家界 ', 'Chrome(44.0.2403.157)', 'Windows 7', '2016-10-13 20:41:41');
INSERT INTO `tp_login_log` VALUES ('26', '1', '182.150.155.31', '中国 四川 成都 ', '傲游(4.9.2.1000)', 'Windows 10', '2016-10-13 20:43:15');
INSERT INTO `tp_login_log` VALUES ('27', '1', '59.59.81.30', '中国 福建 漳州 ', 'IE(7.0)', 'Windows 7', '2016-10-13 20:43:51');
INSERT INTO `tp_login_log` VALUES ('28', '1', '182.18.104.80', '中国 北京 北京 ', 'Firefox(49.0)', 'Windows 7', '2016-10-13 20:45:21');
INSERT INTO `tp_login_log` VALUES ('29', '1', '115.156.193.168', '中国 湖北 武汉 ', 'Chrome(43.0.2357.81)', 'Linux', '2016-10-13 20:47:02');
INSERT INTO `tp_login_log` VALUES ('30', '1', '58.248.232.210', '中国 广东 广州 ', 'Chrome(53.0.2785.116)', 'Windows NT', '2016-10-13 20:51:39');
INSERT INTO `tp_login_log` VALUES ('31', '1', '124.205.212.28', '中国 北京 北京 ', 'Chrome(55.0.2868.0)', 'Windows 8', '2016-10-13 20:51:53');
INSERT INTO `tp_login_log` VALUES ('32', '1', '115.34.11.219', '中国 北京 北京 ', 'Chrome(42.0.2311.154)', 'Windows NT', '2016-10-13 20:52:53');
INSERT INTO `tp_login_log` VALUES ('33', '1', '117.139.67.46', '中国 四川 达州 ', 'Chrome(47.0.2526.108)', 'Windows 7', '2016-10-13 21:02:26');
INSERT INTO `tp_login_log` VALUES ('34', '1', '106.38.206.170', '中国 北京 北京 ', 'Firefox(47.0)', 'Windows 7', '2016-10-13 21:03:20');
INSERT INTO `tp_login_log` VALUES ('35', '1', '120.85.85.245', '中国 广东 广州 ', 'Chrome(53.0.2785.143)', 'Windows 10', '2016-10-13 21:03:35');
INSERT INTO `tp_login_log` VALUES ('36', '1', '182.86.240.18', '中国 江西 吉安 ', 'IE(7.0)', 'Windows 7', '2016-10-13 21:03:40');
INSERT INTO `tp_login_log` VALUES ('37', '1', '122.239.66.22', '中国 浙江 温州 ', 'IE(7.0)', 'Windows 7', '2016-10-13 21:05:55');
INSERT INTO `tp_login_log` VALUES ('38', '1', '125.46.222.246', '中国 河南 郑州 ', 'Chrome(53.0.2785.113)', 'Windows 7', '2016-10-13 21:08:03');
INSERT INTO `tp_login_log` VALUES ('39', '1', '119.131.77.67', '中国 广东 广州 ', 'Chrome(53.0.2785.143)', 'Windows 7', '2016-10-13 21:08:13');
INSERT INTO `tp_login_log` VALUES ('40', '1', '115.44.99.173', '中国 广东 深圳 ', 'Chrome(53.0.2785.116)', '未知操作系统', '2016-10-13 21:08:21');
INSERT INTO `tp_login_log` VALUES ('41', '1', '122.192.20.32', '中国 江苏 徐州 ', 'Chrome(37.0.0.0)', 'Linux', '2016-10-13 21:09:02');
INSERT INTO `tp_login_log` VALUES ('42', '1', '111.207.160.68', '中国 北京 北京 ', 'Chrome(54.0.2840.59)', 'Windows 7', '2016-10-13 21:11:09');
INSERT INTO `tp_login_log` VALUES ('43', '1', '58.33.32.216', '中国 上海 上海 ', 'Chrome(49.0.2623.22)', 'Windows 10', '2016-10-13 21:14:50');
INSERT INTO `tp_login_log` VALUES ('44', '1', '42.91.164.249', '中国 甘肃 兰州 ', 'IE(7.0)', 'Windows 10', '2016-10-13 21:19:20');
INSERT INTO `tp_login_log` VALUES ('45', '1', '175.189.125.116', '中国 湖北 武汉 ', 'Chrome(51.0.2704.106)', 'Windows 10', '2016-10-13 21:19:43');
INSERT INTO `tp_login_log` VALUES ('46', '1', '121.4.142.218', '中国 北京 北京 ', 'Chrome(37.0.0.0)', 'Linux', '2016-10-13 21:21:33');
INSERT INTO `tp_login_log` VALUES ('47', '1', '223.73.57.43', '中国 广东 广州 ', 'Chrome(49.0.2623.22)', 'Windows 10', '2016-10-13 21:31:36');
INSERT INTO `tp_login_log` VALUES ('48', '1', '123.169.18.73', '中国 山东 烟台 ', 'Chrome(53.0.2785.143)', 'Windows 7', '2016-10-13 21:37:00');
INSERT INTO `tp_login_log` VALUES ('49', '1', '183.39.45.154', '中国 广东 深圳 ', 'Chrome(53.0.2785.116)', 'Windows 7', '2016-10-13 21:39:45');
INSERT INTO `tp_login_log` VALUES ('50', '1', '223.73.116.16', '中国 广东 广州 ', 'Chrome(50.0.2661.95)', 'Windows 95', '2016-10-13 21:41:02');
INSERT INTO `tp_login_log` VALUES ('51', '1', '59.41.69.106', '中国 广东 广州 ', 'Chrome(53.0.2785.89)', 'Windows 7', '2016-10-13 21:41:48');
INSERT INTO `tp_login_log` VALUES ('52', '1', '39.188.150.8', '中国 浙江 嘉兴 ', 'Firefox(47.0)', 'Windows 10', '2016-10-13 21:51:06');
INSERT INTO `tp_login_log` VALUES ('53', '1', '117.67.220.202', '中国 安徽 合肥 ', 'Chrome(53.0.2785.113)', 'Windows 7', '2016-10-13 21:52:21');
INSERT INTO `tp_login_log` VALUES ('54', '1', '220.163.113.6', '中国 云南 昆明 ', 'IE(7.0)', 'Windows 10', '2016-10-13 21:54:05');
INSERT INTO `tp_login_log` VALUES ('55', '1', '111.196.83.185', '中国 北京 北京 ', 'Chrome(37.0.0.0)', 'Linux', '2016-10-13 21:55:07');
INSERT INTO `tp_login_log` VALUES ('56', '1', '124.79.56.129', '中国 上海 上海 ', 'IE(8.0)', 'Windows 7', '2016-10-13 21:56:47');
INSERT INTO `tp_login_log` VALUES ('57', '1', '49.80.92.187', '中国 江苏 常州 ', 'Chrome(47.0.2526.106)', 'Windows 7', '2016-10-13 21:59:25');
INSERT INTO `tp_login_log` VALUES ('58', '1', '61.188.32.240', '中国 四川 德阳 ', 'Chrome(50.0.2661.95)', 'Windows 95', '2016-10-13 22:03:58');
INSERT INTO `tp_login_log` VALUES ('59', '1', '14.23.233.120', '中国 广东 广州 ', 'IE(8.0)', 'Windows 7', '2016-10-13 22:04:01');
INSERT INTO `tp_login_log` VALUES ('60', '1', '111.37.25.162', '中国 山东 菏泽 ', '未知浏览器()', '未知操作系统', '2016-10-13 22:05:31');
INSERT INTO `tp_login_log` VALUES ('61', '1', '113.240.234.211', '中国 湖南 长沙 ', 'IE(8.0)', 'Windows 7', '2016-10-13 22:16:18');
INSERT INTO `tp_login_log` VALUES ('62', '1', '113.246.93.189', '中国 湖南 长沙 ', 'Firefox(49.0)', 'Windows 8', '2016-10-13 22:18:48');
INSERT INTO `tp_login_log` VALUES ('63', '1', '123.149.75.154', '中国 河南 郑州 ', 'Chrome(45.0.2454.85)', 'Windows 7', '2016-10-13 22:19:34');
INSERT INTO `tp_login_log` VALUES ('64', '1', '115.207.34.53', '中国 浙江 衢州 ', 'Firefox(49.0)', 'Windows 7', '2016-10-13 22:19:43');
INSERT INTO `tp_login_log` VALUES ('65', '1', '58.61.80.187', '中国 广东 深圳 ', 'IE(7.0)', 'Windows 7', '2016-10-13 22:20:07');
INSERT INTO `tp_login_log` VALUES ('66', '1', '183.39.3.247', '中国 广东 深圳 ', 'Chrome(49.0.2623.110)', 'Windows 10', '2016-10-13 22:22:30');
INSERT INTO `tp_login_log` VALUES ('67', '1', '223.73.57.43', '中国 广东 广州 ', 'Chrome(49.0.2623.22)', 'Windows 10', '2016-10-13 22:23:37');
INSERT INTO `tp_login_log` VALUES ('68', '1', '124.205.212.186', '中国 北京 北京 ', 'Chrome(39.0.2171.95)', 'Windows 95', '2016-10-13 22:26:16');
INSERT INTO `tp_login_log` VALUES ('69', '1', '123.149.23.41', '中国 河南 郑州 ', 'Chrome(35.0.1916.153)', 'Windows 7', '2016-10-13 22:32:26');
INSERT INTO `tp_login_log` VALUES ('70', '1', '123.149.3.54', '中国 河南 郑州 ', 'IE(7.0)', 'Windows 7', '2016-10-13 22:38:28');
INSERT INTO `tp_login_log` VALUES ('71', '1', '36.96.58.69', '中国 新疆 哈密地区 ', 'IE(8.0)', 'Windows 7', '2016-10-13 22:38:48');
INSERT INTO `tp_login_log` VALUES ('72', '1', '27.38.56.63', '中国 广东 深圳 ', 'Chrome(49.0.2623.87)', 'Windows 7', '2016-10-13 22:46:21');
INSERT INTO `tp_login_log` VALUES ('73', '1', '121.226.40.161', '中国 江苏 南通 ', 'Chrome(38.0.2125.122)', 'Windows 7', '2016-10-13 22:46:37');
INSERT INTO `tp_login_log` VALUES ('74', '1', '60.216.29.29', '中国 山东 济南 ', 'Chrome(53.0.2785.116)', 'Windows 10', '2016-10-13 22:48:08');
INSERT INTO `tp_login_log` VALUES ('75', '1', '223.73.67.21', '中国 广东 广州 ', 'Chrome(51.0.2704.106)', 'Windows 7', '2016-10-13 22:50:29');
INSERT INTO `tp_login_log` VALUES ('76', '1', '121.32.198.183', '中国 广东 广州 ', 'IE(7.0)', 'Windows 7', '2016-10-13 22:50:34');
INSERT INTO `tp_login_log` VALUES ('77', '1', '118.29.25.115', '中国 天津 天津 ', 'Chrome(47.0.2526.80)', 'Windows 10', '2016-10-13 22:51:57');
INSERT INTO `tp_login_log` VALUES ('78', '1', '123.79.0.217', '中国 广西 桂林 ', 'Chrome(51.0.2704.63)', 'Windows 7', '2016-10-13 22:59:23');
INSERT INTO `tp_login_log` VALUES ('79', '1', '223.73.0.28', '中国 广东 深圳 ', 'Chrome(44.0.2403.130)', 'Windows 7', '2016-10-13 23:00:35');
INSERT INTO `tp_login_log` VALUES ('80', '1', '14.209.49.17', '中国 广东 肇庆 ', 'Chrome(46.0.2490.86)', 'Windows 7', '2016-10-13 23:01:42');
INSERT INTO `tp_login_log` VALUES ('81', '1', '124.126.199.162', '中国 北京 北京 ', 'Firefox(49.0)', 'Windows 10', '2016-10-13 23:02:38');
INSERT INTO `tp_login_log` VALUES ('82', '1', '116.22.234.33', '中国 广东 广州 ', 'IE(7.0)', 'Windows 7', '2016-10-13 23:05:49');
INSERT INTO `tp_login_log` VALUES ('83', '1', '222.128.173.21', '中国 北京 北京 ', 'Chrome(53.0.2785.116)', 'Windows 10', '2016-10-13 23:11:46');
INSERT INTO `tp_login_log` VALUES ('84', '1', '183.53.93.174', '中国 广东 东莞 ', 'IE(7.0)', 'Windows 7', '2016-10-13 23:12:31');
INSERT INTO `tp_login_log` VALUES ('85', '1', '101.107.77.157', '中国 广东 广州 ', 'IE(7.0)', 'Windows 7', '2016-10-13 23:13:44');
INSERT INTO `tp_login_log` VALUES ('86', '1', '171.214.197.50', '中国 四川 成都 ', 'Chrome(53.0.2785.143)', 'Windows 7', '2016-10-13 23:15:49');
INSERT INTO `tp_login_log` VALUES ('87', '1', '171.110.47.73', '中国 广西 玉林 ', 'Chrome(53.0.2785.116)', 'Windows 7', '2016-10-13 23:20:05');
INSERT INTO `tp_login_log` VALUES ('88', '1', '119.121.213.250', '中国 广东 汕头 ', 'IE(7.0)', 'Windows 10', '2016-10-13 23:21:11');
INSERT INTO `tp_login_log` VALUES ('89', '1', '222.128.153.44', '中国 北京 北京 ', '未知浏览器()', '未知操作系统', '2016-10-13 23:23:27');
INSERT INTO `tp_login_log` VALUES ('90', '1', '157.61.129.13', '中国 广东 汕头 ', 'Firefox(49.0)', 'Windows 7', '2016-10-13 23:24:20');
INSERT INTO `tp_login_log` VALUES ('91', '1', '114.61.86.170', '中国 上海 上海 ', 'Chrome(51.0.2704.106)', 'Windows 7', '2016-10-13 23:26:35');
INSERT INTO `tp_login_log` VALUES ('92', '1', '123.121.149.122', '中国 北京 北京 ', 'Chrome(53.0.2785.116)', 'Windows 7', '2016-10-13 23:27:41');
INSERT INTO `tp_login_log` VALUES ('93', '1', '223.73.53.76', '中国 广东 广州 ', 'Chrome(49.0.2623.22)', 'Windows 10', '2016-10-13 23:33:41');
INSERT INTO `tp_login_log` VALUES ('94', '1', '182.88.162.113', '中国 广西 南宁 ', 'Chrome(50.0.2661.75)', '未知操作系统', '2016-10-13 23:36:42');
INSERT INTO `tp_login_log` VALUES ('95', '1', '121.205.153.216', '中国 福建 宁德 ', 'Chrome(45.0.2454.101)', 'Windows 7', '2016-10-13 23:38:25');
INSERT INTO `tp_login_log` VALUES ('96', '1', '106.117.116.21', '中国 河北 石家庄 ', 'IE(9.0)', 'Windows 7', '2016-10-13 23:46:24');
INSERT INTO `tp_login_log` VALUES ('97', '1', '27.38.56.2', '中国 广东 深圳 ', 'Firefox(49.0)', 'Windows 10', '2016-10-13 23:53:14');
INSERT INTO `tp_login_log` VALUES ('98', '1', '124.205.212.114', '中国 北京 北京 ', 'Chrome(51.0.2704.106)', 'Windows 10', '2016-10-13 23:53:20');
INSERT INTO `tp_login_log` VALUES ('99', '1', '14.103.47.147', '中国 广东 深圳 ', 'Chrome(47.0.2526.80)', 'Windows 7', '2016-10-13 23:58:18');
INSERT INTO `tp_login_log` VALUES ('100', '1', '123.120.17.69', '中国 北京 北京 ', 'Chrome(53.0.2785.116)', '未知操作系统', '2016-10-14 00:05:31');
INSERT INTO `tp_login_log` VALUES ('101', '1', '117.34.177.170', '中国 陕西 西安 ', 'IE(9.0)', 'Windows 7', '2016-10-14 00:05:43');
INSERT INTO `tp_login_log` VALUES ('102', '1', '124.205.212.24', '中国 北京 北京 ', 'Chrome(52.0.2743.116)', '未知操作系统', '2016-10-14 00:08:05');
INSERT INTO `tp_login_log` VALUES ('103', '1', '223.74.147.145', '中国 广东 广州 ', 'Chrome(47.0.2526.80)', '未知操作系统', '2016-10-14 00:17:13');
INSERT INTO `tp_login_log` VALUES ('104', '1', '121.207.182.246', '中国 福建 厦门 ', 'Chrome(52.0.2743.116)', 'Windows 8', '2016-10-14 00:21:32');
INSERT INTO `tp_login_log` VALUES ('105', '1', '58.248.232.210', '中国 广东 广州 ', 'Chrome(53.0.2785.116)', 'Windows NT', '2016-10-14 00:26:20');
INSERT INTO `tp_login_log` VALUES ('106', '1', '221.218.32.113', '中国 北京 北京 ', 'Chrome(50.0.2661.102)', 'Windows NT', '2016-10-14 00:28:36');
INSERT INTO `tp_login_log` VALUES ('107', '1', '58.248.232.210', '中国 广东 广州 ', 'Chrome(53.0.2785.116)', 'Windows NT', '2016-10-14 00:31:02');
INSERT INTO `tp_login_log` VALUES ('108', '1', '14.176.201.142', '越南 越南  ', 'Firefox(49.0)', 'Linux', '2016-10-14 00:39:28');
INSERT INTO `tp_login_log` VALUES ('109', '1', '106.117.116.21', '中国 河北 石家庄 ', 'IE(7.0)', 'Windows 7', '2016-10-14 01:00:30');
INSERT INTO `tp_login_log` VALUES ('110', '1', '14.150.212.184', '中国 广东 广州 ', 'Firefox(49.0)', 'Windows 10', '2016-10-14 01:02:16');
INSERT INTO `tp_login_log` VALUES ('111', '1', '123.55.184.67', '中国 河南 三门峡 ', 'Chrome(53.0.2785.116)', 'Windows 10', '2016-10-14 01:07:57');
INSERT INTO `tp_login_log` VALUES ('112', '1', '124.31.108.206', '中国 西藏 拉萨 ', 'IE(8.0)', 'Windows 7', '2016-10-14 01:16:08');
INSERT INTO `tp_login_log` VALUES ('113', '1', '14.152.69.188', '中国 广东 广州 ', '未知浏览器()', 'Linux', '2016-10-14 01:46:15');
INSERT INTO `tp_login_log` VALUES ('114', '1', '182.18.104.192', '中国 北京 北京 ', 'Firefox(42.0)', 'Windows 7', '2016-10-14 04:11:09');
INSERT INTO `tp_login_log` VALUES ('115', '1', '117.174.159.75', '中国 四川 泸州 ', 'IE(7.0)', 'Windows 7', '2016-10-14 05:48:51');
INSERT INTO `tp_login_log` VALUES ('116', '1', '45.221.238.247', '中国 广东 深圳 ', 'IE(7.0)', 'Windows 7', '2016-10-14 07:05:18');
INSERT INTO `tp_login_log` VALUES ('117', '1', '103.43.185.49', '中国 北京 北京 ', 'Chrome(37.0.0.0)', 'Linux', '2016-10-14 07:45:22');
INSERT INTO `tp_login_log` VALUES ('118', '1', '106.121.5.92', '中国 北京 北京 ', 'Chrome(46.0.2490.85)', 'Linux', '2016-10-14 08:02:18');
INSERT INTO `tp_login_log` VALUES ('119', '1', '183.52.107.83', '中国 广东 江门 ', 'Firefox(49.0)', 'Windows 7', '2016-10-14 08:06:51');
INSERT INTO `tp_login_log` VALUES ('120', '1', '222.245.0.113', '中国 湖南 株洲 ', 'Chrome(53.0.2785.143)', 'Windows 7', '2016-10-14 08:18:37');
INSERT INTO `tp_login_log` VALUES ('121', '1', '123.150.105.241', '中国 天津 天津 ', 'Chrome(53.0.2785.143)', 'Windows 10', '2016-10-14 08:19:37');
INSERT INTO `tp_login_log` VALUES ('122', '1', '182.243.124.121', '中国 云南 普洱 ', 'Chrome(53.0.2785.143)', 'Windows 10', '2016-10-14 08:21:01');
INSERT INTO `tp_login_log` VALUES ('123', '1', '1.84.206.218', '中国 陕西 宝鸡 ', 'IE(7.0)', 'Windows 7', '2016-10-14 08:21:49');
INSERT INTO `tp_login_log` VALUES ('124', '1', '1.26.53.79', '中国 内蒙古 通辽 ', 'Chrome(53.0.2785.143)', 'Windows 10', '2016-10-14 08:25:26');
INSERT INTO `tp_login_log` VALUES ('125', '1', '182.148.106.227', '中国 四川 成都 ', 'IE(7.0)', 'Windows 10', '2016-10-14 08:29:22');
INSERT INTO `tp_login_log` VALUES ('126', '1', '122.192.255.228', '中国 江苏 连云港 ', '傲游(4.9.3.1000)', 'Windows 10', '2016-10-14 08:30:00');
INSERT INTO `tp_login_log` VALUES ('127', '1', '116.247.96.94', '中国 上海 上海 ', 'IE(7.0)', 'Windows XP', '2016-10-14 08:34:23');
INSERT INTO `tp_login_log` VALUES ('128', '1', '1.192.241.143', '中国 河南 郑州 ', 'Chrome(52.0.2743.116)', 'Windows 7', '2016-10-14 08:34:32');
INSERT INTO `tp_login_log` VALUES ('129', '1', '221.202.167.66', '中国 辽宁 葫芦岛 ', 'Chrome(53.0.2785.143)', 'Windows 10', '2016-10-14 08:34:43');
INSERT INTO `tp_login_log` VALUES ('130', '1', '111.203.248.34', '中国 北京 北京 ', '傲游(5.0.1.1700)', 'Windows 10', '2016-10-14 08:34:56');
INSERT INTO `tp_login_log` VALUES ('131', '1', '139.211.220.149', '中国 吉林 通化 ', 'IE(7.0)', 'Windows 10', '2016-10-14 08:35:48');
INSERT INTO `tp_login_log` VALUES ('132', '1', '59.38.35.86', '中国 广东 珠海 ', 'Chrome(49.0.2623.112)', 'Windows 7', '2016-10-14 08:39:10');
INSERT INTO `tp_login_log` VALUES ('133', '1', '222.245.0.113', '中国 湖南 株洲 ', 'Chrome(53.0.2785.143)', 'Windows 7', '2016-10-14 08:40:55');
INSERT INTO `tp_login_log` VALUES ('134', '1', '221.234.237.213', '中国 湖北 武汉 ', 'Chrome(53.0.2785.101)', 'Linux', '2016-10-14 08:42:30');
INSERT INTO `tp_login_log` VALUES ('135', '1', '115.238.81.206', '中国 浙江 杭州 ', 'Firefox(49.0)', 'Windows 7', '2016-10-14 08:42:45');
INSERT INTO `tp_login_log` VALUES ('136', '1', '119.57.173.135', '中国 北京 北京 ', 'Chrome(53.0.2785.116)', 'Windows 7', '2016-10-14 08:45:25');
INSERT INTO `tp_login_log` VALUES ('137', '1', '119.147.44.241', '中国 广东 深圳 ', 'Chrome(53.0.2785.143)', 'Windows 7', '2016-10-14 08:46:45');
INSERT INTO `tp_login_log` VALUES ('138', '1', '60.171.153.102', '中国 安徽 安庆 ', 'IE(7.0)', 'Windows 7', '2016-10-14 08:48:17');
INSERT INTO `tp_login_log` VALUES ('139', '1', '36.7.78.188', '中国 安徽 合肥 ', 'Firefox(49.0)', 'Windows 7', '2016-10-14 08:48:43');
INSERT INTO `tp_login_log` VALUES ('140', '1', '113.143.181.98', '中国 陕西 西安 ', 'Firefox(49.0)', 'Windows 10', '2016-10-14 08:50:04');
INSERT INTO `tp_login_log` VALUES ('141', '1', '112.65.46.54', '中国 上海 上海 ', 'Chrome(49.0.2623.22)', 'Windows 7', '2016-10-14 08:51:43');
INSERT INTO `tp_login_log` VALUES ('142', '1', '42.122.180.142', '中国 天津 天津 ', 'IE(7.0)', 'Windows 7', '2016-10-14 08:54:48');
INSERT INTO `tp_login_log` VALUES ('143', '1', '183.129.211.50', '中国 浙江 杭州 ', 'Chrome(53.0.2785.116)', '未知操作系统', '2016-10-14 09:02:20');
INSERT INTO `tp_login_log` VALUES ('144', '1', '14.17.44.217', '中国 广东 深圳 ', 'Chrome(37.0.0.0)', 'Linux', '2016-10-14 09:02:27');
INSERT INTO `tp_login_log` VALUES ('145', '1', '122.225.84.22', '中国 浙江 嘉兴 ', 'Firefox(49.0)', 'Windows 10', '2016-10-14 09:02:31');
INSERT INTO `tp_login_log` VALUES ('146', '1', '101.86.161.111', '中国 上海 上海 ', 'IE(7.0)', 'Windows 7', '2016-10-14 09:06:03');
INSERT INTO `tp_login_log` VALUES ('147', '1', '112.16.16.143', '中国 浙江 温州 ', 'Firefox(49.0)', 'Windows 7', '2016-10-14 09:07:52');
INSERT INTO `tp_login_log` VALUES ('148', '1', '121.19.186.209', '中国 河北 保定 ', 'Firefox(49.0)', 'Windows 10', '2016-10-14 09:11:10');
INSERT INTO `tp_login_log` VALUES ('149', '1', '180.104.187.22', '中国 江苏 徐州 ', 'Chrome(50.0.2661.102)', '未知操作系统', '2016-10-14 09:13:43');
INSERT INTO `tp_login_log` VALUES ('150', '1', '182.148.56.51', '中国 四川 成都 ', 'Chrome(53.0.2785.143)', 'Windows 10', '2016-10-14 09:14:05');
INSERT INTO `tp_login_log` VALUES ('151', '1', '101.104.9.155', '中国 广东 广州 ', 'Firefox(49.0)', 'Windows 7', '2016-10-14 09:17:37');
INSERT INTO `tp_login_log` VALUES ('152', '1', '113.108.121.75', '中国 广东 深圳 ', 'IE(7.0)', 'Windows 7', '2016-10-14 09:18:03');
INSERT INTO `tp_login_log` VALUES ('153', '1', '114.248.42.174', '中国 北京 北京 ', 'IE(7.0)', 'Windows 10', '2016-10-14 09:19:42');
INSERT INTO `tp_login_log` VALUES ('154', '1', '118.186.4.154', '中国 北京 北京 ', 'Chrome(53.0.2785.143)', '未知操作系统', '2016-10-14 09:20:32');
INSERT INTO `tp_login_log` VALUES ('155', '1', '119.137.31.223', '中国 广东 深圳 ', '未知浏览器()', '未知操作系统', '2016-10-14 09:21:38');
INSERT INTO `tp_login_log` VALUES ('156', '1', '113.118.244.204', '中国 广东 深圳 ', 'Firefox(48.0)', 'Windows 7', '2016-10-14 09:23:16');
INSERT INTO `tp_login_log` VALUES ('157', '1', '114.221.203.97', '中国 江苏 南京 ', 'Chrome(50.0.2661.95)', 'Windows 95', '2016-10-14 09:24:28');
INSERT INTO `tp_login_log` VALUES ('158', '1', '27.115.20.58', '中国 上海 上海 ', 'Chrome(53.0.2785.113)', 'Windows 10', '2016-10-14 09:24:30');
INSERT INTO `tp_login_log` VALUES ('159', '1', '182.145.101.20', '中国 四川 内江 ', 'Chrome(42.0.2311.135)', 'Windows 8', '2016-10-14 09:24:37');
INSERT INTO `tp_login_log` VALUES ('160', '1', '117.185.68.58', '中国 上海 上海 ', 'Chrome(47.0.2526.80)', 'Windows 7', '2016-10-14 09:25:13');
INSERT INTO `tp_login_log` VALUES ('161', '1', '117.185.68.58', '中国 上海 上海 ', 'Chrome(53.0.2785.116)', 'Windows 7', '2016-10-14 09:25:47');
INSERT INTO `tp_login_log` VALUES ('162', '1', '14.20.8.84', '中国 广东 深圳 ', 'Chrome(50.0.2661.95)', 'Windows 95', '2016-10-14 09:27:41');
INSERT INTO `tp_login_log` VALUES ('163', '1', '223.167.101.145', '中国 上海 上海 ', 'IE(8.0)', 'Windows 7', '2016-10-14 09:28:57');
INSERT INTO `tp_login_log` VALUES ('164', '1', '112.229.187.96', '中国 山东 济南 ', 'IE(7.0)', 'Windows 7', '2016-10-14 09:30:00');
INSERT INTO `tp_login_log` VALUES ('165', '1', '210.78.137.38', '中国 北京 北京 ', 'Chrome(28.0.1500.95)', 'Windows 95', '2016-10-14 09:30:33');
INSERT INTO `tp_login_log` VALUES ('166', '1', '114.255.28.139', '中国 北京 北京 ', 'IE(7.0)', 'Windows 10', '2016-10-14 09:30:48');
INSERT INTO `tp_login_log` VALUES ('167', '1', '42.243.109.4', '中国 云南 昆明 ', 'Firefox(49.0)', 'Windows 10', '2016-10-14 09:30:51');
INSERT INTO `tp_login_log` VALUES ('168', '1', '118.180.249.66', '中国 甘肃 兰州 ', 'Chrome(47.0.2526.80)', 'Windows 10', '2016-10-14 09:31:29');
INSERT INTO `tp_login_log` VALUES ('169', '1', '112.87.143.97', '中国 江苏 苏州 ', 'IE(7.0)', 'Windows 7', '2016-10-14 09:34:22');
INSERT INTO `tp_login_log` VALUES ('170', '1', '180.140.167.200', '中国 广西 南宁 ', 'Firefox(49.0)', '未知操作系统', '2016-10-14 09:35:16');
INSERT INTO `tp_login_log` VALUES ('171', '1', '123.235.199.114', '中国 山东 青岛 ', 'Chrome(53.0.2785.116)', 'Windows 7', '2016-10-14 09:36:36');
INSERT INTO `tp_login_log` VALUES ('172', '1', '180.118.205.14', '中国 江苏 镇江 ', 'IE(7.0)', 'Windows 10', '2016-10-14 09:37:00');
INSERT INTO `tp_login_log` VALUES ('173', '1', '27.154.45.32', '中国 福建 厦门 ', 'Chrome(45.0.2454.93)', 'Windows 7', '2016-10-14 09:37:51');
INSERT INTO `tp_login_log` VALUES ('174', '1', '140.207.154.14', '中国 上海 上海 ', 'Chrome(51.0.2704.84)', '未知操作系统', '2016-10-14 09:38:14');
INSERT INTO `tp_login_log` VALUES ('175', '1', '27.46.115.80', '中国 广东 深圳 ', 'Chrome(53.0.2785.116)', 'Windows 10', '2016-10-14 09:38:45');
INSERT INTO `tp_login_log` VALUES ('176', '1', '218.201.183.6', '中国 山东 临沂 ', 'IE(11.0)', 'Windows 10', '2016-10-14 09:39:00');
INSERT INTO `tp_login_log` VALUES ('177', '1', '124.205.209.122', '中国 北京 北京 ', 'Chrome(53.0.2785.143)', '未知操作系统', '2016-10-14 09:40:45');
INSERT INTO `tp_login_log` VALUES ('178', '1', '116.228.202.86', '中国 上海 上海 ', 'Firefox(49.0)', 'Windows 7', '2016-10-14 09:41:04');
INSERT INTO `tp_login_log` VALUES ('179', '1', '220.189.218.2', '中国 浙江 宁波 ', 'Chrome(47.0.2526.80)', 'Windows 10', '2016-10-14 09:41:49');
INSERT INTO `tp_login_log` VALUES ('180', '1', '123.182.241.42', '中国 河北 石家庄 ', 'IE(7.0)', 'Windows 10', '2016-10-14 09:45:43');
INSERT INTO `tp_login_log` VALUES ('181', '1', '36.7.64.14', '中国 安徽 合肥 ', 'IE(7.0)', 'Windows 7', '2016-10-14 09:47:31');
INSERT INTO `tp_login_log` VALUES ('182', '1', '144.255.34.236', '中国 山东 烟台 ', 'Firefox(49.0)', 'Windows 10', '2016-10-14 09:48:14');
INSERT INTO `tp_login_log` VALUES ('183', '1', '106.86.170.224', '中国 重庆 重庆 ', 'Chrome(42.0.2311.154)', 'Windows 10', '2016-10-14 09:49:00');
INSERT INTO `tp_login_log` VALUES ('184', '1', '1.95.29.1', '中国 北京 北京 ', 'Firefox(49.0)', 'Windows 10', '2016-10-14 09:50:45');
INSERT INTO `tp_login_log` VALUES ('185', '1', '116.228.209.194', '中国 上海 上海 ', 'Chrome(50.0.2661.87)', 'Windows 7', '2016-10-14 09:50:57');
INSERT INTO `tp_login_log` VALUES ('186', '1', '116.228.209.194', '中国 上海 上海 ', 'IE(7.0)', 'Windows NT', '2016-10-14 09:54:53');
INSERT INTO `tp_login_log` VALUES ('187', '1', '116.25.96.16', '中国 广东 深圳 ', 'Firefox(49.0)', 'Windows 10', '2016-10-14 09:56:42');
INSERT INTO `tp_login_log` VALUES ('188', '1', '119.142.85.54', '中国 广东 中山 ', 'Firefox(49.0)', 'Windows 7', '2016-10-14 09:56:56');
INSERT INTO `tp_login_log` VALUES ('189', '1', '219.132.152.252', '中国 广东 梅州 ', 'Chrome(53.0.2785.143)', '未知操作系统', '2016-10-14 09:58:52');
INSERT INTO `tp_login_log` VALUES ('190', '1', '110.84.39.140', '中国 福建 厦门 ', 'Chrome(54.0.2840.59)', 'Windows 7', '2016-10-14 10:01:01');
INSERT INTO `tp_login_log` VALUES ('191', '1', '218.72.252.107', '中国 浙江 衢州 ', 'Chrome(51.0.2704.106)', 'Windows 7', '2016-10-14 10:03:15');
INSERT INTO `tp_login_log` VALUES ('192', '1', '14.158.237.196', '中国 广东 汕头 ', 'Chrome(53.0.2785.143)', 'Windows 10', '2016-10-14 10:04:24');
INSERT INTO `tp_login_log` VALUES ('193', '1', '222.217.41.60', '中国 广西 柳州 ', 'Chrome(53.0.2785.143)', 'Windows 7', '2016-10-14 10:09:06');
INSERT INTO `tp_login_log` VALUES ('194', '1', '114.244.133.9', '中国 北京 北京 ', 'IE(7.0)', 'Windows 10', '2016-10-14 10:09:13');
INSERT INTO `tp_login_log` VALUES ('195', '1', '119.145.83.53', '中国 广东 广州 ', 'Firefox(49.0)', 'Windows 10', '2016-10-14 10:10:12');
INSERT INTO `tp_login_log` VALUES ('196', '1', '58.50.148.115', '中国 湖北 宜昌 ', 'Chrome(53.0.2785.143)', 'Windows 7', '2016-10-14 10:13:14');
INSERT INTO `tp_login_log` VALUES ('197', '1', '171.43.239.168', '中国 湖北 武汉 ', 'Firefox(49.0)', 'Windows 7', '2016-10-14 10:15:44');
INSERT INTO `tp_login_log` VALUES ('198', '1', '118.163.124.90', '中国 台湾  ', 'Chrome(53.0.2785.143)', '未知操作系统', '2016-10-14 10:17:48');
INSERT INTO `tp_login_log` VALUES ('199', '1', '115.206.60.136', '中国 浙江 杭州 ', 'Chrome(50.0.2661.102)', 'Windows 10', '2016-10-14 10:18:07');
INSERT INTO `tp_login_log` VALUES ('200', '1', '120.42.99.46', '中国 福建 厦门 ', 'Firefox(49.0)', 'Windows 7', '2016-10-14 10:22:15');
INSERT INTO `tp_login_log` VALUES ('201', '1', '218.88.26.66', '中国 四川 成都 ', 'Chrome(53.0.2785.116)', 'Windows NT', '2016-10-14 10:23:26');
INSERT INTO `tp_login_log` VALUES ('202', '1', '182.150.27.120', '中国 四川 成都 ', 'IE(7.0)', 'Windows 7', '2016-10-14 10:24:46');
INSERT INTO `tp_login_log` VALUES ('203', '1', '113.68.65.248', '中国 广东 广州 ', 'Chrome(50.0.2661.95)', 'Windows 95', '2016-10-14 10:25:39');
INSERT INTO `tp_login_log` VALUES ('204', '1', '58.20.36.79', '中国 湖南 长沙 ', 'IE(8.0)', 'Windows 7', '2016-10-14 10:27:33');
INSERT INTO `tp_login_log` VALUES ('205', '1', '180.175.92.175', '中国 上海 上海 ', 'Chrome(53.0.2785.143)', 'Windows 10', '2016-10-14 10:28:27');
INSERT INTO `tp_login_log` VALUES ('206', '1', '113.248.151.131', '中国 重庆 重庆 ', 'IE(7.0)', 'Windows 10', '2016-10-14 10:28:56');
INSERT INTO `tp_login_log` VALUES ('207', '1', '219.145.23.252', '中国 陕西 西安 ', 'Chrome(52.0.2743.116)', 'Windows 7', '2016-10-14 10:29:34');
INSERT INTO `tp_login_log` VALUES ('208', '1', '114.238.15.218', '中国 江苏 淮安 ', 'Chrome(53.0.2785.143)', 'Windows 10', '2016-10-14 10:29:35');
INSERT INTO `tp_login_log` VALUES ('209', '1', '211.93.3.114', '中国 宁夏 银川 ', 'IE(7.0)', 'Windows 7', '2016-10-14 10:31:48');
INSERT INTO `tp_login_log` VALUES ('210', '1', '222.83.177.236', '中国 广西 河池 ', 'Chrome(52.0.2743.116)', 'Windows 10', '2016-10-14 10:32:48');
INSERT INTO `tp_login_log` VALUES ('211', '1', '60.188.227.131', '中国 浙江 台州 ', 'Chrome(50.0.2661.102)', 'Windows 10', '2016-10-14 10:36:50');
INSERT INTO `tp_login_log` VALUES ('212', '1', '116.25.98.2', '中国 广东 深圳 ', 'Firefox(49.0)', 'Windows 10', '2016-10-14 10:38:21');
INSERT INTO `tp_login_log` VALUES ('213', '1', '118.247.145.31', '中国 北京 北京 ', 'IE(7.0)', 'Windows 10', '2016-10-14 10:38:36');
INSERT INTO `tp_login_log` VALUES ('214', '1', '219.134.165.232', '中国 广东 深圳 ', 'Chrome(52.0.2743.116)', 'Windows 10', '2016-10-14 10:38:38');
INSERT INTO `tp_login_log` VALUES ('215', '1', '113.66.148.19', '中国 广东 广州 ', 'Chrome(49.0.2623.22)', 'Windows 7', '2016-10-14 10:39:23');
INSERT INTO `tp_login_log` VALUES ('216', '1', '118.247.145.31', '中国 北京 北京 ', 'Chrome(51.0.2704.106)', 'Windows 10', '2016-10-14 10:41:07');
INSERT INTO `tp_login_log` VALUES ('217', '1', '60.253.206.106', '中国 北京 北京 ', 'Firefox(45.0)', 'Windows 7', '2016-10-14 10:42:21');
INSERT INTO `tp_login_log` VALUES ('218', '1', '183.156.117.204', '中国 浙江 杭州 ', 'Chrome(53.0.2785.143)', 'Windows 10', '2016-10-14 10:42:38');
INSERT INTO `tp_login_log` VALUES ('219', '1', '58.250.204.20', '中国 广东 深圳 ', 'Chrome(53.0.2785.113)', 'Windows 10', '2016-10-14 10:43:04');
INSERT INTO `tp_login_log` VALUES ('220', '1', '183.14.122.125', '中国 广东 深圳 ', 'Chrome(49.0.2623.75)', 'Windows XP', '2016-10-14 10:43:19');
INSERT INTO `tp_login_log` VALUES ('221', '1', '61.144.197.186', '中国 广东 深圳 ', 'Firefox(49.0)', 'Windows 7', '2016-10-14 10:43:22');
INSERT INTO `tp_login_log` VALUES ('222', '1', '183.15.192.44', '中国 广东 深圳 ', '未知浏览器()', '未知操作系统', '2016-10-14 10:46:47');
INSERT INTO `tp_login_log` VALUES ('223', '1', '36.5.182.128', '中国 安徽 合肥 ', 'Firefox(49.0)', 'Windows 10', '2016-10-14 10:48:03');
INSERT INTO `tp_login_log` VALUES ('224', '1', '125.122.81.46', '中国 浙江 杭州 ', 'Firefox(40.0)', 'Windows 7', '2016-10-14 10:48:27');
INSERT INTO `tp_login_log` VALUES ('225', '1', '183.39.153.240', '中国 广东 深圳 ', 'Chrome(54.0.2840.59)', 'Windows 7', '2016-10-14 10:48:31');
INSERT INTO `tp_login_log` VALUES ('226', '1', '218.17.157.203', '中国 广东 深圳 ', 'IE(8.0)', 'Windows 7', '2016-10-14 10:51:22');
INSERT INTO `tp_login_log` VALUES ('227', '1', '221.6.121.114', '中国 江苏 无锡 ', 'Chrome(53.0.2785.143)', 'Windows 10', '2016-10-14 10:51:55');
INSERT INTO `tp_login_log` VALUES ('228', '1', '113.68.64.238', '中国 广东 广州 ', 'IE(7.0)', 'Windows 10', '2016-10-14 10:52:57');
INSERT INTO `tp_login_log` VALUES ('229', '1', '116.228.209.194', '中国 上海 上海 ', 'Chrome(45.0.2454.93)', 'Windows NT', '2016-10-14 10:53:04');
INSERT INTO `tp_login_log` VALUES ('230', '1', '61.163.138.85', '中国 河南 郑州 ', 'IE(7.0)', 'Windows 10', '2016-10-14 10:59:06');
INSERT INTO `tp_login_log` VALUES ('231', '1', '183.196.233.193', '中国 河北 承德 ', 'Chrome(44.0.2403.157)', '未知操作系统', '2016-10-14 10:59:57');
INSERT INTO `tp_login_log` VALUES ('232', '1', '42.199.52.212', '中国 广东 广州 ', 'Chrome(52.0.2743.116)', 'Windows 7', '2016-10-14 11:00:39');
INSERT INTO `tp_login_log` VALUES ('233', '1', '113.222.192.52', '中国 湖南 株洲 ', 'Chrome(53.0.2785.143)', 'Windows 10', '2016-10-14 11:09:36');
INSERT INTO `tp_login_log` VALUES ('234', '1', '183.155.58.173', '中国 浙江 金华 ', 'IE(7.0)', 'Windows 10', '2016-10-14 11:11:18');
INSERT INTO `tp_login_log` VALUES ('235', '1', '183.39.54.185', '中国 广东 深圳 ', 'Firefox(49.0)', 'Windows 7', '2016-10-14 11:13:35');
INSERT INTO `tp_login_log` VALUES ('236', '1', '39.155.185.8', '中国 北京 北京 ', 'Chrome(51.0.2704.106)', 'Windows 10', '2016-10-14 11:13:49');
INSERT INTO `tp_login_log` VALUES ('237', '1', '221.222.186.144', '中国 北京 北京 ', 'Firefox(49.0)', 'Windows 10', '2016-10-14 11:14:04');
INSERT INTO `tp_login_log` VALUES ('238', '1', '180.152.10.49', '中国 上海 上海 ', 'Chrome(54.0.2840.8)', 'Windows 7', '2016-10-14 11:15:26');
INSERT INTO `tp_login_log` VALUES ('239', '1', '120.202.21.4', '中国 湖北 武汉 ', 'Chrome(52.0.2743.116)', 'Windows 10', '2016-10-14 11:16:27');
INSERT INTO `tp_login_log` VALUES ('240', '1', '112.54.13.194', '中国 辽宁 葫芦岛 ', 'Chrome(50.0.2661.102)', 'Windows 10', '2016-10-14 11:17:47');
INSERT INTO `tp_login_log` VALUES ('241', '1', '60.165.134.87', '中国 甘肃 白银 ', 'IE(7.0)', 'Windows 7', '2016-10-14 11:17:56');
INSERT INTO `tp_login_log` VALUES ('242', '1', '183.15.241.79', '中国 广东 深圳 ', 'Chrome(53.0.2785.143)', 'Windows 10', '2016-10-14 11:18:09');
INSERT INTO `tp_login_log` VALUES ('243', '1', '118.247.145.31', '中国 北京 北京 ', 'Chrome(51.0.2704.106)', 'Windows 10', '2016-10-14 11:18:47');
INSERT INTO `tp_login_log` VALUES ('244', '1', '111.202.111.220', '中国 北京 北京 ', 'Firefox(49.0)', 'Windows 10', '2016-10-14 11:19:13');
INSERT INTO `tp_login_log` VALUES ('245', '1', '116.25.98.2', '中国 广东 深圳 ', 'Firefox(49.0)', 'Windows 10', '2016-10-14 11:19:24');
INSERT INTO `tp_login_log` VALUES ('246', '1', '113.118.108.65', '中国 广东 深圳 ', 'Chrome(47.0.2526.111)', 'Windows XP', '2016-10-14 11:23:25');
INSERT INTO `tp_login_log` VALUES ('247', '1', '222.85.130.130', '中国 贵州 贵阳 ', 'IE(7.0)', 'Windows 7', '2016-10-14 11:31:16');
INSERT INTO `tp_login_log` VALUES ('248', '1', '113.232.118.104', '中国 辽宁 沈阳 ', 'Chrome(54.0.2840.59)', 'Windows 10', '2016-10-14 11:31:57');
INSERT INTO `tp_login_log` VALUES ('249', '1', '59.42.40.119', '中国 广东 广州 ', 'Chrome(53.0.2785.143)', 'Windows 7', '2016-10-14 11:32:24');
INSERT INTO `tp_login_log` VALUES ('250', '1', '125.123.86.131', '中国 浙江 嘉兴 ', 'Chrome(53.0.2785.116)', 'Windows 10', '2016-10-14 11:41:43');
INSERT INTO `tp_login_log` VALUES ('251', '1', '124.193.250.90', '中国 北京 北京 ', 'Chrome(51.0.2704.84)', 'Linux', '2016-10-14 11:43:16');
INSERT INTO `tp_login_log` VALUES ('252', '1', '111.198.75.7', '中国 北京 北京 ', '未知浏览器()', '未知操作系统', '2016-10-14 11:45:01');
INSERT INTO `tp_login_log` VALUES ('253', '1', '61.163.39.245', '中国 河南 信阳 ', 'Chrome(55.0.2882.4)', 'Windows 7', '2016-10-14 11:46:37');
INSERT INTO `tp_login_log` VALUES ('254', '1', '58.62.221.82', '中国 广东 广州 ', 'Chrome(49.0.2623.22)', 'Windows 10', '2016-10-14 11:47:06');
INSERT INTO `tp_login_log` VALUES ('255', '1', '183.39.53.45', '中国 广东 深圳 ', 'Firefox(49.0)', 'Windows 7', '2016-10-14 11:48:53');
INSERT INTO `tp_login_log` VALUES ('256', '1', '121.232.145.178', '中国 江苏 镇江 ', 'Firefox(49.0)', 'Windows NT', '2016-10-14 11:50:24');
INSERT INTO `tp_login_log` VALUES ('257', '1', '61.136.109.138', '中国 河南 新乡 ', 'Chrome(53.0.2785.143)', 'Windows 10', '2016-10-14 11:52:15');
INSERT INTO `tp_login_log` VALUES ('258', '1', '222.245.0.113', '中国 湖南 株洲 ', 'Chrome(53.0.2785.143)', 'Windows 7', '2016-10-14 11:54:25');
INSERT INTO `tp_login_log` VALUES ('259', '1', '120.86.59.85', '中国 广东 惠州 ', '傲游(4.4.5.3000)', 'Windows 7', '2016-10-14 11:55:23');
INSERT INTO `tp_login_log` VALUES ('260', '1', '175.43.158.29', '中国 福建 泉州 ', 'IE(7.0)', 'Windows 10', '2016-10-14 11:59:03');
INSERT INTO `tp_login_log` VALUES ('261', '1', '202.103.40.14', '中国 湖北 武汉 ', 'Chrome(45.0.2454.85)', 'Windows 7', '2016-10-14 11:59:40');
INSERT INTO `tp_login_log` VALUES ('262', '1', '114.216.92.122', '中国 江苏 苏州 ', 'IE(7.0)', 'Windows 7', '2016-10-14 12:00:37');
INSERT INTO `tp_login_log` VALUES ('263', '1', '106.114.193.150', '中国 河北 石家庄 ', 'Chrome(42.0.2311.154)', 'Windows 7', '2016-10-14 12:01:55');
INSERT INTO `tp_login_log` VALUES ('264', '1', '180.109.81.234', '中国 江苏 南京 ', 'Firefox(45.0)', 'Windows 7', '2016-10-14 12:11:00');
INSERT INTO `tp_login_log` VALUES ('265', '1', '117.30.155.83', '中国 福建 厦门 ', 'Chrome(53.0.2785.143)', '未知操作系统', '2016-10-14 12:14:45');
INSERT INTO `tp_login_log` VALUES ('266', '1', '101.26.84.102', '中国 河北 邯郸 ', 'IE(7.0)', 'Windows 7', '2016-10-14 12:15:55');
INSERT INTO `tp_login_log` VALUES ('267', '1', '120.32.165.67', '中国 福建 厦门 ', 'Chrome(50.0.2661.102)', 'Windows 8', '2016-10-14 12:35:26');
INSERT INTO `tp_login_log` VALUES ('268', '1', '175.43.103.221', '中国 福建 泉州 ', 'Chrome(45.0.2454.93)', 'Windows 7', '2016-10-14 12:37:07');
INSERT INTO `tp_login_log` VALUES ('269', '1', '59.55.178.204', '中国 江西 吉安 ', 'IE(7.0)', 'Windows 10', '2016-10-14 12:47:50');
INSERT INTO `tp_login_log` VALUES ('270', '1', '110.228.97.141', '中国 河北 石家庄 ', 'Firefox(49.0)', 'Windows 7', '2016-10-14 12:52:27');
INSERT INTO `tp_login_log` VALUES ('271', '1', '139.214.144.27', '中国 吉林 吉林市 ', 'Chrome(46.0.2490.85)', 'Linux', '2016-10-14 12:55:10');
INSERT INTO `tp_login_log` VALUES ('272', '1', '119.114.113.238', '中国 辽宁 鞍山 ', 'Chrome(49.0.2623.112)', 'Windows 10', '2016-10-14 13:01:22');
INSERT INTO `tp_login_log` VALUES ('273', '1', '60.180.164.120', '中国 浙江 温州 ', 'Chrome(52.0.2743.116)', 'Windows 7', '2016-10-14 13:01:51');
INSERT INTO `tp_login_log` VALUES ('274', '1', '123.139.26.146', '中国 陕西 西安 ', 'Chrome(51.0.2704.106)', 'Windows 7', '2016-10-14 13:13:35');
INSERT INTO `tp_login_log` VALUES ('275', '1', '144.12.103.250', '中国 山东 菏泽 ', 'IE(7.0)', 'Windows 10', '2016-10-14 13:18:41');
INSERT INTO `tp_login_log` VALUES ('276', '1', '180.105.133.227', '中国 江苏 连云港 ', 'Chrome(47.0.2526.80)', 'Windows 10', '2016-10-14 13:23:44');
INSERT INTO `tp_login_log` VALUES ('277', '1', '125.92.7.242', '中国 广东 江门 ', 'Firefox(51.0)', 'Windows 10', '2016-10-14 13:33:18');
INSERT INTO `tp_login_log` VALUES ('278', '1', '1.202.74.114', '中国 北京 北京 ', 'Chrome(49.0.2623.110)', 'Windows 7', '2016-10-14 13:33:27');
INSERT INTO `tp_login_log` VALUES ('279', '1', '171.88.167.149', '中国 四川 成都 ', 'Chrome(53.0.2785.143)', 'Windows 10', '2016-10-14 13:41:44');
INSERT INTO `tp_login_log` VALUES ('280', '1', '116.226.131.136', '中国 上海 上海 ', 'Chrome(50.0.2661.102)', '未知操作系统', '2016-10-14 13:44:40');
INSERT INTO `tp_login_log` VALUES ('281', '1', '122.191.104.95', '中国 湖北 襄阳 ', 'Chrome(52.0.2743.116)', 'Windows 7', '2016-10-14 13:47:50');
INSERT INTO `tp_login_log` VALUES ('282', '1', '115.212.80.90', '中国 浙江 金华 ', 'Chrome(55.0.2873.0)', 'Windows 7', '2016-10-14 13:51:07');
INSERT INTO `tp_login_log` VALUES ('283', '1', '218.104.171.44', '中国 广东 佛山 ', 'Chrome(53.0.2785.143)', 'Windows 10', '2016-10-14 13:52:37');
INSERT INTO `tp_login_log` VALUES ('284', '1', '223.155.37.183', '中国 湖南 邵阳 ', 'Chrome(53.0.2785.89)', 'Windows 10', '2016-10-14 14:01:48');
INSERT INTO `tp_login_log` VALUES ('285', '1', '14.104.139.114', '中国 重庆 重庆 ', '傲游(4.4.8.1000)', 'Windows NT', '2016-10-14 14:03:15');
INSERT INTO `tp_login_log` VALUES ('286', '1', '101.231.133.138', '中国 上海 上海 ', 'Firefox(49.0)', '未知操作系统', '2016-10-14 14:04:26');
INSERT INTO `tp_login_log` VALUES ('287', '1', '218.59.187.28', '中国 山东 东营 ', 'IE(7.0)', 'Windows 10', '2016-10-14 14:04:30');
INSERT INTO `tp_login_log` VALUES ('288', '1', '183.129.211.50', '中国 浙江 杭州 ', 'Chrome(53.0.2785.116)', 'Windows 10', '2016-10-14 14:06:17');
INSERT INTO `tp_login_log` VALUES ('289', '1', '119.130.189.186', '中国 广东 广州 ', 'Chrome(52.0.2743.116)', '未知操作系统', '2016-10-14 14:08:08');
INSERT INTO `tp_login_log` VALUES ('290', '1', '223.73.49.197', '中国 广东 河源 ', 'Chrome(37.0.0.0)', 'Linux', '2016-10-14 14:10:16');
INSERT INTO `tp_login_log` VALUES ('291', '1', '101.104.9.155', '中国 广东 广州 ', 'Firefox(49.0)', 'Windows 7', '2016-10-14 14:11:33');
INSERT INTO `tp_login_log` VALUES ('292', '1', '114.236.80.103', '中国 江苏 盐城 ', 'Chrome(53.0.2785.89)', 'Windows 7', '2016-10-14 14:13:23');
INSERT INTO `tp_login_log` VALUES ('293', '1', '223.93.157.191', '中国 浙江 杭州 ', 'Chrome(52.0.2743.116)', 'Windows 10', '2016-10-14 14:14:49');
INSERT INTO `tp_login_log` VALUES ('294', '1', '183.128.65.78', '中国 浙江 杭州 ', 'Chrome(53.0.2785.143)', 'Windows 7', '2016-10-14 14:19:17');
INSERT INTO `tp_login_log` VALUES ('295', '1', '27.186.134.9', '中国 河北 保定 ', 'IE(7.0)', 'Windows 10', '2016-10-14 14:19:42');
INSERT INTO `tp_login_log` VALUES ('296', '1', '1.69.130.88', '中国 山西 运城 ', 'IE(8.0)', 'Windows 7', '2016-10-14 14:21:51');
INSERT INTO `tp_login_log` VALUES ('297', '1', '58.42.247.149', '中国 贵州 贵阳 ', 'Chrome(42.0.2311.154)', 'Windows 7', '2016-10-14 14:25:17');
INSERT INTO `tp_login_log` VALUES ('298', '1', '27.17.36.227', '中国 湖北 武汉 ', 'IE(8.0)', 'Windows 7', '2016-10-14 14:26:41');
INSERT INTO `tp_login_log` VALUES ('299', '1', '218.18.138.233', '中国 广东 深圳 ', 'Chrome(53.0.2785.116)', 'Windows 7', '2016-10-14 14:30:18');
INSERT INTO `tp_login_log` VALUES ('300', '1', '119.166.217.250', '中国 山东 青岛 ', 'Chrome(53.0.2785.143)', 'Windows NT', '2016-10-14 14:31:10');
INSERT INTO `tp_login_log` VALUES ('301', '1', '112.255.5.195', '中国 山东 青岛 ', 'Chrome(55.0.2883.9)', '未知操作系统', '2016-10-14 14:33:07');
INSERT INTO `tp_login_log` VALUES ('302', '1', '125.70.207.133', '中国 四川 成都 ', '未知浏览器()', '未知操作系统', '2016-10-14 14:35:44');
INSERT INTO `tp_login_log` VALUES ('303', '1', '121.13.21.231', '中国 广东 东莞 ', 'Chrome(54.0.2840.59)', 'Windows 7', '2016-10-14 14:42:52');
INSERT INTO `tp_login_log` VALUES ('304', '1', '223.166.115.44', '中国 上海 上海 ', 'Firefox(49.0)', 'Windows 10', '2016-10-14 14:46:15');
INSERT INTO `tp_login_log` VALUES ('305', '1', '218.88.31.203', '中国 四川 成都 ', 'Chrome(54.0.2840.59)', 'Windows 10', '2016-10-14 14:47:16');
INSERT INTO `tp_login_log` VALUES ('306', '1', '182.149.192.20', '中国 四川 成都 ', 'Chrome(54.0.2840.59)', 'Windows 7', '2016-10-14 14:49:33');
INSERT INTO `tp_login_log` VALUES ('307', '1', '113.128.212.234', '中国 山东 济南 ', 'Firefox(49.0)', 'Windows NT', '2016-10-14 14:50:48');
INSERT INTO `tp_login_log` VALUES ('308', '1', '58.57.110.66', '中国 山东 淄博 ', 'IE(7.0)', 'Windows 10', '2016-10-14 14:51:28');
INSERT INTO `tp_login_log` VALUES ('309', '1', '219.136.144.48', '中国 广东 广州 ', 'Firefox(49.0)', 'Windows 10', '2016-10-14 14:53:36');
INSERT INTO `tp_login_log` VALUES ('310', '1', '218.26.184.125', '中国 山西 太原 ', 'IE(7.0)', 'Windows NT', '2016-10-14 14:56:37');
INSERT INTO `tp_login_log` VALUES ('311', '1', '112.115.242.194', '中国 云南 昆明 ', 'Chrome(54.0.2840.8)', 'Windows 10', '2016-10-14 14:56:46');
INSERT INTO `tp_login_log` VALUES ('312', '1', '182.242.4.153', '中国 云南 昆明 ', 'Chrome(38.0.2125.122)', 'Windows 7', '2016-10-14 14:58:24');
INSERT INTO `tp_login_log` VALUES ('313', '1', '1.207.35.145', '中国 贵州 黔东南苗族侗族自治州 ', 'IE(7.0)', 'Windows 7', '2016-10-14 15:00:12');
INSERT INTO `tp_login_log` VALUES ('314', '1', '60.186.202.124', '中国 浙江 杭州 ', 'IE(7.0)', 'Windows 10', '2016-10-14 15:06:38');
INSERT INTO `tp_login_log` VALUES ('315', '1', '122.194.117.58', '中国 江苏 泰州 ', 'Chrome(46.0.2490.80)', 'Windows 7', '2016-10-14 15:07:03');
INSERT INTO `tp_login_log` VALUES ('316', '1', '116.24.153.35', '中国 广东 深圳 ', 'Chrome(53.0.2785.116)', 'Windows 10', '2016-10-14 15:08:21');
INSERT INTO `tp_login_log` VALUES ('317', '1', '60.181.77.108', '中国 浙江 温州 ', 'IE(8.0)', 'Windows XP', '2016-10-14 15:10:36');
INSERT INTO `tp_login_log` VALUES ('318', '1', '111.207.243.161', '中国 北京 北京 ', 'Firefox(49.0)', 'Windows 7', '2016-10-14 15:11:08');
INSERT INTO `tp_login_log` VALUES ('319', '1', '59.58.226.31', '中国 福建 龙岩 ', 'Chrome(45.0.2454.101)', 'Windows 7', '2016-10-14 15:11:51');
INSERT INTO `tp_login_log` VALUES ('320', '1', '182.200.11.127', '中国 辽宁 沈阳 ', 'IE(7.0)', 'Windows 7', '2016-10-14 15:17:18');
INSERT INTO `tp_login_log` VALUES ('321', '1', '221.234.173.7', '中国 湖北 武汉 ', 'IE(7.0)', 'Windows 7', '2016-10-14 15:22:36');
INSERT INTO `tp_login_log` VALUES ('322', '1', '113.139.193.228', '中国 陕西 西安 ', '未知浏览器()', '未知操作系统', '2016-10-14 15:23:03');
INSERT INTO `tp_login_log` VALUES ('323', '1', '180.175.21.124', '中国 上海 上海 ', 'IE(8.0)', 'Windows XP', '2016-10-14 15:25:06');
INSERT INTO `tp_login_log` VALUES ('324', '1', '222.61.16.61', '中国 海南 海口 ', 'Chrome(51.0.2704.106)', 'Windows NT', '2016-10-14 15:26:37');
INSERT INTO `tp_login_log` VALUES ('325', '1', '222.85.178.175', '中国 贵州 贵阳 ', 'IE(11.0)', 'Windows 10', '2016-10-14 15:28:48');
INSERT INTO `tp_login_log` VALUES ('326', '1', '36.4.45.216', '中国 安徽 宿州 ', 'Chrome(53.0.2785.143)', 'Windows 7', '2016-10-14 15:28:59');
INSERT INTO `tp_login_log` VALUES ('327', '1', '180.173.42.99', '中国 上海 上海 ', 'Firefox(48.0)', '未知操作系统', '2016-10-14 15:32:57');
INSERT INTO `tp_login_log` VALUES ('328', '1', '183.234.48.155', '中国 广东 佛山 ', 'Chrome(50.0.2661.102)', 'Windows 7', '2016-10-14 15:33:13');
INSERT INTO `tp_login_log` VALUES ('329', '1', '116.248.157.14', '中国 云南 玉溪 ', 'Firefox(49.0)', 'Windows 7', '2016-10-14 15:33:52');
INSERT INTO `tp_login_log` VALUES ('330', '1', '182.117.145.190', '中国 河南 安阳 ', 'IE(7.0)', 'Windows 7', '2016-10-14 15:33:52');
INSERT INTO `tp_login_log` VALUES ('331', '1', '115.204.170.241', '中国 浙江 杭州 ', 'IE(8.0)', 'Windows 7', '2016-10-14 15:35:02');
INSERT INTO `tp_login_log` VALUES ('332', '1', '101.81.114.235', '中国 上海 上海 ', 'Chrome(53.0.2785.143)', 'Windows 10', '2016-10-14 15:36:50');
INSERT INTO `tp_login_log` VALUES ('333', '1', '36.110.43.178', '中国 北京 北京 ', 'Chrome(53.0.2785.143)', 'Windows 10', '2016-10-14 15:36:52');
INSERT INTO `tp_login_log` VALUES ('334', '1', '124.192.185.66', '中国 北京 北京 ', 'Chrome(56.0.2887.0)', '未知操作系统', '2016-10-14 15:37:28');
INSERT INTO `tp_login_log` VALUES ('335', '1', '115.226.140.214', '中国 浙江 丽水 ', 'Chrome(52.0.2743.116)', 'Windows 7', '2016-10-14 15:38:19');
INSERT INTO `tp_login_log` VALUES ('336', '1', '220.180.137.118', '中国 安徽 芜湖 ', 'IE(7.0)', 'Windows 7', '2016-10-14 15:39:08');
INSERT INTO `tp_login_log` VALUES ('337', '1', '113.118.244.239', '中国 广东 深圳 ', 'Firefox(49.0)', 'Windows 7', '2016-10-14 15:40:24');
INSERT INTO `tp_login_log` VALUES ('338', '1', '116.31.243.39', '中国 广东 深圳 ', 'Chrome(53.0.2785.116)', 'Windows 7', '2016-10-14 15:41:16');
INSERT INTO `tp_login_log` VALUES ('339', '1', '113.7.75.85', '中国 黑龙江 牡丹江 ', 'Chrome(53.0.2785.101)', 'Windows 10', '2016-10-14 15:42:06');
INSERT INTO `tp_login_log` VALUES ('340', '1', '180.168.139.86', '中国 上海 上海 ', 'Chrome(50.0.2661.95)', 'Windows 95', '2016-10-14 15:43:33');
INSERT INTO `tp_login_log` VALUES ('341', '1', '1.30.133.79', '中国 内蒙古 呼和浩特 ', 'IE(7.0)', 'Windows 8', '2016-10-14 15:47:13');
INSERT INTO `tp_login_log` VALUES ('342', '1', '183.204.7.37', '中国 河南 洛阳 ', 'Firefox(49.0)', 'Windows 7', '2016-10-14 15:47:31');
INSERT INTO `tp_login_log` VALUES ('343', '1', '123.149.210.50', '中国 河南 郑州 ', 'Edge(14.14393)', 'Windows 10', '2016-10-14 15:49:45');
INSERT INTO `tp_login_log` VALUES ('344', '1', '27.191.3.253', '中国 河北 唐山 ', 'Firefox(49.0)', 'Windows 7', '2016-10-14 15:49:57');
INSERT INTO `tp_login_log` VALUES ('345', '1', '183.39.117.27', '中国 广东 深圳 ', 'Chrome(50.0.2661.95)', 'Windows 95', '2016-10-14 15:50:37');
INSERT INTO `tp_login_log` VALUES ('346', '1', '182.105.109.32', '中国 江西 吉安 ', 'IE(7.0)', 'Windows 7', '2016-10-14 15:50:41');
INSERT INTO `tp_login_log` VALUES ('347', '1', '183.128.65.78', '中国 浙江 杭州 ', 'Chrome(53.0.2785.143)', 'Windows 7', '2016-10-14 15:51:02');
INSERT INTO `tp_login_log` VALUES ('348', '1', '111.19.42.214', '中国 陕西  ', 'Chrome(52.0.2729.4)', 'Windows NT', '2016-10-14 15:51:42');
INSERT INTO `tp_login_log` VALUES ('349', '1', '124.73.181.44', '中国 安徽 合肥 ', 'Chrome(54.0.2840.59)', 'Windows 10', '2016-10-14 15:55:38');
INSERT INTO `tp_login_log` VALUES ('350', '1', '125.46.11.253', '中国 河南 郑州 ', 'Chrome(52.0.2743.116)', 'Windows 10', '2016-10-14 15:55:52');
INSERT INTO `tp_login_log` VALUES ('351', '1', '180.106.94.37', '中国 江苏 苏州 ', 'IE(7.0)', 'Windows 7', '2016-10-14 15:57:06');
INSERT INTO `tp_login_log` VALUES ('352', '1', '222.172.185.114', '中国 云南 昆明 ', 'Chrome(53.0.2785.116)', 'Windows 10', '2016-10-14 15:57:49');
INSERT INTO `tp_login_log` VALUES ('353', '1', '119.130.228.158', '中国 广东 广州 ', 'Chrome(53.0.2785.143)', 'Windows 7', '2016-10-14 16:16:33');
INSERT INTO `tp_login_log` VALUES ('354', '1', '114.95.225.48', '中国 上海 上海 ', 'Firefox(49.0)', 'Windows XP', '2016-10-14 16:19:28');
INSERT INTO `tp_login_log` VALUES ('355', '1', '218.104.171.44', '中国 广东 佛山 ', 'Chrome(53.0.2785.143)', 'Windows 10', '2016-10-14 16:21:55');
INSERT INTO `tp_login_log` VALUES ('356', '1', '222.92.146.66', '中国 江苏 苏州 ', 'IE(7.0)', 'Windows 7', '2016-10-14 16:24:29');
INSERT INTO `tp_login_log` VALUES ('357', '1', '120.41.7.74', '中国 福建 厦门 ', 'Chrome(47.0.2526.106)', 'Windows 7', '2016-10-14 16:27:11');
INSERT INTO `tp_login_log` VALUES ('358', '1', '123.235.199.114', '中国 山东 青岛 ', 'Chrome(53.0.2785.116)', 'Windows 7', '2016-10-14 16:27:36');
INSERT INTO `tp_login_log` VALUES ('359', '1', '118.26.132.82', '中国 北京 北京 ', 'IE(7.0)', 'Windows 7', '2016-10-14 16:33:00');
INSERT INTO `tp_login_log` VALUES ('360', '1', '119.145.100.138', '中国 广东 东莞 ', 'Chrome(47.0.2526.80)', 'Windows 7', '2016-10-14 16:39:02');
INSERT INTO `tp_login_log` VALUES ('361', '1', '171.221.250.129', '中国 四川 成都 ', 'Chrome(53.0.2785.143)', '未知操作系统', '2016-10-14 16:39:56');
INSERT INTO `tp_login_log` VALUES ('362', '1', '123.161.248.120', '中国 河南 平顶山 ', 'Chrome(47.0.2526.73)', 'Windows 7', '2016-10-14 16:41:49');
INSERT INTO `tp_login_log` VALUES ('363', '1', '14.127.249.149', '中国 广东 深圳 ', 'Firefox(49.0)', '未知操作系统', '2016-10-14 16:42:25');
INSERT INTO `tp_login_log` VALUES ('364', '1', '221.4.215.11', '中国 广东 珠海 ', 'IE(8.0)', 'Windows 7', '2016-10-14 16:42:32');
INSERT INTO `tp_login_log` VALUES ('365', '1', '113.122.233.17', '中国 山东 临沂 ', 'IE(11.0)', 'Windows 10', '2016-10-14 16:44:20');
INSERT INTO `tp_login_log` VALUES ('366', '1', '220.171.82.58', '中国 新疆 乌鲁木齐 ', 'Chrome(47.0.2526.80)', 'Windows 7', '2016-10-14 16:49:56');
INSERT INTO `tp_login_log` VALUES ('367', '1', '124.127.112.226', '中国 北京 北京 ', 'IE(7.0)', 'Windows 10', '2016-10-14 16:51:02');
INSERT INTO `tp_login_log` VALUES ('368', '1', '223.255.33.162', '中国 北京 北京 ', 'Chrome(52.0.2743.116)', 'Windows 7', '2016-10-14 16:52:40');
INSERT INTO `tp_login_log` VALUES ('369', '1', '114.248.153.136', '中国 北京 北京 ', 'Chrome(52.0.2743.116)', 'Windows 10', '2016-10-14 16:54:26');
INSERT INTO `tp_login_log` VALUES ('370', '1', '42.236.207.243', '中国 河南 郑州 ', 'Chrome(55.0.2882.4)', 'Windows 10', '2016-10-14 16:57:15');
INSERT INTO `tp_login_log` VALUES ('371', '1', '180.137.40.86', '中国 广西 崇左 ', 'IE(7.0)', 'Windows 7', '2016-10-14 16:58:12');
INSERT INTO `tp_login_log` VALUES ('372', '1', '221.207.238.220', '中国 黑龙江 哈尔滨 ', 'IE(7.0)', 'Windows 7', '2016-10-14 16:58:24');
INSERT INTO `tp_login_log` VALUES ('373', '1', '123.55.185.92', '中国 河南 三门峡 ', 'Chrome(53.0.2785.116)', 'Windows 10', '2016-10-14 16:58:48');
INSERT INTO `tp_login_log` VALUES ('374', '1', '120.32.116.19', '中国 福建 福州 ', 'Firefox(49.0)', 'Windows 10', '2016-10-14 16:59:33');
INSERT INTO `tp_login_log` VALUES ('375', '1', '113.118.244.239', '中国 广东 深圳 ', 'Chrome(51.0.2704.106)', 'Windows 7', '2016-10-14 16:59:48');
INSERT INTO `tp_login_log` VALUES ('376', '1', '183.234.123.120', '中国 广东 梅州 ', 'Chrome(52.0.2743.116)', 'Windows 7', '2016-10-14 17:00:38');
INSERT INTO `tp_login_log` VALUES ('377', '1', '27.38.255.57', '中国 广东 深圳 ', 'IE(7.0)', 'Windows NT', '2016-10-14 17:02:04');
INSERT INTO `tp_login_log` VALUES ('378', '1', '117.75.19.171', '中国 云南 昆明 ', 'IE(8.0)', 'Windows 7', '2016-10-14 17:02:20');
INSERT INTO `tp_login_log` VALUES ('379', '1', '114.217.146.131', '中国 江苏 苏州 ', 'Chrome(51.0.2704.79)', 'Linux', '2016-10-14 17:04:51');
INSERT INTO `tp_login_log` VALUES ('380', '1', '218.93.243.98', '中国 江苏 宿迁 ', '傲游(4.4.8.2000)', 'Windows NT', '2016-10-14 17:06:39');
INSERT INTO `tp_login_log` VALUES ('381', '1', '180.173.216.83', '中国 上海 上海 ', 'IE(8.0)', 'Windows 7', '2016-10-14 17:08:41');
INSERT INTO `tp_login_log` VALUES ('382', '1', '117.83.57.53', '中国 江苏 苏州 ', 'Firefox(49.0)', 'Windows 10', '2016-10-14 17:14:53');
INSERT INTO `tp_login_log` VALUES ('383', '1', '123.149.210.50', '中国 河南 郑州 ', 'Edge(14.14393)', 'Windows 10', '2016-10-14 17:15:32');
INSERT INTO `tp_login_log` VALUES ('384', '1', '112.11.85.73', '中国 浙江 嘉兴 ', 'Chrome(52.0.2743.116)', 'Windows 10', '2016-10-14 17:17:00');
INSERT INTO `tp_login_log` VALUES ('385', '1', '223.73.60.86', '中国 广东 广州 ', 'Chrome(50.0.2661.102)', 'Windows 7', '2016-10-14 17:20:48');
INSERT INTO `tp_login_log` VALUES ('386', '1', '120.202.21.4', '中国 湖北 武汉 ', 'Chrome(52.0.2743.116)', 'Windows 10', '2016-10-14 17:20:49');
INSERT INTO `tp_login_log` VALUES ('387', '1', '58.58.180.172', '中国 山东 日照 ', 'Chrome(49.0.2623.22)', 'Windows 7', '2016-10-14 17:23:21');
INSERT INTO `tp_login_log` VALUES ('388', '1', '111.180.86.66', '中国 湖北 潜江 ', 'IE(7.0)', 'Windows 7', '2016-10-14 17:24:45');
INSERT INTO `tp_login_log` VALUES ('389', '1', '211.162.81.121', '中国 江苏 南京 ', 'Chrome(53.0.2785.143)', 'Windows 10', '2016-10-14 17:26:44');
INSERT INTO `tp_login_log` VALUES ('390', '1', '175.11.68.27', '中国 湖南 长沙 ', 'Firefox(49.0)', 'Windows 7', '2016-10-14 17:28:31');
INSERT INTO `tp_login_log` VALUES ('391', '1', '223.255.33.162', '中国 北京 北京 ', 'Chrome(53.0.2785.116)', 'Windows 7', '2016-10-14 17:30:55');
INSERT INTO `tp_login_log` VALUES ('392', '1', '119.130.228.158', '中国 广东 广州 ', 'Chrome(53.0.2785.143)', 'Windows 7', '2016-10-14 17:34:08');
INSERT INTO `tp_login_log` VALUES ('393', '1', '218.17.162.213', '中国 广东 深圳 ', 'Chrome(53.0.2767.5)', 'Windows 7', '2016-10-14 17:35:21');
INSERT INTO `tp_login_log` VALUES ('394', '1', '125.36.56.23', '中国 天津 天津 ', 'Firefox(49.0)', 'Windows 10', '2016-10-14 17:36:41');
INSERT INTO `tp_login_log` VALUES ('395', '1', '111.172.32.67', '中国 湖北 武汉 ', 'Chrome(52.0.2743.116)', '未知操作系统', '2016-10-14 17:38:28');
INSERT INTO `tp_login_log` VALUES ('396', '1', '113.229.223.64', '中国 辽宁 丹东 ', 'IE(7.0)', 'Windows 10', '2016-10-14 17:39:17');
INSERT INTO `tp_login_log` VALUES ('397', '1', '58.61.64.69', '中国 广东 深圳 ', 'Chrome(54.0.2840.59)', 'Windows 10', '2016-10-14 17:41:15');
INSERT INTO `tp_login_log` VALUES ('398', '1', '223.88.22.97', '中国 河南 郑州 ', 'Chrome(53.0.2785.143)', 'Windows 10', '2016-10-14 17:42:18');
INSERT INTO `tp_login_log` VALUES ('399', '1', '218.247.171.18', '中国 北京 北京 ', 'Chrome(54.0.2840.59)', 'Linux', '2016-10-14 17:43:59');
INSERT INTO `tp_login_log` VALUES ('400', '1', '120.4.79.204', '中国 河北 唐山 ', 'IE(7.0)', 'Windows 10', '2016-10-14 17:47:39');
INSERT INTO `tp_login_log` VALUES ('401', '1', '119.130.207.24', '中国 广东 广州 ', 'Firefox(49.0)', 'Windows 10', '2016-10-14 17:49:47');
INSERT INTO `tp_login_log` VALUES ('402', '1', '221.182.236.34', '中国 海南  ', 'Chrome(53.0.2785.116)', 'Windows 7', '2016-10-14 17:51:02');
INSERT INTO `tp_login_log` VALUES ('403', '1', '119.60.73.181', '中国 宁夏 银川 ', 'IE(8.0)', 'Windows 7', '2016-10-14 17:55:10');
INSERT INTO `tp_login_log` VALUES ('404', '1', '115.183.19.109', '中国 北京 北京 ', 'IE(8.0)', 'Windows 7', '2016-10-14 18:01:27');
INSERT INTO `tp_login_log` VALUES ('405', '1', '120.236.156.164', '中国 广东 广州 ', 'Chrome(55.0.2883.9)', 'Windows NT', '2016-10-14 18:06:03');
INSERT INTO `tp_login_log` VALUES ('406', '1', '1.84.106.140', '中国 陕西 西安 ', 'Chrome(53.0.2785.143)', 'Windows 7', '2016-10-14 18:06:42');
INSERT INTO `tp_login_log` VALUES ('407', '1', '218.17.34.102', '中国 广东 深圳 ', 'Chrome(53.0.2785.116)', 'Windows 7', '2016-10-14 18:17:03');
INSERT INTO `tp_login_log` VALUES ('408', '1', '122.224.252.14', '中国 浙江 杭州 ', 'IE(8.0)', 'Windows 7', '2016-10-14 18:27:18');
INSERT INTO `tp_login_log` VALUES ('409', '1', '122.239.68.145', '中国 浙江 温州 ', 'IE(7.0)', 'Windows 7', '2016-10-14 18:30:23');
INSERT INTO `tp_login_log` VALUES ('410', '1', '119.130.207.104', '中国 广东 广州 ', 'Firefox(49.0)', 'Windows 7', '2016-10-14 18:34:23');
INSERT INTO `tp_login_log` VALUES ('411', '1', '106.81.231.49', '中国 重庆 重庆 ', 'Chrome(52.0.2743.82)', 'Windows 10', '2016-10-14 18:46:11');
INSERT INTO `tp_login_log` VALUES ('412', '1', '139.203.5.106', '中国 四川 攀枝花 ', 'Chrome(51.0.2704.106)', 'Windows 10', '2016-10-14 18:50:01');
INSERT INTO `tp_login_log` VALUES ('413', '1', '119.60.73.181', '中国 宁夏 银川 ', 'Firefox(49.0)', 'Windows 10', '2016-10-14 18:53:08');
INSERT INTO `tp_login_log` VALUES ('414', '1', '183.69.224.243', '中国 重庆 重庆 ', 'Firefox(49.0)', 'Windows 10', '2016-10-14 19:05:17');
INSERT INTO `tp_login_log` VALUES ('415', '1', '106.116.176.254', '中国 河北 唐山 ', 'IE(8.0)', 'Windows XP', '2016-10-14 19:10:15');
INSERT INTO `tp_login_log` VALUES ('416', '1', '27.202.236.174', '中国 山东 东营 ', 'Firefox(49.0)', 'Windows 7', '2016-10-14 19:16:16');
INSERT INTO `tp_login_log` VALUES ('417', '1', '175.7.57.48', '中国 湖南 岳阳 ', 'IE(7.0)', 'Windows 10', '2016-10-14 19:21:55');
INSERT INTO `tp_login_log` VALUES ('418', '1', '27.46.115.80', '中国 广东 深圳 ', 'Chrome(53.0.2785.116)', 'Windows 10', '2016-10-14 20:07:04');
INSERT INTO `tp_login_log` VALUES ('419', '1', '49.221.30.35', '中国 吉林 长春 ', 'IE(7.0)', 'Windows 10', '2016-10-14 20:10:53');
INSERT INTO `tp_login_log` VALUES ('420', '1', '171.88.142.131', '中国 四川 成都 ', 'IE(11.0)', 'Windows NT', '2016-10-14 20:13:13');
INSERT INTO `tp_login_log` VALUES ('421', '1', '222.83.177.236', '中国 广西 河池 ', 'Chrome(53.0.2785.143)', 'Windows 10', '2016-10-14 20:18:11');
INSERT INTO `tp_login_log` VALUES ('422', '1', '60.168.205.217', '中国 安徽 合肥 ', 'Chrome(53.0.2785.143)', 'Windows 10', '2016-10-14 20:23:50');
INSERT INTO `tp_login_log` VALUES ('423', '1', '180.136.128.40', '中国 广西 南宁 ', 'Chrome(55.0.2883.9)', 'Windows 10', '2016-10-14 20:24:09');
INSERT INTO `tp_login_log` VALUES ('424', '1', '1.81.32.173', '中国 陕西 西安 ', 'Chrome(47.0.2526.106)', 'Windows NT', '2016-10-14 20:31:15');
INSERT INTO `tp_login_log` VALUES ('425', '1', '106.114.217.66', '中国 河北 石家庄 ', 'Firefox(49.0)', 'Windows NT', '2016-10-14 20:32:17');
INSERT INTO `tp_login_log` VALUES ('426', '1', '222.84.161.166', '中国 广西 桂林 ', 'Chrome(50.0.2661.102)', 'Windows 7', '2016-10-14 20:45:15');
INSERT INTO `tp_login_log` VALUES ('427', '1', '36.5.89.82', '中国 安徽 合肥 ', 'Chrome(53.0.2785.116)', 'Windows NT', '2016-10-14 20:48:03');
INSERT INTO `tp_login_log` VALUES ('428', '1', '123.55.185.92', '中国 河南 三门峡 ', 'Chrome(53.0.2785.116)', 'Windows 10', '2016-10-14 21:04:44');
INSERT INTO `tp_login_log` VALUES ('429', '1', '120.39.6.94', '中国 福建 厦门 ', 'Chrome(53.0.2785.143)', '未知操作系统', '2016-10-14 21:25:14');
INSERT INTO `tp_login_log` VALUES ('430', '1', '218.107.21.167', '中国 广东 广州 ', 'Chrome(53.0.2785.116)', 'Windows NT', '2016-10-14 21:29:11');
INSERT INTO `tp_login_log` VALUES ('431', '1', '183.39.155.126', '中国 广东 深圳 ', 'IE(7.0)', 'Windows 10', '2016-10-14 21:29:51');
INSERT INTO `tp_login_log` VALUES ('432', '1', '220.178.22.247', '中国 安徽 合肥 ', 'IE(7.0)', 'Windows 10', '2016-10-14 21:38:25');
INSERT INTO `tp_login_log` VALUES ('433', '1', '110.184.61.194', '中国 四川 成都 ', 'Chrome(42.0.2311.154)', 'Windows 7', '2016-10-14 21:43:07');
INSERT INTO `tp_login_log` VALUES ('434', '1', '1.81.32.173', '中国 陕西 西安 ', 'Chrome(47.0.2526.106)', 'Windows NT', '2016-10-14 21:55:29');
INSERT INTO `tp_login_log` VALUES ('435', '1', '61.145.169.207', '中国 广东 东莞 ', 'IE(7.0)', 'Windows 10', '2016-10-14 21:58:18');
INSERT INTO `tp_login_log` VALUES ('436', '1', '119.129.82.199', '中国 广东 广州 ', 'IE(7.0)', 'Windows 7', '2016-10-14 22:01:01');
INSERT INTO `tp_login_log` VALUES ('437', '1', '61.141.0.141', '中国 广东 汕头 ', 'Chrome(50.0.2661.95)', 'Windows 95', '2016-10-14 22:01:18');
INSERT INTO `tp_login_log` VALUES ('438', '1', '61.145.169.207', '中国 广东 东莞 ', 'IE(7.0)', 'Windows 7', '2016-10-14 22:02:04');
INSERT INTO `tp_login_log` VALUES ('439', '1', '222.76.239.157', '中国 福建 厦门 ', 'IE(11.0)', 'Windows NT', '2016-10-14 22:13:54');
INSERT INTO `tp_login_log` VALUES ('440', '1', '123.149.73.184', '中国 河南 郑州 ', 'Chrome(45.0.2454.85)', 'Windows 7', '2016-10-14 22:17:03');
INSERT INTO `tp_login_log` VALUES ('441', '1', '121.32.198.183', '中国 广东 广州 ', 'IE(7.0)', 'Windows 7', '2016-10-14 22:29:35');
INSERT INTO `tp_login_log` VALUES ('442', '1', '112.66.148.12', '中国 海南 海口 ', 'Chrome(50.0.2661.102)', 'Windows 10', '2016-10-14 22:40:59');
INSERT INTO `tp_login_log` VALUES ('443', '1', '110.152.244.129', '中国 新疆 乌鲁木齐 ', '傲游(5.0.1.1700)', 'Windows 10', '2016-10-14 22:43:59');
INSERT INTO `tp_login_log` VALUES ('444', '1', '115.34.11.219', '中国 北京 北京 ', 'Chrome(42.0.2311.154)', 'Windows NT', '2016-10-14 22:48:30');
INSERT INTO `tp_login_log` VALUES ('445', '1', '27.152.249.22', '中国 福建 泉州 ', 'Chrome(49.0.2623.22)', 'Windows XP', '2016-10-14 22:55:27');
INSERT INTO `tp_login_log` VALUES ('446', '1', '27.24.189.211', '中国 湖北 咸宁 ', 'IE(7.0)', 'Windows 10', '2016-10-14 23:04:22');
INSERT INTO `tp_login_log` VALUES ('447', '1', '121.13.165.168', '中国 广东 东莞 ', 'Chrome(53.0.2785.143)', 'Windows 10', '2016-10-14 23:06:02');
INSERT INTO `tp_login_log` VALUES ('448', '1', '111.14.51.169', '中国 山东 东营 ', 'Edge(14.14393)', 'Windows 10', '2016-10-14 23:06:03');
INSERT INTO `tp_login_log` VALUES ('449', '1', '42.236.179.15', '中国 河南 郑州 ', 'Chrome(50.0.2661.94)', 'Windows 7', '2016-10-14 23:07:39');
INSERT INTO `tp_login_log` VALUES ('450', '1', '112.249.23.38', '中国 山东 烟台 ', 'IE(7.0)', 'Windows 7', '2016-10-14 23:15:42');
INSERT INTO `tp_login_log` VALUES ('451', '1', '119.181.52.119', '中国 山东 济宁 ', 'IE(7.0)', 'Windows 7', '2016-10-14 23:22:44');
INSERT INTO `tp_login_log` VALUES ('452', '1', '59.108.54.37', '中国 北京 北京 ', 'Chrome(54.0.2840.59)', '未知操作系统', '2016-10-14 23:23:49');
INSERT INTO `tp_login_log` VALUES ('453', '1', '112.5.168.110', '中国 福建 厦门 ', 'Chrome(52.0.2743.116)', 'Windows 7', '2016-10-14 23:50:34');
INSERT INTO `tp_login_log` VALUES ('454', '1', '115.183.237.67', '中国 北京 北京 ', '未知浏览器()', '未知操作系统', '2016-10-15 00:15:31');
INSERT INTO `tp_login_log` VALUES ('455', '1', '119.180.119.137', '中国 山东 烟台 ', 'Chrome(54.0.2840.59)', 'Windows 7', '2016-10-15 00:20:25');
INSERT INTO `tp_login_log` VALUES ('456', '1', '36.44.140.55', '中国 陕西 西安 ', 'IE(7.0)', 'Windows 7', '2016-10-15 00:29:38');
INSERT INTO `tp_login_log` VALUES ('457', '1', '14.23.233.24', '中国 广东 广州 ', 'Chrome(49.0.2623.112)', 'Windows XP', '2016-10-15 00:58:11');
INSERT INTO `tp_login_log` VALUES ('458', '1', '58.22.94.65', '中国 福建 龙岩 ', '未知浏览器()', '未知操作系统', '2016-10-15 01:36:14');
INSERT INTO `tp_login_log` VALUES ('459', '1', '119.139.137.138', '中国 广东 深圳 ', 'Firefox(49.0)', 'Windows 7', '2016-10-15 01:38:06');
INSERT INTO `tp_login_log` VALUES ('460', '1', '113.119.168.84', '中国 广东 广州 ', 'Chrome(52.0.2743.82)', 'Windows 7', '2016-10-15 01:55:27');
INSERT INTO `tp_login_log` VALUES ('461', '1', '119.86.38.187', '中国 重庆 重庆 ', '未知浏览器()', '未知操作系统', '2016-10-15 02:04:17');
INSERT INTO `tp_login_log` VALUES ('462', '1', '125.69.125.122', '中国 四川 成都 ', 'IE(7.0)', 'Windows 10', '2016-10-15 02:15:53');
INSERT INTO `tp_login_log` VALUES ('463', '1', '123.139.39.191', '中国 陕西 西安 ', 'IE(7.0)', 'Windows 10', '2016-10-15 02:39:55');
INSERT INTO `tp_login_log` VALUES ('464', '1', '183.4.146.103', '中国 广东 惠州 ', 'Chrome(50.0.2661.102)', 'Windows 10', '2016-10-15 04:51:39');
INSERT INTO `tp_login_log` VALUES ('465', '1', '27.156.2.223', '中国 福建 福州 ', 'Chrome(53.0.2785.143)', 'Windows 7', '2016-10-15 06:28:13');
INSERT INTO `tp_login_log` VALUES ('466', '1', '39.69.45.83', '中国 山东 潍坊 ', 'Chrome(52.0.2743.116)', 'Windows 10', '2016-10-15 07:46:21');
INSERT INTO `tp_login_log` VALUES ('467', '1', '183.81.182.206', '中国 北京 北京 ', 'IE(7.0)', 'Windows 7', '2016-10-15 08:32:40');
INSERT INTO `tp_login_log` VALUES ('468', '1', '14.131.72.104', '中国 北京 北京 ', 'IE(7.0)', 'Windows 7', '2016-10-15 08:45:37');
INSERT INTO `tp_login_log` VALUES ('469', '1', '117.86.53.104', '中国 江苏 南通 ', 'Chrome(44.0.2403.157)', 'Windows XP', '2016-10-15 08:47:49');
INSERT INTO `tp_login_log` VALUES ('470', '1', '175.10.48.104', '中国 湖南 长沙 ', 'IE(8.0)', 'Windows XP', '2016-10-15 08:54:32');
INSERT INTO `tp_login_log` VALUES ('471', '1', '42.236.246.167', '中国 河南 郑州 ', 'IE(7.0)', 'Windows 10', '2016-10-15 09:04:38');
INSERT INTO `tp_login_log` VALUES ('472', '1', '113.118.244.239', '中国 广东 深圳 ', 'Chrome(51.0.2704.106)', 'Windows 7', '2016-10-15 09:09:51');
INSERT INTO `tp_login_log` VALUES ('473', '1', '116.230.49.198', '中国 上海 上海 ', 'Firefox(49.0)', 'Linux', '2016-10-15 09:10:21');
INSERT INTO `tp_login_log` VALUES ('474', '1', '111.192.190.23', '中国 北京 北京 ', 'Chrome(51.0.2704.106)', 'Windows 7', '2016-10-15 09:14:17');
INSERT INTO `tp_login_log` VALUES ('475', '1', '211.161.27.78', '中国 河北 石家庄 ', 'Chrome(52.0.2743.116)', 'Windows 7', '2016-10-15 09:16:57');
INSERT INTO `tp_login_log` VALUES ('476', '1', '101.23.127.171', '中国 河北 邢台 ', 'Firefox(49.0)', 'Windows XP', '2016-10-15 09:19:16');
INSERT INTO `tp_login_log` VALUES ('477', '1', '106.39.193.124', '中国 北京 北京 ', 'Firefox(49.0)', 'Windows 10', '2016-10-15 09:28:59');
INSERT INTO `tp_login_log` VALUES ('478', '1', '14.155.18.149', '中国 广东 深圳 ', 'IE(7.0)', 'Windows 10', '2016-10-15 09:33:34');
INSERT INTO `tp_login_log` VALUES ('479', '1', '61.141.156.228', '中国 广东 深圳 ', 'Chrome(51.0.2704.106)', 'Windows 10', '2016-10-15 09:36:51');
INSERT INTO `tp_login_log` VALUES ('480', '1', '14.131.72.104', '中国 北京 北京 ', 'IE(7.0)', 'Windows 7', '2016-10-15 09:37:00');
INSERT INTO `tp_login_log` VALUES ('481', '1', '121.30.149.180', '中国 山西 大同 ', 'Chrome(53.0.2767.5)', 'Windows 7', '2016-10-15 10:05:19');
INSERT INTO `tp_login_log` VALUES ('482', '1', '58.208.66.100', '中国 江苏 苏州 ', 'Chrome(52.0.2743.116)', 'Windows 10', '2016-10-15 10:14:33');
INSERT INTO `tp_login_log` VALUES ('483', '1', '14.118.134.34', '中国 广东 中山 ', 'Chrome(53.0.2785.143)', 'Windows 10', '2016-10-15 10:22:07');
INSERT INTO `tp_login_log` VALUES ('484', '1', '115.208.208.144', '中国 浙江 湖州 ', 'Chrome(47.0.2526.80)', 'Windows 7', '2016-10-15 10:22:47');
INSERT INTO `tp_login_log` VALUES ('485', '1', '144.0.142.106', '中国 山东 烟台 ', 'IE(7.0)', 'Windows 7', '2016-10-15 10:22:51');
INSERT INTO `tp_login_log` VALUES ('486', '1', '221.234.25.27', '中国 湖北 荆门 ', 'IE(11.0)', 'Windows 10', '2016-10-15 10:39:37');
INSERT INTO `tp_login_log` VALUES ('487', '1', '61.141.157.213', '中国 广东 深圳 ', 'Chrome(51.0.2704.106)', 'Windows 10', '2016-10-15 10:45:44');
INSERT INTO `tp_login_log` VALUES ('488', '1', '58.215.136.249', '中国 江苏 无锡 ', 'Chrome(17.0.558.0)', 'Windows 2000', '2016-10-15 10:46:24');
INSERT INTO `tp_login_log` VALUES ('489', '1', '113.69.47.64', '中国 广东 佛山 ', 'Chrome(53.0.2785.143)', 'Windows 10', '2016-10-15 10:54:50');
INSERT INTO `tp_login_log` VALUES ('490', '1', '113.118.244.239', '中国 广东 深圳 ', 'Chrome(51.0.2704.106)', 'Windows 7', '2016-10-15 11:00:47');
INSERT INTO `tp_login_log` VALUES ('491', '1', '120.37.15.190', '中国 福建 泉州 ', 'Chrome(53.0.2785.89)', 'Windows 10', '2016-10-15 11:03:48');
INSERT INTO `tp_login_log` VALUES ('492', '1', '110.7.5.133', '中国 内蒙古 呼和浩特 ', 'Chrome(51.0.2704.63)', 'Windows 10', '2016-10-15 11:04:38');
INSERT INTO `tp_login_log` VALUES ('493', '1', '223.104.3.161', '中国 北京 北京 ', 'Chrome(52.0.2743.116)', '未知操作系统', '2016-10-15 11:09:37');
INSERT INTO `tp_login_log` VALUES ('494', '1', '163.125.243.198', '中国 广东 深圳 ', 'Chrome(53.0.2785.143)', 'Windows 7', '2016-10-15 11:29:16');
INSERT INTO `tp_login_log` VALUES ('495', '1', '27.17.132.162', '中国 湖北 武汉 ', 'Chrome(53.0.2785.116)', 'Windows 7', '2016-10-15 11:30:44');
INSERT INTO `tp_login_log` VALUES ('496', '1', '121.32.50.225', '中国 广东 广州 ', 'IE(7.0)', 'Windows 10', '2016-10-15 11:37:00');
INSERT INTO `tp_login_log` VALUES ('497', '1', '211.161.248.19', '中国 上海 上海 ', 'Chrome(53.0.2785.89)', 'Windows 10', '2016-10-15 11:49:45');
INSERT INTO `tp_login_log` VALUES ('498', '1', '180.138.65.33', '中国 广西 南宁 ', 'Chrome(50.0.2661.102)', 'Windows 10', '2016-10-15 11:51:22');
INSERT INTO `tp_login_log` VALUES ('499', '1', '118.254.56.147', '中国 湖南 张家界 ', 'IE(7.0)', 'Windows 7', '2016-10-15 12:07:28');
INSERT INTO `tp_login_log` VALUES ('500', '1', '175.10.48.104', '中国 湖南 长沙 ', 'Chrome(45.0.2454.101)', 'Windows XP', '2016-10-15 12:08:09');
INSERT INTO `tp_login_log` VALUES ('501', '1', '117.174.159.195', '中国 四川 泸州 ', 'Firefox(49.0)', 'Windows 7', '2016-10-15 12:37:39');
INSERT INTO `tp_login_log` VALUES ('502', '1', '116.226.77.248', '中国 上海 上海 ', 'Chrome(53.0.2785.143)', '未知操作系统', '2016-10-15 13:05:47');
INSERT INTO `tp_login_log` VALUES ('503', '1', '222.216.113.219', '中国 广西 柳州 ', 'Chrome(47.0.2526.80)', 'Windows XP', '2016-10-15 13:10:58');
INSERT INTO `tp_login_log` VALUES ('504', '1', '122.228.142.218', '中国 浙江 温州 ', 'Chrome(53.0.2785.116)', 'Windows 10', '2016-10-15 13:18:55');
INSERT INTO `tp_login_log` VALUES ('505', '1', '182.150.168.121', '中国 四川 成都 ', 'Chrome(47.0.2526.108)', 'Windows 10', '2016-10-15 13:30:41');
INSERT INTO `tp_login_log` VALUES ('506', '1', '183.11.29.141', '中国 广东 深圳 ', 'IE(8.0)', 'Windows 7', '2016-10-15 13:44:00');
INSERT INTO `tp_login_log` VALUES ('507', '1', '124.172.169.85', '中国 广东 深圳 ', 'Chrome(47.0.2526.80)', 'Windows 10', '2016-10-15 13:54:23');
INSERT INTO `tp_login_log` VALUES ('508', '1', '219.137.5.5', '中国 广东 广州 ', 'Chrome(52.0.2743.116)', 'Windows 10', '2016-10-15 13:56:40');
INSERT INTO `tp_login_log` VALUES ('509', '1', '124.202.175.46', '中国 北京 北京 ', 'IE(7.0)', 'Windows 7', '2016-10-15 14:01:44');
INSERT INTO `tp_login_log` VALUES ('510', '1', '121.204.63.19', '中国 福建 福州 ', 'Chrome(40.0.2214.115)', '未知操作系统', '2016-10-15 14:05:13');
INSERT INTO `tp_login_log` VALUES ('511', '1', '222.89.237.54', '中国 河南 漯河 ', 'Chrome(53.0.2785.116)', 'Windows 7', '2016-10-15 14:16:18');
INSERT INTO `tp_login_log` VALUES ('512', '1', '223.209.109.111', '中国 广东 惠州 ', 'Firefox(48.0)', 'Linux', '2016-10-15 14:22:49');
INSERT INTO `tp_login_log` VALUES ('513', '1', '36.25.31.80', '中国 浙江 杭州 ', '傲游(5.0.1.1700)', 'Windows 10', '2016-10-15 14:26:14');
INSERT INTO `tp_login_log` VALUES ('514', '1', '222.132.250.19', '中国 山东 临沂 ', 'Chrome(53.0.2785.143)', 'Windows 7', '2016-10-15 14:28:51');
INSERT INTO `tp_login_log` VALUES ('515', '1', '61.145.169.207', '中国 广东 东莞 ', 'IE(7.0)', 'Windows 7', '2016-10-15 14:30:51');
INSERT INTO `tp_login_log` VALUES ('516', '1', '60.210.231.52', '中国 山东 潍坊 ', 'IE(7.0)', 'Windows 10', '2016-10-15 14:32:38');
INSERT INTO `tp_login_log` VALUES ('517', '1', '101.66.178.201', '中国 浙江 金华 ', 'IE(8.0)', 'Windows 7', '2016-10-15 14:33:56');
INSERT INTO `tp_login_log` VALUES ('518', '1', '115.60.7.150', '中国 河南 郑州 ', 'Chrome(53.0.2785.143)', '未知操作系统', '2016-10-15 14:34:01');
INSERT INTO `tp_login_log` VALUES ('519', '1', '61.132.125.118', '中国 江苏 苏州 ', 'Chrome(52.0.2743.116)', 'Windows 10', '2016-10-15 14:34:18');
INSERT INTO `tp_login_log` VALUES ('520', '1', '116.230.49.198', '中国 上海 上海 ', 'Firefox(49.0)', 'Linux', '2016-10-15 14:37:05');
INSERT INTO `tp_login_log` VALUES ('521', '1', '106.114.217.66', '中国 河北 石家庄 ', 'Firefox(49.0)', 'Windows NT', '2016-10-15 14:42:29');
INSERT INTO `tp_login_log` VALUES ('522', '1', '111.192.147.179', '中国 北京 北京 ', 'Chrome(49.0.2623.110)', 'Windows 7', '2016-10-15 14:42:35');
INSERT INTO `tp_login_log` VALUES ('523', '1', '113.68.195.128', '中国 广东 广州 ', 'Firefox(49.0)', 'Windows 7', '2016-10-15 14:46:09');
INSERT INTO `tp_login_log` VALUES ('524', '1', '183.16.11.32', '中国 广东 深圳 ', 'Chrome(53.0.2785.143)', 'Windows 7', '2016-10-15 14:50:01');
INSERT INTO `tp_login_log` VALUES ('525', '1', '61.141.178.211', '中国 广东 深圳 ', 'Chrome(52.0.2743.116)', 'Windows 10', '2016-10-15 15:22:54');
INSERT INTO `tp_login_log` VALUES ('526', '1', '121.207.18.149', '中国 福建 泉州 ', 'Chrome(49.0.2623.22)', 'Windows 10', '2016-10-15 15:25:27');
INSERT INTO `tp_login_log` VALUES ('527', '1', '119.33.194.9', '中国 广东 广州 ', 'Chrome(54.0.2840.59)', 'Linux', '2016-10-15 15:32:03');
INSERT INTO `tp_login_log` VALUES ('528', '1', '61.178.148.136', '中国 甘肃 白银 ', 'Firefox(36.04)', 'Windows NT', '2016-10-15 15:38:50');
INSERT INTO `tp_login_log` VALUES ('529', '1', '117.94.223.148', '中国 江苏 泰州 ', 'Firefox(47.0)', 'Windows 7', '2016-10-15 15:43:15');
INSERT INTO `tp_login_log` VALUES ('530', '1', '49.220.33.204', '中国 湖南 长沙 ', 'IE(7.0)', 'Windows 10', '2016-10-15 15:43:27');
INSERT INTO `tp_login_log` VALUES ('531', '1', '183.39.55.38', '中国 广东 深圳 ', 'Chrome(53.0.2785.143)', '未知操作系统', '2016-10-15 15:45:15');
INSERT INTO `tp_login_log` VALUES ('532', '1', '183.56.249.199', '中国 广东 潮州 ', 'Chrome(54.0.2840.59)', 'Windows 10', '2016-10-15 16:13:50');
INSERT INTO `tp_login_log` VALUES ('533', '1', '113.134.235.108', '中国 陕西 榆林 ', 'Chrome(51.0.2704.63)', 'Windows 10', '2016-10-15 16:29:03');
INSERT INTO `tp_login_log` VALUES ('534', '1', '220.172.53.92', '中国 贵州 贵阳 ', 'Chrome(52.0.2743.116)', '未知操作系统', '2016-10-15 16:39:00');
INSERT INTO `tp_login_log` VALUES ('535', '1', '1.180.235.54', '中国 内蒙古 呼和浩特 ', 'IE(7.0)', 'Windows 10', '2016-10-15 16:41:32');
INSERT INTO `tp_login_log` VALUES ('536', '1', '120.6.97.35', '中国 河北 秦皇岛 ', 'Chrome(49.0.2623.75)', 'Windows XP', '2016-10-15 16:57:49');
INSERT INTO `tp_login_log` VALUES ('537', '1', '117.152.94.242', '中国 湖北 武汉 ', 'IE(8.0)', 'Windows 7', '2016-10-15 17:27:09');
INSERT INTO `tp_login_log` VALUES ('538', '1', '119.130.96.25', '中国 广东 广州 ', 'IE(7.0)', 'Windows 7', '2016-10-15 17:31:49');
INSERT INTO `tp_login_log` VALUES ('539', '1', '125.94.209.196', '中国 广东 广州 ', 'IE(7.0)', 'Windows 10', '2016-10-15 17:44:49');
INSERT INTO `tp_login_log` VALUES ('540', '1', '220.115.175.42', '中国 广东 广州 ', 'Firefox(49.0)', 'Windows 7', '2016-10-15 18:04:04');
INSERT INTO `tp_login_log` VALUES ('541', '1', '122.4.212.46', '中国 山东 潍坊 ', 'IE(7.0)', 'Windows 7', '2016-10-15 18:12:36');
INSERT INTO `tp_login_log` VALUES ('542', '1', '211.161.246.179', '中国 上海 上海 ', 'Firefox(49.0)', 'Windows NT', '2016-10-15 18:20:47');
INSERT INTO `tp_login_log` VALUES ('543', '1', '114.103.106.180', '中国 安徽 黄山 ', 'IE(8.0)', 'Windows 7', '2016-10-15 18:24:55');
INSERT INTO `tp_login_log` VALUES ('544', '1', '115.205.106.71', '中国 浙江 杭州 ', 'IE(8.0)', 'Windows 7', '2016-10-15 18:25:56');
INSERT INTO `tp_login_log` VALUES ('545', '1', '117.145.17.69', '中国 新疆 喀什地区 ', 'Firefox(49.0)', 'Windows 7', '2016-10-15 18:39:17');
INSERT INTO `tp_login_log` VALUES ('546', '1', '218.18.248.76', '中国 广东 深圳 ', 'Chrome(48.0.2564.82)', 'Linux', '2016-10-15 18:40:02');
INSERT INTO `tp_login_log` VALUES ('547', '1', '42.53.135.245', '中国 辽宁 葫芦岛 ', 'IE(7.0)', 'Windows 10', '2016-10-15 18:47:06');
INSERT INTO `tp_login_log` VALUES ('548', '1', '123.149.0.116', '中国 河南 郑州 ', 'IE(7.0)', 'Windows 7', '2016-10-15 19:06:42');
INSERT INTO `tp_login_log` VALUES ('549', '1', '125.92.7.122', '中国 广东 江门 ', 'Firefox(51.0)', 'Windows 10', '2016-10-15 19:11:55');
INSERT INTO `tp_login_log` VALUES ('550', '1', '14.116.37.247', '中国 广东 珠海 ', 'Firefox(49.0)', 'Linux', '2016-10-15 19:37:01');
INSERT INTO `tp_login_log` VALUES ('551', '1', '59.42.184.112', '中国 广东 广州 ', 'Chrome(53.0.2785.143)', 'Windows 7', '2016-10-15 19:37:16');
INSERT INTO `tp_login_log` VALUES ('552', '1', '120.210.181.182', '中国 安徽 池州 ', 'Chrome(53.0.2785.116)', 'Windows 7', '2016-10-15 19:45:01');
INSERT INTO `tp_login_log` VALUES ('553', '1', '180.136.164.133', '中国 广西 南宁 ', 'Chrome(31.0.1650.63)', 'Windows 7', '2016-10-15 20:25:45');
INSERT INTO `tp_login_log` VALUES ('554', '1', '222.84.161.224', '中国 广西 桂林 ', 'Chrome(49.0.2623.22)', 'Windows 7', '2016-10-15 20:34:42');
INSERT INTO `tp_login_log` VALUES ('555', '1', '27.192.216.111', '中国 山东 潍坊 ', 'Chrome(54.0.2840.59)', 'Windows 10', '2016-10-15 20:59:48');
INSERT INTO `tp_login_log` VALUES ('556', '1', '101.41.207.236', '中国 北京 北京 ', 'Chrome(53.0.2785.143)', '未知操作系统', '2016-10-15 21:02:07');
INSERT INTO `tp_login_log` VALUES ('557', '1', '123.150.105.241', '中国 天津 天津 ', 'Chrome(53.0.2785.143)', 'Windows 10', '2016-10-15 21:04:33');
INSERT INTO `tp_login_log` VALUES ('558', '1', '27.14.215.212', '中国 重庆 重庆 ', 'Chrome(35.0.1916.138)', 'Linux', '2016-10-15 21:29:30');
INSERT INTO `tp_login_log` VALUES ('559', '1', '101.22.235.205', '中国 河北 衡水 ', 'IE(7.0)', 'Windows 7', '2016-10-15 21:33:59');
INSERT INTO `tp_login_log` VALUES ('560', '1', '123.244.133.71', '中国 辽宁 盘锦 ', 'Firefox(47.0)', '未知操作系统', '2016-10-15 21:40:37');
INSERT INTO `tp_login_log` VALUES ('561', '1', '113.56.183.235', '中国 湖北 宜昌 ', 'Chrome(53.0.2785.143)', '未知操作系统', '2016-10-15 21:54:51');
INSERT INTO `tp_login_log` VALUES ('562', '1', '113.228.138.141', '中国 辽宁 抚顺 ', 'Chrome(53.0.2785.143)', 'Windows 10', '2016-10-15 21:56:59');
INSERT INTO `tp_login_log` VALUES ('563', '1', '106.114.157.137', '中国 河北 石家庄 ', 'IE(7.0)', 'Windows 7', '2016-10-15 22:02:37');
INSERT INTO `tp_login_log` VALUES ('564', '1', '115.60.4.208', '中国 河南 郑州 ', 'IE(7.0)', 'Windows 7', '2016-10-15 22:05:54');
INSERT INTO `tp_login_log` VALUES ('565', '1', '223.73.57.215', '中国 广东 广州 ', 'Chrome(52.0.2743.116)', 'Windows 10', '2016-10-15 22:07:18');
INSERT INTO `tp_login_log` VALUES ('566', '1', '101.81.1.139', '中国 上海 上海 ', 'IE(7.0)', 'Windows NT', '2016-10-15 22:08:07');
INSERT INTO `tp_login_log` VALUES ('567', '1', '124.205.212.24', '中国 北京 北京 ', 'Chrome(52.0.2743.116)', '未知操作系统', '2016-10-15 22:09:22');
INSERT INTO `tp_login_log` VALUES ('568', '1', '27.214.223.36', '中国 山东 潍坊 ', '未知浏览器()', '未知操作系统', '2016-10-15 22:19:32');
INSERT INTO `tp_login_log` VALUES ('569', '1', '110.85.248.28', '中国 福建 厦门 ', 'Chrome(53.0.2785.143)', 'Windows 10', '2016-10-15 22:39:48');
INSERT INTO `tp_login_log` VALUES ('570', '1', '119.130.251.6', '中国 广东 广州 ', 'IE(7.0)', 'Windows 10', '2016-10-15 23:19:38');
INSERT INTO `tp_login_log` VALUES ('571', '1', '14.18.29.53', '中国 广东 广州 ', 'Chrome(35.0.1916.138)', 'Linux', '2016-10-15 23:19:50');
INSERT INTO `tp_login_log` VALUES ('572', '1', '116.233.49.51', '中国 上海 上海 ', 'Chrome(50.0.2661.95)', 'Windows 95', '2016-10-15 23:58:21');
INSERT INTO `tp_login_log` VALUES ('573', '1', '223.73.214.70', '中国 广东 东莞 ', 'IE(8.0)', 'Windows 7', '2016-10-16 00:10:42');
INSERT INTO `tp_login_log` VALUES ('574', '1', '61.157.241.13', '中国 四川 广元 ', 'Chrome(53.0.2785.113)', 'Windows 10', '2016-10-16 00:12:34');
INSERT INTO `tp_login_log` VALUES ('575', '1', '171.37.33.228', '中国 广西 南宁 ', 'Chrome(51.0.2704.106)', 'Windows 7', '2016-10-16 00:15:15');
INSERT INTO `tp_login_log` VALUES ('576', '1', '113.118.10.207', '中国 广东 深圳 ', 'Chrome(54.0.2840.59)', '未知操作系统', '2016-10-16 01:10:06');
INSERT INTO `tp_login_log` VALUES ('577', '1', '223.74.137.119', '中国 广东 广州 ', 'IE(8.0)', 'Windows XP', '2016-10-16 01:14:47');
INSERT INTO `tp_login_log` VALUES ('578', '1', '222.44.189.37', '中国 上海 上海 ', 'Chrome(49.0.2623.87)', 'Windows 10', '2016-10-16 08:14:12');
INSERT INTO `tp_login_log` VALUES ('579', '1', '116.114.20.110', '中国 内蒙古 呼和浩特 ', 'Firefox(49.0)', '未知操作系统', '2016-10-16 08:34:25');
INSERT INTO `tp_login_log` VALUES ('580', '1', '39.191.159.172', '中国 浙江 衢州 ', 'Chrome(52.0.2743.116)', 'Windows 7', '2016-10-16 08:56:29');
INSERT INTO `tp_login_log` VALUES ('581', '1', '121.237.177.241', '中国 江苏 南京 ', 'Firefox(49.0)', 'Windows 7', '2016-10-16 09:33:11');
INSERT INTO `tp_login_log` VALUES ('582', '1', '219.156.30.160', '中国 河南 郑州 ', 'IE(7.0)', 'Windows 7', '2016-10-16 09:51:48');
INSERT INTO `tp_login_log` VALUES ('583', '1', '106.39.189.202', '中国 北京 北京 ', '未知浏览器()', 'Linux', '2016-10-16 10:06:28');
INSERT INTO `tp_login_log` VALUES ('584', '1', '115.196.67.204', '中国 浙江 杭州 ', 'Chrome(49.0.2623.22)', 'Windows 7', '2016-10-16 10:44:12');
INSERT INTO `tp_login_log` VALUES ('585', '1', '183.15.104.115', '中国 广东 深圳 ', 'Chrome(47.0.2526.106)', 'Windows NT', '2016-10-16 10:49:00');
INSERT INTO `tp_login_log` VALUES ('586', '1', '112.109.216.148', '中国 福建 泉州 ', 'Chrome(49.0.2623.22)', 'Windows 7', '2016-10-16 10:58:53');
INSERT INTO `tp_login_log` VALUES ('587', '1', '115.156.147.32', '中国 湖北 武汉 ', 'Edge(12.10240)', 'Windows 10', '2016-10-16 11:05:47');
INSERT INTO `tp_login_log` VALUES ('588', '1', '118.254.218.26', '中国 湖南 衡阳 ', 'Firefox(49.0)', 'Windows 7', '2016-10-16 11:07:34');
INSERT INTO `tp_login_log` VALUES ('589', '1', '120.209.44.6', '中国 安徽 淮南 ', 'Chrome(53.0.2785.116)', 'Windows 7', '2016-10-16 11:16:27');
INSERT INTO `tp_login_log` VALUES ('590', '1', '1.30.132.209', '中国 内蒙古 呼和浩特 ', 'Firefox(49.0)', 'Windows NT', '2016-10-16 11:21:10');
INSERT INTO `tp_login_log` VALUES ('591', '1', '27.38.154.82', '中国 广东 深圳 ', 'Chrome(52.0.2743.116)', 'Windows NT', '2016-10-16 11:27:49');
INSERT INTO `tp_login_log` VALUES ('592', '1', '183.134.71.241', '中国 浙江 绍兴 ', 'IE(7.0)', 'Windows 7', '2016-10-16 11:37:01');
INSERT INTO `tp_login_log` VALUES ('593', '1', '223.87.36.60', '中国 四川 德阳 ', 'Chrome(49.0.2623.22)', 'Windows NT', '2016-10-16 11:44:45');
INSERT INTO `tp_login_log` VALUES ('594', '1', '219.128.75.226', '中国 广东 佛山 ', 'Firefox(49.0)', 'Windows 10', '2016-10-16 11:59:22');
INSERT INTO `tp_login_log` VALUES ('595', '1', '182.138.218.138', '中国 四川 成都 ', 'Opera(39.0.2256.48)', 'Windows 7', '2016-10-16 12:20:21');
INSERT INTO `tp_login_log` VALUES ('596', '1', '223.73.61.146', '中国 广东 广州 ', 'Firefox(49.0)', 'Windows 7', '2016-10-16 12:29:45');
INSERT INTO `tp_login_log` VALUES ('597', '1', '49.221.62.199', '中国 陕西 西安 ', 'Firefox(49.0)', 'Windows 10', '2016-10-16 12:47:23');
INSERT INTO `tp_login_log` VALUES ('598', '1', '61.153.150.106', '中国 浙江 宁波 ', 'Chrome(50.0.2661.95)', 'Windows 95', '2016-10-16 12:58:14');
INSERT INTO `tp_login_log` VALUES ('599', '1', '14.23.233.9', '中国 广东 广州 ', 'Chrome(53.0.2785.116)', 'Windows 10', '2016-10-16 12:58:20');
INSERT INTO `tp_login_log` VALUES ('600', '1', '120.32.212.95', '中国 福建 厦门 ', '未知浏览器()', '未知操作系统', '2016-10-16 12:59:59');
INSERT INTO `tp_login_log` VALUES ('601', '1', '175.43.103.221', '中国 福建 泉州 ', 'Chrome(45.0.2454.93)', 'Windows 7', '2016-10-16 13:05:56');
INSERT INTO `tp_login_log` VALUES ('602', '1', '122.7.171.238', '中国 山东 泰安 ', 'Chrome(55.0.2883.9)', 'Windows 7', '2016-10-16 13:07:32');
INSERT INTO `tp_login_log` VALUES ('603', '1', '180.106.43.66', '中国 江苏 苏州 ', 'Chrome(53.0.2785.116)', 'Windows 10', '2016-10-16 13:17:51');
INSERT INTO `tp_login_log` VALUES ('604', '1', '219.141.118.11', '中国 贵州 六盘水 ', 'Chrome(47.0.2626.106)', 'Windows 7', '2016-10-16 13:33:44');
INSERT INTO `tp_login_log` VALUES ('605', '1', '218.66.223.4', '中国 福建 宁德 ', '未知浏览器()', '未知操作系统', '2016-10-16 14:08:50');
INSERT INTO `tp_login_log` VALUES ('606', '1', '223.11.23.1', '中国 山西 太原 ', 'Chrome(53.0.2785.143)', '未知操作系统', '2016-10-16 14:22:27');
INSERT INTO `tp_login_log` VALUES ('607', '1', '114.116.17.14', '中国 广东 深圳 ', 'Firefox(49.0)', 'Windows 10', '2016-10-16 14:29:06');
INSERT INTO `tp_login_log` VALUES ('608', '1', '222.210.137.201', '中国 四川 成都 ', 'Chrome(38.0.2125.122)', 'Windows 7', '2016-10-16 14:42:51');
INSERT INTO `tp_login_log` VALUES ('609', '1', '42.91.168.190', '中国 甘肃 兰州 ', 'Chrome(47.0.2526.80)', 'Windows 10', '2016-10-16 14:48:11');
INSERT INTO `tp_login_log` VALUES ('610', '1', '27.198.1.125', '中国 山东 济宁 ', 'IE(7.0)', 'Windows 7', '2016-10-16 14:48:57');
INSERT INTO `tp_login_log` VALUES ('611', '1', '27.204.60.206', '中国 山东 莱芜 ', 'Chrome(53.0.2785.143)', 'Linux', '2016-10-16 14:59:29');
INSERT INTO `tp_login_log` VALUES ('612', '1', '121.226.134.136', '中国 江苏 宿迁 ', 'IE(7.0)', 'Windows 7', '2016-10-16 15:01:34');
INSERT INTO `tp_login_log` VALUES ('613', '1', '27.204.60.206', '中国 山东 莱芜 ', 'IE(7.0)', 'Windows 7', '2016-10-16 15:01:48');
INSERT INTO `tp_login_log` VALUES ('614', '1', '121.194.169.234', '中国 北京 北京 ', 'Chrome(52.0.2743.82)', 'Linux', '2016-10-16 15:22:28');
INSERT INTO `tp_login_log` VALUES ('615', '1', '171.214.159.89', '中国 四川 成都 ', 'Chrome(54.0.2840.59)', 'Windows 10', '2016-10-16 15:39:43');
INSERT INTO `tp_login_log` VALUES ('616', '1', '121.31.189.173', '中国 广西 北海 ', 'Chrome(53.0.2785.116)', 'Windows NT', '2016-10-16 16:32:12');
INSERT INTO `tp_login_log` VALUES ('617', '1', '116.23.154.241', '中国 广东 广州 ', 'IE(7.0)', 'Windows 7', '2016-10-16 16:38:34');
INSERT INTO `tp_login_log` VALUES ('618', '1', '117.158.148.60', '中国 河南 郑州 ', 'Chrome(53.0.2785.113)', 'Windows 7', '2016-10-16 16:48:31');
INSERT INTO `tp_login_log` VALUES ('619', '1', '175.162.170.251', '中国 辽宁 大连 ', 'Chrome(54.0.2840.59)', 'Windows 10', '2016-10-16 16:49:28');
INSERT INTO `tp_login_log` VALUES ('620', '1', '220.179.49.134', '中国 安徽 宿州 ', 'Chrome(52.0.2743.82)', 'Windows 10', '2016-10-16 16:51:19');
INSERT INTO `tp_login_log` VALUES ('621', '1', '58.61.137.149', '中国 广东 深圳 ', 'IE(7.0)', 'Windows 7', '2016-10-16 16:52:48');
INSERT INTO `tp_login_log` VALUES ('622', '1', '116.20.60.233', '中国 广东 佛山 ', 'Firefox(47.0)', 'Windows 7', '2016-10-16 16:59:46');
INSERT INTO `tp_login_log` VALUES ('623', '1', '118.119.182.33', '中国 四川 乐山 ', 'IE(11.0)', 'Windows 7', '2016-10-16 17:02:24');
INSERT INTO `tp_login_log` VALUES ('624', '1', '1.71.217.211', '中国 山西 运城 ', 'IE(7.0)', 'Windows 7', '2016-10-16 17:03:17');
INSERT INTO `tp_login_log` VALUES ('625', '1', '119.136.100.23', '中国 广东 深圳 ', 'Firefox(49.0)', 'Windows 7', '2016-10-16 17:33:55');
INSERT INTO `tp_login_log` VALUES ('626', '1', '182.18.104.96', '中国 北京 北京 ', 'IE(7.0)', 'Windows 10', '2016-10-16 17:51:42');
INSERT INTO `tp_login_log` VALUES ('627', '1', '113.73.33.193', '中国 广东 中山 ', 'Chrome(52.0.2743.116)', 'Windows 10', '2016-10-16 18:01:46');
INSERT INTO `tp_login_log` VALUES ('628', '1', '119.136.100.23', '中国 广东 深圳 ', 'Firefox(49.0)', 'Windows 7', '2016-10-16 18:01:48');
INSERT INTO `tp_login_log` VALUES ('629', '1', '183.228.108.17', '中国 重庆 重庆 ', 'Chrome(52.0.2743.116)', 'Windows 10', '2016-10-16 18:07:14');
INSERT INTO `tp_login_log` VALUES ('630', '1', '218.5.141.97', '中国 福建 泉州 ', 'IE(7.0)', 'Windows 7', '2016-10-16 18:23:08');
INSERT INTO `tp_login_log` VALUES ('631', '1', '60.6.238.7', '中国 河北 邢台 ', 'Chrome(54.0.2840.59)', 'Windows 10', '2016-10-16 18:31:54');
INSERT INTO `tp_login_log` VALUES ('632', '1', '111.132.204.225', '中国 北京 北京 ', 'Chrome(52.0.2743.82)', 'Windows 7', '2016-10-16 19:05:10');
INSERT INTO `tp_login_log` VALUES ('633', '1', '183.196.194.143', '中国 河北 秦皇岛 ', 'IE(8.0)', 'Windows 7', '2016-10-16 19:50:43');
INSERT INTO `tp_login_log` VALUES ('634', '1', '101.22.235.205', '中国 河北 衡水 ', 'IE(7.0)', 'Windows 7', '2016-10-16 19:53:08');
INSERT INTO `tp_login_log` VALUES ('635', '1', '211.161.66.209', '中国 广东 惠州 ', 'Chrome(47.0.2526.106)', 'Windows NT', '2016-10-16 20:18:29');
INSERT INTO `tp_login_log` VALUES ('636', '1', '36.149.135.177', '中国 江苏 苏州 ', 'Chrome(42.0.2311.154)', 'Windows 10', '2016-10-16 20:24:06');
INSERT INTO `tp_login_log` VALUES ('637', '1', '113.222.209.81', '中国 湖南 株洲 ', 'Chrome(55.0.2873.0)', 'Windows 10', '2016-10-16 20:42:30');
INSERT INTO `tp_login_log` VALUES ('638', '1', '182.86.243.194', '中国 江西 吉安 ', 'IE(7.0)', 'Windows 7', '2016-10-16 20:53:50');
INSERT INTO `tp_login_log` VALUES ('639', '1', '58.42.64.60', '中国 贵州 黔南布依族苗族自治州 ', 'Firefox(49.0)', 'Windows 7', '2016-10-16 20:55:53');
INSERT INTO `tp_login_log` VALUES ('640', '1', '182.148.75.221', '中国 四川 成都 ', 'Chrome(53.0.2785.143)', 'Windows 7', '2016-10-16 21:02:57');
INSERT INTO `tp_login_log` VALUES ('641', '1', '49.211.217.115', '中国 江西 南昌 ', 'Chrome(52.0.2743.116)', 'Windows 8', '2016-10-16 21:06:39');
INSERT INTO `tp_login_log` VALUES ('642', '1', '112.247.70.232', '中国 山东 菏泽 ', 'Chrome(53.0.2785.143)', 'Windows 7', '2016-10-16 21:27:24');
INSERT INTO `tp_login_log` VALUES ('643', '1', '183.228.214.16', '中国 重庆 重庆 ', 'IE(9.0)', 'Windows 10', '2016-10-16 21:29:52');
INSERT INTO `tp_login_log` VALUES ('644', '1', '39.91.32.86', '中国 山东 济南 ', 'Chrome(53.0.2785.143)', 'Windows 10', '2016-10-16 21:45:49');
INSERT INTO `tp_login_log` VALUES ('645', '1', '222.211.221.35', '中国 四川 成都 ', 'Chrome(47.0.2526.108)', 'Windows 10', '2016-10-16 21:52:39');
INSERT INTO `tp_login_log` VALUES ('646', '1', '127.0.0.1', '本机地址 本机地址  ', 'Chrome(52.0.2743.116)', 'Linux', '2016-10-17 21:43:49');
INSERT INTO `tp_login_log` VALUES ('647', '1', '127.0.0.1', '本机地址 本机地址  ', 'Chrome(52.0.2743.116)', 'Linux', '2016-10-17 22:22:04');
INSERT INTO `tp_login_log` VALUES ('648', '1', '127.0.0.1', '本机地址 本机地址  ', 'Chrome(52.0.2743.116)', 'Linux', '2016-10-18 14:58:26');
INSERT INTO `tp_login_log` VALUES ('649', '1', '127.0.0.1', '本机地址 本机地址  ', 'Chrome(52.0.2743.116)', 'Linux', '2016-10-18 19:42:08');
INSERT INTO `tp_login_log` VALUES ('650', '1', '127.0.0.1', '本机地址 本机地址  ', 'Chrome(52.0.2743.116)', 'Linux', '2016-10-18 19:42:22');
INSERT INTO `tp_login_log` VALUES ('651', '2', '127.0.0.1', '本机地址 本机地址  ', 'Chrome(52.0.2743.116)', 'Linux', '2016-10-18 19:48:20');
INSERT INTO `tp_login_log` VALUES ('652', '1', '127.0.0.1', '本机地址 本机地址  ', 'Chrome(52.0.2743.116)', 'Linux', '2016-10-19 20:02:13');
INSERT INTO `tp_login_log` VALUES ('653', '1', '127.0.0.1', '本机地址 本机地址  ', 'Chrome(52.0.2743.116)', 'Linux', '2016-10-20 20:00:49');
INSERT INTO `tp_login_log` VALUES ('654', '1', '127.0.0.1', '本机地址 本机地址  ', 'Chrome(52.0.2743.116)', 'Linux', '2016-10-20 21:24:09');
INSERT INTO `tp_login_log` VALUES ('655', '1', '127.0.0.1', '本机地址 本机地址  ', 'Chrome(52.0.2743.116)', 'Linux', '2016-10-25 20:16:08');
INSERT INTO `tp_login_log` VALUES ('656', '2', '127.0.0.1', '本机地址 本机地址  ', 'Chrome(52.0.2743.116)', 'Linux', '2016-10-25 22:00:06');
INSERT INTO `tp_login_log` VALUES ('657', '1', '127.0.0.1', '本机地址 本机地址  ', 'Chrome(52.0.2743.116)', 'Linux', '2016-10-26 19:55:06');
INSERT INTO `tp_login_log` VALUES ('658', '1', '127.0.0.1', '本机地址 本机地址  ', 'Chrome(52.0.2743.116)', 'Linux', '2016-10-27 10:36:28');

-- ----------------------------
-- Table structure for tp_node_map
-- ----------------------------
DROP TABLE IF EXISTS `tp_node_map`;
CREATE TABLE `tp_node_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `module` char(6) NOT NULL COMMENT '模块',
  `map` varchar(255) NOT NULL COMMENT '节点图',
  `is_ajax` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否是ajax请求',
  `comment` varchar(255) NOT NULL COMMENT '节点图描述',
  PRIMARY KEY (`id`),
  KEY `map` (`map`)
) ENGINE=InnoDB AUTO_INCREMENT=489 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='节点图';

-- ----------------------------
-- Records of tp_node_map
-- ----------------------------
INSERT INTO `tp_node_map` VALUES ('362', 'admin', 'AdminGroup/index', '0', 'AdminGroup 首页');
INSERT INTO `tp_node_map` VALUES ('363', 'admin', 'AdminGroup/recycleBin', '0', 'AdminGroup 回收站');
INSERT INTO `tp_node_map` VALUES ('364', 'admin', 'AdminGroup/add', '0', 'AdminGroup 添加');
INSERT INTO `tp_node_map` VALUES ('365', 'admin', 'AdminGroup/edit', '0', '{:__user__}编辑分组管理');
INSERT INTO `tp_node_map` VALUES ('366', 'admin', 'AdminGroup/delete', '0', 'AdminGroup 默认删除操作');
INSERT INTO `tp_node_map` VALUES ('367', 'admin', 'AdminGroup/recycle', '0', 'AdminGroup 从回收站恢复');
INSERT INTO `tp_node_map` VALUES ('368', 'admin', 'AdminGroup/forbid', '0', 'AdminGroup 默认禁用操作');
INSERT INTO `tp_node_map` VALUES ('369', 'admin', 'AdminGroup/resume', '0', 'AdminGroup 默认恢复操作');
INSERT INTO `tp_node_map` VALUES ('370', 'admin', 'AdminGroup/deleteForever', '0', 'AdminGroup 永久删除');
INSERT INTO `tp_node_map` VALUES ('371', 'admin', 'AdminGroup/clear', '0', 'AdminGroup 清空回收站');
INSERT INTO `tp_node_map` VALUES ('377', 'admin', 'AdminNode/load', '0', 'AdminNode 节点快速导入');
INSERT INTO `tp_node_map` VALUES ('378', 'admin', 'AdminNode/index', '0', 'AdminNode 首页');
INSERT INTO `tp_node_map` VALUES ('379', 'admin', 'AdminNode/recycleBin', '0', 'AdminNode 回收站');
INSERT INTO `tp_node_map` VALUES ('380', 'admin', 'AdminNode/add', '0', 'AdminNode 添加');
INSERT INTO `tp_node_map` VALUES ('381', 'admin', 'AdminNode/edit', '0', 'AdminNode 编辑');
INSERT INTO `tp_node_map` VALUES ('382', 'admin', 'AdminNode/delete', '0', 'AdminNode 默认删除操作');
INSERT INTO `tp_node_map` VALUES ('383', 'admin', 'AdminNode/recycle', '0', 'AdminNode 从回收站恢复');
INSERT INTO `tp_node_map` VALUES ('384', 'admin', 'AdminNode/forbid', '0', 'AdminNode 默认禁用操作');
INSERT INTO `tp_node_map` VALUES ('385', 'admin', 'AdminNode/resume', '0', 'AdminNode 默认恢复操作');
INSERT INTO `tp_node_map` VALUES ('386', 'admin', 'AdminNode/deleteForever', '0', 'AdminNode 永久删除');
INSERT INTO `tp_node_map` VALUES ('387', 'admin', 'AdminNode/clear', '0', 'AdminNode 清空回收站');
INSERT INTO `tp_node_map` VALUES ('392', 'admin', 'AdminNodeLoad/index', '0', 'AdminNodeLoad 首页');
INSERT INTO `tp_node_map` VALUES ('393', 'admin', 'AdminNodeLoad/recycleBin', '0', 'AdminNodeLoad 回收站');
INSERT INTO `tp_node_map` VALUES ('394', 'admin', 'AdminNodeLoad/add', '0', 'AdminNodeLoad 添加');
INSERT INTO `tp_node_map` VALUES ('395', 'admin', 'AdminNodeLoad/edit', '0', 'AdminNodeLoad 编辑');
INSERT INTO `tp_node_map` VALUES ('396', 'admin', 'AdminNodeLoad/forbid', '0', 'AdminNodeLoad 默认禁用操作');
INSERT INTO `tp_node_map` VALUES ('397', 'admin', 'AdminNodeLoad/resume', '0', 'AdminNodeLoad 默认恢复操作');
INSERT INTO `tp_node_map` VALUES ('398', 'admin', 'AdminNodeLoad/deleteForever', '0', 'AdminNodeLoad 永久删除');
INSERT INTO `tp_node_map` VALUES ('399', 'admin', 'AdminNodeLoad/clear', '0', 'AdminNodeLoad 清空回收站');
INSERT INTO `tp_node_map` VALUES ('407', 'admin', 'AdminRole/user', '0', 'AdminRole 用户列表');
INSERT INTO `tp_node_map` VALUES ('408', 'admin', 'AdminRole/access', '0', 'AdminRole 授权');
INSERT INTO `tp_node_map` VALUES ('409', 'admin', 'AdminRole/index', '0', 'AdminRole 首页');
INSERT INTO `tp_node_map` VALUES ('410', 'admin', 'AdminRole/recycleBin', '0', 'AdminRole 回收站');
INSERT INTO `tp_node_map` VALUES ('411', 'admin', 'AdminRole/add', '0', 'AdminRole 添加');
INSERT INTO `tp_node_map` VALUES ('412', 'admin', 'AdminRole/edit', '0', 'AdminRole 编辑');
INSERT INTO `tp_node_map` VALUES ('413', 'admin', 'AdminRole/delete', '0', 'AdminRole 默认删除操作');
INSERT INTO `tp_node_map` VALUES ('414', 'admin', 'AdminRole/recycle', '0', 'AdminRole 从回收站恢复');
INSERT INTO `tp_node_map` VALUES ('415', 'admin', 'AdminRole/forbid', '0', 'AdminRole 默认禁用操作');
INSERT INTO `tp_node_map` VALUES ('416', 'admin', 'AdminRole/resume', '0', 'AdminRole 默认恢复操作');
INSERT INTO `tp_node_map` VALUES ('417', 'admin', 'AdminRole/deleteForever', '0', 'AdminRole 永久删除');
INSERT INTO `tp_node_map` VALUES ('418', 'admin', 'AdminRole/clear', '0', 'AdminRole 清空回收站');
INSERT INTO `tp_node_map` VALUES ('422', 'admin', 'AdminUser/password', '0', 'AdminUser 修改密码');
INSERT INTO `tp_node_map` VALUES ('423', 'admin', 'AdminUser/index', '0', 'AdminUser 首页');
INSERT INTO `tp_node_map` VALUES ('424', 'admin', 'AdminUser/recycleBin', '0', 'AdminUser 回收站');
INSERT INTO `tp_node_map` VALUES ('425', 'admin', 'AdminUser/add', '0', 'AdminUser 添加');
INSERT INTO `tp_node_map` VALUES ('426', 'admin', 'AdminUser/edit', '0', '{:__user__}编辑用户{:id}');
INSERT INTO `tp_node_map` VALUES ('427', 'admin', 'AdminUser/recycle', '0', 'AdminUser 从回收站恢复');
INSERT INTO `tp_node_map` VALUES ('428', 'admin', 'AdminUser/forbid', '0', 'AdminUser 默认禁用操作');
INSERT INTO `tp_node_map` VALUES ('429', 'admin', 'AdminUser/resume', '0', 'AdminUser 默认恢复操作');
INSERT INTO `tp_node_map` VALUES ('437', 'admin', 'Demo/excel', '0', 'Demo Excel一键导出');
INSERT INTO `tp_node_map` VALUES ('438', 'admin', 'Demo/download', '0', 'Demo 下载文件');
INSERT INTO `tp_node_map` VALUES ('439', 'admin', 'Demo/downloadImage', '0', 'Demo 下载远程图片');
INSERT INTO `tp_node_map` VALUES ('440', 'admin', 'Demo/mail', '0', 'Demo 发送邮件');
INSERT INTO `tp_node_map` VALUES ('441', 'admin', 'Demo/ueditor', '0', 'Demo 百度编辑器');
INSERT INTO `tp_node_map` VALUES ('442', 'admin', 'Demo/qiniu', '0', 'Demo 七牛上传');
INSERT INTO `tp_node_map` VALUES ('443', 'admin', 'Demo/hashids', '0', 'Demo ID加密');
INSERT INTO `tp_node_map` VALUES ('444', 'admin', 'Demo/layer', '0', 'Demo 丰富弹层');
INSERT INTO `tp_node_map` VALUES ('445', 'admin', 'Demo/tableFixed', '0', 'Demo 表格溢出');
INSERT INTO `tp_node_map` VALUES ('446', 'admin', 'Demo/imageUpload', '0', 'Demo 图片上传回调');
INSERT INTO `tp_node_map` VALUES ('452', 'admin', 'Index/index', '0', 'Index ');
INSERT INTO `tp_node_map` VALUES ('453', 'admin', 'Index/welcome', '0', 'Index 欢迎页');
INSERT INTO `tp_node_map` VALUES ('455', 'admin', 'LoginLog/index', '0', 'LoginLog 首页');
INSERT INTO `tp_node_map` VALUES ('456', 'admin', 'LoginLog/clear', '0', 'LoginLog 清空回收站');
INSERT INTO `tp_node_map` VALUES ('458', 'admin', 'NodeMap/load', '0', 'NodeMap 自动导入');
INSERT INTO `tp_node_map` VALUES ('459', 'admin', 'NodeMap/index', '0', 'NodeMap 首页');
INSERT INTO `tp_node_map` VALUES ('460', 'admin', 'NodeMap/add', '0', 'NodeMap 添加');
INSERT INTO `tp_node_map` VALUES ('461', 'admin', 'NodeMap/edit', '0', 'NodeMap 编辑');
INSERT INTO `tp_node_map` VALUES ('462', 'admin', 'NodeMap/deleteForever', '0', 'NodeMap 永久删除');
INSERT INTO `tp_node_map` VALUES ('465', 'admin', 'Pub/login', '0', 'Pub 用户登录页面');
INSERT INTO `tp_node_map` VALUES ('466', 'admin', 'Pub/loginFrame', '0', 'Pub 小窗口登录页面');
INSERT INTO `tp_node_map` VALUES ('468', 'admin', 'Pub/logout', '0', 'Pub 用户登出');
INSERT INTO `tp_node_map` VALUES ('469', 'admin', 'Pub/checkLogin', '0', 'Pub 登录检测');
INSERT INTO `tp_node_map` VALUES ('470', 'admin', 'Pub/password', '0', 'Pub 修改密码');
INSERT INTO `tp_node_map` VALUES ('471', 'admin', 'Pub/profile', '0', 'Pub 查看用户信息|修改资料');
INSERT INTO `tp_node_map` VALUES ('472', 'admin', 'Upload/index', '0', 'Upload ');
INSERT INTO `tp_node_map` VALUES ('473', 'admin', 'Upload/upload', '0', 'Upload 文件上传');
INSERT INTO `tp_node_map` VALUES ('474', 'admin', 'Upload/remote', '0', 'Upload 远程图片抓取');
INSERT INTO `tp_node_map` VALUES ('475', 'admin', 'Upload/listImage', '0', 'Upload 图片列表');
INSERT INTO `tp_node_map` VALUES ('483', 'admin', 'WebLog/index', '0', 'WebLog ');
INSERT INTO `tp_node_map` VALUES ('484', 'admin', 'WebLog/detail', '0', 'WebLog ');
INSERT INTO `tp_node_map` VALUES ('486', 'admin', 'Pub/index', '0', 'Pub 首页');
INSERT INTO `tp_node_map` VALUES ('487', 'admin', 'AdminGroup/edit', '1', '{:__user__}编辑了分组管理{:id}修改名称为{:name}');
INSERT INTO `tp_node_map` VALUES ('488', 'admin', 'AdminUser/edit', '1', '{:__user__}编辑了用户{:id}，修改真实名字为{:realname}');

-- ----------------------------
-- Table structure for tp_web_log_001
-- ----------------------------
DROP TABLE IF EXISTS `tp_web_log_001`;
CREATE TABLE `tp_web_log_001` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志主键',
  `uid` smallint(5) unsigned NOT NULL COMMENT '用户id',
  `ip` char(15) NOT NULL COMMENT '访客ip',
  `location` varchar(255) NOT NULL COMMENT '访客地址',
  `os` varchar(255) NOT NULL COMMENT '操作系统',
  `browser` varchar(255) NOT NULL COMMENT '浏览器',
  `url` varchar(255) NOT NULL COMMENT 'url',
  `module` char(6) NOT NULL COMMENT '模块',
  `map` varchar(255) NOT NULL COMMENT '节点图',
  `is_ajax` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否是ajax请求',
  `data` text NOT NULL COMMENT '请求的param数据，serialize后的',
  `otime` int(10) unsigned NOT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `ip` (`ip`),
  KEY `map` (`map`),
  KEY `otime` (`otime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='网站日志';

-- ----------------------------
-- Records of tp_web_log_001
-- ----------------------------

-- ----------------------------
-- Table structure for tp_web_log_all
-- ----------------------------
DROP TABLE IF EXISTS `tp_web_log_all`;
CREATE TABLE `tp_web_log_all` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志主键',
  `uid` smallint(5) unsigned NOT NULL COMMENT '用户id',
  `ip` char(15) NOT NULL COMMENT '访客ip',
  `location` varchar(255) NOT NULL COMMENT '访客地址',
  `os` varchar(255) NOT NULL COMMENT '操作系统',
  `browser` varchar(255) NOT NULL COMMENT '浏览器',
  `url` varchar(255) NOT NULL COMMENT 'url',
  `module` char(6) NOT NULL COMMENT '模块',
  `map` varchar(255) NOT NULL COMMENT '节点图',
  `is_ajax` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否是ajax请求',
  `data` text NOT NULL COMMENT '请求的param数据，serialize后的',
  `otime` int(10) unsigned NOT NULL COMMENT '操作时间',
  KEY `id` (`id`),
  KEY `uid` (`uid`),
  KEY `ip` (`ip`),
  KEY `map` (`map`),
  KEY `otime` (`otime`)
) ENGINE=MRG_MyISAM DEFAULT CHARSET=utf8 INSERT_METHOD=LAST UNION=(`tp_web_log_001`);

-- ----------------------------
-- Records of tp_web_log_all
-- ----------------------------
