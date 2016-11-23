<?php 
/**

购物流程页面
商品的核心功能
****/

define('ACC', true);
require('./include/init.php');


//设置一个动作参数，判断用户是做什么？下订单？写地址？提交？清空购物车？
$act = isset($_GET['act'])?$_GET['act']:'buy';

$cart = CartTool::getCart(); //获取购物车实例
$goods = new GoodsModel();

if ($act == 'buy') { //把商品添加奥购物车
	$goods_id = isset($_GET['goods_id'])?$_GET['goods_id']+0:0;
	$num = isset($_GET['num'])?$_GET['num']+0:1;

	if ($goods_id) { //goods_id 为真，把商品加入到购物车里
		$g = $goods->find($goods_id);
		
		if (!empty($g)) { //存在此商品
			//需要判断此商品是否在回收站
			//商品是否已经下架

			if ($g['is_delete'] == 1 || $g['is_on_sale'] == 0) {
				$msg = '此商品不能购买';
				include(ROOT.'view/front/msg.html');
				exit;
			}

			//商品加到购物车中
			$cart->addItem($goods_id,$g['goods_name'], $g['shop_price'],$num);	
		
			//判断库存
			$items = $cart->all();
			if ($items[$goods_id]['num'] > $g['goods_number']) {
			 	//库存不够，加入购物车的动作取消
			 	$cart->decNum($goods_id, $num);
			 	$msg = '库存不足';
				include(ROOT.'view/front/msg.html');
				exit;
			 } 
		}
		//print_r($cart->all());
		
	}
	$items = $cart->all();

	if (empty($items)) {//如果购物车为空，返回首页
		header('location: index.php');
		exit;
	}

	//把购物车的详细信息那出来
	$items = $goods->getCartGoods($items);

	//获取商品的总价格
	$total = $cart->getPrice();

	//获取商品市场总价格
	$market_total = 0.0;
	foreach ($items as $v) {
		$market_total += $v['market_price']*$v['num'];
	}

	$discont = $market_total - $total;
	$rate = 100 * $discont / $market_total;
	$rate = round($rate, 2);

	//print_r($items);exit;

	include(ROOT . 'view/front/jiesuan.html');
}else if ($act == 'clear') {
	$cart->clear();
	$msg = '购物车已经清空';
	include(ROOT.'view/front/msg.html');
}else if ($act == 'tijiao') {

	$items = $cart->all(); // 取出购物车中的商品

	//把购物车的详细信息那出来
	$items = $goods->getCartGoods($items);

	//获取商品的总价格
	$total = $cart->getPrice();

	//获取商品市场总价格
	$market_total = 0.0;
	foreach ($items as $v) {
		$market_total += $v['market_price']*$v['num'];
	}

	$discont = $market_total - $total;
	$rate = round(100 * $discont/$total, 2);

	include(ROOT.'view/front/tijiao.html');
}else if ($act == 'done') {
	//订单入库，重要还价
	/*
		从表单读取送货地址，手机等信息
		从购物车获取总价格信息
		写入orderinfo表
	*/
	//print_r($_POST);
	/*
		Array ( [zone] => 北京 
		[reciver] => 张三
		 [email] => mu@163.com 
		 [address] => 海淀 
		 [zipcode] => 064201 
		 [tel] => 1342569 
		 [mobile] => 25623 
		 [building] => GD 
		 [best_time] => 2.02 
		 [step] => done 
		 [act] => checkout 
		 [address_id] => 
		 [payment] => 4 
		 [x] => 16 
		 [y] => 1 )
	*/
	$OI = new OIModel();
	if (!$OI->_validate($_POST)) { //如果检验没有通过，报错退出
	 	$msg = implode(',', $OI->getErr());
	 	include(ROOT.'view/front/msg.html');
	 	exit;
	 } 
	
	//自动过滤
	 $data = $OI->_facade($_POST);

	 //自动填充
	 $data = $OI->_autoFill($data);

	 //写入总金额
	 $total = $data['order_amount'] = $cart->getPrice();

	 //写入用户信息,从session中读取
	 $data['user_id'] = isset($_SESSION['user_id'])?$_SESSION['user_id']:0;
	 $data['username'] = isset($_SESSION['username'])?$_SESSION['username']:'匿名用户';

	 //写入订单号
	 $order_sn = $data['order_sn'] = $OI->orderSn();


	 if (!$OI->add($data)) {
	  	$msg = '下订单失败';
	  	include(ROOT.'view/front/msg.html');
	  	exit;
	  }

	  //获取刚刚产生的order_id的值
	  $order_id = $OI->insert_id();

	  echo "订单写入成功";


	//订单的商品写进去数据库，
	/*
		一个订单中有N个商品，我们可以循环写入ordergoods表中
	*/
	$items = $cart->all(); //返回订单中的所有商品
	$cnt = 0; //用来记录，插入info表成功的次数
	$OG = new OGModel(); //获取ordergoods表的操作model

	foreach ($items as $k=>$v) {
			$data = array();		
			
			$data['order_id'] = $order_id;
			$data['order_sn'] = $order_sn;
			$data['goods_id'] = $k;
			$data['goods_name'] = $v['name'];
			$data['goods_number'] = $v['num'];
			$data['shop_price'] = $v['price'];
			$data['subtotal'] = $v['num']*$v['price'];
		
			if ($OG->addOG($data)) {
			 	$cnt += 1; //插入一条og成功，$cnt+1,必须所有的商品都加入成功，订单才算成功
			 } 

			//var_dump($cnt);
			// print_r($data);
			// echo "<>br />";
		}

		if (count($items) !== $cnt) { //购物车的商品并没有全部入库成功
			//撤销订单
			$OI->invoke($order_id);
			$msg = '下订单失败';
			include(ROOT.'view/front/msg.html');
			exit;
		}
		var_dump($cnt);

		//下订单成功
		//清空购物车
		$cart->clear();

		
/*
	计算在线支付的md5的值
	v_amount v_moneytype v_oid v_mid v_url key 拼接
*/
	$v_url = 'http://localhost/shangcheng/day17/bool/recive.php';
	$md5key = '#(%#WU)(UFGDKJGNDFG'; //秘钥是自己设置的，非常重要的东西
	$v_md5info = strtoupper(md5($total.'CNY'.$order_sn.'1009001'.$v_url.$md5key));


	include(ROOT.'view/front/order.html');

}


?>