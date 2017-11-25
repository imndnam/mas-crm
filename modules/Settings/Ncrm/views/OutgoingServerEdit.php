<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_Ncrm_OutgoingServerEdit_View extends Settings_Ncrm_Index_View {
    
    public function process(Ncrm_Request $request) {
        $systemDetailsModel = Settings_Ncrm_Systems_Model::getInstanceFromServerType('email', 'OutgoingServer');
        $viewer = $this->getViewer($request);
        $qualifiedName = $request->getModule(false);
		
        $viewer->assign('MODEL',$systemDetailsModel);
		$viewer->assign('QUALIFIED_MODULE', $qualifiedName);
		$viewer->assign('CURRENT_USER_MODEL', Users_Record_Model::getCurrentUserModel());
        $viewer->view('OutgoingServerEdit.tpl',$qualifiedName);
    }
		
	function getPageTitle(Ncrm_Request $request) {
		$qualifiedModuleName = $request->getModule(false);
		return vtranslate('LBL_OUTGOING_SERVER',$qualifiedModuleName);
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
			"modules.Settings.$moduleName.resources.OutgoingServer"
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}
}