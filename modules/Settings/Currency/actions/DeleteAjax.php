<?php

/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_Currency_DeleteAjax_Action extends Settings_Ncrm_Basic_Action {
    
    public function process(Ncrm_Request $request) {
        $response = new Ncrm_Response();
        try{
            $record = $request->get('record');
            $transforCurrencyToId = $request->get('transform_to_id');
            if(empty($transforCurrencyToId)) {
                throw new Exception('Transfer currency id cannot be empty');
            }
            Settings_Currency_Module_Model::tranformCurrency($record, $transforCurrencyToId);
            Settings_Currency_Module_Model::delete($record);
            $response->setResult(array('success'=>'true'));
        }catch(Exception $e){
           $response->setError($e->getCode(), $e->getMessage());
        }
        $response->emit();
    }
    
    public function validateRequest(Ncrm_Request $request) {
        $request->validateWriteAccess(); 
    }
}