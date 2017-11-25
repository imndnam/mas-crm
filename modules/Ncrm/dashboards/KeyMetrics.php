<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Ncrm_KeyMetrics_Dashboard extends Ncrm_IndexAjax_View {
	
	public function process(Ncrm_Request $request) {
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		
		$linkId = $request->get('linkid');
		
		$widget = Ncrm_Widget_Model::getInstance($linkId, $currentUser->getId());
		
		// TODO move this to models
		$keyMetrics = $this->getKeyMetricsWithCount();
		
		$viewer->assign('WIDGET', $widget);
		$viewer->assign('MODULE_NAME', $moduleName);
		$viewer->assign('KEYMETRICS', $keyMetrics);
		
		$content = $request->get('content');
		if(!empty($content)) {
			$viewer->view('dashboards/KeyMetricsContents.tpl', $moduleName);
		} else {
			$viewer->view('dashboards/KeyMetrics.tpl', $moduleName);
		}
		
	}
	
	// NOTE: Move this function to appropriate model.
	protected function getKeyMetricsWithCount() {
		global $current_user, $adb;
		$current_user = Users_Record_Model::getCurrentUserModel();
		
		require_once 'modules/CustomView/ListViewTop.php';
		$metriclists = getMetricList();
		
		foreach ($metriclists as $key => $metriclist) {
			
			$metricresult = NULL;
				$queryGenerator = new QueryGenerator($metriclist['module'], $current_user);
				$queryGenerator->initForCustomViewById($metriclist['id']);
            if($metriclist['module'] == "Calendar") {
                // For calendar we need to eliminate emails or else it will break in status empty condition
                $queryGenerator->addCondition('activitytype', "Emails", 'n',  QueryGenerator::$AND);
			}
				$metricsql = $queryGenerator->getQuery();
				$metricresult = $adb->query(Ncrm_Functions::mkCountQuery($metricsql));
			if($metricresult) {
					$rowcount = $adb->fetch_array($metricresult);
					$metriclists[$key]['count'] = $rowcount['count'];
				}
		}
		return $metriclists;
	}
	
}