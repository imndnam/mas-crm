<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Reports_MoveReports_View extends Ncrm_Index_View {

	public function checkPermission(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$moduleModel = Reports_Module_Model::getInstance($moduleName);

		$currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		if(!$currentUserPriviligesModel->hasModulePermission($moduleModel->getId())) {
			throw new AppException('LBL_PERMISSION_DENIED');
		}
	}

	public function process(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$moduleModel = Ncrm_Module_Model::getInstance($moduleName);
		$folderList = $moduleModel->getFolders();
		$viewer = $this->getViewer($request);
		$viewer->assign('FOLDERS', $folderList);
		$viewer->assign('SELECTED_IDS', $request->get('selected_ids'));
		$viewer->assign('EXCLUDED_IDS', $request->get('excluded_ids'));
		$viewer->assign('VIEWNAME',$request->get('viewname'));
		$viewer->assign('MODULE',$moduleName);
		$viewer->view('MoveReports.tpl', $moduleName);
	}
}