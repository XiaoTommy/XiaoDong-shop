<?php 
/****
布尔教育 高端PHP培训
培  训: http://www.itbool.com
论  坛: http://www.zixue.it
****/
defined('ACC')||exit('ACC Denied');
class Model{
	protected $table = null; //model所控制的表
	protected $db = null; //引入的mysql对象
	protected $pk = ''; //primary key
	protected $fields = array();
	
	protected $_auto = array();
	protected $_valid = array();
	protected $error = array();

	public function __construct(){
		$this->db = mysql::getIns();
	}

	public function table($table){
		$this->table = $table;
	}

	/*
		自动过滤
		负责把传来的数组清除掉不用的单元，留下与表的字段对应的单元
		循环数组，分别判断key，是否是表的字段
		当然，我们先采集表的字段，可以desc goods得到（开发阶段可以使用），或者自己写好（程序上线之后，没有特别耗资源）
	*/
	public function _facade($array=array()){
		$data = array();
		foreach ($array as $k => $v) {
			if (in_array($k, $this->fields)) {//判断$k是不是表的字段
				$data[$k] = $v;
			}
		}
		return $data;
	}

	/*
		自动填充功能
		有些列$_POST没有能传递过来，我们需要有自动填充功能
	*/
	public function _autofill($data){
		foreach ($this->_auto as $k=>$v) {
			if (!array_key_exists($v[0], $data)) {
				switch ($v[1]) {
					case 'values':
						$data[$v[0]] = $v[2];
						break;
					
					case 'function':
						$data[$v[0]] = call_user_func($v[2]); //回调函数
						break;
				}
			}
		}
		return $data;
	}

/*
	自动验证格式：$this->valid = array(
					array('验证的字段名'，0/1/2（验证场景）,'报错提示', 'require/int(某几种情况)/between（范围）/length(范围)','参数')
	)
	protected $_valid = array(
							array('goods_name', 1, '商品名不能为空', 'required')，
							array('cat_id', 1, '必须是整形值', 'number'),
							array('is_new', 0, '必须是整形值', 'in', '0,1'),
							array('goods_brief', 2,'长度应在10-100中间', 'length', '10,100')

	);
*/
 public function _validate($data){
 	if (empty($this->_valid)) {
 		return true;
 	}

 	$this->error = array();

 	foreach ($this->_valid as $k => $v) {
 		switch ($v[1]) {
 			case 1:
 				if (!isset($data[$v[0]])) {
 					$this->error[] = $v[2];
 					return false;
 				}

 				if (!isset($v[4])) {
 					$v[4] = '';
 				}

 				if (!$this->check($data[$v[0]],$v[3],$v[4])) {
 				//if (!$this->check($data[$v[0]],$v[3],$v[4]='')) {
 					$this->error[] = $v[2];
 					return false;
 				}
 				break;

 			case 0:
 				if (isset($data[$v[0]])) {
 					if (!$this->check($data[$v[0]],$v[3],$v[4])) {
 						$this->error[] = $v[2];
 						return false;
 					}
 				}
 				break;
 			
 			case 2:
 				if (isset($data[$v[0]]) || !empty($data[$v[0]]) ) {
 					if (!$this->check($data[$v[0]],$v[3],$v[4])) {
 						$this->error[] = $v[2];
 						return false;
 					}
 				}
 				break;
 		}
 	}
 	return true;
 }

 public function getErr(){
 	return $this->error;
 }

	protected function check($value, $rule='', $parm=''){
		switch ($rule) {
			case 'require':
				return !empty($value);
				//break; 我们直接return了	
			case 'number':
				return is_numeric($value);
			case 'in':
				$tmp = explode(',', $parm);
				return in_array($value, $tmp);
			case 'between':
				list($min,$max) = explode(',', $parm);
				return $value>=$min && $value<=$max;
			case 'length':
				list($min, $max) = explode(',', $parm);
				return strlen($value) >= $min && strlen($value) <=$max; 
			case 'email':
				//判断$value是否是email，可以用正则表达式，也可以用系统函数
				return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
			default:
				return false;
		}
	} 	



	//让父类model具有增删改查的功能，省去其他model的重复操作
	
	/*
		parm array $data
		return bool
	*/
	public function add($data){
		return $this->db->autoExecute($this->table,$data);
	}

	/*
		parm int $id 主键
		return int 影响的行数
	*/
		public function delete($id){
			$sql = 'delete from '.$this->table.' where '. $this->pk.'='.$id;
			if ($this->db->query($sql)) {
				return $this->db->affected_rows();
			}else{
				return false;
			}
		}

	/*
		parn array $data
		parn int $id
		return int 影响行数
	*/
	public function update($data,$id){
		$rs = $this->db->autoExecute($this->table, $data, 'update', ' where '.$this->pk.'='.$id);
		if ($rs) {
			return $this->db->affected_rows();
		}else{
			return false;
		}
	}

	/*
		return array
	*/
	public function select(){
		$sql = 'select * from '.$this->table;
		return $this->db->getALL($sql);
	}

	/*
		parn int $id
		return array
	*/
	public function find($id){
		$sql = 'select * from '.$this->table. ' where '.$this->pk. ' = '.$id;
		return $this->db->getRow($sql);
	}

	public function insert_id(){
		return $this->db->insert_id();
	}










}

?>