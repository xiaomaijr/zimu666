<{extends file="_base.tpl"}>
<{block name="content"}>
    <div class="formsubmit">
        <form id="query_form" name="search-form" method="post" action="list">
            <!---list--->
            <div class="selectlist">
                <label>订单号:</label>
                <input type="text" name="query[order_no]" value="<{$filter.order_no|default:''}>">
            </div>
            <!---list end--->
            <!---list--->
            <div class="selectlist">
                <label>用户手机号:</label>
                <input type="text" name="query[mobile]" value="<{$filter.mobile|default:''}>">
            </div>
            <!---list end--->
            <!---list--->
            <div class="selectlist">
                <label>时间范围:</label>
                <input type="text" name="begin_time" id="start_time" class="laydate-icon"
                       value="<{$filter.beginTime|default:''}>"/>
                <label>~</label>
                <input type="text"name="end_time" id="end_time" class="laydate-icon"
                       value="<{$filter.endTime|default:''}>"/>
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
                <th>订单号</th>
                <th>价钱(元)</th>
                <th>商品期号</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            <{foreach $list as $admin}>
            <tr>
                <td><{$admin.id}></td>
                <td><{$admin.create_time|date_format:'%Y-%m-%d %H'}></td>
                <td><{$admin.good.name|default:''}></td>
                <td><{$admin.category.name}></td>
                <td><{$admin.order_no|default:''}></td>
                <td><{$admin.total_price|default:''}></td>
                <td><{$admin.issue|default:''}></td>
                <td><{$admin.status|default:''}></td>
                <td>
                    <{if $admin.is_del eq 0}>
                    <a href="/order/view?id=<{$admin.id}>">详情</a>
                    <{if $admin.original_status eq 1}>
                    <a href="javascript:void(0)" class="del" data-id="<{$admin.id}>">删除</a>
                    <{/if}>
                    <{if $admin.original_status eq 5}>
                    <a href="javascript:void(0)" class="send" data-id="<{$admin.id}>">配送</a>
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


    $(".send").each(function(){
        var self = this;
        $(this).click(function(){
            if(confirm('你确定该订单已发货？')){
                $.ajax({
                    type: "post",
                    url: '/order/send',
                    data: 'id='+$(this).attr('data-id'),
                    dataType : 'json',
                    success: function(res){
                        if(res.code == 0){
                            alert('发货成功');
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
            if(confirm('你确定要恢复该订单吗？')){
                $.ajax({
                    type: "post",
                    url: '/order/delete',
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
        $('.cancel').click(function(){
            if(confirm('你确定要取消该订单吗？')){
                var self = this;
                if(confirm('是否需要退款?')){
                    $('#overlay').html("<ul style='margin-top: 20%;line-height: 30px;font-size: 16px;font-family: 宋体;'><li>" +
                    "选择取消订单原因:</li> <li><input type='radio' name='reason' value='1'/>用户&nbsp;&nbsp;&nbsp;<input type='radio' name='reason' value='2'/>" +
                    "4S店</li><li><span>退款金额(元):</span><input type='text' id='refund' style='width:80px;'/></li>" + "<li><button id='miscancel'>提交</button></li> </ul>");
                }else{
                    $('#overlay').html("<ul style='margin-top: 20%;line-height: 30px;font-size: 16px;font-family: 宋体;'><li>" +
                    "选择取消订单原因:</li> <li><input type='radio' name='reason' value='1'/>用户</li> <li><input type='radio' name='reason' value='2'/>4S店</li>" +
                    "<li><button id='miscancel'>提交</button></li> </ul>");
                }
                $('#overlay').css('display','block');
                $('#miscancel').click(function(){
                    $('[name=reason]').each(function(){
                        if($(this).attr('checked')){
                            reason = $(this).val();
                        }
                    });
                    if(reason == undefined){
                        alert('请选择取消原因');
                        return false;
                    }
                    refund = $("#refund");
                    if(refund == undefined){
                        param = 'id='+$(self).attr('data-id')+'&reason='+reason;
                    }else{
                        param = 'id='+$(self).attr('data-id')+'&reason='+reason+'&refund_cost='+refund.val();
                    }
                    $.ajax({
                        type: "post",
                        url: '/order/cancel',
                        data: param,
                        success: function(res){
                            result = $.parseJSON(res);
                            if(result.code == 0){
                                alert('取消成功');
                                location.reload();
                            }else{
                                alert('取消失败');
                                return false;
                            }
                        }
                    });

                });
                return false;
            }
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
    $('.refund').click(function(){
        if(confirm('确认退款?')){
            $.ajax({
                type     :   'post',
                url      :   '/order/refund',
                data     :   'id='+$(this).attr('data-id'),
                success  :    function(con){
                    content = $.parseJSON(con);
                    if(content.code == 0){
                        alert('退款成功');
                        history.go(0);
                    }else{
                        alert(content.message);
                        return false;
                    }
                }
            });
        }
    });

    $('.confirm').click(function(){
        if(confirm('已跟客户确认?')){
            $.ajax({
                type     :   'post',
                url      :   '/order/confirm',
                data     :   'id='+$(this).attr('data-id'),
                success  :    function(con){
                    content = $.parseJSON(con);
                    if(content.code == 0){
                        alert('确认成功');
                        history.go(0);
                    }else{
                        alert(content.message);
                        return false;
                    }
                }
            });
        }
    });

});
//time
laydate({
    elem : '#start_time',
    event: 'focus'
});
laydate({
    elem : '#end_time',
    event: 'focus'
});
</script>
<{/block}>