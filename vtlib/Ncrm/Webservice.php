<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/
include_once('vtlib/Ncrm/Utils.php');

/**
 * Provides API to work with NCRM Webservice (available from ncrm 5.1)
 * @package vtlib
 */
class Ncrm_Webservice {
	
	/**
	 * Helper function to log messages
	 * @param String Message to log
	 * @param Boolean true appends linebreak, false to avoid it
	 * @access private
	 */
	static function log($message, $delim=true) {
		Ncrm_Utils::Log($message, $delim);
	}

	/**
	 * Initialize webservice for the given module
	 * @param Ncrm_Module Instance of the module.
	 */
	static function initialize($moduleInstance) {
		if($moduleInstance->isentitytype) {
			// TODO: Enable support when webservice API support is added.
			if(function_exists('vtws_addDefaultModuleTypeEntity')) { 
				vtws_addDefaultModuleTypeEntity($moduleInstance->name);
				self::log("Initializing webservices support ...DONE");
			}
		}
	}

	/**
	 * Initialize webservice for the given module
	 * @param Ncrm_Module Instance of the module.
	 */
	static function uninitialize($moduleInstance) {
		if($moduleInstance->isentitytype) {
			// TODO: Enable support when webservice API support is added.
			if(function_exists('vtws_deleteWebserviceEntity')) { 
				vtws_deleteWebserviceEntity($moduleInstance->name);
				self::log("De-Initializing webservices support ...DONE");
			}
		}
	}
}
?>
