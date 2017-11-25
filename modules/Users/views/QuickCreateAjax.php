<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Users_QuickCreateAjax_View extends Ncrm_QuickCreateAjax_View {

	public function checkPermission(Ncrm_Request $request) {
		$currentUserModel = Users_Record_Model::getCurrentUserModel();

		if (!$currentUserModel->isAdminUser()) {
			throw new AppException(vtranslate('LBL_PERMISSION_DENIED', 'Ncrm'));
		}
	}

	public function process(Ncrm_Request $request) {
		$moduleName = $request->getModule();

		$recordModel = Users_Record_Model::getCleanInstance($moduleName);
		$moduleModel = $recordModel->getModule();

		$fieldList = $moduleModel->getFields();
		$requestFieldList = array_intersect_key($request->getAll(), $fieldList);

		foreach($requestFieldList as $fieldName => $fieldValue){
			$fieldModel = $fieldList[$fieldName];
			if($fieldModel->isEditable()) {
				$recordModel->set($fieldName, $fieldModel->getDBInsertValue($fieldValue));
			}
		}

		$recordStructureInstance = Users_RecordStructure_Model::getInstanceFromRecordModel($recordModel,
										Users_RecordStructure_Model::RECORD_STRUCTURE_MODE_QUICKCREATE);

		$viewer = $this->getViewer($request);
		$viewer->assign('CURRENTDATE', date('Y-n-j'));
		$viewer->assign('MODULE', $moduleName);
		$viewer->assign('SINGLE_MODULE', 'SINGLE_'.$moduleName);
		$viewer->assign('MODULE_MODEL', $moduleModel);
		$viewer->assign('RECORD_STRUCTURE_MODEL', $recordStructureInstance);
		$viewer->assign('RECORD_STRUCTURE', $recordStructureInstance->getStructure());
		$viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());

		$viewer->assign('SCRIPTS', $this->getHeaderScripts($request));

		echo $viewer->view('QuickCreate.tpl',$moduleName,true);

	}


	public function getHeaderScripts(Ncrm_Request $request) {

		$moduleName = $request->getModule();

		$jsFileNames = array(
			"modules.$moduleName.resources.Edit"
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		return $jsScriptInstances;
	}
}