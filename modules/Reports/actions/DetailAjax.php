<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Reports_DetailAjax_Action extends Ncrm_BasicAjax_Action{
    
    public function __construct() {
        parent::__construct();
		$this->exposeMethod('getRecordsCount');
	}
    
    public function process(Ncrm_Request $request) {
		$mode = $request->get('mode');
		if(!empty($mode)) {
			$this->invokeExposedMethod($mode, $request);
			return;
		}
	}
    
    /**
	 * Function to get related Records count from this relation
	 * @param <Ncrm_Request> $request
	 * @return <Number> Number of record from this relation
	 */
	public function getRecordsCount(Ncrm_Request $request) {
		$record = $request->get('record');
		$reportModel = Reports_Record_Model::getInstanceById($record);
		$reportModel->setModule('Reports');
		$reportModel->set('advancedFilter', $request->get('advanced_filter'));
        
        $advFilterSql = $reportModel->getAdvancedFilterSQL();
        $query = $reportModel->getReportSQL($advFilterSql, 'PDF');
        $countQuery = $reportModel->generateCountQuery($query);

        $count = $reportModel->getReportsCount($countQuery);
        $response = new Ncrm_Response();
        $response->setResult($count);
        $response->emit();
    }
    
}