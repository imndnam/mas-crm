<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Calendar_ActivityReminder_Action extends Ncrm_Action_Controller{

	function __construct() {
		$this->exposeMethod('getReminders');
		$this->exposeMethod('postpone');
	}

	public function checkPermission(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$moduleModel = Ncrm_Module_Model::getInstance($moduleName);

		$userPrivilegesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		$permission = $userPrivilegesModel->hasModulePermission($moduleModel->getId());

		if(!$permission) {
			throw new AppException('LBL_PERMISSION_DENIED');
		}
	}

	public function process(Ncrm_Request $request) {
		$mode = $request->getMode();
		if(!empty($mode) && $this->isMethodExposed($mode)) {
			$this->invokeExposedMethod($mode, $request);
			return;
		}

	}

	function getReminders(Ncrm_Request $request) {
		$recordModels = Calendar_Module_Model::getCalendarReminder();
		foreach($recordModels as $record) {
			$records[] = $record->getDisplayableValues();
			$record->updateReminderStatus();
		}

		$response = new Ncrm_Response();
		$response->setResult($records);
		$response->emit();
	}

	function postpone(Ncrm_Request $request) {
		$recordId = $request->get('record');
		$module = $request->getModule();
		$recordModel = Ncrm_Record_Model::getInstanceById($recordId, $module);
		$recordModel->updateReminderStatus(0);
	}
}