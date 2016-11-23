<?php 
/****
布尔教育 高端PHP培训
培  训: http://www.itbool.com
论  坛: http://www.zixue.it
****/

define('ACC', true);
require("../include/init.php");

/*
	接受goods_id
	调用trash方法
*/

if(isset($_GET['act']) && $_GET['act']=='show') {
	//打印所有的回收商品
	$goods = new GoodsModel();
	$goodslist = $goods->getTrash(); //这里的 $goodslist 中间少了一个 s 让我找了一个小时，FUCK

	include(ROOT . 'view/admin/templates/goodslist.html');

}else{
	$goods_id = $_GET['goods_id'] + 0;

	$goods = new GoodsModel();

	if ($goods->trash($goods_id)) {
		echo "移到回收站";
	}else{
		echo "移动失败";
	}
}







?>