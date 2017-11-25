<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_MailConverter_DeleteRule_Action extends Settings_Ncrm_Index_Action {

	public function checkPermission(Ncrm_Request $request) {
		parent::checkPermission($request);
		$recordId = $request->get('record');
		$scannerId = $request->get('scannerId');

		if (!$recordId || !$scannerId) {
			throw new AppException(vtranslate('LBL_PERMISSION_DENIED', $request->getModule(false)));
		}
	}

	public function process(Ncrm_Request $request) {
		$recordId = $request->get('record');
		$qualifiedModuleName = $request->getModule(false);

		$recordModel = Settings_MailConverter_RuleRecord_Model::getInstanceById($recordId);
		$scannerId = $recordModel->getScannerId();

		$response = new Ncrm_Response();
		if ($scannerId === $request->get('scannerId')) {
			$recordModel->delete();
			$response->setResult(vtranslate('LBL_DELETED_SUCCESSFULLY', $qualifiedModuleName));
		} else {
			$response->setError(vtranslate('LBL_RULE_DELETION_FAILED', $qualifiedModuleName));
		}
		$response->emit();
	}
        
        public function validateRequest(Ncrm_Request $request) {
            $request->validateWriteAccess(); 
        } 
}