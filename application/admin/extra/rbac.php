<?php
// +----------------------------------------------------------------------
// | tpadmin [a web admin based ThinkPHP5]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 tianpian
// +----------------------------------------------------------------------
// | Author: tianpian <tianpian0805@gmail.com>
// +----------------------------------------------------------------------

return [
    //RBAC权限验证
    'user_auth_on'=>true,
    'user_auth_type'=>2, // 默认认证类型 1 登录认证 2 实时认证
    'user_auth_key'=>'auth_id',	// 用户认证SESSION标记
    'admin_auth_key'=>'administrator',
    'user_auth_model'=>'AdminUser',	// 默认验证数据表模型
    'user_auth_gateway'=>'Common/login',	// 默认认证网关
    'not_auth_controller'=>'',		// 默认无需认证模块
    'require_auth_controller'=>'',		// 默认需要认证模块
    'not_auth_action'=>'',		// 默认无需认证操作
    'require_auth_action'=>'',		// 默认需要认证操作
    'common_auth_name'=>'common',		// 公共授权控制器名称和方法名称
    'guest_auth_on'=>false,    // 是否开启游客授权访问
    'guest_auth_id'=>0,     // 游客的用户ID

    'role_table'=>'admin_role', //角色表名称，不包含表前缀
    'user_table'=>'admin_role_user', //用户角色关系表名称，不包含表前缀
    'access_table'=>'admin_access', //权限表名称，不包含表前缀
    'node_table'=>'admin_node', //节点表名称，不包含表前缀
];