$(function () {
    $('.shopnum input').each(function(){
        if(parseInt($.trim($(this).val()))==1){
            $(this).siblings('.reduce').addClass('on')
        }
        $(this).blur(function(){
            var num= parseInt($.trim($(this).val()));
            if(num==1){
                $(this).siblings('.reduce').addClass('on');
            }else{
                $(this).siblings('.reduce').removeClass('on');
            }
        });
    });
    //删除
    $('.deteleshop img').click(function(){
        deleteGoodId = $(this).parent('.deteleshop').siblings('td').find('input').val();
        $('.deletetipopen').show();
    });
    //删除提示  按钮
    $('.deleteopenbtn a').click(function(){
        if (typeof deleteGoodId != undefined) {
            $.ajax({
                type        :       'post',
                data        :       'id='+ deleteGoodId,
                dataType    :       'json',
                url         :       '/shop-cart/delete-good',
                success     :       function(data){
                    if (data.code == 0) {
                        window.alert(data.message);
                    }
                }
            });
        }
        $('.deletetipopen').hide();
    });
    //-
    $('.reduce').each(function(){
        $(this).click(function(){
            var num= parseInt($.trim($(this).siblings('input').val()));
            var that = $(this);
            if(num>=2){
                $.ajax({
                    type        :       'post',
                    data        :       'num=-1&id='+ that.parents('.shopnum').attr('item-id'),
                    dataType    :       'json',
                    url         :       '/shop-cart/update-num',
                    success     :       function(data){
                        if (data.code == 0) {
                            that.siblings('input').val(num-1);
                        }
                    }
                });
            }else{
                $(this).siblings('input').val(1);
                $(this).addClass('on');
                return false;
            }
        });
    });
    //+
    $('.plus').each(function(){
        $(this).click(function(){
            var num= parseInt($.trim($(this).siblings('input').val()));
            var that = $(this);
            $.ajax({
                type        :       'post',
                data        :       'num=1&id='+ that.parents('.shopnum').attr('item-id'),
                dataType    :       'json',
                url         :       '/shop-cart/update-num',
                success     :       function(data){
                    if (data.code == 0) {
                        that.siblings('input').val(num+1);
                        that.siblings('.reduce').removeClass('on');
                    }
                }
            });
        });
    });
    $('.paybtn').on('click', function(){
        var shopCartIds = '';
        $('.cs_car table tr td input[type="checkbox"]').each(function () {
            var chk=$(this).find("[checked]");
            if(this.checked){
                shopCartIds = $(this).val() + ',';
            }
        });
        if (shopCartIds == '') {
            window.alert('请选择提交商品');
            return false;
        }
        shopCartIds = shopCartIds.replace(/,$/, '');
        $.ajax({
            type        :       'post',
            data        :       'shop_cart_ids='+ shopCartIds,
            dataType    :       'json',
            url         :       '/order/create',
            success     :       function(data){
                window.console.log(data.code);
                if (data.code == 0) {
                    window.location.href = '/user-center/order-list';
                } else if (data.code == 3001) {
                    window.location.href = '/epay/recharge';
                } else {
                    window.alert(data.message);
                }
            }
        });
    });
});