<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/
require_once 'modules/Emails/mail.php';
class Inventory_Save_Action extends Ncrm_Save_Action {
    
    protected function getRecordModelFromRequest(Ncrm_Request $request) {
		return parent::getRecordModelFromRequest($request);
		
	}
    
}
