{extends file="_base.tpl"}
{block name="content"}
    <style>
        .ui-widget-content .item {
            margin:2px 10px 2px 0;
            float:left;
        }
    </style>
    <h2 class="top10">4S店列表</h2>
    <div class="top10 pad10 ui-widget-content">
        <form id="query_form" name="search-form" method="post" action="/groups/list">
            <div class="item"><label>ID：<input type="text" name="query[id]" value="{$filter.id|default:''}" class="inpt"/></label></div>
            <div class="item">
                <label>4S店名称：<input type="text" name="query[name]" value="{$filter.name|default:''}" class="inpt"/></label>
            </div>
            <input type="hidden" id="orderbycolumn" name="order" value="{$filter.orderby}"/>
            <input type="hidden" id="sortway" name="sortway" value="{$filter.sortway}"/>
            <p><button type="submit" value="1">查询</button></p>
        </form>
    </div>

    <table class="tb-list top10" cellpadding="0" cellspacing="0">
        <thead>
        <tr>
            <th class="sort" width="80px" sort-id="id">ID</th>
            <th class="sort" width="80px" sort-id="city_id">城市名</th>
            <th>4S店名称</th>
            <th>父4S店名称</th>
            <th>地址</th>
            <th>经度</th>
            <th>纬度</th>
            <th>手机号</th>
            <th>下属品牌名</th>
            <th>状态</th>
            <th class="sort" width="80px" sort-id="create_time">创建时间</th>
            <th>最近一次更新时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {foreach item=item from=$groupList}
            <tr>
                <td class="cnt">{$item.id}</td>
                <td class="cnt">{$citys[$item.city_id]|default:'北京'}</td>
                <td class="cnt">{$item.name}</td>
                <td class="cnt">{$upperInfo[$item.upper_id]['name']|default:''}</td>
                <td class="cnt">{$item.address|default:''}</td>
                <td class="cnt">{$item.lng|default:''}</td>
                <td class="cnt">{$item.lat|default:''}</td>
                <td class="cnt">{$item.phone_number|default:''}</td>
                <td class="cnt">{$item.brand_names|default:''|truncate:20}</td>
                <td class="cnt">{$item.is_del|replace:0:'未删除'|replace:1:'已删除'}</td>
                <td class="cnt">{$item.create_time|date_format:"%Y-%m-%d %T"}</td>
                <td class="cnt">{$item.update_time|date_format:"%Y-%m-%d %T"|default:''}</td>
                <td class="cnt">
                    {*{if in_array('admin/view', $smarty.session.money_user_rights)}*}
                    <a href="/groups/view?id={$item.id}">查看</a>
                    {*{/if}*}
                    {*{if in_array('admin/edit', $smarty.session.money_user_rights)}*}
                    |<a href="/groups/edit?id={$item.id}">修改</a>
                    {*{/if}*}
                    {if $item.is_del eq 0}
                    |<a href="#" class="del" data-id="{$item.id}">删除</a>
                    {else}
                    |<a href="#" class="recovery" data-id="{$item.id}">恢复</a>
                    {/if}
                </td>
            </tr>
        {/foreach}
        <tr align="center"><td colspan="13"><a href="/groups/add"><b>添加新4S店</b></a></td> </tr>
        </tbody>
    </table>
    <div class="page">
        {$arrPager.pagelink} 共<font color=red>{$arrPager.count}</font>个结果
    </div>
{/block}
{block name="js-common"}
    <script>
        $(document).ready(function(){

            $(".del").each(function(){
                var self = this;
                var url = '/groups/delete?id='+$(this).attr('data-id');
                $(this).click(function(){
                    if(confirm('你确定要删除该4S店吗？')){
                        $.get(
                                url,
                                function(res){
                                    if(res=='success'){
                                        alert('删除成功');
                                        location.reload();
                                    }else{
                                        result = $.parseJSON(res);
                                        alert(result.message);
                                    }
                                }
                        );
                    }
                });
            });

            $(".recovery").each(function(){
                var self = this;
                var url = '/groups/delete?id='+$(this).attr('data-id')+'&is_del=1';
                $(this).click(function(){
                    if(confirm('你确定要恢复该4S店吗？')){
                        $.get(
                                url,
                                function(res){
                                    if(res=='success'){
                                        alert('恢复成功');
                                        history.go();
                                    }else{
                                        result = $.parseJSON(res);
                                        alert(result.message);
                                    }
                                }
                        );
                    }
                });
            });

            $(".sort").each(function(){
                $(this).click(function(){
                    var newOrderBy = $(this).attr("sort-id");
                    var currentOrderBy = $("#orderbycolumn").val();
                    if(newOrderBy==currentOrderBy){
                        if($("#sortway").val()=="ASC"){
                            $("#sortway").attr("value","DESC");
                        }
                        else if($("#sortway").val()=="DESC"){
                            $("#sortway").attr("value","ASC");
                        }
                        else{
                            $("#sortway").attr("value","DESC");
                        }
                    }
                    else{
                        $("#orderbycolumn").attr("value",newOrderBy);
                        $("#sortway").attr("value","DESC");
                    }
                    $("#query_form").submit();
                });

                var sortName = $(this).attr("sort-id");
                var selectedName="{$filter.orderby}";
                if(sortName==selectedName){
                    var sortWay = "{$filter.sortway}";
                    if('DESC'==sortWay){
                        $(this).attr("class","sort sort_desc");
                    }
                    else if('ASC'==sortWay){
                        $(this).attr("class","sort sort_asc");
                    }
                    else{
                        $(this).attr("class","sort");
                    }
                }
            });

        });
    </script>
{/block}
