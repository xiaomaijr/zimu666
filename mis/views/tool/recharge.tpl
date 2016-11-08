<{extends file="../_base.tpl"}>
<{block name="content"}>
        <table cellpadding="0" cellspacing="0" class="tb-form top10">
            <tr>
                <td class="td1"><em class="required">*</em>用户手机号：</td>
                <td>
                    <input type="text" name="mobile" id="mobile" value="" class="validate[required]" required="true"/>
                </td>
            </tr>
            <tr>
                <td class="td1"><em class="required">*</em>充值金额(元)：</td>
                <td>
                    <input type="text" name="money" id="money" value="0" class="validate[required]"  cate="num"  required="true"/>
                </td>
            </tr>
        </table>
        <div align="center"><button class="btn recharge">提 交</button></div>
<{/block}>
<{block name="js-common"}>
    <script>
        $(document).ready(function() {
            $('.recharge').on('click', function(){
                var mobile = $('#mobile').val();
                if (mobile == '') {
                    window.alert('请填写用户手机号！');
                    $('#mobile').focus();
                    return false;
                }
                var money = $('#money').val();
                if (!money) {
                    window.alert('请填写充值金额！');
                    $('#money').focus();
                    return false;
                }
                $.ajax({
                    url     :       '/tool/recharge',
                    data    :       'mobile='+mobile+'&money='+money,
                    type    :       'post',
                    dataType:       'json',
                    success :       function(data){
                        if (data.code == 0) {
                            window.alert('充值成功!');
                            return false;
                        }
                        window.alert(data.message);
                        return false;
                    }
                });
            });
        });
    </script>
    <{/block}>