<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Project_DetailView_Model extends Ncrm_DetailView_Model {

	/**
	 * Function to get the detail view widgets
	 * @return <Array> - List of widgets , where each widget is an Ncrm_Link_Model
	 */
	public function getWidgets() {
		$userPrivilegesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		$widgetLinks = parent::getWidgets();
		$widgets = array();
		
		$helpDeskInstance = Ncrm_Module_Model::getInstance('HelpDesk');
		if($userPrivilegesModel->hasModuleActionPermission($helpDeskInstance->getId(), 'DetailView')) {
			$createPermission = $userPrivilegesModel->hasModuleActionPermission($helpDeskInstance->getId(), 'EditView');
			$widgets[] = array(
					'linktype' => 'DETAILVIEWWIDGET',
					'linklabel' => 'HelpDesk',
					'linkName'	=> $helpDeskInstance->getName(),
					'linkurl' => 'module='.$this->getModuleName().'&view=Detail&record='.$this->getRecord()->getId().
							'&relatedModule=HelpDesk&mode=showRelatedRecords&page=1&limit=5',
					'action'	=>	($createPermission == true) ? array('Add') : array(),
					'actionURL' =>	$helpDeskInstance->getQuickCreateUrl()
				);
		}

		$projectMileStoneInstance = Ncrm_Module_Model::getInstance('ProjectMilestone');
		if($userPrivilegesModel->hasModuleActionPermission($projectMileStoneInstance->getId(), 'DetailView')) {
			$createPermission = $userPrivilegesModel->hasModuleActionPermission($projectMileStoneInstance->getId(), 'EditView');
			$widgets[] = array(
					'linktype' => 'DETAILVIEWWIDGET',
					'linklabel' => 'LBL_MILESTONES',
					'linkName'	=> $projectMileStoneInstance->getName(),
					'linkurl' => 'module='.$this->getModuleName().'&view=Detail&record='.$this->getRecord()->getId().
							'&relatedModule=ProjectMilestone&mode=showRelatedRecords&page=1&limit=5',
					'action'	=>	($createPermission == true) ? array('Add') : array(),
					'actionURL' =>	$projectMileStoneInstance->getQuickCreateUrl()
			);
		}

		$projectTaskInstance = Ncrm_Module_Model::getInstance('ProjectTask');
		if($userPrivilegesModel->hasModuleActionPermission($projectTaskInstance->getId(), 'DetailView')) {
			$createPermission = $userPrivilegesModel->hasModuleActionPermission($projectTaskInstance->getId(), 'EditView');
			$widgets[] = array(
					'linktype' => 'DETAILVIEWWIDGET',
					'linklabel' => 'LBL_TASKS',
					'linkName'	=> $projectTaskInstance->getName(),
					'linkurl' => 'module='.$this->getModuleName().'&view=Detail&record='.$this->getRecord()->getId().
							'&relatedModule=ProjectTask&mode=showRelatedRecords&page=1&limit=5',
					'action'	=>	($createPermission == true) ? array('Add') : array(),
					'actionURL' =>	$projectTaskInstance->getQuickCreateUrl()
			);
		}


		$documentsInstance = Ncrm_Module_Model::getInstance('Documents');
		if($userPrivilegesModel->hasModuleActionPermission($documentsInstance->getId(), 'DetailView')) {
			$createPermission = $userPrivilegesModel->hasModuleActionPermission($documentsInstance->getId(), 'EditView');
			$widgets[] = array(
					'linktype' => 'DETAILVIEWWIDGET',
					'linklabel' => 'Documents',
					'linkName'	=> $documentsInstance->getName(),
					'linkurl' => 'module='.$this->getModuleName().'&view=Detail&record='.$this->getRecord()->getId().
							'&relatedModule=Documents&mode=showRelatedRecords&page=1&limit=5',
					'action'	=>	($createPermission == true) ? array('Add') : array(),
					'actionURL' =>	$documentsInstance->getQuickCreateUrl()
			);
		}

		foreach ($widgets as $widgetDetails) {
			$widgetLinks[] = Ncrm_Link_Model::getInstanceFromValues($widgetDetails);
		}

		return $widgetLinks;
	}
}