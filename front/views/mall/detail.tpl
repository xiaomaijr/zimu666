<{extends file="_pc_base.tpl"}>
<{block name="css"}>
<link rel="stylesheet" type="text/css" href="/css/detail/detail.css"/>
<{/block}>
<{block name="content"}>
<!--content-->
<div class="content">
    <div class="mianbaoxie">
        <span>当前位置:</span>
        <a href="/">首页</a>
        <label>&gt;</label>
        <label>商品详情</label>
    </div>
    <div class="detailtop">
        <div class="topleft">
            <div class="bigpic">
                <img src="<{$goodInfo.image}>">
            </div>
            <{if !empty($goodInfo.cover_images)}>
            <div class="smallpic">
                <{foreach $goodInfo.coverImages as $smllImage}>
                <span class="on">
                    <img src="<{$smllImage}>">
                    <em></em>
                </span>
                <{/foreach}>
            </div>
            <{/if}>
        </div>
        <div class="topmid">
            <div class="midtext">
                <em>(第<{$indianaGoodInfo.issue}>期)</em>
                <{$goodInfo.name}>
            </div>
            <div class="midrenshu">
                <label>总需:<em><{$goodInfo.total_inputs}></em>人次</label>
                <div class="sprice">
                    <b style="width: 20%"></b>
                </div>
                <div class="sprice_user">
                    <b><{$indianaGoodInfo.involved_num}></b>
                    <strong><{$goodInfo.total_inputs - $indianaGoodInfo.involved_num}></strong>
                </div>
                <div class="sprice_xx">
                    <b>已参与人次</b>
                    <strong>剩余人次</strong>
                </div>
                <div class="canyutext">
                    <em><{$indianaGoodInfo.involved_num}></em>
                    人次已参与，赶快去参加吧！剩余
                    <i><{$goodInfo.total_inputs - $indianaGoodInfo.involved_num}></i>
                    人次
                </div>
                <div class="shopnum" item-id="<{$indianaGoodInfo.id}>">
                    <label>我要参与:</label>
                    <b class="reduce">-</b>
                    <input type="tel" name="" id="" value="1" />
                    <b class="plus">+</b>
                    <label>人次</label>
                </div>
                <div class="midbtn">
                    <a href="javascript:void(0)" class="ljdb fl">立即夺宝</a>
                    <a href="javascript:void(0)" class="adsc fr">加入清单</a>
                </div>
            </div>
        </div>
        <{if !empty($rewardInfos)}>
        <div class="topright">
            <div class="xyusertitle">开奖信息</div>
            <div class="xyuser">
                <div class="titlebg"></div>
                <div class="xyuserpic"><img src="<{$rewardInfos[0].user.avatar}>"> </div>
                <label>恭喜<a href="javascript:void(0)" target="_blank"><{$rewardInfos[0].user.nick_name}></a><b>(河南商丘)</b>获得该期奖品</label>
                <p>幸运号码:<em><{$rewardInfos[0].luck_number}></em></p>
                <p>本期参与:<em><{$rewardInfos[0].order.num}></em>人次</p>
                <p>揭晓时间:<{$rewardInfos[0].create_time}></p>
                <a href="/mall/reward?id=<{$rewardInfos[0].id}>" target="_blank" class="userview">查看详情</a>
            </div>
            <{if !empty($rewardInfos[1])}>
            <div class="xylist">
                <{foreach $rewardInfos as $index => $info}>
                <{if $index gt 0}>
                <span class="reward-issue" item-data="<{$info.indiana_good_id}>">第<{$info.issue}></span>
                <{/if}>
                <{/foreach}>
                <div class="xypages">
                    <a href="javascript:void(0)">下一页</a>
                    <a href="javascript:void(0)" class="on">上一页</a>
                </div>
            </div>
            <{/if}>
        </div>
        <{/if}>
    </div>
    <!--cont-->
    <div class="detailcontent">
        <div class="conttitle">
            <div class="titleborder">
                <span class="on">商品介绍</span>
                <span>所有参与记录</span>
                <span>晒单</span>
            </div>
        </div>
        <div class="conteach">
            <!--商品介绍-->
            <{if !empty($goodInfo.coverImages)}>
            <div class="contlist shopview" style="display: block">
                <{foreach $goodInfo.coverImages as $ci}>
                <img src="<{$ci}>">
                <{/foreach}>
            </div>
            <{/if}>
            <!--所有参与记录-->
            <div class="contlist joinhistory">
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
                                <a href="javascript:void(0)" target="_blank"><{$record.user.nick_name}></a>
                                (<{$record.ip}>)参与了
                                <em><{$record.num}>人次</em>
                                <b>所有夺宝号码</b>
                            </p>
                            <{if !empty($luckNumbers[$record.id])}>
                            <div class="hiddenbox">
                                <{foreach $luckNumbers[$record.id] as $luckNum}>
                                <span><{$luckNum.luck_number}></span>
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
            <{if !empty($displayOrders)}>
            <div class="contlist viewpic">
                <!---list-->
                <{foreach $displayOrders as $order}>
                <div class="viewlist">
                    <div class="viewpicbox">
                        <a href="javascript:;" target="_blank">
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
                                <label>(<{$order.good.issue}>)<{$order.good.name|default:''}></label>
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
            </div>
            <{/if}>
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