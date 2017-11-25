<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Settings_Leads_MappingSave_Action extends Settings_Ncrm_Index_Action {

	public function process(Ncrm_Request $request) {
		$qualifiedModuleName = $request->getModule(false);
		$mapping = $request->get('mapping');
		$csrfKey = $GLOBALS['csrf']['input-name'];
		if(array_key_exists($csrfKey,$mapping)){
			unset($mapping[$csrfKey]);
		}
		$mappingModel = Settings_Leads_Mapping_Model::getCleanInstance();

		$response = new Ncrm_Response();
		if ($mapping) {
			$mappingModel->save($mapping);
            $result = array('status' => true);
		} else {
            $result['status'] = false;
		}
        $response->setResult($result);
		return $response->emit();
	}

	public function validateRequest(Ncrm_Request $request){
		$request->validateWriteAccess();
	}
}
