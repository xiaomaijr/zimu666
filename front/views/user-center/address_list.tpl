<{extends file="_pc_base.tpl"}>
<{block name="css"}>
<link rel="stylesheet" type="text/css" href="/css/user-center/personal_order.css"/>
<{/block}>
<{block name="content"}>
<!--content-->
    <div class="content center">
        <{include file="user.tpl"}>

        <!--地址管理-->
                <{if !empty($addressList)}>
                <{foreach $addressList as $address}>
                <div class="info_box">
                    <i class="delete_adress"></i>
                    <h3><{$address.user_name}> <{$address.city_name|default:'北京'}></h3>
                    <div class="info_detailbox">
                        <label>收货人:</label>
                        <em><{$address.user_name}></em>
                    </div>
                    <div class="info_detailbox">
                        <label>所在地区:</label>
                        <em><{$address.city_name|default:'北京'}> <{$address.district_name|default:'朝阳'}></em>
                    </div>
                    <div class="info_detailbox">
                        <label>详细地址:</label>
                        <em><{$address.address}></em>
                    </div>
                    <div class="info_detailbox">
                        <label>手机号:</label>
                        <em><{$address.mobile}></em>
                    </div>
                    <div class="info_detailbox">
                        <label>固定电话:</label>
                        <em><{$address.call_number}></em>
                    </div>
                    <div class="adress_manger" item-id="<{$address.id}>">
                        <a href="javascript:void(0)" class="adress_modefy">编辑</a>
                        <{if $address.is_default neq 1}>
                        <a href="javascript:void(0)" class="set_adress">设为默认</a>
                        <{/if}>
                    </div>
                </div>
                <!--地址管理 end-->
                <{/foreach}>
                <{/if}>
                <!--地址管理 end-->
                <div class="add_adress_btn">
                    <a href="javascript:void(0)">新增收货信息</a>
                </div>
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
                    <input type="text" name="" id="receiver" value="" />
                </div>
                <div class="adresslist select">
                    <label><em>*</em>所在地区:</label>
                    <span>-请选择-</span>
                    <div class="ad_select">
                        <div class="ad_title">
                            <p>请选择</p>
                        </div>
                        <div class="ad_city">

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
                    <textarea id="address" value=""></textarea>
                </div>
                <div class="adresslist">
                    <label><em>*</em>手机号码:</label>
                    <input type="text" name="" id="phone" value="" />
                    <label>固定电话 :</label>
                    <input type="text" name="" id="call_number" value="" />
                </div>
            </div>
            <div class="adressbtn">
                <a href="javascript:void(0)">保存收货人信息</a>
            </div>
        </div>
        <!--收货人信息 end-->
    </div>
<!--弹层 end-->
<!--content end-->
<{/block}>
<{block name="script"}>
<script type="text/javascript" language="javascript" src="/js/user-center/personal_order.js"></script
<{/block}>