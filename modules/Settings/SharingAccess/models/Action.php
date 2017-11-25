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
 * Sharing Access Action Model Class
 */
class Settings_SharingAccess_Action_Model extends Ncrm_Base_Model {

	static $nonConfigurableActions = array('Hide Details', 'Hide Details and Add Events', 'Show Details', 'Show Details and Add Events');

	public function getId() {
		return $this->get('share_action_id');
	}

	public function getName() {
		return $this->get('share_action_name');
	}

	public function isUtilityTool() {
		return false;
	}

	public function isModuleEnabled($module) {
		$db = PearDatabase::getInstance();
		$tabId = $module->getId();

		$sql = 'SELECT 1 FROM ncrm_org_share_action2tab WHERE tabid = ? AND share_action_id = ?';
		$params = array($tabId, $this->getId());
		$result = $db->pquery($sql, $params);
		if($result && $db->num_rows($result) > 0) {
			return true;
		}
		return false;
	}

	public static function getInstanceFromQResult($result, $rowNo=0) {
		$db = PearDatabase::getInstance();
		$row = $db->query_result_rowdata($result, $rowNo);
		$actionModel = new Settings_SharingAccess_Action_Model();
		return $actionModel->setData($row);
	}

	public static function getInstance($value) {
		$db = PearDatabase::getInstance();

		if(Ncrm_Utils::isNumber($value)) {
			$sql = 'SELECT * FROM ncrm_org_share_action_mapping WHERE share_action_id = ?';
		} else {
			$sql = 'SELECT * FROM ncrm_org_share_action_mapping WHERE share_action_name = ?';
		}
		$params = array($value);
		$result = $db->pquery($sql, $params);
		if($db->num_rows($result) > 0) {
			return self::getInstanceFromQResult($result);
		}
		return null;
	}

	public static function getAll($configurable=true) {
		$db = PearDatabase::getInstance();

		$sql = 'SELECT * FROM ncrm_org_share_action_mapping';
		$params = array();
		if($configurable) {
			$sql .= ' WHERE share_action_name NOT IN ('. generateQuestionMarks(self::$nonConfigurableActions) .')';
			array_push($params, self::$nonConfigurableActions);
		}
		$result = $db->pquery($sql, $params);
		$noOfRows = $db->num_rows($result);
		$actionModels = array();
		for($i=0; $i<$noOfRows; ++$i) {
			$actionModels[] = self::getInstanceFromQResult($result, $i);
		}
		return $actionModels;
	}
}