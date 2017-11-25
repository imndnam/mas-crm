<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: NCRM Open source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class MailManager_Request extends Ncrm_Request {

	public function get($key, $defvalue = '') {
		return urldecode(parent::get($key, $defvalue));
	}

	public static function getInstance($request) {
		return new MailManager_Request($request->getAll(), $request->getAll());
	}
}