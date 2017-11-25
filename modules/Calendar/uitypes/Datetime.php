<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Calendar_Datetime_UIType extends Ncrm_Datetime_UIType {
	
	public function getDisplayValue($value) {
		//Since date_start and due_date fields of calendar can have time appended or removed
		if($this->hasTimeComponent($value)) {
			return self::getDisplayDateTimeValue($value);
		}else{
			return $this->getDisplayDateValue($value);
		}
	}

	public function hasTimeComponent($value) {
		$component = explode(' ', $value);
		if(!empty($component[1])) {
			return true;
		}
		return false;
	}
}


