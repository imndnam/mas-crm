<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Settings_CustomerPortal_Index_View extends Settings_Ncrm_Index_View {

	public function process(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$qualifiedModuleName = $request->getModule(false);
		$viewer = $this->getViewer($request);

		$moduleModel = Settings_Ncrm_Module_Model::getInstance($qualifiedModuleName);

		$viewer->assign('PORTAL_URL', vglobal('PORTAL_URL'));
		$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
		$viewer->assign('MODULES_MODELS', $moduleModel->getModulesList());

		$viewer->assign('USER_MODELS', Users_Record_Model::getAll(true));
		$viewer->assign('GROUP_MODELS', Settings_Groups_Record_Model::getAll());
		$viewer->assign('CURRENT_PORTAL_USER', $moduleModel->getCurrentPortalUser());
		$viewer->assign('CURRENT_DEFAULT_ASSIGNEE', $moduleModel->getCurrentDefaultAssignee());

		$viewer->view('Index.tpl', $qualifiedModuleName);
	}
	
	/**
	 * Function to get the list of Script models to be included
	 * @param Ncrm_Request $request
	 * @return <Array> - List of Ncrm_JsScript_Model instances
	 */
	function getHeaderScripts(Ncrm_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$moduleName = $request->getModule();

		$jsFileNames = array(
			"modules.Settings.$moduleName.resources.CustomerPortal"
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}
}