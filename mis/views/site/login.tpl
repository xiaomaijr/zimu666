<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>子苜</title>
    <script src="/static/common/js/jquery-1.8.2.min.js"></script>
    <style>
        #header h2 {
            float:left;
            margin-top:18px;
            padding-left:10px;
        }
        #header .header {
            width:980px;
            margin: 0 auto;
        }
        #header .logo {
            float:left;
            margin-top:15px;
            width:125px;
            border-right:1px solid #DDD;
        }
        body{
            font-family: "微软雅黑","宋体",arial;
            font-size: 13px;
            color: #575757;
        }
        .tail-bar{
            width: 400px;
        }
    </style>
</head>
<body>
<div id="wrapper">
    <div id="header" style="height:90px">
        <div class="header">
            <div id="logo">

                <span class="logo"><a href="/"><img src="" /></a></span>
                <h2>子苜后台管理</h2>
            </div>
        </div>
    </div>
    <div style="margin:0 auto;width:400px">
        <form id="f1" method="post" action="" >
            <table width="400px">
                <tr>
                    <td width="70">&nbsp;</td>
                    <td id="errmsg" style="color:#f00">
                            <{$error.message|default:''}>
                    </td>
                </tr>
                <tr>
                    <td height="35" align="right">账号：</td><td><input type="text" name="LoginForm[mobile]" id="mobile" /></td>
                </tr>
                <tr>
                    <td height="35" align="right">密码：</td>
                    <td>
                        <input type="password" name="LoginForm[password]"/>
                    </td>
                </tr>
                <tr>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td><button class="btn-next" style="width:100px;height:30px" type="submit">登 录</button></td>
                </tr>
            </table>
        </form>
    </div>
    <div style="margin:50px auto;width:300px;padding-left: 100px">
        <{include file="footer.tpl"}>
    </div>
</div>
<script>
    function checkForm()
    {
        return true;
    }
    function getPass()
    {
        $.post(
                '/admin/forget-pwd',
                'mobile=' + $('#mobile').val(),
                function(result){
                    res = $.parseJSON(result);
            if (res.code == 0)
            {
                alert('请查收短信');
            }
            else
            {
                alert(res.message);
                $('#mobile').focus();
            }
        });
    }
</script>
</body>
</html>
