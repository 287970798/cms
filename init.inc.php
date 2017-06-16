<?php
/**
**设置时区，编码，导入全局配置信息，导入公共函数
**路由解析
**自动加载类
**/
//开启session
if(!isset($_SESSION)) session_start();

//设置编码
header("content-type:text/html;charset=utf-8");

//设置时区
date_default_timezone_set('Asia/Shanghai');

//项目目录
define('ROOT_PATH',dirname(__FILE__));

//加载配置文件
$config = include ROOT_PATH.'/common/conf/config.php';
foreach($config as $k=>$v){
	define($k,$v);
}

//加载公共函数库
if(file_exists(ROOT_PATH.'/common/func/function.php')){
	include ROOT_PATH.'/common/func/function.php';
}

//路由1(普通模式：host/index.php?m=home&c=Index&a=index&id=1)
$m = isset($_GET['m'])?$_GET['m']:'home';
if(!is_dir(ROOT_PATH.'/'.$m)) exit('module '.$m.' 不存在！');   //要检测目录是否存在
$c = isset($_GET['c'])?ucfirst($_GET['c']):'Index';
$a = isset($_GET['a'])?$_GET['a']:'index';
define('M',$m); //定义当前module为常量，以便在自动加载类函数中调用
define('C',$c); //定义当前控制器为常量，以便读取模板
$c .= 'Action';
$action = new $c;
$action->$a();

//路由2(pathInfo模式: host/index.php/m/c/a/id/3/pid/2)
//$pathInfoArr = explode('/',$_SERVER['PATH_INFO']);
//$m = $pathInfoArr[1];
//$c = $pathInfoArr[2];
//$a = $pathInfoArr[3];
//$q = array_slice($pathInfoArr,4); //截取参数
//print_r($q);

//自动加载类
function __autoload($className){
    $fileName = ucfirst($className).'.class.php';
    if(substr($className,-6) == 'Action'){
	//加载控制器类
	$filePath=ROOT_PATH.'/'.M.'/action/'.$fileName;
	if(!file_exists($filePath)) $filePath = ROOT_PATH.'/common/action/'.$fileName;
    }else if(substr($className, -5) == 'Model'){
	//加载模型类
	$filePath=ROOT_PATH.'/'.M.'/model/'.$fileName;
	if(!file_exists($filePath)) $filePath = ROOT_PATH.'/common/model/'.$fileName;
    }else{
	//加载其它类
	$filePath=ROOT_PATH.'/libs/'.$fileName;
    }
    //if(file_exists($filePath)) require_once $filePath;
    require_once $filePath;
}
