<{extends file="_pc_base.tpl"}>
<{block name="css"}>
<link rel="stylesheet" type="text/css" href="/css/user-center/personal_order.css"/>
<{/block}>
<{block name="content"}>
<!--content-->
    <div class="content center">
        <{include file="user.tpl"}>

        <{if !empty($displayOrders)}>
                <div class="orderview order">
                    <{foreach $displayOrders as $order}>
                    <div class="viewlist2">
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
                <{/if}>
            </div>
        </div>
    </div>
<!--content end-->




<!--删除提示-->
    <div class="deletetipopen">
        <div class="deletetipboxbg"></div>
        <div class="deletetipbox">
            <p>确认删除地址?</p>
            <div class="deleteopenbtn">
                <a href="javascript:void(0)">取消</a>
                <a href="javascript:void(0)" class="deleteover">确认</a>
            </div>
        </div>
    </div>
<!--删除提示 end-->

<!--弹层-->
    <div class="openbox">
        <div class="openboxbg"></div>
        <!--收货人信息-->
        <div class="addaddress">
            <div class="opentitle">
                <span>新增收货人信息</span>
                <a href="javascript:void(0)" class="closebox"></a>
            </div>
            <div class="adresscontent">
                <div class="adresslist">
                    <label><em>*</em>收货人:</label>
                    <input type="text" name="" id="" value="" />
                </div>
                <div class="adresslist select">
                    <label><em>*</em>所在地区:</label>
                    <span>-请选择-</span>
                    <div class="ad_select">
                        <div class="ad_title">
                            <em>北京</em>
                            <em>北京</em>
                            <p>请选择</p>
                        </div>
                        <div class="ad_city">
                            <b>北京</b>
                            <b>上海</b>
                            <b>南京</b>
                        </div>
                        <div class="ad_area">
                            <b>朝阳</b>
                            <b>朝北</b>
                            <b>朝南</b>
                        </div>
                        <div class="ad_newadd">
                            <b>11</b>
                            <b>222</b>
                            <b>33</b>
                        </div>
                    </div>
                </div>
                <div class="adresslist">
                    <label><em>*</em>详细地址:</label>
                    <textarea></textarea>
                </div>
                <div class="adresslist">
                    <label><em>*</em>手机号码:</label>
                    <input type="text" name="" id="" value="" />
                    <label>固定电话 :</label>
                    <input type="text" name="" id="" value="" />
                </div>
            </div>
            <div class="adressbtn">
                <a href="javascript:void(0)">保存收货人信息</a>
            </div>
        </div>
        <!--收货人信息 end-->
    </div>
<!--弹层 end-->
    <div class="clear"></div>
    <div class="pagelist">
        <{$paging}>
    </div>
<{/block}>
<{block name="script"}>
<script type="text/javascript" language="javascript" src="/js/user-center/personal_order.js"></script
<{/block}>