<?php

/** ====================================================== 
| @Author	: 김종관 
| @Email	: apmsoft@gmail.com 
| @HomePage	: http://www.apmsoftax.com 
| @Editor	: Eclipse(default) 
| @UPDATE	: 2010-02-16 
----------------------------------------------------------*/ 

# purpose : 이미지 효과주기 
class ImageGD 
{ 
	private $filename; 
	
	private $im; 
	private $quality =100; 
	private $bgcolor = 0x7fffffff; 
	private $fontsrc,$fontangle=0,$fontcolor = array(0,0,0),$fontsize = 20,$x=5,$y=5; 
	
	# 시작 
	public function __construct($filename=null){ 
		if(!file_exists($filename) && $filename) 
		throw new Exception($filename); 
	
		$this->filename = $filename; 
	} 
	
	# void 퀄리티 설정 
	public function setCompressionQuality($quality){ 
		$this->quality = $quality; 
	} 
	
	# 칼라 채우기 
	public function setFilledrectangle($image,$x1,$y1,$x2,$y2,$color){ 
		if(false === ($im = imagefilledrectangle($image,$x1,$y1,$x2,$y2,$color))) return false; 
		return $im; 
	} 
	
	# 칼라 채우기 RGB 
	public function setColorallocate($image,$r,$g,$b){ 
		if(0 > ($im = imagecolorallocate($image,$r,$g,$b))) return false; 
		return $im; 
	} 

	# alpha 
	public function setAlphablending($image,$boolean=false){ 
		imagealphablending($image, $boolean); 
	}
	 
	# alpha 
	public function setSavealpha($image,$boolean=false){ 
		imagesavealpha($image, $boolean); 
	} 
	
	public function setFttext($image,$fontcolor,$text){ 
		imagefttext($image,$this->fontsize,$this->fontangle,$this->x,$this->y,$fontcolor,$this->fontsrc,$text); 
	} 

	# 폰트 파일 경로 지정 
	public function setFont($fontsrc){ $this->fontsrc = $fontsrc; } 
	
	# 칼라 지정 
	public function setFontColor($color){ $this->color = $color; } 
	
	# 폰트 사이즈 
	public function setFontSize($pixel){ $this->fontsize = $pixel; } 
	
	# 배경칼라 
	public function setBgColor($bgcolor){ $this->bgcolor = $bgcolor; } 
	
	# 폰트 앵글 
	public function setFontAngle($angle){ $this->fontangle = $angle; } 
	
	# x:y 축 
	public function setXY($x,$y){ $this->x = $x; $this->y = $y; } 

	# 텍스트 이미지 만들기 
	public function writeTextImage($width,$height,$text){ 
		$this->im = self::createTrueImage($width,$height); 
		self::setAlphablending($this->im); 
		self::setFilledrectangle($this->im,0,0,$width,$height,$this->bgcolor); 
		
		$fontcolor = self::setColorallocate($this->im,$this->fontcolor[0],$this->fontcolor[1],$this->fontcolor[2]); 
		self::setFttext($this->im,$fontcolor,$text); 
		self::setSavealpha($this->im,true); 
	} 
	
	public function setAntialias($image,$boolean=false){ 
		imageantialias($image,$boolean); 
	} 
	
	public function setTTFText($image,$size,$x,$y,$color,$text){ 
		imagettftext($image,$size,$this->fontangle,$x,$y,$color,$this->fontsrc,$text); 
	} 

	# 그림자 입체 텍스트 쓰기 
	public function writeShadowText($width,$height,$text,$bgRGB=array(255,255,255),$mdRGB=array(128,128,128),$frontRGB=array(0,0,0)) 
	{ 
		$this->im = self::createTrueImage($width,$height); 
		
		$bg = self::setColorallocate($this->im,$bgRGB[0],$bgRGB[1],$bgRGB[2]); 
		$middle = self::setColorallocate($this->im, $mdRGB[0],$mdRGB[1],$mdRGB[2]); 
		$front = self::setColorallocate($this->im, $frontRGB[0],$frontRGB[1],$frontRGB[2]); 
		self::setFilledrectangle($this->im,0,0,$width-1,$height-1,$bg); 
		
		// Add some shadow to the text 
		self::setTTFText($this->im,$this->fontsize,$this->x,$this->y,$middle,$text); 
		
		// Add the text 
		self::setTTFText($this->im,$this->fontsize,$this->x - 1,$this->y - 1,$front,$text); 
	} 

	# 이미지 위에 텍스트 쓰기 
	public function combineImageText($width,$height,$text,$filename=null){ 
		$this->im = self::createTrueImage($width,$height); 
		self::setAntialias($this->im,true); 
		$fontcolor = self::setColorallocate($this->im,$this->fontcolor[0],$this->fontcolor[1],$this->fontcolor[2]); 
	
		$filename = ($filename) ? $filename : $this->filename; 
		if(!$filename) throw new Exception(__CLASS__,':'.__METHOD__.':'.__LINE__); 
		$image = self::readImage($filename); 
		self::copy($this->im,$image,0,0,0,0,$width,$height); 
		self::setTTFText($this->im,$this->fontsize,$this->x,$this->y,$fontcolor,$text); 
	} 

	# margin_r : 오른쪽 여백, margin_b : 아래여백 
	public function filterWatermarks($marksfilename,$margin_r=10,$margin_b=10){ 
		if(!file_exists($marksfilename)) 
		
		throw new Exception(__CLASS__.':'.__METHOD__.':'.$marksfilename); 
		
		$this->im = self::readImage($this->filename); 
		self::setAntialias($this->im,true); 
		$image = self::readImage($marksfilename); 
		
		$width = imagesx($image); 
		$height = imagesy($image); 
		$im_x = imagesx($this->im) - $width - $marge_r; 
		$im_y = imagesy($this->im) - $height - $marge_b; 
		
		self::copy($this->im,$image,$im_x,$im_y,0,0,$width,$height); 
	} 

	# void 이미지 자르기 int width,height,x,y 
	public function cropImage($width,$height,$x,$y){ 
		$this->im = self::createTrueImage($width,$height); 
		$image = self::readImage($this->filename); 
		
		if(self::copy($this->im,$image,0,0,$x,$y,$width,$height) === false) 
		throw new Exception(__METHOD__); 
	} 

	# void 이미지 자르기 (center) int width,height 
	public function cropThumbnailImage($width,$height){ 
		$imgsize = self::getImageSize($this->filename); 
	
		# 조정 
		$im_x = 0; 
		$im_y = 0; 
		$image_x = 0; 
		$image_y = 0; 
		
		$wm = $imgsize->width/$width; 
		$hm = $imgsize->height/$height; 
		$h_height = $height/2; 
		$w_height = $width/2; 
		  
		if($imgsize->width > $imgsize->height){ 
		    $width = $imgsize->width / $hm; 
		    $half_width = $width / 2; 
		    $im_x = -($half_width - $w_height); 
		}else if(($imgsize->width <$imgsize->height) || ($imgsize->wdith == $imgsize->height)){ 
		    $height = $imgsize->height / $wm; 
		    $half_height = $height / 2; 
		    $im_y = $half_height - $h_height; 
		} 
		
		$this->im = self::createTrueImage($width,$height); 
		$image = self::readImage($this->filename); 
		if(self::copyResampled($this->im,$image,$im_x,$im_y,$image_x,$image_y,$width,$height,$imgsize->width,$imgsize->height) === false) 
		
		throw new Exception(__METHOD__); 
		
		return true; 
	} 

	# 썸네일 이미지 만들기 int width, height 
	public function thumbnailImage($width,$height){ 
		$imgsize = self::getImageSize($this->filename); 
		
		# 썸네일 사진 사이즈 설정 
		if($imgsize->width>$imgsize->height){ 
			$height= ceil(($imgsize->height*$width)/$imgsize->width); 
		} 
		else if($imgsize->width<$imgsize->height || $imgsize->width == $imgsize->height){      
			$width= ceil(($imgsize->width*$height)/$imgsize->height); 
		} 
		
		$this->im = self::createTrueImage($width,$height); 
		$image = self::readImage($this->filename); 
		
		if(self::copyResampled($this->im, $image, 0,0,0,0,$width,$height,$imgsize->width,$imgsize->height) ===false) 
		
		throw new Exception(__METHOD__); 
		
		return true; 
	} 

	# imagecopy 
	public function copy($im,$image,$im_x,$im_y,$image_x,$image_y,$width,$height)
	{ 
		if(imagecopy($im,$image,$im_x,$im_y,$image_x,$image_y,$width,$height) === false) return false; 
		return true; 
	} 

	# imagemerge 
	public function copyMerge($im,$image,$im_x,$im_y,$image_x,$image_y,$width,$height,$pct)
	{ 
		if(!imagecopymerge($im,$image,$im_x,$im_y,$image_x,$image_y,$width,$height,$pct)) return false; 
		return true; 
	} 

	# imagecopyresampled 
	public function copyResampled($im,$image,$im_x,$im_y,$image_x,$image_y,$width,$height,$oriwidth,$oriheight)
	{ 
		if(imagecopyresampled($im,$image,$im_x,$im_y,$image_x,$image_y,$width,$height,$oriwidth,$oriheight) ===false) return false; 
		return true; 
	} 

	# void : createtruecolor 
	public function createTrueImage($width,$height)
	{ 
		return $im = imagecreatetruecolor($width,$height); 
	} 
	
	# void 
	public function readImage($filename)
	{	
		$count	= strrpos($filename,'.'); 
		$extention = strtolower(substr($filename, $count+1)); 
		switch($extention){ 
			case 'gif': $image = imagecreatefromgif($filename); break; 
			case 'png': $image = imagecreatefrompng($filename); break; 
			case 'jpeg': 
			case 'jpg':	$image = imagecreatefromjpeg($filename); break; 
			default : throw new Exception('i can\'t the image format'); 
		} 
		return $image; 
	} 
	
	# string filename 
	public function write($filename)
	{
		$count	= strrpos($filename,'.'); 
		$extention = strtolower(substr($filename, $count+1)); 
		switch($extention){ 
		case 'gif': imagegif($this->im,$filename); return true; break; 
			case 'png': imagepng($this->im,$filename,($this->quality/10)-1); return true; break; 
			case 'jpg': 
			case 'jpeg': imagejpeg($this->im,$filename,$this->quality); return true; break; 
			default : return false; 
		} 
	} 

	# @ void : GD 버전 
	public function getVersion(){ 
		if(function_exists('gd_info')){ 
			$info = gd_info(); 
			return preg_replace('/bundled \((.*) compatible\)/','\\1', $info['GD Version']); 
		} 
		return false; 
	} 

	# 이미지 사이즈 
	public function getImageSize($filename=null)
	{ 
		$filename = ($filename) ? $filename : $this->filename; 
		$img_info = getImageSize($filename); 
		return json_decode(json_encode(array('width'=>$img_info[0],'height'=>$img_info[1],'mime'=>$img_info['mime']))); 
	} 

	# @ void 
	public function destroy()
	{ 
		if(is_resource($this->im)) imagedestroy($this->im); 
	} 
	
	public function __destruct(){ 
	    self::destroy(); 
	} 
/*
	//cropImage(225, 165, 3, 5, 'jpg', '/path/to/source/image.jpg', '/path/to/dest/image.jpg');
	public static function cropImage($x, $y, $w, $h, $type, $source, $dest = null){
		$filename = $source;
		switch($type) {
	        case 'gif':
	        $image = imagecreatefromgif($source);
	        break;
	        case 'jpg':
	        $image = imagecreatefromjpeg($source);
	        break;
	        case 'png':
	        $image = imagecreatefrompng($source);
	        break;
	    }
	    $crop = imagecreatetruecolor($w, $h);
		imagecopy ( $crop, $image, 0, 0, $x, $y, $w, $h );
		if(!isSet($dest)) $dest = $source;
		imagejpeg($crop, $dest, 100);	
	}


	public static function cropResizeImage($nw, $nh, $source, $stype, $dest) {
	    $size = getimagesize($source);
	    $w = $size[0];
	    $h = $size[1];
	 
	    switch($stype) {
	        case 'gif':
	        $simg = imagecreatefromgif($source);
	        break;
	        case 'jpg':
	        $simg = imagecreatefromjpeg($source);
	        break;
	        case 'png':
	        $simg = imagecreatefrompng($source);
	        break;
	    }
	    $dimg = imagecreatetruecolor($nw, $nh);
	 
	    $wm = $w/$nw;
	    $hm = $h/$nh;
	 
	    $h_height = $nh/2;
	    $w_height = $nw/2;
	 
	    if($w> $h) {
	        $adjusted_width = $w / $hm;
	        $half_width = $adjusted_width / 2;
	        $int_width = $half_width - $w_height;
	        imagecopyresampled($dimg,$simg,-$int_width,0,0,0,$adjusted_width,$nh,$w,$h);
	    } elseif(($w <$h) || ($w == $h)) {
	 
	        $adjusted_height = $h / $wm;
	        $half_height = $adjusted_height / 2;
	        $int_height = $half_height - $h_height;
	        imagecopyresampled($dimg,$simg,0,-$int_height,0,0,$nw,$adjusted_height,$w,$h);
	    } else {
	        imagecopyresampled($dimg,$simg,0,0,0,0,$nw,$nh,$w,$h);
	    }
	    imagejpeg($dimg,$dest,100);
	} 
 * */
}
