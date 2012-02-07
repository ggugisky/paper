<?php   header("Content-Type: text/html; charset=UTF-8");   ?>
<?
class GoogleReader extends WebClass_Model {
	//configuration options
	public $userAgent = 'tuts+rss+bot';
	public $proxy = 0;
	public $proxyUrl = '';
   
	//base urls for api access
	protected $_urlBase = 'https://www.google.com';
	protected $_urlApi = 'http://www.google.com/reader/api/0';
	protected $_urlAuth = 'https://www.google.com/accounts/ClientLogin';
	protected $_urlToken = 'https://www.google.com/reader/api/0/token';
	protected $_urlUserInfo = 'https://www.google.com/reader/api/0/user-info';
	protected $_urlTag = 'https://www.google.com/reader/api/0/tag';
	protected $_urlSubscription = 'https://www.google.com/reader/api/0/subscription';
	protected $_urlStream = 'https://www.google.com/reader/api/0/stream';
	protected $_urlFriend = 'https://www.google.com/reader/api/0/friend';
	protected $_searchFEED = "http://www.google.com/reader/view/user/-/label/important/#search/hello/";					//hello 피드 검색
	
	//object to store logged in user information
	public $userInfo = '';
	
	// variables for authentication
	protected $auth = '';
	protected $token = '';
   	
	
	public function __init($email, $pass){
		$loginSet = array('accountType' => 'GOOGLE', 'Email' => $email,
        					'Passwd' => $pass, 'source'=>'PHP-cUrl-GooleLogin', 'service'=>'reader', 'continue'=>'http://www.google.com/');
        $this->setAuth($loginSet);
	}
	
	protected function setAuth($loginSet){
		if (isset($_SESSION['refresh'])) {
			if ($_SESSION['refresh'] >= time()) { $refresh = 0; } else { $refresh = 1; }
		} else {
			$refresh = 1;
		}
		
		if ($refresh == 0) {
			//pull the auth and userinfo from the session
			$this->auth = $_SESSION['AUTH'];
			$this->token = $_SESSION['TOKEN'];
			$this->userInfo = $_SESSION['userInfo'];
		} else {
			// use the post_anon_url to authenticate against googles authentication service
			$result = $this->postLoginURL($this->_urlAuth, $loginSet);
			
			// Get the Auth token from the results
			preg_match('/Auth=(\S*)/', $result, $match);
			$this->auth = $match[1];
			// grab the write token for use in editting the content in google reader
			$this->token = $this->getURL($this->_urlToken);
			
			// get user information for use later
			$this->userInfo = json_decode($this->getURL($this->_urlUserInfo), true);
			// save it all to session variables so we don't have to auth again.
			$_SESSION['AUTH'] = $this->auth;
			$_SESSION['TOKEN'] = $this->token;
			$_SESSION['userInfo'] = $this->userInfo;
			// set the timeout to reauth to 5 minutes
			$_SESSION['refresh'] = time() + 300;
		}		
	}

   protected function postLoginURL($url, $loginSet) {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);      
      curl_setopt ($ch, CURLOPT_POST, true);
      if ($this->proxy == 1) { curl_setopt($ch, CURLOPT_PROXY, $this->proxyUrl); }
      curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt ($ch, CURLOPT_POSTFIELDS, $loginSet);
      curl_setopt ($ch, CURLOPT_USERAGENT, $this->userAgent);
      curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
      $result = curl_exec($ch);
      curl_close($ch);
      return $result;
   }
   
   protected function getURL($url) {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);      
      if ($this->proxy == 1) { curl_setopt($ch, CURLOPT_PROXY, $this->proxyUrl); }
      curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt ($ch, CURLOPT_HTTPHEADER, array('Authorization: GoogleLogin auth=' . $this->auth));
      curl_setopt ($ch, CURLOPT_USERAGENT, $this->userAgent);
      curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
      $result = curl_exec($ch);
      $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);
      return $result;
   }
   
   //Subscriptions 내용 보기
   public function getSubscriptions() {
   	
	  $this->__init($_SESSION["google_id"], $_SESSION["google_pw"]);
      $result = $this->getURL($this->_urlSubscription.'/list?output=json');
	  return json_decode($result, true);
   }
   
   //태그 리스트 + 사용자 정의 태그 리스트  보기
   function getTags() {
      $result = $this->getURL($this->_urlTag.'/list?output=json');
      return json_decode($result, true);
   }
   
   //친구 리스트(관심 등록 사용자)
   function getFriends() {
      $result = $this->getURL($this->_urlFriend.'/list?output=json');
      return json_decode($result, true);
   }
   
	function getTagStream($id){
		$url = $this->_urlStream."/contents/".$id;
		$result = $this->getURL($url);
		return json_decode($result, true);
	}
	
	function getContents($url){
		$result = $this->getURL($url);
		return json_decode($result, true);
	}
	
	function getAtomContents($url){
		return $this->getURL($url);
	}
	
	function checkLogin( &$objData ){
		$objData["result"] = "F";
		$this->__init( $objData["email"], $objData["pass"]);
		
		$result = $this->userInfo;
		if($result != 0){
			$objData["result"] = "T";
			$_SESSION["google_id"] = $objData["email"];
			$_SESSION["google_pw"] = $objData["pass"];
			$objData["userInfo"] = $this->userInfo;
		}		
	}
		//구글피드 검색
	public function findGoogleFeeds( &$objData ){
		$q = urlEncode(iConv("EUC-KR", "UTF-8", $_REQUEST["q"]));
		$url = "http://www.google.com/reader/api/0/feed-finder?q=".$q."&output=json";			//피드 검색		json, atom4
		return $this->getContents($url);
		//echo json_decode($result);
		//return json_decode($result, true);
	}
}
?>
<?
	/*$objData = Array();
	$objData["service"] = $_REQUEST["service"];
	$objData["email"] = $_REQUEST['user_name'];
	$objData["pass"] = $_REQUEST['user_password'];
	
	//$reader = new GoogleReader("piyonoky@gmail.com", "oz7a8q9a4s5d6o");
	
	$reader = new GoogleReader($objData['email'], $objData['pass']);
	switch( strtoupper( $objData["service"] )){
		case "CHECK_LOGIN":{
			//http://viabookdev.net/api/api.googleReader.php?service=CHECK_LOGIN&user_name=piyonoky@gmail.com&user_password=oz7a8q9a4s5d6o
			echo urldecode( json_encode( $reader ) );
		}break;
		
		case "SEARCH_FEED":{
			//http://viabookdev.net/api/api.googleReader.php?service=SEARCH_FEED&q=apple
			$q = urlEncode(iConv("EUC-KR", "UTF-8", $_REQUEST["q"]));
			$url = "http://www.google.com/reader/api/0/feed-finder?q=".$q."&output=json";			//피드 검색		json, atom4
			$result = $reader->getContents($url);
			//echo urldecode( json_encode( $result ) );
			showArray($result);
		}break;
		
		
		case "_CHECK_LOGIN":{
			//http://viabookdev.net/api/api.googleReader.php?service=_CHECK_LOGIN&user_name=piyonoky@gmail.com&user_password=oz7a8q9a4s5d6o
			$temp = json_encode($reader);	
			$temp = json_decode($temp, true);
			$ra["count"] = count($temp["userInfo"]);

			$objData["result"] = "F";
			if(count($temp["userInfo"]) > 0){
				$objData["result"] = "T";
			}
			echo json_encode($objData);
		}break;
		
		default:{
		}break;
	}*/
	
	


/*echo "<br/><br/>";
showArray($result);*/


/*$aa = json_encode($result);
$bb = json_decode($aa, true);
echo count($bb['userInfo']);
//showArray($bb);*/

/*$reader = new GoogleReader();
$reader->__init("piyonoky@gmail.com", "oz7a8q9a4s5d6o");
//$result = $reader->getFriends();					//친구 리스트 관심 등록 사용자
$result = $reader->getSubscriptions();			//Subscriptions 내용 보기
showArray($result); */

/*$result = $reader->getTags();						//태그 리스트 + 사용자 정의 태그 리스트  보기
$id = $result["tags"][4]["id"];						//해당 태그  리스트 보기						
$targetURL = $_urlStream."contents/".$id;
$result = $reader->getTagStream($id);
*/


/*
$url = "http://www.google.com/reader/api/0/feed-finder?q=".urlEncode("사진")."&output=json";			//피드 검색		json, atom4
$result = $reader->getContents($url);//*/

/*$url = "http://www.google.com/reader/api/0/stream/details?s=feed/http://www.boston.com/bigpicture/index.xml&output=json";		//피드에 대한 디테일 내용 json, xml
$result = $reader->getContents($url);//*/
//showArray($result);

/*$url = "http://www.google.com/reader/atom/feed/http://www.google.com/reader/atom/feed/http://chacherry.com/rss";				//피드 읽기
$result = $reader->getAtomContents($url);
echo $result;//*/
?>
