<?php
/* +***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 * ************************************************************************************/

class Portal_SaveAjax_Action extends Ncrm_SaveAjax_Action {
    
    public function process(Ncrm_Request $request) {
        $module = $request->getModule();
        $recordId = $request->get('record');
        $bookmarkName = $request->get('bookmarkName');
        $bookmarkUrl = $request->get('bookmarkUrl');
        
        Portal_Module_Model::savePortalRecord($recordId, $bookmarkName, $bookmarkUrl);
        
        $response = new Ncrm_Response();
        $result = array('message' => vtranslate('LBL_BOOKMARK_SAVED_SUCCESSFULLY', $module));
        $response->setResult($result);
        $response->emit();
    }
}