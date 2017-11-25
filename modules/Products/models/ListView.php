<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Products_ListView_Model extends Ncrm_ListView_Model {

	/**
	 * Function to get the list view entries
	 * @param Ncrm_Paging_Model $pagingModel
	 * @return <Array> - Associative array of record id mapped to Ncrm_Record_Model instance.
	 */
	public function getListViewEntries($pagingModel) {
		$db = PearDatabase::getInstance();

		$moduleName = $this->getModule()->get('name');
		$moduleFocus = CRMEntity::getInstance($moduleName);
		$moduleModel = Ncrm_Module_Model::getInstance($moduleName);

		$queryGenerator = $this->get('query_generator');
		$listViewContoller = $this->get('listview_controller');

         $searchParams = $this->get('search_params');
        if(empty($searchParams)) {
            $searchParams = array();
        }
        
        $glue = "";
        if(count($queryGenerator->getWhereFields()) > 0 && (count($searchParams)) > 0) {
            $glue = QueryGenerator::$AND;
        }
        $queryGenerator->parseAdvFilterList($searchParams, $glue);
        
		$searchKey = $this->get('search_key');
		$searchValue = $this->get('search_value');
		$operator = $this->get('operator');
		if(!empty($searchKey)) {
			$queryGenerator->addUserSearchConditions(array('search_field' => $searchKey, 'search_text' => $searchValue, 'operator' => $operator));
		}
        
        
        $orderBy = $this->getForSql('orderby');
		$sortOrder = $this->getForSql('sortorder');

		//List view will be displayed on recently created/modified records
		if(empty($orderBy) && empty($sortOrder) && $moduleName != "Users"){
			$orderBy = 'modifiedtime';
			$sortOrder = 'DESC';
		}

        if(!empty($orderBy)){
            $columnFieldMapping = $moduleModel->getColumnFieldMapping();
            $orderByFieldName = $columnFieldMapping[$orderBy];
            $orderByFieldModel = $moduleModel->getField($orderByFieldName);
            if($orderByFieldModel && $orderByFieldModel->getFieldDataType() == Ncrm_Field_Model::REFERENCE_TYPE){
                //IF it is reference add it in the where fields so that from clause will be having join of the table
                $queryGenerator = $this->get('query_generator');
                $queryGenerator->addWhereField($orderByFieldName);
            }
        }
		
		if (!empty($orderBy) && $orderBy === 'smownerid') { 
			$fieldModel = Ncrm_Field_Model::getInstance('assigned_user_id', $moduleModel);
			if ($fieldModel->getFieldDataType() == 'owner') { 
				$orderBy = 'COALESCE(CONCAT(ncrm_users.first_name,ncrm_users.last_name),ncrm_groups.groupname)'; 
			} 
		} 

		$listQuery = $this->getQuery();

		if($this->get('subProductsPopup')){
			$listQuery = $this->addSubProductsQuery($listQuery);
		}

		$sourceModule = $this->get('src_module');
		$sourceField = $this->get('src_field');
		if(!empty($sourceModule)) {
			if(method_exists($moduleModel, 'getQueryByModuleField')) {
				$overrideQuery = $moduleModel->getQueryByModuleField($sourceModule, $sourceField, $this->get('src_record'), $listQuery);
				if(!empty($overrideQuery)) {
					$listQuery = $overrideQuery;
				}
			}
		}

		$startIndex = $pagingModel->getStartIndex();
		$pageLimit = $pagingModel->getPageLimit();

		if(!empty($orderBy)) {
            if($orderByFieldModel && $orderByFieldModel->getFieldDataType() == Ncrm_Field_Model::REFERENCE_TYPE){
                $referenceModules = $orderByFieldModel->getReferenceList();
                $referenceNameFieldOrderBy = array();
                foreach($referenceModules as $referenceModuleName) {
                    $referenceModuleModel = Ncrm_Module_Model::getInstance($referenceModuleName);
                    $referenceNameFields = $referenceModuleModel->getNameFields();

                    $columnList = array();
                    foreach($referenceNameFields as $nameField) {
                        $fieldModel = $referenceModuleModel->getField($nameField);
                        $columnList[] = $fieldModel->get('table').$orderByFieldModel->getName().'.'.$fieldModel->get('column');
                    }
                    if(count($columnList) > 1) {
                        $referenceNameFieldOrderBy[] = getSqlForNameInDisplayFormat(array('first_name'=>$columnList[0],'last_name'=>$columnList[1]),'Users').' '.$sortOrder;
                    } else {
                        $referenceNameFieldOrderBy[] = implode('', $columnList).' '.$sortOrder ;
                    }
                }
                $listQuery .= ' ORDER BY '. implode(',',$referenceNameFieldOrderBy);
            }else{
                $listQuery .= ' ORDER BY '. $orderBy . ' ' .$sortOrder;
            }
		}

		$viewid = ListViewSession::getCurrentView($moduleName);
        if(empty($viewid)){
            $viewid = $pagingModel->get('viewid');
        }
        $_SESSION['lvs'][$moduleName][$viewid]['start'] = $pagingModel->get('page');
		ListViewSession::setSessionQuery($moduleName, $listQuery, $viewid);

		//For Products popup in Price Book Related list
		if($sourceModule !== 'PriceBooks' && $sourceField !== 'priceBookRelatedList') {
			$listQuery .= " LIMIT $startIndex,".($pageLimit+1);
		}

		$listResult = $db->pquery($listQuery, array());

		$listViewRecordModels = array();
		$listViewEntries =  $listViewContoller->getListViewRecords($moduleFocus,$moduleName, $listResult);
		$pagingModel->calculatePageRange($listViewEntries);

		if($db->num_rows($listResult) > $pageLimit && $sourceModule !== 'PriceBooks' && $sourceField !== 'priceBookRelatedList'){
			array_pop($listViewEntries);
			$pagingModel->set('nextPageExists', true);
		}else{
			$pagingModel->set('nextPageExists', false);
		}

		$index = 0;
		foreach($listViewEntries as $recordId => $record) {
			$rawData = $db->query_result_rowdata($listResult, $index++);
			$record['id'] = $recordId;
			$listViewRecordModels[$recordId] = $moduleModel->getRecordFromArray($record, $rawData);
		}
		return $listViewRecordModels;
	}

	public function addSubProductsQuery($listQuery){
		$splitQuery = split('WHERE', $listQuery);
		$query = " LEFT JOIN ncrm_seproductsrel ON ncrm_seproductsrel.crmid = ncrm_products.productid AND ncrm_seproductsrel.setype='Products'";
		$splitQuery[0] .= $query;
		$productId = $this->get('productId');
		$query1 = " AND ncrm_seproductsrel.productid = $productId";
		$splitQuery[1] .= $query1;
		$listQuery = $splitQuery[0]. ' WHERE ' . $splitQuery[1];
		return $listQuery;
	}

	public function getSubProducts($subProductId){
		$flag = false;
		if(!empty($subProductId)){
            $db = PearDatabase::getInstance();
			$result = $db->pquery("SELECT ncrm_seproductsrel.crmid from ncrm_seproductsrel INNER JOIN
                ncrm_crmentity ON ncrm_seproductsrel.crmid = ncrm_crmentity.crmid 
					AND ncrm_crmentity.deleted = 0 AND ncrm_seproductsrel.setype=? 
				WHERE ncrm_seproductsrel.productid=?", array($this->getModule()->get('name'), $subProductId ));
			if($db->num_rows($result) > 0){
				$flag = true;
			}
		}
		return $flag;
	}

	/**
	 * Function to get the list view entries
	 * @param Ncrm_Paging_Model $pagingModel
	 * @return <Array> - Associative array of record id mapped to Ncrm_Record_Model instance.
	 */
	public function getListViewCount() {
		$db = PearDatabase::getInstance();

		$queryGenerator = $this->get('query_generator');

        $searchParams = $this->get('search_params');
        if(empty($searchParams)) {
            $searchParams = array();
        }
        
        $glue = "";
        if(count($queryGenerator->getWhereFields()) > 0 && (count($searchParams)) > 0) {
            $glue = QueryGenerator::$AND;
        }
        $queryGenerator->parseAdvFilterList($searchParams, $glue);
        
        $searchKey = $this->get('search_key');
		$searchValue = $this->get('search_value');
		$operator = $this->get('operator');
		if(!empty($searchKey)) {
			$queryGenerator->addUserSearchConditions(array('search_field' => $searchKey, 'search_text' => $searchValue, 'operator' => $operator));
		}
        
        

		$listQuery = $this->getQuery();

		if($this->get('subProductsPopup')){
			$listQuery = $this->addSubProductsQuery($listQuery);
		}

		$sourceModule = $this->get('src_module');
		if(!empty($sourceModule)) {
			$moduleModel = $this->getModule();
			if(method_exists($moduleModel, 'getQueryByModuleField')) {
				$overrideQuery = $moduleModel->getQueryByModuleField($sourceModule, $this->get('src_field'), $this->get('src_record'), $listQuery);
				if(!empty($overrideQuery)) {
					$listQuery = $overrideQuery;
				}
			}
		}
		$position = stripos($listQuery, ' from ');
		if ($position) {
			$split = spliti(' from ', $listQuery);
			$splitCount = count($split);
			$listQuery = 'SELECT count(*) AS count ';
			for ($i=1; $i<$splitCount; $i++) {
				$listQuery = $listQuery. ' FROM ' .$split[$i];
			}
		}

		if($this->getModule()->get('name') == 'Calendar'){
			$listQuery .= ' AND activitytype <> "Emails"';
		}

		$listResult = $db->pquery($listQuery, array());
		return $db->query_result($listResult, 0, 'count');
	}

}
