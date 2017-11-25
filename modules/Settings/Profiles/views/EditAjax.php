<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

Class Settings_Profiles_EditAjax_View extends Settings_Profiles_Edit_View {

    public function preProcess(Ncrm_Request $request) {
        return true;
    }
    
    public function postProcess(Ncrm_Request $request) {
        return true;
    }
    
    public function process(Ncrm_Request $request) {
        echo $this->getContents($request);
    }
    
    public function getContents(Ncrm_Request $request) {
        $this->initialize($request);
		
        $qualifiedModuleName = $request->getModule(false);
        $viewer = $this->getViewer ($request);
		$viewer->assign('SCRIPTS', $this->getHeaderScripts($request));
        return $viewer->view('EditViewContents.tpl',$qualifiedModuleName,true);
    }
	
	/**
	 * Function to get the list of Script models to be included
	 * @param Ncrm_Request $request
	 * @return <Array> - List of Ncrm_JsScript_Model instances
	 */
	function getHeaderScripts(Ncrm_Request $request) {
		$moduleName = $request->getModule();

		$jsFileNames = array(
			"modules.Settings.Profiles.resources.Profiles",
		);
		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		return $jsScriptInstances;
	}
    
}
