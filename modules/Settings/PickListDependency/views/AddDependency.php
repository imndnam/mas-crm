<?php
/* +***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 * *********************************************************************************** */

class Settings_PickListDependency_AddDependency_View extends Settings_Ncrm_IndexAjax_View {
	function __construct() {
		parent::__construct();
		$this->exposeMethod('GetPickListFields');
	}

	function process(Ncrm_Request $request) {
		$mode = $request->getMode();
		if(!empty($mode) && method_exists($this, $mode)) {
			$this->invokeExposedMethod($mode, $request);
			return;
		}

		$qualifiedModule = $request->getModule(true);
		$viewer = $this->getViewer($request);
		$moduleModels = Ncrm_Module_Model::getEntityModules();

		$viewer->assign('MODULES', $moduleModels);
		echo $viewer->view('AddDependency.tpl', $qualifiedModule);
	}

	/**
	 * Function returns the picklist field for a module
	 * @param Ncrm_Request $request
	 */
	function GetPickListFields(Ncrm_Request $request) {
		$module = $request->get('sourceModule');

		$fieldList = Settings_PickListDependency_Module_Model::getAvailablePicklists($module);

		$response = new Ncrm_Response();
		$response->setResult($fieldList);
		$response->emit();
	}

	function CheckCyclicDependency() {

	}
}