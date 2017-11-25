<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/
class ConfigEditor_Request {
	protected $valuemap;
	
	function __construct($values) {
		$this->valuemap = $values;
	}
	
	function get($key, $defvalue='') {
		$value = $defvalue;
		if (isset($this->valuemap[$key])) {
			$value = $this->valuemap[$key];
		}
		if (!empty($value)) {
			$value = vtlib_purify($value);
		}
		return $value;
	}
	
	function values() {
		return $this->valuemap;
	}
}
?>