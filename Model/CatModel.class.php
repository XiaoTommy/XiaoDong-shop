<?php 
/****
布尔教育 高端PHP培训
培  训: http://www.itbool.com
论  坛: http://www.zixue.it
****/

class CatModel extends Model{
	protected $table = 'categery';

	//把关联数据，键->表中的列， 值->对应表中的值，add()函数自动插入该行
	public function add($data){
		return $this->db->autoExecute($this->table, $data);
	}

	//获取本表下面所有的数据
	public function select(){
		$sql = 'select cat_id, cat_name, parent_id from '. $this->table;
		return $this->db->getALL($sql);
	}

	//根据主键取出一行数据
	public function find($cat_id){
		$sql = 'select * from categery where cat_id='.$cat_id;
		return $this->db->getRow($sql);
	}


	//利用递归无限极分类
	/*
		getCatTree
		pran: int $id
		return $id 栏目的子孙树
	*/
	public function getCatTree($arr, $id=0, $lev=0){
		$tree = array();

		foreach ($arr as $v) {
			if ($v['parent_id'] == $id) {
				$v['lev'] = $lev;
				$tree[] = $v;

				$tree = array_merge($tree, $this->getCatTree($arr, $v['cat_id'],$lev+1)); //用递归实现查找子孙树
																			//array_merge 合并一个或者多个数组
			}
		}
		return $tree;
	}

	//利用无限级查找子栏目 
	/*
		parn: int $id  
		return $id 栏目下的子栏目
	*/
	public function getSon($id){ //我要查看，$id下是否有子栏目
		$sql = 'select cat_id,cat_name,parent_id from '. $this->table. ' where parent_id='.$id;
		return $this->db->getAll($sql);
	}

	//用迭代法查找家谱树
	/*
		parn:int $id
		return array $id栏目中的家谱树
	*/
	public function getTree($id=0){
		$tree = array();
		$cats = $this->select();

		while ($id > 0) {
			foreach ($cats as $v) {
				if ($v['cat_id'] == $id) {
					$tree[] = $v;

					$id = $v['parent_id'];
					break;
				}
			}
		}
		$tree = array_reverse($tree, true); //把数组进行反转，使得父亲在前，子孙在后
		return $tree;		
	}




	//删除栏目
	public function delete($cat_id=0){
		$sql = 'delete from ' . $this->table . ' where cat_id= ' . $cat_id;
		$this->db->query($sql); //因为在mysql.class.php中的affented_rows();中没有写这句话，所以要补充上，而其他函数都有写上的

		return $this->db->affected_rows();//返回的是受影响的行数
	}

	//更新栏目
	public function update($data, $cat_id=0){
		$this->db->autoExecute($this->table, $data, 'update', ' where cat_id=' . $cat_id);
		return $this->db->affected_rows();
	}

	//




}

?>