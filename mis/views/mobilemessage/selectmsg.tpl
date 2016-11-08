{extends file="_base.tpl"}
{block name="content"}
    <style>
        .d_content{
            display: block;
            overflow: hidden;
            margin: 0 100px;
            font-size: 14px;
            color: #575757;
        }
        .titletable{
            height: 38px;
            line-height: 38px;
            background: #F2F1FF;
            border: 1px solid #E0E0E0;
        }
        .titletable td{
            padding: 0 0 0 20px;
            font-weight: bold
        }
        .contenttable{
            border: 1px solid #E0E0E0;
            line-height: 34px;
            border-bottom: 0;
            border-top: 0;
        }
        .contenttable td{
            padding: 5px 0;
            border-bottom: 1px solid #E0E0E0;
        }
        .td1{
            width: 200px;
            text-indent: 20px;
        }
        .contenttable td input{
            float: left;
            display: block;
            margin-right: 10px;
            padding: 6px 12px;
            width: 300px;
            height: 20px;
            border: 1px solid #E0E0E0;
            border-radius: 4px;
            background-color: #fff;
            background-image: none;
            font-size: 14px;
            color: #575757;
            line-height: 1.42857143;
            -webkit-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
            -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
            transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
        }
        .contenttable td input[type=radio]{
            width: 20px;
            position: relative;
            top:8px;
        }
        .contenttable td span{
            float: left;
            line-height: 35px;
            margin: 0 50px 0 0;
        }
        .contenttable td span.submit-btn {
            display: inline-block;
            border: 1px solid #c5dbec;
            background: #dfeffc url(images/ui-bg_glass_85_dfeffc_1x400.png) 50% 50% repeat-x;
            font-weight: bold;
            color: #2e6e9e;
            margin: 3px 0;
            padding: 0 15px;
            border-radius: 5px;
            line-height: 25px;
            cursor: pointer;
        }
        .overtable{
            display: none;
        }
    </style>
<div class="d_content">
    <table cellpadding="0" cellspacing="0" width="100%" class="titletable" style="margin: 20px 0 0">
        <tr><td>验证码查询:</td></tr>
    </table>
    <table cellpadding="0" cellspacing="0" width="100%" class="contenttable">
        <tr>
            <td class="td1">用户类型:</td>
            <td class="selectnumbox">
                <input type="radio" name="typeval" id="" checked="checked" value="1" />
                <span>用户</span>
                <input type="radio" name="typeval" id="" value="2" />
                <span>销售</span>
            </td>
        </tr>
        <tr>
            <td class="td1">用户手机号:</td>
            <td>
                <input type="text" name="" id="phonenum" maxlength="11" />
                <span class="submit-btn">提交</span>
            </td>
        </tr>
    </table>
    <table cellpadding="0" cellspacing="0" width="100%" class="titletable overtable" style="margin: 20px 0 0">
        <tr><td>查询结果:</td></tr>
    </table>
    <table cellpadding="0" cellspacing="0" width="100%" class="contenttable overtable">
        <tr>
            <td class="td1">结果验证码:</td>
            <td>
                <span id="overnumber">1234</span>
            </td>
        </tr>
    </table>
</div>
{/block}
{block name="js-common"}
    <script>
        $(function(){
            $('.submit-btn').click(function(){
                var phone= $.trim($('#phonenum').val());
                var radioval=$('.selectnumbox input[name="typeval"]:checked').val();
                if(phone==''||phone.length!=11){
                    alert('手机号格式错误');
                    return false;
                }
                $.post('/mobilemessage/search-code?mobile='+phone+'&role='+radioval,function(data){
                    var data=eval('('+data+')');
                    if(data.code==0){
                        $('.overtable').show();
                        $('#overnumber').text(data.result);
                    }else{
                        alert(data.message)
                    }
                });
            });
        });
    </script>
{/block}
