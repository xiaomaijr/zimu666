<!DOCTYPE html >
<!--STATUS OK-->
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" href="/static/lib/jquery-ui-1.10.4/css/redmond/jquery-ui-1.10.4.custom.min.css" />
    <link rel="stylesheet" href="/static/risk/css/common.css?v={$smarty.const.STATIC_VERSION}" />
    <link rel="stylesheet" href="/static/risk/css/validationEngine.jquery.css" />
    {*<script src="/static/common/js/jquery-1.7.2.min.js" type="text/javascript"></script>*}
    <script src="/static/common/js/jquery-1.8.2.min.js" type="text/javascript"></script>
    {block name="css-common"}{/block}
</head>
<body style="margin:0">
    {block name="content"}{/block}
    <script src="/static/lib/jquery-ui-1.10.4/js/jquery-ui-1.10.4.custom.min.js" type="text/javascript"></script>
    <script src="/static/lib/jquery-plugins/jquery.select-1.3.6.js" type="text/javascript"></script>
    <script src="/static/lib/jquery-plugins/jquery-ui-timepicker-addon.js" type="text/javascript" ></script>
    <script src="/static/risk/js/common.js?v={$smarty.const.STATIC_VERSION}" type="text/javascript"></script>
    <script src="/static/risk/js/cookie.js" type="text/javascript"></script>
    {block name="js-common"}{/block}
    <script>
    (function(){
        //调整窗口大小, 保证第一时间展示.
        function resize(){
            if(window.parent && window.parent.dialog){
                var size = {
                    width: $('div:first',document.body).outerWidth(true),
                    height:$('div:first',document.body).outerHeight(true)
                }
                window.parent.resizeDialog(size);
            }
        }
        resize();
        $(resize);
        window._resizeFrame = resize;
    })();
    </script>
</body>
</html>
