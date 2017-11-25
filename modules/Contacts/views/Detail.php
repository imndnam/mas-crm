<?php

/* +***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 * *********************************************************************************** */

class Contacts_Detail_View extends Accounts_Detail_View {

	function __construct() {
		parent::__construct();
	}

	public function showModuleDetailView(Ncrm_Request $request) {
		$recordId = $request->get('record');
		$moduleName = $request->getModule();

		$recordModel = Ncrm_Record_Model::getInstanceById($recordId, $moduleName);

		$viewer = $this->getViewer($request);
		$viewer->assign('IMAGE_DETAILS', $recordModel->getImageDetails());

		return parent::showModuleDetailView($request);
	}
}
