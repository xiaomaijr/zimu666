{extends file="_base.tpl"}
{block name="content"}
    <style>
        .ui-widget-content .item {
            margin:2px 10px 2px 0;
            float:left;
            
        }
        .info{
            margin-top: 20px;
            text-align: center;
        }
        .info ul li{
            margin-bottom: 20px;
            font-size: 20px;
        }
        hr{
            margin:10px;
        }
        .info-form{
            margin: auto;
            font-size: 20px;
        }
        td{
            width:200px;
            height:30px;
        }
    </style>
    <h2 class="top10">订单退款详情</h2>


    <div class="info">
        <table align="center" class="info-form">
        <form action="/order/refund" method="post">
            <input type='hidden' name='id' value='{$info.id}'/>
            <tr align="center"><td align="center">用户名称</td><td align="center">{$info.user_name|default:''}</td></tr>
            <tr align="center"><td align="center">司机名称</td><td align="center">{$info.driver_name|default:''}</td></tr>
            <tr align="center"><td align="center">取车地点</td><td align="center">{$info.start_address|default:''}</td></tr>
            <tr align="center"><td align="center">取车地点经度</td><td align="center">{$info.start_lng|default:''}</td></tr>
            <tr align="center"><td align="center">取车地点纬度</td><td align="center">{$info.start_lat|default:''}</td></tr>
            <tr align="center"><td align="center">下车地点</td><td align="center">{$info.end_address|default:''}</td></tr>
            <tr align="center"><td align="center">下车地点经度</td><td align="center">{$info.end_lng|default:''}</td></tr>
            <tr align="center"><td align="center">下车地点纬度</td><td align="center">{$info.end_lat|default:''}</td></tr>
            <tr align="center"><td align="center">预约时间</td><td align="center">{$info.time|date_format:"%Y-%m-%d %T"|default:""}</td></tr>
            <tr align="center"><td align="center">预付费</td><td align="center">{$info.prepayment|default:''}</td></tr>
            {*<tr align="center"><td align="center">抢单司机与车辆列表</td>*}
                {*<div>{$info.accept_list}</div>*}
            {*</tr>*}
            {*<tr align="center"><td align="center">预约单状态</td><td align="center">{$info.status|default:''}</td></tr>*}
            {*<tr align="center"><td align="center">子状态</td><td align="center">{$info.sub_status|default:''}</td></tr>*}
            {*<tr align="center"><td align="center">取消时间</td><td align="center">{$info.cancel_time|date_format:"%Y-%m-%d %T"|default:''}</td></tr>*}
            <tr align="center"><td align="center">退款状态</td><td align="center">{$info.refund_status|default:''|replace:'1':'无退款'|replace:'2':'待退款'|replace:'3':'退款完成'}</td></tr>
            <tr align="center"><td align="center">退款金额</td><td align="center"><input type="text" name="query[refund_cost]" value="{$info.refund_cost|default:''}"/></td></tr>
            {*<tr align="center"><td align="center">手机版本</td><td align="center">{$info.mobile_ver|default:''}</td></tr>*}
            {*<tr align="center"><td align="center">App版本</td><td align="center">{$info.app_ver|default:''}</td></tr>*}
            {*<tr align="center"><td align="center">手机品牌</td><td align="center">{$info.mobile_brand|default:''}</td></tr>*}
            {*<tr align="center"><td align="center">Api版本</td><td align="center">{$info.api_ver|default:''}</td></tr>*}
            {*<tr align="center"><td align="center">渠道</td><td align="center">{$info.channel|default:''}</td></tr>*}
            {*<tr align="center"><td align="center">App名称</td><td align="center">{$info.app_name|default:''}</td></tr>*}
            {*<tr align="center"><td align="center">手机类型</td><td align="center">{$info.mobile_type|default:''}</td></tr>*}
            {*<tr align="center"><td align="center">设备token</td><td align="center">{$info.device_id|default:''}</td></tr>*}
            {*<tr align="center"><td align="center">坐标类型</td><td align="center">{$info.coord_type|default:''}</td></tr>*}
            {*<tr align="center"><td align="center">是否删除</td><td align="center">{$info.del_status|default:'否'}</td></tr>*}
            {*<tr align="center"><td align="center">创建时间</td><td align="center">{$info.create_time|date_format:"%Y-%m-%d %T"|default:''}</td></tr>*}
            {*<tr align="center"><td align="center">最近更新时间</td><td align="center">{$info.update_time|date_format:"%Y-%m-%d %T"|default:''}</td></tr>*}
            <tr align="center"><td><button type="submit" name="edit" value="1">提交</button></td></tr>
        </form>
        </table>
    </div>
{/block}
