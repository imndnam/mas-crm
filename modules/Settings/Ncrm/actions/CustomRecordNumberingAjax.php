<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Settings_Ncrm_CustomRecordNumberingAjax_Action extends Settings_Ncrm_Index_Action {

	public function __construct() {
		parent::__construct();
		$this->exposeMethod('getModuleCustomNumberingData');
		$this->exposeMethod('saveModuleCustomNumberingData');
		$this->exposeMethod('updateRecordsWithSequenceNumber');
	}

	public function checkPermission(Ncrm_Request $request) {
		parent::checkPermission($request);
		$qualifiedModuleName = $request->getModule(false);
		$sourceModule = $request->get('sourceModule');

		if(!$sourceModule) {
			throw new AppException(vtranslate('LBL_PERMISSION_DENIED', $qualifiedModuleName));
		}
	}

	public function process(Ncrm_Request $request) {
		$mode = $request->getMode();
		if(!empty($mode)) {
			echo $this->invokeExposedMethod($mode, $request);
			return;
		}
	}

	/**
	 * Function to get Module custom numbering data
	 * @param Ncrm_Request $request
	 */
	public function getModuleCustomNumberingData(Ncrm_Request $request) {
		$sourceModule = $request->get('sourceModule');

		$moduleModel = Settings_Ncrm_CustomRecordNumberingModule_Model::getInstance($sourceModule);
		$moduleData = $moduleModel->getModuleCustomNumberingData();
		
		$response = new Ncrm_Response();
		$response->setEmitType(Ncrm_Response::$EMIT_JSON);
		$response->setResult($moduleData);
		$response->emit();
	}

	/**
	 * Function save module custom numbering data
	 * @param Ncrm_Request $request
	 */
	public function saveModuleCustomNumberingData(Ncrm_Request $request) {
		$qualifiedModuleName = $request->getModule(false);
		$sourceModule = $request->get('sourceModule');

		$moduleModel = Settings_Ncrm_CustomRecordNumberingModule_Model::getInstance($sourceModule);
		$moduleModel->set('prefix', $request->get('prefix'));
		$moduleModel->set('sequenceNumber', $request->get('sequenceNumber'));

		$result = $moduleModel->setModuleSequence();

		$response = new Ncrm_Response();
		if ($result['success']) {
			$response->setResult(vtranslate('LBL_SUCCESSFULLY_UPDATED', $qualifiedModuleName));
		} else {
			$message = vtranslate('LBL_PREFIX_IN_USE', $qualifiedModuleName);
			$response->setError($message);
		}
		$response->emit();
	}

	/**
	 * Function to update record with sequence number
	 * @param Ncrm_Request $request
	 */
	public function updateRecordsWithSequenceNumber(Ncrm_Request $request) {
		$sourceModule = $request->get('sourceModule');

		$moduleModel = Settings_Ncrm_CustomRecordNumberingModule_Model::getInstance($sourceModule);
		$result = $moduleModel->updateRecordsWithSequence();

		$response = new Ncrm_Response();
		$response->setResult($result);
		$response->emit();
	}
        
        public function validateRequest(Ncrm_Request $request) { 
            $request->validateWriteAccess(); 
        }
}