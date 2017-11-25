<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/
include_once 'include/Webservices/Query.php';
include_once dirname(__FILE__) . '/Filter.php';

class Mobile_WS_SearchFilterModel extends Mobile_WS_FilterModel {
	
	protected $criterias;
	
	function __construct($moduleName) {
		$this->moduleName = $moduleName;
	}
	
	function query() {
		return false;
	}
	
	function queryParameters() {
		return false;
	}
	
	function setCriterias($criterias) {
		$this->criterias = $criterias;
	}
	
	function execute($fieldnames, $pagingModel = false) {
		
		$selectClause = sprintf("SELECT %s", implode(',', $fieldnames));
		$fromClause = sprintf("FROM %s", $this->moduleName);
		$whereClause = "";
		$orderClause = "";
		$groupClause = "";
		$limitClause = $pagingModel? " LIMIT {$pagingModel->currentCount()},{$pagingModel->limit()}" : "" ;
		
		if (!empty($this->criterias)) {
			$_sortCriteria = $this->criterias['_sort'];
			if(!empty($_sortCriteria)) {
				$orderClause = $_sortCriteria;
			}
		}
		
		$query = sprintf("%s %s %s %s %s %s;", $selectClause, $fromClause, $whereClause, $orderClause, $groupClause, $limitClause);
		return vtws_query($query, $this->getUser()); 
	}
	
	static function modelWithCriterias($moduleName, $criterias = false) {
		$model = new Mobile_WS_SearchFilterModel($moduleName);
		$model->setCriterias($criterias);
		return $model;
	}
}