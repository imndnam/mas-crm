<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Products_MassSave_Action extends Ncrm_MassSave_Action {

	public function process(Ncrm_Request $request) {
		//the new values are added to $_REQUEST for MassSave, are removing the Tax details depend on the 'action' value
		$_REQUEST['action'] = 'MassEditSave';
		$request->set('action', 'MassEditSave');

		//the new values are added to $_REQUEST for MassSave, the unit price depend on the 'mass_edit_check' value
		$_REQUEST['unit_price_mass_edit_check'] = 'off';
		parent::process($request);
	}

	/**
	 * Function to get the record model based on the request parameters
	 * @param Ncrm_Request $request
	 * @return Ncrm_Record_Model or Module specific Record Model instance
	 */
	function getRecordModelsFromRequest(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$moduleModel = Ncrm_Module_Model::getInstance($moduleName);

		$recordModels = parent::getRecordModelsFromRequest($request);
		$fieldModelList = $moduleModel->getFields();

		foreach($recordModels as $id => $model) {
			foreach ($fieldModelList as $fieldName => $fieldModel) {
				$fieldDataType = $fieldModel->getFieldDataType();
				// This is added as we are marking massedit in ncrm6 as not an ajax operation
				// and this will force the date fields to be saved in user format. If the user format
				// is other than y-m-d then it fails. We need to review the above process API changes
				// which was added to fix unit price issue where it was getting changed when mass edited.
				if($fieldDataType == 'date' || ($fieldDataType == 'currency') && $uiType != '72') {
					$model->set($fieldName, $fieldModel->getUITypeModel()->getDBInsertValue($model->get($fieldName)));
				}
			}
		}
		return $recordModels;
	}
}
