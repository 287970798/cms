<?php

//GD 图像库
imagecreatetruecolor($w,$h)
imagecolorallocate($image,$r,$g,$b)
imagecolorallocatealpha($image,$r,$g,$b,$alpha)
imagettftext($image,$size,$angle,$x,$y,$color,$fontFile,$text)
imagettfbbox()
imagestring()
imagefontwidth()
imagefontheight()
header('content-type:image/jpeg')
imagejpeg imagegif imagepng

imagecreatefromjpeg imagecreatefromgif imagecreatefrompng
imagecopyresampled()
imagecopyresized()
imagecopymerge()

getimagesize()

//产生随机颜色
function getRandColor($image){
	return imagecolorallocate($image,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
}
//产生随机字符
function generatorStr($type,$length){
	switch($type){
		case 1:
			$text=join('',array_rand(range(0,9),$length));
			break;
		case 2:
			$text=join('',array_rand(array_flip(array_merge(range(a,z),range(A,Z))),4));
			break;
		case 3:
			$text=join('',array_rand(array_flip(array_merge(range(0,9),range(a,z),range(A,Z))),4));
		case 4:
			$str='你,好,中,国,大,美,祖,国,山,河';
			$text=join('',array_rand(array_flip(explode(',',$str)),4));
		default:
			exit('类型出错');
	}
}
//产生唯一文件名
function getUniName(){
	return md5(uniqid(microtime(true),true));
}
//获取扩展名
function getExt($filename){
	//return strtolower(pathinfo($filename,PATHINFO_EXTENSION));
	//return array_reverse(explode('.',basename($filename)))[0];
	//return end(explode('.',basename($filename)));
}
//用到的数组函数
array_flip()
array_rand()
array_reverse()
pathinfo($path,PATHINFO_EXTENSION)
basename($filename)
file_exists()
is_dir()
is_readable()
end()
current()
prev()
next()
each()
reset()


//上传用到的函数
is_uploaded_file()
move_uploaded_file()


microtime(true)
uniqid(microtime(true),true)
