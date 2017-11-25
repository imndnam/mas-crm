<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_Ncrm_CompanyDetailsEdit_View extends Settings_Ncrm_Index_View {

	public function process(Ncrm_Request $request) {
		$qualifiedModuleName = $request->getModule(false);
		$moduleModel = Settings_Ncrm_CompanyDetails_Model::getInstance();

		$viewer = $this->getViewer($request);
		$viewer->assign('MODULE_MODEL', $moduleModel);
		$viewer->assign('QUALIFIED_MODULE_NAME', $qualifiedModuleName);
		$viewer->assign('ERROR_MESSAGE', $request->get('error'));

		$viewer->view('CompanyDetailsEdit.tpl', $qualifiedModuleName);//For Open Source
	}
		
	function getPageTitle(Ncrm_Request $request) {
		$qualifiedModuleName = $request->getModule(false);
		return vtranslate('LBL_CONFIG_EDITOR',$qualifiedModuleName);
	}
	
}