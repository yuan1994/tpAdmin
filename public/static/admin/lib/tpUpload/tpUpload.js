// +----------------------------------------------------------------------
// | tpadmin [a web admin based ThinkPHP5]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 tianpian All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: tianpian <tianpian0805@gmail.com>
// +----------------------------------------------------------------------

/**
 * H5 + iframe 兼容所有浏览器的无刷新上传方案
 */
(function ($) {
    $.fn.tpUpload = function (options) {
        var defaults = {
            'url': '', // 文件上传地址
            'data': {}, // 表单附加数据
            'event': 'change', // 触发上传的事件
            'drag': '', // 是否开启拖拽上传,开启传入拖拽框的选择器
            'dragClass': 'dragenter', // 拖拽上传拖拽框高亮样式
            // 过滤器
            'filter': function (filesArr) {
                return filesArr;
            },
            // 开始上传事件
            'start': function () {
            },
            // 进度
            'progress': function (loaded, total) {
            },
            // 上传成功
            'success': function (responseText) {
            },
            // 上传失败
            'error': function (responseText) {
            },
            // 上传超时
            'timeout': function (responseText) {
            },
            // 上传完成
            'end': function () {
            }
        };
        var settings = $.extend({}, defaults, options);
        var $self = $(this);
        // 判断是否支持H5上传
        if (typeof XMLHttpRequest === "function" && typeof FormData === "function") {
            /* H5 上传 */
            // FORM 特殊处理
            if ($self[0].tagName.toUpperCase() == 'FORM') {
                $self.attr('action', settings.url);
                // 阻止提交默认事件
                $self.submit(function () {
                    return false;
                })
            }
            // 上传事件触发
            $self.on(settings.event || 'change', function (e) {
                // 文件信息对象
                var filesArr = {};
                // FORM 特殊处理
                if ($self[0].tagName.toUpperCase() == 'FORM') {
                    for (var attr in e.target) {
                        // 非表单控件退出循环
                        if (isNaN(attr)) {
                            break;
                        }
                        if (e.target[attr].files !== null) {
                            filesArr[e.target[attr].name] = e.target[attr].files;
                        } else {
                            settings.data[e.target[attr].name] = e.target[attr].value;
                        }
                    }

                } else {
                    // INPUT[TYPE=FILE] 处理
                    filesArr[$self.attr('name')] = e.target.files || e.dataTransfer.files;
                }
                formData = buildData(filesArr);
                if (false === formData) {
                    return false;
                }
                upload(formData);
                // 清空表单
                $self.val('');
                $self.find("[type='file']").val('');
            });
            // 拖拽上传
            if (settings.drag) {
                var dragObj = $(settings.drag);
                dragObj.on('dragenter', function (e) {
                    e.stopPropagation();
                    e.preventDefault();
                    $(this).addClass(settings.dragClass);
                }).on('dragover', function (e) {
                    e.stopPropagation();
                    e.preventDefault();
                    e.originalEvent.dataTransfer.dropEffect = 'copy';
                }).on('drop', function (e) {
                    e.stopPropagation();
                    e.preventDefault();
                    var filesArr = {'file[]': e.originalEvent.dataTransfer.files};
                    formData = buildData(filesArr);
                    if (false === formData) {
                        return false;
                    }
                    upload(formData);
                    $(this).removeClass(settings.dragClass);
                }).on('dragleave', function (e) {
                    e.stopPropagation();
                    e.preventDefault();
                    $(this).removeClass(settings.dragClass);
                });
            }
            // 创建上传表单数据
            function buildData(filesArr) {
                // 过滤器
                if (typeof settings.filter == "function") {
                    filesArr = settings.filter(filesArr);
                    if (false === filesArr) {
                        return false;
                    }
                }
                var formData = new FormData();
                for (var key in filesArr) {
                    for (var i in filesArr[key]) {
                        formData.append(key, filesArr[key][i]);
                    }
                }
                for (var key in settings.data) {
                    formData.append(key, settings.data[key]);
                }

                return formData;
            }

            // 开始通过XMLHttp上传
            function upload(data) {
                var xhr = new XMLHttpRequest();
                if (xhr.upload) {
                    // 开始上传
                    xhr.upload.onloadstart = function (e) {
                        typeof settings.start == "function" && settings.start();
                    };
                    // 上传中
                    xhr.upload.onprogress = function (e) {
                        typeof settings.progress == "function" && settings.progress(e.loaded, e.total);
                    };
                    // 文件上传成功或是失败
                    xhr.onreadystatechange = function (e) {
                        if (xhr.readyState == 4) {
                            if (xhr.status == 200) {
                                try {
                                    var jsonData = JSON.parse(xhr.responseText);
                                } catch(e){
                                    throw '请返回json格式的数据';
                                }
                                typeof settings.success == "function" && settings.success(jsonData);
                            } else {
                                typeof settings.error == "function" && settings.error(xhr.responseText, xhr.status);
                            }
                        }
                    };
                    // 上传失败
                    xhr.onerror = function (e) {
                        typeof settings.error == "function" && settings.error(xhr.responseText, xhr.status);
                    };
                    // 上传超时
                    xhr.ontimeout = function (e) {
                        typeof settings.timeout == "function" && settings.timeout(xhr.responseText);
                    };
                    // 已上传
                    xhr.onloadend = function (e) {
                        typeof settings.end == "function" && settings.end();
                    };
                    // 开始上传
                    xhr.open("POST", settings.url, true);
                    xhr.send(data);
                }
            }
        } else {
            /* iframe 上传 */
            var iframe = $('<iframe style="display: none" name="tp-iframe-upload" />');
            // 自动生成 iframe
            if ($self[0].tagName.toUpperCase() == 'FORM') {
                $self.before(iframe);
                $self.attr({action: settings.url, target: 'tp-iframe-upload'})
            } else {
                $self.show().wrap('<form style="display: inline" method="post" target="tp-iframe-upload" enctype="multipart/form-data" action="' + settings.url + '"></form>').parents('form').before(iframe);
            }
            ;
            // 触发上传事件
            $self.on(settings.event || 'change', function (e) {
                typeof settings.start == "function" && settings.start();
                if ($self[0].tagName.toUpperCase() != 'FORM') {
                    $self.closest('form').submit();
                }
            });
            // 上传完成从 iframe 取回数据
            iframe.on('load', function () {
                    // 取回数据用text
                    var data = $(this).contents().find('body').text();
                    $(this).contents().find('body').html('');
                    try {
                        var jsonData = JSON.parse(data);
                    } catch(e){
                        throw '请返回json格式的数据';
                    }
                    typeof settings.success == "function" && settings.success(jsonData);
                    typeof settings.end == "function" && settings.end();
                    $self.val('');
                    $self.find("[type='file']").val('');
            });
        }
    };
})(jQuery);