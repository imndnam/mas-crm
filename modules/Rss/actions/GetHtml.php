<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Rss_GetHtml_Action extends Ncrm_Action_Controller {

	 function checkPermission(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$record = $request->get('record');

		$currentUserPrivilegesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		if(!$currentUserPrivilegesModel->isPermitted($moduleName, 'ListView', $record)) {
			throw new AppException('LBL_PERMISSION_DENIED');
		}
	}

	public function process(Ncrm_Request $request) {
		$module = $request->get('module');
        $url = $request->get('url');
        $recordModel = Rss_Record_Model::getCleanInstance($module);
        $html = $recordModel->getHtmlFromUrl($url);

		$response = new Ncrm_Response();
		$response->setResult(array('html'=>$html));
		$response->emit();
	}
}
