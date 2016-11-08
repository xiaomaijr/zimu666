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
        .wrap{
            word-break:break-all;width:100%; overflow:auto;
        }
        .hr-style{
            margin: 20px auto;
        }
        .power{
            display: inline-block;
            margin-left: 25px;
            margin-top: 5px;
            line-height: 25px;
            /*display: block;*/
        }
        .role{
            margin-left: 5px;
            font-size: 16px;
        }
        .f18{
            font-size: 14px;
        }
        .branch{
            margin-top:10px;
            border: 1px solid #ccc;
        }
        .selectbox{
            display: block;
            overflow: hidden;
            padding: 15px;
        }
        .selectbox a{
            float: left;
            padding: 0 5px;
            margin: 0 10px;
            color: #666;
        }
    </style>
{/block}
{block name="content"}
    <h3 class="top10">权限配置</h3>
    <hr class="hr-style">
    {foreach $roles  as $role}
        <span class="role">
       {if isset($curId)&& $curId neq $role.id}
           <a href="/role/list?role_id={$role.id}">{$role.name}</a>
       {else}
           {$role.name}
       {/if}
        </span>
    {/foreach}
    <hr class="hr-style">
    <div class="selectbox">
        <a class="all f18" href="javascript:;">全选</a>
        <a class="fanxuan f18" href="javascript:;">反选</a>
        <a class="allcancel f18" href="javascript:;">全部取消</a>
    </div>
    <form action="/role/add" method="post">
        <input type="hidden" name="role_id" value="{$curId|default:0}"/>

        <div class="reser_box">
            <table cellpadding="0" cellspacing="0" border="0">
            {if !empty($urls)}
                {foreach $urls as $key=>$rows}

                        <tr><th><div class="reser_th_b teg_l pl20">{$key}</div></th></tr>
                        <tr>
                            <td>
                                {foreach $rows as $id=>$action}
                                <span class="power f18"><input type="checkbox" class="item" name="power[]" value="{$id}" {if in_array($id, $powers)}checked="true" {/if}/>{$action.name}</span>
                                {/foreach}
                            </td>
                        </tr>

                {/foreach}
            {/if}
        </table>
        </div>
        {*<div class="wrap">
        {if !empty($urls)}
            {foreach $urls as $key=>$rows}
                <fieldset class="branch">
                    <legend>{$key}</legend>
                {foreach $rows as $id=>$action}
                    <span class="power f18"><input type="checkbox" class="item" name="power[]" value="{$id}" {if in_array($id, $powers)}checked="true" {/if}/>{$action.name}</span>
                {/foreach}
                </fieldset>
            {/foreach}
        {/if}
        </div>*}
        <div class="selectbox">
        <a class="all f18" href="javascript:;">全选</a>
        <a class="fanxuan f18" href="javascript:;">反选</a>
        <a class="allcancel f18" href="javascript:;">全部取消</a>
        </div>
        <div style="display: block;overflow: hidden; text-align: center">
            <button class="btn" type="submit" value="submit" style="display: inline-block;float: none">提 交</button>
        </div>
    </form>
    {if !empty($records)}
        {include file="_records.tpl"}
    {/if}
{/block}
{block name="js-common"}
    <script>
        $(".all").on("click",function(){
            $(".item").attr("checked",true);
        });
        $('.fanxuan').click(function () {
            $(".item").each(function(){
                if($(this).attr("checked")){
                    $(this).removeAttr("checked");
                }else{
                    $(this).attr("checked",true);
                }
            });
        });
        $('.allcancel').click(function(){
            $(".item").attr("checked",false);
        });
    </script>
{/block}
