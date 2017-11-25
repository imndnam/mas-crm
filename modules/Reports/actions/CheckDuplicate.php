<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Reports_CheckDuplicate_Action extends Ncrm_Action_Controller {

	function checkPermission(Ncrm_Request $request) {
		return;
	}

	public function process(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$reportName = $request->get('reportname');
		$record = $request->get('record');
		
		if ($record) {
			$recordModel = Ncrm_Record_Model::getInstanceById($record, $moduleName);
		} else {
			$recordModel = Ncrm_Record_Model::getCleanInstance($moduleName);
		}

		$recordModel->set('reportname', $reportName);
		$recordModel->set('reportid', $record);
		$recordModel->set('isDuplicate', $request->get('isDuplicate'));
		
		if (!$recordModel->checkDuplicate()) {
			$result = array('success'=>false);
		} else {
			$result = array('success'=>true, 'message'=>vtranslate('LBL_DUPLICATES_EXIST', $moduleName));
		}
		$response = new Ncrm_Response();
		$response->setResult($result);
		$response->emit();
	}
}
