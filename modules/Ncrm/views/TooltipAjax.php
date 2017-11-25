<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Ncrm_TooltipAjax_View extends Ncrm_PopupAjax_View {

	function preProcess(Ncrm_Request $request) {
		return true;
	}

	function postProcess(Ncrm_Request $request) {
		return true;
	}

	function process (Ncrm_Request $request) {
		$viewer = $this->getViewer ($request);
		$moduleName = $request->getModule();

		$this->initializeListViewContents($request, $viewer);

		echo $viewer->view('TooltipContents.tpl', $moduleName, true);
	}
	
	public function initializeListViewContents(Ncrm_Request $request, Ncrm_Viewer $viewer) {
		$moduleName = $this->getModule($request);
		
		$recordId = $request->get('record');
		$tooltipViewModel = Ncrm_TooltipView_Model::getInstance($moduleName, $recordId);

		$viewer->assign('MODULE', $moduleName);

		$viewer->assign('MODULE_MODEL', $tooltipViewModel->getRecord()->getModule());
		
		$viewer->assign('TOOLTIP_FIELDS', $tooltipViewModel->getFields());
		$viewer->assign('RECORD', $tooltipViewModel->getRecord());
		$viewer->assign('RECORD_STRUCTURE', $tooltipViewModel->getStructure());

		$viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());
	}

}