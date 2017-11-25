<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 * ************************************************************************************/

class Contacts_Module_Model extends Ncrm_Module_Model {
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
	 * Function returns the Calendar Events for the module
	 * @param <Ncrm_Paging_Model> $pagingModel
	 * @return <Array>
	 */
	public function getCalendarActivities($mode, $pagingModel, $user, $recordId = false) {
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$db = PearDatabase::getInstance();

		if (!$user) {
			$user = $currentUser->getId();
		}

		$nowInUserFormat = Ncrm_Datetime_UIType::getDisplayDateValue(date('Y-m-d H:i:s'));
		$nowInDBFormat = Ncrm_Datetime_UIType::getDBDateTimeValue($nowInUserFormat);
		list($currentDate, $currentTime) = explode(' ', $nowInDBFormat);

		$query = "SELECT ncrm_crmentity.crmid, crmentity2.crmid AS contact_id, ncrm_crmentity.smownerid, ncrm_crmentity.setype, ncrm_activity.* FROM ncrm_activity
					INNER JOIN ncrm_crmentity ON ncrm_crmentity.crmid = ncrm_activity.activityid
					INNER JOIN ncrm_cntactivityrel ON ncrm_cntactivityrel.activityid = ncrm_activity.activityid
					INNER JOIN ncrm_crmentity AS crmentity2 ON ncrm_cntactivityrel.contactid = crmentity2.crmid AND crmentity2.deleted = 0 AND crmentity2.setype = ?
					LEFT JOIN ncrm_groups ON ncrm_groups.groupid = ncrm_crmentity.smownerid";

		$query .= Users_Privileges_Model::getNonAdminAccessControlQuery('Calendar');

		$query .= " WHERE ncrm_crmentity.deleted=0
					AND (ncrm_activity.activitytype NOT IN ('Emails'))
					AND (ncrm_activity.status is NULL OR ncrm_activity.status NOT IN ('Completed', 'Deferred'))
					AND (ncrm_activity.eventstatus is NULL OR ncrm_activity.eventstatus NOT IN ('Held'))";

		if ($recordId) {
			$query .= " AND ncrm_cntactivityrel.contactid = ?";
		} elseif ($mode === 'upcoming') {
			$query .= " AND due_date >= '$currentDate'";
		} elseif ($mode === 'overdue') {
			$query .= " AND due_date < '$currentDate'";
		}

		$params = array($this->getName());
		if ($recordId) {
			array_push($params, $recordId);
		}

		if($user != 'all' && $user != '') {
			if($user === $currentUser->id) {
				$query .= " AND ncrm_crmentity.smownerid = ?";
				array_push($params, $user);
			}
		}

		$query .= " ORDER BY date_start, time_start LIMIT ". $pagingModel->getStartIndex() .", ". ($pagingModel->getPageLimit()+1);

		$result = $db->pquery($query, $params);
		$numOfRows = $db->num_rows($result);
		
		$groupsIds = Ncrm_Util_Helper::getGroupsIdsForUsers($currentUser->getId());
		$activities = array();
		for($i=0; $i<$numOfRows; $i++) {
			$newRow = $db->query_result_rowdata($result, $i);
			$model = Ncrm_Record_Model::getCleanInstance('Calendar');
			$ownerId = $newRow['smownerid'];
			$currentUser = Users_Record_Model::getCurrentUserModel();
			$visibleFields = array('activitytype','date_start','time_start','due_date','time_end','assigned_user_id','visibility','smownerid','crmid');
			$visibility = true;
			if(in_array($ownerId, $groupsIds)) {
				$visibility = false;
			} else if($ownerId == $currentUser->getId()){
				$visibility = false;
			}
			if(!$currentUser->isAdminUser() && $newRow['activitytype'] != 'Task' && $newRow['visibility'] == 'Private' && $ownerId && $visibility) {
				foreach($newRow as $data => $value) {
					if(in_array($data, $visibleFields) != -1) {
						unset($newRow[$data]);
					}
				}
				$newRow['subject'] = vtranslate('Busy','Events').'*';
			}
			if($newRow['activitytype'] == 'Task') {
				unset($newRow['visibility']);
			}
			
			$model->setData($newRow);
			$model->setId($newRow['crmid']);
			$activities[] = $model;
		}
		
		$pagingModel->calculatePageRange($activities);
		if($numOfRows > $pagingModel->getPageLimit()){
			array_pop($activities);
			$pagingModel->set('nextPageExists', true);
		} else {
			$pagingModel->set('nextPageExists', false);
		}

		return $activities;
	}

	/**
	 * Function returns query for module record's search
	 * @param <String> $searchValue - part of record name (label column of crmentity table)
	 * @param <Integer> $parentId - parent record id
	 * @param <String> $parentModule - parent module name
	 * @return <String> - query
	 */
	function getSearchRecordsQuery($searchValue, $parentId=false, $parentModule=false) {
		if($parentId && $parentModule == 'Accounts') {
			$query = "SELECT * FROM ncrm_crmentity
						INNER JOIN ncrm_contactdetails ON ncrm_contactdetails.contactid = ncrm_crmentity.crmid
						WHERE deleted = 0 AND ncrm_contactdetails.accountid = $parentId AND label like '%$searchValue%'";
			return $query;
		} else if($parentId && $parentModule == 'Potentials') {
			$query = "SELECT * FROM ncrm_crmentity
						INNER JOIN ncrm_contactdetails ON ncrm_contactdetails.contactid = ncrm_crmentity.crmid
						LEFT JOIN ncrm_contpotentialrel ON ncrm_contpotentialrel.contactid = ncrm_contactdetails.contactid
						LEFT JOIN ncrm_potential ON ncrm_potential.contact_id = ncrm_contactdetails.contactid
						WHERE deleted = 0 AND (ncrm_contpotentialrel.potentialid = $parentId OR ncrm_potential.potentialid = $parentId)
						AND label like '%$searchValue%'";
			
				return $query;
		} else if ($parentId && $parentModule == 'HelpDesk') {
            $query = "SELECT * FROM ncrm_crmentity
                        INNER JOIN ncrm_contactdetails ON ncrm_contactdetails.contactid = ncrm_crmentity.crmid
                        INNER JOIN ncrm_troubletickets ON ncrm_troubletickets.contact_id = ncrm_contactdetails.contactid
                        WHERE deleted=0 AND ncrm_troubletickets.ticketid  = $parentId  AND label like '%$searchValue%'";

            return $query;
        } else if($parentId && $parentModule == 'Campaigns') {
            $query = "SELECT * FROM ncrm_crmentity
                        INNER JOIN ncrm_contactdetails ON ncrm_contactdetails.contactid = ncrm_crmentity.crmid
                        INNER JOIN ncrm_campaigncontrel ON ncrm_campaigncontrel.contactid = ncrm_contactdetails.contactid
                        WHERE deleted=0 AND ncrm_campaigncontrel.campaignid = $parentId AND label like '%$searchValue%'";

            return $query;
        } else if($parentId && $parentModule == 'Vendors') {
            $query = "SELECT ncrm_crmentity.* FROM ncrm_crmentity
                        INNER JOIN ncrm_contactdetails ON ncrm_contactdetails.contactid = ncrm_crmentity.crmid
                        INNER JOIN ncrm_vendorcontactrel ON ncrm_vendorcontactrel.contactid = ncrm_contactdetails.contactid
                        WHERE deleted=0 AND ncrm_vendorcontactrel.vendorid = $parentId AND label like '%$searchValue%'";

            return $query;
        } else if ($parentId && $parentModule == 'Quotes') {
            $query = "SELECT * FROM ncrm_crmentity
                        INNER JOIN ncrm_contactdetails ON ncrm_contactdetails.contactid = ncrm_crmentity.crmid
                        INNER JOIN ncrm_quotes ON ncrm_quotes.contactid = ncrm_contactdetails.contactid
                        WHERE deleted=0 AND ncrm_quotes.quoteid  = $parentId  AND label like '%$searchValue%'";

            return $query;
        } else if ($parentId && $parentModule == 'PurchaseOrder') {
            $query = "SELECT * FROM ncrm_crmentity
                        INNER JOIN ncrm_contactdetails ON ncrm_contactdetails.contactid = ncrm_crmentity.crmid
                        INNER JOIN ncrm_purchaseorder ON ncrm_purchaseorder.contactid = ncrm_contactdetails.contactid
                        WHERE deleted=0 AND ncrm_purchaseorder.purchaseorderid  = $parentId  AND label like '%$searchValue%'";

            return $query;
        } else if ($parentId && $parentModule == 'SalesOrder') {
            $query = "SELECT * FROM ncrm_crmentity
                        INNER JOIN ncrm_contactdetails ON ncrm_contactdetails.contactid = ncrm_crmentity.crmid
                        INNER JOIN ncrm_salesorder ON ncrm_salesorder.contactid = ncrm_contactdetails.contactid
                        WHERE deleted=0 AND ncrm_salesorder.salesorderid  = $parentId  AND label like '%$searchValue%'";

            return $query;
        } else if ($parentId && $parentModule == 'Invoice') {
            $query = "SELECT * FROM ncrm_crmentity
                        INNER JOIN ncrm_contactdetails ON ncrm_contactdetails.contactid = ncrm_crmentity.crmid
                        INNER JOIN ncrm_invoice ON ncrm_invoice.contactid = ncrm_contactdetails.contactid
                        WHERE deleted=0 AND ncrm_invoice.invoiceid  = $parentId  AND label like '%$searchValue%'";

            return $query;
        }

		return parent::getSearchRecordsQuery($parentId, $parentModule);
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
						ncrm_cntactivityrel.contactid, ncrm_seactivityrel.crmid AS parent_id,
						ncrm_crmentity.*, ncrm_activity.activitytype, ncrm_activity.subject, ncrm_activity.date_start, ncrm_activity.time_start,
						ncrm_activity.recurringtype, ncrm_activity.due_date, ncrm_activity.time_end, ncrm_activity.visibility,
						CASE WHEN (ncrm_activity.activitytype = 'Task') THEN (ncrm_activity.status) ELSE (ncrm_activity.eventstatus) END AS status
						FROM ncrm_activity
						INNER JOIN ncrm_crmentity ON ncrm_crmentity.crmid = ncrm_activity.activityid
						INNER JOIN ncrm_cntactivityrel ON ncrm_cntactivityrel.activityid = ncrm_activity.activityid
						LEFT JOIN ncrm_seactivityrel ON ncrm_seactivityrel.activityid = ncrm_activity.activityid
						LEFT JOIN ncrm_users ON ncrm_users.id = ncrm_crmentity.smownerid
						LEFT JOIN ncrm_groups ON ncrm_groups.groupid = ncrm_crmentity.smownerid
							WHERE ncrm_cntactivityrel.contactid = ".$recordId." AND ncrm_crmentity.deleted = 0
								AND ncrm_activity.activitytype <> 'Emails'";

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
	 * Function to get list view query for popup window
	 * @param <String> $sourceModule Parent module
	 * @param <String> $field parent fieldname
	 * @param <Integer> $record parent id
	 * @param <String> $listQuery
	 * @return <String> Listview Query
	 */
	public function getQueryByModuleField($sourceModule, $field, $record, $listQuery) {
		if (in_array($sourceModule, array('Campaigns', 'Potentials', 'Vendors', 'Products', 'Services', 'Emails'))
				|| ($sourceModule === 'Contacts' && $field === 'contact_id' && $record)) {
			switch ($sourceModule) {
				case 'Campaigns'	: $tableName = 'ncrm_campaigncontrel';	$fieldName = 'contactid';	$relatedFieldName ='campaignid';	break;
				case 'Potentials'	: $tableName = 'ncrm_contpotentialrel';	$fieldName = 'contactid';	$relatedFieldName ='potentialid';	break;
				case 'Vendors'		: $tableName = 'ncrm_vendorcontactrel';	$fieldName = 'contactid';	$relatedFieldName ='vendorid';		break;
				case 'Products'		: $tableName = 'ncrm_seproductsrel';		$fieldName = 'crmid';		$relatedFieldName ='productid';		break;
			}

			if ($sourceModule === 'Services') {
				$condition = " ncrm_contactdetails.contactid NOT IN (SELECT relcrmid FROM ncrm_crmentityrel WHERE crmid = '$record' UNION SELECT crmid FROM ncrm_crmentityrel WHERE relcrmid = '$record') ";
			} elseif ($sourceModule === 'Emails') {
				$condition = ' ncrm_contactdetails.emailoptout = 0';
			} elseif ($sourceModule === 'Contacts' && $field === 'contact_id') {
				$condition = " ncrm_contactdetails.contactid != '$record'";
			} else {
				$condition = " ncrm_contactdetails.contactid NOT IN (SELECT $fieldName FROM $tableName WHERE $relatedFieldName = '$record')";
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
    
    public function getDefaultSearchField(){
        return "lastname";
    }
    
}