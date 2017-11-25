<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_Ncrm_ListAjax_Action extends Settings_Ncrm_ListAjax_View{
    
    public function __construct() {
        parent::__construct();
        $this->exposeMethod('getPageCount');
    }
	/**
	 * Function returns the number of records for the current filter
	 * @param Ncrm_Request $request
	 */
	function getRecordsCount(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$cvId = $request->get('viewname');
		$count = $this->getListViewCount($request);

		$result = array();
		$result['module'] = $moduleName;
		$result['viewname'] = $cvId;
		$result['count'] = $count;

		$response = new Ncrm_Response();
		$response->setEmitType(Ncrm_Response::$EMIT_JSON);
		$response->setResult($result);
		$response->emit();
	}
    
    public function getListViewCount(Ncrm_Request $request) {
		$qualifiedModuleName = $request->getModule(false);
		$sourceModule = $request->get('sourceModule');

		$listViewModel = Settings_Ncrm_ListView_Model::getInstance($qualifiedModuleName);
		
		if(!empty($sourceModule)) {
			$listViewModel->set('sourceModule', $sourceModule);
		}

		return $listViewModel->getListViewCount();
    }
    
    public function getPageCount(Ncrm_Request $request) {
        $numOfRecords = $this->getListViewCount($request);
        $pagingModel = new Ncrm_Paging_Model();
        $pageCount = ceil((int) $numOfRecords/(int)($pagingModel->getPageLimit()));
        
		if($pageCount == 0){
			$pageCount = 1;
		}
		$result = array();
		$result['page'] = $pageCount;
		$result['numberOfRecords'] = $numOfRecords;
		$response = new Ncrm_Response();
		$response->setResult($result);
		$response->emit();
    }
}