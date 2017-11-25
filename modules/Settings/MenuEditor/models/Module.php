<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Settings_MenuEditor_Module_Model extends Settings_Ncrm_Module_Model {
	
	var $name = 'MenuEditor';

	/**
	 * Function to save the menu structure
	 */
	public function saveMenuStruncture() {
		$db = PearDatabase::getInstance();
		$selectedModulesList = $this->get('selectedModulesList');

		$updateQuery = "UPDATE ncrm_tab SET tabsequence = CASE tabid ";

		foreach ($selectedModulesList as $sequence => $tabId) {
			$updateQuery .= " WHEN $tabId THEN $sequence ";
		}
		$updateQuery .= "ELSE -1 END";

		$db->pquery($updateQuery, array());
	}
}
