<?php 
/**
regAct.php

接收用户注册的表单信息，完成注册

****/

//print_r($_POST);


define('ACC', true);
require('./include/init.php');

//实例化一个model

$user = new UserModel();

/*
	自动检验
	用户名 4-16 个字符之内
	email 能检测出来
	passwd 不能为空
*/
if (!$user->_validate($_POST)) { //自动检验
	$msg = implode('<br />', $user->getErr());
 	include(ROOT.'view/front/msg.html');
 	exit;
}

//检验用户名是否存在
if ($user->checkUser($_POST['username'])) {
 	$msg = "用户名已经存在";
 	include(ROOT.'view/front/msg.html');
 	exit;
 } 


$data = $user->_autofill($_POST); //自动填充

$data = $user->_facade($data); //自动过滤

if ($user->reg($data)) {
	$msg = "用户注册成功";
}else{
	$msg = "用户注册失败";
}

//引入view
include(ROOT.'./view/front/msg.html');


?>