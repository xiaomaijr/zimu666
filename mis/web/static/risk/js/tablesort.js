
//表格按照表头排序功能
//需要此功能的页面引入此js文件，添加几行代码即可
$(document).ready(function(){

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
        var selectedName="{$arrFilter.orderbycolumn}";
        if(sortName==selectedName){
            var sortWay = "{$arrFilter.sortway}";
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

