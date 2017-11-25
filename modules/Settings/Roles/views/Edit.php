<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

Class Settings_Roles_Edit_View extends Settings_Roles_Index_View {

	public function process(Ncrm_Request $request) {
		$viewer = $this->getViewer ($request);
		$moduleName = $request->getModule();
		$qualifiedModuleName = $request->getModule(false);
		$record = $request->get('record');
		$parentRoleId = $request->get('parent_roleid');
		$roleDirectlyRelated = false;

		if(!empty($record)) {
			$recordModel = Settings_Roles_Record_Model::getInstanceById($record);
			$viewer->assign('MODE', 'edit');
		} else {
			$recordModel = new Settings_Roles_Record_Model();
			$recordModel->setParent(Settings_Roles_Record_Model::getInstanceById($parentRoleId));
			$viewer->assign('MODE', '');
            $roleDirectlyRelated = true;
		}
		$profileId = $recordModel->getDirectlyRelatedProfileId();
		if($profileId){
			$viewer->assign('PROFILE_ID',$profileId);
			$roleDirectlyRelated = true;
		}


		$viewer->assign('PROFILE_DIRECTLY_RELATED_TO_ROLE',$roleDirectlyRelated);
		$viewer->assign('ALL_PROFILES', Settings_Profiles_Record_Model::getAll());
		$viewer->assign('RECORD_MODEL', $recordModel);
		$viewer->assign('RECORD_ID', $record);
		$viewer->assign('MODULE', $moduleName);
		$viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());

		$viewer->view('EditView.tpl', $qualifiedModuleName);
	}


}