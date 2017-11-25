<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Settings_Webforms_GetSourceModuleFields_View extends Settings_Ncrm_IndexAjax_View {

	public function checkPermission(Ncrm_Request $request) {
		parent::checkPermission($request);

		$moduleModel = Ncrm_Module_Model::getInstance($request->getModule());
		$currentUserPrivilegesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();

		if(!$currentUserPrivilegesModel->hasModulePermission($moduleModel->getId())) {
			throw new AppException('LBL_PERMISSION_DENIED');
		}
	}

	public function process(Ncrm_Request $request) {
		$recordId = $request->get('record');
		$qualifiedModuleName = $request->getModule(false);
		$sourceModule = $request->get('sourceModule');
		$viewer = $this->getViewer($request);
		$mode = '';
		$selectedFieldsList = array();

		if ($recordId) {
			$recordModel = Settings_Webforms_Record_Model::getInstanceById($recordId, $qualifiedModuleName);
			$mode = 'edit';
			if ($sourceModule === $recordModel->get('targetmodule')) {
				$selectedFieldsList = $recordModel->getSelectedFieldsList();
			}
		} else {
			$recordModel = Settings_Webforms_Record_Model::getCleanInstance($qualifiedModuleName);
		}

		$viewer->assign('MODE', $mode);
		$viewer->assign('SOURCE_MODULE', $sourceModule);
		$viewer->assign('MODULE', $qualifiedModuleName);
		$viewer->assign('SELECTED_FIELD_MODELS_LIST', $selectedFieldsList);
		$viewer->assign('ALL_FIELD_MODELS_LIST', $recordModel->getAllFieldsList($sourceModule));
		$viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());

		$viewer->view('FieldsEditView.tpl', $qualifiedModuleName);
	}
}