<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class SMSNotifier_Edit_View extends Ncrm_Edit_View {

	public function checkPermission(Ncrm_Request $request) {
		throw new AppException(vtranslate('LBL_PERMISSION_DENIED', $request->getModule()));
	}
}