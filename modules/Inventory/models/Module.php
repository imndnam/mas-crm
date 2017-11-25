<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/
/**
 * Inventory Module Model Class
 */
class Inventory_Module_Model extends Ncrm_Module_Model {

	/**
	 * Function to check whether the module is an entity type module or not
	 * @return <Boolean> true/false
	 */
	public function isQuickCreateSupported(){
		//SalesOrder module is not enabled for quick create
		return false;
	}
	
	/**
	 * Function to check whether the module is summary view supported
	 * @return <Boolean> - true/false
	 */
	public function isSummaryViewSupported() {
		return false;
	}

	static function getAllCurrencies() {
		return getAllCurrencies();
	}

	static function getAllProductTaxes() {
		return getAllTaxes('available');
	}

	static function getAllShippingTaxes() {
		return getAllTaxes('available', 'sh');
	}

	/**
	 * Function to get relation query for particular module with function name
	 * @param <record> $recordId
	 * @param <String> $functionName
	 * @param Ncrm_Module_Model $relatedModule
	 * @return <String>
	 */
	public function getRelationQuery($recordId, $functionName, $relatedModule) {
		if ($functionName === 'get_activities') {
			$userNameSql = getSqlForNameInDisplayFormat(array('first_name' => 'ncrm_users.first_name', 'last_name' => 'ncrm_users.last_name'), 'Users');

			$query = "SELECT CASE WHEN (ncrm_users.user_name not like '') THEN $userNameSql ELSE ncrm_groups.groupname END AS user_name,
						ncrm_crmentity.*, ncrm_activity.activitytype, ncrm_activity.subject, ncrm_activity.date_start, ncrm_activity.time_start,
						ncrm_activity.recurringtype, ncrm_activity.due_date, ncrm_activity.time_end, ncrm_activity.visibility, ncrm_seactivityrel.crmid AS parent_id,
						CASE WHEN (ncrm_activity.activitytype = 'Task') THEN (ncrm_activity.status) ELSE (ncrm_activity.eventstatus) END AS status
						FROM ncrm_activity
						INNER JOIN ncrm_crmentity ON ncrm_crmentity.crmid = ncrm_activity.activityid
						LEFT JOIN ncrm_seactivityrel ON ncrm_seactivityrel.activityid = ncrm_activity.activityid
						LEFT JOIN ncrm_cntactivityrel ON ncrm_cntactivityrel.activityid = ncrm_activity.activityid
						LEFT JOIN ncrm_users ON ncrm_users.id = ncrm_crmentity.smownerid
						LEFT JOIN ncrm_groups ON ncrm_groups.groupid = ncrm_crmentity.smownerid
							WHERE ncrm_crmentity.deleted = 0 AND ncrm_activity.activitytype = 'Task'
								AND ncrm_seactivityrel.crmid = ".$recordId;

			$relatedModuleName = $relatedModule->getName();
			$query .= $this->getSpecificRelationQuery($relatedModuleName);
			$nonAdminQuery = $this->getNonAdminAccessControlQueryForRelation($relatedModuleName);
			if ($nonAdminQuery) {
				$query = appendFromClauseToQuery($query, $nonAdminQuery);
			}
		} else {
			$query = parent::getRelationQuery($recordId, $functionName, $relatedModule);
		}

		return $query;
	}
	
	/**
	 * Function returns export query
	 * @param <String> $where
	 * @return <String> export query
	 */
	public function getExportQuery($focus, $query) {
		$baseTableName = $focus->table_name;
		$splitQuery = spliti(' FROM ', $query);
		$columnFields = explode(',', $splitQuery[0]);
		foreach ($columnFields as $key => &$value) {
			if($value == ' ncrm_inventoryproductrel.discount_amount'){
				$value = ' ncrm_inventoryproductrel.discount_amount AS item_discount_amount';
			} else if($value == ' ncrm_inventoryproductrel.discount_percent'){
				$value = ' ncrm_inventoryproductrel.discount_percent AS item_discount_percent';
			} else if($value == " $baseTableName.currency_id"){
				$value = ' ncrm_currency_info.currency_name AS currency_id';
			}
		}
		$joinSplit = spliti(' WHERE ',$splitQuery[1]);
		$joinSplit[0] .= " LEFT JOIN ncrm_currency_info ON ncrm_currency_info.id = $baseTableName.currency_id";
		$splitQuery[1] = $joinSplit[0] . ' WHERE ' .$joinSplit[1];

		$query = implode(',', $columnFields).' FROM ' . $splitQuery[1];
		
		return $query;
	}
}
