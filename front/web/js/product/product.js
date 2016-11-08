$(function () {
    if($('.protitlebox.brand').height()>96){
        $('.viewbrand').show();
        $('.protitlebox.brand').css('height',96)
    }
    $('.viewbrand').click(function () {
        if($(this).hasClass('on')){
            $(this).removeClass('on').text('展开');
            $('.protitlebox.brand').css('height',96)
        }else{
            $(this).addClass('on').text('收起');
            $('.protitlebox.brand').css('height','auto')
        }
    });
    //选择
    $('.protitlebox a').click(function () {
        $(this).parent('p').siblings().find('a').removeClass('on');
        $(this).addClass('on');
    });
    $('.productselect a').not('.last').click(function () {
        $('.productselect a').removeClass('on');
        $('.productselect a').removeClass('val');
        $(this).addClass('on');
    });
    $('.productselect a:last').click(function () {
        if($(this).hasClass('val')){
            $(this).removeClass('val');
        }else{
            $(this).addClass('val');
        }
        $('.productselect a').removeClass('on');
        $(this).addClass('on');
    });

    //加入购物车
    $('.spricebtn em' ).on('click', function(){
        shopping($(this));
    });
    //立即一元购
    $('.spricebtn i' ).on('click', function(){
        shopping($(this));
    });
});

function shopping(obj) {
    var goodId = obj.parents('.spricebtn').attr('item-id');
    var url = '/shop-cart/add';
    $.ajax({
        url: url,
        type: 'post',
        data: 'indiana_good_id=' + goodId + '&num=1',
        dataType: 'json',
        success: function (data) {
            if (data.code !== 0) {
                window.alert('加入购物车失败');
                return false;
            }
            if (obj.html() == '') {
                window.alert('加入购物车成功!');
                return false;
            } else {
                window.location.href = '/shop-cart/list';
            }
        }
    });
}