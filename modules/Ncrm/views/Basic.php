<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/
/*********************************************************************************
 * $Header$
 * Description:  Contains a variety of utility functions used to display UI
 * components such as top level menus,more menus,header links,crm logo,global search
 * and quick links of header part
 * footer is also loaded
 * function that connect to db connector to get data
 ********************************************************************************/

abstract class Ncrm_Basic_View extends Ncrm_Footer_View {

	function __construct() {
		parent::__construct();
	}

	function preProcess (Ncrm_Request $request, $display=true) {
		parent::preProcess($request, false);

                $viewer = $this->getViewer($request);

		$menuModelsList = Ncrm_Menu_Model::getAll(true);
		$selectedModule = $request->getModule();
		$menuStructure = Ncrm_MenuStructure_Model::getInstanceFromMenuList($menuModelsList, $selectedModule);

		$companyDetails = Ncrm_CompanyDetails_Model::getInstanceById();
		$companyLogo = $companyDetails->getLogo();
		$currentDate  = Ncrm_Date_UIType::getDisplayDateValue(date('Y-n-j'));
		$viewer->assign('CURRENTDATE', $currentDate);
		$viewer->assign('MODULE', $selectedModule);
                $viewer->assign('MODULE_NAME', $selectedModule);
		$viewer->assign('QUALIFIED_MODULE', $selectedModule);
		$viewer->assign('PARENT_MODULE', $request->get('parent'));
		$viewer->assign('VIEW', $request->get('view'));

		// Order by pre-defined automation process for QuickCreate.
		uksort($menuModelsList, array('Ncrm_MenuStructure_Model', 'sortMenuItemsByProcess'));
                
		$viewer->assign('MENUS', $menuModelsList);
		$viewer->assign('MENU_STRUCTURE', $menuStructure);
		$viewer->assign('MENU_SELECTED_MODULENAME', $selectedModule);
		$viewer->assign('MENU_TOPITEMS_LIMIT', $menuStructure->getLimit());
		$viewer->assign('COMPANY_LOGO',$companyLogo);
		$viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());

		$homeModuleModel = Ncrm_Module_Model::getInstance('Home');
		$viewer->assign('HOME_MODULE_MODEL', $homeModuleModel);
		$viewer->assign('HEADER_LINKS',$this->getHeaderLinks());
		$viewer->assign('ANNOUNCEMENT', $this->getAnnouncement());
		$viewer->assign('SEARCHABLE_MODULES', Ncrm_Module_Model::getSearchableModules());

		if($display) {
			$this->preProcessDisplay($request);
		}
	}

	protected function preProcessTplName(Ncrm_Request $request) {
		return 'BasicHeader.tpl';
	}

	//Note: To get the right hook for immediate parent in PHP,
	// specially in case of deep hierarchy
	/*function preProcessParentTplName(Ncrm_Request $request) {
		return parent::preProcessTplName($request);
	}*/

	function postProcess(Ncrm_Request $request){
		$viewer = $this->getViewer($request);
		//$viewer->assign('GUIDERSJSON', Ncrm_Guider_Model::toJsonList($this->getGuiderModels($request)));
		parent::postProcess($request);
	}

	/**
	 * Function to get the list of Script models to be included
	 * @param Ncrm_Request $request
	 * @return <Array> - List of Ncrm_JsScript_Model instances
	 */
	function getHeaderScripts(Ncrm_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$moduleName = $request->getModule();

		$jsFileNames = array(
			'libraries.bootstrap.js.eternicode-bootstrap-datepicker.js.bootstrap-datepicker',
			'~libraries/bootstrap/js/eternicode-bootstrap-datepicker/js/locales/bootstrap-datepicker.'.Ncrm_Language_Handler::getShortLanguageName().'.js',
			'~libraries/jquery/timepicker/jquery.timepicker.min.js',
            'modules.Ncrm.resources.Header',
			'modules.Ncrm.resources.Edit',
			"modules.$moduleName.resources.Edit",
			'modules.Ncrm.resources.Popup',
			"modules.$moduleName.resources.Popup",
			'modules.Ncrm.resources.Field',
			"modules.$moduleName.resources.Field",
			'modules.Ncrm.resources.validator.BaseValidator',
			'modules.Ncrm.resources.validator.FieldValidator',
			"modules.$moduleName.resources.validator.FieldValidator",
			'libraries.jquery.jquery_windowmsg',
			'modules.Ncrm.resources.BasicSearch',
			"modules.$moduleName.resources.BasicSearch",
			'modules.Ncrm.resources.AdvanceFilter',
			"modules.$moduleName.resources.AdvanceFilter",
			'modules.Ncrm.resources.SearchAdvanceFilter',
			"modules.$moduleName.resources.SearchAdvanceFilter",
			'modules.Ncrm.resources.AdvanceSearch',
			"modules.$moduleName.resources.AdvanceSearch",
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($jsScriptInstances,$headerScriptInstances);
		return $headerScriptInstances;
	}

	public function getHeaderCss(Ncrm_Request $request) {
		$headerCssInstances = parent::getHeaderCss($request);

		$cssFileNames = array(
			'~/libraries/jquery/timepicker/jquery.timepicker.css',
		);
		$cssInstances = $this->checkAndConvertCssStyles($cssFileNames);
		$headerCssInstances = array_merge($headerCssInstances, $cssInstances);

		return $headerCssInstances;
	}

	function getGuiderModels(Ncrm_Request $request) {
		return array();
	}

}