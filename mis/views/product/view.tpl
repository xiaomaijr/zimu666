<{extends file="../_base.tpl"}>
<{block name="css-common"}>
    <style>
        .district-li{
            float:left;
            width:140px;
        }
        input[type='checkbox']{
            height: 12px;
        }

        #main{
            padding-left: 100px;
            padding-top: 20px;
        }
        .import_mis{
            right: 150px;
            top: 90px;
            position: absolute;
        }
        .td1{
            width: 200px;
        }
        .brand{
            fload:left;
            padding-right: 20px;
            line-height: 20px;
        }
        table,input{
            font-size: 18px;
        }
        input{
            color:green;
            /*width:600px;*/
        }
    </style>
<{/block}>
<{block name="content"}>
    <{*<h3 class="top10" style="margin-left: 30px;">
        <span style="font-size: 22px;"><{if !isset($action)}>详情<{elseif $action eq 1}>添加<{elseif $action eq 2}>编辑<{/if}></span>
    </h3>*}>
    <{if isset($action)}>
    <form method="post" action="<{if $action eq 2}><{'/product/edit'}><{elseif $action eq 1}><{'/product/add'}><{/if}>" id="f1" class="user_form"><{/if}>
    <input type="hidden" name="id" value="<{if isset($info.id)}><{$info.id}><{/if}>">
    <table cellpadding="0" cellspacing="0" class="tb-form top10" style="padding: 30px;width:900px;">
        <tr>
            <td class="td1" style="color:#2E6E9E; font-size:14px; border-bottom:1px solid #2E6E9E;align-content: center;"><span
                        style="font-size:20px;padding-right:30px">基本信息</span></td>
            <td style="border-bottom:1px solid #2E6E9E;width:500px;align-content: center"></td>
        </tr>
        <tr style="height:5px">
            <td colspan="2">&nbsp;</td>
        </tr>


        <tr>
            <td class="td1"><{if isset($action)}><em class="required">*</em><{/if}>商品名：</td>
            <td style="padding-top:15px;padding-bottom: 15px">
                <input type="text" name="query[name]" id="name" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}>
                       value="<{$info.name|default:''}>" class="validate[required]" required="true"/>
            </td>
        </tr>

        <tr>
            <td class="td1"><{if isset($action)}><em class="required">*</em><{/if}>价钱(元)：</td>
            <td style="padding-top:15px;padding-bottom: 15px">
                <input type="text" name="query[price]" id="price" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}>
                       value="<{$info.price/100|default:''}>" class="validate[required]" required="true"/>
            </td>
        </tr>

        <tr>
            <td class="td1">抢购最低款(元)：</td>
            <td style="padding-top:15px;padding-bottom: 15px">
                <input type="text" name="query[min_price]" id="min_price" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}>
                value="<{$info.min_price/100|default:''}>" class="validate[required]"/>
            </td>
        </tr>
        <tr>
            <td class="td1">类别名：</td>
            <td style="padding-top:15px;padding-bottom: 15px">
                <{if isset($action)}>
                    <select class="sel" name="query[category_id]" style="width:150px;">
                        <option value="">----------请选择----------</option>
                        <{foreach $categorys as $k=>$v}>
                            <option value="<{$k}>" <{if isset($info.cagetory_id)&&$info.category_id eq $k}>selected<{/if}>><{$v.name}></option>
                        <{/foreach}>
                    </select>
                <{else}>
                    {$categorys[$info.category_id]['name']|default:''}
                <{/if}>
            </td>
        </tr>


        <tr>
            <td class="td1">是否设置为热门推荐商品：</td>
            <td style="padding-top:15px;padding-bottom: 15px">
                <input type="radio" name="query[is_hot]" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}>
                       value="<{$info.is_hot|default:1}>" <{if isset($info.is_hot) && $info.is_hot eq 1}>checked="true"<{/if}> class="validate[required]"/>是
                <input type="radio" name="query[is_hot]" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}>
                value="<{$info.is_hot|default:0}>" <{if !isset($info.is_hot) || $info.is_hot eq 0}>checked="true"<{/if}> class="validate[required]"/>否
            </td>
        </tr>

        <tr>
            <td class="td1">是否设置为最新商品：</td>
            <td style="padding-top:15px;padding-bottom: 15px">
                <input type="radio" name="query[is_index]" id="is_index" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}>
                value="<{$info.is_index|default:1}>" <{if isset($info.is_index) && $info.is_index eq 1}>checked="true"<{/if}> class="validate[required]"/>是
                <input type="radio" name="query[is_index]" id="is_index" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}>
                value="<{$info.is_index|default:0}>" <{if isset($info.is_index) && $info.is_index eq 0}>checked="true"<{/if}> class="validate[required]"/>否
            </td>
        </tr>



        <tr>
            <td class="td1">款式：</td>
            <td style="padding-top:15px;padding-bottom: 15px">
                <input type="text" name="query[style]" id="style" <{if !isset($action)}>readonly='true' style='border-style:none;'<{/if}>
                       value="<{$info.style|default:''}>" class="validate[required]"/>
            </td>
        </tr>
        <tr>
            <td class="td1">颜色：</td>
            <td style="padding-top:15px;padding-bottom: 15px">
                <input type="text" name="query[colors]" id="colors" <{if !isset($action)}>readonly='true'
                       style='border-style:none;width:600px;'<{/if}>
                       value="<{$info.color|default:''}>" class="validate[required]"/>

            </td>
        </tr>
        <tr>
            <td class="td1">插图：</td>
            <td style="padding-top:15px;padding-bottom: 15px">
                <input type="text" name="query[cover_images]" id="cover_images" <{if !isset($action)}>readonly='true'
                       style='border-style:none;width:600px;'<{/if}>
                       value="<{$info.cover_images|default:''}>" class="validate[required]"/>
                <{if isset($action)}>
                    <input type="file" name="upload_file_cover" id="upload_file_cover" value="0" >
                    <a  onclick="upload('product','upload_file_cover','cover_images')" href="javascript:;"><span style="font-size: 20px;
                   color:#c7616d;">上传</span></a>
                <{/if}>
            </td>
        </tr>

        <tr>
            <td class="td1">图片：</td>
            <td style="padding-top:15px;padding-bottom: 15px">
                <input type="text" name="query[image]" id="image" <{if !isset($action)}>readonly='true'
                       style='border-style:none;width:600px;'<{/if}>
                       value="<{$info.image|default:''}>" class="validate[required]"/>
                <{if isset($action)}>
                    <input type="file" name="upload_image" id="upload_image" value="0" >
                    <a  onclick="upload('product','upload_image','image')" href="javascript:;"><span style="font-size: 20px;
                   color:#c7616d;">上传</span></a>
                <{/if}>
            </td>
        </tr>


        <{if !isset($action)}>
            <tr>
                <td class="td1">上架状态：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    <{$info.status|default:1|replace:0:'上架'|replace:1:'下架'}>
                </td>
            </tr>
        <{/if}>

        <{if !isset($action)}>
            <tr>
                <td class="td1">创建时间：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    <{$info.create_time|date_format:"%Y-%m-%d %T"|default:''}>
                </td>
            </tr>
            <tr>
                <td class="td1">最近操作时间：</td>
                <td style="padding-top:15px;padding-bottom: 15px">
                    <{$info.update_time|date_format:"%Y-%m-%d %T"|default:''}>
                </td>
            </tr>
        <{/if}>
        <tr style="height:5px">
            <td colspan="2">&nbsp;</td>
        </tr>

    </table>

    <{if isset($action)}>
    <div align="left"><button class="btn" type="submit" value="submit" style="margin-left: 300px;">提 交</button></div>
    <{/if}>
<{/block}>
<{block name="js-common"}>
    <script>

        function upload(type,id,viewid){
            if(type == undefined){
                type = $("#type").val();
            }
            if(id == undefined){
                var id = 'file';
            }
            file = $("#"+id);
            var filename = file.val();
            if(type == 0){
                alert('图片类型不能为空');
                return false;
            }
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
                url  : '/product/upload',
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
                            $("#"+viewid).val(image_urls);
                        }
                        alert(result.src);
                    }else{
                        alert(result.message);
                    }
                }
            });
        }
    </script>
<{/block}>
