<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

include_once 'includes/runtime/Viewer.php';

class Mobile_UI_Viewer extends Ncrm_Viewer{

	private $parameters = array();
	function assign($key, $value) {
		$this->parameters[$key] = $value;
	}

	function viewController() {
		$smarty = new Ncrm_Viewer();

		foreach($this->parameters as $k => $v) {
			$smarty->assign($k, $v);
		}

		$smarty->assign("IS_SAFARI", Mobile::isSafari());
		$smarty->assign("SKIN", Mobile::config('Default.Skin'));
		return $smarty;
	}

	function process($templateName) {
		$smarty = $this->viewController();
		$response = new Mobile_API_Response();
		$response->setResult($smarty->fetch(vtlib_getModuleTemplate('Mobile', $templateName)));
		return $response;
	}

}