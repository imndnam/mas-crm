<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Potentials_PipelinedAmountPerSalesPerson_Dashboard extends Ncrm_IndexAjax_View {
    
    function getSearchParams($assignedto,$stage) {
        $listSearchParams = array();
        $conditions = array(array('assigned_user_id','e',$assignedto),array("sales_stage","e",$stage));
        $listSearchParams[] = $conditions;
        return '&search_params='. json_encode($listSearchParams);
    }

public function process(Ncrm_Request $request) {
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();

		$linkId = $request->get('linkid');

		$moduleModel = Ncrm_Module_Model::getInstance($moduleName);
		$data = $moduleModel->getPotentialsPipelinedAmountPerSalesPerson();
        $listViewUrl = $moduleModel->getListViewUrl();
        for($i = 0;$i<count($data);$i++){
            $data[$i]["links"] = $listViewUrl.$this->getSearchParams($data[$i]["last_name"],$data[$i]["sales_stage"]);
        }

		$widget = Ncrm_Widget_Model::getInstance($linkId, $currentUser->getId());

		$viewer->assign('WIDGET', $widget);
		$viewer->assign('MODULE_NAME', $moduleName);
		$viewer->assign('DATA', $data);

		//Include special script and css needed for this widget
		$viewer->assign('STYLES',$this->getHeaderCss($request));
		$viewer->assign('CURRENTUSER', $currentUser);

		$content = $request->get('content');
		if(!empty($content)) {
			$viewer->view('dashboards/DashBoardWidgetContents.tpl', $moduleName);
		} else {
			$viewer->view('dashboards/PipelinedAmountPerSalesPerson.tpl', $moduleName);
		}
	}
}
