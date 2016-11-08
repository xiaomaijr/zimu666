<div class="personal_listbox">
    <div class="personal_left">
        <h3>个人中心</h3>
        <p><a href="/user-center/order-list" <{if $curUrl eq '/user-center/order-list'}>class="on"<{/if}>>购买记录</a></p>
        <p><a href="/user-center/indianaed-list" <{if $curUrl eq '/user-center/indianaed-list'}>class="on"<{/if}>>中奖记录</a></p>
        <p><a href="/user-center/display-order-list" <{if $curUrl eq '/user-center/display-order-list'}>class="on"<{/if}>>晒单记录</a></p>
        <p><a href="/user-center/address-list" <{if $curUrl eq '/user-center/address-list'}>class="on"<{/if}>>地址管理</a></p>
    </div>
    <div class="personal_right">
        <div class="personcenter">
            <div class="userhead">
                <img src="<{$userInfo.avatar}>">
                <input type="file" name="" id="">
            </div>
            <div class="userinfo">
                <div class="usernikename">
                    <input type="text" name="" id="" maxlength="16">
                    <span><{$userInfo.nick_name|default:''}></span>
                    <b>编辑昵称</b>
                </div>
                <div class="userphoennum"><{$userInfo.account}></div>
                <div class="userprice">
                    <span>账户余额:</span>
                    <em><{$userAccount.recharge|string_format:"%.2f"}>元</em>
                    <a href="javascript:void(0)">充值</a>
                </div>
            </div>
        </div>