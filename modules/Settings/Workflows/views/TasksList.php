<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_Workflows_TasksList_View extends Settings_Ncrm_Index_View {

	public function process(Ncrm_Request $request) {
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		$qualifiedModuleName = $request->getModule(false);

		$recordId = $request->get('record');
		$workflowModel = Settings_Workflows_Record_Model::getInstance($recordId);

		$viewer->assign('WORKFLOW_MODEL', $workflowModel);

		$viewer->assign('TASK_LIST', $workflowModel->getTasks());
		$viewer->assign('MODULE', $moduleName);
		$viewer->assign('RECORD',$recordId);
		$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
		$viewer->view('TasksList.tpl', $qualifiedModuleName);
	}
}