<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Settings_PBXManager_Index_View extends Settings_Ncrm_Index_View{
    
    function __construct() {
        $this->exposeMethod('gatewayInfo');
    }

    public function process(Ncrm_Request $request) {
        $this->gatewayInfo($request);
    }
    
    public function gatewayInfo(Ncrm_Request $request){
        $recordModel = Settings_PBXManager_Record_Model::getInstance();
        $moduleModel = Settings_PBXManager_Module_Model::getCleanInstance();
        $viewer = $this->getViewer($request);  
        
        $viewer->assign('RECORD_ID', $recordModel->get('id'));
        $viewer->assign('MODULE_MODEL', $moduleModel);
        $viewer->assign('MODULE', $request->getModule(false));
        $viewer->assign('QUALIFIED_MODULE', $request->getModule(false));
        $viewer->assign('RECORD_MODEL', $recordModel);
        $viewer->view('index.tpl', $request->getModule(false));
    }


}
