<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Settings_Profiles_Detail_View extends Settings_Ncrm_Index_View {

	public function process(Ncrm_Request $request) {
		$recordId = $request->get('record');
		$moduleName = $request->getModule();
		$qualifiedModuleName = $request->getModule(false);

		$recordModel = Settings_Profiles_Record_Model::getInstanceById($recordId);

		$viewer = $this->getViewer($request);
		$viewer->assign('MODULE', $moduleName);
		$viewer->assign('RECORD_ID', $recordId);
		$viewer->assign('RECORD_MODEL', $recordModel);
		$viewer->assign('ALL_BASIC_ACTIONS', Ncrm_Action_Model::getAllBasic(true));
		$viewer->assign('ALL_UTILITY_ACTIONS', Ncrm_Action_Model::getAllUtility(true));
		$viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());

		$viewer->view('DetailView.tpl', $qualifiedModuleName);
	}

}
