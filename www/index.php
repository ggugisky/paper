<?
require_once($_SERVER["DOCUMENT_ROOT"]."/framework/config.php");
require_once(_FRAMEWORK."/core.php");
require_once(_MODULES."/External/module.facebook.php");
require_once(_MODULES."/External/module.twitter.php");

/// @brief Define Core class 
/// @author ggugi
/// @params 
/// @return 
class INDEX extends CORE{
	protected $facebook;
	protected $twitter;
	public function __construct(){
		$this->default_service = "view_index_main";
		$this->facebook = new API_FACEBOOK();
		$this->twitter = new API_Twitter();
		parent::init();
		parent::execute();
	}
	 
	/// @brief 리스트 페이지 
	/// @author ggugi
	/// @params 
	/// @return 
	protected function view_index_main(){
		$objData = $this->params;
        if($_SERVER["HTTP_HOST"] != _DOMAIN){
			header("location:http://"._DOMAIN);
            die();
        }

		if(!empty($this->m_idx)){
			header("location:http://"._DOMAIN."/timeline.php");
            die();
		} 
		return $objData;
	}

	/// @brief 리스트 페이지 
	/// @author ggugi
	/// @params 
	/// @return 
	protected function view_index_facebookPopUp(){
		$objData = $this->params;
		$this->facebook->checkAllowAccess($objData["callback"],$objData["is_mobile"],$objData["oauth_url"]);
		return $objData;
	}




	/*이후 삭제 처리==============================================================*/	
	/// @brief 리스트 페이지 
	/// @author ggugi
	/// @params 
	/// @return 
	protected function view_timeline_list(){
		$objData = $this->params;
		$objData["page"]= empty($objData["page"]) ? 1 : $objData["page"];
		$objData["count"] = 10;
		$objData["start_pos"] = ($objData["page"]-1)*$objData["count"];
		$this->use_layout = false;
		$this->model->get_timeline_list($objData);
		foreach($objData["timeline_list"] as $val){
			$objData["h_uids"][] = $val[h_uid];
		}
		$this->model->get_comment_list($objData);
		return $objData;
	}

	/// @brief 리스트 페이지 
	/// @author ggugi
	/// @params 
	/// @return 
	protected function api_comment_list(){
		$objData = $this->params;
		$this->model->set_comment_time($objData);
		return $objData;
	}

	/// @brief 리스트 페이지 
	/// @author ggugi
	/// @params 
	/// @return 
	protected function api_timeline_status_insert(){
		$objData = $this->params;
		$objData["name"] = "guest";
		$objData["m_uid"] = $_SESSION[GGUGI_M_UID];
		$objData["now_time"] = time();
		$this->model->set_status_insert($objData);
		return parent::setResult(1,$objData);
	}

}



$_instance= new INDEX();

