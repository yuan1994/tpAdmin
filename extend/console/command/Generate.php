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
use think\Loader;

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
                new Option('delete', 'd', Option::VALUE_OPTIONAL, "If you set this`s value to 1 or true, the files and directory will be removed just generated, please be careful and the operation won`t be restored", '0'),
            ])
            ->setDescription('Automatic generating code')
            ->setHelp(<<<EOF
The <info>%command.name%</info> command can generate all files include php and html , config file :

  <info>php %command.full_name%</info>

You can use this method to set the default config file that you want . You can omit the file`s extension .
The default root path of the config file is ROOT_PATH , your project root path :

  <info>php %command.full_name% --config generate</info> 
  Or <info>php %command.full_name% --config generate.php</info>
  
If you want to assign the root path to the module path , you can input <info>module</info><error>:</error><info>controller</info> , and you can also assign the file :

  <info>php %command.full_name% --config admin:controller_name</info> 
  And <info>php %command.full_name% --config admin:second.dirname.ControllerName/tpconfig.php</info>

You can assign the file you want to generate using the <info>--file</info> option .
All the options are all|controller|model|validate|table|edit|index|recycleBin|form|th|td|config|dir , defaults to all :

  <info>php %command.full_name% --file controller</info>

EOF
            );
    }

    protected function execute(Input $input, Output $output)
    {

        $output->info('代码开始生成中……');
        $config = $input->getOption('config');
        if (strpos($config, ':')) {
            // 针对于指向模块的文件 module:controller[/file.php]
            list($module, $controller) = explode(':', $config, 2);
            $file = 'config.php';
            // 对于module:controller/file.php类型处理
            if (pathinfo($controller, PATHINFO_EXTENSION) == 'php') {
                $file = basename($controller);
                $controller = dirname($controller);
            }
            $controller = str_replace('.', DS, Loader::parseName(str_replace(['/', DS, ':'], '.', $controller)));
            $configFile = APP_PATH . strtolower($module) . DS . 'view' . DS . $controller . DS . $file;
        } else {
            $config = explode(".", $config);
            $configFile = ROOT_PATH . $config[0] . '.php';
        }

        if (!file_exists($configFile)) {
            $output->error('配置文件不存在：' . $configFile);

            return false;
        }
        try {
            $data = include $configFile;
            $data['delete_file'] = $input->getOption('delete');
            $generate = new \Generate();
            $generate->run($data, $input->getOption('file'));
            if ($data['delete_file']) {
                $output->warning('代码删除成功！');
            } else {
                $output->info('代码生成成功！');
            }
        } catch (Exception $e) {
            $errMsg = $e->getMessage();
            if ($e->getCode() == 403) {
                $errMsg = str_replace('<br>', "\n", $errMsg);
            }
            $output->error($errMsg);
        }
    }

}
