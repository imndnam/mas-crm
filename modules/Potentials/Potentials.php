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
 * $Header: /advent/projects/wesat/ncrm_crm/sugarcrm/modules/Potentials/Potentials.php,v 1.65 2005/04/28 08:08:27 rank Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

class Potentials extends CRMEntity {
	var $log;
	var $db;

	var $module_name="Potentials";
	var $table_name = "ncrm_potential";
	var $table_index= 'potentialid';

	var $tab_name = Array('ncrm_crmentity','ncrm_potential','ncrm_potentialscf');
	var $tab_name_index = Array('ncrm_crmentity'=>'crmid','ncrm_potential'=>'potentialid','ncrm_potentialscf'=>'potentialid');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('ncrm_potentialscf', 'potentialid');

	var $column_fields = Array();

	var $sortby_fields = Array('potentialname','amount','closingdate','smownerid','accountname');

	// This is the list of ncrm_fields that are in the lists.
	var $list_fields = Array(
			'Potential'=>Array('potential'=>'potentialname'),
			'Organization Name'=>Array('potential'=>'related_to'),
			'Contact Name'=>Array('potential'=>'contact_id'),
			'Sales Stage'=>Array('potential'=>'sales_stage'),
			'Amount'=>Array('potential'=>'amount'),
			'Expected Close Date'=>Array('potential'=>'closingdate'),
			'Assigned To'=>Array('crmentity','smownerid')
			);

	var $list_fields_name = Array(
			'Potential'=>'potentialname',
			'Organization Name'=>'related_to',
			'Contact Name'=>'contact_id',
			'Sales Stage'=>'sales_stage',
			'Amount'=>'amount',
			'Expected Close Date'=>'closingdate',
			'Assigned To'=>'assigned_user_id');

	var $list_link_field= 'potentialname';

	var $search_fields = Array(
			'Potential'=>Array('potential'=>'potentialname'),
			'Related To'=>Array('potential'=>'related_to'),
			'Expected Close Date'=>Array('potential'=>'closedate')
			);

	var $search_fields_name = Array(
			'Potential'=>'potentialname',
			'Related To'=>'related_to',
			'Expected Close Date'=>'closingdate'
			);

	var $required_fields =  array();

	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to ncrm_field.fieldname values.
	var $mandatory_fields = Array('assigned_user_id', 'createdtime', 'modifiedtime', 'potentialname');

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'potentialname';
	var $default_sort_order = 'ASC';

	// For Alphabetical search
	var $def_basicsearch_col = 'potentialname';

	//var $groupTable = Array('ncrm_potentialgrouprelation','potentialid');
	function Potentials() {
		$this->log = LoggerManager::getLogger('potential');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Potentials');
	}

	function save_module($module)
	{
	}

	/** Function to create list query
	* @param reference variable - where condition is passed when the query is executed
	* Returns Query.
	*/
	function create_list_query($order_by, $where)
	{
		global $log,$current_user;
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
	        require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
        	$tab_id = getTabid("Potentials");
		$log->debug("Entering create_list_query(".$order_by.",". $where.") method ...");
		// Determine if the ncrm_account name is present in the where clause.
		$account_required = preg_match("/accounts\.name/", $where);

		if($account_required)
		{
			$query = "SELECT ncrm_potential.potentialid,  ncrm_potential.potentialname, ncrm_potential.dateclosed FROM ncrm_potential, ncrm_account ";
			$where_auto = "account.accountid = ncrm_potential.related_to AND ncrm_crmentity.deleted=0 ";
		}
		else
		{
			$query = 'SELECT ncrm_potential.potentialid, ncrm_potential.potentialname, ncrm_crmentity.smcreatorid, ncrm_potential.closingdate FROM ncrm_potential inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_potential.potentialid LEFT JOIN ncrm_groups on ncrm_groups.groupid = ncrm_crmentity.smownerid left join ncrm_users on ncrm_users.id = ncrm_crmentity.smownerid ';
			$where_auto = ' AND ncrm_crmentity.deleted=0';
		}

		$query .= $this->getNonAdminAccessControlQuery('Potentials',$current_user);
		if($where != "")
			$query .= " where $where ".$where_auto;
		else
			$query .= " where ".$where_auto;
		if($order_by != "")
			$query .= " ORDER BY $order_by";

		$log->debug("Exiting create_list_query method ...");
		return $query;
	}

	/** Function to export the Opportunities records in CSV Format
	* @param reference variable - order by is passed when the query is executed
	* @param reference variable - where condition is passed when the query is executed
	* Returns Export Potentials Query.
	*/
	function create_export_query($where)
	{
		global $log;
		global $current_user;
		$log->debug("Entering create_export_query(". $where.") method ...");

		include("include/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Potentials", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'ncrm_users.first_name', 'last_name' => 'ncrm_users.last_name'), 'Users');
		$query = "SELECT $fields_list,case when (ncrm_users.user_name not like '') then $userNameSql else ncrm_groups.groupname end as user_name
				FROM ncrm_potential
				inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_potential.potentialid
				LEFT JOIN ncrm_users ON ncrm_crmentity.smownerid=ncrm_users.id
				LEFT JOIN ncrm_account on ncrm_potential.related_to=ncrm_account.accountid
				LEFT JOIN ncrm_contactdetails on ncrm_potential.contact_id=ncrm_contactdetails.contactid
				LEFT JOIN ncrm_potentialscf on ncrm_potentialscf.potentialid=ncrm_potential.potentialid
                LEFT JOIN ncrm_groups
        	        ON ncrm_groups.groupid = ncrm_crmentity.smownerid
				LEFT JOIN ncrm_campaign
					ON ncrm_campaign.campaignid = ncrm_potential.campaignid";

		$query .= $this->getNonAdminAccessControlQuery('Potentials',$current_user);
		$where_auto = "  ncrm_crmentity.deleted = 0 ";

                if($where != "")
                   $query .= "  WHERE ($where) AND ".$where_auto;
                else
                   $query .= "  WHERE ".$where_auto;

		$log->debug("Exiting create_export_query method ...");
		return $query;

	}



	/** Returns a list of the associated contacts
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	 */
	function get_contacts($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_contacts(".$id.") method ...");
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

		$accountid = $this->column_fields['related_to'];
		$search_string = "&fromPotential=true&acc_id=$accountid";

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab$search_string','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'ncrm_users.first_name', 'last_name' => 'ncrm_users.last_name'), 'Users');
		$query = 'select case when (ncrm_users.user_name not like "") then '.$userNameSql.' else ncrm_groups.groupname end as user_name,
					ncrm_contactdetails.accountid,ncrm_potential.potentialid, ncrm_potential.potentialname, ncrm_contactdetails.contactid,
					ncrm_contactdetails.lastname, ncrm_contactdetails.firstname, ncrm_contactdetails.title, ncrm_contactdetails.department,
					ncrm_contactdetails.email, ncrm_contactdetails.phone, ncrm_crmentity.crmid, ncrm_crmentity.smownerid,
					ncrm_crmentity.modifiedtime , ncrm_account.accountname from ncrm_potential
					left join ncrm_contpotentialrel on ncrm_contpotentialrel.potentialid = ncrm_potential.potentialid
					inner join ncrm_contactdetails on ((ncrm_contactdetails.contactid = ncrm_contpotentialrel.contactid) or (ncrm_contactdetails.contactid = ncrm_potential.contact_id))
					INNER JOIN ncrm_contactaddress ON ncrm_contactdetails.contactid = ncrm_contactaddress.contactaddressid
					INNER JOIN ncrm_contactsubdetails ON ncrm_contactdetails.contactid = ncrm_contactsubdetails.contactsubscriptionid
					INNER JOIN ncrm_customerdetails ON ncrm_contactdetails.contactid = ncrm_customerdetails.customerid
					INNER JOIN ncrm_contactscf ON ncrm_contactdetails.contactid = ncrm_contactscf.contactid
					inner join ncrm_crmentity on ncrm_crmentity.crmid = ncrm_contactdetails.contactid
					left join ncrm_account on ncrm_account.accountid = ncrm_contactdetails.accountid
					left join ncrm_groups on ncrm_groups.groupid=ncrm_crmentity.smownerid
					left join ncrm_users on ncrm_crmentity.smownerid=ncrm_users.id
					where ncrm_potential.potentialid = '.$id.' and ncrm_crmentity.deleted=0';

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_contacts method ...");
		return $return_value;
	}

	/** Returns a list of the associated calls
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
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
				if(getFieldVisibilityPermission('Events',$current_user->id,'parent_id', 'readwrite') == '0') {
					$button .= "<input title='".getTranslatedString('LBL_NEW'). " ". getTranslatedString('LBL_TODO', $related_module) ."' class='crmbutton small create'" .
						" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\";this.form.return_module.value=\"$this_module\";this.form.activity_mode.value=\"Events\";' type='submit' name='button'" .
						" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString('LBL_EVENT', $related_module) ."'>";
				}
			}
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'ncrm_users.first_name', 'last_name' => 'ncrm_users.last_name'), 'Users');
		$query = "SELECT ncrm_activity.activityid as 'tmp_activity_id',ncrm_activity.*,ncrm_seactivityrel.crmid as parent_id, ncrm_contactdetails.lastname,ncrm_contactdetails.firstname,
					ncrm_crmentity.crmid, ncrm_crmentity.smownerid, ncrm_crmentity.modifiedtime,
					case when (ncrm_users.user_name not like '') then $userNameSql else ncrm_groups.groupname end as user_name,
					ncrm_recurringevents.recurringtype from ncrm_activity
					inner join ncrm_seactivityrel on ncrm_seactivityrel.activityid=ncrm_activity.activityid
					inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_activity.activityid
					left join ncrm_cntactivityrel on ncrm_cntactivityrel.activityid = ncrm_activity.activityid
					left join ncrm_contactdetails on ncrm_contactdetails.contactid = ncrm_cntactivityrel.contactid
					inner join ncrm_potential on ncrm_potential.potentialid=ncrm_seactivityrel.crmid
					left join ncrm_users on ncrm_users.id=ncrm_crmentity.smownerid
					left join ncrm_groups on ncrm_groups.groupid=ncrm_crmentity.smownerid
					left outer join ncrm_recurringevents on ncrm_recurringevents.activityid=ncrm_activity.activityid
					where ncrm_seactivityrel.crmid=".$id." and ncrm_crmentity.deleted=0
					and ((ncrm_activity.activitytype='Task' and ncrm_activity.status not in ('Completed','Deferred'))
					or (ncrm_activity.activitytype NOT in ('Emails','Task') and  ncrm_activity.eventstatus not in ('','Held'))) ";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_activities method ...");
		return $return_value;
	}

	 /**
	 * Function to get Contact related Products
	 * @param  integer   $id  - contactid
	 * returns related Products record in array format
	 */
	function get_products($id, $cur_tab_id, $rel_tab_id, $actions=false) {
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

		$query = "SELECT ncrm_products.productid, ncrm_products.productname, ncrm_products.productcode,
				ncrm_products.commissionrate, ncrm_products.qty_per_unit, ncrm_products.unit_price,
				ncrm_crmentity.crmid, ncrm_crmentity.smownerid
				FROM ncrm_products
				INNER JOIN ncrm_seproductsrel ON ncrm_products.productid = ncrm_seproductsrel.productid and ncrm_seproductsrel.setype = 'Potentials'
				INNER JOIN ncrm_productcf
				ON ncrm_products.productid = ncrm_productcf.productid
				INNER JOIN ncrm_crmentity ON ncrm_crmentity.crmid = ncrm_products.productid
				INNER JOIN ncrm_potential ON ncrm_potential.potentialid = ncrm_seproductsrel.crmid
				LEFT JOIN ncrm_users
					ON ncrm_users.id=ncrm_crmentity.smownerid
				LEFT JOIN ncrm_groups
					ON ncrm_groups.groupid = ncrm_crmentity.smownerid
				WHERE ncrm_crmentity.deleted = 0 AND ncrm_potential.potentialid = $id";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_products method ...");
		return $return_value;
	}

	/**	Function used to get the Sales Stage history of the Potential
	 *	@param $id - potentialid
	 *	return $return_data - array with header and the entries in format Array('header'=>$header,'entries'=>$entries_list) where as $header and $entries_list are array which contains all the column values of an row
	 */
	function get_stage_history($id)
	{
		global $log;
		$log->debug("Entering get_stage_history(".$id.") method ...");

		global $adb;
		global $mod_strings;
		global $app_strings;

		$query = 'select ncrm_potstagehistory.*, ncrm_potential.potentialname from ncrm_potstagehistory inner join ncrm_potential on ncrm_potential.potentialid = ncrm_potstagehistory.potentialid inner join ncrm_crmentity on ncrm_crmentity.crmid = ncrm_potential.potentialid where ncrm_crmentity.deleted = 0 and ncrm_potential.potentialid = ?';
		$result=$adb->pquery($query, array($id));
		$noofrows = $adb->num_rows($result);

		$header[] = $app_strings['LBL_AMOUNT'];
		$header[] = $app_strings['LBL_SALES_STAGE'];
		$header[] = $app_strings['LBL_PROBABILITY'];
		$header[] = $app_strings['LBL_CLOSE_DATE'];
		$header[] = $app_strings['LBL_LAST_MODIFIED'];

		//Getting the field permission for the current user. 1 - Not Accessible, 0 - Accessible
		//Sales Stage, Expected Close Dates are mandatory fields. So no need to do security check to these fields.
		global $current_user;

		//If field is accessible then getFieldVisibilityPermission function will return 0 else return 1
		$amount_access = (getFieldVisibilityPermission('Potentials', $current_user->id, 'amount') != '0')? 1 : 0;
		$probability_access = (getFieldVisibilityPermission('Potentials', $current_user->id, 'probability') != '0')? 1 : 0;
		$picklistarray = getAccessPickListValues('Potentials');

		$potential_stage_array = $picklistarray['sales_stage'];
		//- ==> picklist field is not permitted in profile
		//Not Accessible - picklist is permitted in profile but picklist value is not permitted
		$error_msg = 'Not Accessible';

		while($row = $adb->fetch_array($result))
		{
			$entries = Array();

			$entries[] = ($amount_access != 1)? $row['amount'] : 0;
			$entries[] = (in_array($row['stage'], $potential_stage_array))? $row['stage']: $error_msg;
			$entries[] = ($probability_access != 1) ? $row['probability'] : 0;
			$entries[] = DateTimeField::convertToUserFormat($row['closedate']);
			$date = new DateTimeField($row['lastmodified']);
			$entries[] = $date->getDisplayDate();

			$entries_list[] = $entries;
		}

		$return_data = Array('header'=>$header,'entries'=>$entries_list);

	 	$log->debug("Exiting get_stage_history method ...");

		return $return_data;
	}

	/**
	* Function to get Potential related Task & Event which have activity type Held, Completed or Deferred.
	* @param  integer   $id
	* returns related Task or Event record in array format
	*/
	function get_history($id)
	{
			global $log;
			$log->debug("Entering get_history(".$id.") method ...");
			$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'ncrm_users.first_name', 'last_name' => 'ncrm_users.last_name'), 'Users');
			$query = "SELECT ncrm_activity.activityid, ncrm_activity.subject, ncrm_activity.status,
		ncrm_activity.eventstatus, ncrm_activity.activitytype,ncrm_activity.date_start,
		ncrm_activity.due_date, ncrm_activity.time_start,ncrm_activity.time_end,
		ncrm_crmentity.modifiedtime, ncrm_crmentity.createdtime,
		ncrm_crmentity.description,case when (ncrm_users.user_name not like '') then $userNameSql else ncrm_groups.groupname end as user_name
				from ncrm_activity
				inner join ncrm_seactivityrel on ncrm_seactivityrel.activityid=ncrm_activity.activityid
				inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_activity.activityid
				left join ncrm_groups on ncrm_groups.groupid=ncrm_crmentity.smownerid
				left join ncrm_users on ncrm_users.id=ncrm_crmentity.smownerid
				where (ncrm_activity.activitytype != 'Emails')
				and (ncrm_activity.status = 'Completed' or ncrm_activity.status = 'Deferred' or (ncrm_activity.eventstatus = 'Held' and ncrm_activity.eventstatus != ''))
				and ncrm_seactivityrel.crmid=".$id."
                                and ncrm_crmentity.deleted = 0";
		//Don't add order by, because, for security, one more condition will be added with this query in include/RelatedListView.php

		$log->debug("Exiting get_history method ...");
		return getHistory('Potentials',$query,$id);
	}


	  /**
	  * Function to get Potential related Quotes
	  * @param  integer   $id  - potentialid
	  * returns related Quotes record in array format
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

		if($actions && getFieldVisibilityPermission($related_module, $current_user->id, 'potential_id', 'readwrite') == '0') {
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
		$query = "select case when (ncrm_users.user_name not like '') then $userNameSql else ncrm_groups.groupname end as user_name,
					ncrm_account.accountname, ncrm_crmentity.*, ncrm_quotes.*, ncrm_potential.potentialname from ncrm_quotes
					inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_quotes.quoteid
					left outer join ncrm_potential on ncrm_potential.potentialid=ncrm_quotes.potentialid
					left join ncrm_groups on ncrm_groups.groupid=ncrm_crmentity.smownerid
                    LEFT JOIN ncrm_quotescf ON ncrm_quotescf.quoteid = ncrm_quotes.quoteid
					LEFT JOIN ncrm_quotesbillads ON ncrm_quotesbillads.quotebilladdressid = ncrm_quotes.quoteid
					LEFT JOIN ncrm_quotesshipads ON ncrm_quotesshipads.quoteshipaddressid = ncrm_quotes.quoteid
					left join ncrm_users on ncrm_users.id=ncrm_crmentity.smownerid
					LEFT join ncrm_account on ncrm_account.accountid=ncrm_quotes.accountid
					where ncrm_crmentity.deleted=0 and ncrm_potential.potentialid=".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_quotes method ...");
		return $return_value;
	}

	/**
	 * Function to get Potential related SalesOrder
 	 * @param  integer   $id  - potentialid
	 * returns related SalesOrder record in array format
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

		if($actions && getFieldVisibilityPermission($related_module, $current_user->id, 'potential_id', 'readwrite') == '0') {
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
		$query = "select ncrm_crmentity.*, ncrm_salesorder.*, ncrm_quotes.subject as quotename
			, ncrm_account.accountname, ncrm_potential.potentialname,case when
			(ncrm_users.user_name not like '') then $userNameSql else ncrm_groups.groupname
			end as user_name from ncrm_salesorder
			inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_salesorder.salesorderid
			left outer join ncrm_quotes on ncrm_quotes.quoteid=ncrm_salesorder.quoteid
			left outer join ncrm_account on ncrm_account.accountid=ncrm_salesorder.accountid
			left outer join ncrm_potential on ncrm_potential.potentialid=ncrm_salesorder.potentialid
			left join ncrm_groups on ncrm_groups.groupid=ncrm_crmentity.smownerid
            LEFT JOIN ncrm_salesordercf ON ncrm_salesordercf.salesorderid = ncrm_salesorder.salesorderid
            LEFT JOIN ncrm_invoice_recurring_info ON ncrm_invoice_recurring_info.start_period = ncrm_salesorder.salesorderid
			LEFT JOIN ncrm_sobillads ON ncrm_sobillads.sobilladdressid = ncrm_salesorder.salesorderid
			LEFT JOIN ncrm_soshipads ON ncrm_soshipads.soshipaddressid = ncrm_salesorder.salesorderid
			left join ncrm_users on ncrm_users.id=ncrm_crmentity.smownerid
			 where ncrm_crmentity.deleted=0 and ncrm_potential.potentialid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_salesorder method ...");
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

		$rel_table_arr = Array("Activities"=>"ncrm_seactivityrel","Contacts"=>"ncrm_contpotentialrel","Products"=>"ncrm_seproductsrel",
						"Attachments"=>"ncrm_seattachmentsrel","Quotes"=>"ncrm_quotes","SalesOrder"=>"ncrm_salesorder",
						"Documents"=>"ncrm_senotesrel");

		$tbl_field_arr = Array("ncrm_seactivityrel"=>"activityid","ncrm_contpotentialrel"=>"contactid","ncrm_seproductsrel"=>"productid",
						"ncrm_seattachmentsrel"=>"attachmentsid","ncrm_quotes"=>"quoteid","ncrm_salesorder"=>"salesorderid",
						"ncrm_senotesrel"=>"notesid");

		$entity_tbl_field_arr = Array("ncrm_seactivityrel"=>"crmid","ncrm_contpotentialrel"=>"potentialid","ncrm_seproductsrel"=>"crmid",
						"ncrm_seattachmentsrel"=>"crmid","ncrm_quotes"=>"potentialid","ncrm_salesorder"=>"potentialid",
						"ncrm_senotesrel"=>"crmid");

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
	 * Function to get the secondary query part of a report
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule,$queryplanner){
		$matrix = $queryplanner->newDependencyMatrix();
		$matrix->setDependency('ncrm_crmentityPotentials',array('ncrm_groupsPotentials','ncrm_usersPotentials','ncrm_lastModifiedByPotentials'));
		$matrix->setDependency('ncrm_potential', array('ncrm_crmentityPotentials','ncrm_accountPotentials',
											'ncrm_contactdetailsPotentials','ncrm_campaignPotentials','ncrm_potentialscf'));


		if (!$queryplanner->requireTable("ncrm_potential",$matrix)){
			return '';
		}

		$query = $this->getRelationQuery($module,$secmodule,"ncrm_potential","potentialid", $queryplanner);

		if ($queryplanner->requireTable("ncrm_crmentityPotentials",$matrix)){
			$query .= " left join ncrm_crmentity as ncrm_crmentityPotentials on ncrm_crmentityPotentials.crmid=ncrm_potential.potentialid and ncrm_crmentityPotentials.deleted=0";
		}
		if ($queryplanner->requireTable("ncrm_accountPotentials")){
			$query .= " left join ncrm_account as ncrm_accountPotentials on ncrm_potential.related_to = ncrm_accountPotentials.accountid";
		}
		if ($queryplanner->requireTable("ncrm_contactdetailsPotentials")){
			$query .= " left join ncrm_contactdetails as ncrm_contactdetailsPotentials on ncrm_potential.contact_id = ncrm_contactdetailsPotentials.contactid";
		}
		if ($queryplanner->requireTable("ncrm_potentialscf")){
			$query .= " left join ncrm_potentialscf on ncrm_potentialscf.potentialid = ncrm_potential.potentialid";
		}
		if ($queryplanner->requireTable("ncrm_groupsPotentials")){
			$query .= " left join ncrm_groups ncrm_groupsPotentials on ncrm_groupsPotentials.groupid = ncrm_crmentityPotentials.smownerid";
		}
		if ($queryplanner->requireTable("ncrm_usersPotentials")){
			$query .= " left join ncrm_users as ncrm_usersPotentials on ncrm_usersPotentials.id = ncrm_crmentityPotentials.smownerid";
		}
		if ($queryplanner->requireTable("ncrm_campaignPotentials")){
			$query .= " left join ncrm_campaign as ncrm_campaignPotentials on ncrm_potential.campaignid = ncrm_campaignPotentials.campaignid";
		}
		if ($queryplanner->requireTable("ncrm_lastModifiedByPotentials")){
			$query .= " left join ncrm_users as ncrm_lastModifiedByPotentials on ncrm_lastModifiedByPotentials.id = ncrm_crmentityPotentials.modifiedby ";
		}
        if ($queryplanner->requireTable("ncrm_createdbyPotentials")){
			$query .= " left join ncrm_users as ncrm_createdbyPotentials on ncrm_createdbyPotentials.id = ncrm_crmentityPotentials.smcreatorid ";
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
			"Calendar" => array("ncrm_seactivityrel"=>array("crmid","activityid"),"ncrm_potential"=>"potentialid"),
			"Products" => array("ncrm_seproductsrel"=>array("crmid","productid"),"ncrm_potential"=>"potentialid"),
			"Quotes" => array("ncrm_quotes"=>array("potentialid","quoteid"),"ncrm_potential"=>"potentialid"),
			"SalesOrder" => array("ncrm_salesorder"=>array("potentialid","salesorderid"),"ncrm_potential"=>"potentialid"),
			"Documents" => array("ncrm_senotesrel"=>array("crmid","notesid"),"ncrm_potential"=>"potentialid"),
			"Accounts" => array("ncrm_potential"=>array("potentialid","related_to")),
			"Contacts" => array("ncrm_potential"=>array("potentialid","contact_id")),
		);
		return $rel_tables[$secmodule];
	}

	// Function to unlink all the dependent entities of the given Entity by Id
	function unlinkDependencies($module, $id) {
		global $log;
		/*//Backup Activity-Potentials Relation
		$act_q = "select activityid from ncrm_seactivityrel where crmid = ?";
		$act_res = $this->db->pquery($act_q, array($id));
		if ($this->db->num_rows($act_res) > 0) {
			for($k=0;$k < $this->db->num_rows($act_res);$k++)
			{
				$act_id = $this->db->query_result($act_res,$k,"activityid");
				$params = array($id, RB_RECORD_DELETED, 'ncrm_seactivityrel', 'crmid', 'activityid', $act_id);
				$this->db->pquery("insert into ncrm_relatedlists_rb values (?,?,?,?,?,?)", $params);
			}
		}
		$sql = 'delete from ncrm_seactivityrel where crmid = ?';
		$this->db->pquery($sql, array($id));*/

		parent::unlinkDependencies($module, $id);
	}

	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;

		if($return_module == 'Accounts') {
			$this->trash($this->module_name, $id);
		} elseif($return_module == 'Campaigns') {
			$sql = 'UPDATE ncrm_potential SET campaignid = ? WHERE potentialid = ?';
			$this->db->pquery($sql, array(null, $id));
		} elseif($return_module == 'Products') {
			$sql = 'DELETE FROM ncrm_seproductsrel WHERE crmid=? AND productid=?';
			$this->db->pquery($sql, array($id, $return_id));
		} elseif($return_module == 'Contacts') {
			$sql = 'DELETE FROM ncrm_contpotentialrel WHERE potentialid=? AND contactid=?';
			$this->db->pquery($sql, array($id, $return_id));

			//If contact related to potential through edit of record,that entry will be present in
			//ncrm_potential contact_id column,which should be set to zero
			$sql = 'UPDATE ncrm_potential SET contact_id = ? WHERE potentialid=? AND contact_id=?';
			$this->db->pquery($sql, array(0,$id, $return_id));

			// Potential directly linked with Contact (not through Account - ncrm_contpotentialrel)
			$directRelCheck = $this->db->pquery('SELECT related_to FROM ncrm_potential WHERE potentialid=? AND contact_id=?', array($id, $return_id));
			if($this->db->num_rows($directRelCheck)) {
				$this->trash($this->module_name, $id);
			}

		} else {
			$sql = 'DELETE FROM ncrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
			$params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
			$this->db->pquery($sql, $params);
		}
	}

	function save_related_module($module, $crmid, $with_module, $with_crmids) {
		$adb = PearDatabase::getInstance();

		if(!is_array($with_crmids)) $with_crmids = Array($with_crmids);
		foreach($with_crmids as $with_crmid) {
			if($with_module == 'Contacts') { //When we select contact from potential related list
				$sql = "insert into ncrm_contpotentialrel values (?,?)";
				$adb->pquery($sql, array($with_crmid, $crmid));

			} elseif($with_module == 'Products') {//when we select product from potential related list
				$sql = "insert into ncrm_seproductsrel values (?,?,?)";
				$adb->pquery($sql, array($crmid, $with_crmid,'Potentials'));

			} else {
				parent::save_related_module($module, $crmid, $with_module, $with_crmid);
			}
		}
	}

}
?>