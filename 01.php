<?php 
/**

****/
define('ACC', true);

require('include/init.php');

require(ROOT.'tool/UpTool.class.php');

$uptool = new UpTool();
$uptool->setExt('rar');
//$uptool->setSize(0.5);

if ($res = $uptool->up('resume')) {
	echo "OK";
	echo $res;
}else{
	echo $uptool->getErr();
	echo "Fail";
}
?>