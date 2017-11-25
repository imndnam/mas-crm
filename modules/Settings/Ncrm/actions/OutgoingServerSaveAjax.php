<?php

/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_Ncrm_OutgoingServerSaveAjax_Action extends Settings_Ncrm_Basic_Action {
    
    public function process(Ncrm_Request $request) {
        $outgoingServerSettingsModel = Settings_Ncrm_Systems_Model::getInstanceFromServerType('email', 'OutgoingServer');
        $loadDefaultSettings = $request->get('default');
        if($loadDefaultSettings == "true") {
            $outgoingServerSettingsModel->loadDefaultValues();
        }else{
            $outgoingServerSettingsModel->setData($request->getAll());
        }
        $response = new Ncrm_Response();
        try{
            $id = $outgoingServerSettingsModel->save($request);
            $data = $outgoingServerSettingsModel->getData();
            $response->setResult($data);
        }catch(Exception $e) {
            $response->setError($e->getCode(), $e->getMessage());
        }
        $response->emit();
    }
    
    public function validateRequest(Ncrm_Request $request) {
        $request->validateWriteAccess(); 
    }
}