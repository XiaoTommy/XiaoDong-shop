<?php 
/****
布尔教育 高端PHP培训
培  训: http://www.itbool.com
论  坛: http://www.zixue.it
****/

define('ACC', true);
require('./include/init.php');



//取出5条新品
$goods = new GoodsModel();
$newlist = $goods->getNew(5);

// print_r($newlist);
// exit;

/*
	取出指定栏目下的商品
*/
// $cat_id = $_GET['cat_id'];
// $sql = select .. from goods where cat_id = $cat_id;
// 这样的写法是错误的，因为cat_id 对应的栏目可能是个大栏目，但是大栏目下是没有对应商品的，
// 商品放在大栏目下的小栏目下
// 正确是找到$cat_id 的所有子孙栏目，
// 然后再找子孙栏目下的商品

//女士大栏目下的5件圣品
$femail_id = 4;
$felist = $goods->catGoods($femail_id);

//男式大栏目下的5件商品，自行完成

include(ROOT.'view/front/index.html');


?>