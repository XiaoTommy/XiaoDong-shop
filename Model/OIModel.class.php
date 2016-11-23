<?php 
/****
****/

defined('ACC') || exit('ACC Denied'); 

class OIModel extends Model{
	protected $table = 'orderinfo';
	protected $pk = 'order_id';
	protected $fields = array(  	//所有的域
			'order_id','order_sn','user_id','username','zone','address','zipcode','reciver',
			'email','tel','mobile','building','best_time',' add_time','order_amount','payment');

	protected $_valid = array( //自动验证
							array('reciver', 1, '收货人不能为空', 'require'),
							array('email', 1, 'email不合法', 'email'),
							array('payment', 1, '必须选择支付方式', 'in', '4,5') //代表在线支付和货到付款
							//1.是必须检测， 0是如果存在就检测， 2是值不为空是检测
							);
	
	protected $_auto = array( //自动填充
							array('add_time','function','time')
							);

	public function orderSn(){
		$sn = 'OI' . date('Ymd'). mt_rand(10000,99999);

		$sql = 'select count(*) from ' . $this->table.' where order_sn ='."'$sn'";
		return $this->db->getOne($sql)?$this->orderSn():$sn;
	}

	public function invoke($order_id){
		//删除订单
		$this->delete($order_id);

		//删除订单对应的商品
		$sql = 'delete from ordergoods where order_id = '.$order_id;

		return $this->db->query($sql);

	}


}	

?>