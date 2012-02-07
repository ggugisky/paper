<?php
define( "_ERROR", 	E_USER_ERROR );
define( "_WARNING", E_WARNING );

class ErrorLog
{
	private static $instance;
	private $log;
	
	public function __construct()
	{
		//set_error_handler( 'ErrorLog::handlerError' );
        set_exception_handler( Array( 'ErrorLog', 'handlerException' ) );
		
		$this->log = Array();
	}

	public function __destruct()
	{
		
	}
	
	public static function getInstance()
	{
		if ( ! ErrorLog::$instance ) {
			ErrorLog::$instance = new ErrorLog();
		}
		
		return ErrorLog::$instance;
	}
	
	public function handlerException( $exception )
	{
		$log = $exception->getMessage() . "\n" . $exception->getTraceAsString() . LINE_BREAK;
        
		echo "<pre>$log</pre>";
		
		$this->log[] = $log;
		
		 if ( ini_get('log_errors') ) {
			error_log($log, 0);
		}
	}
	
	public function printLog()
	{
		//echo "test";
		if ( ErrorLog::$log != null && count( ErrorLog::$log ) > 0 ) {
			
			for ( $i=count(ErrorLog::$log)-1; $i >= 0; $i-- ) {
				$log = stripslashes(ErrorLog::$log[$i]);
				$log = str_replace('\'', "\\'", $log );
				echo $log."<br />";
			}
		}
	}
	
	public static function throwException( $exception )
	{
		ErrorLog::getInstance()->handlerException( $exception );
	}
}

?>