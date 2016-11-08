/**
 * Created by Administrator on 14-8-22.
 */
$(document).ready(function(){
    $(".select_province").each(function(){
        var elcity = $(this).parent().find('.select_city');
        $(this).change(function(){
            select_province = $(this).val();
            //清空控件
            elcity.html("");
            //获取省份的城市
            citys = CITY_CODE[select_province];
            default_city = "";
            $.each(citys, function(k, v){
                if(default_city == ""){
                    default_city = v;
                }
                opt = "<option value=" + k + ">" + v + "</option>";
                elcity.append(opt);
            });

            if(citys.length == 0){
                elcity.append("<option value=''>请选择城市</option>")
            }

            //
            elcity.parent().find('.dropselectbox h4').text(default_city);
        });
    });
});
