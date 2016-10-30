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
// 百度富文本编辑器文件上传
//-------------------------

namespace app\admin\controller;

use think\Request;
use think\Response;
use think\Config;
use think\Loader;
use think\Db;

class Ueditor
{
    // 图片默认域名
    private $imgUrl;

    public function __construct()
    {
        $this->imgUrl = Config::get('qiniu.domain');
    }

    public function index()
    {
        $action = Request::instance()->param('action');
        if (method_exists($this, $action)) {
            return $this->$action();
        } else {
            return Response::create(['state' => "请求地址出错"], 'json');
        }
    }

    /**
     * 配置项
     */
    public function config()
    {
        $config = [
            /* 上传图片配置项 */
            "imageActionName"         => "upload", /* 执行上传图片的action名称 */
            "imageMaxSize"            => 1048576, /* 上传大小限制，单位B */
            "imageAllowFiles"         => [".png", ".jpg", ".jpeg"], /* 上传图片格式显示 */
            "imageInsertAlign"        => "none", /* 插入的图片浮动方式 */
            "imageUrlPrefix"          => $this->imgUrl, /* 图片访问路径前缀 */
            /* 涂鸦图片上传配置项 */
            "scrawlActionName"        => "upload", /* 执行上传涂鸦的action名称 */
            "scrawlMaxSize"           => 1048576, /* 上传大小限制，单位B */
            "scrawlUrlPrefix"         => $this->imgUrl, /* 图片访问路径前缀 */
            "scrawlInsertAlign"       => "none",

            /* 截图工具上传 */
            "snapscreenActionName"    => "upload", /* 执行上传截图的action名称 */
            "snapscreenUrlPrefix"     => $this->imgUrl, /* 图片访问路径前缀 */
            "snapscreenInsertAlign"   => "none", /* 插入的图片浮动方式 */

            /* 抓取远程图片配置 */
            "catcherLocalDomain"      => [
                "127.0.0.1",
                "localhost",
                "img.baidu.com",
                preg_replace("/^https?:\/\/(.*?)\/?$/", "$1", $this->imgUrl),
                preg_replace("/^https?:\/\/(.*?)\/?$/", "$1", Request::instance()->domain()),
            ],
            "catcherActionName"       => "remote", /* 执行抓取远程图片的action名称 */
            "catcherFieldName"        => "source", /* 提交的图片列表表单名称 */
            "catcherUrlPrefix"        => $this->imgUrl, /* 图片访问路径前缀 */
            "catcherMaxSize"          => 2048000, /* 上传大小限制，单位B */
            "catcherAllowFiles"       => [".png", ".jpg", ".jpeg"], /* 抓取图片格式显示 */

            /* 上传视频配置 */
            "videoActionName"         => "uploadVideo", /* 执行上传视频的action名称 */
            "videoFieldName"          => "upfile", /* 提交的视频表单名称 */
            "videoMaxSize"            => 102400000, /* 上传大小限制，单位B，默认100MB */
            "videoAllowFiles"         => [
                ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
                ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid"], /* 上传视频格式显示 */

            /* 上传文件配置 */
            "fileActionName"          => "uploadFile", /* controller里,执行上传视频的action名称 */
            "fileFieldName"           => "upfile", /* 提交的文件表单名称 */
            "fileUrlPrefix"           => $this->imgUrl, /* 文件访问路径前缀 */
            "fileMaxSize"             => 51200000, /* 上传大小限制，单位B，默认50MB */
            "fileAllowFiles"          => [
                ".png", ".jpg", ".jpeg",
            ], /* 上传文件格式显示 */

            /* 列出指定目录下的图片 */
            "imageManagerActionName"  => "listImage", /* 执行图片管理的action名称 */
            "imageManagerListSize"    => 20, /* 每次列出文件数量 */
            "imageManagerUrlPrefix"   => $this->imgUrl, /* 图片访问路径前缀 */
            "imageManagerInsertAlign" => "none", /* 插入的图片浮动方式 */
            "imageManagerAllowFiles"  => [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 列出的文件类型 */
        ];

        return Response::create($config, 'json');
    }

    /**
     * 图片上传
     */
    public function upload($prefix = "image/", $cate = 1)
    {
        $upload = \Qiniu::instance();
        $info = $upload->upload($prefix);
        $error = $upload->getError();

        // 保存图片到数据库
        if ($info) {
            $model = Loader::model("File");
            foreach ($info as $v) {
                if (is_array($v)) {
                    $model->insertRecord($v, $cate);
                }
            }
        }
        if (!empty($error)) {
            $ret['state'] = $error;
        } else {
            $ret = [
                "state"    => 'SUCCESS',
                "url"      => $info[0]['key'],
                "title"    => $info[0]['name'],
                "original" => $info[0]['name'],
                "type"     => $info[0]['type'],
                "size"     => $info[0]['size'],
            ];
        }

        return Response::create($ret, 'json');
    }

    /**
     * 上传文件
     * @return \think\response\Json
     */
    public function uploadFile()
    {
        return $this->upload("file/", 2);
    }

    /**
     * 上传视频
     * @return \think\response\Json
     */
    public function uploadVideo()
    {
        return $this->upload("video/", 3);
    }

    /**
     * 列出本地素材库
     */
    public function listImage()
    {
        // 获取参数
        $size = Request::instance()->param('size/d', 20);
        $start = Request::instance()->param('start/d', 0);

        $model = Db::name("File");
        $count = $model->where('cate=1')->count("*");
        // $count 为 0
        if (!$count) {
            $ret = [
                "state" => "no match file",
                "list"  => [],
                "start" => intval($start),
                "total" => intval($count),
            ];
        } else {
            $list = $model->where('cate=1')->field("`name` AS url,mtime")->limit($start, $size)->select();

            $ret = [
                "state" => "SUCCESS",
                "list"  => $list,
                "start" => intval($start),
                "total" => intval($count),
            ];
        }

        return Response::create($ret, 'json');
    }

    /**
     * 远程图片抓取
     */
    public function remote()
    {
        $data = Request::instance()->param();

        // 遍历上传
        $ret["list"] = [];
        $upload = \Qiniu::instance();
        $model = Loader::model("File");
        foreach ($data['source'] as $source) {
            $file_name = basename($source);
            $file_path = TEMP_PATH . $file_name;

            // 远程下载图片到本地
            $file_path = \File::downloadImage($source, $file_path, 1);

            // 上传图片
            $info = $upload->uploadOne($file_path, "image/");
            if ($error = $upload->getError()) {
                $ret['list'][] = ['state' => $error];
            } else {
                // 上传成功，将数据写入到本地数据库中
                $model->insertRecord($info, 1);

                $ret['list'][] = [
                    'state'  => "SUCCESS",
                    'url'    => $info['key'],
                    'source' => $source,
                ];
            }
            // 删除临时下载的文件
            unlink($file_path);
        }

        return Response::create($ret, 'json');
    }
}
