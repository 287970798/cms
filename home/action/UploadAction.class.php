<?php
class UploadAction extends Action{
	public function index(){
		$this->display('index.html');
	}
	public function doupload(){
		dump($_FILES);
		$filename = $_FILES['myfile']['name'];
		$type = $_FILES['myfile']['type'];
		$tmp_name = $_FILES['myfile']['tmp_name'];
		$size = $_FILES['myfile']['size'];
		$error = $_FILES['myfile']['error'];
		
		//将服务器上的临时文件移动到指定的目录下
		//move_uploaded_file($tmp_name,$destination),成功返回true，失败返回false
		//move_uploaded_file($tmp_name,'uploads/'.$filename);
		//copy($src,$dest);	成功返回true,失败返回false
		copy($tmp_name, 'uploads/'.$filename);
		/*
			服务器端配置

			file_uploads = on , 支持HTTP上传
			upload_tmp_dir = ，临时文件保存的目录
			upload_max_filesize = 2M ，允许上传的最大文件数
			max_file_uploads = 20 , 允许一次上传最大文件数
			post_max_size = 8M , post方式发送数据的最大值

			max_execution_time = -1 ,设置了脚本被解析器终止之前允许的最大执行时间，单位为秒，防止程序写的不好占尽服务器资源。
			max_input_time = 60 , 脚本解析输入数据允许的最大时间，单位是秒
			max_input_nesting_level = 64 , 设置输入变量的嵌套深度

			max_input_vars = 100 ,接受多少输入的变量（限制分别应用于$_GET/$_POST/$_COOKIE超全局变量）指令的使用减轻了以哈希碰撞来进行拒绝服务攻击的可能性。如有超过指令指定数量的变量，将会导致E_WARNING的产生，更多的输入变量将会从请求中截断。
			memory_limit = 128M ,最大单线程的独立内存使用量。也就是一个web请求，给予线程最大的内存使用量的定义。


			错误信息说明

			UPLOAD_ERR_OK : 其值为0，没有错误发生，文件上传成功
			UPLOAD_ERR_INI_SIZE : 其值为1，上传的文件超过php.ini中upload_max_filesize选项限制的值。
			UPLOAD_ERR_FORM_SIZE : 其值为2，上传文件的大小超过了HTML表单中MAX_FILE_SIZE选项指定的值
			UPLOAD_ERR_PARTIAL : 其值为3，文件只有部分被上传
			UPLOAD_ERR_NO_FILE : 其值为4，没有文件被上传
			UPLOAD_ERR_NO_TMP_DIR : 其值为6，找不到临时文件夹
			UPLOAD_ERR_CANT_WRITE : 其值为7，文件写入失败
			UPLOAD_ERR_EXTENSION : 其值为8，上传的文件被PHP扩展程序中断


			客户端限制
			
			通过表单隐藏域限制上传文件的最大值
			<input type="hidden" name="MAX_FILE_SIZE" value="字节数">
			通过accept属性限制上传文件类型
			<input type="file" name="myfile" accept="文件MIME">

			服务器端限制

			限制上传文件大小
			限制上传文件类型
			检测是否为真实图片类型
			检测是否为HTTP POST方式上传
		*/
	}
	//改进上传1
	public function do(){
		dump($_FILES);
		$fileInfo = $_FILES['myfile'];
		$filename = $fileInfo['name'];
		$tmp_name = $fileInfo['tmp_name'];
		$size = $fileInfo['size'];
		$error = $fileInfo['error'];
		if($error == UPLOAD_ERR_OK){
			if(move_uploaded_file($tmp_name,'uploads/'.$filename)){
				echo '文件 '.$filename.' 上传成功！';
			}else{
				echo '文件 '.$filename.' 上传失败！';
			}	
		}else{
			switch($error){
				case 1:
					echo '文件超过php.ini中upload_max_filesize'.'选项限制的值';
					break;
				case 2:
					echo '文件超过表单MAX_FILE_SIZE限制的大小';
					break;
				case 3:
					echo '文件被部分上传！';
					break;
				case 4:
					echo '没有文件被上传！';
					break;
				case 6:
					echo '找不到临时文件夹！';
					break;
				case 7:
					echo '文件写入失败！';
					break;
				case 8:
					echo '系统错误！';
					break;
			}
		}
	}

	//改进上传2
	public function do2(){
		$fileInfo = $_FILES['myfile'];
		dump($fileInfo);
		$maxSize = 2097152; //1024*1024*2 = 2M 允许的最大值
		$allowExt = array('jpeg','jpg','gif','png','wbmp','txt');
		$flag = true; //检测是否为真实的图片类型
		//判断错误号
		if($fileInfo['error'] == UPLOAD_ERR_OK){
			//判断上传文件的大小	
			if($fileInfo['size'] > $maxSize){
				exit('上传文件过大！');
			}
			//上传文件类型
			//$ext = strtolower(end(explode('.',$fileInfo['filename'])));
			$ext = strtolower(pathinfo($fileInfo['name'],PATHINFO_EXTENSION)); //pathinfo()以数组或字符串的形式返回关于路径的信息
			if(!in_array($ext, $allowExt)){
				exit('非法文件类型！');
			}
			//判断文件是否是通过HTTP POST方式上传
			if(!is_uploaded_file($fileInfo['tmp_name'])){
				exit('文件不是通过HTTP POST方式上传的！');
			}
			//检测是否为真实的图片
			//getimagesize()真实的图片，返回图片信息。如果不是真实的图片信息，返回false。
			if($flag){
				if(!@getimagesize($fileInfo['tmp_name'])){
					exit('不是真正的图片类型！');
				}
			}
			//移动到目标目录
			$path = 'uploads';
			if(!file_exists($path)){
				mkdir($path,0777,true);
				chmod($path,0777);
			}
			//确保文件名唯一，防止重名产生覆盖
			$uniName = md5(uniqid(microtime(true),true)).'.'.$ext;
			$destination = $path.'/'.$uniName;
			if(move_uploaded_file($fileInfo['tmp_name'],$destination)){
				echo '上传成功！';
			}else{
				echo '上传失败！';
			}
		}else{
			switch($fileInfo['error']){
				case 1:
					echo '文件超过php.ini中upload_max_filesize'.'选项限制的值';
					break;
				case 2:
					echo '文件超过表单MAX_FILE_SIZE限制的大小';
					break;
				case 3:
					echo '文件被部分上传！';
					break;
				case 4:
					echo '没有文件被上传！';
					break;
				case 6:
					echo '找不到临时文件夹！';
					break;
				case 7:
					echo '文件写入失败！';
					break;
				case 8:
					echo '系统错误！';
					break;
			}
		}

	}

	//封装版 单文件上传
	public function do3(){
		include ROOT_PATH.'/libs/upload.func.php';
		$fileInfo = $_FILES['myfile'];
		$allowExt = array('txt','jpg');
		$newPath = upload($fileInfo,'imooc',false,$allowExt);
		echo $newPath;
	}
	//封装版 多个单文件上传
	public function do4(){
		if(!$_POST){
			$this->display('do4.html');
		}else{
			dump($_FILES);
		}
	}

	//封装版 多文件上传
	public function do5(){
		if(!$_POST){
			$this->display('do5.html');
		}else{
			dump($_FILES);
		}
	}

	//封装类版
	public function do6(){
		if(!$_POST){
			$this->display('do6.html');
		}else{
			$upload = new Upload();
			$dest = $upload->upload();
			echo $dest;
		}
	}

	//封装类文件版
	public function do7(){
		if(!$_POST){
			$this->display('do6.html');
		}else{
			$upload = new Upload('myFile1');
			$ret = $upload->upload();
			dump($ret);
		}
	}

	//下载
	public function download(){
		$filename = $_GET['filename'];
		header('content-disposition:attachment;filename='.basename($filename));
		header('content-length:'.filesize($filename));
		readfile($filename);
	}
}
