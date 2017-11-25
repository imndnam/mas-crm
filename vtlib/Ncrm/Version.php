<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/
include_once('ncrmversion.php');

/**
 * Provides utility APIs to work with Ncrm Version detection
 * @package vtlib
 */
class Ncrm_Version {

	/**
	 * Get current version of ncrm in use.
	 */
	static function current() {
		global $ncrm_current_version;
		return $ncrm_current_version;
	}

	/**
	 * Check current version of ncrm with given version
	 * @param String Version against which comparision to be done
	 * @param String Condition like ( '=', '!=', '<', '<=', '>', '>=')
	 */
	static function check($with_version, $condition='=') {
		$current_version = self::current();
		//xml node is passed to this method sometimes
		if(!is_string($with_version)) {
			$with_version = (string) $with_version;
		}
		$with_version = self::getUpperLimitVersion($with_version);
		return version_compare($current_version, $with_version, $condition);
	}
	
	static function endsWith($string, $endString) {
		$strLen = strlen($string);
    	$endStrLen = strlen($endString);
    	if ($endStrLen > $strLen) return false;
    	return substr_compare($string, $endString, -$endStrLen) === 0;		
	}
	
	static function getUpperLimitVersion($version) {
		if(!self::endsWith($version, '.*')) return $version;
		
		$version = rtrim($version, '.*');
		$lastVersionPartIndex = strrpos($version, '.');
		if ($lastVersionPartIndex === false) {
			$version = ((int) $version) + 1;	
		} else {
			$lastVersionPart = substr($version, $lastVersionPartIndex+1, strlen($version));
			$upgradedVersionPart = ((int) $lastVersionPart) + 1;
			$version = substr($version, 0, $lastVersionPartIndex+1) . $upgradedVersionPart;
		}
		return $version;
	}
}
?>
