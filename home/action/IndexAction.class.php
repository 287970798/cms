<?php
class IndexAction extends Action{
    public function index(){
		define('A','hello');	
		echo A;
    }

    public function getMessage(){
	echo 'this is your message!';
	$id = isset($_GET['id'])?'id='.$_GET['id']:'';
	$this->assign('id',$id);
	$this->display('message.tpl');
    }

}
