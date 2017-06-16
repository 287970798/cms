<?php
class MysqliAction extends Action {
	private $mysqli;
	public function __construct(){
		parent::__construct();
		$mysqli = @new mysqli('localhost','root','renxinwei','school');
		if($mysqli->connect_errno){
			die('数据库连接出错：'.$mysqli->connect_error);
		}
		$mysqli->set_charset('utf8');
		$this->mysqli = $mysqli;
	}

	public function connect(){
	}

	public function create(){
		$sql = <<<EOF
			CREATE TABLE IF NOT EXISTS user (
				id TINYINT(3) UNSIGNED AUTO_INCREMENT KEY,
				username VARCHAR(20) NOT NULL,
				password CHAR(32) NOT NULL,
				age TINYINT(3) UNSIGNED DEFAULT 18
			);
EOF;
		$res = $this->mysqli->query($sql);
		dump($res);
		if($res){
			echo '创建成功！';
		}else{
			echo '创建失败！';
		}
	}

	public function insert(){
		//执行一条SQL语句
		//$sql = "INSERT user(username,password) VALUES ('yyloon','yyloon')";
		$sql = "INSERT user(username,password) VALUES ('yyloon1','yyloon1'),('yyloon2','yyloon2'),('yyloon3','yyloon3')";	
		$res = $this->mysqli->query($sql);
		if($res){
			//得到上一步插入操作返回的自增长的值	
			echo '恭喜您注册成功，您是网站第'.$this->mysqli->insert_id.'位用户。<br>';
			echo '有'.$this->mysqli->affected_rows.'条记录受影响。';
		}else{
			//得到上一步的错误号与错误信息	
			echo 'ERROR:'.$this->mysqli->errno.':'.$this->mysqli->error;
		}
	}

	public function update(){
		$sql = "UPDATE user SET age = age + 10";
		$res = $this->mysqli->query($sql);
		if($res){
			echo '共有'.$this->mysqli->affected_rows.'条记录受影响。';
		}else{
			echo 'ERROR:'.$this->mysqli->errno.':'.$this->mysqli->error;
		}
	}

	public function delete(){
		$sql = "DELETE FROM user WHERE id >= 6";
		$res = $this->mysqli->query($sql);
		if($res){
			echo '共有'.$this->mysqli->affected_rows.'条记录受影响。';
		}else{
			echo 'ERROR:'.$this->mysqli->errno.':'.$this->mysqli->error;
		}
	}


	public function affected_rows(){
		/*
		 affected_rows返回值的三种情况
		 sql语句错误 ： -1
		 没有记录	 ： 0
		 有记录时	 ： 1
		*/
	}

	
	public function select(){
		$sql = "SELECT * FROM user";
		$mysqli_result = $this->mysqli->query($sql);
		//dump($mysqli_result);
		if($mysqli_result && $mysqli_result->num_rows > 0){
			//echo $mysqli_result->num_rows;
			//$rows = $mysqli_result->fetch_all();//获取结果集中的所有记录，默认返回的是二维的索引+索引形式的数组
			//$rows = $mysqli_result->fetch_all(MYSQLI_NUM);
			//$rows = $mysqli_result->fetch_all(MYSQLI_ASSOC);//关联数组的形式返回二维数组
			//$rows = $mysqli_reuslt->fetch_all(MYSQLI_BOTH);//既有索引又有关联
			//dump($rows);
			/*
			$row = $mysqli_result->fetch_row();//取得结果集中的一条记录作为索引数组返回
			dump($row);

			$row = $mysqli_result->fetch_assoc();//取得结果集中的一条记录作为关联数组返回
			dump($row);

			$row = $mysqli_result->fetch_array();//返回的数组既有索引也有关联
			dump($row);

			$row = $mysqli_result->fetch_array(MYSQLI_NUM);
			dump($row);

			$row = $mysqli_result->fetch_array(MYSQLI_ASSOC);
			dump($row);

			$row = $mysqli_result->fetch_array(MYSQLI_BOTH);
			dump($row);
			
			$row = $mysqli_result->fetch_object();//以对象来返回一记录
			dump($row);

			//移动结果集内部指针
			$mysqli_result->data_seek(0);
			$row = $mysqli_result->fetch_assoc();
			dump($row);
			*/

			while($row = $mysqli_result->fetch_assoc()){
				dump($row);
			}
			//释放结果集
			$mysqli_result->free();

		}else{
			echo '查询错误或结果集中无记录！';
		}
		//关闭连接
		$this->mysqli->close();

	}

	public function getUser(){
		$sql = "SELECT * FROM user";
		$mysqli_result = $this->mysqli->query($sql);
		if($mysqli_result && $mysqli_result->num_rows>0){
			$users = $mysqli_result->fetch_all(MYSQLI_ASSOC);
			$this->assign('users',$users);
			$this->display('user.tpl');
		}else{
			echo '查询出错或者结果集为空！';
		}
	}

	public function add(){
		if(!isset($_POST['submit'])){
			$this->display('addUser.tpl');
		}else{
			$username = $_POST['username'];
			$username = $this->mysqli->escape_string($username);
			$password = md5($_POST['password']);
			$age = $_POST['age'];

			$sql = "INSERT user(username,password,age) VALUES('{$username}','{$password}','{$age}')";
			$res = $this->mysqli->query($sql);
			if($res){
				echo "<script type='text/javascript'>
					alert('插入成功！您是网站第{$this->mysqli->insert_id}位用户！');
					location.href='?c=mysqli&a=getUser';
				</script>";

			}else{
				echo "<script type='text/javascript'>
						alert('插入失败！');
						history.back();
					</script>";
			}
		}
	}

	public function updateUser(){
		if(!isset($_POST['submit'])){
			$id = $_GET['id'];
			$sql = "SELECT * FROM user WHERE id = $id";
			$mysqli_result = $this->mysqli->query($sql);
			if($mysqli_result && $mysqli_result->num_rows>0){
				$user = $mysqli_result->fetch_assoc();
				$this->assign('user',$user);
			}
			$this->display('updateUser.tpl');
		}	
		if(isset($_POST['submit'])){
			$sql = 'UPDATE user SET ';
			foreach($_POST as $key=>$value){
				if($key == 'submit') continue;
				if($key == 'password' && $value == '') continue;
				if($key == 'id') continue;
				$sql .= "$key='$value',";
			}
			$sql = substr($sql,0,-1);
			$sql .= "WHERE id='{$_POST['id']}'";
			$res = $this->mysqli->query($sql);
			if($res){
				echo "<script>alert('更新成功！');location.href='?c=mysqli&a=getUser'</script>";
			}else{
				echo '<script>alert("更新失败！'.$this->mysqli->errno.':'.$this->mysqli->error.$sql.'");history.back();</script>';
			}
		}
	}

	public function deleteUser(){
		if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
			echo '<script>alert("id参数错误！");history.back();</script>';
		}
		$id = intval($_GET['id']);
		$sql = "DELETE FROM user WHERE id = '{$id}'";
		$res = $this->mysqli->query($sql);
		if($res){
			echo '<script>alert("删除成功！");history.back()</script>';
		}else{
			echo '<script>alert("删除失败！'.$this->mysqli->errno.':'.$this->mysqli->error.'");history.back()</script>';
		}
	}
	//执行多条语句
	public function multi_query(){
		//1.多条语句用分号分割
		//2.use_result()/store_result()来获取结果集
		//3.more_results()检测是否有更多结果集
		//4.next_result()将结果集指针向下移一位
		$sql = "SELECT * FROM user WHERE id < 10;";
		$sql .= "SELECT * FROM user WHERE id > 20;";
		$res = $this->mysqli->multi_query($sql);
		var_dump($res);
		do{
			if($result = $this->mysqli->store_result()){
				$rows = $result->fetch_all();
				dump($rows);
			}
		}while($this->mysqli->more_results() && $this->mysqli->next_result());
	}
	//预处理语句(插入)
	public function stmt(){
		//准备预处理语句
		$sql = "INSERT INTO user(username,password,age) VALUES(?,?,?)";	
		$mysqli_stmt = $this->mysqli->prepare($sql);
		//dump($mysqli_stmt);
		//绑定参数
		$username = 'jim';
		$password = md5($username);
		$age = 20;
		$mysqli_stmt->bind_param('ssi',$username,$password,$age);
		//执行预处理语句
		if($mysqli_stmt->execute()){
			echo $mysqli_stmt->insert_id;
		}else{
			echo $mysqli_stmt->error;
		}
	}
	//预处理语句防止sql注入 (' or 1=1 #)(查询)
	public function login(){
		$username = $_GET['username'];
		$password = $_GET['password'];
		$sql = "SELECT * FROM user WHERE username=? AND password=?";
		$mysqli_stmt = $this->mysqli->prepare($sql);
		$mysqli_stmt->bind_param('ss',$username,$password);
		if($mysqli_stmt->execute()){
			$mysqli_stmt->store_result();	
			if($mysqli_stmt->num_rows>0){
				echo '登录成功！';
			}else{
				echo '登录失败！';
			}
		}
		//释放结果集
		$mysqli_stmt->free_result();
		//关闭预处理语句
		$mysqli_stmt->close();
		//关闭数据库连接
		$this->mysqli->close();
	}
	//预处理 查询
	public function stmt_select(){
		$id = $_GET['id'];
		$sql = "SELECT id,username,age FROM user WHERE id>=?";
		$mysqli_stmt = $this->mysqli->prepare($sql);
		$mysqli_stmt->bind_param('i',$id);
		if($mysqli_stmt->execute()){
			//bind_result()绑定结果集中的值到变量
			$mysqli_stmt->bind_result($id,$username,$age);
			while($mysqli_stmt->fetch()){
				echo '编号：'.$id;
				echo '用户名：'.$username;
				echo '年龄：'.$age;
				echo '<hr>';
			}
		}	
		$mysqli_stmt->free_result();
		$mysqli_stmt->close();
		$this->mysqli->close();
	}

	//事务
	public function transaction(){
		/*
		$sql = <<<EOF
			CREATE TABLE IF NOT EXISTS account (
				id TINYINT(3) UNSIGNED AUTO_INCREMENT KEY,
				username VARCHAR(20) NOT NULL UNIQUE,
				money FLOAT(6,2) 
			);
EOF;
		$sql = "INSERT account (username,money) VALUES('mary',500)";
		$res = $this->mysqli->query($sql);
		if($res) {
			echo '建表成功！';
		}else{
			echo $this->mysqli->error;
		}
		*/
		//关闭自动提交
		$this->mysqli->autocommit(false);
		$sql = "UPDATE account SET money = money-200 WHERE username = 'tom'";
		$res = $this->mysqli->query($sql);
		$res_affect = $this->mysqli->affected_rows;

		$sql1 = "UPDATE account SET money = money+200 WHERE username = 'mary'";
		$res1 = $this->mysqli->query($sql1);
		$res1_affect = $this->mysqli->affected_rows;
		
		if($res && $res_affect>0 && $res1 && $res1_affect>0){
			$this->mysqli->commit();
			echo '转账成功！';
			$this->mysqli->autocommit(true);
		}else{
			$this->mysqli->rollback();
			echo '转账失败！';
		}
		$this->mysqli->close();
	}

	public function test(){
		$mysqli = new mysqli('localhost','root','renxinwei','school');
		if($mysqli->connect_errno){
			exit('数据连接出错。'.$mysqli->errno.':'.$mysqli->error);
		}
		$mysqli->set_charset('utf8');
		$sql = "SELECT * FROM user WHERE id < 5 LIMIT 1;";
		$sql .= "SELECT * FROM user	WHERE id > 10 LIMIT 1;";
		$sql .= "UPDATE user SET age = 10 WHERE id < 5 LIMIT 1;";
		$res = $mysqli->multi_query($sql);
		do{
			if($result = $mysqli->store_result(MYSQLI_ASSOC)){
				$rows = $result->fetch_all(MYSQLI_ASSOC);
				dump($rows);
			}
		}while($mysqli->more_results() && $mysqli->next_result());

		$sql = <<<EOF
			CREATE TABLE IF NOT EXISTS students (
				id INT(3) UNSIGNED AUTO_INCREMENT KEY,
				name VARCHAR(10) NOT NULL,
				age INT(2) NOT NULL
			);
EOF;
		$res = $mysqli->query($sql);
		$sql = "INSERT INTO students (name, age) VALUES ('tom',20);";
		$sql .= "SELECT * FROM students;";
		$res = $mysqli->multi_query($sql);
		do{
			if($result = $mysqli->store_result()){
				$rows = $result->fetch_all(MYSQLI_ASSOC);
				dump($rows);
			}
		}while($mysqli->more_results() && $mysqli->next_result());
	}
}
