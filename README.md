5天完成一个简易的MVC框架制作的商城<br />
第一天，写了一部分框架<br />

	程序进入页面--index.php; 
	框架初始化文件--init.php; 
	连接数据库类--mysql.clss.php; 
	记录日志类--log.class.php; 
	配置文件--config.inc.php;
	接受配置文件数据--conf.class.php;
	连接数据库抽象类--db.class.php;
	常用函数类--lib_base.php;  //为了使得日志记录整齐规范
	
第二天，写了后台栏目增删改查，和一部分商品增删改查类和方法<br />
	1.首先和后台html配合，修改href 和src（由于相对路径和绝对路径的关系）<br />
	2.然后契合后台html页面(主要是连接)<br />
	
		主页面--index.php；
		左侧栏--left.php； 
		主体内容--main.php；
		顶部栏--top.php；
		底部内容--drag.php；
		
	3.Model层处理信息
	
		Model.class.php (模型处理类，作用是：1.后台的数据管理 2.自动过滤 3.自动填充 4.自动验证格式 5.报错 6.增删改查功)
		CateModel.class.php(栏目模型处理类，作用是：1.获取表全部数据 2.获取一行数据 3.递归查找子孙树 4.迭代查找家谱树 5.删除栏目 6.更新栏目)
		GoodsModel.class.php(商品模型处理类，作用是：1.提供需要过滤的数组 2.提供需要自动填充的数组 3.提供需要自动验证格式的数组 4.回收站功能 5.自动创建货号功能 6.添加图片)
		
	4.admin层处理信息
	
		cateadd.php 和 cateaddAct.php（接受检测数据，使用model中的add方法）
		catedit.php 和 cateditAct.php (接受检测数据，分析数据，然后调用model中的update方法)
		catedel.php (接受检测数据，分析数据，调用model中的delete)
		catelist.php(实例化CatModel，并在html页面显示)

		goodslist.php（实例化GoodsModel，并在html页面显示）
		goodsadd.php  和 goodsaddAct.php（实例化GoodsModel，调用Model中的自动过滤，填充，验证格式、自动添加货号）
		goodstrash.php(商品回收站页面)
		goods.php（实例化GoodsModel，点击时查看商品数据）
		
第3天 常用的工具类 <br />
	1.文件上传类<br />
		文件上传类通过 $_FILES 这个全局变量通过 POST 过来的文件的绝对地址获取文件的内容，使用move_uploaded_file 来实现文件的移动；<br />
		上传文件应该有的功能是：检查时候上传成功以及失败的错误信息、检查后缀、检查大小、移动文件、生成随机文件名、按照日期生成目录等<br />
	2.图片处理类 <br />
		主要是运用GD库的知识，主要有 1.生成缩略图 2.添加水印 3.验证码功能<br />
		
			1.创建画布 imagecreatefromjpeg(filename) 以图片作为画布
				  imagecreatetruecolor(width, height)	纯色画布
			2.创建颜料 imagecolorallocate(image, red, green, blue)
			3.画布填充 imagefill(image, x, y, color)
			4.画线、矩形、椭圆、验证码
				画线 imageline(image, x1, y1, x2, y2, color)
				画矩形 imagerectangle(image, x1, y1, x2, y2, color)
				画椭圆 imageellipse(image, cx, cy, width, height, color)
				字符串 imagestring(image, font, x, y, string, color)
				中文 imagettftext(image, size, angle, x, y, color, fontfile, text)
			5.保存图片 
				header('content-type: image/jpeg');
				imagejpeg(image,'./xx.jpeg' )
			6.销毁画布 imagedestroy(image)
			
第4天 常用的工具类<br />
	3.分页类<br />
		主要是把商品总条数、每页条数、当前页这关系搞清楚，然后要确保地址栏原始信息的保存，只能改变页码，之前的不能改变。<br />
		结合数据库的group by 和 limit(m, n) 来实现进行分页。<br />
	4.购物车类<br />
		主要是储存在session中+单例模式，然后是购物车的一系列增删改查，判断总数总金额<br />
<br />
第五天 实现用户注册、用户支付、后台与前台页面的相互配合<br />
	


