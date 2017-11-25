<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Settings_Workflows_DeleteAjax_Action extends Settings_Ncrm_Index_Action {

	public function process(Ncrm_Request $request) {
		$qualifiedModule = $request->getModule(false);
		$recordId = $request->get('record');

		$response = new Ncrm_Response();
		$recordModel = Settings_Workflows_Record_Model::getInstance($recordId);
		if($recordModel->isDefault()) {
			$response->setError('LBL_DEFAULT_WORKFLOW', vtranslate('LBL_CANNOT_DELETE_DEFAULT_WORKFLOW', $qualifiedModule));
		} else {
			$recordModel->delete();
			$response->setResult(array('success'=>'ok'));
		}
		$response->emit();
	}
        
        public function validateRequest(Ncrm_Request $request) {
            $request->validateWriteAccess(); 
        }
}
