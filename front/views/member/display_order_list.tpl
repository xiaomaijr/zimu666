<{extends file="_pc_base.tpl"}>
<{block name="css"}>
<link rel="stylesheet" type="text/css" href="/css/member/display_order_list.css"/>
    <{/block}>
<{block name="content"}>
<!--content-->
    <div class="content">
        <{if !empty($list)}>
        <div class="orderview order">
            <{foreach $list as $record}>
            <div class="viewlist2">
                <a href="/member/display-order-detail?id=<{$record.id}>">
                    <img src="<{$record.image}>">
                    <span><{$record.good.name}></span>
                    <label>幸运号码:<em><{$record.luck_number}></em></label>
                    <div class="showtext">
                        <span><{$record.title}></span>
                        <p>
                            <i><{$record.user_name}></i>
                            <em><{$record.create_time}></em>
                        </p>
                        <b><{$record.comment|truncate:100}></b>
                    </div>
                </a>
            </div>
            <{/foreach}>
        </div>
        <{/if}>
    </div>
    <div class="clear"></div>
    <div class="pagelist">
        <{$paging}>
        <{*<span class="prevpage on">&lt;上一页</span>
            <a href="javascript:void(0)" class="on">1</a>
            <a href="javascript:void(0)">2</a>
            <a href="javascript:void(0)">3</a>
            <a href="javascript:void(0)">4</a>
            <b>...</b>
            <a href="javascript:void(0)">9</a>
            <span class="nextpage">下一页&gt;</span>*}>
    </div>
<!--content end-->
<{/block}>