<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Reports_EditFolder_View extends Ncrm_IndexAjax_View {

	public function checkPermission(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$moduleModel = Reports_Module_Model::getInstance($moduleName);

		$currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		if(!$currentUserPriviligesModel->hasModulePermission($moduleModel->getId())) {
			throw new AppException('LBL_PERMISSION_DENIED');
		}
	}

	public function process (Ncrm_Request $request) {
		
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		$folderId = $request->get('folderid');

		if ($folderId) {
			$folderModel = Reports_Folder_Model::getInstanceById($folderId);
		} else {
			$folderModel = Reports_Folder_Model::getInstance();
		}
		
		$viewer->assign('FOLDER_MODEL', $folderModel);
		$viewer->assign('MODULE',$moduleName);
		$viewer->view('EditFolder.tpl', $moduleName);
	}
}