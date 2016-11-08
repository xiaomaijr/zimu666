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
    <h3 class="top10" style="margin-left: 30px;"><span style="font-size: 22px;">{if !isset($action)}详情{elseif $action eq 1}添加{elseif $action eq 2}编辑{/if}</span></h3>
    {if isset($action)}<form method="post" action="{if $action eq 2}{'/power/edit'}{elseif $action eq 1}{'/power/add'}{/if}" id="f1" class="user_form">{/if}
    <input type="hidden" name="id" value="{if isset($info.id)}{$info.id}{/if}">
    <table cellpadding="0" cellspacing="0" class="tb-form top10" style="padding: 30px;width:900px;">
        <tr>
            <td class="td1" style="color:#2E6E9E; font-size:14px; border-bottom:1px solid #2E6E9E;align-content: center;"><span
                        style="font-size:20px;padding-right:30px">基本信息</span></td>
            <td style="border-bottom:1px solid #2E6E9E;width:500px;align-content: center"></td>
        </tr>
        <tr style="height:5px">
            <td colspan="2">&nbsp;</td>
        </tr>


        <tr>
            <td class="td1">{if isset($action)}<em class="required">*</em>{/if}角色名：</td>
            <td style="padding-top:15px;padding-bottom: 15px">
                <input type="text" name="query[name]" id="name" {if !isset($action)}readonly='true' style='border-style:none;'{/if}
                       value="{$info.name|default:''}" class="validate[required]" required="true"/>
            </td>
        </tr>

        {if !isset($action)}
            <tr>
                <td class="td1">上架状态：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    {$info.is_del|default:1|replace:0:'未删除'|replace:1:'已删除'}
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
    <div align="left"><button class="btn" type="submit" value="submit" style="margin-left: 300px;">提 交</button></div>
    {/if}
{/block}
