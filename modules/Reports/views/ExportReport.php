<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Reports_ExportReport_View extends Ncrm_View_Controller {

	function __construct() {
		parent::__construct();
		$this->exposeMethod('GetPrintReport');
		$this->exposeMethod('GetXLS');
		$this->exposeMethod('GetCSV');
	}

	function checkPermission(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$moduleModel = Reports_Module_Model::getInstance($moduleName);

		$record = $request->get('record');
		$reportModel = Reports_Record_Model::getCleanInstance($record);

		$currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		if(!$currentUserPriviligesModel->hasModulePermission($moduleModel->getId())) {
			throw new AppException('LBL_PERMISSION_DENIED');
		}
	}

	function preProcess(Ncrm_Request $request) {
		return false;
	}

	function postProcess(Ncrm_Request $request) {
		return false;
	}

	function process(Ncrm_request $request) {
		$mode = $request->getMode();
		if(!empty($mode)) {
			$this->invokeExposedMethod($mode, $request);
		}
	}

	/**
	 * Function exports the report in a Excel sheet
	 * @param Ncrm_Request $request
	 */
	function GetXLS(Ncrm_Request $request) {
		$recordId = $request->get('record');
		$reportModel = Reports_Record_Model::getInstanceById($recordId);
        $reportModel->set('advancedFilter', $request->get('advanced_filter'));
		$reportModel->getReportXLS();
	}

	/**
	 * Function exports report in a CSV file
	 * @param Ncrm_Request $request
	 */
	function GetCSV(Ncrm_Request $request) {
		$recordId = $request->get('record');
		$reportModel = Reports_Record_Model::getInstanceById($recordId);
        $reportModel->set('advancedFilter', $request->get('advanced_filter'));
		$reportModel->getReportCSV();
	}

	/**
	 * Function displays the report in printable format
	 * @param Ncrm_Request $request
	 */
	function GetPrintReport(Ncrm_Request $request) {
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();

		$recordId = $request->get('record');
		$reportModel = Reports_Record_Model::getInstanceById($recordId);
        $reportModel->set('advancedFilter', $request->get('advanced_filter'));
		$printData = $reportModel->getReportPrint();

		$viewer->assign('REPORT_NAME', $reportModel->getName());
		$viewer->assign('PRINT_DATA', $printData['data'][0]);
		$viewer->assign('TOTAL', $printData['total']);
		$viewer->assign('MODULE', $moduleName);
		$viewer->assign('ROW', $printData['data'][1]);

		$viewer->view('PrintReport.tpl', $moduleName);
	}
}