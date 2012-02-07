<?PHP
require_once( _MODULES.'/External/facebook/facebook.php');
require_once( _MODULES.'/HTTPRequest/module.HTTPRequest.php');
class API_FACEBOOK{
	/*
	const APP_ID = '110805222333799';
	const APP_SECRET = '9dd32a5aed8dc12819f1593c047049e2';
	const APP_ID = '192496164131982';
	const APP_SECRET = 'bd9bd61edb90801c83818e6b316c4a10';
	*/
	const APP_ID = '196084300430677';
	const APP_SECRET = 'ff90c3370a99cd11dfbd682433622f3a';
	const OAUTH_URL = '/common.php';
	const PERMISSION = 'user_photo_video_tags,friends_photos,read_insights,user_interests,user_photos,friends_online_presence,user_online_presence,read_stream,offline_access,publish_stream,email,share_item,user_birthday';
	var $connection;
	var $service;
	public $access_token;
	public $http="";
	public $data=array();
	public $callback_url;
	public $_DOMAIN;
	

	public function __construct($access_token="") {

		$this->_DOMAIN = $_SERVER["HTTP_HOST"];
		$this->callback_url = '/index.php?service=view_common_getFacebook';
		$this->connection = new Facebook(array( 'appId' => self::APP_ID, 
												'secret' => self::APP_SECRET, 
												'cookie' => true)
		); 
		$this->setToken($access_token);
	}

 	/**
	 * 객체에 토큰 값 & FUID 값 등록함
	 * null ={access_token}
	 *
	 **/	
	public function setToken($access_token){
		$this->access_token = $access_token;    
		$temp = explode("|",$access_token);
		$temp = explode("-",$temp[1]);
		$this->user_id = trim($temp[1]);
	}

 	/**
	 * app 의 허용 여부 및 토큰 생성 여부를 생성함
	 * 반드시 팝업 형태로 사용해야함.
	 * null ={}
	 *
	 **/	
	public function checkAllowAccess($callback_url="", $is_mobile = false,$oauth_url=""){
		$code = $_REQUEST["code"];
		if(empty($code)) {
			if( $is_mobile == true ){
				$dialog_url  = "https://m.facebook.com/dialog/oauth?client_id=";
			}else{
				$dialog_url  = "https://www.facebook.com/dialog/oauth?client_id=";
			}
			$dialog_url .= self::APP_ID . "&redirect_uri=" . urlencode("http://".$this->_DOMAIN.$oauth_url);
			$dialog_url .= "&scope=".self::PERMISSION;
			header("Location:".$dialog_url);
			die();
		}

		$token_url  = "https://graph.facebook.com/oauth/access_token?client_id=";
		$token_url .= self::APP_ID . "&redirect_uri=" . urlencode("http://".$this->_DOMAIN.$oauth_url) . "&client_secret=";
		$token_url .= self::APP_SECRET . "&code=" . $code;
        $http = new HTTPRequest();
		$access_token = $http->submit($token_url);
        showArray($access_token);
		$this->callback_url = empty($callback_url) ? $this->callback_url : $callback_url;
		$callback_url = "http://".$this->_DOMAIN.$this->callback_url."&" . $access_token;
		header("Location:".$callback_url);
	}

 	/**
	 * 담벼락에 message 를 입력함
	 * json ={ str messgage, str access_token }
	 *
	 **/	
	public function post($access_token , $message , $options){
		$this->setToken($access_token);
		$values = $options;
		$values["access_token"] = $this->access_token;
		$values["message"] = $message;
		$result = $this->connection->api('me/feed', 
							   'POST', 
							   $values 
		);
		return $result;
	
	}

 	/**
	 * 담벼락에 있는 내용을 import
	 * array ={ str access_token, enum source_position, array source_type }
	 * -source_position : [newFeed | wall]
	 *
	 * -source_type : array("text","photo","video","link")
	 **/	
	public function correctFeed(
								$access_token="",
								$source_position = "wall", 
								$source_type=array("text","photo","video","link") 
							   ){
		!empty($access_token) ? $this->setToken($access_token) : $this->access_token;
		if(empty($this->access_token)) return array();
		$fbMethod['access_token'] = $this->access_token;
		$fbMethod["method"] = "fql.multiquery"; 
		$lastTime = mktime(0,0,0,date("m"),date("d"),date("Y"));
		$query["query1"]  = "SELECT source_id,  actor_id , message, type ,likes , attachment, created_time,comments FROM stream ";
		$query["query1"] .= "WHERE ";
		$query["query1"] .= "created_time >= ".$lastTime." and ";
		$query["query1"] .= "filter_key in ";
		$query["query1"] .= "(SELECT filter_key FROM stream_filter ";
		$query["query1"] .= "WHERE uid=".$this->user_id." AND type='newsfeed')";
		$query["query2"] = "select uid , name , pic_square from user where uid IN (select actor_id from #query1)";
		$fbMethod["queries"] = json_encode($query);
		$result = $this->connection->api($fbMethod);
		$result_contents = $result[0]["fql_result_set"];
        showArray($result);
		$result_user = $result[1]["fql_result_set"];
		for($i=0;$i<count($result_user);$i++){
			$userInfo[$result_user[$i]["uid"]] = $result_user[$i]; 	
		}
		$data = array();
		for($i=0;$i<count($result_contents);$i++){
			/*담벼락내용 추출*/	
			if($source_position=="wall" && $result_contents[$i]["source_id"] != $this->user_id ) continue;
			//if($result_contents[$i]["actor_id"] != $this->user_id && $result_contents[$i]["likes"]["user_likes"] !=1) continue;
			
			$temp["type"] = $result_contents[$i]["attachment"]["media"][0]["type"];
			$temp["type"] = empty($temp["type"])? "text" : $temp["type"];
			if(!in_array($temp["type"],$source_type)) continue;

			$url = array();
			switch($temp["type"]){
				case "text":
					break;
				case "link":
					array_push($url,$result_contents[$i]["attachment"]["href"]);
					break;
				case "video":
					array_push($url,$result_contents[$i]["attachment"]["media"][0]["video"]["source_url"]);
					break;
				case "photo":
					foreach($result_contents[$i]["attachment"]["media"] as $photo){
						array_push($url,str_replace("_s.","_n.",$photo["src"]));
					}
					break;
			}
			$temp["user_name"] = $userInfo[$result_contents[$i]["actor_id"]]["name"];
			$temp["user_thumb"] = $userInfo[$result_contents[$i]["actor_id"]]["pic_square"];
			$temp["message"] = $result_contents[$i]["message"];
			$temp["time"] = $result_contents[$i]["created_time"];
			$temp["media_description"] = $result_contents[$i]["attachment"]["description"];
			$temp["media_thumb"] = $result_contents[$i]["attachment"]["media"][0]["src"];
			$temp["url"] = $url;
			$temp["source"] = "Facebook";
			$temp["source_url"] = $result_contents[$i]["likes"]["href"];
			$temp["count"] = count($url);
			$temp["comments"] = $result_contents[$i]["comments"];
			$data[]=$temp;
		}
		return $data;
	}
	
 	/**
	 * graph 를 이용해서 위치에서 import
	 * param ={ str access_token , str $target , int $limit}
	 * $target =
	 *	참조 : http://developers.facebook.com/docs/reference/api/
	 * 
	 **/	
	public function graphGet($access_token,$target,$limit="100"){
		!empty($access_token) ? $this->setToken($access_token) : $this->access_token;
		$param = array(
								"access_token" => $this->access_token,
								"limit"	=>	$limit
						);
		return $this->connection->api($target,"GET",$param);
	}

 	/**
	 * fql 쿼리 를 이용해서 데이터를 import
	 * param ={ str access_token , arr $query}
	 *
	 **/	
	public function fqlGet($access_token,$query){
		!empty($access_token) ? $this->setToken($access_token) : $this->access_token;
		$fbMethod['access_token'] = $this->access_token;
		$fbMethod["method"] = "fql.multiquery"; 
		$fbMethod["queries"] = json_encode($query);
		return $this->connection->api($fbMethod);
		
	}

 	/**
	 * 로그아웃 페이지 호출
	 * null = {null}
	 *
	 **/	
	public function logout(){
		$logoutUrl = $this->connection->getLogoutUrl();
		$url=explode("?",$logoutUrl);
		$param = explode("&",$url[1]);
		$logoutUrl = $url[0]."?".$param[0];
		$logoutUrl .= "&access_token=".$_COOKIE['fb_access_token'];
		unset($_COOKIE['fb_access_token']);
		header("Location:".$logoutUrl);
		
	}

}

?>
