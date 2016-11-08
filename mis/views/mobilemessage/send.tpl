{extends file="_base.tpl"}
{block name="content"}
    <style>
        .ui-widget-content .item {
            margin:2px 10px 2px 0;
            float:left;
        }
        .f1{
            font-size: 16px;
        }
        .f1 input{
            height:100%;
            width:100%;
        }
        .reserva-list{
            /*float:left;*/
        }
        .list-info{
            float:left;
            margin:10px;
            width:400px;
        }

    </style>
    <h2 class="top10">人工派单</h2>
    <table class="tb-list top10 f1" cellpadding="0" cellspacing="0">
        <form action="/mobilemessage/send" method="post">
        <tbody>
        <tr align="center"><td style="width:300px;">请输入用户手机号码:</td><td><input type="text" name="query[mobile]" class="form-input" id="mobile"/></td><td style="width:500px;">多个手机号请用英文逗号','隔开</td></tr>
        <tr align="center"><td>请输入短信内容:</td><td>
                <textarea cols="10" rows="5" name="query[message]" style="width:90%;"></textarea>
            </td><td>字数不能超过五百</td></tr>
        <tr align="center"><td colspan="3"><button type="submit">发送</button></td></tr>
        </tbody>
        </form>
    </table>
{/block}
{block name="js-common"}
    <script>
    </script>
{/block}
