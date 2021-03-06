<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_MailConverter_DeleteMailBox_Action extends Settings_Ncrm_Index_Action {

	public function checkPermission(Ncrm_Request $request) {
		parent::checkPermission($request);
		$recordId = $request->get('record');

		if (!$recordId) {
			throw new AppException(vtranslate('LBL_PERMISSION_DENIED', $request->getModule(false)));
		}
	}

	public function process(Ncrm_Request $request) {
		$recordId = $request->get('record');
		$qualifiedModuleName = $request->getModule(false);
        
		$recordModel = Settings_MailConverter_Record_Model::getInstanceById($recordId);
		$recordModel->delete();

		$response = new Ncrm_Response();
        $result = array('message' => vtranslate('LBL_DELETED_SUCCESSFULLY', $qualifiedModuleName));
        $result['id'] = $recordId;
		$response->setResult($result);
		$response->emit();
	}
        
        public function validateRequest(Ncrm_Request $request) {
            $request->validateWriteAccess(); 
        }
}