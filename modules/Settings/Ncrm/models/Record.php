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
 * Roles Record Model Class
 */
abstract class Settings_Ncrm_Record_Model extends Ncrm_Base_Model {

	abstract function getId();
	abstract function getName();

	public function getRecordLinks() {

		$links = array();
		$recordLinks = array();
		foreach ($recordLinks as $recordLink) {
			$links[] = Ncrm_Link_Model::getInstanceFromValues($recordLink);
		}

		return $links;
	}
	
	public function getDisplayValue($key) {
		return $this->get($key);
	}
}
