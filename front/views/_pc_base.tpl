<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>欢乐购</title>
    <link rel="stylesheet" type="text/css" href="/css/public/reset.css" />
    <link rel="stylesheet" type="text/css" href="/css/public/public.css" />
</head>
<body>
<input type="hidden" id="user-login" value="<{$smarty.session.USER_ID|default:0}>"/>

<{block name="css"}><{/block}>

<{include file="header.tpl"}>
<{include file="headmain.tpl"}>

<{block name="content"}><{/block}>

<{include file="footer.tpl"}>

<script language="JavaScript" type="text/javascript" src="/js/public/jquery-min.js"></script>
<script language="JavaScript" type="text/javascript" src="/js/public/public.js"></script>
<script language="JavaScript" type="text/javascript" src="/js/public/scroll.js"></script>
<{block name="script"}><{/block}>
</html>

