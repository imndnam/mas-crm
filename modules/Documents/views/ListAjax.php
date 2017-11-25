<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Documents_ListAjax_View extends Documents_List_View {
	function __construct() {
		parent::__construct();
		$this->exposeMethod('getRecordsCount');
		$this->exposeMethod('getPageCount');
	}

	function preProcess(Ncrm_Request $request) {
		return true;
	}

	function postProcess(Ncrm_Request $request) {
		return true;
	}

	function process(Ncrm_Request $request) {
		$mode = $request->get('mode');
		if(!empty($mode)) {
			$this->invokeExposedMethod($mode, $request);
			return;
		}
	}
}