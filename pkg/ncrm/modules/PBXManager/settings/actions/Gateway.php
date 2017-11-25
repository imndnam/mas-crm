<?php

/* +***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 * *********************************************************************************** */

class Settings_PBXManager_Gateway_Action extends Settings_Ncrm_IndexAjax_View{
    
    function __construct() {
        $this->exposeMethod('getSecretKey');
    }
    
    public function process(Ncrm_Request $request) {
        $this->getSecretKey($request);
    }
    
    public function getSecretKey(Ncrm_Request $request) {
        $serverModel = PBXManager_Server_Model::getInstance();
        $response = new Ncrm_Response();
        $ncrmsecretkey = $serverModel->get('ncrmsecretkey');
        if($ncrmsecretkey) {
            $connector = $serverModel->getConnector();
            $ncrmsecretkey = $connector->getNcrmSecretKey();
            $response->setResult($ncrmsecretkey);
        }else {
            $ncrmsecretkey = PBXManager_Server_Model::generateNcrmSecretKey();
            $response->setResult($ncrmsecretkey);
        }
        $response->emit();
    }
}
