<?php

final class Context
{
	private static $instance;
	
	private $_post;
	private $_get;
	public $_request;
	public $_data;
	
	public function __construct() 
	{
		$this->_post = Array();
		$this->_get = Array();
		$this->_request = Array();
		$this->_data = Array();
		
		$this->init();
	}
	
	public static function getInstance() 
	{
		if ( ! isset(Context::$instance) ) {
			Context::$instance = new Context();
		}
		
		return Context::$instance;
	}
	
	protected function init() {
		foreach ( $_REQUEST as $key => $value ) {
			if ( isset( $_GET[$key] ) ) {
				$this->_get[$key] = $value;
				
				$this->_request[$key] = $value;
			}
			
			if ( isset( $_POST[$key] ) ) {
				$this->_post[$key] = $value;
				
				$this->_request[$key] = $value;
			}

			//$this->_request[$key] = $value;
		}

		
	}
	
	public function forceSetRequestWithObjects( $object )
	{
		foreach ( $object as $key => $value ) {
			$this->_request[$key] = $value;
		}
	}
	
	public function forceSetRequest( $key, $value )
	{
		$this->_request[$key] = $value;
	}
	
	public function forceRemoveRequest( $key )
	{
		if ( $this->hasRequestWithKey($key) ) {
			unset( $this->_request[$key] );
			unset( $_REQUEST[$key] );
		}
		
		if ( $this->hasGetWithKey($key) ) {
			unset( $this->_get[$key] );
			unset( $_GET[$key] );
		}
		
		if ( $this->hasPostWithKey($key) ) {
			unset( $this->_post[$key] );
			unset( $_POST[$key] );
		}
	}
	
	public function getRequest( $key = "", $method = "" ) 
	{
		if ( $method == "post" ) {
			$target = $this->_post;
		}
		else if ( $method == "get" ) {
			$target = $this->_get;	
		}
		else {
			$target = $this->_request;
		}
		
		if( is_array($key) ){
			foreach($key as $k){
				$temp[$k] = $target[$k];
			}
			return $temp;
		}

		if ( $key == "" ) {
			return $target;
		}
		
		return $target[$key];
	}
	
	public function getPost( $key = "" )
	{
		return $this->getRequest( $key, "post" ); 
	}
	
	public function hasRequestWithKey( $key )
	{
		return ( isset( $this->_request[$key] ) );
	}
	
	public function hasGetWithKey( $key )
	{
		return ( isset( $this->_get[$key] ) );
	}
	
	public function hasPostWithKey( $key )
	{
		return ( isset( $this->_post[$key] ) );
	}
	
	public function setData( $key, $value )
	{
		$this->_data[$key] = $value;
	}
	
	public function setDataFromRequest()
	{
		$this->setDataWithObjects( $this->getRequest() );
	}
	
	public function setDataWithObjects( $object )
	{
		if ( ! is_array( $object ) ) {
		//	return;
		}
		
		foreach ( $object as $key => $value ) {
			$this->_data[$key] = $value;
		}
	}
	
	public function getData( $key = "" ) 
	{
		if ( $key == "" ) {
			return $this->_data;
		}
		
		return $this->_data[$key];
	}
}
?>
