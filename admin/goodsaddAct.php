<?php 
/****
布尔教育 高端PHP培训
培  训: http://www.itbool.com
论  坛: http://www.zixue.it
****/

define('ACC', true);
require('../include/init.php');


//print_r($_POST);
/*
Array
(
    [MAX_FILE_SIZE] => 2097152
    [goods_name] => 小泽玛利亚
    [goods_sn] => 123456789
    [cat_id] => 5
    [shop_price] => 2999
    [goods_desc] => 张先生的
    [goods_weight] => 250
    [weight_unit] => 0.001
    [goods_number] => 5
    [is_best] => 1
    [is_hot] => 1
    [is_on_sale] => 1
    [keywords] => AV
    [goods_brief] => 阿斯顿法国航空
    [seller_note] => 
    [goods_id] => 0
    [act] => insert
)
*/
$data = array();
/*
//数据检验
$data['goods_name'] = trim($_POST['goods_name']);
if ($data['goods_name'] == '') {
	echo "商品名不能为空";
	exit;
}

$data['goods_sn'] = trim($_POST['goods_sn']);
$data['cat_id'] = $_POST['cat_id'];
$data['shop_price'] = $_POST['shop_price'];
$data['market_price'] = $_POST['market_price'];
$data['goods_desc'] = $_POST['goods_desc'];
$data['goods_weight'] = $_POST['goods_weight'] * $_POST['weight_unit'];
$data['is_best'] = isset($_POST['is_best'])?1:0;
$data['is_new'] = isset($_POST['is_new'])?1:0;
$data['is_hot'] = isset($_POST['is_hot'])?1:0;
$data['is_on_sale'] = isset($_POST['is_on_sale'])?1:0;
$data['goods_brief'] = trim($_POST['goods_brief']);

$data['add_time'] = time();
*/
//print_r($data);

$goods = new GoodsModel();
$_POST['goods_weight'] *= $_POST['weight_unit'];

//print_r($_POST);
$data = $goods->_facade($_POST); //自动过滤

//print_r($data);
$data = $goods->_autoFill($data); //自动填充
//print_r($data);

//2016-10-04 判断商品货号 自动添加商品号
if (empty($data['goods_sn'])) {
    $data['goods_sn'] = $goods->createSn();
}


if (!$goods->_validate($data)) {
    echo "没通过验证<br />";
    echo implode(',', $goods->getErr());
    //print_r($goods->getErr());
    //exit;
}




//上传图片
$uptool = new UpTool();
$ori_img = $uptool->up('ori_img');

if ($ori_img) {
    $data['ori_img'] = $ori_img;
}

//如果$ori_img上传成功，则再次生成中等大小的缩略图 大小是300*400
//根据原始地址，定中等地址  aa.jpg--->goods_aa.jpg
if ($ori_img) {

    $ori_img = ROOT . $ori_img; //加上他的绝对路径
   
    //获取修改后的中等图片的名字
    $goods_img = dirname($ori_img).'/goods_'.basename($ori_img);
    if (ImageTool::thumb($ori_img, $goods_img, 300, 400)) {
       $data['goods_img'] = str_replace(ROOT,'',$goods_img); //在$goods_img中 ''取代了ROOT
    } 

    //再次浏览时生成缩略图 大小是160*220
    //定做略图的地址aa.jpg---->thumb_aa.jpg
    //ImageTool::thumb('原始地址', '缩略地址', 宽 高)
    $thumb_img = dirname($ori_img).'/thumb_'.basename($ori_img);

    if (ImageTool::thumb($ori_img, $thumb_img, 160, 220)) {
       $data['thumb_img'] = str_replace(ROOT, '', $thumb_img);
    } 

}
if ($goods->add($data)) {
	echo "商品发布成功";
}else{
	echo "商品发布失败";
}





?>