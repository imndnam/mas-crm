<?php
/* +***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 * *********************************************************************************** */

class Reports_Folder_Model extends Ncrm_Base_Model {

	/**
	 * Function to get the id of the folder
	 * @return <Number>
	 */
	function getId() {
		return $this->get('folderid');
	}

	/**
	 * Function to set the if for the folder
	 * @param <Number>
	 */
	function setId($value) {
		$this->set('folderid', $value);
	}

	/**
	 * Function to get the name of the folder
	 * @return <String>
	 */
	function getName() {
		return $this->get('foldername');
	}
    
	/**
	 * Function returns the instance of Folder model
	 * @return <Reports_Folder_Model>
	 */
	public static function getInstance() {
		return new self();
	}

	/**
	 * Function saves the folder
	 */
	function save() {
		$db = PearDatabase::getInstance();

		$folderId = $this->getId();
		if(!empty($folderId)) {
			$db->pquery('UPDATE ncrm_reportfolder SET foldername = ?, description = ? WHERE folderid = ?',
					array($this->getName(), $this->getDescription(), $folderId));
		} else {
			$result = $db->pquery('SELECT MAX(folderid) AS folderid FROM ncrm_reportfolder', array());
			$folderId = (int) ($db->query_result($result, 0, 'folderid')) + 1;

			$db->pquery('INSERT INTO ncrm_reportfolder(folderid, foldername, description, state) VALUES(?, ?, ?, ?)', array($folderId, $this->getName(), $this->getDescription(), 'CUSTOMIZED'));
			$this->set('folderid', $folderId);
		}
	}

	/**
	 * Function deletes the folder
	 */
	function delete() {
		$db = PearDatabase::getInstance();
		$db->pquery('DELETE FROM ncrm_reportfolder WHERE folderid = ?', array($this->getId()));
	}

	/**
	 * Function returns Report Models for the folder
	 * @param <Ncrm_Paging_Model> $pagingModel
	 * @return <Reports_Record_Model>
	 */
	function getReports($pagingModel) {
		$paramsList = array(
						'startIndex'=>$pagingModel->getStartIndex(),
						'pageLimit'=>$pagingModel->getPageLimit(),
						'orderBy'=>$this->get('orderby'),
						'sortBy'=>$this->get('sortby'));

		$reportClassInstance = Ncrm_Module_Model::getClassInstance('Reports');
		
		$fldrId = $this->getId ();
		if($fldrId == 'All') {
			$fldrId = false;
			$paramsList = array( 'startIndex'=>$pagingModel->getStartIndex(),
								 'pageLimit'=>$pagingModel->getPageLimit(),
								 'orderBy'=>$this->get('orderby'),
								 'sortBy'=>$this->get('sortby')
							);
		}
		
		$reportsList = $reportClassInstance->sgetRptsforFldr($fldrId, $paramsList);
		if(!$fldrId){
			foreach ($reportsList as $reportId => $reports) {
				$reportsCount += count($reports);
			}
		}else{
			$reportsCount = count($reportsList);
		}
		
		$pageLimit = $pagingModel->getPageLimit();
		if($reportsCount > $pageLimit){
			if(!$fldrId){
				$lastKey = end(array_keys($reportsList));
				array_pop($reportsList[$lastKey]);
			}else{
				array_pop($reportsList);
			}
			$pagingModel->set('nextPageExists', true);
		}else{
			$pagingModel->set('nextPageExists', false);
		}
		
		$reportModuleModel = Ncrm_Module_Model::getInstance('Reports');
		
		if($fldrId == false) {
			return $this->getAllReportModels($reportsList, $reportModuleModel);
		} else {
			$reportModels = array();
			for($i=0; $i < count($reportsList); $i++) {
				$reportModel = new Reports_Record_Model();

				$reportModel->setData($reportsList[$i])->setModuleFromInstance($reportModuleModel);
				$reportModels[] = $reportModel;
				unset($reportModel);
			}
			return $reportModels;
		}
	}

	/**
	 * Function to get the description of the folder
	 * @return <String>
	 */
	function getDescription() {
		return $this->get('description');
	}

	/**
	 * Function to get the url for edit folder from list view of the module
	 * @return <string> - url
	 */
	function getEditUrl() {
		return 'index.php?module=Reports&view=EditFolder&folderid='.$this->getId();
	}

	/**
	 * Function to get the url for delete folder from list view of the module
	 * @return <string> - url
	 */
	function getDeleteUrl() {
		return 'index.php?module=Reports&action=Folder&mode=delete&folderid='.$this->getId();
	}

	/**
	 * Function returns the instance of Folder model
	 * @param FolderId
	 * @return <Reports_Folder_Model>
	 */
	public static function getInstanceById($folderId) {
		$folderModel = Ncrm_Cache::get('reportsFolder',$folderId);
        if(!$folderModel){
            $db = PearDatabase::getInstance();
            $folderModel = Reports_Folder_Model::getInstance();

            $result = $db->pquery("SELECT * FROM ncrm_reportfolder WHERE folderid = ?", array($folderId));

            if ($db->num_rows($result) > 0) {
                $values = $db->query_result_rowdata($result, 0);
                $folderModel->setData($values);
            }
            Ncrm_Cache::set('reportsFolder',$folderId,$folderModel);
        }
        return $folderModel;
	}
    
    /**
     * Function returns the instance of Folder model
     * @return <Reports_Folder_Model>
     */
    public static function getAll() {
        $db = PearDatabase::getInstance();
        $folders = Ncrm_Cache::get('reports', 'folders');
        if (!$folders) {
            $folders = array();
            $result = $db->pquery("SELECT * FROM ncrm_reportfolder ORDER BY foldername ASC", array());
            $noOfFolders = $db->num_rows($result);
            if ($noOfFolders > 0) {
                for ($i = 0; $i < $noOfFolders; $i++) {
                    $folderModel = Reports_Folder_Model::getInstance();
                    $values = $db->query_result_rowdata($result, $i);
                    $folders[$values['folderid']] = $folderModel->setData($values);
                    Ncrm_Cache::set('reportsFolder',$values['folderid'],$folderModel);
                }
            }
            Ncrm_Cache::set('reports','folders',$folders);
        }
        return $folders;
    }

    /**
	 * Function returns duplicate record status of the module
	 * @return true if duplicate records exists else false
	 */
	function checkDuplicate() {
		$db = PearDatabase::getInstance();

		$query = 'SELECT 1 FROM ncrm_reportfolder WHERE foldername = ?';
		$params = array($this->getName());

		$folderId = $this->getId();
		if ($folderId) {
			$query .= ' AND folderid != ?';
			array_push($params, $folderId);
		}

		$result = $db->pquery($query, $params);

		if ($db->num_rows($result) > 0) {
			return true;
		}
		return false;
	}

	/**
	 * Function returns whether reports are exist or not in this folder
	 * @return true if exists else false
	 */
	function hasReports() {
		$db = PearDatabase::getInstance();

		$result = $db->pquery('SELECT 1 FROM ncrm_report WHERE folderid = ?', array($this->getId()));

		if ($db->num_rows($result) > 0) {
			return true;
		}
		return false;
	}

	/**
	 * Function returns whether folder is Default or not
	 * @return true if it is read only else false
	 */
	function isDefault() {
        if ($this->get('state') == 'SAVED') {
			return true;
		}
		return false;
	}
	
	/**
	 * Function to get info array while saving a folder
	 * @return Array  info array
	 */
	public function getInfoArray() {
		return array('folderId' => $this->getId(),
			'folderName' => $this->getName(),
			'editURL' => $this->getEditUrl(),
			'deleteURL' => $this->getDeleteUrl(),
			'isEditable' => $this->isEditable(),
			'isDeletable' => $this->isDeletable());
	}

	/**
	 * Function to check whether folder is editable or not
	 * @return <boolean>
	 */
	public function isEditable() {
		if ($this->isDefault()) {
			return false;
		}
		return true;
	}

	/**
	 * Function to get check whether folder is deletable or not
	 * @return <boolean>
	 */
	public function isDeletable() {
		if ($this->isDefault()) {
			return false;
		}
		return true;
	}

	/**
	 * Function to calculate number of reports in this folder
	 * @return <Integer>
	 */
	public function getReportsCount() {
		$db = PearDatabase::getInstance();
		$params = array();
		
        // To get the report ids which are permitted for the user
            $query = "SELECT reportmodulesid, primarymodule from ncrm_reportmodules";
            $result = $db->pquery($query, array());
            $noOfRows = $db->num_rows($result);
            $allowedReportIds = array();
            for($i=0;$i<$noOfRows;$i++){
                $primaryModule = $db->query_result($result,$i,'primarymodule');
                $reportid = $db->query_result($result,$i,'reportmodulesid');
                if(isPermitted($primaryModule,'index') == "yes"){
                    $allowedReportIds[] = $reportid;
                }
            }
        //End
		$sql = "SELECT count(*) AS count FROM ncrm_report
				INNER JOIN ncrm_reportfolder ON ncrm_reportfolder.folderid = ncrm_report.folderid AND 
                ncrm_report.reportid in (".implode(',',$allowedReportIds).")";
		$fldrId = $this->getId();
		if($fldrId == 'All') {
			$fldrId = false;
		}

		// If information is required only for specific report folder?
		if($fldrId !== false) {
			$sql .= " WHERE ncrm_reportfolder.folderid=?";
			array_push($params,$fldrId);
		}
		$currentUserModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		if (!$currentUserModel->isAdminUser()) {
			$currentUserId = $currentUserModel->getId();
			
			$groupId = implode(',',$currentUserModel->get('groups'));
			if ($groupId) {
				$groupQuery = "(SELECT reportid from ncrm_reportsharing WHERE shareid IN ($groupId) AND setype = 'groups') OR ";
			}
			
			$sql .= " AND (ncrm_report.reportid IN (SELECT reportid from ncrm_reportsharing WHERE $groupQuery shareid = ? AND setype = 'users')
						OR ncrm_report.sharingtype = 'Public'
						OR ncrm_report.owner = ?
						OR ncrm_report.owner IN (SELECT ncrm_user2role.userid FROM ncrm_user2role
													INNER JOIN ncrm_users ON ncrm_users.id = ncrm_user2role.userid
													INNER JOIN ncrm_role ON ncrm_role.roleid = ncrm_user2role.roleid
													WHERE ncrm_role.parentrole LIKE ?))";

			$parentRoleSeq = $currentUserModel->get('parent_role_seq').'::%';
			array_push($params, $currentUserId, $currentUserId, $parentRoleSeq);
		}
		$result = $db->pquery($sql, $params);
		return $db->query_result($result, 0, 'count');
	}
	
    /**
	 * Function to get all Report Record Models
	 * @param <Array> $allReportsList
	 * @param <Ncrm_Module_Model> - Reports Module Model
	 * @return <Array> Reports Record Models
	 */
	public function getAllReportModels($allReportsList, $reportModuleModel){
		$allReportModels = array();
        $folders = self::getAll();
		foreach ($allReportsList as $key => $reportsList) {
			for($i=0; $i < count($reportsList); $i++) {
				$reportModel = new Reports_Record_Model();
				$reportModel->setData($reportsList[$i])->setModuleFromInstance($reportModuleModel);
				$reportModel->set('foldername', $folders[$key]->getName());
				$allReportModels[] = $reportModel;
				unset($reportModel);
			}
		}
		return $allReportModels;
	}
    
     /**
	 * Function which provides the records for the current view
	 * @param <Boolean> $skipRecords - List of the RecordIds to be skipped
	 * @return <Array> List of RecordsIds
	 */
	public function getRecordIds($skipRecords=false, $module) {
		$db = PearDatabase::getInstance();
        $baseTableName = "ncrm_report";
        $baseTableId = "reportid";
		$folderId = $this->getId();
		$listQuery = $this->getListViewQuery($folderId);

		if($skipRecords && !empty($skipRecords) && is_array($skipRecords) && count($skipRecords) > 0) {
			$listQuery .= ' AND '.$baseTableName.'.'.$baseTableId.' NOT IN ('. implode(',', $skipRecords) .')';
		}
		$result = $db->query($listQuery);
		$noOfRecords = $db->num_rows($result);
		$recordIds = array();
		for($i=0; $i<$noOfRecords; ++$i) {
			$recordIds[] = $db->query_result($result, $i, $baseTableId);
		}
		return $recordIds;
	}
    
    /**
	 * Function returns Report Models for the folder
	 * @return <Reports_Record_Model>
	 */
	function getListViewQuery($folderId) {
        $sql = "select ncrm_report.*, ncrm_reportmodules.*, ncrm_reportfolder.folderid from ncrm_report 
                inner join ncrm_reportfolder on ncrm_reportfolder.folderid = ncrm_report.folderid 
                inner join ncrm_reportmodules on ncrm_reportmodules.reportmodulesid = ncrm_report.reportid ";
        
        if($folderId != "All") {
                $sql = $sql." where ncrm_reportfolder.folderid = ".$folderId;
        }        
        return $sql;
	}
}