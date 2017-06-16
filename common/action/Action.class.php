<?php
class Action {
    protected $tpl;
    protected $model;

    public function __construct($model=null){
		//实例化模板类
     	$this->tpl = new Templates;
     	//载入模型
     	$this->model = $model;
    }

    public function display($filename){
	//载入模板
     	$this->tpl->display($filename);
    } 

    public function assign($var,$value){
	//分配变量
     	$this->tpl->assign($var,$value); 
    }
}
