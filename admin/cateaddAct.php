<?php 
/**
file cateaddAct.php
作用：接受cateadd.php表单页面的数据
并且调用model，把数据入库

****/

define('ACC', true);
require('../include/init.php');
//1.接收数据
//print_r($_POST);

//2.检验数据
$data = array();
if (empty($_POST['cat_name'])) {
 	exit(栏目名称不能为空);
 } 
$data['cat_name'] = $_POST['cat_name'];

//同理判断intro和父栏目是否合法，我这里没有写


$data['parent_id'] = $_POST['parent_id'];
$data['intro'] = $_POST['intro'];

//print_r($data); exit;


//3.实例化model 并且调用model的相关方法
$cat = new CatModel();
if ($cat->add($data)) {
	echo "栏目添加成功"; //本身control是不允许显示的
}else{
	echo "栏目添加失败";
}

?>