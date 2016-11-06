<?php
/**
 * tpAdmin [a web admin based ThinkPHP5]
 *
 * @author    yuan1994 <tianpian0805@gmail.com>
 * @link      http://tpadmin.yuan1994.com/
 * @copyright 2016 yuan1994 all rights reserved.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 */

return [
    'module'             => 'admin',
    'create_table'       => true,
    'create_table_force' => true,
    'table_name'         => '',
    'table_engine'       => 'InnoDB',
    'menu'               => ['add', 'forbid', 'resume', 'delete', 'recyclebin', 'recycle'],
    'auto_timestamp'     => true,
    'model'              => false,
    'validate'           => false,
    'create_config'      => true,
];
