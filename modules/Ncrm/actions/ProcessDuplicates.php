<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Ncrm_ProcessDuplicates_Action extends Ncrm_Action_Controller {
	function checkPermission(Ncrm_Request $request) {
		$module = $request->getModule();
		$records = $request->get('records');
		if($records) {
			foreach($records as $record) {
				$recordPermission = Users_Privileges_Model::isPermitted($module, 'EditView', $record);
				if(!$recordPermission) {
					throw new AppException(vtranslate('LBL_PERMISSION_DENIED'));
				}
			}
		}
	}

	function process (Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$moduleModel = Ncrm_Module_Model::getInstance($moduleName);
		$records = $request->get('records');
		$primaryRecord = $request->get('primaryRecord');
		$primaryRecordModel = Ncrm_Record_Model::getInstanceById($primaryRecord, $moduleName);

		$fields = $moduleModel->getFields();
		foreach($fields as $field) {
			$fieldValue = $request->get($field->getName());
			if($field->isEditable()) {
				$primaryRecordModel->set($field->getName(), $fieldValue);
			}
		}
		$primaryRecordModel->set('mode', 'edit');
		$primaryRecordModel->save();

		$deleteRecords = array_diff($records, array($primaryRecord));
		foreach($deleteRecords as $deleteRecord) {
			$recordPermission = Users_Privileges_Model::isPermitted($moduleName, 'Delete', $deleteRecord);
			if($recordPermission) {
				$primaryRecordModel->transferRelationInfoOfRecords(array($deleteRecord));
				$record = Ncrm_Record_Model::getInstanceById($deleteRecord);
				$record->delete();
			}
		}

		$response = new Ncrm_Response();
		$response->setResult(true);
		$response->emit();
	}
        
        public function validateRequest(Ncrm_Request $request) { 
            $request->validateWriteAccess(); 
        } 
}