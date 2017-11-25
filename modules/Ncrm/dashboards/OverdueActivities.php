<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

Class Ncrm_OverdueActivities_Dashboard extends Ncrm_IndexAjax_View {

	public function process(Ncrm_Request $request) {
		$currentUser = Users_Record_Model::getCurrentUserModel();

		$moduleName = $request->getModule();
		$page = $request->get('page');
		$linkId = $request->get('linkid');

		$pagingModel = new Ncrm_Paging_Model();
		$pagingModel->set('page', $page);
		$pagingModel->set('limit', 10);

		$user = $request->get('type');
		$moduleModel = Ncrm_Module_Model::getInstance($moduleName);
		$overDueActivities = $moduleModel->getCalendarActivities('overdue', $pagingModel, $user);

		$widget = Ncrm_Widget_Model::getInstance($linkId, $currentUser->getId());
		$viewer = $this->getViewer($request);

		$viewer->assign('WIDGET', $widget);
		$viewer->assign('MODULE_NAME', $moduleName);
		$viewer->assign('ACTIVITIES', $overDueActivities);
		$viewer->assign('PAGING', $pagingModel);
		$viewer->assign('CURRENTUSER', $currentUser);
		
		$content = $request->get('content');
		if(!empty($content)) {
			$viewer->view('dashboards/CalendarActivitiesContents.tpl', $moduleName);
		} else {
			$viewer->view('dashboards/CalendarActivities.tpl', $moduleName);
		}
	}
}