$(function () {
    //我的子苜
    $('.top_help span').hover(function () {
        $('.zimume').show();
    },function () {
        $('.zimume').hide();
    });
    //全部商品分类
    $('.allclick').hover(function () {
        $('.allshopposition').show();
    },function () {
        $('.allshopposition').hide();
    });
    //发现子苜
    $('.nav_link>span').hover(function () {
       $('.navposition').show();
    },function () {
        $('.navposition').hide();
    });
    //收藏-收起
    $('.toptip a').click(function () {
        $('.toptip').slideUp('slow');
    });
    $('.search-btn').on('click',function(){
        var query = $('#search_query').val();
        if (query.length < 1) {
            window.alert('请输入搜索词');
            return false;
        }
        window.location.href = '/mall/product?query=' + query;
    });
})