<{extends file="_pc_base.tpl"}>
<{block name="css"}>
<link rel="stylesheet" type="text/css" href="/css/user-center/showpic.css"/>
    <{/block}>
<{block name="content"}>

<!--content-->
<div class="content">
        <{include file="other_user.tpl"}>
        <div class="othereach">
            <div class="otherlist">
                <!--订单列表-->
                <{if !empty($indianaedList)}>
                <div class="orderlistbox">
                    <!--循环列表-->
                    <{foreach $indianaedList as $record}>
                    <div class="orderlist">
                        <div class="orderlist_titile">
                            <label>第<{$record.good.issue}>期</label>
                        </div>
                        <div class="personal_orderlist">
                            <div class="order_list_d">
                                <div class="wid350 fl">
                                    <a href="/mall/detail?id=<{$record.good.id}>" class="pic">
                                        <img src="<{$record.good.image}>" />
                                    </a>
                                    <a href="/mall/detail?id=<{$record.good.id}>">
                                        <span><{$record.good.name}></span>
                                    </a>
                                    <span>本期参与:<{$record.order.num}>次</span>
                                    <span>幸运号码:<label><{$record.luck_number|default:''}></label></span>
                                    <span>揭晓时间:<{$record.create_time}></span>
                                </div>
                            </div>
                        </div>
                        <div class="wid100 fr pd10"><a href="javascript:void(0)">查看更多</a></div>
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


