<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Calendar_QuickCreateFollowupAjax_View extends Ncrm_QuickCreateAjax_View {

	public function  process(Ncrm_Request $request) {
		$moduleName = $request->getModule();
        $recordId = $request->get('record');
        
        $recordModel = Ncrm_Record_Model::getInstanceById($recordId);
        $moduleModel = $recordModel->getModule();
        $actionname = "EditView";
        
        if(isPermitted($moduleName, $actionname, $recordId) === 'yes'){
            //Start date Field required for validation
            $startDateFieldModel = $moduleModel->getField("date_start");
            $startDateTime = $recordModel->getDisplayValue('date_start');
            $startDate = explode(" ", $startDateTime);
            $startDate = $startDate[0];

            $viewer = $this->getViewer($request);
            $viewer->assign('STARTDATEFIELDMODEL',$startDateFieldModel);
            $viewer->assign('STARTDATE',$startDate);
            $viewer->assign('CURRENTDATE', date('Y-n-j'));
            $viewer->assign('MODULE', $moduleName);
            $viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());
            $viewer->assign('RECORD_MODEL', $recordModel);

            $viewer->view('QuickCreateFollowup.tpl', $moduleName);
        }        
	}
    
}
