
<?
require_once($_SERVER["DOCUMENT_ROOT"]."/framework/config.php");
require_once(_FRAMEWORK."/core.php");
require_once(_MODULES."/External/module.facebook.php");
require_once(_MODULES."/External/module.twitter.php");

/// @brief Define Core class 
/// @author ggugi
/// @params 
/// @return 
class COMMON extends CORE{
	protected $facebook;
	protected $twitter;
	public function __construct(){
		//$this->default_service = "view_index_main";
		$this->facebook = new API_FACEBOOK();
		$this->twitter = new API_Twitter();
		parent::init();
		parent::execute();
	}
	 

	/// @brief 리스트 페이지 
	/// @author ggugi
	/// @params 
	/// @return 
	protected function view_common_facebookPopUp(){
		$objData = $this->params;
		if(empty($_SESSION["caller"])){
			$_SESSION["caller"] = $objData["caller"];
		}
		$objData["callback"] = "/common.php?service=view_common_facebookSave";
		$objData["oauth_url"] = "/common.php?service=view_common_facebookPopUp";
		$this->facebook->checkAllowAccess($objData["callback"],$objData["is_mobile"],$objData["oauth_url"]);
		return $objData;
	}

	/// @brief 리스트 페이지 
	/// @author ggugi
	/// @params 
	/// @return 
	protected function view_common_facebookSave(){
		$objData = $this->params;
		$objData["caller"] = $_SESSION["caller"];
		unset($_SESSION["caller"]);
		$query["query1"]  = "SELECT email, uid ,sex, birthday_date, pic_big, name FROM user where uid=me()";
		$result = $this->facebook->fqlGet($objData["access_token"],$query);
		$user_info = $result[0]["fql_result_set"][0]; 
        $bday = explode("/",$user_info["birthday_date"]);
		$objData["m_name"] = $user_info["name"];
		$objData["m_email"] = $user_info["email"];
		$objData["m_profile_img"] = $user_info["pic_big"];
		$objData["m_sex"] = strtoupper($user_info["sex"]);
		$objData["m_birthday"] = $bday[2]."-".$bday[0]."-".$bday[1];
		$objData["e_sns_uid"] = $user_info["uid"];
		$objData["e_access_token"] = $objData["access_token"];
		$objData["type"] = "FACEBOOK";
		$objData["m_idx"] = $this->m_idx;
		if(empty($objData["m_idx"])){
			$this->model->insert_member_by_facebook($objData);
            parent::setUserCookie($objData["m_idx"]);
            showArray($_COOKIE);
            showArray($objData);
		}else{
			$this->model->async_member($objData);
		}

		return $objData;
	}

	/// @brief 리스트 페이지 
	/// @author ggugi
	/// @params 
	/// @return 
	protected function view_common_twitterPopUp(){
		$objData = $this->params;
		if(empty($_SESSION["caller"])){
			$_SESSION["caller"] = $objData["caller"];
		}
		$objData["callback"] = "/common.php?service=view_common_twitterSave";
		$objData["oauth_url"] = "/common.php?service=view_common_twitterPopUp";
		$this->twitter->checkAllowAccess($objData["callback"],$objData["is_mobile"],$objData["oauth_url"]);
		die();
		return $objData;
	}

	/// @brief 리스트 페이지 
	/// @author ggugi
	/// @params 
	/// @return 
	protected function view_common_twitterSave(){
		$objData = $this->params;
		$objData["caller"] = $_SESSION["caller"];
		unset($_SESSION["caller"]);
		$user_info=$this->twitter->createAccessToken($objData["oauth_verifier"]);
		$objData["access_token"] = implode("|", $user_info);
		if(empty($objData["access_token"])) return parent::setResult(false,$objData,"");
		$objData["m_name"] = $user_info["screen_name"];
		$objData["m_profile_img"]     = "https://api.twitter.com/1/users/profile_image?screen_name=".$user_info["screen_name"]."&size=bigger";

		$objData["e_sns_uid"] = $user_info["user_id"];
		$objData["e_access_token"] = $objData["access_token"];
		$objData["type"] = "TWITTER";
		$objData["m_idx"] = $this->m_idx;
		if(empty($objData["m_idx"])){
			$this->model->insert_member_by_twitter($objData);
            parent::setUserCookie($objData["m_idx"]);
		}else{
			$this->model->async_member($objData);
		}
		return $objData;
	}

	/// @brief 리스트 페이지 
	/// @author ggugi
	/// @params 
	/// @return 
	protected function api_common_logout(){
		$objData = $this->params;
        setcookie("COOKIE_GGUGI_MEMBER_IDX","",time()+3600*24*30);
		return parent::setResult(true,$objData,"로그아웃 처리되었습니다.");
	}

	/// @brief 리스트 페이지 
	/// @author ggugi
	/// @params 
	/// @return 
	protected function secure_common_check_login(){
		$objData = $this->params;
		$objData["m_idx"] = parent::getUserCookie();
        $result = empty($objData["m_idx"]) ? false : true;
		return parent::setResult($result,$objData,"");
	}

	/// @brief 리스트 페이지 
	/// @author ggugi
	/// @params 
	/// @return 
	protected function api_get_facebook_data(){
		$objData = $this->params;
		$objData["m_idx"] = $this->m_idx;
        $this->model->get_external_info($objData);
        $check_data = $this->model->fetch_table(
            array(
                "table" => "timeline",
                "field" => "h_external_uid",
                "where" => "source_from = 'FACEBOOK'"
            )
        );
        if(is_array($check_data)){
            foreach($check_data as $val){
                $temp[$val["h_external_uid"]] = true; 
            } 
        }else{
            $check_data = array();
        }
        $check_data = $temp;

        $mem_check = $this->model->fetch_table(
            array(
                "table" => "member",
                "field" => "m_facebook_uid,m_idx"
            )
        ); 

        if(is_array($mem_check)){
            foreach($mem_check as $val){
                $temp[$val["m_facebook_uid"]] = $val; 
            } 
        }else{
            $check_data = array();
        }
        $mem_check = $temp;

        $facebook_data = $this->facebook->graphGet($objData["external_info"]["FACEBOOK"]["e_access_token"],"me/feed",200);
        //showArray($facebook_data["data"]);
        $facebook_type = array("status" => "TEXT" , "video" => "VIDEO", "photo" => "PHOTO");
        foreach($facebook_data["data"] as $val){
            if($val["application"]["name"] == "ggugi") continue;
            if($val["type"] == "link" || $val["type"] == "photo" || $val["type"] == "video") continue;
            if($val["type"] == "status" && empty($val["message"])) continue;
            if($check_data[$val["id"]]) continue;
            /*
            if($val["type"] == "video"){
                $re = preg_replace("autoplay=1", $val["source"], "");
                showArray($matches);
                showArray($re);
                echo $val["source"];
                echo "xx";
                continue;
            }
            */
           
            $temp["content"] = addslashes($val["message"]);
            $temp["name"] = addslashes($val["from"]["name"]);
            $mem["m_facebook_uid"] = $val["from"]["id"];
            $temp["type"] = $facebook_type[$val["type"]];
            $temp["h_file"] = $val["source"];
            $temp["h_update_time"] = tstampToTime($val["updated_time"],"ISO8601");
            $temp["h_reg_time"] = tstampToTime($val["created_time"],"ISO8601");
            $temp["h_external_uid"] = $val["id"];
            $temp["source_from"] = "FACEBOOK";

            //insert member
            if(!is_array($mem_check[$mem["m_facebook_uid"]])){
                $result = $this->facebook->fqlGet(
                    $objData["external_info"]["e_access_token"],
                    array("query1" => "select uid, name ,pic_big,sex,locale from user where uid =".$mem["m_facebook_uid"])
                );
                $result = $result[0]["fql_result_set"][0];
                $mem["m_name"] = $temp["name"]; 
                $mem["m_profile_img"] = $result["pic_big"]; 
                $mem["m_sex"] = $result["sex"]; 
                $mem["m_locale"] = $result["locale"]; 
                $this->model->set_insert_member($mem);
                $mem_check[$mem["m_facebook_uid"]] = array(
                    "m_idx" =>$mem["m_idx"],
                    "m_facebook_uid" => $mem["m_facebook_uid"]
                );
                
            }
            $temp["m_idx"] = $mem_check[$mem["m_facebook_uid"]]["m_idx"];
            $this->model->set_status_insert($temp);

        }
		return parent::setResult(true,$objData,"");
	}

}



$_instance= new COMMON();

