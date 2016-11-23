<?php 
/**
control MVC中的控制器
	用户直接登录的页面，
****/
define('ACC', true);
require('../include/init.php');

$cat = new CatModel();
$catadd =  $cat->select();

$catadd = $cat->getCatTree($catadd, 0, 0);

include(ROOT.'view/admin/templates/cateadd.html');


?>