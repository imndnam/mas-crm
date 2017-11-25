<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Ncrm_Owner_UIType extends Ncrm_Base_UIType {

	/**
	 * Function to get the Template name for the current UI Type object
	 * @return <String> - Template Name
	 */
	public function getTemplateName() {
		return 'uitypes/Owner.tpl';
	}

	/**
	 * Function to get the Display Value, for the current field type with given DB Insert Value
	 * @param <Object> $value
	 * @return <Object>
	 */
	public function getDisplayValue($value) {
		if (self::getOwnerType($value) === 'User') {
			$userModel = Users_Record_Model::getCleanInstance('Users');
			$userModel->set('id', $value);
			$detailViewUrl = $userModel->getDetailViewUrl();
            $currentUser = Users_Record_Model::getCurrentUserModel();
            if(!$currentUser->isAdminUser()){
                return getOwnerName($value);
            }
		} else {
            $currentUser = Users_Record_Model::getCurrentUserModel();
            if(!$currentUser->isAdminUser()){
                return getOwnerName($value);
            }
            $recordModel = new Settings_Groups_Record_Model();
            $recordModel->set('groupid',$value);
			$detailViewUrl = $recordModel->getDetailViewUrl();
		}
		return "<a href=" .$detailViewUrl. ">" .getOwnerName($value). "</a>";
	}

	/**
	 * Function to get Display value for RelatedList
	 * @param <String> $value
	 * @return <String>
	 */
	public function getRelatedListDisplayValue($value) {
		return $value;
	}

	/**
	 * Function to know owner is either User or Group
	 * @param <Integer> userId/GroupId
	 * @return <String> User/Group
	 */
	public static function getOwnerType($id) {
		$db = PearDatabase::getInstance();

		$result = $db->pquery('SELECT 1 FROM ncrm_users WHERE id = ?', array($id));
		if ($db->num_rows($result) > 0) {
			return 'User';
		}
		return 'Group';
	}
    
    public function getListSearchTemplateName() {
        return 'uitypes/OwnerFieldSearchView.tpl';
    }
}