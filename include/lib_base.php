<?php 
/**

系统最基本的函数
****/
defined('ACC')||exit('ACC Denied');

//递归转译数组
function _addslashes($arr){ //此函数没有使用引用传参
	foreach ($arr as $k => $v) {
		if (is_string($v)) {
		 	$arr[$k] = addslashes($v);
		 } else if (is_array($v)) {  //判断，如果是数组再使用递归转
		 	$arr[$k] = _addslashes($v);
		 }
	}
	return $arr;
	
}








?>