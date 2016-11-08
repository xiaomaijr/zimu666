//添加联系人tab
function createContactBlock()
{
    var contact_block_num=$("#contact_tabs").children().length;
    block_id=10*contact_block_num;//为了避免id重复
    
    $.get("/approve/newcontactblock?num="+block_id, function(contact_block){
        var tab_title='<span id="contact-block-title'+block_id+'">/<a class="link" href="###" onclick="showContactInfo('+block_id+')">电话属性'+(contact_block_num-1)+'</a></span>';
        $("#contact_tabs_title").append(tab_title);
        $("#contact_tabs").append(contact_block);
        
        $(".contact-blocks").each(function(){
            $(this).hide();
        });
        $("#tab-borrower").hide();
        $("#contact-block"+block_id).show();
    }); 
}
//删除联系人tab
function removeContactBlock(blockId)
{
    var blockId = parseInt(blockId);
    $("#"+'contact-block'+blockId).remove();
    $("#"+'contact-block-title'+blockId).remove();
    //展示本人tab
    $(".contact-blocks").each(function(){
        $(this).hide();
    });
    $("#tab-borrower").show();
}

function showContactBorrower()
{
    $(".contact-blocks").each(function(){
        $(this).hide();
    });
    $("#tab-borrower").show();
}

function showContactInfo(id)
{
    $(".contact-blocks").each(function(){
        $(this).hide();
    });
    $("#tab-borrower").hide();
    $("#contact-block"+id).show();
}

//添加流水信息录入区域
function createNewBlock()
{
    var flow_block_num=$("#money-flow-blocks").children().length;
    flow_block_num=10*flow_block_num;//为了避免id重复
    
    $.get("/approve/newmoneyflowblock?num="+flow_block_num, function(new_flow_block){
        $("#money-flow-blocks").append(new_flow_block);
        datepicker();//使日期控件的事件生效
        $("#"+flow_block_num+"_n5_value").focus();
    }); 
}
//删除流水区域
function removeFlowBlock(blockId)
{
    var flow_block_num=$("#money-flow-blocks").children().length;
    if(flow_block_num>1){
        var blockId = parseInt(blockId);
        var blockDivId = 'flow_block'+blockId;
        $("#"+blockDivId).remove();
    }
    else{
        alert('不能再删除了，再删就没了！');
    }
}
//计算一个区域的平均流水
function calculateAvgFlow(blockId)
{
    var namePrefix='flow['+blockId+']';
    var sumMoney = 0;
    var checkedItemCount=0;
    if($("#"+blockId+"_n5_check").attr("checked")){
        sumMoney+=parseFloat($("#"+blockId+"_n5_value").val());
        checkedItemCount++;
    }
    if($("#"+blockId+"_n4_check").attr("checked")){
        sumMoney+=parseFloat($("#"+blockId+"_n4_value").val());
        checkedItemCount++;
    }
    if($("#"+blockId+"_n3_check").attr("checked")){
        sumMoney+=parseFloat($("#"+blockId+"_n3_value").val());
        checkedItemCount++;
    }
    if($("#"+blockId+"_n2_check").attr("checked")){
        sumMoney+=parseFloat($("#"+blockId+"_n2_value").val());
        checkedItemCount++;
    }
    if($("#"+blockId+"_n1_check").attr("checked")){
        sumMoney+=parseFloat($("#"+blockId+"_n1_value").val());
        checkedItemCount++;
    }
    if($("#"+blockId+"_n0_check").attr("checked")){
        sumMoney+=parseFloat($("#"+blockId+"_n0_value").val());
        checkedItemCount++;
    }

    if(sumMoney>0){
         var avgFlow = (sumMoney/checkedItemCount);
         avgFlow=avgFlow.toFixed(2);
         $("#"+blockId+"_avg").attr("value",avgFlow);
    }
    else{
         $("#"+blockId+"_avg").attr("value",0);
    }
}

function showImgBak(i)
{
    $("#left_apply_info_div").hide();
    $("#imgsilder").show();
    
    $('#imgsilder').html('<div class="silder" id="silder"><ul class="silder_list" id="silder_list"></ul></div>');
    $('#silder_list').html($('#img' + i).html());
    $('#silder').imgSilder({
        s_width:'100%', //容器宽度
        s_height:280, //容器高度
        is_showTit:false, // 是否显示图片标题 false :不显示，true :显示
        s_times:10000000, //设置滚动时间
        css_link:'/static/risk/css/imgsilder.css'
    });
}

function showImg(i)
{    
    $('#imgsilder').html('<div id="slides"></div>');
    $('#slides').html($('#img' + i).html());
    $('#slides').slidesjs({
        width: 425,
        height: 280
    });
    
    $("#left_apply_info_div").hide();
    $("#imgsilder").show();
}

function showApplyInfo()
{
    $("#imgsilder").hide();
    $("#left_apply_info_div").show();
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
