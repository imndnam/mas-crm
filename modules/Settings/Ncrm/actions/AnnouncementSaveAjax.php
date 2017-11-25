<?php

/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_Ncrm_AnnouncementSaveAjax_Action extends Settings_Ncrm_Basic_Action {
    
    public function process(Ncrm_Request $request) {
        $currentUser = Users_Record_Model::getCurrentUserModel();
        $annoucementModel = Settings_Ncrm_Announcement_Model::getInstanceByCreator($currentUser);
        $annoucementModel->set('announcement',$request->get('announcement'));
        $annoucementModel->save();
        $responce = new Ncrm_Response();
        $responce->setResult(array('success'=>true));
        $responce->emit();
    }
    
    public function validateRequest(Ncrm_Request $request) {
        $request->validateWriteAccess(); 
    } 
}