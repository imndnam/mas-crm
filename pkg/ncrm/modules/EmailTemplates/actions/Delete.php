<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class EmailTemplates_Delete_Action extends Ncrm_Delete_Action {
	
	function checkPermission(Ncrm_Request $request) {
		return true;
	}

	public function process(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$recordId = $request->get('record');
		$ajaxDelete = $request->get('ajaxDelete');
		
		$recordModel = EmailTemplates_Record_Model::getInstanceById($recordId);
		$moduleModel = $recordModel->getModule();

		$recordModel->delete($recordId);

		$listViewUrl = $moduleModel->getListViewUrl();
		if($ajaxDelete) {
			$response = new Ncrm_Response();
			$response->setResult($listViewUrl);
			return $response;
		} else {
			header("Location: $listViewUrl");
		}
	}
}
