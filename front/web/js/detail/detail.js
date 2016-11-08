$(function () {
    //登陆
    $('.ljdb,.adsc').click(function () {
        var isLogin = $('#user-login').val();
        if (isLogin == 0) {
            $('.openloginbox,.detaillogin').show();
            return false;
        }
        dbNum = $('.shopnum').find('input').val();
        if (dbNum == 0 || typeof dbNum == undefined) {
            return false;
        }
        var goodId = $('.shopnum').attr('item-id');
        $.ajax({
            url     :       '/shop-cart/add',
            type    :       'post',
            data    :       'indiana_good_id=' + goodId + '&num=' + dbNum,
            dataType:       'json',
            success :       function(data){
                if (data.code !== 0) {
                    window.alert('加入购物车失败');
                    return false;
                }
                window.location.href = '/shop-cart/list';
            }
        });
    });
    //-
    $('.reduce').each(function(){
        $(this).click(function(){
            var num= parseInt($.trim($(this).siblings('input').val()));
            if(num>2){
                $(this).siblings('input').val(num-1);
            }else{
                $(this).siblings('input').val(1);
                $(this).addClass('on');
                return false;
            }
        });
    });
    //+
    $('.plus').each(function(){
        $(this).click(function(){
            var num= parseInt($.trim($(this).siblings('input').val()));
            $(this).siblings('input').val(num+1);
            $(this).siblings('.reduce').removeClass('on');
        });
    });
    //smallpic
    $('.smallpic span').hover(function () {
        $('.bigpic img').attr('src',$(this).find('img').attr('src'));
        $('.smallpic span').removeClass('on');
        $(this).addClass('on');

    });
    //切换
    $('.titleborder span').each(function (i) {
        $(this).click(function () {
            $('.titleborder span').removeClass('on');
            $(this).addClass('on');
            $('.contlist').hide().eq(i).show();
        });
    });
    //所有夺宝号码
    $('.listright p').each(function (i) {
        $(this).hover(function () {
            $(this).addClass('on');
        },function () {
           $(this).removeClass('on');
        });
    });
    $('.listright p b').click(function () {
        if(!$(this).parents('.listright').hasClass('on')){
            $(this).parents('.listright').addClass('on');
        }else{
            $(this).parents('.listright').removeClass('on');
        }
    })
});