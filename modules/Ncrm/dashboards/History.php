<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Ncrm_History_Dashboard extends Ncrm_IndexAjax_View {

	public function process(Ncrm_Request $request) {
		$LIMIT = 10;
		
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$viewer = $this->getViewer($request);

		$moduleName = $request->getModule();
		$type = $request->get('type');
		$page = $request->get('page');
		$linkId = $request->get('linkid');
                if( empty($page)) { $page=1; }
		$pagingModel = new Ncrm_Paging_Model();
		$pagingModel->set('page', $page);
		$pagingModel->set('limit', $LIMIT);

		$moduleModel = Ncrm_Module_Model::getInstance($moduleName);
		$history = $moduleModel->getHistory($pagingModel, $type);
		$widget = Ncrm_Widget_Model::getInstance($linkId, $currentUser->getId());
		$modCommentsModel = Ncrm_Module_Model::getInstance('ModComments'); 

		$viewer->assign('WIDGET', $widget);
		$viewer->assign('MODULE_NAME', $moduleName);
		$viewer->assign('HISTORIES', $history);
		$viewer->assign('PAGE', $page);
		$viewer->assign('NEXTPAGE', (count($history) < $LIMIT)? 0 : $page+1);
		$viewer->assign('COMMENTS_MODULE_MODEL', $modCommentsModel); 
		
		$content = $request->get('content');
		if(!empty($content)) {
			$viewer->view('dashboards/HistoryContents.tpl', $moduleName);
		} else {
			$viewer->view('dashboards/History.tpl', $moduleName);
		}
	}
}
