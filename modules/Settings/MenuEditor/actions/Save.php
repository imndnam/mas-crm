<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Settings_MenuEditor_Save_Action extends Settings_Ncrm_Index_Action {

	public function process(Ncrm_Request $request) {
		$moduleName = $request->getModule(false);
		$menuEditorModuleModel = Settings_Ncrm_Module_Model::getInstance($moduleName);
		$selectedModulesList = $request->get('selectedModulesList');

		if ($selectedModulesList) {
			$menuEditorModuleModel->set('selectedModulesList', $selectedModulesList);
			$menuEditorModuleModel->saveMenuStruncture();
		}
		$loadUrl = $menuEditorModuleModel->getIndexViewUrl();
		header("Location: $loadUrl");
	}

        public function validateRequest(Ncrm_Request $request) { 
            $request->validateWriteAccess(); 
        }
}
