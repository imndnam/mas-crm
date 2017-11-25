<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Portal_DeleteAjax_Action extends Ncrm_DeleteAjax_Action {
    
    public function process(Ncrm_Request $request) {
        $recordId = $request->get('record');
        $module = $request->getModule();
        Portal_Module_Model::deleteRecord($recordId);
        
        $response = new Ncrm_Response();
		$response->setResult(array('message'=>  vtranslate('LBL_RECORD_DELETED_SUCCESSFULLY', $module)));
		$response->emit();
    }
}