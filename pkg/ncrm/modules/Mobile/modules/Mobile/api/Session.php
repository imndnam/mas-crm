<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/
include_once 'libraries/HTTP_Session2/HTTP/Session2.php';

class Mobile_API_Session {

	function __construct() {
	}

	static function destroy($sessionid = false) {
		HTTP_Session_Destroy($sessionid);
	}

	static function init($sessionid = false) {
		if(empty($sessionid)) {
			HTTP_Session2::start(null, null);
			$sessionid = HTTP_Session2::id();
		} else {
			HTTP_Session2::start(null, $sessionid);
		}

		if(HTTP_Session2::isIdle() || HTTP_Session2::isExpired()) {
			return false;
		}
		return $sessionid;
	}

	static function get($key, $defvalue = '') {
		return HTTP_Session2::get($key, $defvalue);
	}

	static function set($key, $value) {
		HTTP_Session2::set($key, $value);
	}

}