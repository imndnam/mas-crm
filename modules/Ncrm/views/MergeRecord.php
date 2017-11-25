<?php
/**************************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 **************************************************************************************/

class Ncrm_MergeRecord_View extends Ncrm_Popup_View {
	function process(Ncrm_Request $request) {
		$records = $request->get('records');
		$records = explode(',', $records);
		$module = $request->getModule();
		$moduleModel = Ncrm_Module_Model::getInstance($module);
		$fieldModels =  $moduleModel->getFields();

		foreach($records as $record) {
			$recordModels[] = Ncrm_Record_Model::getInstanceById($record);
		}
		$viewer = $this->getViewer($request);
		$viewer->assign('RECORDS', $records);
		$viewer->assign('RECORDMODELS', $recordModels);
		$viewer->assign('FIELDS', $fieldModels);
		$viewer->assign('MODULE', $module);
		$viewer->view('MergeRecords.tpl', $module);
	}
}
