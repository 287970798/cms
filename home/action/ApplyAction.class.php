<?php
//apply控制器类
class ApplyAction extends Action{

    public function __construct(){
        parent::__construct(new ApplyModel);
    }

    public function showOne(){
	$one = $this->model->getOne();
	print_r($one);
    }
}
