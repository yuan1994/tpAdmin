<?php
/**
 * tpAdmin [a web admin based ThinkPHP5]
 *
 * @author yuan1994 <tianpian0805@gmail.com>
 * @link http://tpadmin.yuan1994.com/
 * @copyright 2016 yuan1994 all rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace mail;

class Fsock
{
    private $errormsg = ""; //错误信息

    public function __construct($config = [])
    {
        $this->config = $config;
    }

    /**
     * @param string $receive //收件人
     * @param string $content //发件内容
     * @param string $subject //主题
     * @return bool
     */
    public function mail($receive, $content, $subject = 'No Subject')
    {
        //是否开启了fsockopen函数
        if (!function_exists('fsockopen')) {
            $this->errormsg = "服务器未开启fsockopen函数";
            return false;
        }
        $headers = "Content-Type: " . $this->config['content_type'] . "; charset=\"" . $this->config['charset'] . "\"\r\nContent-Transfer-Encoding: base64";
        //linebreak
        $lb = $this->config['line_break'];
        //解析后的hdr
        $hdr = explode($lb, $headers);
        //解析后的Content
        if ($content) {
            $content = preg_replace("/^\./", "..", explode($lb, $content));
        }
        $smtp = [
            //1、EHLO，期待返回220或者250
            ["EHLO " . $this->config['smtp_pc'] . $lb, "220,250", "HELO error: "],
            //2、发送Auth Login，期待返回334
            ["AUTH LOGIN" . $lb, "334", "AUTH error:"],
            //3、发送经过Base64编码的用户名，期待返回334
            [base64_encode($this->config['smtp_addr']) . $lb, "334", "AUTHENTIFICATION error : "],
            //4、发送经过Base64编码的密码，期待返回235
            [base64_encode($this->config['smtp_pass']) . $lb, "235", "AUTHENTIFICATION error : "]
        ];
        //5、发送Mail From，期待返回250
        $smtp[] = ["MAIL FROM: <" . $this->config['smtp_addr'] . ">" . $lb, "250", "MAIL FROM error: "];
        //6、发送Rcpt To。期待返回250
        $smtp[] = ["RCPT TO: <" . $receive . ">" . $lb, "250", "RCPT TO error: "];
        //7、发送DATA，期待返回354
        $smtp[] = ["DATA" . $lb, "354", "DATA error: "];
        //8.0、发送From
        $smtp[] = ["From: " . $this->config['smtp_name'] . "<" . $this->config['smtp_addr'] . ">" . $lb, "", ""];
        //8.2、发送To
        $smtp[] = ["To: " . $receive . $lb, "", ""];
        //8.1、发送标题
        $smtp[] = ["Subject: " . $subject . $lb, "", ""];
        //8.3、发送其他Header内容
        foreach ($hdr as $h) {
            $smtp[] = [$h . $lb, "", ""];
        }
        //8.4、发送一个空行，结束Header发送
        $smtp[] = [$lb, "", ""];
        //8.5、发送信件主体
        if ($content) {
            foreach ($content as $b) {
                $smtp[] = [base64_encode($b . $lb) . $lb, "", ""];
            }
        }
        //9、发送“.”表示信件结束，期待返回250
        $smtp[] = ["." . $lb, "250", "DATA(end)error: "];
        //10、发送Quit，退出，期待返回221
        $smtp[] = ["QUIT" . $lb, "221", "QUIT error: "];


        //打开smtp服务器端口
        $fp = @fsockopen($this->config['smtp_host'], $this->config['smtp_port']);
        if (!$fp) {
            $this->errormsg = "连接不到smtp服务器";
            return false;
        }
        while ($result = @fgets($fp, 1024)) {
            if (substr($result, 3, 1) == " ") {
                break;
            }
        }
        $result_str = "";
        //发送smtp数组中的命令/数据
        foreach ($smtp as $req) {
            //发送信息
            @fputs($fp, $req[0]);
            //如果需要接收服务器返回信息，则
            if ($req[1]) {
                //接收信息
                while ($result = @fgets($fp, 1024)) {
                    if (substr($result, 3, 1) == " ") {
                        break;
                    }
                };
                if (!strstr($req[1], substr($result, 0, 3))) {
                    $result_str .= $req[2] . $result . "
";
                }
            }
        }
        //关闭连接
        @fclose($fp);
        if ($result_str) {
            $this->errormsg = "邮件发送失败";
            return false;
        };
        return true;
    }

    /**
     * 获取错误信息
     */
    public function getError()
    {
        return $this->errormsg;
    }
}
