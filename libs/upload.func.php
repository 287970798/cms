<?php
/*
	单文件上传函数

	$fileInfo = $_FILES['myfile'] 必选
	$uploadPath 上传目录
	$flag 是否验证图片真实性
	$allowExt = array() 允许上传文件的类型
	$maxSize 上传文件大小限制
*/
function upload($fileInfo,$uploadPath = 'uploads',$flag = false,$allowExt = array('jpeg','jpg','gif','png','txt'),$maxSize = 2097152){
	//检查错误号
	if($fileInfo['error']>0){
		switch($fileInfo['error']){
			case 1:
				$mes = '文件超过php.ini中upload_max_filesize'.'选项限制的值';
				break;
			case 2:
				$mes = '文件超过表单MAX_FILE_SIZE限制的大小';
				break;
			case 3:
				$mes = '文件被部分上传！';
				break;
			case 4:
				$mes = '没有文件被上传！';
				break;
			case 6:
				$mes = '找不到临时文件夹！';
				break;
			case 7:
				$mes = '文件写入失败！';
				break;
			case 8:
				$mes = '系统错误！';
				break;
		}
		exit($mes);
	}
	//检查文件大小
	if($fileInfo['size']>$maxSize){
		exit('文件过大！');
	}
	//检查allowExt是否为数组
	if(!is_array($allowExt)){
		exit('参数错误，$allowExt应为数组格式！');
	}
	//检查类型
	$ext = pathinfo($fileInfo['name'],PATHINFO_EXTENSION);
	if(!in_array($ext,$allowExt)){
		exit('文件类型不合法！');
	}
	//检测是否为真实图片
	if($flag){
		if(!@getimagesize($fileInfo['tmp_name'])){
			exit('不是真正的图片类型！');
		}
	}
	//检查是否为HTTP POST方式上传
	if(!is_uploaded_file($fileInfo['tmp_name'])){
		exit('不是通过HTTP POST方式上传！');
	}
	//检查上传目录是否存在
	if(!file_exists($uploadPath)){
		mkdir($uploadPath,0777,true);
		chmod($uploadPath,0777);
	}
	$uniName = md5(uniqid(microtime(true),true)).'.'.$ext;
	$destination = $uploadPath.'/'.$uniName;
	//移动文件
	if(!move_uploaded_file($fileInfo['tmp_name'], $destination)){
		exit('文件上传失败！');
	}

	return $destination;
	
}
