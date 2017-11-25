<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class EmailTemplates_DeleteAjax_Action extends Ncrm_Delete_Action {

	public function process(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$recordId = $request->get('record');

		$recordModel = EmailTemplates_Record_Model::getInstanceById($recordId);
		$recordModel->setModule($moduleName);
		$recordModel->delete();

		$cvId = $request->get('viewname');
		$response = new Ncrm_Response();
		$response->setResult(array('viewname'=>$cvId, 'module'=>$moduleName));
		$response->emit();
	}
}
