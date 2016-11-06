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
use think\Exception;

class Generate extends Command
{


    protected function configure()
    {
        // 指令配置
        $this
            ->setName('generate')
            ->setDefinition([
                new Option('config', 'c', Option::VALUE_OPTIONAL, "The config file path of generate.php", 'generate.php'),
                new Option('file', 'f', Option::VALUE_OPTIONAL, "Which file you want to generate : all|controller|model|validate|table|edit|index|recycleBin|form|th|td|config|dir", 'all'),
            ])
            ->setDescription('Automatic generating code')
            ->setHelp(<<<EOF
The <info>%command.name%</info> command can generate all files include php and html , config file :

  <info>php %command.full_name%</info>

You can use this method to set the default config file that you want . You can omit the file`s extension .
The root path of the config file is ROOT_PATH , your project root path :

  <info>php %command.full_name% --config generate</info> 
  or <info>php %command.full_name% --config generate.php</info>

You can assign the file you want to generate using the <info>--file</info> option .
All the options are all|controller|model|validate|table|edit|index|recycleBin|form|th|td|config|dir , defaults to all :

  <info>php %command.full_name% --file controller</info>

EOF
            );
    }

    protected function execute(Input $input, Output $output)
    {

        $output->info('代码开始生成中……');
        $config = explode(".", $input->getOption('config'));
        $configFile = ROOT_PATH . $config[0] . '.php';
        if (!file_exists($configFile)) {
            $output->error('配置文件不存在：' . $configFile);

            return false;
        }
        try {
            $data = include $configFile;
            $generate = new \Generate();
            $generate->run($data, $input->getOption('file'));
            $output->info('代码生成成功！');
        } catch (Exception $e) {
            $errMsg = $e->getMessage();
            if ($e->getCode() == 403) {
                $errMsg = str_replace('<br>', "\n", $errMsg);
            }
            $output->error($errMsg);
        }
    }

}
