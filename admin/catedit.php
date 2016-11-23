<?php 
/**
catedit.php

****/

define('ACC', true);
require('../include/init.php');

//作用：编辑栏目

//1.接受cat_id 
//实例化model取出栏目信息，展示到表单

$cat_id = $_GET['cat_id'] + 0;

$cat = new CatModel();

$catinfo = $cat->find($cat_id); //取得的信息传给$catinfo

$catlist = $cat->select(); //这里我没有向catadd一样换名字，感觉catlist就挺不错的，就直接用
$catlist = $cat->getCatTree($catlist);

//print_r($catinfo);

include(ROOT.'view/admin/templates/catedit.html');

?>