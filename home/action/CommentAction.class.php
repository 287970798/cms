<?php
class CommentAction extends Action{
	private $data = array();
	public function __construct($data){
		$this->data = $data;
	}
	/*检测用户输入的数据*/
	public static function Validate(&$arr){
		if(!($data['email']=filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL))){
			$errors['email'] = '请输入合法邮箱';
		}
		if(!($data['url']=filter(INPUT_POST,'url',FILTER_VALIDATE_URL))){
			$url = '';
		}
		if(!($data['content']=filter(INPUT_POST,'content',FILTER_CALLBACK,array('options'=>'CommentAction::validate_str')))){
			$errors['content'] = '请输入合法内容';
		}
		if(!($data['username']=filter(INPUT_POST,'username',FILTER_CALLBACK,array('options'=>'CommentAction::validate_str')))){
			$errors['username'] = '请输入合法用户名';
		}
		$options = array(
			'options'=>array(
				'min_range'=>1,
				'max_range'=>5
			)
		);
		if(!($data['face']=filter(INPUT_POST,'face',FILTER_VALIDATE_INT,$options))){
			$errors['face'] = '请选择合法头像';
		}
		if(!empty($errors)){
			$arr = $errors;
			return false;
		}
		$arr = $data;
		$arr['email'] = strtolower(trim($arr['email']));
		return true;
	}
	public static function validate_str($str){
		if(mb_strlen($str,'UTF8') < 1){
			return false;
		}
		$str = nl2br(htmlspecialchars($str,ENT_QUOTES));
		return $str;
	}
}
