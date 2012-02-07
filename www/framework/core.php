<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/framework/config.php");
require_once(_FRAMEWORK."/model_mysql.php");
require_once(_FRAMEWORK."/lib.php");

/// @brief Define Core class 
/// @author ggugi ggugisky
/// @params 
/// @return 
class CORE{

	protected $service;
	protected $model;
	protected $memcache;
	protected $params;
	protected $output;
	protected $default_service;
	protected $use_layout = true;
	public $m_idx;
	/// @brief
	/// @author ggugi
	/// @param
	/// @return
	public function __construct(){
		$this->init();
	}

	/// @brief
	/// @author ggugi
	/// @param
	/// @return
	public function __destruct(){
	
	}

	/// @brief
	/// @author ggugi
	/// @param
	/// @return
	protected function execute(){
	   	$result = $this->{$this->service}();	
		$service = explode("_",$this->service);
		switch($service[0]){
			case "view" :
				$this->output = "template";
				break;
			case "api"	:
				$this->output = "json";
				break;
			case "secure"	:
				$this->output = "jsonp";
				break;
			default :
				break;
		}
		$this->set_page_by_output($service,$result);

	}

	/// @brief
	/// @author ggugi
	/// @param
	/// @return
	private function set_page_by_output($service,$result){
		switch($this->output){
			case "template" :
				header("Content-Type: text/html; charset=UTF-8");

				if($this->use_layout) include(_TEMPLATE."/".$service[1]."/header.php");
				$template_file = _TEMPLATE."/".$service[1]."/".$service[2].".php";
				if(!file_exists($template_file)){
					$newfile = `touch $template_file`;
				}
				include_once($template_file);
                if(_USE_GOOGLE_ANALYSTIC && $this->m_idx !=2){
                    echo '<script type="text/javascript" src="/common/script/google.analystics.js"></script>';
                }
				if($this->use_layout) include(_TEMPLATE."/".$service[1]."/footer.php");
				break;
			case "json"	:
				//header("Content-Type: text/plain; charset=UTF-8");
				$result = json_encode($result);
	   			echo $result;
				break;
			case "jsonp"	:
				//header("Content-Type: text/plain; charset=UTF-8");
				$result = $result["RESPONSE"]["jsoncallback"]."(".json_encode($result).")";
	   			echo $result;
				break;
			default :
				break;
		}

	}

	/// @brief
	/// @author ggugi
	/// @param
	/// @return
	protected function init(){
		if(_USE_DB) $this->model = new MODEL();
		if(_USE_MEMCACHE){
			$this->memcache = new Memcache();
			$this->memcache->connect(_MEM_HOST,_MEM_PORT);
		}
		$this->m_idx = $_COOKIE["COOKIE_GGUGI_MEMBER_IDX"];
		foreach($_REQUEST as $key => $val){
			if($key == "service") continue;
			$this->params[$key] = $val;
		}
		$this->service = empty($_REQUEST["service"]) ? $this->default_service : $_REQUEST["service"];
	}

	/// @brief
	/// @author ggugi
	/// @param
	/// @return
	protected function getUserCookie(){
        return $this->m_idx;
    }

	/// @brief
	/// @author ggugi
	/// @param
	/// @return
	protected function setUserCookie($m_idx){
        setcookie("COOKIE_GGUGI_MEMBER_IDX",$m_idx,time()+3600*24*30);
        $this->m_idx = $m_idx;
        return $this->m_idx;
    }

	/// @brief
	/// @author ggugi
	/// @param
	/// @return
	protected function setResult($flag,$objData=array(),$msg="",$type="json"){
		$data["RESULT_SET"]["flag"] = $flag;
		$data["RESULT_SET"]["msg"] = $msg;
		$data["RESPONSE"] = $objData;
		return $data;
	}



}
