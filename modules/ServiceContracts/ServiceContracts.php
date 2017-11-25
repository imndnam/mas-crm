<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/
class ServiceContracts extends CRMEntity {
	var $db, $log; // Used in class functions of CRMEntity

	var $table_name = 'ncrm_servicecontracts';
	var $table_index= 'servicecontractsid';
	var $column_fields = Array();

	/** Indicator if this is a custom module or standard module */
	var $IsCustomModule = true;

	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('ncrm_servicecontractscf', 'servicecontractsid');

	/**
	 * Mandatory for Saving, Include tables related to this module.
	 */
	var $tab_name = Array('ncrm_crmentity', 'ncrm_servicecontracts', 'ncrm_servicecontractscf');

	/**
	 * Mandatory for Saving, Include tablename and tablekey columnname here.
	 */
	var $tab_name_index = Array(
		'ncrm_crmentity' => 'crmid',
		'ncrm_servicecontracts' => 'servicecontractsid',
		'ncrm_servicecontractscf'=>'servicecontractsid');

	/**
	 * Mandatory for Listing (Related listview)
	 */
	var $list_fields = Array (
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'ncrm_'
		'Subject' => Array('servicecontracts', 'subject'),
		'Assigned To' => Array('crmentity','smownerid'),
		'Contract No' => Array('servicecontracts','contract_no'),
		'Used Units' => Array('servicecontracts','used_units'),
		'Total Units' => Array('servicecontracts','total_units')
	);
	var $list_fields_name = Array (
		/* Format: Field Label => fieldname */
		'Subject' => 'subject',
		'Assigned To' => 'assigned_user_id',
		'Contract No' =>  'contract_no',
		'Used Units' => 'used_units',
		'Total Units' => 'total_units'
	);

	// Make the field link to detail view
	var $list_link_field = 'subject';

	// For Popup listview and UI type support
	var $search_fields = Array(
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'ncrm_'
		'Subject' => Array('servicecontracts', 'subject'),
		'Contract No' => Array('servicecontracts', 'contract_no'),
		'Assigned To' => Array('ncrm_crmentity','assigned_user_id'),
		'Used Units' => Array('servicecontracts','used_units'),
		'Total Units' => Array('servicecontracts','total_units')
	);
	var $search_fields_name = Array (
		/* Format: Field Label => fieldname */
		'Subject' => 'subject',
		'Contract No' => 'contract_no',
		'Assigned To' => 'assigned_user_id',
		'Used Units' => 'used_units',
		'Total Units' => 'total_units'
	);

	// For Popup window record selection
	var $popup_fields = Array ('subject');

	// Placeholder for sort fields - All the fields will be initialized for Sorting through initSortFields
	var $sortby_fields = Array();

	// For Alphabetical search
	var $def_basicsearch_col = 'subject';

	// Column value to use on detail view record text display
	var $def_detailview_recname = 'subject';

	// Required Information for enabling Import feature
	var $required_fields = Array ('assigned_user_id'=>1);

	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to ncrm_field.fieldname values.
	var $mandatory_fields = Array('subject','assigned_user_id');

	// Callback function list during Importing
	var $special_functions = Array('set_import_assigned_user');

	var $default_order_by = 'subject';
	var $default_sort_order='ASC';

	function __construct() {
		global $log;
		$this->column_fields = getColumnFields(get_class($this));
		$this->db = new PearDatabase();
		$this->log = $log;
	}

	function save_module($module) {
		$return_action = $_REQUEST['return_action'];
		$for_module = $_REQUEST['return_module'];
		$for_crmid  = $_REQUEST['return_id'];
		if ($return_action && $for_module && $for_crmid) {
			if ($for_module == 'HelpDesk') {
				$on_focus = CRMEntity::getInstance($for_module);
				$on_focus->save_related_module($for_module, $for_crmid, $module, $this->id);
			}
		}
	}

	/**
	 * Return query to use based on given modulename, fieldname
	 * Useful to handle specific case handling for Popup
	 */
	function getQueryByModuleField($module, $fieldname, $srcrecord) {
		// $srcrecord could be empty
	}

	/**
	 * Get list view query.
	 */
	function getListQuery($module, $where='') {
		$query = "SELECT ncrm_crmentity.*, $this->table_name.*";

		// Select Custom Field Table Columns if present
		if(!empty($this->customFieldTable)) $query .= ", " . $this->customFieldTable[0] . ".* ";

		$query .= " FROM $this->table_name";

		$query .= "	INNER JOIN ncrm_crmentity ON ncrm_crmentity.crmid = $this->table_name.$this->table_index";

		// Consider custom table join as well.
		if(!empty($this->customFieldTable)) {
			$query .= " INNER JOIN ".$this->customFieldTable[0]." ON ".$this->customFieldTable[0].'.'.$this->customFieldTable[1] .
				      " = $this->table_name.$this->table_index";
		}
		$query .= " LEFT JOIN ncrm_users ON ncrm_users.id = ncrm_crmentity.smownerid";
		$query .= " LEFT JOIN ncrm_groups ON ncrm_groups.groupid = ncrm_crmentity.smownerid";

		$linkedModulesQuery = $this->db->pquery("SELECT distinct fieldname, columnname, relmodule FROM ncrm_field" .
				" INNER JOIN ncrm_fieldmodulerel ON ncrm_fieldmodulerel.fieldid = ncrm_field.fieldid" .
				" WHERE uitype='10' AND ncrm_fieldmodulerel.module=?", array($module));
		$linkedFieldsCount = $this->db->num_rows($linkedModulesQuery);

		for($i=0; $i<$linkedFieldsCount; $i++) {
			$related_module = $this->db->query_result($linkedModulesQuery, $i, 'relmodule');
			$fieldname = $this->db->query_result($linkedModulesQuery, $i, 'fieldname');
			$columnname = $this->db->query_result($linkedModulesQuery, $i, 'columnname');

			$other = CRMEntity::getInstance($related_module);
			vtlib_setup_modulevars($related_module, $other);

			$query .= " LEFT JOIN $other->table_name ON $other->table_name.$other->table_index =".
					"$this->table_name.$columnname";
		}

		global $current_user;
		$query .= $this->getNonAdminAccessControlQuery($module,$current_user);
		$query .= "WHERE ncrm_crmentity.deleted = 0 ".$where;
		return $query;
	}

	/**
	 * Apply security restriction (sharing privilege) query part for List view.
	 */
	function getListViewSecurityParameter($module) {
		global $current_user;
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		require('user_privileges/sharing_privileges_'.$current_user->id.'.php');

		$sec_query = '';
		$tabid = getTabid($module);

		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1
			&& $defaultOrgSharingPermission[$tabid] == 3) {

				$sec_query .= " AND (ncrm_crmentity.smownerid in($current_user->id) OR ncrm_crmentity.smownerid IN
					(
						SELECT ncrm_user2role.userid FROM ncrm_user2role
						INNER JOIN ncrm_users ON ncrm_users.id=ncrm_user2role.userid
						INNER JOIN ncrm_role ON ncrm_role.roleid=ncrm_user2role.roleid
						WHERE ncrm_role.parentrole LIKE '".$current_user_parent_role_seq."::%'
					)
					OR ncrm_crmentity.smownerid IN
					(
						SELECT shareduserid FROM ncrm_tmp_read_user_sharing_per
						WHERE userid=".$current_user->id." AND tabid=".$tabid."
					)
					OR
						(";

					// Build the query based on the group association of current user.
					if(sizeof($current_user_groups) > 0) {
						$sec_query .= " ncrm_groups.groupid IN (". implode(",", $current_user_groups) .") OR ";
					}
					$sec_query .= " ncrm_groups.groupid IN
						(
							SELECT ncrm_tmp_read_group_sharing_per.sharedgroupid
							FROM ncrm_tmp_read_group_sharing_per
							WHERE userid=".$current_user->id." and tabid=".$tabid."
						)";
				$sec_query .= ")
				)";
		}
		return $sec_query;
	}

    /*
	 * Function to get the secondary query part of a report
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule,$queryplanner) {

		$matrix = $queryplanner->newDependencyMatrix();
        $matrix->setDependency('ncrm_crmentityServiceContracts',array('ncrm_groupsServiceContracts','ncrm_usersServiceContracts'));
		$matrix->setDependency('ncrm_servicecontracts',array('ncrm_servicecontractscf','ncrm_crmentityServiceContracts'));
        if (!$queryplanner->requireTable('ncrm_servicecontracts', $matrix)) {
			return '';
		}

		$query = $this->getRelationQuery($module,$secmodule,"ncrm_servicecontracts","servicecontractsid", $queryplanner);

        if ($queryplanner->requireTable("ncrm_crmentityServiceContracts",$matrix)){
			$query .= " left join ncrm_crmentity as ncrm_crmentityServiceContracts on ncrm_crmentityServiceContracts.crmid = ncrm_servicecontracts.servicecontractsid  and ncrm_crmentityServiceContracts.deleted=0";
		}
        if ($queryplanner->requireTable("ncrm_servicecontractscf")){
		    $query .= " left join ncrm_servicecontractscf on ncrm_servicecontractscf.servicecontractsid = ncrm_servicecontracts.servicecontractsid";
		}
        if ($queryplanner->requireTable("ncrm_groupsServiceContracts")){
			$query .= " left join ncrm_groups as ncrm_groupsServiceContracts on ncrm_groupsServiceContracts.groupid = ncrm_crmentityServiceContracts.smownerid";
		}
		if ($queryplanner->requireTable("ncrm_usersServiceContracts")){
			$query .= " left join ncrm_users as ncrm_usersServiceContracts on ncrm_usersServiceContracts.id = ncrm_crmentityServiceContracts.smownerid";
		}
        if($queryplanner->requireTable("ncrm_contactdetailsRelServiceContracts")){
            $query .= " left join ncrm_contactdetails as ncrm_contactdetailsRelServiceContracts on ncrm_contactdetailsRelServiceContracts.contactid = ncrm_servicecontracts.sc_related_to";
        }
        if($queryplanner->requireTable("ncrm_accountRelServiceContracts")){
            $query .= " left join ncrm_account as ncrm_accountRelServiceContracts on ncrm_accountRelServiceContracts.accountid = ncrm_servicecontracts.sc_related_to";
        }
         if ($queryplanner->requireTable("ncrm_lastModifiedByServiceContracts")){
			$query .= " left join ncrm_users as ncrm_lastModifiedByServiceContracts on ncrm_lastModifiedByServiceContracts.id = ncrm_crmentityServiceContracts.modifiedby ";
		}
        if ($queryplanner->requireTable("ncrm_createdbyServiceContracts")){
			$query .= " left join ncrm_users as ncrm_createdbyServiceContracts on ncrm_createdbyServiceContracts.id = ncrm_crmentityServiceContracts.smcreatorid ";
		}
        return $query;
	}
	/**
	 * Create query to export the records.
	 */
	function create_export_query($where)
	{
		global $current_user,$currentModule;

		include("include/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery('ServiceContracts', "detail_view");

		$fields_list = getFieldsListFromQuery($sql);

		$query = "SELECT $fields_list, ncrm_users.user_name AS user_name
					FROM ncrm_crmentity INNER JOIN $this->table_name ON ncrm_crmentity.crmid=$this->table_name.$this->table_index";

		if(!empty($this->customFieldTable)) {
			$query .= " INNER JOIN ".$this->customFieldTable[0]." ON ".$this->customFieldTable[0].'.'.$this->customFieldTable[1] .
				      " = $this->table_name.$this->table_index";
		}

		$query .= " LEFT JOIN ncrm_groups ON ncrm_groups.groupid = ncrm_crmentity.smownerid";
		$query .= " LEFT JOIN ncrm_users ON ncrm_crmentity.smownerid = ncrm_users.id and ".
		"ncrm_users.status='Active'";

		$linkedModulesQuery = $this->db->pquery("SELECT distinct fieldname, columnname, relmodule FROM ncrm_field" .
				" INNER JOIN ncrm_fieldmodulerel ON ncrm_fieldmodulerel.fieldid = ncrm_field.fieldid" .
				" WHERE uitype='10' AND ncrm_fieldmodulerel.module=?", array($thismodule));
		$linkedFieldsCount = $this->db->num_rows($linkedModulesQuery);

		for($i=0; $i<$linkedFieldsCount; $i++) {
			$related_module = $this->db->query_result($linkedModulesQuery, $i, 'relmodule');
			$fieldname = $this->db->query_result($linkedModulesQuery, $i, 'fieldname');
			$columnname = $this->db->query_result($linkedModulesQuery, $i, 'columnname');

			$other = CRMEntity::getInstance($related_module);
			vtlib_setup_modulevars($related_module, $other);

			$query .= " LEFT JOIN $other->table_name ON $other->table_name.$other->table_index = ".
					"$this->table_name.$columnname";
		}

		$query .= $this->getNonAdminAccessControlQuery($thismodule,$current_user);
		$where_auto = " ncrm_crmentity.deleted=0";

		if($where != '') $query .= " WHERE ($where) AND $where_auto";
		else $query .= " WHERE $where_auto";

		return $query;
	}

	/**
	 * Function which will give the basic query to find duplicates
	 */
	function getDuplicatesQuery($module,$table_cols,$field_values,$ui_type_arr,$select_cols='') {
		$select_clause = "SELECT ". $this->table_name .".".$this->table_index ." AS recordid, ncrm_users_last_import.deleted,".$table_cols;

		// Select Custom Field Table Columns if present
		if(isset($this->customFieldTable)) $query .= ", " . $this->customFieldTable[0] . ".* ";

		$from_clause = " FROM $this->table_name";

		$from_clause .= "	INNER JOIN ncrm_crmentity ON ncrm_crmentity.crmid = $this->table_name.$this->table_index";

		// Consider custom table join as well.
		if(isset($this->customFieldTable)) {
			$from_clause .= " INNER JOIN ".$this->customFieldTable[0]." ON ".$this->customFieldTable[0].'.'.$this->customFieldTable[1] .
				      " = $this->table_name.$this->table_index";
		}
		$from_clause .= " LEFT JOIN ncrm_users ON ncrm_users.id = ncrm_crmentity.smownerid
				LEFT JOIN ncrm_groups ON ncrm_groups.groupid = ncrm_crmentity.smownerid";

		$where_clause = "	WHERE ncrm_crmentity.deleted = 0";
		$where_clause .= $this->getListViewSecurityParameter($module);

		if (isset($select_cols) && trim($select_cols) != '') {
			$sub_query = "SELECT $select_cols FROM  $this->table_name AS t " .
				" INNER JOIN ncrm_crmentity AS crm ON crm.crmid = t.".$this->table_index;
			// Consider custom table join as well.
			if(isset($this->customFieldTable)) {
				$sub_query .= " INNER JOIN ".$this->customFieldTable[0]." tcf ON tcf.".$this->customFieldTable[1]." = t.$this->table_index";
			}
			$sub_query .= " WHERE crm.deleted=0 GROUP BY $select_cols HAVING COUNT(*)>1";
		} else {
			$sub_query = "SELECT $table_cols $from_clause $where_clause GROUP BY $table_cols HAVING COUNT(*)>1";
		}

		$query = $select_clause . $from_clause .
					" LEFT JOIN ncrm_users_last_import ON ncrm_users_last_import.bean_id=" . $this->table_name .".".$this->table_index .
					" INNER JOIN (" . $sub_query . ") AS temp ON ".get_on_clause($field_values,$ui_type_arr,$module) .
					$where_clause .
					" ORDER BY $table_cols,". $this->table_name .".".$this->table_index ." ASC";

		return $query;
	}

	/**
	* Invoked when special actions are performed on the module.
	* @param String Module name
	* @param String Event Type
	*/
	function vtlib_handler($moduleName, $eventType) {

		require_once('include/utils/utils.php');
		global $adb;

 		if($eventType == 'module.postinstall') {
			require_once('vtlib/Ncrm/Module.php');

			$moduleInstance = Ncrm_Module::getInstance($moduleName);

			$accModuleInstance = Ncrm_Module::getInstance('Accounts');
			$accModuleInstance->setRelatedList($moduleInstance,'Service Contracts',array('add'),'get_dependents_list');

			$conModuleInstance = Ncrm_Module::getInstance('Contacts');
			$conModuleInstance->setRelatedList($moduleInstance,'Service Contracts',array('add'),'get_dependents_list');

			$helpDeskInstance = Ncrm_Module::getInstance("HelpDesk");
			$helpDeskInstance->setRelatedList($moduleInstance,"Service Contracts",Array('ADD','SELECT'));

			// Initialize module sequence for the module
			$adb->pquery("INSERT into ncrm_modentity_num values(?,?,?,?,?,?)",array($adb->getUniqueId("ncrm_modentity_num"),$moduleName,'SERCON',1,1,1));

			// Make the picklist value 'Complete' for status as non-editable
			$adb->query("UPDATE ncrm_contract_status SET presence=0 WHERE contract_status='Complete'");

			// Mark the module as Standard module
			$adb->pquery('UPDATE ncrm_tab SET customized=0 WHERE name=?', array($moduleName));

		} else if($eventType == 'module.disabled') {
			$em = new VTEventsManager($adb);
			$em->setHandlerInActive('ServiceContractsHandler');

		} else if($eventType == 'module.enabled') {
			$em = new VTEventsManager($adb);
			$em->setHandlerActive('ServiceContractsHandler');

		} else if($eventType == 'module.preuninstall') {
		// TODO Handle actions when this module is about to be deleted.
		} else if($eventType == 'module.preupdate') {
		// TODO Handle actions before this module is updated.
		} else if($eventType == 'module.postupdate') {
		// TODO Handle actions after this module is updated.
		}
 	}

	/**
	 * Handle saving related module information.
	 * NOTE: This function has been added to CRMEntity (base class).
	 * You can override the behavior by re-defining it here.
	 */
	function save_related_module($module, $crmid, $with_module, $with_crmids) {

		if(!is_array($with_crmids)) $with_crmids = Array($with_crmids);
		foreach($with_crmids as $with_crmid) {
			parent::save_related_module($module, $crmid, $with_module, $with_crmid);
			if ($with_module == 'HelpDesk') {
				$this->updateHelpDeskRelatedTo($crmid,$with_crmid);
				$this->updateServiceContractState($crmid);
			}
		}
	 }

	 // Function to Update the parent_id of HelpDesk with sc_related_to of ServiceContracts if the parent_id is not set.
	 function updateHelpDeskRelatedTo($focusId, $entityIds) {

		if(!is_array($entityIds)) $entityIds = array($entityIds);
		$selectTicketsQuery = "SELECT ticketid FROM ncrm_troubletickets
								WHERE (parent_id IS NULL OR parent_id = 0 OR contact_id IS NULL OR contact_id =0)
									AND ticketid IN (" . generateQuestionMarks($entityIds) .")";$selectTicketsResult = $this->db->pquery($selectTicketsQuery, array($entityIds));
		$noOfTickets = $this->db->num_rows($selectTicketsResult);
		for($i=0; $i < $noOfTickets; ++$i) {
			$ticketId = $this->db->query_result($selectTicketsResult,$i,'ticketid');
			$serviceContractsRelateToTypeResult = $this->db->pquery('SELECT setype FROM ncrm_crmentity WHERE crmid =
				(SELECT sc_related_to FROM ncrm_servicecontracts WHERE servicecontractsid = ?)', array($focusId));
			$serviceContractsRelateToType = $this->db->query_result($serviceContractsRelateToTypeResult, 0, 'setype');
			if($serviceContractsRelateToType == 'Accounts') {
				$updateQuery = "UPDATE ncrm_troubletickets, ncrm_servicecontracts SET parent_id=ncrm_servicecontracts.sc_related_to" .
					" WHERE ncrm_servicecontracts.sc_related_to IS NOT NULL AND ncrm_servicecontracts.sc_related_to != 0" .
					" AND ncrm_servicecontracts.servicecontractsid = ? AND ncrm_troubletickets.ticketid = ?";
				$this->db->pquery($updateQuery, array($focusId, $ticketId));
			} elseif($serviceContractsRelateToType == 'Contacts') {
				$updateQuery = "UPDATE ncrm_troubletickets, ncrm_servicecontracts SET contact_id=ncrm_servicecontracts.sc_related_to" .
					" WHERE ncrm_servicecontracts.sc_related_to IS NOT NULL AND ncrm_servicecontracts.sc_related_to != 0" .
					" AND ncrm_servicecontracts.servicecontractsid = ? AND ncrm_troubletickets.ticketid = ?";
				$this->db->pquery($updateQuery, array($focusId, $ticketId));
			}
		}
	}

	// Function to Compute and Update the Used Units and Progress of the Service Contract based on all the related Trouble tickets.
	function updateServiceContractState($focusId) {
		$this->id = $focusId;
		$this->retrieve_entity_info($focusId,'ServiceContracts');

		$contractTicketsResult = $this->db->pquery("SELECT relcrmid FROM ncrm_crmentityrel
														WHERE module = 'ServiceContracts'
														AND relmodule = 'HelpDesk' AND crmid = ?
													UNION
														SELECT crmid FROM ncrm_crmentityrel
														WHERE relmodule = 'ServiceContracts'
														AND module = 'HelpDesk' AND relcrmid = ?",
													array($focusId,$focusId));

		$noOfTickets = $this->db->num_rows($contractTicketsResult);
		$ticketFocus = CRMEntity::getInstance('HelpDesk');
		$totalUsedUnits = 0;
		for($i=0; $i < $noOfTickets; ++$i) {
			$ticketId = $this->db->query_result($contractTicketsResult, $i, 'relcrmid');
			$ticketFocus->id = $ticketId;
			if(isRecordExists($ticketId)) {
				$ticketFocus->retrieve_entity_info($ticketId, 'HelpDesk');
				if (strtolower($ticketFocus->column_fields['ticketstatus']) == 'closed') {
					$totalUsedUnits += $this->computeUsedUnits($ticketFocus->column_fields);
				}
			}
		}
		$this->updateUsedUnits($totalUsedUnits);

		$this->calculateProgress();
	}

	// Function to Upate the Used Units of the Service Contract based on the given Ticket id.
	function computeUsedUnits($ticketData, $operator='+') {
		$trackingUnit = strtolower($this->column_fields['tracking_unit']);
		$workingHoursPerDay = 24;

		$usedUnits = 0;
		if ($trackingUnit == 'incidents') {
			$usedUnits = 1;
		} elseif ($trackingUnit == 'days') {
			if(!empty($ticketData['days'])) {
				$usedUnits = $ticketData['days'];
			} elseif(!empty($ticketData['hours'])) {
				$usedUnits = $ticketData['hours'] / $workingHoursPerDay;
			}
		} elseif ($trackingUnit == 'hours') {
			if(!empty($ticketData['hours'])) {
				$usedUnits = $ticketData['hours'];
			} elseif(!empty($ticketData['days'])) {
				$usedUnits = $ticketData['days'] * $workingHoursPerDay;
			}
		}
		return $usedUnits;
	}

	// Function to Upate the Used Units of the Service Contract.
	function updateUsedUnits($usedUnits) {
		$this->column_fields['used_units'] = $usedUnits;
		$updateQuery = "UPDATE ncrm_servicecontracts SET used_units = $usedUnits WHERE servicecontractsid = ?";
		$this->db->pquery($updateQuery, array($this->id));
	}

	// Function to Calculate the End Date, Planned Duration, Actual Duration and Progress of a Service Contract
	function calculateProgress() {
		$updateCols = array();
		$updateParams = array();

		$startDate = $this->column_fields['start_date'];
		$dueDate = $this->column_fields['due_date'];
		$endDate = $this->column_fields['end_date'];

		$usedUnits = decimalFormat($this->column_fields['used_units']);
		$totalUnits = decimalFormat($this->column_fields['total_units']);

		$contractStatus = $this->column_fields['contract_status'];

		// Update the End date if the status is Complete or if the Used Units reaches/exceeds Total Units
		// We need to do this first to make sure Actual duration is computed properly
		if($contractStatus == 'Complete' || (!empty($usedUnits) && !empty($totalUnits) && $usedUnits >= $totalUnits)) {
			if(empty($endDate)) {
				$endDate = date('Y-m-d');
				$this->db->pquery('UPDATE ncrm_servicecontracts SET end_date=? WHERE servicecontractsid = ?', array(date('Y-m-d'), $this->id));
			}
		} else {
			$endDate = null;
			$this->db->pquery('UPDATE ncrm_servicecontracts SET end_date=? WHERE servicecontractsid = ?', array(null, $this->id));
		}

		// Calculate the Planned Duration based on Due date and Start date. (in days)
		if(!empty($dueDate) && !empty($startDate)) {
			$plannedDurationUpdate = " planned_duration = (TO_DAYS(due_date)-TO_DAYS(start_date)+1)";
		} else {
			$plannedDurationUpdate = " planned_duration = ''";
		}
		array_push($updateCols, $plannedDurationUpdate);

		// Calculate the Actual Duration based on End date and Start date. (in days)
		if(!empty($endDate) && !empty($startDate)) {
			$actualDurationUpdate = "actual_duration = (TO_DAYS(end_date)-TO_DAYS(start_date)+1)";
		} else {
			$actualDurationUpdate = "actual_duration = ''";
		}
		array_push($updateCols, $actualDurationUpdate);

		// Update the Progress based on Used Units and Total Units (in percentage)
		if(!empty($usedUnits) && !empty($totalUnits)) {
			$progressUpdate = 'progress = ?';
			$progressUpdateParams = floatval(($usedUnits * 100) / $totalUnits);
		} else {
			$progressUpdate = 'progress = ?';
			$progressUpdateParams = null;
		}
		array_push($updateCols, $progressUpdate);
		array_push($updateParams, $progressUpdateParams);

		if(count($updateCols) > 0) {
			$updateQuery = 'UPDATE ncrm_servicecontracts SET '. implode(",", $updateCols) .' WHERE servicecontractsid = ?';
			array_push($updateParams, $this->id);
			$this->db->pquery($updateQuery, $updateParams);
		}
	}

	/**
	 * Handle deleting related module information.
	 * NOTE: This function has been added to CRMEntity (base class).
	 * You can override the behavior by re-defining it here.
	 */
	function delete_related_module($module, $crmid, $with_module, $with_crmid) {
	 	parent::delete_related_module($module, $crmid, $with_module, $with_crmid);
	 	if ($with_module == 'HelpDesk') {
	 		$this->updateServiceContractState($crmid);
	 	}
	}

	/**
	 * Handle getting related list information.
	 * NOTE: This function has been added to CRMEntity (base class).
	 * You can override the behavior by re-defining it here.
	 */
	//function get_related_list($id, $cur_tab_id, $rel_tab_id, $actions=false) { }

	/** Function to unlink an entity with given Id from another entity */
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log, $currentModule;

		if($return_module == 'Accounts') {
			$focus = CRMEntity::getInstance($return_module);
			$entityIds = $focus->getRelatedContactsIds($return_id);
			array_push($entityIds, $return_id);
			$entityIds = implode(',', $entityIds);
			$return_modules = "'Accounts','Contacts'";
		} else {
			$entityIds = $return_id;
			$return_modules = "'".$return_module."'";
		}

		$query = 'DELETE FROM ncrm_crmentityrel WHERE (relcrmid='.$id.' AND module IN ('.$return_modules.') AND crmid IN ('.$entityIds.')) OR (crmid='.$id.' AND relmodule IN ('.$return_modules.') AND relcrmid IN ('.$entityIds.'))';
		$this->db->pquery($query, array());

		$sql = 'SELECT tabid, tablename, columnname FROM ncrm_field WHERE fieldid IN (SELECT fieldid FROM ncrm_fieldmodulerel WHERE module=? AND relmodule IN ('.$return_modules.'))';
		$fieldRes = $this->db->pquery($sql, array($currentModule));
		$numOfFields = $this->db->num_rows($fieldRes);
		for ($i = 0; $i < $numOfFields; $i++) {
			$tabId = $this->db->query_result($fieldRes, $i, 'tabid');
			$tableName = $this->db->query_result($fieldRes, $i, 'tablename');
			$columnName = $this->db->query_result($fieldRes, $i, 'columnname');
			$relatedModule = vtlib_getModuleNameById($tabId);
			$focusObj = CRMEntity::getInstance($relatedModule);

			$updateQuery = "UPDATE $tableName SET $columnName=? WHERE $columnName IN ($entityIds) AND $focusObj->table_index=?";
			$updateParams = array(null, $id);
			$this->db->pquery($updateQuery, $updateParams);
		}
	}

    /**
	 * Move the related records of the specified list of id's to the given record.
	 * @param String This module name
	 * @param Array List of Entity Id's from which related records need to be transfered
	 * @param Integer Id of the the Record to which the related records are to be moved
	 */
	function transferRelatedRecords($module, $transferEntityIds, $entityId) {
		global $adb,$log;
		$log->debug("Entering function transferRelatedRecords ($module, $transferEntityIds, $entityId)");

		$rel_table_arr = Array("Documents"=>"ncrm_senotesrel","Attachments"=>"ncrm_seattachmentsrel");

		$tbl_field_arr = Array("ncrm_senotesrel"=>"notesid","ncrm_seattachmentsrel"=>"attachmentsid");

		$entity_tbl_field_arr = Array("ncrm_senotesrel"=>"crmid","ncrm_seattachmentsrel"=>"crmid");

		foreach($transferEntityIds as $transferId) {
			foreach($rel_table_arr as $rel_module=>$rel_table) {
				$id_field = $tbl_field_arr[$rel_table];
				$entity_id_field = $entity_tbl_field_arr[$rel_table];
				// IN clause to avoid duplicate entries
				$sel_result =  $adb->pquery("select $id_field from $rel_table where $entity_id_field=? " .
						" and $id_field not in (select $id_field from $rel_table where $entity_id_field=?)",
						array($transferId,$entityId));
				$res_cnt = $adb->num_rows($sel_result);
				if($res_cnt > 0) {
					for($i=0;$i<$res_cnt;$i++) {
						$id_field_value = $adb->query_result($sel_result,$i,$id_field);
						$adb->pquery("update $rel_table set $entity_id_field=? where $entity_id_field=? and $id_field=?",
							array($entityId,$transferId,$id_field_value));
					}
				}
			}
		}
		parent::transferRelatedRecords($module, $transferEntityIds, $entityId);
		$log->debug("Exiting transferRelatedRecords...");
	}
}
?>