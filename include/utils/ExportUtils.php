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


/**	function used to get the permitted blocks
 *	@param string $module - module name
 *	@param string $disp_view - view name, this may be create_view, edit_view or detail_view
 *	@return string $blockid_list - list of block ids within the paranthesis with comma seperated
 */
function getPermittedBlocks($module, $disp_view)
{
	global $adb, $log;
	$log->debug("Entering into the function getPermittedBlocks($module, $disp_view)");

        $tabid = getTabid($module);
        $block_detail = Array();
        $query="select blockid,blocklabel,show_title from ncrm_blocks where tabid=? and $disp_view=0 and visible = 0 order by sequence";
        $result = $adb->pquery($query, array($tabid));
        $noofrows = $adb->num_rows($result);
	$blockid_list ='(';
	for($i=0; $i<$noofrows; $i++)
	{
		$blockid = $adb->query_result($result,$i,"blockid");
		if($i != 0)
			$blockid_list .= ', ';
		$blockid_list .= $blockid;
		$block_label[$blockid] = $adb->query_result($result,$i,"blocklabel");
	}
	$blockid_list .= ')';

	$log->debug("Exit from the function getPermittedBlocks($module, $disp_view). Return value = $blockid_list");
	return $blockid_list;
}

/**	function used to get the query which will list the permitted fields
 *	@param string $module - module name
 *	@param string $disp_view - view name, this may be create_view, edit_view or detail_view
 *	@return string $sql - query to get the list of fields which are permitted to the current user
 */
function getPermittedFieldsQuery($module, $disp_view)
{
	global $adb, $log;
	$log->debug("Entering into the function getPermittedFieldsQuery($module, $disp_view)");

	global $current_user;
	require('user_privileges/user_privileges_'.$current_user->id.'.php');

	//To get the permitted blocks
	$blockid_list = getPermittedBlocks($module, $disp_view);

        $tabid = getTabid($module);
	if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0 || $module == "Users")
	{
 		$sql = "SELECT ncrm_field.columnname, ncrm_field.fieldlabel, ncrm_field.tablename FROM ncrm_field WHERE ncrm_field.tabid=".$tabid." AND ncrm_field.block IN $blockid_list AND ncrm_field.displaytype IN (1,2,4,5) and ncrm_field.presence in (0,2) ORDER BY block,sequence";
  	}
  	else
  	{
		$profileList = getCurrentUserProfileList();
		$sql = "SELECT ncrm_field.columnname, ncrm_field.fieldlabel, ncrm_field.tablename FROM ncrm_field INNER JOIN ncrm_profile2field ON ncrm_profile2field.fieldid=ncrm_field.fieldid INNER JOIN ncrm_def_org_field ON ncrm_def_org_field.fieldid=ncrm_field.fieldid WHERE ncrm_field.tabid=".$tabid." AND ncrm_field.block IN ".$blockid_list." AND ncrm_field.displaytype IN (1,2,4,5) AND ncrm_profile2field.visible=0 AND ncrm_def_org_field.visible=0 AND ncrm_profile2field.profileid IN (". implode(",", $profileList) .") and ncrm_field.presence in (0,2) GROUP BY ncrm_field.fieldid ORDER BY block,sequence";
	}

	$log->debug("Exit from the function getPermittedFieldsQuery($module, $disp_view). Return value = $sql");
	return $sql;
}

/**	function used to get the list of fields from the input query as a comma seperated string
 *	@param string $query - field table query which contains the list of fields
 *	@return string $fields - list of fields as a comma seperated string
 */
function getFieldsListFromQuery($query)
{
	global $adb, $log;
	$log->debug("Entering into the function getFieldsListFromQuery($query)");

	$result = $adb->query($query);
	$num_rows = $adb->num_rows($result);

	for($i=0; $i < $num_rows;$i++)
	{
		$columnName = $adb->query_result($result,$i,"columnname");
		$fieldlabel = $adb->query_result($result,$i,"fieldlabel");
		$tablename = $adb->query_result($result,$i,"tablename");

		//HANDLE HERE - Mismatch fieldname-tablename in field table, in future we have to avoid these if elses
		if($columnName == 'smownerid')//for all assigned to user name
		{
			$fields .= "case when (ncrm_users.user_name not like '') then ncrm_users.user_name else ncrm_groups.groupname end as '".$fieldlabel."',";
		}
		elseif($tablename == 'ncrm_account' && $columnName == 'parentid')//Account - Member Of
		{
			 $fields .= "ncrm_account2.accountname as '".$fieldlabel."',";
		}
		elseif($tablename == 'ncrm_contactdetails' && $columnName == 'accountid')//Contact - Account Name
		{
			$fields .= "ncrm_account.accountname as '".$fieldlabel."',";
		}
		elseif($tablename == 'ncrm_contactdetails' && $columnName == 'reportsto')//Contact - Reports To
		{
			$fields .= " concat(ncrm_contactdetails2.lastname,' ',ncrm_contactdetails2.firstname) as 'Reports To Contact',";
		}
		elseif($tablename == 'ncrm_potential' && $columnName == 'related_to')//Potential - Related to (changed for B2C model support)
		{
			$fields .= "ncrm_potential.related_to as '".$fieldlabel."',";
		}
		elseif($tablename == 'ncrm_potential' && $columnName == 'campaignid')//Potential - Campaign Source
		{
			$fields .= "ncrm_campaign.campaignname as '".$fieldlabel."',";
		}
		elseif($tablename == 'ncrm_seproductsrel' && $columnName == 'crmid')//Product - Related To
		{
			$fields .= "case ncrm_crmentityRelatedTo.setype
					when 'Leads' then concat('Leads :::: ',ncrm_ProductRelatedToLead.lastname,' ',ncrm_ProductRelatedToLead.firstname)
					when 'Accounts' then concat('Accounts :::: ',ncrm_ProductRelatedToAccount.accountname)
					when 'Potentials' then concat('Potentials :::: ',ncrm_ProductRelatedToPotential.potentialname)
				    End as 'Related To',";
		}
		elseif($tablename == 'ncrm_products' && $columnName == 'contactid')//Product - Contact
		{
			$fields .= " concat(ncrm_contactdetails.lastname,' ',ncrm_contactdetails.firstname) as 'Contact Name',";
		}
		elseif($tablename == 'ncrm_products' && $columnName == 'vendor_id')//Product - Vendor Name
		{
			$fields .= "ncrm_vendor.vendorname as '".$fieldlabel."',";
		}
		elseif($tablename == 'ncrm_producttaxrel' && $columnName == 'taxclass')//avoid product - taxclass
		{
			$fields .= "";
		}
		elseif($tablename == 'ncrm_attachments' && $columnName == 'name')//Emails filename
		{
			$fields .= $tablename.".name as '".$fieldlabel."',";
		}
		//By Pavani...Handling mismatch field and table name for trouble tickets
      	elseif($tablename == 'ncrm_troubletickets' && $columnName == 'product_id')//Ticket - Product
        {
			$fields .= "ncrm_products.productname as '".$fieldlabel."',";
        }
        elseif($tablename == 'ncrm_notes' && ($columnName == 'filename' || $columnName == 'filetype' || $columnName == 'filesize' || $columnName == 'filelocationtype' || $columnName == 'filestatus' || $columnName == 'filedownloadcount' ||$columnName == 'folderid')){
			continue;
		}
		elseif(($tablename == 'ncrm_invoice' || $tablename == 'ncrm_quotes' || $tablename == 'ncrm_salesorder')&& $columnName == 'accountid') {
			$fields .= 'concat("Accounts::::",ncrm_account.accountname) as "'.$fieldlabel.'",';
		}
		elseif(($tablename == 'ncrm_invoice' || $tablename == 'ncrm_quotes' || $tablename == 'ncrm_salesorder' || $tablename == 'ncrm_purchaseorder') && $columnName == 'contactid') {
			$fields .= 'concat("Contacts::::",ncrm_contactdetails.lastname," ",ncrm_contactdetails.firstname) as "'.$fieldlabel.'",';
		}
		elseif($tablename == 'ncrm_invoice' && $columnName == 'salesorderid') {
			$fields .= 'concat("SalesOrder::::",ncrm_salesorder.subject) as "'.$fieldlabel.'",';
		}
		elseif(($tablename == 'ncrm_quotes' || $tablename == 'ncrm_salesorder') && $columnName == 'potentialid') {
			$fields .= 'concat("Potentials::::",ncrm_potential.potentialname) as "'.$fieldlabel.'",';
		}
		elseif($tablename == 'ncrm_quotes' && $columnName == 'inventorymanager') {
			$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>'ncrm_inventoryManager.first_name', 'last_name' => 'ncrm_inventoryManager.last_name'), 'Users');
			$fields .= $userNameSql. ' as "'.$fieldlabel.'",';
		}
		elseif($tablename == 'ncrm_salesorder' && $columnName == 'quoteid') {
			$fields .= 'concat("Quotes::::",ncrm_quotes.subject) as "'.$fieldlabel.'",';
		}
		elseif($tablename == 'ncrm_purchaseorder' && $columnName == 'vendorid') {
			$fields .= 'concat("Vendors::::",ncrm_vendor.vendorname) as "'.$fieldlabel.'",';
		}
		else
		{
			$fields .= $tablename.".".$columnName. " as '" .$fieldlabel."',";
		}
	}
	$fields = trim($fields,",");

	$log->debug("Exit from the function getFieldsListFromQuery($query). Return value = $fields");
	return $fields;
}



?>
