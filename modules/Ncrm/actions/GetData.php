<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Ncrm_GetData_Action extends Ncrm_IndexAjax_View {

	public function process(Ncrm_Request $request) {
		$record = $request->get('record');
		$sourceModule = $request->get('source_module');
		$response = new Ncrm_Response();

		$permitted = Users_Privileges_Model::isPermitted($sourceModule, 'DetailView', $record);
		if($permitted) {
			$recordModel = Ncrm_Record_Model::getInstanceById($record, $sourceModule);
			$data = $recordModel->getData();
			$response->setResult(array('success'=>true, 'data'=>array_map('decode_html',$data)));
		} else {
			$response->setResult(array('success'=>false, 'message'=>vtranslate('LBL_PERMISSION_DENIED')));
		}
		$response->emit();
	}
}
