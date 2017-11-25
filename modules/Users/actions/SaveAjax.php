<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/
vimport('~~/include/Webservices/Custom/ChangePassword.php');

class Users_SaveAjax_Action extends Ncrm_SaveAjax_Action {
	
	function __construct() {
		parent::__construct();
		$this->exposeMethod('userExists');
		$this->exposeMethod('savePassword');
                $this->exposeMethod('restoreUser');
	}

	public function checkPermission(Ncrm_Request $request) {
            $currentUserModel = Users_Record_Model::getCurrentUserModel();

            $userId = $request->get('userid');
            if (!$currentUserModel->isAdminUser()) {
                $mode = $request->getMode();
                if ($mode == 'savePassword' && (isset($userId) && $currentUserModel->getId() != $userId)) {
                    throw new AppException(vtranslate('LBL_PERMISSION_DENIED', 'Ncrm'));
                }
                 else if ($mode != 'savePassword' && ($currentUserModel->getId() != $request->get('record'))) {
                    throw new AppException(vtranslate('LBL_PERMISSION_DENIED', 'Ncrm'));
                }
            }
    }

	public function process(Ncrm_Request $request) {

                $mode = $request->get('mode');
		if (!empty($mode)) {
			$this->invokeExposedMethod($mode, $request);
			return;
		}
		
		$recordModel = $this->saveRecord($request);

                $fieldModelList = $recordModel->getModule()->getFields();
		$result = array();
		foreach ($fieldModelList as $fieldName => $fieldModel) {
			$fieldValue = $displayValue = Ncrm_Util_Helper::toSafeHTML($recordModel->get($fieldName));
			if ($fieldModel->getFieldDataType() !== 'currency') {
				$displayValue = $fieldModel->getDisplayValue($fieldValue, $recordModel->getId());
			}
			if($fieldName == 'language') {
				$displayValue =  Ncrm_Language_Handler::getLanguageLabel($fieldValue);
			}
            
            if(($fieldName == 'currency_decimal_separator' || $fieldName == 'currency_grouping_separator') && ($displayValue == '&nbsp;')) {
                $displayValue = vtranslate('LBL_Space', 'Users');
            }
            
			$result[$fieldName] = array('value' => $fieldValue, 'display_value' => $displayValue);
		}

		$result['_recordLabel'] = $recordModel->getName();
		$result['_recordId'] = $recordModel->getId();

		$response = new Ncrm_Response();
		$response->setEmitType(Ncrm_Response::$EMIT_JSON);
		$response->setResult($result);
		$response->emit();
	}

	/**
	 * Function to get the record model based on the request parameters
	 * @param Ncrm_Request $request
	 * @return Ncrm_Record_Model or Module specific Record Model instance
	 */
	public function getRecordModelFromRequest(Ncrm_Request $request) {
		$recordModel = parent::getRecordModelFromRequest($request);
		$fieldName = $request->get('field');
                $currentUserModel=  Users_Record_Model::getCurrentUserModel();
		if ($fieldName === 'is_admin' && (!$currentUserModel->isAdminUser()||!$request->get('value'))) {
			$recordModel->set($fieldName, 'off');
                        $recordModel->set('is_owner',0);
		}
                else if($fieldName === 'is_admin' && $currentUserModel->isAdminUser()){
                    $recordModel->set($fieldName, 'on');
                    $recordModel->set('is_owner',1);
                }       
                return $recordModel;
	}
	
		
	public function userExists(Ncrm_Request $request){
		$module = $request->getModule();
		$userName = $request->get('user_name');
		$userModuleModel = Users_Module_Model::getCleanInstance($module);
		$status = $userModuleModel->checkDuplicateUser($userName);
		$response = new Ncrm_Response();
		$response->setResult($status);
		$response->emit();
	}
	
	public function savePassword(Ncrm_Request $request) {
		$module = $request->getModule();
		$userModel = vglobal('current_user');
		$newPassword = $request->get('new_password');
		$oldPassword = $request->get('old_password');
		
		$wsUserId = vtws_getWebserviceEntityId($module, $request->get('userid'));
		$wsStatus = vtws_changePassword($wsUserId, $oldPassword, $newPassword, $newPassword, $userModel);
		
		$response = new Ncrm_Response();
		if ($wsStatus['message']) {
			$response->setResult($wsStatus);
		} else {
			$response->setError('JS_PASSWORD_INCORRECT_OLD', 'JS_PASSWORD_INCORRECT_OLD');
		}
		$response->emit();
	}
        
        /*
         * To restore a user
         * @param Ncrm_Request Object
         */
        public function restoreUser(Ncrm_Request $request) {
                $moduleName = $request->getModule();
                $record = $request->get('userid');
                
                $recordModel = Users_Record_Model::getInstanceById($record, $moduleName);
                $recordModel->set('status', 'Active');
                $recordModel->set('id', $record);
                $recordModel->set('mode', 'edit');
                $recordModel->set('user_hash', $recordModel->getUserHash());
                $recordModel->save();
                
                $db = PearDatabase::getInstance();
                $db->pquery("UPDATE ncrm_users SET deleted=? WHERE id=?", array(0,$record));
                
                $userModuleModel = Users_Module_Model::getInstance($moduleName);
		$listViewUrl = $userModuleModel->getListViewUrl();
		
		$response = new Ncrm_Response();
		$response->setResult(array('message'=>vtranslate('LBL_USER_RESTORED_SUCCESSFULLY', $moduleName), 'listViewUrl' => $listViewUrl));
		$response->emit();
        }
        }
