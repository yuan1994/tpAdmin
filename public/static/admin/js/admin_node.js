/**************** 节点树配置 *******************/
var zTree, treeId = 'tree', rMenu = $("#rMenu");
var setting = {
    edit: {
        drag: {
            autoExpandTrigger: true,
            prev: true,
            inner: true,
            next: true
        },
        enable: true,
        editNameSelectAll: true,
        showRemoveBtn: true,
        removeTitle: '删除节点',
        showRenameBtn: true,
        renameTitle: '编辑节点'
    },
    data: {
        simpleData: {
            enable: true,
            idKey: "id",
            pIdKey: "pId",
            rootPId: '1'
        }
    },
    callback: {
        onDrop: onDrop,
        onRightClick: OnRightClick,
        beforeEditName: onEditName,
        beforeRemove: onRemove
    },
    view: {
        nameIsHTML: true,
        showTitle: false,
        selectedMulti: false
    },
    check: {
        enable: true,
        chkboxType: {"Y": "", "N": ""}
    }
};

// 默认选中模块
var currentModuleId = getHash('mid', 1);
initGroup(currentModuleId);

$(function () {
    // 设置高度
    var groupHeight = $('.tp-page-aside').height() - $('.tp-panel-module').outerHeight() - 150;
    $('.tp-panel-group .panel-body').height(groupHeight);
});

/**
 * 初始化或分组
 * @param moduleId
 */
function initGroup(moduleId) {
    $("[data-module-id='" + moduleId + "']").addClass('focus').siblings().removeClass('focus');
    $.post(THINK_CURRENT, {type: 'group', module_id: moduleId}, function (ret) {
        if (ret.code) {
            layer.alert(ret.msg, {icon: 2});
        } else {
            var list = ret.data.list, countList = ret.data.count,
                listHTML = typeof countList[0] == 'undefined' ? ''
                    : '<li data-group-id="0"><a href="javascript:;" class="list-select"><i class="Hui-iconfont"></i> 未分组'
                    + ' (' + countList[0] + ')'
                    + '</a></li>';
            for (var i = 0; i < list.length; i++) {
                listHTML += '<li data-group-id="' + list[i].id + '"><a href="javascript:;" class="list-select"><i class="Hui-iconfont">' + list[i].icon + '</i> ' + list[i].name + ' (' + countList[list[i].id] + ')' + '</a></li>';
            }
            $('#group-list').html(listHTML);
            initNode(moduleId, getHash('gid', list[0].id));
        }
    }, 'json');
}

/**
 * 初始化节点
 * @param moduleId
 * @param groupId
 */
function initNode(moduleId, groupId) {
    $("[data-group-id='" + groupId + "']").addClass('focus').siblings().removeClass('focus');
    $.post(THINK_CURRENT, {type: 'node', module_id: moduleId, group_id: groupId}, function (ret) {
        if (ret.code) {
            layer.alert(ret.msg, {icon: 2});
        } else {
            initTree(ret.data.list);
        }
    }, 'json');
}

/**
 * 获取链接中的hash值
 * @param name
 * @param defaultValue
 * @returns {{}}
 */
function getHash(name, defaultValue) {
    var hash = location.hash,
        tmpArr = hash.split(/[#&=]/),
        tmpObj = {};
    for (var i = 1; i < tmpArr.length; i += 2) {
        tmpObj[tmpArr[i]] = tmpArr[i + 1];
    }

    return undefined == name ? tmpObj : tmpObj[name] || defaultValue;
}

/**
 * 绑定模块点击事件
 */
$('#module-list').on('click', '.list-select', function () {
    currentModuleId = $(this).parent('li').attr('data-module-id');
    location.href = '#mid=' + currentModuleId;
    initGroup(currentModuleId);
});

/**
 * 绑定分组点击事件
 */
$('#group-list').on('click', '.list-select', function () {
    var groupId = $(this).parent('li').attr('data-group-id');
    location.href = '#mid=' + currentModuleId + '&gid=' + groupId;
    initNode(currentModuleId, groupId);
});

/**
 * 模块右键菜单事件
 */
$('#module-list li').each(function () {
    var self = this;
    $(self).contextPopup({
        title: $(self).find('a').text(),
        items: [
            {
                label: '添加子节点',
                action: function () {
                    layer_open('添加', THINK_CONTROLLER + '/add?pid=' + $(self).attr('data-module-id'));
                }
            },
            {
                label: '编辑模块',
                action: function () {
                    layer_open('编辑', THINK_CONTROLLER + '/edit?id=' + $(self).attr('data-module-id'));
                }
            },
            {
                label: '删除模块',
                action: function () {
                    del('', $(self).attr('data-module-id'), THINK_CONTROLLER + '/delete', function (data) {
                        if (!data.code) {
                            $(self).remove();
                        }
                    });
                }
            }
        ]

    });

});


/**
 * 头部菜单批量操作方法
 * @param url
 * @param desc
 */
function treeOpAll(url, desc) {
    var id = getCheckedId();
    if (id.length == 0) {
        layer.alert('请选择要操作的对象');
        return;
    }
    layer.confirm('你确定要' + desc + '选中的这些节点？', {}, function () {
        $.post(url, {'id': id.join(',')}, function (ret) {
            ajax_progress(ret, function () {
                location.reload();
            });
        }, 'json');
    })
}


/*********************** 节点树操作 *******************/

/**
 * 添加节点、批量导入节点
 */
$(document).on('click', '.J_add', function () {
    var id = $(this).attr('data-id')
        || (zTree.getSelectedNodes()[0] ? zTree.getSelectedNodes()[0].id : undefined)
        || (zTree.getCheckedNodes()[0] ? zTree.getCheckedNodes()[0].id : undefined)
        || '0';
    layer_open('添加', THINK_CONTROLLER + '/add?pid=' + id);
    return false;
}).on('click', '.J_load', function () {
    var id = $(this).attr('data-id')
        || (zTree.getSelectedNodes()[0] ? zTree.getSelectedNodes()[0].id : undefined)
        || (zTree.getCheckedNodes()[0] ? zTree.getCheckedNodes()[0].id : undefined)
        || '0';
    layer_open('批量导入', THINK_CONTROLLER + '/load?pid=' + id);
});
// 编辑节点
function onEditName(treeId, treeNode) {
    layer_open('编辑', THINK_CONTROLLER + '/edit?id=' + treeNode.id);
    return false;
}
// 删除节点
function onRemove(treeId, treeNode) {
    del('', treeNode.id, THINK_CONTROLLER + '/delete', function (data) {
        if (!data.code) {
            zTree.removeNode(treeNode);
        }
    });
    return false;
}
// 拖拽排序
function onDrop(event, treeId, treeNodes, targetNode, moveType, isCopy) {
    var data = {'id': treeNodes[0].id, 'pid': treeNodes[0].pId, 'level': parseInt(treeNodes[0].level) + 1};
    var prev = treeNodes[0].getPreNode();
    if (typeof prev == 'undefined' || typeof prev.sort == 'undefined') {
        data['sort'] = 0;
    } else {
        data['sort'] = parseInt(prev.sort) + 1;
    }
    $.post(THINK_CONTROLLER + '/sort', data, function (ret) {
        if (ret.code) {
            layer.alert(ret.msg);
        }
    }, 'json');
}
// 获取当前选中的节点
function getCheckedId() {
    var id = [];
    var checked = zTree.getCheckedNodes()[0] ? zTree.getCheckedNodes() : zTree.getSelectedNodes();
    for (var i in checked) {
        id.push(checked[i].id);
    }
    return id;
}

// 选中/取消选择当前节点
function checkTreeNode(checked) {
    var nodes = zTree.getSelectedNodes();
    if (nodes && nodes.length > 0) {
        zTree.checkNode(nodes[0], checked, true);
    }
    hideRMenu();
}
// 右键菜单
function OnRightClick(event, treeId, treeNode) {
    if (!treeNode && event.target.tagName.toLowerCase() != "button" && $(event.target).parents("a").length == 0) {
        zTree.cancelSelectedNode();
        showRMenu("root", event.clientX, event.clientY);
    } else if (treeNode && !treeNode.noR) {
        zTree.selectNode(treeNode);
        showRMenu("node", event.clientX, event.clientY);
    }
}
// 显示右键菜单
function showRMenu(type, x, y) {
    $("#rMenu ul").show();
    rMenu.css({"top": y + "px", "left": x + "px", "visibility": "visible"});
    $("body").bind("mousedown", onBodyMouseDown);
}
// 隐藏右键菜单
function hideRMenu() {
    if (rMenu) rMenu.css({"visibility": "hidden"});
    $("body").unbind("mousedown", onBodyMouseDown);
}
function onBodyMouseDown(event) {
    if (!(event.target.id == "rMenu" || $(event.target).parents("#rMenu").length > 0)) {
        rMenu.css({"visibility": "hidden"});
    }
}

/**
 * 初始化节点树
 * @param node
 */
function initTree(node) {
    var treeObj = $.fn.zTree.init($("#" + treeId), setting, node);
    zTree = $.fn.zTree.getZTreeObj(treeId);
    treeObj.expandAll(true);
}
