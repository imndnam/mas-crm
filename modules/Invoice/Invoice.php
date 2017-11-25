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

class Invoice extends CRMEntity {
	var $log;
	var $db;

	var $table_name = "ncrm_invoice";
	var $table_index= 'invoiceid';
	var $tab_name = Array('ncrm_crmentity','ncrm_invoice','ncrm_invoicebillads','ncrm_invoiceshipads','ncrm_invoicecf', 'ncrm_inventoryproductrel');
	var $tab_name_index = Array('ncrm_crmentity'=>'crmid','ncrm_invoice'=>'invoiceid','ncrm_invoicebillads'=>'invoicebilladdressid','ncrm_invoiceshipads'=>'invoiceshipaddressid','ncrm_invoicecf'=>'invoiceid','ncrm_inventoryproductrel'=>'id');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('ncrm_invoicecf', 'invoiceid');

	var $column_fields = Array();

	var $update_product_array = Array();

	var $sortby_fields = Array('subject','invoice_no','invoicestatus','smownerid','accountname','lastname');

	// This is used to retrieve related ncrm_fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'smownerid', 'opportunity_id', 'case_id', 'contact_id', 'task_id', 'note_id', 'meeting_id', 'call_id', 'email_id', 'parent_name', 'member_id' );

	// This is the list of ncrm_fields that are in the lists.
	var $list_fields = Array(
				//'Invoice No'=>Array('crmentity'=>'crmid'),
				'Invoice No'=>Array('invoice'=>'invoice_no'),
				'Subject'=>Array('invoice'=>'subject'),
				'Sales Order'=>Array('invoice'=>'salesorderid'),
				'Status'=>Array('invoice'=>'invoicestatus'),
				'Total'=>Array('invoice'=>'total'),
				'Assigned To'=>Array('crmentity'=>'smownerid')
				);

	var $list_fields_name = Array(
				        'Invoice No'=>'invoice_no',
				        'Subject'=>'subject',
				        'Sales Order'=>'salesorder_id',
				        'Status'=>'invoicestatus',
				        'Total'=>'hdnGrandTotal',
				        'Assigned To'=>'assigned_user_id'
				      );
	var $list_link_field= 'subject';

	var $search_fields = Array(
				//'Invoice No'=>Array('crmentity'=>'crmid'),
				'Invoice No'=>Array('invoice'=>'invoice_no'),
				'Subject'=>Array('purchaseorder'=>'subject'),
                'Account Name'=>Array('contactdetails'=>'account_id'),
                'Created Date' => Array('crmentity'=>'createdtime'),
                'Assigned To'=>Array('crmentity'=>'smownerid'),
				);

	var $search_fields_name = Array(
				        'Invoice No'          => 'invoice_no',
				        'Subject'             => 'subject',
                        'Account Name'        => 'account_id',
                        'Created Time'        => 'createdtime',
                        'Assigned To'         => 'assigned_user_id'
				      );

	// This is the list of ncrm_fields that are required.
	var $required_fields =  array("accountname"=>1);

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'crmid';
	var $default_sort_order = 'ASC';

	//var $groupTable = Array('ncrm_invoicegrouprelation','invoiceid');

	var $mandatory_fields = Array('subject','createdtime' ,'modifiedtime', 'assigned_user_id');
	var $_salesorderid;
	var $_recurring_mode;

	// For Alphabetical search
	var $def_basicsearch_col = 'subject';

	var $entity_table = "ncrm_crmentity";

	// For workflows update field tasks is deleted all the lineitems.
	var $isLineItemUpdate = true;

	/**	Constructor which will set the column_fields in this object
	 */
	function Invoice() {
		$this->log =LoggerManager::getLogger('Invoice');
		$this->log->debug("Entering Invoice() method ...");
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Invoice');
		$this->log->debug("Exiting Invoice method ...");
	}


	/** Function to handle the module specific save operations

	*/

	function save_module($module) {
		global $updateInventoryProductRel_deduct_stock;
		$updateInventoryProductRel_deduct_stock = true;

		//in ajax save we should not call this function, because this will delete all the existing product values
		if(isset($this->_recurring_mode) && $this->_recurring_mode == 'recurringinvoice_from_so' && isset($this->_salesorderid) && $this->_salesorderid!='') {
			// We are getting called from the RecurringInvoice cron service!
			$this->createRecurringInvoiceFromSO();

		} else if(isset($_REQUEST)) {
			if($_REQUEST['action'] != 'InvoiceAjax' && $_REQUEST['ajxaction'] != 'DETAILVIEW'
					&& $_REQUEST['action'] != 'MassEditSave' && $_REQUEST['action'] != 'ProcessDuplicates'
					&& $_REQUEST['action'] != 'SaveAjax' && $this->isLineItemUpdate != false && $_REQUEST['action'] != 'FROM_WS') {
				//Based on the total Number of rows we will save the product relationship with this entity
				saveInventoryProductDetails($this, 'Invoice');
			} else if($_REQUEST['action'] == 'InvoiceAjax' || $_REQUEST['action'] == 'MassEditSave' || $_REQUEST['action'] == 'FROM_WS') {
				$updateInventoryProductRel_deduct_stock = false;
			}
		}
		// Update the currency id and the conversion rate for the invoice
		$update_query = "update ncrm_invoice set currency_id=?, conversion_rate=? where invoiceid=?";

		$update_params = array($this->column_fields['currency_id'], $this->column_fields['conversion_rate'], $this->id);
		$this->db->pquery($update_query, $update_params);
	}

	/**
	 * Customizing the restore procedure.
	 */
	function restore($module, $id) {
		global $updateInventoryProductRel_deduct_stock;
		$status = getInvoiceStatus($id);
		if($status != 'Cancel') {
			$updateInventoryProductRel_deduct_stock = true;
		}
		parent::restore($module, $id);
	}

	/**
	 * Customizing the Delete procedure.
	 */
	function trash($module, $recordId) {
		$status = getInvoiceStatus($recordId);
		if($status != 'Cancel') {
			addProductsToStock($recordId);
		}
		parent::trash($module, $recordId);
	}

	/**	function used to get the name of the current object
	 *	@return string $this->name - name of the current object
	 */
	function get_summary_text()
	{
		global $log;
		$log->debug("Entering get_summary_text() method ...");
		$log->debug("Exiting get_summary_text method ...");
		return $this->name;
	}


	/**	function used to get the list of activities which are related to the invoice
	 *	@param int $id - invoice id
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
		$query = "SELECT case when (ncrm_users.user_name not like '') then $userNameSql else ncrm_groups.groupname end as user_name,
				ncrm_contactdetails.lastname, ncrm_contactdetails.firstname, ncrm_contactdetails.contactid,
				ncrm_activity.*,ncrm_seactivityrel.crmid as parent_id,ncrm_crmentity.crmid, ncrm_crmentity.smownerid,
				ncrm_crmentity.modifiedtime
				from ncrm_activity
				inner join ncrm_seactivityrel on ncrm_seactivityrel.activityid=ncrm_activity.activityid
				inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_activity.activityid
				left join ncrm_cntactivityrel on ncrm_cntactivityrel.activityid= ncrm_activity.activityid
				left join ncrm_contactdetails on ncrm_contactdetails.contactid = ncrm_cntactivityrel.contactid
				left join ncrm_users on ncrm_users.id=ncrm_crmentity.smownerid
				left join ncrm_groups on ncrm_groups.groupid=ncrm_crmentity.smownerid
				where ncrm_seactivityrel.crmid=".$id." and activitytype='Task' and ncrm_crmentity.deleted=0
						and (ncrm_activity.status is not NULL and ncrm_activity.status != 'Completed')
						and (ncrm_activity.status is not NULL and ncrm_activity.status != 'Deferred')";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_activities method ...");
		return $return_value;
	}

	/**	function used to get the the activity history related to the quote
	 *	@param int $id - invoice id
	 *	@return array - return an array which will be returned from the function GetHistory
	 */
	function get_history($id)
	{
		global $log;
		$log->debug("Entering get_history(".$id.") method ...");
		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'ncrm_users.first_name', 'last_name' => 'ncrm_users.last_name'), 'Users');
		$query = "SELECT ncrm_contactdetails.lastname, ncrm_contactdetails.firstname,
				ncrm_contactdetails.contactid,ncrm_activity.*,ncrm_seactivityrel.*,
				ncrm_crmentity.crmid, ncrm_crmentity.smownerid, ncrm_crmentity.modifiedtime,
				ncrm_crmentity.createdtime, ncrm_crmentity.description,
				case when (ncrm_users.user_name not like '') then $userNameSql else ncrm_groups.groupname end as user_name
				from ncrm_activity
				inner join ncrm_seactivityrel on ncrm_seactivityrel.activityid=ncrm_activity.activityid
				inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_activity.activityid
				left join ncrm_cntactivityrel on ncrm_cntactivityrel.activityid= ncrm_activity.activityid
				left join ncrm_contactdetails on ncrm_contactdetails.contactid = ncrm_cntactivityrel.contactid
                left join ncrm_groups on ncrm_groups.groupid=ncrm_crmentity.smownerid
				left join ncrm_users on ncrm_users.id=ncrm_crmentity.smownerid
				where ncrm_activity.activitytype='Task'
					and (ncrm_activity.status = 'Completed' or ncrm_activity.status = 'Deferred')
					and ncrm_seactivityrel.crmid=".$id."
					and ncrm_crmentity.deleted = 0";
		//Don't add order by, because, for security, one more condition will be added with this query in include/RelatedListView.php

		$log->debug("Exiting get_history method ...");
		return getHistory('Invoice',$query,$id);
	}



	/**	Function used to get the Status history of the Invoice
	 *	@param $id - invoice id
	 *	@return $return_data - array with header and the entries in format Array('header'=>$header,'entries'=>$entries_list) where as $header and $entries_list are arrays which contains header values and all column values of all entries
	 */
	function get_invoicestatushistory($id)
	{
		global $log;
		$log->debug("Entering get_invoicestatushistory(".$id.") method ...");

		global $adb;
		global $mod_strings;
		global $app_strings;

		$query = 'select ncrm_invoicestatushistory.*, ncrm_invoice.invoice_no from ncrm_invoicestatushistory inner join ncrm_invoice on ncrm_invoice.invoiceid = ncrm_invoicestatushistory.invoiceid inner join ncrm_crmentity on ncrm_crmentity.crmid = ncrm_invoice.invoiceid where ncrm_crmentity.deleted = 0 and ncrm_invoice.invoiceid = ?';
		$result=$adb->pquery($query, array($id));
		$noofrows = $adb->num_rows($result);

		$header[] = $app_strings['Invoice No'];
		$header[] = $app_strings['LBL_ACCOUNT_NAME'];
		$header[] = $app_strings['LBL_AMOUNT'];
		$header[] = $app_strings['LBL_INVOICE_STATUS'];
		$header[] = $app_strings['LBL_LAST_MODIFIED'];

		//Getting the field permission for the current user. 1 - Not Accessible, 0 - Accessible
		//Account Name , Amount are mandatory fields. So no need to do security check to these fields.
		global $current_user;

		//If field is accessible then getFieldVisibilityPermission function will return 0 else return 1
		$invoicestatus_access = (getFieldVisibilityPermission('Invoice', $current_user->id, 'invoicestatus') != '0')? 1 : 0;
		$picklistarray = getAccessPickListValues('Invoice');

		$invoicestatus_array = ($invoicestatus_access != 1)? $picklistarray['invoicestatus']: array();
		//- ==> picklist field is not permitted in profile
		//Not Accessible - picklist is permitted in profile but picklist value is not permitted
		$error_msg = ($invoicestatus_access != 1)? 'Not Accessible': '-';

		while($row = $adb->fetch_array($result))
		{
			$entries = Array();

			// Module Sequence Numbering
			//$entries[] = $row['invoiceid'];
			$entries[] = $row['invoice_no'];
			// END
			$entries[] = $row['accountname'];
			$entries[] = $row['total'];
			$entries[] = (in_array($row['invoicestatus'], $invoicestatus_array))? $row['invoicestatus']: $error_msg;
			$entries[] = DateTimeField::convertToUserFormat($row['lastmodified']);

			$entries_list[] = $entries;
		}

		$return_data = Array('header'=>$header,'entries'=>$entries_list);

	 	$log->debug("Exiting get_invoicestatushistory method ...");

		return $return_data;
	}

	// Function to get column name - Overriding function of base class
	function get_column_value($columname, $fldvalue, $fieldname, $uitype, $datatype = '') {
		if ($columname == 'salesorderid') {
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

		// Define the dependency matrix ahead
		$matrix = $queryPlanner->newDependencyMatrix();
		$matrix->setDependency('ncrm_crmentityInvoice', array('ncrm_usersInvoice', 'ncrm_groupsInvoice', 'ncrm_lastModifiedByInvoice'));
		$matrix->setDependency('ncrm_inventoryproductrelInvoice', array('ncrm_productsInvoice', 'ncrm_serviceInvoice'));
		$matrix->setDependency('ncrm_invoice',array('ncrm_crmentityInvoice', "ncrm_currency_info$secmodule",
				'ncrm_invoicecf', 'ncrm_salesorderInvoice', 'ncrm_invoicebillads',
				'ncrm_invoiceshipads', 'ncrm_inventoryproductrelInvoice', 'ncrm_contactdetailsInvoice', 'ncrm_accountInvoice'));

		if (!$queryPlanner->requireTable('ncrm_invoice', $matrix)) {
			return '';
		}

		$query = $this->getRelationQuery($module,$secmodule,"ncrm_invoice","invoiceid", $queryPlanner);

		if ($queryPlanner->requireTable('ncrm_crmentityInvoice', $matrix)) {
			$query .= " left join ncrm_crmentity as ncrm_crmentityInvoice on ncrm_crmentityInvoice.crmid=ncrm_invoice.invoiceid and ncrm_crmentityInvoice.deleted=0";
		}
		if ($queryPlanner->requireTable('ncrm_invoicecf')) {
			$query .= " left join ncrm_invoicecf on ncrm_invoice.invoiceid = ncrm_invoicecf.invoiceid";
		}
		if ($queryPlanner->requireTable("ncrm_currency_info$secmodule")) {
			$query .= " left join ncrm_currency_info as ncrm_currency_info$secmodule on ncrm_currency_info$secmodule.id = ncrm_invoice.currency_id";
		}
		if ($queryPlanner->requireTable('ncrm_salesorderInvoice')) {
			$query .= " left join ncrm_salesorder as ncrm_salesorderInvoice on ncrm_salesorderInvoice.salesorderid=ncrm_invoice.salesorderid";
		}
		if ($queryPlanner->requireTable('ncrm_invoicebillads')) {
			$query .= " left join ncrm_invoicebillads on ncrm_invoice.invoiceid=ncrm_invoicebillads.invoicebilladdressid";
		}
		if ($queryPlanner->requireTable('ncrm_invoiceshipads')) {
			$query .= " left join ncrm_invoiceshipads on ncrm_invoice.invoiceid=ncrm_invoiceshipads.invoiceshipaddressid";
		}
		if ($queryPlanner->requireTable('ncrm_inventoryproductrelInvoice', $matrix)) {
			$query .= " left join ncrm_inventoryproductrel as ncrm_inventoryproductrelInvoice on ncrm_invoice.invoiceid = ncrm_inventoryproductrelInvoice.id";
            // To Eliminate duplicates in reports
            if(($module == 'Products' || $module == 'Services') && $secmodule == "Invoice"){
                if($module == 'Products'){
                    $query .= " and ncrm_inventoryproductrelInvoice.productid = ncrm_products.productid ";    
                }else if($module == 'Services'){
                    $query .= " and ncrm_inventoryproductrelInvoice.productid = ncrm_service.serviceid "; 
                }
            }
		}
		if ($queryPlanner->requireTable('ncrm_productsInvoice')) {
			$query .= " left join ncrm_products as ncrm_productsInvoice on ncrm_productsInvoice.productid = ncrm_inventoryproductrelInvoice.productid";
		}
		if ($queryPlanner->requireTable('ncrm_serviceInvoice')) {
			$query .= " left join ncrm_service as ncrm_serviceInvoice on ncrm_serviceInvoice.serviceid = ncrm_inventoryproductrelInvoice.productid";
		}
		if ($queryPlanner->requireTable('ncrm_groupsInvoice')) {
			$query .= " left join ncrm_groups as ncrm_groupsInvoice on ncrm_groupsInvoice.groupid = ncrm_crmentityInvoice.smownerid";
		}
		if ($queryPlanner->requireTable('ncrm_usersInvoice')) {
			$query .= " left join ncrm_users as ncrm_usersInvoice on ncrm_usersInvoice.id = ncrm_crmentityInvoice.smownerid";
		}
		if ($queryPlanner->requireTable('ncrm_contactdetailsInvoice')) {
			$query .= " left join ncrm_contactdetails as ncrm_contactdetailsInvoice on ncrm_invoice.contactid = ncrm_contactdetailsInvoice.contactid";
		}
		if ($queryPlanner->requireTable('ncrm_accountInvoice')) {
			$query .= " left join ncrm_account as ncrm_accountInvoice on ncrm_accountInvoice.accountid = ncrm_invoice.accountid";
		}
		if ($queryPlanner->requireTable('ncrm_lastModifiedByInvoice')) {
			$query .= " left join ncrm_users as ncrm_lastModifiedByInvoice on ncrm_lastModifiedByInvoice.id = ncrm_crmentityInvoice.modifiedby ";
		}
        if ($queryPlanner->requireTable("ncrm_createdbyInvoice")){
			$query .= " left join ncrm_users as ncrm_createdbyInvoice on ncrm_createdbyInvoice.id = ncrm_crmentityInvoice.smcreatorid ";
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
			"Calendar" =>array("ncrm_seactivityrel"=>array("crmid","activityid"),"ncrm_invoice"=>"invoiceid"),
			"Documents" => array("ncrm_senotesrel"=>array("crmid","notesid"),"ncrm_invoice"=>"invoiceid"),
			"Accounts" => array("ncrm_invoice"=>array("invoiceid","accountid")),
                        "Contacts" => array("ncrm_invoice"=>array("invoiceid","contactid")),
		);
		return $rel_tables[$secmodule];
	}

	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;

		if($return_module == 'Accounts' || $return_module == 'Contacts') {
			$this->trash('Invoice',$id);
		} elseif($return_module=='SalesOrder') {
			$relation_query = 'UPDATE ncrm_invoice set salesorderid=? where invoiceid=?';
			$this->db->pquery($relation_query, array(null,$id));
		} else {
			$sql = 'DELETE FROM ncrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
			$params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
			$this->db->pquery($sql, $params);
		}
	}

	/*
	 * Function to get the relations of salesorder to invoice for recurring invoice procedure
	 * @param - $salesorder_id Salesorder ID
	 */
	function createRecurringInvoiceFromSO(){
		global $adb;
		$salesorder_id = $this->_salesorderid;
		$query1 = "SELECT * FROM ncrm_inventoryproductrel WHERE id=?";
		$res = $adb->pquery($query1, array($salesorder_id));
		$no_of_products = $adb->num_rows($res);
		$fieldsList = $adb->getFieldsArray($res);
		$update_stock = array();
		for($j=0; $j<$no_of_products; $j++) {
			$row = $adb->query_result_rowdata($res, $j);
			$col_value = array();
			for($k=0; $k<count($fieldsList); $k++) {
				if($fieldsList[$k]!='lineitem_id'){
					$col_value[$fieldsList[$k]] = $row[$fieldsList[$k]];
				}
			}
			if(count($col_value) > 0) {
				$col_value['id'] = $this->id;
				$columns = array_keys($col_value);
				$values = array_values($col_value);
				$query2 = "INSERT INTO ncrm_inventoryproductrel(". implode(",",$columns) .") VALUES (". generateQuestionMarks($values) .")";
				$adb->pquery($query2, array($values));
				$prod_id = $col_value['productid'];
				$qty = $col_value['quantity'];
				$update_stock[$col_value['sequence_no']] = $qty;
				updateStk($prod_id,$qty,'',array(),'Invoice');
			}
		}

		$query1 = "SELECT * FROM ncrm_inventorysubproductrel WHERE id=?";
		$res = $adb->pquery($query1, array($salesorder_id));
		$no_of_products = $adb->num_rows($res);
		$fieldsList = $adb->getFieldsArray($res);
		for($j=0; $j<$no_of_products; $j++) {
			$row = $adb->query_result_rowdata($res, $j);
			$col_value = array();
			for($k=0; $k<count($fieldsList); $k++) {
					$col_value[$fieldsList[$k]] = $row[$fieldsList[$k]];
			}
			if(count($col_value) > 0) {
				$col_value['id'] = $this->id;
				$columns = array_keys($col_value);
				$values = array_values($col_value);
				$query2 = "INSERT INTO ncrm_inventorysubproductrel(". implode(",",$columns) .") VALUES (". generateQuestionMarks($values) .")";
				$adb->pquery($query2, array($values));
				$prod_id = $col_value['productid'];
				$qty = $update_stock[$col_value['sequence_no']];
				updateStk($prod_id,$qty,'',array(),'Invoice');
			}
		}

		// Add the Shipping taxes for the Invoice
		$query3 = "SELECT * FROM ncrm_inventoryshippingrel WHERE id=?";
		$res = $adb->pquery($query3, array($salesorder_id));
		$no_of_shippingtax = $adb->num_rows($res);
		$fieldsList = $adb->getFieldsArray($res);
		for($j=0; $j<$no_of_shippingtax; $j++) {
			$row = $adb->query_result_rowdata($res, $j);
			$col_value = array();
			for($k=0; $k<count($fieldsList); $k++) {
				$col_value[$fieldsList[$k]] = $row[$fieldsList[$k]];
			}
			if(count($col_value) > 0) {
				$col_value['id'] = $this->id;
				$columns = array_keys($col_value);
				$values = array_values($col_value);
				$query4 = "INSERT INTO ncrm_inventoryshippingrel(". implode(",",$columns) .") VALUES (". generateQuestionMarks($values) .")";
				$adb->pquery($query4, array($values));
			}
		}

		//Update the netprice (subtotal), taxtype, discount, S&H charge, adjustment and total for the Invoice

		$updatequery  = " UPDATE ncrm_invoice SET ";
		$updateparams = array();
		// Remaining column values to be updated -> column name to field name mapping
		$invoice_column_field = Array (
			'adjustment' => 'txtAdjustment',
			'subtotal' => 'hdnSubTotal',
			'total' => 'hdnGrandTotal',
			'taxtype' => 'hdnTaxType',
			'discount_percent' => 'hdnDiscountPercent',
			'discount_amount' => 'hdnDiscountAmount',
			's_h_amount' => 'hdnS_H_Amount',
		);
		$updatecols = array();
		foreach($invoice_column_field as $col => $field) {
			$updatecols[] = "$col=?";
			$updateparams[] = $this->column_fields[$field];
		}
		if (count($updatecols) > 0) {
			$updatequery .= implode(",", $updatecols);

			$updatequery .= " WHERE invoiceid=?";
			array_push($updateparams, $this->id);

			$adb->pquery($updatequery, $updateparams);
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
	* Returns Export Invoice Query.
	*/
	function create_export_query($where)
	{
		global $log;
		global $current_user;
		$log->debug("Entering create_export_query(".$where.") method ...");

		include("include/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Invoice", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);
		$fields_list .= getInventoryFieldsForExport($this->table_name);
		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>'ncrm_users.first_name', 'last_name' => 'ncrm_users.last_name'), 'Users');

		$query = "SELECT $fields_list FROM ".$this->entity_table."
				INNER JOIN ncrm_invoice ON ncrm_invoice.invoiceid = ncrm_crmentity.crmid
				LEFT JOIN ncrm_invoicecf ON ncrm_invoicecf.invoiceid = ncrm_invoice.invoiceid
				LEFT JOIN ncrm_salesorder ON ncrm_salesorder.salesorderid = ncrm_invoice.salesorderid
				LEFT JOIN ncrm_invoicebillads ON ncrm_invoicebillads.invoicebilladdressid = ncrm_invoice.invoiceid
				LEFT JOIN ncrm_invoiceshipads ON ncrm_invoiceshipads.invoiceshipaddressid = ncrm_invoice.invoiceid
				LEFT JOIN ncrm_inventoryproductrel ON ncrm_inventoryproductrel.id = ncrm_invoice.invoiceid
				LEFT JOIN ncrm_products ON ncrm_products.productid = ncrm_inventoryproductrel.productid
				LEFT JOIN ncrm_service ON ncrm_service.serviceid = ncrm_inventoryproductrel.productid
				LEFT JOIN ncrm_contactdetails ON ncrm_contactdetails.contactid = ncrm_invoice.contactid
				LEFT JOIN ncrm_account ON ncrm_account.accountid = ncrm_invoice.accountid
				LEFT JOIN ncrm_currency_info ON ncrm_currency_info.id = ncrm_invoice.currency_id
				LEFT JOIN ncrm_groups ON ncrm_groups.groupid = ncrm_crmentity.smownerid
				LEFT JOIN ncrm_users ON ncrm_users.id = ncrm_crmentity.smownerid";

		$query .= $this->getNonAdminAccessControlQuery('Invoice',$current_user);
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