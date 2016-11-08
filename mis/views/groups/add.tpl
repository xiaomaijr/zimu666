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
            width: 100px;
        }
        .brand{
            fload:left;
            padding-right: 20px;
            line-height: 20px;
        }
    </style>
{/block}
{block name="js-common"}
        {*<script src="/static/risk/js/admin_template.js" type="text/javascript"></script>*}
{/block}
{block name="content"}
    <h3 class="top10">{if !isset($action)}详情{elseif $action eq 1}添加{elseif $action eq 2}编辑{/if}</h3>
    {if isset($action)}<form method="post" action="{if $action eq 2}{'/groups/edit'}{elseif $action eq 1}{'/groups/add'}{/if}" id="f1" class="user_form">{/if}
        <input type="hidden" name="id" value="{if isset($info.id)}{$info.id}{/if}">
        <table cellpadding="0" cellspacing="0" class="tb-form top10" style="padding: 30px;">
            <tr>
                <td class="td1" style="color:#2E6E9E; font-size:14px; border-bottom:1px solid #2E6E9E;"><span style="padding-right:30px">基本信息</span></td>
                <td style="border-bottom:1px solid #2E6E9E;"></td>
            </tr>
            <tr style="height:5px">
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class="td1">{if isset($action)}<em class="required">*</em>{/if}城市名：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    {if isset($action)}
                        <select class="sel" name="query[city_id]" style="width:150px;">
                            <option value="">----------请选择----------</option>
                            {foreach $citys as $k=>$v}
                                <option value="{$k}" {if isset($info.city_id)&&$info.city_id eq $k}selected{/if}>{$v.name}</option>
                            {/foreach}
                        </select>
                    {else}
                        {$info.city_name}
                    {/if}
                </td>
            </tr>


            <tr>
                <td class="td1">{if isset($action)}<em class="required">*</em>{/if}四S店名称：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    <input type="text" name="query[name]" id="name" {if !isset($action)}readonly='true' style='border-style:none;'{/if} value="{if isset($info.name)}{$info.name}{/if}" class="validate[required]" required="true"/>
                </td>
            </tr>

            <tr>
                <td class="td1">父四S店名：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    {if isset($action)}
                        <select class="sel" name="query[upper_id]" style="width:150px;">
                            <option value="">----------请选择----------</option>
                            {foreach $groups as $k=>$v}
                                <option value="{$k}" {if isset($info.upper_id)&&$info.upper_id eq $k}selected{/if}>{$v.name}</option>
                            {/foreach}
                        </select>
                    {else}
                        {$info.upper_name|default:''}
                    {/if}
                </td>
            </tr>

            {if isset($action)}
            <tr>
                <td class="td1">下属父品牌：</td>
                <td  style="padding-top:15px;padding-bottom: 15px">
                    {if !empty($firstBrands)}
                        {foreach $firstBrands as $k=>$brand}
                        <span class="brand"><input type="checkbox" name="brand_ids[]" {if !isset($action)}readonly='true' style='border-style:none;'{/if} value={$k}  {if isset($info.brand_ids)&&in_array($k,$info.brand_ids)}checked="true"{/if}/>&nbsp;{$brand.name}</span>
                        {/foreach}
                    {/if}
                </td>
            </tr>
            <tr style="padding-top: 20px;padding-bottom: 20px">
                <td class="td1">下属子品牌：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                        {if !empty($secondBrands)}
                            {foreach $secondBrands as $k=>$brand}
                    <span class="brand"><input type="checkbox" name="brand_ids[]" {if !isset($action)}readonly='true' style='border-style:none;'{/if} value={$k}  {if isset($info.brand_ids)&&in_array($k,$info.brand_ids)}checked="true"{/if}/>&nbsp;{$brand.name}</span>
                            {/foreach}
                        {/if}
                </td>
            </tr>
            {else}
                <tr>
                    <td class="td1">下属品牌名：</td>
                    <td style="padding-top:15px;padding-bottom: 15px">
                        {$info.brand_names|default:''}
                    </td>
                </tr>
            {/if}
            <tr>
                <td class="td1">{if isset($action)}<em class="required">*</em>{/if}地址：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    <input type="text" name="query[address]" id="address" {if !isset($action)}readonly='true' style='border-style:none;'{/if} value="{if isset($info.address)}{$info.address}{/if}" class="validate[required]" required="true"/>
                </td>
            </tr>
            <tr>
                <td class="td1">经度：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    <input type="text" name="query[address_lng]" id="address_lng" {if !isset($action)}readonly='true' style='border-style:none;'{/if} value="{if isset($info.address_lng)}{$info.address_lng}{/if}" class="validate[required]"  cate="num" />
                </td>
            </tr>
            <tr>
                <td class="td1">纬度：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    <input type="text" name="query[address_lat]" id="address_lat" {if !isset($action)}readonly='true' style='border-style:none;'{/if} value="{if isset($info.address_lat)}{$info.address_lat}{/if}" class="validate[required]"  cate="num" />
                </td>
            </tr>
            <tr>
                <td class="td1">{if isset($action)}<em class="required">*</em>{/if}联系电话：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    <input type="text" name="query[phone_number]" id="phone_number" {if !isset($action)}readonly='true' style='border-style:none;'{/if} value="{if isset($info.phone_number)}{$info.phone_number}{/if}" class="validate[required]"  cate="num" required="true"/>
                </td>
            </tr>

            {if !isset($action)}
            <tr>
                <td class="td1">删除状态：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    {$info.is_del|replace:0:'未删除'|replace:1:'已删除'}
                </td>
            </tr>
            {/if}
            {if !isset($action)}
                <tr>
                    <td class="td1">创建时间：</td>
                    <td style="padding-top:15px;padding-bottom: 15px">
                        {$info.create_time|date_format:"%Y-%m-%d %T"|default:''}
                    </td>
                </tr>
                <tr>
                    <td class="td1">最近操作时间：</td>
                    <td style="padding-top:15px;padding-bottom: 15px">
                        {$info.update_time|date_format:"%Y-%m-%d %T"|default:''}
                    </td>
                </tr>
            {/if}
            <tr style="height:5px">
                <td colspan="2">&nbsp;</td>
            </tr>
        </table>
    {if isset($action)}
            <div align="center"><button class="btn" type="submit" value="submit">提 交</button></div>
    </form>
    {/if}
{/block}
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
