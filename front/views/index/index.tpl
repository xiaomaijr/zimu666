<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" />
    <meta content="telephone=no" name="format-detection" />
    <title>导航</title>
    <link rel="stylesheet" type="text/css" href="/css/reset.css" />
    <link rel="stylesheet" type="text/css" href="/css/index.css" />
    <script type="text/javascript" src="/js/index.js"></script>
</head>

<body>
<!--导航-->
<div class="menus">
    <{if !empty($categorys)}>
    <ul class="g_clr">
        <{foreach $categorys as $category}>
        <li>
            <a class="link <{if $category.id eq $currentId}>active<{/if}>"  item-id="<{$category.id|default:2}>" href="javascript:void(0);">
                <span class="title"><{$category.name}></span>
                <span class="menu-split"></span>
            </a>
        </li>
        <{/foreach}>
    </ul>
    <{/if}>
</div>
<!--导航 end-->
<div class="content">
    <{if !empty($apps)}>
    <ul class="js_bd">
        <{foreach $apps as $app}>
        <li>
            <a href="<{$app.url}>" style="background:url(<{$app.logo}>) no-repeat 2px -38px;;">
                <span><{$app.name}></span>
            </a>
        </li>
        <{/foreach}>
    </ul>
    <{/if}>
    <{if !empty($others)}>
    <div style="display: block;margin: 0 30px;background: #f7f7f7;height: 1px"></div>
    <ul class="eachlist">
	<{foreach $others as $other}>
        <li>
            <a href="<{$other.url|default:''}>"><{$other.name|default:''}></a>
        </li>
	<{/foreach}>
    </ul>
    <{/if}>
</div>
</body>
</html>