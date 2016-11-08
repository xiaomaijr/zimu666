<!doctype html>
<head>
    <meta charset=utf-8>
    <title>{block name="title"}{$title|default:''}{/block}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <!-- Define a description for better SEO result -->
    <meta name="description" content="{$description|default:'买宝马，0首付，宝马直接开回家'}">
    <meta name="keywords" content="{$keywords|default:''}">
    <link rel="stylesheet" href="/assets/css/app.css" data-inline>
    <link rel="shortcut icon" href="/favicon.ico">
</head>

{include file="header.tpl"}

{block name="css-common"}{/block}

{block name="content"}{/block}

{include file="footer.tpl"}


<script src="/assets/js/vendor/bowser.min.js"></script>
<script src="/assets/js/vendor/jquery.min.js"></script>
<script src="/assets/js/vendor/jquery.waypoints.min.js"></script>
<script src="/assets/js/vendor/bootstrap.min.js"></script>
<script src="/assets/js/vendor/swiper.jquery.min.js"></script>
<script src="/assets/js/vendor/jquery.cookie.js"></script>
<script src="/assets/js/app.js"></script>

{block name="script"}{/block}

<script>
    var _hmt = _hmt || [];
    (function() {
        var hm = document.createElement("script");
        hm.src = "//hm.baidu.com/hm.js?28b51bc33cffaa9efa2aea9ccdc72f7a";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();
</script>
<div style="display:none">
    <script language="javascript" type="text/javascript" src="http://js.users.51.la/18592749.js">
    </script>
    <noscript>
        <a href="http://www.51.la/?18592749" target="_blank">
            <img alt="&#x6211;&#x8981;&#x5566;&#x514D;&#x8D39;&#x7EDF;&#x8BA1;" src="http://img.users.51.la/18592749.asp" style="border:none" />
        </a>
    </noscript>
</div>