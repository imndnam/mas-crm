<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Users_SystemSetup_View extends Ncrm_Index_View {
	
	public function preProcess(Ncrm_Request $request, $display=true) {
		return true;
	}
	
	public function process(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$viewer = $this->getViewer($request);
		$userModel = Users_Record_Model::getCurrentUserModel();
		$isFirstUser = Users_CRMSetup::isFirstUser($userModel);
		
		if($isFirstUser) {
			$viewer->assign('IS_FIRST_USER', $isFirstUser);
			$viewer->assign('PACKAGES_LIST', Users_CRMSetup::getPackagesList());
			$viewer->view('SystemSetup.tpl', $moduleName);
		} else {
			header ('Location: index.php?module=Users&parent=Settings&view=UserSetup');
			exit();
		}
	}
	
	function postProcess(Ncrm_Request $request) {
		return true;
	}
	
}