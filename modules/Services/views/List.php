<?php

/* +***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 * *********************************************************************************** */

Class Services_List_View extends Ncrm_List_View {
    
    /**
    * Function to get the list of Script models to be included
    * @param Ncrm_Request $request
    * @return <Array> - List of Ncrm_JsScript_Model instances
    */
   function getHeaderScripts(Ncrm_Request $request) {
           $headerScriptInstances = parent::getHeaderScripts($request);

           $moduleName = $request->getModule();
           $modulePopUpFile = 'modules.'.$moduleName.'.resources.Edit';
           unset($headerScriptInstances[$modulePopUpFile]);


           $jsFileNames = array(
                'modules.Products.resources.Edit',
           );
           $jsFileNames[] = $modulePopUpFile;
           $jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
           $headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
           return $headerScriptInstances;
   }
}