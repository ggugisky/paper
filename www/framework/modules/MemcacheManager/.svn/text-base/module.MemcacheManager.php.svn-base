<?php

class MemcacheManager
{
	static $memcache;
	
	private function init( $host, $port ) {
		if ( MemcacheManager::$memcache != null ) {
			return;
		}
		
		MemcacheManager::$memcache = new Memcache;
		MemcacheManager::$memcache->connect($host, $port) or die ("Could not connect");
	}
	
	public function __construct( $host="localhost", $port=11211 ) {
		$this->init( $host, $port );
	}
	
	public function __destruct() {
		if ( ! MemcacheManager::$memcache ) {
			MemcacheManager::$memcache->close();
			MemcacheManager::$memcache = null;
		}
	}
	
	public function set( $key, $value, $time = 10) {
		$compress = is_bool($value) || is_int($value) || is_float($value) ? false : MEMCACHE_COMPRESSED;
		MemcacheManager::$memcache->set($key, $value, $compress, $time) or die ("Failed to save data at the server");
	}
	
	public function get( $key ) {
		return MemcacheManager::$memcache->get($key);
	}
	
	public function getVersion() {
		return MemcacheManager::$memcache->getVersion();
	}
}

?>
	