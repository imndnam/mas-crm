<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Settings_Roles_Save_Action extends Ncrm_Action_Controller {
	
	public function checkPermission(Ncrm_Request $request) {
		$currentUser = Users_Record_Model::getCurrentUserModel();
		if(!$currentUser->isAdminUser()) {
			throw new AppException('LBL_PERMISSION_DENIED');
		}
	}

	public function process(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$qualifiedModuleName = $request->getModule(false);
		$recordId = $request->get('record');
		$roleName = $request->get('rolename');
		$allowassignedrecordsto = $request->get('allowassignedrecordsto');

		$moduleModel = Settings_Ncrm_Module_Model::getInstance($qualifiedModuleName);
		if(!empty($recordId)) {
			$recordModel = Settings_Roles_Record_Model::getInstanceById($recordId);
		} else {
			$recordModel = new Settings_Roles_Record_Model();
		}
        
        if($request->get('profile_directly_related_to_role') == '1') {
            $profileId = $request->get('profile_directly_related_to_role_id');
            $profileName = $request->get('profilename');
			if(empty($profileName)){
				$profileName = $roleName.'+'.vtranslate('LBL_PROFILE',$qualifiedModuleName);
			}
            if($profileId){
                $profileRecordModel = Settings_Profiles_Record_Model::getInstanceById($profileId);
            } else {
                $profileRecordModel = Settings_Profiles_Record_Model::getInstanceByName($profileName, true);
                if(empty($profileRecordModel)) {
                    $profileRecordModel = new Settings_Profiles_Record_Model();
                }
			}
            $profileRecordModel->set('directly_related_to_role','1');
			
            $profileRecordModel->set('profilename', $profileName)
                               ->set('profile_permissions', $request->get('permissions'));
            $profileRecordModel->set('viewall', $request->get('viewall'));
			$profileRecordModel->set('editall', $request->get('editall'));
			$savedProfileId = $profileRecordModel->save();
            $roleProfiles = array($savedProfileId);
        }else{
            $roleProfiles = $request->get('profiles');
        }
        
		$parentRoleId = $request->get('parent_roleid');
		if($recordModel && !empty($parentRoleId)) {
			$parentRole = Settings_Roles_Record_Model::getInstanceById($parentRoleId);
			if(!empty($allowassignedrecordsto)) $recordModel->set('allowassignedrecordsto', $allowassignedrecordsto); // set the value of assigned records to
			if($parentRole && !empty($roleName) && !empty($roleProfiles)) {
				$recordModel->set('rolename', $roleName);
				$recordModel->set('profileIds', $roleProfiles);
				$parentRole->addChildRole($recordModel);
			}

			//After role updation recreating user privilege files
			if ($roleProfiles) {
				foreach ($roleProfiles as $profileId) {
					$profileRecordModel = Settings_Profiles_Record_Model::getInstanceById($profileId);
					$profileRecordModel->recalculate(array($recordId));
				}
			}
		}

		$redirectUrl = $moduleModel->getDefaultUrl();
		header("Location: $redirectUrl");
	}
        
        public function validateRequest(Ncrm_Request $request) {
            $request->validateWriteAccess(); 
        } 
}
