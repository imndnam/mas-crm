<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Reports_SaveAjax_View extends Ncrm_IndexAjax_View {

	public function checkPermission(Ncrm_Request $request) {
		$record = $request->get('record');
		if (!$record) {
			throw new AppException('LBL_PERMISSION_DENIED');
		}

		$moduleName = $request->getModule();
		$moduleModel = Reports_Module_Model::getInstance($moduleName);
		$reportModel = Reports_Record_Model::getCleanInstance($record);

		$currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		if (!$currentUserPriviligesModel->hasModulePermission($moduleModel->getId()) && !$reportModel->isEditable()) {
			throw new AppException('LBL_PERMISSION_DENIED');
		}
	}

	public function process(Ncrm_Request $request) {
		$mode = $request->getMode();
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();

		$record = $request->get('record');
		$reportModel = Reports_Record_Model::getInstanceById($record);

		$reportModel->setModule('Reports');
       
		$reportModel->set('advancedFilter', $request->get('advanced_filter'));

		$page = $request->get('page');
		$pagingModel = new Ncrm_Paging_Model();
		$pagingModel->set('page', $page);
		$pagingModel->set('limit', Reports_Detail_View::REPORT_LIMIT);

		if ($mode === 'save') {
			$reportModel->saveAdvancedFilters();
			$reportData = $reportModel->getReportData($pagingModel);
            $data = $reportData['data'];
		} else if ($mode === 'generate') {
			$reportData = $reportModel->generateData($pagingModel);
            $data = $reportData['data'];
		}
		$calculation = $reportModel->generateCalculationData();

		$viewer->assign('PRIMARY_MODULE', $reportModel->getPrimaryModule());
		$viewer->assign('CALCULATION_FIELDS', $calculation);
        $viewer->assign('DATA', $data);
		$viewer->assign('RECORD_ID', $record);
		$viewer->assign('PAGING_MODEL', $pagingModel);
		$viewer->assign('MODULE', $moduleName);
        $viewer->assign('NEW_COUNT',$reportData['count']);
        $viewer->assign('REPORT_RUN_INSTANCE', ReportRun::getInstance($record));
		$viewer->view('ReportContents.tpl', $moduleName);
	}

        public function validateRequest(Ncrm_Request $request) {
            $request->validateWriteAccess(); 
        } 
}
