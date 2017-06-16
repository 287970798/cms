<!DOCTYPE html>
<html lang="zh">
<head>
<title><!--{webname}--></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,inital-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
<meta name="keywords" content="">
<meta name="description" content="">
</head>
<body>
{include file='header.tpl'}
{$title}
{$substyle}
{if $style}
样式一
    {if $substyle}
    sub
    {/if}
{else}
样式二
{/if}

{foreach $a(key,value)}

{@key}-{@value}<br>

{/foreach}

{include file='footer.tpl'}
</body>
</html>
