<?php
/* +***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 * *********************************************************************************** */

class ModTracker_Relation_Model extends Ncrm_Record_Model {

	function setParent($parent) {
		$this->parent = $parent;
	}

	function getParent() {
		return $this->parent;
	}

	function getLinkedRecord() {
        $db = PearDatabase::getInstance();
        
		$targetId = $this->get('targetid');
		$targetModule = $this->get('targetmodule');
        
        $query = 'SELECT * FROM ncrm_crmentity WHERE crmid = ?';
		$params = array($targetId);
		$result = $db->pquery($query, $params);
		$noOfRows = $db->num_rows($result);
		$moduleModels = array();
		if($noOfRows) {
			if(!array_key_exists($targetModule, $moduleModels)) {
				$moduleModel = Ncrm_Module_Model::getInstance($targetModule);
			}
			$row = $db->query_result_rowdata($result, 0);
			$modelClassName = Ncrm_Loader::getComponentClassName('Model', 'Record', $targetModule);
			$recordInstance = new $modelClassName();
			$recordInstance->setData($row)->setModuleFromInstance($moduleModel);
			$recordInstance->set('id', $row['crmid']);
			return $recordInstance;
		}
		return false;
	}
}