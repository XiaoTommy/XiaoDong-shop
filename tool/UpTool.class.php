<?php 
/**
单文件上传类
多文件一会自己写

作用：
	上传文件
	配置允许的后缀
	配置允许的大小

方法：获取文件后缀，判断文件后缀，
	  判断文件大小
	  良好的报错信息，
	  随机生成目录，生成文件名
****/

defined('ACC')||exit('ACC Denied');

class UpTool{

	protected $allowExt = 'jpg, png, gif, jpeg, bmp';
	protected $maxSize = 1; //1M，最大为1M

	protected $errornum = 0; //错误代码
	protected $error = array(
							0=>'无措',
							1=>'上传文件大小超出系统限制',
							2=>'上传文件大小超出表单限制',
							3=>'文件只有一部分被上传',
							4=>'没有文件被上传',
							6=>'找不到临时文件夹',
							7=>'文件写入失败',
							8=>'文件后缀不允许',
							9=>'文件大小超出范围',
							10=>'文件目录创建失败',
							11=>'文件移动失败'
		);

	//	protected $file = NULL;//准备储存上传文件的信息使用

	// //获取文件信息
	// protected function getFile($key){
	// 	return $this->file = $_FILES[$key];
	// }

	//上传文件
	public function up($key){
		if(!isset($_FILES[$key])){
			return false;
		}
		$file = $_FILES[$key];

		//检查是否上传成功
		if ($file['error']) {
			$this->errornum = $file['error'];
			return false;
		}

		//获取后缀
		$ext = $this->getExt($file['name']);

		//检查后缀
		if (!$this->isAllowExt($ext)) {
			$this->errornum = 8;
			return false;
		}

		//检查大小
		if (!$this->isAllowSize($file['size'])) {
			$this->errornum = 9;
			return false;
		}

		//通过上传
		//	创建目录
		$dir = $this->mk_dir();

		if ($dir == false) {
			$this->errornum = 10;
			return false;
		}

		//生成随机文件名
		$newname = $this->randName().'.'.$ext;
		$dir = $dir.'/'.$newname;

		//移动文件
		if (!move_uploaded_file($file['tmp_name'], $dir)) {
		 	$this->errornum = 11;
		 	return false;
		}
		//return $dir; 相对路径 
		return str_replace(ROOT, '', $dir); 	
	}

	public function getErr(){
		return $this->error[$this->errornum];
	}

	//允许的自定义后缀名字
	public function setExt($exts){
		$this->allowExt = $exts;
	}
	//允许自定义内存大小
	public function setSize($num){
		$this->maxSize = $num;
	}

	//获取文件后缀
	//parn string $file
	//return string $ext 后缀
	protected function getExt($file){
		$tmp = explode('.', $file);
		return end($tmp);
	}

	//判断后缀(防止大小写)
	//parn string $ext 文件后缀
	//return bool
	protected function isAllowExt($ext){
		return in_array(strtolower($ext), explode(',', strtolower($this->allowExt)));
	}

	//判断大小
	protected function isAllowSize($size){
		return $size <= $this->maxSize * 1024 * 1024;
	}

	//按照日期创建上传目录
	protected function mk_dir(){
		$dir = ROOT.'data/images/'.date('Ym/d');
		
		if (is_dir($dir) || mkdir($dir,0777,true)) { //0777可以被忽略，表示获得最大的访问权限
			return $dir;
		}else{
			return false;
		}
	}

	//生成随机文件名
	protected function randName($length = 6){
		$str = 'abcdefghijklmnopqrstuvwxyz0123456789';
		return substr(str_shuffle($str), 0, $length); 
	}
}



?>