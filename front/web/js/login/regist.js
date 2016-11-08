var newMobile = /^1[3-9]\d{9}$/;//大陆手机
var isClick = false;
$(function(){
    $('.loginbtn a').click(function(){
        if (isClick) {
            return false;
        }
        var username= $.trim($('#reusername').val()),
            codemsg=$.trim($('#codemsg').val()),
            repwd= $.trim($('#repwd').val()),
            repwdre= $.trim($('#repwdre').val());
        if(username==''){
            $('.loginbox p').text('手机号不能为空!');
            $('#reusername').focus();
            return false;
        }
        $('.loginbox p').text('');
        if(!newMobile.test(username)){
            $('.loginbox p').text('手机号格式不正确!');
            $('#reusername').focus().select();
            return false;
        }
        $('.loginbox p').text('');
        if(codemsg==''){
            $('.loginbox p').text('验证码!');
            $('#codemsg').focus();
            return false;
        }
        $('.loginbox p').text('');
        if(repwd==''){
            $('.loginbox p').text('密码不能为空!');
            return false;
        }
        $('.loginbox p').text('');
        if(repwd.length<6){
            $('.loginbox p').text('密码不能少于六位数!');
            return false;
        }
        $('.loginbox p').text('');
        if(repwdre!=repwd){
            $('.loginbox p').text('密码和重复密码不一致!');
            return false;
        }
        $('.loginbox p').text('');
        isClick = true;
        $.ajax({
            url     :       '/user-passport/register',
            data    :       {"account" : username, "auth_code" : codemsg, "password" : repwd, "confirm_password" : repwdre},
            type    :       'post',
            dataType:       'json',
        }).done(function(data){
            isClick = false;
            if (data.code === 0) {
                window.location.href = data.data.backUrl;
                return true;
            }
            $('.loginbox p').text(data.message);
        }).fail(function(){
            isClick = false;
            $('.loginbox p').text(data.message);
        });
    });
    //获取验证码
    $('.fibox span').click(function () {
        var username= $.trim($('#reusername').val());
        if(username==''){
            $('.loginbox p').text('手机号不能为空!');
            $('#reusername').focus();
            return false;
        }
        $('.loginbox p').text('');
        if(!newMobile.test(username)){
            $('.loginbox p').text('手机号格式不正确!');
            $('#reusername').focus().select();
            return false;
        }
        $('.loginbox p').text('');
        $.ajax({
            url     :       '/user-passport/get-code',
            data    :       {"account" : username},
            type    :       'post',
            dataType:       'json',
        }).done(function(data){
            if (data.code === 0) {
                $('.loginbox p').text('验证码已发送至手机，请查阅！');
                var time=60;
                $('.fibox span').hide();
                $('.fibox b').show().text(time+'s重新获取');
                $('.fibox span').hide();
                var stime=setInterval(function () {
                    time--;
                    if(time>0){
                        $('.fibox span').hide();
                        $('.fibox b').show().text(time+'s重新获取');
                    }else{
                        $('.fibox span').show().text('重新获取');
                        $('.fibox b').hide();
                        clearInterval(stime);
                    }
                },1000);
                return false;
            }
            $('.loginbox p').text(data.message);
        }).fail(function(){
            $('.loginbox p').text(data.message);
        });

    });
});