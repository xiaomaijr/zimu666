<{extends file="_pc_base.tpl"}>
<{block name="css"}>
<link rel="stylesheet" type="text/css" href="/css/member/shopcar.css"/>
    <{/block}>
<{block name="content"}>
<!--content-->
    <div class="content">
        <!--购物车无商品-->
        <{if empty($list)}>
        <div class="shopcar_null">
            <p>购物车空空的~快去看看心仪的商品吧<br /><a href="/mall/product">去购物&nbsp;&nbsp;&gt;</a> </p>
        </div>
        <{else}>
        <!--购物车无商品 end-->
        <!--购物车有商品-->
        <div class="cs_car">
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <th width="4%"></th>
                    <th class="shopname">商品</th>
                    <th class="shopval">商品价值</th>
                    <th>单价</th>
                    <th width="100">数量</th>
                    <th>价格</th>
                    <th width="4%"></th>
                </tr>
                <{foreach $list as $good}>
                <tr>
                    <td><input type="checkbox" value="<{$good.id}>" class="shop-cart-id"/></td>
                    <td>
                        <a href="/mall/detail?id=<{$good.good.id}>">
                            <img src="<{$good.good.image}>" />
                            <span><{$good.good.name}></span>
                            <label><{$good.good.name}></label>
                        </a>
                    </td>
                    <td>¥<{round($good.good.price/100, 2)|string_format:"%.2f"}></td>
                    <td>¥<{round($good.good.min_price/100, 2)|string_format:"%.2f"}></td>
                    <td class="shopnum" item-id="<{$good.id}>">
                        <b class="reduce">-</b>
                        <input type="tel" name="buy_num" id="buy-num" value="<{$good.num}>" />
                        <b class="plus">+</b>
                    </td>
                    <td>¥<{round($good.total_price/100, 2)|string_format:"%.2f"}></td>
                    <td class="deteleshop"><img src="/image/member/del.png"/></td>
                </tr>
                <{/foreach}>
            </table>
        </div>
        <div class="jiesuan">
            <div class="jiesuanlist">
                <label>总计:</label>
                <span>¥<{round($totalPrice/100, 2)|string_format:"%.2f"}></span>
            </div>
            <div class="jiesuanlist">
                <b>账户余额:</b>
                <strong>¥<{$user_account.recharge|string_format:"%.2f"}></strong>
            </div>
        </div>
        <div class="clear"></div>
        <div class="shopcarbtn">
            <a href="javascript:;" class="paybtn">去计算</a>
            <a href="/mall/product" class="goshopbtn">继续购物</a>
        </div>
        <{/if}>
        <!--购物车有商品 end-->

    </div>
<!--content end-->


<!--删除提示-->
    <div class="deletetipopen">
        <div class="deletetipboxbg"></div>
        <div class="deletetipbox">
            <p>确认删除商品?</p>
            <div class="deleteopenbtn">
                <a href="javascript:void(0)">取消</a>
                <a href="javascript:void(0)" class="deleteover">确认</a>
            </div>
        </div>
    </div>
<!--删除提示 end-->
<{/block}>
<{block name="script"}>
    <script src="/js/shop-cart/list.js" language="JavaScript" type="text/javascript"></script>
<{/block}>