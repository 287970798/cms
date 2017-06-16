<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title></title>
</head>
<body>
	<img src="?c=gd&a=test4" alt=""  id="verifyCode">
	<span id="code"></span>
	<script>
		var verifyCode = document.getElementById('verifyCode');
		verifyCode.onclick = function(){
			this.src="?c=gd&a=test4&mt="+Math.random()	
		}
	</script>
</body>
</html>
