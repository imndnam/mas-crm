<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class PurchaseOrder_CompanyDetails_Action extends Ncrm_Action_Controller {

	function checkPermission(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$moduleModel = Ncrm_Module_Model::getInstance($moduleName);

		$currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		if(!$currentUserPriviligesModel->hasModulePermission($moduleModel->getId())) {
			throw new AppException(vtranslate($moduleName).' '.vtranslate('LBL_NOT_ACCESSIBLE'));
		}
	}

	function process(Ncrm_Request $request) {
		$companyModel = Ncrm_CompanyDetails_Model::getInstanceById();
        $companyDetails = array(
            'street' => $companyModel->get('organizationname') .' '.$companyModel->get('address'),
            'city' => $companyModel->get('city'),
            'state' => $companyModel->get('state'),
            'code' => $companyModel->get('code'),
            'country' =>  $companyModel->get('country'),
            );
		$response = new Ncrm_Response();
		$response->setResult($companyDetails);
		$response->emit();
	}
}

?>