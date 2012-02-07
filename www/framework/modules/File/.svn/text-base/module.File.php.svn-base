<?php

final class File
{
	public function __construct() {
	  
	}

	public function readFile($file_name) {
	    if(!file_exists($file_name)) return false;
	    $filesize = filesize($file_name);
	    if($filesize<1) return false;
	
	    if(function_exists('file_get_contents')) return file_get_contents($file_name);
	
	    $fp = fopen($file_name, "r");
	    $buff = '';
	    if($fp) {
	        while(!feof($fp) && strlen($buff)<=$filesize) {
	            $str = fgets($fp, 1024);
	            $buff .= $str;
	        }
	        fclose($fp);
	    }
	    return $buff;
	}
	
	public function writeFile($file_name, $buff, $mode = "w" ) {
	    $pathinfo = @pathinfo($file_name);
		$path = $pathinfo['dirname'];
		if(!is_dir($path)) File::makeDir($path);
		
		$mode = strtolower($mode);
		if(@!$fp = fopen($file_name, $mode)) return false;
		fwrite($fp, $buff);
		fclose($fp);
		@chmod($file_name, 0644);
		return true;
	}
	
	public function removeFile( $file_name ){
		@unlink( $file_name );
	}
	
	public function moveDir($source_dir, $target_dir) {
	    if(!is_dir($source_dir)) return;
	
	    if(!is_dir($target_dir)) {
	        File::makeDir($target_dir);
	        @unlink($target_dir);
	    }
	
	    @rename($source_dir, $target_dir); 
	}
	
	public function readDir($path, $filter = '', $to_lower = false, $concat_prefix = false) {
	    if(substr($path,-1)!='/') $path .= '/';
	    if(!is_dir($path)) return array();
	    $oDir = dir($path);
	    while( ($file = $oDir->read()) ) {
	        if(substr($file,0,1)=='.') continue;
	        if($filter && !preg_match($filter, $file)) continue;
	        if($to_lower) $file = strtolower($file);
	        if($filter) $file = preg_replace($filter, '$1', $file);
	        else $file = $file;
	
	        if($concat_prefix) $file = $path.$file;
	        $output[] = $file;
	    }
	    if(!$output) return array();
	    return $output;
	}
	
	public function changeOwner( $path, $owner, $group = NULL ) {
		/*110630 소유권 변경 막음*/return true;

		if(!is_dir($path)) {
			@chown( $path, $owner );
			
			if ( $group != NULL ) {
				@chgrp( $path, $group );
			}
			else {
				@chgrp( $path, $owner );
			}
		}
	}
	
	public function makeDir($path_string, $owner = NULL, $group = NULL) {
	    $path_list = explode('/', $path_string);
		$path = ( substr( $path_string, 0, 1 ) == "/" ) ? "/" : "";

		for($i=0;$i<count($path_list);$i++) {
			if(!$path_list[$i]) continue;
			$path .= $path_list[$i].'/';

			if(!is_dir($path)) {
				@mkdir( $path);
				//@mkdir( $path, 0755 );
	            //@chmod( $path, 0755 );
				
				if ( $owner != NULL ) {
					File::changeOwner( $path, $owner, $group );
				}
			}
		}
	
		return is_dir($path_string);
	}
	
	public function removeDir($path) {
	    if(!is_dir($path)) return;
	    $directory = dir($path);
	    while( ($entry = $directory->read()) ) {
	        if ($entry != "." && $entry != "..") {
	            if (is_dir($path."/".$entry)) {
	                File::removeDir($path."/".$entry);
	            } else {
	                @unlink($path."/".$entry);
	            }
	        }
	    }
	    $directory->close();
	    @rmdir($path);
	}
	
	public function filesize($size) {
	    if(!$size) return "0Byte";
	    if($size<1024) return ($size."Byte");
	    if($size >1024 && $size< 1024 *1024) return sprintf("%0.1fKB",$size / 1024);
	    return sprintf("%0.2fMB",$size / (1024*1024));
	}
}
?>
