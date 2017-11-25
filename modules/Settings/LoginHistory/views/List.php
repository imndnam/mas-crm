<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_LoginHistory_List_View extends Settings_Ncrm_List_View {
	
	function preProcess(Ncrm_Request $request, $display=true) {
		$viewer = $this->getViewer($request);
		$loginHistoryRecordModel = new  Settings_LoginHistory_Record_Model();
		$usersList = $loginHistoryRecordModel->getAccessibleUsers();
		$viewer->assign('USERSLIST',$usersList);
        $viewer->assign('SELECTED_USER',$request->get('user_name'));
		parent::preProcess($request, false);
	}
}