<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Rss_ViewTypes_View extends Ncrm_IndexAjax_View {

    function __construct() {
        parent::__construct();
        $this->exposeMethod('getRssWidget');
        $this->exposeMethod('getRssAddForm');
    }
        
	/**
     * Function to display rss sidebar widget
     * @param <Ncrm_Request> $request
     */
    public function getRssWidget(Ncrm_Request $request) {
        $module = $request->get('module');
        $moduleModel = Ncrm_Module_Model::getInstance($module);
        $rssSources = $moduleModel->getRssSources();
        $viewer = $this->getViewer($request);
        $viewer->assign('MODULE', $module);
        $viewer->assign('RSS_SOURCES', $rssSources);
        echo $viewer->view('RssWidgetContents.tpl', $module, true);
    }
    
    /**
     * Function to get the rss add form 
     * @param <Ncrm_Request> $request
     */
    public function getRssAddForm(Ncrm_Request $request) {
        $module = $request->getModule();
		$moduleModel = Ncrm_Module_Model::getInstance($module);
		$viewer = $this->getViewer($request);
		$viewer->assign('MODULE',$module);
        $viewer->view('RssAddForm.tpl', $module);
    }
   
}
