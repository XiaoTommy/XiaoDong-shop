<?php 
/****

****/


define('ACC', true);
require('./include/init.php');

$goods_id = isset($_GET['goods_id'])?$_GET['goods_id']+0:0;


//线查询商品信息
$goods = new GoodsModel();
$g = $goods->find($goods_id);

if (empty($g)) {
	header('location: index.php');
	exit;
}

$cat = new catModel();
$nav = $cat->getTree($g['cat_id']);





include(ROOT.'view/front/shangpin.html');


?>