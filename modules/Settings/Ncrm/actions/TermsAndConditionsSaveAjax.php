<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Settings_Ncrm_TermsAndConditionsSaveAjax_Action extends Settings_Ncrm_Basic_Action {
    
    public function process(Ncrm_Request $request) {
        $model = Settings_Ncrm_TermsAndConditions_Model::getInstance();
        $model->setText($request->get('tandc'));
        $model->save();
        
        $response = new Ncrm_Response();
        $response->emit();
    }
    
    public function validateRequest(Ncrm_Request $request) {
        $request->validateWriteAccess(); 
    } 
}