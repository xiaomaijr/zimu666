<{extends file="_base.tpl"}>
<{block name="content"}>
    <div class="formsubmit">
        <form id="query_form" name="search-form" method="post" action="list">
            <!---list--->
            <div class="selectlist">
                <label>主标签:</label>
                <input type="text" name="query[p_sign]" value="<{$filter.p_sign|default:''}>">
            </div>
            <!---list end--->
            <button>搜索</button>
        </form>
    </div>


<!---table-->
    <div class="tablebox">
        <table cellpadding="0" cellspacing="0" bgcolor="#ffffff" width="100%">
            <tr>
                <th>编号</th>
                <th>主标签</th>
                <th>从标签</th>
                <th>内容</th>
                <th>描述</th>
                <th>创建时间</th>
                <th>操作</th>
            </tr>
            <{foreach $list as $admin}>
            <tr>
                <td><{$admin.id}></td>
                <td><{$admin.p_sign}></td>
                <td><{$admin.s_sign|default:''}></td>
                <td><{$admin.data|truncate:50}></td>
                <td><{$admin.description|truncate:50}></td>
                <td><{$admin.create_time|date_format:'%Y-%m-%d %H'}></td>
                <td>
                    <{if $admin.is_del eq 0}>
                    <a href="javascript:void(0)" class="del" data-id="<{$admin.id}>">删除</a>
                    <a href="/cms/edit?id=<{$admin.id}>" >编辑</a>
                    <a href="/cms/view?id=<{$admin.id}>" >详情</a>
                    <{else}>
                    <a href="javascript:void(0)" class="recovery" data-id="<{$admin.id}>">恢复</a>
                    <{/if}>
                </td>
            </tr>
            <{/foreach}>
        </table>


        <div class="page">
            <{$arrPager.pagelink}>
        </div>
    </div>
<!---table end-->
<{/block}>
<{block name="js-common"}>
    <script>
        $(document).ready(function() {
            $(".del").each(function () {
                var self = this;
                $(this).click(function () {
                    if (confirm('你确定要删除该配置吗？')) {
                        $.ajax({
                            type: "post",
                            url: '/cms/delete',
                            data: 'id=' + $(this).attr('data-id') + '&is_del=1',
                            dataType: 'json',
                            success: function (res) {
                                if (res.code == 0) {
                                    alert('删除成功');
                                    history.go(0);
                                } else {
                                    var result = $.parseJSON(res);
                                    alert(result.message);
                                }
                            }
                        });
                    }
                });
            });

            $(".recovery").each(function () {
                var self = this;
                $(this).click(function () {
                    if (confirm('你确定要恢复该配置吗？')) {
                        $.ajax({
                            type: "post",
                            url: '/cms/delete',
                            data: 'id=' + $(this).attr('data-id') + '&is_del=0',
                            dataType: 'json',
                            success: function (res) {
                                if (res.code == 0) {
                                    alert('恢复成功');
                                    location.reload();
                                } else {
                                    var result = $.parseJSON(res);
                                    alert(result.message);
                                }
                            }
                        });
                    }
                });
            });

            $(".sort").each(function () {
                $(this).click(function () {
                    var newOrderBy = $(this).attr("sort-id");
                    var currentOrderBy = $("#orderbycolumn").val();
                    if (newOrderBy == currentOrderBy) {
                        if ($("#sortway").val() == "ASC") {
                            $("#sortway").attr("value", "DESC");
                        }
                        else if ($("#sortway").val() == "DESC") {
                            $("#sortway").attr("value", "ASC");
                        }
                        else {
                            $("#sortway").attr("value", "DESC");
                        }
                    }
                    else {
                        $("#orderbycolumn").attr("value", newOrderBy);
                        $("#sortway").attr("value", "DESC");
                    }
                    $("#query_form").submit();
                });

                var sortName = $(this).attr("sort-id");
                var selectedName = "{$filter.orderby}";
                if (sortName == selectedName) {
                    var sortWay = "{$filter.sortway}";
                    if ('DESC' == sortWay) {
                        $(this).attr("class", "sort sort_desc");
                    }
                    else if ('ASC' == sortWay) {
                        $(this).attr("class", "sort sort_asc");
                    }
                    else {
                        $(this).attr("class", "sort");
                    }
                }
            });
        });
    </script>
    <{/block}>