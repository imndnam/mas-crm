<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/
include_once 'vtlib/Ncrm/Mailer.php';

class Emails_Mailer_Model extends Ncrm_Mailer {

	public static function getInstance() {
		return new self();
	}

	/**
	 * Function returns error from phpmailer
	 * @return <String>
	 */
	function getError() {
		return $this->ErrorInfo;
	}
}
