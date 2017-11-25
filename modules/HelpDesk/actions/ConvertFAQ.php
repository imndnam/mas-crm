<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class HelpDesk_ConvertFAQ_Action extends Ncrm_Action_Controller {

	public function checkPermission(Ncrm_Request $request) {
		$recordPermission = Users_Privileges_Model::isPermitted('Faq', 'EditView');

		if(!$recordPermission) {
			throw new AppException('LBL_PERMISSION_DENIED');
		}
	}

	public function process(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$recordId = $request->get('record');

		$result = array();
		if (!empty ($recordId)) {
			$recordModel = Ncrm_Record_Model::getInstanceById($recordId, $moduleName);

			$faqRecordModel = Faq_Record_Model::getInstanceFromHelpDesk($recordModel);

			$answer = $faqRecordModel->get('faq_answer');
			if ($answer) {
				$faqRecordModel->save();
				header("Location: ".$faqRecordModel->getDetailViewUrl());
			} else {
				header("Location: ".$faqRecordModel->getEditViewUrl()."&parentId=$recordId&parentModule=$moduleName");
			}
		}
	}
}
