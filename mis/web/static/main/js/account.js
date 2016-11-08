var regObj={ 
	regForm:$("#regForm"),
	regPhone:$("#regPhone"),
	regFindPhone:$("#regFindPhone"),
	regPwd:$("#regPwd"),
	regAgainPwd:$("#regAgainPwd"),
	regVcode:$("#regVcode"),
	regAgreement:$("#regAgreement"),
	regFreshCode:$("#regFreshCode"),
	regVcodeImg:$("#regVcodeImg"),
	regSubmit:$("#regSubmit"),
	loginForm:$("#loginForm"),
	loginSubmit:$("#loginSubmit"),
	loginUser:$("#loginUser"),
	loginPwd:$("#loginPwd"),
	loginVcode:$("#loginVcode"),
	loginFreshCode:$("#loginFreshCode"),
	loginRemeber:$("#loginRemeber"),
	regMsgVcode:$("#regMsgVcode"),
	regGetMsgVcode:$("#regGetMsgVcode"),
	regValidateMsgVcode:$("#regValidateMsgVcode"),
	phone:$("#phone"),
	validatePhoneClose:$("#validatePhoneClose"),
	changePhone:$("#changePhone"),
	regFindPwdSubmit:$("#regFindPwdSubmit"),
	regResetPwdSubmit:$("#regResetPwdSubmit")
}
var regTip={ 
	regTipPhone:$("#regTipPhone"),
	regTipPwd:$("#regTipPwd"),
	regTipAgainPwd:$("#regTipAgainPwd"),
	regTipVcode:$("#regTipVcode"),
	regTipAgreement:$("#regTipAgreement"),
	loginTipUser:$("#loginTipUser"),
	loginTipPwd:$("#loginTipPwd"),
	loginTipVcode:$("#loginTipVcode"),
	regTipMsgVcode:$("#regTipMsgVcode"),
	proInTip:$("#proInTip")
}
var tipTxt={ 
	regPhone:{ 
		isNormal:"手机号码",
		isNull:"请输入有效手机号",
		findIsNull:"请输入您注册时使用的手机号",
		notEleven:"请输入11位有效手机号",
		isExist:"此号码已被注册，请更换号码或<a target='_blank' href='/zhigou/login'>直接登录</a>",
		isMsgOut:"此号码验证码发送过于频繁，请更换号码或明天再试",
		notRegFindPwd:"此号码尚未注册，无法用于找回密码"
	},
	regPwd:{ 
		isNormal:"密码",
		isNull:"请输入密码",
		isPwdNull:"请先输入密码",
		lessSix:"请设置6~16位密码，需包含字母和数字",
		isSimple:"您输入的密码太简单，请使用字母和数字组合密码",
		notEuqal:"您输入的密码不一致"
	},
	regAgainPwd:{
		isNull:"请再次输入密码",
		lessSix:"请设置6~16位密码，需包含字母和数字",
		notEuqal:"您输入的密码不一致"
	},
	regVcode:{ 
		isNormal:"验证码",
		isNull:"请输入验证码",
		msgIsNull:"请输入短信验证码",
		msgNotSix:"请输入6位数字验证码",
		notFour:"请输入4位验证码",
		msgNotRight:"验证码错误，请核对短信，或重新获取短信验证码",
		notRight:"验证码错误，请重新输入"
	},
	regAgreement:{ 
		isChecked:"同意借款协议后才可投资"
	},
	loginUser:{ 
		isNormal:"手机号/用户名",
		isNull:"请输入手机号/用户名"
	}
}
var error={ 
	serverError:"获取服务器信息错误，请刷新重试"
}
$(function(){ 
	//注册提交，弹出验证手机号浮层
	regObj.regSubmit.click(function(){ 
		var bool=false,
		vcode=$.trim(regObj.regVcode.val()),
		phone=$.trim(regObj.regPhone.val()),
		bool1=checkRegPhone(1),
		bool2=checkRegPwd(),
		bool3=checkRegAgainPwd(),
        bool4=checkRegVcode(vcode,regTip.regTipVcode),
		bool5=checkRegAgreement(),
		bool6=false;
        
		bool=bool1 && bool2 && bool3 && bool4 && bool5;
		if(bool){ 
			checkErrNo=checkRegVcodeAndPhoneRight(vcode,phone);
			if(checkErrNo==0){
				regTip.regTipVcode.html(tipTxt.regVcode.notRight);
				freshVcode();
				regObj.regVcode.val("").focus();
				bool6=false;
			}
			else if(checkErrNo==2){
				regTip.regTipPhone.html(tipTxt.regPhone.isExist);
				regObj.regPhone.next(".tip-ok").hide();
				bool6=false;
			}
			else if(checkErrNo==3){
				bool6=true;
			}
			//bool6=true;
			if(bool6){ 
				PopLayer.iframe('/zhigou/validatePhone?phone='+phone+'&type=1');
			}
		}
	})

	//找回密码，弹出验证手机号浮层
	regObj.regFindPwdSubmit.click(function(){ 
		var bool=false,
		bool1=checkRegPhone(2),
		vcode=$.trim(regObj.regVcode.val()),
		phone=$.trim(regObj.regFindPhone.val()),
		bool2=checkRegVcode(vcode,regTip.regTipVcode),
		bool3=false;
		if(bool1 && bool2){ 
	        checkErrNo=checkRegVcodeAndPhoneRight(vcode,phone);
			if(checkErrNo==0){
				regTip.regTipVcode.html(tipTxt.regVcode.notRight);
				freshVcode();
				regObj.regVcode.val("").focus();
				bool3=false;
			}
			else if(checkErrNo==3){
				regTip.regTipPhone.html(tipTxt.regPhone.notRegFindPwd);
				freshVcode();
				regObj.regVcode.val("").focus();
				bool3=false;
			}
			else if(checkErrNo==2){
				bool3=true;
			}
		}
		if(bool3){ 
			PopLayer.iframe('/zhigou/validatePhone?phone='+phone+'&type=2');
		}

	})

	//注册验证提交
	window.regSubmit = function(){
        //jquery在父页面获取子页面中的对象
        var vcode = $(".dialog").get(0).contentWindow.document.getElementById("regMsgVcode").value;
        $.ajax({
			type:'post',
			url:'/zhigou/register',
			dataType:"json",
			data:regObj.regForm.serialize()+"&captcha="+vcode,
			success:function(result){//callback 0:成功，1:失败
				if(result.status==0){
					window.location.href="/zhigou/registerSuccess";
				}
				else{ 
					window.parent.PopLayer.close();
					$("#regTipPwd").html(result.msg);
				}
			}
		})
	}
	regObj.regPhone.blur(function(){ 
		checkRegPhone(1);
	})
	regObj.regFindPhone.blur(function(){ 
		checkRegPhone(2);
	})
	regObj.regPwd.blur(function(){ 
		$("#pwdstrength_regPwd").hide();
		regTip.regTipPwd.html("");
		checkRegPwd();
	})
	regObj.regAgainPwd.blur(function(){ 
		checkRegAgainPwd();
	})
	regObj.regVcode.blur(function(){
        checkRegVcode($.trim(regObj.regVcode.val()),regTip.regTipVcode);
	})
	regObj.regFreshCode.click(function(){ 
		freshVcode();
	})
	regObj.regVcodeImg.click(function(){ 
		freshVcode();
	})
	regObj.loginFreshCode.click(function(){ 
		freshVcode();
	})
	regObj.regAgreement.change(function(){ 
		checkRegAgreement();
	})
	regObj.regMsgVcode.blur(function(){ 
		var regMsgVcode=$.trim($(this).val());
		if(regMsgVcode==""||regMsgVcode==null){ 
			regTip.regTipMsgVcode.html(tipTxt.regVcode.msgIsNull).css("color","#F33");
		}else if(regMsgVcode.length!=6){ 
			regTip.regTipMsgVcode.html(tipTxt.regVcode.msgNotSix).css("color","#F33");
		}else{ 
			regTip.regTipMsgVcode.html("");
		}
	})
	regObj.loginUser.blur(function(){ 
		checkLoginUser();
	})
	regObj.loginPwd.blur(function(){ 
		checkLoginPwd();
	})
	regObj.loginVcode.blur(function(){ 
		var vcode=$.trim(regObj.loginVcode.val());
		checkRegVcode(vcode,regTip.loginTipUser);
	})
	//验证手机号弹层-获取手机验证码
	regObj.phone.text($.getUrlParam('phone'));
	//验证手机号弹层-更换手机号
	regObj.changePhone.click(function(){ 
		window.parent.regObj.regPhone.val("").focus();
		window.parent.regObj.regPhone.next(".tip-ok").hide();
		window.parent.regObj.regVcode.val("");
		window.parent.freshVcode();
		window.parent.PopLayer.close();
	})
	//验证手机号弹层-获取手机验证码
	window.getMsgVcode=function(){ 
		$.ajax({ 
			type:'post',
			url:'/zhigou/SendMobileCaptcha',
			dataType:"json",
			data:{
                mobile:$.trim($("#phone").text()),
                type:1
            },
			success:function(result){ 
				if(result.status==0){ 
					regTip.regTipMsgVcode.html(result.msg).css("color","#666666");
				}else{ 
					regTip.regTipMsgVcode.html(result.msg).css("color","#F33");
				}
			}
		})
	}
	//验证手机号弹层-关闭弹层
	regObj.validatePhoneClose.click(function(){ 
		window.parent.regObj.regVcode.val("").focus();
		window.parent.freshVcode();
		window.parent.PopLayer.close();
	})

	//验证手机号弹层-验证手机
	regObj.regValidateMsgVcode.click(function(){ 
		checkValidateMsgVcode($.getUrlParam('type'));
	})

	//验证手机号弹层-验证，arguments:1：注册时验证，2：找回密码时验证
	function checkValidateMsgVcode(){ 
		var bool=true,phone=$.trim($("#phone").text()),
			regMsgVcode=$.trim(regObj.regMsgVcode.val());

		if(regMsgVcode==""||regMsgVcode==null){ 
			regTip.regTipMsgVcode.html(tipTxt.regVcode.msgIsNull).css("color","#F33");
			bool=false;
		}
		else{ 
			//手机号验证码验证
			$.ajax({ 
				type:"post",
				url:"/zhigou/captchacheck",
				async:false,
				dataType:"json",
				data:{ 
					phone:phone,
					captcha:regMsgVcode
				},
				success:function(result){ 
					if(result.status==0){ 
						bool=true;
					}
					else{ 
						bool=false;
						regTip.regTipMsgVcode.html(result.msg).css("color","#F33");
					}
				}
			})
		}
		if(bool){ 
			if(arguments[0]==1){
				window.parent.regSubmit();
			}
			else if(arguments[0]==2){
				window.parent.location.href="/zhigou/resetPassword?phone="+phone;
			}
		}
	}
	//重置密码提交
	regObj.regResetPwdSubmit.click(function(){ 
		var bool=false,
			bool1=checkRegPwd(),
			bool2=checkRegAgainPwd();
		bool=bool1 && bool2;
		if(bool){ 
			$.ajax({ 
				type:"post",
				url:"/zhigou/resetPassword",
				dataType:"json",
				data:{pwd:$.trim(regObj.regPwd.val()),againPwd:$.trim(regObj.regAgainPwd.val()),phone:$.getUrlParam("phone")},
				success:function(result){ 
					if(result.status==0){  //0成功 1失败
						window.location.href="/zhigou/findPwdSuccess";
					}
				}
			})
		}
	})
	//登录提交
	regObj.loginSubmit.click(function(){
		var bool4=false,$this=$(this);
		if(checkLoginUser()){ 
			if(checkLoginPwd()){ 
				var isRemeber=0;
				if(regObj.loginRemeber.attr("checked")=="checked"){ 
					isRemeber=1;
				}
				var data=regObj.loginForm.serialize()+"&remeber="+isRemeber;
				if(!$("#loginVcodeContain").hasClass("none")){ 
					var vcode=$.trim(regObj.loginVcode.val());
					if(checkRegVcode(vcode,regTip.loginTipUser)){ 
						checkErrNo=checkRegVcodeAndPhoneRight(vcode);
						if(checkErrNo==0){
							regTip.loginTipUser.html(tipTxt.regVcode.msgNotRight);
							freshVcode();
							regObj.loginVcode.val("").focus();
							bool4=false;
						}
						else if(checkErrNo==1){
							data+="&vcode="+vcode;
							bool4=true;
						}
					}
				}
				else{ 
					bool4=true;
				}
				if(bool4){ 
					$.ajax({ 
						type:'post',
						url:'/zhigou/login',
						beforeSend:function(){ 
							$this.hide().next().css("display","inline-block");
						},
						dataType:"json",
						data:data,
						success:function(result){
							if(result.status==0){
								if($.getUrlParam("return")==null){ 
									window.location.href="/zhigou/index";  //进入个人中心页面
								}else{ 
									window.location.href=$.getUrlParam("return");
								}
							}
							else{
								$this.css("display","inline-block").next().hide();
								if(result.status==3){ 
									$("#loginVcodeContain").removeClass("none");
								}
								regTip.loginTipUser.html(result.msg);
								freshVcode();
								regObj.loginVcode.val("").focus();
							}
						}	
					})
				}
			}
		}
	})
	if($("#loginVcodeContain").length!=0){ 
		$(document).bind("keypress",function(e){ 
			if (e.keyCode == 13) {
	            regObj.loginSubmit.click();
	        }
		})
	}
	//登录判断用户
	function checkLoginUser(){ 
		var bool=false,
			user=$.trim(regObj.loginUser.val());
		if(user==""||user==null||user==tipTxt.loginUser.isNormal){ 
			regTip.loginTipUser.html(tipTxt.loginUser.isNull);
			bool=false;
		}
		else{ 
			regTip.loginTipUser.html("");
			bool=true;
		}
		return bool;
	}
	//登录判断密码
	function checkLoginPwd(){ 
		var bool=false,
			pwd=$.trim(regObj.loginPwd.val());
		if(pwd==""||pwd==null||pwd==tipTxt.regPwd.isNormal){ 
			regTip.loginTipUser.html(tipTxt.regPwd.isNull);
			bool=false;
		}
		else{ 
			regTip.loginTipUser.html("");
			bool=true;
		}
		return bool;
	}
	//判断手机号   //type:1 注册登录时，2 找回密码时
	function checkRegPhone(type){ 
		var bool=false,
		    phone="";
		if(type==1){ 
			phone=$.trim(regObj.regPhone.val());
		}else if(type==2){ 
			phone=$.trim(regObj.regFindPhone.val());
		}
		regObj.regPhone.next(".tip-ok").hide();
		if(phone==""||phone==null||checkIsNum(phone)){ 
			if(type==1){ 
				regTip.regTipPhone.html(tipTxt.regPhone.isNull);
			}else if(type==2){ 
				regTip.regTipPhone.html(tipTxt.regPhone.findIsNull);
			}
			bool=false;
		}
		else if(phone.length!=11){ 
			regTip.regTipPhone.html(tipTxt.regPhone.notEleven);
			bool=false;
		}
		else{ 
			regTip.regTipPhone.html("");
			regObj.regPhone.next(".tip-ok").show();
			bool=true;
		}
		return bool;
	}
	//判断密码
	function checkRegPwd(){ 
		var bool=false,
			pwd=$.trim(regObj.regPwd.val()),
			againPwd=$.trim(regObj.regAgainPwd.val()),
			reg1=/[0-9]+/,reg2=/[A-Za-z]+/;
		regObj.regPwd.next(".tip-ok").hide();
		if(pwd==""||pwd==null){ 
			regTip.regTipPwd.html(tipTxt.regPwd.isNull);
			bool=false;
		}
		else if(pwd.length<6){ 
			regTip.regTipPwd.html(tipTxt.regPwd.lessSix);
			bool=false;
		}
		else if(!checkIsNum(pwd)){ 
			regTip.regTipPwd.html(tipTxt.regPwd.isSimple);
			bool=false;
		}
		else if(!(reg1.test(pwd) && reg2.test(pwd))){ 
			regTip.regTipPwd.html(tipTxt.regPwd.isSimple);
			bool=false;
		}
		else{ 
			regTip.regTipPwd.html("");
			regObj.regPwd.next(".tip-ok").show();
			bool=true;
		}
		return bool;
	}
	//判断确认密码
	function checkRegAgainPwd(){ 
		var bool=false,
			pwd=$.trim(regObj.regPwd.val()),
			againPwd=$.trim(regObj.regAgainPwd.val());
		regObj.regAgainPwd.next(".tip-ok").hide();
		if(pwd==""||pwd==null){ 
			if(againPwd==""||againPwd==null){ 
				regTip.regTipAgainPwd.html(tipTxt.regPwd.isPwdNull);
				bool=false;
			}
		}
		else if(pwd!=""||pwd!=null){ 
			if(againPwd==""||againPwd==null){ 
				regTip.regTipAgainPwd.html(tipTxt.regAgainPwd.isNull);
				bool=false;
			}
			else if(pwd!=againPwd){ 
				regTip.regTipAgainPwd.html(tipTxt.regPwd.notEuqal);
				bool=false;
			}
			else{ 
				regTip.regTipAgainPwd.html("");
				regObj.regAgainPwd.next(".tip-ok").show();
				bool=true;
			}
		}
		return bool;
	}
	//注册的时候，判断验证码和验证手机号是否存在,vcode:验证码，position:提示的位置
	function checkRegVcode(vcode,pos){
		var bool=false;
		if(vcode==""||vcode==null||vcode==tipTxt.regVcode.isNormal){ 
			pos.html(tipTxt.regVcode.isNull);
			bool=false;
		}
		else if(vcode.length!=4){ 
			pos.html(tipTxt.regVcode.notFour);
			bool=false;
		}
		else{
			pos.html("");
			bool=true;
		}
		return bool;
	}



	//判断是否选择已阅读并同意协议
	function checkRegAgreement(){ 
		var bool=false;
		if(!(regObj.regAgreement.attr("checked")=="checked")){ 
			regTip.regTipAgreement.html(tipTxt.regAgreement.isChecked);
			bool=false;
		}
		else{
			regTip.regTipAgreement.html(""); 
			bool=true;
		}
		return bool;
	}
	//刷新验证码
	window.freshVcode = function(type){ 
		console.log(type);
		if(type==undefined){ 
			type="licai_money";
		}
		if(regObj.regVcodeImg.length!=0 && !regObj.regVcodeImg.is(":hidden")){ 
			regObj.regVcodeImg.attr("src","/zhigou/createvcode?type="+type+"&t="+new Date().getTime());
		}
	}
	//判断验证码和手机号是否正确,callback:0：验证码错误，1：验证码正确，2：验证码正确，手机号已被注册，3：验证码正确，手机号未被注册
	function checkRegVcodeAndPhoneRight(){
        var bool=0,
        	data={};
        data.type='licai_money';
        if(arguments.length==1){ 
        	data.vcode=arguments[0];
        }
        else if(arguments.length==2){
        	data.vcode=arguments[0];
        	data.phone=arguments[1];
        }
		$.ajax({
			type:"post",
            url:"/zhigou/VcodeAndMobileCheck",
            data:data,
			async:false,
			dataType:"json",
			success:function(result){
				bool=result.status;
			}
		})
		return bool;
	}
	$("#regPwd").CipherStrengths();
	if($("#regPwd").length!=0){ 
		freshVcode();
	}
	/*产品详情页立即投资*/
	var viewTipTxt={ 
		isNull:"请输入投资金额",
		isBalanceLow:"余额不足，需充值<span></span>元，请<a href='/zhigou/recharge'>充值</a>",
		isFillLow:"投资金额应不少于100元",
		lessZero:"起投金额应为正数",
		tooMuch:"您最多只可投<span></span>元",
		agreement:"同意借款协议，方可进行投资",
		moreMillion:"投资金额请不要超过100万",
		finalTip:""
	}
	if($("#proHideMsg").length!=0){ 
		var getViewHideMsg={ 
			hideMsg:eval("("+$("#proHideMsg").val()+")")
		}
		var viewRedPacket={ 
			proInTxt:$("#proInTxt"),
			proRedPacket:$("#proRedPacket"),
			proHideMsg:$("#proHideMsg"),
			proInTip:$("#proInTip"),
			num:parseInt($("#proInTxt").val()),
			maxFix:parseInt(getViewHideMsg.hideMsg.maxFix),
			fixMin:parseInt(getViewHideMsg.hideMsg.fix_min),
			//fixMin:100,
			maxPercentage:parseInt(getViewHideMsg.hideMsg.maxPercentage),
			maxRate:parseFloat(getViewHideMsg.hideMsg.maxRate),
			rateNum:parseInt($("#proInTxt").val())*parseFloat(getViewHideMsg.hideMsg.maxRate),
			percentageMin:parseInt(getViewHideMsg.hideMsg.scale_min),
			investMoney:getViewHideMsg.hideMsg.investMoney,
			enbleMoney:parseInt(getViewHideMsg.hideMsg.enbleMoney),
			proSpan:$("#proRedPacket").find("span"),
			fixId:getViewHideMsg.hideMsg.fix_id,
			percentageId:getViewHideMsg.hideMsg.scale_id,
			selId:""
		}
		if(viewRedPacket.investMoney>=100){
			$("#proInTxt").attr('placeholder','100元起投');
		}else if(viewRedPacket.investMoney<100&&viewRedPacket.investMoney>0){
			$("#proInTxt").attr('placeholder','请投资'+viewRedPacket.investMoney);
		}

		//是否有红包
		if (hasRedPacket()!=0&&viewRedPacket.investMoney>=100) { 
			viewRedPacket.proRedPacket.show();
		}
		else{ 
			viewRedPacket.proRedPacket.hide();
		}
		//产品详情页投资红包

		viewRedPacket.proInTxt.live("keyup",function(){ 
			if(!viewRedPacket.proRedPacket.hasClass("sprite-red-packet-close")&&viewRedPacket.investMoney>=100){
				countRedPaper();
			}
		}).live("focus",function(){ 
			$("#proInTip").html("");
		});
		$("#proInvestBtn").click(function(){ 
			var bool=countRedPaper();
			if(bool){ 
				if($("#proInvestCheck").attr("checked")=="checked"){ 
					var amount=0;
					if(viewRedPacket.proSpan.find("span").length!=0 && !viewRedPacket.proRedPacket.hasClass("sprite-red-packet-close")){ 
						var amount=viewRedPacket.proSpan.find("span").text();
					}
					if(viewRedPacket.proRedPacket.hasClass("sprite-red-packet-close")){ 
						viewRedPacket.selId="";
					}
					$.ajax({ 
						type:"post",
						url:"/zhigou/invest-product",
						dataType:"json",
						beforeSend:function(){ 
							$("#proInvestBtn").hide();
							$("#proInvestBtn").next(".btn-loading").css("display","block");
						},
						data:{ 
							amount:viewRedPacket.proInTxt.val(),
							bonusid:viewRedPacket.selId,
							bonus_amount:amount,
							productid:viewRedPacket.proHideMsg.attr("proid")
						},
						success:function(data){ //status:0成功，1传值有空，2系统错误，3登录失效
							$("#proInvestBtn").css("display","block");
							$("#proInvestBtn").next(".btn-loading").hide();
							var json=data;

							if(json.status==0){ 
								proMask();
								$(".pro-mask-sh").show();
								$("#proTip .p1").hide();
								$("#proTip .pro-success").show();
							}
							else if(json.status==3){ 
								window.location.href="/zhigou/login";
							}
							else{ 
								proMask();
								$(".pro-mask-sh").show();
								$("#proTip .p1").hide();
								$("#proTip .pro-fail").show().find("span").text(json.msg);
							}
						}
					})
				}
				else{ 
					$("#proInTip").html(viewTipTxt.agreement);
				}
			}else{ 
				$("#proInTip").html(viewTipTxt.finalTip);
			}

		})
		viewRedPacket.proRedPacket.click(function(){ 
			if($(this).hasClass("sprite-red-packet-close")){ 
				$(this).removeClass("sprite-red-packet-close").attr("data-tip","抵用当前投资额下最高红包金额");
				countRedPaper();
			}
			else{
				$(this).addClass("sprite-red-packet-close").attr("data-tip","抵用当前投资额下最高红包金额，点击后启用").find("span").html("");
			}
		}).mouseover(function(){ 
			viewRedPacket.proInTxt.blur();
		})
	}
	function hasRedPacket(){ 
		if(getViewHideMsg.hideMsg!=undefined){
			//两类红包都有
			if(viewRedPacket.fixId!=0 && viewRedPacket.percentageId!=0){ 
				return 3;
			}
			//只有定投
			else if(viewRedPacket.fixId!=0 && viewRedPacket.percentageId==0){ 
				return 2;
			}
			//只有百分比
			else if(viewRedPacket.fixId==0 && viewRedPacket.percentageId!=0){ 
				return 1;
			}
			//都没有
			else{ 
				return 0;
			}

		}
	};
	function compareAmount(arg1,arg2,arg3){ //arg1:可用金额，arg2:可投金额，arg3:红包额
		//可用余额大于等于可投金额
		if(arg1>=arg2){ 
			if(viewRedPacket.num>arg2){ 
				viewTipTxt.finalTip="您最多只可投<span>"+arg2+"</span>元";
				return false;
			}
			else{ 
				viewTipTxt.finalTip="";
				return true;
			}
		}
		//可用余额小于可投金额
		else{ 
			if(viewRedPacket.num>arg2){ 
				viewTipTxt.finalTip="您最多只可投<span>"+arg2+"</span>元";
				return false;
			}
			//是否有红包
			if(!viewRedPacket.proRedPacket.hasClass("sprite-red-packet-close")){ 
				viewRedPacket.num=viewRedPacket.num-parseInt(arg3);
			}
			else{ 
				//所用红包ID
				viewRedPacket.selId="0";
			}
			if(viewRedPacket.num>arg1){ 
				viewTipTxt.finalTip="余额不足，需充值<span>"+(viewRedPacket.num-viewRedPacket.enbleMoney)+"</span>元，请<a target='_blank' href='/zhigou/recharge'>充值</a>";
				return false;
			}
			else{ 
				viewTipTxt.finalTip="";
				return true;
			}
		}
	};
	function countRedPaper(){
		//所用红包ID
		viewRedPacket.selType="";
		viewRedPacket.selId="";
		viewRedPacket.num=$("#proInTxt").val();
		viewRedPacket.rateNum=viewRedPacket.num*viewRedPacket.maxRate;
		//没填或者等于0

		if(viewRedPacket.num=="" || viewRedPacket.num==0 || isNaN(viewRedPacket.num)){ 
			//viewRedPacket.proRedPacket.removeClass("sprite-red-packet-on sprite-red-packet-close");
			viewRedPacket.proSpan.html("");
			
			viewTipTxt.finalTip=viewTipTxt.isNull;

			return false;
			
		}
		if(checkNumLessZero(viewRedPacket.num)){ 
			viewRedPacket.proRedPacket.addClass("sprite-red-packet-on");
			viewRedPacket.proSpan.html("¥<span>0</span>");
			viewTipTxt.finalTip=viewTipTxt.lessZero;
			return false;
		}
		if(viewRedPacket.investMoney>=100){
			//大于0小于定投起投额（目前是100）
			if(viewRedPacket.num>0 && viewRedPacket.num<100){ 
				viewRedPacket.proRedPacket.addClass("sprite-red-packet-on");
				viewRedPacket.proSpan.html("¥<span>0</span>");
				viewTipTxt.finalTip=viewTipTxt.isFillLow;
				return false;
			}
		}
		
		/*if(viewRedPacket.num%100!=0){ 
			viewTipTxt.finalTip=viewTipTxt.isFillLow;
			return false;
		}*/
		if(viewRedPacket.num>1000000){ 
			viewTipTxt.finalTip=viewTipTxt.moreMillion;
			return false;
		}
		viewRedPacket.proRedPacket.addClass("sprite-red-packet-on");
		//两类红包都有
		if(hasRedPacket()==3){ 
			if(viewRedPacket.num>=viewRedPacket.fixMin && viewRedPacket.num<viewRedPacket.percentageMin){
				viewRedPacket.proSpan.html("¥<span>"+viewRedPacket.maxFix+"</span>");
				//所用红包ID
				viewRedPacket.selId=viewRedPacket.fixId;

				return compareAmount(viewRedPacket.enbleMoney,viewRedPacket.investMoney,viewRedPacket.maxFix);
			}
			else if(viewRedPacket.num>=viewRedPacket.percentageMin){ 
				if(viewRedPacket.maxFix>=viewRedPacket.rateNum){ 
					viewRedPacket.proSpan.html("¥<span>"+viewRedPacket.maxFix+"</span>");
					//所用红包ID
					viewRedPacket.selId=viewRedPacket.fixId;

					return compareAmount(viewRedPacket.enbleMoney,viewRedPacket.investMoney,viewRedPacket.maxFix);
				}
				else{ 
					if(viewRedPacket.rateNum<=viewRedPacket.maxPercentage){ 
						var i=viewRedPacket.rateNum.toFixed(1);
						if(i.substring(i.length-1,i.length)=="0"){ 
							i=viewRedPacket.rateNum.toFixed(0);
						}
						viewRedPacket.proSpan.html("¥<span>"+i+"</span>");
						//所用红包ID
						viewRedPacket.selId=viewRedPacket.percentageId;

						return compareAmount(viewRedPacket.enbleMoney,viewRedPacket.investMoney,i);
					}
					else{ 
						if(viewRedPacket.maxFix>=viewRedPacket.maxPercentage){ 
							viewRedPacket.proSpan.html("¥<span>"+viewRedPacket.maxFix+"</span>");
							//所用红包ID
							viewRedPacket.selId=viewRedPacket.fixId;

							return compareAmount(viewRedPacket.enbleMoney,viewRedPacket.investMoney,viewRedPacket.maxFix);
						}
						else{ 
							viewRedPacket.proSpan.html("¥<span>"+viewRedPacket.maxPercentage+"</span>");
							//所用红包ID
							viewRedPacket.selId=viewRedPacket.percentageId;
							return compareAmount(viewRedPacket.enbleMoney,viewRedPacket.investMoney,viewRedPacket.maxPercentage);
						}
					}
				}
			}
		}
		//只有定投
		else if(hasRedPacket()==2){ 
			viewRedPacket.proSpan.html("¥<span>"+viewRedPacket.maxFix+"</span>");
			//所用红包ID
			viewRedPacket.selId=viewRedPacket.fixId;

			return compareAmount(viewRedPacket.enbleMoney,viewRedPacket.investMoney,viewRedPacket.maxFix);
		}
		//只有百分比
		else if(hasRedPacket()==1){ 
			if(viewRedPacket.num<viewRedPacket.percentageMin){
				viewRedPacket.proSpan.html("¥<span>0</span>");
				//所用红包ID
				viewRedPacket.selId="0";

				return compareAmount(viewRedPacket.enbleMoney,viewRedPacket.investMoney,0);
			}
			else{ 
				if(viewRedPacket.rateNum<=viewRedPacket.maxPercentage){ 
					var j=viewRedPacket.rateNum.toFixed(1);
					if(j.substring(j.length-1,j.length)=="0"){ 
						j=viewRedPacket.rateNum.toFixed(0);
					}
					viewRedPacket.proSpan.html("¥<span>"+j+"</span>");
					//所用红包ID
					viewRedPacket.selId=viewRedPacket.percentageId;

					return compareAmount(viewRedPacket.enbleMoney,viewRedPacket.investMoney,j);
				}
				else{ 
					viewRedPacket.proSpan.html("¥<span>"+viewRedPacket.maxPercentage+"</span>");
					//所用红包ID
					viewRedPacket.selId=viewRedPacket.percentageId;

					return compareAmount(viewRedPacket.enbleMoney,viewRedPacket.investMoney,viewRedPacket.maxPercentage);
				}
			}
		}
		else if(hasRedPacket()==0){ 
			//所用红包ID
			viewRedPacket.selId="0";

			return compareAmount(viewRedPacket.enbleMoney,viewRedPacket.investMoney,0);
		}
	}

	/*产品详情页投资记录翻页*/
	var recordStartPage=1;
	function recordPage(page){ 
		var total=$("#recordTotalPage").val();
		var cls={ 
			"l":"sprite-record-left","r":"sprite-record-right",
			"ld":"sprite-record-left-disable","rd":"sprite-record-right-disable"
		}
		if(page==1){ 
			$("#recordLast").addClass(cls.ld).removeClass(cls.l);
			$("#recordNext").addClass(cls.r).removeClass(cls.rd);
		}
		else if(total!=""&&total!=null&&page==total){ 
			$("#recordLast").addClass(cls.l).removeClass(cls.ld);
			$("#recordNext").addClass(cls.rd).removeClass(cls.r);
		}
		else if(page>1&&page<total){ 
			$("#recordLast").removeClass(cls.ld).addClass(cls.l);
			$("#recordNext").removeClass(cls.rd).addClass(cls.r);
		}
		else if(page<1){ 
			recordStartPage=1;
			$("#recordLast").addClass(cls.ld).removeClass(cls.l);
			$("#recordNext").addClass(cls.r);
			return;
		}
		else if(total!=""&&total!=null&&page>total){ 
			recordStartPage=total;
			$("#recordLast").addClass(cls.l);
			$("#recordNext").addClass(cls.rd).removeClass(cls.r);
			return;
		}
		var productId=$("#productId").val();
		$.ajax({ 
			type:"get",
			url:"/zhigou/product-order-view",
			dataType:"json",
			beforeSend:function(){ 
				$("#InvestRecordList").html('<img class="loading" src="/static/img/loading_big.gif">');
			},
			data:{ 
				productid:productId,
				page:page
			},
			success:function(data){ 
				var json=data;
				$("#recordTotalPage").val(json.total_page_num);
				var html="";
				for(var i=0; i<json.orders_list.length; i++){ 
					html+='<div class="sub-con wrap-clear"><div class="record-sub-l record-con-l text-hide wrap-left">'+json.orders_list[i].username+'</div><div class="record-sub-m record-con-l text-hide wrap-left">'+json.orders_list[i].invest_amount+'</div><div class="record-sub-r record-con-r text-hide wrap-left">'+json.orders_list[i].invest_time+'</div></div>';
				}
				$("#InvestRecordList").html(html);
				recordStartPage=page;
			}
		});
	}
	/*if($("#InvestRecordList").length==1){ 
		recordPage(recordStartPage);
	}*/
	$("#recordLast").bind("click",function(){ 
		recordPage(recordStartPage-1);
	})
	$("#recordNext").bind("click",function(){ 
		recordPage(recordStartPage+1);
	})

	/*if($("#proMask").css("display")=="block"){ 
		proMask();
	}*/
	function proMask(){ 
		$("#proMask").height($("body").height());
		var L=($("body").width()-$("#proTip").width())/2;
		var T=$("body").scrollTop();
		$("#proTip").css({"left":L+"px","top":150+T+"px"});
		var proTag=setInterval(function(){ 
			var $sec=$("#proTip .pro-second");

			var $num=parseInt($sec.text());

			$sec.text($num-1);
			if($num==1){ 
				clearInterval(proTag);
				window.location.href=window.location.href;
			}
		},1000)
		$("#proTip .pro-back").click(function(){ 
			window.location.href=window.location.href;
		})
	}

	/*设置或修改用户名*/
	function checkUserName(txt){ 
		var bool=false;
		$.ajax({ 
			type:"post",
			async:false,
			dataType:"json",
			url:"/zhigou/account/usernameuniq",
			data:{user_name:txt},
			success:function(result){ 
				if(result.status==0){ 
					bool=true;
				}
				else{ 
					$("#setUserNameTip").html(result.msg);
					bool=false;
				}
			},
			error:function(){ 
				$("#setUserNameTip").html(error.serverError);
			}
		})
		return bool;
	}
	function setUserName(){ 
		var setUserName=$("#setUserName"),tip=$("#setUserNameTip"),
			txt=setUserName.val(),reg=/^[_A-Za-z0-9\u4e00-\u9fa5]+$/;
		if(txt==""||txt==null){ 
			tip.html("请输入您的用户名");
			return false;
		}else if(!reg.test(txt)){ 
			tip.html("用户名只支持下划线、中文、英文或数字");
			return false;
		}else if(getLength(txt)<4){ 
			tip.html("用户名最少2个汉字或4位字母/数字");
			return false;
		}else if(getLength(txt)>20){ 
			tip.html("用户名支持最多20个字符");
			return false;
		}else if(!checkUserName(txt)){ 
			//tip.html("此用户名已被占用，换一个试试");
			return false;
		}else{ 
			tip.html("");
			return true;
		}
	}
	$("#setUserName").bind("blur",function(){
		//setUserName();
	}).focus(function(){ 
		$("#setUserNameTip").html("");
	});
	$("#setUserNameBtn").click(function(){ 
		if(setUserName()){ 
			$.ajax({ 
				type:"post",
				url:"/zhigou/account/modifyname",
				dataType:"json",
				data:{user_name:$("#setUserName").val()},
				beforeSend:function(){ 
					$("#setUserNameBtn").hide();
					$("#setUserNameLoading").show();
				},
				success:function(result){ 
					if(result.status==0){ 
						//$("#setUserNameTip").html(result.msg);
						$("#setUserNameCon").hide();
						$("#setUserNameSuccess").show();
					}
					else{ 
						$("#setUserNameTip").html(result.msg);
					}
				},
				error:function(){ 
					$("#setUserNameTip").html(error.serverError);
				}
			});
		}
	})
	//设置修改交易密码
	var payPwdObj={ 
		oldPayPwd:$("#oldPayPwd"),
		oldPayTipPwd:$("#oldPayTipPwd"),
		setPayPwd:$("#setPayPwd"),
		payPwd:$("#payPwd"),
		setPayTipPwd:$("#setPayTipPwd"),
		payTipPwd:$("#payTipPwd"),
		setPayAgainPwd:$("#setPayAgainPwd"),
		setPayTipAgainPwd:$("#setPayTipAgainPwd"),
		payAgainPwd:$("#payAgainPwd"),
		payTipAgainPwd:$("#payTipAgainPwd"),
		payPwdSubmit:$("#payPwdSubmit"),
		updatePayPwdSubmit:$("#updatePayPwdSubmit")
	}
	var loginPwdObj={ 
		oldLoginPwd:$("#oldLoginPwd"),
		oldLoginTipPwd:$("#oldLoginTipPwd"),
		newLoginPwd:$("#newLoginPwd"),
		newLoginTipPwd:$("#newLoginTipPwd"),
		loginAgainPwd:$("#loginAgainPwd"),
		loginTipAgainPwd:$("#loginTipAgainPwd"),
		modifyLoginPwdSubmit:$("#modifyLoginPwdSubmit")
	}
	var setPayPwdTxt={ 
		isNull:"请输入交易密码",
		lessDigit:"请设置8~16位交易密码，需包含字母和数字",
		isSimple:"您输入的密码太简单，请使用字母和数字组合密码",
		isAgainNull:"请再次输入交易密码",
		isAgainNullAndNull:"请先输入交易密码",
		isNotEqual:"两次输入的密码不一致"
	}
	var setOldPayPwdTxt={ 
		isNull:"请输入您当前的交易密码",
		lessDigit:"当前密码输入有误",
		isSimple:"您输入的密码太简单，请使用字母和数字组合密码"
	}
	var setOldLoginPwdTxt={ 
		isNull:"请输入您当前使用的密码",
		lessDigit:"当前密码输入有误",
		isSimple:"登录密码至少包含字母和数字"
	}
	var updatePayPwdTxt={ 
		isNull:"请输入新交易密码",
		lessDigit:"请设置8~16位新密码，需包含字母和数字",
		isSimple:"您输入的密码太简单，请使用字母和数字组合密码",
		isAgainNull:"请再次输入新交易密码",
		isAgainNullAndNull:"请先输入新交易密码",
		isNotEqual:"两次输入的密码不一致"
	}
	var updateLoginPwdTxt={ 
		isNull:"请输入新密码", lessDigit:"请设置6~16位新密码，需包含字母和数字", isSimple:"您输入的密码太简单，请使用字母和数字组合密码",
		isAgainNull:"请再次输入新密码", isAgainNullAndNull:"请先输入新密码", isNotEqual:"两次输入的密码不一致"
	}
	payPwdObj.setPayPwd.CipherStrengths();
	payPwdObj.payPwd.CipherStrengths();
	loginPwdObj.newLoginPwd.CipherStrengths();
	//密码输入验证
	function checkPwd(pwd,tip,txt,digit){ //pwd密码  tip提示位置  txt提示内容  digit位数
		var payPwd=$.trim(pwd.val()),reg=/^[A-Za-z]+$/;
		if(checkIsNull(payPwd)){ tip.html(txt.isNull); return false; }
		else if(payPwd.length<digit){ tip.html(txt.lessDigit); return false; }
		else if(reg.test(payPwd)){ tip.html(txt.isSimple); return false; }
		else if(!checkIsNum(payPwd)){ tip.html(txt.isSimple); return false; }
		else{ tip.html(""); return true; }
	}
	//再次输入密码验证
	function checkAgainPwd(pwd,againPwd,tip,txt){　
		var payPwd=$.trim(pwd.val()),payAgainPwd=$.trim(againPwd.val());
		if(checkIsNull(payAgainPwd)){ 
			if(checkIsNull(payPwd)){ tip.html(txt.isAgainNullAndNull); return false; }
			else{ tip.html(txt.isAgainNull); return false; }
		}else{ 
			if(payPwd!=payAgainPwd){ tip.html(txt.isNotEqual); return false; }
			else{ tip.html(""); return true; }
		}
	}
	payPwdObj.oldPayPwd.blur(function(){ 
		checkPwd(payPwdObj.oldPayPwd,payPwdObj.oldPayTipPwd,setOldPayPwdTxt,8);
	}).focus(function(){ 
		payPwdObj.oldPayTipPwd.html("");
	});

	payPwdObj.setPayPwd.blur(function(){ 
		checkPwd(payPwdObj.setPayPwd,payPwdObj.setPayTipPwd,setPayPwdTxt,8);
		$("#pwdstrength_setPayPwd").hide();
	}).focus(function(){ 
		payPwdObj.setPayTipPwd.html("");
	});

	payPwdObj.payPwd.blur(function(){ 
		checkPwd(payPwdObj.payPwd,payPwdObj.payTipPwd,updatePayPwdTxt,8);
		$("#pwdstrength_payPwd").hide();
	}).focus(function(){ 
		payPwdObj.payTipPwd.html("");
	});

	payPwdObj.payAgainPwd.blur(function(){ 
		checkAgainPwd(payPwdObj.payPwd,payPwdObj.payAgainPwd,payPwdObj.payTipAgainPwd,updatePayPwdTxt);
	}).focus(function(){ 
		$("#pwdstrength_payPwd").hide();
		payPwdObj.payTipAgainPwd.html("");
	});

	payPwdObj.setPayAgainPwd.blur(function(){ 
		checkAgainPwd(payPwdObj.setPayPwd,payPwdObj.setPayAgainPwd,payPwdObj.setPayTipAgainPwd,setPayPwdTxt);
	}).focus(function(){ 
		$("#pwdstrength_payPwd").hide();
		payPwdObj.setPayTipAgainPwd.html("");
	});

	loginPwdObj.oldLoginPwd.blur(function(){ 
		checkPwd(loginPwdObj.oldLoginPwd,loginPwdObj.oldLoginTipPwd,setOldLoginPwdTxt,6);
	}).focus(function(){ 
		loginPwdObj.oldLoginTipPwd.html("");
	});

	loginPwdObj.newLoginPwd.blur(function(){ 
		checkPwd(loginPwdObj.newLoginPwd,loginPwdObj.newLoginTipPwd,updateLoginPwdTxt,6);
		$("#pwdstrength_newLoginPwd").hide();
	}).focus(function(){ 
		loginPwdObj.newLoginTipPwd.html("");
	});

	loginPwdObj.loginAgainPwd.blur(function(){ 
		checkAgainPwd(loginPwdObj.newLoginPwd,loginPwdObj.loginAgainPwd,loginPwdObj.loginTipAgainPwd,updateLoginPwdTxt);
	}).focus(function(){ 
		loginPwdObj.loginTipAgainPwd.html("");
	});
	/*设置交易密码*/
	payPwdObj.payPwdSubmit.click(function(){ 
		var bool1=checkPwd(payPwdObj.setPayPwd,payPwdObj.setPayTipPwd,setPayPwdTxt,8),
			bool2=checkAgainPwd(payPwdObj.setPayPwd,payPwdObj.setPayAgainPwd,payPwdObj.setPayTipAgainPwd,setPayPwdTxt),
			$this=$(this),
			data={pwd:$.trim(payPwdObj.setPayPwd.val()),pwd_again:$.trim(payPwdObj.setPayAgainPwd.val())},
			callback=function(){$("#setPayPwdSuccess").removeClass("none"); $("#setPayPwdCon").hide();};
		if(bool1&&bool2){ 
			checkAjax($this,"","/zhigou/account/bindpaypwd",true,data,callback,payPwdObj.setPayTipAgainPwd);
		}
	})
	/*修改交易密码*/
	payPwdObj.updatePayPwdSubmit.click(function(){ 
		var bool1=checkPwd(payPwdObj.oldPayPwd,payPwdObj.oldPayTipPwd,setOldPayPwdTxt,8),
			bool2=checkPwd(payPwdObj.payPwd,payPwdObj.payTipPwd,updatePayPwdTxt,8),
			bool3=checkAgainPwd(payPwdObj.payPwd,payPwdObj.payAgainPwd,payPwdObj.payTipAgainPwd,updatePayPwdTxt),
			$this=$(this),
			data={old_passwd:payPwdObj.oldPayPwd.val(), new_passwd:payPwdObj.payPwd.val(), new_passwd_again:payPwdObj.payAgainPwd.val()},
			callback=function(){$("#updatePayPwdSuccess").removeClass("none"); $("#updatePayPwdCon").hide();};
		if($.trim(payPwdObj.oldPayPwd.val())==$.trim(payPwdObj.payPwd.val())){ 
			payPwdObj.payTipPwd.html("当前密码不能和新密码一样");
			return false;
		}
		if(bool1&&bool2&&bool3){ 
			checkAjax($this,"","/zhigou/account/update-paypwd",true,data,callback,payPwdObj.payTipAgainPwd);
		}
	})
	/*修改登录密码*/
	loginPwdObj.modifyLoginPwdSubmit.click(function(){ 
		var bool1=checkPwd(loginPwdObj.oldLoginPwd,loginPwdObj.oldLoginTipPwd,setOldLoginPwdTxt,6),
			bool2=checkPwd(loginPwdObj.newLoginPwd,loginPwdObj.newLoginTipPwd,updateLoginPwdTxt,6),
			bool3=checkAgainPwd(loginPwdObj.newLoginPwd,loginPwdObj.loginAgainPwd,loginPwdObj.loginTipAgainPwd,updateLoginPwdTxt),
			$this=$(this),
			data={old_pwd:loginPwdObj.oldLoginPwd.val(), new_pwd:loginPwdObj.newLoginPwd.val(), new_pwd_again:loginPwdObj.loginAgainPwd.val()},
			callback=function(){$("#modifyLoginPwdCon").hide(); $("#modifyLoginPwdSuccess").show();};
		if($.trim(loginPwdObj.oldLoginPwd.val())==$.trim(loginPwdObj.newLoginPwd.val())){ 
			loginPwdObj.newLoginTipPwd.html("当前密码不能和新密码一样");
			return false;
		}
		if(bool1&&bool2&&bool3){ 
			checkAjax($this,"","/zhigou/account/modifyloginpwd",true,data,callback,loginPwdObj.loginTipAgainPwd);
		}
	})
	function checkPhoneVcode(vcode,tip){ 
		var num=$.trim(vcode.val());
		tip.css("color","#f33");
		if(checkIsNull(num)){ tip.html("请输入短信验证码"); return false;}
		else if(checkIsNum(num)){ tip.html("请输入6位数字验证码"); return false;}
		else if(num.length!=6){ tip.html("请输入6位数字验证码"); return false;}
		else{ tip.html(""); return true;}
	}
	$("#personGetMsgVcode").find(".ptxt").blur(function(){ 
		var a=$(this),b=$(this).parent().next();
		checkPhoneVcode(a,b);
	}).focus(function(){ $(this).parent().next().html("");})

	//修改手机号step1
	$("#modifyPhoneStep1").click(function(){ 
		var a=$("#personGetMsgVcode").find(".ptxt"),b=$("#personGetMsgVcode").next(),vcode=$.trim(a.val()),$this=$(this),bool=checkPhoneVcode(a,b);
		if(bool){ 
			var data={captcha:vcode};
			function callback(){window.location.href="/zhigou/account/modifymobilepage-step2?captcha="+vcode;}
			checkAjax($this,"","/zhigou/account/checksmscaptcha",true,data,callback,b);
		}
	})
	//修改手机号step2
	$("#modifyPhoneStep2").click(function(){ 
		var bool1=checkNewPhone($("#modifyPhoneNew").val(),$("#modifyPhoneNew").parent().next());
		var bool2=checkPhoneVcode($("#personGetMsgVcode input"),$("#personGetMsgVcode").next());
		var $this=$(this);
		var data={mobile:$.trim($("#modifyPhoneNew").val()),new_captcha:$.trim($("#personGetMsgVcode input").val())};
		function callback(){window.location.href="/zhigou/account/modifymobilepage-step3";}
		if(bool1&&bool2){ 
			checkAjax($this,"","/zhigou/account/modifyphone",true,data,callback,$("#personGetMsgVcode").next())
		}
	})
	$("#modifyPhoneNew").focus(function(){ 
		$(this).parent().next().html("");
	});
	//找回交易密码step1
	$("#findPayPwdStep1").click(function(){ 
		var a=$("#personGetMsgVcode").find(".ptxt"),b=$("#personGetMsgVcode").next(),vcode=$.trim(a.val()),$this=$(this),bool=checkPhoneVcode(a,b);
		if(bool){ 
			var data={captcha:vcode};
			function callback(){window.location.href="/zhigou/account/retrieve-paypwdpage-step2?captcha="+vcode;}
			checkAjax($this,"","/zhigou/account/checksmscaptcha",true,data,callback,b);
		}
	})
	//找回交易密码step2
	$("#findPayPwdStep2").click(function(){ 
		var bool1=checkPwd(payPwdObj.payPwd,payPwdObj.payTipPwd,setPayPwdTxt,8),
			bool2=checkAgainPwd(payPwdObj.payPwd,payPwdObj.payAgainPwd,payPwdObj.payTipAgainPwd,setPayPwdTxt),
			$this=$(this);
		if(bool1&&bool2){ 
			var data={pwd:$.trim(payPwdObj.payPwd.val()),pwd_again:$.trim(payPwdObj.payAgainPwd.val()),captcha:$.getUrlParam('captcha')};
			function callback(){window.location.href="/zhigou/account/retrieve-paypwdpage-step3";}
			checkAjax($this,"","/zhigou/account/retrieve-paypwd",true,data,callback,payPwdObj.payTipAgainPwd);
		}
	})
	//增加R码兑换红包功能
	$("#exchangeRcode").focus(function(){ 
		$(this).parent().next().html("");
	})
	$("#exchangeVcode").focus(function(){ 
		$(this).parent().next().html("");
	})
	$("#exchangeForm").submit(function(){ 
		var rcode=$.trim($("#exchangeRcode").val());
		var captcha=$.trim($("#exchangeVcode").val());
		if(rcode==""){ 
			$("#exchangeRcode").parent().next().html("请输入您收到的R码");
			return false;
		}
		if(!$("#exchangeVcodeArea").is(":hidden")){ 
			if(captcha==""){ 
				$("#exchangeVcode").parent().next().html("请输入右侧图片验证码");
				return false;
			}
		}
		$.ajax({ 
			type:"post",
			url:"/zhigou/convert-bonus-rcode",
			data:$("#exchangeForm").serialize(),
			dataType:"json",
			success:function(result){ 
				if(result.status==0){ 
					proMask();
					$(".pro-mask-sh").show();
					$("#proTip .p1").hide();
					$("#proTip .pro-success").show();
				}else if(result.status==3){ 
					$("#exchangeVcode").parent().next().html("验证码错误，请重新输入");
				}
				else{ 
					proMask();
					$(".pro-mask-sh").show();
					$("#proTip .p1").hide();
					$("#proTip .pro-fail").show().find("span").text(result.msg);
					if(result.data>=3){ 
						$("#exchangeVcodeArea").show();
						freshVcode("exchange");
					}
				}
			}
		})
	})
	$("#exchangeFreshCode").click(function(){ 
		freshVcode("exchange");
	})
})
