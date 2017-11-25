<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Portal_EditAjax_View extends Ncrm_IndexAjax_View {

    public function process(Ncrm_Request $request) {
        $moduleName = $request->getModule();
        $recordId = $request->get('record');

        $viewer = $this->getViewer($request);
        
        if(!empty($recordId)) {
            $data = Portal_Module_Model::getRecord($recordId);
            
            $viewer->assign('RECORD', $recordId);
            $viewer->assign('BOOKMARK_NAME', $data['bookmarkName']);
            $viewer->assign('BOOKMARK_URL', $data['bookmarkUrl']);
        }
        
        $viewer->assign('MODULE', $moduleName);
        
        $viewer->view('EditView.tpl', $moduleName);
    }
}