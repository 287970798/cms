<?php
/*构建文件信息*/
function getFiles(){
	$i = 0;
	foreach($_FILES as $file){
		if(is_string($file['name'])){
			$files[$i++] = $file;	
		}elseif(is_array($file['name'])){
			foreach($file['name'] as $key=>$value){
				$files[$i]['name'] = $file['name'][$key];
				$files[$i]['type'] = $file['type'][$key];
				$files[$i]['tmp_name'] = $file['tmp_name'][$key];
				$files[$i]['error'] = $file['error'][$key];
				$files[$i]['size'] = $file['size'][$key];
				$i++;
			}
		}	
	}
	return $files;
}
/*上传文件*/
function uploads($fileInfo,$uploadPath='uploads',$flag=true,$maxSize=1048576,$allowExt=array('jpeg','jpg','gif','png')){
	//判断错误号
	if($fileInfo['error'] == UPLOAD_ERR_OK){
		//检测上传文件的大小	
		if($fileInfo['size']>$maxSize){
			$res['mes'] = $fileInfo['name'].'上传文件过大！';
		}
		//检测文件类型
		$ext = getExt($fileInfo['name']);
		if(!in_array($ext,$allowExt)){
			$res['mes'] = $fileInfo['name'].'非法文件类型！';
		}
		//检测是否是真正的图片类型
		if($flag){
			if(!@getimagesize($fileInfo['tmp_name'])){
				$res['mes'] = $fileInfo['name'].'不是真实图片类型！';
			}
		}
		//检测文件是否通过 HTTP POST方式上传的
		if(!is_uploaded_file($fileInfo['tmp_name'])){
			$res['mes'] = $fileInfo['name'].'不是通过 HTTP POST 方式上传的图片！';
		}
		//有错返回，无错移动
		if(isset($res)) return $res;
		//移动文件
		if(!file_exists($uploadPath)){
			mkdir($uploadPath,0777,true);
			chmod($uploadPath,0777);
		}
		$uniName = getUniName();
		$destination = $uploadPath.'/'.$uniName.'.'.$ext;
		if(!move_uploaded_file($fileInfo['tmp_name'],$destination)){
			$res['mes'] = $fileInfo['name'].'文件移动失败！';
		}else{
			$res['mes'] = $fileInfo['name'].'上传成功！';
			$res['dest'] = $destination;
		}
	}else{
		switch($fileInfo['error']){
			case 1:
				$res['mes'] = '文件超过php.ini中upload_max_filesize'.'选项限制的值';
				break;
			case 2:
				$res['mes'] = '文件超过表单MAX_FILE_SIZE限制的大小';
				break;
			case 3:
				$res['mes'] = '文件被部分上传！';
				break;
			case 4:
				$res['mes'] = '没有文件被上传！';
				break;
			case 6:
				$res['mes'] = '找不到临时文件夹！';
				break;
			case 7:
				$res['mes'] = '文件写入失败！';
				break;
			case 8:
				$res['mes'] = '系统错误！';
				break;
		}
	}
	return $res;
}
