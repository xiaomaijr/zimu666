<?php /* Smarty version 3.1.27, created on 2015-11-04 17:29:58
         compiled from "/home/work/www/jiadao-mall/mis/views/order/order_list.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:2046800645639d016ba9007_69035046%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0d08a7166e321cbeffef2bd8921ff4e630f049f0' => 
    array (
      0 => '/home/work/www/jiadao-mall/mis/views/order/order_list.tpl',
      1 => 1446519414,
      2 => 'file',
    ),
    '370a89e66067ac44ca6bea2e338637ad7180ee9e' => 
    array (
      0 => '/home/work/www/jiadao-mall/mis/views/_base.tpl',
      1 => 1446519414,
      2 => 'file',
    ),
    'df6ff9627ae0a0230b4420fef5f75672a4cac724' => 
    array (
      0 => 'df6ff9627ae0a0230b4420fef5f75672a4cac724',
      1 => 0,
      2 => 'string',
    ),
    '9005a7461018afd583ff0d074941b778ba1eaceb' => 
    array (
      0 => '9005a7461018afd583ff0d074941b778ba1eaceb',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '2046800645639d016ba9007_69035046',
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_5639d016cb8f40_69588913',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5639d016cb8f40_69588913')) {
function content_5639d016cb8f40_69588913 ($_smarty_tpl) {
if (!is_callable('smarty_modifier_date_format')) require_once '/home/work/www/jiadao-mall/vendor/smarty/smarty/libs/plugins/modifier.date_format.php';

$_smarty_tpl->properties['nocache_hash'] = '2046800645639d016ba9007_69035046';
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
$_smarty_tpl->properties['nocache_hash'] = '2046800645639d016ba9007_69035046';
?>

    <style>
        .ui-widget-content .item {
            margin:2px 10px 2px 0;
            float:left;
        }
    </style>
    <h2 class="top10">订单列表</h2>
    <div class="top10 pad10 ui-widget-content">
        <form id="query_form" name="search-form" method="post" action="list" >
            <div class="item"><label>ID：<input type="text" name="query[id]" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['filter']->value['id'])===null||$tmp==='' ? '' : $tmp);?>
" class="inpt"/></label></div>
            <div class="item">
                <label>手机号码：<input type="text" name="query[mobile]" class="inpt" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['filter']->value['mobile'])===null||$tmp==='' ? '' : $tmp);?>
"/></label>
            </div>
            <input type="hidden" id="orderbycolumn" name="order" value="<?php echo $_smarty_tpl->tpl_vars['filter']->value['orderby'];?>
"/>
            <input type="hidden" id="sortway" name="sortway" value="<?php echo $_smarty_tpl->tpl_vars['filter']->value['sortway'];?>
"/>
            <p><button type="submit">查询</button></p>
        </form>
        <a href="/mobilemessage/send"><button value="短信fasong " class="fr" style="margin-top:-29px">短信发送</button></a>
    </div>
    <table class="tb-list top10" cellpadding="0" cellspacing="0">
        <thead>
        <tr>
            
            <th >订单号</th>
            <th class="sort" width="80px" sort-id="user_id">用户</th>
            <th >用户电话</th>
            <th class="sort" width="160px" sort-id="vehicle_type_id">车型名称</th>
            <th >颜色</th>
            <th>价格方案</th>
            <th>城市</th>
            <th >定金费用(元)</th>
            <th>订单状态</th>
            <th class="sort" width="80px" sort-id="create_time">下单时间</th>
            
            
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
                
                <td class="cnt"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['item']->value['order_id'])===null||$tmp==='' ? '' : $tmp);?>
</td>
                <td class="cnt"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['userInfo']->value[$_smarty_tpl->tpl_vars['item']->value['user_id']]['name'])===null||$tmp==='' ? '' : $tmp);?>
</td>
                <td class="cnt"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['item']->value['mobile'])===null||$tmp==='' ? '' : $tmp);?>
</td>
                <td class="cnt"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['item']->value['vehicle_type_name'])===null||$tmp==='' ? '' : $tmp);?>
</td>
                <td class="cnt"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['item']->value['color'])===null||$tmp==='' ? '' : $tmp);?>
</td>
                <td class="cnt"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['item']->value['price_type'])===null||$tmp==='' ? '' : $tmp);?>
</td>
                <td class="cnt"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['item']->value['city'])===null||$tmp==='' ? '' : $tmp);?>
</td>
                <td class="cnt"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['item']->value['order_fee'])===null||$tmp==='' ? '' : $tmp);?>
</td>
                <td class="cnt"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['orderStatus']->value[$_smarty_tpl->tpl_vars['item']->value['status']])===null||$tmp==='' ? '' : $tmp);?>
</td>
                <td class="cnt"><?php echo (($tmp = @smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['create_time'],"%Y-%m-%d %T"))===null||$tmp==='' ? '' : $tmp);?>
</td>
                
                
                
                
                
                <td class="cnt">

                    
                    
                    
                    
                    
                    
                    
                    
                    

                    <?php if ($_smarty_tpl->tpl_vars['item']->value['is_del'] == 0) {?>
                    <a href="#" class="del" data-id="<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
">删除</a>
                        <?php } else { ?>
                    <?php }?>

                    <?php if (isset($_smarty_tpl->tpl_vars['item']->value['status']) && $_smarty_tpl->tpl_vars['item']->value['status'] == 2) {?>
                        |<a href="#" class="confirm" data-id="<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
">客服确认</a>
                    <?php }?>
                    
                    
                    
                    
                        
                    

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
$_smarty_tpl->properties['nocache_hash'] = '2046800645639d016ba9007_69035046';
?>

<?php echo '<script'; ?>
>
$(document).ready(function(){


    $(".del").each(function(){
        var self = this;
        var url = '/order/delete?id='+$(this).attr('data-id');
        $(this).click(function(){
            if(confirm('你确定要删除该记录吗？')){
                $.get(
                        url,
                        function(res){
                            if(res=='success'){
                                alert('删除成功');
                                location.reload();
                            }
                        }
                );
            }
        });
    });

        $('.cancel').click(function(){
            if(confirm('你确定要取消该订单吗？')){
                var self = this;
                if(confirm('是否需要退款?')){
                    $('#overlay').html("<ul style='margin-top: 20%;line-height: 30px;font-size: 16px;font-family: 宋体;'><li>" +
                    "选择取消订单原因:</li> <li><input type='radio' name='reason' value='1'/>用户&nbsp;&nbsp;&nbsp;<input type='radio' name='reason' value='2'/>" +
                    "4S店</li><li><span>退款金额(元):</span><input type='text' id='refund' style='width:80px;'/></li>" + "<li><button id='miscancel'>提交</button></li> </ul>");
                }else{
                    $('#overlay').html("<ul style='margin-top: 20%;line-height: 30px;font-size: 16px;font-family: 宋体;'><li>" +
                    "选择取消订单原因:</li> <li><input type='radio' name='reason' value='1'/>用户</li> <li><input type='radio' name='reason' value='2'/>4S店</li>" +
                    "<li><button id='miscancel'>提交</button></li> </ul>");
                }
                $('#overlay').css('display','block');
                $('#miscancel').click(function(){
                    $('[name=reason]').each(function(){
                        if($(this).attr('checked')){
                            reason = $(this).val();
                        }
                    });
                    if(reason == undefined){
                        alert('请选择取消原因');
                        return false;
                    }
                    refund = $("#refund");
                    if(refund == undefined){
                        param = 'id='+$(self).attr('data-id')+'&reason='+reason;
                    }else{
                        param = 'id='+$(self).attr('data-id')+'&reason='+reason+'&refund_cost='+refund.val();
                    }
                    $.ajax({
                        type: "post",
                        url: '/order/cancel',
                        data: param,
                        success: function(res){
                            result = $.parseJSON(res);
                            if(result.code == 0){
                                alert('取消成功');
                                location.reload();
                            }else{
                                alert('取消失败');
                                return false;
                            }
                        }
                    });

                });
                return false;
            }
        });

    $(".sort").each(function(){
        $(this).click(function(){
            var newOrderBy = $(this).attr("sort-id");
            var currentOrderBy = $("#orderbycolumn").val();
            if(newOrderBy==currentOrderBy){
                if($("#sortway").val()=="ASC"){
                    $("#sortway").attr("value","DESC");
                }
                else if($("#sortway").val()=="DESC"){
                    $("#sortway").attr("value","ASC");
                }
                else{
                    $("#sortway").attr("value","DESC");
                }
            }
            else{
                $("#orderbycolumn").attr("value",newOrderBy);
                $("#sortway").attr("value","DESC");
            }
            $("#query_form").submit();
        });

        var sortName = $(this).attr("sort-id");
        var selectedName="<?php echo $_smarty_tpl->tpl_vars['filter']->value['orderby'];?>
";
        if(sortName==selectedName){
            var sortWay = "<?php echo $_smarty_tpl->tpl_vars['filter']->value['sortway'];?>
";
            if('DESC'==sortWay){
                $(this).attr("class","sort sort_desc");
            }
            else if('ASC'==sortWay){
                $(this).attr("class","sort sort_asc");
            }
            else{
                $(this).attr("class","sort");
            }
        }
    });
    $('.refund').click(function(){
        if(confirm('确认退款?')){
            $.ajax({
                type     :   'post',
                url      :   '/order/refund',
                data     :   'id='+$(this).attr('data-id'),
                success  :    function(con){
                    content = $.parseJSON(con);
                    if(content.code == 0){
                        alert('退款成功');
                        history.go(0);
                    }else{
                        alert(content.message);
                        return false;
                    }
                }
            });
        }
    });

    $('.confirm').click(function(){
        if(confirm('已跟客户确认?')){
            $.ajax({
                type     :   'post',
                url      :   '/order/confirm',
                data     :   'id='+$(this).attr('data-id'),
                success  :    function(con){
                    content = $.parseJSON(con);
                    if(content.code == 0){
                        alert('确认成功');
                        history.go(0);
                    }else{
                        alert(content.message);
                        return false;
                    }
                }
            });
        }
    });
});
<?php echo '</script'; ?>
>

<div id="overlay"></div>
</body>
</html>
<?php }
}
?>