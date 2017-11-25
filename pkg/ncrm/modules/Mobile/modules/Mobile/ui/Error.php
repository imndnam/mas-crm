<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Mobile_UI_Error  extends Mobile_WS_Controller {
	protected $error;
	
	function setError($e) {
		$this->error = $e;
	}
	
	function process(Mobile_API_Request $request) {
		$viewer = new Mobile_UI_Viewer();
		$viewer->assign('errorcode', $this->error['code']);
		$viewer->assign('errormsg', $this->error['message']);
		return $viewer->process('generic/Error.tpl');
	}

}