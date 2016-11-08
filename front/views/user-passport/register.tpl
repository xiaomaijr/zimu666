<{extends file="_pc_base.tpl"}>
<{block name="css"}>
    <link rel="stylesheet" type="text/css" href="/css/login/login.css" />
<{/block}>
<{block name="content"}>
<div class="loginbox">
    <div class="fibox">
        <label for="reusername">手机号</label>
        <input type="text" name="" id="reusername" maxlength="32" placeholder="请输入用户名" />
    </div>
    <div class="fibox code">
        <label for="repwd">验证码</label>
        <input type="text" name="" id="codemsg" placeholder="请输入验证码" />
        <span>获取验证码</span>
        <b>59s重新获取</b>
    </div>
    <div class="fibox">
        <label for="codemsg">密码</label>
        <input type="password" name="" id="repwd" placeholder="请输入账户密码" />
    </div>
    <div class="fibox">
        <label for="repwdre">重复密码</label>
        <input type="password" name="" id="repwdre" placeholder="重复输入账户密码" />
    </div>
    <p></p>
    <div class="forgotpwd">
        <a href="/user-passport/login-view">已有帐号,快速登录</a>
    </div>
    <div class="loginbtn"><a href="javascript:void(0)">注册</a></div>
</div>
<{/block}>
<{block name="script"}>
    <script src="/js/login/regist.js" language="JavaScript" type="text/javascript"></script>
<{/block}>

