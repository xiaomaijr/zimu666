/**
 * Created by Administrator on 14-8-22.
 */
$(document).ready(function(){
    $(".choose-province").each(function(){
        var select_city = $(this).closest('.dialog').find('.choose-city');
        $(this).change(function(){
            select_province = $(this).val();
            //清空控件
            select_city.html("");
            //获取省份的城市
            citys = CITY_CODE[select_province];
            //当切换省份时，已选择的城市也要跟着切换
            default_city = "";
            $.each(citys, function(k, v){
                if(default_city == ""){
                    default_city = v;
                }
                opt = "<option value=" + k + ">" + v + "</option>";
                select_city.append(opt);
            });

            if(citys.length == 0){
                select_city.append("<option value=''>请选择城市</option>")
            }

            //
            select_city.parent().find('.dropselectbox h4').text(default_city);
        });
    });
});
