<{extends file="_pc_base.tpl"}>
<{block name="css"}>
<link rel="stylesheet" type="text/css" href="/css/login/login.css" />
<{/block}>
<{block name="content"}>
    <div class="loginbox">
        <div class="logintitle">登陆</div>
        <div class="fibox">
            <label for="reusername">手机号</label>
            <input type="text" name="" id="reusername" maxlength="32" placeholder="请输入用户名" />
        </div>
        <div class="fibox code">
            <label for="codemsg">验证码</label>
            <input type="text" name="" id="codemsg" placeholder="请输入验证码" />
            <span>获取验证码</span>
            <b>59s重新获取</b>
        </div>
        <input type="hidden" class="back-url" value="<{$params.back_url|default:'/'}>"/>
        <div class="loginbtn"><a href="javascript:void(0)">登录</a></div>
    </div>
<{/block}>
<{block name="script"}>
    <script src="/js/login/login.js" language="JavaScript" type="text/javascript"></script>
<{/block}>

