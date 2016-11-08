<{extends file="_base.tpl"}>
<{block name="content"}>
    <div class="formsubmit">
        <form id="query_form" name="search-form" method="post" action="list">
            <!---list--->
            <div class="selectlist">
                <label>商品名:</label>
                <input type="text" name="query[name]" value="<{$filter.name|default:''}>">
            </div>
            <!---list end--->
            <!---list--->
            <div class="selectlist">
                <label>类别名:</label>
                <input type="text" name="query[category_name]" value="<{$filter.category_name|default:''}>">
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
                <th>商品名</th>
                <th>类别名</th>
                <th>颜色</th>
                <th>价钱(元)</th>
                <th>抢购最低价(元)</th>
                <th>款式</th>
                <th>操作</th>
            </tr>
            <{foreach $list as $admin}>
            <tr>
                <td><{$admin.id}></td>
                <td><{$admin.create_time|date_format:'%Y-%m-%d %H'}></td>
                <td><{$admin.name|default:''}></td>
                <td><{$categorys[$admin.category_id]['name']}></td>
                <td><{$admin.color|default:''}></td>
                <td><{round($admin.price/100, 2)|default:''}></td>
                <td><{round($admin.min_price/100, 2)|default:''}></td>
                <td><{$admin.style|default:''}></td>
                <td>
                    <{if $admin.is_del eq 0}>
                        <a href="javascript:void(0)" class="del" data-id="<{$admin.id}>">删除</a>
                        <a href="/product/edit?id=<{$admin.id}>">编辑</a>
                    <{if $admin.status eq 0}>
                            <a href="javascript:void(0)" class="shelves" data-id="<{$admin.id}>">上架</a>
                         <{/if}>
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
            if(confirm('你确定要删除该产品吗？')){
                $.ajax({
                    type: "post",
                    url: '/product/delete',
                    data: 'id='+$(this).attr('data-id')+'&is_del=1',
                    dataType : 'json',
                    success: function(res){
                        if(res.code == 0){
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
            if(confirm('你确定要恢复该产品吗？')){
                $.ajax({
                    type: "post",
                    url: '/product/delete',
                    data: 'id='+$(this).attr('data-id')+'&is_del=0',
                    dataType : 'json',
                    success: function(res){
                        if(res.code == 0){
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
    $(".shelves").each(function(){
        var self = this;
        $(this).click(function(){
            if(confirm('你确定要上架该产品吗？')){
                $.ajax({
                    type: "post",
                    url: '/product/shelves',
                    data: 'id='+$(this).attr('data-id'),
                    dataType : 'json',
                    success: function(res){
                        if(res.code == 0){
                            alert('上架成功');
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
<{/block}>