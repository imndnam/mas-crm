<?php

/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_Ncrm_ConfigEditorSaveAjax_Action extends Settings_Ncrm_Basic_Action {

	public function process(Ncrm_Request $request) {
		$response = new Ncrm_Response();
		$qualifiedModuleName = $request->getModule(false);
		$updatedFields = $request->get('updatedFields');
		$moduleModel = Settings_Ncrm_ConfigModule_Model::getInstance();

		if ($updatedFields) {
			$moduleModel->set('updatedFields', $updatedFields);
			$status = $moduleModel->save();

			if ($status === true) {
				$response->setResult(array($status));
			} else {
				$response->setError(vtranslate($status, $qualifiedModuleName));
			}
		} else {
			$response->setError(vtranslate('LBL_FIELDS_INFO_IS_EMPTY', $qualifiedModuleName));
		}
		$response->emit();
	}
        
        public function validateRequest(Ncrm_Request $request) { 
            $request->validateWriteAccess(); 
        }
}