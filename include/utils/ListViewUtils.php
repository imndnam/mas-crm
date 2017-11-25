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
 * $Header: /cvsroot/ncrmcrm/ncrm_crm/include/utils/ListViewUtils.php,v 1.32 2006/02/03 06:53:08 mangai Exp $
 * Description:  Includes generic helper functions used throughout the application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('include/database/PearDatabase.php');
require_once('include/ComboUtil.php'); //new
require_once('include/utils/CommonUtils.php'); //new
require_once('user_privileges/default_module_view.php'); //new
require_once('include/utils/UserInfoUtil.php');
require_once('include/Zend/Json.php');

/** Function to get the list query for a module
 * @param $module -- module name:: Type string
 * @param $where -- where:: Type string
 * @returns $query -- query:: Type query
 */
function getListQuery($module, $where = '') {
	global $log;
	$log->debug("Entering getListQuery(" . $module . "," . $where . ") method ...");

	global $current_user;
	require('user_privileges/user_privileges_' . $current_user->id . '.php');
	require('user_privileges/sharing_privileges_' . $current_user->id . '.php');
	$tab_id = getTabid($module);
	$userNameSql = getSqlForNameInDisplayFormat(array('first_name' => 'ncrm_users.first_name', 'last_name' =>
				'ncrm_users.last_name'), 'Users');
	switch ($module) {
		Case "HelpDesk":
			$query = "SELECT ncrm_crmentity.crmid, ncrm_crmentity.smownerid,
			ncrm_troubletickets.title, ncrm_troubletickets.status,
			ncrm_troubletickets.priority, ncrm_troubletickets.parent_id,
			ncrm_contactdetails.contactid, ncrm_contactdetails.firstname,
			ncrm_contactdetails.lastname, ncrm_account.accountid,
			ncrm_account.accountname, ncrm_ticketcf.*, ncrm_troubletickets.ticket_no
			FROM ncrm_troubletickets
			INNER JOIN ncrm_ticketcf
				ON ncrm_ticketcf.ticketid = ncrm_troubletickets.ticketid
			INNER JOIN ncrm_crmentity
				ON ncrm_crmentity.crmid = ncrm_troubletickets.ticketid
			LEFT JOIN ncrm_groups
				ON ncrm_groups.groupid = ncrm_crmentity.smownerid
			LEFT JOIN ncrm_contactdetails
				ON ncrm_troubletickets.parent_id = ncrm_contactdetails.contactid
			LEFT JOIN ncrm_account
				ON ncrm_account.accountid = ncrm_troubletickets.parent_id
			LEFT JOIN ncrm_users
				ON ncrm_crmentity.smownerid = ncrm_users.id
			LEFT JOIN ncrm_products
				ON ncrm_products.productid = ncrm_troubletickets.product_id";
			$query .= ' ' . getNonAdminAccessControlQuery($module, $current_user);
			$query .= "WHERE ncrm_crmentity.deleted = 0 " . $where;
			break;

		Case "Accounts":
			//Query modified to sort by assigned to
			$query = "SELECT ncrm_crmentity.crmid, ncrm_crmentity.smownerid,
			ncrm_account.accountname, ncrm_account.email1,
			ncrm_account.email2, ncrm_account.website, ncrm_account.phone,
			ncrm_accountbillads.bill_city,
			ncrm_accountscf.*
			FROM ncrm_account
			INNER JOIN ncrm_crmentity
				ON ncrm_crmentity.crmid = ncrm_account.accountid
			INNER JOIN ncrm_accountbillads
				ON ncrm_account.accountid = ncrm_accountbillads.accountaddressid
			INNER JOIN ncrm_accountshipads
				ON ncrm_account.accountid = ncrm_accountshipads.accountaddressid
			INNER JOIN ncrm_accountscf
				ON ncrm_account.accountid = ncrm_accountscf.accountid
			LEFT JOIN ncrm_groups
				ON ncrm_groups.groupid = ncrm_crmentity.smownerid
			LEFT JOIN ncrm_users
				ON ncrm_users.id = ncrm_crmentity.smownerid
			LEFT JOIN ncrm_account ncrm_account2
				ON ncrm_account.parentid = ncrm_account2.accountid";
			$query .= getNonAdminAccessControlQuery($module, $current_user);
			$query .= "WHERE ncrm_crmentity.deleted = 0 " . $where;
			break;

		Case "Potentials":
			//Query modified to sort by assigned to
			$query = "SELECT ncrm_crmentity.crmid, ncrm_crmentity.smownerid,
			ncrm_account.accountname,
			ncrm_potential.related_to, ncrm_potential.potentialname,
			ncrm_potential.sales_stage, ncrm_potential.amount,
			ncrm_potential.currency, ncrm_potential.closingdate,
			ncrm_potential.typeofrevenue, ncrm_potential.contact_id,
			ncrm_potentialscf.*
			FROM ncrm_potential
			INNER JOIN ncrm_crmentity
				ON ncrm_crmentity.crmid = ncrm_potential.potentialid
			INNER JOIN ncrm_potentialscf
				ON ncrm_potentialscf.potentialid = ncrm_potential.potentialid
			LEFT JOIN ncrm_account
				ON ncrm_potential.related_to = ncrm_account.accountid
			LEFT JOIN ncrm_contactdetails
				ON ncrm_potential.contact_id = ncrm_contactdetails.contactid
			LEFT JOIN ncrm_campaign
				ON ncrm_campaign.campaignid = ncrm_potential.campaignid
			LEFT JOIN ncrm_groups
				ON ncrm_groups.groupid = ncrm_crmentity.smownerid
			LEFT JOIN ncrm_users
				ON ncrm_users.id = ncrm_crmentity.smownerid";
			$query .= getNonAdminAccessControlQuery($module, $current_user);
			$query .= "WHERE ncrm_crmentity.deleted = 0 " . $where;
			break;

		Case "Leads":
			$query = "SELECT ncrm_crmentity.crmid, ncrm_crmentity.smownerid,
			ncrm_leaddetails.firstname, ncrm_leaddetails.lastname,
			ncrm_leaddetails.company, ncrm_leadaddress.phone,
			ncrm_leadsubdetails.website, ncrm_leaddetails.email,
			ncrm_leadscf.*
			FROM ncrm_leaddetails
			INNER JOIN ncrm_crmentity
				ON ncrm_crmentity.crmid = ncrm_leaddetails.leadid
			INNER JOIN ncrm_leadsubdetails
				ON ncrm_leadsubdetails.leadsubscriptionid = ncrm_leaddetails.leadid
			INNER JOIN ncrm_leadaddress
				ON ncrm_leadaddress.leadaddressid = ncrm_leadsubdetails.leadsubscriptionid
			INNER JOIN ncrm_leadscf
				ON ncrm_leaddetails.leadid = ncrm_leadscf.leadid
			LEFT JOIN ncrm_groups
				ON ncrm_groups.groupid = ncrm_crmentity.smownerid
			LEFT JOIN ncrm_users
				ON ncrm_users.id = ncrm_crmentity.smownerid";
			$query .= getNonAdminAccessControlQuery($module, $current_user);
			$query .= "WHERE ncrm_crmentity.deleted = 0 AND ncrm_leaddetails.converted = 0 " . $where;
			break;
		Case "Products":
			$query = "SELECT ncrm_crmentity.crmid, ncrm_crmentity.smownerid, ncrm_crmentity.description, ncrm_products.*, ncrm_productcf.*
			FROM ncrm_products
			INNER JOIN ncrm_crmentity
				ON ncrm_crmentity.crmid = ncrm_products.productid
			INNER JOIN ncrm_productcf
				ON ncrm_products.productid = ncrm_productcf.productid
			LEFT JOIN ncrm_vendor
				ON ncrm_vendor.vendorid = ncrm_products.vendor_id
			LEFT JOIN ncrm_groups
				ON ncrm_groups.groupid = ncrm_crmentity.smownerid
			LEFT JOIN ncrm_users
				ON ncrm_users.id = ncrm_crmentity.smownerid";
			if ((isset($_REQUEST["from_dashboard"]) && $_REQUEST["from_dashboard"] == true) && (isset($_REQUEST["type"]) && $_REQUEST["type"] == "dbrd"))
				$query .= " INNER JOIN ncrm_inventoryproductrel on ncrm_inventoryproductrel.productid = ncrm_products.productid";

			$query .= getNonAdminAccessControlQuery($module, $current_user);
			$query .= " WHERE ncrm_crmentity.deleted = 0 " . $where;
			break;
		Case "Documents":
			$query = "SELECT case when (ncrm_users.user_name not like '') then $userNameSql else ncrm_groups.groupname end as user_name,ncrm_crmentity.crmid, ncrm_crmentity.modifiedtime,
			ncrm_crmentity.smownerid,ncrm_attachmentsfolder.*,ncrm_notes.*
			FROM ncrm_notes
			INNER JOIN ncrm_crmentity
				ON ncrm_crmentity.crmid = ncrm_notes.notesid
			LEFT JOIN ncrm_groups
				ON ncrm_groups.groupid = ncrm_crmentity.smownerid
			LEFT JOIN ncrm_users
				ON ncrm_users.id = ncrm_crmentity.smownerid
			LEFT JOIN ncrm_attachmentsfolder
				ON ncrm_notes.folderid = ncrm_attachmentsfolder.folderid";
			$query .= getNonAdminAccessControlQuery($module, $current_user);
			$query .= "WHERE ncrm_crmentity.deleted = 0 " . $where;
			break;
		Case "Contacts":
			//Query modified to sort by assigned to
			$query = "SELECT ncrm_contactdetails.firstname, ncrm_contactdetails.lastname,
			ncrm_contactdetails.title, ncrm_contactdetails.accountid,
			ncrm_contactdetails.email, ncrm_contactdetails.phone,
			ncrm_crmentity.smownerid, ncrm_crmentity.crmid
			FROM ncrm_contactdetails
			INNER JOIN ncrm_crmentity
				ON ncrm_crmentity.crmid = ncrm_contactdetails.contactid
			INNER JOIN ncrm_contactaddress
				ON ncrm_contactaddress.contactaddressid = ncrm_contactdetails.contactid
			INNER JOIN ncrm_contactsubdetails
				ON ncrm_contactsubdetails.contactsubscriptionid = ncrm_contactdetails.contactid
			INNER JOIN ncrm_contactscf
				ON ncrm_contactscf.contactid = ncrm_contactdetails.contactid
			LEFT JOIN ncrm_account
				ON ncrm_account.accountid = ncrm_contactdetails.accountid
			LEFT JOIN ncrm_groups
				ON ncrm_groups.groupid = ncrm_crmentity.smownerid
			LEFT JOIN ncrm_users
				ON ncrm_users.id = ncrm_crmentity.smownerid
			LEFT JOIN ncrm_contactdetails ncrm_contactdetails2
				ON ncrm_contactdetails.reportsto = ncrm_contactdetails2.contactid
			LEFT JOIN ncrm_customerdetails
				ON ncrm_customerdetails.customerid = ncrm_contactdetails.contactid";
			if ((isset($_REQUEST["from_dashboard"]) && $_REQUEST["from_dashboard"] == true) &&
					(isset($_REQUEST["type"]) && $_REQUEST["type"] == "dbrd")) {
				$query .= " INNER JOIN ncrm_campaigncontrel on ncrm_campaigncontrel.contactid = " .
						"ncrm_contactdetails.contactid";
			}
			$query .= getNonAdminAccessControlQuery($module, $current_user);
			$query .= "WHERE ncrm_crmentity.deleted = 0 " . $where;
			break;
		Case "Calendar":

			$query = "SELECT ncrm_activity.activityid as act_id,ncrm_crmentity.crmid, ncrm_crmentity.smownerid, ncrm_crmentity.setype,
		ncrm_activity.*,
		ncrm_contactdetails.lastname, ncrm_contactdetails.firstname,
		ncrm_contactdetails.contactid,
		ncrm_account.accountid, ncrm_account.accountname
		FROM ncrm_activity
		LEFT JOIN ncrm_activitycf
			ON ncrm_activitycf.activityid = ncrm_activity.activityid
		LEFT JOIN ncrm_cntactivityrel
			ON ncrm_cntactivityrel.activityid = ncrm_activity.activityid
		LEFT JOIN ncrm_contactdetails
			ON ncrm_contactdetails.contactid = ncrm_cntactivityrel.contactid
		LEFT JOIN ncrm_seactivityrel
			ON ncrm_seactivityrel.activityid = ncrm_activity.activityid
		LEFT OUTER JOIN ncrm_activity_reminder
			ON ncrm_activity_reminder.activity_id = ncrm_activity.activityid
		LEFT JOIN ncrm_crmentity
			ON ncrm_crmentity.crmid = ncrm_activity.activityid
		LEFT JOIN ncrm_users
			ON ncrm_users.id = ncrm_crmentity.smownerid
		LEFT JOIN ncrm_groups
			ON ncrm_groups.groupid = ncrm_crmentity.smownerid
		LEFT JOIN ncrm_users ncrm_users2
			ON ncrm_crmentity.modifiedby = ncrm_users2.id
		LEFT JOIN ncrm_groups ncrm_groups2
			ON ncrm_crmentity.modifiedby = ncrm_groups2.groupid
		LEFT OUTER JOIN ncrm_account
			ON ncrm_account.accountid = ncrm_contactdetails.accountid
		LEFT OUTER JOIN ncrm_leaddetails
	       		ON ncrm_leaddetails.leadid = ncrm_seactivityrel.crmid
		LEFT OUTER JOIN ncrm_account ncrm_account2
	        	ON ncrm_account2.accountid = ncrm_seactivityrel.crmid
		LEFT OUTER JOIN ncrm_potential
	       		ON ncrm_potential.potentialid = ncrm_seactivityrel.crmid
		LEFT OUTER JOIN ncrm_troubletickets
	       		ON ncrm_troubletickets.ticketid = ncrm_seactivityrel.crmid
		LEFT OUTER JOIN ncrm_salesorder
			ON ncrm_salesorder.salesorderid = ncrm_seactivityrel.crmid
		LEFT OUTER JOIN ncrm_purchaseorder
			ON ncrm_purchaseorder.purchaseorderid = ncrm_seactivityrel.crmid
		LEFT OUTER JOIN ncrm_quotes
			ON ncrm_quotes.quoteid = ncrm_seactivityrel.crmid
		LEFT OUTER JOIN ncrm_invoice
	                ON ncrm_invoice.invoiceid = ncrm_seactivityrel.crmid
		LEFT OUTER JOIN ncrm_campaign
		ON ncrm_campaign.campaignid = ncrm_seactivityrel.crmid";

			//added to fix #5135
			if (isset($_REQUEST['from_homepage']) && ($_REQUEST['from_homepage'] ==
					"upcoming_activities" || $_REQUEST['from_homepage'] == "pending_activities")) {
				$query.=" LEFT OUTER JOIN ncrm_recurringevents
			             ON ncrm_recurringevents.activityid=ncrm_activity.activityid";
			}
			//end

			$query .= getNonAdminAccessControlQuery($module, $current_user);
			$query.=" WHERE ncrm_crmentity.deleted = 0 AND activitytype != 'Emails' " . $where;
			break;
		Case "Emails":
			$query = "SELECT DISTINCT ncrm_crmentity.crmid, ncrm_crmentity.smownerid,
			ncrm_activity.activityid, ncrm_activity.subject,
			ncrm_activity.date_start,
			ncrm_contactdetails.lastname, ncrm_contactdetails.firstname,
			ncrm_contactdetails.contactid
			FROM ncrm_activity
			INNER JOIN ncrm_crmentity
				ON ncrm_crmentity.crmid = ncrm_activity.activityid
			LEFT JOIN ncrm_users
				ON ncrm_users.id = ncrm_crmentity.smownerid
			LEFT JOIN ncrm_seactivityrel
				ON ncrm_seactivityrel.activityid = ncrm_activity.activityid
			LEFT JOIN ncrm_contactdetails
				ON ncrm_contactdetails.contactid = ncrm_seactivityrel.crmid
			LEFT JOIN ncrm_cntactivityrel
				ON ncrm_cntactivityrel.activityid = ncrm_activity.activityid
				AND ncrm_cntactivityrel.contactid = ncrm_cntactivityrel.contactid
			LEFT JOIN ncrm_groups
				ON ncrm_groups.groupid = ncrm_crmentity.smownerid
			LEFT JOIN ncrm_salesmanactivityrel
				ON ncrm_salesmanactivityrel.activityid = ncrm_activity.activityid
			LEFT JOIN ncrm_emaildetails
				ON ncrm_emaildetails.emailid = ncrm_activity.activityid";
			$query .= getNonAdminAccessControlQuery($module, $current_user);
			$query .= "WHERE ncrm_activity.activitytype = 'Emails'";
			$query .= "AND ncrm_crmentity.deleted = 0 " . $where;
			break;
		Case "Faq":
			$query = "SELECT ncrm_crmentity.crmid, ncrm_crmentity.createdtime, ncrm_crmentity.modifiedtime,
			ncrm_faq.*
			FROM ncrm_faq
			INNER JOIN ncrm_crmentity
				ON ncrm_crmentity.crmid = ncrm_faq.id
			LEFT JOIN ncrm_products
				ON ncrm_faq.product_id = ncrm_products.productid";
			$query .= getNonAdminAccessControlQuery($module, $current_user);
			$query .= "WHERE ncrm_crmentity.deleted = 0 " . $where;
			break;

		Case "Vendors":
			$query = "SELECT ncrm_crmentity.crmid, ncrm_vendor.*
			FROM ncrm_vendor
			INNER JOIN ncrm_crmentity
				ON ncrm_crmentity.crmid = ncrm_vendor.vendorid
			INNER JOIN ncrm_vendorcf
				ON ncrm_vendor.vendorid = ncrm_vendorcf.vendorid
			WHERE ncrm_crmentity.deleted = 0 " . $where;
			break;
		Case "PriceBooks":
			$query = "SELECT ncrm_crmentity.crmid, ncrm_pricebook.*, ncrm_currency_info.currency_name
			FROM ncrm_pricebook
			INNER JOIN ncrm_crmentity
				ON ncrm_crmentity.crmid = ncrm_pricebook.pricebookid
			INNER JOIN ncrm_pricebookcf
				ON ncrm_pricebook.pricebookid = ncrm_pricebookcf.pricebookid
			LEFT JOIN ncrm_currency_info
				ON ncrm_pricebook.currency_id = ncrm_currency_info.id
			WHERE ncrm_crmentity.deleted = 0 " . $where;
			break;
		Case "Quotes":
			//Query modified to sort by assigned to
			$query = "SELECT ncrm_crmentity.*,
			ncrm_quotes.*,
			ncrm_quotesbillads.*,
			ncrm_quotesshipads.*,
			ncrm_potential.potentialname,
			ncrm_account.accountname,
			ncrm_currency_info.currency_name
			FROM ncrm_quotes
			INNER JOIN ncrm_crmentity
				ON ncrm_crmentity.crmid = ncrm_quotes.quoteid
			INNER JOIN ncrm_quotesbillads
				ON ncrm_quotes.quoteid = ncrm_quotesbillads.quotebilladdressid
			INNER JOIN ncrm_quotesshipads
				ON ncrm_quotes.quoteid = ncrm_quotesshipads.quoteshipaddressid
			LEFT JOIN ncrm_quotescf
				ON ncrm_quotes.quoteid = ncrm_quotescf.quoteid
			LEFT JOIN ncrm_currency_info
				ON ncrm_quotes.currency_id = ncrm_currency_info.id
			LEFT OUTER JOIN ncrm_account
				ON ncrm_account.accountid = ncrm_quotes.accountid
			LEFT OUTER JOIN ncrm_potential
				ON ncrm_potential.potentialid = ncrm_quotes.potentialid
			LEFT JOIN ncrm_contactdetails
				ON ncrm_contactdetails.contactid = ncrm_quotes.contactid
			LEFT JOIN ncrm_groups
				ON ncrm_groups.groupid = ncrm_crmentity.smownerid
			LEFT JOIN ncrm_users
				ON ncrm_users.id = ncrm_crmentity.smownerid
			LEFT JOIN ncrm_users as ncrm_usersQuotes
			        ON ncrm_usersQuotes.id = ncrm_quotes.inventorymanager";
			$query .= getNonAdminAccessControlQuery($module, $current_user);
			$query .= "WHERE ncrm_crmentity.deleted = 0 " . $where;
			break;
		Case "PurchaseOrder":
			//Query modified to sort by assigned to
			$query = "SELECT ncrm_crmentity.*,
			ncrm_purchaseorder.*,
			ncrm_pobillads.*,
			ncrm_poshipads.*,
			ncrm_vendor.vendorname,
			ncrm_currency_info.currency_name
			FROM ncrm_purchaseorder
			INNER JOIN ncrm_crmentity
				ON ncrm_crmentity.crmid = ncrm_purchaseorder.purchaseorderid
			LEFT OUTER JOIN ncrm_vendor
				ON ncrm_purchaseorder.vendorid = ncrm_vendor.vendorid
			LEFT JOIN ncrm_contactdetails
				ON ncrm_purchaseorder.contactid = ncrm_contactdetails.contactid
			INNER JOIN ncrm_pobillads
				ON ncrm_purchaseorder.purchaseorderid = ncrm_pobillads.pobilladdressid
			INNER JOIN ncrm_poshipads
				ON ncrm_purchaseorder.purchaseorderid = ncrm_poshipads.poshipaddressid
			LEFT JOIN ncrm_purchaseordercf
				ON ncrm_purchaseordercf.purchaseorderid = ncrm_purchaseorder.purchaseorderid
			LEFT JOIN ncrm_currency_info
				ON ncrm_purchaseorder.currency_id = ncrm_currency_info.id
			LEFT JOIN ncrm_groups
				ON ncrm_groups.groupid = ncrm_crmentity.smownerid
			LEFT JOIN ncrm_users
				ON ncrm_users.id = ncrm_crmentity.smownerid";
			$query .= getNonAdminAccessControlQuery($module, $current_user);
			$query .= "WHERE ncrm_crmentity.deleted = 0 " . $where;
			break;
		Case "SalesOrder":
			//Query modified to sort by assigned to
			$query = "SELECT ncrm_crmentity.*,
			ncrm_salesorder.*,
			ncrm_sobillads.*,
			ncrm_soshipads.*,
			ncrm_quotes.subject AS quotename,
			ncrm_account.accountname,
			ncrm_currency_info.currency_name
			FROM ncrm_salesorder
			INNER JOIN ncrm_crmentity
				ON ncrm_crmentity.crmid = ncrm_salesorder.salesorderid
			INNER JOIN ncrm_sobillads
				ON ncrm_salesorder.salesorderid = ncrm_sobillads.sobilladdressid
			INNER JOIN ncrm_soshipads
				ON ncrm_salesorder.salesorderid = ncrm_soshipads.soshipaddressid
			LEFT JOIN ncrm_salesordercf
				ON ncrm_salesordercf.salesorderid = ncrm_salesorder.salesorderid
			LEFT JOIN ncrm_currency_info
				ON ncrm_salesorder.currency_id = ncrm_currency_info.id
			LEFT OUTER JOIN ncrm_quotes
				ON ncrm_quotes.quoteid = ncrm_salesorder.quoteid
			LEFT OUTER JOIN ncrm_account
				ON ncrm_account.accountid = ncrm_salesorder.accountid
			LEFT JOIN ncrm_contactdetails
				ON ncrm_salesorder.contactid = ncrm_contactdetails.contactid
			LEFT JOIN ncrm_potential
				ON ncrm_potential.potentialid = ncrm_salesorder.potentialid
			LEFT JOIN ncrm_invoice_recurring_info
				ON ncrm_invoice_recurring_info.salesorderid = ncrm_salesorder.salesorderid
			LEFT JOIN ncrm_groups
				ON ncrm_groups.groupid = ncrm_crmentity.smownerid
			LEFT JOIN ncrm_users
				ON ncrm_users.id = ncrm_crmentity.smownerid";
			$query .= getNonAdminAccessControlQuery($module, $current_user);
			$query .= "WHERE ncrm_crmentity.deleted = 0 " . $where;
			break;
		Case "Invoice":
			//Query modified to sort by assigned to
			//query modified -Code contribute by Geoff(http://forums.ncrm.com/viewtopic.php?t=3376)
			$query = "SELECT ncrm_crmentity.*,
			ncrm_invoice.*,
			ncrm_invoicebillads.*,
			ncrm_invoiceshipads.*,
			ncrm_salesorder.subject AS salessubject,
			ncrm_account.accountname,
			ncrm_currency_info.currency_name
			FROM ncrm_invoice
			INNER JOIN ncrm_crmentity
				ON ncrm_crmentity.crmid = ncrm_invoice.invoiceid
			INNER JOIN ncrm_invoicebillads
				ON ncrm_invoice.invoiceid = ncrm_invoicebillads.invoicebilladdressid
			INNER JOIN ncrm_invoiceshipads
				ON ncrm_invoice.invoiceid = ncrm_invoiceshipads.invoiceshipaddressid
			LEFT JOIN ncrm_currency_info
				ON ncrm_invoice.currency_id = ncrm_currency_info.id
			LEFT OUTER JOIN ncrm_salesorder
				ON ncrm_salesorder.salesorderid = ncrm_invoice.salesorderid
			LEFT OUTER JOIN ncrm_account
			        ON ncrm_account.accountid = ncrm_invoice.accountid
			LEFT JOIN ncrm_contactdetails
				ON ncrm_contactdetails.contactid = ncrm_invoice.contactid
			INNER JOIN ncrm_invoicecf
				ON ncrm_invoice.invoiceid = ncrm_invoicecf.invoiceid
			LEFT JOIN ncrm_groups
				ON ncrm_groups.groupid = ncrm_crmentity.smownerid
			LEFT JOIN ncrm_users
				ON ncrm_users.id = ncrm_crmentity.smownerid";
			$query .= getNonAdminAccessControlQuery($module, $current_user);
			$query .= "WHERE ncrm_crmentity.deleted = 0 " . $where;
			break;
		Case "Campaigns":
			//Query modified to sort by assigned to
			//query modified -Code contribute by Geoff(http://forums.ncrm.com/viewtopic.php?t=3376)
			$query = "SELECT ncrm_crmentity.*,
			ncrm_campaign.*
			FROM ncrm_campaign
			INNER JOIN ncrm_crmentity
				ON ncrm_crmentity.crmid = ncrm_campaign.campaignid
			INNER JOIN ncrm_campaignscf
			        ON ncrm_campaign.campaignid = ncrm_campaignscf.campaignid
			LEFT JOIN ncrm_groups
				ON ncrm_groups.groupid = ncrm_crmentity.smownerid
			LEFT JOIN ncrm_users
				ON ncrm_users.id = ncrm_crmentity.smownerid
			LEFT JOIN ncrm_products
				ON ncrm_products.productid = ncrm_campaign.product_id";
			$query .= getNonAdminAccessControlQuery($module, $current_user);
			$query .= "WHERE ncrm_crmentity.deleted = 0 " . $where;
			break;
		Case "Users":
			$query = "SELECT id,user_name,first_name,last_name,email1,phone_mobile,phone_work,is_admin,status,email2,
					ncrm_user2role.roleid as roleid,ncrm_role.depth as depth
				 	FROM ncrm_users
				 	INNER JOIN ncrm_user2role ON ncrm_users.id = ncrm_user2role.userid
				 	INNER JOIN ncrm_role ON ncrm_user2role.roleid = ncrm_role.roleid
					WHERE deleted=0 AND status <> 'Inactive'" . $where;
			break;
		default:
			// vtlib customization: Include the module file
			$focus = CRMEntity::getInstance($module);
			$query = $focus->getListQuery($module, $where);
		// END
	}

	if ($module != 'Users') {
		$query = listQueryNonAdminChange($query, $module);
	}
	$log->debug("Exiting getListQuery method ...");
	return $query;
}

/* * This function stores the variables in session sent in list view url string.
 * Param $lv_array - list view session array
 * Param $noofrows - no of rows
 * Param $max_ent - maximum entires
 * Param $module - module name
 * Param $related - related module
 * Return type void.
 */

function setSessionVar($lv_array, $noofrows, $max_ent, $module = '', $related = '') {
	$start = '';
	if ($noofrows >= 1) {
		$lv_array['start'] = 1;
		$start = 1;
	} elseif ($related != '' && $noofrows == 0) {
		$lv_array['start'] = 1;
		$start = 1;
	} else {
		$lv_array['start'] = 0;
		$start = 0;
	}

	if (isset($_REQUEST['start']) && $_REQUEST['start'] != '') {
		$lv_array['start'] = ListViewSession::getRequestStartPage();
		$start = ListViewSession::getRequestStartPage();
	} elseif ($_SESSION['rlvs'][$module][$related]['start'] != '') {

		if ($related != '') {
			$lv_array['start'] = $_SESSION['rlvs'][$module][$related]['start'];
			$start = $_SESSION['rlvs'][$module][$related]['start'];
		}
	}
	if (isset($_REQUEST['viewname']) && $_REQUEST['viewname'] != '')
		$lv_array['viewname'] = vtlib_purify($_REQUEST['viewname']);

	if ($related == '')
		$_SESSION['lvs'][$_REQUEST['module']] = $lv_array;
	else
		$_SESSION['rlvs'][$module][$related] = $lv_array;

	if ($start < ceil($noofrows / $max_ent) && $start != '') {
		$start = ceil($noofrows / $max_ent);
		if ($related == '')
			$_SESSION['lvs'][$currentModule]['start'] = $start;
	}
}

/* * Function to get the table headers for related listview
 * Param $navigation_arrray - navigation values in array
 * Param $url_qry - url string
 * Param $module - module name
 * Param $action- action file name
 * Param $viewid - view id
 * Returns an string value
 */

function getRelatedTableHeaderNavigation($navigation_array, $url_qry, $module, $related_module, $recordid) {
	global $log, $app_strings, $adb;
	$log->debug("Entering getTableHeaderNavigation(" . $navigation_array . "," . $url_qry . "," . $module . "," . $action_val . "," . $viewid . ") method ...");
	global $theme;
	$relatedTabId = getTabid($related_module);
	$tabid = getTabid($module);

	$relatedListResult = $adb->pquery('SELECT * FROM ncrm_relatedlists WHERE tabid=? AND
		related_tabid=?', array($tabid, $relatedTabId));
	if (empty($relatedListResult))
		return;
	$relatedListRow = $adb->fetch_row($relatedListResult);
	$header = $relatedListRow['label'];
	$actions = $relatedListRow['actions'];
	$functionName = $relatedListRow['name'];

	$urldata = "module=$module&action={$module}Ajax&file=DetailViewAjax&record={$recordid}&" .
			"ajxaction=LOADRELATEDLIST&header={$header}&relation_id={$relatedListRow['relation_id']}" .
			"&actions={$actions}&{$url_qry}";

	$formattedHeader = str_replace(' ', '', $header);
	$target = 'tbl_' . $module . '_' . $formattedHeader;
	$imagesuffix = $module . '_' . $formattedHeader;

	$output = '<td align="right" style="padding="5px;">';
	if (($navigation_array['prev']) != 0) {
		$output .= '<a href="javascript:;" onClick="loadRelatedListBlock(\'' . $urldata . '&start=1\',\'' . $target . '\',\'' . $imagesuffix . '\');" alt="' . $app_strings['LBL_FIRST'] . '" title="' . $app_strings['LBL_FIRST'] . '"><img src="' . ncrm_imageurl('start.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
		$output .= '<a href="javascript:;" onClick="loadRelatedListBlock(\'' . $urldata . '&start=' . $navigation_array['prev'] . '\',\'' . $target . '\',\'' . $imagesuffix . '\');" alt="' . $app_strings['LNK_LIST_PREVIOUS'] . '"title="' . $app_strings['LNK_LIST_PREVIOUS'] . '"><img src="' . ncrm_imageurl('previous.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
	} else {
		$output .= '<img src="' . ncrm_imageurl('start_disabled.gif', $theme) . '" border="0" align="absmiddle">&nbsp;';
		$output .= '<img src="' . ncrm_imageurl('previous_disabled.gif', $theme) . '" border="0" align="absmiddle">&nbsp;';
	}

	$jsHandler = "return VT_disableFormSubmit(event);";
	$output .= "<input class='small' name='pagenum' type='text' value='{$navigation_array['current']}'
		style='width: 3em;margin-right: 0.7em;' onchange=\"loadRelatedListBlock('{$urldata}&start='+this.value+'','{$target}','{$imagesuffix}');\"
		onkeypress=\"$jsHandler\">";
	$output .= "<span name='listViewCountContainerName' class='small' style='white-space: nowrap;'>";
	$computeCount = $_REQUEST['withCount'];
	if (PerformancePrefs::getBoolean('LISTVIEW_COMPUTE_PAGE_COUNT', false) === true
			|| ((boolean) $computeCount) == true) {
		$output .= $app_strings['LBL_LIST_OF'] . ' ' . $navigation_array['verylast'];
	} else {
		$output .= "<img src='" . ncrm_imageurl('windowRefresh.gif', $theme) . "' alt='" . $app_strings['LBL_HOME_COUNT'] . "'
			onclick=\"loadRelatedListBlock('{$urldata}&withCount=true&start={$navigation_array['current']}','{$target}','{$imagesuffix}');\"
			align='absmiddle' name='" . $module . "_listViewCountRefreshIcon'/>
			<img name='" . $module . "_listViewCountContainerBusy' src='" . ncrm_imageurl('vtbusy.gif', $theme) . "' style='display: none;'
			align='absmiddle' alt='" . $app_strings['LBL_LOADING'] . "'>";
	}
	$output .= '</span>';

	if (($navigation_array['next']) != 0) {
		$output .= '<a href="javascript:;" onClick="loadRelatedListBlock(\'' . $urldata . '&start=' . $navigation_array['next'] . '\',\'' . $target . '\',\'' . $imagesuffix . '\');"><img src="' . ncrm_imageurl('next.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
		$output .= '<a href="javascript:;" onClick="loadRelatedListBlock(\'' . $urldata . '&start=' . $navigation_array['verylast'] . '\',\'' . $target . '\',\'' . $imagesuffix . '\');"><img src="' . ncrm_imageurl('end.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
	} else {
		$output .= '<img src="' . ncrm_imageurl('next_disabled.gif', $theme) . '" border="0" align="absmiddle">&nbsp;';
		$output .= '<img src="' . ncrm_imageurl('end_disabled.gif', $theme) . '" border="0" align="absmiddle">&nbsp;';
	}
	$output .= '</td>';
	$log->debug("Exiting getTableHeaderNavigation method ...");
	if ($navigation_array['first'] == '')
		return;
	else
		return $output;
}

/* Function to get the Entity Id of a given Entity Name */

function getEntityId($module, $entityName) {
	global $log, $adb;
	$log->info("in getEntityId " . $entityName);

	$query = "select fieldname,tablename,entityidfield from ncrm_entityname where modulename = ?";
	$result = $adb->pquery($query, array($module));
	$fieldsname = $adb->query_result($result, 0, 'fieldname');
	$tablename = $adb->query_result($result, 0, 'tablename');
	$entityidfield = $adb->query_result($result, 0, 'entityidfield');
	if (!(strpos($fieldsname, ',') === false)) {
		$fieldlists = explode(',', $fieldsname);
		$fieldsname = "trim(concat(";
		$fieldsname = $fieldsname . implode(",' ',", $fieldlists);
		$fieldsname = $fieldsname . "))";
		$entityName = trim($entityName);
	}

	if ($entityName != '') {
		$sql = "select $entityidfield from $tablename INNER JOIN ncrm_crmentity ON ncrm_crmentity.crmid = $tablename.$entityidfield " .
				" WHERE ncrm_crmentity.deleted = 0 and $fieldsname=?";
		$result = $adb->pquery($sql, array($entityName));
		if ($adb->num_rows($result) > 0) {
			$entityId = $adb->query_result($result, 0, $entityidfield);
		}
	}
	if (!empty($entityId))
		return $entityId;
	else
		return 0;
}

function decode_html($str) {
	global $default_charset;$default_charset='UTF-8'; 
	// Direct Popup action or Ajax Popup action should be treated the same.
	if ((isset($_REQUEST['action']) && $_REQUEST['action'] == 'Popup') || (isset($_REQUEST['file']) && $_REQUEST['file'] == 'Popup'))
		return html_entity_decode($str);
	else
		return html_entity_decode($str, ENT_QUOTES, $default_charset);
}

function popup_decode_html($str) {
	global $default_charset;
	$slashes_str = popup_from_html($str);
	$slashes_str = htmlspecialchars($slashes_str, ENT_QUOTES, $default_charset);
	return decode_html(br2nl($slashes_str));
}

//function added to check the text length in the listview.
function textlength_check($field_val) {
	global $listview_max_textlength, $default_charset;
	if ($listview_max_textlength && $listview_max_textlength > 0) {
		$temp_val = preg_replace("/(<\/?)(\w+)([^>]*>)/i", "", $field_val);
		if (function_exists('mb_strlen')) {
			if (mb_strlen(html_entity_decode($temp_val)) > $listview_max_textlength) {
				$temp_val = mb_substr(preg_replace("/(<\/?)(\w+)([^>]*>)/i", "", $field_val), 0, $listview_max_textlength, $default_charset) . '...';
			}
		} elseif (strlen(html_entity_decode($field_val)) > $listview_max_textlength) {
			$temp_val = substr(preg_replace("/(<\/?)(\w+)([^>]*>)/i", "", $field_val), 0, $listview_max_textlength) . '...';
		}
	} else {
		$temp_val = $field_val;
	}
	return $temp_val;
}

/**
 * this function accepts a modulename and a fieldname and returns the first related module for it
 * it expects the uitype of the field to be 10
 * @param string $module - the modulename
 * @param string $fieldname - the field name
 * @return string $data - the first related module
 */
function getFirstModule($module, $fieldname) {
	global $adb;
	$sql = "select fieldid, uitype from ncrm_field where tabid=? and fieldname=?";
	$result = $adb->pquery($sql, array(getTabid($module), $fieldname));

	if ($adb->num_rows($result) > 0) {
		$uitype = $adb->query_result($result, 0, "uitype");

		if ($uitype == 10) {
			$fieldid = $adb->query_result($result, 0, "fieldid");
			$sql = "select * from ncrm_fieldmodulerel where fieldid=?";
			$result = $adb->pquery($sql, array($fieldid));
			$count = $adb->num_rows($result);

			if ($count > 0) {
				$data = $adb->query_result($result, 0, "relmodule");
			}
		}
	}
	return $data;
}

function VT_getSimpleNavigationValues($start, $size, $total) {
	$prev = $start - 1;
	if ($prev < 0) {
		$prev = 0;
	}
	if ($total === null) {
		return array('start' => $start, 'first' => $start, 'current' => $start, 'end' => $start, 'end_val' => $size, 'allflag' => 'All',
			'prev' => $prev, 'next' => $start + 1, 'verylast' => 'last');
	}
	if (empty($total)) {
		$lastPage = 1;
	} else {
		$lastPage = ceil($total / $size);
	}

	$next = $start + 1;
	if ($next > $lastPage) {
		$next = 0;
	}
	return array('start' => $start, 'first' => $start, 'current' => $start, 'end' => $start, 'end_val' => $size, 'allflag' => 'All',
		'prev' => $prev, 'next' => $next, 'verylast' => $lastPage);
}

function getRecordRangeMessage($listResult, $limitStartRecord, $totalRows = '') {
	global $adb, $app_strings;
	$numRows = $adb->num_rows($listResult);
	$recordListRangeMsg = '';
	if ($numRows > 0) {
		$recordListRangeMsg = $app_strings['LBL_SHOWING'] . ' ' . $app_strings['LBL_RECORDS'] .
				' ' . ($limitStartRecord + 1) . ' - ' . ($limitStartRecord + $numRows);
		if (PerformancePrefs::getBoolean('LISTVIEW_COMPUTE_PAGE_COUNT', false) === true) {
			$recordListRangeMsg .= ' ' . $app_strings['LBL_LIST_OF'] . " $totalRows";
		}
	}
	return $recordListRangeMsg;
}

function listQueryNonAdminChange($query, $module, $scope = '') {
	$instance = CRMEntity::getInstance($module);
	return $instance->listQueryNonAdminChange($query, $scope);
}

function html_strlen($str) {
	$chars = preg_split('/(&[^;\s]+;)|/', $str, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
	return count($chars);
}

function html_substr($str, $start, $length = NULL) {
	if ($length === 0)
		return "";
	//check if we can simply use the built-in functions
	if (strpos($str, '&') === false) { //No entities. Use built-in functions
		if ($length === NULL)
			return substr($str, $start);
		else
			return substr($str, $start, $length);
	}

	// create our array of characters and html entities
	$chars = preg_split('/(&[^;\s]+;)|/', $str, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_OFFSET_CAPTURE);
	$html_length = count($chars);
	// check if we can predict the return value and save some processing time
	if (($html_length === 0) or ($start >= $html_length) or (isset($length) and ($length <= -$html_length)))
		return "";

	//calculate start position
	if ($start >= 0) {
		$real_start = $chars[$start][1];
	} else { //start'th character from the end of string
		$start = max($start, -$html_length);
		$real_start = $chars[$html_length + $start][1];
	}
	if (!isset($length)) // no $length argument passed, return all remaining characters
		return substr($str, $real_start);
	else if ($length > 0) { // copy $length chars
		if ($start + $length >= $html_length) { // return all remaining characters
			return substr($str, $real_start);
		} else { //return $length characters
			return substr($str, $real_start, $chars[max($start, 0) + $length][1] - $real_start);
		}
	} else { //negative $length. Omit $length characters from end
		return substr($str, $real_start, $chars[$html_length + $length][1] - $real_start);
	}
}

function counterValue() {
	static $counter = 0;
	$counter = $counter + 1;
	return $counter;
}

function getUsersPasswordInfo(){
	global $adb;
	$sql = "SELECT user_name, user_hash FROM ncrm_users WHERE deleted=?";
	$result = $adb->pquery($sql, array(0));
	$usersList = array();
	for ($i=0; $i<$adb->num_rows($result); $i++) {
		$userList['name'] = $adb->query_result($result, $i, "user_name");
		$userList['hash'] = $adb->query_result($result, $i, "user_hash");
		$usersList[] = $userList;
	}
	return $usersList;
}

?>
