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
            width: 100px;
        }
    </style>
<{/block}>
<{block name="content"}>
    <{if isset($action)}><form method="post" action="<{if $action eq 2}>/cms/edit<{elseif $action eq 1}>/cms/add<{/if}>" id="f1" class="user_form"><{/if}>
        <input type="hidden" name="id" value="<{if isset($info.id)}><{$info.id}><{/if}>">
        <table cellpadding="0" cellspacing="0" class="tb-form top10">
            <tr>
                <td class="td1" style="color:#2E6E9E; font-size:14px; border-bottom:1px solid #2E6E9E;"><span style="padding-right:30px">基本信息</span></td>
                <td style="border-bottom:1px solid #2E6E9E;"></td>
            </tr>
            <tr style="height:5px">
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class="td1"><em class="required">*</em>主标签：</td>
                <td>
                    <input type="text" name="query[p_sign]" id="cms_name" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}> value="<{if isset($info.p_sign)}><{$info.p_sign}><{/if}>" class="validate[required]" required="true"/>
                </td>
            </tr>
            <tr>
                <td class="td1">从标签：</td>
                <td>
                    <input type="text" name="query[s_sign]" id="cms_mobile" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}> value="<{if isset($info.s_sign)}><{$info.s_sign}><{/if}>" class="validate[required]"  cate="num" />
                </td>
            </tr>

            <tr>
                <td class="td1" style="color:#2E6E9E; font-size:14px; border-bottom:1px solid #2E6E9E;" colspan="2"><span style="padding-right:30px">详细数据,以行未单位,每行之间用英文,分割</span></td>
            </tr>
            <tr style="height:5px">
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class="td1"><em class="required">*</em>CMSdata：</td>
                <td>
                    <textarea cols="40" rows="10" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}> class="sel" name="query[data]" required="true" style="width:500px;height:200px;"><{$info.data|default:''}></textarea>
                </td>
            </tr>
            <tr>
                <td class="td1"></em>描述：</td>
                <td>
                    <textarea cols="40" rows="10" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}> class="sel" name="query[description]" style="width:500px;height:200px;margin-top: 20px;"><{$info.description|default:''}></textarea>
                </td>
            </tr>
            <{if !isset($action)}>
            <tr>
                <td class="td1">删除状态：</td>
                <td>
                    <{$info.is_del|default:0|replace:0:'未删除'|replace:1:'删除'}>
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
