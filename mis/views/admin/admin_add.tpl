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
            line-height: 40px;
        }
    </style>
<{/block}>
<{block name="content"}>
    <h3 class="top10"><{if isset($action)}><{if $action eq 1}>编辑<{elseif $action eq 2}>添加<{else}>查看<{/if}><{/if}>用户</h3>
    <form method="post" action="<{if isset($action)&&$action eq 1}><{'/admin/edit'}><{else}><{'/admin/add'}><{/if}>" id="f1" class="user_form">
        <table cellpadding="0" cellspacing="0" class="tb-form top10">
            <tr>
                <td class="td1" style="color:#2E6E9E; font-size:14px; border-bottom:1px solid #2E6E9E;"><span style="padding-right:30px">基本信息</span></td>
                <td style="border-bottom:1px solid #2E6E9E;"></td>
            </tr>
            <tr style="height:5px">
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class="td1"><{if isset($action)}><em class="required">*</em><{/if}>用户姓名：</td>
                <td>
                    <input type="text" name="admin[name]" id="admin_name" value="<{$admin.name|default:''}>" <{if !isset($action)}> style="border-style: none;" <{/if}> class="validate[required]"  required="true" />
                </td>
            </tr>
            <tr>
                <td class="td1"><{if isset($action)}><em class="required">*</em><{/if}>用户手机：</td>
                <td>
                    <input type="text" name="admin[mobile]" id="admin_mobile" value="<{$admin.mobile|default:''}>" <{if !isset($action)}> style="border-style: none;" <{/if}> class="validate[required] mobile"  valid-data="notempty|请输入手机号码||mobile|请输入正确的手机号码" valid="mobile" required="true" cate="num" />
                </td>
            </tr>
            <tr>
                <td class="td1"><{if isset($action)}><em class="required">*</em><{/if}>用户密码：</td>
                <td>
                    <input type="text" name="admin[password]" id="admin_password" value="<{$admin.password|default:''}>" <{if !isset($action)}> style="border-style: none;width:220px;" <{/if}>  <{if isset($action)&&$action eq 1}>readonly="true" <{/if}> class="validate[required] password"  valid-data="notempty|请输入登录密码||password|请输入正确的登录密码" valid="password" required="true" cate="num" />
                </td>
            </tr>

            <tr>
                <td class="td1"><{if isset($action)}><em class="required">*</em><{/if}>联系邮箱：</td>
                <td><input type="text" name="admin[mail]" value="<{$admin.mail|default:''}>" id="admin_mail"  <{if !isset($action)}> style="border-style: none;" <{/if}> class="validate[required] email" valid-data="notempty|请输入邮箱||email|请输入正确的邮箱" valid="email" required="true"/></td>
            </tr>


            <{if isset($action)&&$action eq 1 }>
            <tr>
                <td class="td1">是否重置密码</td>
                <td><input type="checkbox" name="admin[is_reset_pwd]" value="1" id="admin_reset_pwd"  class="" valid-data="" valid=""/></td>
            <tr>
            <{/if}>

            <tr style="height:5px">
                <td colspan="2">&nbsp;</td>
            </tr>
        </table>
        <input type="hidden" name="admin[id]" value="<{if isset($admin.id)}><{$admin.id}><{/if}>">
        <{if isset($action)}>
            <div align="center"><button class="btn" type="submit" value="submit">提 交</button></div>
        <{/if}>
    </form>
<{/block}>
