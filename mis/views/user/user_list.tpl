{extends file="_base.tpl"}
{block name="content"}
    <style>
        .ui-widget-content .item {
            margin:2px 10px 2px 0;
            float:left;
        }
    </style>
    <h2 class="top10">用户管理</h2>
    <div class="top10 pad10 ui-widget-content">
        <form id="query_form" name="search-form" method="post" action="list">
            <div class="item"><label>ID：<input type="text" name="query[id]" value="{$filter.id|default:''}" class="inpt"/></label></div>
            <div class="item">
                <label>手机号码：<input type="text" name="query[mobile]" value="{$filter.mobile|default:''}" class="inpt"/></label>
            </div>
            <input type="hidden" id="orderbycolumn" name="order" value="{$filter.orderby}"/>
            <input type="hidden" id="sortway" name="sortway" value="{$filter.sortway}"/>
            <p><button type="submit">查询</button></p>
        </form>
    </div>
    <table class="tb-list top10" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th class="sort" width="80px" sort-id="id">ID</th>
                <th class="sort" width="80px" sort-id="user_id">用户ID</th>
                <th>姓名</th>
                <th>电话</th>
                <th>性别</th>
                <th>年龄</th>
                <th>有无驾照</th>
                <th>用户来源</th>
                <th>账户余额(元)</th>
                <th>礼金(元)</th>
                <th>冻结金额(元)</th>
                <th>创建时间</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
        {foreach item=item from=$arrList}
            <tr>
                <td class="cnt">{$item.id}</td>
                <td class="cnt">{$item.user_id}</td>
                <td class="cnt">{$item.name|default:''}</td>
                <td class="cnt">{$item.mobile|default:''}</td>
                <td class="cnt">{$item.sex|default:''|replace:1:'男'|replace:2:'女'}</td>
                <td class="cnt">{$item.age|default:''}</td>
                <td class="cnt">{$item.driver_license|default:0|replace:0:'无'|replace:1:'有'|replace:2:'有,并满一年'}</td>
                <td class="cnt">{$item.source|default:0|replace:0:'其他'|replace:1:'APP'}</td>
                <td class="cnt">{round($item.remainder/100,2)|default:0|number_format}</td>
                <td class="cnt">{round($item.not_paid/100,2)|default:0|number_format}</td>
                <td class="cnt">{round($item.locked/100,2)|default:0|number_format}</td>
                <td class="cnt">{$item.create_time|date_format:"%Y-%m-%d %T"}</td>
                <td class="cnt">
                    {*{if in_array('user/view', $smarty.session.money_user_rights)}*}
                        {*<a href="/user/view?id={$item.id}">查看订单</a> |*}
                    {*{/if}*}
                    {*{if in_array('user/edit', $smarty.session.money_user_rights)}*}
                        {*<a href="/user/edit?id={$item.id}">修改</a> |*}
                    {*{/if}*}
                    {if isset($item.is_del)&&$item.is_del eq 0}
                        <a href="#" class="del" data-id="{$item.id}">删除</a>
                    {else}
                        <a href="#" class="recovery" data-id="{$item.id}">恢复</a>
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
{block name="js-common"}
<script>
$(document).ready(function(){
    
    $(".del").each(function(){
        var self = this;
        $(this).click(function(){
            if(confirm('你确定要删除该用户吗？')){
                $.ajax({
                    type: "get",
                    url: '/user/delete',
                    data: 'id='+$(this).attr('data-id'),
                    success: function(res){
                        result = $.parseJSON(res);
                        if(result.code == 0){
                            alert('删除成功');
                            location.reload();
                        }else{
                            alert(result.message);
                            return false;
                        }
                    }
                });
            }
        });
    });

    $(".recovery").each(function(){
        var self = this;
        $(this).click(function(){
            if(confirm('你确定要恢复该用户吗？')){
                $.ajax({
                    type: "post",
                    url: '/user/delete',
                    data: 'id='+$(this).attr('data-id')+'&is_del=1',
                    success: function(res){
                        var result = $.parseJSON(res);
                        if(result.code == 0){
                            alert('恢复成功');
                            location.reload();
                        }else{
                            alert(result.message);
                            return false;
                        }
                    }
                });
            }
        });
    });
    
    $(".sort").each(function(){
        $(this).click(function(){
            var newOrderBy = $(this).attr("sort-id");
            var currentOrderBy = $("#orderbycolumn").val();
            if(newOrderBy==currentOrderBy){
                if($("#sortway").val()=="ASC"){
                    $("#sortway").attr("value","DESC");
                }
                else if($("#sortway").val()=="DESC"){
                    $("#sortway").attr("value","ASC");
                }
                else{
                    $("#sortway").attr("value","DESC");
                }
            }
            else{
                $("#orderbycolumn").attr("value",newOrderBy);
                $("#sortway").attr("value","DESC");
            }
            $("#query_form").submit();
        });
        
        var sortName = $(this).attr("sort-id");
        var selectedName="{$filter.orderby}";
        if(sortName==selectedName){
            var sortWay = "{$filter.sortway}";
            if('DESC'==sortWay){
                $(this).attr("class","sort sort_desc");
            }
            else if('ASC'==sortWay){
                $(this).attr("class","sort sort_asc");
            }
            else{
                $(this).attr("class","sort");
            }
        }
    });
});
</script>
{/block}