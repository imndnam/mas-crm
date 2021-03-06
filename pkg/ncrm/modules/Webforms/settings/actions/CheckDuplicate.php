<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_Webforms_CheckDuplicate_Action extends Settings_Ncrm_Index_Action {

	public function checkPermission(Ncrm_Request $request) {
		parent::checkPermission($request);

		$moduleModel = Ncrm_Module_Model::getInstance($request->getModule());
		$currentUserPrivilegesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();

		if(!$currentUserPrivilegesModel->hasModulePermission($moduleModel->getId())) {
			throw new AppException('LBL_PERMISSION_DENIED');
		}
	}

	public function process(Ncrm_Request $request) {
		$recordId = $request->get('record');
		$qualifiedModuleName = $request->getModule(false);

		if ($recordId) {
			$recordModel = Settings_Webforms_Record_Model::getInstanceById($recordId, $qualifiedModuleName);
		} else {
			$recordModel = Settings_Webforms_Record_Model::getCleanInstance($qualifiedModuleName);
		}
		$recordModel->set('name', $request->get('name'));

		if (!$recordModel->checkDuplicate()) {
			$result = array('success'=>false);
		} else {
			$result = array('success'=>true, 'message'=>vtranslate('LBL_DUPLICATES_EXIST', $qualifiedModuleName));
		}

		$response = new Ncrm_Response();
		$response->setResult($result);
		$response->emit();
	}

}