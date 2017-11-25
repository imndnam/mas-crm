<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/
require_once('modules/com_ncrm_workflow/VTEntityMethodManager.inc');
class VTEntityMethodTask extends VTTask{
	
	public $executeImmediately = true;
	
	public function getFieldNames(){return array('methodName');}
	public function doTask($entityData){
		global $adb;
		$emm = new VTEntityMethodManager($adb);
		$emm->executeMethod($entityData, $this->methodName);
	}
}
?>