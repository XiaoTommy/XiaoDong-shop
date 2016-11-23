<?php 
/****
布尔教育 高端PHP培训
培  训: http://www.itbool.com
论  坛: http://www.zixue.it
****/

defined('ACC')||exit('ACC Denied');

class GoodsModel extends Model{
	protected $table = 'goods';
	protected $pk = 'goods_id';
	protected $fields = array('goods_id', 'goods_sn', 'cat_id', 'brand_id', 'goods_name', 'shop_price', 'market_price',
	  'goods_number', 'click_count', 'goods_weight', 'goods_brief', 'goods_desc', 'thumb_img', 'goods_img', 'ori_img',
	  'is_on_sale', 'is_delete', 'is_best', 'is_new', 'is_hot', 'add_time', 'last_update');

	protected $_auto = array(
							array('is_hot','values',0),
							array('is_new','values',0),
							array('is_best','values',0),
							array('add_time','function','time')
							);
	protected $_valid = array(
							array('goods_name', 1, '商品名不能为空', 'require'),
							array('cat_id', 1, '栏目id必须是整形值', 'number'),
							array('is_new', 0, '新品is_new只能是0或者1', 'in', '0,1'),
							array('goods_brief', 2,'长度应在10-100中间', 'length', '10,100')

	);//验证规则 0表示存在字段就验证（默认） 1 表示必须验证  2 表示值不为空的时候验证
	  /*

	  */

//	$data = array();

	/*
		parm array $data
		return bool
	*/
	/*
	public function add($data){
		return $this->db->autoExecute($this->table,$data);
	}
	*/

//回收站功能
	/*
	parn int $id
	return bool
	*/
	public function trash($id){
		return $this->update(array('is_delete'=>1),$id);
	}

	public function getGoods(){
		$sql = 'select * from goods where is_delete=0';
		return $this->db->getAll($sql);
	}

	public function getTrash(){
		$sql = 'select * from goods where is_delete=1';
		return $this->db->getAll($sql);
	}

	/*
	创建商品的货号
	*/
	public function createSn(){
		$sn = 'BL' . date('Ymd') . mt_rand(10000, 99999);

		$sql = 'select count(*) from '. $this->table. " where goods_sn='".$sn."'"; //查看货号相同的是否存在，存在一个返回1，存在两个返回2

		return $this->db->getOne($sql)?$this->createSn():$sn; //如果存在，再次递归创造
	}

	/*
		取出指定条数的新品
	*/
	public function getNew($n=5){
		$sql = 'select goods_id, goods_name, shop_price, market_price, thumb_img from '.$this->table.' where is_new=1 order by add_time limit 5';

		return $this->db->getAll($sql);
	}

	/*
		取出指定栏目的商品
	*/
	public function catGoods($cat_id, $offset=0, $limit=5){ //$offset 表示偏移量   $limit 表示取出的条目
		$categery = new CatModel();
		$cats = $categery->select(); //取出所有栏目
		$sons = $categery->getCatTree($cats, $cat_id);

		$sub = array($cat_id);

		if (!empty($sons)) { //没有子栏目
			foreach ($sons as $v) {
				$sub[] = $v['cat_id'];
			}
		}
		
		$in = implode(',', $sub);

		$sql = 'select  goods_id, goods_name, shop_price, market_price, thumb_img from '.$this->table.' where cat_id in ('.$in.') order by add_time limit '.$offset.','.$limit;

		return $this->db->getAll($sql);
	}

	public function catGoodsCount($cat_id){
		$categery = new CatModel();
		$cats = $categery->select(); //取出所有栏目
		$sons = $categery->getCatTree($cats, $cat_id);

		$sub = array($cat_id);

		if (!empty($sons)) { //没有子栏目
			foreach ($sons as $v) {
				$sub[] = $v['cat_id'];
			}
		}
		
		$in = implode(',', $sub);

		$sql = 'select count(*) from goods where cat_id in ('.$in.')';

		return $this->db->getOne($sql);
	}

	//获取购物车中商品的详细信息
	//parn array $items 购物车中的商品数组
	//return 商品数组中的详细信息
	public function getCartGoods($items){
	
		foreach ($items as $k=>$v) {//循环购物车中的商品，每循环一次，得到一次thumb_img
			
			$sql = 'select goods_id, goods_name, thumb_img, shop_price, market_price from '.$this->table.' where goods_id='.$k ;
			$row = $this->db->getRow($sql);
		
			$items[$k]['thumb_img'] = $row['thumb_img'];
			$items[$k]['market_price'] = $row['market_price'];
		}

		return $items;

	}

}



?>