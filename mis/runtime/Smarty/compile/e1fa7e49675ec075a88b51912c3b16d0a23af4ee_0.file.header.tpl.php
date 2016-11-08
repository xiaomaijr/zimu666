<?php /* Smarty version 3.1.27, created on 2015-11-04 18:19:01
         compiled from "/home/work/www/jiadao-mall/mis/views/header.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:19691421775639db9579a493_18120775%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e1fa7e49675ec075a88b51912c3b16d0a23af4ee' => 
    array (
      0 => '/home/work/www/jiadao-mall/mis/views/header.tpl',
      1 => 1446629988,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19691421775639db9579a493_18120775',
  'variables' => 
  array (
    'key' => 0,
    'item' => 0,
    'ca' => 0,
    'has_right' => 0,
    'name' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_5639db957edf01_15978992',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5639db957edf01_15978992')) {
function content_5639db957edf01_15978992 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '19691421775639db9579a493_18120775';
?>
<div id="header">
    <div id="logo"><img src="/static/risk/img/logo.png" /><span>好车驾到电商后台管理系统（beta）</span></div>

    <?php $_smarty_tpl->tpl_vars['user_name'] = new Smarty_Variable($_SESSION['money_user_name'], null, 0);?>
    <ul id="jsmenu">
        <?php
$_from = RiskConfig::$menu;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['item']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
$foreach_item_Sav = $_smarty_tpl->tpl_vars['item'];
?>
            <?php $_smarty_tpl->tpl_vars['has_right'] = new Smarty_Variable(false, null, 0);?>
            <?php if (in_array($_smarty_tpl->tpl_vars['key']->value,$_SESSION['money_user_rights'])) {?>
                <?php $_smarty_tpl->tpl_vars['has_right'] = new Smarty_Variable(true, null, 0);?>
            <?php }?>
            <?php
$_from = $_smarty_tpl->tpl_vars['item']->value['mlist'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['name'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['name']->_loop = false;
$_smarty_tpl->tpl_vars['ca'] = new Smarty_Variable;
foreach ($_from as $_smarty_tpl->tpl_vars['ca']->value => $_smarty_tpl->tpl_vars['name']->value) {
$_smarty_tpl->tpl_vars['name']->_loop = true;
$foreach_name_Sav = $_smarty_tpl->tpl_vars['name'];
?>
                <?php if (in_array($_smarty_tpl->tpl_vars['ca']->value,$_SESSION['money_user_rights'])) {?>
                    <?php $_smarty_tpl->tpl_vars['has_right'] = new Smarty_Variable(true, null, 0);?>
                <?php }?>
            <?php
$_smarty_tpl->tpl_vars['name'] = $foreach_name_Sav;
}
?>

            <?php if ($_smarty_tpl->tpl_vars['has_right']->value) {?>
                <li>
                    <a href="/<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
">
                        <?php echo $_smarty_tpl->tpl_vars['item']->value['title'];?>

                    </a>
                    <ul>
                        <?php
$_from = $_smarty_tpl->tpl_vars['item']->value['mlist'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['name'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['name']->_loop = false;
$_smarty_tpl->tpl_vars['ca'] = new Smarty_Variable;
foreach ($_from as $_smarty_tpl->tpl_vars['ca']->value => $_smarty_tpl->tpl_vars['name']->value) {
$_smarty_tpl->tpl_vars['name']->_loop = true;
$foreach_name_Sav = $_smarty_tpl->tpl_vars['name'];
?>
                            <?php if (in_array($_smarty_tpl->tpl_vars['ca']->value,$_SESSION['money_user_rights'])) {?>
                            <li><a href="/<?php echo $_smarty_tpl->tpl_vars['ca']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['name']->value;?>
</a></li>
                            <?php }?>
                        <?php
$_smarty_tpl->tpl_vars['name'] = $foreach_name_Sav;
}
?>
                    </ul>
                </li>
            <?php }?>
        <?php
$_smarty_tpl->tpl_vars['item'] = $foreach_item_Sav;
}
?>
    </ul>
    <div class="quick-menu">
        当前管理员：<?php echo $_SESSION['money_user_name'];?>
&nbsp;&nbsp;
        <a href="/admin/modify-pwd?id=<?php echo $_SESSION['money_user_id'];?>
">修改密码</a>&nbsp;&nbsp;
        <a href="/site/logout">退出</a>
    </div>
</div>
<?php }
}
?>