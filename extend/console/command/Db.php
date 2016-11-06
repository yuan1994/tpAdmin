<?php
/**
 * tpAdmin [a web admin based ThinkPHP5]
 *
 * @author    yuan1994 <tianpian0805@gmail.com>
 * @link      http://tpadmin.yuan1994.com/
 * @copyright 2016 yuan1994 all rights reserved.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 */

namespace console\command;

use think\Config;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\console\output\Question;
use think\Exception;
use think\Db as ThinkDb;

class Db extends Command
{


    protected function configure()
    {
        // 指令配置
        $this
            ->setName('db')
            ->setDefinition([
                new Option('db', 'd', Option::VALUE_OPTIONAL, "Which a database you want to look up , defaults to the config you set"),
                new Option('table', 't', Option::VALUE_OPTIONAL, "Which a table you want to look up , defaults to the config you set"),
                new Option('prefix', 'p', Option::VALUE_OPTIONAL, "The current table`s prefix , defaults to the config you set"),
            ])
            ->setDescription('Look up the databases details and the tables details')
            ->setHelp(<<<EOF
The <info>%command.name%</info> command can look up all tables of the current default database then you can look up the table`s details that you option :

  <info>php %command.full_name%</info>

You can assign the database name that you want to look up :

  <info>php %command.full_name% --db tpadmin</info> 

You can also immediately look up the table details like this , the table`s prefix name is omitted :

  <info>php %command.full_name% --table admin_user</info>

If you want to assign the table`s prefix name , you can command :

  <info>php %command.full_name% --table admin_user --prefix tp_</info>

EOF
            );
    }

    protected function execute(Input $input, Output $output)
    {
        // 查看表详情
        if ($input->hasOption('table')) {
            $prefix = $input->getOption('prefix') ?: Config::get('database.prefix');
            $table = $prefix . $input->getOption('table');
            $this->outputTable($table, $output);

            return 0;
        }
        // 查看数据库详情
        $db = $input->getOption('db') ?: Config::get('database.database');
        $tables = ThinkDb::getTables($db);
        $this->outputDb($tables, $db, $output);

        while (1) {
            $answer = $output->ask($input, "请选择一个你想查看详情的数据表（输入序号或者表名），直接回车退出：\n Please option a table that you want to look up it`s details ( input a No. or a table name ) , or enter to exit :");
            if (null === $answer) {
                return 0;
            }
            if (is_numeric($answer)) {
                if (!isset($tables[$answer])) {
                    $output->error(' 不存在该表：' . $answer);
                    continue;
                }
                $this->outputTable($tables[$answer], $output);
            } else {
                if (!in_array($answer, $tables)) {
                    $output->error(' 不存在该表：' . $answer);
                    continue;
                }
                $this->outputTable($answer, $output);
            }
        }

        return 0;
    }

    /**
     * 自动获取列宽，适用于关联数组
     */
    private function getColumnWidthAs(array &$commands, $default = 0)
    {
        // 设置默认值
        foreach (end($commands) as $key => $val) {
            $width[$key] = $default;
        }
        foreach ($commands as &$command) {
            foreach ($command as $key => &$val) {
                if (null === $val) {
                    $val = 'NULL';
                } elseif (false === $val) {
                    $val = 'FALSE';
                } elseif (true === $val) {
                    $val = 'TRUE';
                }
                $width[$key] = max(strlen($val), $width[$key], strlen($key));
            }
        }

        return $width;
    }

    /**
     * 自动获取列宽，适用于索引数组
     */
    private function getColumnWidthIdx(array &$commands, array $header = [0, 1], $default = 3)
    {
        // 设置默认值
        foreach ($header as $key => $val) {
            $width[$val] = is_array($default) ? $default[$key] : $default;
        }
        foreach ($commands as $key => &$command) {
            if (null === $command) {
                $command = 'NULL';
            } elseif (false === $command) {
                $command = 'FALSE';
            } elseif (true === $command) {
                $command = 'TRUE';
            }
            $width[1] = max(strlen($command), $width[1]);
        }

        return $width;
    }

    /**
     * 输出数据表信息
     */
    private function outputTable($table, $output)
    {
        $fields = ThinkDb::getFields($table);
        $width = $this->getColumnWidthAs($fields);
        $breakLine = '<info>+</info>';
        $fieldLine = '<info>|</info>';
        // 表头
        foreach ($width as $key => $val) {
            $breakLine .= str_repeat('-', $val + 2) . '+';
            $fieldLine .= ' ' . ucfirst($key) . str_repeat(' ', $val - strlen($key) + 1) . '<info>|</info>';
        }
        $output->comment('Table : ' . $table);
        $output->info($breakLine);
        $output->writeln($fieldLine);
        $output->info($breakLine);
        // 表体
        foreach ($fields as $field) {
            $fieldLine = '<info>|</info>';
            foreach ($field as $key => $val) {
                $fieldLine .= ' ' . $val . str_repeat(' ', $width[$key] - strlen($val) + 1) . '<info>|</info>';
            }
            $output->writeln($fieldLine);
        }
        $output->info($breakLine);
    }

    /**
     * 输出数据库信息
     */
    private function outputDb($tables, $db, $output) {
        $width = $this->getColumnWidthIdx($tables, [0, 1], [3, 5]);
        $breakLine = '<info>+</info>';
        $fieldLine = '<info>|</info>';
        // 表头
        $header = ['No.', 'Table'];
        foreach ($width as $key => $val) {
            $breakLine .= str_repeat('-', $val + 2) . '<info>+</info>';
            $fieldLine .= ' ' . $header[$key] . str_repeat(' ', $val - strlen($header[$key]) + 1) . '<info>|</info>';
        }
        $output->comment('Database : ' . $db);
        $output->info($breakLine);
        $output->writeln($fieldLine);
        $output->info($breakLine);
        // 表体
        foreach ($tables as $key => $table) {
            $fieldLine = '<info>|</info> ' . $key . str_repeat(' ', $width[0] - strlen($key) + 1)
                . '<info>|</info> ' . $table . str_repeat(' ', $width[1] - strlen($table) + 1) . '<info>|</info>';
            $output->writeln($fieldLine);
        }
        $output->info($breakLine);
    }
}
