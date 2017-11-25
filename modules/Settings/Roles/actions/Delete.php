<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Settings_Roles_Delete_Action extends Settings_Ncrm_Basic_Action {

	public function process(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$qualifiedModuleName = $request->getModule(false);
		$recordId = $request->get('record');
		$transferRecordId = $request->get('transfer_record');

		$moduleModel = Settings_Ncrm_Module_Model::getInstance($qualifiedModuleName);
		$recordModel = Settings_Roles_Record_Model::getInstanceById($recordId);
		$transferToRole = Settings_Roles_Record_Model::getInstanceById($transferRecordId);
		if($recordModel && $transferToRole) {
			$recordModel->delete($transferToRole);
		}

		$redirectUrl = $moduleModel->getDefaultUrl();
		header("Location: $redirectUrl");
	}
        
        public function validateRequest(Ncrm_Request $request) {
            $request->validateWriteAccess(); 
        }
}
