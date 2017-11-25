<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Ncrm_MassSave_Action extends Ncrm_Mass_Action {

	function checkPermission(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$moduleModel = Ncrm_Module_Model::getInstance($moduleName);
		$currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();

		if(!$currentUserPriviligesModel->hasModuleActionPermission($moduleModel->getId(), 'Save')) {
			throw new AppException(vtranslate($moduleName).' '.vtranslate('LBL_NOT_ACCESSIBLE'));
		}
	}

	public function process(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$moduleModel = Ncrm_Module_Model::getInstance($moduleName);
		$recordModels = $this->getRecordModelsFromRequest($request);
        $allRecordSave= true;
		foreach($recordModels as $recordId => $recordModel) {
			if(Users_Privileges_Model::isPermitted($moduleName, 'Save', $recordId)) {
				$recordModel->save();
			}
            else {
                $allRecordSave= false;
            }
		}
        
        $response = new Ncrm_Response();
        if($allRecordSave) {
           $response->setResult(true);
        } else {
           $response->setResult(false);
        }
   	$response->emit();
	}

	/**
	 * Function to get the record model based on the request parameters
	 * @param Ncrm_Request $request
	 * @return Ncrm_Record_Model or Module specific Record Model instance
	 */
	function getRecordModelsFromRequest(Ncrm_Request $request) {

		$moduleName = $request->getModule();
		$moduleModel = Ncrm_Module_Model::getInstance($moduleName);
		$recordIds = $this->getRecordsListFromRequest($request);
		$recordModels = array();

		$fieldModelList = $moduleModel->getFields();
		foreach($recordIds as $recordId) {
			$recordModel = Ncrm_Record_Model::getInstanceById($recordId, $moduleModel);
			$recordModel->set('id', $recordId);
			$recordModel->set('mode', 'edit');

			foreach ($fieldModelList as $fieldName => $fieldModel) {
				$fieldValue = $request->get($fieldName, null);
				$fieldDataType = $fieldModel->getFieldDataType();
				if($fieldDataType == 'time'){
					$fieldValue = Ncrm_Time_UIType::getTimeValueWithSeconds($fieldValue);
				}
				if(isset($fieldValue) && $fieldValue != null) {
					if(!is_array($fieldValue)) {
						$fieldValue = trim($fieldValue);
					}
					$recordModel->set($fieldName, $fieldValue);
				} else {
                    $uiType = $fieldModel->get('uitype');
                    if($uiType == 70) {
                        $recordModel->set($fieldName, $recordModel->get($fieldName));
                    }  else {
                        $uiTypeModel = $fieldModel->getUITypeModel();
                        $recordModel->set($fieldName, $uiTypeModel->getUserRequestValue($recordModel->get($fieldName)));
                    }
				}
			}
			$recordModels[$recordId] = $recordModel;
		}
		return $recordModels;
	}
}
