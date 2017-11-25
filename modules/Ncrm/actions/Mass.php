<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

abstract class Ncrm_Mass_Action extends Ncrm_Action_Controller {

	protected function getRecordsListFromRequest(Ncrm_Request $request) {
		$cvId = $request->get('viewname');
		$module = $request->get('module');
		if(!empty($cvId) && $cvId=="undefined"){
			$sourceModule = $request->get('sourceModule');
			$cvId = CustomView_Record_Model::getAllFilterByModule($sourceModule)->getId();
		}
		$selectedIds = $request->get('selected_ids');
		$excludedIds = $request->get('excluded_ids');

		if(!empty($selectedIds) && $selectedIds != 'all') {
			if(!empty($selectedIds) && count($selectedIds) > 0) {
				return $selectedIds;
			}
		}

		$customViewModel = CustomView_Record_Model::getInstanceById($cvId);
		if($customViewModel) {
            $searchKey = $request->get('search_key');
            $searchValue = $request->get('search_value');
            $operator = $request->get('operator');
            if(!empty($operator)) {
                $customViewModel->set('operator', $operator);
                $customViewModel->set('search_key', $searchKey);
                $customViewModel->set('search_value', $searchValue);
            }

            $customViewModel->set('search_params',$request->get('search_params'));
			return $customViewModel->getRecordIds($excludedIds,$module);
		}
	}
        
        public function validateRequest(Ncrm_Request $request) {
            $request->validateWriteAccess(); 
        } 
}
