<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Settings_LoginHistory_Record_Model extends Settings_Ncrm_Record_Model {
	
	/**
	 * Function to get the Id
	 * @return <Number> Profile Id
	 */
	public function getId() {
		return $this->get('login_id');
	}

	/**
	 * Function to get the Profile Name
	 * @return <String>
	 */
	public function getName() {
		return $this->get('user_name');
	}
	
	public function getAccessibleUsers(){
		$adb = PearDatabase::getInstance();
		$usersListArray = array();
		
		$query = 'SELECT user_name, first_name, last_name FROM ncrm_users';
		$result = $adb->pquery($query, array());
		while($row = $adb->fetchByAssoc($result)) {
			$usersListArray[$row['user_name']] = getFullNameFromArray('Users', $row);
		}
		return $usersListArray;
	}
	
	/**
	 * Function to retieve display value for a field
	 * @param <String> $fieldName - field name for which values need to get
	 * @return <String>
	 */
	public function getDisplayValue($fieldName, $recordId = false) {
		if($fieldName == 'login_time' || $fieldName == 'logout_time'){
			if($this->get($fieldName) != '0000-00-00 00:00:00'){
				return Ncrm_Datetime_UIType::getDateTimeValue($this->get($fieldName));
			}else{
				return '---';
			}
		} else {
			return $this->get($fieldName);
		}
		
	}
}
