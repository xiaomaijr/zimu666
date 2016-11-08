{extends file="_base.tpl"}
{block name="content"}
<div>

    <div class="ui-tabs ui-widget ui-widget-content ui-corner-all loaner-info">
        <div>

            <div>
                <p style="font-size: 24px;font: bold;margin-top: 5px">资料信息填写</p>
            </div>

            <form method="post" action="/mis/save" id="form" onsubmit="return false">
            <input type="hidden" name="apply[input_user]" value="1" />
            <div id="input_fields">

                <div class="top10" style="padding-left:120px;">
                        <table>
                            <tbody>

                            <tr>
                                <td colspan="2">
                                    <p style="border-bottom: 2px solid #3e8ed9;font-size: 20px; margin-top: 10px">企业信息</p>
                                </td>
                            </tr>

                            <tr>
                                <td class="td-title" align="right">企业名称：</td>
                                <td class="td-input" align="left">
                                    <input type="text" class="inpt" id="org_name" name="apply[org_name]" value="{$coop_info.org_name}">
                                </td>
                            </tr>

                            <tr>
                                <td class="td-title" align="right">企业网址：</td>
                                <td class="td-input" align="left">
                                    <input type="text" class="inpt" id="org_website" name="apply[org_website]" value="{$coop_info.org_website}">
                                </td>
                            </tr>


                            <tr>
                                <td class="td-title" align="right">企业地址：</td>
                                <td class="td-input" align="left">
                                    <input type="text" class="inpt" id="org_addr" name="apply[org_addr]" value="{$coop_info.org_addr}">
                                </td>
                            </tr>


                            <tr>
                                <td class="td-title" align="right">联系电话：</td>
                                <td class="td-input" align="left">
                                    <input type="text" class="inpt" id="org_phone" name="apply[org_phone]" value="{$coop_info.org_phone}">
                                </td>
                            </tr>


                            <tr>
                                <td class="td-title" align="right">联系邮箱：</td>
                                <td class="td-input" align="left">
                                    <input type="text" class="inpt" id="org_email" name="apply[org_email]" value="{$coop_info.org_email}">
                                </td>
                            </tr>

                            <tr>
                                <td class="td-title" align="right">企业简介：</td>
                                <td class="td-input" align="left">
                                    <input type="text" class="inpt" id="org_summary" name="apply[org_summary]" value="{$coop_info.org_summary}">
                                </td>
                            </tr>


                            <tr>
                                <td class="td-title" align="right">组织机构代码：</td>
                                <td class="td-input" align="left">
                                    <input type="text" class="inpt" id="org_code" name="apply[org_code]" value="{$coop_info.org_code}">
                                </td>
                            </tr>


                            <tr>
                                <td class="td-title" align="right">企业执照注册号：</td>
                                <td class="td-input" align="left">
                                    <input type="text" class="inpt" id="license_no" name="apply[license_no]" value="{$coop_info.license_no}">
                                </td>
                            </tr>


                            <tr>
                                <td class="td-title" align="right">营业执照所在地：</td>
                                <td class="td-input" align="left">
                                    <select class="choose-province sel" id="license_province" name="apply[license_province]" width="200">
                                        <option value="">请选择省份</option>
                                        {if $coop_info.license_province}
                                            <option selected>{$coop_info.license_province}</option>
                                        {/if}
                                    </select>


                                    <select class="choose-city sel" id="license_city" name="apply[license_city]" width="200">
                                        <option value="">请选择城市</option>
                                        {if $coop_info.license_city}
                                            <option selected>{$coop_info.license_city}</option>
                                        {/if}
                                    </select>

                                </td>
                            </tr>


                            <tr>
                                <td class="td-title" align="right">营业执照过期日：</td>
                                <td class="td-input" align="left">
                                    {literal}
                                        <input id="license_expire_date" name="apply[license_expire_date]" value="2015-02-03" class="Wdate" type="text" onFocus="WdatePicker({lang:'zh-cn'})" />
                                    {/literal}
                                </td>
                            </tr>


                            <tr>
                                <td class="td-title" align="right">营业范围：</td>
                                <td class="td-input" align="left">
                                    <input type="text" class="inpt" id="business_scope" name="apply[business_scope]" value="{$coop_info.business_scope}">
                                </td>
                            </tr>


                            <tr>
                                <td class="td-title" align="right">企业营业执照：</td>
                                <td class="td-input" align="left">
                                    <input class="pic-upload" type="file" id="license_pic" name="apply[license_pic]" value="{$coop_info.license_pic}"/>
                                    <div id="image_preview_license_pic" class="preview"></div>
                                </td>
                            </tr>


                            <tr>
                                <td class="td-title" align="right">法人代表姓名：</td>
                                <td class="td-input" align="left">
                                    <input type="text" class="inpt" id="chief_name" name="apply[chief_name]" value="{$coop_info.chief_name}">
                                </td>
                            </tr>


                            <tr>
                                <td class="td-title" align="right">法人代表证件类型：</td>
                                <td class="td-input" align="left">
                                    <select class="sel" id="chief_cert_type" name="apply[chief_cert_type]" width="200">
                                        <option value="身份证">身份证</option>
                                    </select>
                                </td>
                            </tr>


                            <tr>
                                <td class="td-title" align="right">法人证件号码：</td>
                                <td class="td-input" align="left">
                                    <input type="text" class="inpt" id="chief_cert_no" name="apply[chief_cert_no]" value="{$coop_info.chief_cert_no}">
                                </td>
                            </tr>


                            <tr>
                                <td class="td-title" align="right">法人手机号：</td>
                                <td class="td-input" align="left">
                                    <input type="text" class="inpt" id="chief_phone" name="apply[chief_phone]" value="{$coop_info.chief_phone}">
                                </td>
                            </tr>


                            <tr>
                                <td class="td-title" align="right">法人证件扫描：</td>
                                <td class="td-input" align="left">
                                    正面
                                    <input class="pic-upload" type="file" id="chief_cert_front_pic" name="apply[chief_cert_front_pic]" value="{$coop_info.chief_cert_front_pic}"/>
                                    <div id="image_preview_chief_cert_front_pic" class="preview"></div>
                                    反面
                                    <input class="pic-upload" type="file" id="chief_cert_back_pic" name="apply[chief_cert_back_pic]" value="{$coop_info.chief_cert_back_pic}"/>
                                    <div id="image_preview_chief_cert_back_pic" class="preview"></div>
                                </td>
                            </tr>


                            <tr>
                                <td colspan="2">
                                    <p style="border-bottom: 2px solid #3e8ed9;font-size: 20px; margin-top: 10px">银行账户信息</p>
                                </td>
                            </tr>


                            <tr>
                                <td class="td-title" align="right">开户银行：</td>
                                <td class="td-input" align="left">
                                    <select class="sel" id="bank_code" name="apply[bank_code]" width="200">
                                        <option value="">请选择银行</option>

                                        {if $coop_info.bank_code}
                                            <option selected>{$bank_name}</option>
                                        {/if}

                                        {foreach from=$banks_id_name item=bankname key=bankno}
                                            <option value="{$bankno}">{$bankname}</option>
                                        {/foreach}
                                    </select>
                                </td>
                            </tr>


                            <tr>
                                <td class="td-title" align="right">开户所在地：</td>
                                <td class="td-input" align="left">
                                    <select class="choose-province sel" id="bank_province" name="apply[bank_province]" width="200">
                                        <option value="">请选择省份</option>
                                        {if $coop_info.bank_province}
                                            <option selected>{$coop_info.bank_province}</option>
                                        {/if}
                                    </select>

                                    <select class="choose-city sel" id="bank_city" name="apply[bank_city]" width="200">
                                        <option value="">请选择城市</option>
                                        {if $coop_info.bank_city}
                                            <option selected>{$coop_info.bank_city}</option>
                                        {/if}
                                    </select>

                                </td>
                            </tr>


                            <tr>
                                <td class="td-title" align="right">开户银行支行：</td>
                                <td class="td-input" align="left">
                                    <input type="text" class="inpt" id="bank_branch" name="apply[bank_branch]" value="{$coop_info.bank_branch}">
                                </td>
                            </tr>


                            <tr>
                                <td class="td-title" align="right">银行开户姓名：</td>
                                <td class="td-input" align="left">
                                    <input type="text" class="inpt" id="bank_account_name" name="apply[bank_account_name]" value="{$coop_info.bank_account_name}">
                                </td>
                            </tr>


                            <tr>
                                <td class="td-title" align="right">企业银行账号：</td>
                                <td class="td-input" align="left">
                                    <input type="text" class="inpt" id="bank_account_no" name="apply[bank_account_no]" value="{$coop_info.bank_account_no}">
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <p style="border-bottom: 2px solid #3e8ed9;font-size: 20px; margin-top: 10px">企业资质信息</p>
                                </td>
                            </tr>


                            <tr>
                                <td class="td-title" align="right">组织机构代码证：</td>
                                <td class="td-input" align="left">
                                    <input class="pic-upload" type="file" id="org_code_pic" name="apply[org_code_pic]" value="{$coop_info.org_code_pic}"/>
                                    <div id="image_preview_org_code_pic" class="preview"></div>
                                </td>
                            </tr>


                            <tr>
                                <td class="td-title" align="right">税务登记证：</td>
                                <td class="td-input" align="left">
                                    <input class="pic-upload" type="file" id="tax_license_pic" name="apply[tax_license_pic]" value="{$coop_info.tax_license_pic}"/>
                                    <div id="image_preview_tax_license_pic" class="preview"></div>
                                </td>
                            </tr>


                            <tr>
                                <td class="td-title" align="right">银行结算账户开户许可证：</td>
                                <td class="td-input" align="left">
                                    <input class="pic-upload" type="file" id="bank_license_pic" name="apply[bank_license_pic]" value="{$coop_info.bank_license_pic}"/>
                                    <div id="image_preview_bank_license_pic" class="preview"></div>
                                </td>
                            </tr>


                            <tr>
                                <td colspan="2">
                                    <p style="border-bottom: 2px solid #3e8ed9;font-size: 20px; margin-top: 10px">行业资质信息</p>
                                </td>
                            </tr>

                            <tr>
                                <td class="td-title" align="right">行业许可证：</td>
                                <td class="td-input" align="left">
                                    <input class="pic-upload" type="file" id="industry_license_pic" name="apply[industry_license_pic]" value="{$coop_info.industry_license_pic}"/>
                                    <div id="image_preview_industry_license_pic" class="preview"></div>
                                </td>
                            </tr>


                            <tr>
                                <td class="td-title" align="right">信用机构代码证：</td>
                                <td class="td-input" align="left">
                                    <input class="pic-upload" type="file" id="credit_license_pic" name="credit_license_pic" value="{$coop_info.credit_license_pic}"/>
                                    <div id="image_preview_credit_license_pic" class="preview"></div>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>

                {if $focus == 'edit'}
                    <div class="top10" style="padding: 12px 0 0 120px">
                        <button class="btn btn-green" id="btn_save" style="width: 100px">保存</button>
                        <button class="btn btn-green" id="btn_apply" style="width: 100px;margin-left: 12px" href="/mis/apply">提交</button>
                    </div>
                {/if}
            </div>
            </form>

        </div>
    </div>
</div>
{/block}
{block name='js-common'}
<script src="/static/main/js/city.js?v={$LICAI_ZHIGOU_STATIC_VERSION}" type="text/javascript"></script>
<script src="/static/lib/jquery-plugins/jquery.uploadify.min.js?ver={math equation=rand(0,999)}" type="text/javascript"></script>
<script type="text/javascript" src="/static/common/js/My97DatePicker/WdatePicker.js?v={$LICAI_ZHIGOU_STATIC_VERSION}"></script>

<script type="text/javascript">
    $(function(){

        $('#btn_save').click(function(){
            save();
        });
        $("#btn_apply").click(function(){
//            window.location.href="/mis/apply";
            apply();
        })

        var coopInfo = JSON.parse('{$str_coop_info}');

        $('input[type=file]').each(function(){

            var id = $(this).attr('id');
            var id_key = id + '_url';
            var time = new Date().valueOf();
            if (coopInfo[id_key] && coopInfo[id_key].original_img[0]) {
                var previewHtml=''+
                        '<span id="imagespan' + time + '" onmouseover="$(this).find(\'img.del\').show();" onmouseout="$(this).find(\'img.del\').hide();">'+
                        '<a href="' + coopInfo[id_key].original_img[0] + '" target="_blank"><img src="' + coopInfo[id_key].original_img[0] + '" width="80" height="60" /></a>'+
                        '<img id="deleteimage" src="/static/risk/img/delete.png" class="del hidden" style="cursor:pointer" onclick="delImg('+time+');" />&nbsp;'+
                        '</span>';
                previewHtml += '<input type="hidden" id="imageinpt'+time+'" name="apply[' + id + ']" value="' + coopInfo[id] + '" />'
                $('#image_preview_'+id).append(previewHtml).show();
            }
        })


        $('input[type=file]').each(function(){
            var id = $(this).attr('id');
            $('#'+id).uploadify({
                'swf'      : '/static/risk/flash/uploadify.swf',
                'uploader' : '/site/uploadimage',
                'buttonImage': '/static/risk/img/selimage.png',
                'fileTypeDesc': '图片文件',
                'fileTypeExts': '*.jpg;*.jpeg;*.gif;*.png',
                'multi'    : true,
                'auto'     : true,
                'width'    : 72,
                'buttonClass' : 'my-upload-css',
                'onUploadStart' : function(file) {
                    $('#'+id).uploadify("settings", "formData",
                            {
                                "item" : id
                            });
                },
                'onUploadSuccess' : function(file, data, response){
                    var time = new Date().valueOf();
                    var fileArr = data.split('@-@');

                    var imagePriview=createPrivewDiv('image',time,fileArr);//预览
                    var formField=createHiddenField('image',time,fileArr,id);//form字段
                    $('#image_preview_'+id).append(imagePriview).show();
                    $('#form').append(formField);
                }
            });
        });

        //营业执照城市绑定
        if($("#license_province").length!=0){
            var provinceHtml="";
            if(province_city_list.province!=null){
                $(province_city_list.province).each(function(k,v){
                    provinceHtml+='<option value="'+v+'">'+v+'</option>';
                });
            }
            $("#license_province").append(provinceHtml);

            $("#license_province").change(function(){
                var cityHtml="", citymap=province_city_list.citymap;
                if(citymap[$(this).attr("value")]!=null){
                    $(citymap[$(this).attr("value")]).each(function(k,v){
                        cityHtml+='<option value="'+v+'">'+v+'</option>';
                    })
                }
                $("#license_city").parent().find(".dropselectbox h4").html("请选择城市");
                $("#license_city").html(cityHtml);
            })
        }

        //银行开户地城市绑定
        if($("#bank_province").length!=0){
            var provinceHtml="";
            if(province_city_list.province!=null){
                $(province_city_list.province).each(function(k,v){
                    provinceHtml+='<option value="'+v+'">'+v+'</option>';
                });
            }
            $("#bank_province").append(provinceHtml);

            $("#bank_province").change(function(){
                var cityHtml="",citymap=province_city_list.citymap;
                if(citymap[$(this).attr("value")]!=null){
                    $(citymap[$(this).attr("value")]).each(function(k,v){
                        cityHtml+='<option value="'+v+'">'+v+'</option>';
                    })
                }
                $("#bank_city").parent().find(".dropselectbox h4").html("请选择城市");
                $("#bank_city").html(cityHtml);
            })
        }

    });


    function save() {
        $.ajax({
            type: 'post',
            url: '/mis/save',
            data: $('#form').serialize(),
            success: function(data) {
                if (data.length > 1024) { //session过期，跳转到登录页
                    window.location.href="/login";
                }
                alert(data);
//                window.location.href="/site/index";
            }
        })
    }


    function apply() {
        $.ajax({
            type: 'post',
            url: '/mis/apply',
//            data: $('#form').serialize(),
            success: function(data) {
                if (data.length > 1024) {
                    window.location.href="/login";
                } else if ('1' != data) {
                    alert(data);
                } else {
                    window.location.href="/mis/checktip";
                }
            }
        })
    }


    // 自动保存
    function autosave()
    {
        $.ajax({
            type: 'post',
            url: '/mis/save',
            data: $('#form').serialize(),
            success: function(data) {
                console.log(data);
            }
        })
    }
    //定时保存，暂时去掉
//    var obj = window.setInterval(autosave, 60000, true);


    function createPrivewDiv(type,time,f)
    {
        var previewHtml='';

        if(type=='doc'){
            //$(this)find('img.del').show();
            previewHtml=''+
                    '<span id="docspan'+time+ '" onmouseover="$(this).find(\'img.del\').show();" onmouseout="$(this).find(\'img.del\').hide();">'+
                    '<a href="' + f[1] + '" target="_blank"><img src="/static/risk/img/fileicon2.jpg" width="60" height="60" title="点击下载"/></a>'+
                    '<img id="deletedoc" src="/static/risk/img/delete.png" class="del hidden" style="cursor:pointer" onclick="delDoc(' + time + ');" />&nbsp;'+
                    '</span>';
        }
        else if(type=='image'){
            previewHtml=''+
                    '<span id="imagespan' + time + '" onmouseover="$(this).find(\'img.del\').show();" onmouseout="$(this).find(\'img.del\').hide();">'+
                    '<a href="' + f[1] + '" target="_blank"><img src="' + f[1] + '" width="80" height="60" /></a>'+
                    '<img id="deleteimage" src="/static/risk/img/delete.png" class="del hidden" style="cursor:pointer" onclick="delImg('+time+');" />&nbsp;'+
                    '</span>';
        }
        return previewHtml;
    }

    function createHiddenField(type,time,f, id)
    {
        var formHidden='';
        if(type=='image'){
            formHidden='<input type="hidden" id="imageinpt'+time+'" name="apply[' + id + ']" value="' + f[2] + '" />';
        }
        else if(type=='doc'){
            formHidden='<input type="hidden" id="docinpt'+time+ '" name="apply[' + t + ']" value="' + f[2] + '" />';
        }
        return formHidden;
    }

    function delImg(t)
    {
        $('#imagespan' + t).remove();
        $('#imageinpt' + t).remove();
    }

    function delDoc(t)
    {
        $('#docspan' + t).remove();
        $('#docinpt' + t).remove();
    }


</script>
{/block}
