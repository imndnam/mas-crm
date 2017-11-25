<?php

/* +***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 * *********************************************************************************** */

class Products_Detail_View extends Ncrm_Detail_View {

	public function showModuleDetailView(Ncrm_Request $request) {
		$recordId = $request->get('record');
		$moduleName = $request->getModule();

		$recordModel = Ncrm_Record_Model::getInstanceById($recordId, $moduleName);
		$baseCurrenctDetails = $recordModel->getBaseCurrencyDetails();
		
		$viewer = $this->getViewer($request);
		$viewer->assign('BASE_CURRENCY_SYMBOL', $baseCurrenctDetails['symbol']);
		$viewer->assign('TAXCLASS_DETAILS', $recordModel->getTaxClassDetails());
		$viewer->assign('IMAGE_DETAILS', $recordModel->getImageDetails());

		return parent::showModuleDetailView($request);
	}

	public function showModuleBasicView(Ncrm_Request $request) {
		return $this->showModuleDetailView($request);
	}

	public function getHeaderScripts(Ncrm_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$moduleName = $request->getModule();
		$moduleDetailFile = 'modules.'.$moduleName.'.resources.Detail';
		$moduleRelatedListFile = 'modules.'.$moduleName.'.resources.RelatedList';
		unset($headerScriptInstances[$moduleDetailFile]);
		unset($headerScriptInstances[$moduleRelatedListFile]);

		$jsFileNames = array(
			'~libraries/jquery/jquery.cycle.min.js',
			'modules.PriceBooks.resources.Detail',
			'modules.PriceBooks.resources.RelatedList',
		);
		
		$jsFileNames[] = $moduleDetailFile;
		$jsFileNames[] = $moduleRelatedListFile;
		
		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}
	
	/**
	 * Function returns related records
	 * @param Ncrm_Request $request
	 * @return <type>
	 */
	function showRelatedList(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$relatedModuleName = $request->get('relatedModule');
		$parentId = $request->get('record');
		$label = $request->get('tab_label');

		$requestedPage = $request->get('page');
		if(empty ($requestedPage)) {
			$requestedPage = 1;
		}

		$pagingModel = new Ncrm_Paging_Model();
		$pagingModel->set('page',$requestedPage);

		$parentRecordModel = Ncrm_Record_Model::getInstanceById($parentId, $moduleName);
		$relationListView = Ncrm_RelationListView_Model::getInstance($parentRecordModel, $relatedModuleName, $label);
		$orderBy = $request->get('orderby');
		$sortOrder = $request->get('sortorder');
		if($sortOrder == "ASC") {
			$nextSortOrder = "DESC";
			$sortImage = "fa-chevron-down";
		} else {
			$nextSortOrder = "ASC";
			$sortImage = "fa-chevron-up";
		}
		if(!empty($orderBy)) {
			$relationListView->set('orderby', $orderBy);
			$relationListView->set('sortorder',$sortOrder);
		}
		$models = $relationListView->getEntries($pagingModel);
		$links = $relationListView->getLinks();
		$header = $relationListView->getHeaders();
		$noOfEntries = count($models);
		
		if($relatedModuleName == 'PriceBooks'){
			foreach ($models as $recordId => $recorModel) {
				$productIdsList[$parentId] = $parentId;
				$relatedRecordCurrencyId = $recorModel->get('currency_id');
				$parentModuleModel = $parentRecordModel->getModule();
				$unitPricesList = $parentModuleModel->getPricesForProducts($relatedRecordCurrencyId, $productIdsList);
				$recorModel->set('unit_price', $unitPricesList[$parentId]);
			}
		}

		$relationModel = $relationListView->getRelationModel();
		$relationField = $relationModel->getRelationField();
		
		$viewer = $this->getViewer($request);
		$viewer->assign('RELATED_RECORDS' , $models);
		$viewer->assign('PARENT_RECORD', $parentRecordModel);
		$viewer->assign('RELATED_LIST_LINKS', $links);
		$viewer->assign('RELATED_HEADERS', $header);
		$viewer->assign('RELATED_MODULE', $relationModel->getRelationModuleModel());
		$viewer->assign('RELATED_ENTIRES_COUNT', $noOfEntries);
		$viewer->assign('RELATION_FIELD', $relationField);

		if (PerformancePrefs::getBoolean('LISTVIEW_COMPUTE_PAGE_COUNT', false)) {
			$totalCount = $relationListView->getRelatedEntriesCount();
			$pageLimit = $pagingModel->getPageLimit();
			$pageCount = ceil((int) $totalCount / (int) $pageLimit);

			if($pageCount == 0){
				$pageCount = 1;
			}
			$viewer->assign('PAGE_COUNT', $pageCount);
			$viewer->assign('TOTAL_ENTRIES', $totalCount);
			$viewer->assign('PERFORMANCE', true);
		}
        
        $viewer->assign('IS_EDITABLE', $relationModel->isEditable());
		$viewer->assign('IS_DELETABLE', $relationModel->isDeletable());
        
		$viewer->assign('MODULE', $moduleName);
		$viewer->assign('PAGING', $pagingModel);
		$viewer->assign('ORDER_BY',$orderBy);
		$viewer->assign('SORT_ORDER',$sortOrder);
		$viewer->assign('NEXT_SORT_ORDER',$nextSortOrder);
		$viewer->assign('SORT_IMAGE',$sortImage);
		$viewer->assign('COLUMN_NAME',$orderBy);
		$viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());
		
		return $viewer->view('RelatedList.tpl', $moduleName, 'true');
	}
}
