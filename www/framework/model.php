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
		$this->db = oci_connect(_DB_USER,_DB_PASSWORD,_DB_HOST);
		if(!$this->db){
			die("oracle connection error");
		}
	}

	/// @brief
	/// @author
	/// @param
	/// @return
	public function __destruct(){
		ocilogoff($this->db);
	}

	/// @brief Query to some table.
	/// @author ggugi
	/// @param str query string 
	/// @return resource result
	public function query($sql){
		$result = oci_parse($this->db,$sql)or die(oci_error());
		oci_execute($result);
		return $result;
	}
	
	/// @brief
	/// @author
	/// @param
	/// @return
	public function fetch_row($sql){ 
		$result = $this->query($sql);
		while($row = oci_fetch_row($result)){
			$temp[]=$row;	
		}
		$temp = count($temp)> 1 ? $temp : $temp[0];
		return $temp;
	}

	/// @brief
	/// @author
	/// @param
	/// @return
	public function fetch_assoc($sql){ 
		$result = $this->query($sql);
		while($row = oci_fetch_assoc($result)){
			$temp[]=$row;	
		}
		$temp = count($temp)> 1 ? $temp : $temp[0];
		return $temp;
	}

	/// @brief
	/// @author
	/// @param
	/// @return
	public function result($sql){ 
		$result = $this->query($sql);
		$return = oci_fetch_row($result);
		return $return;
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
	////////////////////////////////////////////////////////////////////////////////////////
	//// CMS ADMIN MODEL 
	////////////////////////////////////////////////////////////////////////////////////////

	/// @brief 관리자 비밀번호 확인 처리 
	/// @author
	/// @param objData = {str user_id,str user_passwd}
	/// @return objData["result"] = return count of user 
	public function admin_login_check(&$objData){ 
		try{
			$sql = sprintf(
				"select ca_idx from cms_admin where user_id='%s' and user_passwd='%s'",
				$objData["user_id"],
				$objData["user_passwd"]
			);
			$result = $this->result($sql);
			$objData["ca_idx"] = $result[0];
			return true;
		}catch(Exception $e){
			showArray($e);
			return false;
		}
	}

	/// @brief 관리자 비밀번호 변경처리 
	/// @author
	/// @param objData = {str user_id, str current_user_passwd, str new_user_passwd}
	/// @return objData["result"] = return count of user 
	public function admin_passwd_change(&$objData){ 
		try{
			$sql = sprintf(
				"update cms_admin set 
				 user_passwd = '%s'
				 where user_id='%s' and user_passwd='%s'",
				$objData["new_user_passwd"],
				$objData["user_id"],
				$objData["current_user_passwd"]
			);
			$result = $this->result($sql);
			$objData["result"] = $result[0];
			return true;
		}catch(Exception $e){
			showArray($e);
			return false;
		}
	}

	/// @brief 관리자 게시판 글목록
	/// @author ggugi
	/*/ @param objData = {
			int cb_idx,
			str subject,
			str content,
			int cc_idx,
			int ca_idx 
	*/// }
	/// @return objData["result"] = return count of user 
	public function admin_bbs_list(&$objData){ 
		try{
			$sql = sprintf(
				"select * from cms_board where cc_idx=%d order by reg_time desc",
				$objData["cc_idx"]
			);
			$objData["bbs_list"] = $this->fetch_assoc($sql);
			return true;
		}catch(Exception $e){
			showArray($e);
			return false;
		}
	}


	/// @brief 관리자 게시판 글내용
	/// @author ggugi
	/*/ @param objData = {
			int cb_idx,
			str subject,
			str content,
			int cc_idx,
			int ca_idx 
	*/// }
	/// @return objData["result"] = return count of user 
	public function admin_bbs_view(&$objData){ 
		try{
			$sql = sprintf(
				"select DBMS_LOB.substr(CONTENT, dbms_lob.getlength(CONTENT),1) as CONT , SUBJECT, UPDATE_TIME,VIEW_COUNT from cms_board where cb_idx=%d",
				$objData["cb_idx"]
			);
			$objData["bbs_view"] = $this->fetch_assoc($sql);
			$objData["bbs_view"]["CONT"] = stripslashes($objData["bbs_view"]["CONT"]);
			return true;
		}catch(Exception $e){
			showArray($e);
			return false;
		}
	}

	/// @briefi 관리자 게시판 글입력
	/// @author
	/*/ @param objData = {
			str subject,
			str content,
			int cc_idx,
			int ca_idx 
	*/// }
	/// @return objData["result"] = return count of user 
	public function admin_bbs_insert(&$objData){ 
		try{
			$sql = sprintf(
				"insert into cms_board(
				 subject,
				 content,
				 cc_idx,
				 ca_idx,
				 view_count,
				 update_time,
				 reg_time
				 )values(
					'%s',
					'%s',
					'%d',
					'%d',
					'%d',
					'%d',
					'%d'
				 )
				 
				 ",
				$objData["subject"],
				addslashes($objData["content"]),
				$objData["cc_idx"],
				$objData["ca_idx"],
				1,
				time(),
				time()
			);
			$this->query($sql);
			return true;
		}catch(Exception $e){
			showArray($e);
			return false;
		}
	}

	/// @brief 관리자 게시판 글수정
	/// @author ggugi
	/*/ @param objData = {
			int cb_idx,
			str subject,
			str content,
			int cc_idx,
			int ca_idx 
	*/// }
	/// @return objData["result"] = return count of user 
	public function admin_bbs_update(&$objData){ 
		try{
			$sql = sprintf(
				"update cms_board set
				 subject	= '%s',
				 content	= '%s',
				 update_time= %d
				 where
				 cb_idx		= %d",
				$objData["subject"],
				$objData["content"],
				time(),
				$objData["cb_idx"]
			);
			$this->query($sql);
			return true;
		}catch(Exception $e){
			showArray($e);
			return false;
		}
	}

	/// @brief 관리자 게시판 글삭제
	/// @author ggugi
	/*/ @param objData = {
			int ca_idx,
			int cb_idx
	*/// }
	/// @return objData["result"] = return count of user 
	public function admin_bbs_delete(&$objData){ 
		try{
			$sql = sprintf(
				"delete from cms_board where cb_idx=%d and ca_idx=%d",
				$objData["cb_idx"],
				$objData["ca_idx"]
			);
			$this->query($sql);
			return true;
		}catch(Exception $e){
			showArray($e);
			return false;
		}
	}

	/// @brief 카테고리 목록 가져오기
	/// @author ggugi
	/// @param objData = {}
	/// @return objData["result"] = return count of user 
	public function admin_category_list(&$objData){ 
		try{
			$sql = sprintf("select * from cms_category order by sort asc");
			$objData["category_list"] = $this->fetch_assoc($sql);
			return true;
		}catch(Exception $e){
			showArray($e);
			return false;
		}
	}

	/// @brief 카테고리 등록
	/// @author ggugi
	/// @param objData = {}
	/// @return objData["result"] = return count of user 
	public function admin_category_insert(&$objData){ 
		try{
			$sql = sprintf(
				"insert into cms_category set 
				 catetory_title = "
			);
			$objData["category_list"] = $this->fetch_assoc($sql);
			return true;
		}catch(Exception $e){
			showArray($e);
			return false;
		}
	}
	////////////////////////////////////////////////////////////////////////////////////////
	//// CMS BOARD MODEL 
	////////////////////////////////////////////////////////////////////////////////////////

	/// @brief 관리자 게시판 글목록
	/// @author ggugi
	/*/ @param objData = {
			int cb_idx,
			str subject,
			str content,
			int cc_idx,
			int ca_idx 
	*/// }
	/// @return objData["result"] = return count of user 
	public function cms_bbs_list(&$objData){ 
		try{
			$sql = sprintf(
				//"SELECT * FROM (SELECT ROWNUM RNUM, CMS_BOARD.* FROM CMS_BOARD WHERE CC_IDX=%d ORDER BY REG_TIME DESC) A
				//WHERE A.RNUM BETWEEN %d AND %d",
				"select * from cms_board where cc_idx=%d order by reg_time desc",
				$objData["cc_idx"]
			);
			$objData["bbs_list"] = $this->fetch_assoc($sql);
			return true;
		}catch(Exception $e){
			showArray($e);
			return false;
		}
	}

	/// @brief 관리자 게시판 글목록
	/// @author ggugi
	/*/ @param objData = {
			int cb_idx,
			str subject,
			str content,
			int cc_idx,
			int ca_idx 
	*/// }
	/// @return objData["result"] = return count of user 
	public function cms_bbs_list_count(&$objData){ 
		try{
			$sql = sprintf(
				"SELECT count(*) FROM CMS_BOARD WHERE CC_IDX=%d)",
				$objData["cc_idx"]
			);
			$objData["bbs_count"] = $this->fetch_row($sql);
			return true;
		}catch(Exception $e){
			showArray($e);
			return false;
		}
	}

	/// @brief 관리자 게시판 글내용
	/// @author ggugi
	/*/ @param objData = {
			int cb_idx,
			str subject,
			str content,
			int cc_idx,
			int ca_idx 
	*/// }
	/// @return objData["result"] = return count of user 
	public function cms_bbs_view(&$objData){ 
		try{
			$sql = sprintf(
				"select DBMS_LOB.substr(CONTENT, dbms_lob.getlength(CONTENT),1) as CONT , SUBJECT, UPDATE_TIME,VIEW_COUNT from cms_board where cb_idx=%d",
				$objData["cb_idx"]
			);
			$objData["bbs_view"] = $this->fetch_assoc($sql);
			$objData["bbs_view"]["CONT"] = stripslashes($objData["bbs_view"]["CONT"]);
			return true;
		}catch(Exception $e){
			showArray($e);
			return false;
		}
	}

	/// @brief 카테고리 목록 가져오기
	/// @author ggugi
	/// @param objData = {}
	/// @return objData["result"] = return count of user 
	public function cms_category_info(&$objData){ 
		try{
			$sql = sprintf("select * from cms_category where cc_idx=%s",$objData["cc_idx"]);
			$objData["category_info"] = $this->fetch_assoc($sql);
			return true;
		}catch(Exception $e){
			showArray($e);
			return false;
		}
	}



	/*
	/// @brief
	/// @author
	/// @param objData = {}
	/// @return objData["result"] = return count of user 
	public function (&$objData){ 
		try{
			$sql = sprintf(
				"select count(*) from cms_admin where user_id='%s' and user_passwd='%s'",
				$objData["user_id"],
				$objData["user_passwd"]
			);
			$objData["result"] = $this->fetch_row($sql);
			return true;
		}catch(Exception $e){
			showArray($e);
			return false;
		}
	}

*/	
}


?>
