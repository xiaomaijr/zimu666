<{extends file="_pc_base.tpl"}>
<{block name="css"}>
<link rel="stylesheet" type="text/css" href="/css/epay/recharge.css"/>
<{/block}>
<{block name="content"}>
<!--content-->
    <div class="content">
        <div class="czbox">
            <div class="paytitle">
                <span>充值金额</span>
            </div>
            <div class="payway">
                <div class="wayselect">
                    <input type="radio" name="price" id="" checked="checked">
                    <span>20元</span>
                </div>
                <div class="wayselect">
                    <input type="radio" name="price" id="">
                    <span>50元</span>
                </div>
                <div class="wayselect">
                    <input type="radio" name="price" id="" >
                    <span>100元</span>
                </div>
                <div class="wayselect">
                    <input type="radio" name="price" id="">
                    <span>200元</span>
                </div>
                <div class="wayselect other">
                    <input type="radio" name="price" id="">
                    <span>其他金额</span>
                    <input type="text" name="" value="" placeholder="请输入充值金额">
                </div>
            </div>
            <div class="czway">
                <div class="paytitle">
                    <span>支付方式</span>
                </div>
                <div class="payway">
                    <div class="wayselect">
                        <input type="radio" name="pay" id="" value="1" checked="checked" />
                        <span>易宝支付</span>
                    </div>
                </div>
            </div>
            <div class="czbtn">
                <a href="javascript:void(0)">充值</a>
            </div>
        </div>
    </div>
<!--content end-->
<!--支付宝回调弹层-->
    <div class="openbox" style="display: block">
        <div class="openboxbg"></div>
        <div class="zfb_open" style="display: block;">
            <div class="opentitle">
                <a href="javascript:void(0)" class="closebox"></a>
            </div>
            <div class="zfbcontent">
                <p>请您在新打开的页面上完成付款。</p>
                <span>付款完成前请不要关闭此窗口<br />完成付款后请根据您的情况点击下面的按钮</span>
                <div class="zfbbtn_box">
                    <a href="javascript:void(0)" class="fl">已完成付款</a>
                    <a href="javascript:void(0)" class="fr">付款遇到问题</a>
                </div>
                <em>返回选择其他支付方式</em>
            </div>
        </div>
    </div>
<!--支付宝回调弹层 end-->
<!--content end-->
<{/block}>
<{block name="script"}>
<script type="text/javascript" language="javascript" src="/js/epay/recharge.js"></script
<{/block}>