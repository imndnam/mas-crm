<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Users_Logout_Action extends Ncrm_Action_Controller {

	function checkPermission(Ncrm_Request $request) {
		return true;
	}

	function process(Ncrm_Request $request) {
		session_regenerate_id(true); // to overcome session id reuse.
		Ncrm_Session::destroy();
		
		//Track the logout History
		$moduleName = $request->getModule();
		$moduleModel = Users_Module_Model::getInstance($moduleName);
		$moduleModel->saveLogoutHistory();
		//End
		
		header ('Location: index.php');
	}
}
