<?php

/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Users_ChangePassword_View extends Ncrm_Basic_View {
    
    public function preProcess (Ncrm_Request $request, $display=true) {
		parent::preProcess($request, false);
        
		$viewer = $this->getViewer($request);

		$moduleName = $request->getModule();
		if(!empty($moduleName)) {
			$moduleModel = Ncrm_Module_Model::getInstance($moduleName);
			$currentUser = Users_Record_Model::getCurrentUserModel();
			$userPrivilegesModel = Users_Privileges_Model::getInstanceById($currentUser->getId());
			$viewer->assign('MODULE', $moduleName);

			//Dont check for module permissions since for non admin users module permission will not be there 

			$linkParams = array('MODULE'=>$moduleName, 'ACTION'=>$request->get('view'));
			$linkModels = $moduleModel->getSideBarLinks($linkParams);

			$viewer->assign('QUICK_LINKS', $linkModels);
		}
		
		$viewer->assign('CURRENT_USER_MODEL', Users_Record_Model::getCurrentUserModel());
		$viewer->assign('CURRENT_VIEW', $request->get('view'));
		if($display) {
			$this->preProcessDisplay($request);
		}
	}
    
    
    protected function preProcessDisplay(Ncrm_Request $request) {}
	
	public function process(Ncrm_Request $request) {
		$viewer = $this->getViewer($request);
		$viewer->assign('UI5_URL', $this->getUI5EmbedURL($request));
		$viewer->view('UI5EmbedView.tpl');
	}
    
}