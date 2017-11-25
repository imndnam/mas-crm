<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_CustomerPortal_Save_Action extends Settings_Ncrm_Index_Action {

	public function process(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$qualifiedModuleName = $request->getModule(false);
		$privileges = $request->get('privileges');
		$defaultAssignee = $request->get('defaultAssignee');
		$portalModulesInfo = $request->get('portalModulesInfo');

		if ($privileges && $defaultAssignee && $portalModulesInfo) {
			$moduleModel = Settings_CustomerPortal_Module_Model::getInstance($qualifiedModuleName);
			$moduleModel->set('privileges', $privileges);
			$moduleModel->set('defaultAssignee', $defaultAssignee);
			$moduleModel->set('portalModulesInfo', $portalModulesInfo);
			$moduleModel->save();
		}
		
		$responce = new Ncrm_Response();
        $responce->setResult(array('success'=>true));
        $responce->emit();
	}
}