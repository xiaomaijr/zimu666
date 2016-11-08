<?php /* Smarty version 3.1.27, created on 2015-11-04 17:29:54
         compiled from "/home/work/www/jiadao-mall/mis/views/sms-bulk/bulklist.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:9929354985639d012d13b20_53737765%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a27592582da164ffff5f40ac4644edb75b560f50' => 
    array (
      0 => '/home/work/www/jiadao-mall/mis/views/sms-bulk/bulklist.tpl',
      1 => 1446519414,
      2 => 'file',
    ),
    '370a89e66067ac44ca6bea2e338637ad7180ee9e' => 
    array (
      0 => '/home/work/www/jiadao-mall/mis/views/_base.tpl',
      1 => 1446519414,
      2 => 'file',
    ),
    'db6cf23b466350f035a547dc435072c3fc4976ca' => 
    array (
      0 => 'db6cf23b466350f035a547dc435072c3fc4976ca',
      1 => 0,
      2 => 'string',
    ),
    '3e69c709bb24c0b0c3f67b11c19a071ca2a8b79a' => 
    array (
      0 => '3e69c709bb24c0b0c3f67b11c19a071ca2a8b79a',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '9929354985639d012d13b20_53737765',
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_5639d012e113f6_07781400',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5639d012e113f6_07781400')) {
function content_5639d012e113f6_07781400 ($_smarty_tpl) {
if (!is_callable('smarty_modifier_date_format')) require_once '/home/work/www/jiadao-mall/vendor/smarty/smarty/libs/plugins/modifier.date_format.php';

$_smarty_tpl->properties['nocache_hash'] = '9929354985639d012d13b20_53737765';
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
$_smarty_tpl->properties['nocache_hash'] = '9929354985639d012d13b20_53737765';
?>

    <style>
        .ui-widget-content .item {
            margin:2px 10px 2px 0;
            float:left;
        }
    </style>
    <h2 class="top10">短信任务列表</h2>

        <a href="/sms-bulk/add"><button value="添加任务 " class="fr" style="margin-top:-29px">添加任务</button></a>

    <table class="tb-list top10" cellpadding="0" cellspacing="0">
        <thead>
        <tr>
            
            <th class="sort" width="80px" sort-id="id">任务id</th>
            <th>任务名称</th>
            <th>短信内容</th>
            <th>状态</th>
            <th>数量</th>
            <th class="sort" width="80px" sort-id="push_time">发布时间</th>
            <th class="sort" width="80px" sort-id="create_time">创建时间</th>
            
            
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php
$_from = $_smarty_tpl->tpl_vars['adminList']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['item']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
$foreach_item_Sav = $_smarty_tpl->tpl_vars['item'];
?>
            <tr>
                
                <td class="cnt"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['item']->value['id'])===null||$tmp==='' ? '' : $tmp);?>
</td>
                <td class="cnt"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['item']->value['title'])===null||$tmp==='' ? '' : $tmp);?>
</td>
                <td class="cnt"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['item']->value['content'])===null||$tmp==='' ? '' : $tmp);?>
</td>
                <td class="cnt"><?php if ($_smarty_tpl->tpl_vars['item']->value['status'] == 0) {?>正常<?php } else { ?> 已发送<?php }?></td>
                <td class="cnt"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['item']->value['total_num'])===null||$tmp==='' ? '0' : $tmp);?>
</td>
                <td class="cnt"><?php echo (($tmp = @smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['push_time'],"%Y-%m-%d %T"))===null||$tmp==='' ? '' : $tmp);?>
</td>
                <td class="cnt"><?php echo (($tmp = @smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['create_time'],"%Y-%m-%d %T"))===null||$tmp==='' ? '' : $tmp);?>
</td>
                
                
                
                
                
                <td class="cnt">
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                        
                    
                    <?php if (isset($_smarty_tpl->tpl_vars['item']->value['status']) && $_smarty_tpl->tpl_vars['item']->value['status'] == 0) {?>
                        <a href="/sms-bulk/edit?id=<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
" class="confirm" data-id="<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
">修改</a>|
                        <a href="javascript:;" onclick="deleteMessage(<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
)" class="confirm" data-id="<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
" >删除</a>|
                        <a href="/sms-bulk/send-message?id=<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
&push_type=release" onclick="sendMessage(<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
)" class="confirm" data-id="<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
">发送消息</a>|

                    <?php }?>
                        <a href="/sms-bulk/view?id=<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
" class="confirm" data-id="<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
">查看</a>

                </td>
            </tr>
        <?php
$_smarty_tpl->tpl_vars['item'] = $foreach_item_Sav;
}
?>
        </tbody>
    </table>


    <div class="page">
        <?php echo $_smarty_tpl->tpl_vars['arrPager']->value['pagelink'];?>
 共<font color=red><?php echo $_smarty_tpl->tpl_vars['arrPager']->value['count'];?>
</font>个结果
        
    </div>

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
    <?php
$_smarty_tpl->properties['nocache_hash'] = '9929354985639d012d13b20_53737765';
?>

<?php echo '<script'; ?>
>
    function deleteMessage(id){
        $.ajax({
            type: 'get',
            url: '/sms-bulk/delete-message?id='+id,
            processData: false,
            contentType: false,
            success: function (res) {
                var result = $.parseJSON(res);
                alert(result.message);
                window.location.href = "/sms-bulk/list";
            }
        });
    }
    function sendMessage(id){
        $.ajax({
            type: 'get',
            url: '/sms-bulk/send-message?push_type=release&id='+id,
            processData: false,
            contentType: false,
            success: function (res) {
                var result = $.parseJSON(res);
                alert(result.message);
                window.location.href = "/sms-bulk/list";
            }
        });
    }

<?php echo '</script'; ?>
>

<div id="overlay"></div>
</body>
</html>
<?php }
}
?>