<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/
include_once 'include/Webservices/Delete.php';

class Mobile_WS_DeleteRecords extends Mobile_WS_Controller {
	
	function process(Mobile_API_Request $request) {
		global $current_user;
		
		$current_user = $this->getActiveUser();
		
		$records = $request->get('records');
		if (empty($records)) {
			$records = array($request->get('record'));
		} else {
			$records = Zend_Json::decode($records);
		}
		
		$deleted = array();
		foreach($records as $record) {
			try {
				vtws_delete($record, $current_user);
				$result = true;
			} catch(Exception $e) {
				$result = false;
			}
			$deleted[$record] = $result;
		}
		
		$response = new Mobile_API_Response();
		$response->setResult(array('deleted' => $deleted));
		
		return $response;
	}
}