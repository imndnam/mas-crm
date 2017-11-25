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
 * Calendar Edit View Record Structure Model
 */
class Calendar_EditRecordStructure_Model extends Ncrm_EditRecordStructure_Model {

	/**
	 * Function to get the values in stuctured format
	 * @return <array> - values in structure array('block'=>array(fieldinfo));
	 */
	public function getStructure() {
		if(!empty($this->structuredValues)) {
			return $this->structuredValues;
		}
        
		$values = array();
		$recordModel = $this->getRecord();
		$recordExists = !empty($recordModel);
		$moduleModel = $this->getModule();
		$blockModelList = $moduleModel->getBlocks();
                
		foreach($blockModelList as $blockLabel=>$blockModel) {
			$fieldModelList = $blockModel->getFields();
			if (!empty ($fieldModelList)) {
				$values[$blockLabel] = array();
				foreach($fieldModelList as $fieldName=>$fieldModel) {
					if($fieldModel->isEditable()) {
						if($recordExists) {
							$fieldValue = $recordModel->get($fieldName);
							if($fieldName == 'date_start') {
								$fieldValue = $fieldValue.' '.$recordModel->get('time_start');
							} else if($fieldName == 'due_date' && $moduleModel->get('name') != 'Calendar') {
                          		//Do not concat duedate and endtime for Tasks as it contains only duedate
								if($moduleModel->getName() != 'Calendar') {
                                    $fieldValue = $fieldValue.' '.$recordModel->get('time_end');
								}
							} else if($fieldName == 'visibility' && empty($fieldValue)) {
								$currentUserModel = Users_Record_Model::getCurrentUserModel();
								$sharedType = $currentUserModel->get('calendarsharedtype');
								if($sharedType == 'public' || $sharedType == 'selectedusers')
									$fieldValue = 'Public';
							} else if($fieldName == 'eventstatus' && empty($fieldValue)) {
                                    $currentUserModel = Users_Record_Model::getCurrentUserModel();
                                    $defaulteventstatus = $currentUserModel->get('defaulteventstatus');
                                    $fieldValue = $defaulteventstatus;
                            } else if($fieldName == 'activitytype' && empty($fieldValue)) {
                                    $currentUserModel = Users_Record_Model::getCurrentUserModel();
                                    $defaultactivitytype = $currentUserModel->get('defaultactivitytype');
                                    $fieldValue = $defaultactivitytype;
                            }
							$fieldModel->set('fieldvalue', $fieldValue);
						}
						$values[$blockLabel][$fieldName] = $fieldModel;
					}
				}
			}
		}
		$this->structuredValues = $values;
		return $values;
	}
}