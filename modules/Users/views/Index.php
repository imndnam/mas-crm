<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Users_Index_View extends Ncrm_Basic_View {

	public function preProcess (Ncrm_Request $request) {
		parent::preProcess($request);
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		if($currentUserModel->isAdminUser()) {
			$settingsIndexView = new Settings_Ncrm_Index_View();
			$settingsIndexView->preProcessSettings($request);
		}
	}

	public function postProcess(Ncrm_Request $request) {
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		if($currentUserModel->isAdminUser()) {
			$settingsIndexView = new Settings_Ncrm_Index_View();
			$settingsIndexView->postProcessSettings($request);
		}
		parent::postProcess($request);
	}

	public function process(Ncrm_Request $request) {
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
			'modules.Ncrm.resources.Ncrm',
			"modules.$moduleName.resources.$moduleName",
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}
}