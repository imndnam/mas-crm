<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Settings_Roles_MoveAjax_Action extends Settings_Ncrm_Basic_Action {

	public function preProcess(Ncrm_Request $request) {
		return;
	}

	public function postProcess(Ncrm_Request $request) {
		return;
	}

	public function process(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$recordId = $request->get('record');
		$parentRoleId = $request->get('parent_roleid');

		$parentRole = Settings_Roles_Record_Model::getInstanceById($parentRoleId);
		$recordModel = Settings_Roles_Record_Model::getInstanceById($recordId);

		$response = new Ncrm_Response();
		$response->setEmitType(Ncrm_Response::$EMIT_JSON);
		try {
			$recordModel->moveTo($parentRole);
		} catch (AppException $e) {
			$response->setError('Move Role Failed');
		}
		$response->emit();
	}
}
