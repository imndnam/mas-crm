<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

 Class Project_Record_Model extends Ncrm_Record_Model {

	/**
	 * Function to get the summary information for module
	 * @return <array> - values which need to be shown as summary
	 */
	public function getSummaryInfo() {
		$adb = PearDatabase::getInstance();
		
		$query ='SELECT smownerid,enddate,projecttaskstatus,projecttaskpriority
				FROM ncrm_projecttask
						INNER JOIN ncrm_crmentity ON ncrm_crmentity.crmid=ncrm_projecttask.projecttaskid
							AND ncrm_crmentity.deleted=0
						WHERE ncrm_projecttask.projectid = ? ';

		$result = $adb->pquery($query, array($this->getId()));

		$tasksOpen = $taskCompleted = $taskDue = $taskDeferred = $numOfPeople = 0;
        $highTasks = $lowTasks = $normalTasks = $otherTasks = 0;
		$currentDate = date('Y-m-d');
		$inProgressStatus = array('Open', 'In Progress');
		$usersList = array();

		while($row = $adb->fetchByAssoc($result)) {
			$projectTaskStatus = $row['projecttaskstatus'];
			switch($projectTaskStatus){

				case 'Open' : $tasksOpen++;
							   break;

				case 'Deferred' : $taskDeferred++;
							   break;

				case 'Completed': $taskCompleted++;
								break;
			}
            $projectTaskPriority = $row['projecttaskpriority'];
            switch($projectTaskPriority){
                case 'high' : $highTasks++;break;
                case 'low' : $lowTasks++;break;
                case 'normal' : $normalTasks++;break;
                default : $otherTasks++;break;
            }
            
			if(!empty($row['enddate']) && (strtotime($row['enddate']) < strtotime($currentDate)) &&
					(in_array($row['projecttaskstatus'], $inProgressStatus))) {
				$taskDue++;
			}
			$usersList[] = $row['smownerid'];
		}
		
		$usersList = array_unique($usersList);
		$numOfPeople = count($usersList);

		$summaryInfo['projecttaskstatus'] =  array(
			'LBL_TASKS_OPEN' => $tasksOpen,
			'Progress' => $this->get('progress'),
			'LBL_TASKS_DUE' => $taskDue,
			'LBL_TASKS_COMPLETED' => $taskCompleted,
		);
        
        $summaryInfo['projecttaskpriority'] =  array(
			'LBL_TASKS_HIGH' => $highTasks,
			'LBL_TASKS_NORMAL' => $normalTasks,
			'LBL_TASKS_LOW' => $lowTasks,
			'LBL_TASKS_OTHER' => $otherTasks,
		);
        
        return $summaryInfo;
	}
 }
?>
