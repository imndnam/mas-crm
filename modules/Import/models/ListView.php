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
 * Ncrm ListView Model Class
 */
class Import_ListView_Model extends Ncrm_ListView_Model {

	/**
	 * Function to get the list of listview links for the module
	 * @param <Array> $linkParams
	 * @return false - no List View Links needed on Import pages
	 */
	public function getListViewLinks($linkParams) {
		return false;
	}

	/**
	 * Function to get the list of Mass actions for the module
	 * @param <Array> $linkParams
	 * @return false - no List View Links needed on Import pages
	 */
	public function getListViewMassActions($linkParams) {
		return false;
	}

	/**
	 * Function to get the list view entries
	 * @param Ncrm_Paging_Model $pagingModel
	 * @return <Array> - Associative array of record id mapped to Ncrm_Record_Model instance.
	 */
	public function getListViewEntries($pagingModel) {
		$db = PearDatabase::getInstance();

		$moduleName = $this->getModule()->get('name');
		$moduleFocus = CRMEntity::getInstance($moduleName);
		$moduleModel = Ncrm_Module_Model::getInstance($moduleName);

		$queryGenerator = $this->get('query_generator');
		$listViewContoller = $this->get('listview_controller');

		$listQuery = $queryGenerator->getQuery();

		$startIndex = $pagingModel->getStartIndex();
		$pageLimit = $pagingModel->getPageLimit();

		$importedRecordIds = $this->getLastImportedRecord();
        $listViewRecordModels = array();
		if(count($importedRecordIds) != 0) {
            $moduleModel = $this->get('module');
            $listQuery .= ' AND '.$moduleModel->basetable.'.'.$moduleModel->basetableid.' IN ('. implode(',', $importedRecordIds).')';

            $listQuery .= " LIMIT $startIndex, $pageLimit";

            $listResult = $db->pquery($listQuery, array());

            $listViewEntries =  $listViewContoller->getListViewRecords($moduleFocus,$moduleName, $listResult);
            $pagingModel->calculatePageRange($listViewEntries);
            foreach($listViewEntries as $recordId => $record) {
                $record['id'] = $recordId;
                $listViewRecordModels[$recordId] = $moduleModel->getRecordFromArray($record);
            }

        }
		return $listViewRecordModels;
	}

	/**
	 * Function to get the list view entries
	 * @param Ncrm_Paging_Model $pagingModel
	 * @return <Array> - Associative array of record id mapped to Ncrm_Record_Model instance.
	 */
	public function getListViewCount() {
		$db = PearDatabase::getInstance();

		$queryGenerator = $this->get('query_generator');
		//$queryGenerator->setFields(array('id'));

		$listQuery = $queryGenerator->getQuery();

		$importedRecordIds = $this->getLastImportedRecord();
		if(count($importedRecordIds) != 0) {
			$moduleModel = $this->get('module');
			$listQuery .= ' AND '.$moduleModel->basetable.'.'.$moduleModel->basetableid.' IN ('. implode(',', $importedRecordIds).')';
		}

		$listResult = $db->pquery($listQuery, array());
		return $db->num_rows($listResult);
	}

	/**
	 * Static Function to get the Instance of Ncrm ListView model for a given module and custom view
	 * @param <String> $moduleName - Module Name
	 * @param <Number> $viewId - Custom View Id
	 * @return Ncrm_ListView_Model instance
	 */
	public static function getInstance($moduleName, $viewId='0') {
		$db = PearDatabase::getInstance();
		$currentUser = Users_Record_Model::getCurrentUserModel();

		$modelClassName = Ncrm_Loader::getComponentClassName('Model', 'ListView', 'Import');
		$instance = new $modelClassName();

		$moduleModel = Ncrm_Module_Model::getInstance($moduleName);
		$queryGenerator = new QueryGenerator($moduleModel->get('name'), $currentUser);

		$customView = new CustomView();
		$viewId = $customView->getViewIdByName('All', $moduleName);
		$queryGenerator->initForCustomViewById($viewId);

		$controller = new ListViewController($db, $currentUser, $queryGenerator);

		return $instance->set('module', $moduleModel)->set('query_generator', $queryGenerator)->set('listview_controller', $controller);
	}

	public function getLastImportedRecord() {
		$db = PearDatabase::getInstance();

		$user = Users_Record_Model::getCurrentUserModel();
		$userDBTableName = Import_Utils_Helper::getDbTableName($user);

		$result = $db->pquery('SELECT recordid FROM '.$userDBTableName.' WHERE status NOT IN (?,?) AND recordid IS NOT NULL',Array(Import_Data_Action::$IMPORT_RECORD_FAILED,  Import_Data_Action::$IMPORT_RECORD_SKIPPED));
		$noOfRecords = $db->num_rows($result);

		$importedRecordIds = array();
		for($i=0; $i<$noOfRecords; ++$i) {
			$importedRecordIds[] = $db->query_result($result, $i, 'recordid');
		}
		return $importedRecordIds;
	}
}
