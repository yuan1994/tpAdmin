<?php

return [
    'controller'         => 'Test',
    'controller_title'   => '测试',
    'form'               => [
        [
            'title'       => '字段一',
            'name'        => 'field1',
            'type'        => 'text',
            'option'      => '选项值， 1:值一#2:值二#3:值三',
            'value'       => '默认值',
            'sort'        => false,
            'search'      => false,
            'search_type' => '',
            'require'     => false,
            'validate'    => [
                'datatype' => 'm',
                'nullmsg'  => '为空信息',
                'errormsg' => '错误信息',
            ],
        ],
    ],
    'create_table'       => true,
    'create_table_force' => true,
    'table_name'         => '',
    'auto_create_field'  => ['id', 'status', 'create_time', 'update_time'],
    'table_engine'       => 'InnoDB',
    'field'              => [
        [
            'name'        => 'field1',
            'type'        => 'varchar(255)',
            'default'     => false,
            'allow_null'  => false,
            'key'         => false,
            'primary_key' => false,
            'comment'     => '',
            'extra'       => '', // 扩展属性，例如AUTO_INCREMENT
        ],
    ],
    'menu'               => ['add', 'forbid', 'resume', 'delete', 'recycleBin'],
    'model'              => true,
    'validate'           => true,
];