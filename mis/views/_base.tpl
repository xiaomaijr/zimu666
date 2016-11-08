<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><{block name="title"}>子苜后台管理中心<{/block}></title>

    <link rel="stylesheet" type="text/css" href="/assets/css/reset.css"/>
    <link rel="stylesheet" type="text/css" href="/assets/css/admin.css"/>
    <script src="/static/common/js/jquery-1.8.2.min.js" type="text/javascript"></script>
    <script src="/static/common/js/laydate/laydate.js" type="text/javascript"></script>
    <{block name="script"}><{/block}>
    <{block name="css-common"}><{/block}>
</head>
<body>
        <{include file="header.tpl"}>
        <!----右侧----->
        <div class="right">
            <div class="top">
                <a href="/site/logout">退出登录</a>
                <span><{$smarty.session.money_user_name}>,欢迎来到子苜后台管理中心!</span>
            </div>
            <div class="content">
                <{block name="content"}><{/block}>
            </div>
            <{include file="footer.tpl"}>
        </div>
        <!----右侧 end----->

    <script src="/static/lib/jquery-ui-1.10.4/js/jquery-ui-1.10.4.custom.min.js" type="text/javascript"></script>
    <script src="/static/lib/jquery-plugins/jquery.select-1.3.6.js" type="text/javascript"></script>
    <script src="/static/lib/jquery-plugins/jquery-ui-timepicker-addon.js" type="text/javascript" ></script>
    <script src="/static/risk/js/common.js?v=<{$smarty.const.STATIC_VERSION}>" type="text/javascript"></script>
    <script src="/static/risk/js/cookie.js" type="text/javascript"></script>
    <script src="/assets/js/admin.js" type="text/javascript"></script>
    <{block name="js-common"}><{/block}>
</body>
</html>
