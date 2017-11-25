<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class ModComments_SaveAjax_Action extends Ncrm_SaveAjax_Action {

	public function checkPermission(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$record = $request->get('record');
		//Do not allow ajax edit of existing comments
		if ($record) {
			throw new AppException('LBL_PERMISSION_DENIED');
		}
	}

	public function process(Ncrm_Request $request) {
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$request->set('assigned_user_id', $currentUserModel->getId());
		$request->set('userid', $currentUserModel->getId());
		
		$recordModel = $this->saveRecord($request);

		$fieldModelList = $recordModel->getModule()->getFields();
		$result = array();
		foreach ($fieldModelList as $fieldName => $fieldModel) {
			$fieldValue = $recordModel->get($fieldName);
			$result[$fieldName] = array('value' => $fieldValue, 'display_value' => $fieldModel->getDisplayValue($fieldValue));
		}
		$result['id'] = $recordModel->getId();

		$result['_recordLabel'] = $recordModel->getName();
		$result['_recordId'] = $recordModel->getId();

		$response = new Ncrm_Response();
		$response->setEmitType(Ncrm_Response::$EMIT_JSON);
		$response->setResult($result);
		$response->emit();
	}
	
	/**
	 * Function to save record
	 * @param <Ncrm_Request> $request - values of the record
	 * @return <RecordModel> - record Model of saved record
	 */
	public function saveRecord($request) {
		$recordModel = $this->getRecordModelFromRequest($request);
		
		$recordModel->save();
		if($request->get('relationOperation')) {
			$parentModuleName = $request->get('sourceModule');
			$parentModuleModel = Ncrm_Module_Model::getInstance($parentModuleName);
			$parentRecordId = $request->get('sourceRecord');
			$relatedModule = $recordModel->getModule();
			$relatedRecordId = $recordModel->getId();

			$relationModel = Ncrm_Relation_Model::getInstance($parentModuleModel, $relatedModule);
			$relationModel->addRelation($parentRecordId, $relatedRecordId);
		}
		return $recordModel;
	}
	
	/**
	 * Function to get the record model based on the request parameters
	 * @param Ncrm_Request $request
	 * @return Ncrm_Record_Model or Module specific Record Model instance
	 */
	public function getRecordModelFromRequest(Ncrm_Request $request) {
		$recordModel = parent::getRecordModelFromRequest($request);
		
		$recordModel->set('commentcontent', $request->getRaw('commentcontent'));

		return $recordModel;
	}
}