<{extends file="_pc_base.tpl"}>
<{block name="css"}>
<link rel="stylesheet" type="text/css" href="/css/user-center/showpic.css"/>
    <{/block}>
<{block name="content"}>

<!--content-->
<div class="content">
    <{include file="other_user.tpl"}>
    <{if !empty($displayOrders)}>
    <div class="othereach">
        <div class="otherlist">
            <{foreach $displayOrders as $order}>
            <div class="viewlist2 other">
                <a href="/member/display-order-detail?id=<{$order.id}>">
                    <img src="<{$order.image}>">
                    <span><{$order.good.name}></span>
                    <label>幸运号码:<em><{$order.luck_number}></em></label>
                    <div class="showtext">
                        <span><{$order.title}></span>
                        <p>
                            <i><{$order.user_name}></i>
                            <em><{$order.create_time}></em>
                        </p>
                        <b><{$order.comment|truncate:100}></b>
                    </div>
                </a>
            </div>
            <{/foreach}>
        </div>
    </div>
    <{/if}>
</div>
    </div>
<!--content end-->
<!--弹层 end-->
    <div class="clear"></div>
    <div class="pagelist">
        <{$paging}>
    </div>
    <{/block}>
<{block name="script"}>
<script type="text/javascript" language="javascript" src="/js/user-center/otherpersonal.js"></script
<{/block}>


