<?php
// +----------------------------------------------------------------------
// | tpadmin [a web admin based ThinkPHP5]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 tianpian
// +----------------------------------------------------------------------
// | Author: tianpian <tianpian0805@gmail.com>
// +----------------------------------------------------------------------

//------------------------
// 百度富文本编辑器文件上传
//-------------------------

namespace app\admin\controller;

class Ueditor{
    public function index(){
        $action = input("param.action");
        if (method_exists($this,$action)){
            $this->$action();
        } else {
            return json(['state'=> "请求地址出错"]);
        }
    }

    /**
     * 配置项
     */
    public function config(){
        $img_url = config('qiniu.domain');
        $config = array(
            /* 上传图片配置项 */
            "imageActionName" => "upload", /* 执行上传图片的action名称 */
            "imageMaxSize" => 1048576, /* 上传大小限制，单位B */
            "imageAllowFiles" => [".png", ".jpg", ".jpeg"], /* 上传图片格式显示 */
            "imageInsertAlign" => "none", /* 插入的图片浮动方式 */
            "imageUrlPrefix" => $img_url, /* 图片访问路径前缀 */
            /* 涂鸦图片上传配置项 */
            "scrawlActionName" => "upload", /* 执行上传涂鸦的action名称 */
            "scrawlMaxSize" => 1048576, /* 上传大小限制，单位B */
            "scrawlUrlPrefix" => $img_url, /* 图片访问路径前缀 */
            "scrawlInsertAlign" => "none",

            /* 截图工具上传 */
            "snapscreenActionName" => "upload", /* 执行上传截图的action名称 */
            "snapscreenUrlPrefix" => $img_url, /* 图片访问路径前缀 */
            "snapscreenInsertAlign" => "none", /* 插入的图片浮动方式 */

            /* 抓取远程图片配置 */
            "catcherLocalDomain" => ["127.0.0.1","localhost","img.baidu.com",preg_replace("/^https?:\/\/(.*?)\/?$/","$1",$img_url),preg_replace("/^https?:\/\/(.*?)\/?$/","$1",request()->domain())],
            "catcherActionName" => "remote", /* 执行抓取远程图片的action名称 */
            "catcherFieldName" => "source", /* 提交的图片列表表单名称 */
            "catcherUrlPrefix" => $img_url, /* 图片访问路径前缀 */
            "catcherMaxSize" => 2048000, /* 上传大小限制，单位B */
            "catcherAllowFiles" => [".png", ".jpg", ".jpeg"], /* 抓取图片格式显示 */

            /* 上传视频配置 */
            "videoActionName" => "uploadVideo", /* 执行上传视频的action名称 */
            "videoFieldName" => "upfile", /* 提交的视频表单名称 */
            "videoMaxSize" => 102400000, /* 上传大小限制，单位B，默认100MB */
            "videoAllowFiles" => [
                ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
                ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid"], /* 上传视频格式显示 */

            /* 上传文件配置 */
            "fileActionName" => "uploadFile", /* controller里,执行上传视频的action名称 */
            "fileFieldName" => "upfile", /* 提交的文件表单名称 */
            "fileUrlPrefix" => $img_url, /* 文件访问路径前缀 */
            "fileMaxSize" => 51200000, /* 上传大小限制，单位B，默认50MB */
            "fileAllowFiles" => [
                ".png", ".jpg", ".jpeg"
            ], /* 上传文件格式显示 */

            /* 列出指定目录下的图片 */
            "imageManagerActionName" => "listimage", /* 执行图片管理的action名称 */
            "imageManagerListSize" => 20, /* 每次列出文件数量 */
            "imageManagerUrlPrefix" => $img_url, /* 图片访问路径前缀 */
            "imageManagerInsertAlign" => "none", /* 插入的图片浮动方式 */
            "imageManagerAllowFiles" => [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 列出的文件类型 */
        );

        return json_encode($config);
    }

    /**
     * 图片上传
     */
    public function upload($prefix="image/",$cate=1){
        $upload = \Qiniu::instance();
        $info = $upload->upload($prefix);
        $error = $upload->getError();

        //保存图片到数据库
        if ($info){
            $model = model("File");
            $domain = config('qiniu.domain');
            foreach ($info as $v){
                if (is_array($v)){
                    $model->insertRecord($v,$cate,$domain);
                }
            }
        }
        if (!empty($error)){
            $ret['state'] = $error;
        } else {
            $ret['state'] = "SUCCESS";
        }
        return json($ret);
    }

    /**
     * 上传文件
     * @return \think\response\Json
     */
    public function uploadFile(){
        return $this->upload("file/",2);
    }

    /**
     * 上传视频
     * @return \think\response\Json
     */
    public function uploadVideo(){
        return $this->upload("video/",3);
    }

    /**
     * 列出本地素材库
     */
    public function listimage(){
        /* 获取参数 */
        $size = input("param.size",20,'intval');
        $start = input("param.start",0,'intval');

        $model = db("File");
        $count = $model->count("*");
        if (!$count){ //$count为0
            $ret = array(
                "state" => "no match file",
                "list" => array(),
                "start" => $start,
                "total" => $count
            );
        } else {
            $list = $model->field("`name` AS url,mtime")->limit($start,$size)->select();

            $ret = array(
                "state" => "SUCCESS",
                "list" => $list,
                "start" => $start,
                "total" => intval($count)
            );
        }
        return json($ret);
    }

    /**
     * 远程图片抓取
     */
    public function remote(){
        $sources = input("param.source");

        //遍历上传
        $ret["list"] = [];
        $upload = \Qiniu::instance();
        $model = model("File");
        $domain = config('qiniu.domain');
        foreach ($sources as $source){
            $file_name = basename($source);
            $file_path = TEMP_PATH.$file_name;
            //远程下载图片到本地
            $file_path = \File::downloadImage($source,$file_path,1);

            //上传图片
            $info = $upload->uploadOne($file_path,"image/");
            if ($error = $upload->getError()){
                $ret['list'][] = array('state'=> $error);
            } else {
                //上传成功，将数据写入到本地数据库中
                $model->insertRecord($info,1,$domain);

                $ret['list'][] = array(
                    'state' => "SUCCESS",
                    'url' => $info['key'],
                    'source' => $source
                );
            }
            //删除临时下载的文件
            unlink($file_path);
        }
        return json($ret);
    }
}