<?
require_once($_SERVER["DOCUMENT_ROOT"]."/framework/config.php");
require_once(_FRAMEWORK."/core.php");
//require_once(_MODULES."/External/module.facebook.php");
//require_once(_MODULES."/External/module.twitter.php");

/// @brief Define Core class 
/// @author ggugi
/// @params 
/// @return 
class PAPER extends CORE{
	protected $facebook;
	protected $twitter;
	public function __construct(){
		$this->default_service = "view_paper_index";
		//$this->facebook = new API_FACEBOOK();
		//$this->twitter = new API_Twitter();
		parent::init();
		parent::execute();
	}

	/// @brief Define Core class 
	/// @author ggugisky
	/// @params 
	/// @return 
	protected function view_paper_index(){
		$objData = $this->params;
		$objData["m_idx"] = $this->m_idx;
		if(empty($objData["m_idx"])) return parent::setResult(false,$objData,"U should sign in ggugi.com");
		$this->model->get_member_info($objData);
		return $objData;
	}

	/// @brief Define Core class 
	/// @author ggugisky
	/// @params 
	/// @return 
	protected function view_mobile_paperList(){
		$objData = $this->params;
		$objData["m_idx"] = $this->m_idx;
		$this->model->get_tag_list($objData);	
		$this->model->get_member_info($objData);
		return $objData;
	}


	/// @brief Define Core class 
	/// @author ggugisky
	/// @params 
	/// @return 
	protected function api_paper_getTagList(){
		$objData = $this->params;
		$objData["m_idx"] = $this->m_idx;
		$this->model->get_tag_list($objData);	
		return parent::setResult(true,$objData);
	}

	/// @brief Define Core class 
	/// @author ggugisky
	/// @params 
	/// @return 
	protected function api_paper_getData(){
		$objData = $this->params;
		$objData["m_idx"] = $this->m_idx;
		$this->model->get_member_info($objData);
		$this->model->get_paper_list($objData);
		$size_arr = array(
			"",
			"xsmall",
			"small",
			"middle",
			"large"
		);
		$i=0;
		foreach($objData["paper_list"] as $val){
			$length = (rand()%4+1);	
			$str_length = strlen($val["h_content"]);
			if($str_length < 1000){
				$size_value = 1;
			}elseif($str_length < 5000){
				$size_value = 2;
			}elseif($str_length < 10000){
				$size_value = 3;
			}else{
				$size_value = 4;
			}

			if($val["h_type"] == "PHOTO"){
				$size_value = 4;
			}

			$val["size"] =  $size_arr[$size_value];
			$val["size_value"] = $size_value;
			$val["date"] = date("Y.m.d (H:i)",$val["h_reg_time"]);
			$temp[] = $val;
			$i++;
		}
		$objData["paper_list"] = $temp;
		return parent::setResult(true,$objData);
	}
}



$_instance= new PAPER();

