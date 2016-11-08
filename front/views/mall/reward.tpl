<{extends file="_pc_base.tpl"}>
<{block name="css"}>
<link rel="stylesheet" type="text/css" href="/css/detail/detail.css"/>
<link rel="stylesheet" type="text/css" href="/css/login/login.css"/>
<{/block}>
<{block name="content"}>
<!--content-->
<!--content-->
    <div class="content">
        <div class="mianbaoxie">
            <span>当前位置:</span>
            <a href="/">首页</a>
            <label>&gt;</label>
            <label>商品详情</label>
        </div>
        <div class="detailtop">
            <div class="boxleft">
                <div class="topleft">
                    <div class="bigpic">
                        <img src="<{$goodInfo.image}>">
                    </div>
                </div>
                <div class="topmid">
                    <div class="midtext">
                        <em>(第<{$indianaGoodInfo.issue}>期)</em>
                        <{$goodInfo.name}>
                    </div>
                    <{if $indianaGoodInfo.status eq 2}>
                    <div class="jiexiao">
                        <span>幸运号码:</span>
                        <em><{$indianaGoodInfo.luck_number|default:''}></em>
                    </div>
                    <div class="xingyun">
                        <div class="userbox">
                            <img src="<{$rewardUserInfo.avatar}>">
                            <span>恭喜 <a href="javascript:void(0)"><{$rewardUserInfo.nick_name|default:''}></a> 获得了本期奖品 </span>
                            <p>揭晓时间: <{$indianaGoodInfo.reward_time|default:''}></p>
                        </div>
                        <div class="userdetailbox">
                            <p>奖品获得者本期总共参与了<em><{count($luckNumbers)}></em>次</p>
                            <div class="numlist">
                                <span>Ta的号码:</span>
                                <{foreach $luckNumbers as $luckNumber}>
                                <label <{if $luckNumber.luck_number eq $indianaGoodInfo.luck_number}>class="on"<{/if}>><{$luckNumber.luck_number}></label>
                                <{/foreach}>
                            </div>
                        </div>
                    </div>
                    <div class="canyu">
                        <{if $indianaGoodInfo.reward_user_id neq $smarty.session.USER_ID}>
                        <!---
                            您没有参与本次夺宝
                        -->
                        很遗憾没有中奖,再接再厉!
                        <{else}>
                        恭喜您,获得本次大奖!
                        <{/if}>
                    </div>
                    <{/if}>
                </div>
                <!---计算公式-->
                <div class="clear"></div>
                <div class="gongshi">
                    <p>·&nbsp;&nbsp;计算公式</p>
                    <div class="gongshibox">
                        <div class="fl red">
                            <span>10000009</span>
                            <em>本期幸运号码</em>
                        </div>
                        <div class="gongshitext">=(</div>
                        <div class="fl">
                            <span>7621752908</span>
                            <label>50个时间求和</label>
                        </div>
                        <div class="gongshitext">%</div>
                        <div class="fl">
                            <span>75</span>
                            <label>该奖品总需人次</label>
                        </div>
                        <div class="gongshitext">)+</div>
                        <div class="fl">
                            <span>10000001</span>
                            <label>原始数</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="topright">
                <div class="xyusertitle">最新一期</div>
                <div class="xyuser">
                    <div class="titlebg"></div>
                    <a href="javascript:void(0)">
                        <div class="xyuserpic"><img src="<{$goodInfo.image}>"> </div>
                        <label>最新一期正在进行,赶快参加吧!</label>

                        <div class="midtext">
                            <em>(第<{$nextGoodInfo.issue}>期)</em>
                            <{$goodInfo.name}>
                        </div>
                    </a>
                    <div class="midrenshu on">
                        <label>总需:<em><{$nextGoodInfo.total_inputs}></em>人次</label>
                        <div class="sprice">
                            <b style="width: 20%"></b>
                        </div>
                        <div class="sprice_user">
                            <b><{$nextGoodInfo.involved_num}></b>
                            <strong><{$nextGoodInfo.total_inputs - $nextGoodInfo.involved_num}></strong>
                        </div>
                        <div class="sprice_xx">
                            <b>已参与人次</b>
                            <strong>剩余人次</strong>
                        </div>
                        <div class="shopnum" item-id="<{$nextGoodInfo.id}>">
                            <label>我要参与:</label>
                            <b class="reduce">-</b>
                            <input type="tel" name="" id="" value="1" />
                            <b class="plus">+</b>
                            <label>人次</label>
                        </div>
                        <div class="midbtn">
                            <a href="javascript:void(0)" class="ljdb">立即夺宝</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--cont-->
        <div class="detailcontent">
            <div class="conttitle">
                <div class="titleborder">
                    <span class="on">所有参与记录</span>
                    <span>晒单</span>
                    <span>计算结果</span>
                </div>
            </div>
            <!--所有参与记录-->
            <div class="contlist joinhistory" style="display: block">
                <{if !empty($partRecords)}>
                <{foreach $partRecords as $partTime => $partRecord}>
                <div class="contdate">
                    <label><{$partTime}></label>
                    <em></em>
                </div>
                <div class="viewuserbox">
                    <!---list--->
                    <{foreach $partRecord as $record}>
                    <div class="viewuserlist">
                        <label>
                            <{$record.create_time}>
                            <b><strong></strong></b>
                        </label>
                        <div class="listright">
                            <p>
                                <img src="<{$record.user_avatar}>">
                                <a href="javascript:void(0)" target="_blank"><{$record.user_name}></a>
                                (<{$record.ip}>)参与了
                                <em><{$record.num}>人次</em>
                                <b>所有夺宝号码</b>
                            </p>
                            <{if !empty($orderLuckNumbers[$record.id])}>
                            <div class="hiddenbox">
                                <{foreach $orderLuckNumbers[$record.id] as $tmp}>
                                <span><{$tmp.luck_number}></span>
                                <{/foreach}>
                            </div>
                            <{/if}>
                        </div>
                    </div>
                    <{/foreach}>
                    <!---list end--->

                </div>
                <{/foreach}>
                <div class="xypages down">
                    <a href="javascript:void(0)">下一页</a>
                    <a href="javascript:void(0)" class="on">上一页</a>
                </div>
                <{/if}>
            </div>
            <!--晒单-->
            <div class="contlist viewpic">
                <{if !empty($displayOrders)}>
                <{foreach $displayOrders as $order}>
                <!---list-->
                <div class="viewlist">
                    <div class="viewpicbox">
                        <a href="javascript:void(0)" target="_blank">
                            <img src="<{$order.user_avatar}>">
                            <label><{$order.user_name}></label>
                        </a>
                    </div>
                    <div class="viewtext">
                        <div class="texttitle">
                            <span><{$order.title}></span>
                            <label><{$order.create_time}></label>
                        </div>
                        <div class="textcont">
                            <a href="/member/display-order-detail?id=<{$order.id}>" target="_blank">
                                <label>(第<{$order.issue}>期)<{$order.good.name|default:''}></label>
                                <p><{$order.comment}></p>
                                <div class="clear"></div>
                                <img src="<{$order.image}>">
                            </a>
                        </div>
                    </div>
                </div>
                <{/foreach}>
                <!---list end-->

                <div class="xypages down">
                    <a href="javascript:void(0)">下一页</a>
                    <a href="javascript:void(0)" class="on">上一页</a>
                </div>
                <{/if}>
            </div>
                <div class="contlist over">
                    <div class="guize">
                        <div class="guizered">幸运号码<br />计算规则</div>
                        <div class="guizetext">
                            <p><em>1</em>截止该奖品开奖时间点前本站全部奖品的最后50参与时间所代表数值之和；</p>
                            <p><em>2</em>将这50个时间的数值进行求和（得出数值A）（每个时间按时、分、秒、毫秒的顺序组合，如20:15:25.123则为201525123）；</p>
                            <p><em>3</em>数值B为奖品所需人次；；</p>
                            <p><em>4</em>数值A除以数值B得到的余数+原始数10000001，得到最终幸运号码，拥有该幸运号码者，直接获得该奖品；</p>
                            <p><em>5</em>余数是指整数除法中被除数未被除尽部分， 如7÷3 = 2 ......1，1就是余数 ；</p>
                            <p class="on"><em>注</em>消费者在娱乐同时，应该结合自身实际情况进行适当的消费。</p>
                        </div>
                    </div>
                    <div class="listbox">
                        <div class="listth">
                            <span>夺宝时间</span>
                            <label>昵称</label>
                        </div>
                        <div class="listtip">截止该奖品最后夺宝时间【<{$indianaGoodInfo.end_time|date_format:"%Y-%m-%d %H:%i:%s"}>】最后50条全站参与记录</div>
                        <{if !empty($statPartRecords)}>
                        <div class="zuihou">
                            <{foreach $statPartRecords[0] as $statRow}>
                            <div class="zuihoulist">
                                <span><{$statRow.part_date}></span>
                                <p>
                                    <em><{$statRow.part_time}></em>
                                    <i><{$statRow.part_time_display}></i>
                                </p>
                                <a href="javascript:void(0)" target="_blank"><{$statRow.user.nick_name}></a>
                            </div>
                            <{/foreach}>
                        </div>
                        <{/if}>
                        <div class="jisuantext">
                            <label>计算结果</label>
                            <div class="jieguotext">
                                <p>1、求和：7621752908(上面50条参与记录的时间取值相加)</p>
                                <p>2、奖品总需人次：75</p>
                                <p>3、求余：7621752908 % <em>7</em><em>5</em>(奖品所需人次) =<span>8</span> (余数) </p>
                                <p>4、<span>8</span> (余数) + 10000001 = <span>1</span><span>0</span><span>0</span><span>0</span><span>0</span><span>0</span><span>0</span><span>9</span></p>
                                <i>幸运号码：10000009</i>
                            </div>
                        </div>
                        <{if !empty($statPartRecords[1])}>
                        <div class="zuihou noselect">
                            <{foreach $statPartRecords[1] as $row}>
                            <div class="zuihoulist">
                                <span><{$row.part_date}></span>
                                <p>
                                    <em><{$row.part_time}></em>
                                </p>
                                <a href="javascript:void(0)" target="_blank"><{$row.user.nick_name}></a>
                            </div>
                            <{/foreach}>
                        </div>
                        <{/if}>
                    </div>
                </div>



            </div>
        </div>
        <!--cont end-->

    </div>
<!--content end-->


<!----login---->
    <div class="openloginbox">
        <div class="openbg"></div>
        <div class="detaillogin">
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
            <div class="loginbtn"><a href="javascript:void(0)">登录</a></div>
        </div>
    </div>
<!----login end---->
<{/block}>
<{block name="script"}>
    <script src="/js/detail/detail.js" language="JavaScript" type="text/javascript"></script>
<{/block}>