$(function(){ 
	//添加银行卡
	//if($("#bankAdd").length!=0){ 
		var $window=$(window),
			bankAdd=$("#bankAdd"),
			bankAddTip=$("#bankAddTip"),
			bankAddCancel=$("#bankAddCancel"),
			bankAddBtn=$("#bankAddBtn"),
			bankAddHide=$("#bankAddHide"),
			provinceHide=$("#provinceHide"),
			cityHide=$("#cityHide"),
			bankAddCard=$("#bankAddCard"),
			bankAddError=$("#bankAddError");
		bankAdd.live("click",function(){ 
			bankAddTip.slideDown();
			var tipH=bankAddTip.offset().top+bankAddTip.height();
			var H=$window.height()-tipH;
			if(H<0){ 
				$("body,html").animate({ 
					scrollTop:bankAddTip.offset().top
				});
			}
		})
		bankAddCancel.click(function(){ 
			bankAddTip.slideUp();
		})
		bankAddBtn.click(function(){ 
			var val=bankAddHide.val(),
				province=provinceHide.val(),
				city=cityHide.val(),
				card=$.trim(bankAddCard.val());
			if(province==""||province==null){ 
				bankAddError.html("请选择开户省份");
				return false;
			}else if(city==""||city==null){ 
				bankAddError.html("请选择开户城市");
				return false;
			}
			else if(val==""||val==null){ 
				bankAddError.html("请选择开户银行");
				return false;
			}
			else if(card==""||card==null){ 
				bankAddError.html("请填写银行卡号");
				return false;
			}
			else if(checkIsNum(card)||checkNumLessZero(card)||card.length<8){ 
				bankAddError.html("请填写正确的银行卡号");
				return false;
			}
			else{ 
				bankAddError.html("");
				$.ajax({ 
					type:"post",
					url:"/zhigou/account/bindbankcard",
					dataType:"json",
					data:{ 
						bank_code:val,
						province:province,
						city:city,
						bank_card_no:card
					},
					beforeSend:function(){ 
						bankAddBtn.hide();
						$("#bankAddBtnLoading").css("display","inline-block");
					},
					success:function(result){ 
						bankAddBtn.show();
						$("#bankAddBtnLoading").hide();
						if(result.status==0){ 
							bankAddError.html("添加成功");
							window.location.href=window.location.href;
						}
						else{ 
							bankAddError.html(result.msg);
						}
					},
					error:function(){ 
						bankAddError.html("添加失败，请刷新重试");
					}
				});
			}
		})

	//城市绑定
	if($("#selectProvince").length!=0){ 
		var provinceHtml="";
		if(province_city_list.province!=null){ 
			$(province_city_list.province).each(function(k,v){
				provinceHtml+='<li data-value="'+v+'">'+v+'</li>';
			});
		}
		$("#selectProvince").append(provinceHtml);
		var selectProvinceHeight=$("#selectProvince li").height()*$("#selectProvince li").length;
		selectProvinceHeight=selectProvinceHeight>280?280:selectProvinceHeight;
		$("#selectProvince").height(selectProvinceHeight);
		
		$("#selectProvince li").live("click",function(){ 
			$("#selectCity").parent().find(".select-normal p").html("城市").css("color","#ccc").end().find(".select-hidden-value").val("");
			var cityHtml="",citymap=province_city_list.citymap;
			if(citymap[$(this).attr("data-value")]!=null){ 
				$(citymap[$(this).attr("data-value")]).each(function(k,v){ 
					cityHtml+='<li data-value="'+v+'">'+v+'</li>';
				})
			}
			$("#selectCity").html(cityHtml);
			var selectCityHeight=$("#selectCity li").height()*$("#selectCity li").length;
			selectCityHeight=selectCityHeight>280?280:selectCityHeight;
			$("#selectCity").height(selectCityHeight);
		})
	}
	//}
	//删除银行卡
	if($("#accountIndexBank").length!=0){ 
		$("#accountIndexBank .block").hover(function(){ 
			$(this).find(".account-bank-del").removeClass("none");
		},function(){ 
			$(this).find(".account-bank-del").addClass("none");
		})
		$("#accountIndexBank .account-bank-del").bind("click",function(){ 
			var $this=$(this);
			$.ajax({ 
				type:"post",
				url:"/zhigou/account/delbankcard",
				dataType:"json",
				data:{ 
					sub_bank_card_no:$this.attr("data-card-no"),
					bank_code:$this.attr("data-bank-code")
				},
				success:function(result){ 
					if(result.status==0){ 
						$this.closest(".block").remove();
						if($("#accountIndexBank .block").length<=2&&$("#bankAdd").length==0){ 
							$('<a class="bank-add block border-e5 wrap-left" href="javascript:;" id="bankAdd"><i class="sprite sprite-add-bank pngfix"></i>添加银行卡</a>').insertBefore($("#bankAddTip"));
						}
					}
					else{ 
						alert(result.msg);
					}
				},
				error:function(){ 
					alert("删除失败，请刷新重试");
				}
			});
		})
	}
	if($("#accountBank").length!=0){ 
		var accountBankBlock=$("#accountBank .block").not(".bank-add");
		accountBankBlock.click(function(){ 
			accountBankBlock.removeClass("block-on");
			$(this).addClass("block-on");
			$("#accountBankTip").html("");
		});
	}
	//充值
	if($("#rechargeBtn").length!=0){ 
		function checkRechargeCash(){ 
			var rechargeCash=$.trim($("#rechargeCash").val());
			var rechargeCashTip=$("#rechargeCashTip");
			if(rechargeCash==""||rechargeCash==null){ 
				addCash();
				rechargeCashTip.html("请输入充值金额");return false;
			}else if(checkIsNum(rechargeCash)){ 
				rechargeCashTip.html("金额需要数字");return false;
			}else if(checkNumLessZero(rechargeCash)){ 
				rechargeCashTip.html("充值金额应大于等于1元");return false;
			}else if(parseFloat(rechargeCash)<1){ 
				rechargeCashTip.html("充值金额应大于等于1元");return false;
			}else if(!/^\d+(\.\d{1,2})?$/.test(rechargeCash)){ 
				rechargeCashTip.html("充值金额小数点后不超过2位");return false;
			}else{
				rechargeCashTip.html("");return true;
			}
		}
		function addCash(){ 
			var canCash=$("#canCash").attr("data-value");
			var rechargeCash=$.trim($("#rechargeCash").val());
			if(rechargeCash==""||rechargeCash==null){ 
				var total=parseFloat(canCash);
			}else{ 
				var total=parseFloat(canCash)+parseFloat(rechargeCash);
			}
			var finalTotal=getIntFloat(total.toFixed(2));
			var html='<span class="cash-num">'+finalTotal[0]+'</span><span class="cash-ling">.'+finalTotal[1]+' 元</span>';
			$("#afterCash").html(html);
		}
		$("#rechargeCash").blur(function(){
			checkRechargeCash();
		}).keyup(function(){ 
			if(checkRechargeCash()){  
				addCash();
			}
		})
		function checkRechargeBankList(){ 
			var rechargeBankListTip=$("#rechargeBankListTip");
			if($("#rechargeBankList li").hasClass("on")){ 
				var bankCode=$("#rechargeBankList li.on").attr("data-code");
				rechargeBankListTip.html("");return true;
			}else{ 
				rechargeBankListTip.html("请选择银行");return false;
			}
		}
		window.checkRecharge=function(){ 
			var bool1=checkRechargeCash();
			var bool2=checkRechargeBankList();
			if(!(bool1&&bool2)){ 
				$("body,html").animate({ 
					scrollTop:200
				});
				return false;
			}else{ 
				PopLayer.iframe('/zhigou/frame-recharge');
				return true;
			}
		}
		window.removeBankList=function(){ 
			$("#rechargeBankList li").removeClass("on");
		}
		$("#rechargeBankList li").click(function(){ 
			var $this=$(this),$des=$("#rechargeBankDes");
			$("#rechargeBankList li").removeClass("on");
			$this.addClass("on");
			var val=$this.data("data-html");
			$("#rechargeBankListTip").html("");
			$("body,html").animate({ 
				scrollTop:400
			});
			$("#hideBankList").val($this.attr("data-code"));
			if(typeof(val)!="undefined"){ 
				$des.html(val);
			}else{
				$.ajax({ 
					type:"post",
					url:"/zhigou/bank-cash-limit",
					dataType:"json",
					beforeSend:function(){ 
						$des.html("<img class='loading' src='/static/money/main/img/loading.gif' />");
					},
					data:{ 
						bank_code:$this.attr("data-code")
					},
					success:function(result){ 
						if(result.status==0){ 
							$this.data("data-html",result.data);
							$des.html(result.data);
						}
					}
				})
			}
		})

	}
	//提现提交
	if($("#cashBtn").length!=0){ 
		function checkPlanCash(){ 
			var canCash=$.trim($("#canCash").attr("data-value")),
			planCash=$.trim($("#planCash").val()),planCashTip=$("#planCashTip");
			if(planCash==""||planCash==null){ 
				planCashTip.html("请输入金额");return false;
			}else if(checkIsNum(planCash)){ 
				planCashTip.html("金额需要数字");return false;
			}else if(checkNumLessZero(planCash)){ 
				planCashTip.html("金额需要大于0");return false;
			}else if(!/^\d+(\.\d{1,2})?$/.test(planCash)){ 
				planCashTip.html("小数点后不超过2位");return false;
			}else if(canCash-planCash<0){ 
				planCashTip.html("您的账户余额不足");return false;
			}else{ 
				planCashTip.html("");return planCash;
			}
		}
		function checkPayPwdCash(){ 
			var payPwdCash=$.trim($("#payPwdCash").val()),payPwdCashTip=$("#payPwdCashTip");
			if(payPwdCash==""||payPwdCash==null){ 
				payPwdCashTip.html("请输入交易密码");
				return false;
			}
			else{ 
				payPwdCashTip.html("");
				return payPwdCash;
			}
		}
		function checkCashVcode(){ 
			var cashVcode=$.trim($("#cashVcode").val()),cashVcodeTip=$("#cashVcodeTip");
			if(cashVcode==""||cashVcode==null){ 
				cashVcodeTip.html("请输入短信验证码").css("color","#f33");
				return false;
			}
			else if(cashVcode.length!=6){ 
				cashVcodeTip.html("请输入6位数字验证码").css("color","#f33");
				return false;
			}
			else{ 
				cashVcodeTip.html("").css("color","#666");;
				return cashVcode;
			}
		}
		$("#planCash").blur(function(){ 
			checkPlanCash();
		}).focus(function(){ 
			$("#planCashTip").html("");
		})
		$("#payPwdCash").blur(function(){ 
			checkPayPwdCash();
		}).focus(function(){ 
			$("#payPwdCashTip").html("");
		})
		$("#cashVcode").blur(function(){ 
			checkCashVcode();
		}).focus(function(){ 
			$("#cashVcodeTip").html("");
		})
		$("#cashBtn").click(function(){ 
			var accountBankTip=$("#accountBankTip");
			var bool1=false,bool2=checkPlanCash(),bool3=checkPayPwdCash(),bool4=checkCashVcode();
			if($("#accountBank .block-on").length==0){ 
				accountBankTip.html("请选择银行卡");
				$("body,html").animate({ 
					scrollTop:130
				});
				bool1=false;
			}
			else{ 
				accountBankTip.html("");
				bool1=true;
			}
			
			if(bool1&&bool2&&bool3&&bool4){ 
				$.ajax({ 
					type:"post",
					url:"/zhigou/netbank-cash",
					dataType:"json",
					beforeSend:function(){ 
						$("#cashBtn").hide().next().removeClass("none");
					},
					data:{ 
						cash_amount:bool2,
						bank_code:$("#accountBank .block-on").attr("data-code"),
						sub_card_no:$("#accountBank .block-on").attr("data-card-no"),
						pay_pwd:bool3,
						captcha:bool4
					},
					success:function(result){ 
						if(result.status==0){ 
							window.location.href="/zhigou/withdraw-success";
						}else{ 
							$("#cashVcodeTip").html(result.msg).css("color","#f33");
						}
						$("#cashBtn").show().next().addClass("none");
					},
					error:function(){ 
						$("#cashVcodeTip").html(error.serverError).css("color","#f33");
					}
				});
			}
		})
	}
	//存钱罐收益chart
	//window.rate_charts = [{"name":"七日年化收益率","data":[2.36,3.34,4.28,5.07,6.15,7.26,5.33],"categories":["10-09","10-10","10-11","10-12","10-13","10-14","10-15"]}];
	//window.ave_rate = [[1415462400000,2.36],[1415548800000,3.34],[1415635200000,4.28],[1415721600000,5.07],[1415808000000,6.15],[1415894400000,7.26],[1415980800000,5.33]];
	if($("#annualRevenue").length!=0){ 
		piggyChart("#annualRevenue",rateCharts,3,6,0);
	}
	if($("#millionIncome").length!=0){ 
		piggyChart("#millionIncome",incomeCharts,0.5,3,1);
	}
	function piggyChart(el,data,min,max,mark){ 
		/*$(el).highcharts({
	        chart:{
	        	spacingTop:0,
	            plotBorderColor:'#e2e2e2',
	            plotBorderWidth:1,
	            spacingBottom:0,
	            margin: [15, 1, 30, 40]
	        },
	        title:{text:""},
	        credits: {
	            enabled:false
	        },
	        xAxis:{
	            type:"datetime",
	            title:{text:""},
	            fontSize:'9pt',
	            labels:{
	                formatter:function(){
	                    return Highcharts.dateFormat("%m-%d",this.value)
	                }
	                //step: 2
	                //align:"left"
	            },
	            //categories:[0,1,2,3,4,5,6],
	            lineColor:"#e2e2e2",
	            showFirstLabel:true,
	            showLastLabel:true,
	            offset: 0,
	            tickLength: 0,
	            tickPosition: "inside",
	            tickColor:"#e2e2e2",
	            tickPixelInterval:50,
	            tickmarkPlacement:"on",
	            gridLineWidth:0,
	            gridLineColor:"#e2e2e2"
	        },
	        yAxis:{
	            title:{text:""},
	            labels:{
	                format:"{value}%"
	            },
	            lineColor:"#e2e2e2",
	            offset:0,
	            tickLength: 0,
	            tickPosition: "inside",
	            tickColor:"#e2e2e2",
	            gridLineWidth:1,
	            gridLineColor:"#e2e2e2",
	            tickInterval: 2.5,
	            min: 0,
	            max:7.5
	        },
	        tooltip:{
	            formatter:function(){
	                return Highcharts.dateFormat("%m-%d",this.x) +'<br/>'+ this.y +'%';
	            }
	        },
	        legend:{
	            enabled:!1,
	            align:"center",
	            verticalAlign:"top",
	            floating:!0,
	            borderWidth:0
	        },
	        plotOptions: {
	            line: {
	                dataLabels: {
	                    enabled: false
	                },
	                enableMouseTracking: true,
	                marker:{
	                    enabled: true,
	                    radius:2,
	                    lineColor:"#ff0000",
	                    fillColor:"#ff0000"
	                },
	                lineWidth:1,
	                lineColor:"#ff0000"
	            }
	        },
	        series:[{
	            type:"line",
	            name:name,
	            data:data
	        }]
	    });*/

		$(el).highcharts({ 
	        chart: {
	            type: 'spline',
	            spacingTop:0,
	            plotBorderColor:'#bbbbbb',
	            plotBorderWidth:1,
	            spacingBottom:0,
	            margin: [ 15, 1, 30, 40]
	        },
	        title: {
	            text: ''
	        },
	        subtitle: {
	            text: ''
	        },
	        credits: {
	            enabled:false
	        },
	        xAxis: {
	            categories: data[0].categories,
	            lineWidth:1,
	            lineColor:"#bbb",
	            tickInterval:2,
	            tickmarkPlacement:"on",
	            gridLineWidth:0,
	            showFirstLabel:true,
	            showLastLabel:true,
	            labels:{ 
	            	align:"center"
	            }
	        },
	        yAxis: {
	            title: {
	                text: ''
	            },
	            lineColor:"#bbb",
	            tickInterval: 1,
	            min: min,
	            max:max,
	            labels:{
	                formatter:function(){
	                	if(mark==1){ 
	                    	return Highcharts.numberFormat(this.value,1,'.')
	                	}
	                	else{ 
	                    	return Highcharts.numberFormat(this.value,1,'.')+"%"
	                	}
	                }
	            }
	        },
	        legend: {
	            enabled: false
	        },
	        tooltip: {
	            enabled: true,
	            backgroundColor:"#a40000",
	            borderColor:"#a40000",
	            style:{ 
	            	"color":"#fff"
	            },	
	            formatter: function() {
	            	if(mark==1){
	                	return '<b>'+ this.series.name +'</b><br/>日期：'+  this.x + '<br/>收益：'+this.y;
	            	}
	            	else{ 
	                	return '<b>'+ this.series.name +'</b><br/>日期：'+  this.x + '<br/>利率：'+this.y+'%';
	            	}
	            }
	        },
	        plotOptions: {
	            spline: {
	                dataLabels: {
	                    enabled: false
	                },
	                enableMouseTracking: true,
	                marker:{
	                    enabled: true,
	                    radius:2,
	                    lineColor:"#ff0000",
	                    fillColor:"#ff0000"
	                },
	                lineWidth:1,
	                lineColor:"#ff0000"
	            }
	        },
	        series: data
	    });
	}
	function getPiggyDetail(beginDate,endDate,url){ 
		$.ajax({ 
			type:"post",
			url:url,
			data:{ 
				begindate:beginDate,
				enddate:endDate
			},
			beforeSend:function(){ 
				$("#piggyDetail").html("<img class='loading' src='/static/money/main/img/loading.gif' />");
			},
			dataType:"json",
			success:function(result){ 
				if(result.status==0){ 
					$("#piggyDetail").html(result.data);
					if(piggyDetailTag!=1){ 
						$("body,html").scrollTop($(".piggy-detail table").offset().top-50);
					}
					piggyDetailTag+=1;
				}
			}
		})
	}
	$("#piggyDetail .page a").live("click",function(e){ 
		stopDefault(e);
		var href=$(this).attr("href");
		var start=$.trim($("#beginDate").val());
		var end=$.trim($("#endDate").val());
		getPiggyDetail(start,end,href);
	})
	$("#piggyCha").live("click",function(){　
		var start=$.trim($("#beginDate").val());
		var end=$.trim($("#endDate").val());
		if(start==""){ 
			alert("请选择开始日期"); return false;
		}
		if(end==""){ 
			alert("请选择结束日期"); return false;
		}
		getPiggyDetail(start,end,"/zhigou/accountincomelist");
	})
	if($("#piggyDetail").length!=0){ 
		var piggyDetailTag=1;
		getPiggyDetail("","","/zhigou/accountincomelist");
	}

	/*我的投资*/
	$(".payment-plan").click(function(){ 
		var $this=$(this);
		if($this.children("i").hasClass("sprite-down1-jiao")){ 
			$this.children("i").removeClass("sprite-down1-jiao").addClass("sprite-up1-jiao");
			var $planCon=$this.parent().parent().next(".li6");
			var productId=$this.attr("data-pro");
            var orderId=$this.attr("data-order");
			var val=$planCon.find("tbody").data("data-html");
			if(typeof(val)=="undefined"){ 
				$.ajax({ 
					type:"post",
					url:"/zhigou/account/repaymentdetailapi",
					data:{product_id:productId,order_id:orderId},
					beforeSend:function(){ 
						$planCon.find("tbody").html("<tr><td colspan='6'><img style='display:block;margin:30px auto;' src='/static/money/main/img/loading.gif' /></td></tr>");
					},
					dataType:"json",
					success:function(result){ 
						if(result.status==0){ 
							var html="";
							if(result.data && result.data.length!=0){
								for(var i=0; i<result.data.length;i++){ 
									html+='<tr><td class="td1">'+result.data[i].period+'/'+result.data[i].period_total+'</td><td>'+result.data[i].due_amount+'</td><td>'+result.data[i].due_time+'</td><td>'+result.data[i].repay_time+'</td><td>'+result.data[i].overdue_fee+'</td><td>'+result.data[i].status+'</td></tr>';
								}
							}else{ 
								html+="<tr><td colspan='6'><p style='text-align:center;padding:15px 0;'>暂无回款计划</p></td></tr>";
							}
						}
						$planCon.find("tbody").html(html).data("data-html",html);
					}
				});
			}else{ 
				$planCon.find("tbody").html(val);
			}
			$planCon.slideDown().end().parent().css("background","#fbfbfb");
		}
		else{ 
			$this.children("i").addClass("sprite-down1-jiao").removeClass("sprite-up1-jiao");
			$this.parent().parent().next(".li6").slideUp().end().parent().css("background","#fff");
		}
	})
	/*交易记录*/
	var payRec={url:"/zhigou/account/paymentrecordapi/p1"};
	$(".payRecordDate a").click(function(){ 
		$(".payRecordDate a").removeClass("on");
		$("#payStart").val("");
		$("#payEnd").val("");
		$(this).addClass("on");
		getPayRecord(payRec.url);
	});
	$("#payRecordType dd a").click(function(){ 
		$("#payRecordType dd a").removeClass("on");$(this).addClass("on");
		var url=""
		getPayRecord(payRec.url);
	});
	$("#payEnd").change(function(){ 
		$(this).blur();
		$(".payRecordDate a").removeClass("on");
		var payStart=$.trim($("#payStart").val());
		if(payStart==""||payStart==null){ 
			alert("请选择起始日期");
		}else{getPayRecord(payRec.url);}
	})
	$("#payStart").change(function(){ 
		$(this).blur();
		$(".payRecordDate a").removeClass("on");
		var payEnd=$.trim($("#payEnd").val());
		if(payEnd==""||payEnd==null){ 
			//alert("请选择起始日期");
		}else{getPayRecord(payRec.url);}
	})
	function getPayRecord(url){   //page分页
		var start=$.trim($("#payStart").val());
		var end=$.trim($("#payEnd").val());
		var transType=$("#payRecordType dd a.on").attr("data-record");
		var month=$(".payRecordDate a.on").attr("data-record");
		var payRecordList=$("#payRecordList");
		$.ajax({ 
			type:"post",
			url:url,
			data:{ 
				start:start,
				end:end,
				trans_type:transType,
				month:month
			},
			dataType:"html",
			beforeSend:function(){ 
				payRecordList.html("<img style='display:block;margin:30px auto;' src='/static/money/main/img/loading.gif' />");
			},
			success:function(result){ 
				payRecordList.html(result);
			}
		});
	}
	if($("#payRecordList").length!=0){ 
		getPayRecord(payRec.url);
	}
	$("#payRecordList .page a").live("click",function(e){ 
		stopDefault(e);
		var url=$(this).attr("href");
		getPayRecord(url);
	})
	/*合同点击事件*/
	$('.contractBtn').on('click',function(){
		$('.fuceng-blog').bPopup({closeClass: 'no-click'});
	});

	$('.sure-click').on('click',function(){
		var url = $('.xuanze-ht').find('input[type=radio]:checked').parent().attr('data-href');
		$(this).attr('href', url);
	});


});
