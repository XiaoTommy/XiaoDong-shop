<?php 
/**
用户登录页面
**/

define('ACC', true);
require('./include/init.php');



if (isset($_POST['act'])) {
	//说明用户通过点击登录按钮
	//收取用户名、密码之类的
	$u = $_POST['username'];
	$p = $_POST['password'];

	//检测合法性

	$user = new UserModel();
	//核对用户名密码
	//加载model
	$row = $user->checkUser($u,$p);	

	if (empty($row)) {//检测数据库中是否存在
	 	$msg = "用户名密码不匹配";
	 } else{
	 	$msg = "登录成功";
	 	session_start();
	 	$_SESSION = $row;

        if(isset($_POST['remember'])){
            setcookie('remuser', $u, time()+14*24*3600);
        }else{
            setcookie('remuser', '', 0);
        }

	 }

	include(ROOT.'view/front/msg.html');
	exit;

}else{
	 $remuser = isset($_COOKIE['remuser'])?$_COOKIE['remuser']:'';
	 
	//用户准备登录
	include(ROOT.'view/front/denglu.html');
}



?>