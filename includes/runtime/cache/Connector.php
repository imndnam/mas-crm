<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

include_once dirname(__FILE__) . '/Connectors.php';

class Ncrm_Cache_Connector {
	protected $connection;

	protected function __construct() {
		if (!$this->connection) {
			$this->connection = new Ncrm_Cache_Connector_Memory();
		}
	}

	protected function cacheKey($ns, $key) {
		if(is_array($key)) $key = implode('-', $key);
		return $ns . '-' . $key;
	}

	public function set($namespace, $key, $value) {
		$this->connection->set($this->cacheKey($namespace, $key), $value);
	}

	public function get($namespace, $key) {
		return $this->connection->get($this->cacheKey($namespace, $key));
	}

	public function has($namespace, $key) {
		return $this->get($namespace, $key) !== false;
	}
    
    public function flush(){
        $this->connection->flush(); 

        $time = time()+1; //one second future 
        while(time() < $time) { 
            //sleep 
        } 
    }

    public static function getInstance() {
		static $singleton = NULL;
		if ($singleton === NULL) {
			$singleton = new self();
		}
		return $singleton;
	}
}