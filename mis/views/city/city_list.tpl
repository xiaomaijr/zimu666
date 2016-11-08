{extends file="_base.tpl"}
{block name="content"}
    <h2 class="top10">城市列表</h2>
    <div class="top10 pad10 ui-widget-content">
        <form id="query_form" name="search-form" method="post" action="/city/list">
            <div class="item"><label>ID：<input type="text" name="query[id]" value="{$filter.id|default:''}" class="inpt"/></label></div>
            <div class="item">
                <label>城市名称：<input type="text" name="query[name]" value="{$filter.name|default:''}" class="inpt"/></label>
            </div>
            <input type="hidden" id="orderbycolumn" name="order" value="{$filter.orderby}"/>
            <input type="hidden" id="sortway" name="sortway" value="{$filter.sortway}"/>
            <p><button type="submit">查询</button></p>
        </form>
            <a href="/city/add"><button value="添加销售顾问" class="fr" style="margin-top:-29px">添加城市</button></a>
    </div>
    <table class="tb-list top10" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th class="sort" sort-id="id" width="80px">ID</th>
                <th class="sort" sort-id="name">名称</th>
                <th class="sort" sort-id="status">上线状态</th>
                <th class="sort" sort-id="create_time" width="150px">创建时间</th>
                <th class="sort" sort-id="update_time" width="150px">更新时间</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
        {foreach item=item from=$adminList}
            <tr>
                <td class="cnt">{$item.id|default:''}</td>
                <td class="cnt">{$item.name}</td>
                <td class="cnt">{$item.status}</td>
                <td class="cnt">{$item.create_time|default:''}</td>
                <td class="cnt">{$item.update_time|default:''}</td>
                <td class="cnt">
                    <a href="/city/view?id={$item.id}">查看</a>
                    |<a href="/city/edit?id={$item.id}">修改</a>
                    {if $item.is_del eq 0}
                    |<a href="#" class="del" onclick="ListDeteleMethod('/city/del?id='+{$item.id})" data-id="{$item.id}">删除</a>
                    {else}
                    |<a href="#" class="recovery" data-id="{$item.id}">恢复</a>
                    {/if}
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
    <div class="page">
        {$arrPager.pagelink} 共<font color=red>{$arrPager.count}</font>个结果
    </div>
{/block}