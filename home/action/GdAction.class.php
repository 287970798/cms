<?php
class GdAction extends Action {
	public function index(){
		//phpinfo();
		//var_dump(extension_loaded('gd'));
		//var_dump(function_exists('gd_info'));
		//dump(get_defined_functions());
		$this->display('index.html');
	}
	public function test(){
		//1.创建一个画布
		$width = 500;
		$height = 400;
		$image = imagecreatetruecolor($width,$height);
		//2.分配颜色
		$red = imagecolorallocate($image,255,0,0);
		$blue = imagecolorallocate($image,0,0,255);
		$white = imagecolorallocate($image,255,255,255);
		//3.横着写一个字符
		imagechar($image,5,100,200,'k',$red);
		//垂直写入一个字符
		imagecharup($image,5,150,200,'i',$blue);
		//水平写入一个字符串
		imagestring($image,5,200,200,'uniteedu',$white);
		//4.告诉浏览器以图片的形式显示
		header('content-type:image/jpeg');
		//5.输出图像
		imagejpeg($image);
		//6.俏毁资源
		imagedestroy($image);
	}
	public function test2(){
		//创建画布
		$image = imagecreatetruecolor(500,500);
		//分配颜色
		$white = imagecolorallocate($image,255,255,255);
		$randColor = imagecolorallocate($image,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
		//填充矩形
		imagefilledrectangle($image,0,0,500,500,$white);
		//绘画
		imagettftext($image,20,0,100,100,$randColor,ROOT_PATH.'/public/fonts/cour.ttf','uniteedu');
		imagettftext($image,40,30,200,200,$randColor,ROOT_PATH.'/public/fonts/cour.ttf','uniteedu');
		//浏览器以图像的形式输出
		header('content-type:image/png');
		//输出
		imagepng($image);
		imagepng($image,ROOT_PATH.'/images/1.png');
		//销毁
		imagedestroy($image);
		
	}
	public function test3(){
		//创建画布
		$width = 200;
		$height = 100;
		$image = imagecreatetruecolor($width,$height);
		//分配颜色
		$white = imagecolorallocate($image,255,255,255);
		$randColor = imagecolorallocate($image,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
		//矩形填充
		imagefilledrectangle($image,0,0,$width,$height,$white);
		//绘制
		$size = mt_rand(20,28);
		$angle = mt_rand(-15,15);
		$x = 30;
		$y = 50;
		$font = ROOT_PATH.'/public/fonts/cour.ttf';
		$text = mt_rand(1000,9999);
		imagettftext($image,$size,$angle,$x,$y,$randColor,$font,$text);
		//浏览器
		header('content-type:image/png');
		//输出
		imagepng($image);
		//销毁
		imagedestroy($image);
	}
	public function verifyCode(){
		$width = 200;
		$height = 40;
		$fontWidth = imagefontwidth(28);
		$fontHeight = imagefontheight(28);
		$image = imagecreatetruecolor($width,$height);
		$white = imagecolorallocate($image,255,255,255);
		function getRandColor($image){
			return $randColor = imagecolorallocate($image,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
		}
		imagefilledrectangle($image,0,0,$width,$height,$white);
		//随机字符
		$text = join('',array_merge(range(0,9),range('a','z'),range('A','Z')));
		$length = 4;
		for($i=0;$i<$length;$i++){
			$randColor = getRandColor($image);
			$size = mt_rand(20,28);
			$angle = mt_rand(-15,15);
			$x = ($width/$length)*$i + ($width/$length-$fontWidth)/2;
			$y = $fontHeight + ($height-$fontHeight)/2;
			$font = ROOT_PATH.'/public/fonts/cour.ttf';
			$text = str_shuffle($text);
			imagettftext($image,$size,$angle,$x,$y,$randColor,$font,$text{1});
		}
		//干扰像素
		for($i=1;$i<=50;$i++){
			imagesetpixel($image,mt_rand(0,$width),mt_rand(0,$height),getRandColor($image));
		}
		//干扰线段
		for($i=1;$i<=3;$i++){
			imageline($image,mt_rand(0,$width),mt_rand(0,$height),mt_rand(0,$width),mt_rand(0,$height),getRandColor($image));
		}
		//干扰圆弧
		for($i=1;$i<=3;$i++){
			imagearc($image,mt_rand(0,$width),mt_rand(0,$height),mt_rand(0,$width/2),mt_rand(0,$height/2),mt_rand(0,360),mt_rand(0,360),getRandColor($image));
		}
		header('content-type:image/png');
		imagepng($image);
		imagedestroy($image);
	}
	public function test4(){
		include ROOT_PATH.'/libs/verify.func.php';
		getVerify(3,4);
	}
	public function test5(){
		include ROOT_PATH.'/libs/Captcha.class.php';
		$config=array(
			'fontFile'=>ROOT_PATH.'/public/fonts/cour.ttf',
			'snow'=>1,
			'pixel'=>0,
			'line'=>0,
			'arc'=>2
		);
		$code = new Captcha($config);
		$code = $code->getCaptcha();
		$_SESSION['code']=$code;
	}
	public function session(){
		dump($_SESSION);
	}
	public function note(){
		echo <<<EOF
		imagecreatetruecolor();<br>
		imagecolorallocate();<br>
		imagettftext();<br>
		imagesetpixel();<br>
		imageline();<br>
		imagearc();<br>
		header('content-type:image/png');<br>
		imagepng(\$image);
		imagefontwidth();
		imagefontheight()
EOF;
	}
}
