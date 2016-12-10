/* -----------H-ui前端框架-------------
 * H-ui.admin.js v2.4
 * http://www.h-ui.net/
 * Created & Modified by guojunhui
 * Date modified 15:42 2016.03.14
 *
 * Copyright 2013-2016 北京颖杰联创科技有限公司 All rights reserved.
 * Licensed under MIT license.
 * http://opensource.org/licenses/MIT
 *
 */
var num = 0, oUl = $("#min_title_list"), hide_nav = $("#Hui-tabNav");

/*获取顶部选项卡总长度*/
function tabNavallwidth() {
    var taballwidth = 0,
        $tabNav = hide_nav.find(".acrossTab"),
        $tabNavWp = hide_nav.find(".Hui-tabNav-wp"),
        $tabNavitem = hide_nav.find(".acrossTab li"),
        $tabNavmore = hide_nav.find(".Hui-tabNav-more");
    if (!$tabNav[0]) {
        return
    }
    $tabNavitem.each(function (index, element) {
        taballwidth += Number(parseFloat($(this).width() + 60))
    });
    $tabNav.width(taballwidth + 25);
    var w = $tabNavWp.width();
    if (taballwidth + 25 > w) {
        $tabNavmore.show()
    }
    else {
        $tabNavmore.hide();
        $tabNav.css({left: 0})
    }
}

/*左侧菜单响应式*/
function Huiasidedisplay() {
    if ($(window).width() >= 768) {
        $(".Hui-aside").show()
    }
}
function getskincookie() {
    var v = getCookie("Huiskin");
    var hrefStr = $("#skin").attr("href");
    if (v == null || v == "") {
        v = "default";
    }
    if (hrefStr != undefined) {
        var hrefRes = hrefStr.substring(0, hrefStr.lastIndexOf('skin/')) + 'skin/' + v + '/skin.css';
        $("#skin").attr("href", hrefRes);
    }
}
function Hui_admin_tab(obj) {
    var bStop = false;
    var bStopIndex = 0;
    var _href = $(obj).attr('_href');
    var _titleName = $(obj).attr("data-title");
    var topWindow = $(window.parent.document);
    var show_navLi = topWindow.find("#min_title_list li");
    show_navLi.each(function () {
        if ($(this).find('span').attr("data-href") == _href) {
            bStop = true;
            bStopIndex = show_navLi.index($(this));
            return false;
        }
    });
    if (!bStop) {
        creatIframe(_href, _titleName, $(obj).attr('data-nav'));
        min_titleList();
    }
    else {
        show_navLi.removeClass("active").eq(bStopIndex).addClass("active");
        var iframe_box = topWindow.find("#iframe_box");
        iframe_box.find(".show_iframe").hide().eq(bStopIndex).show().find("iframe").attr("src", _href);
    }
}
function min_titleList() {
    var topWindow = $(window.parent.document);
    var show_nav = topWindow.find("#min_title_list");
    var aLi = show_nav.find("li");
};
function creatIframe(href, titleName, nav) {
    var topWindow = $(window.parent.document);
    var show_nav = topWindow.find('#min_title_list');
    show_nav.find('li').removeClass("active");
    var iframe_box = topWindow.find('#iframe_box');
    show_nav.append('<li class="active"><span data-href="' + href + '">' + titleName + '</span><i></i><em></em></li>');
    var taballwidth = 0,
        $tabNav = topWindow.find(".acrossTab"),
        $tabNavWp = topWindow.find(".Hui-tabNav-wp"),
        $tabNavitem = topWindow.find(".acrossTab li"),
        $tabNavmore = topWindow.find(".Hui-tabNav-more");
    if (!$tabNav[0]) {
        return
    }
    $tabNavitem.each(function (index, element) {
        taballwidth += Number(parseFloat($(this).width() + 60))
    });
    $tabNav.width(taballwidth + 25);
    var w = $tabNavWp.width();
    if (taballwidth + 25 > w) {
        $tabNavmore.show()
    }
    else {
        $tabNavmore.hide();
        $tabNav.css({left: 0})
    }
    var iframeBox = iframe_box.find('.show_iframe');
    iframeBox.hide();
    var ts = get_random();
    iframe_box.append('<div class="show_iframe"><div class="loading"></div><iframe frameborder="0" src=' + href + ' id=' + ts + ' name="' + ts + '"></iframe></div>');
    var showBox = iframe_box.find('.show_iframe:visible');
    showBox.find('iframe').load(function () {
        showBox.find('.loading').hide();

        /* 动态添加面包屑导航 */
        $('#nav-title', window.frames.length == 0 ?
            window.parent.window.frames[ts].document :
            window.frames[ts].document
        ).html('<i class="Hui-iconfont"></i> ' + nav);
    });


}
function removeIframe() {
    var topWindow = $(window.parent.document);
    var iframe = topWindow.find('#iframe_box .show_iframe');
    var tab = topWindow.find(".acrossTab li");
    var showTab = topWindow.find(".acrossTab li.active");
    var showBox = topWindow.find('.show_iframe:visible');
    var i = showTab.index();
    tab.eq(i - 1).addClass("active");
    iframe.eq(i - 1).show();
    tab.eq(i).remove();
    iframe.eq(i).remove();
}

/*关闭弹出框口*/
function layer_close() {
    var index = parent.layer.getFrameIndex(window.name);
    parent.layer.close(index);
}

