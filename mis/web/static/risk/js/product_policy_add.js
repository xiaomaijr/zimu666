$(document).ready(function(){
    var globalScope;
    // 绑定点击箭头排序
    function attachUpDownClickEvent() {
        $(".selected-policy-items .down").unbind("click");
        $(".selected-policy-items .down").click(function() {
            var scope = $(this).parentsUntil(".select-sort-table");
            var curIndex = $(this).parent().parent().index();
            var cur;
            var lis = scope.find(".selected-policy-items li");
            scope.find(".selected-policy-items li").remove();
            lis.each(
                function(i){
                    if (curIndex == i) {
                        cur = $(this);
                    } else if ((curIndex + 1) == i){
                        scope.find(".selected-policy-items").append($(this));
                        scope.find(".selected-policy-items").append(cur);
                    } else {
                        scope.find(".selected-policy-items").append($(this));
                    }
                }
            );
            scope.find(".selected-policy-items").find('img').remove();
            attachDragSortEvent();
            attachHoverEvent();
            attachDeleteEvent();
        });

        $(".selected-policy-items .up").unbind("click");
        $(".selected-policy-items .up").click(function() {
            var scope = $(this).parentsUntil(".select-sort-table");
            var curIndex = $(this).parent().parent().index();
            var cur;
            var lis = scope.find(".selected-policy-items li");
            scope.find(".selected-policy-items li").remove();
            lis.each(
                function(i){
                    if ((curIndex - 1) == i) {
                        cur = $(this);
                    } else if (curIndex == i){
                        scope.find(".selected-policy-items").append($(this));
                        scope.find(".selected-policy-items").append(cur);
                    } else {
                        scope.find(".selected-policy-items").append($(this));
                    }
                }
            );
            scope.find(".selected-policy-items").find('img').remove();
            attachDragSortEvent();
            attachHoverEvent();
            attachDeleteEvent();
        });
    }

    // 绑定鼠标移动已选择项目之后出现小下箭头
    function attachHoverEvent() {
        $(".policy-items ul li").unbind("hover");
        $(".policy-items ul li").hover(
            function() {
                var scope = $(this).parentsUntil(".select-sort-table");
                globalScope = scope;
                var curIndex = $(this).index();
                var maxIndex = $(this).parent().children().length - 1;
                scope.find(".selected-policy-items").find('img').remove();
                if (curIndex== 0) {
                    $(this).find('div').append('<img class="down" src="/static/risk/img/down.gif"/>');
                } else if (maxIndex == curIndex) {
                    $(this).find('div').append('<img class="up" src="/static/risk/img/up.gif"/>');
                } else {
                    $(this).find('div').append('<img class="down" src="/static/risk/img/down.gif"/>')
                        .append('<img class="up" src="/static/risk/img/up.gif"/>');
                }
                attachUpDownClickEvent();
            },
            function() {
                $(this).find('div').find('img').remove();
            }
        );
    }

    // 拖动结束之后更新data-sort
    function saveOrder() {
        $(".selected-policy-items").find('img').remove();
    };

    // 支持拖动排序
    function attachDragSortEvent() {
        $(".selected-policy-items").dragsort("destroy");
        $(".selected-policy-items").eq(0).dragsort(
            {
                dragSelector: "div",
                dragBetween: true,
                dragEnd: saveOrder,
                placeHolderTemplate: "<li class='placeHolder'><div></div></li>"
            }
        );

        $(".selected-policy-items").eq(1).dragsort(
            {
                dragSelector: "div",
                dragBetween: true,
                dragEnd: saveOrder,
                placeHolderTemplate: "<li class='placeHolder'><div></div></li>"
            }
        );

        $(".selected-policy-items").eq(2).dragsort(
            {
                dragSelector: "div",
                dragBetween: true,
                dragEnd: saveOrder,
                placeHolderTemplate: "<li class='placeHolder'><div></div></li>"
            }
        );

        $(".selected-policy-items").eq(3).dragsort(
            {
                dragSelector: "div",
                dragBetween: true,
                dragEnd: saveOrder,
                placeHolderTemplate: "<li class='placeHolder'><div></div></li>"
            }
        );

        $(".selected-policy-items").eq(4).dragsort(
            {
                dragSelector: "div",
                dragBetween: true,
                dragEnd: saveOrder,
                placeHolderTemplate: "<li class='placeHolder'><div></div></li>"
            }
        );

        $(".selected-policy-items").eq(5).dragsort(
            {
                dragSelector: "div",
                dragBetween: true,
                dragEnd: saveOrder,
                placeHolderTemplate: "<li class='placeHolder'><div></div></li>"
            }
        );

        $(".selected-policy-items").eq(6).dragsort(
            {
                dragSelector: "div",
                dragBetween: true,
                dragEnd: saveOrder,
                placeHolderTemplate: "<li class='placeHolder'><div></div></li>"
            }
        );
    }

    // 支持删除操作
    function attachDeleteEvent() {
        $(".selected-policy-items a").unbind("click");
        $(".selected-policy-items a").click(function() {
            var itemId = $(this).parent().attr('data-item-id');
            $(this).parentsUntil(".select-sort-table").find('.policy-items').
                find('input[value="' + itemId + '"]').attr('checked', false);

            $(this).parent().remove();
            var lis = $(".selected-policy-items li");
        });
    }
    attachDragSortEvent();
    attachHoverEvent();
    attachDeleteEvent();

    // 支持全部删除
    $(".title-policy a").click(function() {
        $(this).parentsUntil(".select-sort-table").find('.policy-items input').attr('checked', false);
        $(this).parent().parent().next().find('.selected-policy-items li').remove();
    });

    // 支持全选
    $("input[name='select_all_one']").click(function() {
        var scope = $(this).parentsUntil(".select-sort-table");
        if ($(this).attr('checked') == 'checked') {
            var selectedItems = scope.find('.selected-policy-items');
            scope.find('.policy-items .select-one').each(function(){
                if (!$(this).attr('checked')) {
                    var _this    = $(this);
                    var itemId   = _this.val();
                    var itemName = $.trim(_this.parent().text());
                    var maxSort = selectedItems.find('li').length;
                    var newItem = '<li data-sort="' + (maxSort + 1) +'" data-item-id="' + itemId + '"><div> ' +
                        itemName + '</div><a href="javascript:;">&nbsp;&nbsp;删除</a></li>';
                    selectedItems.append(newItem);
                    $(this).attr('checked', 'checked');
                }
            });
        } else {
            scope.find('.policy-items input').attr('checked', false);
            scope.find('.selected-policy-items li').remove();
        }
    });

    // 选中某个
    $(".select-one").click(function() {
        var _this    = $(this);
        var itemId   = _this.val();
        var selectedItems = _this.parentsUntil(".select-sort-table").find('.selected-policy-items');

        if ($(this).attr('checked') == 'checked') {
            var itemName = $.trim(_this.parent().text());
            var maxSort = selectedItems.find('li').length;
            var newItem = '<li data-sort="' + (maxSort + 1) +'" data-item-id="' + itemId + '"><div> ' +
                itemName + '</div><a href="javascript:;">&nbsp;&nbsp;删除</a></li>';
            selectedItems.append(newItem);
        } else {
            selectedItems.find('li[data-item-id="' + itemId + '"]').remove();
            saveOrder();
        }

        attachDragSortEvent();
        attachHoverEvent();
        attachDeleteEvent();
    });

    $("#policyFrom").submit(function() {
        var sortSets   = $(".selected-policy-items");
        sortSets.each(function(i){
            sortSet = $(this);
            var sortHidden = sortSet.next();
            var sortMap = '';
            sortSet.find('li').each(function(){
                sortMap += $(this).attr('data-item-id') + ',';
            });

            $(this).next().val(sortMap.substr(0, sortMap.length - 1));
        });

        return true;
    });
});