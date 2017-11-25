<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class RecycleBin_List_View extends Ncrm_Index_View {

	function checkPermission(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$moduleModel = Ncrm_Module_Model::getInstance($moduleName);

		$currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		if(!$currentUserPriviligesModel->hasModulePermission($moduleModel->getId())) {
			throw new AppException('LBL_PERMISSION_DENIED');
		}
	}

	function preProcess(Ncrm_Request $request, $display=true) {
		parent::preProcess($request, false);
		$viewer = $this->getViewer ($request);
		$moduleName = $request->getModule();
		
		$moduleModel = RecycleBin_Module_Model::getInstance($moduleName);
		
		$linkParams = array('MODULE'=>$moduleName, 'ACTION'=>$request->get('view'));
		
		$quickLinkModels = $moduleModel->getSideBarLinks($linkParams);
		
		$viewer->assign('QUICK_LINKS', $quickLinkModels);
		$this->initializeListViewContents($request, $viewer);
		
		if($display) {
			$this->preProcessDisplay($request);
		}
	}

	function preProcessTplName(Ncrm_Request $request) {
		return 'ListViewPreProcess.tpl';
	}

	function process (Ncrm_Request $request) {
		$viewer = $this->getViewer ($request);
		$moduleName = $request->getModule();
		$moduleModel = Ncrm_Module_Model::getInstance($moduleName);

		$this->initializeListViewContents($request, $viewer);
		
		$viewer->assign('MODULE_MODEL', $moduleModel);
		$viewer->assign('CURRENT_USER_MODEL', Users_Record_Model::getCurrentUserModel());
		
		$viewer->view('ListViewContents.tpl', $moduleName);
	}

    function postProcess(Ncrm_Request $request) {
        $viewer = $this->getViewer ($request);
		$moduleName = $request->getModule();

		$viewer->view('ListViewPostProcess.tpl', $moduleName);
		parent::postProcess($request);
    }

	/*
	 * Function to initialize the required data in smarty to display the List View Contents
	 */
	public function initializeListViewContents(Ncrm_Request $request, Ncrm_Viewer $viewer) {
		$moduleName = $request->getModule();
		$sourceModule = $request->get('sourceModule');

		$pageNumber = $request->get('page');
		$orderBy = $request->get('orderby');
		$sortOrder = $request->get('sortorder');
		if($sortOrder == "ASC"){
			$nextSortOrder = "DESC";
			$sortImage = "fa-chevron-down";
		}else{
			$nextSortOrder = "ASC";
			$sortImage = "fa-chevron-up";
		}

		if(empty ($pageNumber)){
			$pageNumber = '1';
		}

		$moduleModel = RecycleBin_Module_Model::getInstance($moduleName);
		//If sourceModule is empty, pick the first module name from the list
		if(empty($sourceModule)) {
			foreach($moduleModel->getAllModuleList() as $model) {
				$sourceModule = $model->get('name');
				break;
			}
		}
		$listViewModel = RecycleBin_ListView_Model::getInstance($moduleName, $sourceModule);
		
		$linkParams = array('MODULE'=>$moduleName, 'ACTION'=>$request->get('view'));
		$linkModels = $moduleModel->getListViewMassActions($linkParams);

		$pagingModel = new Ncrm_Paging_Model();
		$pagingModel->set('page', $pageNumber);

		if(!empty($orderBy)) {
			$listViewModel->set('orderby', $orderBy);
			$listViewModel->set('sortorder',$sortOrder);
		}

		if(!$this->listViewHeaders){
			$this->listViewHeaders = $listViewModel->getListViewHeaders();
		}
		if(!$this->listViewEntries){
			$this->listViewEntries = $listViewModel->getListViewEntries($pagingModel);
		}
		$noOfEntries = count($this->listViewEntries);

		$viewer->assign('MODULE', $moduleName);
		
		$viewer->assign('LISTVIEW_LINKS', $moduleModel->getListViewLinks());
		$viewer->assign('LISTVIEW_MASSACTIONS', $linkModels);

		$viewer->assign('PAGING_MODEL', $pagingModel);
		$viewer->assign('PAGE_NUMBER',$pageNumber);

		$viewer->assign('ORDER_BY',$orderBy);
		$viewer->assign('SORT_ORDER',$sortOrder);
		$viewer->assign('NEXT_SORT_ORDER',$nextSortOrder);
		$viewer->assign('SORT_IMAGE',$sortImage);
		$viewer->assign('COLUMN_NAME',$orderBy);

		$viewer->assign('LISTVIEW_ENTRIES_COUNT',$noOfEntries);
		$viewer->assign('LISTVIEW_HEADERS', $this->listViewHeaders);
		$viewer->assign('LISTVIEW_ENTRIES', $this->listViewEntries);
		$viewer->assign('MODULE_LIST', $moduleModel->getAllModuleList());
		$viewer->assign('SOURCE_MODULE',$sourceModule);
                $viewer->assign('DELETED_RECORDS_TOTAL_COUNT', $moduleModel->getDeletedRecordsTotalCount()); 
		
		if (PerformancePrefs::getBoolean('LISTVIEW_COMPUTE_PAGE_COUNT', false)) {
			if(!$this->listViewCount){
				$this->listViewCount = $listViewModel->getListViewCount();
			}
			$totalCount = $this->listViewCount;
			$pageLimit = $pagingModel->getPageLimit();
			$pageCount = ceil((int) $totalCount / (int) $pageLimit);

			if($pageCount == 0){
				$pageCount = 1;
			}
			$viewer->assign('PAGE_COUNT', $pageCount);
			$viewer->assign('LISTVIEW_COUNT', $totalCount);
		}
		$viewer->assign('IS_MODULE_DELETABLE', $listViewModel->getModule()->isPermitted('Delete'));
		
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
			'modules.Ncrm.resources.List',
			"modules.$moduleName.resources.List",
			'modules.CustomView.resources.CustomView',
			"modules.$moduleName.resources.CustomView",
			"modules.Emails.resources.MassEdit",
			"modules.Ncrm.resources.CkEditor"
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}
	
	/**
	 * Function to get the page count for list
	 * @return total number of pages
	 */
	function getPageCount(Ncrm_Request $request){
		$moduleName = $request->getModule();
		$sourceModule = $request->get('sourceModule');
		$listViewModel = RecycleBin_ListView_Model::getInstance($moduleName, $sourceModule);
		
		$listViewCount = $listViewModel->getListViewCount($request);
		$pagingModel = new Ncrm_Paging_Model();
		$pageLimit = $pagingModel->getPageLimit();
		$pageCount = ceil((int) $listViewCount / (int) $pageLimit);

		if($pageCount == 0){
			$pageCount = 1;
		}
		$result = array();
		$result['page'] = $pageCount;
		$result['numberOfRecords'] = $listViewCount;
		$response = new Ncrm_Response();
		$response->setResult($result);
		$response->emit();
	}
	
	/**
	 * Function returns the number of records for the current filter
	 * @param Ncrm_Request $request
	 */
	function getRecordsCount(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$sourceModule = $request->get('sourceModule');
		$listViewModel = RecycleBin_ListView_Model::getInstance($moduleName, $sourceModule);
		
		$count = $listViewModel->getListViewCount();

		$result = array();
		$result['module'] = $moduleName;
		$result['count'] = $count;

		$response = new Ncrm_Response();
		$response->setEmitType(Ncrm_Response::$EMIT_JSON);
		$response->setResult($result);
		$response->emit();
	}
}