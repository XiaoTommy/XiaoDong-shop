<?php 
/****
布尔教育 高端PHP培训
培  训: http://www.itbool.com
论  坛: http://www.zixue.it
****/


print_r($_POST);

$md5key = '#(%#WU)(UFGDKJGNDFG';
	
//计算出自己的md5info
$md5info = strtoupper(md5($_POST['v_oid'].$_POST['v_pstatus'].$_POST['v_amount'].$_POST['v_moneytype'].$md5key));

//自己计算出的md5info 和表单发过来的md5info 对比
if ($md5info !== $_POST['v_md5str']) {
	echo "出老千";
	exit;
}

echo "执行sql语句，把订单号".$_POST['v_oid'];
echo "已经支付";


?>