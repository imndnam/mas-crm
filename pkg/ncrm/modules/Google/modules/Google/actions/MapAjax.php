<?php

/* +***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 * *********************************************************************************** */

class Google_MapAjax_Action extends Ncrm_BasicAjax_Action {

    public function process(Ncrm_Request $request) {
        switch ($request->get("mode")) {
            case 'getLocation':$result = $this->getLocation($request);
                break;
        }
        echo json_encode($result);
    }

    /**
     * get address for the record, based on the module type.
     * @param Ncrm_Request $request
     * @return type 
     */
    function getLocation(Ncrm_Request $request) {
        $address = Google_Map_Helper::getLocation($request);
        return empty($address) ? "" : array("address" => join(",", $address));
    }
    
    public function validateRequest(Ncrm_Request $request) { 
        $request->validateReadAccess(); 
    } 

}

?>
