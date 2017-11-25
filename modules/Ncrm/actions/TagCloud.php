<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Ncrm_TagCloud_Action extends Ncrm_Action_Controller {

	function __construct() {
		parent::__construct();
		$this->exposeMethod('save');
		$this->exposeMethod('delete');
	}

	function checkPermission(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$moduleModel = Ncrm_Module_Model::getInstance($moduleName);

		$userPrivilegesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		$permission = $userPrivilegesModel->hasModulePermission($moduleModel->getId());
		if(!$permission) {
			throw new AppException('LBL_PERMISSION_DENIED');
		}
		return true;
	}

	public function process(Ncrm_Request $request) {
		$mode = $request->getMode();
		if(!empty($mode)) {
			echo $this->invokeExposedMethod($mode, $request);
			return;
		}
	}

	/**
	 * Function saves a tag for a record
	 * @param Ncrm_Request $request
	 */
	public function save(Ncrm_Request $request) {
		$currentUser = Users_Record_Model::getCurrentUserModel();

		$tagModel = new Ncrm_Tag_Model();
		$tagModel->set('userid', $currentUser->id);
		$tagModel->set('record', $request->get('record'));
		$tagModel->set('tagname', decode_html($request->get('tagname')));
		$tagModel->set('module', $request->getModule());
		$tagModel->save();

		$taggedInfo = Ncrm_Tag_Model::getAll($currentUser->id, $request->getModule(), $request->get('record'));
		$response = new Ncrm_Response();
		$response->setResult($taggedInfo);
		$response->emit($taggedInfo);
	}

	/**
	 * Function deleted a tag
	 * @param Ncrm_Request $request
	 */
	public function delete(Ncrm_Request $request) {
		$tagModel = new Ncrm_Tag_Model();
		$tagModel->set('record', $request->get('record'));
		$tagModel->set('tag_id', $request->get('tag_id'));
		$tagModel->delete();
	}

	/**
	 * Function returns list of tage for the record
	 * @param Ncrm_Request $request
	 */
	public function getTags(Ncrm_Request $request) {
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$record = $request->get('record');
		$module = $request->getModule();
		$tags = Ncrm_Tag_Model::getAll($currentUser->id, $module, $record);

		$response = new Ncrm_Response();
		$response->emit($tags);
	}
}
