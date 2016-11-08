<?php /* Smarty version 3.1.27, created on 2015-11-04 18:18:59
         compiled from "/home/work/www/jiadao-mall/mis/views/site/login.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:8875186785639db93359c19_04837505%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '001719d4cd335470534fb07503a653b4eb2c0167' => 
    array (
      0 => '/home/work/www/jiadao-mall/mis/views/site/login.tpl',
      1 => 1446629988,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8875186785639db93359c19_04837505',
  'variables' => 
  array (
    'error' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_5639db934220a0_83946895',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5639db934220a0_83946895')) {
function content_5639db934220a0_83946895 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '8875186785639db93359c19_04837505';
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>驾到</title>
    <?php echo '<script'; ?>
 src="/static/common/js/jquery-1.8.2.min.js"><?php echo '</script'; ?>
>
    <style>
        #header h2 {
            float:left;
            margin-top:18px;
            padding-left:10px;
        }
        #header .header {
            width:980px;
            margin: 0 auto;
        }
        #header .logo {
            float:left;
            margin-top:15px;
            width:125px;
            border-right:1px solid #DDD;
        }
        body{
            font-family: "微软雅黑","宋体",arial;
            font-size: 13px;
            color: #575757;
        }
        .tail-bar{
            width: 400px;
        }
    </style>
</head>
<body>
<div id="wrapper">
    <div id="header" style="height:90px">
        <div class="header">
            <div id="logo">

                <span class="logo"><a href="/"><img src="/static/risk/img/logo.png" /></a></span>
                <h2>驾到后台管理</h2>
            </div>
        </div>
    </div>
    <div style="margin:0 auto;width:400px">
        
        <form id="f1" method="post" action="" >
            
            <table width="400px">
                <tr>
                    <td width="70">&nbsp;</td>
                    <td id="errmsg" style="color:#f00">
                        
                            <?php echo (($tmp = @$_smarty_tpl->tpl_vars['error']->value['message'])===null||$tmp==='' ? '' : $tmp);?>

                        
                    </td>
                </tr>
                <tr>
                    <td height="35" align="right">账号：</td><td><input type="text" name="LoginForm[mobile]" id="mobile" /></td>
                </tr>
                <tr>
                    <td height="35" align="right">密码：</td>
                    <td>
                        <input type="password" name="LoginForm[password]"/>
                        <span class="forget-password">
                            
                            <a href="javascript:;" target="_blank" onclick="getPass();">忘记密码？</a>
                        </span>
                    </td>
                </tr>
                
                    
                    
                        
                    
                
                <tr>
                    <td></td>
                    
                </tr>
                <tr>
                    <td></td>
                    <td><button class="btn-next" style="width:100px;height:30px" type="submit">登 录</button></td>
                </tr>
            </table>
        </form>
    </div>
    <div style="margin:50px auto;width:300px;padding-left: 100px">
        <?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0);
?>

    </div>
</div>
<?php echo '<script'; ?>
>
    function checkForm()
    {
        return true;
    }
    function getPass()
    {
        $.post(
                '/admin/forget-pwd',
                'mobile=' + $('#mobile').val(),
                function(result){
                    res = $.parseJSON(result);
            if (res.code == 0)
            {
                alert('请查收短信');
            }
            else
            {
                alert(res.message);
                $('#mobile').focus();
            }
        });
    }
<?php echo '</script'; ?>
>
</body>
</html>
<?php }
}
?>