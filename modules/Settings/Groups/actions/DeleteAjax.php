<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Settings_Groups_DeleteAjax_Action extends Settings_Ncrm_Basic_Action {

	public function process(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$qualifiedModuleName = $request->getModule(false);
		$recordId = $request->get('record');
		$transferRecordId = $request->get('transfer_record');

		$moduleModel = Settings_Ncrm_Module_Model::getInstance($qualifiedModuleName);
		$recordModel = Settings_Groups_Record_Model::getInstance($recordId);

		$transferToOwner = Settings_Groups_Record_Model::getInstance($transferRecordId);
		if(!$transferToOwner){
			$transferToOwner = Users_Record_Model::getInstanceById($transferRecordId, 'Users');
		}

		if($recordModel && $transferToOwner) {
			$recordModel->delete($transferToOwner);
		}

		$response = new Ncrm_Response();
		$result = array('success'=>true);
		
		$response->setResult($result);
		$response->emit();
	}
        
        public function validateRequest(Ncrm_Request $request) {
            $request->validateWriteAccess(); 
        }
}
