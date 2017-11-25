<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Documents_CheckFileIntegrity_Action extends Ncrm_Action_Controller {

	public function checkPermission(Ncrm_Request $request) {
		$moduleName = $request->getModule();

		if(!Users_Privileges_Model::isPermitted($moduleName, 'DetailView', $request->get('record'))) {
			throw new AppException(vtranslate('LBL_PERMISSION_DENIED', $moduleName));
		}
	}

	public function process(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$recordId = $request->get('record');

		$documentRecordModel = Ncrm_Record_Model::getInstanceById($recordId, $moduleName);
		$resultVal = $documentRecordModel->checkFileIntegrity();

		$result = array('success'=>$resultVal);
		if ($resultVal) {
                        $documentRecordModel->updateFileStatus(true);
			$result['message'] = vtranslate('LBL_FILE_AVAILABLE', $moduleName);
		} else {
                        $documentRecordModel->updateFileStatus(false);
			$result['message'] = vtranslate('LBL_FILE_NOT_AVAILABLE', $moduleName);
		}

		$response = new Ncrm_Response();
		$response->setResult($result);
		$response->emit();
	}
}
