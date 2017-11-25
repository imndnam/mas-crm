<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Inventory_MassSave_Action extends Ncrm_MassSave_Action {

	public function process(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$recordModels = $this->getRecordModelsFromRequest($request);
		foreach($recordModels as $recordId => $recordModel) {
			if(Users_Privileges_Model::isPermitted($moduleName, 'Save', $recordId)) {
				//Inventory line items getting wiped out
				$_REQUEST['ajxaction'] = 'DETAILVIEW';
				$recordModel->save();
			}
		}

		$response = new Ncrm_Response();
		$response->setResult(true);
		$response->emit();
	}
}
