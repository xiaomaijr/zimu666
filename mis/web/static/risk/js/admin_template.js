$(document).ready(function(){
    $('.allCity').change(function(){
        var self = $(this);
        var allcity = self.parent().next().find('input[type="checkbox"]');
        if(self.attr('checked'))
        {
            allcity.attr('checked', 'true');
        }
        else
        {
            allcity.removeAttr('checked');
        }
    });

    $('input[name="admin[zone][]"]').change(function(){
        var self = $(this);
        if(self.attr('checked'))
        {
            self.parent().siblings().attr('checked', true);

            var all = self.parent().parent().parent().find('input[name="admin[zone][]"]');
            var checkeds = $(this).parent().parent().parent().find('input[name="admin[zone][]"]:checked');
            var allChecked = false;

            if(all.length == checkeds.length)
            {
                allChecked = true;
            }

            if(allChecked == false)
            {
                $('.allCity').removeAttr('checked');
            }
            else
            {
                $('.allCity').attr('checked', true);
            }
        }
        else
        {
            self.parent().siblings().removeAttr('checked');
            self.parent().parent().parent().prev().find('input').removeAttr('checked');
        }
    });

    $('input[name="admin[city][]"]').change(function(){
        var all = $(this).parent().find('input[name="admin[city][]"]');
        var checkeds = $(this).parent().find('input[name="admin[city][]"]:checked');
        var allChecked = false;

        if(all.length==checkeds.length){
            allChecked = true;
        }

        if(allChecked == false)
        {
            $(this).parent().find('legend').find('input[type="checkbox"]').removeAttr('checked');
            $('.allCity').removeAttr('checked');
        }
        else
        {
            $(this).parent().find('legend').find('input[type="checkbox"]').attr('checked', true);

            var allzone = $('input[name="admin[zone][]"]');
            var allcheckedZone = $('input[name="admin[zone][]"]:checked');

            if(allzone.length == allcheckedZone.length)
            {
                $('.allCity').attr('checked', true);
            }
        }
    });

    $('.btn').click(function(e){
        var checkedRole = $('input[name="admin[role][]"]:checked');
        var checkedCity = $('input[name="admin[city][]"]:checked');
        if(checkedRole.length == 0 || checkedCity.length == 0)
        {
            var warn = '';
            if(checkedRole.length == 0)
            {
                warn += '至少选择一个角色！\n';
            }

            if(checkedCity.length == 0)
            {
                warn += '至少选择一个城市！';
            }
            alert(warn);
            e.preventDefault();
        }
    });

    $(".select-all-bank").bind('click', function(){
       if($(this).attr('checked'))
       {
           $(this).closest("fieldset").find("input[type=checkbox]").attr('checked', 'checked');
       }
       else
       {
           $(this).closest("fieldset").find("input[type=checkbox]").removeAttr('checked');
       }
    });
});