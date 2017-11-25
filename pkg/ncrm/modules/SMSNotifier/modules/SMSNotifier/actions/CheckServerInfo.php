<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class SMSNotifier_CheckServerInfo_Action extends Ncrm_Action_Controller {

	function checkPermission(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$moduleModel = Ncrm_Module_Model::getInstance($moduleName);

		$currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		if(!$currentUserPriviligesModel->hasModulePermission($moduleModel->getId())) {
			throw new AppException(vtranslate($moduleName).' '.vtranslate('LBL_NOT_ACCESSIBLE'));
		}
	}

	function process(Ncrm_Request $request) {
		$db = PearDatabase::getInstance();
		$response = new Ncrm_Response();

		$result = $db->pquery('SELECT 1 FROM ncrm_smsnotifier_servers WHERE isactive = 1', array());
		if($db->num_rows($result)) {
			$response->setResult(true);
		} else {
			$response->setResult(false);
		}
		return $response;
	}
}