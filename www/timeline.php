<?
require_once($_SERVER["DOCUMENT_ROOT"]."/framework/config.php");
require_once(_FRAMEWORK."/core.php");
require_once(_MODULES."/External/module.facebook.php");
require_once(_MODULES."/External/module.twitter.php");

/// @brief Define Core class 
/// @author ggugi
/// @params 
/// @return 
class TIMELINE extends CORE{
	protected $facebook;
	protected $twitter;
	public function __construct(){
		$this->default_service = "view_timeline_index";
		$this->facebook = new API_FACEBOOK();
		$this->twitter = new API_Twitter();
		parent::init();
		if(empty($this->m_idx)){
			header("location:/index.php");
		} 
		parent::execute();
	}
	 
	/// @brief 리스트 페이지 
	/// @author ggugi
	/// @params 
	/// @return 
	protected function view_timeline_index(){
		$objData = $this->params;
		$objData["m_idx"] = $this->m_idx;
		$this->model->get_member_info($objData);
		$this->model->get_external_info($objData);
		$this->model->get_timeline_count($objData);
		return $objData;
	}

	/// @brief 리스트 페이지 
	/// @author ggugi
	/// @params 
	/// @return 
	protected function view_timeline_list(){
		$objData = $this->params;
		$objData["m_idx"] = parent::getUserCookie();
		$this->model->get_member_info($objData);
		$objData["page"]= empty($objData["page"]) ? 1 : $objData["page"];
		//$objData["start_pos"] = ($objData["page"]-1)*$objData["count"];
		$this->use_layout = false;
		$this->model->get_timeline_list($objData);
		if(!is_array($objData["timeline_list"])) return $objData;	

		foreach($objData["timeline_list"] as $val){
			$objData["h_uids"][] = $val[h_uid];
		}
		$this->model->get_comment_list($objData);
		$objData["emotion"] = array("-1" => "ㅡㅡ^","0"=>"ㅡ_ㅡ","1"=>"^.^","2"=>"^__^");
		$this->model->get_timeline_emotion($objData);
		$temp = array();
		$i=0;
		foreach($objData["timeline_list"] as $val){
			$val["checked_activity"] = false;
						
			if(is_array($objData["comment_list"][$val["h_uid"]])){
				foreach($objData["comment_list"][$val["h_uid"]] as $v){
					if($v["m_idx"] == $objData["m_idx"]){
						$val["checked_activity"] = true;
						break;
					}
				}
			}

			if(is_array($objData["emotion_list"][$val["h_uid"]]) && !$val["checked_activity"]){
				foreach($objData["emotion_list"][$val["h_uid"]] as $ev){
					if($ev["m_idx"] == $objData["m_idx"]){
						$val["checked_activity"] = true;
						break;
					}
				}
			}
					
			$temp[$val["h_uid"]] = $val;

			//check activity if content's count is avobe 1
			if($objData["count"]>1 && !$val["checked_activity"]) break;

		    //if count of contents avobe 100 
			if($i>50){
				break;
			}

			$i++;
		}
		$objData["timeline_list"] = $temp;
		return $objData;
	}


	/// @brief 리스트 페이지 
	/// @author ggugi
	/// @params 
	/// @return 
	protected function api_get_externalInfo(){
		$objData = $this->params;
		$objData["m_idx"] = parent::getUserCookie();
		$this->model->get_external_info($objData);
		return parent::setResult(true,$objData["external_info"]);
	}

	/// @brief 리스트 페이지 
	/// @author ggugi
	/// @params 
	/// @return 
	protected function api_timeline_upload_photo(){
		$objData = $this->params;
		$objData["m_idx"] = parent::getUserCookie();
		$maxsize = 200000000;
		try {
			foreach( $_FILES as &$_FILE ) {
				if ( $_FILE["size"] <= $maxsize ) {
						$filePath = _FILES."/".md5($file["name"].time());
					if( empty($_FILE["tmp_name"]) ) {
						continue;
					}
					copy($_FILE["tmp_name"], $filePath);
					$result = $_FILE;
					$result["file_path"] = $filePath;
					$result["path"]= substr( $filePath, strlen( _ROOT) );
					//이미지 여부 체크 필요
					$result["imgInfo"] = getImageSize($result["file_path"]);
					/*
					if(!in_array($result["imgInfo"][2],$this->available_image) && $check_img){
						return parent::setResult(0,$objData,"unsupport format");	
					}
					*/
					unset($result["tmp_name"]);
					$results[] = $result;
				} else {
					return parent::setResult(0,$objData,"over size");	
				}
			}
				$objData["result"]=$results;
		}catch(Exception $e){
			showArray($e);
		}

		return parent::setResult(true,$objData);
	}

	/// @brief 리스트 페이지 
	/// @author ggugi
	/// @params 
	/// @return 
	protected function api_set_emotion(){
		$objData = $this->params;
		$objData["m_idx"] = parent::getUserCookie();
		$this->model->set_timeline_emotion($objData);
		return parent::setResult(true,$objData);
	}

	/// @brief 리스트 페이지 
	/// @author ggugi
	/// @params 
	/// @return 
	protected function api_timeline_set_level(){
		$objData = $this->params;
		$objData["m_idx"] = parent::getUserCookie();
		//if($objData["m_idx"] != $objData["h_uid"]) return parent::setResult(false,$objData,"Permission denined!!");
		$this->model->set_timeline_level($objData);
		return parent::setResult(true,$objData);
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
	protected function api_timeline_remove(){
		$objData = $this->params;
		$objData["m_idx"] = parent::getUserCookie();
		$this->model->remove_timeline($objData);
		return parent::setResult(true,$objData);
	}

	/// @brief 리스트 페이지 
	/// @author ggugi
	/// @params 
	/// @return 
	protected function api_timeline_comment_insert(){
		$objData = $this->params;
		$objData["m_idx"] = parent::getUserCookie();
		$objData["c_reg_date"] = date("Y.m.d (H:i)");
		$this->model->get_member_info($objData);
		$this->model->set_comment_add($objData);
		return parent::setResult(true,$objData);
	}

	/// @brief 리스트 페이지 
	/// @author ggugi
	/// @params 
	/// @return 
	protected function api_timeline_status_insert(){
		$objData = $this->params;
		$objData["m_idx"] = parent::getUserCookie();
		$this->model->get_member_info($objData);
		$objData["name"] = $objData["member_info"]["m_name"];
		$objData["h_reg_time"] = time();
		$objData["h_update_time"] = time();
		$objData["source_from"] = "GGUGI";
		$this->model->set_status_insert($objData);
		$this->model->get_external_info($objData);
		if($objData["facebook"] == "true"){
			$options["description"] = "솔직한 나만의 이야기.(my own story.)";
        	$options["name"]        = "꾸기닷컴(Episode #My Paper)";
        	$options["picture"]     = "http://www.ggugi.com/image/no_profile.gif";
			$options["type"]		= "like";
        	$options["link"]        = "http://www.ggugi.com/timeline.php";
			if($objData["type"] == "PHOTO"){
        	$options["picture"]     = "http://ggugi.com".$objData["h_file"];
			//$options["type"]		= "photo";
        	//unset($options["link"]);     
        	//unset($options["name"]);     
        	//unset($options["description"]);     
			}
			$result = $this->facebook->post(
				$objData["external_info"]["FACEBOOK"]["e_access_token"],
				$objData["content"],
				$options
			);
		
		}

		if($objData["twitter"] == "true"){
			$options["description"] = "꾸기 닷컴입니다. 제가 뭘 만들고 싶을까요?? 1월 20일 오픈합니다^^";
        	$options["name"]        = "꾸기닷컴(Episode #My Paper)";
        	$options["picture"]     = "http://ggugi.com/image/no_profile.gif";
        	$options["link"]        = "http://ggugi.com/timeline.php";
			$this->twitter->post(
				$objData["external_info"]["TWITTER"]["e_access_token"],
				$objData["content"]
			);
		
		}



		return parent::setResult(1,$objData);
	}

}



$_instance= new TIMELINE();

