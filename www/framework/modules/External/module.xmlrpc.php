<?php
require_once( _MODULES.'/External/xmlrpc/xmlrpc.php');

class API_XMLRPC{
	const NAVER_BLOG_API_URL = "https://api.blog.naver.com/xmlrpc";
	const KEY_WORD = "viabook,비아북";
	var $connection;
	var $service;
	public $access_token;
	public $http="";
	public $data=array();

	public function __construct($access_token=""){
		$GLOBALS['xmlrpc_internalencoding'] = 'UTF-8';
		$this->connection = new xmlrpc_client(self::NAVER_BLOG_API_URL);
		// 인증서 체크 안함.
		$this->connection->setSSLVerifyPeer(0);
	}

 	/**
	 * 객체에 블로그ID 및 블로그 API KEY 값 등록함
	 * null ={access_token}
	 *
	 **/
	public function setToken($access_token){
		$this->access_token = $access_token;    
		$temp = explode("|",$access_token);
		$this->blog_id = trim($temp[0]);
		$this->blog_api_key = trim($temp[1]);
	}

 	/**
	 * 블로그 카테고리 값 배열로 리턴
	 **/
	public function getCategories($access_token){
		$this->setToken($access_token);
		//카테고리 정보 추출 시작
		$blog_message = new xmlrpcmsg("metaWeblog.getCategories",
			array( 
				new xmlrpcval($this->blog_id, "string"),
				new xmlrpcval($this->blog_id, "string"),
				new xmlrpcval($this->blog_api_key, "string"),
			)
		);
		$blog_message->request_charset_encoding = 'UTF-8';

		$response = $this->connection->send($blog_message);
		$result = $response->value(); //카테고리 struct 반환
		for ( $i = 0 ; $i < $result->arraysize() ; $i++ ) { 
			$categories[$i] = $result->arraymem($i)->me['struct']['title']->me['string']; //카테고리 타이틀 배열로 생성
		}
		$data[]=$categories;
		return $data;
	}

 	/**
	 * 블로그 새글 등록
	 **/
	public function newPost( $access_token, $title, $message ){
		$this->setToken($access_token);
		// 카테고리 정보 추출
		$result	= $this->getCategories($access_token);

		// 등록 메시지 설정
		$content = array(
			'title'   => new xmlrpcval($title, "string"),
			'description'   => new xmlrpcval($message, "string"),
			'dateCreated'  => new xmlrpcval(date("Ymd")."T".date("H:i:s"), "dateTime.iso8601"),
			'categories' => new XMLRPCval(array(new XMLRPCval($cate,"string")), "array"),
			'mt_keywords' => new xmlrpcval(self::KEY_WORD)
		);

		$blog_message = new xmlrpcmsg("metaWeblog.newPost",
			array( 
				new xmlrpcval($this->blog_id, "string"),
				new xmlrpcval($this->blog_id, "string"),
				new xmlrpcval($this->blog_api_key, "string"),
				new xmlrpcval($content, "struct"),
				new xmlrpcval(true, "boolean")
			)
		);
		$blog_message->request_charset_encoding = 'UTF-8';

		$response = $this->connection->send($blog_message);
		$result = $response->value();

		return $result;
	}
}
?>