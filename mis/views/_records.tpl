{*<tr>
    <td colspan="2">
        <div class="reser_td_b pl20">
            <div class="reser_div_a fl  pr10 ">操作日志:</div></div>
        <div class="playlogbox">
            {foreach $records as $record}
                <p>{$record.operator}操作了{$record.business_desc}&nbsp;&nbsp;&nbsp;{$record.record_time}</p>
            {/foreach}
        </div>
    </td>
</tr>*}
<table cellpadding="0" cellspacing="0" width="100%" style="margin:20px 0 0;color: #575757;height: 38px;line-height: 38px;background: #F2F1FF;border: 1px solid #E0E0E0;font-size: 14px;">
    <tr><td style="padding: 0 0 0 20px; font-size: 14px;font-weight: bold">操作日志:</td></tr>
</table>
<table class="recordstab"  cellpadding="0" cellspacing="0" width="100%" style="font-size: 14px;color: #575757;height: 38px;line-height: 38px;text-align: center">
    <tr style="background: #FDF6DA;color: #333;">
        <th>角色</th>
        <th>操作人</th>
        <th>操作记录</th>
        <th>时间</th>
        <th>状态</th>
    </tr>
    {foreach $records as $record}
    <tr>
        <td>{$record.role}</td>
        <td>{$record.operator}</td>
        <td>{$record.business_desc}</td>
        <td>{$record.record_time}</td>
        <td>有效</td>
    </tr>
    {/foreach}
</table>
<style type="text/css">
    .recordstab{
        border: 0;
        border-right: 1px solid #E0E0E0;
    }
    .recordstab td,.recordstab th{
        font-size: 14px;
        border-left: 1px solid #E0E0E0 ;
        border-bottom: 1px solid #E0E0E0 ;
    }
</style>