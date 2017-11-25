<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class ModComments_DetailAjax_View extends Ncrm_IndexAjax_View {

	public function process(Ncrm_Request $request) {
		$record = $request->get('record');
		$moduleName = $request->getModule();
		$recordModel = ModComments_Record_Model::getInstanceById($record);
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
        $modCommentsModel = Ncrm_Module_Model::getInstance('ModComments');
		
		$viewer = $this->getViewer($request);
		$viewer->assign('CURRENTUSER', $currentUserModel);
		$viewer->assign('COMMENT', $recordModel);
        $viewer->assign('COMMENTS_MODULE_MODEL', $modCommentsModel);
		echo $viewer->view('Comment.tpl', $moduleName, true);
	}
}