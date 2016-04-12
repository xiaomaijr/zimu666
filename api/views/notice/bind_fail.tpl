<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" charset="UTF-8" content="user-scalable=no"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
    <script src="/assets/js/jquery-1.8.3.min.js"></script>
    <title>开户失败</title>
    <style>
        @charset "utf-8";
        /* CSS Document */
        body, div, dl, dt, dd, ul, ol, li, h1, h2, h3, h4, h5, h6, pre, form, fieldset, input, textarea, p, blockquote, th, td {margin: 0;padding: 0;}
        fieldset, img {border: 0 none;}
        table {border-collapse: collapse;border-spacing: 0;}
        ol, ul {list-style: none outside none;}
        address, caption, cite, code, dfn, em, strong, th, var ,i,b{font-style: normal;font-weight: normal;}
        caption, th {text-align: left;}
        h1, h2, h3, h4, h5, h6 {font-size: 100%;font-weight: normal;}
        abbr, acronym {border: 0 none;}

            /* 清浮动 */
        .cf:before,.cf:after{content:" ";display:table}
        .cf:after{clear:both}
        .cf{*zoom:1}
        body{font-size:1rem;background:#f9f9f9;padding-bottom:2rem;}
        .wrapper{max-width: 100%;background:#f9f9f9;}
        header,nav,section{max-width: 100%;}
        header img{max-width:100%}
        header img{max-width:60%}
        a:link{color:#fff;}
        nav,footer{background:#1a50a4;height:2.9rem;line-height:3rem;color:#fff;text-align:center;font-size:1.3rem;margin-bottom:2rem;}
        footer{position:absolute;bottom:0;left:0;right:0;}
        footer a{color:#fff;text-decoration:none;}
        .ven_cont_con{padding:0.5rem;margin-bottom:1rem;}
        .ven_cont_con p{padding:1rem;border:1px solid #95b7ea;border-radius:3px;background:#edf3fd;font-size:1.125rem;line-height:1.5rem;color:#242424;}
        .contact{padding:2.5rem 0.5rem;font-size:1.125rem;}
        .demandScc dt{float:left;margin-left:15%;width:4rem;}
        .demandScc dt img{width:4rem;height:4rem;}
        .demandScc dd{float:left;margin-left:1rem;line-height:5rem;font-size:1.5rem;color:#242424;}

    </style>
</head>
<body>
<div class="wrapper">
    <nav><span>小麦金融网</span></nav>
    <section>
        <article class="contact">
            <dl class="demandScc cf">
                <dt><img src="/assets/img/notice/y_fail.gif" alt="开户失败"/></dt>
                <dd>开户失败!参数有误, 异常编码：<{$resultCode|default:''}></dd>
            </dl>
        </article>
    </section>
    <footer><a href="xiaomai://register">点击返回APP</a></footer>
</div>

</body>

</html>

