
<?
require_once($_SERVER["DOCUMENT_ROOT"]."/framework/config.php");
require_once(_FRAMEWORK."/core.php");
//require_once(_MODULES."/External/module.facebook.php");
//require_once(_MODULES."/External/module.twitter.php");

/// @brief Define Core class 
/// @author ggugi
/// @params 
/// @return 
class SCRAP extends CORE{
	protected $facebook;
	protected $twitter;
	public function __construct(){
		//$this->default_service = "view_index_main";
		//$this->facebook = new API_FACEBOOK();
		//$this->twitter = new API_Twitter();
		parent::init();
		parent::execute();
	}

	/// @brief Define Core class 
	/// @author ggugisky
	/// @params 
	/// @return 
	protected function api_scrap_insert(){
		$objData = $this->params;
		$objData["m_idx"] = parent::getUserCookie();
		if(empty($objData["m_idx"])) return parent::setResult(false,$objData,"U should sign in ggugi.com");
		$this->model->get_member_info($objData);
		$this->model->set_scrap_insert($objData);
		return parent::setResult(true,$objData);
	}


}



$_instance= new SCRAP();

