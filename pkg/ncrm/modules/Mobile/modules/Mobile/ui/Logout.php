<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Mobile_UI_Logout extends Mobile_WS_Controller {
	
	function process(Mobile_API_Request $request) {
		HTTP_Session2::destroy(HTTP_Session2::detectId());
		header('Location: index.php');
		exit;
	}

}