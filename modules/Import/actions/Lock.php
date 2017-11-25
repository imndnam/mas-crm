<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Import_Lock_Action extends Ncrm_Action_Controller {

	public function  __construct() {
	}

	public function process(Ncrm_Request $request) {
		return false;
	}

	public static function lock($importId, $module, $user) {
		$adb = PearDatabase::getInstance();

		if(!Ncrm_Utils::CheckTable('ncrm_import_locks')) {
			Ncrm_Utils::CreateTable(
				'ncrm_import_locks',
				"(ncrm_import_lock_id INT NOT NULL PRIMARY KEY,
				userid INT NOT NULL,
				tabid INT NOT NULL,
				importid INT NOT NULL,
				locked_since DATETIME)",
				true);
		}

		$adb->pquery('INSERT INTO ncrm_import_locks VALUES(?,?,?,?,?)',
						array($adb->getUniqueID('ncrm_import_locks'), $user->id, getTabid($module), $importId, date('Y-m-d H:i:s')));
	}

	public static function unLock($user, $module=false) {
		$adb = PearDatabase::getInstance();
		if(Ncrm_Utils::CheckTable('ncrm_import_locks')) {
			$query = 'DELETE FROM ncrm_import_locks WHERE userid=?';
			$params = array(method_exists($user, 'get')?$user->get('id'):$user->id);
			if($module != false) {
				$query .= ' AND tabid=?';
				array_push($params, getTabid($module));
			}
			$adb->pquery($query, $params);
		}
	}

	public static function isLockedForModule($module) {
		$adb = PearDatabase::getInstance();

		if(Ncrm_Utils::CheckTable('ncrm_import_locks')) {
			$lockResult = $adb->pquery('SELECT * FROM ncrm_import_locks WHERE tabid=?',array(getTabid($module)));

			if($lockResult && $adb->num_rows($lockResult) > 0) {
				$lockInfo = $adb->query_result_rowdata($lockResult, 0);
				return $lockInfo;
			}
		}

		return null;
	}
}