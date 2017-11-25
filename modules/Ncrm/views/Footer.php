<?php

/* +***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 * *********************************************************************************** */

abstract class Ncrm_Footer_View extends Ncrm_Header_View {

	function __construct() {
		parent::__construct();
	}

	//Note: To get the right hook for immediate parent in PHP,
	// specially in case of deep hierarchy
	/*function preProcessParentTplName(Ncrm_Request $request) {
		return parent::preProcessTplName($request);
	}*/

	/*function postProcess(Ncrm_Request $request) {
		parent::postProcess($request);
	}*/
}
