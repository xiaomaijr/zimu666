<div class="left_nav">
    <span>zimu.com</span>
    <div class="leftbox">
        <!----list--->
        <{foreach RiskConfig::$menu as $url => $data}>
        <div class="list">
            <label><{$data.title}><i>&lt;</i></label>
            <div class="lefthide">
                <{foreach $data.mlist as $purl => $title}>
                <a href="<{$purl}>" class="on"><{$title}></a>
                <{/foreach}>
            </div>
        </div>
        <{/foreach}>
        <!----list end--->
    </div>
</div>
