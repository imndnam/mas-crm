<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/
class Settings_Ncrm_Basic_Action extends Settings_Ncrm_IndexAjax_View {
    
    function __construct() {
		parent::__construct();
		$this->exposeMethod('updateFieldPinnedStatus');
	}
    
    function process(Ncrm_Request $request) {
		$mode = $request->getMode();
		if(!empty($mode)) {
			echo $this->invokeExposedMethod($mode, $request);
			return;
		}
	}
    
    public function updateFieldPinnedStatus(Ncrm_Request $request) {
        $fieldId = $request->get('fieldid');
        $menuItemModel = Settings_Ncrm_MenuItem_Model::getInstanceById($fieldId);
        
        $pin = $request->get('pin');
        if($pin == 'true') {
            $menuItemModel->markPinned();
        }else{
            $menuItemModel->unMarkPinned();
        }
        
	$response = new Ncrm_Response();
	$response->setResult(array('SUCCESS'=>'OK'));
	$response->emit();
    }
    
    public function validateRequest(Ncrm_Request $request) {
        $request->validateWriteAccess(); 
    } 
}