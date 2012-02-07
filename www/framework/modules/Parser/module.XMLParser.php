<?php

class XMLParser {
	var $result;
	
	function __construct() {
		$this->init();
	}

	function init() {
		
	}
	
	function toXML( $data, $rootNodeName = 'data', $xml ) 
	{
		// turn off compatibility mode as simple xml throws a wobbly if you don't.
		if (ini_get('zend.ze1_compatibility_mode') == 1)  {
			ini_set ('zend.ze1_compatibility_mode', 0);
		}
 
		// loop through the data passed in.
		foreach($data as $key => $value) {
			// no numeric keys in our xml please!
			if (is_numeric($key)) {
				// make string key...
				$key = ( $xml->getName() ) ? $xml->getName() : "unknownNode";//"unknownNode_". (string) $key;
			}
			else {
				if ( substr( $key, 0, 1 ) == "@" ) {
					continue;
				}
				
				// replace anything not alpha numeric
				$key = preg_replace('/[^a-z_-]/i', '', $key);
			}
			
			// if there is another array found recrusively call this function
			if (is_array($value)) {
				
				// is Array
				if ( isset( $value[0]) ) {
					for ( $i=0; $i<count($value); $i++ ) {
						$node = $xml->addChild( $key );
						foreach($value[$i] as $k => $v ) {
							if ( substr( $k, 0, 1) == "@" ) {
								$k = preg_replace('/[^a-z_-]/i', '', $k);
								
								$node->addAttribute( $k, $v );
							}
							else {
								$node = $xml->addChild($k);
								
								foreach($v as $k2 => $v2 ) {
									if ( substr( $k2, 0, 1) == "@" ) {
										$k2 = preg_replace('/[^a-z_-]/i', '', $k2);
										
										$node->addAttribute( $k2, $v2 );
									}
								}
								
								$this->toXML($v, $rootNodeName, $node);
							}
						}
					}
				}
				// is Object
				else {
					$node = $xml->addChild($key);
					
					foreach($value as $k => $v ) {
						if ( substr( $k, 0, 1) == "@" ) {
							$k = preg_replace('/[^a-z_-]/i', '', $k);
							
							$node->addAttribute( $k, $v );
						}
					}
					
					// recrusive call.
					$this->toXML($value, $rootNodeName, $node);
				}
			}
			else {
				// add single node.
				
				if ( ! $this->textNeedsEscaping( $value ) ) {
					$value = htmlspecialchars( $value );
					$value = stripslashes( $value );
					
					$xml->addChild($key, $value);
				} 
				else {
					$node = $xml->addChild($key);
					
					$value = stripslashes( $value );
					
					$import = dom_import_simplexml($node);   
					$no = $import->ownerDocument;
					$import->appendChild( $no->createCDATASection($value) );
				}
			}
 
		}
		// pass back as string. or simple xml object if you want!
		return $xml->asXML();
	}

	function textNeedsEscaping( $str )
	{
		if ( $str == null ) return false;
		
		for ( $i=0; $i<strlen($str); $i++ ){
			$char = substr($str, $i, 1);
			if ( $char == '<' || $char == '>' || $char == '&' ) {
				return true;
			}
		}
		return false;
	}

	function parse( $data, $rootNodeName = 'xml', $xml=null ) 
	{
		if ($xml == null) {
			$xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$rootNodeName />");
		}
		
		foreach($data as $key => $value ) {
			if ( substr( $key, 0, 1) == "@" ) {
				$key = preg_replace('/[^a-z_-]/i', '', $key);
				
				$xml->addAttribute( $key, $value );
			}
		}
		
		$this->result = $this->toXML( $data, $rootNodeName, $xml);
	}
	
	function output() {
		return $this->result;
	}
}

?>