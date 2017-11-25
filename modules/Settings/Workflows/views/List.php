<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_Workflows_List_View extends Settings_Ncrm_List_View {

	public function preProcess(Ncrm_Request $request, $display=true) {
		$viewer = $this->getViewer($request);
		$viewer->assign('SUPPORTED_MODULE_MODELS', Settings_Workflows_Module_Model::getSupportedModules());
        $viewer->assign('CRON_RECORD_MODEL', Settings_CronTasks_Record_Model::getInstanceByName('Workflow'));
		parent::preProcess($request, $display);
	}
}
