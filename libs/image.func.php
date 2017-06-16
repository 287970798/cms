<?php
/*
	指定缩放比例
	最大宽度和高度，等比例缩放
	可以对缩略图文件添加前缀
	选择是否删除缩略图的源文件
*/

function getImageInfo($filename){
	if(!$info = getimagesize($filename)){
		exit('文件不是真实图片');
	}
	$fileInfo['width']=$info[0];
	$fileInfo['height']=$info[1];
	//$mime = image_type_to_mime_type($info[2]);
	$mime=$info['mime'];
	$fileInfo['createFun']=str_replace('/','createfrom',$mime);
	$fileInfo['outFun']=str_replace('/','',$mime);
	$fileInfo['ext']=strtolower(image_type_to_extension($info[2]));
	return $fileInfo;
}
/*
	生成缩略图函数
	$filename
	$dst_dir
	$pre	前缀
	$dst_w
	$dst_h
	$scale
	$delSource 是否删除源文件
	return 返回缩略图路径
*/
function thumb($filename,$dst_dir=IMAGES.'/thumb',$pre='thumb_',$dst_w=null,$dst_h=null,$scale=0.5,$delSource=false){
	$fileInfo=getImageInfo($filename);
	$src_w=$fileInfo['width'];
	$src_h=$fileInfo['height'];
	
	if(is_numeric($dst_w)&&is_numeric($dst_h)){
		$src_ratio=$src_w/$src_h;
		if($dst_w/$dst_h>$src_ratio){
			$dst_w = $dst_h*$src_ratio;
		}else{
			$dst_h = $dst_w/$src_ratio;
		}
	}else{
		//没有指定则按照指定的缩放比例处理
		$dst_w=$src_w*$scale;
		$dst_h=$src_h*$scale;
	}
	$dst_image=imagecreatetruecolor($dst_w,$dst_h);
	$src_image=$fileInfo['createFun']($filename);
	imagecopyresampled($dst_image,$src_image,0,0,0,0,$dst_w,$dst_h,$src_w,$src_h);
	//检测目标目录是否存在，不存在则创建
	if($dst_dir&&!file_exists($dst_dir)){
		mkdir($dst_dir,0777);
		chmod($dst_dir,0777);
	}
	$randNum=mt_rand(100000,999999);
	$dst_name="{$pre}{$randNum}".$fileInfo['ext'];
	$destination=$dst_dir?$dst_dir.'/'.$dst_name:$dst_name;
	$fileInfo['outFun']($dst_image,$destination);
	imagedestroy($dst_image);
	imagedestroy($src_image);
	if($delSource){
		unlink($filename);
	}
	return $destination;
}
/*
	生成文字水印
*/
function waterText($filename,$fontFile,$text='uniteedu',$delSource=false,$r=255,$g=0,$b=0,$alpha=80,$size=30,$angle=0,$x=0,$y=30){
	//$r=255;
	//$g=0;
	//$b=0;
	//$alpha=100;
	//$size=30;
	//$angle=0;
	//$x=0;
	//$y=30;
	//$fontFile=_PUBLIC_.'/fonts/simhei.ttf';
	//$text='联创优学uniteedu';
	$filename=IMAGES.'/banner.jpg';
	$fileInfo=getimageInfo($filename);
	$image=$fileInfo['createFun']($filename);
	$color=imagecolorallocatealpha($image,$r,$g,$b,$alpha);
	imagettftext($image,$size,$angle,$x,$y,$color,$fontFile,$text);
	$dstDir=IMAGES.'/water';
	if($dstDir&&!file_exists($dstDir)){
		mkdir($dstDir,0777,true);
	}
	$pre='water_';
	$randNum=mt_rand(100000,999999);
	$dstName="{$pre}{$randNum}".$fileInfo['ext'];
	$destination=$dstDir?$dstDir.'/'.$dstName:$destName;
	$fileInfo['outFun']($image,$destination);
	imagedestroy($image);
	if($delSource){
		unlink($filename);
	}
	return $destination;
}
/*
	图片水印
*/
function waterPic($dstName,$srcName,$pos=0,$dstDir=IMAGES.'/waterPic',$pre='waterPic_',$pct=50,$delSource=false){
	//$dstName=IMAGES.'/banner.jpg';
	//$srcName=IMAGES.'/lcyx.jpg';
	//$delSource=false;
	//$pre='waterPic_';
	//$dstDir=IMAGES.'/waterPic';
	$dstInfo=getImageInfo($dstName);
	$srcInfo=getImageInfo($srcName);
	//$pos=0;
	//$pct=50;
	$dstImage=$dstInfo['createFun']($dstName);
	$srcImage=$srcInfo['createFun']($srcName);
	$srcW=$srcInfo['width'];
	$srcH=$srcInfo['height'];
	$dstW=$dstInfo['width'];
	$dstH=$dstInfo['height'];
	switch($pos){
		case 0:
			$x=0;
			$y=0;
			break;
		case 1:
			$x=($dstW-$srcW)/2;
			$y=0;
			break;
		case 2:
			$x=$dstW-$srcW;
			$y=0;
			break;
		case 3:
			$x=0;
			$y=($dstH-$srcH)/2;
			break;
		case 4:
			$x=($dstW-$srcW)/2;
			$y=($dstH-$srcH)/2;
			break;
		case 5:
			$x=$dstW-$srcW;
			$y=($dstH-$srcH)/2;
			break;
		case 6:
			$x=0;
			$y=$dstH-$srcH;
			break;
		case 7:
			$x=($dstW-$srcW)/2;
			$y=$dstH-$srcH;
			break;
		case 8:
			$x=$dstW-$srcW;
			$y=$dstH-$srcH;
			break;
		default:
			$x=0;
			$y=0;
	}
	imagecopymerge($dstImage,$srcImage,$x,$y,0,0,$srcW,$srcH,$pct);
	if($dstDir&&!file_exists($dstDir)){
		mkdir($dstDir,0777,true);
	}
	$randNum=mt_rand(100000,999999);
	$dstFileName="{$pre}{$randNum}".$dstInfo['ext'];
	$destination=$dstDir?$dstDir.'/'.$dstFileName:$dstFileName;
	$dstInfo['outFun']($dstImage,$destination);
	imagedestroy($dstImage);
	imagedestroy($srcImage);
	if($delSource){
		@unlink($dstName);
	}
	return $destination;
}
