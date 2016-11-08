<{extends file="_pc_base.tpl"}>
<{block name="css"}>
<link rel="stylesheet" type="text/css" href="/css/user-center/showpic.css"/>
<{/block}>
<{block name="content"}>
<!--content-->
    <div class="content">
            <{include file='other_user.tpl'}>
            <div class="othereach">
                <div class="otherlist">
                    <!--订单列表-->
                    <{if !empty($orderList)}>
                    <div class="orderlistbox">
                        <!--循环列表-->
                        <{foreach $orderList as $order}>
                        <div class="orderlist">
                            <div class="orderlist_titile">
                                <label>第<{$order.good.issue}>期</label>
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
                                        <span>本期参与:<{$order.num}>次</span>
                                        <span>幸运号码:<label><{$indianaedOrder[$order.id]['luck_number']|default:'未揭晓'}></label></span>
                                        <span>揭晓时间:<{$indianaedOrder[$order.id]['create_time']|default:'请等待'}></span>
                                    </div>
                                </div>
                            </div>
                            <div class="wid100 fr pd10"><a href="javascript:void(0)">查看更多</a></div>
                            <div class="wid100 fr"><em><{$order.status}></em></div>
                        </div>
                        <{/foreach}>
                        <!--循环列表 end-->
                    </div>
                    <{/if}>
                    <!--订单列表 end-->
                </div>
            </div>
        </div>
    </div>
<!--content end-->

<div class="openviewmore">
    <div class="deletetipboxbg"></div>
    <div class="viewmorebox">
        <div class="viewtitle">第201010010001期  电信50元充值卡(可充值中国电信话费)</div>
        <div class="viewtextbox">
            <p>参与10次,夺宝号码</p>
            <span>10101010100011</span>
            <span>10101010100011</span>
            <span>10101010100011</span>
            <span>10101010100011</span>
            <span>10101010100011</span>
        </div>
        <div class="closethisbox">
            点击关闭窗口
        </div>
    </div>
</div>

<!--弹层 end-->
    <div class="clear"></div>
    <div class="pagelist">
        <{$paging}>
    </div>
<{/block}>
<{block name="script"}>
<script type="text/javascript" language="javascript" src="/js/user-center/otherpersonal.js"></script
<{/block}>