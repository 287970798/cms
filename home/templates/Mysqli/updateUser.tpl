<!DOCTYPE html>
<html lang=cn>
<head>
<meta charset="utf-8">
<title>更新用户 - <!--{webname}--></title>
<style>
	label{
		display:inline-block;
		width:60px;
	}
	input{
		width:200px;
	}
</style>
</head>
<body>
	<form action="?c=mysqli&a=updateUser" method="post">
		<input type='hidden' name='id' value="{$user['id']}">
		<div>
			<label for="username">用户名</label>
			<input type="text" name="username" id="username" value="{$user['username']}">
		</div>
		<div>
			<label for="password">密码</label>
			<input type="password" name="password" id="password">
		</div>
		<div>
			<label for="age">年龄</label>
			<input type="number" min="1" max="125" name="age" id="age" value="{$user['age']}">
		</div>
		<div>
			<button type="submit" name="submit">更新</button>
		</div>
	</form>
</body>
</html>
