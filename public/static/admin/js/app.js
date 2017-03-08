/**
 * Created by yuan1994 <tianpian0805@gamil.com> on 16-10-2.
 */

$(function () {
    //多级菜单
    $.Huifold(".menu_dropdown .sub-menu-title", ".menu_dropdown .sub-menu-list", "fast", 3, "click");

    getskincookie();
    //layer.config({extend: 'extend/layer.ext.js'});
    Huiasidedisplay();
    var resizeID;
    $(window).resize(function () {
        clearTimeout(resizeID);
        resizeID = setTimeout(function () {
            Huiasidedisplay();
        }, 500);
    });

    $(".nav-toggle").click(function () {
        $(".Hui-aside").slideToggle();
    });
    $(".Hui-aside").on("click", ".menu_dropdown dd li a", function () {
        if ($(window).width() < 768) {
            $(".Hui-aside").slideToggle();
        }
    });
    /*左侧菜单*/
    $.Huifold(".menu_dropdown dl dt", ".menu_dropdown dl dd", "fast", 3, "click");

    /*选项卡导航*/

    $(".Hui-aside").on("click", ".menu_dropdown [_href]", function () {
        $(".Hui-aside .menu_dropdown [_href]").removeClass('focus');
        $(this).addClass('focus');
        Hui_admin_tab(this);
    });

    /* 生成面包屑导航 */
    $(".Hui-aside .menu_dropdown [_href]").each(function () {
        var nav = $(this).html();
        var tmp_node = $(this);
        while (tmp_node.closest('.sub-menu-list').length) {
            nav = tmp_node.closest('.sub-menu-list').prev('.sub-menu-title').attr('data-title') + ' <span class="c-gray en">&gt;</span> ' + nav;
            tmp_node = tmp_node.closest('.sub-menu-list').prev('.sub-menu-title');
        }

        nav = tmp_node.closest('dd').prev('dt').html().replace(/(<[^>]*>.*?<\/[^>]*>)/ig, '') + ' <span class="c-gray en">&gt;</span> ' + nav;

        $(this).attr('data-nav', nav);
    });

    $(document).on("click", "#min_title_list li", function () {
        var bStopIndex = $(this).index();
        var iframe_box = $("#iframe_box");
        $("#min_title_list li").removeClass("active").eq(bStopIndex).addClass("active");
        iframe_box.find(".show_iframe").hide().eq(bStopIndex).show();
    });
    $(document).on("click", "#min_title_list li i", function () {
        var aCloseIndex = $(this).parents("li").index();
        $(this).parent().remove();
        $('#iframe_box').find('.show_iframe').eq(aCloseIndex).remove();
        num == 0 ? num = 0 : num--;
        tabNavallwidth();
    });
    $(document).on("dblclick", "#min_title_list li", function () {
        var aCloseIndex = $(this).index();
        var iframe_box = $("#iframe_box");
        if (aCloseIndex > 0) {
            $(this).remove();
            $('#iframe_box').find('.show_iframe').eq(aCloseIndex).remove();
            num == 0 ? num = 0 : num--;
            $("#min_title_list li").removeClass("active").eq(aCloseIndex - 1).addClass("active");
            iframe_box.find(".show_iframe").hide().eq(aCloseIndex - 1).show();
            tabNavallwidth();
        } else {
            return false;
        }
    });
    tabNavallwidth();

    $('#js-tabNav-next').click(function () {
        num == oUl.find('li').length - 1 ? num = oUl.find('li').length - 1 : num++;
        toNavPos();
    });
    $('#js-tabNav-prev').click(function () {
        num == 0 ? num = 0 : num--;
        toNavPos();
    });

    function toNavPos() {
        oUl.stop().animate({'left': -num * 100}, 100);
    }

    /*换肤*/
    $("#Hui-skin .dropDown-menu a").click(function () {
        var v = $(this).attr("data-val");
        setCookie("Huiskin", v);
        var hrefStr = $("#skin").attr("href");
        var hrefRes = hrefStr.substring(0, hrefStr.lastIndexOf('skin/')) + 'skin/' + v + '/skin.css';

        $(window.frames.document).contents().find("#skin").attr("href", hrefRes);
        //$("#skin").attr("href",hrefResd);
    });

    // 绑定刷新事件
    var baseUrl = location.href;
    $(document).on('click', '.btn-refresh', function () {
        window.location.href = baseUrl;
    })
});

/**
 * 弹出层
 * @param title 层标题
 * @param url 层链接(opt.type=2|默认)或者HTML内容(opt.type=1)
 * @param opt 选项 {w:WIDTH('800px|80%'),h:HEIGHT('600px|80%'),type:1|2,fn:CALLBACK(回调函数),confirm:BOOL(关闭弹层警告)}
 */
function layer_open(title, url, opt) {
    if (typeof opt === "undefined") opt = {nav: true};
    w = opt.w || "80vw";
    h = opt.h || "80vh";
    // 不支持vh,vw单位时采取js动态获取
    if (!attr_support('height', '10vh')) {
        w = w.replace(/([\d\.]+)(vh|vw)/, function (source, num, unit) {
            return $(window).width() * num / 100 + 'px';
        });
        h = h.replace(/([\d\.]+)(vh|vw)/, function (source, num, unit) {
            return $(window).height() * num / 100 + 'px';
        });
    }
    return layer.open({
        type: opt.type || 2,
        area: [w, h],
        fix: false, // 不固定
        maxmin: true,
        shade: 0.4,
        title: title,
        content: url,
        success: function (layero, index) {
            if (typeof opt.confirm !== "undefined" && opt.confirm === true) {
                layero.find(".layui-layer-close").off("click").on("click", function () {
                    layer.alert('您确定要关闭当前窗口吗？', {
                        btn: ['确定', '取消'] //按钮
                    }, function (i) {
                        layer.close(i);
                        layer.close(index);
                    });
                });
            }
            // 自动添加面包屑导航
            if (true === opt.nav) {
                layer.getChildFrame('#nav-title', index).html($('#nav-title').html() + ' <span class="c-gray en">&gt;</span> ' + $('.layui-layer-title').html());
            }
            if (typeof opt.fn === "function") {
                opt.fn(layero, index);
            }
        }
    });
};

/**
 * 全屏打开窗口，参数见layer_open
 */
function full_page(title, url, opt) {
    return layer_open(title, url, $.extend({w: "100%", h: "100%"}, opt))
};

/**
 * iframe内打开新窗口
 * @param title
 * @param url
 */
function open_window(title, url) {
    //解决在非iframe页里打开不了页面的问题
    if (window.parent.frames.length == 0) {
        window.open(url);
        return false;
    }
    var bStop = false;
    var bStopIndex = 0;
    var topWindow = $(window.top.parent.document);
    var show_navLi = topWindow.find("#min_title_list li");
    var iframe_box = topWindow.find('#iframe_box');
    show_navLi.each(function () {
        if ($(this).find('span').attr("data-href") == url) {
            bStop = true;
            bStopIndex = show_navLi.index($(this));
            return false;
        }
    });
    if (!bStop) {
        var show_nav = topWindow.find('#min_title_list');
        show_nav.find('li').removeClass("active");
        show_nav.append('<li class="active"><span data-href="' + url + '">' + title + '</span><i></i><em></em></li>');
        var taballwidth = 0,
            $tabNav = $(".acrossTab", window.top.parent.document),
            $tabNavitem = $(".acrossTab li", window.top.parent.document);
        $tabNavitem.each(function (index, element) {
            taballwidth += Number(parseFloat($(this).width() + 60))
        });
        $tabNav.width(taballwidth + 25);
        var iframeBox = iframe_box.find('.show_iframe');
        iframeBox.hide();
        iframe_box.append('<div class="show_iframe"><div class="loading"></div><iframe frameborder="0" src=' + url + '></iframe></div>');
        var showBox = iframe_box.find('.show_iframe:visible');
        showBox.find('iframe').attr("src", url).load(function () {
            showBox.find('.loading').hide();
        });
    }
    else {
        show_navLi.removeClass("active").eq(bStopIndex).addClass("active");
        iframe_box.find(".show_iframe").hide().eq(bStopIndex).show().find("iframe").attr("src", url);
    }

}

/**
 * 操作对象发送ajax请求
 * @param url 请求地址
 * @param data 请求参数
 * @param callback 成功回调
 * @param param 回调参数
 * @param shade 是否遮罩
 */
function ajax_req(url, data, callback, param, shade) {
    if (shade == true) var loading = layer.load(2);
    $.post(url, data, function (ret) {
        shade == true && layer.close(loading);
        ajax_progress(ret, callback, param);
    }, 'json')
}

/**
 * ajax 处理，对应服务端 ajax_return_adv 方法返回的 json 数据处理
 * @param data ajax返回数据
 * @param callback 成功回调函数
 * @param param 回调参数
 */
function ajax_progress(data, callback, param) {
    if (data.code == 0) {
        if (typeof data.opt == "object") {
            var index = parent.layer.getFrameIndex(window.name);
            if (data.opt.close) {
                parent.layer.close(index);
            }
            if (data.opt.redirect == 'current') {
                // 当前页重定向
                if (!data.opt.url) {
                    // 刷新
                    window.location.reload();
                } else {
                    // 重定向到 url
                    window.location.href = data.opt.url;
                }
            } else if (data.opt.redirect == 'parent') {
                // 父层重定向
                if (!data.opt.url) {
                    // 刷新
                    window.parent.location.reload();
                } else {
                    // 重定向到 url
                    window.parent.location.href = data.opt.url;
                }
                // 关闭当前层
                parent.layer.close(index);
            }
            // 父层弹出信息
            if (data.opt.alert) {
                parent.layer.alert(data.opt.alert);
                parent.layer.close(index);
            }
            if (!data.opt.close && !data.opt.redirect && !data.opt.alert) {
                parent.layer.msg(data.msg);
                parent.layer.close(index);
            }
        } else {
            layer.msg(data.msg);
        }
        if (typeof callback == "function") {
            if (typeof param != "undefined" && param) {
                param.unshift(data)
            } else {
                param = [data];
            }
            callback.apply(this, param);
        }
    } else {
        if (data.code == 400) {
            login(data.data);
        } else {
            layer.alert(data.msg, {title: "错误信息", icon: 2});
        }
    }
}

/**
 * 恢复禁用等状态改变回调函数
 * @param ret
 * @param obj
 * @param type
 */
function change_status(ret, obj, type) {
    //配置数据，TYPE:['下一状态文字描述','当前状态class颜色','下一状态class颜色','下一状态方法名','状态标签选择器','下一状态标签icon','下一状态标签title']
    var data = {
        'resume': ['禁用', 'success', 'warning', 'forbid', '.status', '&#xe615;', '正常'],
        'forbid': ['恢复', 'warning', 'success', 'resume', '.status', '&#xe631;', '禁用']
    };
    var $this = $(obj);
    $this.html(data[type][0])
        .attr("title", "点击" + data[type][0])
        .removeClass("label-" + data[type][1])
        .addClass("label-" + data[type][2])
        .attr("onclick", $this.attr("onclick").replace(new RegExp(type, 'g'), data[type][3]));
    $this.parents("tr")
        .find(data[type][4])
        .html(data[type][5])
        .removeClass("c-" + data[type][2])
        .addClass("c-" + data[type][1])
        .attr("title", data[type][6]);
}

/**
 * 动态加载javascript或style文件
 * @param src
 * @param callback
 * @param type
 */
function load_file(src, callback, type) {
    type = type || 'script';
    var head = document.getElementsByTagName('head')[0];
    if (type == 'script') {
        var node = document.createElement('script');
        node.type = 'text/javascript';
        node.charset = 'UTF-8';
        node.src = src;
    } else {
        var node = document.createElement('link');
        node.rel = 'stylesheet';
        node.href = src;
    }

    if (node.addEventListener) {
        node.addEventListener('load', function () {
            typeof callback == "function" && callback();
        }, false);
    } else if (node.attachEvent) {
        node.attachEvent('onreadystatechange', function () {
            var target = window.event.srcElement;
            if (target.readyState == 'loaded') {
                typeof callback == "function" && callback();
            }
        });
    }
    head.appendChild(node);
}

/**
 * 高级版 Tab 切换
 * @param tabBar Tab 标签
 * @param tabCon Tab 容器
 * @param class_name 被选中标签class
 * @param tabEvent 触发 Tab 切换的事件
 * @param i 被激活索引
 * @param callback 切换回调函数 callback(index,$tabCon,$tabBar)
 * @param finished 初始化完成之后的回调函数 finished(index,$tabCon,$tabBar)
 */
jQuery.tpTab = function (tabBar, tabCon, class_name, tabEvent, i, callback, finished) {
    var $tabBar = $(tabBar), $tabCon = $(tabCon);

    function chg(index) {
        $tabBar.removeClass(class_name).eq(index).addClass(class_name);
        $tabCon.hide().eq(index).show();
    }

    // 初始化操作
    chg(i || 0);
    typeof finished === "function" && finished(i, $tabCon, $tabBar);

    $tabBar.bind(tabEvent, function () {
        var index = $tabBar.index(this);
        chg(index);
        typeof callback === "function" && callback(index, $tabCon, $tabBar);
    });
};

/**
 * 保存排序
 *
 * @param url 保存排序的提交地址
 * @param select 选择器
 */
function saveOrder(url, select) {
    var data = {};
    $(select || ".order-input").each(function (index, item) {
        data[$(item).attr('data-id')] = $(item).val();
    });
    if (data.length == 0) {
        layer.msg('没有可排序的对象');
        return;
    }
    ajax_req(url || THINK_CONTROLLER + '/saveOrder', {'sort': data});
}

/**
 * 浏览器打印，oper为大于的数并且有相应注释就打印该区域，否则打印整个网页
 * @param oper
 */
function printerPreview(oper) {
    if (typeof oper != "undefined") {
        bdhtml = window.document.body.innerHTML;// 获取当前页的html代码
        sprnstr = "<!--startprint" + oper + "-->";// 设置打印开始区域
        eprnstr = "<!--endprint" + oper + "-->";// 设置打印结束区域
        prnhtml = bdhtml.substring(bdhtml.indexOf(sprnstr) + sprnstr.length); // 从开始代码向后取html
        prnhtml = prnhtml.substring(0, prnhtml.indexOf(eprnstr));// 从结束代码向前取html
        window.document.body.innerHTML = prnhtml;
        window.print();
        window.document.body.innerHTML = bdhtml;
    } else {
        window.print();
    }
}

/**
 * 永久删除操作项
 * @param obj this
 * @param id 对象id
 * @param url 删除地址，一般为 {:url('delete_forever')}
 */
function del_forever(obj, id, url, fn) {
    _del(obj, id, url, '您确定要删除此项并且不能恢复？', fn);
}

/**
 * 假性删除操作项
 * @param obj this
 * @param id 对象id
 * @param url 删除地址，一般为 {:url('delete')}
 * @param fn 回调函数
 */
function del(obj, id, url, fn) {
    _del(obj, id, url, '您确定要把此条数据放入回收站？', fn);
}

/**
 * 从回收站恢复操作
 * @param obj this
 * @param id 对象id
 * @param url 恢复地址，一般为 {:url('recycle')}
 * @param fn 回调函数
 */
function recycle(obj, id, url, fn) {
    _recycle(obj, id, url, '您确定要从回收站还原此条数据吗？', fn);
}

/**
 * 批量永久删除操作项
 * @param url 批量删除地址，一般为 {:url('delete_forever')}
 * @param checkbox_group checkbox组的名称，默认 id[]
 */
function del_forever_all(url, checkbox_group) {
    _del_all(url, checkbox_group || 'id[]', '您确定要删除这些项目并且不能恢复？');
}

/**
 * 批量假性删除操作项
 * @param url 批量删除地址，一般为 {:url('delete')}
 * @param checkbox_group checkbox组的名称，默认 id[]
 */
function del_all(url, checkbox_group) {
    _del_all(url, checkbox_group || 'id[]', '您确定要把这些项目放入回收站？');
}

/**
 * 批量从回收站恢复操作项
 * @param url 批量恢复地址，一般为 {:url('recycle')}
 * @param checkbox_group checkbox组的名称，默认 id[]
 */
function recycle_all(url, checkbox_group) {
    _del_recycle_all(url, checkbox_group || 'id[]', "您确定要还原这些项目？", "已还原")
}

/**
 * 批量禁用操作项
 * @param url 批量禁用地址，一般为 {:url('forbid')}
 * @param checkbox_group checkbox组的名称，默认 id[]
 */
function forbid_all(url, checkbox_group) {
    _del_recycle_all(url, checkbox_group || 'id[]', "您确定要禁用这些项目？", "已禁用！")
}

/**
 * 批量恢复操作项
 * @param url 批量恢复地址，一般为 {:url('resume')}
 * @param checkbox_group checkbox组的名称，默认 id[]
 */
function resume_all(url, checkbox_group) {
    _del_recycle_all(url, checkbox_group || 'id[]', "您确定要恢复这些项目？", "已恢复！")
}

/**
 * 清空回收站
 * @param url 清空回收站地址，一般为 {:url('clear')}
 */
function clear_recyclebin(url) {
    layer.confirm("您确定要清空回收站并且不可恢复？", {
        btn: ['确定', '取消'],
        title: '提示',
        icon: 3
    }, function () {
        $.post(url, '', function (data) {
            if (data.code == 0) {
                layer.msg("已清空", {icon: 1, time: 1000});
                window.location.reload();
            } else {
                layer.alert(data.msg);
            }
        }, 'json')
    }, function (index) {
        layer.close(index);
    });
}

/**
 * 登录超时回调
 * @param url
 */
function login(url) {
    layer.alert('登录超时，请重新登录', function (index) {
        layer_open('登录', url);
    });
}

/**
 * 表格无限宽横向溢出
 * @param selector
 * @param width 不赋值默认为th的width值和
 * @param force 强制将表格宽度设置成实际的宽度
 */
function table_fixed(selector, width, force) {
    var attr = typeof force == 'undefined' ? 'min-width' : 'width';
    $(selector).each(function () {
        $this = $(this);
        //未设置宽度自动获取width属性的宽
        if (typeof width === "undefined") {
            width = 0;
            $this.find("tr:first th").each(function () {
                width += parseInt($(this).attr("width") || $(this).innerWidth());
            })
        }
        $this.css(attr, width + "px");
        $this.css("table-layout", "fixed");
        $this.wrap('<div style="width:100%;overflow:auto"></div>');
    });
}

/**
 * 生成随机字符串
 * @param prefix
 * @returns {string}
 */
function get_random(prefix) {
    prefix = prefix || "";
    return prefix + (new Date()).getTime().toString(36) + "_" + Math.random().toString(36).substr(2);
};

/**
 * 检查浏览器是否支持某属性
 * @param attrName
 * @param attrValue
 * @returns {boolean}
 */
function attr_support(attrName, attrValue) {
    try {
        var element = document.createElement('div');
        if (attrName in element.style) {
            element.style[attrName] = attrValue;
            return element.style[attrName] === attrValue;
        } else {
            return false;
        }
    } catch (e) {
        return false;
    }
}

function _del_recycle(obj, id, url, msg, returnMsg, fn) {
    layer.confirm(msg, {
        btn: ['确定', '取消'],
        title: '提示',
        icon: 3
    }, function () {
        $.post(url, {id: id}, function (data) {
            if (data.code == 0) {
                layer.msg(returnMsg, {icon: 1, time: 1000});
                $(obj).parents("tr").fadeOut();
            } else {
                layer.alert(data.msg);
            }
            fn && fn(data);
        }, 'json')
    }, function (index) {
        layer.close(index);
    });
}

function _del(obj, id, url, msg, fn) {
    _del_recycle(obj, id, url, msg, "已删除！", fn)
}

function _recycle(obj, id, url, msg, fn) {
    _del_recycle(obj, id, url, msg, "已还原！", fn)
}

function _del_recycle_all(url, checkbox_group, msg, return_msg) {
    layer.confirm(msg, {
        btn: ['确定', '取消'],
        title: '提示',
        icon: 3
    }, function () {
        id = [];
        $(":checked[name='" + checkbox_group + "']").each(function () {
            id.push($(this).val())
        });
        $.post(url, {id: id.join(',')}, function (data) {
            if (data.code == 0) {
                parent.layer.msg(return_msg, {icon: 1, time: 1000});
                window.location.reload();
            } else {
                layer.alert(data.msg);
            }
        }, 'json')
    }, function (index) {
        layer.close(index);
    });
}

function _del_all(url, checkbox_group, msg) {
    _del_recycle_all(url, checkbox_group, msg, "已删除！")
}
