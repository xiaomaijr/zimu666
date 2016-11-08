$(function () {
    $(".wayselect input[type='radio']").click(function () {
        if($(this).siblings('input').length>0){
            $(this).siblings('input').show();
        }else{
            $(".wayselect.other input[type='text']").hide();
        }
    });
    //关闭弹层
    $('.closebox,.openboxbg,.zfbcontent em').click(function(){
        $('.zfb_open,.openbox').hide();
    });
});