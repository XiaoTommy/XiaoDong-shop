<?php 
/**
栏目删除页面
****/

//1.接收数据
define('ACC', true);
require('../include/init.php');

$cat_id = $_GET['cat_id'] + 0; //加0，是为了防止人为了在后面追加 ?tid=1 or 1 这样的语句
							   //不管你的参数多么险恶,+0后都老老实实变成数值类型
//要删除的时候，我们需要判断这个是栏目是否有子栏目
//如果有子栏目，则该栏目不允许删除
//无限级分类有3中：1.查子栏目 2.查子孙栏目 3.查家谱树
	//因此我们可以在model中写一个方法，专门查找子栏目，并且调用

$cat = new CatModel();

$sons = $cat->getSon($cat_id);
if (!empty($sons)) {
	exit('存在子栏目，不允许删除');
}

if ($cat->delete($cat_id)) {
	echo "删除成功";
}else{
	echo "删除失败";
}

?>