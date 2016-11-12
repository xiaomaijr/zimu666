<{extends file="_pc_base.tpl"}>
<{block name="css"}>
<link rel="stylesheet" type="text/css" href="/css/product/product.css"/>
<{/block}>
<{block name="content"}>
<!--content-->
    <div class="content">
        <div class="mianbaoxie">
            <span>当前位置:</span>
            <a href="javascript:void(0)">首页</a>
            <label>&gt;</label>
            <a href="javascript:void(0)">全部商品</a>
            <label>&gt;</label>
            <label>分类1</label>
        </div>
        <div class="titlebox">
            <div class="producttitle">
                <label>分类</label>
                <div class="protitlebox">
                    <{foreach $categoryMaps as $id => $category}>
                    <p><a href="<{$params.categoryUrl}>&category_id=<{$id}>" <{if $id eq $params.categoryId}>class="on"<{/if}>><{$category.name}></a></p>
                    <{/foreach}>
                </div>
            </div>
            <{*<div class="producttitle">
                <label>品牌</label>
                <div class="protitlebox brand">
                    <p><a href="javascript:void(0)" class="on">全部品牌</a></p>
                    <p><a href="javascript:void(0)">品牌1</a></p>
                    <p><a href="javascript:void(0)">品牌1</a></p>
                    <p><a href="javascript:void(0)">品牌1</a></p>
                    <p><a href="javascript:void(0)">品牌1</a></p>
                    <p><a href="javascript:void(0)">品牌1</a></p>
                    <p><a href="javascript:void(0)">品牌1</a></p>
                    <p><a href="javascript:void(0)">品牌1</a></p>
                    <p><a href="javascript:void(0)">品牌1</a></p>
                    <p><a href="javascript:void(0)">品牌1</a></p>
                </div>
                <div class="viewbrand">展开</div>
            </div>*}>
        </div>
        <!--box title-->
        <div class="productselect">
            <{foreach $orderByMaps as $col => $brief}>
            <a href="<{$params.orderUrl}>&orderBy=<{$col}>" class="last <{if $col eq $params.orderBy}>on<{/if}>"><{$brief}><i></i></a>
            <{/foreach}>
        </div>
        <!--box title end-->
        <div class="product">
            <!--list-->
            <{if !empty($products)}>
            <{foreach $products as $product}>
            <div class="productbox">
                <a href="/mall/detail?id=<{$product.id}>">
                    <div>
                        <img src="<{$product.image}>">
                        <span><{$product.name}> 第<{$indianaGoods[$product.id]['issue']}>期</span>
                        <label>总需人次:<{$product.total_inputs}></label>
                        <div class="sprice">
                            <b></b>
                        </div>
                        <div class="sprice_user">
                            <b><{$indianaGoods[$product.id]['involved_num']}></b>
                            <strong><{$product.total_inputs - $indianaGoods[$product.id]['involved_num']}></strong>
                        </div>
                        <div class="sprice_xx">
                            <b>已参与人次</b>
                            <strong>剩余人次</strong>
                        </div>
                    </div>
                    <div class="spricebtn" item-id="<{$indianaGoods[$product.id].id}>">
                        <i>立即一元购</i>
                        <em></em>
                    </div>
                </a>
            </div>
            <{/foreach}>
            <{/if}>
            <!--list end-->
        </div>
        <div class="clear"></div>
        <div class="pagelist">
            <{$pageing}>
            <{*<span class="prevpage on">&lt;上一页</span>
            <a href="javascript:void(0)" class="on">1</a>
            <a href="javascript:void(0)">2</a>
            <a href="javascript:void(0)">3</a>
            <a href="javascript:void(0)">4</a>
            <b>...</b>
            <a href="javascript:void(0)">9</a>
            <span class="nextpage">下一页&gt;</span>*}>
        </div>
    </div>
<!--content end-->
<{/block}>
<{block name="script"}>
    <script src="/js/product/product.js" language="JavaScript" type="text/javascript"></script>
<{/block}>

