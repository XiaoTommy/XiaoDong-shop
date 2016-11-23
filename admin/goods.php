<?php 
/****
布尔教育 高端PHP培训
培  训: http://www.itbool.com
论  坛: http://www.zixue.it
****/

define('ACC', true);
require('../include/init.php');

/*
	接受goods_id
	实例化goodsModel
	调用find方法
	展示商品信息
*/

$goods_id = $_GET['goods_id'] + 0;

$goods = new GoodsModel();
$g = $goods->find($goods_id);

if (empty($g)) {
	echo "商品不存在";
}
print_r($g)














?>