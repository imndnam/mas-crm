<?php

/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Users_IndexAjax_Action extends Ncrm_BasicAjax_Action {
    
    function __construct() {
		parent::__construct();
		$this->exposeMethod('toggleLeftPanel');
	}
    
    function process(Ncrm_Request $request) {
		$mode = $request->get('mode');
		if(!empty($mode)) {
			$this->invokeExposedMethod($mode, $request);
			return;
		}
	}
    
    public function toggleLeftPanel (Ncrm_Request $request) {
        $currentUser = Users_Record_Model::getCurrentUserModel();
        $currentUser->set('leftpanelhide',$request->get('showPanel'));
        $currentUser->leftpanelhide = $request->get('showPanel');
        $currentUser->set('mode','edit');
        $response = new Ncrm_Response();
        try{
            $currentUser->save();
            $response->setResult(array('success'=>true));
        }catch(Exception $e){
            $response->setError($e->getCode(),$e->getMessage());
        }
        $response->emit();
    }
}