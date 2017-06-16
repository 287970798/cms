<?php
class TeacherAction extends Action {
	
	public function __construct(){
		parent::__construct(new TeacherModel);
	}

	public function index(){
		echo 'this is TeacherAction\'s index function';
	}

	public function show(){
		if(isset($_GET['id'])){
			$this->model->id = $_GET['id'];
			echo $this->model->id;
		}
		$teachers = $this->model->getAll();
		$this->assign('teachers',$teachers);
		$this->display('show.tpl');
	}
}
