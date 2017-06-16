<?php
class Templates {
	private $tplDir;
	private $tplCDir;
	private $cacheDir;
    private $var = array();
    private $config = array();

    public function __construct(){
		$this->tplDir = ROOT_PATH.'/'.M.'/'.TPL_DIR;
		$this->tplCDir = ROOT_PATH.'/'.M.'/runtime/'.TPL_C_DIR;
		$this->cacheDir = ROOT_PATH.'/'.M.'/runtime/'.CACHE_DIR;

		//检测目录
		if(!is_dir($this->tplDir) || !is_dir($this->tplCDir) || !is_dir($this->cacheDir)){
		    exit("模板目录{$this->tplDir}/编译目录{$this->tplCDir}/缓存目录{$this->cacheDir}不存在！");
		}
		//导入系统变量(XML或数据库)
		$this->config['webname']='uniteedu offical site';
    }

    public function assign($var, $value){
		$this->var["$var"] = $value;
    }

    public function display($filename){

		//模板文件路徑
		$tplFile = $this->tplDir.'/'.C.'/'.$filename;
		if(!file_exists($tplFile)) exit("模板文件{$tplFile}不存在！");

		//编译文件路徑
		$parFile = $this->tplCDir.'/'.md5($filename).$filename.'.php';
		
		//编译文件
		if(!file_exists($parFile)||filemtime($parFile)<filemtime($tplFile)||DE_BUG){
		    $parser = new Parser($tplFile);
		    $parser->compile($parFile);
		    echo "<script>console.log('$tplFile compiled!');</script>";
		}

		//载入编译后的文件
		include $parFile; 
    }

    //create方法，用于header/footer这种模块模板的解析使用，不用生成缓存(include文件的解析)
    public function create($filename){
	
		//模板文件路徑
		$tplFile = $this->tplDir.'/'.C.'/'.$filename;
		if(!file_exists($tplFile)) exit('模板文件不存在！');

		//编译文件路徑
		$parFile = $this->tplCDir.'/'.md5($filename).$filename.'.php';
		
		//编译文件
		if(!file_exists($parFile)||filemtime($parFile)<filemtime($tplFile)||DE_BUG){
		    $parser = new Parser($tplFile);
		    $parser->compile($parFile);
		    echo "<script>console.log('$tplFile compiled!');</script>";
		}

		//载入编译后的文件
		include $parFile; 

    }

}
