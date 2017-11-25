<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/
chdir(dirname(__FILE__) . '/../../../');
include_once 'include/Webservices/Relation.php';
include_once 'vtlib/Ncrm/Module.php';
include_once 'includes/main/WebUI.php';
vimport('includes.http.Request');

class PBXManager_PBXManager_Callbacks {
    
    function validateRequest($ncrmsecretkey,$request) {
        if($ncrmsecretkey == $request->get('ncrmsignature')){
            return true;
        }
        return false;
    }

    function process($request){
	$pbxmanagerController = new PBXManager_PBXManager_Controller();
        $connector = $pbxmanagerController->getConnector();
        if($this->validateRequest($connector->getNcrmSecretKey(),$request)) {
            $pbxmanagerController->process($request);
        }else {
            $response = $connector->getXmlResponse();
            echo $response;
        }
    }
}
$pbxmanager = new PBXManager_PBXManager_Callbacks();
$pbxmanager->process(new Ncrm_Request($_REQUEST));
?>