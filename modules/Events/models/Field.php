<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

/**
 * Events Field Model Class
 */
class Events_Field_Model extends Calendar_Field_Model {


	function get($propertyName) {
		if(property_exists($this,$propertyName)) {
			$fieldName = $this->getName();
			if($propertyName == 'label' && $fieldName == 'due_date') {
				return 'End Date & Time';
			}
			return $this->$propertyName;
		}
		return null;
	}
	
	/**
	 * Customize the display value for detail view.
	 */
	public function getDisplayValue($value, $record = false, $recordInstance = false) {
		if ($recordInstance) {
			if ($this->getName() == 'due_date') {
				$displayValue = $value. ' ' . $recordInstance->get('time_end');
				$value = $this->getUITypeModel()->getDisplayValue($displayValue);
					list($endDate, $endTime,$meridiem) = explode(' ', $value);
				return $endDate . ' ' . $endTime.' '. $meridiem;

			}
		}
		return parent::getDisplayValue($value, $record, $recordInstance);
	}


	/**
	 * Function to check whether field is ajax editable'
	 * @return <Boolean>
	 */
	public function isAjaxEditable() {
		if(!$this->isEditable() || $this->get('uitype') == 30) {
			return false;
		}
		return true;
	}

    public function getFieldDataType() {
        if($this->getName() == 'contact_id' ) {
            return 'multireference';
        }
        return parent::getFieldDataType();

    }
}