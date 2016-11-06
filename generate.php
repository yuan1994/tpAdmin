<?php

return [
    'module'             => 'admin',
    'controller'         => 'Test',
    'title'              => '测试',
    'form'               => [
        [
            'title'       => '字段一',
            'name'        => 'field1',
            'type'        => 'radio',
            'option'      => '1:值一#2:值二#3:值三',
            'default'     => '默认值',
            'sort'        => false,
            'search'      => true,
            'search_type' => 'select',
            'require'     => true,
            'validate'    => [
                'datatype' => '*',
                'nullmsg'  => '为空信息',
                'errormsg' => '错误信息',
            ],

        ],
        [
            'title'       => '字段一',
            'name'        => 'field2',
            'type'        => 'date',
            'option'      => '1:值一#2:值二#3:值三',
            'default'     => '2',
            'sort'        => true,
            'search'      => true,
            'search_type' => 'text',
            'require'     => true,
            'validate'    => [
                'datatype' => 'n',
                'nullmsg'  => '为空信息',
                'errormsg' => '错误信息',
            ],
        ],
        [
            'title'       => '状态',
            'name'        => 'status',
            'type'        => 'radio',
            'option'      => '1:启用#0:禁用',
            'default'     => '0',
            'sort'        => false,
            'search'      => false,
            'search_type' => 'select',
            'require'     => true,
            'validate'    => [
                'datatype' => 'n',
                'nullmsg'  => '为空信息',
                'errormsg' => '错误信息',
            ],
        ],
    ],
    'create_table'       => true,
    'create_table_force' => false,
    'table_name'         => '',
    'table_engine'       => 'InnoDB',
    'field'              => [
        [
            'name'     => 'field1',
            'type'     => 'varchar(25)',
            'default'  => 123,
            'not_null' => true,
            'key'      => true,
            'comment'  => '',
            'extra'    => '', // 扩展属性，例如AUTO_INCREMENT
        ],
        [
            'name'       => 'field2',
            'type'       => 'varchar(255)',
            'default'    => 123,
            'allow_null' => true,
            'key'        => true,
            'comment'    => '',
            'extra'      => '', // 扩展属性，例如AUTO_INCREMENT
        ],
    ],
    'menu'               => ['add', 'forbid', 'resume', 'delete', 'recyclebin'],
    'auto_timestamp'     => true,
    'model'              => false,
    'validate'           => false,
];