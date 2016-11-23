<?php 
/**

购物车类

1.无论刷新多少次页面，新增多少商品
	查看购物车的时候应该都是一样的结果
	
	在整站范围内，购物车是全局有效的

	所以，把购物车的信息数据 放到session/cookie或者数据库中

2.全局有效性，购物车只能有一实例，不能再3个页面购物就形成了3个购物车

	所以，使用单例模式


最终，我们使用  session + 单例
功能分析：

		判断商品是否存在
		添加商品
		删除商品
		修改商品数量

		商品数量+1 -1

		查询购物车的商品种类
		查询购物车的商品数量
		查询购物车的商品总金额
		返回购物车里的所有商品

		清空购物车
**/

class CartTool{
	private static $ins = null;
	private $items = array();


	protected function __construct(){
	}

	final protected function __clone(){
	}

	//获取实例
	protected static function getIns(){
		if (!(self::$ins instanceof self)) {
			self::$ins = new self();
		}
		return self::$ins;
	}

	//把购物车的单例对象放到session中
	public static function getCart(){
		if(!isset($_SESSION['cart']) || !($_SESSION['cart'] instanceof self)){
			$_SESSION['cart'] = self::getIns();
		}
		return $_SESSION['cart'];
	}

	//添加商品
	/*
	int $id 商品主键
	string $name 商品名称
	float $price 商品价格
	int $num 商品数量
	*/
	public function addItem($id, $name, $price, $num=1){

		//如果该商品已经存在，就直接增加一个
		if ($this->hasItem($id)) {
			$this->incNum($id, $num);
			return;
		}

		$item = array();
		$item['name'] = $name;
		$item['price'] = $price;
		$item['num'] = $num;
	
		$this->items[$id] = $item;
	}

	//修改数量
	/*
	int $id 商品主键
	int $num 商品数量
	*/
	public function modnum($id, $num=1){
		if (!$this->hasItem($id)) {
			return false;
		}
		$this->items[$id]['num'] = $num;
	}

	//商品数量增加 一个
	public function incNum($id, $num=1){
		if ($this->hasItem($id)) {
			$this->items[$id]['num'] += $num;
		}
	}

	//商品数量减少 一个
	public function decNum($id, $num=1){
		if ($this->hasItem($id)) {
			$this->items[$id]['num'] -= $num;
		}

		//如果数量减少为0，则删除商品
		if ($this->item[$id]['num'] < 1) {
		 	$this->delItem($id);
		 } 
	}

	//判断商品是否存在
	public function hasItem($id){
		return array_key_exists($id, $this->items);
	}

	//删除商品
	public function delItem($id){
		unset($this->item[$id]);
	}

	//查询商品种类
	public function getCnt(){
		return count($this->items);
	}

	//查询购物车中商品的个数
	public function getNum(){
		if ($this->getCnt() == 0) {
			return 0;
		}

		$sum = 0;

		foreach ($this->items as $item) {
			$sum += $item['num'];
		}
		return $sum;
	}

	//查询购物车中商品的总金额
	public function getPrice(){
		if ($this->getCnt() == 0) {
			return 0;
		}

		$price = 0.0;

		foreach ($this->items as $item) {
			$price += $item['num'] * $item['price'];
		}
		return $price;

	}

	//返回购物车中的所有商品
	public function all(){
		return $this->items;
	} 

	//清空购物车
	public function clear(){
		$this->items = array();
	}

}

$cart = CartTool::getCart();

/*
if ($_GET['test'] == 'addzhangsan') {
	$cart->addItem(1, 'zhangsan', 23.54, 1);
	echo "购买成功";
}else if ($_GET['test'] == 'clear') {
	$cart->clear();
}else if ($_GET['test'] == 'show') {
	print_r($cart->all());
	echo "<br />";
	echo "一共".$cart->getCnt()."种商品";
	echo "一共".$cart->getNum()."个商品";
	echo "一共".$cart->getPrice()."钱";
}else if ($_GET['test'] == 'nuoya') {
	$cart->addItem(2, '诺亚', 9999.99, 2);
}

//print_r(CartTool::getCart());  //CarTool Object ( [items:CarTool:private] => Array ( ) )
*/







?>