<?php
//解析类
class Parser {
    private $tpl;

    public function __construct($tplFile){
	//读入模板文件
	if(!$this->tpl = file_get_contents($tplFile)){
	    exit('模板文件'.$tplFile.'读取失败');
	}
    }

    //解析普通变量
    //{$a} $var['a']
    public function parVar(){
	$pattern = '/\{\$(\w+)([\w\-\>\+\[\]\'\"]*)\}/';
	if(preg_match($pattern, $this->tpl)){
	    $this->tpl=preg_replace($pattern,'<?php echo $this->var["\1"]\2?>',$this->tpl);
	}
    }

    //解析系统变量
    public function parConfig(){
	$pattern = '/<!--\{(\w+)\}-->/';
	if(preg_match($pattern,$this->tpl)){
	    $this->tpl = preg_replace($pattern,'<?php echo $this->config["\1"]?>',$this->tpl);
	}
    }

    //解析if语句
    public function parIf(){
	$patternIf = '/\{if\s+\$(\w+)\}/'; 
	$patternElse = '/\{else\}/';
	$patternEndIf = '/\{\/if\}/';
	if(preg_match($patternIf,$this->tpl)){
	    //if
	    $this->tpl = preg_replace($patternIf,'<?php if($this->var["\1"]){?>',$this->tpl);
	    //else
	    if(preg_match($patternElse,$this->tpl)){
		$this->tpl = preg_replace($patternElse,'<?php }else{?>',$this->tpl);
	    }
	    //endif
	    if(preg_match($patternEndIf,$this->tpl)){
		$this->tpl = preg_replace($patternEndIf,'<?php }?>',$this->tpl);	
	    }else{
		exit('ERROR:if语句没有闭合');
	    }
	    
	}
	
    }
    
    //解析foreach
    public function parForeach(){
	$patternForeach = '/\{foreach\s+\$(\w+)\((\w+),(\w+)\)\}/';
	$patternEndForeach = '/\{\/foreach\}/';
	$patternVar = '/\{@(\w+)([\w\-\>\[\]\'\"\+]*)\}/';
	if(preg_match($patternForeach,$this->tpl)){
	    $this->tpl = preg_replace($patternForeach,'<?php foreach($this->var["\1"] as $\2=>$\3){?>',$this->tpl);
	    if(preg_match($patternVar,$this->tpl)){
		$this->tpl = preg_replace($patternVar,'<?php echo $\1\2?>',$this->tpl);
	    }
	    if(preg_match($patternEndForeach,$this->tpl)){
		$this->tpl = preg_replace($patternEndForeach,'<?php }?>',$this->tpl);
	    }else{
		exit('ERROR:foreach语句没有闭合。');
	    }
	}

    }

    //解析include
    public function parInclude(){
	$pattern = '/\{include\s+file=(\'|\")([\w\.\-\/]+)\1\}/';
	if(preg_match_all($pattern,$this->tpl,$file)){
	   foreach($file[2] as $v){
		if(!file_exists(TPL_DIR.'/'.$v)){
		    exit('ERROR:包含文件出错！');
		}
	   } 
	   $this->tpl = preg_replace($pattern,'<?php $this->create("\2") ?>',$this->tpl);
	}
    }

    //编译
    public function compile($parFile){
	$this->parVar();
	$this->parConfig();
	$this->parIf();
	$this->parForeach();
	$this->parInclude();
	
	//生成编译文件
	if(!file_put_contents($parFile,$this->tpl)){
	    exit('ERROR:编译文件出错！');
	}
    }

}
