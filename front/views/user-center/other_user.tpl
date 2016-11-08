<div class="personcenter">
    <div class="userhead">
        <img src="<{$userInfo.avatar}>">
    </div>
    <div class="userinfo">
        <div class="usernikename">
            <span><{$userInfo.nick_name|default:''}></span>
        </div>
    </div>
</div>
<div class="otherbox">
    <div class="othernav">
        <div class="each">
            <a href="/user-center/order-list?user_id=<{$userInfo.user_id}>" <{if $curUrl eq '/user-center/order-list'}>class="on"<{/if}> style="border-left: 0;">夺宝记录</a>
            <a href="/user-center/indianaed-list?user_id=<{$userInfo.user_id}>" <{if $curUrl eq '/user-center/indianaed-list'}>class="on"<{/if}>>中奖记录</a>
            <a href="/user-center/display-order-list?user_id=<{$userInfo.user_id}>" <{if $curUrl eq '/user-center/display-order-list'}>class="on"<{/if}>>晒单记录</a>
        </div>
    </div>