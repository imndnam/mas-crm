<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class EmailTemplates_List_View extends Ncrm_Index_View {
	
	function __construct() {
		parent::__construct();
	}
	
	function preProcess (Ncrm_Request $request, $display=true) {
		parent::preProcess($request, false);
		
		$viewer = $this->getViewer ($request);
		$moduleName = $request->getModule();

		$listViewModel = EmailTemplates_ListView_Model::getInstance($moduleName);

		$linkParams = array('MODULE'=>$moduleName, 'ACTION'=>$request->get('view'));
		$quickLinkModels = $listViewModel->getSideBarLinks($linkParams);
		
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
		$viewer->assign('VIEW', $request->get('view'));
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
		$cvId = $request->get('viewname');
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

		$listViewModel = EmailTemplates_ListView_Model::getInstance($moduleName, $cvId);
		$linkParams = array('MODULE'=>$moduleName, 'ACTION'=>$request->get('view'), 'CVID'=>$cvId);
		$linkModels = $listViewModel->getListViewMassActions($linkParams);
		$pagingModel = new Ncrm_Paging_Model();
		$pagingModel->set('page', $pageNumber);

		if(!empty($orderBy)) {
			$listViewModel->set('orderby', $orderBy);
			$listViewModel->set('sortorder',$sortOrder);
		}

		$searchKey = $request->get('search_key');
		$searchValue = $request->get('search_value');
		$operator = $request->get('operator');
		if(!empty($operator)) {
			$listViewModel->set('operator', $operator);
			$viewer->assign('OPERATOR',$operator);
			$viewer->assign('ALPHABET_VALUE',$searchValue);
		}
		if(!empty($searchKey) && !empty($searchValue)) {
			$listViewModel->set('search_key', $searchKey);
			$listViewModel->set('search_value', $searchValue);
		}
		if(!$this->listViewHeaders){
			$this->listViewHeaders = $listViewModel->getListViewHeaders();
		}
		if(!$this->listViewEntries){
			$this->listViewEntries = $listViewModel->getListViewEntries($pagingModel);
		}
		$noOfEntries = count($this->listViewEntries);
		$viewer->assign('VIEWID', $cvId);
		$viewer->assign('MODULE', $moduleName);
		
		if(!$this->listViewLinks){
			$this->listViewLinks = $listViewModel->getListViewLinks($linkParams);
		}
		$viewer->assign('LISTVIEW_LINKS', $this->listViewLinks);
		$viewer->assign('LISTVIEW_MASSACTIONS', $linkModels['LISTVIEWMASSACTION']);

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

		if (PerformancePrefs::getBoolean('LISTVIEW_COMPUTE_PAGE_COUNT', false)) {
			if(!$this->listViewCount){
				$this->listViewCount = $listViewModel->getListViewCount();
			}
			$viewer->assign('LISTVIEW_COUNT', $this->listViewCount);
		}

		$viewer->assign('IS_MODULE_EDITABLE', $listViewModel->getModule()->isPermitted('EditView'));
		$viewer->assign('IS_MODULE_DELETABLE', $listViewModel->getModule()->isPermitted('Delete'));
	}
	
	/**
	 * Function returns the number of records for the current filter
	 * @param Ncrm_Request $request
	 */
	function getRecordsCount(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$count = $this->getListViewCount($request);

		$result = array();
		$result['module'] = $moduleName;
		$result['count'] = $count;

		$response = new Ncrm_Response();
		$response->setEmitType(Ncrm_Response::$EMIT_JSON);
		$response->setResult($result);
		$response->emit();
	}

	/**
	 * Function to get listView count
	 * @param Ncrm_Request $request
	 */
	function getListViewCount(Ncrm_Request $request){
		$moduleName = $request->getModule();
		$searchKey = $request->get('search_key');
		$searchValue = $request->get('search_value');

		$listViewModel = EmailTemplates_ListView_Model::getInstance($moduleName);
		$listViewModel->set('search_key', $searchKey);
		$listViewModel->set('search_value', $searchValue);
		$listViewModel->set('operator', $request->get('operator'));

		return $listViewModel->getListViewCount();
	}



	/**
	 * Function to get the page count for list
	 * @return total number of pages
	 */
	function getPageCount(Ncrm_Request $request){
		$listViewCount = $this->getListViewCount($request);
		$pagingModel = new Ncrm_Paging_Model();
		$pageLimit = $pagingModel->getPageLimit();
		$pageCount = ceil((int) $listViewCount / (int) $pageLimit);

		$result = array();
		$result['page'] = $pageCount;
		$result['numberOfRecords'] = $listViewCount;
		$response = new Ncrm_Response();
		$response->setResult($result);
		$response->emit();
	}
	
	/**
	 * Function to get the list of Script models to be included
	 * @param Ncrm_Request $request
	 * @return <Array> - List of Ncrm_JsScript_Model instances
	 */
	function getHeaderScripts(Ncrm_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);

		$jsFileNames = array(
			'modules.Ncrm.resources.List',
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}
	
}