<?

require_once( dirname(__FILE__).'/easyapns/class_APNS.php' );
require_once( dirname(__FILE__).'/easyapns/class_DbConnect.php' );

class Push 
{
	private $apns;
	private $db;
	private $certificate;

	function __construct($certificate, $log_path="push.log") 
	{
		// CREATE DATABASE OBJECT ( MAKE SURE TO CHANGE LOGIN INFO IN CLASS FILE )
		$this->db = new DbConnect( _DB_HOST, _DB_USERNAME, _DB_PASSWORD, _DB_NAME );
		$this->db->show_errors();

		$this->certificate = $certificate;
		
		// CREATE APNS OBJECT, WITH DATABASE OBJECT AND ARGUMENTS
		$this->apns = new APNS($this->db, NULL, $this->certificate, $this->certificate);
	}

	public function registerDevice( $params ) 
	{
		if (
			! $params[devicetoken] || 
			! $params[clientid] ) {
			return false;
		}

		$args = Array(
						"appname"			=> $params[appname],
						"appversion"		=> $params[appversion],
						"deviceuid"		=> $params[deviceuid],
						"devicetoken"		=> $params[devicetoken],
						"devicename"		=> $params[devicename],
						"devicemodel"	=> $params[devicemodel],
						"deviceversion"	=> $params[deviceversion],
						"pushbadge"		=> $params[pushbadge],
						"pushalert"			=> $params[pushalert],
						"pushsound"		=> $params[pushsound],
						"development"	=> $params[development],
						"clientid"			=> $params[clientid],
						"development"	=> $params[development]
					);


		$this->apns->registerDevice( $args );

		return true;
	}

	public function unregisterDevice( $params ) 
	{
		$this->apns->unregisterDevice( $params );
	}

	public function processQueue()
	{
		$this->apns->processQueue();
	}
	
	public function send( $clientid, $message, $badge = NULL )
	{
		$this->apns->newMessage( NULL, '2010-01-01 00:00:00', $clientid );
		$this->apns->addMessageAlert( $message );
		if ( $badge != NULL) $this->apns->addMessageBadge( $badge );
		$this->apns->addMessageSound('bingbong.aiff');
		
		$this->apns->queueMessage();
		$this->apns->processQueue();
	}
}

?>
