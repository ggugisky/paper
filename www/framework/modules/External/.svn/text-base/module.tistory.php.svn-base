<?PHP
require_once( _MODULES.'/HTTPRequest/module.HTTPRequest.php');
class API_TISTORY{



	public $_DOMAIN;
	public $domain;

	/// @brief 인증 코드 생성
	/// @author ggugisky 
	/// @param null
	/// @return json
	public function __construct(){
		$this->_DOMAIN = $_SERVER["HTTP_HOST"];
		$this->domain = sprintf("http://%s",$this->_DOMAIN);
		if($this->_DOMAIN == 'www.viabook.net'){
			$this->CONSUMER_KEY  ='13dfa7d54308b9dba2303c2f985ffcc5';	
			$this->CONSUMER_SECRET  ='35728de9773d5afc4e2dae09c2e8d39c63f619721d3c30357c3ed36948be9123ffaecb36';	
		}elseif($this->_DOMAIN == 'test.viabook.net'){
			$this->CONSUMER_KEY	='8697c086f244bcbdaf9c43a3fe105f02';
			$this->CONSUMER_SECRET	='cbbb859dfeaf81c864b2d817d124400769026287b2407fbe7e191ee86d6a5da8bc2015ee';
		}elseif($this->_DOMAIN == 'app.viabook.net'){
			$this->CONSUMER_KEY	='8f0cb917b75b1aed2d277cca9431adae';
			$this->CONSUMER_SECRET	='49d1b6919fc0b6d07a52a20f0422546a02dd9df0aa11cd00c964d4a47eae2f4a33428b60';
		}elseif($this->_DOMAIN == 'apptest.viabook.net'){
			$this->CONSUMER_KEY	='27ba3a34587c03860adfb2b250182da2';
			$this->CONSUMER_SECRET	='08a1722f6de2051a08c3059ceb959196090ed51856ecc9fc7e0c83eced07a7252596950a';
		}elseif($this->_DOMAIN == 'appdev.viabook.net'){
			$this->CONSUMER_KEY	='5d5f936b274c6dd2cc7243d60fda3d4c';
			$this->CONSUMER_SECRET	='bceb5645d9f03927ead9f043365d575f077b1c50af557877a967371f07a63dba468a3aac';

		}else{
			$this->CONSUMER_KEY	='6826537849ded9cc8b9f091f2dcf4b2b';
			$this->CONSUMER_SECRET	='3098ce8dfcc67b5b491f96e32345aca58b98d9effa59fa95613f455e876fd349a52019b8';
		}
		
	}

	/// @brief 인증 코드 생성
	/// @author ggugisky 
	/// @param null
	/// @return json
	public function checkAllowAccess($is_mobile){
		$oauth_url = "https://www.tistory.com/oauth/authorize/";
		$oauth_url.= "?client_id=".$this->CONSUMER_KEY;
		if(!$is_mobile){
			$oauth_url.= "&redirect_uri=".$this->domain."/API/MEMBER/SAVE_TISTORY_TOKEN/";
		}else{
			$oauth_url.= "&redirect_uri=".$this->domain."/API/APPLICATION/APP_SAVE_TISTORY_TOKEN";
		}
		$oauth_url.= "&response_type=code";
		header("Location:".$oauth_url);
	}

	/// @brief 인증 코드 생성
	/// @author ggugisky 
	/// @param null
	/// @return json
	public function createAccessToken($auth_code,$is_mobile){
		$grant_type = 'authorization_code';
		$url = 'https://www.tistory.com/oauth/access_token/';
		$params["code"]= $auth_code;
		$params["client_id"]= $this->CONSUMER_KEY; 
		$params["client_secret"]= $this->CONSUMER_SECRET; 
		if($this->_DOMAIN == "app.viabook.net" || $this->_DOMAIN == "apptest.viabook.net" || $this->_DOMAIN == "appdev.viabook.net"){
			$params["redirect_uri"]	= $this->domain."/API/APPLICATION/APP_SAVE_TISTORY_TOKEN";
		}else{
			$params["redirect_uri"] = $this->domain."/API/MEMBER/SAVE_TISTORY_TOKEN/";
		}
		$params["grant_type"]= $grant_type;
		$access_token = HTTPRequest::submit($url,$params);
		$access_token = explode("=",$access_token);
		return $access_token[1];
	}

	/// @brief 블로그 정보 가져오기
	/// @author ggugisky 
	/// @param access_token : 토큰값 , output="json"
	/// @return json
	public function infoApi($access_token,$output="json"){
		$url = "https://www.tistory.com/apis/blog/info";
		$params["access_token"] = $access_token;
		$params["output"] = $output;
		$request = new HTTPRequest();
		return $request->submit($url,$params);
	}

	/// @brief 블로그 목록 가져오기
	/// @author ggugisky 
	/// @param access_token : 토큰값 , output="json"
	/// @return json
	public function listApi($access_token,$targetUrl,$output="json",$page=1,$limit=30){
		$url = "https://www.tistory.com/apis/post/list";
		$params["access_token"] = $access_token;
		$params["targetUrl"] = $targetUrl;
		$params["output"] = $output;
		return HTTPRequest::submit($url,$params);
	}

}
?>
