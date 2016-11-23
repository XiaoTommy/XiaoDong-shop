<?php 
/****
布尔教育 高端PHP培训
培  训: http://www.itbool.com
论  坛: http://www.zixue.it
****/

define('ACC', true);
require('../include/init.php');

//具体和cateaddAct.php 基本一样

//接收数据
//判断合法性
$data = array();
if (empty($_POST['cat_name'])) {
	exit('栏目名字不能为空');
}
$data['cat_name'] = $_POST['cat_name'];
$data['parent_id'] = $_POST['parent_id'] + 0;
$data['intro'] = $_POST['intro'];

$cat_id = $_POST['cat_id'] + 0;

/*
一个栏目A，不能修改成为A的子孙栏目的子栏目
	如果B是A的后代，则A不能成为B的后代
	也就是A是祖先，B是孙子，A不能成为B的孙子了
因此，我们打算为A修改一个祖先成为B的时候
	我们需要先查找B的家谱树里是否有A，如果有A则茶杯了
*/

//调用model 
$cat = new CatModel();

//查找新的父栏目的家谱树
// echo "你想修改",$cat_id,'栏目<br />';
// echo "想修改他的新父栏目为",$data['parent_id'],'<br />';
// echo $data['parent_id'],'栏目的家谱树是<br />';
//print_r($cat->getTree($data['parent_id']));
//echo "<br />";
$trees = $cat->getTree($data['parent_id']);


//判断自身是否在新父栏目的家谱树里面
$flag = true;
foreach ($trees as $v) { // 要修改A的父栏目是B，但是B是A的子栏目，要提前检测B的父栏目中有没有A
	if ($v['cat_id'] == $cat_id) { //遍历B，如果发现B的父栏目中有和A一样的cat_id,则不能修改
		$flag = false;
		break;
	}
}

if (!$flag) {
	//echo $cat_id,'是',$data['parent_id'],'的祖先';
	echo "不允许修改上级栏目";
	exit;
}

if ($cat->update($data, $cat_id)) {
 	echo "修改成功";
 } else{
 	echo "修改失败";
 }


?>