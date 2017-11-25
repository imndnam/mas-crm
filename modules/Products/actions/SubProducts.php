<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Products_SubProducts_Action extends Ncrm_Action_Controller {

	function checkPermission(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$moduleModel = Ncrm_Module_Model::getInstance($moduleName);

		$currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		if(!$currentUserPriviligesModel->hasModulePermission($moduleModel->getId())) {
			throw new AppException(vtranslate($moduleName).' '.vtranslate('LBL_NOT_ACCESSIBLE'));
		}
	}

	function process(Ncrm_Request $request) {
		$productId = $request->get('record');
		$productModel = Ncrm_Record_Model::getInstanceById($productId, 'Products');
		$subProducts = $productModel->getSubProducts();
		$values = array();
		foreach($subProducts as $subProduct) {
			$values[$subProduct->getId()] = $subProduct->getName();
		}

		$response = new Ncrm_Response();
		$response->setResult($values);
		$response->emit();
	}
}
