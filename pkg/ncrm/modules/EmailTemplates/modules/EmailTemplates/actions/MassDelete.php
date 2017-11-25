<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class EmailTemplates_MassDelete_Action extends Ncrm_Mass_Action {

	function checkPermission(){
		return true;
	}

	function preProcess(Ncrm_Request $request) {
		return true;
	}

	function postProcess(Ncrm_Request $request) {
		return true;
	}

	public function process(Ncrm_Request $request) {
		$moduleName = $request->getModule();

		$recordModel = new EmailTemplates_Record_Model();
		$recordModel->setModule($moduleName);
		$selectedIds = $request->get('selected_ids');
		$excludedIds = $request->get('excluded_ids');

		if($selectedIds == 'all' && empty($excludedIds)){
			$recordModel->deleteAllRecords();
		}else{
			$recordIds = $this->getRecordsListFromRequest($request, $recordModel);
			foreach($recordIds as $recordId) {
				$recordModel = EmailTemplates_Record_Model::getInstanceById($recordId);
				$recordModel->delete();
			}
		}
		
		$response = new Ncrm_Response();
		$response->setResult(array('module'=>$moduleName));
		$response->emit();
	}
	
	public function getRecordsListFromRequest(Ncrm_Request $request, $recordModel) {
		$selectedIds = $request->get('selected_ids');
		$excludedIds = $request->get('excluded_ids');
		
		if(!empty($selectedIds) && $selectedIds != 'all') {
			if(!empty($selectedIds) && count($selectedIds) > 0) {
				return $selectedIds;
			}
		}
		if(!empty($excludedIds)){
			$moduleModel = $recordModel->getModule();
			$recordIds  = $moduleModel->getRecordIds($excludedIds);
			return $recordIds;
		}
	}
}
