<{extends file="_pc_base.tpl"}>
<{block name="css"}>
<link rel="stylesheet" type="text/css" href="/css/member/display_order_list.css"/>
    <{/block}>
<{block name="content"}>
<!--content-->
    <div class="content">
        <div class="personcenter">
            <div class="userhead">
                <img src="<{$info.user_avatar}>">
            </div>
            <div class="userinfo">
                <div class="usernikename">
                    <span><{$info.user_name}></span>
                </div>
            </div>
        </div>
        <div class="viewlist">
            <div class="showtext">
                <span><{$info.title}></span>
                <a href="/mall/detail?id=<{$info.good.id}>">
                    <span><{$info.good.name}></span>
                </a>
                <label>商品期数:<em>第<{$info.issue}>期</em></label>
                <label>幸运号码:<em><{$info.luck_number}></em></label>
                <label>本期参与:<em><{$order.num}></em>次</label>
                <p>
                    <i><{$info.user_name}></i>
                    <em><{$info.create_time}></em>
                </p>
                <p>
                    <i>开奖时间:</i>
                    <em><{$order.create_time}></em>
                </p>
                <b><{$info.comment|truncate:100}></b>
            </div>
            <div class="detailimg">
                <img src="<{$info.image}>">
            </div>
        </div>
    </div>
<!--content end-->
<{/block}>