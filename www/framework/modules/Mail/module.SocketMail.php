<?php

define( "_ENTER", "\r\n" );

class SocketMail 
{
	var $socket = null;
	var $host = "smtp.mail.yahoo.co.kr";
	var $port = 25;
	var $errno = null;
	var $errstr = null;
	
	private function connect()
	{
		if ( $this->socket == null ) {
			$this->socket = &fsockopen($this->host, $this->port, &$this->errno, &$this->errstr, 30);
			
			$connect_host = $_SERVER[HTTP_HOST];
			
			fgets($this->socket, 128);  
	        fputs($this->socket, "ehlo $connect_host"._ENTER);  
	        fgets($this->socket, 128);
		}
	}
	
	private function close()
	{
		if ( $this->socket != null ) {
			@fclose($this->socket);
		}
	}
	
	private function encode2047($string) {
		return '=?utf-8?b?'.base64_encode($string).'?=';
	}
	
	private function encode( $string, $charset = "utf-8", $encode = true ) {
		if ( $encode == true ) {
			$string = $this->encode2047($string);
		}

		if ( $charset == "utf-8" ) {
			return $string;
		}
		
		return iconv( $charset, "utf-8", $string );
	}
	
	protected function init( $host, $port = 25 ) 
	{
		$this->host = $host;
		$this->port = $port;
		
		$this->connect();
	}
	
	public function __construct( $host, $port = 25 ) 
	{
		$this->init( $host, $port );
	}
	
	public function __destruct()
	{
		$this->close();
	}
	
	public function auth( $id, $pass ) 
	{
		if ( $this->socket ) {
			fputs($this->socket, "auth login"._ENTER); 
	        fgets($this->socket, 128); 
	        fputs($this->socket, base64_encode($id)._ENTER); 
	        fgets($this->socket, 128); 
	        fputs($this->socket, base64_encode($pass)._ENTER); 
	        fgets($this->socket, 128);
		}
	}
	
	public function send( $header, $body, $charset = "utf-8", $content_type = "text/html")
	{
		$returnvalue = Array();
		
	    if ( $this->socket ) {
	        fputs($this->socket, "mail from: <".$header[From].">"._ENTER);
	        $returnvalue[] = fgets($this->socket, 128);
	        
	        fputs($this->socket, "rcpt to: <".$header[To].">"._ENTER);
	        $returnvalue[] = fgets($this->socket, 128);
	        
	        fputs($this->socket, "data"._ENTER);
			$returnvalue[] = fgets($this->socket, 128);
	        
	        fputs($this->socket, "Return-Path: ".$header[From]._ENTER);  
	        $returnvalue[] = fgets($this->socket, 128);
	        
	        fputs($this->socket, "From: \"".$this->encode( $header[Name], $charset )."\" <".$header[From].">"._ENTER);
	        $returnvalue[] = fgets($this->socket, 128);
	        
	        fputs($this->socket, "To: <".$header[To].">"._ENTER);
	        $returnvalue[] = fgets($this->socket, 128);
	        
	        fputs($this->socket, "Subject: ".$this->encode( $header[Subject], $charset )._ENTER);
	        $returnvalue[] = fgets($this->socket, 128);
	        
	        fputs($this->socket, "Content-Type: ".$content_type."; charset=\"utf-8\""._ENTER);  
	       	//fputs($this->socket, "Content-Transfer-Encoding: base64"._ENTER);  
	        $returnvalue[] = fgets($this->socket, 128);
	        
	        fputs($this->socket, _ENTER); 
			$returnvalue[] = fgets($this->socket, 128);
	        
	        // $message= chunk_split(base64_encode());  
	        fputs($this->socket, $this->encode( $body, $charset, false ) );    
	        //$returnvalue[] = fgets($this->socket, 128);
	        fputs($this->socket, _ENTER);    
	        $returnvalue[] = fgets($this->socket, 128);
	        
	        fputs($this->socket, _ENTER."."._ENTER);  
	        $returnvalue[] = fgets($this->socket, 128);
	    }
		else {
			return false;
		}

		$this->close();
		
		//if ( preg_match("^250", $returnvalue[0]) && preg_match("^250", $returnvalue[1]) && preg_match("^250", $returnvalue[10]) ) { 
        //    return true;
       // }
		return true;
		//return false;
	}
}

?>
