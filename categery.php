<?php 
/****
布尔教育 高端PHP培训
培  训: http://www.itbool.com
论  坛: http://www.zixue.it
****/
define('ACC', true);
require('./include/init.php');



$cat_id = isset($_GET['cat_id'])?$_GET['cat_id']+0:0;
$page = isset($_GET['page'])?$_GET['page']+0:1;
if ($page < 1) {
	$page = 1;
}


$goodsModel = new GoodsModel();
$total = $goodsModel->catGoodsCount($cat_id);

//每一页取两条
$perpage = 2;

if ($page > ceil($total/$perpage)) {
	$page = 1;
}

$offset = ($page-1)*$perpage;


$pagetool = new PageTool($total, $page, $perpage);
$pagecode = $pagetool->show();


$cat = new CatModel();
$categery = $cat->find($cat_id);
//print_r($categery);exit;

if (empty($categery)) { //如果输入的$cat_id 有误，返回首页并退出
	header('location: index.php');
	exit;
}

//取出树状导航
$cats = $cat->select(); //获取所有的栏目
$sort = $cat->getCatTree($cats, 0, 1); //利用无限级分类把他们都分类好

//取出面包屑导航
$nav = $cat->getTree($cat_id);

//取出栏目下的商品
$goods = new GoodsModel();
$goodslist = $goods->catGoods($cat_id, $offset, $perpage);
//print_r($goodslist);exit;

include(ROOT.'./view/front/lanmu.html');













?>