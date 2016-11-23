<?php 
/**

测试mysql类
****/
defined('ACC')||exit('ACC Denied');
class mysql extends db {
	private static $ins = NULL;
	private $conn = NULL;
	private $conf = array();

	protected function __construct(){
		//把conf.class.php    $conf数组传过来给这个类的$conf  以实现单例模式  
        //过程是最行先把config.inc.php 的$CFG数组传给conf.class.php 实现单例,然后再从conf.class.php 把数组传到mysql.class.php 类里$conf;过程  
        //就是这样的.  
        //实现单例模式传数组数据 
		$this->conf = conf::getIns();

		//连接数据库
		$this->connect($this->conf->host, $this->conf->user, $this->conf->pwd);
		//选库
		$this->select_db($this->conf->db);
		
		//选择字符集
		$this->setChar($this->conf->char);
	}

	//析构函数
	public function __destruct(){
	}

	//单例函数
	public static function getIns(){
		if (!(self::$ins instanceof self)) { //或者 self::$ins instanceof self
			self::$ins = new self();
		}

		return self::$ins;
	} 

	//数据库连接函数
	public function connect($h, $u, $p){
		$this->conn = mysql_connect($h, $u, $p);
		if (!$this->conn) {
			$err = new Exception('连接失败');
			throw $err;
		}
	}
 
 	//选择库函数
	protected function select_db($db){
		$sql = 'use ' . $db;
		$this->query($sql);
	}

	//设置字符集
	protected function setChar($char){
		$sql = 'set names ' . $char;//一定要注意，set names 后面是有空格的， 
									//use boolshop   和  set names utf8
									//insert into test(t1, t2) values ('tttt1', 'tttt2');
		return $this->query($sql);
	}

	//query执行函数
	public function query($sql){
		$rs = mysql_query($sql, $this->conn);
		log::write($sql);
		
		return $rs;
	}

	//自动拼接sql函数，重要
	public function autoExecute($table, $arr, $mode='insert', $where = ' where 1 limit 1'){ //where 1 表示条件成立 limit 1 表示只取一个
		/*	insert into tbname (username, passwd, email) values ('',)
			把所有的键名用','连接起来
			implode(',', array_key($arr));
			implode("','", array_values($arr));
		*/

		if (!is_array($arr)) {
			return false;
		}

		//拼接
		if ($mode == 'update') {
			$sql = 'update ' . $table . ' set ';
			foreach ($arr as $k =>$v) {
				$sql .= $k . "='" . $v ."',";
			}
			$sql = rtrim($sql,','); //删除字符串末尾的空白符
			$sql .= $where;

			return $this->query($sql);
		}

		//insert拼接
		$sql = 'insert into '.$table.'(' . implode(',', array_keys($arr)).')';
		$sql .= ' values (\'';
		$sql .= implode("','", array_values($arr));
		$sql .= '\')';

		return $this->query($sql);
	}

	//取所在的数据函数
	public function getAll($sql){
		$rs = $this->query($sql);

		$list = array();
		while ($row = mysql_fetch_assoc($rs)) { //将来该函数会被废除
			$list[] = $row;//mysql_fetch_assoc — 从结果集中取得一行作为关联数组
		}
		
		return $list;
	}

	//取单行函数
	public function getRow($sql){
		$rs = $this->query($sql);

		return mysql_fetch_assoc($rs);
	}

	//取单个数据函数
	public function getOne($sql){
		$rs = $this->query($sql);
		$row = mysql_fetch_row($rs); //返回的是一个数组
		return $row[0];
	}

	//返回影响行数的函数
	public function affected_rows(){ //因为mysql删除时，只考虑语法的正确性，所以，如果你删除99999行也是成立的，但是对行数没有影响啊
		return mysql_affected_rows($this->conn);
	}

	//返回最新的auto_increment列的自增长的值
	public function insert_id(){
		return mysql_insert_id($this->conn);
	}
}


?>