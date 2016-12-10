{extend name="[MODULE]template/base" /}
{block name="content"}
<div class="page-container">
    [FORM]
    <div class="cl pd-5 bg-1 bk-gray">
        <span class="l">
            [MENU]
        </span>
        <span class="r pt-5 pr-5">
            共有数据 ：<strong>{$count ?? '0'}</strong> 条
        </span>
    </div>
    <table class="table table-border table-bordered table-hover table-bg mt-20">
        <thead>
        <tr class="text-c">
            [TH]
            <th width="70">操作</th>
        </tr>
        </thead>
        <tbody>
        {volist name="list" id="vo"}
        <tr class="text-c">
            [TD]
            <td class="f-14">
[TD_MENU]
            </td>
        </tr>
        {/volist}
        </tbody>
    </table>
    <div class="page-bootstrap">{$page ?? ''}</div>
</div>
{/block}
[SCRIPT]
