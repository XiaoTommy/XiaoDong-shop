<?php 
/**
control MVC中的控制器
	用户直接登录的页面，
****/
define('ACC', true);
require('../include/init.php');

//调用model
$cat = new CatModel();
$catlist = $cat->select(); //传给catlist， 然后用到html中 ,只要和html中的参数一致就OK了
//print_r($catlist);

$catlist = $cat->getCatTree($catlist,0,0);
//print_r($catlist);

include(ROOT.'view/admin/templates/catelist.html');


?>