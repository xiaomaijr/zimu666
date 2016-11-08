{extends file="../_base.tpl"}
{block name="css-common"}
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
            /*width:600px;*/
        }
    </style>
{/block}
{block name="content"}
    <h3 class="top10" style="margin-left: 30px;"><span style="font-size: 22px;">订单导出</span></h3>
    <form method="post" action="/order/excel" id="f1" class="user_form">
        <input type="hidden" name="flag" value="1" id="flag"/>
    <table cellpadding="0" cellspacing="0" class="tb-form top10" style="padding: 30px;width:900px;">
        <tr>
            <td class="td1" style="color:#2E6E9E; font-size:14px; border-bottom:1px solid #2E6E9E;align-content: center;"><span
                        style="font-size:20px;padding-right:30px">筛选信息</span></td>
            <td style="border-bottom:1px solid #2E6E9E;width:500px;align-content: center"></td>
        </tr>
        <tr style="height:5px">
            <td colspan="2">&nbsp;</td>
        </tr>


        <tr>
            <td class="td1">起始日期：</td>
            <td style="padding-top:15px;padding-bottom: 15px">
                <input type="text" name="query[start_time]" id="start_time" class="laydate-icon" />
            </td>
        </tr>

        <tr>
            <td class="td1">截至日期：</td>
            <td style="padding-top:15px;padding-bottom: 15px">
                <input type="text" name="query[end_time]" id="end_time" class="laydate-icon" />
            </td>
        </tr>

        {*<tr>*}
            {*<td class="td1">4S店：</td>*}
            {*<td style="padding-top:15px;padding-bottom: 15px;">*}
                {*<select name="query[group_id]" style="width:190px;">*}
                    {*<option value="">--------------请选择--------------</option>*}
                    {*{foreach $groups as $gk=>$group}*}
                        {*<option value="{$gk}">{$group.name}</option>*}
                    {*{/foreach}*}
                {*</select>*}
            {*</td>*}
        {*</tr>*}

        {*<tr>*}
            {*<td class="td1">司机：</td>*}
            {*<td style="padding-top:15px;padding-bottom: 15px;">*}
                {*<select name="query[driver_id]" style="width:190px;">*}
                    {*<option value="">--------------请选择--------------</option>*}
                    {*{foreach $drivers as $dk=>$driver}*}
                        {*<option value="{$dk}">{$driver.name}</option>*}
                    {*{/foreach}*}
                {*</select>*}
            {*</td>*}
        {*</tr>*}


        <tr style="height:5px">
            <td colspan="2">&nbsp;</td>
        </tr>
    </table>
    <div align="left" style="margin-left:300px; "><button class="btn" type="submit" value="submit">提 交</button></div>
    </form>
{/block}
{block name="js-common"}
    <script>
        laydate({
            elem : '#start_time',
            event: 'focus'
        });
        laydate({
            elem : '#end_time',
            event: 'focus'
        });

    </script>
{/block}
