<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of txhe License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
class Leads extends CRMEntity {
	var $log;
	var $db;

	var $table_name = "ncrm_leaddetails";
	var $table_index= 'leadid';

	var $tab_name = Array('ncrm_crmentity','ncrm_leaddetails','ncrm_leadsubdetails','ncrm_leadaddress','ncrm_leadscf');
	var $tab_name_index = Array('ncrm_crmentity'=>'crmid','ncrm_leaddetails'=>'leadid','ncrm_leadsubdetails'=>'leadsubscriptionid','ncrm_leadaddress'=>'leadaddressid','ncrm_leadscf'=>'leadid');

	var $entity_table = "ncrm_crmentity";

	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('ncrm_leadscf', 'leadid');

	//construct this from database;
	var $column_fields = Array();
	var $sortby_fields = Array('lastname','firstname','email','phone','company','smownerid','website');

	// This is used to retrieve related ncrm_fields from form posts.
	var $additional_column_fields = Array('smcreatorid', 'smownerid', 'contactid','potentialid' ,'crmid');

	// This is the list of ncrm_fields that are in the lists.
	var $list_fields = Array(
		'First Name'=>Array('leaddetails'=>'firstname'),
		'Last Name'=>Array('leaddetails'=>'lastname'),
		'Company'=>Array('leaddetails'=>'company'),
		'Phone'=>Array('leadaddress'=>'phone'),
		'Website'=>Array('leadsubdetails'=>'website'),
		'Email'=>Array('leaddetails'=>'email'),
		'Assigned To'=>Array('crmentity'=>'smownerid')
	);
	var $list_fields_name = Array(
		'First Name'=>'firstname',
		'Last Name'=>'lastname',
		'Company'=>'company',
		'Phone'=>'phone',
		'Website'=>'website',
		'Email'=>'email',
		'Assigned To'=>'assigned_user_id'
	);
	var $list_link_field= 'lastname';

	var $search_fields = Array(
		'Name'=>Array('leaddetails'=>'lastname'),
		'Company'=>Array('leaddetails'=>'company')
	);
	var $search_fields_name = Array(
		'Name'=>'lastname',
		'Company'=>'company'
	);

	var $required_fields =  array();

	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to ncrm_field.fieldname values.
	var $mandatory_fields = Array('assigned_user_id', 'lastname', 'createdtime' ,'modifiedtime');

	//Default Fields for Email Templates -- Pavani
	var $emailTemplate_defaultFields = array('firstname','lastname','leadsource','leadstatus','rating','industry','secondaryemail','email','annualrevenue','designation','salutation');

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'lastname';
	var $default_sort_order = 'ASC';

	// For Alphabetical search
	var $def_basicsearch_col = 'lastname';

	//var $groupTable = Array('ncrm_leadgrouprelation','leadid');

	function Leads()	{
		$this->log = LoggerManager::getLogger('lead');
		$this->log->debug("Entering Leads() method ...");
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Leads');
		$this->log->debug("Exiting Lead method ...");
	}

	/** Function to handle module specific operations when saving a entity
	*/
	function save_module($module)
	{
	}

	// Mike Crowe Mod --------------------------------------------------------Default ordering for us

	/** Function to export the lead records in CSV Format
	* @param reference variable - where condition is passed when the query is executed
	* Returns Export Leads Query.
	*/
	function create_export_query($where)
	{
		global $log;
		global $current_user;
		$log->debug("Entering create_export_query(".$where.") method ...");

		include("include/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Leads", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'ncrm_users.first_name', 'last_name' => 'ncrm_users.last_name'), 'Users');
		$query = "SELECT $fields_list,case when (ncrm_users.user_name not like '') then $userNameSql else ncrm_groups.groupname end as user_name
	      			FROM ".$this->entity_table."
				INNER JOIN ncrm_leaddetails
					ON ncrm_crmentity.crmid=ncrm_leaddetails.leadid
				LEFT JOIN ncrm_leadsubdetails
					ON ncrm_leaddetails.leadid = ncrm_leadsubdetails.leadsubscriptionid
				LEFT JOIN ncrm_leadaddress
					ON ncrm_leaddetails.leadid=ncrm_leadaddress.leadaddressid
				LEFT JOIN ncrm_leadscf
					ON ncrm_leadscf.leadid=ncrm_leaddetails.leadid
	                        LEFT JOIN ncrm_groups
                        	        ON ncrm_groups.groupid = ncrm_crmentity.smownerid
				LEFT JOIN ncrm_users
					ON ncrm_crmentity.smownerid = ncrm_users.id and ncrm_users.status='Active'
				";

		$query .= $this->getNonAdminAccessControlQuery('Leads',$current_user);
		$where_auto = " ncrm_crmentity.deleted=0 AND ncrm_leaddetails.converted =0";

		if($where != "")
			$query .= " where ($where) AND ".$where_auto;
		else
			$query .= " where ".$where_auto;

		$log->debug("Exiting create_export_query method ...");
		return $query;
	}



	/** Returns a list of the associated tasks
 	 * @param  integer   $id      - leadid
 	 * returns related Task or Event record in array format
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
		$query = "SELECT ncrm_activity.*,ncrm_seactivityrel.crmid as parent_id, ncrm_contactdetails.lastname,
			ncrm_contactdetails.contactid, ncrm_crmentity.crmid, ncrm_crmentity.smownerid,
			ncrm_crmentity.modifiedtime,case when (ncrm_users.user_name not like '') then
		$userNameSql else ncrm_groups.groupname end as user_name,
		ncrm_recurringevents.recurringtype
		from ncrm_activity inner join ncrm_seactivityrel on ncrm_seactivityrel.activityid=
		ncrm_activity.activityid inner join ncrm_crmentity on ncrm_crmentity.crmid=
		ncrm_activity.activityid left join ncrm_cntactivityrel on
		ncrm_cntactivityrel.activityid = ncrm_activity.activityid left join
		ncrm_contactdetails on ncrm_contactdetails.contactid = ncrm_cntactivityrel.contactid
		left join ncrm_users on ncrm_users.id=ncrm_crmentity.smownerid
		left outer join ncrm_recurringevents on ncrm_recurringevents.activityid=
		ncrm_activity.activityid left join ncrm_groups on ncrm_groups.groupid=
		ncrm_crmentity.smownerid where ncrm_seactivityrel.crmid=".$id." and
			ncrm_crmentity.deleted = 0 and ((ncrm_activity.activitytype='Task' and
			ncrm_activity.status not in ('Completed','Deferred')) or
			(ncrm_activity.activitytype NOT in ('Emails','Task') and
			ncrm_activity.eventstatus not in ('','Held'))) ";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_activities method ...");
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
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'ncrm_users.first_name', 'last_name' => 'ncrm_users.last_name'), 'Users');
		$query = "SELECT case when (ncrm_users.user_name not like '') then $userNameSql else ncrm_groups.groupname end as user_name ,
				ncrm_campaign.campaignid, ncrm_campaign.campaignname, ncrm_campaign.campaigntype, ncrm_campaign.campaignstatus,
				ncrm_campaign.expectedrevenue, ncrm_campaign.closingdate, ncrm_crmentity.crmid, ncrm_crmentity.smownerid,
				ncrm_crmentity.modifiedtime from ncrm_campaign
				inner join ncrm_campaignleadrel on ncrm_campaignleadrel.campaignid=ncrm_campaign.campaignid
				inner join ncrm_crmentity on ncrm_crmentity.crmid = ncrm_campaign.campaignid
				inner join ncrm_campaignscf ON ncrm_campaignscf.campaignid = ncrm_campaign.campaignid
				left join ncrm_groups on ncrm_groups.groupid=ncrm_crmentity.smownerid
				left join ncrm_users on ncrm_users.id = ncrm_crmentity.smownerid
				where ncrm_campaignleadrel.leadid=".$id." and ncrm_crmentity.deleted=0";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_campaigns method ...");
		return $return_value;
	}


		/** Returns a list of the associated emails
	 	 * @param  integer   $id      - leadid
	 	 * returns related emails record in array format
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
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='". getTranslatedString('LBL_ADD_NEW')." ". getTranslatedString($singular_modname)."' accessyKey='F' class='crmbutton small create' onclick='fnvshobj(this,\"sendmail_cont\");sendmail(\"$this_module\",$id);' type='button' name='button' value='". getTranslatedString('LBL_ADD_NEW')." ". getTranslatedString($singular_modname)."'></td>";
			}
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'ncrm_users.first_name', 'last_name' => 'ncrm_users.last_name'), 'Users');
		$query ="select case when (ncrm_users.user_name not like '') then $userNameSql else ncrm_groups.groupname end as user_name," .
				" ncrm_activity.activityid, ncrm_activity.subject, ncrm_activity.semodule, ncrm_activity.activitytype," .
				" ncrm_activity.date_start, ncrm_activity.time_start, ncrm_activity.status, ncrm_activity.priority, ncrm_crmentity.crmid," .
				" ncrm_crmentity.smownerid,ncrm_crmentity.modifiedtime, ncrm_users.user_name, ncrm_seactivityrel.crmid as parent_id " .
				" from ncrm_activity" .
				" inner join ncrm_seactivityrel on ncrm_seactivityrel.activityid=ncrm_activity.activityid" .
				" inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_activity.activityid" .
				" left join ncrm_groups on ncrm_groups.groupid=ncrm_crmentity.smownerid" .
				" left join ncrm_users on  ncrm_users.id=ncrm_crmentity.smownerid" .
				" where ncrm_activity.activitytype='Emails' and ncrm_crmentity.deleted=0 and ncrm_seactivityrel.crmid=".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_emails method ...");
		return $return_value;
	}

	/**
	 * Function to get Lead related Task & Event which have activity type Held, Completed or Deferred.
	 * @param  integer   $id      - leadid
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
			ncrm_activity.due_date,ncrm_activity.time_start,ncrm_activity.time_end,
			ncrm_crmentity.modifiedtime,ncrm_crmentity.createdtime,
			ncrm_crmentity.description, $userNameSql as user_name,ncrm_groups.groupname
				from ncrm_activity
				inner join ncrm_seactivityrel on ncrm_seactivityrel.activityid=ncrm_activity.activityid
				inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_activity.activityid
				left join ncrm_groups on ncrm_groups.groupid=ncrm_crmentity.smownerid
				left join ncrm_users on ncrm_crmentity.smownerid= ncrm_users.id
				where (ncrm_activity.activitytype != 'Emails')
				and (ncrm_activity.status = 'Completed' or ncrm_activity.status = 'Deferred' or (ncrm_activity.eventstatus = 'Held' and ncrm_activity.eventstatus != ''))
				and ncrm_seactivityrel.crmid=".$id."
	                        and ncrm_crmentity.deleted = 0";
		//Don't add order by, because, for security, one more condition will be added with this query in include/RelatedListView.php

		$log->debug("Exiting get_history method ...");
		return getHistory('Leads',$query,$id);
	}

	/**
	* Function to get lead related Products
	* @param  integer   $id      - leadid
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
				INNER JOIN ncrm_seproductsrel ON ncrm_products.productid = ncrm_seproductsrel.productid  and ncrm_seproductsrel.setype = 'Leads'
			 	INNER JOIN ncrm_productcf
					ON ncrm_products.productid = ncrm_productcf.productid
				INNER JOIN ncrm_crmentity ON ncrm_crmentity.crmid = ncrm_products.productid
				INNER JOIN ncrm_leaddetails ON ncrm_leaddetails.leadid = ncrm_seproductsrel.crmid
				LEFT JOIN ncrm_users
					ON ncrm_users.id=ncrm_crmentity.smownerid
				LEFT JOIN ncrm_groups
					ON ncrm_groups.groupid = ncrm_crmentity.smownerid
			   WHERE ncrm_crmentity.deleted = 0 AND ncrm_leaddetails.leadid = $id";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_products method ...");
		return $return_value;
	}

	/** Function to get the Columnnames of the Leads Record
	* Used By ncrmCRM Word Plugin
	* Returns the Merge Fields for Word Plugin
	*/
	function getColumnNames_Lead()
	{
		global $log,$current_user;
		$log->debug("Entering getColumnNames_Lead() method ...");
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0)
		{
			$sql1 = "select fieldlabel from ncrm_field where tabid=7 and ncrm_field.presence in (0,2)";
			$params1 = array();
		}else
		{
			$profileList = getCurrentUserProfileList();
			$sql1 = "select ncrm_field.fieldid,fieldlabel from ncrm_field inner join ncrm_profile2field on ncrm_profile2field.fieldid=ncrm_field.fieldid inner join ncrm_def_org_field on ncrm_def_org_field.fieldid=ncrm_field.fieldid where ncrm_field.tabid=7 and ncrm_field.displaytype in (1,2,3,4) and ncrm_profile2field.visible=0 and ncrm_def_org_field.visible=0 and ncrm_field.presence in (0,2)";
			$params1 = array();
			if (count($profileList) > 0) {
				$sql1 .= " and ncrm_profile2field.profileid in (". generateQuestionMarks($profileList) .")  group by fieldid";
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
		$log->debug("Exiting getColumnNames_Lead method ...");
		return $mergeflds;
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

		$rel_table_arr = Array("Activities"=>"ncrm_seactivityrel","Documents"=>"ncrm_senotesrel","Attachments"=>"ncrm_seattachmentsrel",
					"Products"=>"ncrm_seproductsrel","Campaigns"=>"ncrm_campaignleadrel");

		$tbl_field_arr = Array("ncrm_seactivityrel"=>"activityid","ncrm_senotesrel"=>"notesid","ncrm_seattachmentsrel"=>"attachmentsid",
					"ncrm_seproductsrel"=>"productid","ncrm_campaignleadrel"=>"campaignid");

		$entity_tbl_field_arr = Array("ncrm_seactivityrel"=>"crmid","ncrm_senotesrel"=>"crmid","ncrm_seattachmentsrel"=>"crmid",
					"ncrm_seproductsrel"=>"crmid","ncrm_campaignleadrel"=>"leadid");

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
	function generateReportsSecQuery($module,$secmodule, $queryPlanner) {
		$matrix = $queryPlanner->newDependencyMatrix();
		$matrix->setDependency('ncrm_leaddetails',array('ncrm_crmentityLeads', 'ncrm_leadaddress','ncrm_leadsubdetails','ncrm_leadscf','ncrm_email_trackLeads'));
		$matrix->setDependency('ncrm_crmentityLeads',array('ncrm_groupsLeads','ncrm_usersLeads','ncrm_lastModifiedByLeads'));

		// TODO Support query planner
		if (!$queryPlanner->requireTable("ncrm_leaddetails",$matrix)){
			return '';
		}
		$query = $this->getRelationQuery($module,$secmodule,"ncrm_leaddetails","leadid", $queryPlanner);
		if ($queryPlanner->requireTable("ncrm_crmentityLeads",$matrix)){
		    $query .= " left join ncrm_crmentity as ncrm_crmentityLeads on ncrm_crmentityLeads.crmid = ncrm_leaddetails.leadid and ncrm_crmentityLeads.deleted=0";
		}
		if ($queryPlanner->requireTable("ncrm_leadaddress")){
		    $query .= " left join ncrm_leadaddress on ncrm_leaddetails.leadid = ncrm_leadaddress.leadaddressid";
		}
		if ($queryPlanner->requireTable("ncrm_leadsubdetails")){
		    $query .= " left join ncrm_leadsubdetails on ncrm_leadsubdetails.leadsubscriptionid = ncrm_leaddetails.leadid";
		}
		if ($queryPlanner->requireTable("ncrm_leadscf")){
		    $query .= " left join ncrm_leadscf on ncrm_leadscf.leadid = ncrm_leaddetails.leadid";
		}
		if ($queryPlanner->requireTable("ncrm_email_trackLeads")){
		    $query .= " LEFT JOIN ncrm_email_track AS ncrm_email_trackLeads ON ncrm_email_trackLeads.crmid = ncrm_leaddetails.leadid";
		}
		if ($queryPlanner->requireTable("ncrm_groupsLeads")){
		    $query .= " left join ncrm_groups as ncrm_groupsLeads on ncrm_groupsLeads.groupid = ncrm_crmentityLeads.smownerid";
		}
		if ($queryPlanner->requireTable("ncrm_usersLeads")){
		    $query .= " left join ncrm_users as ncrm_usersLeads on ncrm_usersLeads.id = ncrm_crmentityLeads.smownerid";
		}
		if ($queryPlanner->requireTable("ncrm_lastModifiedByLeads")){
		    $query .= " left join ncrm_users as ncrm_lastModifiedByLeads on ncrm_lastModifiedByLeads.id = ncrm_crmentityLeads.modifiedby ";
		}
        if ($queryPlanner->requireTable("ncrm_createdbyLeads")){
			$query .= " left join ncrm_users as ncrm_createdbyLeads on ncrm_createdbyLeads.id = ncrm_crmentityLeads.smcreatorid ";
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
			"Calendar" => array("ncrm_seactivityrel"=>array("crmid","activityid"),"ncrm_leaddetails"=>"leadid"),
			"Products" => array("ncrm_seproductsrel"=>array("crmid","productid"),"ncrm_leaddetails"=>"leadid"),
			"Campaigns" => array("ncrm_campaignleadrel"=>array("leadid","campaignid"),"ncrm_leaddetails"=>"leadid"),
			"Documents" => array("ncrm_senotesrel"=>array("crmid","notesid"),"ncrm_leaddetails"=>"leadid"),
			"Services" => array("ncrm_crmentityrel"=>array("crmid","relcrmid"),"ncrm_leaddetails"=>"leadid"),
			"Emails" => array("ncrm_seactivityrel"=>array("crmid","activityid"),"ncrm_leaddetails"=>"leadid"),
		);
		return $rel_tables[$secmodule];
	}

	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;

		if($return_module == 'Campaigns') {
			$sql = 'DELETE FROM ncrm_campaignleadrel WHERE leadid=? AND campaignid=?';
			$this->db->pquery($sql, array($id, $return_id));
		}
		elseif($return_module == 'Products') {
			$sql = 'DELETE FROM ncrm_seproductsrel WHERE crmid=? AND productid=?';
			$this->db->pquery($sql, array($id, $return_id));
		} else {
			$sql = 'DELETE FROM ncrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
			$params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
			$this->db->pquery($sql, $params);
		}
	}

	function getListButtons($app_strings,$mod_strings = false) {
		$list_buttons = Array();

		if(isPermitted('Leads','Delete','') == 'yes') {
			$list_buttons['del'] =	$app_strings[LBL_MASS_DELETE];
		}
		if(isPermitted('Leads','EditView','') == 'yes') {
			$list_buttons['mass_edit'] = $app_strings[LBL_MASS_EDIT];
			$list_buttons['c_owner'] = $app_strings[LBL_CHANGE_OWNER];
		}
		if(isPermitted('Emails','EditView','') == 'yes')
			$list_buttons['s_mail'] = $app_strings[LBL_SEND_MAIL_BUTTON];

		// end of mailer export
		return $list_buttons;
	}

	function save_related_module($module, $crmid, $with_module, $with_crmids) {
		$adb = PearDatabase::getInstance();

		if(!is_array($with_crmids)) $with_crmids = Array($with_crmids);
		foreach($with_crmids as $with_crmid) {
			if($with_module == 'Products')
				$adb->pquery("insert into ncrm_seproductsrel values (?,?,?)", array($crmid, $with_crmid, $module));
			elseif($with_module == 'Campaigns')
				$adb->pquery("insert into  ncrm_campaignleadrel values(?,?,1)", array($with_crmid, $crmid));
			else {
				parent::save_related_module($module, $crmid, $with_module, $with_crmid);
			}
		}
	}

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
				if($tableName != 'ncrm_crmentity' && $tableName != $this->table_name) {
					if($this->tab_name_index[$tableName]) {
						$fromClause .= " INNER JOIN " . $tableName . " ON " . $tableName . '.' . $this->tab_name_index[$tableName] .
							" = $this->table_name.$this->table_index";
					}
				}
			}
		}
        $fromClause .= " LEFT JOIN ncrm_users ON ncrm_users.id = ncrm_crmentity.smownerid
						LEFT JOIN ncrm_groups ON ncrm_groups.groupid = ncrm_crmentity.smownerid";

        $whereClause = " WHERE ncrm_crmentity.deleted = 0 AND ncrm_leaddetails.converted=0 ";
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