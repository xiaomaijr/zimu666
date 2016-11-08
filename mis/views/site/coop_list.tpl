{extends file="_base.tpl"}

{block name="title"}债权录入{/block}

{block name="content"}

<table class="tb-list top10" cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th class="sort" width="60px">机构ID</th>
            <th class="sort">机构名称</th>
            <th>联系电话</th>
            <th class="sort" width="80px">法人姓名</th>
            <th class="sort">申请时间</th>
            <th class="sort">状态</th>
            <th with="80px">操作</th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$arrCoopList item=item}
        <tr>
            <td>{$item.coop_id}</td>
            <td>{$item.org_name}</td>
            <td>{$item.org_phone}</td>
            <td>{$item.chief_name}</td>
            <td>{$item.create_time|date_format:"%Y-%m-%d %T"}</td>
            <td>{$item.check_status}

            </td>
            <td>
                <a href="/coop/view?coop_id={$item.coop_id}">查看</a>
                {*<a href="/coop/edit?coop_id={$item.coop_id}">编辑</a>*}
                <a href="/coop/apply?coop_id={$item.coop_id}">提交审核</a>
            </td>
        </tr>
        {/foreach}
    </tbody>
</table>
<div class="page">
    {pager count=$arrPager.count pagesize=$arrPager.pagesize page=$arrPager.page pagelink=$arrPager.pagelink list=5} 共<font color=red>{$arrPager.count}</font>个结果
</div>
{/block}

