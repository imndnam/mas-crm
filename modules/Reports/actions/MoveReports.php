<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Reports_MoveReports_Action extends Ncrm_Mass_Action {

	public function checkPermission(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$moduleModel = Reports_Module_Model::getInstance($moduleName);

		$currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		if(!$currentUserPriviligesModel->hasModulePermission($moduleModel->getId())) {
			throw new AppException('LBL_PERMISSION_DENIED');
		}
	}

	public function process(Ncrm_Request $request) {
		$parentModule = 'Reports';
		$reportIdsList = Reports_Record_Model::getRecordsListFromRequest($request);
		$folderId = $request->get('folderid');

		if (!empty ($reportIdsList)) {
			foreach ($reportIdsList as $reportId) {
				$reportModel = Reports_Record_Model::getInstanceById($reportId);
				if (!$reportModel->isDefault() && $reportModel->isEditable()) {
					$reportModel->move($folderId);
				} else {
					$reportsMoveDenied[] = vtranslate($reportModel->getName(), $parentModule);
				}
			}
		}
		$response = new Ncrm_Response();
		if (empty ($reportsMoveDenied)) {
			$response->setResult(array(vtranslate('LBL_REPORTS_MOVED_SUCCESSFULLY', $parentModule)));
		} else {
			$response->setError($reportsMoveDenied, vtranslate('LBL_DENIED_REPORTS', $parentModule));
		}

		$response->emit();
	}
}