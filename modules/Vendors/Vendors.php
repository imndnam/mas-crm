<?php
/*********************************************************************************
** The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
*
 ********************************************************************************/
class Vendors extends CRMEntity {
	var $log;
	var $db;
	var $table_name = "ncrm_vendor";
	var $table_index= 'vendorid';
	var $tab_name = Array('ncrm_crmentity','ncrm_vendor','ncrm_vendorcf');
	var $tab_name_index = Array('ncrm_crmentity'=>'crmid','ncrm_vendor'=>'vendorid','ncrm_vendorcf'=>'vendorid');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('ncrm_vendorcf', 'vendorid');
	var $column_fields = Array();

        //Pavani: Assign value to entity_table
        var $entity_table = "ncrm_crmentity";
        var $sortby_fields = Array('vendorname','category');

        // This is the list of ncrm_fields that are in the lists.
	var $list_fields = Array(
                                'Vendor Name'=>Array('vendor'=>'vendorname'),
                                'Phone'=>Array('vendor'=>'phone'),
                                'Email'=>Array('vendor'=>'email'),
                                'Category'=>Array('vendor'=>'category')
                                );
        var $list_fields_name = Array(
                                        'Vendor Name'=>'vendorname',
                                        'Phone'=>'phone',
                                        'Email'=>'email',
                                        'Category'=>'category'
                                     );
        var $list_link_field= 'vendorname';

	var $search_fields = Array(
                                'Vendor Name'=>Array('vendor'=>'vendorname'),
                                'Phone'=>Array('vendor'=>'phone')
                                );
        var $search_fields_name = Array(
                                        'Vendor Name'=>'vendorname',
                                        'Phone'=>'phone'
                                     );
	//Specifying required fields for vendors
        var $required_fields =  array();

	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to ncrm_field.fieldname values.
	var $mandatory_fields = Array('createdtime', 'modifiedtime', 'vendorname', 'assigned_user_id');

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'vendorname';
	var $default_sort_order = 'ASC';

	// For Alphabetical search
	var $def_basicsearch_col = 'vendorname';

	/**	Constructor which will set the column_fields in this object
	 */
	function Vendors() {
		$this->log =LoggerManager::getLogger('vendor');
		$this->log->debug("Entering Vendors() method ...");
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Vendors');
		$this->log->debug("Exiting Vendor method ...");
	}

	function save_module($module)
	{
	}

	/**	function used to get the list of products which are related to the vendor
	 *	@param int $id - vendor id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_products($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_products(".$id.") method ...");
		$this_module = $currentModule;

        $related_module = vtlib_getModuleNameById($rel_tab_id);
		checkFileAccessForInclusion("modules/$related_module/$related_module.php");
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
        vtlib_setup_modulevars($related_module, $other);
		$singular_modname = vtlib_toSingular($related_module);

		$parenttab = getParentTab();

		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

		$button = '';

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\";this.form.parent_id.value=\"\";' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>";
			}
		}

		$query = "SELECT ncrm_products.productid, ncrm_products.productname, ncrm_products.productcode,
					ncrm_products.commissionrate, ncrm_products.qty_per_unit, ncrm_products.unit_price,
					ncrm_crmentity.crmid, ncrm_crmentity.smownerid,ncrm_vendor.vendorname
			  		FROM ncrm_products
			  		INNER JOIN ncrm_vendor ON ncrm_vendor.vendorid = ncrm_products.vendor_id
			  		INNER JOIN ncrm_crmentity ON ncrm_crmentity.crmid = ncrm_products.productid INNER JOIN ncrm_productcf
				    ON ncrm_products.productid = ncrm_productcf.productid
					LEFT JOIN ncrm_users
						ON ncrm_users.id=ncrm_crmentity.smownerid
					LEFT JOIN ncrm_groups
						ON ncrm_groups.groupid = ncrm_crmentity.smownerid
			  		WHERE ncrm_crmentity.deleted = 0 AND ncrm_vendor.vendorid = $id";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_products method ...");
		return $return_value;
	}

	/**	function used to get the list of purchase orders which are related to the vendor
	 *	@param int $id - vendor id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_purchase_orders($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_purchase_orders(".$id.") method ...");
		$this_module = $currentModule;

        $related_module = vtlib_getModuleNameById($rel_tab_id);
		checkFileAccessForInclusion("modules/$related_module/$related_module.php");
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
        vtlib_setup_modulevars($related_module, $other);
		$singular_modname = vtlib_toSingular($related_module);

		$parenttab = getParentTab();

		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

		$button = '';

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>";
			}
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'ncrm_users.first_name', 'last_name' => 'ncrm_users.last_name'), 'Users');
		$query = "select case when (ncrm_users.user_name not like '') then $userNameSql else ncrm_groups.groupname end as user_name,ncrm_crmentity.*, ncrm_purchaseorder.*,ncrm_vendor.vendorname from ncrm_purchaseorder inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_purchaseorder.purchaseorderid left outer join ncrm_vendor on ncrm_purchaseorder.vendorid=ncrm_vendor.vendorid LEFT JOIN ncrm_purchaseordercf ON ncrm_purchaseordercf.purchaseorderid = ncrm_purchaseorder.purchaseorderid LEFT JOIN ncrm_pobillads ON ncrm_pobillads.pobilladdressid = ncrm_purchaseorder.purchaseorderid LEFT JOIN ncrm_poshipads ON ncrm_poshipads.poshipaddressid = ncrm_purchaseorder.purchaseorderid  left join ncrm_groups on ncrm_groups.groupid=ncrm_crmentity.smownerid left join ncrm_users on ncrm_users.id=ncrm_crmentity.smownerid where ncrm_crmentity.deleted=0 and ncrm_purchaseorder.vendorid=".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_purchase_orders method ...");
		return $return_value;
	}
	//Pavani: Function to create, export query for vendors module
        /** Function to export the vendors in CSV Format
        * @param reference variable - where condition is passed when the query is executed
        * Returns Export Vendors Query.
        */
        function create_export_query($where)
        {
                global $log;
                global $current_user;
                $log->debug("Entering create_export_query(".$where.") method ...");

                include("include/utils/ExportUtils.php");

                //To get the Permitted fields query and the permitted fields list
                $sql = getPermittedFieldsQuery("Vendors", "detail_view");
                $fields_list = getFieldsListFromQuery($sql);

                $query = "SELECT $fields_list FROM ".$this->entity_table."
                                INNER JOIN ncrm_vendor
                                        ON ncrm_crmentity.crmid = ncrm_vendor.vendorid
                                LEFT JOIN ncrm_vendorcf
                                        ON ncrm_vendorcf.vendorid=ncrm_vendor.vendorid
                                LEFT JOIN ncrm_seattachmentsrel
                                        ON ncrm_vendor.vendorid=ncrm_seattachmentsrel.crmid
                                LEFT JOIN ncrm_attachments
                                ON ncrm_seattachmentsrel.attachmentsid = ncrm_attachments.attachmentsid
                                LEFT JOIN ncrm_users
                                        ON ncrm_crmentity.smownerid = ncrm_users.id and ncrm_users.status='Active'
                                ";
                $where_auto = " ncrm_crmentity.deleted = 0 ";

                 if($where != "")
                   $query .= "  WHERE ($where) AND ".$where_auto;
                else
                   $query .= "  WHERE ".$where_auto;

                $log->debug("Exiting create_export_query method ...");
                return $query;
        }

	/**	function used to get the list of contacts which are related to the vendor
	 *	@param int $id - vendor id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_contacts($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_contacts(".$id.") method ...");
		$this_module = $currentModule;

        $related_module = vtlib_getModuleNameById($rel_tab_id);
		checkFileAccessForInclusion("modules/$related_module/$related_module.php");
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
        vtlib_setup_modulevars($related_module, $other);
		$singular_modname = vtlib_toSingular($related_module);

		$parenttab = getParentTab();

		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

		$button = '';

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'ncrm_users.first_name', 'last_name' => 'ncrm_users.last_name'), 'Users');
		$query = "SELECT case when (ncrm_users.user_name not like '') then $userNameSql else ncrm_groups.groupname end as user_name,ncrm_contactdetails.*, ncrm_crmentity.crmid, ncrm_crmentity.smownerid,ncrm_vendorcontactrel.vendorid,ncrm_account.accountname from ncrm_contactdetails
				inner join ncrm_crmentity on ncrm_crmentity.crmid = ncrm_contactdetails.contactid
				inner join ncrm_vendorcontactrel on ncrm_vendorcontactrel.contactid=ncrm_contactdetails.contactid
				INNER JOIN ncrm_contactaddress ON ncrm_contactdetails.contactid = ncrm_contactaddress.contactaddressid
				INNER JOIN ncrm_contactsubdetails ON ncrm_contactdetails.contactid = ncrm_contactsubdetails.contactsubscriptionid
				INNER JOIN ncrm_customerdetails ON ncrm_contactdetails.contactid = ncrm_customerdetails.customerid
				INNER JOIN ncrm_contactscf ON ncrm_contactdetails.contactid = ncrm_contactscf.contactid
				left join ncrm_groups on ncrm_groups.groupid=ncrm_crmentity.smownerid
				left join ncrm_account on ncrm_account.accountid = ncrm_contactdetails.accountid
				left join ncrm_users on ncrm_users.id=ncrm_crmentity.smownerid
				where ncrm_crmentity.deleted=0 and ncrm_vendorcontactrel.vendorid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_contacts method ...");
		return $return_value;
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

		$rel_table_arr = Array("Products"=>"ncrm_products","PurchaseOrder"=>"ncrm_purchaseorder","Contacts"=>"ncrm_vendorcontactrel");

		$tbl_field_arr = Array("ncrm_products"=>"productid","ncrm_vendorcontactrel"=>"contactid","ncrm_purchaseorder"=>"purchaseorderid");

		$entity_tbl_field_arr = Array("ncrm_products"=>"vendor_id","ncrm_vendorcontactrel"=>"vendorid","ncrm_purchaseorder"=>"vendorid");

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
		$log->debug("Exiting transferRelatedRecords...");
	}

	/** Returns a list of the associated emails
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_emails($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_emails(".$id.") method ...");
		$this_module = $currentModule;

        $related_module = vtlib_getModuleNameById($rel_tab_id);
		checkFileAccessForInclusion("modules/$related_module/$related_module.php");
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
        vtlib_setup_modulevars($related_module, $other);
		$singular_modname = vtlib_toSingular($related_module);

		$parenttab = getParentTab();

		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

		$button = '';

		$button .= '<input type="hidden" name="email_directing_module"><input type="hidden" name="record">';

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='". getTranslatedString('LBL_ADD_NEW')." ". getTranslatedString($singular_modname)."' accessyKey='F' class='crmbutton small create' onclick='fnvshobj(this,\"sendmail_cont\");sendmail(\"$this_module\",$id);' type='button' name='button' value='". getTranslatedString('LBL_ADD_NEW')." ". getTranslatedString($singular_modname)."'></td>";
			}
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'ncrm_users.first_name', 'last_name' => 'ncrm_users.last_name'), 'Users');
		$query = "SELECT case when (ncrm_users.user_name not like '') then $userNameSql else ncrm_groups.groupname end as user_name,
			ncrm_activity.activityid, ncrm_activity.subject,
			ncrm_activity.activitytype, ncrm_crmentity.modifiedtime,
			ncrm_crmentity.crmid, ncrm_crmentity.smownerid, ncrm_activity.date_start,ncrm_activity.time_start, ncrm_seactivityrel.crmid as parent_id
			FROM ncrm_activity, ncrm_seactivityrel, ncrm_vendor, ncrm_users, ncrm_crmentity
			LEFT JOIN ncrm_groups
				ON ncrm_groups.groupid=ncrm_crmentity.smownerid
			WHERE ncrm_seactivityrel.activityid = ncrm_activity.activityid
				AND ncrm_vendor.vendorid = ncrm_seactivityrel.crmid
				AND ncrm_users.id=ncrm_crmentity.smownerid
				AND ncrm_crmentity.crmid = ncrm_activity.activityid
				AND ncrm_vendor.vendorid = ".$id."
				AND ncrm_activity.activitytype='Emails'
				AND ncrm_crmentity.deleted = 0";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_emails method ...");
		return $return_value;
	}

	/*
	 * Function to get the primary query part of a report
	 * @param - $module Primary module name
	 * returns the query string formed on fetching the related data for report for primary module
	 */
	function generateReportsQuery($module, $queryPlanner) {
		$moduletable = $this->table_name;
		$moduleindex = $this->table_index;
		$modulecftable = $this->tab_name[2];
		$modulecfindex = $this->tab_name_index[$modulecftable];

		$query = "from $moduletable
			inner join $modulecftable as $modulecftable on $modulecftable.$modulecfindex=$moduletable.$moduleindex
			inner join ncrm_crmentity on ncrm_crmentity.crmid=$moduletable.$moduleindex
			left join ncrm_groups as ncrm_groups$module on ncrm_groups$module.groupid = ncrm_crmentity.smownerid
			left join ncrm_users as ncrm_users".$module." on ncrm_users".$module.".id = ncrm_crmentity.smownerid
			left join ncrm_groups on ncrm_groups.groupid = ncrm_crmentity.smownerid
			left join ncrm_users on ncrm_users.id = ncrm_crmentity.smownerid
			left join ncrm_users as ncrm_lastModifiedByVendors on ncrm_lastModifiedByVendors.id = ncrm_crmentity.modifiedby ";
		return $query;
	}

	/*
	 * Function to get the secondary query part of a report
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule, $queryplanner) {

		$matrix = $queryplanner->newDependencyMatrix();

		$matrix->setDependency("ncrm_crmentityVendors",array("ncrm_usersVendors","ncrm_lastModifiedByVendors"));
		$matrix->setDependency("ncrm_vendor",array("ncrm_crmentityVendors","ncrm_vendorcf","ncrm_email_trackVendors"));
		if (!$queryplanner->requireTable('ncrm_vendor', $matrix)) {
			return '';
		}
		$query = $this->getRelationQuery($module,$secmodule,"ncrm_vendor","vendorid", $queryplanner);
		// TODO Support query planner
		if ($queryplanner->requireTable("ncrm_crmentityVendors",$matrix)){
		    $query .=" left join ncrm_crmentity as ncrm_crmentityVendors on ncrm_crmentityVendors.crmid=ncrm_vendor.vendorid and ncrm_crmentityVendors.deleted=0";
		}
		if ($queryplanner->requireTable("ncrm_vendorcf")){
		    $query .=" left join ncrm_vendorcf on ncrm_vendorcf.vendorid = ncrm_crmentityVendors.crmid";
		}
		if ($queryplanner->requireTable("ncrm_email_trackVendors")){
		    $query .=" LEFT JOIN ncrm_email_track AS ncrm_email_trackVendors ON ncrm_email_trackVendors.crmid = ncrm_vendor.vendorid";
		}
		if ($queryplanner->requireTable("ncrm_usersVendors")){
		    $query .=" left join ncrm_users as ncrm_usersVendors on ncrm_usersVendors.id = ncrm_crmentityVendors.smownerid";
		}
		if ($queryplanner->requireTable("ncrm_lastModifiedByVendors")){
		    $query .=" left join ncrm_users as ncrm_lastModifiedByVendors on ncrm_lastModifiedByVendors.id = ncrm_crmentityVendors.modifiedby ";
		}
        if ($queryplanner->requireTable("ncrm_createdbyVendors")){
			$query .= " left join ncrm_users as ncrm_createdbyVendors on ncrm_createdbyVendors.id = ncrm_crmentityVendors.smcreatorid ";
		}
		return $query;
	}

	/*
	 * Function to get the relation tables for related modules
	 * @param - $secmodule secondary module name
	 * returns the array with table names and fieldnames storing relations between module and this module
	 */
	function setRelationTables($secmodule){
		$rel_tables = array (
			"Products" =>array("ncrm_products"=>array("vendor_id","productid"),"ncrm_vendor"=>"vendorid"),
			"PurchaseOrder" =>array("ncrm_purchaseorder"=>array("vendorid","purchaseorderid"),"ncrm_vendor"=>"vendorid"),
			"Contacts" =>array("ncrm_vendorcontactrel"=>array("vendorid","contactid"),"ncrm_vendor"=>"vendorid"),
			"Emails" => array("ncrm_seactivityrel"=>array("crmid","activityid"),"ncrm_vendor"=>"vendorid"),
		);
		return $rel_tables[$secmodule];
	}

	// Function to unlink all the dependent entities of the given Entity by Id
	function unlinkDependencies($module, $id) {
		global $log;
		//Deleting Vendor related PO.
		$po_q = 'SELECT ncrm_crmentity.crmid FROM ncrm_crmentity
			INNER JOIN ncrm_purchaseorder ON ncrm_crmentity.crmid=ncrm_purchaseorder.purchaseorderid
			INNER JOIN ncrm_vendor ON ncrm_vendor.vendorid=ncrm_purchaseorder.vendorid
			WHERE ncrm_crmentity.deleted=0 AND ncrm_purchaseorder.vendorid=?';
		$po_res = $this->db->pquery($po_q, array($id));
		$po_ids_list = array();
		for($k=0;$k < $this->db->num_rows($po_res);$k++)
		{
			$po_id = $this->db->query_result($po_res,$k,"crmid");
			$po_ids_list[] = $po_id;
			$sql = 'UPDATE ncrm_crmentity SET deleted = 1 WHERE crmid = ?';
			$this->db->pquery($sql, array($po_id));
		}
		//Backup deleted Vendors related Potentials.
		$params = array($id, RB_RECORD_UPDATED, 'ncrm_crmentity', 'deleted', 'crmid', implode(",", $po_ids_list));
		$this->db->pquery('INSERT INTO ncrm_relatedlists_rb VALUES (?,?,?,?,?,?)', $params);

		//Backup Product-Vendor Relation
		$pro_q = 'SELECT productid FROM ncrm_products WHERE vendor_id=?';
		$pro_res = $this->db->pquery($pro_q, array($id));
		if ($this->db->num_rows($pro_res) > 0) {
			$pro_ids_list = array();
			for($k=0;$k < $this->db->num_rows($pro_res);$k++)
			{
				$pro_ids_list[] = $this->db->query_result($pro_res,$k,"productid");
			}
			$params = array($id, RB_RECORD_UPDATED, 'ncrm_products', 'vendor_id', 'productid', implode(",", $pro_ids_list));
			$this->db->pquery('INSERT INTO ncrm_relatedlists_rb VALUES (?,?,?,?,?,?)', $params);
		}
		//Deleting Product-Vendor Relation.
		$pro_q = 'UPDATE ncrm_products SET vendor_id = 0 WHERE vendor_id = ?';
		$this->db->pquery($pro_q, array($id));

		/*//Backup Contact-Vendor Relaton
		$con_q = 'SELECT contactid FROM ncrm_vendorcontactrel WHERE vendorid = ?';
		$con_res = $this->db->pquery($con_q, array($id));
		if ($this->db->num_rows($con_res) > 0) {
			for($k=0;$k < $this->db->num_rows($con_res);$k++)
			{
				$con_id = $this->db->query_result($con_res,$k,"contactid");
				$params = array($id, RB_RECORD_DELETED, 'ncrm_vendorcontactrel', 'vendorid', 'contactid', $con_id);
				$this->db->pquery('INSERT INTO ncrm_relatedlists_rb VALUES (?,?,?,?,?,?)', $params);
			}
		}
		//Deleting Contact-Vendor Relaton
		$vc_sql = 'DELETE FROM ncrm_vendorcontactrel WHERE vendorid=?';
		$this->db->pquery($vc_sql, array($id));*/

		parent::unlinkDependencies($module, $id);
	}

	function save_related_module($module, $crmid, $with_module, $with_crmids) {
		$adb = PearDatabase::getInstance();

		if(!is_array($with_crmids)) $with_crmids = Array($with_crmids);
		foreach($with_crmids as $with_crmid) {
			if($with_module == 'Contacts')
				$adb->pquery("insert into ncrm_vendorcontactrel values (?,?)", array($crmid, $with_crmid));
			elseif($with_module == 'Products')
				$adb->pquery("update ncrm_products set vendor_id=? where productid=?", array($crmid, $with_crmid));
			else {
				parent::save_related_module($module, $crmid, $with_module, $with_crmid);
			}
		}
	}

    // Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;
        if($return_module == 'Contacts') {
			$sql = 'DELETE FROM ncrm_vendorcontactrel WHERE vendorid=? AND contactid=?';
			$this->db->pquery($sql, array($id,$return_id));
		} else {
			parent::unlinkRelationship($id, $return_module, $return_id);
		}
	}

}
?>
