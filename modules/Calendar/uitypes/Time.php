<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Calendar_Time_UIType extends Ncrm_Time_UIType {


	public function getEditViewDisplayValue($value) {
		if(!empty($value)) {
			return parent::getEditViewDisplayValue($value);
		}

		$specialTimeFields = array('time_start', 'time_end');

		$fieldInstance = $this->get('field')->getWebserviceFieldObject();
		$fieldName = $fieldInstance->getFieldName();

		if(!in_array($fieldName, $specialTimeFields)){
			return parent::getEditViewDisplayValue($value);
		}else{
			return $this->getDisplayTimeDifferenceValue($fieldName, $value);
		}
		
	}

	/**
	 * Function to get the calendar event call duration value in hour format
	 * @param type $fieldName
	 * @param type $value
	 * @return <Ncrm_Time_UIType> - getTimeValue
	 */
	public function getDisplayTimeDifferenceValue($fieldName, $value){
		$userModel = Users_Privileges_Model::getCurrentUserModel();
		$date = new DateTime($value);
		
		//No need to set the time zone as DateTimeField::getDisplayTime API is already doing this
		/*if(empty($value)) {
			$timeZone = $userModel->get('time_zone');
			$targetTimeZone = new DateTimeZone($timeZone);
			$date->setTimezone($targetTimeZone);
		}*/
		
		if($fieldName == 'time_end' && empty($value)) {
			$defaultCallDuration = $userModel->get('callduration');
			$date->modify("+{$defaultCallDuration} minutes");
		}
		
		$dateTimeField = new DateTimeField($date->format('Y-m-d H:i:s'));
		$value = $dateTimeField->getDisplayTime();
		return $value;
	}

}