<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header$
 * Description:  Defines the Account SugarBean Account entity with the necessary
 * methods and variables.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
class Quotes extends CRMEntity {
	var $log;
	var $db;

	var $table_name = "ncrm_quotes";
	var $table_index= 'quoteid';
	var $tab_name = Array('ncrm_crmentity','ncrm_quotes','ncrm_quotesbillads','ncrm_quotesshipads','ncrm_quotescf','ncrm_inventoryproductrel');
	var $tab_name_index = Array('ncrm_crmentity'=>'crmid','ncrm_quotes'=>'quoteid','ncrm_quotesbillads'=>'quotebilladdressid','ncrm_quotesshipads'=>'quoteshipaddressid','ncrm_quotescf'=>'quoteid','ncrm_inventoryproductrel'=>'id');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('ncrm_quotescf', 'quoteid');
	var $entity_table = "ncrm_crmentity";

	var $billadr_table = "ncrm_quotesbillads";

	var $object_name = "Quote";

	var $new_schema = true;

	var $column_fields = Array();

	var $sortby_fields = Array('subject','crmid','smownerid','accountname','lastname');

	// This is used to retrieve related ncrm_fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'smownerid', 'opportunity_id', 'case_id', 'contact_id', 'task_id', 'note_id', 'meeting_id', 'call_id', 'email_id', 'parent_name', 'member_id' );

	// This is the list of ncrm_fields that are in the lists.
	var $list_fields = Array(
				//'Quote No'=>Array('crmentity'=>'crmid'),
				// Module Sequence Numbering
				'Quote No'=>Array('quotes'=>'quote_no'),
				// END
				'Subject'=>Array('quotes'=>'subject'),
				'Quote Stage'=>Array('quotes'=>'quotestage'),
				'Potential Name'=>Array('quotes'=>'potentialid'),
				'Account Name'=>Array('account'=> 'accountid'),
				'Total'=>Array('quotes'=> 'total'),
				'Assigned To'=>Array('crmentity'=>'smownerid')
				);

	var $list_fields_name = Array(
				        'Quote No'=>'quote_no',
				        'Subject'=>'subject',
				        'Quote Stage'=>'quotestage',
				        'Potential Name'=>'potential_id',
					'Account Name'=>'account_id',
					'Total'=>'hdnGrandTotal',
				        'Assigned To'=>'assigned_user_id'
				      );
	var $list_link_field= 'subject';

	var $search_fields = Array(
				'Quote No'=>Array('quotes'=>'quote_no'),
				'Subject'=>Array('quotes'=>'subject'),
				'Account Name'=>Array('quotes'=>'accountid'),
				'Quote Stage'=>Array('quotes'=>'quotestage'),
				);

	var $search_fields_name = Array(
					'Quote No'=>'quote_no',
				        'Subject'=>'subject',
				        'Account Name'=>'account_id',
				        'Quote Stage'=>'quotestage',
				      );

	// This is the list of ncrm_fields that are required.
	var $required_fields =  array("accountname"=>1);

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'crmid';
	var $default_sort_order = 'ASC';
	//var $groupTable = Array('ncrm_quotegrouprelation','quoteid');

	var $mandatory_fields = Array('subject','createdtime' ,'modifiedtime', 'assigned_user_id');

	// For Alphabetical search
	var $def_basicsearch_col = 'subject';

	// For workflows update field tasks is deleted all the lineitems.
	var $isLineItemUpdate = true;

	/**	Constructor which will set the column_fields in this object
	 */
	function Quotes() {
		$this->log =LoggerManager::getLogger('quote');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Quotes');
	}

	function save_module()
	{
		global $adb;
		//in ajax save we should not call this function, because this will delete all the existing product values
		if($_REQUEST['action'] != 'QuotesAjax' && $_REQUEST['ajxaction'] != 'DETAILVIEW'
				&& $_REQUEST['action'] != 'MassEditSave' && $_REQUEST['action'] != 'ProcessDuplicates'
				&& $_REQUEST['action'] != 'SaveAjax' && $this->isLineItemUpdate != false) {
			//Based on the total Number of rows we will save the product relationship with this entity
			saveInventoryProductDetails($this, 'Quotes');
		}

		// Update the currency id and the conversion rate for the quotes
		$update_query = "update ncrm_quotes set currency_id=?, conversion_rate=? where quoteid=?";
		$update_params = array($this->column_fields['currency_id'], $this->column_fields['conversion_rate'], $this->id);
		$adb->pquery($update_query, $update_params);
	}

	/**	function used to get the list of sales orders which are related to the Quotes
	 *	@param int $id - quote id
	 *	@return array - return an array which will be returned from the function GetRelatedList
	 */
	function get_salesorder($id)
	{
		global $log,$singlepane_view;
		$log->debug("Entering get_salesorder(".$id.") method ...");
		require_once('modules/SalesOrder/SalesOrder.php');
	        $focus = new SalesOrder();

		$button = '';

		if($singlepane_view == 'true')
			$returnset = '&return_module=Quotes&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Quotes&return_action=CallRelatedList&return_id='.$id;

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'ncrm_users.first_name', 'last_name' => 'ncrm_users.last_name'), 'Users');
		$query = "select ncrm_crmentity.*, ncrm_salesorder.*, ncrm_quotes.subject as quotename
			, ncrm_account.accountname,case when (ncrm_users.user_name not like '') then
			$userNameSql else ncrm_groups.groupname end as user_name
		from ncrm_salesorder
		inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_salesorder.salesorderid
		left outer join ncrm_quotes on ncrm_quotes.quoteid=ncrm_salesorder.quoteid
		left outer join ncrm_account on ncrm_account.accountid=ncrm_salesorder.accountid
		left join ncrm_groups on ncrm_groups.groupid=ncrm_crmentity.smownerid
        LEFT JOIN ncrm_salesordercf ON ncrm_salesordercf.salesorderid = ncrm_salesorder.salesorderid
        LEFT JOIN ncrm_invoice_recurring_info ON ncrm_invoice_recurring_info.start_period = ncrm_salesorder.salesorderid
		LEFT JOIN ncrm_sobillads ON ncrm_sobillads.sobilladdressid = ncrm_salesorder.salesorderid
		LEFT JOIN ncrm_soshipads ON ncrm_soshipads.soshipaddressid = ncrm_salesorder.salesorderid
		left join ncrm_users on ncrm_users.id=ncrm_crmentity.smownerid
		where ncrm_crmentity.deleted=0 and ncrm_salesorder.quoteid = ".$id;
		$log->debug("Exiting get_salesorder method ...");
		return GetRelatedList('Quotes','SalesOrder',$focus,$query,$button,$returnset);
	}

	/**	function used to get the list of activities which are related to the Quotes
	 *	@param int $id - quote id
	 *	@return array - return an array which will be returned from the function GetRelatedList
	 */
	function get_activities($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_activities(".$id.") method ...");
		$this_module = $currentModule;

        $related_module = vtlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/Activity.php");
		$other = new Activity();
        vtlib_setup_modulevars($related_module, $other);
		$singular_modname = vtlib_toSingular($related_module);

		$parenttab = getParentTab();

		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

		$button = '';

		$button .= '<input type="hidden" name="activity_mode">';

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				if(getFieldVisibilityPermission('Calendar',$current_user->id,'parent_id', 'readwrite') == '0') {
					$button .= "<input title='".getTranslatedString('LBL_NEW'). " ". getTranslatedString('LBL_TODO', $related_module) ."' class='crmbutton small create'" .
						" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\";this.form.return_module.value=\"$this_module\";this.form.activity_mode.value=\"Task\";' type='submit' name='button'" .
						" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString('LBL_TODO', $related_module) ."'>&nbsp;";
				}
			}
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'ncrm_users.first_name', 'last_name' => 'ncrm_users.last_name'), 'Users');
		$query = "SELECT case when (ncrm_users.user_name not like '') then $userNameSql else
		ncrm_groups.groupname end as user_name, ncrm_contactdetails.contactid,
		ncrm_contactdetails.lastname, ncrm_contactdetails.firstname, ncrm_activity.*,
		ncrm_seactivityrel.crmid as parent_id,ncrm_crmentity.crmid, ncrm_crmentity.smownerid,
		ncrm_crmentity.modifiedtime,ncrm_recurringevents.recurringtype
		from ncrm_activity
		inner join ncrm_seactivityrel on ncrm_seactivityrel.activityid=
		ncrm_activity.activityid
		inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_activity.activityid
		left join ncrm_cntactivityrel on ncrm_cntactivityrel.activityid=
		ncrm_activity.activityid
		left join ncrm_contactdetails on ncrm_contactdetails.contactid =
		ncrm_cntactivityrel.contactid
		left join ncrm_users on ncrm_users.id=ncrm_crmentity.smownerid
		left outer join ncrm_recurringevents on ncrm_recurringevents.activityid=
		ncrm_activity.activityid
		left join ncrm_groups on ncrm_groups.groupid=ncrm_crmentity.smownerid
		where ncrm_seactivityrel.crmid=".$id." and ncrm_crmentity.deleted=0 and
			activitytype='Task' and (ncrm_activity.status is not NULL and
			ncrm_activity.status != 'Completed') and (ncrm_activity.status is not NULL and
			ncrm_activity.status != 'Deferred')";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_activities method ...");
		return $return_value;
	}

	/**	function used to get the the activity history related to the quote
	 *	@param int $id - quote id
	 *	@return array - return an array which will be returned from the function GetHistory
	 */
	function get_history($id)
	{
		global $log;
		$log->debug("Entering get_history(".$id.") method ...");
		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'ncrm_users.first_name', 'last_name' => 'ncrm_users.last_name'), 'Users');
		$query = "SELECT ncrm_activity.activityid, ncrm_activity.subject, ncrm_activity.status,
			ncrm_activity.eventstatus, ncrm_activity.activitytype,ncrm_activity.date_start,
			ncrm_activity.due_date,ncrm_activity.time_start, ncrm_activity.time_end,
			ncrm_contactdetails.contactid,
			ncrm_contactdetails.firstname,ncrm_contactdetails.lastname, ncrm_crmentity.modifiedtime,
			ncrm_crmentity.createdtime, ncrm_crmentity.description, case when (ncrm_users.user_name not like '') then $userNameSql else ncrm_groups.groupname end as user_name
			from ncrm_activity
				inner join ncrm_seactivityrel on ncrm_seactivityrel.activityid=ncrm_activity.activityid
				inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_activity.activityid
				left join ncrm_cntactivityrel on ncrm_cntactivityrel.activityid= ncrm_activity.activityid
				left join ncrm_contactdetails on ncrm_contactdetails.contactid= ncrm_cntactivityrel.contactid
                                left join ncrm_groups on ncrm_groups.groupid=ncrm_crmentity.smownerid
				left join ncrm_users on ncrm_users.id=ncrm_crmentity.smownerid
				where ncrm_activity.activitytype='Task'
  				and (ncrm_activity.status = 'Completed' or ncrm_activity.status = 'Deferred')
	 	        	and ncrm_seactivityrel.crmid=".$id."
                                and ncrm_crmentity.deleted = 0";
		//Don't add order by, because, for security, one more condition will be added with this query in include/RelatedListView.php

		$log->debug("Exiting get_history method ...");
		return getHistory('Quotes',$query,$id);
	}





	/**	Function used to get the Quote Stage history of the Quotes
	 *	@param $id - quote id
	 *	@return $return_data - array with header and the entries in format Array('header'=>$header,'entries'=>$entries_list) where as $header and $entries_list are arrays which contains header values and all column values of all entries
	 */
	function get_quotestagehistory($id)
	{
		global $log;
		$log->debug("Entering get_quotestagehistory(".$id.") method ...");

		global $adb;
		global $mod_strings;
		global $app_strings;

		$query = 'select ncrm_quotestagehistory.*, ncrm_quotes.quote_no from ncrm_quotestagehistory inner join ncrm_quotes on ncrm_quotes.quoteid = ncrm_quotestagehistory.quoteid inner join ncrm_crmentity on ncrm_crmentity.crmid = ncrm_quotes.quoteid where ncrm_crmentity.deleted = 0 and ncrm_quotes.quoteid = ?';
		$result=$adb->pquery($query, array($id));
		$noofrows = $adb->num_rows($result);

		$header[] = $app_strings['Quote No'];
		$header[] = $app_strings['LBL_ACCOUNT_NAME'];
		$header[] = $app_strings['LBL_AMOUNT'];
		$header[] = $app_strings['Quote Stage'];
		$header[] = $app_strings['LBL_LAST_MODIFIED'];

		//Getting the field permission for the current user. 1 - Not Accessible, 0 - Accessible
		//Account Name , Total are mandatory fields. So no need to do security check to these fields.
		global $current_user;

		//If field is accessible then getFieldVisibilityPermission function will return 0 else return 1
		$quotestage_access = (getFieldVisibilityPermission('Quotes', $current_user->id, 'quotestage') != '0')? 1 : 0;
		$picklistarray = getAccessPickListValues('Quotes');

		$quotestage_array = ($quotestage_access != 1)? $picklistarray['quotestage']: array();
		//- ==> picklist field is not permitted in profile
		//Not Accessible - picklist is permitted in profile but picklist value is not permitted
		$error_msg = ($quotestage_access != 1)? 'Not Accessible': '-';

		while($row = $adb->fetch_array($result))
		{
			$entries = Array();

			// Module Sequence Numbering
			//$entries[] = $row['quoteid'];
			$entries[] = $row['quote_no'];
			// END
			$entries[] = $row['accountname'];
			$entries[] = $row['total'];
			$entries[] = (in_array($row['quotestage'], $quotestage_array))? $row['quotestage']: $error_msg;
			$date = new DateTimeField($row['lastmodified']);
			$entries[] = $date->getDisplayDateTimeValue();

			$entries_list[] = $entries;
		}

		$return_data = Array('header'=>$header,'entries'=>$entries_list);

	 	$log->debug("Exiting get_quotestagehistory method ...");

		return $return_data;
	}

	// Function to get column name - Overriding function of base class
	function get_column_value($columname, $fldvalue, $fieldname, $uitype, $datatype='') {
		if ($columname == 'potentialid' || $columname == 'contactid') {
			if ($fldvalue == '') return null;
		}
		return parent::get_column_value($columname, $fldvalue, $fieldname, $uitype, $datatype);
	}

	/*
	 * Function to get the secondary query part of a report
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule,$queryPlanner){
		$matrix = $queryPlanner->newDependencyMatrix();
		$matrix->setDependency('ncrm_crmentityQuotes', array('ncrm_usersQuotes', 'ncrm_groupsQuotes', 'ncrm_lastModifiedByQuotes'));
		$matrix->setDependency('ncrm_inventoryproductrelQuotes', array('ncrm_productsQuotes', 'ncrm_serviceQuotes'));
		$matrix->setDependency('ncrm_quotes',array('ncrm_crmentityQuotes', "ncrm_currency_info$secmodule",
				'ncrm_quotescf', 'ncrm_potentialRelQuotes', 'ncrm_quotesbillads','ncrm_quotesshipads',
				'ncrm_inventoryproductrelQuotes', 'ncrm_contactdetailsQuotes', 'ncrm_accountQuotes',
				'ncrm_invoice_recurring_info','ncrm_quotesQuotes','ncrm_usersRel1'));

		if (!$queryPlanner->requireTable('ncrm_quotes', $matrix)) {
			return '';
		}

		$query = $this->getRelationQuery($module,$secmodule,"ncrm_quotes","quoteid", $queryPlanner);
		if ($queryPlanner->requireTable("ncrm_crmentityQuotes", $matrix)){
			$query .= " left join ncrm_crmentity as ncrm_crmentityQuotes on ncrm_crmentityQuotes.crmid=ncrm_quotes.quoteid and ncrm_crmentityQuotes.deleted=0";
		}
		if ($queryPlanner->requireTable("ncrm_quotescf")){
			$query .= " left join ncrm_quotescf on ncrm_quotes.quoteid = ncrm_quotescf.quoteid";
		}
		if ($queryPlanner->requireTable("ncrm_quotesbillads")){
			$query .= " left join ncrm_quotesbillads on ncrm_quotes.quoteid=ncrm_quotesbillads.quotebilladdressid";
		}
		if ($queryPlanner->requireTable("ncrm_quotesshipads")){
			$query .= " left join ncrm_quotesshipads on ncrm_quotes.quoteid=ncrm_quotesshipads.quoteshipaddressid";
		}
		if ($queryPlanner->requireTable("ncrm_currency_info$secmodule")){
			$query .= " left join ncrm_currency_info as ncrm_currency_info$secmodule on ncrm_currency_info$secmodule.id = ncrm_quotes.currency_id";
		}
		if ($queryPlanner->requireTable("ncrm_inventoryproductrelQuotes",$matrix)){
			$query .= " left join ncrm_inventoryproductrel as ncrm_inventoryproductrelQuotes on ncrm_quotes.quoteid = ncrm_inventoryproductrelQuotes.id";
            // To Eliminate duplicates in reports
            if(($module == 'Products' || $module == 'Services') && $secmodule == "Quotes"){
                if($module == 'Products'){
                    $query .= " and ncrm_inventoryproductrelQuotes.productid = ncrm_products.productid ";    
                }else if($module== 'Services'){
                    $query .= " and ncrm_inventoryproductrelQuotes.productid = ncrm_service.serviceid ";
                }
            }
		}
		if ($queryPlanner->requireTable("ncrm_productsQuotes")){
			$query .= " left join ncrm_products as ncrm_productsQuotes on ncrm_productsQuotes.productid = ncrm_inventoryproductrelQuotes.productid";
		}
		if ($queryPlanner->requireTable("ncrm_serviceQuotes")){
			$query .= " left join ncrm_service as ncrm_serviceQuotes on ncrm_serviceQuotes.serviceid = ncrm_inventoryproductrelQuotes.productid";
		}
		if ($queryPlanner->requireTable("ncrm_groupsQuotes")){
			$query .= " left join ncrm_groups as ncrm_groupsQuotes on ncrm_groupsQuotes.groupid = ncrm_crmentityQuotes.smownerid";
		}
		if ($queryPlanner->requireTable("ncrm_usersQuotes")){
			$query .= " left join ncrm_users as ncrm_usersQuotes on ncrm_usersQuotes.id = ncrm_crmentityQuotes.smownerid";
		}
		if ($queryPlanner->requireTable("ncrm_usersRel1")){
			$query .= " left join ncrm_users as ncrm_usersRel1 on ncrm_usersRel1.id = ncrm_quotes.inventorymanager";
		}
		if ($queryPlanner->requireTable("ncrm_potentialRelQuotes")){
			$query .= " left join ncrm_potential as ncrm_potentialRelQuotes on ncrm_potentialRelQuotes.potentialid = ncrm_quotes.potentialid";
		}
		if ($queryPlanner->requireTable("ncrm_contactdetailsQuotes")){
			$query .= " left join ncrm_contactdetails as ncrm_contactdetailsQuotes on ncrm_contactdetailsQuotes.contactid = ncrm_quotes.contactid";
		}
		if ($queryPlanner->requireTable("ncrm_accountQuotes")){
			$query .= " left join ncrm_account as ncrm_accountQuotes on ncrm_accountQuotes.accountid = ncrm_quotes.accountid";
		}
		if ($queryPlanner->requireTable("ncrm_lastModifiedByQuotes")){
			$query .= " left join ncrm_users as ncrm_lastModifiedByQuotes on ncrm_lastModifiedByQuotes.id = ncrm_crmentityQuotes.modifiedby ";
		}
        if ($queryPlanner->requireTable("ncrm_createdbyQuotes")){
			$query .= " left join ncrm_users as ncrm_createdbyQuotes on ncrm_createdbyQuotes.id = ncrm_crmentityQuotes.smcreatorid ";
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
			"SalesOrder" =>array("ncrm_salesorder"=>array("quoteid","salesorderid"),"ncrm_quotes"=>"quoteid"),
			"Calendar" =>array("ncrm_seactivityrel"=>array("crmid","activityid"),"ncrm_quotes"=>"quoteid"),
			"Documents" => array("ncrm_senotesrel"=>array("crmid","notesid"),"ncrm_quotes"=>"quoteid"),
			"Accounts" => array("ncrm_quotes"=>array("quoteid","accountid")),
			"Contacts" => array("ncrm_quotes"=>array("quoteid","contactid")),
			"Potentials" => array("ncrm_quotes"=>array("quoteid","potentialid")),
		);
		return $rel_tables[$secmodule];
	}

	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;

		if($return_module == 'Accounts' ) {
			$this->trash('Quotes',$id);
		} elseif($return_module == 'Potentials') {
			$relation_query = 'UPDATE ncrm_quotes SET potentialid=? WHERE quoteid=?';
			$this->db->pquery($relation_query, array(null, $id));
		} elseif($return_module == 'Contacts') {
			$relation_query = 'UPDATE ncrm_quotes SET contactid=? WHERE quoteid=?';
			$this->db->pquery($relation_query, array(null, $id));
		} else {
			$sql = 'DELETE FROM ncrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
			$params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
			$this->db->pquery($sql, $params);
		}
	}

	function insertIntoEntityTable($table_name, $module, $fileid = '')  {
		//Ignore relation table insertions while saving of the record
		if($table_name == 'ncrm_inventoryproductrel') {
			return;
		}
		parent::insertIntoEntityTable($table_name, $module, $fileid);
	}

	/*Function to create records in current module.
	**This function called while importing records to this module*/
	function createRecords($obj) {
		$createRecords = createRecords($obj);
		return $createRecords;
	}

	/*Function returns the record information which means whether the record is imported or not
	**This function called while importing records to this module*/
	function importRecord($obj, $inventoryFieldData, $lineItemDetails) {
		$entityInfo = importRecord($obj, $inventoryFieldData, $lineItemDetails);
		return $entityInfo;
	}

	/*Function to return the status count of imported records in current module.
	**This function called while importing records to this module*/
	function getImportStatusCount($obj) {
		$statusCount = getImportStatusCount($obj);
		return $statusCount;
	}

	function undoLastImport($obj, $user) {
		$undoLastImport = undoLastImport($obj, $user);
	}

	/** Function to export the lead records in CSV Format
	* @param reference variable - where condition is passed when the query is executed
	* Returns Export Quotes Query.
	*/
	function create_export_query($where)
	{
		global $log;
		global $current_user;
		$log->debug("Entering create_export_query(".$where.") method ...");

		include("include/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Quotes", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);
		$fields_list .= getInventoryFieldsForExport($this->table_name);
		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>'ncrm_users.first_name', 'last_name' => 'ncrm_users.last_name'), 'Users');

		$query = "SELECT $fields_list FROM ".$this->entity_table."
				INNER JOIN ncrm_quotes ON ncrm_quotes.quoteid = ncrm_crmentity.crmid
				LEFT JOIN ncrm_quotescf ON ncrm_quotescf.quoteid = ncrm_quotes.quoteid
				LEFT JOIN ncrm_quotesbillads ON ncrm_quotesbillads.quotebilladdressid = ncrm_quotes.quoteid
				LEFT JOIN ncrm_quotesshipads ON ncrm_quotesshipads.quoteshipaddressid = ncrm_quotes.quoteid
				LEFT JOIN ncrm_inventoryproductrel ON ncrm_inventoryproductrel.id = ncrm_quotes.quoteid
				LEFT JOIN ncrm_products ON ncrm_products.productid = ncrm_inventoryproductrel.productid
				LEFT JOIN ncrm_service ON ncrm_service.serviceid = ncrm_inventoryproductrel.productid
				LEFT JOIN ncrm_contactdetails ON ncrm_contactdetails.contactid = ncrm_quotes.contactid
				LEFT JOIN ncrm_potential ON ncrm_potential.potentialid = ncrm_quotes.potentialid
				LEFT JOIN ncrm_account ON ncrm_account.accountid = ncrm_quotes.accountid
				LEFT JOIN ncrm_currency_info ON ncrm_currency_info.id = ncrm_quotes.currency_id
				LEFT JOIN ncrm_users AS ncrm_inventoryManager ON ncrm_inventoryManager.id = ncrm_quotes.inventorymanager
				LEFT JOIN ncrm_groups ON ncrm_groups.groupid = ncrm_crmentity.smownerid
				LEFT JOIN ncrm_users ON ncrm_users.id = ncrm_crmentity.smownerid";

		$query .= $this->getNonAdminAccessControlQuery('Quotes',$current_user);
		$where_auto = " ncrm_crmentity.deleted=0";

		if($where != "") {
			$query .= " where ($where) AND ".$where_auto;
		} else {
			$query .= " where ".$where_auto;
		}

		$log->debug("Exiting create_export_query method ...");
		return $query;
	}

}

?>
