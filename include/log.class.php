<?php 
/**

file log.class.php
记录信息到日志

****/

/*
给定内容，写入文件（fopen，fwrite）
如果文件大于1M，重新写一份

传给我一个内容，
	判断日志大小，如果大于1M--->备份, 否则写入
*/
defined('ACC')||exit('ACC Denied');
class log{

	//设置一个常量，代表日志文件的名称
	const LOGFILE = 'curr.log'; 

	//写日志
	public static function write($cont){
		$cont .= "\r\n";
		//判断时候备份
		$log = self::isBak(); //计算日志文件的地址

		$fh = fopen($log, 'ab');

		fwrite($fh, $cont);
		fclose($fh);
	}

	//备份日志
	public static function bak(){
		//把原来的日志文件，修改名字，储存起来
		//改成年-月-日.bak的形式
		$log = ROOT . 'data/log/'.self::LOGFILE;
		$bak = ROOT . 'data/log/' . date('ymd') . mt_rand(10000,999999).'.bak';
		return rename($log, $bak);
	}

	//读取并且判断日志大小
	public static function isBak(){
		$log = ROOT . 'data/log/'.self::LOGFILE;
		if (!file_exists($log)) { //如果文件不存在，则创建文件
			touch($log); //touch()在linux下也有此命令,快速创建文件
			return $log;
		}

		//如果存在，判断大小
		//清楚缓存
		clearstatcache(true,$log);
		$size = filesize($log);
		if ($size <= 1024*1024) { //若文件小于1M
			return $log;
		}

		//文件大于1M，需要复制文件
		
		if (!self::bak()) {
		 	return $log;
		 } else{
		 	touch($log); //创建
		 	return $log;
		 }
		

	}


}








?>