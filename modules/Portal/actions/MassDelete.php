<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Portal_MassDelete_Action extends Ncrm_MassDelete_Action {

    function checkPermission(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$moduleModel = Ncrm_Module_Model::getInstance($moduleName);

		$currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		if(!$currentUserPriviligesModel->hasModulePermission($moduleModel->getId())) {
			throw new AppException('LBL_PERMISSION_DENIED');
		}
	}

    public function process(Ncrm_Request $request) {
        $module = $request->getModule();
        
        Portal_Module_Model::deleteRecords($request);
        
        $response = new Ncrm_Response();
        $result = array('message' => vtranslate('LBL_BOOKMARKS_DELETED_SUCCESSFULLY', $module));
        $response->setResult($result);
        $response->emit();
    }
}