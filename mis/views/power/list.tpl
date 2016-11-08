{extends file="_base.tpl"}
{block name="content"}
    <style>
        .ui-widget-content .item {
            margin:2px 10px 2px 0;
            float:left;
        }
    </style>
    <h2 class="top10">角色列表</h2>
    <table class="tb-list top10" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th class="sort" width="80px" sort-id="id">ID</th>
                <th>角色名</th>
                <th>Operator_Id</th>
                <th class="sort" width="80px" sort-id="is_del">状态</th>
                <th class="sort" width="80px" sort-id="create_time">创建时间</th>
                <th>更新时间</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
        {foreach item=item from=$roles}
            <tr>
                <td class="cnt">{$item.id}</td>
                <td class="cnt">{$item.name|default:''}</td>
                <td class="cnt">{$item.operator_id|default:''}</td>
                <td class="cnt">{$item.is_del|default:0|replace:0:'未删除'|replace:1:'已删除'}</td>
                <td class="cnt">{$item.create_time|date_format:"%Y-%m-%d %T"}</td>
                <td class="cnt">{$item.update_time|date_format:"%Y-%m-%d %T"}</td>
                <td class="cnt">
                    {*{if in_array('user/view', $smarty.session.money_user_rights)}*}
                    <a href="/power/view?id={$item.id}">查看</a>
                    {*{/if}*}
                    {*{if in_array('user/edit', $smarty.session.money_user_rights)}*}
                    |<a href="/power/edit?id={$item.id}">修改</a>
                    {*{/if}*}
                    {if $item.is_del eq 0}
                    |<a href="#" class="del" data-id="{$item.id}">删除</a>
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
{block name="js-common"}
<script>
$(document).ready(function(){
    
    $(".del").each(function(){
        var self = this;
        $(this).click(function(){
            if(confirm('你确定删除该记录吗？')){
                $.ajax({
                    type: "post",
                    url: '/power/delete',
                    data: 'id='+$(this).attr('data-id')+'&is_del=1',
                    success: function(res){
                        var result = $.parseJSON(res);
                        if(result.code == 0){
                            alert('删除成功');
                            history.go(0);
                        }else{
                            var result = $.parseJSON(res);
                            alert(result.message);
                        }
                    }
                });
            }
        });
    });

    $(".recovery").each(function(){
        var self = this;
        $(this).click(function(){
            if(confirm('你确定恢复该记录吗？')){
                $.ajax({
                    type: "post",
                    url: '/power/delete',
                    data: 'id='+$(this).attr('data-id')+'&is_del=0',
                    success: function(res){
                        var result = $.parseJSON(res);
                        if(result.code == 0){
                            alert('恢复成功');
                            location.reload();
                        }else{
                            var result = $.parseJSON(res);
                            alert(result.message);
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