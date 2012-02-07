<?php

class MysqlDB
{
	var $cn = NULL; ///< connector resource
	var $port = ""; //
	var $result = NULL; ///< result
	var $errno = 0; ///< 에러 발생시 에러 코드 (0이면 에러가 없다고 정의)
	var $errstr = ''; ///< 에러 발생시 에러 메세지
	var $query = ''; ///< 가장 최근에 수행된 query string
	var $transaction_started = false; ///< 트랙잭션 처리 flag
	var $is_connected = false; ///< DB에 접속이 되었는지에 대한 flag
	var $column_type = array(
            'bignumber' => 'bigint',
            'number' => 'bigint',
            'varchar' => 'varchar',
            'char' => 'char',
            'text' => 'text',
            'bigtext' => 'longtext',
            'date' => 'varchar(14)',
        );
	public function __construct() {
		//
	}

	public function setError( $e ) {
		echo $e;
	}

	public function isError() {
		//
	}

	public function connect( $hostname, $userid, $password, $dbname ) 
	{
		// db 정보가 없으면 무시
		if( ! $hostname || ! $userid || ! $password || ! $dbname ) return;

		// 접속시도  
		$this->cn = @mysql_connect( $hostname, $userid, $password );
		if(mysql_error()) {
			$this->setError(mysql_error(), mysql_errno());
			return;
		}

		// 버전 확인후 4.1 이하면 오류 표시
		if(mysql_get_server_info($this->cn)<"4.1") {
			$this->setError("cannot be installed under the version of mysql 4.1. Current mysql version is ".mysql_get_server_info(), -1);
			return;
		}

		// db 선택
		@mysql_select_db( $dbname, $this->cn);
		if(mysql_error()) {
			$this->setError(mysql_error(), mysql_errno());
			return;
		}

		// 접속체크
		$this->is_connected = true;

		// mysql의 경우 utf8임을 지정
		$this->Query("set names 'utf8'");
	}

	/**
	 * @brief DB접속 해제
	 **/
	public function close() {
		if(!$this->isConnected()) return;
		@mysql_close($this->cn);
	}

	/**
	 * @brief : 쿼리문의 실행 및 결과의 fetch 처리
	 *
	 * query : query문 실행하고 result return
	 * fetch : reutrn 된 값이 없으면 NULL
	 *         rows이면 array object
	 *         row이면 object
	 **/
	public function Query($query) 
	{
		if(!$this->isConnected()) return;

		$this->query = $query;

		// 쿼리 문 실행
		$result = @mysql_query($query, $this->cn);

		$this->result = $result;

		// 오류 체크
		if( mysql_error($this->cn) ) $this->setError( mysql_error( $this->cn ), mysql_errno($this->cn) );

		// 결과 리턴
		return $result;
	}
	
	/**
	 * @brief 결과를 fetch
	 **/
	public function fetch( $result = null ) 
	{
		if ( $result == null) {
			$result = $this->result;
		}

		if (!$this->isConnected() || $this->isError() || ! $result ) return null;
		$output = Array();

		while($tmp = mysql_fetch_object($result)) {
			$output[] = $tmp;
		}
		if(count($output)==1) return $output[0];
		return $output;
	}

	public function fetch_array($result) 
	{
		if (!$this->isConnected() || $this->isError() || !$result ) return;
		while($tmp = mysql_fetch_object($result)) {
			$output[] = $tmp;
		}
		//if(count($output)==1) return $output[0];
		return $output;
	}

	public function fetch_object($result)
	{
		if (!$this->isConnected() || $this->isError() || !$result ) return;
		return mysql_fetch_object($result);
	}

	/**
	 * @brief 쿼리에서 입력되는 문자열 변수들의 quotation 조절
	 **/
	public function addQuotes($string) 
	{
		if(get_magic_quotes_gpc()) $string = stripslashes(str_replace("\\","\\\\",$string));
		if(!is_numeric($string)) $string = @mysql_escape_string($string);
		return $string;
	}

	/**
	 * @brief 테이블 기생성 여부 return
	 **/
	public function isTableExists($target_name) 
	{
		$query = sprintf("show tables like '%s'", $this->addQuotes($target_name));
		$result = $this->Query($query);
		$tmp = $this->fetch($result);
		if(!$tmp) return false;
		return true;
	}

	
	/**
	 * @brief 특정 테이블에 특정 column 추가
	 **/
	public function addColumn($table_name, $column_name, $type='number', $size='', $default = '', $notnull=false) 
	{
		$type = $this->column_type[$type];
		if(strtoupper($type)=='INTEGER') $size = '';
		$query = sprintf("alter table %s add %s ", $table_name, $column_name);
		if($size) $query .= sprintf(" %s(%s) ", $type, $size);
		else $query .= sprintf(" %s ", $type);
		if($default) $query .= sprintf(" default '%s' ", $default);
		if($notnull) $query .= " not null ";

		$this->Query($query);
	}
	
	/**
	 * @brief 특정 테이블의 column의 정보를 return
	 **/
	public function isColumnExists($table_name, $column_name) 
	{
		$query = sprintf("show fields from %s", $table_name);
		$result = $this->Query($query);
		if($this->isError()) return;
		$output = $this->fetch($result);
		if($output) {
			$column_name = strtolower($column_name);
			foreach($output as $key => $val) {
				$name = strtolower($val->Field);
				if($column_name == $name) return true;
			}
		}
		return false;
	}

	/**
	 * @brief 특정 테이블의 index 정보를 return
	 **/
	public function isIndexExists($table_name, $index_name) 
	{
		$query = sprintf("show indexes from %s", $table_name);
		$result = $this->Query($query);
		if($this->isError()) return;
		$output = $this->fetch($result);

		for($i=0;$i<count($output);$i++) {
			if($output[$i]->Key_name == $index_name) return true;
		}
		return false;
	}

	public function insert_id()
	{
		return mysql_insert_id();
	}

	public function isConnected() { return $this->is_connected ? true : false; }

}
?>