<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Ncrm_TagCloud_Dashboard extends Ncrm_IndexAjax_View {
	
	/**
	 * Function to get the list of Script models to be included
	 * @param Ncrm_Request $request
	 * @return <Array> - List of Ncrm_JsScript_Model instances
	 */
	public function getHeaderScripts(Ncrm_Request $request) {

		$jsFileNames = array(
			'~/libraries/jquery/jquery.tagcloud.js'
		);

		$headerScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		return $headerScriptInstances;
	}
	
	public function process(Ncrm_Request $request) {
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		
		$linkId = $request->get('linkid');
		
		$widget = Ncrm_Widget_Model::getInstance($linkId, $currentUser->getId());
		
		$tags = Ncrm_Tag_Model::getAll($currentUser->id);

		//Include special script and css needed for this widget
		$viewer->assign('SCRIPTS',$this->getHeaderScripts($request));

		$viewer->assign('WIDGET', $widget);
		$viewer->assign('TAGS', $tags);
		$viewer->assign('MODULE_NAME', $moduleName);
		
		$content = $request->get('content');
		if(!empty($content)) {
			$viewer->view('dashboards/TagCloudContents.tpl', $moduleName);
		} else {
			$viewer->view('dashboards/TagCloud.tpl', $moduleName);
		}
		
	}
	
}