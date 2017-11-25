<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 * ************************************************************************************/

class Project_Module_Model extends Ncrm_Module_Model {

	public function getSideBarLinks($linkParams) {
		$linkTypes = array('SIDEBARLINK', 'SIDEBARWIDGET');
		$links = parent::getSideBarLinks($linkParams);

		$quickLinks = array(
			array(
				'linktype' => 'SIDEBARLINK',
				'linklabel' => 'LBL_TASKS_LIST',
				'linkurl' => $this->getTasksListUrl(),
				'linkicon' => '',
			),
            array(
				'linktype' => 'SIDEBARLINK',
				'linklabel' => 'LBL_MILESTONES_LIST',
				'linkurl' => $this->getMilestonesListUrl(),
				'linkicon' => '',
			),
		);
		foreach($quickLinks as $quickLink) {
			$links['SIDEBARLINK'][] = Ncrm_Link_Model::getInstanceFromValues($quickLink);
		}

		return $links;
	}

	public function getTasksListUrl() {
		$taskModel = Ncrm_Module_Model::getInstance('ProjectTask');
		return $taskModel->getListViewUrl();
	}
    public function getMilestonesListUrl() {
		$milestoneModel = Ncrm_Module_Model::getInstance('ProjectMilestone');
		return $milestoneModel->getListViewUrl();
	}
}