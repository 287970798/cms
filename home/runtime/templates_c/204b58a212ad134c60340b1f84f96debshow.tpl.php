<!DOCTYPE html>
<html lang=zh>
<head>
<meta charset="utf-8">
<title>teacher - <?php echo $this->config["webname"]?></title>
</head>
<body>
<?php foreach($this->var["teachers"] as $key=>$value){?>
<?php echo $value->name?>-<?php echo $value->phone?><br>
<?php }?>
</body>
</html>
