<?php 
/****
布尔教育 高端PHP培训
培  训: http://www.itbool.com
论  坛: http://www.zixue.it
****/

defined('ACC') || exit('ACC Denied'); 

class UserModel extends Model{
	protected $table = 'user';
	protected $pk = 'user_id';
	protected $fields = array(  	//所有的域
			'user_id', 'username', 'email', 'passwd', 'regtime', 'lastlogin');

	protected $_valid = array(
							array('username', 1, '用户名必须存在', 'require'),
							array('username', 0, '用户名必须在4-16个字符之间', 'length', '4,16'),
						//	array('username', 1, '用户名必须在4-16个字符之间', 'length', '4, 16'); //这样会导致参数个数不正确
							array('email', 1, 'email不合法', 'email'),
							array('passwd', 1, 'password不能为空', 'require')
							//1.是必须检测， 0是如果存在就检测， 2是值不为空是检测
							);
	
	protected $_auto = array(
							array('regtime','function','time')
							);
	
	//用户注册，密码加密
	public function reg($data){
		if ($data['passwd']) {
			$data['passwd'] = $this->encPasswd($data['passwd']);
		}
		
		return $this->add($data);
	}

	protected function encPasswd($p){
		return md5($p);
	}

	/*
		根据用户名查询用户信息
	*/
	public function checkUser($username, $passwd=''){
		if ($passwd == '') {
			$sql = 'select count(*) from ' .$this->table. " where username='".$username."'";
			return $this->db->getOne($sql);
		}else{
			$sql = 'select user_id, username, email, passwd from '.$this->table." where username='".$username."'";

			$row = $this->db->getRow($sql);
		
			if (empty($row)) {
				return false;
			}
			if ($row['passwd'] !== $this->encPasswd($passwd) ) {
				return false;
			}

			unset($row['passwd']);
			return $row;
		}
		
	} 





}






?>