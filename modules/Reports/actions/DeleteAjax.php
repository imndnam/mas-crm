<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Reports_DeleteAjax_Action extends Ncrm_DeleteAjax_Action {

	public function process(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$recordId = $request->get('record');
		$response = new Ncrm_Response();

		$recordModel = Reports_Record_Model::getInstanceById($recordId, $moduleName);

		if (!$recordModel->isDefault() && $recordModel->isEditable()) {
			$recordModel->delete();
			$response->setResult(array(vtranslate('LBL_REPORTS_DELETED_SUCCESSFULLY', $parentModule)));
		} else {
			$response->setError(vtranslate('LBL_REPORT_DELETE_DENIED', $moduleName));
		}
		$response->emit();
	}
}
