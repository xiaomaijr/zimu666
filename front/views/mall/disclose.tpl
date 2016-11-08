<{extends file="_pc_base.tpl"}>
<{block name="css"}>
<link rel="stylesheet" type="text/css" href="/css/index/index.css"/>
    <style type="text/css">
        .jxlist{
            margin: 0 0 20px;
        }
    </style>
<{/block}>
<{block name="content"}>
<!----content--->
<div class="content">
    <{if !empty($lattestGoods)}>
    <div class="jiexiao" style="margin: 50px auto;">
        <{foreach $lattestGoods as $good}>
        <div class="jxlist">
            <a href="/mall/detail?id=<{$good.id}>">
                <div class="jxicon"></div>
                <img src="<{$good.image}>">
                <span><{$good.name}></span>
                <label>总需人次：<{$good.total_inputs}></label>
                <em>揭晓倒计时:<{$good.end_time}></em>
            </a>
        </div>
        <{/foreach}>
    </div>
    <{/if}>
</div>
<!--content end-->
<{/block}>
<{block name="script"}>
    <script src="/js/mall/disclose.js" language="JavaScript" type="text/javascript"></script>
<{/block}>

