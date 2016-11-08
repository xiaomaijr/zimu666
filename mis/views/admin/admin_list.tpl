{extends file="../_base.tpl"}
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
                <label>姓名：<input type="text" name="query[name]" value="{$filter.name|default:''}" class="inpt"/></label>
            </div>
            <div class="item">
                角色：<select name="query[role_id]" class="sel" id="query_role_id">
                    <option value="">请选择</option>
                    {foreach $roleArray as $k=>$role}
                        <option value="{$k}" {if isset($filter.role_id eq $k)}selected="true" {/if}>{$role.name}</option>
                    {/foreach}
                    {html_options options=$roleArray selected=$filter.role_id|default:0}
                </select>
            </div>
            <div class="item">
                <label>手机号码：<input type="text" name="query[mobile]" value="{$filter.mobile|default:''}" class="inpt"/></label>
            </div>
            <input type="hidden" id="orderbycolumn" name="order" value="{$filter.orderby}"/>
            <input type="hidden" id="sortway" name="sortway" value="{$filter.sortway}"/>
            <p><button type="submit">查询</button></p>
        </form>
        {if in_array('admin/add', $smarty.session.money_user_rights)}
            <a href="/admin/add"><button value="添加新角色" class="fr" style="margin-top:-29px">添加新用户</button></a>
        {/if}
    </div>
    <table class="tb-list top10" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th class="sort" width="80px" sort-id="id">ID</th>
                <th>姓名</th>
                <th>身份</th>
                <th>手机</th>
                <th>邮箱</th>
                <th>添加时间</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
        {foreach item=item from=$lists}
            <tr>
                <td class="cnt">{$item.id}</td>
                <td class="cnt">{$item.name}</td>
                <td class="cnt">{$roleArray[$item.role_id]}</td>
                <td class="cnt">{$item.mobile}</td>
                <td class="cnt">{$item.mail}</td>
                <td class="cnt">{$item.create_time|date_format:"%Y-%m-%d %T"}</td>
                <td class="cnt">
                    {if in_array('admin/view', $smarty.session.money_user_rights)}
                        <a href="/admin/view?id={$item.id}">查看</a> |
                    {/if}
                    {if in_array('admin/edit', $smarty.session.money_user_rights)}
                        <a href="/admin/edit?id={$item.id}">修改</a> |
                    {/if}
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
                    type: "post",
                    url: '/admin/delete',
                    data: 'id='+$(this).attr('data-id'),
                    success: function(res){
                        result = $.parseJSON(res);
                        if(result.code == 0){
                            alert('删除成功');
                            location.reload();
                        }else{
                            alert('删除失败');
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
                    url: '/admin/delete',
                    data: 'id='+$(this).attr('data-id')+'&is_del=1',
                    success: function(res){
                        result = $.parseJSON(res);
                        if(result.code == 0){
                            alert('恢复成功');
                            location.reload();
                        }else{
                            alert('恢复失败');
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