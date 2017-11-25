<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

Class Settings_Roles_EditAjax_Action extends Settings_Ncrm_IndexAjax_View {

	function __construct() {
		parent::__construct();
		$this->exposeMethod('checkDuplicate');
	}

	public function process(Ncrm_Request $request) {
		$mode = $request->get('mode');
		if(!empty($mode)) {
			$this->invokeExposedMethod($mode, $request);
			return;
		}
	}

	public function checkDuplicate(Ncrm_Request $request) {
		$roleName = $request->get('rolename');
		$recordId = $request->get('record');
		
		$recordModel = Settings_Roles_Record_Model::getInstanceByName($roleName, array($recordId));

		$response = new Ncrm_Response();
		if(!empty($recordModel)) {
			$response->setResult(array('success' => true,'message'=>  vtranslate('LBL_DUPLICATES_EXIST',$request->getModule(false))));
			
		}else{
			$response->setResult(array('success' => false));
		}
		$response->emit();
	}

}