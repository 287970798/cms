<?php
class TeacherModel extends Model {
	private $id;
	private $name;
	private $phone;

	public function __get($key){
		return $this->$key;	
	}
	public function __set($key,$value){
		$this->$key = $value;	
	}


	public function getAll(){
		$sql = "SELECT * FROM teacher";
		return parent::all($sql);
	}

}
