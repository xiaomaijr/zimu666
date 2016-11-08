window.onload=function(){
    var wid1=parseInt($(this).parents('td').find('img').eq(0).width()),
        hei1=parseInt($(this).parents('td').find('img').eq(0).height()),
        wid2=parseInt($(this).parents('td').find('img').eq(1).width()),
        hei2=parseInt($(this).parents('td').find('img').eq(1).height());
    $('#duibibtn').click(function(){
        var imgsrc1=$(this).parents('td').find('img').eq(0).attr('src'),
            imgsrc2=$(this).parents('td').find('img').eq(1).attr('src');
        $('.imgwindow_2').find('img').eq(0).attr('src',imgsrc1);
        $('.imgwindow_2').find('img').eq(1).attr('src',imgsrc2);
        $('.imgwindow_2').show();
        if(wid1>hei1){
            $('.imgwindow_2').find('img').eq(0).css('width',600);
            $('.imgwindow_2').find('img').eq(0).css('margin-top',(600- $('.imgwindow_2').find('img').eq(0).height())/2);
        }else{
            $('.imgwindow_2').find('img').eq(0).css('height',600);
            $('.imgwindow_2').find('img').eq(0).css('margin-left',(600- $('.imgwindow_2').find('img').eq(0).width())/2);
        }
        if(wid2>hei2){
            $('.imgwindow_2').find('img').eq(1).css('width',600);
            $('.imgwindow_2').find('img').eq(1).css('margin-top',(600- $('.imgwindow_2').find('img').eq(1).height())/2);
        }else{
            $('.imgwindow_2').find('img').eq(1).css('height',600);
            $('.imgwindow_2').find('img').eq(1).css('margin-left',(600- $('.imgwindow_2').find('img').eq(1).width())/2);
        }
    });
    $('.imgwindowbg_2').click(function(){
        $('.imgwindowcontent_2 img').attr('src','');
        $('.imgwindow_2').hide();
        current1=0;
        current2=0;
        $('.imgwindow img').css('transform','rotate(0deg)');
        $('.imgwindow_2').find('img').eq(0).css({"left":0 + "px", "top":0 + "px"});
        $('.imgwindow_2').find('img').eq(1).css({"left":0 + "px", "top":0 + "px"});
    });
    var current1= 0,
        current2=0;
    $('.imgwindowplaybox_2').each(function(i){
        if(i==0){
            $(this).find('label').eq(1).click(function(){
                current1=(current1+90)%360;
                $('.imgwindow_2').find('img').eq(0).css('transform','rotate('+current1+'deg)');
            });
            if(wid1>hei1){
                $(this).find('label').eq(2).click(function(){
                    $('.imgwindow_2').find('img').eq(0).css('width',$('.imgwindow_2').find('img').eq(0).width()+70);
                });
                $(this).find('label').eq(0).click(function(){
                    $('.imgwindow_2').find('img').eq(0).css('width',$('.imgwindow_2').find('img').eq(0).width()-70);
                });
            }else{
                $(this).find('label').eq(2).click(function(){
                    $('.imgwindow_2').find('img').eq(0).css('height',$('.imgwindow_2').find('img').eq(0).height()+70);
                });
                $(this).find('label').eq(0).click(function(){
                    $('.imgwindow_2').find('img').eq(0).css('height',$('.imgwindow_2').find('img').eq(0).height()-70);
                });
            }
        }else{
            $(this).find('label').eq(1).click(function(){
                current2=(current2+90)%360;
                $('.imgwindow_2').find('img').eq(1).css('transform','rotate('+current2+'deg)');
            });
            if(wid2>hei2){
                $(this).find('label').eq(2).click(function(){
                    $('.imgwindow_2').find('img').eq(1).css('width',$('.imgwindow_2').find('img').eq(1).width()+70);
                });
                $(this).find('label').eq(0).click(function(){
                    $('.imgwindow_2').find('img').eq(1).css('width',$('.imgwindow_2').find('img').eq(1).width()-70);
                });
            }else{
                $(this).find('label').eq(2).click(function(){
                    $('.imgwindow_2').find('img').eq(1).css('height',$('.imgwindow_2').find('img').eq(1).height()+70);
                });
                $(this).find('label').eq(0).click(function(){
                    $('.imgwindow_2').find('img').eq(1).css('height',$('.imgwindow_2').find('img').eq(1).height()-70);
                });
            }
        }
    });
    $(".imgwindowcontent_2 img").draggable({cursor: 'move' ,revert:false,
        stop: function() {
            var bigImg,width,height,over_left,over_top,left,top,postion;
            postion = $(this).position();
            bigImg =  new Image();
            bigImg.src = $(".imgwindowcontent_2 img").attr('src');
            width = bigImg.width;
            height = bigImg.height;
            over_left = (parseInt(width)-parseInt($(this).css("width")))/2;
            over_top = (parseInt(height)-parseInt($(this).css("height")))/2;
            left = postion.left;
            top = postion.top;
        }
    });
};