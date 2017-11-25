<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class SMSNotifier_CheckStatus_View extends Ncrm_IndexAjax_View {

	function checkPermission(Ncrm_Request $request) {
		$moduleName = $request->getModule();

		if(!Users_Privileges_Model::isPermitted($moduleName, 'DetailView', $request->get('record'))) {
			throw new AppException(vtranslate($moduleName).' '.vtranslate('LBL_NOT_ACCESSIBLE'));
		}
	}

	function process(Ncrm_Request $request) {
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();

		$notifierRecordModel = Ncrm_Record_Model::getInstanceById($request->get('record'), $moduleName);
		$notifierRecordModel->checkStatus();

		$viewer->assign('RECORD', $notifierRecordModel);
		$viewer->view('StatusWidget.tpl', $moduleName);
	}
}