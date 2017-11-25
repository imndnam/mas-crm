<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Potentials_TopPotentials_Dashboard extends Ncrm_IndexAjax_View {

	public function process(Ncrm_Request $request) {
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();

		$linkId = $request->get('linkid');
		$page = $request->get('page');
		if(empty($page)) {
			$page = 1;
		}
		$pagingModel = new Ncrm_Paging_Model();
		$pagingModel->set('page', $page);

		$moduleModel = Ncrm_Module_Model::getInstance($moduleName);
		$models = $moduleModel->getTopPotentials($pagingModel);
        $moduleHeader = $moduleModel->getTopPotentialsHeader();

		$widget = Ncrm_Widget_Model::getInstance($linkId, $currentUser->getId());

		$viewer->assign('WIDGET', $widget);
		$viewer->assign('MODULE_NAME', $moduleName);
        $viewer->assign('MODULE_HEADER', $moduleHeader);
		$viewer->assign('MODELS', $models);

		$content = $request->get('content');
		if(!empty($content)) {
			$viewer->view('dashboards/TopPotentialsContents.tpl', $moduleName);
		} else {
			$viewer->view('dashboards/TopPotentials.tpl', $moduleName);
		}
	}
}
