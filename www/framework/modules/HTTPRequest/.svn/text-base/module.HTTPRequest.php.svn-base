<?PHP

/**
* @author Lee Yong Uk (oneweb@vi-nyl.com)
*/

class HTTPRequest {
	/**
	* Format and sign an OAuth / API request
	*/
	public $http_code;
	public $url;
	public $host = "php.net";
	public $timeout = 60;
	public $connecttimeout = 60; 
	public $ssl_verifypeer = FALSE;
	public $format = 'json';
	public $decode_json = TRUE;
	public $http_info;
	public $useragent = 'HTTPRequest 0.31';
  
	public function __construct() 
	{
		$this->host = $_SERVER["HTTP_HOST"];
	}
	
	/**
	* Make an HTTP request
	*
	* @return API results
	*/
	public function load($url, $method, $postfields = NULL) 
	{
		$this->http_info = array();
		$ci = curl_init();
		/* Curl settings */
		curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
		curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
		curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ci, CURLOPT_HTTPHEADER, array('Expect:'));
		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
		curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));
		curl_setopt($ci, CURLOPT_HEADER, FALSE);

		switch ($method) {
		  case 'POST':
			curl_setopt($ci, CURLOPT_POST, TRUE);
			if (!empty($postfields)) {
			  curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
			}
			break;
		   case 'GET':
			    if (!empty($postfields)) {
					$i = 0;
					foreach($postfields as $key => $value) { 
				        $i++; 
				        if($i == 1) { $url .= "?"; } 
				        else { $url .= "&amp;"; } 
				        $url .= $key."=".urlencode($value); 
				    }
				}
			break;
		  case 'DELETE':
			curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
			if (!empty($postfields)) {
			  $url = "{$url}?{$postfields}";
			}
		}

		curl_setopt($ci, CURLOPT_URL, $url);
		$response = curl_exec($ci);
		$this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
		$this->http_info = array_merge($this->http_info, curl_getinfo($ci));
		$this->url = $url;
		curl_close ($ci);
		return $response;
	}

	public function submit($url, $postfields=NULL, $header=NULL, $cookie=NULL)
	{
		$this->url = $url;
		
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_HEADER, $header);
	    curl_setopt($ch, CURLOPT_NOBODY, $header);
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
		curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
	    curl_setopt($ch, CURLOPT_COOKIE, $cookie);
	    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	 
	    if ($postfields) {
	        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	        curl_setopt($ch, CURLOPT_POST, 1);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
	    }
		
	    $result = curl_exec($ch);
		
		if ( !$result ) {
			return curl_error($ch);
		}
		
	    curl_close($ch);
		
		return $result;
	}

	/**
	* Get the header info to store.
	*/
	protected function getHeader($ch, $header) 
	{
		$i = strpos($header, ':');
		if (!empty($i)) {
			$key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
			$value = trim(substr($header, $i + 2));
			$this->http_header[$key] = $value;
		}
		return strlen($header);
	}
}
?>
