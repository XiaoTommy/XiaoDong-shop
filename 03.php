<?php 
/**

在商城中引入smarty

分析：
smarty是一个模板类
所以 
1.把smarty的lib放在bool/lib下
2.在init.php中，引入smarty.class.php
3.new 一个 smarty 对象
**/

define('ACC', true);
require('include/init.php');


require(ROOT.'lib/smarty3/libs/Smarty.class.php');

$smarty = new Smarty();

var_dump($smarty);


















