<?php
/*+********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ********************************************************************************/
require_once('include/database/PearDatabase.php');
//require_once('modules/Reports/CannedReports.php');
global $adb;

$rptfolder = Array(Array('Account and Contact Reports'=>'Account and Contact Reports'),
		   Array('Lead Reports'=>'Lead Reports'),
	           Array('Potential Reports'=>'Potential Reports'),
		   Array('Activity Reports'=>'Activity Reports'),
		   Array('HelpDesk Reports'=>'HelpDesk Reports'),
		   Array('Product Reports'=>'Product Reports'),
		   Array('Quote Reports' =>'Quote Reports'),
		   Array('PurchaseOrder Reports'=>'PurchaseOrder Reports'),
		   Array('Invoice Reports'=>'Invoice Reports'),
		   Array('SalesOrder Reports'=>'SalesOrder Reports'),
		   Array('Campaign Reports'=>'Campaign Reports')
                  );

$reportmodules = Array(Array('primarymodule'=>'Contacts','secondarymodule'=>'Accounts'),
		       Array('primarymodule'=>'Contacts','secondarymodule'=>'Accounts'),
		       Array('primarymodule'=>'Contacts','secondarymodule'=>'Potentials'),
		       Array('primarymodule'=>'Leads','secondarymodule'=>''),
		       Array('primarymodule'=>'Leads','secondarymodule'=>''),
		       Array('primarymodule'=>'Potentials','secondarymodule'=>''),
		       Array('primarymodule'=>'Potentials','secondarymodule'=>''),
		       Array('primarymodule'=>'Calendar','secondarymodule'=>''),
		       Array('primarymodule'=>'Calendar','secondarymodule'=>''),
		       Array('primarymodule'=>'HelpDesk','secondarymodule'=>'Products'),
		       Array('primarymodule'=>'HelpDesk','secondarymodule'=>''),
  		       Array('primarymodule'=>'HelpDesk','secondarymodule'=>''),
		       Array('primarymodule'=>'Products','secondarymodule'=>''),
		       Array('primarymodule'=>'Products','secondarymodule'=>'Contacts'),
		       Array('primarymodule'=>'Quotes','secondarymodule'=>''),
		       Array('primarymodule'=>'Quotes','secondarymodule'=>''),
		       Array('primarymodule'=>'PurchaseOrder','secondarymodule'=>'Contacts'),
		       Array('primarymodule'=>'PurchaseOrder','secondarymodule'=>''),
		       Array('primarymodule'=>'Invoice','secondarymodule'=>''),
		       Array('primarymodule'=>'SalesOrder','secondarymodule'=>''),
		       Array('primarymodule'=>'Campaigns','secondarymodule'=>'')
		      );

$selectcolumns = Array(Array('ncrm_contactdetails:firstname:Contacts_First_Name:firstname:V',
                             'ncrm_contactdetails:lastname:Contacts_Last_Name:lastname:V',
                             'ncrm_contactsubdetails:leadsource:Contacts_Lead_Source:leadsource:V',
                             'ncrm_contactdetails:accountid:Contacts_Account_Name:account_id:V',
			     'ncrm_account:industry:Accounts_industry:industry:V',
			     'ncrm_contactdetails:email:Contacts_Email:email:E'),

		       Array('ncrm_contactdetails:firstname:Contacts_First_Name:firstname:V',
                             'ncrm_contactdetails:lastname:Contacts_Last_Name:lastname:V',
                             'ncrm_contactsubdetails:leadsource:Contacts_Lead_Source:leadsource:V',
                             'ncrm_contactdetails:accountid:Contacts_Account_Name:account_id:V',
                             'ncrm_account:industry:Accounts_industry:industry:V',
                             'ncrm_contactdetails:email:Contacts_Email:email:E'),

		       Array('ncrm_contactdetails:firstname:Contacts_First_Name:firstname:V',
                             'ncrm_contactdetails:lastname:Contacts_Last_Name:lastname:V',
                             'ncrm_contactdetails:accountid:Contacts_Account_Name:account_id:V',
                             'ncrm_contactdetails:email:Contacts_Email:email:E',
                             'ncrm_potential:potentialname:Potentials_Potential_Name:potentialname:V',
                             'ncrm_potential:sales_stage:Potentials_Sales_Stage:sales_stage:V'),

		       Array('ncrm_leaddetails:firstname:Leads_First_Name:firstname:V',
			     'ncrm_leaddetails:lastname:Leads_Last_Name:lastname:V',
			     'ncrm_leaddetails:company:Leads_Company:company:V',
			     'ncrm_leaddetails:email:Leads_Email:email:E',
			     'ncrm_leaddetails:leadsource:Leads_Lead_Source:leadsource:V'),

		       Array('ncrm_leaddetails:firstname:Leads_First_Name:firstname:V',
                             'ncrm_leaddetails:lastname:Leads_Last_Name:lastname:V',
                             'ncrm_leaddetails:company:Leads_Company:company:V',
                             'ncrm_leaddetails:email:Leads_Email:email:E',
			     'ncrm_leaddetails:leadsource:Leads_Lead_Source:leadsource:V',
			     'ncrm_leaddetails:leadstatus:Leads_Lead_Status:leadstatus:V'),

		       Array('ncrm_potential:potentialname:Potentials_Potential_Name:potentialname:V',
                             'ncrm_potential:amount:Potentials_Amount:amount:N',
                             'ncrm_potential:potentialtype:Potentials_Type:opportunity_type:V',
                             'ncrm_potential:leadsource:Potentials_Lead_Source:leadsource:V',
                             'ncrm_potential:sales_stage:Potentials_Sales_Stage:sales_stage:V'),

		       Array('ncrm_potential:potentialname:Potentials_Potential_Name:potentialname:V',
                             'ncrm_potential:amount:Potentials_Amount:amount:N',
                             'ncrm_potential:potentialtype:Potentials_Type:opportunity_type:V',
                             'ncrm_potential:leadsource:Potentials_Lead_Source:leadsource:V',
			     'ncrm_potential:sales_stage:Potentials_Sales_Stage:sales_stage:V'),

		       Array('ncrm_activity:subject:Calendar_Subject:subject:V',
			     'ncrm_contactdetailsCalendar:lastname:Calendar_Contact_Name:contact_id:I',
                             'ncrm_activity:status:Calendar_Status:taskstatus:V',
                             'ncrm_activity:priority:Calendar_Priority:taskpriority:V',
                             'ncrm_usersCalendar:user_name:Calendar_Assigned_To:assigned_user_id:V'),

		       Array('ncrm_activity:subject:Calendar_Subject:subject:V',
                             'ncrm_contactdetailsCalendar:lastname:Calendar_Contact_Name:contact_id:I',
                             'ncrm_activity:status:Calendar_Status:taskstatus:V',
                             'ncrm_activity:priority:Calendar_Priority:taskpriority:V',
                             'ncrm_usersCalendar:user_name:Calendar_Assigned_To:assigned_user_id:V'),

        	       Array('ncrm_troubletickets:title:HelpDesk_Title:ticket_title:V',
                             'ncrm_troubletickets:status:HelpDesk_Status:ticketstatus:V',
                             'ncrm_products:productname:Products_Product_Name:productname:V',
                             'ncrm_products:discontinued:Products_Product_Active:discontinued:V',
                             'ncrm_products:productcategory:Products_Product_Category:productcategory:V',
			     'ncrm_products:manufacturer:Products_Manufacturer:manufacturer:V'),

 		       Array('ncrm_troubletickets:title:HelpDesk_Title:ticket_title:V',
                             'ncrm_troubletickets:priority:HelpDesk_Priority:ticketpriorities:V',
                             'ncrm_troubletickets:severity:HelpDesk_Severity:ticketseverities:V',
                             'ncrm_troubletickets:status:HelpDesk_Status:ticketstatus:V',
                             'ncrm_troubletickets:category:HelpDesk_Category:ticketcategories:V',
                             'ncrm_usersHelpDesk:user_name:HelpDesk_Assigned_To:assigned_user_id:V'),

		       Array('ncrm_troubletickets:title:HelpDesk_Title:ticket_title:V',
                             'ncrm_troubletickets:priority:HelpDesk_Priority:ticketpriorities:V',
                             'ncrm_troubletickets:severity:HelpDesk_Severity:ticketseverities:V',
                             'ncrm_troubletickets:status:HelpDesk_Status:ticketstatus:V',
                             'ncrm_troubletickets:category:HelpDesk_Category:ticketcategories:V',
                             'ncrm_usersHelpDesk:user_name:HelpDesk_Assigned_To:assigned_user_id:V'),

 		       Array('ncrm_products:productname:Products_Product_Name:productname:V',
                             'ncrm_products:productcode:Products_Product_Code:productcode:V',
                             'ncrm_products:discontinued:Products_Product_Active:discontinued:V',
                             'ncrm_products:productcategory:Products_Product_Category:productcategory:V',
                             'ncrm_products:website:Products_Website:website:V',
			     'ncrm_vendorRelProducts:vendorname:Products_Vendor_Name:vendor_id:I',
			     'ncrm_products:mfr_part_no:Products_Mfr_PartNo:mfr_part_no:V'),

		       Array('ncrm_products:productname:Products_Product_Name:productname:V',
                             'ncrm_products:manufacturer:Products_Manufacturer:manufacturer:V',
                             'ncrm_products:productcategory:Products_Product_Category:productcategory:V',
                             'ncrm_contactdetails:firstname:Contacts_First_Name:firstname:V',
                             'ncrm_contactdetails:lastname:Contacts_Last_Name:lastname:V',
                             'ncrm_contactsubdetails:leadsource:Contacts_Lead_Source:leadsource:V'),

		       Array('ncrm_quotes:subject:Quotes_Subject:subject:V',
                             'ncrm_potentialRelQuotes:potentialname:Quotes_Potential_Name:potential_id:I',
                             'ncrm_quotes:quotestage:Quotes_Quote_Stage:quotestage:V',
                             'ncrm_quotes:contactid:Quotes_Contact_Name:contact_id:V',
                             'ncrm_usersRel1:user_name:Quotes_Inventory_Manager:assigned_user_id1:I',
                             'ncrm_accountQuotes:accountname:Quotes_Account_Name:account_id:I'),

		       Array('ncrm_quotes:subject:Quotes_Subject:subject:V',
                             'ncrm_potentialRelQuotes:potentialname:Quotes_Potential_Name:potential_id:I',
                             'ncrm_quotes:quotestage:Quotes_Quote_Stage:quotestage:V',
                             'ncrm_quotes:contactid:Quotes_Contact_Name:contact_id:V',
                             'ncrm_usersRel1:user_name:Quotes_Inventory_Manager:assigned_user_id1:I',
                             'ncrm_accountQuotes:accountname:Quotes_Account_Name:account_id:I',
			     'ncrm_quotes:carrier:Quotes_Carrier:carrier:V',
			     'ncrm_quotes:shipping:Quotes_Shipping:shipping:V'),

		       Array('ncrm_purchaseorder:subject:PurchaseOrder_Subject:subject:V',
			     'ncrm_vendorRelPurchaseOrder:vendorname:PurchaseOrder_Vendor_Name:vendor_id:I',
			     'ncrm_purchaseorder:tracking_no:PurchaseOrder_Tracking_Number:tracking_no:V',
			     'ncrm_contactdetails:firstname:Contacts_First_Name:firstname:V',
			     'ncrm_contactdetails:lastname:Contacts_Last_Name:lastname:V',
			     'ncrm_contactsubdetails:leadsource:Contacts_Lead_Source:leadsource:V',
			     'ncrm_contactdetails:email:Contacts_Email:email:E'),

		       Array('ncrm_purchaseorder:subject:PurchaseOrder_Subject:subject:V',
			     'ncrm_vendorRelPurchaseOrder:vendorname:PurchaseOrder_Vendor_Name:vendor_id:I',
			     'ncrm_purchaseorder:requisition_no:PurchaseOrder_Requisition_No:requisition_no:V',
                             'ncrm_purchaseorder:tracking_no:PurchaseOrder_Tracking_Number:tracking_no:V',
			     'ncrm_contactdetailsPurchaseOrder:lastname:PurchaseOrder_Contact_Name:contact_id:I',
			     'ncrm_purchaseorder:carrier:PurchaseOrder_Carrier:carrier:V',
			     'ncrm_purchaseorder:salescommission:PurchaseOrder_Sales_Commission:salescommission:N',
			     'ncrm_purchaseorder:exciseduty:PurchaseOrder_Excise_Duty:exciseduty:N',
                             'ncrm_usersPurchaseOrder:user_name:PurchaseOrder_Assigned_To:assigned_user_id:V'),

		       Array('ncrm_invoice:subject:Invoice_Subject:subject:V',
			     'ncrm_invoice:salesorderid:Invoice_Sales_Order:salesorder_id:I',
			     'ncrm_invoice:customerno:Invoice_Customer_No:customerno:V',
			     'ncrm_invoice:exciseduty:Invoice_Excise_Duty:exciseduty:N',
			     'ncrm_invoice:salescommission:Invoice_Sales_Commission:salescommission:N',
			     'ncrm_accountInvoice:accountname:Invoice_Account_Name:account_id:I'),

		       Array('ncrm_salesorder:subject:SalesOrder_Subject:subject:V',
			     'ncrm_quotesSalesOrder:subject:SalesOrder_Quote_Name:quote_id:I',
			     'ncrm_contactdetailsSalesOrder:lastname:SalesOrder_Contact_Name:contact_id:I',
			     'ncrm_salesorder:duedate:SalesOrder_Due_Date:duedate:D',
			     'ncrm_salesorder:carrier:SalesOrder_Carrier:carrier:V',
			     'ncrm_salesorder:sostatus:SalesOrder_Status:sostatus:V',
			     'ncrm_accountSalesOrder:accountname:SalesOrder_Account_Name:account_id:I',
			     'ncrm_salesorder:salescommission:SalesOrder_Sales_Commission:salescommission:N',
			     'ncrm_salesorder:exciseduty:SalesOrder_Excise_Duty:exciseduty:N',
			     'ncrm_usersSalesOrder:user_name:SalesOrder_Assigned_To:assigned_user_id:V'),

		       Array('ncrm_campaign:campaignname:Campaigns_Campaign_Name:campaignname:V',
			     'ncrm_campaign:campaigntype:Campaigns_Campaign_Type:campaigntype:V',
			     'ncrm_campaign:targetaudience:Campaigns_Target_Audience:targetaudience:V',
			     'ncrm_campaign:budgetcost:Campaigns_Budget_Cost:budgetcost:I',
			     'ncrm_campaign:actualcost:Campaigns_Actual_Cost:actualcost:I',
			     'ncrm_campaign:expectedrevenue:Campaigns_Expected_Revenue:expectedrevenue:I',
			     'ncrm_campaign:expectedsalescount:Campaigns_Expected_Sales_Count:expectedsalescount:N',
			     'ncrm_campaign:actualsalescount:Campaigns_Actual_Sales_Count:actualsalescount:N',
			     'ncrm_usersCampaigns:user_name:Campaigns_Assigned_To:assigned_user_id:V')
			);

$reports = Array(Array('reportname'=>'Contacts by Accounts',
                       'reportfolder'=>'Account and Contact Reports',
                       'description'=>'Contacts related to Accounts',
                       'reporttype'=>'tabular',
		       'sortid'=>'','stdfilterid'=>'','advfilterid'=>'0'),

		 Array('reportname'=>'Contacts without Accounts',
                       'reportfolder'=>'Account and Contact Reports',
                       'description'=>'Contacts not related to Accounts',
                       'reporttype'=>'tabular',
		       'sortid'=>'','stdfilterid'=>'','advfilterid'=>'1'),

		 Array('reportname'=>'Contacts by Potentials',
                       'reportfolder'=>'Account and Contact Reports',
                       'description'=>'Contacts related to Potentials',
                       'reporttype'=>'tabular',
		       'sortid'=>'','stdfilterid'=>'','advfilterid'=>'2'),

		 Array('reportname'=>'Lead by Source',
                       'reportfolder'=>'Lead Reports',
                       'description'=>'Lead by Source',
                       'reporttype'=>'summary',
		       'sortid'=>'0','stdfilterid'=>'','advfilterid'=>''),

		 Array('reportname'=>'Lead Status Report',
                       'reportfolder'=>'Lead Reports',
                       'description'=>'Lead Status Report',
                       'reporttype'=>'summary',
                       'sortid'=>'1','stdfilterid'=>'','advfilterid'=>''),

		 Array('reportname'=>'Potential Pipeline',
                       'reportfolder'=>'Potential Reports',
                       'description'=>'Potential Pipeline',
                       'reporttype'=>'summary',
                       'sortid'=>'2','stdfilterid'=>'','advfilterid'=>''),

		 Array('reportname'=>'Closed Potentials',
                       'reportfolder'=>'Potential Reports',
                       'description'=>'Potential that have Won',
                       'reporttype'=>'tabular',
                       'sortid'=>'','stdfilterid'=>'','advfilterid'=>'3'),

		 Array('reportname'=>'Last Month Activities',
                       'reportfolder'=>'Activity Reports',
                       'description'=>'Last Month Activities',
                       'reporttype'=>'tabular',
                       'sortid'=>'','stdfilterid'=>'0','advfilterid'=>''),

		 Array('reportname'=>'This Month Activities',
                       'reportfolder'=>'Activity Reports',
                       'description'=>'This Month Activities',
                       'reporttype'=>'tabular',
                       'sortid'=>'','stdfilterid'=>'1','advfilterid'=>''),

		 Array('reportname'=>'Tickets by Products',
                       'reportfolder'=>'HelpDesk Reports',
                       'description'=>'Tickets related to Products',
                       'reporttype'=>'tabular',
                       'sortid'=>'','stdfilterid'=>'','advfilterid'=>''),

		 Array('reportname'=>'Tickets by Priority',
                       'reportfolder'=>'HelpDesk Reports',
                       'description'=>'Tickets by Priority',
                       'reporttype'=>'summary',
                       'sortid'=>'3','stdfilterid'=>'','advfilterid'=>''),

 		 Array('reportname'=>'Open Tickets',
                       'reportfolder'=>'HelpDesk Reports',
                       'description'=>'Tickets that are Open',
                       'reporttype'=>'tabular',
                       'sortid'=>'','stdfilterid'=>'','advfilterid'=>'4'),

		 Array('reportname'=>'Product Details',
                       'reportfolder'=>'Product Reports',
                       'description'=>'Product Detailed Report',
                       'reporttype'=>'tabular',
                       'sortid'=>'','stdfilterid'=>'','advfilterid'=>''),

		 Array('reportname'=>'Products by Contacts',
                       'reportfolder'=>'Product Reports',
                       'description'=>'Products related to Contacts',
                       'reporttype'=>'tabular',
                       'sortid'=>'','stdfilterid'=>'','advfilterid'=>''),

		 Array('reportname'=>'Open Quotes',
                       'reportfolder'=>'Quote Reports',
                       'description'=>'Quotes that are Open',
                       'reporttype'=>'tabular',
                       'sortid'=>'','stdfilterid'=>'','advfilterid'=>'5'),

		 Array('reportname'=>'Quotes Detailed Report',
                       'reportfolder'=>'Quote Reports',
                       'description'=>'Quotes Detailed Report',
                       'reporttype'=>'tabular',
                       'sortid'=>'','stdfilterid'=>'','advfilterid'=>''),

		 Array('reportname'=>'PurchaseOrder by Contacts',
                       'reportfolder'=>'PurchaseOrder Reports',
                       'description'=>'PurchaseOrder related to Contacts',
                       'reporttype'=>'tabular',
                       'sortid'=>'','stdfilterid'=>'','advfilterid'=>''),

		 Array('reportname'=>'PurchaseOrder Detailed Report',
                       'reportfolder'=>'PurchaseOrder Reports',
                       'description'=>'PurchaseOrder Detailed Report',
                       'reporttype'=>'tabular',
                       'sortid'=>'','stdfilterid'=>'','advfilterid'=>''),

		 Array('reportname'=>'Invoice Detailed Report',
                       'reportfolder'=>'Invoice Reports',
                       'description'=>'Invoice Detailed Report',
                       'reporttype'=>'tabular',
		       'sortid'=>'','stdfilterid'=>'','advfilterid'=>''),

		 Array('reportname'=>'SalesOrder Detailed Report',
                       'reportfolder'=>'SalesOrder Reports',
                       'description'=>'SalesOrder Detailed Report',
                       'reporttype'=>'tabular',
                       'sortid'=>'','stdfilterid'=>'','advfilterid'=>''),

	         Array('reportname'=>'Campaign Expectations and Actuals',
		       'reportfolder'=>'Campaign Reports',
		       'description'=>'Campaign Expectations and Actuals',
		       'reporttype'=>'tabular',
		       'sortid'=>'','stdfilterid'=>'','advfilterid'=>'')

		);

$sortorder = Array(
                   Array(
                         Array('columnname'=>'ncrm_leaddetails:leadsource:Leads_Lead_Source:leadsource:V',
                               'sortorder'=>'Ascending'
                              )
			),
		   Array(
                         Array('columnname'=>'ncrm_leaddetails:leadstatus:Leads_Lead_Status:leadstatus:V',
                               'sortorder'=>'Ascending'
                              )
                        ),
		   Array(
                         Array('columnname'=>'ncrm_potential:sales_stage:Potentials_Sales_Stage:sales_stage:V',
                               'sortorder'=>'Ascending'
                              )
                        ),
		   Array(
                         Array('columnname'=>'ncrm_troubletickets:priority:HelpDesk_Priority:ticketpriorities:V',
                               'sortorder'=>'Ascending'
                              )
                        )
                  );

$stdfilters = Array(Array('columnname'=>'ncrm_crmentity:modifiedtime:modifiedtime:Calendar_Modified_Time',
			  'datefilter'=>'lastmonth',
			  'startdate'=>'2005-05-01',
			  'enddate'=>'2005-05-31'),

		    Array('columnname'=>'ncrm_crmentity:modifiedtime:modifiedtime:Calendar_Modified_Time',
                          'datefilter'=>'thismonth',
                          'startdate'=>'2005-06-01',
                          'enddate'=>'2005-06-30')
		   );

$advfilters = Array(
                      Array(
                            Array('columnname'=>'ncrm_contactdetails:accountid:Contacts_Account_Name:account_id:V',
                                  'comparator'=>'n',
                                  'value'=>''
                                 )
                           ),
		      Array(
                            Array('columnname'=>'ncrm_contactdetails:accountid:Contacts_Account_Name:account_id:V',
                                  'comparator'=>'e',
                                  'value'=>''
                                 )
                           ),
		      Array(
                            Array('columnname'=>'ncrm_potential:potentialname:Potentials_Potential_Name:potentialname:V',
                                  'comparator'=>'n',
                                  'value'=>''
                                 )
                           ),
		      Array(
                            Array('columnname'=>'ncrm_potential:sales_stage:Potentials_Sales_Stage:sales_stage:V',
                                  'comparator'=>'e',
                                  'value'=>'Closed Won'
                                 )
                           ),
		      Array(
                            Array('columnname'=>'ncrm_troubletickets:status:HelpDesk_Status:ticketstatus:V',
                                  'comparator'=>'n',
                                  'value'=>'Closed'
                                 )
                           ),
		      Array(
                            Array('columnname'=>'ncrm_quotes:quotestage:Quotes_Quote_Stage:quotestage:V',
                                  'comparator'=>'n',
                                  'value'=>'Accepted'
                                 ),
			    Array('columnname'=>'ncrm_quotes:quotestage:Quotes_Quote_Stage:quotestage:V',
                                  'comparator'=>'n',
                                  'value'=>'Rejected'
                                 )
                           )
                     );
//quotes:quotestage:Quotes_Quote_Stage:quotestage:V
foreach($rptfolder as $key=>$rptarray)
{
        foreach($rptarray as $foldername=>$folderdescription)
        {
                PopulateReportFolder($foldername,$folderdescription);
                $reportid[$foldername] = $key+1;
        }
}

foreach($reports as $key=>$report)
{
        $queryid = insertSelectQuery();
        insertReports($queryid,$reportid[$report['reportfolder']],$report['reportname'],$report['description'],$report['reporttype']);
        insertSelectColumns($queryid,$selectcolumns[$key]);
        insertReportModules($queryid,$reportmodules[$key]['primarymodule'],$reportmodules[$key]['secondarymodule']);

	if(isset($stdfilters[$report['stdfilterid']]))
	{
		$i = $report['stdfilterid'];
		insertStdFilter($queryid,$stdfilters[$i]['columnname'],$stdfilters[$i]['datefilter'],$stdfilters[$i]['startdate'],$stdfilters[$i]['enddate']);
	}

	if(isset($advfilters[$report['advfilterid']]))
	{
		insertAdvFilter($queryid,$advfilters[$report['advfilterid']]);
	}

	if($report['reporttype'] == "summary")
	{
		insertSortColumns($queryid,$sortorder[$report['sortid']]);
	}
}
$adb->pquery("UPDATE ncrm_report SET sharingtype='Public'",array());
/** Function to store the foldername and folderdescription to database
 *  This function accepts the given folder name and description
 *  ans store it in db as SAVED
 */

function PopulateReportFolder($fldrname,$fldrdescription)
{
	global $adb;
	$sql = "INSERT INTO ncrm_reportfolder (FOLDERNAME,DESCRIPTION,STATE) VALUES(?,?,?)";
	$params = array($fldrname, $fldrdescription, 'SAVED');
	$result = $adb->pquery($sql, $params);
}

/** Function to add an entry in selestquery ncrm_table
 *
 */

function insertSelectQuery()
{
	global $adb;
	$genQueryId = $adb->getUniqueID("ncrm_selectquery");
        if($genQueryId != "")
        {
		$iquerysql = "insert into ncrm_selectquery (QUERYID,STARTINDEX,NUMOFOBJECTS) values (?,?,?)";
		$iquerysqlresult = $adb->pquery($iquerysql, array($genQueryId,0,0));
	}

	return $genQueryId;
}

/** Function to store the ncrm_field names selected for a ncrm_report to a database
 *
 *
 */

function insertSelectColumns($queryid,$columnname)
{
	global $adb;
	if($queryid != "")
	{
		for($i=0;$i < count($columnname);$i++)
		{
			$icolumnsql = "insert into ncrm_selectcolumn (QUERYID,COLUMNINDEX,COLUMNNAME) values (?,?,?)";
			$icolumnsqlresult = $adb->pquery($icolumnsql, array($queryid, $i, $columnname[$i]));
		}
	}
}


/** Function to store the ncrm_report details to database
 *  This function accepts queryid,folderid,reportname,description,reporttype
 *  as arguments and store the informations in ncrm_report ncrm_table
 */

function insertReports($queryid,$folderid,$reportname,$description,$reporttype)
{
	global $adb;
	if($queryid != "")
	{
		$ireportsql = "insert into ncrm_report (REPORTID,FOLDERID,REPORTNAME,DESCRIPTION,REPORTTYPE,QUERYID,STATE) values (?,?,?,?,?,?,?)";
        $ireportparams = array($queryid, $folderid, $reportname, $description, $reporttype, $queryid, 'SAVED');
		$ireportresult = $adb->pquery($ireportsql, $ireportparams);
	}
}

/** Function to store the ncrm_report modules to database
 *  This function accepts queryid,primary module and secondary module
 *  as arguments and store the informations in ncrm_reportmodules ncrm_table
 */


function insertReportModules($queryid,$primarymodule,$secondarymodule)
{
	global $adb;
	if($queryid != "")
	{
		$ireportmodulesql = "insert into ncrm_reportmodules (REPORTMODULESID,PRIMARYMODULE,SECONDARYMODULES) values (?,?,?)";
		$ireportmoduleresult = $adb->pquery($ireportmodulesql, array($queryid, $primarymodule, $secondarymodule));
	}
}


/** Function to store the ncrm_report sortorder to database
 *  This function accepts queryid,sortlists
 *  as arguments and store the informations sort columns and
 *  and sortorder in ncrm_reportsortcol ncrm_table
 */


function insertSortColumns($queryid,$sortlists)
{
	global $adb;
	if($queryid != "")
	{
		foreach($sortlists as $i=>$sort)
                {
			$sort_bysql = "insert into ncrm_reportsortcol (SORTCOLID,REPORTID,COLUMNNAME,SORTORDER) values (?,?,?,?)";
			$sort_byresult = $adb->pquery($sort_bysql, array(($i+1), $queryid, $sort['columnname'], $sort['sortorder']));
		}
	}

}


/** Function to store the ncrm_report sort date details to database
 *  This function accepts queryid,filtercolumn,datefilter,startdate,enddate
 *  as arguments and store the informations in ncrm_reportdatefilter ncrm_table
 */


function insertStdFilter($queryid,$filtercolumn,$datefilter,$startdate,$enddate)
{
	global $adb;
	if($queryid != "")
	{
		$ireportmodulesql = "insert into ncrm_reportdatefilter (DATEFILTERID,DATECOLUMNNAME,DATEFILTER,STARTDATE,ENDDATE) values (?,?,?,?,?)";
		$ireportmoduleresult = $adb->pquery($ireportmodulesql, array($queryid, $filtercolumn, $datefilter, $startdate, $enddate));
	}

}

/** Function to store the ncrm_report conditions to database
 *  This function accepts queryid,filters
 *  as arguments and store the informations in ncrm_relcriteria ncrm_table
 */


function insertAdvFilter($queryid,$filters)
{
	global $adb;
	if($queryid != "")
	{
		$columnIndexArray = array();
		foreach($filters as $i=>$filter)
		{
			$irelcriteriasql = "insert into ncrm_relcriteria(QUERYID,COLUMNINDEX,COLUMNNAME,COMPARATOR,VALUE) values (?,?,?,?,?)";
			$irelcriteriaresult = $adb->pquery($irelcriteriasql, array($queryid,$i,$filter['columnname'],$filter['comparator'],$filter['value']));
			$columnIndexArray[] = $i;
		}
		$conditionExpression = implode(' and ', $columnIndexArray);
		$adb->pquery('INSERT INTO ncrm_relcriteria_grouping VALUES(?,?,?,?)', array(1, $queryid, '', $conditionExpression));
	}
}
?>