<?php 
/****
布尔教育 高端PHP培训
培  训: http://www.itbool.com
论  坛: http://www.zixue.it
****/

define('ACC', true);
require('./include/init.php');




session_destroy();

$msg = "退出成功";

include(ROOT.'view/front/msg.html');

?>