<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Reports_ChartSaveAjax_View extends Ncrm_IndexAjax_View {

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


		$secondaryModules = $reportModel->getSecondaryModules();
		if(empty($secondaryModules)) {
			$viewer->assign('CLICK_THROUGH', true);
		}

		$dataFields = $request->get('datafields', 'count(*)');
		if(is_string($dataFields)) $dataFields = array($dataFields);

		$reportModel->set('reporttypedata', Zend_Json::encode(array(
																'type'=>$request->get('charttype', 'pieChart'),
																'groupbyfield'=>$request->get('groupbyfield'),
																'datafields'=>$dataFields)
															));
		$reportModel->set('reporttype', 'chart');
		$reportModel->save();

		$reportChartModel = Reports_Chart_Model::getInstanceById($reportModel);
        
        $data = $reportChartModel->getData();
		$viewer->assign('CHART_TYPE', $reportChartModel->getChartType());
		$viewer->assign('DATA', json_encode($data, JSON_HEX_APOS));
		$viewer->assign('MODULE', $moduleName);

		$viewer->view('ChartReportContents.tpl', $moduleName);
	}
}