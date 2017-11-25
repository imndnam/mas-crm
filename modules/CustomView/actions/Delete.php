<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class CustomView_Delete_Action extends Ncrm_Action_Controller {

	public function process(Ncrm_Request $request) {
		$customViewModel = CustomView_Record_Model::getInstanceById($request->get('record'));
		$moduleModel = $customViewModel->getModule();

		$customViewModel->delete();

		$listViewUrl = $moduleModel->getListViewUrl();
		header("Location: $listViewUrl");
	}
    
    public function validateRequest(Ncrm_Request $request) {
        $request->validateWriteAccess();
    }
}
