/**
 * Created with JetBrains PhpStorm.
 * User: Administrator
 * Date: 14-2-24
 * Time: 下午7:23
 * To change this template use File | Settings | File Templates.
 */
$(document).ready(function(){
    //删除确认
    if ($('.delete-policy').length > 0)
    {
        $('.delete-policy').each(function(){
            var self = this;
            $(this).click(function(){
                if(confirm('你确定要删除该政策吗？')){
                    $.ajax({
                        type: "post",
                        url: 'http://risk.rong360.com/policy/delete',
                        data: 'id='+$(this).attr('data-id'),
                        success: function(res){
                            res = $.parseJSON(res);
                            alert(res.info);
                            if(res.status=='success'){
                                $(self).parent().parent().remove();
                                var num = $('font').html()-1;
                                $('font').html(num);
                            }
                        }
                    })
                }
            });
        });
    }


});

