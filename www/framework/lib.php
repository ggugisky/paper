<?php
###############################################
# This file is library of functions
# @2011-06-21
###############################################

//개체 상세 보기 함수 
function showArray( $object ) 
{
	ob_start(); 
	print_r( $object ); 
	$print = ob_get_clean();
	$print = htmlspecialchars( $print );
  
	echo sprintf( "<pre>%s</pre>", $print );

}

//결과 리턴 공통 
function setResult($flag,$msg="",$objData=""){
		$result["RESEULT_SET"]["flag"]=$flag;
		$result["RESEULT_SET"]["msg"]=$msg;
		$result["RESPONSE"]=$objData;
		return $flag;
		//return json_encode($result);
}

//컨텐츠 이미지 경로 변경 
function changeImgFromSource( $content,$storage_key,$content_key){
	try {
		$header  ="<html xmlns='http://www.w3.org/1999/xhtml'>\n";
		$header .="<head>\n";
		$header .="<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />\n";
		$header .="</head><body>\n";
		$footer ="\n</body></html>";
		$objData["content"] = $header.stripslashes($content).$footer;
		
		$objData["dom"] = new DOMDocument('1.0','UTF-8');
		$objData["dom"]->loadHTML( $objData["content"] );

		$objData["images"] = Array();
		$objData["imageElements"] =&$objData["dom"]->getElementsByTagName("img");
		if ( is_object( $objData["imageElements"] ) && count( $objData["imageElements"] ) > 0 ) {

			foreach( $objData["imageElements"] as $img ) {

				$imgSrc = $img->getAttribute( "src" );
				$new_name = md5($imgSrc);
				$new_url ="http://"._IMAGE_URL."/".$storage_key."/".$content_key."/".$new_name;	
				$objData["images"][] = Array(
					"url" => $imgSrc,
					"element" => $img,
					"new_url" => $new_url,
					"new_name" => $new_name
				);
				$img->setAttribute( "width", "80%");
				$img->setAttribute( "height","");
				if(!preg_match("/http:\/\/"._IMAGE_URL."\/".$storage_key."\/".$content_key."/imsU", $imgSrc, $matches)){
				//	$img->setAttribute( "src", $new_url);
				}
			}
		}
		$objData["has_video"]="F";
		$objData["videoElements"] =&$objData["dom"]->getElementsByTagName("iframe");
		if ( is_object( $objData["videoElements"] ) && count( $objData["videoElements"] ) > 0 ) {
			foreach( $objData["videoElements"] as $img ) {
				$videoSrc = $img->getAttribute( "src" );
				$objData["has_video"] = preg_match("/youtube/imsU",$videoSrc) ? "T" : "F";
				if($objData["has_video"]=="T") break;
			}
		}

		$content = $objData["dom"]->saveHTML();
		$content_arr = explode("<body>",$content);
		$content_arr = explode("</body>",$content_arr[1]);
		$content_result = $content_arr[0];

		$objData["article_content"] = trim($content_result);
		return $objData;


		//html_entity_decode($objData["article_content"], ENT_QUOTES, 'UTF-8');
	}
	catch( Exception $e ) {
		$objData["err_msg"] = $e->getMessage();
		$objData["msg"] = "";//$e->getMessage();
		//
		return false;
	}
    
	return true;
}


function consol($msg){
		$msg = addslashes($msg);
		$command=sprintf("echo \"%s\" 1>&2",
						 $msg
						);
		system($command);
}

function console($msg) {
	$msg = addslashes($msg);
	$command = sprintf("echo \"%s\" 1>&2", $msg );
	system($command);
}

function debug($service,$return="",$spend_time=0,$result="F"){
	foreach($_REQUEST as $key =>$val){
		$param .= "Request[$key] :".$val."\n";
	}

	if(is_array($return)){
		foreach($return as $key =>$val){
			$val = strlen($val) > 32 ? "string(".strlen($val).")" : $val;
			$val = is_array($val) ? "array(".count($val).")" : $val;
			$mem_data .= "memcache_data[$key] :".$val."\n";
		}
	}
	$spend_time = round($spend_time,4);
	//detail display log
	$str  = "==============================================\n";
	$str .= "Service: ".$service."\n";
	$str .= "Date   : ".date("Y-m-d (H:i:s)")."\n";
	$str .= "During Time   : ".$spend_time." s\n";
	$str .= "Result   : ".$result." \n";
	$str .= $param;
	$str .= "----------------------------------------------\n";
	$str .= $mem_data;
	$str .= "==============================================\n";
	consol($str);

	//write short log 
	$log =sprintf("echo '%s:%d:%s:%s' >> %s/access_log",
				  $service,
				  time(),
				  abs($spend_time),
				  $result,
				  _LOG
				 );
	$write_log=`$log`;
	
	$detail_log = sprintf('echo "%s" >> %s/detail_log',$str,_LOG);
	$write_log = `$detail_log`;
}

/*
-----------------------------------------------------------------------------------------
return (unix timestamp) tstampToTime("각포맷별날짜값","ISO8601 | RFC 2822");
-----------------------------------------------------------------------------------------
*/ 
function tstampToTime($tstamp , $format = "MYSQL" ) { 
        
    // VIABOOK : 1984-09-01 14:21:31 (viabook db)
    // ISO8601 : 1984-09-01T14:21:31Z (facebook)
    // RFC 2822 : Thu, 21 Dec 2000 16:01:07 +0200 
    // TWITTER : Mon May 02 11:12:02 +0000 2011
    $MONTH_TYPE = array("Jan"=>"1" , "Feb"=>"2", "Mar"=>"3","Apr"=>"4", "May"=>"5", "Jun"=>"6", 
                  "Jul"=>"7","Aug"=>"8","Sep"=>"9","Oct"=>"10","Nov"=>"11","Dec"=>"12");
    if($format == "MYSQL") sscanf($tstamp,"%u-%u-%u %u:%u:%u",$year,$month,$day,$hour,$min,$sec);
    if($format == "ISO8601") sscanf($tstamp,"%u-%u-%uT%u:%u:%uZ",$year,$month,$day,$hour,$min,$sec);
    if($format == "RFC2822") sscanf($tstamp,"%u, %u %u %u %u:%u:%u +%u",$date,$day,$month,$year,$hour,$min,$sec,$zone);
    if($format == "TWITTER") sscanf($tstamp,"%s %s %d %d:%d:%d +%d %d",$date,$month,$day,$hour,$min,$sec,$zone,$year);

    $month = is_numeric($month) ? $month : $MONTH_TYPE[$month];
    $hour = $format == "MYSQL" ? $hour : $hour+9;
    $newtstamp=mktime($hour,$min,$sec,$month,$day,$year);
    return $newtstamp;
}

?>
