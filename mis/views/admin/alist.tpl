<{extends file="../_base.tpl"}>
<{block name="content"}>
    <div class="formsubmit">
        <form id="query_form" name="search-form" method="post" action="list">
        <!---list--->
        <div class="selectlist">
            <label>姓名:</label>
            <input type="text" name="query[name]" value="<{$filter.name|default:''}>">
        </div>
        <!---list end--->
        <!---list--->
        <div class="selectlist">
            <label>手机号:</label>
            <input type="text" name="query[mobile]" value="<{$filter.mobile|default:''}>">
        </div>
        <!---list end--->
        <button>搜索</button>
        </form>
    </div>


    <!---table-->
    <div class="tablebox">
        <table cellpadding="0" cellspacing="0" bgcolor="#ffffff" width="100%">
            <tr>
                <th>编号</th>
                <th>时间</th>
                <th>手机</th>
                <th>用户名</th>
                <th>操作</th>
            </tr>
            <{foreach $list as $admin}>
            <tr>
                <td><{$admin.id}></td>
                <td><{$admin.create_time|date_format:'%Y-%m-%d %H'}></td>
                <td><{$admin.name|default:''}></td>
                <td><{$admin.mobile}></td>
                <td>
                    <{if $admin.is_del eq 0}>
                        <a href="javascript:void(0)" class="del" data-id="<{$admin.id}>">删除</a>
                    <{else}>
                        <a href="javascript:void(0)" class="recovery" data-id="<{$admin.id}>">恢复</a>
                    <{/if}>
                </td>
            </tr>
            <{/foreach}>
        </table>


        <div class="page">
            <{$arrPager.pagelink}>
        </div>
    </div>
    <!---table end-->
<{/block}>
<{block name="js-common"}>
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
                        window.console.log(result);
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
<{/block}>