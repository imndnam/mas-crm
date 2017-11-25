<?php

/* +***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 * *********************************************************************************** */

class ModComments_Save_Action extends Ncrm_Save_Action {

	public function process(Ncrm_Request $request) {
		$recordId = $request->get('record');
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$request->set('assigned_user_id', $currentUserModel->getId());
		$request->set('userid', $currentUserModel->getId());
		
		$recordModel = $this->saveRecord($request);
		$responseFieldsToSent = array('reasontoedit','commentcontent');
		$fieldModelList = $recordModel->getModule()->getFields();
		foreach ($responseFieldsToSent as $fieldName) {
            $fieldModel = $fieldModelList[$fieldName];
            $fieldValue = $recordModel->get($fieldName);
			$result[$fieldName] = $fieldModel->getDisplayValue(Ncrm_Util_Helper::toSafeHTML($fieldValue));
		}

		$result['success'] = true;
		$result['modifiedtime'] = Ncrm_Util_Helper::formatDateDiffInStrings($recordModel->get('modifiedtime'));
		$result['modifiedtimetitle'] = Ncrm_Util_Helper::formatDateTimeIntoDayString($recordModel->get('modifiedtime'));

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
	protected function getRecordModelFromRequest(Ncrm_Request $request) {
		$recordModel = parent::getRecordModelFromRequest($request);
		
		$recordModel->set('commentcontent', $request->getRaw('commentcontent'));
		$recordModel->set('reasontoedit', $request->getRaw('reasontoedit'));

		return $recordModel;
	}
	
}
