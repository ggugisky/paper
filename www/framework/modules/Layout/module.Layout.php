<?php
require_once ( _MODULES.'/Template/module.Template.php' );

final class Layout extends Template
{
	private $site_title = '';
	private $js_files = array();
	private $css_files = array();
	private $html_header = '';
	private $html_footer = '';

	public function __construct( $root="", $site_name = "" )
	{
		parent::__construct( $root );

		$this->site_title = ( $site_name != "" ) ? $site_name : "";
	}

	public function setLayout( &$objData ) 
	{	
		if ( $objData["layout_file"] != '' )
		{
			$this->define( "LAYOUT", $objData["layout_file"] );
		}
	}

	public function setBrowserTitle( $title )
	{
		$this->site_title = $title;
	}

	public function getBrowserTitle()
	{
		return $this->site_title;
	}

	public function addJsFile( $file )
	{
		if ( !in_array( trim($file), $this->js_files ) )
		{
			array_push( $this->js_files, trim($file) );
		}
		//$this->js_files[] = $file;
	}

	public function addCSSFile( $file )
	{
		if ( !in_array( trim($file), $this->css_files ) )
		{
			array_push( $this->css_files, trim($file) );
		}
		//$this->css_files[] = $file;
	}

	private function uniqueArray($myArray) { 
		if(!is_array($myArray)) 
			   return $myArray; 

		foreach ($myArray as &$myvalue){ 
			$myvalue=serialize($myvalue); 
		} 

		$myArray=array_unique($myArray); 

		foreach ($myArray as &$myvalue){ 
			$myvalue=unserialize($myvalue); 
		} 

		return $myArray; 

	}

	public function getHeader()
	{
		$this->uniqueArray( $this->js_files );
		$this->uniqueArray( $this->css_files );
		
		//showArray( $this->js_files );
		//showArray( $this->css_files );

		$header = '';
		for ( $i=0; $i<count( $this->js_files ); $i ++ )
		{
			$header .= "\n".'<script type="text/javascript" charset="utf-8" src="'.$this->js_files[$i].'"></script>';
		}
		for ( $i=0; $i<count( $this->css_files ); $i ++ )
		{
			$header .= "\n".'<link rel="stylesheet" type="text/css" href="'.$this->css_files[$i].'" />';
		}
		$header .= ($this->html_header?"/n":"").$this->html_header;
		return $header;
	}

	public function getFooter()
	{
		$footer = '';
		$footer .= "\n".$this->html_footer;
		return $footer;
	}
}

?>