<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Documents_AddFolder_View extends Ncrm_IndexAjax_View {

	public function checkPermission(Ncrm_Request $request) {
		$moduleName = $request->getModule();

		if(!Users_Privileges_Model::isPermitted($moduleName, 'EditView')) {
			throw new AppException(vtranslate('LBL_PERMISSION_DENIED', $moduleName));
		}
	}

	public function process (Ncrm_Request $request) {
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		
		$viewer->assign('MODULE',$moduleName);
		$viewer->view('AddFolder.tpl', $moduleName);
	}
}