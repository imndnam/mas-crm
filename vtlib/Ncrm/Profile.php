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
 * Provides API to work with NCRM Profile
 * @package vtlib
 */
class Ncrm_Profile {

        var $id; 
        var $name; 
        var $desc; 

        public function save() { 
            if (!$this->id) { 
                    $this->create(); 
            } else { 
                    $this->update(); 
            } 
        } 

        private function create() { 
            global $adb; 

            $this->id = $adb->getUniqueID('ncrm_profile'); 

            $sql = "INSERT INTO ncrm_profile (profileid, profilename, description) 
                            VALUES (?,?,?)"; 
            $binds = array($this->id, $this->name, $this->desc); 
            $adb->pquery($sql, $binds); 

            $sql = "INSERT INTO ncrm_profile2field (profileid, tabid, fieldid, visible, readonly) 
                            SELECT ?, tabid, fieldid, 0, 0 
                            FROM ncrm_field"; 
            $binds = array($this->id); 
            $adb->pquery($sql, $binds); 

            $sql = "INSERT INTO ncrm_profile2tab (profileid, tabid, permissions) 
                            SELECT ?, tabid, 0 
                            FROM ncrm_tab"; 
            $binds = array($this->id); 
            $adb->pquery($sql, $binds); 

            $sql = "INSERT INTO ncrm_profile2standardpermissions (profileid, tabid, Operation, permissions) 
                            SELECT ?, tabid, actionid, 0 
                    FROM ncrm_actionmapping, ncrm_tab 
                            WHERE actionname IN ('Save', 'EditView', 'Delete', 'index', 'DetailView') AND isentitytype = 1"; 
            $binds = array($this->id); 
            $adb->pquery($sql, $binds); 

            self::log("Initializing profile permissions ... DONE"); 
        } 

        private function update() { 
            throw new Exception("Not implemented"); 
        } 
 		 
        /**
	 * Helper function to log messages
	 * @param String Message to log
	 * @param Boolean true appends linebreak, false to avoid it
	 * @access private
	 */
	static function log($message, $delimit=true) {
		Ncrm_Utils::Log($message, $delimit);
	}

	/**
	 * Initialize profile setup for Field
	 * @param Ncrm_Field Instance of the field
	 * @access private
	 */
	static function initForField($fieldInstance) {
		global $adb;

		// Allow field access to all
		$adb->pquery("INSERT INTO ncrm_def_org_field (tabid, fieldid, visible, readonly) VALUES(?,?,?,?)",
			Array($fieldInstance->getModuleId(), $fieldInstance->id, '0', '0'));

		$profileids = self::getAllIds();
		foreach($profileids as $profileid) {
			$adb->pquery("INSERT INTO ncrm_profile2field (profileid, tabid, fieldid, visible, readonly) VALUES(?,?,?,?,?)",
				Array($profileid, $fieldInstance->getModuleId(), $fieldInstance->id, '0', '0'));
		}
	}

	/**
	 * Delete profile information related with field.
	 * @param Ncrm_Field Instance of the field
	 * @access private
	 */
	static function deleteForField($fieldInstance) {
		global $adb;

		$adb->pquery("DELETE FROM ncrm_def_org_field WHERE fieldid=?", Array($fieldInstance->id));
		$adb->pquery("DELETE FROM ncrm_profile2field WHERE fieldid=?", Array($fieldInstance->id));
	}

	/**
	 * Get all the existing profile ids
	 * @access private
	 */
	static function getAllIds() {
		global $adb;
		$profileids = Array();
		$result = $adb->pquery('SELECT profileid FROM ncrm_profile', array());
		for($index = 0; $index < $adb->num_rows($result); ++$index) {
			$profileids[] = $adb->query_result($result, $index, 'profileid');
		}
		return $profileids;
	}

	/**
	 * Initialize profile setup for the module
	 * @param Ncrm_Module Instance of module
	 * @access private
	 */
	static function initForModule($moduleInstance) {
		global $adb;

		$actionids = Array();
		$result = $adb->pquery("SELECT actionid from ncrm_actionmapping WHERE actionname IN 
			(?,?,?,?,?)", array('Save','EditView','Delete','index','DetailView'));
		/* 
		 * NOTE: Other actionname (actionid >= 5) is considered as utility (tools) for a profile.
		 * Gather all the actionid for associating to profile.
		 */
		for($index = 0; $index < $adb->num_rows($result); ++$index) {
			$actionids[] = $adb->query_result($result, $index, 'actionid');
		}

		$profileids = self::getAllIds();		

		foreach($profileids as $profileid) {			
			$adb->pquery("INSERT INTO ncrm_profile2tab (profileid, tabid, permissions) VALUES (?,?,?)",
				Array($profileid, $moduleInstance->id, 0));

			if($moduleInstance->isentitytype) {
				foreach($actionids as $actionid) {
					$adb->pquery(
						"INSERT INTO ncrm_profile2standardpermissions (profileid, tabid, Operation, permissions) VALUES(?,?,?,?)",
						Array($profileid, $moduleInstance->id, $actionid, 0));
				}
			}
		}
		self::log("Initializing module permissions ... DONE");
	}

	/**
	 * Delete profile setup of the module
	 * @param Ncrm_Module Instance of module
	 * @access private
	 */
	static function deleteForModule($moduleInstance) {
		global $adb;
		$adb->pquery("DELETE FROM ncrm_profile2tab WHERE tabid=?", Array($moduleInstance->id));
		$adb->pquery("DELETE FROM ncrm_profile2standardpermissions WHERE tabid=?", Array($moduleInstance->id));
	}
}
?>
