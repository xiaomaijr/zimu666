//判断是否是数字，true:非数字，false:数字
function checkIsNum(num){if(isNaN(num*1)){return true;}else{return false;}}
//判断是否小于0，true:小于等于0，false:大于0
function checkNumLessZero(num){ if(num*1<=0){return true;}else{return false;}}
//获取字符长度
function getLength(str){ return str.replace(/([^\x00-\xFF])/g,'..').length;}
function checkIsNull(str){ if(str==""||str==null){ return true;} else{ return false;}}
function stopDefault(e) {
if(e && e.preventDefault) {e.preventDefault();}else {window.event.returnValue = false;}return false;
}
function stopBubble(e) { 
	if(e&&e.stopPropagation){e.stopPropagation();}
	else{ window.event.cancelBubble = true;}
}
function getIntFloat(num){ 
	var arr=num.split(".");
	if(arr[1]==undefined){ 
		arr[1]="00";
	}
	return arr
}
function checkAjax(obj,type,url,async,data,callback,tip){ 
	type=type||"post"; obj=obj||$("");
	$.ajax({ 
		type:type,
		url:url,
		async:async,
		dataType:"json",
		data:data,
		beforeSend:function(){ 
			obj.hide().next().css("display","inline-block");
		},
		success:function(result){ 
			if(result.status==0){ 
				callback();
			}else{
				obj.css("display","inline-block").next().hide();
				tip.html(result.msg).css({"color":"#f33", "fontSize":"12px"});;
			}
		},
		error:function(){ 
			tip.html(error.serverError);
		}
	})
}
function logout(){ 
	$.ajax({ 
		type:"post",
		url:"/zhigou/logout",
		dataType:"json",
		success:function(result){ 
			if(result.status==0){ 
				window.location.href=window.location.href;
			}
			else{ 
				alert(result.msg);
			}
		},
		error:function(){ 
			alert(error.serverError);
		}
	})
}
(function($){
	/*jquery.placeholder*/
	//判断浏览器是否支持 placeholder属性  
	function isPlaceholder(){  
	    var input = document.createElement('input');  
	    return 'placeholder' in input;  
	}  
	  
	if (!isPlaceholder()) {//不支持placeholder 用jquery来完成  
	    $(document).ready(function() {  
	        if(!isPlaceholder()){  
	            $("input").not("input[type='password']").each(//把input绑定事件 排除password框  
	                function(){  
	                    if($(this).val()=="" && $(this).attr("placeholder")!=""){  
	                        $(this).val($(this).attr("placeholder"));  
	                        $(this).focus(function(){  
	                            if($(this).val()==$(this).attr("placeholder")) $(this).val("");  
	                        });  
	                        $(this).blur(function(){  
	                            if($(this).val()=="") $(this).val($(this).attr("placeholder"));  
	                        });  
	                    }  
	            });  
	            //对password框的特殊处理1.创建一个text框 2获取焦点和失去焦点的时候切换  
	            var pwdField    = $("input[type=password][placeholder]");  
	            var pwdVal      = pwdField.attr('placeholder');  
	            pwdField.after('<input id="pwdPlaceholder" class="txt" type="text" value='+pwdVal+' autocomplete="off" />');  
	            var pwdPlaceholder = $('#pwdPlaceholder');  
	            pwdPlaceholder.show();  
	            pwdField.hide();  
	              
	            pwdPlaceholder.focus(function(){  
	                pwdPlaceholder.hide();  
	                pwdField.show();  
	                pwdField.focus();  
	            });  
	              
	            pwdField.blur(function(){  
	                if(pwdField.val() == '') {  
	                    pwdPlaceholder.show();  
	                    pwdField.hide();  
	                }  
	            });  
	              
	        }  
	    });  
	      
	}
	/*jquery.cipherStrengths*/
	$.fn.CipherStrengths = function(options) {
        var defaults = {
			defBg: '#e8ecef', //默认背景颜色
			lowBg: '#F30',    //弱背景颜色
			midBg: '#FC0',    //中背景颜色
			highBg: '#0C3',    //强背景颜色
			curColor: '#fff',    //当前状态文字颜色
			defColor: '#8a949c',  //默认文字颜色
			textSize: '12px',
			width: null //强度提示层宽度
        };
        var options = $.extend(defaults, options);
        this.each(function() {
            new $.CipherStrengths(this, options);
        });
        return this;
    };
    $.CipherStrengths = function(onput, options) {
		var input = $(onput);
		input.attr('autocomplete', 'off');
		$('body').append('<div style="display:none" id="pwdstrength_'+input.attr('id')+'"></div>');
		var $wrap = $("#pwdstrength_"+input.attr('id'));
		var tarWidth = input.outerWidth() - $wrap.outerWidth() + $wrap.width()
		$wrap.css({
			overflow: 'hidden',
			fontSize: options.textSize,
			position: 'absolute',
			top: input.offset().top + input.outerHeight(),
			left: input.offset().left,
			height: 16,
			width: tarWidth,
			zIndex: 99
		});
		var tempWidth = tarWidth / 3 - 1;
		var BL,BM,BH,TL,TM,TH;
		BL = BM = BH = options.defBg;
		TL = TM = TH = options.defColor;
		input.keyup(function() {
			checkSVal()
        });
		//测试某个字符是属于哪一类
		function charMode(iN) {
			if (iN >= 48 && iN <= 57) //数字  
			return 1;
			if (iN >= 65 && iN <= 90) //大写字母  
			return 2;
			if (iN >= 97 && iN <= 122) //小写  
			return 4;
			else return 8; //特殊字符  
		};
		//计算出当前密码当中一共有多少种模式
		function bitTotal(n) {
			var m = 0;
			for (i = 0; i < 4; i++) {
				if (n & 1) m++;
				n >>>= 1;
			}
			return m;
		};
		//返回密码的强度级别  
		function checkStrong(c) {
			if (c.length <= 5) return 0; //密码太短
			var m = 0;
			for (var i = 0; i < c.length; i++) {
				//测试每一个字符的类别并统计一共有多少种模式
				m |= charMode(c.charCodeAt(i));
			}
			return bitTotal(m);
		};
		function checkSVal(){
			var val = input.val();
			if (val == null || val == '' || val.length <= 5) {
				BL = BM = BH = options.defBg;
				TL = TM = TH = options.defColor;
				$wrap.hide();
			} else {
				var s = checkStrong(val);
				switch (s) {
				case 0:
					BL = BM = BH = options.defBg;
					TL = TM = TH = options.defColor;
					break;
				case 1:
					BL = options.lowBg;
					TL = options.curColor;
					break;
				case 2:
					BL = options.lowBg;
					BM = options.midBg;
					TL = options.curColor;
					TM = options.curColor;
					break;
				case 3:
					BL = options.lowBg;
					BM = options.midBg;
					BH = options.highBg;
					TL = options.curColor;
					TM = options.curColor;
					TH = options.curColor;
					break;
				default:
					break;
				};
				var sty = 'width:'+tempWidth+'px;float:left; margin-right:1px; text-align: center; height:16px; line-height:normal; vertical-align:text-top; overflow:hidden';
				var html = '<span style="'+sty+'; background:'+BL+';color:'+TL+'">弱</span>';
				html += '<span style="'+sty+'; px;background:'+BM+';color:'+TM+'">中</span>';
				html += '<span style="'+sty+'; px;background:'+BH+';color:'+TH+'">强</span>';
				$wrap.html(html).show();
			};
		};
	}
	$.centerSize = function(size){
        var view = {
                width   : $(window).width(),
                height  : $(window).height()
            },
            scrollTop = $(window).scrollTop();
        return {
            top: Math.max(scrollTop + (view.height - size.height)/2, 0),
            left:Math.max((view.width - size.width)/2, 0)
        };
    };
    $.center = function(el){
        el = $(el);
        el.css('position','absolute')
            .css($.centerSize({
                width   : el.outerWidth(),
                height  : el.outerHeight()
            }));
    };
    //获取url的参数值
    $.getUrlParam = function(name)
	{
		var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
		var r = window.location.search.substr(1).match(reg);
		if (r!=null) return unescape(r[2]); return null;
	}
})(jQuery)

/**
 * 通用蒙版,用于遮罩页面; 
 * 使用方法: 
 * 	打开: mask.on(); 关闭: mask.off()
 * 	
 * 注意: 蒙版的z-index默认为1000,可以通过mask.zIndex来修改;
 */
   
var mask = {
	isOpen: false,
	isIe6: $.browser.msie && ($.browser.version < 7),
	zIndex: 2000,
	on: function(){
		if (mask.isOpen) {
			return false;
		};
		mask.isOpen = true;
		var dom = mask.dom = $('#theMask');
		if(!dom[0]){
			dom = mask.dom = $('<div id="theMask"></div>')
				.css({
					position:'absolute',
					top: 0,
					left:0,
					width:'100%',
					background:'#000',
					opacity:0.3,
					display:'none'
				});
			dom.appendTo($(document.body));
			$(window).resize(function(){
                mask._resize();
            });
		};
		this._resize();
		mask.isIe6 && $('select').hide();
		dom.show();
	},
	_resize: function(){
	    mask.dom.css({
            width:$(document).width(), 
            height:$(document).height(),
            zIndex:mask.zIndex
        });
	},
	off: function(){
		var dom = $('#theMask')
		if(!dom[0]){
			return false;
		}
		mask.isOpen = false;
		mask.isIe6 && $('select').show();
		dom.hide();
	}
};


var PopLayer = function(options){
	options = options || {};
	var me = this,
        defaultFn = function(){return true;};
	me.options = $.extend({
		beforeOpen : defaultFn,
        beforeClose: defaultFn,
		afterClose: defaultFn,
        ok: defaultFn,
        cancel: defaultFn
        ,iframe: false
	},options);
    options.trigger && (me.trigger = $(options.trigger));
    options.target && (me.target = $(options.target));

	me.init();
	//me.trigger = $(trigger);
	//me.target = $(target);
};

$.extend(PopLayer.prototype, {
	init: function(){
		this._initEvent();
	},
	_initEvent: function(){
		var me = this, o = me.options, root, isIframe;
		me.trigger && me.trigger.click(function(evt){
            me.open();
            evt.preventDefault();
            //evt.stopPropagation();    
        });
        if(me.target){
            isIframe = o.iframe && me.target[0].tagName.toLowerCase() == 'iframe';
            //去掉Iframe模式的支持, 因为IE6下会报告权限错误.
            if(isIframe){
                return;
            }
            function bindAction(){
                //root = isIframe ? $(me.target[0].contentWindow.document) : me.target;
                root = me.target;
                root.find('.close, .cancel').click(function(evt){
                    o.cancel();
                    me.close();
                    evt.preventDefault();
                });
                root.find('.ok').click(function(evt){
                    o.ok();
                    me.close(); 
                    evt.preventDefault();
                });
            }
            //isIframe ? me.target.load(bindAction) : bindAction();
            bindAction();
        }
	},
	open: function(){
        var me = this, o = me.options;
        if(o.beforeOpen()){
            me.update();
        }
	},
	close: function(){
        var me = this, o = me.options;
        if(o.beforeClose()){
            mask.off();
            me.target.hide();
			var target = me.target.get(0);
			if(target && target.tagName && target.tagName.toUpperCase()=='IFRAME'){
				me.target.remove();
			}
            o.afterClose();
			try
			{
				me.loading.remove();
			}
			catch(e)
			{}
            PopLayer.current = null;
        }
	},
	update: function(){
        PopLayer.current = this;
        mask.on();
        this.target.show();
        $.center(this.target);
	}
});

PopLayer.iframe = function(url){
    var rtn, 
        loading = $('<img width="48" src="/static/img/loading.gif?v=1">').css("zIndex",mask.zIndex+1),
        frame = $('<iframe class="dialog" frameborder="0" scrolling="no"></iframe>')
        .css({position:'absolute',left:-9999, top:-9999, zIndex:9999, width:1, height:1})
        .appendTo(document.body)
        .attr('src', url);
    rtn = new PopLayer({target:frame, iframe:true});
    
    frame.load(function(){
        var size, 
            win = frame[0].contentWindow,
            oriSize = {
                width : frame.width(),
                height: frame.height()
            };
        frame.css({width:1, height:1});
        //去掉0和1的影响
        function fixSize(a, b){
            return a > 1 ? a : b;
        }
        size = {
            //body.scrollWidth 用来解决ie下document与浏览器大小相同的问题
            width : fixSize( $(win.document.body)[0].scrollWidth, $(win.document).outerWidth()),
            height: fixSize( $(win.document.body)[0].scrollHeight, $(win.document).outerHeight())
        };
        frame.css(oriSize);
        frame.animate($.extend(size, $.centerSize(size)), {duration:200});
        loading.remove();
    });
    
    rtn.open();
    $.center(loading.appendTo(document.body));
    PopLayer.current.loading = loading;
    return rtn;
};
PopLayer.close = function(){
    PopLayer.current && PopLayer.current.close();
    if(PopLayer.afterClose){
        PopLayer.afterClose();
    }
};
/**
 * @param size: {width:VAL, height:VAL}
 */
PopLayer.resize = function(size){
    if(PopLayer.current){
        PopLayer.current.target.animate($.extend(size, $.centerSize(size)), {duration:200});
        PopLayer.current.loading.remove();
    }
}

$(function(){ 
	$("[data-tip]").live("mouseenter",function(){ 
		var _this = $(this),_dataTip,
			_html = ""; 
			$("#data-tip").remove();
			if(_this.attr('data-tip')!=""){ 
				_html += "<div class='data-tip border-e5' id='data-tip'><p class='txt'>"+_this.attr('data-tip')+"</p></div>";
				$("body").append(_html);
				_dataTip=$("#data-tip");
			    _dataTip.live("click mouseleave",function(e){ 
			    	if(e.type == "click"){ 
			    		e.stopPropagation ? e.stopPropagation() : e.cancelBubble = true		    		
			    	}
			    	if(e.type == "mouseleave"){
			    		$(this).remove();
			    	}
			    })
				//定位
			var _sTop = _this.offset().top,
				_sLeft = _this.offset().left,
				_sWidth = _this.outerWidth(),
				_sHeight = _this.outerHeight(),
				_tWidth = _dataTip.width() > 200?200:_dataTip.width(),
				_tHeight = _dataTip.height(),
				_tTop = _sTop;
			var _tLeft = _sLeft-(_tWidth - _sWidth)/2;
			var _tipY = $(window).height()-(_sTop + _tHeight),
				_tipX = $(window).width()-(_sLeft + _tWidth);
				if(_tipY<30){ 
					_tTop=_tTop - _sHeight - _tHeight;
				}else{ 
					_tTop=_tTop + _sHeight + 5;
				}
				if(_tipX<30){ 
					//_tLeft=_tLeft - ()
				}
				_dataTip.css({ 
					position:"absolute",
					top:_tTop,
					left:_tLeft,
					width:_tWidth,
					zIndex:100
				});
			}
	})
	$(document).click(function(){ 
		$("#data-tip").remove();
	})
})
/*首页倒计时*/
window.onload = function(){
	if($(".count-down span").length!=0){ 
		$.ajax({ 
			type:"get",
			url:"/zhigou/server_time?rand="+Math.random(),
			dataType:"json",
			success:function(data){ 
				if(data.status==0){ 
					var serverTime=data.cur_time;
					var tag=setInterval(function(){
				      serverTime=serverTime+1;
				      $(".count-down span").each(function(){
				        var obj = $(this);
				        var endTime = new Date(parseInt(obj.attr('end-time')) * 1000);
				        var nMS=endTime.getTime() - serverTime*1000;
				        var myD=Math.floor(nMS/(1000 * 60 * 60 * 24));
				        var myH=Math.floor(nMS/(1000*60*60)) % 24 + myD*24;
				        var myM=Math.floor(nMS/(1000*60)) % 60;
				        var myS=Math.floor(nMS/1000) % 60;
				        if(myH<=0&&myM<=0&&myS<=0){ 
				        	myH=0;
				        	myM=0;
				        	myS=0;
				        	clearInterval(tag);
				        }
				        myH=myH<10?"0"+myH:myH;
				        myM=myM<10?"0"+myM:myM;
				        myS=myS<10?"0"+myS:myS;
				        if(obj.attr("data-type")=="list"){ 
							var str = "<span class='s02'>"+myH+"</span><span class='s03'>"+myM+"</span><span class='s04'>"+myS+"</span>";
				        }else{ 
							var str = myH+"<span>时</span>"+myM+"<span>分</span>"+myS+"<span>秒</span>";
				        }
						obj.html(str);
				      });
				    }, 1000);
				}
			}
		});
	}
}
$(function(){ 
	//下拉
	$(".down-tit").hover(function(){ 
		$(this).find(".down-tip").show();
	},function(){ 
		$(this).find(".down-tip").hide();
	})
})
/*select样式美化*/
$(function(){ 
	if($(".select").length!=0){ 
		$(".select").each(function(){ 
			var $this=$(this);
			var selectHeight=$this.find(".select-option li").height()*$this.find(".select-option li").length;
			selectHeight=selectHeight>280?280:selectHeight;
			$this.find(".select-option").height(selectHeight);
			$this.find(".select-normal").click(function(e){ 
				stopBubble(e);
				$this.find(".select-option").toggleClass("select-on");
			}).end().find(".select-option li").live("mouseover",function(){ 
				$(this).addClass("hover");
			}).live("mouseout",function(){ 
				$(this).removeClass("hover");
			}).live("click",function(){ 
				$this.find(".select-option li").removeClass("on");
				$(this).addClass("on");
				$this.find(".select-option").removeClass("select-on");
				$this.find(".select-normal p").html($(this).html()).css("color","#333");
				$this.find(".select-hidden-value").val($(this).attr("data-value"));
			})
		})
		$("body").live("click",function(){ 
			$(".select-option").removeClass("select-on");
		})
	}
})
function checkNewPhone(phone,tip){ 
	var bool=false,data={mobile:phone};
	function callback(){bool=true;};
	if(checkIsNull(phone)){
		tip.html("请输入有效手机号"); return bool;
	}else if(checkIsNum(phone)){ 
		tip.html("请输入11位有效手机号"); return bool;
	}else if(phone.length!=11){ 
		tip.html("请输入11位有效手机号"); return bool;
	}
	checkAjax("","","/zhigou/account/checkmobileuniq",false,data,callback,tip);
	return bool;
}
/*获取手机验证码,倒计时*/
window.getPhoneVcode=function(){ 
	var phone="",i=59,a=$("#personGetMsgVcode"),url="/zhigou/account/usersmscaptcha";
	if(arguments[0]!=undefined){ 
		if(arguments[2]=="cash"){ 
			phone="";
		}else{ 
			var b=$.trim(arguments[0]);
			if(!checkNewPhone(b,$("#modifyPhoneNew").parent().next())){ 
				return false;
			}else{ 
				phone=b;
			}
		}
	}
	if(arguments[1]!=undefined){ 
		url=arguments[1];
	}
	var data={mobile:phone};
	function callback(){a.next().html("短信已发送至您的手机，请输入短信中的验证码").css({"color":"#666666", "fontSize":"12px"});}
    checkAjax("","",url,true,data,callback,a.next());
    a.find(".num").text(i);
		a.find("a").addClass("none").parent().find("span").removeClass("none");
    	var sign=setInterval(function(){
            a.find(".num").text(i);i--;
            if(i<0){
                clearInterval(sign);
                a.find("span").addClass("none");
                a.find("a").removeClass("none");
                a.next().html("");
            }},1000)
}