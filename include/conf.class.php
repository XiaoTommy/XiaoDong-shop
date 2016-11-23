<?php 
/**
file conf.class.php
配置文件的读写类

做为配置文件config.inc.php的一个借口，并且为单例模式

你调用了配置文件config.inc.php
****/

defined('ACC')||exit('ACC Denied');
class conf{
	protected static $ins = null;
	protected $data = array(); //定义一个空数组，把相对路径带过来的数据传进来

	final protected function __construct(){
		//读取配置文件
		//先用相对路径
		include(ROOT.'/include/config.inc.php');
		$this->data = $_CFG;  //一次性把配置文件信息读过来，赋给data属性，以后就不管配置文件了，以后配置的值从data属性中找
	}

	final protected function __clone(){  //这里不明白为什么！！！

	}

	//判断$ins是否存在，典型的单例模式
	public static function getIns(){
		if (self::$ins instanceof self) {
			return self::$ins;
		}else{
			self::$ins = new self();
			return self::$ins;
		}
	}

	//利用魔术方法__get()，读取data内的信息
	public function __get($key){
		if (array_key_exists($key, $this->data)) {
			return $this->data[$key];
		}else{
			return null;
		}
	}

	//在运行期，动态增加或改变配置选项 ，使用魔术方法__set()
	public function __set($key, $value){
		$this->data[$key] = $value;
	}

}

$conf = conf::getIns(); //调用28行唯一的一个公共出口

/****
//var_dump($conf);  
print_r($conf);


//打印结果源代码，此刻已经能把配置文件的信息，读取到自身的data属性中存储起来
conf Object
(
    [data:protected] => Array
        (
            [host] => localhost
            [user] => root
            [pwd] => 123456
        )

)


//读取选项
echo $conf->host; //localhost
echo $conf->user.'<br />'; //root
//var_dump($conf->template_dir); //null说明现在还不能追加或者更改配置选项

//动态追加选项
$conf->template_dir = 'D:/wamp/www/smarty'; //动态追加配置选项，属于samrty中的template

echo $conf->template_dir;

****/



?>