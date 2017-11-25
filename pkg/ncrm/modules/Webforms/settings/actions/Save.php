<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_Webforms_Save_Action extends Settings_Ncrm_Index_Action {

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
			$recordModel->set('mode', 'edit');
		} else {
			$recordModel = Settings_Webforms_Record_Model::getCleanInstance($qualifiedModuleName);
			$recordModel->set('mode', '');
		}

		$fieldsList = $recordModel->getModule()->getFields();
		foreach ($fieldsList as $fieldName => $fieldModel) {
			$fieldValue = $request->get($fieldName);
			if (!$fieldValue) {
				$fieldValue = $fieldModel->get('defaultvalue');
			}
			$recordModel->set($fieldName, $fieldValue);
		}

		$returnUrl = $recordModel->getModule()->getListViewUrl();
		$recordModel->set('selectedFieldsData', $request->get('selectedFieldsData'));
		if (!$recordModel->checkDuplicate()) {
			$recordModel->save();
			$returnUrl = $recordModel->getDetailViewUrl();
		}
		header("Location: $returnUrl");


	}
        
        public function validateRequest(Ncrm_Request $request) {
            $request->validateWriteAccess(); 
        } 
}