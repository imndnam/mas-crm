<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/
require_once('data/CRMEntity.php');
require_once('data/Tracker.php');
require_once 'vtlib/Ncrm/Module.php';

class ModCommentsCore extends CRMEntity {
	var $db, $log; // Used in class functions of CRMEntity

	var $table_name = 'ncrm_modcomments';
	var $table_index= 'modcommentsid';

	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('ncrm_modcommentscf', 'modcommentsid');

	/**
	 * Mandatory for Saving, Include tables related to this module.
	 */
	var $tab_name = Array('ncrm_crmentity', 'ncrm_modcomments', 'ncrm_modcommentscf');

	/**
	 * Mandatory for Saving, Include tablename and tablekey columnname here.
	 */
	var $tab_name_index = Array(
		'ncrm_crmentity' => 'crmid',
		'ncrm_modcomments' => 'modcommentsid',
		'ncrm_modcommentscf'=>'modcommentsid');

	/**
	 * Mandatory for Listing (Related listview)
	 */
	var $list_fields = Array (
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'ncrm_'
		'Comment' => Array('modcomments', 'commentcontent'),
		'Assigned To' => Array('crmentity','smownerid')
	);
	var $list_fields_name = Array (
		/* Format: Field Label => fieldname */
		'Comment' => 'commentcontent',
		'Assigned To' => 'assigned_user_id'
	);

	// Make the field link to detail view
	var $list_link_field = 'commentcontent';

	// For Popup listview and UI type support
	var $search_fields = Array(
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'ncrm_'
		'Comment' => Array('modcomments', 'commentcontent')
	);
	var $search_fields_name = Array (
		/* Format: Field Label => fieldname */
		'Comment' => 'commentcontent'
	);

	// For Popup window record selection
	var $popup_fields = Array ('commentcontent');

	// Allow sorting on the following (field column names)
	var $sortby_fields = Array ('commentcontent');

	// Should contain field labels
	//var $detailview_links = Array ('Comment');

	// For Alphabetical search
	var $def_basicsearch_col = 'commentcontent';

	// Column value to use on detail view record text display
	var $def_detailview_recname = 'commentcontent';

	// Required Information for enabling Import feature
	var $required_fields = Array ('assigned_user_id'=>1);

	// Callback function list during Importing
	var $special_functions = Array('set_import_assigned_user');

	var $default_order_by = 'modcommentsid';
	var $default_sort_order='DESC';

	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to ncrm_field.fieldname values.
	var $mandatory_fields = Array('createdtime', 'modifiedtime', 'commentcontent');

	function __construct() {
		global $log, $currentModule;
		$this->column_fields = getColumnFields('ModComments');
		$this->db = PearDatabase::getInstance();
		$this->log = $log;
	}

	function getSortOrder() {
		global $currentModule;

		$sortorder = $this->default_sort_order;
		if($_REQUEST['sorder']) $sortorder = $this->db->sql_escape_string($_REQUEST['sorder']);
		else if($_SESSION[$currentModule.'_Sort_Order'])
			$sortorder = $_SESSION[$currentModule.'_Sort_Order'];

		return $sortorder;
	}

	function getOrderBy() {
		global $currentModule;

		$use_default_order_by = '';
		if(PerformancePrefs::getBoolean('LISTVIEW_DEFAULT_SORTING', true)) {
			$use_default_order_by = $this->default_order_by;
		}

		$orderby = $use_default_order_by;
		if($_REQUEST['order_by']) $orderby = $this->db->sql_escape_string($_REQUEST['order_by']);
		else if($_SESSION[$currentModule.'_Order_By'])
			$orderby = $_SESSION[$currentModule.'_Order_By'];
		return $orderby;
	}

	function save_module($module) {
	}

	/**
	 * Return query to use based on given modulename, fieldname
	 * Useful to handle specific case handling for Popup
	 */
	function getQueryByModuleField($module, $fieldname, $srcrecord) {
		// $srcrecord could be empty
	}

	/**
	 * Get list view query (send more WHERE clause condition if required)
	 */
	function getListQuery($module, $usewhere=false) {
		$query = "SELECT ncrm_crmentity.*, $this->table_name.*";

		// Keep track of tables joined to avoid duplicates
		$joinedTables = array();

		// Select Custom Field Table Columns if present
		if(!empty($this->customFieldTable)) $query .= ", " . $this->customFieldTable[0] . ".* ";

		$query .= " FROM $this->table_name";

		$query .= "	INNER JOIN ncrm_crmentity ON ncrm_crmentity.crmid = $this->table_name.$this->table_index";

		$joinedTables[] = $this->table_name;
		$joinedTables[] = 'ncrm_crmentity';

		// Consider custom table join as well.
		if(!empty($this->customFieldTable)) {
			$query .= " INNER JOIN ".$this->customFieldTable[0]." ON ".$this->customFieldTable[0].'.'.$this->customFieldTable[1] .
				      " = $this->table_name.$this->table_index";
			$joinedTables[] = $this->customFieldTable[0];
		}
		$query .= " LEFT JOIN ncrm_users ON ncrm_users.id = ncrm_crmentity.smownerid";
		$query .= " LEFT JOIN ncrm_groups ON ncrm_groups.groupid = ncrm_crmentity.smownerid";

		$joinedTables[] = 'ncrm_users';
		$joinedTables[] = 'ncrm_groups';

		$linkedModulesQuery = $this->db->pquery("SELECT distinct fieldname, columnname, relmodule FROM ncrm_field" .
				" INNER JOIN ncrm_fieldmodulerel ON ncrm_fieldmodulerel.fieldid = ncrm_field.fieldid" .
				" WHERE uitype='10' AND ncrm_fieldmodulerel.module=?", array($module));
		$linkedFieldsCount = $this->db->num_rows($linkedModulesQuery);

		for($i=0; $i<$linkedFieldsCount; $i++) {
			$related_module = $this->db->query_result($linkedModulesQuery, $i, 'relmodule');
			$fieldname = $this->db->query_result($linkedModulesQuery, $i, 'fieldname');
			$columnname = $this->db->query_result($linkedModulesQuery, $i, 'columnname');

			$other =  CRMEntity::getInstance($related_module);
			vtlib_setup_modulevars($related_module, $other);

			if(!in_array($other->table_name, $joinedTables)) {
				$query .= " LEFT JOIN $other->table_name ON $other->table_name.$other->table_index = $this->table_name.$columnname";
				$joinedTables[] = $other->table_name;
			}
		}

                global $current_user; 
                $query .= getNonAdminAccessControlQuery($module, $current_user); 
		$query .= "	WHERE ncrm_crmentity.deleted = 0 ";
		if($usewhere) {
			$query .= $usewhere;
		}
		$query .= $this->getListViewSecurityParameter($module);
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

	/**
	 * Create query to export the records.
	 */
	function create_export_query($where)
	{
		global $current_user;

		include("include/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery('ModComments', "detail_view");

		$fields_list = getFieldsListFromQuery($sql);

		$query = "SELECT $fields_list, ncrm_users.user_name AS user_name
					FROM ncrm_crmentity INNER JOIN $this->table_name ON ncrm_crmentity.crmid=$this->table_name.$this->table_index";

		if(!empty($this->customFieldTable)) {
			$query .= " INNER JOIN ".$this->customFieldTable[0]." ON ".$this->customFieldTable[0].'.'.$this->customFieldTable[1] .
				      " = $this->table_name.$this->table_index";
		}

		$query .= " LEFT JOIN ncrm_groups ON ncrm_groups.groupid = ncrm_crmentity.smownerid";
		$query .= " LEFT JOIN ncrm_users ON ncrm_crmentity.smownerid = ncrm_users.id and ncrm_users.status='Active'";

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

			$query .= " LEFT JOIN $other->table_name ON $other->table_name.$other->table_index = $this->table_name.$columnname";
		}

		$where_auto = " ncrm_crmentity.deleted=0";

		if($where != '') $query .= " WHERE ($where) AND $where_auto";
		else $query .= " WHERE $where_auto";

		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		require('user_privileges/sharing_privileges_'.$current_user->id.'.php');

		// Security Check for Field Access
		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[7] == 3)
		{
			//Added security check to get the permitted records only
			$query = $query." ".getListViewSecurityParameter($thismodule);
		}
		return $query;
	}

	/**
	 * Transform the value while exporting (if required)
	 */
	function transform_export_value($key, $value) {
		return parent::transform_export_value($key, $value);
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
				$sub_query .= " LEFT JOIN ".$this->customFieldTable[0]." tcf ON tcf.".$this->customFieldTable[1]." = t.$this->table_index";
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
	
	/*
	 * Function to get the secondary query part of a report
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule,$queryplanner){
		$matrix = $queryplanner->newDependencyMatrix();
		
		$matrix->setDependency('ncrm_crmentityModComments',array('ncrm_groupsModComments','ncrm_usersModComments', 'ncrm_contactdetailsRelModComments', 'ncrm_modcommentsRelModComments'));
		$matrix->setDependency('ncrm_modcomments', array('ncrm_crmentityModComments'));

		if (!$queryplanner->requireTable("ncrm_modcomments",$matrix)){
			return '';
		}

		$query = $this->getRelationQuery($module,$secmodule,"ncrm_modcomments","modcommentsid", $queryplanner);

		if ($queryplanner->requireTable("ncrm_crmentityModComments",$matrix)){
			$query .= " left join ncrm_crmentity as ncrm_crmentityModComments on ncrm_crmentityModComments.crmid=ncrm_modcomments.modcommentsid and ncrm_crmentityModComments.deleted=0";
		}
		if ($queryplanner->requireTable("ncrm_groupsModComments")){
			$query .= " left join ncrm_groups ncrm_groupsModComments on ncrm_groupsModComments.groupid = ncrm_crmentityModComments.smownerid";
		}
		if ($queryplanner->requireTable("ncrm_usersModComments")){
			$query .= " left join ncrm_users as ncrm_usersModComments on ncrm_usersModComments.id = ncrm_crmentityModComments.smownerid";
		}
		if ($queryplanner->requireTable("ncrm_contactdetailsRelModComments")){
			$query .= " left join ncrm_contactdetails as ncrm_contactdetailsRelModComments on ncrm_contactdetailsRelModComments.contactid = ncrm_crmentityModComments.crmid";
		}
		if ($queryplanner->requireTable("ncrm_modcommentsRelModComments")){
			$query .= " left join ncrm_modcomments as ncrm_modcommentsRelModComments on ncrm_modcommentsRelModComments.modcommentsid = ncrm_crmentityModComments.crmid";
		}
		return $query;
	}
	
	/**
	 * Invoked when special actions are performed on the module.
	 * @param String Module name
	 * @param String Event Type (module.postinstall, module.disabled, module.enabled, module.preuninstall)
	 */
	function vtlib_handler($modulename, $event_type) {
		if($event_type == 'module.postinstall') {
			// TODO Handle post installation actions
		} else if($event_type == 'module.disabled') {
			// TODO Handle actions when this module is disabled.
		} else if($event_type == 'module.enabled') {
			// TODO Handle actions when this module is enabled.
		} else if($event_type == 'module.preuninstall') {
			// TODO Handle actions when this module is about to be deleted.
		} else if($event_type == 'module.preupdate') {
			// TODO Handle actions before this module is updated.
		} else if($event_type == 'module.postupdate') {
			// TODO Handle actions after this module is updated.
		}
	}

	/**
	 * Handle saving related module information.
	 * NOTE: This function has been added to CRMEntity (base class).
	 * You can override the behavior by re-defining it here.
	 */
	// function save_related_module($module, $crmid, $with_module, $with_crmid) { }

	/**
	 * Handle deleting related module information.
	 * NOTE: This function has been added to CRMEntity (base class).
	 * You can override the behavior by re-defining it here.
	 */
	//function delete_related_module($module, $crmid, $with_module, $with_crmid) { }

	/**
	 * Handle getting related list information.
	 * NOTE: This function has been added to CRMEntity (base class).
	 * You can override the behavior by re-defining it here.
	 */
	//function get_related_list($id, $cur_tab_id, $rel_tab_id, $actions=false) { }

	/**
	 * Handle getting dependents list information.
	 * NOTE: This function has been added to CRMEntity (base class).
	 * You can override the behavior by re-defining it here.
	 */
	//function get_dependents_list($id, $cur_tab_id, $rel_tab_id, $actions=false) { }
}
?>
