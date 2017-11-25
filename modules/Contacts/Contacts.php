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
 * $Header: /advent/projects/wesat/ncrm_crm/sugarcrm/modules/Contacts/Contacts.php,v 1.70 2005/04/27 11:21:49 rank Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
// Contact is used to store customer information.
class Contacts extends CRMEntity {
	var $log;
	var $db;

	var $table_name = "ncrm_contactdetails";
	var $table_index= 'contactid';
	var $tab_name = Array('ncrm_crmentity','ncrm_contactdetails','ncrm_contactaddress','ncrm_contactsubdetails','ncrm_contactscf','ncrm_customerdetails');
	var $tab_name_index = Array('ncrm_crmentity'=>'crmid','ncrm_contactdetails'=>'contactid','ncrm_contactaddress'=>'contactaddressid','ncrm_contactsubdetails'=>'contactsubscriptionid','ncrm_contactscf'=>'contactid','ncrm_customerdetails'=>'customerid');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('ncrm_contactscf', 'contactid');

	var $column_fields = Array();

	var $sortby_fields = Array('lastname','firstname','title','email','phone','smownerid','accountname');

	var $list_link_field= 'lastname';

	// This is the list of ncrm_fields that are in the lists.
	var $list_fields = Array(
	'First Name' => Array('contactdetails'=>'firstname'),
	'Last Name' => Array('contactdetails'=>'lastname'),
	'Title' => Array('contactdetails'=>'title'),
	'Account Name' => Array('account'=>'accountid'),
	'Email' => Array('contactdetails'=>'email'),
	'Office Phone' => Array('contactdetails'=>'phone'),
	'Assigned To' => Array('crmentity'=>'smownerid')
	);

	var $range_fields = Array(
		'first_name',
		'last_name',
		'primary_address_city',
		'account_name',
		'account_id',
		'id',
		'email1',
		'salutation',
		'title',
		'phone_mobile',
		'reports_to_name',
		'primary_address_street',
		'primary_address_city',
		'primary_address_state',
		'primary_address_postalcode',
		'primary_address_country',
		'alt_address_city',
		'alt_address_street',
		'alt_address_city',
		'alt_address_state',
		'alt_address_postalcode',
		'alt_address_country',
		'office_phone',
		'home_phone',
		'other_phone',
		'fax',
		'department',
		'birthdate',
		'assistant_name',
		'assistant_phone');


	var $list_fields_name = Array(
	'First Name' => 'firstname',
	'Last Name' => 'lastname',
	'Title' => 'title',
	'Account Name' => 'account_id',
	'Email' => 'email',
	'Office Phone' => 'phone',
	'Assigned To' => 'assigned_user_id'
	);

	var $search_fields = Array(
	'First Name' => Array('contactdetails'=>'firstname'),
	'Last Name' => Array('contactdetails'=>'lastname'),
	'Title' => Array('contactdetails'=>'title'),
	'Account Name'=>Array('contactdetails'=>'account_id'),
	'Assigned To'=>Array('crmentity'=>'smownerid'),
		);

	var $search_fields_name = Array(
	'First Name' => 'firstname',
	'Last Name' => 'lastname',
	'Title' => 'title',
	'Account Name'=>'account_id',
	'Assigned To'=>'assigned_user_id'
	);

	// This is the list of ncrm_fields that are required
	var $required_fields =  array("lastname"=>1);

	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to ncrm_field.fieldname values.
	var $mandatory_fields = Array('assigned_user_id','lastname','createdtime' ,'modifiedtime');

	//Default Fields for Email Templates -- Pavani
	var $emailTemplate_defaultFields = array('firstname','lastname','salutation','title','email','department','phone','mobile','support_start_date','support_end_date');

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'lastname';
	var $default_sort_order = 'ASC';

	// For Alphabetical search
	var $def_basicsearch_col = 'lastname';

	var $related_module_table_index = array(
		'Potentials' => array('table_name' => 'ncrm_potential', 'table_index' => 'potentialid', 'rel_index' => 'contact_id'),
		'Quotes' => array('table_name' => 'ncrm_quotes', 'table_index' => 'quoteid', 'rel_index' => 'contactid'),
		'SalesOrder' => array('table_name' => 'ncrm_salesorder', 'table_index' => 'salesorderid', 'rel_index' => 'contactid'),
		'PurchaseOrder' => array('table_name' => 'ncrm_purchaseorder', 'table_index' => 'purchaseorderid', 'rel_index' => 'contactid'),
		'Invoice' => array('table_name' => 'ncrm_invoice', 'table_index' => 'invoiceid', 'rel_index' => 'contactid'),
		'HelpDesk' => array('table_name' => 'ncrm_troubletickets', 'table_index' => 'ticketid', 'rel_index' => 'contact_id'),
		'Products' => array('table_name' => 'ncrm_seproductsrel', 'table_index' => 'productid', 'rel_index' => 'crmid'),
		'Calendar' => array('table_name' => 'ncrm_cntactivityrel', 'table_index' => 'activityid', 'rel_index' => 'contactid'),
		'Documents' => array('table_name' => 'ncrm_senotesrel', 'table_index' => 'notesid', 'rel_index' => 'crmid'),
		'ServiceContracts' => array('table_name' => 'ncrm_servicecontracts', 'table_index' => 'servicecontractsid', 'rel_index' => 'sc_related_to'),
		'Services' => array('table_name' => 'ncrm_crmentityrel', 'table_index' => 'crmid', 'rel_index' => 'crmid'),
		'Campaigns' => array('table_name' => 'ncrm_campaigncontrel', 'table_index' => 'campaignid', 'rel_index' => 'contactid'),
		'Assets' => array('table_name' => 'ncrm_assets', 'table_index' => 'assetsid', 'rel_index' => 'contact'),
		'Project' => array('table_name' => 'ncrm_project', 'table_index' => 'projectid', 'rel_index' => 'linktoaccountscontacts'),
		'Emails' => array('table_name' => 'ncrm_seactivityrel', 'table_index' => 'crmid', 'rel_index' => 'activityid'),
	);

	function Contacts() {
		$this->log = LoggerManager::getLogger('contact');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Contacts');
	}

	// Mike Crowe Mod --------------------------------------------------------Default ordering for us
	/** Function to get the number of Contacts assigned to a particular User.
	*  @param varchar $user name - Assigned to User
	*  Returns the count of contacts assigned to user.
	*/
	function getCount($user_name)
	{
		global $log;
		$log->debug("Entering getCount(".$user_name.") method ...");
		$query = "select count(*) from ncrm_contactdetails  inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_contactdetails.contactid inner join ncrm_users on ncrm_users.id=ncrm_crmentity.smownerid where user_name=? and ncrm_crmentity.deleted=0";
		$result = $this->db->pquery($query,array($user_name),true,"Error retrieving contacts count");
		$rows_found =  $this->db->getRowCount($result);
		$row = $this->db->fetchByAssoc($result, 0);


		$log->debug("Exiting getCount method ...");
		return $row["count(*)"];
	}

	// This function doesn't seem to be used anywhere. Need to check and remove it.
	/** Function to get the Contact Details assigned to a particular User based on the starting count and the number of subsequent records.
	*  @param varchar $user_name - Assigned User
	*  @param integer $from_index - Initial record number to be displayed
	*  @param integer $offset - Count of the subsequent records to be displayed.
	*  Returns Query.
	*/
    function get_contacts($user_name,$from_index,$offset)
    {
	global $log;
	$log->debug("Entering get_contacts(".$user_name.",".$from_index.",".$offset.") method ...");
      $query = "select ncrm_users.user_name,ncrm_groups.groupname,ncrm_contactdetails.department department, ncrm_contactdetails.phone office_phone, ncrm_contactdetails.fax fax, ncrm_contactsubdetails.assistant assistant_name, ncrm_contactsubdetails.otherphone other_phone, ncrm_contactsubdetails.homephone home_phone,ncrm_contactsubdetails.birthday birthdate, ncrm_contactdetails.lastname last_name,ncrm_contactdetails.firstname first_name,ncrm_contactdetails.contactid as id, ncrm_contactdetails.salutation as salutation, ncrm_contactdetails.email as email1,ncrm_contactdetails.title as title,ncrm_contactdetails.mobile as phone_mobile,ncrm_account.accountname as account_name,ncrm_account.accountid as account_id, ncrm_contactaddress.mailingcity as primary_address_city,ncrm_contactaddress.mailingstreet as primary_address_street, ncrm_contactaddress.mailingcountry as primary_address_country,ncrm_contactaddress.mailingstate as primary_address_state, ncrm_contactaddress.mailingzip as primary_address_postalcode,   ncrm_contactaddress.othercity as alt_address_city,ncrm_contactaddress.otherstreet as alt_address_street, ncrm_contactaddress.othercountry as alt_address_country,ncrm_contactaddress.otherstate as alt_address_state, ncrm_contactaddress.otherzip as alt_address_postalcode  from ncrm_contactdetails inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_contactdetails.contactid inner join ncrm_users on ncrm_users.id=ncrm_crmentity.smownerid left join ncrm_account on ncrm_account.accountid=ncrm_contactdetails.accountid left join ncrm_contactaddress on ncrm_contactaddress.contactaddressid=ncrm_contactdetails.contactid left join ncrm_contactsubdetails on ncrm_contactsubdetails.contactsubscriptionid = ncrm_contactdetails.contactid left join ncrm_groups on ncrm_groups.groupid=ncrm_crmentity.smownerid left join ncrm_users on ncrm_crmentity.smownerid=ncrm_users.id where user_name='" .$user_name ."' and ncrm_crmentity.deleted=0 limit " .$from_index ."," .$offset;

	$log->debug("Exiting get_contacts method ...");
      return $this->process_list_query1($query);
    }


    /** Function to process list query for a given query
    *  @param $query
    *  Returns the results of query in array format
    */
    function process_list_query1($query)
    {
	global $log;
	$log->debug("Entering process_list_query1(".$query.") method ...");

        $result =& $this->db->query($query,true,"Error retrieving $this->object_name list: ");
        $list = Array();
        $rows_found =  $this->db->getRowCount($result);
        if($rows_found != 0)
        {
		   $contact = Array();
               for($index = 0 , $row = $this->db->fetchByAssoc($result, $index); $row && $index <$rows_found;$index++, $row = $this->db->fetchByAssoc($result, $index))

             {
                foreach($this->range_fields as $columnName)
                {
                    if (isset($row[$columnName])) {

                        $contact[$columnName] = $row[$columnName];
                    }
                    else
                    {
                            $contact[$columnName] = "";
                    }
	     }
// TODO OPTIMIZE THE QUERY ACCOUNT NAME AND ID are set separetly for every ncrm_contactdetails and hence
// ncrm_account query goes for ecery single ncrm_account row

                    $list[] = $contact;
                }
        }

        $response = Array();
        $response['list'] = $list;
        $response['row_count'] = $rows_found;
        $response['next_offset'] = $next_offset;
        $response['previous_offset'] = $previous_offset;


	$log->debug("Exiting process_list_query1 method ...");
        return $response;
    }


    /** Function to process list query for Plugin with Security Parameters for a given query
    *  @param $query
    *  Returns the results of query in array format
    */
    function plugin_process_list_query($query)
    {
          global $log,$adb,$current_user;
          $log->debug("Entering process_list_query1(".$query.") method ...");
          $permitted_field_lists = Array();
          require('user_privileges/user_privileges_'.$current_user->id.'.php');
          if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0)
          {
              $sql1 = "select columnname from ncrm_field where tabid=4 and block <> 75 and ncrm_field.presence in (0,2)";
			  $params1 = array();
          }else
          {
              $profileList = getCurrentUserProfileList();
              $sql1 = "select columnname from ncrm_field inner join ncrm_profile2field on ncrm_profile2field.fieldid=ncrm_field.fieldid inner join ncrm_def_org_field on ncrm_def_org_field.fieldid=ncrm_field.fieldid where ncrm_field.tabid=4 and ncrm_field.block <> 6 and ncrm_field.block <> 75 and ncrm_field.displaytype in (1,2,4,3) and ncrm_profile2field.visible=0 and ncrm_def_org_field.visible=0 and ncrm_field.presence in (0,2)";
			  $params1 = array();
			  if (count($profileList) > 0) {
			  	 $sql1 .= " and ncrm_profile2field.profileid in (". generateQuestionMarks($profileList) .")";
			  	 array_push($params1, $profileList);
			  }
          }
          $result1 = $this->db->pquery($sql1, $params1);
          for($i=0;$i < $adb->num_rows($result1);$i++)
          {
              $permitted_field_lists[] = $adb->query_result($result1,$i,'columnname');
          }

          $result =& $this->db->query($query,true,"Error retrieving $this->object_name list: ");
          $list = Array();
          $rows_found =  $this->db->getRowCount($result);
          if($rows_found != 0)
          {
              for($index = 0 , $row = $this->db->fetchByAssoc($result, $index); $row && $index <$rows_found;$index++, $row = $this->db->fetchByAssoc($result, $index))
              {
                  $contact = Array();

		  $contact[lastname] = in_array("lastname",$permitted_field_lists) ? $row[lastname] : "";
		  $contact[firstname] = in_array("firstname",$permitted_field_lists)? $row[firstname] : "";
		  $contact[email] = in_array("email",$permitted_field_lists) ? $row[email] : "";


                  if(in_array("accountid",$permitted_field_lists))
                  {
                      $contact[accountname] = $row[accountname];
                      $contact[account_id] = $row[accountid];
                  }else
		  {
                      $contact[accountname] = "";
                      $contact[account_id] = "";
		  }
                  $contact[contactid] =  $row[contactid];
                  $list[] = $contact;
              }
          }

          $response = Array();
          $response['list'] = $list;
          $response['row_count'] = $rows_found;
          $response['next_offset'] = $next_offset;
          $response['previous_offset'] = $previous_offset;
          $log->debug("Exiting process_list_query1 method ...");
          return $response;
    }


	/** Returns a list of the associated opportunities
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_opportunities($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_opportunities(".$id.") method ...");
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
				$button .= "<input title='".getTranslatedString('LBL_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\";this.form.return_action.value=\"updateRelations\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}

		// Should Opportunities be listed on Secondary Contacts ignoring the boundaries of Organization.
		// Useful when the Reseller are working to gain Potential for other Organization.
		$ignoreOrganizationCheck = true;

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'ncrm_users.first_name', 'last_name' => 'ncrm_users.last_name'), 'Users');
		$query ='select case when (ncrm_users.user_name not like "") then '.$userNameSql.' else ncrm_groups.groupname end as user_name,
		ncrm_contactdetails.accountid, ncrm_contactdetails.contactid , ncrm_potential.potentialid, ncrm_potential.potentialname,
		ncrm_potential.potentialtype, ncrm_potential.sales_stage, ncrm_potential.amount, ncrm_potential.closingdate,
		ncrm_potential.related_to, ncrm_potential.contact_id, ncrm_crmentity.crmid, ncrm_crmentity.smownerid, ncrm_account.accountname
		from ncrm_contactdetails
		left join ncrm_contpotentialrel on ncrm_contpotentialrel.contactid=ncrm_contactdetails.contactid
		left join ncrm_potential on (ncrm_potential.potentialid = ncrm_contpotentialrel.potentialid or ncrm_potential.contact_id=ncrm_contactdetails.contactid)
		inner join ncrm_crmentity on ncrm_crmentity.crmid = ncrm_potential.potentialid
		left join ncrm_account on ncrm_account.accountid=ncrm_contactdetails.accountid
		LEFT JOIN ncrm_potentialscf ON ncrm_potential.potentialid = ncrm_potentialscf.potentialid
		left join ncrm_groups on ncrm_groups.groupid=ncrm_crmentity.smownerid
		left join ncrm_users on ncrm_users.id=ncrm_crmentity.smownerid
		where  ncrm_crmentity.deleted=0 and ncrm_contactdetails.contactid ='.$id;

		if (!$ignoreOrganizationCheck) {
			// Restrict the scope of listing to only related contacts of the organization linked to potential via related_to of Potential
			$query .= ' and (ncrm_contactdetails.accountid = ncrm_potential.related_to or ncrm_contactdetails.contactid=ncrm_potential.contact_id)';
		}

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_opportunities method ...");
		return $return_value;
	}


	/** Returns a list of the associated tasks
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
				if(getFieldVisibilityPermission('Calendar',$current_user->id,'contact_id', 'readwrite') == '0') {
					$button .= "<input title='".getTranslatedString('LBL_NEW'). " ". getTranslatedString('LBL_TODO', $related_module) ."' class='crmbutton small create'" .
						" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\";this.form.return_module.value=\"$this_module\";this.form.activity_mode.value=\"Task\";' type='submit' name='button'" .
						" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString('LBL_TODO', $related_module) ."'>&nbsp;";
				}
				if(getFieldVisibilityPermission('Events',$current_user->id,'contact_id', 'readwrite') == '0') {
					$button .= "<input title='".getTranslatedString('LBL_NEW'). " ". getTranslatedString('LBL_TODO', $related_module) ."' class='crmbutton small create'" .
						" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\";this.form.return_module.value=\"$this_module\";this.form.activity_mode.value=\"Events\";' type='submit' name='button'" .
						" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString('LBL_EVENT', $related_module) ."'>";
				}
			}
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'ncrm_users.first_name', 'last_name' => 'ncrm_users.last_name'), 'Users');
		$query = "SELECT case when (ncrm_users.user_name not like '') then $userNameSql else ncrm_groups.groupname end as user_name," .
				" ncrm_contactdetails.lastname, ncrm_contactdetails.firstname,  ncrm_activity.activityid ," .
				" ncrm_activity.subject, ncrm_activity.activitytype, ncrm_activity.date_start, ncrm_activity.due_date," .
				" ncrm_activity.time_start,ncrm_activity.time_end, ncrm_cntactivityrel.contactid, ncrm_crmentity.crmid," .
				" ncrm_crmentity.smownerid, ncrm_crmentity.modifiedtime, ncrm_recurringevents.recurringtype," .
				" case when (ncrm_activity.activitytype = 'Task') then ncrm_activity.status else ncrm_activity.eventstatus end as status, " .
				" ncrm_seactivityrel.crmid as parent_id " .
				" from ncrm_contactdetails " .
				" inner join ncrm_cntactivityrel on ncrm_cntactivityrel.contactid = ncrm_contactdetails.contactid" .
				" inner join ncrm_activity on ncrm_cntactivityrel.activityid=ncrm_activity.activityid" .
				" inner join ncrm_crmentity on ncrm_crmentity.crmid = ncrm_cntactivityrel.activityid " .
				" left join ncrm_seactivityrel on ncrm_seactivityrel.activityid = ncrm_cntactivityrel.activityid " .
				" left join ncrm_users on ncrm_users.id=ncrm_crmentity.smownerid" .
				" left outer join ncrm_recurringevents on ncrm_recurringevents.activityid=ncrm_activity.activityid" .
				" left join ncrm_groups on ncrm_groups.groupid=ncrm_crmentity.smownerid" .
				" where ncrm_contactdetails.contactid=".$id." and ncrm_crmentity.deleted = 0" .
						" and ((ncrm_activity.activitytype='Task' and ncrm_activity.status not in ('Completed','Deferred'))" .
						" or (ncrm_activity.activitytype Not in ('Emails','Task') and  ncrm_activity.eventstatus not in ('','Held')))";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_activities method ...");
		return $return_value;
	}
	/**
	* Function to get Contact related Task & Event which have activity type Held, Completed or Deferred.
	* @param  integer   $id      - contactid
	* returns related Task or Event record in array format
	*/
	function get_history($id)
	{
		global $log;
		$log->debug("Entering get_history(".$id.") method ...");
		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'ncrm_users.first_name', 'last_name' => 'ncrm_users.last_name'), 'Users');
		$query = "SELECT ncrm_activity.activityid, ncrm_activity.subject, ncrm_activity.status
			, ncrm_activity.eventstatus,ncrm_activity.activitytype, ncrm_activity.date_start,
			ncrm_activity.due_date,ncrm_activity.time_start,ncrm_activity.time_end,
			ncrm_contactdetails.contactid, ncrm_contactdetails.firstname,
			ncrm_contactdetails.lastname, ncrm_crmentity.modifiedtime,
			ncrm_crmentity.createdtime, ncrm_crmentity.description,ncrm_crmentity.crmid,
			case when (ncrm_users.user_name not like '') then $userNameSql else ncrm_groups.groupname end as user_name
				from ncrm_activity
				inner join ncrm_cntactivityrel on ncrm_cntactivityrel.activityid= ncrm_activity.activityid
				inner join ncrm_contactdetails on ncrm_contactdetails.contactid= ncrm_cntactivityrel.contactid
				inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_activity.activityid
				left join ncrm_seactivityrel on ncrm_seactivityrel.activityid=ncrm_activity.activityid
                left join ncrm_groups on ncrm_groups.groupid=ncrm_crmentity.smownerid
				left join ncrm_users on ncrm_users.id=ncrm_crmentity.smownerid
				where (ncrm_activity.activitytype != 'Emails')
				and (ncrm_activity.status = 'Completed' or ncrm_activity.status = 'Deferred' or (ncrm_activity.eventstatus = 'Held' and ncrm_activity.eventstatus != ''))
				and ncrm_cntactivityrel.contactid=".$id."
                                and ncrm_crmentity.deleted = 0";
		//Don't add order by, because, for security, one more condition will be added with this query in include/RelatedListView.php
		$log->debug("Entering get_history method ...");
		return getHistory('Contacts',$query,$id);
	}
	/**
	* Function to get Contact related Tickets.
	* @param  integer   $id      - contactid
	* returns related Ticket records in array format
	*/
	function get_tickets($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_tickets(".$id.") method ...");
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

		if($actions && getFieldVisibilityPermission($related_module, $current_user->id, 'parent_id', 'readwrite') == '0') {
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
				ncrm_crmentity.crmid, ncrm_troubletickets.title, ncrm_contactdetails.contactid, ncrm_troubletickets.parent_id,
				ncrm_contactdetails.firstname, ncrm_contactdetails.lastname, ncrm_troubletickets.status, ncrm_troubletickets.priority,
				ncrm_crmentity.smownerid, ncrm_troubletickets.ticket_no, ncrm_troubletickets.contact_id
				from ncrm_troubletickets inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_troubletickets.ticketid
				left join ncrm_contactdetails on ncrm_contactdetails.contactid=ncrm_troubletickets.contact_id
				LEFT JOIN ncrm_ticketcf ON ncrm_troubletickets.ticketid = ncrm_ticketcf.ticketid
				left join ncrm_users on ncrm_users.id=ncrm_crmentity.smownerid
				left join ncrm_groups on ncrm_groups.groupid=ncrm_crmentity.smownerid
				where ncrm_crmentity.deleted=0 and ncrm_contactdetails.contactid=".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_tickets method ...");
		return $return_value;
	}

	  /**
	  * Function to get Contact related Quotes
	  * @param  integer   $id  - contactid
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

		if($actions && getFieldVisibilityPermission($related_module, $current_user->id, 'contact_id', 'readwrite') == '0') {
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
		$query = "select case when (ncrm_users.user_name not like '') then $userNameSql else ncrm_groups.groupname end as user_name,ncrm_crmentity.*, ncrm_quotes.*,ncrm_potential.potentialname,ncrm_contactdetails.lastname,ncrm_account.accountname from ncrm_quotes inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_quotes.quoteid left outer join ncrm_contactdetails on ncrm_contactdetails.contactid=ncrm_quotes.contactid left outer join ncrm_potential on ncrm_potential.potentialid=ncrm_quotes.potentialid  left join ncrm_account on ncrm_account.accountid = ncrm_quotes.accountid LEFT JOIN ncrm_quotescf ON ncrm_quotescf.quoteid = ncrm_quotes.quoteid LEFT JOIN ncrm_quotesbillads ON ncrm_quotesbillads.quotebilladdressid = ncrm_quotes.quoteid LEFT JOIN ncrm_quotesshipads ON ncrm_quotesshipads.quoteshipaddressid = ncrm_quotes.quoteid left join ncrm_users on ncrm_users.id=ncrm_crmentity.smownerid left join ncrm_groups on ncrm_groups.groupid=ncrm_crmentity.smownerid where ncrm_crmentity.deleted=0 and ncrm_contactdetails.contactid=".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_quotes method ...");
		return $return_value;
	  }
	/**
	 * Function to get Contact related SalesOrder
 	 * @param  integer   $id  - contactid
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

		if($actions && getFieldVisibilityPermission($related_module, $current_user->id, 'contact_id', 'readwrite') == '0') {
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
		$query = "select case when (ncrm_users.user_name not like '') then $userNameSql else ncrm_groups.groupname end as user_name,ncrm_crmentity.*, ncrm_salesorder.*, ncrm_quotes.subject as quotename, ncrm_account.accountname, ncrm_contactdetails.lastname from ncrm_salesorder inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_salesorder.salesorderid LEFT JOIN ncrm_salesordercf ON ncrm_salesordercf.salesorderid = ncrm_salesorder.salesorderid LEFT JOIN ncrm_sobillads ON ncrm_sobillads.sobilladdressid = ncrm_salesorder.salesorderid LEFT JOIN ncrm_soshipads ON ncrm_soshipads.soshipaddressid = ncrm_salesorder.salesorderid left join ncrm_users on ncrm_users.id=ncrm_crmentity.smownerid left outer join ncrm_quotes on ncrm_quotes.quoteid=ncrm_salesorder.quoteid left outer join ncrm_account on ncrm_account.accountid=ncrm_salesorder.accountid LEFT JOIN ncrm_invoice_recurring_info ON ncrm_invoice_recurring_info.start_period = ncrm_salesorder.salesorderid left outer join ncrm_contactdetails on ncrm_contactdetails.contactid=ncrm_salesorder.contactid left join ncrm_groups on ncrm_groups.groupid=ncrm_crmentity.smownerid where ncrm_crmentity.deleted=0  and  ncrm_salesorder.contactid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_salesorder method ...");
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

		$query = 'SELECT ncrm_products.productid, ncrm_products.productname, ncrm_products.productcode,
		 		  ncrm_products.commissionrate, ncrm_products.qty_per_unit, ncrm_products.unit_price,
				  ncrm_crmentity.crmid, ncrm_crmentity.smownerid,ncrm_contactdetails.lastname
				FROM ncrm_products
				INNER JOIN ncrm_seproductsrel
					ON ncrm_seproductsrel.productid=ncrm_products.productid and ncrm_seproductsrel.setype="Contacts"
				INNER JOIN ncrm_productcf
					ON ncrm_products.productid = ncrm_productcf.productid
				INNER JOIN ncrm_crmentity
					ON ncrm_crmentity.crmid = ncrm_products.productid
				INNER JOIN ncrm_contactdetails
					ON ncrm_contactdetails.contactid = ncrm_seproductsrel.crmid
				LEFT JOIN ncrm_users
					ON ncrm_users.id=ncrm_crmentity.smownerid
				LEFT JOIN ncrm_groups
					ON ncrm_groups.groupid = ncrm_crmentity.smownerid
			   WHERE ncrm_contactdetails.contactid = '.$id.' and ncrm_crmentity.deleted = 0';

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_products method ...");
		return $return_value;
	 }

	/**
	 * Function to get Contact related PurchaseOrder
 	 * @param  integer   $id  - contactid
	 * returns related PurchaseOrder record in array format
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

		if($actions && getFieldVisibilityPermission($related_module, $current_user->id, 'contact_id', 'readwrite') == '0') {
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
		$query = "select case when (ncrm_users.user_name not like '') then $userNameSql else ncrm_groups.groupname end as user_name,ncrm_crmentity.*, ncrm_purchaseorder.*,ncrm_vendor.vendorname,ncrm_contactdetails.lastname from ncrm_purchaseorder inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_purchaseorder.purchaseorderid left outer join ncrm_vendor on ncrm_purchaseorder.vendorid=ncrm_vendor.vendorid left outer join ncrm_contactdetails on ncrm_contactdetails.contactid=ncrm_purchaseorder.contactid left join ncrm_users on ncrm_users.id=ncrm_crmentity.smownerid LEFT JOIN ncrm_purchaseordercf ON ncrm_purchaseordercf.purchaseorderid = ncrm_purchaseorder.purchaseorderid LEFT JOIN ncrm_pobillads ON ncrm_pobillads.pobilladdressid = ncrm_purchaseorder.purchaseorderid LEFT JOIN ncrm_poshipads ON ncrm_poshipads.poshipaddressid = ncrm_purchaseorder.purchaseorderid left join ncrm_groups on ncrm_groups.groupid=ncrm_crmentity.smownerid where ncrm_crmentity.deleted=0 and ncrm_purchaseorder.contactid=".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_purchase_orders method ...");
		return $return_value;
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
		$query = "select case when (ncrm_users.user_name not like '') then $userNameSql else ncrm_groups.groupname end as user_name," .
				" ncrm_activity.activityid, ncrm_activity.subject, ncrm_activity.activitytype, ncrm_crmentity.modifiedtime," .
				" ncrm_crmentity.crmid, ncrm_crmentity.smownerid, ncrm_activity.date_start, ncrm_activity.time_start, ncrm_seactivityrel.crmid as parent_id " .
				" from ncrm_activity, ncrm_seactivityrel, ncrm_contactdetails, ncrm_users, ncrm_crmentity" .
				" left join ncrm_groups on ncrm_groups.groupid=ncrm_crmentity.smownerid" .
				" where ncrm_seactivityrel.activityid = ncrm_activity.activityid" .
				" and ncrm_contactdetails.contactid = ncrm_seactivityrel.crmid and ncrm_users.id=ncrm_crmentity.smownerid" .
				" and ncrm_crmentity.crmid = ncrm_activity.activityid  and ncrm_contactdetails.contactid = ".$id." and" .
						" ncrm_activity.activitytype='Emails' and ncrm_crmentity.deleted = 0";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_emails method ...");
		return $return_value;
	}

	/** Returns a list of the associated Campaigns
	  * @param $id -- campaign id :: Type Integer
	  * @returns list of campaigns in array format
	  */

	function get_campaigns($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_campaigns(".$id.") method ...");
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

		$button .= '<input type="hidden" name="email_directing_module"><input type="hidden" name="record">';

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='". getTranslatedString('LBL_ADD_NEW')." ". getTranslatedString($singular_modname)."' accessyKey='F' class='crmbutton small create' onclick='fnvshobj(this,\"sendmail_cont\");sendmail(\"$this_module\",$id);' type='button' name='button' value='". getTranslatedString('LBL_ADD_NEW')." ". getTranslatedString($singular_modname)."'></td>";
			}
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'ncrm_users.first_name', 'last_name' => 'ncrm_users.last_name'), 'Users');
		$query = "SELECT case when (ncrm_users.user_name not like '') then $userNameSql else ncrm_groups.groupname end as user_name,
					ncrm_campaign.campaignid, ncrm_campaign.campaignname, ncrm_campaign.campaigntype, ncrm_campaign.campaignstatus,
					ncrm_campaign.expectedrevenue, ncrm_campaign.closingdate, ncrm_crmentity.crmid, ncrm_crmentity.smownerid,
					ncrm_crmentity.modifiedtime from ncrm_campaign
					inner join ncrm_campaigncontrel on ncrm_campaigncontrel.campaignid=ncrm_campaign.campaignid
					inner join ncrm_crmentity on ncrm_crmentity.crmid = ncrm_campaign.campaignid
					inner join ncrm_campaignscf ON ncrm_campaignscf.campaignid = ncrm_campaign.campaignid
					left join ncrm_groups on ncrm_groups.groupid=ncrm_crmentity.smownerid
					left join ncrm_users on ncrm_users.id = ncrm_crmentity.smownerid
					where ncrm_campaigncontrel.contactid=".$id." and ncrm_crmentity.deleted=0";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_campaigns method ...");
		return $return_value;
	}

	/**
	* Function to get Contact related Invoices
	* @param  integer   $id      - contactid
	* returns related Invoices record in array format
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

		if($actions && getFieldVisibilityPermission($related_module, $current_user->id, 'contact_id', 'readwrite') == '0') {
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
		$query = "SELECT case when (ncrm_users.user_name not like '') then $userNameSql else ncrm_groups.groupname end as user_name,
			ncrm_crmentity.*,
			ncrm_invoice.*,
			ncrm_contactdetails.lastname,ncrm_contactdetails.firstname,
			ncrm_salesorder.subject AS salessubject
			FROM ncrm_invoice
			INNER JOIN ncrm_crmentity
				ON ncrm_crmentity.crmid = ncrm_invoice.invoiceid
			LEFT OUTER JOIN ncrm_contactdetails
				ON ncrm_contactdetails.contactid = ncrm_invoice.contactid
			LEFT OUTER JOIN ncrm_salesorder
				ON ncrm_salesorder.salesorderid = ncrm_invoice.salesorderid
			LEFT JOIN ncrm_groups
				ON ncrm_groups.groupid = ncrm_crmentity.smownerid
            LEFT JOIN ncrm_invoicecf
                ON ncrm_invoicecf.invoiceid = ncrm_invoice.invoiceid
			LEFT JOIN ncrm_invoicebillads
				ON ncrm_invoicebillads.invoicebilladdressid = ncrm_invoice.invoiceid
			LEFT JOIN ncrm_invoiceshipads
				ON ncrm_invoiceshipads.invoiceshipaddressid = ncrm_invoice.invoiceid
			LEFT JOIN ncrm_users
				ON ncrm_crmentity.smownerid = ncrm_users.id
			WHERE ncrm_crmentity.deleted = 0
			AND ncrm_contactdetails.contactid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_invoices method ...");
		return $return_value;
	}

    /**
	* Function to get Contact related vendors.
	* @param  integer   $id      - contactid
	* returns related vendor records in array format
	*/
	function get_vendors($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_vendors(".$id.") method ...");
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

		if($actions && getFieldVisibilityPermission($related_module, $current_user->id, 'parent_id', 'readwrite') == '0') {
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
		$query = "SELECT case when (ncrm_users.user_name not like '') then $userNameSql else ncrm_groups.groupname end as user_name,
				ncrm_crmentity.crmid, ncrm_vendor.*,  ncrm_vendorcf.*
				from ncrm_vendor inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_vendor.vendorid
                INNER JOIN ncrm_vendorcontactrel on ncrm_vendorcontactrel.vendorid=ncrm_vendor.vendorid
				LEFT JOIN ncrm_vendorcf on ncrm_vendorcf.vendorid=ncrm_vendor.vendorid
				LEFT JOIN ncrm_users on ncrm_users.id=ncrm_crmentity.smownerid
				LEFT JOIN ncrm_groups on ncrm_groups.groupid=ncrm_crmentity.smownerid
				WHERE ncrm_crmentity.deleted=0 and ncrm_vendorcontactrel.contactid=".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_vendors method ...");
		return $return_value;
	}

	/** Function to export the contact records in CSV Format
	* @param reference variable - where condition is passed when the query is executed
	* Returns Export Contacts Query.
	*/
        function create_export_query($where)
        {
		global $log;
		global $current_user;
		$log->debug("Entering create_export_query(".$where.") method ...");

		include("include/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Contacts", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);

		$query = "SELECT ncrm_contactdetails.salutation as 'Salutation',$fields_list,case when (ncrm_users.user_name not like '') then ncrm_users.user_name else ncrm_groups.groupname end as user_name
                                FROM ncrm_contactdetails
                                inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_contactdetails.contactid
                                LEFT JOIN ncrm_users ON ncrm_crmentity.smownerid=ncrm_users.id and ncrm_users.status='Active'
                                LEFT JOIN ncrm_account on ncrm_contactdetails.accountid=ncrm_account.accountid
				left join ncrm_contactaddress on ncrm_contactaddress.contactaddressid=ncrm_contactdetails.contactid
				left join ncrm_contactsubdetails on ncrm_contactsubdetails.contactsubscriptionid=ncrm_contactdetails.contactid
			        left join ncrm_contactscf on ncrm_contactscf.contactid=ncrm_contactdetails.contactid
			        left join ncrm_customerdetails on ncrm_customerdetails.customerid=ncrm_contactdetails.contactid
	                        LEFT JOIN ncrm_groups
                        	        ON ncrm_groups.groupid = ncrm_crmentity.smownerid
				LEFT JOIN ncrm_contactdetails ncrm_contactdetails2
					ON ncrm_contactdetails2.contactid = ncrm_contactdetails.reportsto";
		$query .= getNonAdminAccessControlQuery('Contacts',$current_user);
		$where_auto = " ncrm_crmentity.deleted = 0 ";

                if($where != "")
                   $query .= "  WHERE ($where) AND ".$where_auto;
                else
                   $query .= "  WHERE ".$where_auto;

		$log->info("Export Query Constructed Successfully");
		$log->debug("Exiting create_export_query method ...");
		return $query;
        }


/** Function to get the Columnnames of the Contacts
* Used By ncrmCRM Word Plugin
* Returns the Merge Fields for Word Plugin
*/
function getColumnNames()
{
	global $log, $current_user;
	$log->debug("Entering getColumnNames() method ...");
	require('user_privileges/user_privileges_'.$current_user->id.'.php');
	if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0)
	{
	 $sql1 = "select fieldlabel from ncrm_field where tabid=4 and block <> 75 and ncrm_field.presence in (0,2)";
	 $params1 = array();
	}else
	{
	 $profileList = getCurrentUserProfileList();
	 $sql1 = "select ncrm_field.fieldid,fieldlabel from ncrm_field inner join ncrm_profile2field on ncrm_profile2field.fieldid=ncrm_field.fieldid inner join ncrm_def_org_field on ncrm_def_org_field.fieldid=ncrm_field.fieldid where ncrm_field.tabid=4 and ncrm_field.block <> 75 and ncrm_field.displaytype in (1,2,4,3) and ncrm_profile2field.visible=0 and ncrm_def_org_field.visible=0 and ncrm_field.presence in (0,2)";
	 $params1 = array();
	 if (count($profileList) > 0) {
	 	$sql1 .= " and ncrm_profile2field.profileid in (". generateQuestionMarks($profileList) .") group by fieldid";
  	 	array_push($params1, $profileList);
	 }
  }
	$result = $this->db->pquery($sql1, $params1);
	$numRows = $this->db->num_rows($result);
	for($i=0; $i < $numRows;$i++)
	{
	$custom_fields[$i] = $this->db->query_result($result,$i,"fieldlabel");
	$custom_fields[$i] = preg_replace("/\s+/","",$custom_fields[$i]);
	$custom_fields[$i] = strtoupper($custom_fields[$i]);
	}
	$mergeflds = $custom_fields;
	$log->debug("Exiting getColumnNames method ...");
	return $mergeflds;
}
//End
/** Function to get the Contacts assigned to a user with a valid email address.
* @param varchar $username - User Name
* @param varchar $emailaddress - Email Addr for each contact.
* Used By ncrmCRM Outlook Plugin
* Returns the Query
*/
function get_searchbyemailid($username,$emailaddress)
{
	global $log;
	global $current_user;
	require_once("modules/Users/Users.php");
	$seed_user=new Users();
	$user_id=$seed_user->retrieve_user_id($username);
	$current_user=$seed_user;
	$current_user->retrieve_entity_info($user_id, 'Users');
	require('user_privileges/user_privileges_'.$current_user->id.'.php');
	require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
	$log->debug("Entering get_searchbyemailid(".$username.",".$emailaddress.") method ...");
	$query = "select ncrm_contactdetails.lastname,ncrm_contactdetails.firstname,
					ncrm_contactdetails.contactid, ncrm_contactdetails.salutation,
					ncrm_contactdetails.email,ncrm_contactdetails.title,
					ncrm_contactdetails.mobile,ncrm_account.accountname,
					ncrm_account.accountid as accountid  from ncrm_contactdetails
						inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_contactdetails.contactid
						inner join ncrm_users on ncrm_users.id=ncrm_crmentity.smownerid
						left join ncrm_account on ncrm_account.accountid=ncrm_contactdetails.accountid
						left join ncrm_contactaddress on ncrm_contactaddress.contactaddressid=ncrm_contactdetails.contactid
			      LEFT JOIN ncrm_groups ON ncrm_groups.groupid = ncrm_crmentity.smownerid";
	$query .= getNonAdminAccessControlQuery('Contacts',$current_user);
	$query .= "where ncrm_crmentity.deleted=0";
	if(trim($emailaddress) != '') {
		$query .= " and ((ncrm_contactdetails.email like '". formatForSqlLike($emailaddress) .
		"') or ncrm_contactdetails.lastname REGEXP REPLACE('".$emailaddress.
		"',' ','|') or ncrm_contactdetails.firstname REGEXP REPLACE('".$emailaddress.
		"',' ','|'))  and ncrm_contactdetails.email != ''";
	} else {
		$query .= " and (ncrm_contactdetails.email like '". formatForSqlLike($emailaddress) .
		"' and ncrm_contactdetails.email != '')";
	}

	$log->debug("Exiting get_searchbyemailid method ...");
	return $this->plugin_process_list_query($query);
}

/** Function to get the Contacts associated with the particular User Name.
*  @param varchar $user_name - User Name
*  Returns query
*/

function get_contactsforol($user_name)
{
	global $log,$adb;
	global $current_user;
	require_once("modules/Users/Users.php");
	$seed_user=new Users();
	$user_id=$seed_user->retrieve_user_id($user_name);
	$current_user=$seed_user;
	$current_user->retrieve_entity_info($user_id, 'Users');
	require('user_privileges/user_privileges_'.$current_user->id.'.php');
	require('user_privileges/sharing_privileges_'.$current_user->id.'.php');

	if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0)
  {
    $sql1 = "select tablename,columnname from ncrm_field where tabid=4 and ncrm_field.presence in (0,2)";
	$params1 = array();
  }else
  {
    $profileList = getCurrentUserProfileList();
    $sql1 = "select tablename,columnname from ncrm_field inner join ncrm_profile2field on ncrm_profile2field.fieldid=ncrm_field.fieldid inner join ncrm_def_org_field on ncrm_def_org_field.fieldid=ncrm_field.fieldid where ncrm_field.tabid=4 and ncrm_field.displaytype in (1,2,4,3) and ncrm_profile2field.visible=0 and ncrm_def_org_field.visible=0 and ncrm_field.presence in (0,2)";
	$params1 = array();
	if (count($profileList) > 0) {
		$sql1 .= " and ncrm_profile2field.profileid in (". generateQuestionMarks($profileList) .")";
		array_push($params1, $profileList);
	}
  }
  $result1 = $adb->pquery($sql1, $params1);
  for($i=0;$i < $adb->num_rows($result1);$i++)
  {
      $permitted_lists[] = $adb->query_result($result1,$i,'tablename');
      $permitted_lists[] = $adb->query_result($result1,$i,'columnname');
      if($adb->query_result($result1,$i,'columnname') == "accountid")
      {
        $permitted_lists[] = 'ncrm_account';
        $permitted_lists[] = 'accountname';
      }
  }
	$permitted_lists = array_chunk($permitted_lists,2);
	$column_table_lists = array();
	for($i=0;$i < count($permitted_lists);$i++)
	{
	   $column_table_lists[] = implode(".",$permitted_lists[$i]);
  }

	$log->debug("Entering get_contactsforol(".$user_name.") method ...");
	$query = "select ncrm_contactdetails.contactid as id, ".implode(',',$column_table_lists)." from ncrm_contactdetails
						inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_contactdetails.contactid
						inner join ncrm_users on ncrm_users.id=ncrm_crmentity.smownerid
						left join ncrm_customerdetails on ncrm_customerdetails.customerid=ncrm_contactdetails.contactid
						left join ncrm_account on ncrm_account.accountid=ncrm_contactdetails.accountid
						left join ncrm_contactaddress on ncrm_contactaddress.contactaddressid=ncrm_contactdetails.contactid
						left join ncrm_contactsubdetails on ncrm_contactsubdetails.contactsubscriptionid = ncrm_contactdetails.contactid
                        left join ncrm_campaigncontrel on ncrm_contactdetails.contactid = ncrm_campaigncontrel.contactid
                        left join ncrm_campaignrelstatus on ncrm_campaignrelstatus.campaignrelstatusid = ncrm_campaigncontrel.campaignrelstatusid
			      LEFT JOIN ncrm_groups ON ncrm_groups.groupid = ncrm_crmentity.smownerid
						where ncrm_crmentity.deleted=0 and ncrm_users.user_name='".$user_name."'";
  $log->debug("Exiting get_contactsforol method ...");
	return $query;
}


	/** Function to handle module specific operations when saving a entity
	*/
	function save_module($module)
	{
		$this->insertIntoAttachment($this->id,$module);
	}

	/**
	 *      This function is used to add the ncrm_attachments. This will call the function uploadAndSaveFile which will upload the attachment into the server and save that attachment information in the database.
	 *      @param int $id  - entity id to which the ncrm_files to be uploaded
	 *      @param string $module  - the current module name
	*/
	function insertIntoAttachment($id,$module)
	{
		global $log, $adb,$upload_badext;
		$log->debug("Entering into insertIntoAttachment($id,$module) method.");

		$file_saved = false;
		//This is to added to store the existing attachment id of the contact where we should delete this when we give new image
		$old_attachmentid = $adb->query_result($adb->pquery("select ncrm_crmentity.crmid from ncrm_seattachmentsrel inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_seattachmentsrel.attachmentsid where  ncrm_seattachmentsrel.crmid=?", array($id)),0,'crmid');
		foreach($_FILES as $fileindex => $files)
		{
			if($files['name'] != '' && $files['size'] > 0)
			{
				$files['original_name'] = vtlib_purify($_REQUEST[$fileindex.'_hidden']);
				$file_saved = $this->uploadAndSaveFile($id,$module,$files);
			}
		}

		$imageNameSql = 'SELECT name FROM ncrm_seattachmentsrel INNER JOIN ncrm_attachments ON
								ncrm_seattachmentsrel.attachmentsid = ncrm_attachments.attachmentsid LEFT JOIN ncrm_contactdetails ON
								ncrm_contactdetails.contactid = ncrm_seattachmentsrel.crmid WHERE ncrm_seattachmentsrel.crmid = ?';
		$imageNameResult = $adb->pquery($imageNameSql,array($id));
		$imageName = decode_html($adb->query_result($imageNameResult, 0, "name"));

		//Inserting image information of record into base table
		$adb->pquery('UPDATE ncrm_contactdetails SET imagename = ? WHERE contactid = ?',array($imageName,$id));

		//This is to handle the delete image for contacts
		if($module == 'Contacts' && $file_saved)
		{
			if($old_attachmentid != '')
			{
				$setype = $adb->query_result($adb->pquery("select setype from ncrm_crmentity where crmid=?", array($old_attachmentid)),0,'setype');
				if($setype == 'Contacts Image')
				{
					$del_res1 = $adb->pquery("delete from ncrm_attachments where attachmentsid=?", array($old_attachmentid));
					$del_res2 = $adb->pquery("delete from ncrm_seattachmentsrel where attachmentsid=?", array($old_attachmentid));
				}
			}
		}

		$log->debug("Exiting from insertIntoAttachment($id,$module) method.");
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

		$rel_table_arr = Array("Potentials"=>"ncrm_contpotentialrel","Potentials"=>"ncrm_potential","Activities"=>"ncrm_cntactivityrel",
				"Emails"=>"ncrm_seactivityrel","HelpDesk"=>"ncrm_troubletickets","Quotes"=>"ncrm_quotes","PurchaseOrder"=>"ncrm_purchaseorder",
				"SalesOrder"=>"ncrm_salesorder","Products"=>"ncrm_seproductsrel","Documents"=>"ncrm_senotesrel",
				"Attachments"=>"ncrm_seattachmentsrel","Campaigns"=>"ncrm_campaigncontrel",'Invoice'=>'ncrm_invoice',
                'ServiceContracts'=>'ncrm_servicecontracts','Project'=>'ncrm_project','Assets'=>'ncrm_assets');

		$tbl_field_arr = Array("ncrm_contpotentialrel"=>"potentialid","ncrm_potential"=>"potentialid","ncrm_cntactivityrel"=>"activityid",
				"ncrm_seactivityrel"=>"activityid","ncrm_troubletickets"=>"ticketid","ncrm_quotes"=>"quoteid","ncrm_purchaseorder"=>"purchaseorderid",
				"ncrm_salesorder"=>"salesorderid","ncrm_seproductsrel"=>"productid","ncrm_senotesrel"=>"notesid",
				"ncrm_seattachmentsrel"=>"attachmentsid","ncrm_campaigncontrel"=>"campaignid",'ncrm_invoice'=>'invoiceid',
                'ncrm_servicecontracts'=>'servicecontractsid','ncrm_project'=>'projectid','ncrm_assets'=>'assetsid',
                'ncrm_payments'=>'paymentsid');

		$entity_tbl_field_arr = Array("ncrm_contpotentialrel"=>"contactid","ncrm_potential"=>"contact_id","ncrm_cntactivityrel"=>"contactid",
				"ncrm_seactivityrel"=>"crmid","ncrm_troubletickets"=>"contact_id","ncrm_quotes"=>"contactid","ncrm_purchaseorder"=>"contactid",
				"ncrm_salesorder"=>"contactid","ncrm_seproductsrel"=>"crmid","ncrm_senotesrel"=>"crmid",
				"ncrm_seattachmentsrel"=>"crmid","ncrm_campaigncontrel"=>"contactid",'ncrm_invoice'=>'contactid',
                'ncrm_servicecontracts'=>'sc_related_to','ncrm_project'=>'linktoaccountscontacts','ncrm_assets'=>'contact',
                'ncrm_payments'=>'relatedcontact');

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
			$adb->pquery("UPDATE ncrm_potential SET related_to = ? WHERE related_to = ?", array($entityId, $transferId));
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
		$matrix->setDependency('ncrm_crmentityContacts',array('ncrm_groupsContacts','ncrm_usersContacts','ncrm_lastModifiedByContacts'));
		$matrix->setDependency('ncrm_contactdetails', array('ncrm_crmentityContacts','ncrm_contactaddress',
								'ncrm_customerdetails','ncrm_contactsubdetails','ncrm_contactscf'));

		if (!$queryplanner->requireTable('ncrm_contactdetails', $matrix)) {
			return '';
		}


		$query = $this->getRelationQuery($module,$secmodule,"ncrm_contactdetails","contactid", $queryplanner);

		if ($queryplanner->requireTable("ncrm_crmentityContacts",$matrix)){
			$query .= " left join ncrm_crmentity as ncrm_crmentityContacts on ncrm_crmentityContacts.crmid = ncrm_contactdetails.contactid  and ncrm_crmentityContacts.deleted=0";
		}
		if ($queryplanner->requireTable("ncrm_contactdetailsContacts")){
			$query .= " left join ncrm_contactdetails as ncrm_contactdetailsContacts on ncrm_contactdetailsContacts.contactid = ncrm_contactdetails.reportsto";
		}
		if ($queryplanner->requireTable("ncrm_contactaddress")){
			$query .= " left join ncrm_contactaddress on ncrm_contactdetails.contactid = ncrm_contactaddress.contactaddressid";
		}
		if ($queryplanner->requireTable("ncrm_customerdetails")){
			$query .= " left join ncrm_customerdetails on ncrm_customerdetails.customerid = ncrm_contactdetails.contactid";
		}
		if ($queryplanner->requireTable("ncrm_contactsubdetails")){
			$query .= " left join ncrm_contactsubdetails on ncrm_contactdetails.contactid = ncrm_contactsubdetails.contactsubscriptionid";
		}
		if ($queryplanner->requireTable("ncrm_accountContacts")){
			$query .= " left join ncrm_account as ncrm_accountContacts on ncrm_accountContacts.accountid = ncrm_contactdetails.accountid";
		}
		if ($queryplanner->requireTable("ncrm_contactscf")){
			$query .= " left join ncrm_contactscf on ncrm_contactdetails.contactid = ncrm_contactscf.contactid";
		}
		if ($queryplanner->requireTable("ncrm_email_trackContacts")){
			$query .= " LEFT JOIN ncrm_email_track AS ncrm_email_trackContacts ON ncrm_email_trackContacts.crmid = ncrm_contactdetails.contactid";
		}
		if ($queryplanner->requireTable("ncrm_groupsContacts")){
			$query .= " left join ncrm_groups as ncrm_groupsContacts on ncrm_groupsContacts.groupid = ncrm_crmentityContacts.smownerid";
		}
		if ($queryplanner->requireTable("ncrm_usersContacts")){
			$query .= " left join ncrm_users as ncrm_usersContacts on ncrm_usersContacts.id = ncrm_crmentityContacts.smownerid";
		}
		if ($queryplanner->requireTable("ncrm_lastModifiedByContacts")){
			$query .= " left join ncrm_users as ncrm_lastModifiedByContacts on ncrm_lastModifiedByContacts.id = ncrm_crmentityContacts.modifiedby ";
		}
        if ($queryplanner->requireTable("ncrm_createdbyContacts")){
			$query .= " left join ncrm_users as ncrm_createdbyContacts on ncrm_createdbyContacts.id = ncrm_crmentityContacts.smcreatorid ";
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
			"Calendar" => array("ncrm_cntactivityrel"=>array("contactid","activityid"),"ncrm_contactdetails"=>"contactid"),
			"HelpDesk" => array("ncrm_troubletickets"=>array("contact_id","ticketid"),"ncrm_contactdetails"=>"contactid"),
			"Quotes" => array("ncrm_quotes"=>array("contactid","quoteid"),"ncrm_contactdetails"=>"contactid"),
			"PurchaseOrder" => array("ncrm_purchaseorder"=>array("contactid","purchaseorderid"),"ncrm_contactdetails"=>"contactid"),
			"SalesOrder" => array("ncrm_salesorder"=>array("contactid","salesorderid"),"ncrm_contactdetails"=>"contactid"),
			"Products" => array("ncrm_seproductsrel"=>array("crmid","productid"),"ncrm_contactdetails"=>"contactid"),
			"Campaigns" => array("ncrm_campaigncontrel"=>array("contactid","campaignid"),"ncrm_contactdetails"=>"contactid"),
			"Documents" => array("ncrm_senotesrel"=>array("crmid","notesid"),"ncrm_contactdetails"=>"contactid"),
			"Accounts" => array("ncrm_contactdetails"=>array("contactid","accountid")),
			"Invoice" => array("ncrm_invoice"=>array("contactid","invoiceid"),"ncrm_contactdetails"=>"contactid"),
			"Emails" => array("ncrm_seactivityrel"=>array("crmid","activityid"),"ncrm_contactdetails"=>"contactid"),
		);
		return $rel_tables[$secmodule];
	}

	// Function to unlink all the dependent entities of the given Entity by Id
	function unlinkDependencies($module, $id) {
		global $log;

		//Deleting Contact related Potentials.
		$pot_q = 'SELECT ncrm_crmentity.crmid FROM ncrm_crmentity
			INNER JOIN ncrm_potential ON ncrm_crmentity.crmid=ncrm_potential.potentialid
			LEFT JOIN ncrm_account ON ncrm_account.accountid=ncrm_potential.related_to
			WHERE ncrm_crmentity.deleted=0 AND ncrm_potential.related_to=?';
		$pot_res = $this->db->pquery($pot_q, array($id));
		$pot_ids_list = array();
		for($k=0;$k < $this->db->num_rows($pot_res);$k++)
		{
			$pot_id = $this->db->query_result($pot_res,$k,"crmid");
			$pot_ids_list[] = $pot_id;
			$sql = 'UPDATE ncrm_crmentity SET deleted = 1 WHERE crmid = ?';
			$this->db->pquery($sql, array($pot_id));
		}
		//Backup deleted Contact related Potentials.
		$params = array($id, RB_RECORD_UPDATED, 'ncrm_crmentity', 'deleted', 'crmid', implode(",", $pot_ids_list));
		$this->db->pquery('INSERT INTO ncrm_relatedlists_rb VALUES(?,?,?,?,?,?)', $params);

		//Backup Contact-Trouble Tickets Relation
		$tkt_q = 'SELECT ticketid FROM ncrm_troubletickets WHERE contact_id=?';
		$tkt_res = $this->db->pquery($tkt_q, array($id));
		if ($this->db->num_rows($tkt_res) > 0) {
			$tkt_ids_list = array();
			for($k=0;$k < $this->db->num_rows($tkt_res);$k++)
			{
				$tkt_ids_list[] = $this->db->query_result($tkt_res,$k,"ticketid");
			}
			$params = array($id, RB_RECORD_UPDATED, 'ncrm_troubletickets', 'contact_id', 'ticketid', implode(",", $tkt_ids_list));
			$this->db->pquery('INSERT INTO ncrm_relatedlists_rb VALUES (?,?,?,?,?,?)', $params);
		}
		//removing the relationship of contacts with Trouble Tickets
		$this->db->pquery('UPDATE ncrm_troubletickets SET contact_id=0 WHERE contact_id=?', array($id));

		//Backup Contact-PurchaseOrder Relation
		$po_q = 'SELECT purchaseorderid FROM ncrm_purchaseorder WHERE contactid=?';
		$po_res = $this->db->pquery($po_q, array($id));
		if ($this->db->num_rows($po_res) > 0) {
			$po_ids_list = array();
			for($k=0;$k < $this->db->num_rows($po_res);$k++)
			{
				$po_ids_list[] = $this->db->query_result($po_res,$k,"purchaseorderid");
			}
			$params = array($id, RB_RECORD_UPDATED, 'ncrm_purchaseorder', 'contactid', 'purchaseorderid', implode(",", $po_ids_list));
			$this->db->pquery('INSERT INTO ncrm_relatedlists_rb VALUES (?,?,?,?,?,?)', $params);
		}
		//removing the relationship of contacts with PurchaseOrder
		$this->db->pquery('UPDATE ncrm_purchaseorder SET contactid=0 WHERE contactid=?', array($id));

		//Backup Contact-SalesOrder Relation
		$so_q = 'SELECT salesorderid FROM ncrm_salesorder WHERE contactid=?';
		$so_res = $this->db->pquery($so_q, array($id));
		if ($this->db->num_rows($so_res) > 0) {
			$so_ids_list = array();
			for($k=0;$k < $this->db->num_rows($so_res);$k++)
			{
				$so_ids_list[] = $this->db->query_result($so_res,$k,"salesorderid");
			}
			$params = array($id, RB_RECORD_UPDATED, 'ncrm_salesorder', 'contactid', 'salesorderid', implode(",", $so_ids_list));
			$this->db->pquery('INSERT INTO ncrm_relatedlists_rb VALUES (?,?,?,?,?,?)', $params);
		}
		//removing the relationship of contacts with SalesOrder
		$this->db->pquery('UPDATE ncrm_salesorder SET contactid=0 WHERE contactid=?', array($id));

		//Backup Contact-Quotes Relation
		$quo_q = 'SELECT quoteid FROM ncrm_quotes WHERE contactid=?';
		$quo_res = $this->db->pquery($quo_q, array($id));
		if ($this->db->num_rows($quo_res) > 0) {
			$quo_ids_list = array();
			for($k=0;$k < $this->db->num_rows($quo_res);$k++)
			{
				$quo_ids_list[] = $this->db->query_result($quo_res,$k,"quoteid");
			}
			$params = array($id, RB_RECORD_UPDATED, 'ncrm_quotes', 'contactid', 'quoteid', implode(",", $quo_ids_list));
			$this->db->pquery('INSERT INTO ncrm_relatedlists_rb VALUES (?,?,?,?,?,?)', $params);
		}
		//removing the relationship of contacts with Quotes
		$this->db->pquery('UPDATE ncrm_quotes SET contactid=0 WHERE contactid=?', array($id));
		//remove the portal info the contact
		$this->db->pquery('DELETE FROM ncrm_portalinfo WHERE id = ?', array($id));
		$this->db->pquery('UPDATE ncrm_customerdetails SET portal=0,support_start_date=NULL,support_end_date=NULl WHERE customerid=?', array($id));
		parent::unlinkDependencies($module, $id);
	}

	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;

		if($return_module == 'Accounts') {
			$sql = 'UPDATE ncrm_contactdetails SET accountid = ? WHERE contactid = ?';
			$this->db->pquery($sql, array(null, $id));
		} elseif($return_module == 'Potentials') {
			$sql = 'DELETE FROM ncrm_contpotentialrel WHERE contactid=? AND potentialid=?';
			$this->db->pquery($sql, array($id, $return_id));

			//If contact related to potential through edit of record,that entry will be present in
			//ncrm_potential contact_id column,which should be set to zero
			$sql = 'UPDATE ncrm_potential SET contact_id = ? WHERE contact_id=? AND potentialid=?';
			$this->db->pquery($sql, array(0,$id, $return_id));
		} elseif($return_module == 'Campaigns') {
			$sql = 'DELETE FROM ncrm_campaigncontrel WHERE contactid=? AND campaignid=?';
			$this->db->pquery($sql, array($id, $return_id));
		} elseif($return_module == 'Products') {
			$sql = 'DELETE FROM ncrm_seproductsrel WHERE crmid=? AND productid=?';
			$this->db->pquery($sql, array($id, $return_id));
		} elseif($return_module == 'Vendors') {
			$sql = 'DELETE FROM ncrm_vendorcontactrel WHERE vendorid=? AND contactid=?';
			$this->db->pquery($sql, array($return_id, $id));
		} else {
			$sql = 'DELETE FROM ncrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
			$params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
			$this->db->pquery($sql, $params);
		}
	}

	//added to get mail info for portal user
	//type argument included when when addin customizable tempalte for sending portal login details
	public static function getPortalEmailContents($entityData, $password, $type='') {
        require_once 'config.inc.php';
		global $PORTAL_URL, $HELPDESK_SUPPORT_EMAIL_ID;

		$adb = PearDatabase::getInstance();
		$moduleName = $entityData->getModuleName();

		$companyDetails = getCompanyDetails();

		$portalURL = '<a href="'.$PORTAL_URL.'" style="font-family:Arial, Helvetica, sans-serif;font-size:12px; font-weight:bolder;text-decoration:none;color: #4242FD;">'.getTranslatedString('Please Login Here', $moduleName).'</a>';

		//here id is hardcoded with 5. it is for support start notification in ncrm_notificationscheduler
		$query='SELECT ncrm_emailtemplates.subject,ncrm_emailtemplates.body
					FROM ncrm_notificationscheduler
						INNER JOIN ncrm_emailtemplates ON ncrm_emailtemplates.templateid=ncrm_notificationscheduler.notificationbody
					WHERE schedulednotificationid=5';

		$result = $adb->pquery($query, array());
		$body=decode_html($adb->query_result($result,0,'body'));
		$contents=$body;
		$contents = str_replace('$contact_name$',$entityData->get('firstname')." ".$entityData->get('lastname'),$contents);
		$contents = str_replace('$login_name$',$entityData->get('email'),$contents);
		$contents = str_replace('$password$',$password,$contents);
		$contents = str_replace('$URL$',$portalURL,$contents);
		$contents = str_replace('$support_team$',getTranslatedString('Support Team', $moduleName),$contents);
		$contents = str_replace('$logo$','<img src="cid:logo" />',$contents);

		//Company Details
		$contents = str_replace('$address$',$companyDetails['address'],$contents);
		$contents = str_replace('$companyname$',$companyDetails['companyname'],$contents);
		$contents = str_replace('$phone$',$companyDetails['phone'],$contents);
		$contents = str_replace('$companywebsite$',$companyDetails['website'],$contents);
		$contents = str_replace('$supportemail$',$HELPDESK_SUPPORT_EMAIL_ID,$contents);

		if($type == "LoginDetails") {
			$temp=$contents;
			$value["subject"]=decode_html($adb->query_result($result,0,'subject'));
			$value["body"]=$temp;
			return $value;
		}
		return $contents;
	}

	function save_related_module($module, $crmid, $with_module, $with_crmids) {
		$adb = PearDatabase::getInstance();

		if(!is_array($with_crmids)) $with_crmids = Array($with_crmids);
		foreach($with_crmids as $with_crmid) {
			if($with_module == 'Products') {
				$adb->pquery("insert into ncrm_seproductsrel values (?,?,?)", array($crmid, $with_crmid, 'Contacts'));

			} elseif($with_module == 'Campaigns') {
				$adb->pquery("insert into ncrm_campaigncontrel values(?,?,1)", array($with_crmid, $crmid));

			} elseif($with_module == 'Potentials') {
				$adb->pquery("insert into ncrm_contpotentialrel values(?,?)", array($crmid, $with_crmid));

			}
            else if($with_module == 'Vendors'){
        		$adb->pquery("insert into ncrm_vendorcontactrel values (?,?)", array($with_crmid,$crmid));
            }else {
				parent::save_related_module($module, $crmid, $with_module, $with_crmid);
			}
		}
	}

	function getListButtons($app_strings,$mod_strings = false) {
		$list_buttons = Array();

		if(isPermitted('Contacts','Delete','') == 'yes') {
			$list_buttons['del'] = $app_strings[LBL_MASS_DELETE];
		}
		if(isPermitted('Contacts','EditView','') == 'yes') {
			$list_buttons['mass_edit'] = $app_strings[LBL_MASS_EDIT];
			$list_buttons['c_owner'] = $app_strings[LBL_CHANGE_OWNER];
		}
		if(isPermitted('Emails','EditView','') == 'yes'){
			$list_buttons['s_mail'] = $app_strings[LBL_SEND_MAIL_BUTTON];
		}
		return $list_buttons;
	}
}

?>