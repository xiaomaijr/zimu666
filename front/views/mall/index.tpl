<{extends file="_pc_base.tpl"}>
<{block name="css"}>
<link rel="stylesheet" type="text/css" href="/css/index/index.css"/>
<{/block}>
<{block name="content"}>
<!----content--->
<!--焦点-->
    <div class="leftLoop">
        <div class="hd">
            <ul class="hd_ul">
                <li class="small_click"></li>
                <li></li>
                <li></li>
                <li></li>
            </ul>
        </div>
        <!--<div class="cut">
            <span class="prev"></span>
            <span class="next"></span>
        </div>-->
        <div class="bd">
            <ul class="picList">
                <{foreach $banners as $banner}>
                <li><a href="<{$banner[1]|default:'/'}>"><img src="<{$banner[0]}>" /></a></li>
                <{/foreach}>
            </ul>
        </div>
    </div>
<!--焦点 end-->
<{if !empty($lattestGoods)}>
    <div class="indextext">
        <em></em>
        <span>最新揭晓</span>
        <label>截止目前共揭晓商品<i>1000</i>个</label>
        <a href="/mall/disclose">更多商品,点击查看>></a>
    </div>
    <div class="jiexiao">
        <{foreach $lattestGoods as $lattestGood}>
        <div class="jxlist">
            <a href="/mall/reward?id=<{$lattestGood.indiana_good_id}>">
                <div class="jxicon"></div>
                <img src="<{$lattestGood.image}>">
                <span><{$lattestGood.name}> 第<{$lattestGood.issue|default:1}>期</span>
                <label>总需人次：<{$lattestGood.total_inputs}></label>
                <em>揭晓倒计时:<{$lattestGood.end_time - time()}></em>
            </a>
        </div>
        <{/foreach}>
    </div>
<{/if}>
<{if !empty($hotGoods)}>
    <div class="indextext">
        <em></em>
        <span>人气推荐</span>
        <a href="/mall/product?page_type=hot">更多商品,点击查看>></a>
    </div>
    <div class="renqi">
        <{foreach $hotGoods as $hotGood}>
        <div class="rqlist">
            <a href="/mall/detail?id=<{$hotGood.id}>">
                <img src="<{$hotGood.image}>">
                <span><{$hotGood.name}> 第<{$indianaHotGoods[$hotGood.id]['issue']}>期</span>
                <label>总需人次:<{$hotGood.total_inputs}></label>
                <div class="sprice">
                    <b></b>
                </div>
                <div class="sprice_user">
                    <b><{$indianaHotGoods[$hotGood.id]['involved_num']}></b>
                    <strong><{$hotGood.total_inputs - $indianaHotGoods[$hotGood.id]['involved_num']}></strong>
                </div>
                <div class="sprice_xx">
                    <b>已参与人次</b>
                    <strong>剩余人次</strong>
                </div>
            </a>
            <div class="spricebtn" item-id="<{$indianaHotGoods[$hotGood.id]['id']}>">
                <i>立即一元购</i>
                <em></em>
            </div>
    </div>
        <{/foreach}>
    </div>
<{/if}>
<{if !empty($newGoods)}>
    <div class="indextext">
        <em></em>
        <span>新品上架</span>
        <a href="/mall/product?page_type=new">更多商品,点击查看>></a>
    </div>
    <div class="newpro">
        <{foreach $newGoods as $newGood}>
        <div class="newprolist">
            <a href="/mall/detail?id=<{$newGood.id}>">
                <img src="<{$newGood.image}>">
                <span>总需人次:<{$newGood.total_inputs}></span>
                <label><{$newGood.name}> 第<{$indianaHotGoods[$newGood.id]['issue']}>期</label>
            </a>
        </div>
        <{/foreach}>
    </div>
<{/if}>
<{if !empty($displayOrders)}>
    <div class="indextext">
        <em></em>
        <span>晒单分享</span>
        <label>截止目前共揭晓商品<i>1000</i>个</label>
        <a href="/member/display-order-list">更多晒单,点击查看>></a>
    </div>
    <div class="index_share">
        <div class="scrollshare">
            <{foreach $displayOrders as $displayOrder}>
            <div class="sharebox">
                <{foreach $displayOrder as $order}>
                <div class="sharelist">
                    <a href="/member/display-order-detail?id=<{$order.id}>">
                        <img src="<{$order.image}>">
                    </a>
                    <div class="sharetext">
                        <i class="shareup"></i>
                        <a href="/member/display-order-detail?id=<{$order.id}>"><{$order.comment}></a>
                        <span>--<{$order.create_time}></span>
                        <i class="sharedown"></i>
                    </div>
                </div>
                <{/foreach}>
            </div>
            <{/foreach}>
        </div>
    </div>
<{/if}>
<!----content end--->
<{/block}>
<{block name="script"}>
    <script src="/js/index/index.js" language="JavaScript" type="text/javascript"></script>
<{/block}>

