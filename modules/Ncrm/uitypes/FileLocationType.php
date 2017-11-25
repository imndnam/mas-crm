<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Ncrm_FileLocationType_UIType extends Ncrm_Base_UIType {

	/**
	 * Function to get the Template name for the current UI Type object
	 * @return <String> - Template Name
	 */
	public function getTemplateName() {
		return 'uitypes/FileLocationType.tpl';
	}

	/**
	 * Function to get the Display Value, for the current field type with given DB Insert Value
	 * @param <String> value of field
	 * @return <String> Converted value
	 */
	public function getDisplayValue($value) {
		if ($value === 'I') {
			$value = 'LBL_INTERNAL';
		} else {
			$value = 'LBL_EXTERNAL';
		}
		return vtranslate($value, 'Documents');
	}

}