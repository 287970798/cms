<?php
//dump
function dump($arr){
	echo '<pre>';
	print_r($arr);
	echo '</pre>';
}
/*得到文件的扩展名*/
function getExt($filename){
	return strtolower(pathinfo($filename,PATHINFO_EXTENSION));
}
/*获得唯一字符串*/
function getUniName(){
	return md5(uniqid(microtime(true),true));
}
