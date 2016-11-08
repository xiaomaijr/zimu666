$(function () {
   //焦点图
    var Focus_lunbo=jQuery(".leftLoop").slide({ mainCell:".bd ul",effect:"leftLoop",vis:1,scroll:1,autoPlay:true});
    $('.allshopposition').show();
    //全部商品分类
    $('.allclick').hover(function () {
        $('.allshopposition').show();
    },function () {
        $('.allshopposition').show();
    });
    setInterval(function () {
        $('.scrollshare').animate({'margin-top':-133},500,function () {
            $('.scrollshare').css('margin-top',0);
            $('.sharebox:first').insertAfter($('.sharebox:last'));
        });
    },2500);

    //加入购物车
    $('.spricebtn em' ).on('click', function(){
        shopping($(this));
    });
    //立即一元购
    $('.spricebtn i' ).on('click', function(){
        shopping($(this));
    });
});
var maxtime =60*1000*10;//10分钟
function CountDown(){
    if(maxtime>=0){
        var minutes = Math.floor(maxtime/60/1000);
        var seconds = Math.floor(maxtime/1000%60);
        var hm = Math.floor(maxtime%100);
        if(minutes<10){
            minutes='0'+minutes;
        }else if(minutes<1){
            minutes='00';
        }
        if(seconds<10){
            seconds='0'+seconds;
        }else if(seconds<1){
            seconds='00';
        }
        if(hm<10){
            hm='0'+hm;
        }else if(hm<1){
            hm='00';
        }
        var msg = "揭晓倒计时"+minutes+":"+seconds+":"+hm;
        maxtime=maxtime-10;
        $('.jxlist em').text(msg)
    }
    else{
    }
}
timer = setInterval("CountDown()",10);

function shopping(obj)
{
    var goodId = obj.parents('.spricebtn').attr('item-id');
    var url = '/shop-cart/add';
    $.ajax({
        url     :       url,
        type    :       'post',
        data    :       'indiana_good_id=' + goodId + '&num=1',
        dataType:       'json',
        success :       function(data){
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