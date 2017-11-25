<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Users_Detail_View extends Users_PreferenceDetail_View {

	public function preProcess(Ncrm_Request $request) {
		parent::preProcess($request, false);
		$this->preProcessSettings($request);
	}

	public function preProcessSettings(Ncrm_Request $request) {
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		$qualifiedModuleName = $request->getModule(false);
		$selectedMenuId = $request->get('block');
		$fieldId = $request->get('fieldid');

		$settingsModel = Settings_Ncrm_Module_Model::getInstance();
		$menuModels = $settingsModel->getMenus();

		if(!empty($selectedMenuId)) {
			$selectedMenu = Settings_Ncrm_Menu_Model::getInstanceById($selectedMenuId);
		} elseif(!empty($moduleName) && $moduleName != 'Ncrm') {
			$fieldItem = Settings_Ncrm_Index_View::getSelectedFieldFromModule($menuModels,$moduleName);
			if($fieldItem){
				$selectedMenu = Settings_Ncrm_Menu_Model::getInstanceById($fieldItem->get('blockid'));
				$fieldId = $fieldItem->get('fieldid');
			} else {
				reset($menuModels);
				$firstKey = key($menuModels);
				$selectedMenu = $menuModels[$firstKey];
			}
		} else {
			reset($menuModels);
			$firstKey = key($menuModels);
			$selectedMenu = $menuModels[$firstKey];
		}

		$viewer->assign('SELECTED_FIELDID',$fieldId);
		$viewer->assign('SELECTED_MENU', $selectedMenu);
		$viewer->assign('SETTINGS_MENUS', $menuModels);
		$viewer->assign('MODULE', $moduleName);
		$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
        $viewer->assign('LOAD_OLD', Settings_Ncrm_Index_View::$loadOlderSettingUi);
		$viewer->assign('CURRENT_USER_MODEL', $currentUserModel);
		$viewer->view('SettingsMenuStart.tpl', $qualifiedModuleName);
	}

	public function postProcessSettings(Ncrm_Request $request) {
		$viewer = $this->getViewer($request);
		$qualifiedModuleName = $request->getModule(false);
		$viewer->view('SettingsMenuEnd.tpl', $qualifiedModuleName);
	}

	public function postProcess(Ncrm_Request $request) {
		$this->postProcessSettings($request);
		parent::postProcess($request);
	}

	public function process(Ncrm_Request $request) {
		$viewer = $this->getViewer($request);

		$viewer->assign('CURRENT_USER_MODEL', Users_Record_Model::getCurrentUserModel());
		$viewer->view('UserViewHeader.tpl', $request->getModule());
		parent::process($request);
	}

	public function getHeaderScripts(Ncrm_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$moduleName = $request->getModule();

		$jsFileNames = array(
			'modules.Settings.Ncrm.resources.Index'
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}

	/**
	 * Function to get Ajax is enabled or not
	 * @param Ncrm_Record_Model record model
	 * @return <boolean> true/false
	 */
	function isAjaxEnabled($recordModel) {
		if($recordModel->get('status') != 'Active') {
			return false;
		}
		return $recordModel->isEditable();
	}
}
