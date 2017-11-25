<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

Class Settings_ModuleManager_Index_View extends Settings_Ncrm_Index_View {

	public function  preProcess(Ncrm_Request $request) {
		parent::preProcess($request);
	}

	public function process(Ncrm_Request $request) {
		$viewer = $this->getViewer ($request);
		$moduleName = $request->getModule();
		$qualifiedModuleName = $request->getModule(false);

		$allModules = Settings_ModuleManager_Module_Model::getAll();
		
		$viewer->assign('ALL_MODULES', $allModules);
		$viewer->assign('MODULE', $moduleName);
		$viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());

		echo $viewer->view('IndexContents.tpl', $qualifiedModuleName,true);
	}
	
	

}