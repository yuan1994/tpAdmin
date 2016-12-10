{extend name="[MODULE]template/base" /}
{block name="content"}
<div class="page-container">
    <form class="form form-horizontal" id="form" method="post" action="{:\\think\\Request::instance()->baseUrl()}">
        <input type="hidden" name="id" value="{$vo.id ?? ''}">
[ROWS]
        <div class="row cl">
            <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
                <button type="submit" class="btn btn-primary radius">&nbsp;&nbsp;提交&nbsp;&nbsp;</button>
                <button type="button" class="btn btn-default radius ml-20" onClick="layer_close();">&nbsp;&nbsp;取消&nbsp;&nbsp;</button>
            </div>
        </div>
    </form>
</div>
{/block}
{block name="script"}
<script type="text/javascript" src="__LIB__/Validform/5.3.2/Validform.min.js"></script>[SCRIPT]
<script>
    $(function () {
[SET_VALUE]

        $('.skin-minimal input').iCheck({
            checkboxClass: 'icheckbox-blue',
            radioClass: 'iradio-blue',
            increaseArea: '20%'
        });

        $("#form").Validform({
            tiptype: 2,
            ajaxPost: true,
            showAllError: true,
            callback: function (ret){
                ajax_progress(ret);
            }
        });
    })
</script>
{/block}
