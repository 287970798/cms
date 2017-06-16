<?php
function getVerify($type=1,$length=4,$codeName='verifyCode',$pixel=3,$line=3,$arc=3,$width=200,$height=40,$font=ROOT_PATH.'/public/fonts/simkai.ttf'){
	//创建画布
	$image = imagecreatetruecolor($width,$height);
	//分配颜色
	$white = imagecolorallocate($image,255,255,255);
	//矩形填充
	imagefilledrectangle($image,0,0,$width,$height,$white);
	//随机颜色
	function getRandColor($image){
		return imagecolorallocate($image,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
	}
	/*
	** 默认4位数字验证码
	** 1 数字
	** 2 字母
	** 3 数字+字母
	** 4 汉字
	*/
	switch($type){
		case 1:
			//数字
			$string = str_shuffle(join('',array_rand(range(0,9),$length)));
			break;
		case 2:
			//字母
			$string = str_shuffle(join('',array_rand(array_flip(array_merge(range('a','z'),range('A','Z'))),$length)));
			break;
		case 3:
			//数字+字母
			$string = join('',array_rand(array_flip(array_merge(range(0,9),range('a','z'),range('A','Z'))),$length));
			break;
		case 4:
			//汉字
			$str = '习,近,平,向,哈,萨,克,斯,坦,人,民,转,达,中,国,人,民,的,诚,挚,问,候,和,良,好,祝,愿,习,近,平,指,出,中,国,和,哈,萨,克,斯,坦,是,山,水,相,连,的,友,好,邻,邦,两,国,人,民,友,谊,源,远,流,长,历,久,弥,新';
			$string = join('',array_rand(array_flip(explode(',',$str)),$length));
			break;
		default:
			exit('非法参数');
	}
	//将验证码存入session
	if(!isset($_SESSION)) session_start();
	$_SESSION[$codeName] = $string;
	//绘制
	for($i=0;$i<$length;$i++){
		$size = mt_rand(20,28);
		$fontWidth = imagefontwidth(20);
		$fontHeight = imagefontheight(28);
		$angel = mt_rand(-15,15);
		$x = ($width/$length-$fontWidth)/2 + ($width/$length)*$i;
		$y = ($height-$fontHeight)/2 + $fontHeight;
		$text = mb_substr($string,$i,1,'utf-8');
		imagettftext($image,$size,$angel,$x,$y,getRandColor($image),$font,$text);	
	}
	//干扰像素
	for($i=1;$i<=$pixel;$i++){
		imagesetpixel($image,mt_rand(0,$width),mt_rand(0,$height),getRandColor($image));
	}
	//干扰线段
	for($i=1;$i<=$line;$i++){
		imageline($image,mt_rand(0,$width),mt_rand(0,$height),mt_rand(0,$width),mt_rand(0,$height),getRandColor($image));
	}
	//干扰圆弧
	for($i=1;$i<=$arc;$i++){
		imagearc($image,mt_rand(0,$width),mt_rand(0,$height),mt_rand(0,$width/2),mt_rand(0,$height/2),mt_rand(0,360),mt_rand(0,360),getRandColor($image));
	}

	header('content-type:image/png');
	imagepng($image);
	imagedestroy($image);
}
