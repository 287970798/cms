<?php
//Apply模型类
class ApplyModel extends Model{
    public function __construct(){
    }

    public function getOne(){
	return array(
	    'name'=>'张三',
	    'age'=>'19',
	    'phone'=>'15966326431'
	);
    }

}
