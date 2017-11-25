<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_CronTasks_UpdateSequence_Action extends Settings_Ncrm_Index_Action {

	public function process(Ncrm_Request $request) {
		$qualifiedModuleName = $request->getModule(false);
		$sequencesList = $request->get('sequencesList');

		$moduleModel = Settings_CronTasks_Module_Model::getInstance($qualifiedModuleName);

		$response = new Ncrm_Response();
		if ($sequencesList) {
			$moduleModel->updateSequence($sequencesList);
			$response->setResult(array(true));
		} else {
			$response->setError();
		}

		$response->emit();
	}

}