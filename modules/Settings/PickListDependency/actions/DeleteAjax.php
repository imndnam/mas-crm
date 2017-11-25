<?php

/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_PickListDependency_DeleteAjax_Action extends Settings_Ncrm_Index_Action {
    
    public function process(Ncrm_Request $request) {
        $sourceModule = $request->get('sourceModule');
        $sourceField = $request->get('sourcefield');
        $targetField = $request->get('targetfield');
        $recordModel = Settings_PickListDependency_Record_Model::getInstance($sourceModule, $sourceField, $targetField);
        
        $response = new Ncrm_Response();
        try{
            $result = $recordModel->delete();
            $response->setResult(array('success', $result));
        }catch(Exception $e) {
            $response->setError($e->getCode(), $e->getMessage());
        }
        $response->emit();
    }
    
    public function validateRequest(Ncrm_Request $request) {
        $request->validateWriteAccess(); 
    }
}