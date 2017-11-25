<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_PickListDependency_IndexAjax_View extends Settings_PickListDependency_Edit_View {

    public function __construct() {
        parent::__construct();
        $this->exposeMethod('getDependencyGraph');
    }
    
    public function preProcess(Ncrm_Request $request) {
        return true;
    }
    
    public function postProcess(Ncrm_Request $request) {
        return true;
    }
    
    public function process(Ncrm_Request $request) {
        $mode = $request->getMode();

		if($mode){
			echo $this->invokeExposedMethod($mode, $request);
			return;
		}
    }
    
}