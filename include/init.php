<?php 
/**
file init.php
初始化文件：框架初始化 


****/

defined('ACC')||exit('ACC Denied');

//初始化当前的绝对路径
//我们之所以换成正斜线"/"，是因为win/linux都支持正斜线，但是linux不支持反斜线
//echo __FILE__; //D:\wamp\www\shangcheng\include\init.php
//echo substr(str_replace('\\', '/', __FILE__), 0, -8);  //D:/wamp/www/shangcheng/include/
//echo dirname(str_replace('\\', '/', __FILE__)).'/'; //同上，dirname是指返回去掉文件名后的路径
//echo dirname(__FILE__);  //D:\wamp\www\shangcheng\include
//echo dirname(__DIR__); //D:\wamp\www\shangcheng
define('ROOT', str_replace('\\', '/', dirname(dirname(__FILE__))) . '/') ; //把目录常量化，以后找目录，直接找ROOT就OK


//设置报错级别,开发状态比较多，运营状态最好不要
define('DEBUG', true);  //这个有一点小小的问题//DEBUG是调试模式，true表示开启了

//echo ROOT;  //D:/wamp/www/shangcheng/include/

// require(ROOT.'include/db.class.php');
// require(ROOT.'include/mysql.class.php');
// require(ROOT.'Model/Model.class.php');
// require(ROOT.'Model/TestModel.class.php');
// require(ROOT.'include/conf.class.php');
// require(ROOT.'include/log.class.php');

require(ROOT.'include/lib_base.php');

function __autoload($class) {
	if(strtolower(substr($class,-5)) == 'model') {
		//echo ROOT .'Model/'. $class . '.class.php';当问题出现的时候调试使用的
		require(ROOT .'Model/'. $class . '.class.php'); 
	}else if (strtolower(substr($class, -4)) == 'tool') {
		require(ROOT .'tool/'. $class . '.class.php');
	}else{
		require(ROOT .'include/'. $class . '.class.php');
	}
}



//过滤参数，使用递归方式过滤$_GET, $_POST, $_COOKIES
$_GET = _addslashes($_GET);
$_POST = _addslashes($_POST);
$_COOKIE = _addslashes($_COOKIE);


//开启session
session_start();




//设置报错级别
if (@define('DEBUG')) { //加@ 是我不想让这一行报提示，具体原因暂时没有找到
	error_reporting(E_ALL); //如果是调试模式，报错全开
}else{
	error_reporting(E_ALL);
}












?>