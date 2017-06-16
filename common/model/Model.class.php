<?php
//模型基类
class Model{

	//查找多个记录
	protected function all($sql){
		$mysqli = DB::getDB();
		$result = $mysqli->query($sql);
		$rows = array();
		while($row = $result->fetch_object()){
			$rows[] = $row;
		}
		DB::unDB($mysqli,$result);
		return Tool::htmlString($rows);
	}
	//查找单条记录
	protected function one($sql){}
    
}
