<?php /* Smarty version 3.1.27, created on 2015-11-04 18:19:01
         compiled from "/home/work/www/jiadao-mall/mis/views/site/welcome.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:2147533055639db95702396_02012447%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '985bffec9020c3987502dec12f429166ffb0c89f' => 
    array (
      0 => '/home/work/www/jiadao-mall/mis/views/site/welcome.tpl',
      1 => 1446629988,
      2 => 'file',
    ),
    '370a89e66067ac44ca6bea2e338637ad7180ee9e' => 
    array (
      0 => '/home/work/www/jiadao-mall/mis/views/_base.tpl',
      1 => 1446629988,
      2 => 'file',
    ),
    '777af159789b2b7e5a0c9b84843599f0cdfcf76a' => 
    array (
      0 => '777af159789b2b7e5a0c9b84843599f0cdfcf76a',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '2147533055639db95702396_02012447',
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_5639db9578ebd8_85901739',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5639db9578ebd8_85901739')) {
function content_5639db9578ebd8_85901739 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '2147533055639db95702396_02012447';
?>
<!DOCTYPE html >
<!--STATUS OK-->
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>好车驾到电商后台管理系统（beta）</title>
    <link rel="stylesheet" href="/static/lib/jquery-ui-1.10.4/css/redmond/jquery-ui-1.10.4.custom.min.css" />
    <link rel="stylesheet" href="/static/risk/css/common.css?v=<?php echo @constant('STATIC_VERSION');?>
" />
    <link rel="stylesheet" href="/static/risk/css/validationEngine.jquery.css" />
    <?php echo '<script'; ?>
 src="/static/common/js/jquery-1.8.2.min.js" type="text/javascript"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="/static/common/js/laydate/laydate.js" type="text/javascript"><?php echo '</script'; ?>
>
    
    
    <style>
        #overlay{     /*弹出窗口定位在页面中间的样式*/
            position: fixed;
            top: 50%;
            left: 50%;
            width: 300px;
            line-height: 20px;
            height: 200px;
            margin-left: -150px;
            margin-top: -100px;
            background-color: #ffffff;
            text-align: center;
            /* 浮层需要的样式 */
            z-index: 10;
            /* 保持在其他元素上最上面 */
            outline: 9999px solid rgba(0,0,0,0.5);
            display: none;
        }
    </style>
</head>
<body style="margin:0 3px">
<div id="wrapper">
        <?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0);
?>

        
        <div id="main">
            <?php
$_smarty_tpl->properties['nocache_hash'] = '2147533055639db95702396_02012447';
?>

    welcome~!

        </div>
        <?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0);
?>

    </div>
    <?php echo '<script'; ?>
 src="/static/lib/jquery-ui-1.10.4/js/jquery-ui-1.10.4.custom.min.js" type="text/javascript"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="/static/lib/jquery-plugins/jquery.select-1.3.6.js" type="text/javascript"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="/static/lib/jquery-plugins/jquery-ui-timepicker-addon.js" type="text/javascript" ><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="/static/risk/js/common.js?v=<?php echo @constant('STATIC_VERSION');?>
" type="text/javascript"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="/static/risk/js/cookie.js" type="text/javascript"><?php echo '</script'; ?>
>
    
<div id="overlay"></div>
</body>
</html>
<?php }
}
?>