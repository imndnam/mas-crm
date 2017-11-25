<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

Class Settings_ModuleManager_ModuleExport_Action extends Settings_Ncrm_IndexAjax_View {
	
	function __construct() {
		parent::__construct();
		$this->exposeMethod('exportModule');
	}
    
    function process(Ncrm_Request $request) {
		$mode = $request->getMode();
		if(!empty($mode)) {
			$this->invokeExposedMethod($mode, $request);
			return;
		}
	}
    
    protected function exportModule(Ncrm_Request $request) {
        $moduleName = $request->get('forModule');
		
		$moduleModel = Ncrm_Module_Model::getInstance($moduleName);
		
		if (!$moduleModel->isExportable()) {
			echo 'Module not exportable!';
			return;
		}

		$package = new Ncrm_PackageExport();
		$package->export($moduleModel, '', sprintf("%s-%s.zip", $moduleModel->get('name'), $moduleModel->get('version')), true);
    }
	
}