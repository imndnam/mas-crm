<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Portal_Detail_View extends Ncrm_Index_View {
    
    function preProcess(Ncrm_Request $request, $display=true) {
        parent::preProcess($request);
    }
    
    public function process(Ncrm_Request $request) {
        $recordId = $request->get('record');
        $module = $request->getModule();
        
        $url = Portal_Module_Model::getWebsiteUrl($recordId);
        $recordList = Portal_Module_Model::getAllRecords();
        
        $viewer = $this->getViewer($request);
        
        $viewer->assign('MODULE', $module);
        $viewer->assign('RECORD_ID', $recordId);
        $viewer->assign('URL', $url);
        $viewer->assign('RECORDS_LIST', $recordList);
        
        $viewer->view('DetailView.tpl', $module);
    }
    
    function getHeaderScripts(Ncrm_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$moduleName = $request->getModule();

		$jsFileNames = array(
			'modules.Ncrm.resources.List',
            'modules.Ncrm.resources.Detail',
			"modules.$moduleName.resources.List",
            "modules.$moduleName.resources.Detail",
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}
}