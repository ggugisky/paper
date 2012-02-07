<?PHP
require_once( _MODULES.'/File/module.File.php' );

/*
 * Template Module
 * author : progdesigner
 * version 1.0.2
 */

class Template
{
//	private $compiled_path = './files/cache/';
	private $root;
	private $files;
	private $caches;
	private $vars;
	private $loops;
	private $subLoops;
	private $topCacheName;
	private $complies;
	private $js_files;
	private $css_files;
	
	public function __construct( $root="" ) 
	{
		$this->root = ( $root == "" ) ? _ROOT : $root ;
		$this->files = array();
		$this->caches = array();
		$this->vars = array();
		$this->loops = array();
		$this->subLoops = array();
		$this->compiles = array();
		$this->js_files = array();
		$this->css_files = array();
		
		$this->topCacheName = "undefined";
		$this->compiled_name = '';
		
		//$GLOBALS["__TEMPLETE_MODULE_START__"] = getMicroTime();
	}

	private function setError( $msg, $name = null ) {
		//ErrorLog::error( $msg );
	}
    
	# 파일을 정의하는 함수
	public function define( $name, $file='' ) 
	{
		if(is_array( $name ) && $file == '') {
			//$this->setDebug("define step1");
			foreach($name as $n => $v) 
				$this->define($n, $v);
		} else {
			//$this->setDebug("define step2");
			if(!$this->isName($name)) return $this->setError("function define -"."$name is not valid", $this->name);
			$this->files[$name] = $file;
			$this->caches[$name] = $this->scanBuffer( $this->readFile($file), 0, $name, $this->vars );
		}
		return true;
	}
	
	# 변수를 지정하는 함수
	public function assign($name, $value='') 
	{
		if(is_array($name) && $value == "") {
			foreach($name as $n => $v) $this->assign($n, $v);
		} else {
			if(!$this->isName($name)) return $this->setError("function assign -"."$name is not valid", $this);
			if(is_array($value) || is_object($value)) return $this->setError("function assign -"."$name value is not valid", $this->name);
			$this->vars[$name] = $value;					
		}
		return true;
	}
	
	# 파싱함수
	public function parse($name, $loop="") 
	{
		if(!$this->isName($name)) return $this->setError("function parse -"."$name is not valid", $this->name);
		if(is_array($loop)) {
			if(!$loop) return false;
			if(isset($loop[0])) return $this->parseLoop($name, $loop);
			else $this->assign($loop);
		}
		if(!isset($this->caches[$name])) return $this->setError("function parse -"."$name cache is not exists", $this->name);
		if(!isset($this->vars[$name])) $this->vars[$name] = "";
		$this->vars[$name] .= $this->parseString($this->caches[$name]);
		//if ( __DEBUG > 0) $this->vars[$name] .= "\n\r".sprintf('<!-- [DEBUG] COMPILE TEMPLETE "%s" -->', $this->files[$name]);
		if(isset($this->subLoops[$name])) {
			for($i=0, $max=count($this->subLoops[$name]); $i<$max; $i++) {
				$subname = $this->subLoops[$name][$i];
				$this->vars[$subname] = "";
			}
		}
		$this->topCacheName = $name;
		return true;
	}
	
	public function clearCache()
	{
		$this->caches = Array();
	}
	
	# 불러오기
	public function fetch($name) 
	{
		if(!$this->isName($name)) return $this->setError("function fetch -"."$name is not valid", $this->name);
		if(!isset($this->vars[$name])) return $this->setError("function fetch -"."$name is not exists", $this->name);
		return $this->vars[$name];
	}
	
	# 출력하기
	public function tprint($name='null') 
	{
		if($name == 'null' && $this->topCacheName != "undefined" ) $name = $this->topCacheName;
		if(!$this->isName($name)) return $this->setError("function tprint -"."$name is not valid", $this->name);
		if(!isset($this->vars[$name])) return $this->setError("function tprint -"."$name is not exists");
		print $this->output($name);

		return true;
	}
	
	private function output( $name )
	{
		ob_start();

        if( !file_exists($this->complies[$name]) ) {
			print $this->vars[$name];
        } else {
			include_once($this->complies[$name]);
        }
		
		return ob_get_clean();
	}
	
	# define, parse를 동시처리하는 함수
	public function process($name, $file="") 
	{
		if(!$this->isName($name)) return $this->setError("$name is not valid");
		if($file != "") $this->files[$name] = $file;
		$buffer = $this->scanBuffer($this->readFile($this->files[$name]), 0, $name, $this->vars);
		if(isset($this->subLoops[$name])) {
			foreach($this->subLoops[$name] as $loop) {
				$this->parseLoop($loop, $this->loops[$loop]);
			}
		}
		$this->topCacheName = $name;
		return $this->vars[$name] = $this->parseString($buffer);
	}
	
	# 파일 지정 함수 (assign의 다른이름)
	public function setFile($name, $file) 
	{
		if(!$this->isName($name)) return $this->setError("$name is not valid");
		$this->files[$name] = $file;
		return true;
	}

	# 변수 지정 함수 (assign의 다른이름)
	public function setVar($name, $value="") 
	{
		$this->assign($name, $value);
	}

	# 루프 변수 지정 함수
	public function setLoop($name, &$value) 
	{
		if(!$this->isName($name)) return $this->setError("$name is not valid");
		if(!is_array($value[0])) return $this->setError("LOOP value is not valid");
		$this->loops[$name] = &$value;
		$this->parseLoop( $name, &$value );
		return true;
	}

	# Import 된 JS 파일 출력 함수
	public function getJsFiles()
	{
		return $this->js_files;
	}

	# Import 된 CSS 파일 출력 함수
	public function getCSSFiles()
	{
		return $this->css_files;
	}
	
	# 템플릿 변수명 체크
	private function isName($name) 
	{
		return preg_match("/^[a-z0-9\_\-.]+$/i", $name);
	}
	
	# 파일 읽기
	public function readFile($file)
	{
		if($this->root != '') $file = $this->root . "/" . $file;
		
		if(!@is_readable($file)) return $this->setError("$file is not readable");
		
		$buffer = File::readFile($file);
		return $buffer;
	}
	
	# URL읽기
	private function readURL($url) {
		$errno = -1;
		$errstr = '';
		$r = parse_url($url);
		if(!isset($r['host'])) $r['host'] = $_SERVER['HTTP_HOST'];
		if(!isset($r['port'])) $r['port'] = 80;
		$path = isset($r['query']) ? $r['path'] . "?" . $r['query'] : $r['path'];
		if(!$fp = @fsockopen($r['host'], $r['port'], $errno, $errstr, 30)) {
			return $this->setError("$url is not connected");
		}
		fwrite($fp, "GET $path HTTP/1.0\r\nHost: $r[host]\r\n\r\n");
		$body = false; 
		$buffer = "";
		while(!feof($fp)) {
			$s = fgets($fp, 1024);
			if($body == true) {
				$buffer .= $s;
			}
			else if($s == "\r\n") {
				$body = true;
			}
		}
		fclose($fp);
		return $buffer;
	}
	
	# Templete Path 찾기
	private function templetePath( $name ) 
	{
		$arr = explode('/', $this->files[$name] );
		$path = "/";
		for ( $i=0;$i<count($arr)-1;$i++ ){
			if ( $arr[$i] != '.' && trim( $arr[$i] ) != "" ) {
				$path .= trim($arr[$i])."/";
			}
		}
		return $path; 
	}
	
	# PHP실행
	private function exePHP($path) 
	{
        $var_arr = explode("?", $path);     	// 변수를 지정했을 경우
        if($var_arr[1]) parse_str($var_arr[1]);	// 변수를 파싱해서 넘겨줌.
        $filename = $var_arr[0];
		if(!is_readable($filename)) return $this->setError("$filename is not readable");	
		ob_start();
		include_once $filename;	// 에러발생시 출력
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}

	# 소스코드실행
	private function exeCode($code, $vars = Array()) 
	{
		$code = $this->parseString( $code, $vars, "{", "}" );
		if(substr($code, -1) != ";") $code .= ";";
		ob_start();
		eval(str_replace(">>", "echo ", $code)); // 에러발생시 출력
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
	
	# 문자를 파싱
	private function parseString($buffer, $vars = null, $start="{{", $end="}}") 
	{
		$buffers = explode($start, $buffer);
		$max = count($buffers);
		$str = $buffers[0];
		
		for($i=1; $i<$max; $i++) {
			$find_str = explode($end, $buffers[$i]);
			$find_key = $find_str[0];
			
			$temp = explode( ":", $find_key );
			
			if ( is_array( $temp ) ) {
				$key = $temp[0];
				$option = $temp[1];
			}
			else {
				$key = $find_key;
				$option = "";
			}
			
			if( $this->isName($key) == true ) {
				$vars = ( $vars == null ) ? $this->vars : $vars;
				
				if ( isset( $vars[$key] ) ) {
					if ( is_array( $vars[$key] ) ) {
						$str .= $this->stringParseLoop($key, $vars[$key]) . substr($buffers[$i], strlen($find_key) + strlen($end));
					}
					else {
						switch( $option ) {
							case "HTML":
								$value = htmlspecialchars( $vars[$key] );
							break;
							
							case "URL":
							case "ENCODE":
								$value = urlencode( $vars[$key] );
							break;
							
							case "DECODE":
								$value = urldecode( $vars[$key] );
							break;
							
							case "NUMBERFORMAT":
								if( empty($vars[$key]) ) $vars[$key] = 0;
								$value = number_format( $vars[$key] );
							break;
							
							default:
								$value = $vars[$key];
							break;
						}
						
						$str .= $value . substr($buffers[$i], strlen($find_key) + strlen($end));
					}
				} else {
					$str .= substr($buffers[$i], strlen($find_key) + strlen($end));
				}
			} else {
				$str .= "{{" . $buffers[$i];
			}
		}

		return $str;
	}
	
	# 루프영역을 파싱
	private function parseLoop($name, $loop="") 
	{
		$this->vars[$name] = $this->stringParseLoop( $name, $loop );
	}
	
	private function stringParseLoop($name, $loop="") 
	{
		$str = "";
		
		for($i=0, $max=count($loop); $i<$max; $i++) {
			//$str .= $this->parseString($this->caches[$name], $loop[$i]);
			
			//[S] King_2011.07.18 for loop's if
			$tmp = $this->parseString($this->caches[$name], $loop[$i]);
			$tmp = $this->scanBuffer($tmp, 0, null, $loop[$i]);
			$str .= $tmp;
			//[E] King_2011.07.18 for loop's if
		}
		
		return $str;
	}
	
	private function scanBuffer($buffer, $offset, $parent, $vars) 
	{
		static $area = array();
		$str = "";
		while(is_int($pos = strpos($buffer, "<!-- ", $offset))) {
			$str .= substr($buffer, $offset, $pos - $offset);
			$offset = $pos + 5;
			
			if(!in_array(substr($buffer, $offset, 2), array("IN", "RE", "IM", "EX", "DY", "LO", "IF", "AR"))) {
				$str .= "<!-- ";
				continue;
			}
			
			if(is_int($endpos = strpos($buffer, "-->", $offset))) {
				$offset = $endpos + 3;
				if($endpos - $offset > 50) {
					$str .= substr($buffer, $pos, $offset - $pos);
					continue;
				}
				$arr = explode(" ", $this->parseString(substr($buffer, $pos + 5, $endpos - $pos - 6)));
				
				if(isset($arr[2])) {
					$name = substr($arr[2], 1, -1);
				} else {
					$str .= substr($buffer, $pos, $offset - $pos);
					continue;
				}
				
				switch($arr[0]) {
					case 'INCLUDE':
					case 'REQUIRE':
						try {
							if($arr[1] == "FILE") {
								$fileName = $this->readFile($this->templetePath( $parent ).$name);
								
								//if ( file_exists( $fileName ) ) {
								$str .= $this->scanBuffer( $fileName, 0, $parent, $vars );
								//}
							}
							else if($arr[1] == "URL") {
								$str .= $this->scanBuffer( $this->readURL($name), 0, $parent, $vars );
							}
						}
						catch( Exception $e ) {
							if ( $arr[0] == "REQUIRE" ) {
								echo $e->getMessage();
								exit();
							}
						}
						break;
					case 'IMPORT':
						$_import_file = ( substr( $name, 0, 1) == "/" ) ? trim($name) : $this->templetePath( $parent ).str_replace( "./", "", trim($name) );
						
						if($arr[1] == "JS") {
							array_push( $this->js_files, $_import_file );
						}
						else if($arr[1] == "CSS") {
							array_push( $this->css_files, $_import_file );
						}
						break;
					case 'EXECUTE':
						if($arr[1] == "FILE") {
							$str .= $this->exePHP($name);
						}
						else if($arr[1] == "CODE") {
							$str .= $this->exeCode($name);
						}
						break;
					case 'DYNAMIC':
						if($arr[1] != "AREA") break;
						if(isset($area[$name])) {
							$arr[1] = "END";
							unset($area[$name]);
						} else {
							$arr[1] = "START";
							$area[$name] = 1;
						}
						break;
					case 'LOOP':
						if($arr[1] == "START") {
							$this->caches[$name] = &$this->scanBuffer($buffer, &$offset, $name, $vars);
							$str .= "{{" . $name . "}}";
							if(!isset($this->subLoops[$parent])) $this->subLoops[$parent] = array();
							$this->subLoops[$parent][] = $name;
						} else if($arr[1] == "END") {
							return $str;
						}
						break;
					case 'IF':
						if( isset($vars[$name]) ) {
							if( $arr[1] == "START" ) {
								if( $vars[$name] !== true ) {
									$next = "<!-- IF END '" . $name . "' -->";
									if(is_int($ifendpos = strpos($buffer, $next, $offset))) {
										$offset = $ifendpos + strlen($next);
									}
								}
								else {
									
								}
							}
						}
						else {
							$str .= substr($buffer, $pos, $offset - $pos);
						}

						break;
						
					case 'IFNOT':
						if ( isset($vars[$name]) ) {
							if( $arr[1] == "START" ) {
								if( $vars[$name] !== false ) {
									$next = "<!-- IFNOT END '" . $name . "' -->";
									if(is_int($ifendpos = strpos($buffer, $next, $offset))) {
										$offset = $ifendpos + strlen($next);
									}
								}
								else {
									//
								}
							}
						}
						else {
							$str .= substr($buffer, $pos, $offset - $pos);
						}

						break;

					default:
						$str .= substr($buffer, $pos, $offset - $pos);
						break;
				}
			} else {
				$str .= "<!-- ";
			}
		}
		
		return $str.substr($buffer, $offset);
	}

	private $compile_name;

	public function parsePath( $name ) {
		$buffer = $this->vars[$name];
		$this->compile_name = $name;
		$buffer = preg_replace_callback('/(img|input)([^>]*)src=[\'"]{1}(?!http)(.*?)[\'"]{1}/is', array($this, 'parseImgPath'), $buffer);
		$this->vars[$name] = $buffer;
		$this->compile_name = '';
	}
	
	# 템플릿 컴파일시 이미지 경로 업데이트
	private function parseImgPath( $matches ) 
	{
		$str1 = $matches[0];
	    $str2 = $path = $matches[3];
	
	    if(!preg_match('/^([a-z0-9\_\.])/i',$path)) return $str1;
	
	    $path = preg_replace('/^(\.\/|\/)/','',$path);
	    $path = $this->templetePath($this->compile_name).$path;
	    
	    $output = str_replace($str2, $path, $str1);
	    return $output;
	}
}

?>