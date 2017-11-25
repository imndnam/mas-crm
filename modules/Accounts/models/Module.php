<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 * ************************************************************************************/

class Accounts_Module_Model extends Ncrm_Module_Model {

	/**
	 * Function to get the Quick Links for the module
	 * @param <Array> $linkParams
	 * @return <Array> List of Ncrm_Link_Model instances
	 */
	public function getSideBarLinks($linkParams) {
		$parentQuickLinks = parent::getSideBarLinks($linkParams);

		$quickLink = array(
			'linktype' => 'SIDEBARLINK',
			'linklabel' => 'LBL_DASHBOARD',
			'linkurl' => $this->getDashBoardUrl(),
			'linkicon' => '',
		);

		//Check profile permissions for Dashboards
		$moduleModel = Ncrm_Module_Model::getInstance('Dashboard');
		$userPrivilegesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		$permission = $userPrivilegesModel->hasModulePermission($moduleModel->getId());
		if($permission) {
			$parentQuickLinks['SIDEBARLINK'][] = Ncrm_Link_Model::getInstanceFromValues($quickLink);
		}
		
		return $parentQuickLinks;
	}

	/**
	 * Function to get list view query for popup window
	 * @param <String> $sourceModule Parent module
	 * @param <String> $field parent fieldname
	 * @param <Integer> $record parent id
	 * @param <String> $listQuery
	 * @return <String> Listview Query
	 */
	public function getQueryByModuleField($sourceModule, $field, $record, $listQuery) {
		if (($sourceModule == 'Accounts' && $field == 'account_id' && $record)
				|| in_array($sourceModule, array('Campaigns', 'Products', 'Services', 'Emails'))) {

			if ($sourceModule === 'Campaigns') {
				$condition = " ncrm_account.accountid NOT IN (SELECT accountid FROM ncrm_campaignaccountrel WHERE campaignid = '$record')";
			} elseif ($sourceModule === 'Products') {
				$condition = " ncrm_account.accountid NOT IN (SELECT crmid FROM ncrm_seproductsrel WHERE productid = '$record')";
			} elseif ($sourceModule === 'Services') {
				$condition = " ncrm_account.accountid NOT IN (SELECT relcrmid FROM ncrm_crmentityrel WHERE crmid = '$record' UNION SELECT crmid FROM ncrm_crmentityrel WHERE relcrmid = '$record') ";
			} elseif ($sourceModule === 'Emails') {
				$condition = ' ncrm_account.emailoptout = 0';
			} else {
				$condition = " ncrm_account.accountid != '$record'";
			}

			$position = stripos($listQuery, 'where');
			if($position) {
				$split = spliti('where', $listQuery);
				$overRideQuery = $split[0] . ' WHERE ' . $split[1] . ' AND ' . $condition;
			} else {
				$overRideQuery = $listQuery. ' WHERE ' . $condition;
			}
			return $overRideQuery;
		}
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
			$focus = CRMEntity::getInstance($this->getName());
			$focus->id = $recordId;
			$entityIds = $focus->getRelatedContactsIds();
			$entityIds = implode(',', $entityIds);

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
							WHERE ncrm_crmentity.deleted = 0 AND ncrm_activity.activitytype <> 'Emails'
								AND (ncrm_seactivityrel.crmid = ".$recordId;
			if($entityIds) {
				$query .= " OR ncrm_cntactivityrel.contactid IN (".$entityIds."))";
			} else {
				$query .= ")";
			}

			$relatedModuleName = $relatedModule->getName();
			$query .= $this->getSpecificRelationQuery($relatedModuleName);
			$nonAdminQuery = $this->getNonAdminAccessControlQueryForRelation($relatedModuleName);
			if ($nonAdminQuery) {
				$query = appendFromClauseToQuery($query, $nonAdminQuery);
			}

			// There could be more than one contact for an activity.
			$query .= ' GROUP BY ncrm_activity.activityid';
		} else {
			$query = parent::getRelationQuery($recordId, $functionName, $relatedModule);
		}

		return $query;
	}
}
