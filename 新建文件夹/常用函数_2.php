<?php 

basename — 返回路径中的文件名部分
dirname — 返回路径中的目录部分

sin — 正弦    sin() 返回参数 arg 的正弦值。参数 arg 的单位为弧度
deg2rad — 将角度转换为弧度
	// 返回值的精度由配置中的 precision 指示确定
	echo sin(deg2rad(60));  //  0.866025403 ...
	echo sin(60);           // -0.304810621 ...


round — 对浮点数进行四舍五入

FILTER_VALIDATE_EMAIL   检测email  也可以使用正则表达式

mysql连接函数（我感觉挺重要的额！！）
	mysql_connect — 打开一个到 MySQL 服务器的连接
			mysql_connect('localhost', 'mysql_user', 'mysql_password');
	mysql_query — 发送一条 MySQL 查询
	mysql_fetch_array — 从结果集中取得一行作为关联数组，或数字数组，或二者兼有
	mysql_fetch_assoc — 从结果集中取得一行作为关联数组 
	mysql_fetch_field — 从结果集中取得列信息并作为对象返回
	mysql_fetch_lengths — 取得结果集中每个输出的长度 
	mysql_fetch_object — 从结果集中取得一行作为对象
	mysql_fetch_row — 从结果集中取得一行作为枚举数组


数组

array_push(array, var)  压入数据！
效果类似于
array_push($array, $var)
	等价于$array[] = $var; //意思是把 $var 的值压入 $array[]中

array_unique — 移除数组中重复的值
	array_unique(array)
mb_substr(str, start)  返回字符串的一部分,他可以设置字符编码 mb_substr($arr, 0, 10, 'UTF8')
substr(string, start)  返回字符串的子串 ,不用设置字符编码





数据库导入数据  source sql的路径
数据库清空 truncate 表的名字




