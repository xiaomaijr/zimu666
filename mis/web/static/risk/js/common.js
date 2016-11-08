curdialog = null;

var timeout         = 500;
var closetimer      = 0;
var ddmenuitem      = 0;

$(document).ready(function(){
    $('.dialog').click(function(){
        curdialog = $('#' + $(this).attr('tid')).dialog({
            modal: true,
            width: $('#' + $(this).attr('tid')).attr('width'),
            height: $('#' + $(this).attr('tid')).attr('height')
        });
    });
    $('.sel').each(function(){
        var width = $(this).attr('width') ? parseInt($(this).attr('width')) : 100;
        $(this).sSelect({minWidth : width});
    });
    $('button').button();
    $('.radio').buttonset();
    mydialog();
    datepicker();
	datetimepicker();
    menu();
    $('#jsmenu>li>a').click(function () {
        location.href=$(this).find('input').val();
    });
    //排序
    ListSortMethod($('#orderbycolumn').val(),$('#sortway').val());
    //表单验证
    if ($('.validateForm').length > 0)
    {
        $.getScript("/static/lib/jquery-plugins/jquery.validationEngine.js", function(){
            $.getScript("/static/lib/jquery-plugins/jquery.validationEngine-zh_CN.js", function(){
                $('.validateForm').validationEngine();
            });
        });
        $('.sel').each(function(){
            $(this).show().css("position", "absolute").css("z-index", "-10");
        });
    }
    //数字文本框
    if ($('.num').length > 0)
    {
        $('.num').each(function(event){
            $(this).keypress(function(event) {
                var keyCode = event.which;
                if (keyCode == 45 || keyCode == 46 || keyCode == 0 || keyCode == 8 || (keyCode >= 48 && keyCode <=57))
                    return true;
                else
                    return false;
            }).focus(function() {
                this.style.imeMode='disabled';
            });
        });
    }
});
function mydialog()
{
    $('.mydialog').each(function(){
        $(this).click(function(){
            var url = $(this).attr('href');
            var title = $(this).attr('title');
            var obj_dialog = $('#dialog').length ? $('#dialog') : $('<div id="dialog"></div>');
            var dialog = obj_dialog
                .html('<iframe style="border: 0px; " src="' + url + '" width="100%" height="100%" name="dialog_frame"></iframe>')
                .dialog({
                    autoOpen: false,
                    modal: true,
                    title: title,
                });
            dialog.dialog('open');
            $('#dialog iframe').after('<div class="dialog-loading"><img src="/static/risk/img/loading.gif" /></div>');
            curdialog = dialog;
            return false;
        });
    });
}
function closeDialog()
{
    curdialog.dialog("close");
}
function resizeDialog(size)
{
    //resize
    $('#dialog').width(size.width);
    $('#dialog').height(size.height);
    $('#dialog').parent().width(size.width+25);

    //remove loading
    $('#dialog .dialog-loading').remove();

	//position center
	positioneDialog();
}

function positioneDialog()
{
    var div = $("#dialog").parent();
    var winHeight = $(window).height();
    var winWidth = $(window).width();
    var divHeight = div.height();
    var divWidth = div.width();
    var top = (winHeight - divHeight) / 2;
    var left = (winWidth - divWidth) / 2;
	var scrollTop = $(document).scrollTop();
	var scrollLeft = $(document).scrollLeft();
	div.css({'top' : top + scrollTop+"px", left : left + scrollLeft+"px"})
}

function datepicker()
{
    $('.datepicker').each(function(){
        $(this).datepicker({
			changeYear: true,
			changeMonth: true,
			monthNamesShort: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
			dateFormat: 'yy-mm-dd' 
		});
    });
}

function datetimepicker()
{
    $('.datetimepicker').each(function(){
        $(this).datetimepicker({
            showSecond: false,
            changeMonth: true,
            changeYear: true,
            timeFormat: 'HH:mm:ss',
            dateFormat: 'yy-mm-dd'
        });
    });
}

function jsmenu_open()
{
    jsmenu_canceltimer();
    jsmenu_close();
    ddmenuitem = $(this).find('ul').eq(0).css('visibility', 'visible');
}

function jsmenu_close()
{
    if(ddmenuitem)
    {
         ddmenuitem.css('visibility', 'hidden');
    }
}

function jsmenu_timer()
{
    closetimer = window.setTimeout(jsmenu_close, timeout);
}

function jsmenu_canceltimer()
{
    if(closetimer)
    {
        window.clearTimeout(closetimer);
        closetimer = null;
    }
}

function menu()
{
    $('#jsmenu > li').bind('mouseover', jsmenu_open);
    $('#jsmenu > li').bind('mouseout',  jsmenu_timer);
    document.onclick = jsmenu_close;
}

//计算利息
function update_month_payment()
{
    var limit=parseInt($("#approve_recommended_limit").val());//金额
    var term=parseInt($("#approve_recommended_term").val());//期限
    var month_rate = parseFloat($("#product_fee_rate").val());//月管理费
    
    if(limit>0 && term>0 && month_rate>0){
    
        term = parseInt(term);
        limit = parseFloat(limit);
        real_rate = parseFloat(month_rate)* 0.01;
        
        var total_payment = limit * real_rate * term + limit;
        var month_panment = total_payment/term;
        
        $('#month_payment').html(fmoney(month_panment, 2)+"元");
        $('#total_payment').html(fmoney(total_payment, 2)+"元");
    }
}

//格式化钱的金额
function fmoney(s, n)  
{  
    n = n > 0 && n <= 20 ? n : 2;  
    s = parseFloat((s + "").replace(/[^\d\.-]/g, "")).toFixed(n) + "";  
    var l = s.split(".")[0].split("").reverse(),  
    r = s.split(".")[1];  
    t = "";  
    for(i = 0; i < l.length; i ++ )  
    {  
        t += l[i] + ((i + 1) % 3 == 0 && (i + 1) != l.length ? "," : "");  
    }  
    return t.split("").reverse().join("") + "." + r;  
}



//退出
function logout(){
    var params = {};

    $.ajax({
        type: 'POST',
        url: '/site/logout.html',
        success: function(){
            window.parent.location.href="/site/login.html";
        }
    });
}
//list Detele Btn click 2015-12-4 Wangjian
function ListDeteleMethod(obj){
    var url = obj;
        if(confirm('你确定要删除该记录吗？')){
            $.get(
                url,
                function(res){
                    if(res=='success'){
                        alert('删除成功');
                        location.reload();
                    }/*else{
                        //var result = $.parseJSON(res);
                        alert(res.message);
                    }*/
                }
            );
        }
}
//list sort Btn click 2015-12-4 Wangjian
function ListSortMethod(obj,th){
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
        var selectedName=obj;
        if(sortName==selectedName){
            var sortWay = th;
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
}
//自定义筛选条件
function SelectFormMethod(val){
    $('#lockstatus').val(val);
    $("#query_form").submit();
}
//recoverd
function ListRecoverMethod(obj){
    var url = obj;
        if(confirm('你确定要恢复该条记录吗？')){
            $.get(
                url,
                function(res){
                    if(res=='success'){
                        alert('恢复成功!');
                        location.reload();
                    }/*else{
                     //var result = $.parseJSON(res);
                     alert(res.message);
                     }*/
                }
            );
        }
}