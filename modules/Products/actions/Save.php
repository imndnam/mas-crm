<?php

/* +***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 * *********************************************************************************** */

class Products_Save_Action extends Ncrm_Save_Action {

	public function process(Ncrm_Request $request) {
		$result = Ncrm_Util_Helper::transformUploadedFiles($_FILES, true);
		$_FILES = $result['imagename'];
		parent::process($request);
	}
}
