<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/
class Mobile_WS_FetchModuleFilters extends Mobile_WS_Controller {
	
	function process(Mobile_API_Request $request) {
		$response = new Mobile_API_Response();

		$module = $request->get('module');
		$current_user = $this->getActiveUser();
		
		$result = array();
		
		$filters = $this->getModuleFilters($module, $current_user);
		$yours = array();
		$others= array();
		if(!empty($filters)) {
			foreach($filters as $filter) {
				if($filter['userName'] == $current_user->column_fields['user_name']) {
					$yours[] = $filter;
				} else {
					$others[]= $filter;
				}
			}
		}
		
		$result['filters'] = array('yours' => $yours, 'others' => $others);
		$response->setResult($result);

		return $response;
	}

	protected function getModuleFilters($moduleName, $user) {
		
		$filters = array();
		
		global $adb;
		$sql = "SELECT ncrm_customview.*, ncrm_users.user_name FROM ncrm_customview 
			INNER JOIN ncrm_users ON ncrm_customview.userid = ncrm_users.id WHERE ncrm_customview.entitytype=?";
		$parameters = array($moduleName);

		if(!is_admin($user)) {
			require('user_privileges/user_privileges_'.$user->id.'.php');
			
			$sql .= " AND (ncrm_customview.status=0 or ncrm_customview.userid = ? or ncrm_customview.status = 3 or ncrm_customview.userid IN
			(SELECT ncrm_user2role.userid FROM ncrm_user2role INNER JOIN ncrm_users on ncrm_users.id=ncrm_user2role.userid 
			INNER JOIN ncrm_role on ncrm_role.roleid=ncrm_user2role.roleid WHERE ncrm_role.parentrole LIKE '".$current_user_parent_role_seq."::%'))";
			
			array_push($parameters, $current_user->id);
		}
		
		$result = $adb->pquery($sql, $parameters);
		if($result && $adb->num_rows($result)) {
			while($resultrow = $adb->fetch_array($result)) {
				$filters[] = $this->prepareFilterDetailUsingResultRow($resultrow);
			}
		}
		
		return $filters;
	}
	
	protected function prepareFilterDetailUsingResultRow($resultrow) {
		$filter = array();
		$filter['cvid'] = $resultrow['cvid'];
		$filter['viewname'] = decode_html($resultrow['viewname']);
		$filter['setdefault'] = $resultrow['setdefault'];
		$filter['setmetrics'] = $resultrow['setmetrics'];
		$filter['moduleName'] = decode_html($resultrow['entitytype']);
		$filter['status']     = decode_html($resultrow['status']);
		$filter['userName']   = decode_html($resultrow['user_name']);
		return $filter;
	}
}