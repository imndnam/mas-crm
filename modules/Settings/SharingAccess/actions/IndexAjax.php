<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

Class Settings_SharingAccess_IndexAjax_Action extends Settings_Ncrm_IndexAjax_View {

	function __construct() {
		parent::__construct();
		$this->exposeMethod('saveRule');
		$this->exposeMethod('deleteRule');
	}

	public function process(Ncrm_Request $request) {
		$mode = $request->get('mode');
		if(!empty($mode)) {
			$this->invokeExposedMethod($mode, $request);
			return;
		}
	}

	public function saveRule(Ncrm_Request $request) {
		$forModule = $request->get('for_module');
		$ruleId = $request->get('record');

		$moduleModel = Settings_SharingAccess_Module_Model::getInstance($forModule);
		if(empty($ruleId)) {
			$ruleModel = new Settings_SharingAccess_Rule_Model();
			$ruleModel->setModuleFromInstance($moduleModel);
		}else {
			$ruleModel = Settings_SharingAccess_Rule_Model::getInstance($moduleModel, $ruleId);
		}

		$ruleModel->set('source_id', $request->get('source_id'));
		$ruleModel->set('target_id', $request->get('target_id'));
		$ruleModel->set('permission', $request->get('permission'));

		$response = new Ncrm_Response();
		$response->setEmitType(Ncrm_Response::$EMIT_JSON);
		try {
			$ruleModel->save();
		} catch (AppException $e) {
			$response->setError('Saving Sharing Access Rule failed');
		}
		$response->emit();
	}

	public function deleteRule(Ncrm_Request $request) {
		$forModule = $request->get('for_module');
		$ruleId = $request->get('record');

		$moduleModel = Settings_SharingAccess_Module_Model::getInstance($forModule);
		$ruleModel = Settings_SharingAccess_Rule_Model::getInstance($moduleModel, $ruleId);

		$response = new Ncrm_Response();
		$response->setEmitType(Ncrm_Response::$EMIT_JSON);
		try {
			$ruleModel->delete();
		} catch (AppException $e) {
			$response->setError('Deleting Sharing Access Rule failed');
		}
		$response->emit();
	}
        
        public function validateRequest(Ncrm_Request $request) {
            $request->validateWriteAccess(); 
        } 
}