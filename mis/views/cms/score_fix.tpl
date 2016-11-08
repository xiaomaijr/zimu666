{extends file="../_base.tpl"}
{block name="css-common"}
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
        .submit_btn, .cms-history{
            float: left;
            height: 25px;
            line-height: 25px;
            padding: 0 10px;
            margin: 5px 0 0 50px;
            -webkit-border-radius:6px;
            -moz-border-radius:6px;
            border-radius:6px;
            color: #fff;
            font-size: 14px;
            background: #3366aa;
            cursor: pointer;
        }
        .import_mis{
            right: 150px;
            top: 90px;
            position: absolute;
        }
        .td1{
            width: 200px;
        }
        .td2{
            padding-top:15px;
            padding-bottom: 15px
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
        /*zhujun*/
        .audit{
            margin-left: 150px;
        }

        .con-data{
            display: inline-block;
            width:400px;
            height:180px;
            resize: none;
            letter-spacing:3px ;
            font-size: 16px;
            line-height: 30px;
        }

        .reser_div_a{
            width: 200px;
        }

    </style>
{/block}
{block name="content"}



	<!-- 地址导航 -->
    <div class="dind_a"><a href="javascript:void(0);" onclick="history.back()">后退</a></div>

	<div class="reser_box">
		<table cellpadding="0" cellspacing="0" border="0">
		<tbody>
		<tr><th><div class="reser_th_b teg_l pl20">积分详情</div></th></tr>
        {foreach $infos as $key=>$info}
        <tr>
            <td>
                <div class="reser_td_b pl20">
                    <div class="reser_div_a fl  pr10 ">{$info.title}:</div>
                    <div class="reser_div_b fl pr30">
						<span class="lh34 fl pr10">
                            {if in_array($key, $score_keys)}
                                <input type="text" name="{$key}" id="{$key}" value="{round($info[0][0]/100)|default:''}"/>
                            {elseif in_array($key, $input_keys)}
                                <input type="text" name="{$key}" id="{$key}" value="{$info[0][0]|default:''}"/>
                            {else}
                                <textarea cols="20" rows="5" name="{$key}" id="{$key}" class="con-data" >{$info[0][0]|default:''}</textarea>
                            {/if}
                			</span>
                			<span class="lh34 fl pr10">
                    </div>
                </div>
                <div class="submit_btn">提交</div>

                <div class="cms-history" onclick="location.href='/cms/history?p_sign={$key}'">查看历史记录</div>

            </td>
        </tr>
        {/foreach}
		</tbody>
		</table>
	</div>

{/block}

{block name="js-common"}
    <script>
        $(function(){
            $('.submit_btn').bind('click', function(){
                var action = $(this).siblings().find('input').attr('name');
                var con = $(this).siblings().find('input').val();
                var desc = $(this).siblings('.pl20').find('.reser_div_a').text().split(':')[0]
                if(!con){
                    $('[name='+action+']').focus();
                    alert(desc+'不能为空');
//                    alert($('[name='+action+']').parents('.pr30').siblings('.pr10').text().split(':')[0]+'不能为空');
                    return false;
                }
                $.ajax({
                    type     :     'post',
                    url      :     '/cms/score-fix',
                    data     :     'query[sign]='+action+'&query[data]='+con+'&query[desc]='+desc,
                    success  :     function(data){
                        var res = $.parseJSON(data);
                        if(res.code == 0){
                            alert('提交成功');
                            return false;
                        }else{
                            alert(res.message);
                            return false;
                        }
                    }
                })
            });
        });
    </script>
{/block}

