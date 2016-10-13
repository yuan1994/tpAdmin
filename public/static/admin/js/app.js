/**
 * Created by tianpian <tianpian0805@gamil.com> on 16-10-2.
 */

$(function () {
    //多级菜单
    $.Huifold(".menu_dropdown .sub-menu-title",".menu_dropdown .sub-menu-list","fast",3,"click");
});

/**
 * 弹出层
 * @param title 层标题
 * @param url 层链接(opt.type=2|默认)或者HTML内容(opt.type=1)
 * @param opt 选项 {w:WIDTH('800px|80%'),h:HEIGHT('600px|80%'),type:1|2,fn:CALLBACK(回调函数),confirm:BOOL(关闭弹层警告)}
 */
function layer_open(title,url,opt){
    if (typeof opt === "undefined") opt = {};
    return layer.open({
        type: opt.type || 2,
        area: [opt.w || "80%", opt.h || "80%"],
        fix: false, //不固定
        maxmin: true,
        shade:0.4,
        title: title,
        content: url,
        success: function (layero,index) {
            if (typeof opt.confirm !== "undefined" && opt.confirm === true){
                layero.find(".layui-layer-close").off("click").on("click",function () {
                    layer.alert('您确定要关闭当前窗口吗？', {
                        btn: ['确定','取消'] //按钮
                    }, function(i){
                        layer.close(i);
                        layer.close(index);
                    });
                });
            }
            if (typeof opt.fn === "function"){
                opt.fn();
            }
        }
    });
};

/**
 * 全屏打开窗口，参数见layer_open
 */
function full_page(title,url,opt) {
  return layer_open(title,url,$.extend({w:"100%",h:"100%"},opt))
};

/**
 * iframe内打开新窗口
 * @param title
 * @param url
 */
function open_window(title,url){
    //解决在非iframe页里打开不了页面的问题
    if (window.parent.frames.length == 0){
        window.open(url);
        return false;
    }
    var bStop=false;
    var bStopIndex=0;
    var topWindow=$(window.top.parent.document);
    var show_navLi=topWindow.find("#min_title_list li");
    var iframe_box=topWindow.find('#iframe_box');
    show_navLi.each(function() {
        if($(this).find('span').attr("data-href")==url){
            bStop=true;
            bStopIndex=show_navLi.index($(this));
            return false;
        }
    });
    if(!bStop){
        var show_nav=topWindow.find('#min_title_list');
        show_nav.find('li').removeClass("active");
        show_nav.append('<li class="active"><span data-href="'+url+'">'+title+'</span><i></i><em></em></li>');
        var taballwidth=0,
            $tabNav = $(".acrossTab",window.top.parent.document),
            $tabNavitem = $(".acrossTab li",window.top.parent.document);
        $tabNavitem.each(function(index, element) {
            taballwidth+=Number(parseFloat($(this).width()+60))
        });
        $tabNav.width(taballwidth+25);
        var iframeBox=iframe_box.find('.show_iframe');
        iframeBox.hide();
        iframe_box.append('<div class="show_iframe"><div class="loading"></div><iframe frameborder="0" src='+url+'></iframe></div>');
        var showBox=iframe_box.find('.show_iframe:visible');
        showBox.find('iframe').attr("src",url).load(function(){
            showBox.find('.loading').hide();
        });
    }
    else{
        show_navLi.removeClass("active").eq(bStopIndex).addClass("active");
        iframe_box.find(".show_iframe").hide().eq(bStopIndex).show().find("iframe").attr("src",url);
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
function ajax_req(url,data,callback,param,shade){
    if(shade === true) var loading = layer.load(2);
    $.post(url,data,function(ret){
        shade === true || layer.close(loading);
        ajax_progress(ret,callback,param);
    },'json')
}

/**
 *  * ajax处理，对应服务端ajax_return_adv方法返回的json数据处理
 * @param data ajax返回数据
 * @param callback 成功回调函数
 * @param param 回调参数
 */
function ajax_progress(data,callback,param){
    if(data.status == 'y'){
        var index = parent.layer.getFrameIndex(window.name);
        if(data.close){
            parent.layer.close(index);
        }
        if(data.redirect == 'current'){ //是否当前页重定向
            if(!data.url){ //刷新
                window.location.reload();
            } else { //重定向到url
                window.location.href = data.url;
            }
        } else if(data.redirect == 'parent'){ //是否父层重定向
            if(!data.url){ //刷新
                window.parent.location.reload();
            } else { //重定向到url
                window.parent.location.href = data.url;
            }
            parent.layer.close(index); //关闭当前层
        }
        if(data.alert){ //父层弹出信息
            parent.layer.alert(data.alert);
            parent.layer.close(index);
        }
        if(!data.close && !data.redirect && !data.alert){
            parent.layer.msg(data.info);
            parent.layer.close(index);
        }
        if (typeof callback == "function"){
            callback.apply(this,param);
        }
    } else {
        layer.alert(data.info,{title:"错误信息",icon:2});
    }
}

/**
 * 恢复禁用等状态改变回调函数
 * @param obj
 * @param type
 */
function change_status(obj,type) {
    //配置数据，TYPE:['下一状态文字描述','当前状态class颜色','下一状态class颜色','下一状态方法名','状态标签选择器','下一状态标签icon','下一状态标签title']
    var  data = {
        'resume':['禁用','success','warning','forbid','.status','&#xe615;','正常'],
        'forbid':['恢复','warning','success','resume','.status','&#xe631;','禁用'],
    };
    var $this = $(obj);
    $this.html(data[type][0]).attr("title","点击"+data[type][0]).removeClass("label-"+data[type][1]).addClass("label-"+data[type][2]).attr("onclick",$this.attr("onclick").replace(new RegExp(type,'g'),data[type][3]));
    $this.parents("tr").find(data[type][4]).html(data[type][5]).removeClass("c-"+data[type][2]).addClass("c-"+data[type][1]).attr("title",data[type][6]);
}

/**
 * 永久删除操作项
 * @param obj this
 * @param id 对象id
 * @param url 删除地址，一般为 {:url('delete_forever')}
 */
function del_forever(obj,id,url){
    _del(obj,id,url,'您确定要删除此项并且不能恢复？');
}

/**
 * 假性删除操作项
 * @param obj this
 * @param id 对象id
 * @param url 删除地址，一般为 {:url('delete')}
 */
function del(obj,id,url){
    _del(obj,id,url,'您确定要把此条数据放入回收站？');
}

/**
 * 从回收站恢复操作
 * @param obj this
 * @param id 对象id
 * @param url 恢复地址，一般为 {:url('recycle')}
 */
function recycle(obj,id,url){
    _recycle(obj,id,url,'您确定要从回收站还原此条数据吗？');
}

/**
 * 批量永久删除操作项
 * @param url 批量删除地址，一般为 {:url('delete_forever')}
 * @param checkbox_group checkbox组的名称，默认 id[]
 */
function del_forever_all(url, checkbox_group){
    _del_all(url,checkbox_group||'id[]','您确定要删除这些项目并且不能恢复？');
}

/**
 * 批量假性删除操作项
 * @param url 批量删除地址，一般为 {:url('delete')}
 * @param checkbox_group checkbox组的名称，默认 id[]
 */
function del_all(url, checkbox_group){
    _del_all(url,checkbox_group||'id[]','您确定要把这些项目放入回收站？');
}

/**
 * 批量从回收站恢复操作项
 * @param url 批量恢复地址，一般为 {:url('recycle')}
 * @param checkbox_group checkbox组的名称，默认 id[]
 */
function recycle_all(url, checkbox_group){
    _del_recycle_all(url,checkbox_group||'id[]',"您确定要还原这些项目？","已还原")
}

/**
 * 批量禁用操作项
 * @param url 批量禁用地址，一般为 {:url('forbid')}
 * @param checkbox_group checkbox组的名称，默认 id[]
 */
function forbid_all(url, checkbox_group){
    _del_recycle_all(url,checkbox_group||'id[]',"您确定要禁用这些项目？","已禁用！")
}

/**
 * 批量恢复操作项
 * @param url 批量恢复地址，一般为 {:url('resume')}
 * @param checkbox_group checkbox组的名称，默认 id[]
 */
function resume_all(url, checkbox_group){
    _del_recycle_all(url,checkbox_group||'id[]',"您确定要恢复这些项目？","已恢复！")
}

/**
 * 清空回收站
 * @param url 清空回收站地址，一般为 {:url('clear')}
 */
function clear_recyclebin(url){
    layer.confirm("您确定要清空回收站并且不可恢复？", {
        btn: ['确定','取消'],
        title:'提示',
        icon:3
    }, function(){
        $.post(url,'',function(data){
            if(data.status == 'y'){
                layer.msg("已清空",{icon:1,time:1000});
                window.location.reload();
            } else {
                layer.alert(data.info);
            }
        },'json')
    }, function(index){
        layer.close(index);
    });
}

/**
 * 登录超时回调
 * @param url
 */
function login(url){
    layer.alert('登录超时，请重新登录',function(index){
        layer_open('登录',url);
    });
}

/**
 * 表格无限宽横向溢出
 * @param selector
 * @param width 不赋值默认为th的width值和
 */
function table_fixed(selector,width) {
    $obj = $(selector);
    //未设置宽度自动获取width属性的宽
    if (typeof width === "undefined"){
        width = 0;
        $obj.find("tr:first th").each(function () {
            width += parseInt($(this).attr("width") || $(this).innerWidth());
        })
    }
    $obj.css({"width":width+"px","table-layout":"fixed"});
    $obj.wrap('<div style="width:100%;overflow:auto"></div>');
}

//生成随机字符串
var get_random = function (prefix) {
    prefix = prefix || "";
    return prefix + Date.now().toString(36) + "_" + Math.random().toString(36).substr(2);
};

function _del_recycle(obj,id,url,msg,returnMsg){
    layer.confirm(msg, {
        btn: ['确定','取消'],
        title:'提示',
        icon:3
    }, function(){
        $.post(url,{id:id},function(data){
            if(data.status == 'y'){
                layer.msg(returnMsg,{icon:1,time:1000});
                $(obj).parents("tr").fadeOut();
            } else {
                layer.alert(data.info);
            }
        },'json')
    }, function(index){
        layer.close(index);
    });
}

function _del(obj,id,url,msg){
    _del_recycle(obj,id,url,msg,"已删除！")
}

function _recycle(obj,id,url,msg){
    _del_recycle(obj,id,url,msg,"已还原！")
}

function _del_recycle_all(url,checkbox_group,msg,returnMsg){
    layer.confirm(msg, {
        btn: ['确定','取消'],
        title:'提示',
        icon:3
    }, function(){
        id = [];
        $(":checked[name='"+checkbox_group+"']").each(function(){
            id.push($(this).val())
        });
        $.post(url,{id:id.join(',')},function(data){
            if(data.status == 'y'){
                parent.layer.msg(returnMsg,{icon:1,time:1000});
                window.location.reload();
            } else {
                layer.alert(data.info);
            }
        },'json')
    }, function(index){
        layer.close(index);
    });
}

function _del_all(url,checkbox_group,msg){
    _del_recycle_all(url,checkbox_group,msg,"已删除！")
}