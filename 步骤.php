<?php 
1.第一天，写了一部分框架
	程序进入页面 		index.php
	框架初始化文件 		init.php
	连接数据库类 		mysql.clss.php
	记录日志类 			log.class.php
	
	配置文件 			config.inc.php
	接受配置文件数据 	conf.class.php
	连接数据库抽象类 	db.class.php
	
	常用函数类 			lib_base.php
			//为了使得日志记录整齐规范

	index.php -> init.php ->

2.第二天，写了后台栏目增删改查，和一部分商品增删改查类和方法
	1.首先和后台html配合，修改href 和src（由于路径的关系） //这步我直接复制的
	2.然后契合后台html页面(只是连接)
		index.php 
		left.php 
		main.php 
		top.php
		drag.php
	3.Model层处理信息
		Model.class.php (模型处理类，对后台的数据管理，自动过滤、自动填充、自动验证格式、、报错、增删改查功(insert/update/delete/select/find))
		
		CateModel.class.php(栏目模型处理类，获取表全部数据、获取一行数据、查找子孙树、家谱树、子栏目、删除栏目、更新栏目（有一部分和Model的重复了，可以精简程序的）)

		GoodsModel.class.php(商品模型处理类，提供需要过滤的数组、提供需要自动填充的数组、提供需要自动验证格式的数组、回收站功能、自动创建货号功能、添加图片还没有做)

	4.admin层处理信息
		cateadd.php（创建CatModel实例，显示页面，和html页面交互） 和 cateaddAct.php（接受检查数据，使用model中的add方法）

		catedit.php(和html交互) 和cateditAct.php(接受检测数据，分析情况，调用model中的update方法)

		catedel.php (接受数据，分析情况，调用model中的delete)

		catelist.php(实例化CatModel，和html交互)


		goodslist.php（实例化GoodsModel，和html交互）

		goodsadd.php(和html交互) 和 goodsaddAct.php（实例化GoodsModel，调用Model中的自动过滤，填充，验证格式、自动添加货号、）

		goodstrash.php(s商品回收站页面)

		goods.php（实例化GoodsModel，点击时查看商品数据）

3.第3天 写了常用的工具类 
	文件上传类
		文件上传类有点生疏了，多文件上传类还没有写，需要看一下以前的笔记，好好认真复习下
			文件上传类通过 $_FILES 这个全局变量通过 POST 过来的文件的绝对地址获取文件的内容，
		另外：文件上传的html中，必须生命 encotype='multipart/from-data'	,通知服务器解析成文件，而不是字符串
			使用move_uploaded_file 来实现文件的移动
			上传文件应该有的功能是：检查时候上传成功以及失败的错误信息、检查后缀、检查大小、移动文件、生成随机文件名、按照日期生成目录

	图片处理类
		主要是运用GD库的知识，主要有 生成缩略图 和 添加水印 的功能，另外附赠了 验证码 功能
			1.创建画布 imagecreatefromjpeg(filename) 以图片作为画布
					   imagecreatetruecolor(width, height)	纯色画布
			2.创建颜料 imagecolorallocate(image, red, green, blue)
			3.画布填充 imagefill(image, x, y, color)
			4.画线、矩形、椭圆、验证码
						线 imageline(image, x1, y1, x2, y2, color)
						矩形 imagerectangle(image, x1, y1, x2, y2, color)
						椭圆 imageellipse(image, cx, cy, width, height, color)
						字符串 imagestring(image, font, x, y, string, color)
						中文 imagettftext(image, size, angle, x, y, color, fontfile, text)
						圆弧比较麻烦
			5.保存图片 
						header('content-type: image/jpeg');
						imagejpeg(image,'./xx.jpeg' )
			6.销毁画布 imagedestroy(image)
		添加水印就是 图片复制 + 图片透明化
			复制 imagecopy(dst_im, src_im, dst_x, dst_y, src_x, src_y, src_w, src_h)
			透明化 imagecopymerge(dst_im, src_im, dst_x, dst_y, src_x, src_y, src_w, src_h, pct)
			缩略图 imagecopyresampled(dst_image, src_image, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)

第4天 什么也没有做
第5天 写了剩下的两个常用的工具类（写的很辛苦）（基础知识很重要！！！）
	分页类
		主要是把商品总条数、每页条数、当前页这几个关系搞清楚，然后要确保地址栏原始信息的保存，只能改变页码，之前的不能变
	购物车类
		主要是储存在session中+单例模式，然后是购物车的一系列增删改查，判断总数总金额

	接下来还有用户的注册、用户的支付、后台与前台页面的相互契合


	------------------------------------------------------------
	目前已经掌握的知识点:
1:基础知识(变量,常量,函数,数组,字符串....)
以贴吧案例做的小结.
2:系统学习了Mysql
3:面向对象

开发商城:
1:微型框架 提高开发效率
{
数据库类
配置文件
Model层
日志功能
}

日志功能:是指把运行的sql语句,
已经错误记录,要能记录到日志文件里.

知识点: 文件操作

2:后台 栏目管理
无限级栏目:
需要知识点---递归

3:商品管理
功能要求: 上传商品+处理商品图片
知识点: 文件上传+gd库

4:前台用户登陆
功能要求:登陆,注册,记住用户名
知识点:session+cookie

5:下订单
功能要求:购物车+订单功能
知识点:面向对象+单例做购物车

6: 在线支付订单
知识点:在线支付

项目驱动的方式,引出知识点.
