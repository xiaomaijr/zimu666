$(function(){
    $('.adress_modefy,.add_adress_btn a').click(function(){
        $('.openbox,.addaddress').show();
        modifyAddressId = $(this).parents('.adress_manger').attr('item-id');
    });
    $('.closebox,.openboxbg,.deletetipboxbg').click(function(){
        $('.addaddress,.openbox,.vieworderopen,.vieworder').hide();
    });
    $('.wid125 label').click(function () {
        var that = $(this);
        var itemStatus = that.attr('item-status');
        var itemId = that.attr('item-id');
        if (itemStatus == 6) {
            $.ajax({
                type    :   'post',
                data    :   'id=' + itemId,
                dataType:   'json',
                url     :   '/order/confirm-receive',
                success :   function(data){
                    if (data.code == 0) {
                        that.html('晒单');
                        that.attr('item-status', 7);
                        return true;
                    }
                    window.alert(data.message);
                    return false;
                }
            });
        } else {
            $('.vieworderopen').find(".order-id").val(itemId);
            $('.vieworderopen,.vieworder').show();
        }
    });
    //选择市区
    $('.adresslist span').click(function(){
        var ev = ev || event;
        ev.cancelBubble = true;
        if(ev.ctrlKey == false) {
            if ($('.ad_select').css('display') == 'none') {
                $('.ad_title em,.ad_area,.ad_newadd').hide();
                $.ajax({
                    type        :       'post',
                    url         :       '/member/province',
                    dataType    :       'json',
                    success     :       function(data){
                        if (data.code == 0) {
                            var html = '';
                            for(var i in data.data.provinces){
                                html += '<em item-id="' +  data.data.provinces[i]['province_id'] + '">'+data.data.provinces[i]['province_name']+'</em>';
                            }
                            window.console.log(html);
                            $('.ad_city').append(html);
                        }
                    }
                });
                $('.ad_select,.ad_city').show();
            }
        }
        this.offOn = true;
    });
    //重新选择市
    $('.ad_title em').eq(0).click(function(){
        var ev = ev || event;
        ev.cancelBubble = true;
        if(ev.ctrlKey == false) {
            $('.ad_area,.ad_newadd').hide();
            $.ajax({
                type        :       'post',
                url         :       '/member/province',
                dataType    :       'json',
                success     :       function(data){
                    if (data.code == 0) {
                        var html = '';
                        for(var i in data.data.citys){
                            html += '<em>'+data.data.citys[i]['province_name']+'</em>';
                        }
                        
                    }
                }
            });
            $('.ad_city').show();
            $('.ad_title em').hide();
        }
        this.offOn = true;
    });
    $('.ad_title em').eq(1).click(function(){
        var ev = ev || event;
        ev.cancelBubble = true;
        if(ev.ctrlKey == false) {
            $('.ad_newadd,.ad_city').hide();
            $('.ad_area').show();
            $('.ad_title em').eq(1).hide();
        }
        this.offOn = true;
    });
    //选择市
    $('.ad_city b').click(function(){
        var ev = ev || event;
        ev.cancelBubble = true;
        if(ev.ctrlKey == false) {
            $('.ad_title em').eq(0).show().text($(this).text());
            $('.ad_city,.ad_newadd').hide();
            $('.ad_area').show();
        }
        this.offOn = true;
    });
    //选择区
    $('.ad_area b').click(function(){
        var ev = ev || event;
        ev.cancelBubble = true;
        if(ev.ctrlKey == false) {
            $('.ad_area,.ad_city').hide();
            $('.ad_newadd').show();
            $('.ad_title em').eq(1).show().text($(this).text());
        }
        this.offOn = true;
    });
    //新增选择
    $('.ad_newadd b').click(function(){
        var ev = ev || event;
        ev.cancelBubble = true;
        if(ev.ctrlKey == false) {
            $('.ad_select').hide();
            $('.adresslist span').html($('.ad_title em').eq(0).text()+'&nbsp;&nbsp;'+$('.ad_title em').eq(1).text()+'&nbsp;&nbsp;'+$(this).text());
        }
        this.offOn = true;
    });
    $(document).click(function(){
        $('.ad_select').hide();
    });
    //删除
    $('.orderlist_titile b,.delete_adress').click(function(){
        deleteId = $(this).attr('item-id');
        type = $(this).attr('item-type');
        window.console.log(deleteId + '_' + type);
        $('.deletetipopen').show();
    });
    //删除提示  按钮
    $('.deleteopenbtn a').click(function(){
        if (type == 'order') {
            var url = '/user-center/order-delete';
        } else if (type == 'indianaed') {
            var url = '/user-center/indianaed-delete';
        } 
        $.ajax({
            url     :       url,
            data    :       'id=' + deleteId + '&is_del=1',
            type    :       'post',
            dataType:       'json',
            success :       function(res) {
                if (res.code == 0) {
                    window.location.reload();
                } else {
                    window.alert(res.message);
                }
            }
        });
        $('.deletetipopen').hide();
    });
    //添加收货人地址
    $('.adressbtn a').on('click', function(){
        var receiver = $('#receiver').val();
        if (receiver == '' || typeof receiver == undefined) {
            $('#receiver').focus();
        }
        var cityId = 1, district_id = 1, street_id = 1;
        var address = $('#address').val();
        var phone = $('#phone').val();
        var callNumber = $('#call_number').val();
        var data = 'user_name=' + receiver + '&city_id=' + cityId + '&district_id=' + district_id + '&street_id=' + street_id
            + '&address=' + address + '&mobile=' + phone + '&call_number=' + callNumber;
        if (typeof modifyAddressId == undefined) {
            var url = '/user-center/add-address';
        } else {
            var url = '/user-center/modify-address';
            data += '&address_id=' + modifyAddressId;
        }
        $.ajax({
            url     :       url,
            data    :       data,
            type    :       'post',
            dataType:       'json',
            success :       function(res) {
                if (res.code == 0) {
                    window.location.reload();
                } else {
                    window.alert(res.message);
                }
            }
        });
    });
    //设置默认地址
    $('.set_adress').on('click', function(){
        var addressId = $(this).parents('.adress_manger').attr('item-id');
        if (addressId) {
            $.ajax({
                url     :       '/user-center/set-default-address',
                data    :       'address_id=' + addressId,
                type    :       'post',
                dataType:       'json',
                success :       function(res) {
                    if (res.code == 0) {
                        window.location.reload();
                    } else {
                        window.alert(res.message);
                    }
                }
            });
        }
    });
    //编辑昵称
    $('.usernikename b').click(function () {
        if($('.usernikename input').css('display')=='none'){
            $(this).text('完成');
            $(this).on('click', function(){
                var nickName = $('.usernikename input').val();
                if (nickName.length > 0) {
                    $.ajax({
                        url     :       '/user-center/modify-nick',
                        data    :       'nick_name=' + nickName,
                        type    :       'post',
                        dataType:       'json',
                        success :       function(data){
                            if (data.code == 0) {
                                $(this).text('编辑昵称');
                                $('.usernikename input').hide();
                                $('.usernikename span').show().text(nickName);
                            } else {
                                window.alert(data.message);
                                return false;
                            }
                        }
                    });
                }
            });
            $('.usernikename input').show().val($('.usernikename span').text());
            $('.usernikename span').hide();
        }else{
            $(this).text('编辑昵称');
            $('.usernikename input').hide();
            $('.usernikename span').show().text($('.usernikename input').val());
        }
    });
    $('#upload_image').on('change', function(type,id,viewid){
        var type = 'smalllogo';
        var id = 'upload_image';
        var viewid = 'image_urls';
        var imageHtmlPre = '<div class="picboxlist"> <img src="';
        var imageHtmlApp = '"> <i></i> <strong>删除</strong> </div>';
        file = $("#"+id);
        var filename = file.val();
        if(filename == 0){
            alert("未选择上传文件");
            return false;
        }
        var fileObj = document.getElementById(id).files[0];
        var form = new FormData();
        form.append("myfile", fileObj);
        form.append("type",type);
        $.ajax({
            type : 'post',
            url  : '/user-center/upload',
            data : form,
            processData :false,
            contentType : false,
            success:function(res){
                var result = $.parseJSON(res);
                if(result.code == 0){
                    if(viewid != undefined){
                        var image_urls = $("#"+viewid).val();
                        if(image_urls != ''){
                            image_urls = image_urls+','+result.src;
                        }else{
                            image_urls = result.src;
                        }
                        var imageHtml = imageHtmlPre + result.src + imageHtmlApp;
                        $("#"+viewid).val(image_urls);
                        $('.historypic').append(imageHtml);
                    }
                }else{
                    alert(result.message);
                }
            }
        });
    });
    //上传图片删除事件
    $('.historypic').on('hover', '.picboxlist', function(){
        $(this).addClass('on');
    },function () {
        $(this).removeClass('on');
    });
    // $(' .picboxlist').hover(function () {
    //     $(this).addClass('on');
    // },function () {
    //     $(this).removeClass('on');
    // })
    $('.display-btn').on('click',function(){
        var title = $.trim($('#title').val());
        if (title == '') {
            window.alert('请填写标题');
            $('#title').focus();
            return false;
        }
        var content = $.trim($('#content').val());
        if (content == '') {
            window.alert('请填写感受');
            $('#content').focus();
            return false;
        }
        var orderId = $('.order-id').val();
        var imageUrls = $('#image_urls').val();
        if (imageUrls == '') {
            window.alert('请选择分享图片');
            return false;
        }
        var params = 'order_id='+orderId+'&image_urls='+imageUrls+'&title='+title+'&content='+content;
        $.ajax({
            type        :       'post',
            url         :       '/member/display-order',
            data        :       params,
            dataType    :       'json',
            success     :       function(data){
                if (data.code == 0) {
                    window.location.reload();
                }
                window.alert(data.message);
            }
        });
    });
    $('.cancal-order').on('click', function(){
        var orderId = $(this).attr('data-id');
        if (typeof orderId == undefined) {
            return false;
        }
        $.ajax({
            type        :       'post',
            url         :       '/order/cancal',
            data        :       'id=' + orderId,
            dataType    :       'json',
            success     :       function(data){
                if (data.code == 0) {
                    window.location.reload();
                }
                window.alert(date.message);
            }
        });
    });
});