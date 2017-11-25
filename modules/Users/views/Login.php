<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Users_Login_View extends Ncrm_View_Controller {

	function loginRequired() {
		return false;
	}
	
	function checkPermission(Ncrm_Request $request) {
		return true;
	}
    function process (Ncrm_Request $request) {
        global $globalSiteName;

        $viewer = $this->getViewer($request);
        $moduleName = $request->getModule();

        $viewer->assign('MODULE', $moduleName);
        $viewer->assign('CURRENT_VERSION', vglobal('ncrm_current_version'));
        $viewer->assign('CRM_NAME', $globalSiteName);

        $viewer->view('Login.tpl', 'Users');
    }

}
