$(function () {

    //查看更多
    $('.wid100 a').click(function () {
        $('.openviewmore,.viewmorebox').show();
    })
    //关闭查看更多
    $('.closethisbox,.deletetipboxbg').click(function () {
        $('.openviewmore,.viewmorebox').hide();
    })
})