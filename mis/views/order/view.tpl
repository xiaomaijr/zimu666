<{extends file="../_base.tpl"}>
<{block name="css-common"}>
    <style>
        .district-li{
            float:left;
            width:140px;
        }
        input[type='checkbox']{
            height: 12px;
        }

        #main{
            padding-left: 100px;
            padding-top: 20px;
        }
        .import_mis{
            right: 150px;
            top: 90px;
            position: absolute;
        }
        .td1{
            width: 200px;
        }
        .brand{
            fload:left;
            padding-right: 20px;
            line-height: 20px;
        }
        table,input{
            font-size: 18px;
        }
        input{
            color:green;
            width:400px;
        }
    </style>
<{/block}>
<{block name="content"}>
    <{if isset($action)}><form method="post" action="<{if $action eq 2}><{'/order/edit'}><{elseif $action eq 1}><{'/order/add'}><{/if}>" id="f1" class="user_form"><{/if}>
        <input type="hidden" name="id" value="<{if isset($info.id)}><{$info.id}><{/if}>">
        <table cellpadding="0" cellspacing="0" class="tb-form top10" style="padding: 30px;width:900px;">

            <tr>
                <td class="td1"><{if isset($action)}><em class="required">*</em><{/if}>订单号：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    <input type="text" name="query[order_no]" id="name" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}> value="<{$info.order_no|default:''}>" class="validate[required]" required="true"/>
                </td>
            </tr>

            <tr>
                <td class="td1"><{if isset($action)}><em class="required">*</em><{/if}>用户手机号：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    <input type="text" name="query[mobile]" id="name" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}> value="<{$info.mobile|default:''}>" class="validate[required]" required="true"/>
                </td>
            </tr>

            <tr>
                <td class="td1"><{if isset($action)}><em class="required">*</em><{/if}>用户名：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    <input type="text" name="query[user_name]" id="name" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}> value="<{$user_info.nick_name|default:''}>" class="validate[required]" required="true"/>
                </td>
            </tr>

            <tr>
                <td class="td1"><{if isset($action)}><em class="required">*</em><{/if}>商品类别名：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    <input type="text" name="query[category_name]" id="name" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}> value="<{$category.name|default:''}>" class="validate[required]" required="true"/>
                </td>
            </tr>

            <tr>
                <td class="td1"><{if isset($action)}><em class="required">*</em><{/if}>商品名：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    <input type="text" name="query[start_address]" id="name" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}> value="<{$good.name|default:''}>" class="validate[required]" required="true"/>
                </td>
            </tr>            <tr>
                <td class="td1"><{if isset($action)}><em class="required">*</em><{/if}>商品期号：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    <input type="text" name="query[good_issue]" id="name" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}> value="<{$info.good_issue|default:''}>" class="validate[required]" required="true"/>
                </td>
            </tr>
            <tr>
                <td class="td1"><{if isset($action)}><em class="required">*</em><{/if}>商品总价：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    <input type="text" name="query[price]" id="name" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}> value="<{$good.price/100|default:''}>" class="validate[required]" required="true"/>
                </td>
            </tr>

            <tr>
                <td class="td1"><{if isset($action)}><em class="required">*</em><{/if}>商品最低价：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    <input type="text" name="query[min_price]" id="name" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}> value="<{$good.min_price/100|default:''}>" class="validate[required]" required="true"/>
                </td>
            </tr>

            <tr>
                <td class="td1"><{if isset($action)}><em class="required">*</em><{/if}>用户购买数量：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    <input type="text" name="query[num]" id="name" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}> value="<{$info.num|default:''}>" class="validate[required]" required="true"/>
                </td>
            </tr>

            <tr>
                <td class="td1"><{if isset($action)}><em class="required">*</em><{/if}>用户购买商品金额：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    <input type="text" name="query[total_price]" id="name" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}> value="<{$info.total_price|default:''}>" class="validate[required]" required="true"/>
                </td>
            </tr>

            <tr>
                <td class="td1"><{if isset($action)}><em class="required">*</em><{/if}>购物车编号：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    <input type="text" name="query[cart_id]" id="name" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}> value="<{$info.cart_id|default:''}>" class="validate[required]" required="true"/>
                </td>
            </tr>

            <tr>
                <td class="td1"><{if isset($action)}><em class="required">*</em><{/if}>商品颜色：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    <input type="text" name="query[color]" id="name" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}> value="<{$info.color|default:''}>" class="validate[required]" required="true"/>
                </td>
            </tr>
            <tr>
                <td class="td1"><{if isset($action)}><em class="required">*</em><{/if}>商品款式：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    <input type="text" name="query[style]" id="name" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}> value="<{$info.style|default:''}>" class="validate[required]" required="true"/>
                </td>
            </tr>
            <tr>
                <td class="td1"><{if isset($action)}><em class="required">*</em><{/if}>状态：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    <input type="text" name="query[status]" id="name" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}> value="<{$info.status|default:''}>" class="validate[required]" required="true"/>
                </td>
            </tr>
            <tr>
                <td class="td1"><{if isset($action)}><em class="required">*</em><{/if}>中奖幸运号：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    <input type="text" name="query[reward_luck_number]" id="name" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}> value="<{$status[$info.reward_luck_number]|default:''}>" class="validate[required]" required="true"/>
                </td>
            </tr>
            <{*<tr>
                <td class="td1"><{if isset($action)}><em class="required">*</em><{/if}>订单子状态：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    <input type="text" name="query[sub_status]" id="name" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}> value="<{$substatus[$info.sub_status]|default:''}>" class="validate[required]" required="true"/>
                </td>
            </tr>


            <tr>
                <td class="td1"><{if isset($action)}><em class="required">*</em><{/if}>退款状态：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    <input type="text" name="query[refund_status]" id="name" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}> value="<{$refundstatus[$info.refund_status]|default:''}>" class="validate[required]" required="true"/>
                </td>
            </tr>
            <tr>
                <td class="td1"><{if isset($action)}><em class="required">*</em><{/if}>退款费用(元)：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    <input type="text" name="query[refund_cost]" id="name" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}> value="<{$info.refund_cost|default:''}>" class="validate[required]" required="true"/>
                </td>
            </tr>

            <tr>
                <td class="td1"><{if isset($action)}><em class="required">*</em><{/if}>总消费费用(元)：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    <input type="text" name="query[cost_total]" id="name" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}> value="<{$info.cost_total|default:''}>" class="validate[required]" required="true"/>
                </td>
            </tr>
            <tr>
                <td class="td1"><{if isset($action)}><em class="required">*</em><{/if}>尾款(元)：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    <input type="text" name="query[need_pay]" id="name" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}> value="<{$info.need_pay|default:''}>" class="validate[required]" required="true"/>
                </td>
            </tr>
            <tr>
                <td class="td1"><{if isset($action)}><em class="required">*</em><{/if}>取消时间：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    <input type="text" name="query[cancel_time]" id="name" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}> value="<{$info.cancel_time|date_format:"%Y-%m-%d %T"|default:''}>" class="validate[required]" required="true"/>
                </td>
            </tr>
            <tr>
                <td class="td1"><{if isset($action)}><em class="required">*</em><{/if}>司机点击出发时间：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    <input type="text" name="query[driver_depart_time]" id="name" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}> value="<{$info.driver_depart_time|date_format:"%Y-%m-%d %T"|default:''}>" class="validate[required]" required="true"/>
                </td>
            </tr>
            <tr>
                <td class="td1"><{if isset($action)}><em class="required">*</em><{/if}>司机出发地点经度：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    <input type="text" name="query[driver_depart_lng]" id="name" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}> value="<{$info.driver_depart_lng|default:''}>" class="validate[required]" required="true"/>
                </td>
            </tr>
            <tr>
                <td class="td1"><{if isset($action)}><em class="required">*</em><{/if}>司机出发地点纬度：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    <input type="text" name="query[driver_depart_lat]" id="name" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}> value="<{$info.driver_depart_lat|default:''}>" class="validate[required]" required="true"/>
                </td>
            </tr>*}>

            <{if isset($action)&&$action eq 2}>
            <tr>
                <td class="td1">删除状态：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    <input type="radio" name="query[is_del]" id="cms_mobile"  value=0  <{if isset($info.is_del)&&$info.delete eq 0}>checked="true"<{/if}>/>未删除
                    <input type="radio" name="query[is_del]" id="cms_mobile"  value=1 <{if isset($info.is_del)&&$info.delete eq 1}>checked="true"<{/if}>/>删除
                </td>
            </tr>
            <{elseif !isset($action)}>
            <tr>
                <td class="td1">删除状态：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    <{$info.is_del|default:0|replace:0:'未删除'|replace:1:'已删除'}>
                </td>
            </tr>
            <{/if}>
            <{if !isset($action)}>
                <tr>
                    <td class="td1">创建时间：</td>
                    <td style="padding-top:15px;padding-bottom: 15px">
                        <{$info.create_time|date_format:"%Y-%m-%d %T"|default:''}>
                    </td>
                </tr>
                <tr>
                    <td class="td1">最近操作时间：</td>
                    <td style="padding-top:15px;padding-bottom: 15px">
                        <{$info.update_time|date_format:"%Y-%m-%d %T"|default:''}>
                    </td>
                </tr>
            <{/if}>
            <tr style="height:5px">
                <td colspan="2">&nbsp;</td>
            </tr>
        </table>
    <{if isset($action)}>
            <div align="center"><button class="btn" type="submit" value="submit">提 交</button></div>
    </form>
    <{/if}>
<{/block}>
{*{block name="js-common"}*}
    {*<script>*}
        {*$(document).ready(function(){*}

            {*$(".brand").each(function(){*}
                {*var self = this;*}
                {*var url = '/groups/child-brand?id='+$(this).attr('data-id');*}
                {*$(this).click(function(){*}
                    {*if(confirm('你确定要删除该四S店吗？')){*}
                        {*$.get(*}
                                {*url,*}
                                {*function(res){*}
                                    {*if(res=='success'){*}
                                        {*$(self).parent().parent().remove();*}
                                        {*var num = $('font').html()-1;*}
                                        {*$('font').html(num);*}
                                    {*}*}
                                {*}*}
                        {*);*}
                    {*}*}
                {*});*}
            {*});*}

        {*});*}
    {*</script>*}
{*{/block}*}
