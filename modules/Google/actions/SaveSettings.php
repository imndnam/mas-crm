<?php

/* +***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 * *********************************************************************************** */

class Google_SaveSettings_Action extends Ncrm_BasicAjax_Action {

    public function process(Ncrm_Request $request) {
        $sourceModule = $request->get('sourcemodule');
        $fieldMapping = $request->get('fieldmapping');
        Google_Utils_Helper::saveSettings($request);
        Google_Utils_Helper::saveFieldMappings($sourceModule, $fieldMapping);
        $response = new Ncrm_Response;
        $result = array('settingssaved' => true);
        $response->setResult($result);
        $response->emit();
    }
    
}

?>