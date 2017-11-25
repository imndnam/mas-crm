<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

/**
 * Function to get or set a global variable
 * @param type $key
 * @param type $value
 * @return value of the given key
 */
function vglobal($key, $value=null) {
	$returnVal = false;
	if($value !== null) {
		$GLOBALS[$key] = $value;
	}
	$returnVal = isset($GLOBALS[$key]) ? $GLOBALS[$key] : false;
	return $returnVal;
}