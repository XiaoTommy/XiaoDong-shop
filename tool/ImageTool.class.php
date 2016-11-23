<?php 
/**
图片操作类
	首先使用 getimagesize() 获取函数的宽高
			 image_type_to_mime_type() 获取图片的后缀信息

	然后  水印就是把指定的图片（透明化copy）
			首先按照比例缩放 imagecopyresampled()
			然后copy到另一个画布上 imagecopymerge() , 制造水印效果

****/

class ImageTool{
	//imageinfo() 分析图片信息
	//return array()
	public static function imageInfo($image){
		//判断图片是否存在
		if (!file_exists($image)) {
		 	return false;
		 } 

		$info = getimagesize($image);
		/*
		getimagesize() 得到图片信息 
			如果不是图片，返回false
			getimagesize() 函数将测定任何 
			GIF，JPG，PNG，SWF，SWC，PSD，TIFF，BMP，IFF，JP2，JPX，JB2，JPC，XBM 或 WBMP 
			图像文件的大小并返回图像的尺寸以及文件类型和一个可以用于普通 HTML 文件中 IMG 标记中的
			 height/width 文本字符串
			Array
				(
				    [0] => 539   						宽
				    [1] => 810							高
				    [2] => 2  //---->代表的是JPG		图片类型（GIF/JPG/PNG....）
				    [3] => width="539" height="810"
				    [bits] => 8
				    [channels] => 3
				    [mime] => image/jpeg
				)
		*/
		
		 //得到图片信息
		if($info == false) {
		  	return false;
		} 

		 //$info 是一个数组，但是名字不好记忆，我赋值
		$img['width'] = $info[0];
		$img['height'] = $info[1];
		$img['ext'] = substr($info['mime'], strpos($info['mime'], '/')+1); //获取后缀 
									//$info[mime]// 会警告。应该加上''
		//$img['ext'] = end(explode('/', image_type_to_mime_type($info[2])));
		
		return $img;
	}

	/*
		加水印功能
		parn string $dst 待操作文件
		parn strinf $water 水印文件
		parn string $sava 保存文件，不填写默认为替换原始文件
					$alpha 透明度
					$pos = 0 1 2 3 分别是 左上 右上 右下 左下 
	*/
	public static function water($dst, $water, $save=NULL, $pos=2, $alpha=50){ //$dst 和 $water $save是路径加文件名形式的
		//保证文件存在
		if (!file_exists($dst) || !file_exists($water)) {
			
			return false;
		}

		//首先保证水印不能比待操作文件大
		$dinfo = self::imageInfo($dst);
		$winfo = self::imageInfo($water);
		
		if (($winfo['height'] > $dinfo['height']) || ($winfo['width'] > $dinfo['width'])) {
			return false;
		}

		//首先要写画布,取得创建画布的函数
		$dfunc = 'imagecreatefrom'.$dinfo['ext'];
		$wfunc = 'imagecreatefrom'.$winfo['ext'];

		if (!function_exists($dfunc) || !function_exists($wfunc)) {
			return false;
		}

		//动态加载函数创建画布
		$dim = $dfunc($dst);
		$wim = $wfunc($water); //创建水印画布

		//根据水印的位置，计算粘贴的坐标
		switch ($pos) {
			case 0: //左上角
				$posx = 0;
				$posy = 0;
				break;
			
			case 1: //右上角
				$posx = $dinfo['width'] - $winfo['width'];
				$posy = 0; 			//这里的posy因为写成了poxy，找了一个小时，槽！！！
				break;

			case 2: //右下角
				$posx = $dinfo['width'] - $winfo['width'];
				$posy = $dinfo['height'] - $winfo['height'];
				break;

			case 3: //左下角
				$posx = 0;
				$posy = $dinfo['height'] - $winfo['height'];
				break;
			
			default:
				echo "请输入0-4之间的数字"; 
				break;
		}


		//加水印
		imagecopymerge($dim, $wim, $posx, $posy, 0, 0, $winfo['width'], $winfo['height'], $alpha);

		//保存
		if (!$save) {
			$save = $dst;
			unlink($dst); //删除原图
		}

		$createfunc = 'image'.$dinfo['ext'];
		$createfunc($dim,$save);

		imagedestroy($dim);
		imagedestroy($wim);

		return true;
	}

	/**
		thumb 生成缩略图
		等比例缩放，两边留白
	**/
	public static function thumb($dst, $save=NULL, $width=200, $height=200){
		$dinfo = self::imageInfo($dst);
		
		//首先判断图片是否存在
		if ($dinfo == false) {
			return false;
		}

		//计算缩放比例
		$calc = min($width/$dinfo['width'], $height/$dinfo['height']);

		//创建原始画布
		$dfunc = 'imagecreatefrom'.$dinfo['ext'];
		$dim = $dfunc($dst);

		//创建缩略画布
		$tim = imagecreatetruecolor($width, $height);

		//创建白色填充缩略图
		$white = imagecolorallocate($tim, 255, 255, 255);

		//填充缩略画布
		imagefill($tim, 0, 0, $white);

		//复制并且缩略
		$dwidth = (int)$dinfo['width']*$calc;
		$dheight = (int)$dinfo['height']*$calc;

		$paddingx = (int)($width - $dwidth) / 2;
		$paddingy = (int)($height - $dheight) / 2;
		//imagecopyresampled(dst_image, src_image, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)
		imagecopyresampled($tim, $dim, $paddingx, $paddingy, 0, 0, $dwidth, $dheight, $dinfo['width'], $dinfo['height']);
	
		//保存图片
		if (!$save) {
			$save = $dst;
			unlink($dst);
		}

		$createfunc = 'image'. $dinfo['ext'];
		$createfunc($tim,$save);

		//销毁图片
		imagedestroy($dim);
		imagedestroy($tim);

		return true;
	} 

	//写验证码
    public static function captcha($width=50,$height=25) {
            //造画布
            $image = imagecreatetruecolor($width,$height) ;
           
            //造背影色
            $gray = imagecolorallocate($image, 200, 200, 200);
           
            //填充背景
            imagefill($image, 0, 0, $gray);
           
            //造随机字体颜色
            $color = imagecolorallocate($image, mt_rand(0, 125), mt_rand(0, 125), mt_rand(0, 125)) ;
            //造随机线条颜色
            $color1 =imagecolorallocate($image, mt_rand(100, 125), mt_rand(100, 125), mt_rand(100, 125));
            $color2 =imagecolorallocate($image, mt_rand(100, 125), mt_rand(100, 125), mt_rand(100, 125));
            $color3 =imagecolorallocate($image, mt_rand(100, 125), mt_rand(100, 125), mt_rand(100, 125));
           
            //在画布上画线
            imageline($image, mt_rand(0, 50), mt_rand(0, 25), mt_rand(0, 50), mt_rand(0, 25), $color1) ;
            imageline($image, mt_rand(0, 50), mt_rand(0, 20), mt_rand(0, 50), mt_rand(0, 20), $color2) ;
            imageline($image, mt_rand(0, 50), mt_rand(0, 20), mt_rand(0, 50), mt_rand(0, 20), $color3) ;
           
            //在画布上写字
            $text = substr(str_shuffle('ABCDEFGHIJKMNPRSTUVWXYZabcdefghijkmnprstuvwxyz23456789'), 0,4) ;
            imagestring($image, 5, 7, 5, $text, $color) ;
           
            //显示、销毁
            header('content-type: image/jpeg');
            imagejpeg($image);
            imagedestroy($image);
    }





}

//print_r(ImageTool::imageinfo('./01.jpg'));

/*
echo ImageTool::water('./01.jpg', './03.png', './1001.jpg', 0)?"OK":"fail";
echo ImageTool::water('./01.jpg', './03.png', './1002.jpg', 1)?"OK":"fail";
echo ImageTool::water('./01.jpg', './03.png', './1003.jpg', 2)?"OK":"fail";

echo ImageTool::water('./01.jpg', './03.png', './1005.jpg', 3)?"OK":"fail";
*/
// echo ImageTool::thumb('./01.jpg', './2001.jpg', 200, 200)?"OK":"fail";
// echo ImageTool::thumb('./01.jpg', './2002.jpg', 200, 400)?"OK":"fail";
// echo ImageTool::thumb('./01.jpg', './2003.jpg', 400, 200)?"OK":"fail";

?>