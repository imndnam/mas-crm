<?php

/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_PickListDependency_Index_Action extends Settings_Ncrm_Basic_Action {
    
    function __construct() {
        parent::__construct();
        $this->exposeMethod('checkCyclicDependency');
    }
   
    public function checkCyclicDependency(Ncrm_Request $request) {
        $module = $request->get('sourceModule');
        $sourceField = $request->get('sourcefield');
        $targetField = $request->get('targetfield');
        $result = Ncrm_DependencyPicklist::checkCyclicDependency($module, $sourceField, $targetField);
        $response = new Ncrm_Response();
        $response->setResult(array('result'=>$result));
        $response->emit();
    }
}