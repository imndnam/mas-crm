<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Potentials_GroupedBySalesStage_Dashboard extends Ncrm_IndexAjax_View {

	/**
	 * Retrieves css styles that need to loaded in the page
	 * @param Ncrm_Request $request - request model
	 * @return <array> - array of Ncrm_CssScript_Model
	 */
	function getHeaderCss(Ncrm_Request $request){
		$cssFileNames = array(
			//Place your widget specific css files here
		);
		$headerCssScriptInstances = $this->checkAndConvertCssStyles($cssFileNames);
		return $headerCssScriptInstances;
	}
    
    function getSearchParams($stage,$assignedto,$dates) {
        $listSearchParams = array();
        $conditions = array();
        array_push($conditions,array("sales_stage","e",$stage));
        if($assignedto == ''){
            $currenUserModel = Users_Record_Model::getCurrentUserModel();
			$assignedto = $currenUserModel->getId();
        }
        if($assignedto != 'all'){
            $ownerType = vtws_getOwnerType($assignedto);
            if($ownerType == 'Users')
                array_push($conditions,array("assigned_user_id","e",getUserFullName($assignedto)));
            else{
                $groupName = getGroupName($assignedto);
                $groupName = $groupName[0];
                array_push($conditions,array("assigned_user_id","e",$groupName));
            }
        } 
        if(!empty($dates)) {
            array_push($conditions,array("closingdate","bw",$dates['start'].','.$dates['end']));
        }
        $listSearchParams[] = $conditions;
        return '&search_params='. json_encode($listSearchParams);
    }
    
	public function process(Ncrm_Request $request) {
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();

		$linkId = $request->get('linkid');
		$owner = $request->get('owner');
		$dates = $request->get('expectedclosedate');

		//Date conversion from user to database format
		if(!empty($dates)) {
			$dates['start'] = Ncrm_Date_UIType::getDBInsertedValue($dates['start']);
			$dates['end'] = Ncrm_Date_UIType::getDBInsertedValue($dates['end']);
		}
		
		$moduleModel = Ncrm_Module_Model::getInstance($moduleName);
		$data = $moduleModel->getPotentialsCountBySalesStage($owner, $dates);
        $listViewUrl = $moduleModel->getListViewUrl();
        for($i = 0;$i<count($data);$i++){
            $data[$i][] = $listViewUrl.$this->getSearchParams($data[$i][0],$owner,$dates);
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
			$viewer->view('dashboards/GroupBySalesStage.tpl', $moduleName);
		}
	}
}
