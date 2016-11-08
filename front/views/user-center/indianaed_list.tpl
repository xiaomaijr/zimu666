<{extends file="_pc_base.tpl"}>
<{block name="css"}>
<link rel="stylesheet" type="text/css" href="/css/user-center/personal_order.css"/>
<{/block}>
<{block name="content"}>
<!--content-->
    <div class="content center">
        <{include file="user.tpl"}>

        <div class="orderlist_titile">
                    <span class="wid650 his">商品</span>
                    <span class="wid100">状态</span>
                    <span class="wid125">操作</span>
                </div>
                <!--订单列表-->
                <{if !empty($indianaedList)}>
                <div class="orderlistbox">
                    <!--循环列表-->
                    <{foreach $indianaedList as $record}>
                    <div class="orderlist">
                        <div class="orderlist_titile">
                            <label>第<{$record.good.issue}>期</label>
                            <b item-id="<{$record.id}>" item-type="indianaed"></b>
                        </div>
                        <div class="personal_orderlist">
                            <div class="order_list_d">
                                <div class="wid350 fl add">
                                    <a href="/mall/detail?id=<{$record.good.id}>" class="pic">
                                        <img src="<{$record.good.image}>" />
                                    </a>
                                    <a href="/mall/detail?id=<{$record.good.id}>">
                                        <span><{$record.good.name}></span>
                                    </a>
                                    <span>获得者:<{$record.user.nick_name|default:$record.user.account}>(本期参与<{$record.order.num}>次)</span>
                                    <span>总需:<{$record.good.total_inputs}>次</span>
                                    <span>幸运号码:<label><{$record.luck_number|default:''}></label></span>
                                    <span>揭晓时间:<{$record.create_time}></span>
                                </div>
                            </div>
                        </div>
                        <div class="wid100 fl pd10"><em><{$orderStatusMap[$record.order.status]}></em></div>
                        <div class="wid125 fl pd10">
                            <{if $record.order.status eq 6}><label item-status="<{$record.order.status}>" item-id="<{$record.order.id}>">确认收货</label><{/if}>
                            <{if $record.order.status eq 7}><label item-status="<{$record.order.status}>" item-id="<{$record.order.id}>">晒单</label><{/if}>
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

<!--晒单弹窗-->
    <div class="vieworderopen">
        <div class="deletetipboxbg"></div>
        <div class="vieworder">
            <div class="opentitle">
                <span>晒单信息</span>
                <a href="javascript:void(0)" class="closebox"></a>
            </div>
            <div class="adresscontent">
                <input type="hidden" class="order-id" value=""/>
                <div class="adresslist">
                    <label><em>*</em>标题:</label>
                    <input type="text" name="title" id="title" value="" />
                </div>
                <div class="adresslist share">
                    <label><em>*</em>分享感受:</label>
                    <textarea name="content" id="content" value=""></textarea>
                </div>
                <div class="adresslist">
                    <label><em>*</em>上传图片:</label>
                    <div class="uploadpic">
                        <input type="hidden" id="image_urls" value=""/>
                        <div class="uploadbtn">
                            <input type="file"  name="upload_image" id="upload_image" value="0">
                            <div class="uploadbtnbor"><i>+</i></div>
                        </div>
                        <div class="historypic">

                        </div>
                    </div>
                </div>
                <button class="display-btn">提交</button>
            </div>

        </div>
    </div>
<!--晒单弹窗 end-->

<!--弹层 end-->
    <div class="clear"></div>
    <div class="pagelist">
        <{$paging}>
    </div>
<{/block}>
<{block name="script"}>
<script type="text/javascript" language="javascript" src="/js/user-center/personal_order.js"></script
<{/block}>