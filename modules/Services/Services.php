<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/
class Services extends CRMEntity {
	var $db, $log; // Used in class functions of CRMEntity

	var $table_name = 'ncrm_service';
	var $table_index= 'serviceid';
	var $column_fields = Array();

	/** Indicator if this is a custom module or standard module */
	var $IsCustomModule = true;

	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('ncrm_servicecf', 'serviceid');

	/**
	 * Mandatory for Saving, Include tables related to this module.
	 */
	var $tab_name = Array('ncrm_crmentity','ncrm_service','ncrm_servicecf');

	/**
	 * Mandatory for Saving, Include tablename and tablekey columnname here.
	 */
	var $tab_name_index = Array(
		'ncrm_crmentity'=>'crmid',
		'ncrm_service'=>'serviceid',
		'ncrm_servicecf'=>'serviceid',
		'ncrm_producttaxrel'=>'productid');

	/**
	 * Mandatory for Listing (Related listview)
	 */
	var $list_fields = Array(
   		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'ncrm_'
		'Service No'=>Array('service'=>'service_no'),
		'Service Name'=>Array('service'=>'servicename'),
        'Commission Rate'=>Array('service'=>'commissionrate'),
		'No of Units'=>Array('service'=>'qty_per_unit'),
		'Price'=>Array('service'=>'unit_price')
	);
	var $list_fields_name = Array(
		/* Format: Field Label => fieldname */
		'Service No'=>'service_no',
		'Service Name'=>'servicename',
		'Commission Rate'=>'commissionrate',
		'No of Units'=>'qty_per_unit',
		'Price'=>'unit_price'
	);

	// Make the field link to detail view
	var $list_link_field= 'servicename';

	// For Popup listview and UI type support
	var $search_fields = Array(
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'ncrm_'
		'Service No'=>Array('service'=>'service_no'),
		'Service Name'=>Array('service'=>'servicename'),
		'Price'=>Array('service'=>'unit_price')
	);
	var $search_fields_name = Array(
		/* Format: Field Label => fieldname */
		'Service No'=>'service_no',
		'Service Name'=>'servicename',
		'Price'=>'unit_price'
	);

	// For Popup window record selection
	var $popup_fields = Array ('servicename','service_usageunit','unit_price');

	// Placeholder for sort fields - All the fields will be initialized for Sorting through initSortFields
	var $sortby_fields = Array();

	// For Alphabetical search
	var $def_basicsearch_col = 'servicename';

	// Column value to use on detail view record text display
	var $def_detailview_recname = 'servicename';

	// Required Information for enabling Import feature
	var $required_fields = Array('servicename'=>1);

	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to ncrm_field.fieldname values.
	var $mandatory_fields = Array('servicename', 'assigned_user_id');

	var $default_order_by = 'servicename';
	var $default_sort_order='ASC';

	var $unit_price;

	/**	Constructor which will set the column_fields in this object
	 */
	function __construct() {
		global $log;
		$this->column_fields = getColumnFields(get_class($this));
		$this->db = PearDatabase::getInstance();
		$this->log = $log;
	}

	function save_module($module)
	{
		//Inserting into service_taxrel table
		if($_REQUEST['ajxaction'] != 'DETAILVIEW'&& $_REQUEST['action'] != 'MassEditSave' && $_REQUEST['action'] != 'ProcessDuplicates')
		{
			$this->insertTaxInformation('ncrm_producttaxrel', 'Services');
			$this->insertPriceInformation('ncrm_productcurrencyrel', 'Services');
		}
		// Update unit price value in ncrm_productcurrencyrel
		$this->updateUnitPrice();
	}

	/**	function to save the service tax information in ncrm_servicetaxrel table
	 *	@param string $tablename - ncrm_tablename to save the service tax relationship (servicetaxrel)
	 *	@param string $module	 - current module name
	 *	$return void
	*/
	function insertTaxInformation($tablename, $module)
	{
		global $adb, $log;
		$log->debug("Entering into insertTaxInformation($tablename, $module) method ...");
		$tax_details = getAllTaxes();

		$tax_per = '';
		//Save the Product - tax relationship if corresponding tax check box is enabled
		//Delete the existing tax if any
		if($this->mode == 'edit')
		{
			for($i=0;$i<count($tax_details);$i++)
			{
				$taxid = getTaxId($tax_details[$i]['taxname']);
				$sql = "delete from ncrm_producttaxrel where productid=? and taxid=?";
				$adb->pquery($sql, array($this->id,$taxid));
			}
		}
		for($i=0;$i<count($tax_details);$i++)
		{
			$tax_name = $tax_details[$i]['taxname'];
			$tax_checkname = $tax_details[$i]['taxname']."_check";
			if($_REQUEST[$tax_checkname] == 'on' || $_REQUEST[$tax_checkname] == 1)
			{
				$taxid = getTaxId($tax_name);
				$tax_per = $_REQUEST[$tax_name];
				if($tax_per == '')
				{
					$log->debug("Tax selected but value not given so default value will be saved.");
					$tax_per = getTaxPercentage($tax_name);
				}

				$log->debug("Going to save the Product - $tax_name tax relationship");

				$query = "insert into ncrm_producttaxrel values(?,?,?)";
				$adb->pquery($query, array($this->id,$taxid,$tax_per));
			}
		}

		$log->debug("Exiting from insertTaxInformation($tablename, $module) method ...");
	}

	/**	function to save the service price information in ncrm_servicecurrencyrel table
	 *	@param string $tablename - ncrm_tablename to save the service currency relationship (servicecurrencyrel)
	 *	@param string $module	 - current module name
	 *	$return void
	*/
	function insertPriceInformation($tablename, $module)
	{
		global $adb, $log, $current_user;
		$log->debug("Entering into insertPriceInformation($tablename, $module) method ...");
		//removed the update of currency_id based on the logged in user's preference : fix 6490


		$currency_details = getAllCurrencies('all');

		//Delete the existing currency relationship if any
		if($this->mode == 'edit' &&  $_REQUEST['action'] != 'MassEditSave' && $_REQUEST['action'] != 'ProcessDuplicates')
		{
			for($i=0;$i<count($currency_details);$i++)
			{
				$curid = $currency_details[$i]['curid'];
				$sql = "delete from ncrm_productcurrencyrel where productid=? and currencyid=?";
				$adb->pquery($sql, array($this->id,$curid));
			}
		}

		$service_base_conv_rate = getBaseConversionRateForProduct($this->id, $this->mode,$module);

		//Save the Product - Currency relationship if corresponding currency check box is enabled
		for($i=0;$i<count($currency_details);$i++)
		{
			$curid = $currency_details[$i]['curid'];
			$curname = $currency_details[$i]['currencylabel'];
			$cur_checkname = 'cur_' . $curid . '_check';
			$cur_valuename = 'curname' . $curid;
			$base_currency_check = 'base_currency' . $curid;
			$requestPrice = CurrencyField::convertToDBFormat($_REQUEST['unit_price'], null, true);
			$actualPrice = CurrencyField::convertToDBFormat($_REQUEST[$cur_valuename], null, true);
			if($_REQUEST[$cur_checkname] == 'on' || $_REQUEST[$cur_checkname] == 1)
			{
				$conversion_rate = $currency_details[$i]['conversionrate'];
				$actual_conversion_rate = $service_base_conv_rate * $conversion_rate;
				$converted_price = $actual_conversion_rate * $requestPrice;

				$log->debug("Going to save the Product - $curname currency relationship");

				$query = "insert into ncrm_productcurrencyrel values(?,?,?,?)";
				$adb->pquery($query, array($this->id,$curid,$converted_price,$actualPrice));

				// Update the Product information with Base Currency choosen by the User.
				if ($_REQUEST['base_currency'] == $cur_valuename) {
					$adb->pquery("update ncrm_service set currency_id=?, unit_price=? where serviceid=?", array($curid, $actualPrice, $this->id));
				}
			}else{
				$curid = fetchCurrency($current_user->id);
				$adb->pquery("update ncrm_service set currency_id=? where serviceid=?", array($curid, $this->id));
			}
		}

		$log->debug("Exiting from insertPriceInformation($tablename, $module) method ...");
	}

	function updateUnitPrice() {
		$prod_res = $this->db->pquery("select unit_price, currency_id from ncrm_service where serviceid=?", array($this->id));
		$prod_unit_price = $this->db->query_result($prod_res, 0, 'unit_price');
		$prod_base_currency = $this->db->query_result($prod_res, 0, 'currency_id');

		$query = "update ncrm_productcurrencyrel set actual_price=? where productid=? and currencyid=?";
		$params = array($prod_unit_price, $this->id, $prod_base_currency);
		$this->db->pquery($query, $params);
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
		$query .= " LEFT JOIN ncrm_groups
						ON ncrm_groups.groupid = ncrm_crmentity.smownerid
					LEFT JOIN ncrm_users
						ON ncrm_users.id = ncrm_crmentity.smownerid ";
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

	/**
	 * Create query to export the records.
	 */
	function create_export_query($where)
	{
		global $current_user;

		include("include/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery('Services', "detail_view");

		$fields_list = getFieldsListFromQuery($sql);

		$query = "SELECT $fields_list
					FROM ncrm_crmentity INNER JOIN $this->table_name ON ncrm_crmentity.crmid=$this->table_name.$this->table_index";

		if(!empty($this->customFieldTable)) {
			$query .= " INNER JOIN ".$this->customFieldTable[0]." ON ".$this->customFieldTable[0].'.'.$this->customFieldTable[1] .
				      " = $this->table_name.$this->table_index";
		}

		$query .= " LEFT JOIN ncrm_groups ON ncrm_groups.groupid = ncrm_crmentity.smownerid";
		$query .= " LEFT JOIN ncrm_users ON ncrm_crmentity.smownerid = ncrm_users.id AND ncrm_users.status='Active'";
		$query .= $this->getNonAdminAccessControlQuery('Services',$current_user);
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
		$from_clause .=	" LEFT JOIN ncrm_groups ON ncrm_groups.groupid = ncrm_crmentity.smownerid
							LEFT JOIN ncrm_users ON ncrm_users.id = ncrm_crmentity.smownerid";
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

	/**	function used to get the list of quotes which are related to the service
	 *	@param int $id - service id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_quotes($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_quotes(".$id.") method ...");
		$this_module = $currentModule;

        $related_module = vtlib_getModuleNameById($rel_tab_id);
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
		$query = "SELECT ncrm_crmentity.*,
			ncrm_quotes.*,
			ncrm_potential.potentialname,
			ncrm_account.accountname,
			ncrm_inventoryproductrel.productid,
			case when (ncrm_users.user_name not like '') then $userNameSql
				else ncrm_groups.groupname end as user_name
			FROM ncrm_quotes
			INNER JOIN ncrm_crmentity
				ON ncrm_crmentity.crmid = ncrm_quotes.quoteid
			INNER JOIN ncrm_inventoryproductrel
				ON ncrm_inventoryproductrel.id = ncrm_quotes.quoteid
			LEFT OUTER JOIN ncrm_account
				ON ncrm_account.accountid = ncrm_quotes.accountid
			LEFT OUTER JOIN ncrm_potential
				ON ncrm_potential.potentialid = ncrm_quotes.potentialid
			LEFT JOIN ncrm_groups
				ON ncrm_groups.groupid = ncrm_crmentity.smownerid
            LEFT JOIN ncrm_quotescf
                ON ncrm_quotescf.quoteid = ncrm_quotes.quoteid
			LEFT JOIN ncrm_quotesbillads
				ON ncrm_quotesbillads.quotebilladdressid = ncrm_quotes.quoteid
			LEFT JOIN ncrm_quotesshipads
				ON ncrm_quotesshipads.quoteshipaddressid = ncrm_quotes.quoteid
			LEFT JOIN ncrm_users
				ON ncrm_users.id = ncrm_crmentity.smownerid
			WHERE ncrm_crmentity.deleted = 0
			AND ncrm_inventoryproductrel.productid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_quotes method ...");
		return $return_value;
	}

	/**	function used to get the list of purchase orders which are related to the service
	 *	@param int $id - service id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_purchase_orders($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_purchase_orders(".$id.") method ...");
		$this_module = $currentModule;

        $related_module = vtlib_getModuleNameById($rel_tab_id);
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
		$query = "SELECT ncrm_crmentity.*,
			ncrm_purchaseorder.*,
			ncrm_service.servicename,
			ncrm_inventoryproductrel.productid,
			case when (ncrm_users.user_name not like '') then $userNameSql
				else ncrm_groups.groupname end as user_name
			FROM ncrm_purchaseorder
			INNER JOIN ncrm_crmentity
				ON ncrm_crmentity.crmid = ncrm_purchaseorder.purchaseorderid
			INNER JOIN ncrm_inventoryproductrel
				ON ncrm_inventoryproductrel.id = ncrm_purchaseorder.purchaseorderid
			INNER JOIN ncrm_service
				ON ncrm_service.serviceid = ncrm_inventoryproductrel.productid
			LEFT JOIN ncrm_groups
				ON ncrm_groups.groupid = ncrm_crmentity.smownerid
            LEFT JOIN ncrm_purchaseordercf
                ON ncrm_purchaseordercf.purchaseorderid = ncrm_purchaseorder.purchaseorderid
			LEFT JOIN ncrm_pobillads
				ON ncrm_pobillads.pobilladdressid = ncrm_purchaseorder.purchaseorderid
			LEFT JOIN ncrm_poshipads
				ON ncrm_poshipads.poshipaddressid = ncrm_purchaseorder.purchaseorderid
			LEFT JOIN ncrm_users
				ON ncrm_users.id = ncrm_crmentity.smownerid
			WHERE ncrm_crmentity.deleted = 0
			AND ncrm_service.serviceid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_purchase_orders method ...");
		return $return_value;
	}

	/**	function used to get the list of sales orders which are related to the service
	 *	@param int $id - service id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_salesorder($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_salesorder(".$id.") method ...");
		$this_module = $currentModule;

        $related_module = vtlib_getModuleNameById($rel_tab_id);
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

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>'ncrm_users.first_name', 'last_name' =>
			'ncrm_users.last_name'), 'Users');
		$query = "SELECT ncrm_crmentity.*,
			ncrm_salesorder.*,
			ncrm_service.servicename AS servicename,
			ncrm_account.accountname,
			case when (ncrm_users.user_name not like '') then $userNameSql
				else ncrm_groups.groupname end as user_name
			FROM ncrm_salesorder
			INNER JOIN ncrm_crmentity
				ON ncrm_crmentity.crmid = ncrm_salesorder.salesorderid
			INNER JOIN ncrm_inventoryproductrel
				ON ncrm_inventoryproductrel.id = ncrm_salesorder.salesorderid
			INNER JOIN ncrm_service
				ON ncrm_service.serviceid = ncrm_inventoryproductrel.productid
			LEFT OUTER JOIN ncrm_account
				ON ncrm_account.accountid = ncrm_salesorder.accountid
            LEFT JOIN ncrm_invoice_recurring_info
                ON ncrm_invoice_recurring_info.start_period = ncrm_salesorder.salesorderid
			LEFT JOIN ncrm_groups
				ON ncrm_groups.groupid = ncrm_crmentity.smownerid
            LEFT JOIN ncrm_salesordercf
                ON ncrm_salesordercf.salesorderid = ncrm_salesorder.salesorderid
			LEFT JOIN ncrm_sobillads
				ON ncrm_sobillads.sobilladdressid = ncrm_salesorder.salesorderid
			LEFT JOIN ncrm_soshipads
				ON ncrm_soshipads.soshipaddressid = ncrm_salesorder.salesorderid
			LEFT JOIN ncrm_users
				ON ncrm_users.id = ncrm_crmentity.smownerid
			WHERE ncrm_crmentity.deleted = 0
			AND ncrm_service.serviceid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_salesorder method ...");
		return $return_value;
	}

	/**	function used to get the list of invoices which are related to the service
	 *	@param int $id - service id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_invoices($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_invoices(".$id.") method ...");
		$this_module = $currentModule;

        $related_module = vtlib_getModuleNameById($rel_tab_id);
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
		$query = "SELECT ncrm_crmentity.*,
			ncrm_invoice.*,
			ncrm_inventoryproductrel.quantity,
			ncrm_account.accountname,
			case when (ncrm_users.user_name not like '') then $userNameSql
				else ncrm_groups.groupname end as user_name
			FROM ncrm_invoice
			INNER JOIN ncrm_crmentity
				ON ncrm_crmentity.crmid = ncrm_invoice.invoiceid
			LEFT OUTER JOIN ncrm_account
				ON ncrm_account.accountid = ncrm_invoice.accountid
			INNER JOIN ncrm_inventoryproductrel
				ON ncrm_inventoryproductrel.id = ncrm_invoice.invoiceid
			LEFT JOIN ncrm_groups
				ON ncrm_groups.groupid = ncrm_crmentity.smownerid
            LEFT JOIN ncrm_invoicecf
                ON ncrm_invoicecf.invoiceid = ncrm_invoice.invoiceid
			LEFT JOIN ncrm_invoicebillads
				ON ncrm_invoicebillads.invoicebilladdressid = ncrm_invoice.invoiceid
			LEFT JOIN ncrm_invoiceshipads
				ON ncrm_invoiceshipads.invoiceshipaddressid = ncrm_invoice.invoiceid
			LEFT JOIN ncrm_users
				ON  ncrm_users.id = ncrm_crmentity.smownerid
			WHERE ncrm_crmentity.deleted = 0
			AND ncrm_inventoryproductrel.productid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_invoices method ...");
		return $return_value;
	}

	/**	function used to get the list of pricebooks which are related to the service
	 *	@param int $id - service id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_service_pricebooks($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $currentModule,$log,$singlepane_view,$mod_strings;
		$log->debug("Entering get_service_pricebooks(".$id.") method ...");

		$related_module = vtlib_getModuleNameById($rel_tab_id);
		checkFileAccessForInclusion("modules/$related_module/$related_module.php");
		require_once("modules/$related_module/$related_module.php");
		$focus = new $related_module();
		$singular_modname = vtlib_toSingular($related_module);

		if($singlepane_view == 'true')
			$returnset = "&return_module=$currentModule&return_action=DetailView&return_id=$id";
		else
			$returnset = "&return_module=$currentModule&return_action=CallRelatedList&return_id=$id";

		$button = '';
		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) && isPermitted($related_module,1, '') == 'yes' && isPermitted($currentModule,'EditView',$id) == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_TO'). " ". getTranslatedString($related_module) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"AddServiceToPriceBooks\";this.form.module.value=\"$currentModule\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_TO'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}

		$query = "SELECT ncrm_crmentity.crmid,
			ncrm_pricebook.*,
			ncrm_pricebookproductrel.productid as prodid
			FROM ncrm_pricebook
			INNER JOIN ncrm_crmentity
				ON ncrm_crmentity.crmid = ncrm_pricebook.pricebookid
			INNER JOIN ncrm_pricebookproductrel
				ON ncrm_pricebookproductrel.pricebookid = ncrm_pricebook.pricebookid
			INNER JOIN ncrm_pricebookcf
				ON ncrm_pricebookcf.pricebookid = ncrm_pricebook.pricebookid
			WHERE ncrm_crmentity.deleted = 0
			AND ncrm_pricebookproductrel.productid = ".$id;
		$log->debug("Exiting get_product_pricebooks method ...");

		$return_value = GetRelatedList($currentModule, $related_module, $focus, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_service_pricebooks method ...");
		return $return_value;
	}


	/**	Function to display the Services which are related to the PriceBook
	 *	@param string $query - query to get the list of products which are related to the current PriceBook
	 *	@param object $focus - PriceBook object which contains all the information of the current PriceBook
	 *	@param string $returnset - return_module, return_action and return_id which are sequenced with & to pass to the URL which is optional
	 *	return array $return_data which will be formed like array('header'=>$header,'entries'=>$entries_list) where as $header contains all the header columns and $entries_list will contain all the Service entries
	 */
	function getPriceBookRelatedServices($query,$focus,$returnset='')
	{
		global $log;
		$log->debug("Entering getPriceBookRelatedServices(".$query.",".get_class($focus).",".$returnset.") method ...");

		global $adb;
		global $app_strings;
		global $current_language,$current_user;
		$current_module_strings = return_module_language($current_language, 'Services');
        $no_of_decimal_places = getCurrencyDecimalPlaces();
		global $list_max_entries_per_page;
		global $urlPrefix;

		global $theme;
		$pricebook_id = $_REQUEST['record'];
		$theme_path="themes/".$theme."/";
		$image_path=$theme_path."images/";

		$computeCount = $_REQUEST['withCount'];
		if(PerformancePrefs::getBoolean('LISTVIEW_COMPUTE_PAGE_COUNT', false) === true ||
				((boolean) $computeCount) == true){
			$noofrows = $adb->query_result($adb->query(Ncrm_Functions::mkCountQuery($query)),0,'count');
		}else{
			$noofrows = null;
		}
		$module = 'PriceBooks';
		$relatedmodule = 'Services';
		if(!$_SESSION['rlvs'][$module][$relatedmodule])
		{
			$modObj = new ListViewSession();
			$modObj->sortby = $focus->default_order_by;
			$modObj->sorder = $focus->default_sort_order;
			$_SESSION['rlvs'][$module][$relatedmodule] = get_object_vars($modObj);
		}
		if(isset($_REQUEST['relmodule']) && $_REQUEST['relmodule']!='' && $_REQUEST['relmodule'] == $relatedmodule) {
			$relmodule = vtlib_purify($_REQUEST['relmodule']);
			if($_SESSION['rlvs'][$module][$relmodule]) {
				setSessionVar($_SESSION['rlvs'][$module][$relmodule],$noofrows,$list_max_entries_per_page,$module,$relmodule);
			}
		}
		global $relationId;
		$start = RelatedListViewSession::getRequestCurrentPage($relationId, $query);
		$navigation_array =  VT_getSimpleNavigationValues($start, $list_max_entries_per_page,
				$noofrows);

		$limit_start_rec = ($start-1) * $list_max_entries_per_page;

		if( $adb->dbType == "pgsql")
			$list_result = $adb->pquery($query.
					" OFFSET $limit_start_rec LIMIT $list_max_entries_per_page", array());
		else
			$list_result = $adb->pquery($query.
					" LIMIT $limit_start_rec, $list_max_entries_per_page", array());

		$header=array();
		$header[]=$current_module_strings['LBL_LIST_SERVICE_NAME'];
		if(getFieldVisibilityPermission('Services', $current_user->id, 'unit_price') == '0')
			$header[]=$current_module_strings['LBL_SERVICE_UNIT_PRICE'];
		$header[]=$current_module_strings['LBL_PB_LIST_PRICE'];
		if(isPermitted("PriceBooks","EditView","") == 'yes' || isPermitted("PriceBooks","Delete","") == 'yes')
			$header[]=$app_strings['LBL_ACTION'];

		$currency_id = $focus->column_fields['currency_id'];
		$numRows = $adb->num_rows($list_result);
		for($i=0; $i<$numRows; $i++) {
			$entity_id = $adb->query_result($list_result,$i,"crmid");
			$unit_price = 	$adb->query_result($list_result,$i,"unit_price");
			if($currency_id != null) {
				$prod_prices = getPricesForProducts($currency_id, array($entity_id),'Services');
				$unit_price = $prod_prices[$entity_id];
			}
			$listprice = $adb->query_result($list_result,$i,"listprice");
			$field_name=$entity_id."_listprice";

			$entries = Array();
			$entries[] = textlength_check($adb->query_result($list_result,$i,"servicename"));
			if(getFieldVisibilityPermission('Services', $current_user->id, 'unit_price') == '0')
				$entries[] = CurrencyField::convertToUserFormat($unit_price, null, true);

			$entries[] = CurrencyField::convertToUserFormat($listprice, null, true);
			$action = "";
			if(isPermitted("PriceBooks","EditView","") == 'yes' && isPermitted('Services', 'EditView', $entity_id) == 'yes') {
				$action .= '<img style="cursor:pointer;" src="themes/images/editfield.gif" border="0" onClick="fnvshobj(this,\'editlistprice\'),editProductListPrice(\''.$entity_id.'\',\''.$pricebook_id.'\',\''.number_format($listprice, $no_of_decimal_places,'.','').'\')" alt="'.$app_strings["LBL_EDIT_BUTTON"].'" title="'.$app_strings["LBL_EDIT_BUTTON"].'"/>';
			} else {
				$action .= '<img src="'. ncrm_imageurl('blank.gif', $theme).'" border="0" />';
			}
			if(isPermitted("PriceBooks","Delete","") == 'yes' && isPermitted('Services', 'Delete', $entity_id) == 'yes')
			{
				if($action != "")
					$action .= '&nbsp;|&nbsp;';
				$action .= '<img src="themes/images/delete.gif" onclick="if(confirm(\''.$app_strings['ARE_YOU_SURE'].'\')) deletePriceBookProductRel('.$entity_id.','.$pricebook_id.');" alt="'.$app_strings["LBL_DELETE"].'" title="'.$app_strings["LBL_DELETE"].'" style="cursor:pointer;" border="0">';
			}
			if($action != "")
				$entries[] = $action;
			$entries_list[] = $entries;
		}
		$navigationOutput[] =  getRecordRangeMessage($list_result, $limit_start_rec,$noofrows);
		$navigationOutput[] = getRelatedTableHeaderNavigation($navigation_array, '',$module,$relatedmodule,$focus->id);
		$return_data = array('header'=>$header,'entries'=>$entries_list,'navigation'=>$navigationOutput);

		$log->debug("Exiting getPriceBookRelatedServices method ...");
		return $return_data;
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

		$rel_table_arr = Array("Quotes"=>"ncrm_inventoryproductrel","PurchaseOrder"=>"ncrm_inventoryproductrel","SalesOrder"=>"ncrm_inventoryproductrel",
				"Invoice"=>"ncrm_inventoryproductrel","PriceBooks"=>"ncrm_pricebookproductrel","Documents"=>"ncrm_senotesrel");

		$tbl_field_arr = Array("ncrm_inventoryproductrel"=>"id","ncrm_pricebookproductrel"=>"pricebookid","ncrm_senotesrel"=>"notesid");

		$entity_tbl_field_arr = Array("ncrm_inventoryproductrel"=>"productid","ncrm_pricebookproductrel"=>"productid","ncrm_senotesrel"=>"crmid");

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

	/*
	 * Function to get the primary query part of a report
	 * @param - $module primary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsQuery($module,$queryPlanner){
	   	global $current_user;

			$matrix = $queryPlanner->newDependencyMatrix();
			$matrix->setDependency('ncrm_seproductsrel',array('ncrm_crmentityRelServices','ncrm_accountRelServices','ncrm_leaddetailsRelServices','ncrm_servicecf','ncrm_potentialRelServices'));
			$query = "from ncrm_service
				inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_service.serviceid";
			if ($queryPlanner->requireTable("ncrm_servicecf")){
			    $query .= " left join ncrm_servicecf on ncrm_service.serviceid = ncrm_servicecf.serviceid";
			}
			if ($queryPlanner->requireTable("ncrm_usersServices")){
			    $query .= " left join ncrm_users as ncrm_usersServices on ncrm_usersServices.id = ncrm_crmentity.smownerid";
			}
			if ($queryPlanner->requireTable("ncrm_groupsServices")){
			    $query .= " left join ncrm_groups as ncrm_groupsServices on ncrm_groupsServices.groupid = ncrm_crmentity.smownerid";
			}
			if ($queryPlanner->requireTable("ncrm_seproductsrel")){
			    $query .= " left join ncrm_seproductsrel on ncrm_seproductsrel.productid= ncrm_service.serviceid";
			}
			if ($queryPlanner->requireTable("ncrm_crmentityRelServices")){
			    $query .= " left join ncrm_crmentity as ncrm_crmentityRelServices on ncrm_crmentityRelServices.crmid = ncrm_seproductsrel.crmid and ncrm_crmentityRelServices.deleted = 0";
			}
			if ($queryPlanner->requireTable("ncrm_accountRelServices")){
			    $query .= " left join ncrm_account as ncrm_accountRelServices on ncrm_accountRelServices.accountid=ncrm_seproductsrel.crmid";
			}
			if ($queryPlanner->requireTable("ncrm_leaddetailsRelServices")){
			    $query .= " left join ncrm_leaddetails as ncrm_leaddetailsRelServices on ncrm_leaddetailsRelServices.leadid = ncrm_seproductsrel.crmid";
			}
			if ($queryPlanner->requireTable("ncrm_potentialRelServices")){
			    $query .= " left join ncrm_potential as ncrm_potentialRelServices on ncrm_potentialRelServices.potentialid = ncrm_seproductsrel.crmid";
			}
			if ($queryPlanner->requireTable("ncrm_lastModifiedByServices")){
			    $query .= " left join ncrm_users as ncrm_lastModifiedByServices on ncrm_lastModifiedByServices.id = ncrm_crmentity.modifiedby";
			}
			if ($queryPlanner->requireTable("innerService")){
			    $query .= " LEFT JOIN (
					SELECT ncrm_service.serviceid,
							(CASE WHEN (ncrm_service.currency_id = 1 ) THEN ncrm_service.unit_price
								ELSE (ncrm_service.unit_price / ncrm_currency_info.conversion_rate) END
							) AS actual_unit_price
					FROM ncrm_service
					LEFT JOIN ncrm_currency_info ON ncrm_service.currency_id = ncrm_currency_info.id
					LEFT JOIN ncrm_productcurrencyrel ON ncrm_service.serviceid = ncrm_productcurrencyrel.productid
					AND ncrm_productcurrencyrel.currencyid = ". $current_user->currency_id . "
				) AS innerService ON innerService.serviceid = ncrm_service.serviceid";
			}
			return $query;
	}

	/*
	 * Function to get the secondary query part of a report
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule, $queryPlanner) {
		global $current_user;
		$matrix = $queryPlanner->newDependencyMatrix();
		$matrix->setDependency('ncrm_service',array('actual_unit_price','ncrm_currency_info','ncrm_productcurrencyrel','ncrm_servicecf','ncrm_crmentityServices'));
		$matrix->setDependency('ncrm_crmentityServices',array('ncrm_usersServices','ncrm_groupsServices','ncrm_lastModifiedByServices'));
		if (!$queryPlanner->requireTable("ncrm_service",$matrix)){
			return '';
		}
		$query = $this->getRelationQuery($module,$secmodule,"ncrm_service","serviceid", $queryPlanner);
		if ($queryPlanner->requireTable("innerService")){
		    $query .= " LEFT JOIN (
			SELECT ncrm_service.serviceid,
			(CASE WHEN (ncrm_service.currency_id = " . $current_user->currency_id . " ) THEN ncrm_service.unit_price
			WHEN (ncrm_productcurrencyrel.actual_price IS NOT NULL) THEN ncrm_productcurrencyrel.actual_price
			ELSE (ncrm_service.unit_price / ncrm_currency_info.conversion_rate) * ". $current_user->conv_rate . " END
			) AS actual_unit_price FROM ncrm_service
            LEFT JOIN ncrm_currency_info ON ncrm_service.currency_id = ncrm_currency_info.id
            LEFT JOIN ncrm_productcurrencyrel ON ncrm_service.serviceid = ncrm_productcurrencyrel.productid
			AND ncrm_productcurrencyrel.currencyid = ". $current_user->currency_id . ")
            AS innerService ON innerService.serviceid = ncrm_service.serviceid";
		}
		if ($queryPlanner->requireTable("ncrm_crmentityServices",$matrix)){
		    $query .= " left join ncrm_crmentity as ncrm_crmentityServices on ncrm_crmentityServices.crmid=ncrm_service.serviceid and ncrm_crmentityServices.deleted=0";
		}
		if ($queryPlanner->requireTable("ncrm_servicecf")){
		    $query .= " left join ncrm_servicecf on ncrm_service.serviceid = ncrm_servicecf.serviceid";
		}
		if ($queryPlanner->requireTable("ncrm_usersServices")){
		    $query .= " left join ncrm_users as ncrm_usersServices on ncrm_usersServices.id = ncrm_crmentityServices.smownerid";
		}
		if ($queryPlanner->requireTable("ncrm_groupsServices")){
		    $query .= " left join ncrm_groups as ncrm_groupsServices on ncrm_groupsServices.groupid = ncrm_crmentityServices.smownerid";
		}
		if ($queryPlanner->requireTable("ncrm_lastModifiedByServices")){
		    $query .= " left join ncrm_users as ncrm_lastModifiedByServices on ncrm_lastModifiedByServices.id = ncrm_crmentityServices.modifiedby ";
		}
        if ($queryPlanner->requireTable("ncrm_createdbyServices")){
			$query .= " left join ncrm_users as ncrm_createdbyServices on ncrm_createdbyServices.id = ncrm_crmentityServices.smcreatorid ";
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
			"Quotes" => array("ncrm_inventoryproductrel"=>array("productid","id"),"ncrm_service"=>"serviceid"),
			"PurchaseOrder" => array("ncrm_inventoryproductrel"=>array("productid","id"),"ncrm_service"=>"serviceid"),
			"SalesOrder" => array("ncrm_inventoryproductrel"=>array("productid","id"),"ncrm_service"=>"serviceid"),
			"Invoice" => array("ncrm_inventoryproductrel"=>array("productid","id"),"ncrm_service"=>"serviceid"),
			"PriceBooks" => array("ncrm_pricebookproductrel"=>array("productid","pricebookid"),"ncrm_service"=>"serviceid"),
			"Documents" => array("ncrm_senotesrel"=>array("crmid","notesid"),"ncrm_service"=>"serviceid"),
		);
		return $rel_tables[$secmodule];
	}

	// Function to unlink all the dependent entities of the given Entity by Id
	function unlinkDependencies($module, $id) {
		global $log;
		$this->db->pquery('DELETE from ncrm_seproductsrel WHERE productid=? or crmid=?',array($id,$id));

		parent::unlinkDependencies($module, $id);
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
			$moduleInstance->allowSharing();

			$ttModuleInstance = Ncrm_Module::getInstance('HelpDesk');
			$ttModuleInstance->setRelatedList($moduleInstance,'Services',array('select'));

			$leadModuleInstance = Ncrm_Module::getInstance('Leads');
			$leadModuleInstance->setRelatedList($moduleInstance,'Services',array('select'));

			$accModuleInstance = Ncrm_Module::getInstance('Accounts');
			$accModuleInstance->setRelatedList($moduleInstance,'Services',array('select'));

			$conModuleInstance = Ncrm_Module::getInstance('Contacts');
			$conModuleInstance->setRelatedList($moduleInstance,'Services',array('select'));

			$potModuleInstance = Ncrm_Module::getInstance('Potentials');
			$potModuleInstance->setRelatedList($moduleInstance,'Services',array('select'));

			$pbModuleInstance = Ncrm_Module::getInstance('PriceBooks');
			$pbModuleInstance->setRelatedList($moduleInstance,'Services',array('select'),'get_pricebook_services');

			// Initialize module sequence for the module
			$adb->pquery("INSERT into ncrm_modentity_num values(?,?,?,?,?,?)",array($adb->getUniqueId("ncrm_modentity_num"),$moduleName,'SER',1,1,1));

			// Mark the module as Standard module
			$adb->pquery('UPDATE ncrm_tab SET customized=0 WHERE name=?', array($moduleName));

		} else if($eventType == 'module.disabled') {
		// TODO Handle actions when this module is disabled.
		} else if($eventType == 'module.enabled') {
		// TODO Handle actions when this module is enabled.
		} else if($eventType == 'module.preuninstall') {
		// TODO Handle actions when this module is about to be deleted.
		} else if($eventType == 'module.preupdate') {
		// TODO Handle actions before this module is updated.
		} else if($eventType == 'module.postupdate') {
		// TODO Handle actions after this module is updated.

			//adds sharing accsess
			$ServicesModule  = Ncrm_Module::getInstance('Services');
			Ncrm_Access::setDefaultSharing($ServicesModule);
		}
 	}

	/** Function to unlink an entity with given Id from another entity */
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log, $currentModule;
		$log->fatal('id:--'.$id);
		$log->fatal('return_module:--'.$return_module);
		$log->fatal('return_id:---'.$return_id);
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
	}

	/**
	* Function to get Product's related Products
	* @param  integer   $id      - productid
	* returns related Products record in array format
	*/
	function get_services($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_products(".$id.") method ...");
		$this_module = $currentModule;

        $related_module = vtlib_getModuleNameById($rel_tab_id);
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

		if($actions && $this->ismember_check() === 0) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input type='hidden' name='createmode' id='createmode' value='link' />".
					"<input title='".getTranslatedString('LBL_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\";' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}

		$query = "SELECT ncrm_service.serviceid, ncrm_service.servicename,
			ncrm_service.service_no, ncrm_service.commissionrate,
			ncrm_service.service_usageunit, ncrm_service.unit_price,
			ncrm_crmentity.crmid, ncrm_crmentity.smownerid
			FROM ncrm_service
			INNER JOIN ncrm_crmentity ON ncrm_crmentity.crmid = ncrm_service.serviceid
			INNER JOIN ncrm_servicecf
				ON ncrm_service.serviceid = ncrm_servicecf.serviceid
			LEFT JOIN ncrm_crmentityrel ON ncrm_crmentityrel.relcrmid = ncrm_service.serviceid AND ncrm_crmentityrel.module='Services'
			LEFT JOIN ncrm_users
				ON ncrm_users.id=ncrm_crmentity.smownerid
			LEFT JOIN ncrm_groups
				ON ncrm_groups.groupid = ncrm_crmentity.smownerid
			WHERE ncrm_crmentity.deleted = 0 AND ncrm_crmentityrel.crmid = $id ";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_products method ...");
		return $return_value;
	}
}
?>
