<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_MailConverter_CheckMailBoxMaxLimit_Action extends Settings_Ncrm_Index_Action {
	
	public function process(Ncrm_Request $request) {
		$recordsCount = Settings_MailConverter_Record_Model::getCount();
		$qualifiedModuleName = $request->getModule(false);
		$response = new Ncrm_Response();
        global $max_mailboxes;
        if ($recordsCount < $max_mailboxes) {
			$result = array(true);
			$response->setResult($result);
		} else {
			$response->setError(vtranslate('LBL_MAX_LIMIT_EXCEEDED', $qualifiedModuleName));
		}
		$response->emit();
	}
}
?>
