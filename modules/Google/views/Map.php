<?php

/* +***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 * *********************************************************************************** */

class Google_Map_View extends Ncrm_Detail_View {

    /**
     * must be overriden
     * @param Ncrm_Request $request
     * @return boolean 
     */
    function preProcess(Ncrm_Request $request) {
        return true;
    }

    /**
     * must be overriden
     * @param Ncrm_Request $request
     * @return boolean 
     */
    function postProcess(Ncrm_Request $request) {
        return true;
    }

    /**
     * called when the request is recieved.
     * if viewtype : detail then show location
     * TODO : if viewtype : list then show the optimal route.    
     * @param Ncrm_Request $request
     */
    function process(Ncrm_Request $request) {
        switch ($request->get('viewtype')) {
            case 'detail':$this->showLocation($request);
                break;
            default:break;
        }
    }

    /**
     * display the template.
     * @param Ncrm_Request $request
     */
    function showLocation(Ncrm_Request $request) {
        $viewer = $this->getViewer($request);
        // record and source_module values to be passed to populate the values in the template,
        // required to get the respective records address based on the module type.
        $viewer->assign('RECORD', $request->get('record'));
        $viewer->assign('SOURCE_MODULE', $request->get('source_module'));
        $viewer->view('map.tpl', $request->getModule());
    }

}

?>
