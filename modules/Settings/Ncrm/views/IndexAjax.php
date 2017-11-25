<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_Ncrm_IndexAjax_View extends Settings_Ncrm_Index_View {
	function __construct() {
		parent::__construct();
		$this->exposeMethod('getSettingsShortCutBlock');
                $this->exposeMethod('realignSettingsShortCutBlock');
	}
	
	public function preProcess (Ncrm_Request $request) {
		return;
	}

	public function postProcess (Ncrm_Request $request) {
		return;
	}
	
	public function process (Ncrm_Request $request) {
		$mode = $request->getMode();

		if($mode){
			echo $this->invokeExposedMethod($mode, $request);
			return;
		}
	}
	
	public function getSettingsShortCutBlock(Ncrm_Request $request) {
		$fieldid = $request->get('fieldid');
		$viewer = $this->getViewer($request);
		$qualifiedModuleName = $request->getModule(false);
		$pinnedSettingsShortcuts = Settings_Ncrm_MenuItem_Model::getPinnedItems();
		$viewer->assign('SETTINGS_SHORTCUT',$pinnedSettingsShortcuts[$fieldid]);
		$viewer->assign('MODULE',$qualifiedModuleName);
		$viewer->view('SettingsShortCut.tpl', $qualifiedModuleName);
	}
        
        public function realignSettingsShortCutBlock(Ncrm_Request $request){
		$viewer = $this->getViewer($request);
		$qualifiedModuleName = $request->getModule(false);
		$pinnedSettingsShortcuts = Settings_Ncrm_MenuItem_Model::getPinnedItems();
		$viewer->assign('SETTINGS_SHORTCUT',$pinnedSettingsShortcuts);
		$viewer->assign('MODULE',$qualifiedModuleName);
		$viewer->view('ReAlignSettingsShortCut.tpl', $qualifiedModuleName);
	}
}