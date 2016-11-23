<?php 
/**

分页类

分页原理的 3 个变量
总条数 		$total
每页条数 	$perpage
当前页 		$page

2 个公式
总页数 			$cnt = ceil($ttal/$perpage)
第$page页显示  	($page-1)*$perpage +1 ---- ($page-1)*$perpage +1+$perpage

在分页导航中，地址栏中的 $page 应该根据页码来决定，但是同时不能把地址栏中的其他参数搞丢，
	所以先把地址栏中的地址获取并且保存起来


**/
defined('ACC') || exit('ACC Denied');

class PageTool{
	protected $total = 0;
	protected $perpage = 10;
	protected $page = 1;

	public function __construct($total, $page=false, $perpage=false){
		$this->total = $total;
		if ($perpage) {
			$this->perpage = $perpage;
		}

		if ($page) {
			$this->page = $page;
		}
	}

	//创建分页导航 	先保存地址栏，在分析
	public function show(){
		$cnt = ceil($this->total/$this->perpage); //总页数
		
		//保存地址栏信息
		$uri = $_SERVER['REQUEST_URI'];
		$parse = parse_url($uri); //parse_url — 解析 URL，返回其组成部分

		$param = array();
		if (isset($parse['query'])) { 
			parse_str($parse['query'], $param); //parse_str 解析字符串,此时 $param 中储存的就是地址栏中?之后的数据了
		}
		
		//print_r(parse_url($uri)); 
		//var_dump($param); 

		//不管$param中，有没有page单元，都要unset，确保没有page单元，保存除去page之外的单元
		unset($param['page']);

		$url = $parse['path'].'?';
		if (!empty($param)) {
			$param = http_build_query($param); //http_build_query()自动转换成地址栏格式
			$url = $url.$param.'&';
		}

		//计算页码导航
		$nav = array();
		$nav[0] ='<span class="page_now">'. $this->page .'</span>';

		for ($left = $this->page-1, $right = $this->page+1; ($left>=1 || $right<=$cnt) && count($nav)<5 ; ) { 
			if ($left >= 1) {
				array_unshift($nav, '<a href="'.$url . 'page=' . $left.'">['.$left.']</a>');
				$left -= 1;
			}	
			if ($right <= $cnt) {
				array_push($nav, '<a href="'.$url . 'page=' . $right. '">['.$right.']</a>');
				$right += 1;
			}
		}
		//print_r($nav);
		return implode('', $nav);
	}	
}

/*
分页类调用测试
new pagetool(商品总数, 当前页， 每页条数)
show() 返回分页代码

$page = $_GET['page']?$_GET['page']:1;
$p = new PageTool(200, $page, 6);
echo $p->show();
*/










?>