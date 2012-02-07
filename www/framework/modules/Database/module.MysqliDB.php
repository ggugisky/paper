<?php

class RecordSet 
{
	private $_queryID = null;
	private $_currentRow = -1;
	private $_numOfRows = -1;
	private $_numOfFields = -1;
	
	var $fetchMode = MYSQLI_BOTH; // MYSQLI_ASSOC, MYSQLI_NUM, MYSQLI_BOTH
	var $EOF = false;
	var $fields = null;
	
	
	function __construct( &$result )
	{
		$this->_queryID = &$result;
		
		$this->_init();
	}
	
	function __destruct()
	{
		$this->close();
	}
	
	function _init() 
	{
		if ( ! $this->_queryID ) {
			$this->fields = null;
			$this->EOF = true;
			return;
		}
		
		$this->_numOfRows = @mysqli_num_rows($this->_queryID);
		$this->_numOfFields = @mysqli_num_fields($this->_queryID);
	}
	
	function _fetch() 
	{
		if ( gettype( $this->_queryID ) == "boolean" ) {
			$this->fields = null;
			$this->EOF  = true;
			
			return $this->_queryID;
		}
		
		$this->fields = mysqli_fetch_array($this->_queryID, $this->fetchMode);
		
		$this->EOF = ( is_array( $this->fields ) ) ? false : true;
		
		return ( $this->EOF ) ? false : true;
	}
	
	function getCount()
	{
		return $this->_numOfRows;
	}
	
	function getObject()
	{
		if ( ! $this->fields ) {
			return null;
		}
		
		$object = Array();
		
		foreach( $this->fields as $key => $value ) {
			if ( ! is_numeric( $key ) ) {
				$object[$key] = $value;
			}
		}
		
		return $object;
	}
	
	function close()
	{
		if ( $this->_queryID ) {
			@mysqli_free_result( $this->_queryID );
		}
		
		$this->_queryID = null;
	}
	
	function fetch()
	{
		if ( ! $this->_queryID ) {
			$this->fields = null;
			return false;
		}
		
		$this->_currentRow = 0;
		return $this->_fetch();
	}
	
	function MoveNext() 
	{
		if (!$this->EOF) {
			$this->_currentRow++;
			if ($this->_fetch()) return true;
		}
		
		$this->EOF = true;
		return false;
	}	
}

class ConnectionObject
{
	private $_connectionID;
	private $_startTime;
	private $_endTime;
	private $_query;
	private $_error = '';
	
	function __construct( $autocommit = 0 )
	{
		$this->_startTime = microtime(true);
		
		$this->_connectionID = &mysqli_init();
		
		if (!$this->_connectionID) {
		    die('mysqli_init failed');
		}
		
		if (!$this->_connectionID->options(MYSQLI_INIT_COMMAND, 'SET AUTOCOMMIT = '.$autocommit)) {
		    die('Setting MYSQLI_INIT_COMMAND failed');
		}
		
		if (!$this->_connectionID->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5)) {
		    die('Setting MYSQLI_OPT_CONNECT_TIMEOUT failed');
		}
	}
	
	function __destruct()
	{
		$this->close();
	}
	
	function getError()
	{
		return $this->_error;
	}
	
	function connect( $hostName, $userName, $password, $databaseName )
	{
		if (!$this->_connectionID->real_connect($hostName, $userName, $password, $databaseName )) {
		    die('Connect Error (' . mysqli_connect_errno() . ') '
		            . mysqli_connect_error());
		}
	}
	
	function query( $query )
	{
		$this->_query = $query;
		
		$result = mysqli_query($this->_connectionID, $query, MYSQLI_STORE_RESULT );
		
		if ( ! $result ) {
			$this->_error = mysqli_error($this->_connectionID);
		}
		
		$this->close();
		
		if ( $result ) {
			return $result;
		}
		
		return false;
	}
	
	function close()
	{
		if ( $this->_connectionID ) {
			$this->_connectionID->close();
			$this->_connectionID = null;
			
			$this->_endTime = microtime(true) - $this->_startTime;
		}
	}
	
	function prepare( $value )
	{
		 $result = $this->_connectionID->real_escape_string($value);
		 
		 $this->close();
		 
		 return $result;
	}
	
	function debugLog()
	{
		$log = Array();
		$log[] = "";
		$log[] = "	EXCUTE QUERY     : ".$this->_query;
		$log[] = "	EXCUTE TIME      : ".$this->_endTime;
		$log[] = "";
		return $log;
	}
}

class MysqliDB
{
	private $_hostName;
	private $_userName;
	private $_password;
	private $_databaseName;
	private $_connectionObjects;
	private $_startTime;
	private $_endTime;
	private $_debugLog;
	var $connectoinCount = 0;
	
	function __construct( $hostName, $userName, $password, $databaseName )
	{
		$this->_startTime = microtime(true);
		
		$this->_hostName = $hostName;
		$this->_userName = $userName;
		$this->_password = $password;
		$this->_databaseName = $databaseName;
		$this->_connectionObjects = Array();
		$this->_debugLog = Array();
	}
	
	function __destruct()
	{
		$this->Close();
	}
	
	function _createConnectionObject( $autocommit ) 
	{
		$connection = new ConnectionObject( $autocommit );
		$connection->connect( $this->_hostName, $this->_userName, $this->_password, $this->_databaseName );
		$this->_connectionObjects[] = $connection;
		
		$this->connectoinCount = count($this->_connectionObjects);
		
		return $connection;
	}
	
	function Execute( $query, $autocommit = 1 )
	{
		$connection = $this->_createConnectionObject( $autocommit );
		$result = $connection->query( $query );
		
		$recordSet = new RecordSet( $result );
		$recordSet->fetch();
		
		return $recordSet;
	}
	
	function Close()
	{
		for( $i=0; $i<$this->connectoinCount; $i++ ) {
			$this->_connectionObjects[$i]->close();
		}
		
		$this->connectoinCount = count($this->_connectionObjects);
		
		$this->_endTime = microtime(true) - $this->_startTime;
	}
	
	function useDatabase( $databaseName )
	{
		$this->_databaseName = $databaseName;
	}
	
	function prepare($value, $do_like = false)
	{
		$connection = $this->_createConnectionObject( true );
		
		if ($do_like) {
			$value = str_replace(array('%', '_'), array('\%', '\_'), $value);
		}
		
		return $connection->prepare( $value );
	}
	
	function getDebugData()
	{
		$this->_debugLog[] = "        ::Mysqli Database Debug:: ";
		
		for( $i=0; $i<$this->connectoinCount; $i++ ) {
			$this->_debugLog = array_merge( $this->_debugLog, $this->_connectionObjects[$i]->debugLog() );
		}
		
		$this->_debugLog[] = "";
		$this->_debugLog[] = "	QUERY COUNT      : ".$this->connectoinCount;
		$this->_debugLog[] = "	QUERY TOTAL TIME : ".$this->_endTime;
		$this->_debugLog[] = "";
		$this->_debugLog[] = "";
		
		return $this->_debugLog;
	}
}
	
?>