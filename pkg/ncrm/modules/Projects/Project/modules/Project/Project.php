<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/
class Project extends CRMEntity {
    var $db, $log; // Used in class functions of CRMEntity

    var $table_name = 'ncrm_project';
    var $table_index= 'projectid';
    var $column_fields = Array();

    /** Indicator if this is a custom module or standard module */
    var $IsCustomModule = true;

    /**
     * Mandatory table for supporting custom fields.
     */
    var $customFieldTable = Array('ncrm_projectcf', 'projectid');

    /**
     * Mandatory for Saving, Include tables related to this module.
     */
    var $tab_name = Array('ncrm_crmentity', 'ncrm_project', 'ncrm_projectcf');

    /**
     * Mandatory for Saving, Include tablename and tablekey columnname here.
     */
    var $tab_name_index = Array(
		'ncrm_crmentity' => 'crmid',
		'ncrm_project'   => 'projectid',
	    'ncrm_projectcf' => 'projectid');

    /**
     * Mandatory for Listing (Related listview)
     */
    var $list_fields = Array (
    /* Format: Field Label => Array(tablename, columnname) */
    // tablename should not have prefix 'ncrm_'
		'Project Name'=> Array('project', 'projectname'),
		'Start Date'=> Array('project', 'startdate'),
		'Status'=>Array('project','projectstatus'),
		'Type'=>Array('project','projecttype'),
		'Assigned To' => Array('crmentity','smownerid')
    );
    var $list_fields_name = Array(
    /* Format: Field Label => fieldname */
		'Project Name'=> 'projectname',
		'Start Date'=> 'startdate',
		'Status'=>'projectstatus',
		'Type'=>'projecttype',
		'Assigned To' => 'assigned_user_id'
	);

	// Make the field link to detail view from list view (Fieldname)
	var $list_link_field = 'projectname';

	// For Popup listview and UI type support
	var $search_fields = Array(
	/* Format: Field Label => Array(tablename, columnname) */
	// tablename should not have prefix 'ncrm_'
	'Project Name'=> Array('project', 'projectname'),
	'Start Date'=> Array('project', 'startdate'),
	'Status'=>Array('project','projectstatus'),
	'Type'=>Array('project','projecttype'),
	);
	var $search_fields_name = Array(
	/* Format: Field Label => fieldname */
	'Project Name'=> 'projectname',
	'Start Date'=> 'startdate',
	'Status'=>'projectstatus',
	'Type'=>'projecttype',
	);

	// For Popup window record selection
	var $popup_fields = Array('projectname');

	// Placeholder for sort fields - All the fields will be initialized for Sorting through initSortFields
	var $sortby_fields = Array();

	// For Alphabetical search
	var $def_basicsearch_col = 'projectname';

	// Column value to use on detail view record text display
	var $def_detailview_recname = 'projectname';

	// Required Information for enabling Import feature
	var $required_fields = Array('projectname'=>1);

	// Callback function list during Importing
	var $special_functions = Array('set_import_assigned_user');

	var $default_order_by = 'projectname';
	var $default_sort_order='ASC';
	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to ncrm_field.fieldname values.
	var $mandatory_fields = Array('createdtime', 'modifiedtime', 'projectname', 'assigned_user_id');

	function __construct() {
	    global $log, $currentModule;
	    $this->column_fields = getColumnFields(get_class($this));
	    $this->db = PearDatabase::getInstance();
	    $this->log = $log;
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
	function getListQuery($module, $usewhere='') {
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
		$query .= $this->getNonAdminAccessControlQuery($module,$current_user);
		$query .= "	WHERE ncrm_crmentity.deleted = 0 ".$usewhere;
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
		$sql = getPermittedFieldsQuery('Project', "detail_view");

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

		$query .= $this->getNonAdminAccessControlQuery($thismodule,$current_user);
		$where_auto = " ncrm_crmentity.deleted=0";

		if($where != '') $query .= " WHERE ($where) AND $where_auto";
		else $query .= " WHERE $where_auto";

		return $query;
	}

	/**
	 * Transform the value while exporting
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

	/**
	 * Invoked when special actions are performed on the module.
	 * @param String Module name
	 * @param String Event Type (module.postinstall, module.disabled, module.enabled, module.preuninstall)
	 */
	function vtlib_handler($modulename, $event_type) {
		if($event_type == 'module.postinstall') {
			global $adb;

			include_once('vtlib/Ncrm/Module.php');
			$moduleInstance = Ncrm_Module::getInstance($modulename);
			$projectsResult = $adb->pquery('SELECT tabid FROM ncrm_tab WHERE name=?', array('Project'));
			$projectTabid = $adb->query_result($projectsResult, 0, 'tabid');

			// Mark the module as Standard module
			$adb->pquery('UPDATE ncrm_tab SET customized=0 WHERE name=?', array($modulename));

			// Add module to Customer portal
			if(getTabid('CustomerPortal') && $projectTabid) {
				$checkAlreadyExists = $adb->pquery('SELECT 1 FROM ncrm_customerportal_tabs WHERE tabid=?', array($projectTabid));
				if($checkAlreadyExists && $adb->num_rows($checkAlreadyExists) < 1) {
					$maxSequenceQuery = $adb->query("SELECT max(sequence) as maxsequence FROM ncrm_customerportal_tabs");
					$maxSequence = $adb->query_result($maxSequenceQuery, 0, 'maxsequence');
					$nextSequence = $maxSequence+1;
					$adb->query("INSERT INTO ncrm_customerportal_tabs(tabid,visible,sequence) VALUES ($projectTabid,1,$nextSequence)");
					$adb->query("INSERT INTO ncrm_customerportal_prefs(tabid,prefkey,prefvalue) VALUES ($projectTabid,'showrelatedinfo',1)");
				}
			}

			// Add Gnatt chart to the related list of the module
			$relation_id = $adb->getUniqueID('ncrm_relatedlists');
			$max_sequence = 0;
			$result = $adb->query("SELECT max(sequence) as maxsequence FROM ncrm_relatedlists WHERE tabid=$projectTabid");
			if($adb->num_rows($result)) $max_sequence = $adb->query_result($result, 0, 'maxsequence');
			$sequence = $max_sequence+1;
			$adb->pquery("INSERT INTO ncrm_relatedlists(relation_id,tabid,related_tabid,name,sequence,label,presence) VALUES(?,?,?,?,?,?,?)",
						array($relation_id,$projectTabid,0,'get_gantt_chart',$sequence,'Charts',0));

			// Add Project module to the related list of Accounts module
			$accountsModuleInstance = Ncrm_Module::getInstance('Accounts');
			$accountsModuleInstance->setRelatedList($moduleInstance, 'Projects', Array('ADD','SELECT'), 'get_dependents_list');

			// Add Project module to the related list of Accounts module
			$contactsModuleInstance = Ncrm_Module::getInstance('Contacts');
			$contactsModuleInstance->setRelatedList($moduleInstance, 'Projects', Array('ADD','SELECT'), 'get_dependents_list');

			// Add Project module to the related list of HelpDesk module
			$helpDeskModuleInstance = Ncrm_Module::getInstance('HelpDesk');
			$helpDeskModuleInstance->setRelatedList($moduleInstance, 'Projects', Array('SELECT'), 'get_related_list');

			$modcommentsModuleInstance = Ncrm_Module::getInstance('ModComments');
			if($modcommentsModuleInstance && file_exists('modules/ModComments/ModComments.php')) {
				include_once 'modules/ModComments/ModComments.php';
				if(class_exists('ModComments')) ModComments::addWidgetTo(array('Project'));
			}

			$result = $adb->pquery("SELECT 1 FROM ncrm_modentity_num WHERE semodule = ? AND active = 1", array($modulename));
			if (!($adb->num_rows($result))) {
				//Initialize module sequence for the module
				$adb->pquery("INSERT INTO ncrm_modentity_num values(?,?,?,?,?,?)", array($adb->getUniqueId("ncrm_modentity_num"), $modulename, 'PROJ', 1, 1, 1));
			}

		} else if($event_type == 'module.disabled') {
			// TODO Handle actions when this module is disabled.
		} else if($event_type == 'module.enabled') {
			// TODO Handle actions when this module is enabled.
		} else if($event_type == 'module.preuninstall') {
			// TODO Handle actions when this module is about to be deleted.
		} else if($event_type == 'module.preupdate') {
			// TODO Handle actions before this module is updated.
		} else if($event_type == 'module.postupdate') {
			global $adb;

			$projectsResult = $adb->pquery('SELECT tabid FROM ncrm_tab WHERE name=?', array('Project'));
			$projectTabid = $adb->query_result($projectsResult, 0, 'tabid');

			// Add Gnatt chart to the related list of the module
			$relation_id = $adb->getUniqueID('ncrm_relatedlists');
			$max_sequence = 0;
			$result = $adb->query("SELECT max(sequence) as maxsequence FROM ncrm_relatedlists WHERE tabid=$projectTabid");
			if($adb->num_rows($result)) $max_sequence = $adb->query_result($result, 0, 'maxsequence');
			$sequence = $max_sequence+1;
			$adb->pquery("INSERT INTO ncrm_relatedlists(relation_id,tabid,related_tabid,name,sequence,label,presence) VALUES(?,?,?,?,?,?,?)",
						array($relation_id,$projectTabid,0,'get_gantt_chart',$sequence,'Charts',0));

			// Add Comments widget to Project module
			$modcommentsModuleInstance = Ncrm_Module::getInstance('ModComments');
			if($modcommentsModuleInstance && file_exists('modules/ModComments/ModComments.php')) {
				include_once 'modules/ModComments/ModComments.php';
				if(class_exists('ModComments')) ModComments::addWidgetTo(array('Project'));
			}

			$result = $adb->pquery("SELECT 1 FROM ncrm_modentity_num WHERE semodule = ? AND active = 1", array($modulename));
			if (!($adb->num_rows($result))) {
				//Initialize module sequence for the module
				$adb->pquery("INSERT INTO ncrm_modentity_num values(?,?,?,?,?,?)", array($adb->getUniqueId("ncrm_modentity_num"), $modulename, 'PROJ', 1, 1, 1));
			}
		}
	}

	static function registerLinks() {

	}

    /**
     * Here we override the parent's method,
     * This is done because the related lists for this module use a custom query
     * that queries the child module's table (column of the uitype10 field)
     *
     * @see data/CRMEntity#save_related_module($module, $crmid, $with_module, $with_crmid)
     */
    //function save_related_module($module, $crmid, $with_module, $with_crmid) {    }

    /**
     * Here we override the parent's method
     * This is done because the related lists for this module use a custom query
     * that queries the child module's table (column of the uitype10 field)
     *
     * @see data/CRMEntity#delete_related_module($module, $crmid, $with_module, $with_crmid)
     */
    function delete_related_module($module, $crmid, $with_module, $with_crmid) {
         if (!in_array($with_module, array('ProjectMilestone', 'ProjectTask'))) {
             parent::delete_related_module($module, $crmid, $with_module, $with_crmid);
             return;
         }
        $destinationModule = vtlib_purify($_REQUEST['destination_module']);
		if(empty($destinationModule)) $destinationModule = $with_module;
        if (!is_array($with_crmid)) $with_crmid = Array($with_crmid);
        foreach($with_crmid as $relcrmid) {
            $child = CRMEntity::getInstance($destinationModule);
            $child->retrieve_entity_info($relcrmid, $destinationModule);
            $child->mode='edit';
            $child->column_fields['projectid']='';
            $child->save($destinationModule,$relcrmid);
        }
    }

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


	function get_gantt_chart($id, $cur_tab_id, $rel_tab_id, $actions=false){
		require_once("BURAK_Gantt.class.php");

		$headers = array();
		$headers[0] = getTranslatedString('LBL_PROGRESS_CHART');

		$entries = array();

        global $adb,$tmp_dir,$default_charset;
        $record = $id;
		$g = new BURAK_Gantt();
		// set grid type
		$g->setGrid(1);
		// set Gantt colors
		$g->setColor("group","000000");
		$g->setColor("progress","660000");

		$related_projecttasks = $adb->pquery("SELECT pt.* FROM ncrm_projecttask AS pt
												INNER JOIN ncrm_crmentity AS crment ON pt.projecttaskid=crment.crmid
												WHERE projectid=? AND crment.deleted=0 AND pt.startdate IS NOT NULL AND pt.enddate IS NOT NULL",
										array($record)) or die("Please install the ProjectMilestone and ProjectTasks modules first.");

		while($rec_related_projecttasks = $adb->fetchByAssoc($related_projecttasks)){

			if($rec_related_projecttasks['projecttaskprogress']=="--none--"){
				$percentage = 0;
			} else {
				$percentage = str_replace("%","",$rec_related_projecttasks['projecttaskprogress']);
			}

            $rec_related_projecttasks['projecttaskname'] = iconv($default_charset, "ISO-8859-2//TRANSLIT",$rec_related_projecttasks['projecttaskname']);
			$g->addTask($rec_related_projecttasks['projecttaskid'],$rec_related_projecttasks['startdate'],$rec_related_projecttasks['enddate'],$percentage,$rec_related_projecttasks['projecttaskname']);
		}


		$related_projectmilestones = $adb->pquery("SELECT pm.* FROM ncrm_projectmilestone AS pm
													INNER JOIN ncrm_crmentity AS crment on pm.projectmilestoneid=crment.crmid
													WHERE projectid=? and crment.deleted=0",
											array($record)) or die("Please install the ProjectMilestone and ProjectTasks modules first.");

		while($rec_related_projectmilestones = $adb->fetchByAssoc($related_projectmilestones)){
            $rec_related_projectmilestones['projectmilestonename'] = iconv($default_charset, "ISO-8859-2//TRANSLIT",$rec_related_projectmilestones['projectmilestonename']);
            $g->addMilestone($rec_related_projectmilestones['projectmilestoneid'],$rec_related_projectmilestones['projectmilestonedate'],$rec_related_projectmilestones['projectmilestonename']);
		}

		$g->outputGantt($tmp_dir."diagram_".$record.".jpg","100");

		$origin = $tmp_dir."diagram_".$record.".jpg";
		$destination = $tmp_dir."pic_diagram_".$record.".jpg";

		$imagesize = getimagesize($origin);
		$actualWidth = $imagesize[0];
		$actualHeight = $imagesize[1];

		$size = 1000;
		if($actualWidth > $size){
			$width = $size;
			$height = ($actualHeight * $size) / $actualWidth;
			copy($origin,$destination);
			$id_origin = imagecreatefromjpeg($destination);
			$id_destination = imagecreate($width, $height);
			imagecopyresized($id_destination, $id_origin, 0, 0, 0, 0, $width, $height, $actualWidth, $actualHeight);
			imagejpeg($id_destination,$destination);
			imagedestroy($id_origin);
			imagedestroy($id_destination);

			$image = $destination;
		} else {
			$image = $origin;
		}

		$fullGanttChartImageUrl = $tmp_dir."diagram_".$record.".jpg";
		$thumbGanttChartImageUrl = $image;
		$entries[0] = array("<a href='$fullGanttChartImageUrl' border='0' target='_blank'><img src='$thumbGanttChartImageUrl' border='0'></a>");

		return array('header'=> $headers, 'entries'=> $entries);
	}

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

		$rel_table_arr = Array("ProjectTask"=>"ncrm_projecttask",'ProjectMilestone'=>'ncrm_projectmilestone',
                                "Documents"=>"ncrm_senotesrel","Attachments"=>"ncrm_seattachmentsrel");

		$tbl_field_arr = Array("ncrm_projecttask"=>"projecttaskid",'ncrm_projectmilestone'=>'projectmilestoneid',
                                "ncrm_senotesrel"=>"notesid","ncrm_seattachmentsrel"=>"attachmentsid");

		$entity_tbl_field_arr = Array("ncrm_projecttask"=>"projectid",'ncrm_projectmilestone'=>'projectid',
                                    "ncrm_senotesrel"=>"crmid","ncrm_seattachmentsrel"=>"crmid");

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
