<?php 
/**
file db.class.php
数据库

因为目前采用什么数据库还不清楚

****/
defined('ACC')||exit('ACC Denied');

abstract class db{
	//连接数据库   
	//return bool
	public abstract function connect($h, $u, $p);

	//发送查询  
	//parns $sql 发送sql语句  
	//return mixed/resource
	public abstract function query($sql);

	//查询单行数据
	//parns $sql 发送select型语句
	//return array/bool
	public abstract function getRow($sql);	

	//查询多行数据
	//parns $sql 发送select型语句
	//return array/bool
	public abstract function getAll($sql);	

	//查询单个数据
	//parns $sql 发送select型语句
	//return array/bool
	public abstract function getOne($sql);	

	//自动执行insert和update语句
	//parns $sql 发送select型语句
	//return array/bool
	/*
	$this->autoExecute('user', array('username'->'zhangsan', 'email'->'zhangsan@163.com'),'insert');
	会自动生成 insert into user (username, email) values ('zhangsan', 'zhangsan@163.com');
	update时使用 where
	*/
	public abstract function autoExecute($table, $data, $act='insert', $where='');
}



?>