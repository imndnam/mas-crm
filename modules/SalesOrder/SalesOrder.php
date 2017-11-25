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
class SalesOrder extends CRMEntity {
	var $log;
	var $db;

	var $table_name = "ncrm_salesorder";
	var $table_index= 'salesorderid';
	var $tab_name = Array('ncrm_crmentity','ncrm_salesorder','ncrm_sobillads','ncrm_soshipads','ncrm_salesordercf','ncrm_invoice_recurring_info','ncrm_inventoryproductrel');
	var $tab_name_index = Array('ncrm_crmentity'=>'crmid','ncrm_salesorder'=>'salesorderid','ncrm_sobillads'=>'sobilladdressid','ncrm_soshipads'=>'soshipaddressid','ncrm_salesordercf'=>'salesorderid','ncrm_invoice_recurring_info'=>'salesorderid','ncrm_inventoryproductrel'=>'id');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('ncrm_salesordercf', 'salesorderid');
	var $entity_table = "ncrm_crmentity";

	var $billadr_table = "ncrm_sobillads";

	var $object_name = "SalesOrder";

	var $new_schema = true;

	var $update_product_array = Array();

	var $column_fields = Array();

	var $sortby_fields = Array('subject','smownerid','accountname','lastname');

	// This is used to retrieve related ncrm_fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'smownerid', 'opportunity_id', 'case_id', 'contact_id', 'task_id', 'note_id', 'meeting_id', 'call_id', 'email_id', 'parent_name', 'member_id' );

	// This is the list of ncrm_fields that are in the lists.
	var $list_fields = Array(
				// Module Sequence Numbering
				//'Order No'=>Array('crmentity'=>'crmid'),
				'Order No'=>Array('salesorder','salesorder_no'),
				// END
				'Subject'=>Array('salesorder'=>'subject'),
				'Account Name'=>Array('account'=>'accountid'),
				'Quote Name'=>Array('quotes'=>'quoteid'),
				'Total'=>Array('salesorder'=>'total'),
				'Assigned To'=>Array('crmentity'=>'smownerid')
				);

	var $list_fields_name = Array(
				        'Order No'=>'salesorder_no',
				        'Subject'=>'subject',
				        'Account Name'=>'account_id',
				        'Quote Name'=>'quote_id',
					'Total'=>'hdnGrandTotal',
				        'Assigned To'=>'assigned_user_id'
				      );
	var $list_link_field= 'subject';

	var $search_fields = Array(
				'Order No'=>Array('salesorder'=>'salesorder_no'),
				'Subject'=>Array('salesorder'=>'subject'),
				'Account Name'=>Array('account'=>'accountid'),
				'Quote Name'=>Array('salesorder'=>'quoteid')
				);

	var $search_fields_name = Array(
					'Order No'=>'salesorder_no',
				        'Subject'=>'subject',
				        'Account Name'=>'account_id',
				        'Quote Name'=>'quote_id'
				      );

	// This is the list of ncrm_fields that are required.
	var $required_fields =  array("accountname"=>1);

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'subject';
	var $default_sort_order = 'ASC';
	//var $groupTable = Array('ncrm_sogrouprelation','salesorderid');

	var $mandatory_fields = Array('subject','createdtime' ,'modifiedtime', 'assigned_user_id');

	// For Alphabetical search
	var $def_basicsearch_col = 'subject';

	// For workflows update field tasks is deleted all the lineitems.
	var $isLineItemUpdate = true;

	/** Constructor Function for SalesOrder class
	 *  This function creates an instance of LoggerManager class using getLogger method
	 *  creates an instance for PearDatabase class and get values for column_fields array of SalesOrder class.
	 */
	function SalesOrder() {
		$this->log =LoggerManager::getLogger('SalesOrder');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('SalesOrder');
	}

	function save_module($module)
	{

		//Checking if quote_id is present and updating the quote status
		if($this->column_fields["quote_id"] != '')
		{
        		$qt_id = $this->column_fields["quote_id"];
        		$query1 = "update ncrm_quotes set quotestage='Accepted' where quoteid=?";
        		$this->db->pquery($query1, array($qt_id));
		}

		//in ajax save we should not call this function, because this will delete all the existing product values
		if($_REQUEST['action'] != 'SalesOrderAjax' && $_REQUEST['ajxaction'] != 'DETAILVIEW'
				&& $_REQUEST['action'] != 'MassEditSave' && $_REQUEST['action'] != 'ProcessDuplicates'
				&& $_REQUEST['action'] != 'SaveAjax' && $this->isLineItemUpdate != false) {
			//Based on the total Number of rows we will save the product relationship with this entity
			saveInventoryProductDetails($this, 'SalesOrder');
		}

		// Update the currency id and the conversion rate for the sales order
		$update_query = "update ncrm_salesorder set currency_id=?, conversion_rate=? where salesorderid=?";
		$update_params = array($this->column_fields['currency_id'], $this->column_fields['conversion_rate'], $this->id);
		$this->db->pquery($update_query, $update_params);
	}

	/** Function to get activities associated with the Sales Order
	 *  This function accepts the id as arguments and execute the MySQL query using the id
	 *  and sends the query and the id as arguments to renderRelatedActivities() method
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
		$query = "SELECT case when (ncrm_users.user_name not like '') then $userNameSql else ncrm_groups.groupname end as user_name,ncrm_contactdetails.lastname, ncrm_contactdetails.firstname, ncrm_contactdetails.contactid, ncrm_activity.*,ncrm_seactivityrel.crmid as parent_id,ncrm_crmentity.crmid, ncrm_crmentity.smownerid, ncrm_crmentity.modifiedtime from ncrm_activity inner join ncrm_seactivityrel on ncrm_seactivityrel.activityid=ncrm_activity.activityid inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_activity.activityid left join ncrm_cntactivityrel on ncrm_cntactivityrel.activityid= ncrm_activity.activityid left join ncrm_contactdetails on ncrm_contactdetails.contactid = ncrm_cntactivityrel.contactid left join ncrm_users on ncrm_users.id=ncrm_crmentity.smownerid left join ncrm_groups on ncrm_groups.groupid=ncrm_crmentity.smownerid where ncrm_seactivityrel.crmid=".$id." and activitytype='Task' and ncrm_crmentity.deleted=0 and (ncrm_activity.status is not NULL and ncrm_activity.status != 'Completed') and (ncrm_activity.status is not NULL and ncrm_activity.status !='Deferred')";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_activities method ...");
		return $return_value;
	}

	/** Function to get the activities history associated with the Sales Order
	 *  This function accepts the id as arguments and execute the MySQL query using the id
	 *  and sends the query and the id as arguments to renderRelatedHistory() method
	 */
	function get_history($id)
	{
		global $log;
		$log->debug("Entering get_history(".$id.") method ...");
		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'ncrm_users.first_name', 'last_name' => 'ncrm_users.last_name'), 'Users');
		$query = "SELECT ncrm_contactdetails.lastname, ncrm_contactdetails.firstname,
			ncrm_contactdetails.contactid,ncrm_activity.*, ncrm_seactivityrel.*,
			ncrm_crmentity.crmid, ncrm_crmentity.smownerid, ncrm_crmentity.modifiedtime,
			ncrm_crmentity.createdtime, ncrm_crmentity.description, case when
			(ncrm_users.user_name not like '') then $userNameSql else ncrm_groups.groupname
			end as user_name from ncrm_activity
				inner join ncrm_seactivityrel on ncrm_seactivityrel.activityid=ncrm_activity.activityid
				inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_activity.activityid
				left join ncrm_cntactivityrel on ncrm_cntactivityrel.activityid= ncrm_activity.activityid
				left join ncrm_contactdetails on ncrm_contactdetails.contactid = ncrm_cntactivityrel.contactid
                                left join ncrm_groups on ncrm_groups.groupid=ncrm_crmentity.smownerid
				left join ncrm_users on ncrm_users.id=ncrm_crmentity.smownerid
			where activitytype='Task'
				and (ncrm_activity.status = 'Completed' or ncrm_activity.status = 'Deferred')
				and ncrm_seactivityrel.crmid=".$id."
                                and ncrm_crmentity.deleted = 0";
		//Don't add order by, because, for security, one more condition will be added with this query in include/RelatedListView.php

		$log->debug("Exiting get_history method ...");
		return getHistory('SalesOrder',$query,$id);
	}



	/** Function to get the invoices associated with the Sales Order
	 *  This function accepts the id as arguments and execute the MySQL query using the id
	 *  and sends the query and the id as arguments to renderRelatedInvoices() method.
	 */
	function get_invoices($id)
	{
		global $log,$singlepane_view;
		$log->debug("Entering get_invoices(".$id.") method ...");
		require_once('modules/Invoice/Invoice.php');

		$focus = new Invoice();

		$button = '';
		if($singlepane_view == 'true')
			$returnset = '&return_module=SalesOrder&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=SalesOrder&return_action=CallRelatedList&return_id='.$id;

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'ncrm_users.first_name', 'last_name' => 'ncrm_users.last_name'), 'Users');
		$query = "select ncrm_crmentity.*, ncrm_invoice.*, ncrm_account.accountname,
			ncrm_salesorder.subject as salessubject, case when
			(ncrm_users.user_name not like '') then $userNameSql else ncrm_groups.groupname
			end as user_name from ncrm_invoice
			inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_invoice.invoiceid
			left outer join ncrm_account on ncrm_account.accountid=ncrm_invoice.accountid
			inner join ncrm_salesorder on ncrm_salesorder.salesorderid=ncrm_invoice.salesorderid
            LEFT JOIN ncrm_invoicecf ON ncrm_invoicecf.invoiceid = ncrm_invoice.invoiceid
			LEFT JOIN ncrm_invoicebillads ON ncrm_invoicebillads.invoicebilladdressid = ncrm_invoice.invoiceid
			LEFT JOIN ncrm_invoiceshipads ON ncrm_invoiceshipads.invoiceshipaddressid = ncrm_invoice.invoiceid
			left join ncrm_users on ncrm_users.id=ncrm_crmentity.smownerid
			left join ncrm_groups on ncrm_groups.groupid=ncrm_crmentity.smownerid
			where ncrm_crmentity.deleted=0 and ncrm_salesorder.salesorderid=".$id;

		$log->debug("Exiting get_invoices method ...");
		return GetRelatedList('SalesOrder','Invoice',$focus,$query,$button,$returnset);

	}

	/**	Function used to get the Status history of the Sales Order
	 *	@param $id - salesorder id
	 *	@return $return_data - array with header and the entries in format Array('header'=>$header,'entries'=>$entries_list) where as $header and $entries_list are arrays which contains header values and all column values of all entries
	 */
	function get_sostatushistory($id)
	{
		global $log;
		$log->debug("Entering get_sostatushistory(".$id.") method ...");

		global $adb;
		global $mod_strings;
		global $app_strings;

		$query = 'select ncrm_sostatushistory.*, ncrm_salesorder.salesorder_no from ncrm_sostatushistory inner join ncrm_salesorder on ncrm_salesorder.salesorderid = ncrm_sostatushistory.salesorderid inner join ncrm_crmentity on ncrm_crmentity.crmid = ncrm_salesorder.salesorderid where ncrm_crmentity.deleted = 0 and ncrm_salesorder.salesorderid = ?';
		$result=$adb->pquery($query, array($id));
		$noofrows = $adb->num_rows($result);

		$header[] = $app_strings['Order No'];
		$header[] = $app_strings['LBL_ACCOUNT_NAME'];
		$header[] = $app_strings['LBL_AMOUNT'];
		$header[] = $app_strings['LBL_SO_STATUS'];
		$header[] = $app_strings['LBL_LAST_MODIFIED'];

		//Getting the field permission for the current user. 1 - Not Accessible, 0 - Accessible
		//Account Name , Total are mandatory fields. So no need to do security check to these fields.
		global $current_user;

		//If field is accessible then getFieldVisibilityPermission function will return 0 else return 1
		$sostatus_access = (getFieldVisibilityPermission('SalesOrder', $current_user->id, 'sostatus') != '0')? 1 : 0;
		$picklistarray = getAccessPickListValues('SalesOrder');

		$sostatus_array = ($sostatus_access != 1)? $picklistarray['sostatus']: array();
		//- ==> picklist field is not permitted in profile
		//Not Accessible - picklist is permitted in profile but picklist value is not permitted
		$error_msg = ($sostatus_access != 1)? 'Not Accessible': '-';

		while($row = $adb->fetch_array($result))
		{
			$entries = Array();

			// Module Sequence Numbering
			//$entries[] = $row['salesorderid'];
			$entries[] = $row['salesorder_no'];
			// END
			$entries[] = $row['accountname'];
			$entries[] = $row['total'];
			$entries[] = (in_array($row['sostatus'], $sostatus_array))? $row['sostatus']: $error_msg;
			$date = new DateTimeField($row['lastmodified']);
			$entries[] = $date->getDisplayDateTimeValue();

			$entries_list[] = $entries;
		}

		$return_data = Array('header'=>$header,'entries'=>$entries_list);

	 	$log->debug("Exiting get_sostatushistory method ...");

		return $return_data;
	}

	/*
	 * Function to get the secondary query part of a report
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule,$queryPlanner){
		$matrix = $queryPlanner->newDependencyMatrix();
		$matrix->setDependency('ncrm_crmentitySalesOrder', array('ncrm_usersSalesOrder', 'ncrm_groupsSalesOrder', 'ncrm_lastModifiedBySalesOrder'));
		$matrix->setDependency('ncrm_inventoryproductrelSalesOrder', array('ncrm_productsSalesOrder', 'ncrm_serviceSalesOrder'));
		$matrix->setDependency('ncrm_salesorder',array('ncrm_crmentitySalesOrder', "ncrm_currency_info$secmodule",
				'ncrm_salesordercf', 'ncrm_potentialRelSalesOrder', 'ncrm_sobillads','ncrm_soshipads',
				'ncrm_inventoryproductrelSalesOrder', 'ncrm_contactdetailsSalesOrder', 'ncrm_accountSalesOrder',
				'ncrm_invoice_recurring_info','ncrm_quotesSalesOrder'));

		if (!$queryPlanner->requireTable('ncrm_salesorder', $matrix)) {
			return '';
		}

		$query = $this->getRelationQuery($module,$secmodule,"ncrm_salesorder","salesorderid", $queryPlanner);
		if ($queryPlanner->requireTable("ncrm_crmentitySalesOrder",$matrix)){
			$query .= " left join ncrm_crmentity as ncrm_crmentitySalesOrder on ncrm_crmentitySalesOrder.crmid=ncrm_salesorder.salesorderid and ncrm_crmentitySalesOrder.deleted=0";
		}
		if ($queryPlanner->requireTable("ncrm_salesordercf")){
			$query .= " left join ncrm_salesordercf on ncrm_salesorder.salesorderid = ncrm_salesordercf.salesorderid";
		}
		if ($queryPlanner->requireTable("ncrm_sobillads")){
			$query .= " left join ncrm_sobillads on ncrm_salesorder.salesorderid=ncrm_sobillads.sobilladdressid";
		}
		if ($queryPlanner->requireTable("ncrm_soshipads")){
			$query .= " left join ncrm_soshipads on ncrm_salesorder.salesorderid=ncrm_soshipads.soshipaddressid";
		}
		if ($queryPlanner->requireTable("ncrm_currency_info$secmodule")){
			$query .= " left join ncrm_currency_info as ncrm_currency_info$secmodule on ncrm_currency_info$secmodule.id = ncrm_salesorder.currency_id";
		}
		if ($queryPlanner->requireTable("ncrm_inventoryproductrelSalesOrder", $matrix)){
			$query .= " left join ncrm_inventoryproductrel as ncrm_inventoryproductrelSalesOrder on ncrm_salesorder.salesorderid = ncrm_inventoryproductrelSalesOrder.id";
            // To Eliminate duplicates in reports
            if(($module == 'Products' || $module == 'Services') && $secmodule == "SalesOrder"){
                if($module == 'Products'){
                    $query .= " and ncrm_inventoryproductrelSalesOrder.productid = ncrm_products.productid ";    
                }else if($module == 'Services'){
                    $query .= " and ncrm_inventoryproductrelSalesOrder.productid = ncrm_service.serviceid "; 
                }
            }
		}
		if ($queryPlanner->requireTable("ncrm_productsSalesOrder")){
			$query .= " left join ncrm_products as ncrm_productsSalesOrder on ncrm_productsSalesOrder.productid = ncrm_inventoryproductrelSalesOrder.productid";
		}
		if ($queryPlanner->requireTable("ncrm_serviceSalesOrder")){
			$query .= " left join ncrm_service as ncrm_serviceSalesOrder on ncrm_serviceSalesOrder.serviceid = ncrm_inventoryproductrelSalesOrder.productid";
		}
		if ($queryPlanner->requireTable("ncrm_groupsSalesOrder")){
			$query .= " left join ncrm_groups as ncrm_groupsSalesOrder on ncrm_groupsSalesOrder.groupid = ncrm_crmentitySalesOrder.smownerid";
		}
		if ($queryPlanner->requireTable("ncrm_usersSalesOrder")){
			$query .= " left join ncrm_users as ncrm_usersSalesOrder on ncrm_usersSalesOrder.id = ncrm_crmentitySalesOrder.smownerid";
		}
		if ($queryPlanner->requireTable("ncrm_potentialRelSalesOrder")){
			$query .= " left join ncrm_potential as ncrm_potentialRelSalesOrder on ncrm_potentialRelSalesOrder.potentialid = ncrm_salesorder.potentialid";
		}
		if ($queryPlanner->requireTable("ncrm_contactdetailsSalesOrder")){
			$query .= " left join ncrm_contactdetails as ncrm_contactdetailsSalesOrder on ncrm_salesorder.contactid = ncrm_contactdetailsSalesOrder.contactid";
		}
		if ($queryPlanner->requireTable("ncrm_invoice_recurring_info")){
			$query .= " left join ncrm_invoice_recurring_info on ncrm_salesorder.salesorderid = ncrm_invoice_recurring_info.salesorderid";
		}
		if ($queryPlanner->requireTable("ncrm_quotesSalesOrder")){
			$query .= " left join ncrm_quotes as ncrm_quotesSalesOrder on ncrm_salesorder.quoteid = ncrm_quotesSalesOrder.quoteid";
		}
		if ($queryPlanner->requireTable("ncrm_accountSalesOrder")){
			$query .= " left join ncrm_account as ncrm_accountSalesOrder on ncrm_accountSalesOrder.accountid = ncrm_salesorder.accountid";
		}
		if ($queryPlanner->requireTable("ncrm_lastModifiedBySalesOrder")){
			$query .= " left join ncrm_users as ncrm_lastModifiedBySalesOrder on ncrm_lastModifiedBySalesOrder.id = ncrm_crmentitySalesOrder.modifiedby ";
		}
        if ($queryPlanner->requireTable("ncrm_createdbySalesOrder")){
			$query .= " left join ncrm_users as ncrm_createdbySalesOrder on ncrm_createdbySalesOrder.id = ncrm_crmentitySalesOrder.smcreatorid ";
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
			"Calendar" =>array("ncrm_seactivityrel"=>array("crmid","activityid"),"ncrm_salesorder"=>"salesorderid"),
			"Invoice" =>array("ncrm_invoice"=>array("salesorderid","invoiceid"),"ncrm_salesorder"=>"salesorderid"),
			"Documents" => array("ncrm_senotesrel"=>array("crmid","notesid"),"ncrm_salesorder"=>"salesorderid"),
		);
		return $rel_tables[$secmodule];
	}

	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;

		if($return_module == 'Accounts') {
			$this->trash('SalesOrder',$id);
		}
		elseif($return_module == 'Quotes') {
			$relation_query = 'UPDATE ncrm_salesorder SET quoteid=? WHERE salesorderid=?';
			$this->db->pquery($relation_query, array(null, $id));
		}
		elseif($return_module == 'Potentials') {
			$relation_query = 'UPDATE ncrm_salesorder SET potentialid=? WHERE salesorderid=?';
			$this->db->pquery($relation_query, array(null, $id));
		}
		elseif($return_module == 'Contacts') {
			$relation_query = 'UPDATE ncrm_salesorder SET contactid=? WHERE salesorderid=?';
			$this->db->pquery($relation_query, array(null, $id));
		} else {
			$sql = 'DELETE FROM ncrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
			$params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
			$this->db->pquery($sql, $params);
		}
	}

	public function getJoinClause($tableName) {
		if ($tableName == 'ncrm_invoice_recurring_info') {
			return 'LEFT JOIN';
		}
		return parent::getJoinClause($tableName);
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
	* Returns Export SalesOrder Query.
	*/
	function create_export_query($where)
	{
		global $log;
		global $current_user;
		$log->debug("Entering create_export_query(".$where.") method ...");

		include("include/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("SalesOrder", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);
		$fields_list .= getInventoryFieldsForExport($this->table_name);
		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>'ncrm_users.first_name', 'last_name' => 'ncrm_users.last_name'), 'Users');

		$query = "SELECT $fields_list FROM ".$this->entity_table."
				INNER JOIN ncrm_salesorder ON ncrm_salesorder.salesorderid = ncrm_crmentity.crmid
				LEFT JOIN ncrm_salesordercf ON ncrm_salesordercf.salesorderid = ncrm_salesorder.salesorderid
				LEFT JOIN ncrm_sobillads ON ncrm_sobillads.sobilladdressid = ncrm_salesorder.salesorderid
				LEFT JOIN ncrm_soshipads ON ncrm_soshipads.soshipaddressid = ncrm_salesorder.salesorderid
				LEFT JOIN ncrm_inventoryproductrel ON ncrm_inventoryproductrel.id = ncrm_salesorder.salesorderid
				LEFT JOIN ncrm_products ON ncrm_products.productid = ncrm_inventoryproductrel.productid
				LEFT JOIN ncrm_service ON ncrm_service.serviceid = ncrm_inventoryproductrel.productid
				LEFT JOIN ncrm_contactdetails ON ncrm_contactdetails.contactid = ncrm_salesorder.contactid
				LEFT JOIN ncrm_invoice_recurring_info ON ncrm_invoice_recurring_info.salesorderid = ncrm_salesorder.salesorderid
				LEFT JOIN ncrm_potential ON ncrm_potential.potentialid = ncrm_salesorder.potentialid
				LEFT JOIN ncrm_account ON ncrm_account.accountid = ncrm_salesorder.accountid
				LEFT JOIN ncrm_currency_info ON ncrm_currency_info.id = ncrm_salesorder.currency_id
				LEFT JOIN ncrm_quotes ON ncrm_quotes.quoteid = ncrm_salesorder.quoteid
				LEFT JOIN ncrm_groups ON ncrm_groups.groupid = ncrm_crmentity.smownerid
				LEFT JOIN ncrm_users ON ncrm_users.id = ncrm_crmentity.smownerid";

		$query .= $this->getNonAdminAccessControlQuery('SalesOrder',$current_user);
		$where_auto = " ncrm_crmentity.deleted=0";

		if($where != "") {
			$query .= " where ($where) AND ".$where_auto;
		} else {
			$query .= " where ".$where_auto;
		}

		$log->debug("Exiting create_export_query method ...");
		return $query;
	}

    /**
	 * Function which will give the basic query to find duplicates
	 * @param <String> $module
	 * @param <String> $tableColumns
	 * @param <String> $selectedColumns
	 * @param <Boolean> $ignoreEmpty
	 * @return string
	 */
	// Note : remove getDuplicatesQuery API once ncrm5 code is removed
    function getQueryForDuplicates($module, $tableColumns, $selectedColumns = '', $ignoreEmpty = false) {
		if(is_array($tableColumns)) {
			$tableColumnsString = implode(',', $tableColumns);
		}
        $selectClause = "SELECT " . $this->table_name . "." . $this->table_index . " AS recordid," . $tableColumnsString;

        // Select Custom Field Table Columns if present
        if (isset($this->customFieldTable))
            $query .= ", " . $this->customFieldTable[0] . ".* ";

        $fromClause = " FROM $this->table_name";

        $fromClause .= " INNER JOIN ncrm_crmentity ON ncrm_crmentity.crmid = $this->table_name.$this->table_index";

		if($this->tab_name) {
			foreach($this->tab_name as $tableName) {
				if($tableName != 'ncrm_crmentity' && $tableName != $this->table_name && $tableName != 'ncrm_inventoryproductrel') {
                    if($tableName == 'ncrm_invoice_recurring_info') {
						$fromClause .= " LEFT JOIN " . $tableName . " ON " . $tableName . '.' . $this->tab_name_index[$tableName] .
							" = $this->table_name.$this->table_index";
					}elseif($this->tab_name_index[$tableName]) {
						$fromClause .= " INNER JOIN " . $tableName . " ON " . $tableName . '.' . $this->tab_name_index[$tableName] .
							" = $this->table_name.$this->table_index";
					}
				}
			}
		}
        $fromClause .= " LEFT JOIN ncrm_users ON ncrm_users.id = ncrm_crmentity.smownerid
						LEFT JOIN ncrm_groups ON ncrm_groups.groupid = ncrm_crmentity.smownerid";

        $whereClause = " WHERE ncrm_crmentity.deleted = 0";
        $whereClause .= $this->getListViewSecurityParameter($module);

		if($ignoreEmpty) {
			foreach($tableColumns as $tableColumn){
				$whereClause .= " AND ($tableColumn IS NOT NULL AND $tableColumn != '') ";
			}
		}

        if (isset($selectedColumns) && trim($selectedColumns) != '') {
            $sub_query = "SELECT $selectedColumns FROM $this->table_name AS t " .
                    " INNER JOIN ncrm_crmentity AS crm ON crm.crmid = t." . $this->table_index;
            // Consider custom table join as well.
            if (isset($this->customFieldTable)) {
                $sub_query .= " LEFT JOIN " . $this->customFieldTable[0] . " tcf ON tcf." . $this->customFieldTable[1] . " = t.$this->table_index";
            }
            $sub_query .= " WHERE crm.deleted=0 GROUP BY $selectedColumns HAVING COUNT(*)>1";
        } else {
            $sub_query = "SELECT $tableColumnsString $fromClause $whereClause GROUP BY $tableColumnsString HAVING COUNT(*)>1";
        }

		$i = 1;
		foreach($tableColumns as $tableColumn){
			$tableInfo = explode('.', $tableColumn);
			$duplicateCheckClause .= " ifnull($tableColumn,'null') = ifnull(temp.$tableInfo[1],'null')";
			if (count($tableColumns) != $i++) $duplicateCheckClause .= " AND ";
		}

        $query = $selectClause . $fromClause .
                " LEFT JOIN ncrm_users_last_import ON ncrm_users_last_import.bean_id=" . $this->table_name . "." . $this->table_index .
                " INNER JOIN (" . $sub_query . ") AS temp ON " . $duplicateCheckClause .
                $whereClause .
                " ORDER BY $tableColumnsString," . $this->table_name . "." . $this->table_index . " ASC";
        return $query;
    }

}

?>
