<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Settings_CustomerPortal_Module_Model extends Settings_Ncrm_Module_Model {

	var $name = 'CustomerPortal';

	/**
	 * Function to get Current portal user
	 * @return <Interger> userId
	 */
	public function getCurrentPortalUser() {
		$db = PearDatabase::getInstance();

		$result = $db->pquery("SELECT prefvalue FROM ncrm_customerportal_prefs WHERE prefkey = 'userid' AND tabid = 0", array());
		if ($db->num_rows($result)) {
			return $db->query_result($result, 0, 'prefvalue');
		}
		return false;
	}

	/**
	 * Function to get current default assignee from portal
	 * @return <Integer> userId
	 */
	public function getCurrentDefaultAssignee() {
		$db = PearDatabase::getInstance();

		$result = $db->pquery("SELECT prefvalue FROM ncrm_customerportal_prefs WHERE prefkey = 'defaultassignee' AND tabid = 0", array());
		if ($db->num_rows($result)) {
			return $db->query_result($result, 0, 'prefvalue');
		}
		return false;
	}

	/**
	 * Function to get list of portal modules
	 * @return <Array> list of portal modules <Ncrm_Module_Model>
	 */
	public function getModulesList() {
		if (!$this->portalModules) {
			$db = PearDatabase::getInstance();

			$query = "SELECT ncrm_customerportal_tabs.*, ncrm_customerportal_prefs.prefvalue, ncrm_tab.name FROM ncrm_customerportal_tabs
					INNER JOIN ncrm_customerportal_prefs ON ncrm_customerportal_prefs.tabid = ncrm_customerportal_tabs.tabid AND ncrm_customerportal_prefs.prefkey='showrelatedinfo'
					INNER JOIN ncrm_tab ON ncrm_customerportal_tabs.tabid = ncrm_tab.tabid AND ncrm_tab.presence = 0 ORDER BY ncrm_customerportal_tabs.sequence";

			$result = $db->pquery($query, array());
			$rows = $db->num_rows($result);

			for($i=0; $i<$rows; $i++) {
				$rowData = $db->query_result_rowdata($result, $i);
				$tabId = $rowData['tabid'];
				$moduleModel = Ncrm_Module_Model::getInstance($tabId);
				foreach ($rowData as $key => $value) {
					$moduleModel->set($key, $value);
				}
				$portalModules[$tabId] = $moduleModel;
			}
			$this->portalModules = $portalModules;
		}
		return $this->portalModules;
	}

	/**
	 * Function to save the details of Portal modules
	 */
	public function save() {
		$db = PearDatabase::getInstance();
		$privileges = $this->get('privileges');
		$defaultAssignee = $this->get('defaultAssignee');
		$portalModulesInfo = $this->get('portalModulesInfo');
		
		//Update details of view all record option for every module from Customer portal
		$updateQuery = "UPDATE ncrm_customerportal_prefs SET prefvalue = CASE ";
		foreach ($portalModulesInfo as $tabId => $moduleDetails) {
			$prefValue = $moduleDetails['prefValue'];
			$updateQuery .= " WHEN tabid = $tabId THEN $prefValue ";
		}
		$updateQuery .= " WHEN prefkey = ? THEN $privileges ";
		$updateQuery .= " WHEN prefkey = ? THEN $defaultAssignee ";
		$updateQuery .= " ELSE prefvalue END";

		$db->pquery($updateQuery, array('userid', 'defaultassignee'));

		//Update the sequence of every module in Customer portal
		$updateSequenceQuery = "UPDATE ncrm_customerportal_tabs SET visible = CASE ";

		foreach ($portalModulesInfo as $tabId => $moduleDetails) {
			$visible = $moduleDetails['visible'];
			$updateSequenceQuery .= " WHEN tabid = $tabId THEN $visible ";
		}

		$updateSequenceQuery .= " END, sequence = CASE ";
		foreach ($portalModulesInfo as $tabId => $moduleDetails) {
			$sequence = $moduleDetails['sequence'];
			$updateSequenceQuery .= " WHEN tabid = $tabId THEN $sequence ";
		}
		$updateSequenceQuery .= "END";
		
		$db->pquery($updateSequenceQuery, array());
	}
}
