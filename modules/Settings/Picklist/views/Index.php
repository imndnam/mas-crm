<?php

/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_Picklist_Index_View extends Settings_Ncrm_Index_View {
    
    public function process(Ncrm_Request $request) {
        
        $sourceModule = $request->get('source_module');
        $pickListSupportedModules = Settings_Picklist_Module_Model::getPicklistSupportedModules();
        if(empty($sourceModule)) {
            //take the first module as the source module
            $sourceModule = $pickListSupportedModules[0]->name;
        }
        $moduleModel = Settings_Picklist_Module_Model::getInstance($sourceModule);
        $viewer = $this->getViewer($request);
        $qualifiedName = $request->getModule(FALSE);
        
        $viewer->assign('PICKLIST_MODULES',$pickListSupportedModules);
        
        //TODO: see if you needs to optimize this , since its will gets all the fields and filter picklist fields
        $pickListFields = $moduleModel->getFieldsByType(array('picklist','multipicklist'));
        if(count($pickListFields) > 0) {
            $selectedPickListFieldModel = reset($pickListFields);

            $selectedFieldAllPickListValues = Ncrm_Util_Helper::getPickListValues($selectedPickListFieldModel->getName());
            
			
            $viewer->assign('PICKLIST_FIELDS',$pickListFields);
            $viewer->assign('SELECTED_PICKLIST_FIELDMODEL',$selectedPickListFieldModel);
            $viewer->assign('SELECTED_PICKLISTFIELD_ALL_VALUES',$selectedFieldAllPickListValues);
            $viewer->assign('ROLES_LIST', Settings_Roles_Record_Model::getAll());
        }else{
            $viewer->assign('NO_PICKLIST_FIELDS',true);
            $createPicklistUrl = '';
            $settingsLinks = $moduleModel->getSettingLinks();
            foreach($settingsLinks as $linkDetails) {
                if($linkDetails['linklabel'] == 'LBL_EDIT_FIELDS') {
                    $createPicklistUrl = $linkDetails['linkurl'];
                    break;
                }
            }
            $viewer->assign('CREATE_PICKLIST_URL',$createPicklistUrl);
                
        }
        $viewer->assign('SELECTED_MODULE_NAME', $sourceModule);
        $viewer->assign('QUALIFIED_NAME',$qualifiedName);
        
		$viewer->view('Index.tpl',$qualifiedName);
    }
	
	function getHeaderScripts(Ncrm_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$moduleName = $request->getModule();

		$jsFileNames = array(
			"modules.$moduleName.resources.$moduleName",
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}
}