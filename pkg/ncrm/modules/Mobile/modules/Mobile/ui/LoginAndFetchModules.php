<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/
include_once dirname(__FILE__) . '/../api/ws/LoginAndFetchModules.php';

class Mobile_UI_LoginAndFetchModules extends Mobile_WS_LoginAndFetchModules {
	
	protected function cacheModules($modules) {
		$this->sessionSet("_MODULES", $modules);
	}
	
	function process(Mobile_API_Request $request) {
		$wsResponse = parent::process($request);
		
		$response = false;
		if($wsResponse->hasError()) {
			$response = $wsResponse;
		} else {
			$wsResponseResult = $wsResponse->getResult();
			
			$modules = Mobile_UI_ModuleModel::buildModelsFromResponse($wsResponseResult['modules']);
			$this->cacheModules($modules);

			$viewer = new Mobile_UI_Viewer();
			$viewer->assign('_MODULES', $modules);

			$response = $viewer->process('generic/Home.tpl');
		}
		return $response;
	}

}