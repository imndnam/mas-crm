<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Ncrm_BasicAjax_Action extends Ncrm_Action_Controller {

	function checkPermission(Ncrm_Request $request) {
		return;
	}

	public function process(Ncrm_Request $request) {
		$searchValue = $request->get('search_value');
		$searchModule = $request->get('search_module');

		$parentRecordId = $request->get('parent_id');
		$parentModuleName = $request->get('parent_module');
		$relatedModule = $request->get('module');

		$searchModuleModel = Ncrm_Module_Model::getInstance($searchModule);
		$records = $searchModuleModel->searchRecord($searchValue, $parentRecordId, $parentModuleName, $relatedModule);

		$result = array();
		if(is_array($records)){
			foreach($records as $moduleName=>$recordModels) {
				foreach($recordModels as $recordModel) {
					$result[] = array('label'=>decode_html($recordModel->getName()), 'value'=>decode_html($recordModel->getName()), 'id'=>$recordModel->getId());
				}
			}
		}

		$response = new Ncrm_Response();
		$response->setResult($result);
		$response->emit();
	}
}
