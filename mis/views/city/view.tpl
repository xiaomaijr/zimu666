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
            padding: 20px 50px;
        }
        .import_mis{
            right: 150px;
            top: 90px;
            position: absolute;
        }
        .td1{
            width: 200px;
            text-indent: 20px;
            font-size: 14px;
            line-height: 24px;
        }
        .td2{
            padding-top:15px;
            padding-bottom: 15px
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

    {if isset($action)}
    <form method="post" action="{if $action eq 2}{'/seller/add'}{elseif $action eq 1}{'/seller/edit'}{/if}" id="f1" class="user_form">{/if}
    <input type="hidden" name="id" value="{if isset($info.id)}{$info.id}{/if}">
        <div class="reser_box">
    <table cellpadding="0" cellspacing="0" class="tb-form top10" width="100%">


        <tr>
            <td class="td1">{if isset($action)}<em class="required">*</em>{/if}ID：</td>
            <td class="td2">
                <input type="text" name="query[seller_id]" id="id" {if !isset($action)}readonly='true' style='border-style:none;'{/if}
                       value="{$info.id|default:''}" class="validate[required]" required="true"/>
            </td>
        </tr>

        <tr>
            <td class="td1">{if isset($action)}<em class="required">*</em>{/if}城市名称：</td>
            <td class="td2">
                <input type="text" name="query[name]" id="mobile" {if !isset($action)}readonly='true' style='border-style:none;'{/if}
                       value="{$info.name|default:''}" class="validate[required]" required="true"/>
            </td>
        </tr>

        <tr>
            <td class="td1">状态：</td>
            <td class="td2">

                <select name="query[status]" id="sex"  {if !isset($action)}readonly='true'{/if}>
                    {html_options options=$arrStatus selected=$info.status|default:0}
                </select>

            </td>
        </tr>

        {if !isset($action)}
            <tr>
                <td class="td1">创建时间：</td>
                <td class="td2">
                    {$info.create_time|date_format:"%Y-%m-%d %T"|default:''}
                </td>
            </tr>
            <tr>
                <td class="td1">最近操作时间：</td>
                <td class="td2">
                    {$info.update_time|date_format:"%Y-%m-%d %T"|default:''}
                </td>
            </tr>
        {/if}


    </table>
</div>
    {if isset($action)}
    <div align="left"><button class="btn" type="submit" value="submit" style="margin-left: 300px;">提 交</button></div>
    {/if}
{/block}

