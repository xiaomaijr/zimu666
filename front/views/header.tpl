<!--header-->
<div class="header">
    <div class="toptip">
        <span>一元购子苜商城唯一官方网址(www.zimu666.cn)</span>
        <a href="javacript:void(0)"></a>
    </div>
    <div class="top_help">
        <div class="center">
            <a href="http://wpa.b.qq.com/cgi/wpa.php?ln=2&uin=919292777" target="_blank" class="qqserver">在线客服</a>
            <a href="/epay/recharge" target="_blank">充值</a>
            <span>
                    <a href="javascript:void(0)">个人中心</a>
                    <div class="zimume">
                        <a href="/user-center/order-list">购买记录</a>
                        <a href="/user-center/indianaed-list">中奖记录</a>
                        <a href="/user-center/display-order-list">晒单纪录</a>
                        <a href="/user-center/address-list">地址管理</a>
                    </div>
            </span>
            <{if !empty($smarty.session.USER_ID)}>
            <a href="/user-passport/login-out">退出</a>
            <{else}>
            <a href="/user-passport/login-view">登陆</a>
            <{/if}>
        </div>
    </div>
    <div class="logosearch">
        <a href="javascript:;"><img src="/image/public/logo.png"></a>
        <div class="searchbox">
            <div class="searchinput">
                <input type="text" name="" id="search_query" value="">
                <b>充值卡</b>
                <b>点券</b>
            </div>
            <span class="search-btn"></span>
        </div>
    </div>
    <div class="nav">
        <div class="center">
            <{if !empty($smarty.session.CATEGORYS)}>
            <div class="allclick">
                <span>全部商品分类</span>
                <div class="allshopposition">
                    <{foreach $smarty.session.CATEGORYS as $cate}>
                    <a href="/mall/product?category_id=<{$cate.id}>"><{$cate.name}></a>
                    <{/foreach}>
                </div>
            </div>
            <{/if}>
            <div class="nav_link">
                <a href="/" <{if $smarty.session.CURURL eq '/'}>class="on"<{/if}>>首页</a>
                <a href="/mall/product" <{if $smarty.session.CURURL eq '/mall/product'}>class="on"<{/if}>>全部商品</a>
                <a href="/mall/disclose" <{if $smarty.session.CURURL eq '/mall/disclose'}>class="on"<{/if}>>最新揭晓</a>
                <a href="/member/display-order-list" <{if $smarty.session.CURURL eq '/member/display-order-list'}>class="on"<{/if}>>晒单分享</a>
            </div>
            <div class="shopcar" onclick="window.location.href='/shop-cart/list'">购物车</div>
        </div>
    </div>
</div>
<!--header end-->