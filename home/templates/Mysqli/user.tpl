<!DOCTYPE html>
<html lang=cn>
<head>
<meta charset="utf-8">
<title>用户列表 - <!--{webname}--></title>
</head>
<body>
	<nav>
		<a href="?c=mysqli&a=add">新增</a>
	</nav>
	<table>
		<tr>
			<td>编号</td>
			<td>用户名</td>
			<td>年龄</td>
			<td>操作</td>
		</tr>
		{foreach $users(key,value)}
			<tr>
				<td>{@key+1}</td>
				<td>{@value['username']}</td>
				<td>{@value['age']}</td>
				<td><a href='?c=mysqli&a=updateUser&id={@value['id']}'>更新</a> | <a href='?c=mysqli&a=deleteUser&id={@value['id']}' onclick='javascript:return confirm("确定要删除吗？");'>删除</a></td>
			</tr>
		{/foreach}
	</table>
</body>
</html>
