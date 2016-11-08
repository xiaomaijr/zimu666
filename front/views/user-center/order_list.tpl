<{extends file="_pc_base.tpl"}>
<{block name="css"}>
<link rel="stylesheet" type="text/css" href="/css/user-center/personal_order.css"/>
<{/block}>
<{block name="content"}>
<!--content-->
    <div class="content center">
        <{include file="user.tpl"}>
                <div class="orderlist_titile">
                    <span class="wid350">商品</span>
                    <span class="wid100">夺宝状态</span>
                    <span class="wid100">总价</span>
                    <span class="wid100">参与人次</span>
                    <span class="wid100">夺宝号码</span>
                    <span class="wid125">操作</span>
                </div>
                <!--订单列表-->
                <{if !empty($orderList)}>
                <div class="orderlistbox">
                    <!--循环列表-->
                    <{foreach $orderList as $order}>
                    <div class="orderlist on">
                        <div class="orderlist_titile">
                            <label>第<{$order.issue}>期</label>
                            <b item-id="<{$order.id}>" item-type="order"></b>
                        </div>
                        <div class="personal_orderlist">
                            <div class="order_list_d">
                                <div class="wid350 fl">
                                    <a href="/mall/detail?id=<{$order.good.id}>" class="pic">
                                        <img src="<{$order.good.image}>" />
                                    </a>
                                    <a href="/mall/detail?id=<{$order.good.id}>">
                                        <span><{$order.good.name}></span>
                                    </a>
                                    <span>获得者:<{$indianaedOrder[$order.id]['user']['nick_name']|default:'未揭晓'}></span>
                                    <span>总需:<{$order.good.total_inputs}>次</span>
                                    <span>幸运号码:<label><{$indianaedOrder[$order.id]['luck_number']|default:'未揭晓'}></label></span>
                                    <span>揭晓时间:<{$indianaedOrder[$order.id]['create_time']|default:'请等待'}></span>
                                </div>
                                <div class="wid100 fl"><em><{$order.status}></em></div>
                                <div class="wid100 fl"><em>¥<{$order.total_price|string_format:"%.2f"}></em></div>
                                <div class="wid100 fl"><em><{$order.num|default:0}></em></div>
                            </div>
                        </div>
                        <div class="wid100 fl pd10"><a href="/mall/detail?id=<{$order.good.id}>">查看更多</a></div>
                        <div class="wid125 fl pd10">
                            <{if $order.original_status eq 1}>
                            <a href="/order/pay?id=<{$order.id}>">立即支付</a>
                            <em data-id="<{$order.id}>" class="cancal-order">取消订单</em>
                            <{else}>
                            <a href="/mall/reward?id=<{$order.indiana_good.id}>" class="pdeail">详情</a>
                            <{/if}>
                        </div>
                    </div>
                    <{/foreach}>
                    <!--循环列表 end-->
                </div>
                <{/if}>
                <!--订单列表 end-->
            </div>
        </div>
    </div>
<!--content end-->




<!--删除提示-->
    <div class="deletetipopen">
        <div class="deletetipboxbg"></div>
        <div class="deletetipbox">
            <p>确认删除订单?</p>
            <div class="deleteopenbtn">
                <a href="javascript:void(0)">取消</a>
                <a href="javascript:void(0)" class="deleteover">确认</a>
            </div>
        </div>
    </div>
<!--删除提示 end-->

<!--弹层 end-->
    <div class="clear"></div>
    <div class="pagelist">
        <{$paging}>
    </div>
<{/block}>
<{block name="script"}>
<script type="text/javascript" language="javascript" src="/js/user-center/personal_order.js"></script
<{/block}>