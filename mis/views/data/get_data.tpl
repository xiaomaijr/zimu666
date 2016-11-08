{extends file="_base.tpl"}
{block name="content"}
    <h2 class="top10">数据统计</h2>
    <div class="top10 pad10 ui-widget-content">
        <form id="query_form" name="search-form" method="post" action="get-data">
            <span>时间筛选:</span>
            <input type="text" name="begin_time" id="start_time" class="laydate-icon"
                   value="{$filter.beginTime|default:''}"/><span>&nbsp;&nbsp;&nbsp;至</span>
            <input type="text" name="end_time" id="end_time" class="laydate-icon" value="{$filter.endTime|default:''}"/>
            <p id="shaixuanbtn" style="display: inline-block;margin: 0 0 0 25px;">
                <button>筛选</button>
            </p>
        </form>
    </div>
    <div style="display: block;overflow: hidden">
    <table class="tb-list top10" cellpadding="0" cellspacing="0" style="float: left;width: auto">
        <thead>
        <tr>
            <th width="150px">统计项</th>
            {foreach item=item from=$lists}
                <th width="100px">{$item.date|default:''}</th>
            {/foreach}
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="cnt">总订单数</td>
            {foreach item=item from=$lists}
                <td class="cnt">{$item.order_total|default:0}</td>
            {/foreach}
        </tr>
        <tr>
            <td class="cnt">成交订单数</td>
            {foreach item=item from=$lists}
                <td class="cnt">{$item.order_finish|default:0}</td>
            {/foreach}
        </tr>
        <tr>
            <td class="cnt">未成交订单数</td>
            {foreach item=item from=$lists}
                <td class="cnt">{$item.order_fail|default:0}</td>
            {/foreach}
        </tr>
        <tr>
            <td class="cnt">特价车订单数</td>
            {foreach item=item from=$lists}
                <td class="cnt">{$item.order_activity|default:0}</td>
            {/foreach}
        </tr>
        <tr>
            <td class="cnt">新增销售顾问</td>
            {foreach item=item from=$lists}
                <td class="cnt">{$item.nue_seller|default:0}</td>
            {/foreach}
        </tr>
        <tr>
            <td class="cnt">新增特价车</td>
            {foreach item=item from=$lists}
                <td class="cnt">{$item.nue_activity|default:0}</td>
            {/foreach}
        </tr>
        <tr>
            <td class="cnt">有成交销售顾问</td>
            {foreach item=item from=$lists}
                <td class="cnt">{$item.finish_seller|default:0}</td>
            {/foreach}
        </tr>
        <tr>
            <td class="cnt">发放积分总额</td>
            {foreach item=item from=$lists}
                <td class="cnt">{$item.score_sum|default:0}</td>
            {/foreach}
        </tr>
        <tr>
            <td class="cnt">用户端充值总额</td>
            {foreach item=item from=$lists}
                <td class="cnt">{$item.user_charge|default:0}</td>
            {/foreach}
        </tr>
        <tr>
            <td class="cnt">销售端充值总额</td>
            {foreach item=item from=$lists}
                <td class="cnt">{$item.seller_charge|default:0}</td>
            {/foreach}
        </tr>
        <tr>
            <td class="cnt">今日总收入</td>
            {foreach item=item from=$lists}
                <td class="cnt">{$item.total_Charge|default:0}</td>
            {/foreach}
        </tr>
        </tbody>
    </table>
    </div>
{/block}
{block name="js-common"}
    <script>
        //time
        laydate({
            elem: '#start_time',
            event: 'focus'
        });
        laydate({
            elem: '#end_time',
            event: 'focus'
        });
        //筛选
        $('#shaixuanbtn').click(function () {
//        var ab = '';
//        $('.inpt').val(ab);
            $('#query_form').submit();
        });
    </script>
{/block}