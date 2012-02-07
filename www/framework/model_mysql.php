<?php
/// @brief Define Model class
/// @author ggugisky 
/// @param 
/// @return
class MODEL{
	protected $dbconn;
	/// @brief
	/// @author
	/// @param
	/// @return
	public function __construct(){
		$this->db = new mysqli(_DB_HOST,_DB_USER,_DB_PASSWORD,_DB_NAME);
		$this->db->query("SET NAMES utf8");
	}

	/// @brief
	/// @author
	/// @param
	/// @return
	public function __destruct(){
		$this->db->close();
		
	}

	/// @brief Query to some table.
	/// @author ggugi
	/// @param str query string 
	/// @return resource result
	public function query($sql){
		return $this->db->query($sql)or die($this->db->error);
	}
	
	/// @brief
	/// @author
	/// @param
	/// @return
	public function fetch_row($sql, $restruct="auto_restruct"){ 
		$result = $this->db->query($sql)or die($this->db->error);
		while($row = $result->fetch_row()){
			$temp[]=$row;	
		}
		if($restruct=="auto_restruct" && count($temp) == 1){
			$temp=$temp[0];
		}
		return $temp;
	}

	/// @brief
	/// @author
	/// @param
	/// @return
	public function fetch_assoc($sql,$restruct="auto_restruct"){ 
		$result = $this->db->query($sql)or die($this->db->error);
		while($row = $result->fetch_assoc()){
			$temp[]=$row;	
		}

		if($restruct=="auto_restruct" && count($temp) == 1){
			$temp=$temp[0];
		}
		
		return $temp;
	}

	/// @brief
	/// @author
	/// @param objData = {str table , str field , str case, str where, str order , str sort}
	/// @param start_postion 
	/// @param limit 
	/// @return
	public function fetch_table($objData,$limit=0,$start_postion=0){ 
		$objData["field"] = is_null($objData["field"]) ? "*" : $objData["field"];
		$objData["sort"] = is_null($objData["sort"]) ? "asc" : $objData["sort"];
		$sql = sprintf(	
			"select %s from %s where 1=1 ",
			$objData["field"],
			$objData["table"]
		);

		if(!is_null($objData["where"])){
			$sql = sprintf("%s and %s",$sql,$objData["where"]);
		}

		if(!is_null($objData["order"])){
			$sql = sprintf(
				"%s order by %s %s",
				$sql,
				$objData["order"],
				$objData["sort"]
			);
		}

		if($limit>0){
			$sql = sprintf("%s limit %s %s",$sql,$start_postion,$limit);
		}
		return $this->fetch_assoc($sql);
	}


	///////////////////////////////////////////////////////////////////////////////////////
	///user define model
	///////////////////////////////////////////////////////////////////////////////////////

	/// @brief
	/// @author
	/// @param objData = {str table , str field , str case, str where, str order , str sort}
	/// @param start_postion 
	/// @param limit 
	/// @return
	public function get_timeline_list(&$objData,$limit=0,$start_postion=0){ 
		$sql = sprintf(
					"select t.*,m.m_name,m.m_profile_img from timeline as t left outer join member as m 
					 on
					 t.m_idx = m.m_idx
					 where 
					 t.is_show=1 
                     and
                     t.source_from<>'SCRAP'
					 and
					 (t.h_level >= %d or t.m_idx = %d)
					 order by t.h_update_time desc limit %d,%d",
					$objData["member_info"]["m_level"],
					$objData["m_idx"],
					$objData["start_pos"],
					$objData["count"]
				);
		$objData["timeline_list"]=$this->fetch_assoc($sql,"no_restruct");
		return ture;
	}





	/// @brief
	/// @author
	/// @param objData = {str table , str field , str case, str where, str order , str sort}
	/// @param start_postion 
	/// @param limit 
	/// @return
	public function get_timeline_count(&$objData,$limit=0,$start_postion=0){ 
		$sql = sprintf(
					"select count(*) from timeline
					 where 
					 is_show=1" 
				);
		$timeline_count=$this->fetch_row($sql);
		$objData["timeline_count"]=$timeline_count[0];
		return ture;
	}



	/// @brief
	/// @author
	/// @param objData = {str table , str field , str case, str where, str order , str sort}
	/// @param start_postion 
	/// @param limit 
	/// @return
	public function get_comment_list(&$objData){ 
		$sql = sprintf(
					"select p.*,m.m_profile_img from post_comment as p left outer join member m 
					 on 
					 p.m_idx = m.m_idx

					where p.h_uid in(%s)
					order by p.c_reg_time asc
					",
					implode(",",$objData["h_uids"])
		);
		$list = $this->fetch_assoc($sql,"no_restruct");
		$list = count($list)>0 ? $list : array(); 
		$temp = array();
		foreach($list as $val){
			$temp[$val[h_uid]][] = $val;  	
		}
		$objData["comment_list"] = $temp;
		return ture;
	}

	/// @brief
	/// @author
	/// @param objData = {str table , str field , str case, str where, str order , str sort}
	/// @param start_postion 
	/// @param limit 
	/// @return
	public function set_status_insert(&$objData){ 
		$sql = sprintf(
					"insert into timeline set
					 m_idx		= %d,
					 h_content	= '%s',
					 h_name		= '%s',
					 h_reg_time = %d,
					 h_update_time = %d,
					 h_type		= '%s',
					 h_file		= '%s',
					 h_external_uid	 = '%s',
					 is_show	= 1,
					 source_from= '%s'
					",
					$objData["m_idx"],
					$objData["content"],
					$objData["name"],
					$objData["h_reg_time"],
					$objData["h_update_time"],
					$objData["type"],
					$objData["h_file"],
					$objData["h_external_uid"],
					$objData["source_from"]
		);
		$this->query($sql);

		$this->point_update($objData,"STATUS");
		return ture;
	}

	/// @brief
	/// @author
	/// @param objData = {str table , str field , str case, str where, str order , str sort}
	/// @param start_postion 
	/// @param limit 
	/// @return
	public function point_update(&$objData,$type){ 
		$point_arr = array(
			"STATUS"	=> 30,
			"EMOTION"	=> 5,
			"SHARE"		=> 20,
			"VISITE"	=> 1,
			"COMMENT"	=> 20,
			"SCRAP"		=> 20,
			"SYNC"		=> 30,
		);
		//point
		$point_sql =sprintf(
			"insert into point set
			 m_idx		= %d,
			 p_type		= '%s',
			 p_point	= %d,
			 p_reg_time = %d
			",
			$objData["m_idx"],
			$type,
			$point_arr[$type],
			time()
		);
		$this->query($point_sql);

		//update total_point
		$point_sql = sprintf("update member set m_total_point=m_total_point+%d where m_idx=%d;",$point_arr[$type],$objData["m_idx"]);
		$this->query($point_sql);

		if($type == "EMOTION"){
			$point_sql = sprintf(
				"update member set m_emotion_point=m_emotion_point+%d where m_idx=%d;",
				$objData["em_emotion"],$objData["m_idx"]
			);
			$this->query($point_sql);
		}

		$sql = sprintf("select m_total_point, m_emotion_point,m_level from member where m_idx=%d",$objData["m_idx"]);
		$result = $this->fetch_row($sql);
		$objData["m_total_point"] = $result[0];
		$objData["m_emotion_point"] = $result[1];
		$objData["m_level"] = $result[2];

		//update level by point
		$point_arr = array("","",10000000000000,128000,32000,8000,2000);
		if($point_arr[$result[2]] <= $result[0]){
			$sql = sprintf(
				"update member set m_level=m_level-1,m_update_time=%d where m_idx=%d",
				 time(),
				 $objData["m_idx"]
			);
			$this->query($sql);
			$objData["m_level"]--;
		}
		return true;



	}

	/// @brief
	/// @author
	/// @param objData = {str table , str field , str case, str where, str order , str sort}
	/// @param start_postion 
	/// @param limit 
	/// @return
	public function remove_timeline(&$objData){ 
		$sql = sprintf(
					"update timeline set
					 is_show	= 0,
					 h_update_time = %d
					 where
					 m_idx		= %d
					 and
					 h_uid		= %d
					",
					time(),
					$objData["m_idx"],
					$objData["h_uid"]
		);
		$this->query($sql);
		return ture;
	}

	/// @brief
	/// @author
	/// @param objData = {str table , str field , str case, str where, str order , str sort}
	/// @param start_postion 
	/// @param limit 
	/// @return
	public function set_timeline_emotion(&$objData){ 
		$sql = sprintf(
					"insert into emotion set
					 m_idx		= %d,
					 h_uid		= %d,
					 em_emotion	= %d,
					 em_reg_time = %d,
					 em_update_time = %d
					",
					$objData["m_idx"],
					$objData["h_uid"],
					$objData["em_emotion"],
					time(),
					time()
		);
		$this->query($sql);
		$this->point_update($objData,"EMOTION");
		return ture;
	}

	/// @brief
	/// @author
	/// @param objData = {str table , str field , str case, str where, str order , str sort}
	/// @param start_postion 
	/// @param limit 
	/// @return
	public function set_timeline_level(&$objData){ 
		$sql = sprintf(
					"update timeline set
					 h_level = %d
					 where 
					 m_idx	 = %d
					 and
					 h_uid	= %d
					",
					$objData["h_level"],
					$objData["m_idx"],
					$objData["h_uid"]
		);
		$this->query($sql);
		return ture;
	}

	/// @brief
	/// @author
	/// @param objData = {str table , str field , str case, str where, str order , str sort}
	/// @param start_postion 
	/// @param limit 
	/// @return
	public function get_timeline_emotion(&$objData){ 
		$sql = sprintf(
					"select e.*,m.m_profile_img from emotion as e left outer join member m 
					 on 
					 e.m_idx = m.m_idx

					where 
					e.h_uid in(%s) 
					and
					e.m_idx = '%s'",
					implode(",",$objData["h_uids"]),
					$objData["m_idx"]
		);
		$list = $this->fetch_assoc($sql,"no_restruct");
		$list = count($list)>0 ? $list : array(); 
		$temp = array();
		foreach($list as $val){
			$temp[$val[h_uid]][] = $val;  	
		}
		$objData["emotion_list"] = $temp;
		return ture;
	}

	///////////////////////////////////////////////////////////////////////////////////////
	///common model
	///////////////////////////////////////////////////////////////////////////////////////

	/// @brief
	/// @author
	/// @param objData = {str table , str field , str case, str where, str order , str sort}
	/// @param start_postion 
	/// @param limit 
	/// @return
	public function set_insert_member(&$objData){ 
			$sql = sprintf(
				"insert into member set
					m_level			= 6,
					m_name			= '%s',
					m_email			= '%s',
					m_sex			= '%s',
					m_birthday		= '%s',
					m_facebook_uid	= '%s',
					m_profile_img	= '%s',
					m_locale	    = '%s',
					m_update_time	= %d,
					m_reg_time		= %d	
				",
				$objData["m_name"],
				$objData["m_email"],
				$objData["m_sex"],
				$objData["m_birthday"],
				$objData["m_facebook_uid"],
				$objData["m_profile_img"],
				$objData["m_locale"],
				time(),
				time()
			);	
			$this->query($sql);

            $last_idx = $this->fetch_row(sprintf("select m_idx from member order by m_reg_time desc limit 0,1"));
            $objData["m_idx"] = $last_idx[0];
            return true;
    }

	///////////////////////////////////////////////////////////////////////////////////////
	///common model
	///////////////////////////////////////////////////////////////////////////////////////

	/// @brief
	/// @author
	/// @param objData = {str table , str field , str case, str where, str order , str sort}
	/// @param start_postion 
	/// @param limit 
	/// @return
	public function insert_member_by_facebook(&$objData){ 
		$check_external = $this->fetch_row(sprintf("select m_idx,m_email from member where m_facebook_uid='%s'",$objData["e_sns_uid"])); 
        $objData["m_idx"] = $check_external[0];
		if(empty($objData["m_idx"])){
			$sql = sprintf(
				"insert into member set
					m_level			= 6,
					m_name			= '%s',
					m_email			= '%s',
					m_sex			= '%s',
					m_birthday		= '%s',
					m_profile_img	= '%s',
					m_facebook_uid	= '%s',
					m_locale	    = '%s',
					m_update_time	= %d,
					m_reg_time		= %d	
				",
				$objData["m_name"],
				$objData["m_email"],
				$objData["m_sex"],
				$objData["m_birthday"],
				$objData["m_profile_img"],
				$objData["e_sns_uid"],
				$objData["e_sns_uid"],
				$objData["m_locale"],
				time(),
				time()
			);	
			$this->query($sql);

            $last_idx = $this->fetch_row(sprintf("select m_idx from member order by m_reg_time desc limit 0,1"));
            $objData["m_idx"] = $last_idx[0];
			$this->point_update($objData,"SYNC");

			$sql = sprintf(
				"insert into external set
					e_name			= '%s',
					m_idx			= %d,
					e_sns_uid		= '%s',
					e_access_token	= '%s',
					e_email			= '%s',
					e_profile_img	= '%s',
					e_type			= '%s',
					e_update_time	= %d,
					e_reg_time		= %d	
				",
				$objData["m_name"],
				$objData["m_idx"],
				$objData["e_sns_uid"],
				$objData["e_access_token"],
				$objData["m_email"],
				$objData["m_profile_img"],
				$objData["type"],
				time(),
				time()
			);	
			$this->query($sql);
		}else{
		    $sql = sprintf(
				"update member set
					m_name			= '%s',
					m_email			= '%s',
					m_sex			= '%s',
					m_birthday		= '%s',
					m_profile_img	= '%s',
					m_update_time	= %d
                    where
                    m_idx           = %d
				",
				$objData["m_name"],
				$objData["m_email"],
				$objData["m_sex"],
				$objData["m_birthday"],
				$objData["m_profile_img"],
				time(),
				time(),
                $objData["m_idx"]
			);	
			$this->query($sql);

            if(!empty($check_external[1])){
                $sql = sprintf(
                    "update external set
                        e_name			= '%s',
                        e_sns_uid   	= '%s',
                        e_access_token	= '%s',
                        e_email			= '%s',
                        e_profile_img	= '%s',
                        e_update_time	= %d
                        where 
                        m_idx = %d
                        and
                        e_type = '%s'
                    ",
                    $objData["m_name"],
                    $objData["e_sns_uid"],
                    $objData["e_access_token"],
                    $objData["m_email"],
                    $objData["m_profile_img"],
                    time(),
                    $objData["m_idx"],
                    $objData["type"]
                );	
            }else{
                $sql = sprintf(
                    "insert into external set
                        e_name			= '%s',
                        m_idx			= %d,
                        e_sns_uid		= '%s',
                        e_access_token	= '%s',
                        e_email			= '%s',
                        e_profile_img	= '%s',
                        e_type			= '%s',
                        e_update_time	= %d,
                        e_reg_time		= %d	
                    ",
                    $objData["m_name"],
                    $objData["m_idx"],
                    $objData["e_sns_uid"],
                    $objData["e_access_token"],
                    $objData["m_email"],
                    $objData["m_profile_img"],
                    $objData["type"],
                    time(),
                    time()
                );	
            
            }

			$this->query($sql);
        }
		return true;
	}


	/// @brief
	/// @author
	/// @param objData = {str table , str field , str case, str where, str order , str sort}
	/// @param start_postion 
	/// @param limit 
	/// @return
	public function async_member(&$objData){ 
		$check_external = $this->fetch_row(sprintf("select count(m_idx) from external where e_sns_uid='%s'",$objData["e_access_token"])); 
		if($check_external[0]<1){
			$sql = sprintf(
				"insert into external set
					e_name			= '%s',
					m_idx			= %d,
					e_sns_uid		= %d,
					e_access_token	= '%s',
					e_email			= '%s',
					e_profile_img	= '%s',
					e_type			= '%s',
					e_update_time	= %d,
					e_reg_time		= %d	
				",
				$objData["m_name"],
				$objData["m_idx"],
				$objData["e_sns_uid"],
				$objData["e_access_token"],
				$objData["m_email"],
				$objData["m_profile_img"],
				$objData["type"],
				time(),
				time()
			);	
			$this->query($sql);
			$this->point_update($objData,"SYNC");
		}else{
			$sql = sprintf(
				"update external set
					e_name			= '%s',
					e_sns_uid   	= '%s',
					e_access_token	= '%s',
					e_email			= '%s',
					e_profile_img	= '%s',
					e_update_time	= %d
                    where 
                    m_idx = %d
					and
					e_type			= '%s'
				",
				$objData["m_name"],
				$objData["e_sns_uid"],
				$objData["e_access_token"],
				$objData["m_email"],
				$objData["m_profile_img"],
				time(),
				$objData["type"],
				$objData["m_idx"]
			);	
			$this->query($sql);
        }
		return true;
	}

	/// @brief
	/// @author
	/// @param objData = {str table , str field , str case, str where, str order , str sort}
	/// @param start_postion 
	/// @param limit 
	/// @return
	public function get_member_info(&$objData){ 
		$sql = sprintf("select * from member where m_idx=%d",$objData["m_idx"]);
		$objData["member_info"] = $this->fetch_assoc($sql);
		return true;
	}
	
	/// @brief
	/// @author
	/// @param objData = {str table , str field , str case, str where, str order , str sort}
	/// @param start_postion 
	/// @param limit 
	/// @return
	public function get_external_info(&$objData){ 
		$sql = sprintf("select * from external where m_idx=%d",$objData["m_idx"]);
		$result = $this->fetch_assoc($sql,"no_restruct");
		foreach($result as $val){
			$temp[$val["e_type"]] = $val; 
		}
		$objData["external_info"] = $temp;
		return true;
	}

	/// @brief
	/// @author
	/// @param objData = {str table , str field , str case, str where, str order , str sort}
	/// @param start_postion 
	/// @param limit 
	/// @return
	public function set_comment_add(&$objData){ 
		$sql = sprintf(
			"insert into post_comment set 
			 h_uid	= %d,
			 m_idx	= %d,
			 c_name = '%s',
			 c_content = '%s',
			 c_update_time = %d,
			 c_reg_time = %d",
			$objData["h_uid"],
			$objData["m_idx"],
			$objData["member_info"]["m_name"],
			$objData["c_content"],
			time(),
			time()
			);
		$result = $this->query($sql);
		$this->point_update($objData,"COMMENT");
		$objData["external_info"] = $temp;
		return true;
	}

	///////////////////////////////////////////////////////////////////////////////////////
	///scrap model
	///////////////////////////////////////////////////////////////////////////////////////

	/// @brief
	/// @author
	/// @param objData = {str table , str field , str case, str where, str order , str sort}
	/// @param start_postion 
	/// @param limit 
	/// @return
	public function set_scrap_insert(&$objData){ 
        try{
		$result = changeImgFromSource($objData["content"],"1","1");
		$sql = sprintf(
			"insert into timeline set
			 	h_url 	= '%s',
			 	h_title 		= '%s',
			 	h_content 		= '%s',
				h_reg_time 		= '%d',
				h_update_time 	= '%d',
				h_copyright 	= '%s',
				m_idx			= '%s',
				source_from		= 'SCRAP',
				h_type			= 'SCRAP',
				h_tag			= '%s',
				h_level			= 5,
				is_show			= 1,
				h_name			= '%s'
			",
			$objData["content_url"],
			$objData["subjects"],
			addslashes($result["article_content"]),
			time(),
			time(),
			$objData["copyright"],
			$objData["m_idx"],
			$objData["tag"],
			$objData["member_info"]["m_name"]
			);
		$result = $this->query($sql);
		$this->point_update($objData,"SCRAP");
		return true;
        }catch(Exception $e){
            showArray($e);
            return false;
        }
	}

	/// @brief
	/// @author
	/// @param objData = {str table , str field , str case, str where, str order , str sort}
	/// @param start_postion 
	/// @param limit 
	/// @return
	public function get_paper_list(&$objData,$limit=0,$start_postion=0){ 
		$sql["DIARY"] = sprintf(
					"select t.*,m.m_name,m.m_profile_img from timeline as t left outer join member as m 
					 on
					 t.m_idx = m.m_idx
					 where 
					 t.m_idx =%d
					 and
					 t.is_show=1 
					 and
					 t.source_from = 'GGUGI'
					 order by t.h_update_time desc limit 0,900",
					 $objData["m_idx"]
				);
	
		$sql["TAG"] = sprintf(
					"select t.*,m.m_name,m.m_profile_img from timeline as t left outer join member as m 
					 on
					 t.m_idx = m.m_idx
					 where 
					 t.h_tag='%s'
					 and
					 t.source_from = 'SCRAP'
					 order by t.h_update_time desc limit 0,900",
					 $objData["h_tag"]
				);

		$sql["SCRAP"] = sprintf(
					"select t.*,m.m_name,m.m_profile_img from timeline as t left outer join member as m 
					 on
					 t.m_idx = m.m_idx
					 where 
					 t.m_idx=%d
					 and
					 t.source_from = 'SCRAP'
					 order by t.h_update_time desc limit 0,900",
					 $objData["m_idx"]
				);
		$sql["SNS"] = sprintf(
					"select t.*,m.m_name,m.m_profile_img from timeline as t left outer join member as m 
					 on
					 t.m_idx = m.m_idx
					 where 
					 t.is_show=1 
					 and
					 (
					 	t.source_from = 'FACEBOOK'
					 	or
					 	t.source_from = 'TWITTER'
					 )
					 order by t.h_update_time desc limit 0,900"
				);
		$sql["BLOG"] = sprintf(
					"select t.*,m.m_name,m.m_profile_img from timeline as t left outer join member as m 
					 on
					 t.m_idx = m.m_idx
					 where 
					 t.is_show=1 
					 and
					 (
					 	t.source_from = 'NAVER'
					 	or
					 	t.source_from = 'TISTORY'
					 	or
					 	t.source_from = 'EGLOOS'
					 	or
					 	t.source_from = 'DAUM'
					 	or
					 	t.source_from = 'NAVER'
					 )
					 order by t.h_update_time desc limit 0,900"
				);
		$objData["paper_list"]=$this->fetch_assoc($sql[$objData["type"]],"no_restruct");
		return ture;
	}



	/// @brief
	/// @author
	/// @param objData = {str table , str field , str case, str where, str order , str sort}
	/// @param start_postion 
	/// @param limit 
	/// @return
	public function get_tag_list(&$objData,$limit=0,$start_postion=0){ 
		$sql = sprintf(
					"select h_tag,count(*) as cnt from timeline 
					 where 
					 source_from = 'SCRAP'
					 and
					 h_tag <> ''
					 group by h_tag"
				);
		$objData["tag_list"]=$this->fetch_assoc($sql,"no_restruct");
		return ture;
	}

	
}


?>
