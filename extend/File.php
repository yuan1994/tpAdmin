<?php
/**
 * tpAdmin [a web admin based ThinkPHP5]
 *
 * @author yuan1994 <tianpian0805@gmail.com>
 * @link http://tpadmin.yuan1994.com/
 * @copyright 2016 yuan1994 all rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

//------------------------
// 文件下载和图片下载
//-------------------------

class File
{
    /**
     * 文件下载
     * @param $file_path
     * @param string $file_name
     * @param string $file_size
     * @param string $ext
     */
    public static function download($file_path, $file_name = '', $file_size = '', $ext = '')
    {
        if (!$file_name) {
            $file_name = basename($file_path);
        }
        if (!$file_size && in_array(substr($file_path, 0, 1), [".", "/"])) {
            try {
                $file_size = filesize($file_path);
            } catch (\Exception $e) {
//                return $e->getMessage();
                return "文件不存在";
            }
        }
        if (!$ext) {
            $ext = pathinfo($file_path, PATHINFO_EXTENSION);
        }
        if ($ext && !strpos($file_name, ".")) {
            $file_name = $file_name . "." . $ext;
        }
        $content_type = self::getMime($ext);

        header("Cache-Control:");
        header("Cache-Control: public");

        // 文件类型
        header("Content-type: {$content_type}");

        // 处理中文文件名
        $ua = $_SERVER["HTTP_USER_AGENT"];
        if (preg_match("/MSIE/", $ua)) {
            $encoded_filename = rawurlencode($file_name);
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
        } else if (preg_match("/Firefox/", $ua)) {
            header("Content-Disposition: attachment; filename*=\"utf8''" . $file_name . '"');
        } else {
            header('Content-Disposition: attachment; filename="' . $file_name . '"');
        }
        // 文件大小
        if ($file_size) {
            header("Accept-Length: ". $file_size);
            header("Content-Length: " . $file_size);
        }
        readfile($file_path);
    }

    /**
     * 功能：php多种方式完美实现下载远程图片保存到本地
     * 参数：文件url,保存文件名称，使用的下载方式
     * 当保存文件名称为空时则使用远程文件原来的名称
     * @param string $url      请求图片的链接
     * @param string $filename 保存的文件名
     * @param int $type        保存图片的类型 0为curl,适用于静态图片,其他为缓冲缓存,适用于动态图片
     * @return string $filename 返回保存的文件名
     */
    public static function downloadImage($url, $filename, $type = 0)
    {
        if ($url == '') {
            return false;
        }
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'ico', 'tif', 'tiff'])) {
            $ext = pathinfo($url, PATHINFO_EXTENSION);
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'ico', 'tif', 'tiff'])) {
                $ext = 'jpg';
            }
            $filename = $filename . "." . $ext;
        }

        //下载文件流
        if ($type) {
            $ch = curl_init();
            $timeout = 5;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $img = curl_exec($ch);
            curl_close($ch);
        } else {
            ob_start();
            readfile($url);
            $img = ob_get_contents();
            ob_end_clean();
        }

        //保存文件
//        try {
            $fp2 = fopen($filename, 'w');
            fwrite($fp2, $img);
            fclose($fp2);
            return $filename;
        /*} catch (\think\Exception $e) {
            //TODO 异常处理
            return false;
        }*/
    }

    /**
     * 获取文件Mime
     * @param string $ext
     * @return string
     */
    public static function getMime($ext)
    {
        $mimes = [
            'xml'   => 'text/xml',
            'json'  => 'application/json',
            'js'    => 'text/javascript',
            'php'   => 'application/octet-stream',
            'css'   => 'text/css',
            'html'  => 'text/html',
            'htm'   => 'text/html',
            'xhtml' => 'text/html',
            'rss'   => 'application/rss+xml',
            'yaml'  => 'application/x-yaml',
            'atom'  => 'application/atom+xml',
            'pdf'   => 'application/pdf',
            'text'  => 'text/plain',
            'png'   => 'image/png',
            'jpg'   => 'image/jpeg',
            'gif'   => 'image/gif',
            'csv'   => 'text/csv',
            'tif'   => 'image/tiff',
            'ai'    => 'application/postscript',
            'asp'   => 'text/asp',
            'au'    => 'audio/basic',
            'avi'   => 'video/avi',
            'rmvb'  => 'application/vnd.rn-realmedia-vbr',
            '3gp'   => 'application/octet-stream',
            'flv'   => 'application/octet-stream',
            'mp3'   => 'audio/mpeg',
            'wav'   => 'audio/wav',
            'sql'   => 'application/octet-stream',
            'rar'   => 'application/octet-stream',
            'zip'   => 'application/zip',
            '7z'    => 'application/octet-stream',
            'bmp'   => 'application/x-bmp',
            'cdr'   => 'application/x-cdr',
            'class' => 'java/*',
            'exe'   => 'application/x-msdownload',
            'fax'   => 'image/fax',
            'icb'   => 'application/x-icb',
            'ico'   => 'image/x-icon',
            'java'  => 'java/*',
            'jfif'  => 'image/jpeg',
            'jpeg'  => 'image/jpeg',
            'jsp'   => 'text/html',
            'mp4'   => 'video/mpeg4',
            'mpa'   => 'video/x-mpg',
            'mpeg'  => 'video/mpg',
            'mpg'   => 'video/mpg',
            'mpga'  => 'audio/rn-mpeg',
            'ras'   => 'application/x-ras',
            'tiff'  => 'image/tiff',
            'txt'   => 'text/plain',
            'wax'   => 'audio/x-ms-wax',
            'wm'    => 'video/x-ms-wm',
            'apk'   => 'application/vnd.android.package-archive',
            'doc'   => 'application/msword',
            'dot'   => 'application/msword',
            'docx'  => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'dotx'  => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
            'docm'  => 'application/vnd.ms-word.document.macroEnabled.12',
            'dotm'  => 'application/vnd.ms-word.template.macroEnabled.12',
            'xls'   => 'application/vnd.ms-excel',
            'xlt'   => 'application/vnd.ms-excel',
            'xla'   => 'application/vnd.ms-excel',
            'xlsx'  => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'xltx'  => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
            'xlsm'  => 'application/vnd.ms-excel.sheet.macroEnabled.12',
            'xltm'  => 'application/vnd.ms-excel.template.macroEnabled.12',
            'xlam'  => 'application/vnd.ms-excel.addin.macroEnabled.12',
            'xlsb'  => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
            'ppt'   => 'application/vnd.ms-powerpoint',
            'pot'   => 'application/vnd.ms-powerpoint',
            'pps'   => 'application/vnd.ms-powerpoint',
            'ppa'   => 'application/vnd.ms-powerpoint',
            'pptx'  => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'potx'  => 'application/vnd.openxmlformats-officedocument.presentationml.template',
            'ppsx'  => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
            'ppam'  => 'application/vnd.ms-powerpoint.addin.macroEnabled.12',
            'pptm'  => 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
            'potm'  => 'application/vnd.ms-powerpoint.template.macroEnabled.12',
            'ppsm'  => 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
        ];
        return isset($mimes[$ext]) ? $mimes[$ext] : 'application/octet-stream';
    }
}
