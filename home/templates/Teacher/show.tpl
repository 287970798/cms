<!DOCTYPE html>
<html lang=zh>
<head>
<meta charset="utf-8">
<title>teacher - <!--{webname}--></title>
</head>
<body>
{foreach $teachers(key,value)}
{@value->name}-{@value->phone}<br>
{/foreach}
</body>
</html>
