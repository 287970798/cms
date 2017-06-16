<?php
class Gd2Action extends Action {
	public function index(){
		echo 'Gd2 - index method.';
	}
	public function test(){
		$filename = ROOT_PATH.'/public/images/ipad.png';
		//得到图片信息
		$fileInfo = getimagesize($filename);
		list($src_w,$src_h) = $fileInfo;
		//创建100*100
		$dst_w = 100;
		$dst_h = 100;
		//创建目标画布
		$dst_image = imagecreatetruecolor($dst_w,$dst_h);
		//通过图片文件创建画面资源 imagecreatefromjpeg|gif|png
		$src_image = imagecreatefrompng($filename);
		imagecopyresampled($dst_image,$src_image,0,0,0,0,$dst_w,$dst_h,$src_w,$src_h);
		imagepng($dst_image,ROOT_PATH.'/public/images/thumb_100x100.png');
		imagedestroy($dst_image);
		/*
			getimagesize()
			imagecopyresampled()
			imagecreatefromjpeg|png|gif() 
		*/
	}
	//生成两张缩图
	public function test2(){
		$filename = ROOT_PATH.'/public/images/ipad.png';
		$fileInfo = getimagesize($filename);
		if($fileInfo){
			list($src_w,$src_h) = $fileInfo;
		}

		$src_image = imagecreatefrompng($filename);
		$dst_image_50 = imagecreatetruecolor(50,50);
		$dst_image_270 = imagecreatetruecolor(270,270);
		
		imagecopyresampled($dst_image_50,$src_image,0,0,0,0,50,50,$src_w,$src_h);
		imagecopyresampled($dst_image_270,$src_image,0,0,0,0,270,270,$src_w,$src_h);

		imagepng($dst_image_50,ROOT_PATH.'/public/images/thumb_50x50.png');
		imagepng($dst_image_270,ROOT_PATH.'/public/images/thumb_270x270.png');

		imagedestroy($dst_image_50);
		imagedestroy($dst_image_270);
		imagedestroy($src_image);
	}
	//等比例缩放
	public function test3(){
		$filename = IMAGES.'/ad.jpeg';
		$fileInfo = getimagesize($filename);
		if($fileInfo){
			list($src_w,$src_h)=$fileInfo;
		}else{
			exit('不是图片');
		}

		//设置最大宽高
		$dst_w = 300;
		$dst_h = 600;

		//目标宽高算法
		$src_ratio = $src_w / $src_h;
		if($dst_w/$dst_h>$src_ratio){
			$dst_w = $dst_h*$src_ratio;
		}else{
			$dst_h = $dst_w/$src_ratio;
		}

		$src_image = imagecreatefromjpeg($filename);
		$dst_image = imagecreatetruecolor($dst_w,$dst_h);
		imagecopyresampled($dst_image,$src_image,0,0,0,0,$dst_w,$dst_h,$src_w,$src_h);

		imagejpeg($dst_image,IMAGES.'/thumb_'.$dst_w.'x'.$dst_h.'.jpg');

		imagedestroy($dst_image);
		imagedestroy($src_image);
	}
	public function test4(){
		$filename = IMAGES.'/banner.jpg';
		$fileInfo = getimagesize($filename);
		if($fileInfo){
			list($src_w,$src_h)=$fileInfo;
			$mime = $fileInfo['mime'];
		}else{
			exit('不是图片');
		}
		
		$createFun = str_replace('/','createfrom',$mime);
		$outFun = str_replace('/',null,$mime);

		//设置最大宽高
		$dst_w = 300;
		$dst_h = 600;

		//目标宽高算法
		$src_ratio = $src_w / $src_h;
		if($dst_w/$dst_h>$src_ratio){
			$dst_w = $dst_h*$src_ratio;
		}else{
			$dst_h = $dst_w/$src_ratio;
		}

		$src_image = $createFun($filename);
		$dst_image = imagecreatetruecolor($dst_w,$dst_h);
		imagecopyresampled($dst_image,$src_image,0,0,0,0,$dst_w,$dst_h,$src_w,$src_h);

		$outFun($dst_image,IMAGES.'/thumb_'.$dst_w.'x'.$dst_h.'.jpg');

		imagedestroy($dst_image);
		imagedestroy($src_image);
	}
	public function test5(){
		include LIBS.'/image.func.php';
		$filename=IMAGES.'/banner2.jpg';
		$path=thumb($filename);
		echo $path;
		/*
			两个函数
			image_type_to_mime_type()
			image_type_to_extension()
		*/
	}
	//水印
	public function test6(){
		$filename=IMAGES.'/banner.jpg';	
		$fileInfo = getimagesize($filename);
		$mime=$fileInfo['mime'];
		$createFun=str_replace('/','createfrom',$mime);
		$outFun=str_replace('/',null,$mime);
		$image=$createFun($filename);
		$red=imagecolorallocatealpha($image,255,0,0,100);
		$fontFile=_PUBLIC_.'/fonts/simhei.ttf';
		imagettftext($image,30,0,0,30,$red,$fontFile,'联创优学uniteedu.cn');
		header('content-type:'.$mime);
		$outFun($image);
		imagedestroy($image);
		/*
			imagecolorallocatealpha(); 分配颜色+alpha
		*/
	}
	//水印 封装调用
	public function test7(){
		include LIBS.'/image.func.php';
		$filename=IMAGES.'/banner.jpg';
		$fontFile=_PUBLIC_.'/fonts/simhei.ttf';
		$text='联创优学uniteedu';
		$path=waterText($filename,$fontFile,$text);
		dump($path);
	}
	//图像水印
	public function test8(){
		$logo=IMAGES.'/lcyx.jpg';
		$filename=IMAGES.'/banner.jpg';
		$fileInfo=getimagesize($logo);
		$dst_image=imagecreatefromjpeg($filename);
		$src_image=imagecreatefromjpeg($logo);
		$dst_x=0;
		$dst_y=0;
		$src_x=0;
		$src_y=0;
		$src_w=$fileInfo[0];
		$src_h=$fileInfo[1];
		$pct=20;
		imagecopymerge($dst_image,$src_image,$dst_x,$dst_y,$src_x,$src_y,$src_w,$src_h,$pct);
		header('content-type:image/jpeg');
		imagejpeg($dst_image);
		imagedestroy($dst_image);
		imagedestroy($src_image);
	}
	//图片水印 函数封装调用
	public function test9(){
		include LIBS.'/image.func.php';
		$srcName=IMAGES.'/banner.jpg';
		$dstName=IMAGES.'/lcyx.jpg';
		$path=waterPic($dstName,$srcName,4);
		echo $path;
	}
	//缩略图 封装类调用
	public function test10(){
		$filename=IMAGES."/banner.jpg";
		$image=new Image;
		$path=$image->thumb($filename);
		echo $path;
	}
	//文字水印 封闭类调用
	public function test11(){
		$filename=IMAGES."/banner2.jpg";
		$fontFile=_PUBLIC_."/fonts/simhei.ttf";
		$image=new Image;
		$dst=$image->waterText($filename,$fontFile,'this is a text',4);
		if($dst){
			dump($dst);
		}else{
			dump($image->error);
		}
		
	}
	//图片水印 封装类调用
	public function test12(){
		$filename=IMAGES."/banner.jpg";
		$waterPic=IMAGES."/lcyx.jpg";
		$image=new Image;
		$dst=$image->waterPic($filename,$waterPic,8);
		dump($dst);
	}
}
