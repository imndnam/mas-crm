<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Reports_Folder_Action extends Ncrm_Action_Controller {

	function __construct() {
		parent::__construct();
		$this->exposeMethod('save');
		$this->exposeMethod('delete');
	}

	public function checkPermission(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$moduleModel = Reports_Module_Model::getInstance($moduleName);

		$currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		if(!$currentUserPriviligesModel->hasModulePermission($moduleModel->getId())) {
			throw new AppException('LBL_PERMISSION_DENIED');
		}
	}

	public function process(Ncrm_Request $request) {
		$mode = $request->get('mode');
		if(!empty($mode)) {
			$this->invokeExposedMethod($mode, $request);
			return;
		}
	}

	/**
	 * Function that saves/updates the Folder
	 * @param Ncrm_Request $request
	 */
	function save(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$folderModel = Reports_Folder_Model::getInstance();
		$folderId = $request->get('folderid');

		if(!empty($folderId)) {
			$folderModel->set('folderid', $folderId);
		}

		$folderModel->set('foldername', $request->get('foldername'));
		$folderModel->set('description', $request->get('description'));

		if ($folderModel->checkDuplicate()) {
			throw new AppException(vtranslate('LBL_DUPLICATES_EXIST', $moduleName));
		}

		$folderModel->save();
		$result = array('success' => true, 'message' => vtranslate('LBL_FOLDER_SAVED', $moduleName), 'info' => $folderModel->getInfoArray());

		$response = new Ncrm_Response();
		$response->setResult($result);
		$response->emit();
	}

	/**
	 * Function that deletes the Folder
	 * @param Ncrm_Request $request
	 */
	function delete(Ncrm_Request $request) {
		$folderId = $request->get('folderid');
		$moduleName = $request->getModule();

		if ($folderId) {
			$folderModel = Reports_Folder_Model::getInstanceById($folderId);

			if ($folderModel->isDefault()) {
				throw new AppException(vtranslate('LBL_FOLDER_CAN_NOT_BE_DELETED', $moduleName));
			} else {
				if ($folderModel->hasReports()) {
					throw new AppException(vtranslate('LBL_FOLDER_NOT_EMPTY', $moduleName));
				}
			}

			$folderModel->delete();
			$result = array('success'=>true, 'message'=>vtranslate('LBL_FOLDER_DELETED', $moduleName));

			$response = new Ncrm_Response();
			$response->setResult($result);
			$response->emit();
		}
	}
        
        public function validateRequest(Ncrm_Request $request) { 
            $request->validateWriteAccess(); 
        }
}