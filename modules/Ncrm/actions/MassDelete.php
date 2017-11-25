<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Ncrm_MassDelete_Action extends Ncrm_Mass_Action {

	function checkPermission(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$moduleModel = Ncrm_Module_Model::getInstance($moduleName);

		$currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		if(!$currentUserPriviligesModel->hasModuleActionPermission($moduleModel->getId(), 'Delete')) {
			throw new AppException('LBL_PERMISSION_DENIED');
		}
	}

	function preProcess(Ncrm_Request $request) {
		return true;
	}

	function postProcess(Ncrm_Request $request) {
		return true;
	}

	public function process(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$moduleModel = Ncrm_Module_Model::getInstance($moduleName);

		if($request->get('selected_ids') == 'all' && $request->get('mode') == 'FindDuplicates') {
            $recordIds = Ncrm_FindDuplicate_Model::getMassDeleteRecords($request);
        } else {
            $recordIds = $this->getRecordsListFromRequest($request);
        }

		foreach($recordIds as $recordId) {
			if(Users_Privileges_Model::isPermitted($moduleName, 'Delete', $recordId)) {
				$recordModel = Ncrm_Record_Model::getInstanceById($recordId, $moduleModel);
				$recordModel->delete();
			}else{ 
                            $permission =   'No'; 
                        } 
		}
                
                if($permission==='No'){ 
                    throw new AppException(vtranslate('LBL_PERMISSION_DENIED'));  
                } 

		$cvId = $request->get('viewname');
		$response = new Ncrm_Response();
		$response->setResult(array('viewname'=>$cvId, 'module'=>$moduleName));
		$response->emit();
	}
}
