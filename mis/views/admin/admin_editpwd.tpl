{extends file="_base.tpl"}
<style>
</style>
{block name="content"}
    <h2>修改密码</h2>
    <div id="main" align="center" style="margin-top: 100px;">
        {*{if $Yii->user->hasFlash('saveinfo')}*}
            <div id="saveinfo" style="color: #ff0000"></div>
        {*{/if}*}
        {*<form id="f1" method="post" action="/admin/modify-pwd">*}
        <form id="f1" >
            <input type="hidden" name="edit" value="1" />
            <input type="hidden" name="id" value="{$info.id|default:0}" />
            <table class="tb-form" align="center">
                <tr style="line-height: 40px;">
                    <td class="td1"><em class="required">*</em>原密码：</td>
                    <td><input type="password" name="old_pwd" id="oldpass" size="20" class="validate[required]" /></td>
                </tr>
                <tr style="line-height: 40px;">
                    <td class="td1"><em class="required">*</em>新密码：</td>
                    <td><input type="password" name="new_pwd" id="newpass" size="20" class="validate[required]" /></td>
                </tr>
                <tr style="line-height: 40px;">
                    <td class="td1"><em class="required">*</em>确认新密码：</td>
                    <td><input type="password" name="renew_pwd" id="re-newpass" size="20" class="validate[required]" /></td>
                </tr>
            </table>
            <div align="center" style="margin-top: 20px;"><a href="javascript:;" id="modify">提交</a></div>
        </form>
    </div>
{/block}
{block name="js-common"}
    <script>
        $(document).ready(function(){
            $("#modify").click(function(){
                var form = new FormData($("#f1")[0]);
                $.ajax({
                    type : 'post',
                    url  : '/admin/modify-pwd',
                    data : form,
                    processData : false,
                    contentType : false,
                    success : function(res){
                        result = $.parseJSON(res);
                        if(result.code != 0 ){
                            $("#saveinfo").html(result.message);
                        }else{
                            alert(result.message);
                            location.replace("/site/logout");
                        }
                    }
                });
            });
        });
    </script>
{/block}