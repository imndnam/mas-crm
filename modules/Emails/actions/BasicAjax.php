<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Emails_BasicAjax_Action extends Ncrm_Action_Controller {

	public function checkPermission(Ncrm_Request $request) {
		return;
	}

	public function process(Ncrm_Request $request) {
		$moduleName = $request->get('module');
		$moduleModel = Ncrm_Module_Model::getInstance($moduleName);
		$searchValue = $request->get('searchValue');

		$emailsResult = array();
		if ($searchValue) {
			$emailsResult = $moduleModel->searchEmails($request->get('searchValue'));
		}

		$response = new Ncrm_Response();
		$response->setResult($emailsResult);
		$response->emit();
	}
}

?>
