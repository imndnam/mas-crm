<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Emails_List_View extends Ncrm_List_View {

	public function preProcess(Ncrm_Request $request) {
	}

	public function process(Ncrm_Request $request) {
		header('Location: index.php?module=MailManager&view=List');
	}
}