{extends file="_base.tpl"}
{block name="content"}
    <style>
        .ui-widget-content .item {
            margin:2px 10px 2px 0;
            float:left;
        }
    </style>
    <h2 class="top10">历史列表</h2>
    {*<div class="top10 pad10 ui-widget-content">*}
        {*<form id="query_form" name="search-form" method="post" action="/cms/list">*}
            {*<div class="item"><label>ID：<input type="text" name="query[id]"  value="{$filter.id|default:''}" class="inpt"/></label></div>*}
            {*<div class="item">*}
                {*<label>主标签：<input type="text" name="query[p_sign]"  value="{$filter.p_sign|default:''}" class="inpt"/></label>*}
            {*</div>*}
            {*<div class="item">*}
                {*<label>从标签：<input type="text" name="query[s_sign]"  value="{$filter.s_sign|default:''}" class="inpt"/></label>*}
            {*</div>*}
            {*<input type="hidden" id="orderbycolumn" name="order" value="{$filter.orderby}"/>*}
            {*<input type="hidden" id="sortway" name="sortway" value="{$filter.sortway}"/>*}
            {*<p><button type="submit" value="1">查询</button></p>*}
        {*</form>*}
    {*</div>*}
    <table class="tb-list top10" cellpadding="0" cellspacing="0">
        <thead>
        <tr>
            <th>ID</th>
            <th>主标签</th>
            <th>从标签</th>
            <th>数据</th>
            <th>描述</th>
            <th>删除状态</th>
            <th>创建时间</th>
            <th>更新时间</th>
            <th>管理员</th>
        </tr>
        </thead>
        <tbody>
        {foreach item=item from=$lists}
            <tr>
                <td class="cnt">{$item.id}</td>
                <td class="cnt">{$item.p_sign}</td>
                <td class="cnt">{$item.s_sign|default:''}</td>
                <td class="cnt">{$item.data|truncate:30:''}</td>
                <td class="cnt">{$item.description|default:''|truncate:30:''}</td>
                <td class="cnt">{$item.is_del|replace:0:'未删'|replace:1:'已删除'}</td>
                <td class="cnt">{$item.create_time|date_format:"%Y-%m-%d %T"|default:''}</td>
                <td class="cnt">{$item.update_time|date_format:"%Y-%m-%d %T"|default:''}</td>

                <td class="cnt">
                    {$ops[$item.operator_id]|default:''}
                </td>
            </tr>
        {/foreach}
        {*<tr align="center"><td colspan="11"><a href="/cms/add"><b>添加新配置</b></a></td></tr>*}
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
                var url = '/cms/delete?id='+$(this).attr('data-id');
                $(this).click(function(){
                    if(confirm('你确定要删除该记录吗？')){
                        $.get(
                            url,
                            function(res){
                                if(res=='success'){
                                    alert('删除成功');
                                    location.reload();
                                }
                            }
                        );
                    }
                });
            });
            $(".recovery").each(function(){
                var self = this;
                var url = '/cms/delete?id='+$(this).attr('data-id')+'&is_del=1';
                $(this).click(function(){
                    if(confirm('你确定要恢复该记录吗？')){
                        $.get(
                            url,
                            function(res){
                                if(res=='success'){
                                    alert('恢复成功');
                                    location.reload();
                                }
                            }
                        );
                    }
                });
            });

            {*$(".sort").each(function(){*}
                {*$(this).click(function(){*}
                    {*var newOrderBy = $(this).attr("sort-id");*}
                    {*var currentOrderBy = $("#orderbycolumn").val();*}
                    {*if(newOrderBy==currentOrderBy){*}
                        {*if($("#sortway").val()=="ASC"){*}
                            {*$("#sortway").attr("value","DESC");*}
                        {*}*}
                        {*else if($("#sortway").val()=="DESC"){*}
                            {*$("#sortway").attr("value","ASC");*}
                        {*}*}
                        {*else{*}
                            {*$("#sortway").attr("value","DESC");*}
                        {*}*}
                    {*}*}
                    {*else{*}
                        {*$("#orderbycolumn").attr("value",newOrderBy);*}
                        {*$("#sortway").attr("value","DESC");*}
                    {*}*}
                    {*$("#query_form").submit();*}
                {*});*}

                {*var sortName = $(this).attr("sort-id");*}
                {*var selectedName="{$filter.orderby}";*}
                {*if(sortName==selectedName){*}
                    {*var sortWay = "{$filter.sortway}";*}
                    {*if('DESC'==sortWay){*}
                        {*$(this).attr("class","sort sort_desc");*}
                    {*}*}
                    {*else if('ASC'==sortWay){*}
                        {*$(this).attr("class","sort sort_asc");*}
                    {*}*}
                    {*else{*}
                        {*$(this).attr("class","sort");*}
                    {*}*}
                {*}*}
            {*});*}
        });
    </script>
{/block}
