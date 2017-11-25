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
include_once('modules/Users/Users.php');
@include_once('include/events/include.inc');

/**
 * Provides API to work with NCRM Eventing (available from ncrm 5.1)
 * @package vtlib
 */
class Ncrm_Event {
	/** Event name like: ncrm.entity.aftersave, ncrm.entity.beforesave */
	var $eventname;
	/** Event handler class to use */
	var $classname;
	/** Filename where class is defined */
	var $filename;
	/** Condition for the event */
	var $condition;

	/** Internal caching */
	static $is_supported = '';
	
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
	 * Check if NCRM support Events
	 */
	static function hasSupport() {
		if(self::$is_supported === '') {
			self::$is_supported = Ncrm_Utils::checkTable('ncrm_eventhandlers');
		}
		return self::$is_supported;
	}

	/**
	 * Handle event registration for module
	 * @param Ncrm_Module Instance of the module to use
	 * @param String Name of the Event like ncrm.entity.aftersave, ncrm.entity.beforesave
	 * @param String Name of the Handler class (should extend VTEventHandler)
	 * @param String File path which has Handler class definition
	 * @param String Condition for the event to trigger (default blank)
	 */
	static function register($moduleInstance, $eventname, $classname, $filename, $condition='', $dependent='[]') {
		// Security check on fileaccess, don't die if it fails
		if(Ncrm_Utils::checkFileAccess($filename, false)) {
			global $adb;
			$eventsManager = new VTEventsManager($adb);
			$eventsManager->registerHandler($eventname, $filename, $classname, $condition, $dependent);
			$eventsManager->setModuleForHandler($moduleInstance->name, $classname);

			self::log("Registering Event $eventname with [$filename] $classname ... DONE");
		}
	}

	/**
	 * Trigger event based on CRM Record
	 * @param String Name of the Event to trigger
	 * @param Integer CRM record id on which event needs to be triggered.
	 */
	static function trigger($eventname, $crmid) {
		if(!self::hasSupport()) return;

		global $adb;
		$checkres = $adb->pquery("SELECT setype, crmid, deleted FROM ncrm_crmentity WHERE crmid=?", Array($crmid));
		if($adb->num_rows($checkres)) {
			$result = $adb->fetch_array($checkres, 0);
			if($result['deleted'] == '0') {
				$module = $result['setype'];
				$moduleInstance = CRMEntity::getInstance($module);
				$moduleInstance->retrieve_entity_info($result['crmid'], $module);
				$moduleInstance->id = $result['crmid'];

				global $current_user;
				if(!$current_user) {
					$current_user = new Users();
					$current_user->id = $moduleInstance->column_fields['assigned_user_id'];
				}

				// Trigger the event
				$em = new VTEventsManager($adb);
				$em->triggerEvent($eventname, VTEntityData::fromCRMEntity($moduleInstance));
			}
		}		
	}

	/**
	 * Get all the registered module events
	 * @param Ncrm_Module Instance of the module to use
	 */
	static function getAll($moduleInstance) {
		global $adb;
		$events = false;
		if(self::hasSupport()) {
			// Get all events related to module
			$records = $adb->pquery("SELECT * FROM ncrm_eventhandlers WHERE handler_class IN 
				(SELECT handler_class FROM ncrm_eventhandler_module WHERE module_name=?)", Array($moduleInstance->name)); 
			if($records) {
				while($record = $adb->fetch_array($records)) {
					$event = new Ncrm_Event();					
					$event->eventname = $record['event_name'];
					$event->classname = $record['handler_class']; 
					$event->filename  = $record['handler_path'];
					$event->condition = $record['condition'];
					$events[] = $event;
				}
			}
		}
		return $events;
	}		
}
?>