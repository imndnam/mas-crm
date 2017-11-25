<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Settings_SMSNotifier_Delete_Action extends Settings_Ncrm_Index_Action {

	public function process(Ncrm_Request $request) {
		$recordId = $request->get('record');
		$qualifiedModuleName = $request->getModule(false);

		$response = new Ncrm_Response();
		if ($recordId) {
			$status = Settings_SMSNotifier_Module_Model::deleteRecords(array($recordId));
			if ($status) {
				$response->setResult(array(vtranslate('LBL_DELETED_SUCCESSFULLY'), $qualifiedModuleName));
			} else {
				$response->setError(vtranslate('LBL_DELETE_FAILED', $qualifiedModuleName));
			}
		} else {
			$response->setError(vtranslate('LBL_INVALID_RECORD', $qualifiedModuleName));
		}
		$response->emit();
	}
        
        public function validateRequest(Ncrm_Request $request) {
            $request->validateWriteAccess(); 
        } 
}