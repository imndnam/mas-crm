<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/
class Ncrm_CurrencyList_UIType extends Ncrm_Base_UIType {
	/**
	 * Function to get the Template name for the current UI Type Object
	 * @return <String> - Template Name
	 */
	public function getTemplateName() {
		return 'uitypes/CurrencyList.tpl';
	}

	public function getDisplayValue($value) {
		$db = PearDatabase::getInstance();
		$result = $db->pquery('SELECT currency_name FROM ncrm_currency_info WHERE currency_status = ? AND id = ?',
					array('Active', $value));
		if($db->num_rows($result)) {
			return $db->query_result($result, 0, 'currency_name');
		}
		return $value;
	}

	public function getCurrenyListReferenceFieldName() {
		return 'currency_name';
	}
}
?>
