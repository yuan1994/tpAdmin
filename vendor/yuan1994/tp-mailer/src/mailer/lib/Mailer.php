<?php
/**
 * tp-mailer [A powerful and beautiful php mailer for All of ThinkPHP and Other PHP Framework based SwiftMailer]
 *
 * @author    yuan1994 <tianpian0805@gmail.com>
 * @link      https://github.com/yuan1994/tp-mailer
 * @copyright 2016 yuan1994 all rights reserved.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 */

namespace mailer\lib;

use Swift_Mailer;
use Swift_Message;

/**
 * Class Mailer
 * @package mailer\lib
 * @method Mailer view(string $template, array $param = [], array $config = [])
 */
class Mailer
{
    /*
     * @var Mailer 单例
     */
    protected static $instance;
    /**
     * @var array 注册的方法
     */
    protected static $methods = [];
    /**
     * @var \Swift_Message
     */
    protected $message;
    /**
     * @var \Swift_SmtpTransport|\Swift_SendmailTransport|\Swift_MailTransport
     */
    protected $transport;
    /**
     * @var array 以行设置文本的内容
     */
    protected $line = [];
    /**
     * @var array 注册组件列表
     */
    protected $plugin = [];
    /**
     * @var string|null 错误信息
     */
    protected $errMsg;
    /**
     * @var array|null 发送失败的帐号
     */
    protected $fails;

    /**
     * @param null $transport
     *
     * @return Mailer
     */
    public static function instance($transport = null)
    {
        if (null === self::$instance) {
            self::$instance = new static($transport);
        }

        return self::$instance;
    }

    /**
     * 动态注入方法
     *
     * @param string   $methodName
     * @param callable $methodCallable
     */
    public static function addMethod($methodName, $methodCallable)
    {
        if (!is_callable($methodCallable)) {
            throw new InvalidArgumentException('Second param must be callable');
        }
        self::$methods[$methodName] = \Closure::bind($methodCallable, Mailer::instance(), get_class());
    }

    /**
     * 动态调用方法
     *
     * @param string $methodName
     * @param array  $args
     *
     * @return $this
     */
    public function __call($methodName, array $args)
    {
        if (isset(self::$methods[$methodName])) {
            return call_user_func_array(self::$methods[$methodName], $args);
        }

        throw new BadMethodCallException('There is no method with the given name to call');
    }

    /**
     * Mailer constructor.
     *
     * @param mixed $transport
     */
    public function __construct($transport = null)
    {
        $this->transport = $transport;
        $this->init();
    }

    /**
     * 重置实例
     *
     * @return $this
     */
    public function init()
    {
        $this->message = Swift_Message::newInstance(
            null,
            null,
            Config::get('content_type'),
            Config::get('charset')
        )
            ->setFrom(Config::get('addr'), Config::get('name'));

        return $this;
    }

    /**
     * 设置邮件主题
     *
     * @param string $subject
     *
     * @return $this
     */
    public function subject($subject)
    {
        $this->message->setSubject($subject);

        return $this;
    }

    /**
     * 设置发件人
     *
     * @param string|array $address
     * @param null         $name
     *
     * @return $this
     */
    public function from($address, $name = null)
    {
        $this->message->setFrom($address, $name);

        return $this;
    }

    /**
     * 设置收件人
     *
     * @param  string|array $address
     * @param null          $name
     *
     * @return $this
     */
    public function to($address, $name = null)
    {
        $this->message->setTo($address, $name);

        return $this;
    }

    /**
     * 设置邮件内容为HTML内容
     *
     * @param string $content
     * @param array  $param
     * @param array  $config
     *
     * @return $this
     */
    public function html($content, $param = [], $config = [])
    {
        if ($param) {
            $content = strtr($content, $this->parseParam($param, $config));
        }
        $this->message->setBody($content, MailerConfig::CONTENT_HTML);

        return $this;
    }

    /**
     * 设置邮件内容为纯文本内容
     *
     * @param string $content
     * @param array  $param
     * @param array  $config
     *
     * @return $this
     */
    public function text($content, $param = [], $config = [])
    {
        if ($param) {
            $content = strtr($content, $this->parseParam($param, $config));
        }
        $this->message->setBody($content, MailerConfig::CONTENT_PLAIN);

        return $this;
    }

    /**
     * 设置邮件内容为纯文本内容
     *
     * @param string $content
     * @param array  $param
     * @param array  $config
     *
     * @return Mailer
     */
    public function raw($content, $param = [], $config = [])
    {
        return $this->text($content, $param, $config);
    }

    /**
     * 添加一行数据
     *
     * @param string $content
     * @param array  $param
     * @param array  $config
     *
     * @return $this
     */
    public function line($content = '', $param = [], $config = [])
    {
        $this->line[] = strtr($content, $this->parseParam($param, $config));

        return $this;
    }

    /**
     * 添加附件
     *
     * @param string               $filePath
     * @param string|\Closure|null $attr
     *
     * @return $this
     */
    public function attach($filePath, $attr = null)
    {
        $attachment = \Swift_Attachment::fromPath($filePath);
        if ($attr instanceof \Closure) {
            call_user_func_array($attr, [& $attachment, $this]);
        } elseif ($attr) {
            $attachment->setFilename($this->cnEncode($attr));
        } else {
            // 修复中文文件名乱码bug
            $tmp = str_replace("\\", '/', $filePath);
            $tmp = explode('/', $tmp);
            $filename = end($tmp);
            $attachment->setFilename($this->cnEncode($filename));
        }
        $this->message->attach($attachment);

        return $this;
    }

    /**
     * Signed/Encrypted Message
     *
     * @param \Swift_Signers_SMimeSigner $smimeSigner
     *
     * @return $this
     */
    public function signCertificate($smimeSigner)
    {
        if ($smimeSigner instanceof \Closure) {
            $signer = \Swift_Signers_SMimeSigner::newInstance();
            call_user_func_array($smimeSigner, [& $signer]);
            $this->message->attachSigner($signer);
        }

        return $this;
    }

    /**
     * 设置字符编码
     *
     * @param string $charset
     *
     * @return $this
     */
    public function charset($charset)
    {
        $this->message->setCharset($charset);

        return $this;
    }

    /**
     * 设置邮件最大长度
     *
     * @param int $length
     *
     * @return $this
     */
    public function lineLength($length)
    {
        $this->message->setMaxLineLength($length);

        return $this;
    }

    /**
     * 设置优先级
     *
     * @param int $priority
     *
     * @return $this
     */
    public function priority($priority = MailerConfig::PRIORITY_HIGHEST)
    {
        $this->message->setPriority($priority);

        return $this;
    }

    /**
     * Requesting a Read Receipt
     *
     * @param string $address
     *
     * @return $this
     */
    public function readReceiptTo($address)
    {
        $this->message->setReadReceiptTo($address);

        return $this;
    }

    /**
     * 注册SwiftMailer插件
     * 详情请见 http://swiftmailer.org/docs/plugins.html
     *
     * @param object $plugin
     */
    public function registerPlugin($plugin)
    {
        $this->plugin[] = $plugin;
    }

    /**
     * 获取头信息
     *
     * @return \Swift_Mime_HeaderSet
     */
    public function getHeaders()
    {
        return $this->message->getHeaders();
    }

    /**
     * 获取头信息 (字符串)
     *
     * @return string
     */
    public function getHeadersString()
    {
        return $this->getHeaders()->toString();
    }

    /**
     * 发送邮件
     *
     * @param \Closure|null        $message
     * @param \Closure|string|null $transport
     * @param \Closure|null        $send
     *
     * @return bool|int
     * @throws Exception
     */
    public function send($message = null, $transport = null, $send = null)
    {
        try {
            // 获取将行数据设置到message里
            if ($this->line) {
                $this->message->setBody(implode("\r\n", $this->line), MailerConfig::CONTENT_PLAIN);
                $this->line = [];
            }
            // 匿名函数
            if ($message instanceof \Closure) {
                call_user_func_array($message, [& $this, & $this->message]);
            }
            // 邮件驱动
            if (null === $transport && !$this->transport) {
                $transport = $this->transport;
            }
            // 直接传递的是Swift_Transport对象
            if (is_object($transport)) {
                $transportDriver = $transport;
            } else {
                // 其他匿名函数和驱动名称
                $transportInstance = Transport::instance();
                if ($transport instanceof \Closure) {
                    $transportDriver = call_user_func_array($transport, [$transportInstance]);
                } else {
                    $transportDriver = $transportInstance->getDriver($transport);
                }
            }

            $swiftMailer = Swift_Mailer::newInstance($transportDriver);
            // debug模式记录日志
            if (Config::get('debug')) {
                Log::write(var_export($this->getHeadersString(), true), Log::INFO);
            }

            // 注册插件
            if ($this->plugin) {
                foreach ($this->plugin as $plugin) {
                    $swiftMailer->registerPlugin($plugin);
                }
                $this->plugin = [];
            }

            // 发送邮件
            if ($send instanceof \Closure) {
                call_user_func_array($send, [$swiftMailer, $this]);
            } else {
                return $swiftMailer->send($this->message, $this->fails);
            }
        } catch (\Exception $e) {
            $this->errMsg = $e->getMessage();

            // 将错误信息记录在日志中
            $log = "Error: " . $this->errMsg . "\n"
                . '邮件头信息：' . "\n"
                . var_export($this->getHeadersString(), true);
            Log::write($log, Log::ERROR);

            // 异常处理
            if (Config::get('debug')) {
                // 调试模式直接抛出异常
                throw new Exception($e->getMessage());
            } else {
                return false;
            }
        }
    }

    /**
     * 获取错误信息
     *
     * @return mixed
     */
    public function getError()
    {
        return $this->errMsg;
    }

    /**
     * 获取发送错误的邮箱帐号列表
     *
     * @return mixed
     */
    public function getFails()
    {
        return $this->fails;
    }

    /**
     * 中文文件名编码, 防止乱码
     *
     * @param string $string
     *
     * @return string
     */
    public function cnEncode($string)
    {
        return "=?UTF-8?B?" . base64_encode($string) . "?=";
    }

    /**
     * 将参数中的key值替换为可替换符号
     *
     * @param array $param
     * @param array $config
     *
     * @return mixed
     */
    protected function parseParam(array $param, array $config = [])
    {
        $ret = [];
        $leftDelimiter = isset($config['left_delimiter'])
            ? $config['left_delimiter']
            : Config::get('left_delimiter', '{');
        $rightDelimiter = isset($config['right_delimiter'])
            ? $config['right_delimiter']
            : Config::get('right_delimiter', '}');
        foreach ($param as $k => $v) {
            // 处理变量中包含有对元数据嵌入的变量
            $this->embedImage($k, $v, $param);
            $ret[$leftDelimiter . $k . $rightDelimiter] = $v;
        }

        return $ret;
    }

    /**
     * 对嵌入元数据的变量进行处理
     *
     * @param string $k
     * @param string $v
     * @param array  $param
     */
    protected function embedImage(&$k, &$v, &$param)
    {
        $flag = Config::get('embed', 'embed:');
        if (false !== strpos($k, $flag)) {
            if (is_array($v) && $v) {
                if (!isset($v[1])) {
                    $v[1] = MailerConfig::MIME_JPEG;
                }
                if (!isset($v[2])) {
                    $v[2] = 'image.jpg';
                }
                list($imgData, $name, $mime) = $v;
                $v = $this->message->embed(
                    \Swift_Image::newInstance($imgData, $name, $mime)
                );
            } else {
                $v = $this->message->embed(\Swift_Image::fromPath($v));
            }
            unset($param[$k]);
            $k = substr($k, strlen($flag));
            $param[$k] = $v;
        }
    }
}
