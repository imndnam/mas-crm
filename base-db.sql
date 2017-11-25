-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.16-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for newcrm_dev_fb
CREATE DATABASE IF NOT EXISTS `newcrm_dev_fb` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `newcrm_dev_fb`;

-- Dumping structure for table newcrm_dev_fb.com_ncrm_workflows
CREATE TABLE IF NOT EXISTS `com_ncrm_workflows` (
  `workflow_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(100) DEFAULT NULL,
  `summary` varchar(400) NOT NULL,
  `test` text,
  `execution_condition` int(11) NOT NULL,
  `defaultworkflow` int(1) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `filtersavedinnew` int(1) DEFAULT NULL,
  `schtypeid` int(10) DEFAULT NULL,
  `schdayofmonth` varchar(100) DEFAULT NULL,
  `schdayofweek` varchar(100) DEFAULT NULL,
  `schannualdates` varchar(100) DEFAULT NULL,
  `schtime` varchar(50) DEFAULT NULL,
  `nexttrigger_time` datetime DEFAULT NULL,
  PRIMARY KEY (`workflow_id`),
  UNIQUE KEY `com_ncrm_workflows_idx` (`workflow_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.com_ncrm_workflows: ~23 rows (approximately)
/*!40000 ALTER TABLE `com_ncrm_workflows` DISABLE KEYS */;
INSERT INTO `com_ncrm_workflows` (`workflow_id`, `module_name`, `summary`, `test`, `execution_condition`, `defaultworkflow`, `type`, `filtersavedinnew`, `schtypeid`, `schdayofmonth`, `schdayofweek`, `schannualdates`, `schtime`, `nexttrigger_time`) VALUES
	(1, 'Invoice', 'UpdateInventoryProducts On Every Save', '[{"fieldname":"subject","operation":"does not contain","value":"`!`"}]', 3, 1, 'basic', 5, NULL, NULL, NULL, NULL, NULL, NULL),
	(2, 'Accounts', 'Send Email to user when Notifyowner is True', '[{"fieldname":"notify_owner","operation":"is","value":"true:boolean"}]', 2, 1, 'basic', 5, NULL, NULL, NULL, NULL, NULL, NULL),
	(3, 'Contacts', 'Send Email to user when Notifyowner is True', '[{"fieldname":"notify_owner","operation":"is","value":"true:boolean"}]', 2, 1, 'basic', 5, NULL, NULL, NULL, NULL, NULL, NULL),
	(4, 'Contacts', 'Send Email to user when Portal User is True', '[{"fieldname":"portal","operation":"is","value":"true:boolean"}]', 2, 1, 'basic', 5, NULL, NULL, NULL, NULL, NULL, NULL),
	(5, 'Potentials', 'Send Email to users on Potential creation', NULL, 1, 1, 'basic', 5, NULL, NULL, NULL, NULL, NULL, NULL),
	(6, 'Contacts', 'Workflow for Contact Creation or Modification', '', 3, 1, 'basic', 5, NULL, NULL, NULL, NULL, NULL, NULL),
	(7, 'HelpDesk', 'Ticket Creation From Portal : Send Email to Record Owner and Contact', '[{"fieldname":"from_portal","operation":"is","value":1,"valuetype":"rawtext","joincondition":"and","groupjoin":"and","groupid":0},{"fieldname":"from_portal","operation":"is","value":"1","valuetype":"rawtext","joincondition":"","groupjoin":"and","groupid":"0"}]', 1, 1, 'basic', 6, NULL, NULL, NULL, NULL, NULL, NULL),
	(9, 'HelpDesk', 'Send Email to Contact on Ticket Update', '[{"fieldname":"(contact_id : (Contacts) emailoptout)","operation":"is","value":"0","valuetype":"rawtext","joincondition":"and","groupjoin":"and","groupid":"0"},{"fieldname":"from_portal","operation":"is","value":0,"valuetype":"rawtext","joincondition":"and","groupjoin":"and","groupid":0},{"fieldname":"ticketstatus","operation":"has changed to","value":"Closed","valuetype":"rawtext","joincondition":"or","groupjoin":"and","groupid":"1"},{"fieldname":"solution","operation":"has changed","value":"","valuetype":"","joincondition":"or","groupjoin":"and","groupid":"1"},{"fieldname":"description","operation":"has changed","value":"","valuetype":"","joincondition":"or","groupjoin":"and","groupid":"1"}]', 3, 1, 'basic', 6, NULL, NULL, NULL, NULL, NULL, NULL),
	(10, 'Events', 'Workflow for Events when Send Notification is True', '[{"fieldname":"sendnotification","operation":"is","value":"true:boolean"}]', 3, 1, 'basic', 5, NULL, NULL, NULL, NULL, NULL, NULL),
	(11, 'Calendar', 'Workflow for Calendar Todos when Send Notification is True', '[{"fieldname":"sendnotification","operation":"is","value":"true:boolean"}]', 3, 1, 'basic', 5, NULL, NULL, NULL, NULL, NULL, NULL),
	(12, 'Potentials', 'Calculate or Update forecast amount', '', 3, 1, 'basic', 5, NULL, NULL, NULL, NULL, NULL, NULL),
	(13, 'Events', 'Workflow for Events when Send Notification is True', '[{"fieldname":"sendnotification","operation":"is","value":"true:boolean"}]', 3, 1, 'basic', 5, NULL, NULL, NULL, NULL, NULL, NULL),
	(14, 'Calendar', 'Workflow for Calendar Todos when Send Notification is True', '[{"fieldname":"sendnotification","operation":"is","value":"true:boolean"}]', 3, 1, 'basic', 5, NULL, NULL, NULL, NULL, NULL, NULL),
	(15, 'HelpDesk', 'Comment Added From CRM : Send Email to Organization', '[{"fieldname":"_VT_add_comment","operation":"is added","value":"","valuetype":"rawtext","joincondition":"","groupjoin":"and","groupid":"0"},{"fieldname":"from_portal","operation":"is","value":"0","valuetype":"rawtext","joincondition":"","groupjoin":"and","groupid":"0"},{"fieldname":"(parent_id : (Accounts) emailoptout)","operation":"is","value":"0","valuetype":"rawtext","joincondition":"and","groupjoin":"and","groupid":"0"}]', 3, 1, 'basic', 6, NULL, NULL, NULL, NULL, NULL, NULL),
	(16, 'PurchaseOrder', 'Update Inventory Products On Every Save', NULL, 3, 1, 'basic', 5, NULL, NULL, NULL, NULL, NULL, NULL),
	(17, 'HelpDesk', 'Comment Added From Portal : Send Email to Record Owner', '[{"fieldname":"_VT_add_comment","operation":"is added","value":"","valuetype":"rawtext","joincondition":"","groupjoin":"and","groupid":"0"},{"fieldname":"from_portal","operation":"is","value":"1","valuetype":"rawtext","joincondition":"","groupjoin":"and","groupid":"0"}]', 3, 1, 'basic', 6, NULL, NULL, NULL, NULL, NULL, NULL),
	(18, 'HelpDesk', 'Comment Added From CRM : Send Email to Contact, where Contact is not a Portal User', '[{"fieldname":"(contact_id : (Contacts) portal)","operation":"is","value":"0","valuetype":"rawtext","joincondition":"and","groupjoin":"and","groupid":"0"},{"fieldname":"_VT_add_comment","operation":"is added","value":"","valuetype":"rawtext","joincondition":"","groupjoin":"and","groupid":"0"},{"fieldname":"from_portal","operation":"is","value":"0","valuetype":"rawtext","joincondition":"","groupjoin":"and","groupid":"0"},{"fieldname":"(contact_id : (Contacts) emailoptout)","operation":"is","value":"0","valuetype":"rawtext","joincondition":"and","groupjoin":"and","groupid":"0"}]', 3, 1, 'basic', 6, NULL, NULL, NULL, NULL, NULL, NULL),
	(19, 'HelpDesk', 'Comment Added From CRM : Send Email to Contact, where Contact is Portal User', '[{"fieldname":"(contact_id : (Contacts) portal)","operation":"is","value":"1","valuetype":"rawtext","joincondition":"and","groupjoin":"and","groupid":"0"},{"fieldname":"_VT_add_comment","operation":"is added","value":"","valuetype":"rawtext","joincondition":"","groupjoin":"and","groupid":"0"},{"fieldname":"from_portal","operation":"is","value":"0","valuetype":"rawtext","joincondition":"","groupjoin":"and","groupid":"0"},{"fieldname":"(contact_id : (Contacts) emailoptout)","operation":"is","value":"0","valuetype":"rawtext","joincondition":"and","groupjoin":"and","groupid":"0"}]', 3, 1, 'basic', 6, NULL, NULL, NULL, NULL, NULL, NULL),
	(20, 'HelpDesk', 'Send Email to Record Owner on Ticket Update', '[{"fieldname":"from_portal","operation":"is","value":0,"valuetype":"rawtext","joincondition":"and","groupjoin":"and","groupid":0},{"fieldname":"ticketstatus","operation":"has changed to","value":"Closed","valuetype":"rawtext","joincondition":"or","groupjoin":"and","groupid":"1"},{"fieldname":"solution","operation":"has changed","value":"","valuetype":"","joincondition":"or","groupjoin":"and","groupid":"1"},{"fieldname":"assigned_user_id","operation":"has changed","value":"","valuetype":"","joincondition":"or","groupjoin":"and","groupid":"1"},{"fieldname":"description","operation":"has changed","value":"","valuetype":"","joincondition":"or","groupjoin":"and","groupid":"1"}]', 3, 1, 'basic', 6, NULL, NULL, NULL, NULL, NULL, NULL),
	(21, 'HelpDesk', 'Ticket Creation From CRM : Send Email to Record Owner', '[{"fieldname":"from_portal","operation":"is","value":"0","valuetype":"rawtext","joincondition":"","groupjoin":"and","groupid":"0"}]', 1, 1, 'basic', 6, NULL, NULL, NULL, NULL, NULL, NULL),
	(22, 'HelpDesk', 'Send Email to Organization on Ticket Update', '[{"fieldname":"(parent_id : (Accounts) emailoptout)","operation":"is","value":"0","valuetype":"rawtext","joincondition":"and","groupjoin":"and","groupid":"0"},{"fieldname":"from_portal","operation":"is","value":0,"valuetype":"rawtext","joincondition":"and","groupjoin":"and","groupid":0},{"fieldname":"ticketstatus","operation":"has changed to","value":"Closed","valuetype":"rawtext","joincondition":"or","groupjoin":"and","groupid":"1"},{"fieldname":"solution","operation":"has changed","value":"","valuetype":"","joincondition":"or","groupjoin":"and","groupid":"1"},{"fieldname":"description","operation":"has changed","value":"","valuetype":"","joincondition":"or","groupjoin":"and","groupid":"1"}]', 3, 1, 'basic', 6, NULL, NULL, NULL, NULL, NULL, NULL),
	(23, 'HelpDesk', 'Ticket Creation From CRM : Send Email to Organization', '[{"fieldname":"(parent_id : (Accounts) emailoptout)","operation":"is","value":"0","valuetype":"rawtext","joincondition":"and","groupjoin":"and","groupid":"0"},{"fieldname":"from_portal","operation":"is","value":"0","valuetype":"rawtext","joincondition":"","groupjoin":"and","groupid":"0"}]', 1, 1, 'basic', 6, NULL, NULL, NULL, NULL, NULL, NULL),
	(24, 'HelpDesk', 'Ticket Creation From CRM : Send Email to Contact', '[{"fieldname":"(contact_id : (Contacts) emailoptout)","operation":"is","value":"0","valuetype":"rawtext","joincondition":"and","groupjoin":"and","groupid":"0"},{"fieldname":"from_portal","operation":"is","value":"0","valuetype":"rawtext","joincondition":"","groupjoin":"and","groupid":"0"}]', 1, 1, 'basic', 6, NULL, NULL, NULL, NULL, NULL, NULL);
/*!40000 ALTER TABLE `com_ncrm_workflows` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.com_ncrm_workflows_seq
CREATE TABLE IF NOT EXISTS `com_ncrm_workflows_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.com_ncrm_workflows_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `com_ncrm_workflows_seq` DISABLE KEYS */;
INSERT INTO `com_ncrm_workflows_seq` (`id`) VALUES
	(24);
/*!40000 ALTER TABLE `com_ncrm_workflows_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.com_ncrm_workflowtasks
CREATE TABLE IF NOT EXISTS `com_ncrm_workflowtasks` (
  `task_id` int(11) NOT NULL AUTO_INCREMENT,
  `workflow_id` int(11) DEFAULT NULL,
  `summary` varchar(400) NOT NULL,
  `task` text,
  PRIMARY KEY (`task_id`),
  UNIQUE KEY `com_ncrm_workflowtasks_idx` (`task_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.com_ncrm_workflowtasks: ~22 rows (approximately)
/*!40000 ALTER TABLE `com_ncrm_workflowtasks` DISABLE KEYS */;
INSERT INTO `com_ncrm_workflowtasks` (`task_id`, `workflow_id`, `summary`, `task`) VALUES
	(1, 1, '', 'O:18:"VTEntityMethodTask":6:{s:18:"executeImmediately";b:1;s:10:"workflowId";i:1;s:7:"summary";s:0:"";s:6:"active";b:1;s:10:"methodName";s:15:"UpdateInventory";s:2:"id";i:1;}'),
	(2, 2, 'An account has been created ', 'O:11:"VTEmailTask":9:{s:18:"executeImmediately";s:0:"";s:10:"workflowId";s:1:"2";s:7:"summary";s:28:"An account has been created ";s:6:"active";s:1:"1";s:10:"methodName";s:11:"NotifyOwner";s:9:"recepient";s:36:"$(assigned_user_id : (Users) email1)";s:7:"subject";s:26:"Regarding Account Creation";s:7:"content";s:301:"An Account has been assigned to you on ncrmCRM<br>Details of account are :<br><br>AccountId:<b>$account_no</b><br>AccountName:<b>$accountname</b><br>Rating:<b>$rating</b><br>Industry:<b>$industry</b><br>AccountType:<b>$accounttype</b><br>Description:<b>$description</b><br><br><br>Thank You<br>Admin";s:2:"id";s:1:"2";}'),
	(3, 3, 'An contact has been created ', 'O:11:"VTEmailTask":9:{s:18:"executeImmediately";s:0:"";s:10:"workflowId";s:1:"3";s:7:"summary";s:28:"An contact has been created ";s:6:"active";s:1:"1";s:10:"methodName";s:11:"NotifyOwner";s:9:"recepient";s:36:"$(assigned_user_id : (Users) email1)";s:7:"subject";s:26:"Regarding Contact Creation";s:7:"content";s:305:"An Contact has been assigned to you on ncrmCRM<br>Details of Contact are :<br><br>Contact Id:<b>$contact_no</b><br>LastName:<b>$lastname</b><br>FirstName:<b>$firstname</b><br>Lead Source:<b>$leadsource</b><br>Department:<b>$department</b><br>Description:<b>$description</b><br><br><br>Thank You<br>Admin";s:2:"id";s:1:"3";}'),
	(4, 4, 'Email Customer Portal Login Details', 'O:18:"VTEntityMethodTask":6:{s:18:"executeImmediately";b:1;s:10:"workflowId";i:4;s:7:"summary";s:35:"Email Customer Portal Login Details";s:6:"active";b:1;s:10:"methodName";s:22:"SendPortalLoginDetails";s:2:"id";i:4;}'),
	(5, 5, 'An Potential has been created ', 'O:11:"VTEmailTask":8:{s:18:"executeImmediately";s:0:"";s:10:"workflowId";s:1:"5";s:7:"summary";s:30:"An Potential has been created ";s:6:"active";s:1:"1";s:9:"recepient";s:36:"$(assigned_user_id : (Users) email1)";s:7:"subject";s:30:"Regarding Potential Assignment";s:7:"content";s:342:"An Potential has been assigned to you on ncrmCRM<br>Details of Potential are :<br><br>Potential No:<b>$potential_no</b><br>Potential Name:<b>$potentialname</b><br>Amount:<b>$amount</b><br>Expected Close Date:<b>$closingdate ($_DATE_FORMAT_)</b><br>Type:<b>$opportunity_type</b><br><br><br>Description :$description<br><br>Thank You<br>Admin";s:2:"id";s:1:"5";}'),
	(6, 6, 'An contact has been created ', 'O:11:"VTEmailTask":8:{s:18:"executeImmediately";s:0:"";s:10:"workflowId";s:1:"6";s:7:"summary";s:28:"An contact has been created ";s:6:"active";s:1:"1";s:9:"recepient";s:36:"$(assigned_user_id : (Users) email1)";s:7:"subject";s:28:"Regarding Contact Assignment";s:7:"content";s:384:"An Contact has been assigned to you on ncrmCRM<br>Details of Contact are :<br><br>Contact Id:<b>$contact_no</b><br>LastName:<b>$lastname</b><br>FirstName:<b>$firstname</b><br>Lead Source:<b>$leadsource</b><br>Department:<b>$department</b><br>Description:<b>$description</b><br><br><br>And <b>CustomerPortal Login Details</b> is sent to the EmailID :-$email<br><br>Thank You<br>Admin";s:2:"id";s:1:"6";}'),
	(7, 7, 'Notify Related Contact when Ticket is created from Portal', 'O:11:"VTEmailTask":9:{s:18:"executeImmediately";s:1:"0";s:7:"summary";s:57:"Notify Related Contact when Ticket is created from Portal";s:6:"active";s:1:"1";s:2:"id";s:1:"7";s:10:"workflowId";s:1:"7";s:9:"fromEmail";s:93:"$(general : (__NcrmMeta__) supportName)&lt;$(general : (__NcrmMeta__) supportEmailId)&gt;";s:9:"recepient";s:33:",$(contact_id : (Contacts) email)";s:7:"subject";s:93:"[From Portal] $ticket_no [ Ticket Id : $(general : (__NcrmMeta__) recordId) ] $ticket_title";s:7:"content";s:161:"Ticket No : $ticket_no<br>\r\n							  Ticket ID : $(general : (__NcrmMeta__) recordId)<br>\r\n							  Ticket Title : $ticket_title<br><br>\r\n							  $description";}'),
	(10, 9, 'Send Email to Contact on Ticket Update', 'O:11:"VTEmailTask":9:{s:18:"executeImmediately";s:1:"0";s:7:"summary";s:38:"Send Email to Contact on Ticket Update";s:6:"active";s:1:"1";s:2:"id";s:2:"10";s:9:"fromEmail";s:93:"$(general : (__NcrmMeta__) supportName)&lt;$(general : (__NcrmMeta__) supportEmailId)&gt;";s:9:"recepient";s:33:",$(contact_id : (Contacts) email)";s:7:"subject";s:79:"$ticket_no [ Ticket Id : $(general : (__NcrmMeta__) recordId) ] $ticket_title";s:7:"content";s:636:"Ticket ID : $(general : (__NcrmMeta__) recordId)<br>Ticket Title : $ticket_title<br><br>\r\n								Dear $(contact_id : (Contacts) lastname) $(contact_id : (Contacts) firstname),<br><br>\r\n								The Ticket is replied the details are :<br><br>\r\n								Ticket No : $ticket_no<br>\r\n								Status : $ticketstatus<br>\r\n								Category : $ticketcategories<br>\r\n								Severity : $ticketseverities<br>\r\n								Priority : $ticketpriorities<br><br>\r\n								Description : <br>$description<br><br>\r\n								Solution : <br>$solution<br>\r\n								The comments are : <br>\r\n								$allComments<br><br>\r\n								Regards<br>Support Administrator";s:10:"workflowId";s:1:"9";}'),
	(13, 12, 'update forecast amount', 'O:18:"VTUpdateFieldsTask":6:{s:18:"executeImmediately";b:1;s:10:"workflowId";i:12;s:7:"summary";s:22:"update forecast amount";s:6:"active";b:1;s:19:"field_value_mapping";s:95:"[{"fieldname":"forecast_amount","valuetype":"expression","value":"amount * probability / 100"}]";s:2:"id";i:13;}'),
	(14, 13, 'Send Notification Email to Record Owner', 'O:11:"VTEmailTask":8:{s:18:"executeImmediately";s:0:"";s:10:"workflowId";s:2:"13";s:7:"summary";s:39:"Send Notification Email to Record Owner";s:6:"active";s:1:"1";s:9:"recepient";s:36:"$(assigned_user_id : (Users) email1)";s:7:"subject";s:17:"Event :  $subject";s:7:"content";s:771:"$(assigned_user_id : (Users) last_name) $(assigned_user_id : (Users) first_name) ,<br/><b>Activity Notification Details:</b><br/>Subject             : $subject<br/>Start date and time : $date_start ($(general : (__NcrmMeta__) usertimezone))<br/>End date and time   : $due_date ($(general : (__NcrmMeta__) usertimezone)) <br/>Status              : $eventstatus <br/>Priority            : $taskpriority <br/>Related To          : $(parent_id : (Leads) lastname) $(parent_id : (Leads) firstname) $(parent_id : (Accounts) accountname) $(parent_id : (Potentials) potentialname) $(parent_id : (HelpDesk) ticket_title) $(parent_id : (Campaigns) campaignname) <br/>Contacts List       : $contact_id <br/>Location            : $location <br/>Description         : $description";s:2:"id";s:2:"14";}'),
	(15, 14, 'Send Notification Email to Record Owner', 'O:11:"VTEmailTask":8:{s:18:"executeImmediately";s:0:"";s:10:"workflowId";s:2:"14";s:7:"summary";s:39:"Send Notification Email to Record Owner";s:6:"active";s:1:"1";s:9:"recepient";s:36:"$(assigned_user_id : (Users) email1)";s:7:"subject";s:16:"Task :  $subject";s:7:"content";s:689:"$(assigned_user_id : (Users) last_name) $(assigned_user_id : (Users) first_name) ,<br/><b>Task Notification Details:</b><br/>Subject : $subject<br/>Start date and time : $date_start ($(general : (__NcrmMeta__) usertimezone))<br/>End date and time   : $due_date ($_DATE_FORMAT_) <br/>Status              : $taskstatus <br/>Priority            : $taskpriority <br/>Related To          : $(parent_id : (Leads) lastname) $(parent_id : (Leads) firstname) $(parent_id : (Accounts) accountname) $(parent_id : (Potentials) potentialname) $(parent_id : (HelpDesk) ticket_title) $(parent_id : (Campaigns) campaignname) <br/>Contacts List       : $contact_id <br/>Description         : $description";s:2:"id";s:2:"15";}'),
	(18, 16, 'Update Inventory Products', 'O:18:"VTEntityMethodTask":6:{s:18:"executeImmediately";b:1;s:10:"workflowId";i:16;s:7:"summary";s:25:"Update Inventory Products";s:6:"active";b:1;s:10:"methodName";s:15:"UpdateInventory";s:2:"id";i:18;}'),
	(19, 17, 'Comment Added From Portal : Send Email to Record Owner', 'O:11:"VTEmailTask":9:{s:18:"executeImmediately";s:1:"0";s:7:"summary";s:54:"Comment Added From Portal : Send Email to Record Owner";s:6:"active";s:1:"1";s:2:"id";s:2:"19";s:10:"workflowId";s:2:"17";s:9:"fromEmail";s:112:"$(contact_id : (Contacts) lastname) $(contact_id : (Contacts) firstname)&lt;$(contact_id : (Contacts) email)&gt;";s:9:"recepient";s:37:",$(assigned_user_id : (Users) email1)";s:7:"subject";s:92:"Respond to Ticket ID## $(general : (__NcrmMeta__) recordId) ## in Customer Portal - URGENT";s:7:"content";s:329:"Dear $(assigned_user_id : (Users) last_name) $(assigned_user_id : (Users) first_name),<br><br>\r\n								Customer has provided the following additional information to your reply:<br><br>\r\n								<b>$lastComment</b><br><br>\r\n								Kindly respond to above ticket at the earliest.<br><br>\r\n								Regards<br>Support Administrator";}'),
	(20, 18, 'Comment Added From CRM : Send Email to Contact, where Contact is not a Portal User', 'O:11:"VTEmailTask":9:{s:18:"executeImmediately";s:1:"0";s:7:"summary";s:82:"Comment Added From CRM : Send Email to Contact, where Contact is not a Portal User";s:6:"active";s:1:"1";s:2:"id";s:2:"20";s:10:"workflowId";s:2:"18";s:9:"fromEmail";s:93:"$(general : (__NcrmMeta__) supportName)&lt;$(general : (__NcrmMeta__) supportEmailId)&gt;";s:9:"recepient";s:33:",$(contact_id : (Contacts) email)";s:7:"subject";s:79:"$ticket_no [ Ticket Id : $(general : (__NcrmMeta__) recordId) ] $ticket_title";s:7:"content";s:525:"Dear $(contact_id : (Contacts) lastname) $(contact_id : (Contacts) firstname),<br><br>\r\n							The Ticket is replied the details are :<br><br>\r\n							Ticket No : $ticket_no<br>\r\n							Status : $ticketstatus<br>\r\n							Category : $ticketcategories<br>\r\n							Severity : $ticketseverities<br>\r\n							Priority : $ticketpriorities<br><br>\r\n							Description : <br>$description<br><br>\r\n							Solution : <br>$solution<br>\r\n							The comments are : <br>\r\n							$allComments<br><br>\r\n							Regards<br>Support Administrator";}'),
	(21, 19, 'Comment Added From CRM : Send Email to Contact, where Contact is Portal User', 'O:11:"VTEmailTask":9:{s:18:"executeImmediately";s:1:"0";s:7:"summary";s:76:"Comment Added From CRM : Send Email to Contact, where Contact is Portal User";s:6:"active";s:1:"1";s:2:"id";s:2:"21";s:10:"workflowId";s:2:"19";s:9:"fromEmail";s:93:"$(general : (__NcrmMeta__) supportName)&lt;$(general : (__NcrmMeta__) supportEmailId)&gt;";s:9:"recepient";s:33:",$(contact_id : (Contacts) email)";s:7:"subject";s:79:"$ticket_no [ Ticket Id : $(general : (__NcrmMeta__) recordId) ] $ticket_title";s:7:"content";s:554:"Ticket No : $ticket_no<br>\r\n										Ticket Id : $(general : (__NcrmMeta__) recordId)<br>\r\n										Subject : $ticket_title<br><br>\r\n										Dear $(contact_id : (Contacts) lastname) $(contact_id : (Contacts) firstname),<br><br>\r\n										There is a reply to <b>$ticket_title</b> in the "Customer Portal" at NCrm.\r\n										You can use the following link to view the replies made:<br>\r\n										<a href="$(general : (__NcrmMeta__) portaldetailviewurl)">Ticket Details</a><br><br>\r\n										Thanks<br>$(general : (__NcrmMeta__) supportName)";}'),
	(22, 15, 'Comment Added From CRM : Send Email to Organization', 'O:11:"VTEmailTask":9:{s:18:"executeImmediately";s:1:"0";s:7:"summary";s:51:"Comment Added From CRM : Send Email to Organization";s:6:"active";s:1:"1";s:2:"id";s:2:"22";s:10:"workflowId";s:2:"15";s:9:"fromEmail";s:93:"$(general : (__NcrmMeta__) supportName)&lt;$(general : (__NcrmMeta__) supportEmailId)&gt;";s:9:"recepient";s:34:",$(parent_id : (Accounts) email1),";s:7:"subject";s:79:"$ticket_no [ Ticket Id : $(general : (__NcrmMeta__) recordId) ] $ticket_title";s:7:"content";s:601:"Ticket ID : $(general : (__NcrmMeta__) recordId)<br>Ticket Title : $ticket_title<br><br>\r\n								Dear $(parent_id : (Accounts) accountname),<br><br>\r\n								The Ticket is replied the details are :<br><br>\r\n								Ticket No : $ticket_no<br>\r\n								Status : $ticketstatus<br>\r\n								Category : $ticketcategories<br>\r\n								Severity : $ticketseverities<br>\r\n								Priority : $ticketpriorities<br><br>\r\n								Description : <br>$description<br><br>\r\n								Solution : <br>$solution<br>\r\n								The comments are : <br>\r\n								$allComments<br><br>\r\n								Regards<br>Support Administrator";}'),
	(23, 7, 'Notify Record Owner when Ticket is created from Portal', 'O:11:"VTEmailTask":9:{s:18:"executeImmediately";s:1:"0";s:7:"summary";s:54:"Notify Record Owner when Ticket is created from Portal";s:6:"active";s:1:"1";s:2:"id";s:2:"23";s:10:"workflowId";s:1:"7";s:9:"fromEmail";s:124:"$(contact_id : (Contacts) lastname) $(contact_id : (Contacts) firstname)&lt;$(general : (__NcrmMeta__) supportEmailId)&gt;";s:9:"recepient";s:37:",$(assigned_user_id : (Users) email1)";s:7:"subject";s:93:"[From Portal] $ticket_no [ Ticket Id : $(general : (__NcrmMeta__) recordId) ] $ticket_title";s:7:"content";s:161:"Ticket No : $ticket_no<br>\r\n							  Ticket ID : $(general : (__NcrmMeta__) recordId)<br>\r\n							  Ticket Title : $ticket_title<br><br>\r\n							  $description";}'),
	(24, 20, 'Send Email to Record Owner on Ticket Update', 'O:11:"VTEmailTask":9:{s:18:"executeImmediately";s:1:"0";s:7:"summary";s:43:"Send Email to Record Owner on Ticket Update";s:6:"active";s:1:"1";s:2:"id";s:2:"24";s:10:"workflowId";s:2:"20";s:9:"fromEmail";s:93:"$(general : (__NcrmMeta__) supportName)&lt;$(general : (__NcrmMeta__) supportEmailId)&gt;";s:9:"recepient";s:37:",$(assigned_user_id : (Users) email1)";s:7:"subject";s:40:"Ticket Number : $ticket_no $ticket_title";s:7:"content";s:607:"Ticket ID : $(general : (__NcrmMeta__) recordId)<br>Ticket Title : $ticket_title<br><br>\r\n								Dear $(assigned_user_id : (Users) last_name) $(assigned_user_id : (Users) first_name),<br><br>\r\n								The Ticket is replied the details are :<br><br>\r\n								Ticket No : $ticket_no<br>\r\n								Status : $ticketstatus<br>\r\n								Category : $ticketcategories<br>\r\n								Severity : $ticketseverities<br>\r\n								Priority : $ticketpriorities<br><br>\r\n								Description : <br>$description<br><br>\r\n								Solution : <br>$solution\r\n								$allComments<br><br>\r\n								Regards<br>Support Administrator";}'),
	(25, 21, 'Ticket Creation From CRM : Send Email to Record Owner', 'O:11:"VTEmailTask":9:{s:18:"executeImmediately";s:1:"0";s:7:"summary";s:53:"Ticket Creation From CRM : Send Email to Record Owner";s:6:"active";s:1:"1";s:2:"id";s:2:"25";s:10:"workflowId";s:2:"21";s:9:"fromEmail";s:93:"$(general : (__NcrmMeta__) supportName)&lt;$(general : (__NcrmMeta__) supportEmailId)&gt;";s:9:"recepient";s:37:",$(assigned_user_id : (Users) email1)";s:7:"subject";s:40:"Ticket Number : $ticket_no $ticket_title";s:7:"content";s:607:"Ticket ID : $(general : (__NcrmMeta__) recordId)<br>Ticket Title : $ticket_title<br><br>\r\n								Dear $(assigned_user_id : (Users) last_name) $(assigned_user_id : (Users) first_name),<br><br>\r\n								The Ticket is replied the details are :<br><br>\r\n								Ticket No : $ticket_no<br>\r\n								Status : $ticketstatus<br>\r\n								Category : $ticketcategories<br>\r\n								Severity : $ticketseverities<br>\r\n								Priority : $ticketpriorities<br><br>\r\n								Description : <br>$description<br><br>\r\n								Solution : <br>$solution\r\n								$allComments<br><br>\r\n								Regards<br>Support Administrator";}'),
	(26, 22, 'Send Email to Organization on Ticket Update', 'O:11:"VTEmailTask":9:{s:18:"executeImmediately";s:1:"0";s:7:"summary";s:43:"Send Email to Organization on Ticket Update";s:6:"active";s:1:"1";s:2:"id";s:2:"26";s:9:"fromEmail";s:93:"$(general : (__NcrmMeta__) supportName)&lt;$(general : (__NcrmMeta__) supportEmailId)&gt;";s:9:"recepient";s:33:",$(parent_id : (Accounts) email1)";s:7:"subject";s:79:"$ticket_no [ Ticket Id : $(general : (__NcrmMeta__) recordId) ] $ticket_title";s:7:"content";s:601:"Ticket ID : $(general : (__NcrmMeta__) recordId)<br>Ticket Title : $ticket_title<br><br>\r\n								Dear $(parent_id : (Accounts) accountname),<br><br>\r\n								The Ticket is replied the details are :<br><br>\r\n								Ticket No : $ticket_no<br>\r\n								Status : $ticketstatus<br>\r\n								Category : $ticketcategories<br>\r\n								Severity : $ticketseverities<br>\r\n								Priority : $ticketpriorities<br><br>\r\n								Description : <br>$description<br><br>\r\n								Solution : <br>$solution<br>\r\n								The comments are : <br>\r\n								$allComments<br><br>\r\n								Regards<br>Support Administrator";s:10:"workflowId";s:2:"22";}'),
	(27, 23, 'Ticket Creation From CRM : Send Email to Organization', 'O:11:"VTEmailTask":9:{s:18:"executeImmediately";s:1:"0";s:7:"summary";s:53:"Ticket Creation From CRM : Send Email to Organization";s:6:"active";s:1:"1";s:2:"id";s:2:"27";s:9:"fromEmail";s:93:"$(general : (__NcrmMeta__) supportName)&lt;$(general : (__NcrmMeta__) supportEmailId)&gt;";s:9:"recepient";s:33:",$(parent_id : (Accounts) email1)";s:7:"subject";s:79:"$ticket_no [ Ticket Id : $(general : (__NcrmMeta__) recordId) ] $ticket_title";s:7:"content";s:601:"Ticket ID : $(general : (__NcrmMeta__) recordId)<br>Ticket Title : $ticket_title<br><br>\r\n								Dear $(parent_id : (Accounts) accountname),<br><br>\r\n								The Ticket is replied the details are :<br><br>\r\n								Ticket No : $ticket_no<br>\r\n								Status : $ticketstatus<br>\r\n								Category : $ticketcategories<br>\r\n								Severity : $ticketseverities<br>\r\n								Priority : $ticketpriorities<br><br>\r\n								Description : <br>$description<br><br>\r\n								Solution : <br>$solution<br>\r\n								The comments are : <br>\r\n								$allComments<br><br>\r\n								Regards<br>Support Administrator";s:10:"workflowId";s:2:"23";}'),
	(28, 24, 'Ticket Creation From CRM : Send Email to Contact', 'O:11:"VTEmailTask":9:{s:18:"executeImmediately";s:1:"0";s:7:"summary";s:48:"Ticket Creation From CRM : Send Email to Contact";s:6:"active";s:1:"1";s:2:"id";s:2:"28";s:9:"fromEmail";s:93:"$(general : (__NcrmMeta__) supportName)&lt;$(general : (__NcrmMeta__) supportEmailId)&gt;";s:9:"recepient";s:33:",$(contact_id : (Contacts) email)";s:7:"subject";s:79:"$ticket_no [ Ticket Id : $(general : (__NcrmMeta__) recordId) ] $ticket_title";s:7:"content";s:636:"Ticket ID : $(general : (__NcrmMeta__) recordId)<br>Ticket Title : $ticket_title<br><br>\r\n								Dear $(contact_id : (Contacts) lastname) $(contact_id : (Contacts) firstname),<br><br>\r\n								The Ticket is replied the details are :<br><br>\r\n								Ticket No : $ticket_no<br>\r\n								Status : $ticketstatus<br>\r\n								Category : $ticketcategories<br>\r\n								Severity : $ticketseverities<br>\r\n								Priority : $ticketpriorities<br><br>\r\n								Description : <br>$description<br><br>\r\n								Solution : <br>$solution<br>\r\n								The comments are : <br>\r\n								$allComments<br><br>\r\n								Regards<br>Support Administrator";s:10:"workflowId";s:2:"24";}');
/*!40000 ALTER TABLE `com_ncrm_workflowtasks` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.com_ncrm_workflowtasks_entitymethod
CREATE TABLE IF NOT EXISTS `com_ncrm_workflowtasks_entitymethod` (
  `workflowtasks_entitymethod_id` int(11) NOT NULL,
  `module_name` varchar(100) DEFAULT NULL,
  `method_name` varchar(100) DEFAULT NULL,
  `function_path` varchar(400) DEFAULT NULL,
  `function_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`workflowtasks_entitymethod_id`),
  UNIQUE KEY `com_ncrm_workflowtasks_entitymethod_idx` (`workflowtasks_entitymethod_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.com_ncrm_workflowtasks_entitymethod: ~10 rows (approximately)
/*!40000 ALTER TABLE `com_ncrm_workflowtasks_entitymethod` DISABLE KEYS */;
INSERT INTO `com_ncrm_workflowtasks_entitymethod` (`workflowtasks_entitymethod_id`, `module_name`, `method_name`, `function_path`, `function_name`) VALUES
	(1, 'SalesOrder', 'UpdateInventory', 'include/InventoryHandler.php', 'handleInventoryProductRel'),
	(2, 'Invoice', 'UpdateInventory', 'include/InventoryHandler.php', 'handleInventoryProductRel'),
	(3, 'Contacts', 'SendPortalLoginDetails', 'modules/Contacts/ContactsHandler.php', 'Contacts_sendCustomerPortalLoginDetails'),
	(4, 'HelpDesk', 'NotifyOnPortalTicketCreation', 'modules/HelpDesk/HelpDeskHandler.php', 'HelpDesk_nofifyOnPortalTicketCreation'),
	(5, 'HelpDesk', 'NotifyOnPortalTicketComment', 'modules/HelpDesk/HelpDeskHandler.php', 'HelpDesk_notifyOnPortalTicketComment'),
	(6, 'HelpDesk', 'NotifyOwnerOnTicketChange', 'modules/HelpDesk/HelpDeskHandler.php', 'HelpDesk_notifyOwnerOnTicketChange'),
	(7, 'HelpDesk', 'NotifyParentOnTicketChange', 'modules/HelpDesk/HelpDeskHandler.php', 'HelpDesk_notifyParentOnTicketChange'),
	(8, 'ModComments', 'CustomerCommentFromPortal', 'modules/ModComments/ModCommentsHandler.php', 'CustomerCommentFromPortal'),
	(9, 'ModComments', 'TicketOwnerComments', 'modules/ModComments/ModCommentsHandler.php', 'TicketOwnerComments'),
	(10, 'PurchaseOrder', 'UpdateInventory', 'include/InventoryHandler.php', 'handleInventoryProductRel');
/*!40000 ALTER TABLE `com_ncrm_workflowtasks_entitymethod` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.com_ncrm_workflowtasks_entitymethod_seq
CREATE TABLE IF NOT EXISTS `com_ncrm_workflowtasks_entitymethod_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.com_ncrm_workflowtasks_entitymethod_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `com_ncrm_workflowtasks_entitymethod_seq` DISABLE KEYS */;
INSERT INTO `com_ncrm_workflowtasks_entitymethod_seq` (`id`) VALUES
	(10);
/*!40000 ALTER TABLE `com_ncrm_workflowtasks_entitymethod_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.com_ncrm_workflowtasks_seq
CREATE TABLE IF NOT EXISTS `com_ncrm_workflowtasks_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.com_ncrm_workflowtasks_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `com_ncrm_workflowtasks_seq` DISABLE KEYS */;
INSERT INTO `com_ncrm_workflowtasks_seq` (`id`) VALUES
	(28);
/*!40000 ALTER TABLE `com_ncrm_workflowtasks_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.com_ncrm_workflowtask_queue
CREATE TABLE IF NOT EXISTS `com_ncrm_workflowtask_queue` (
  `task_id` int(11) DEFAULT NULL,
  `entity_id` varchar(100) DEFAULT NULL,
  `do_after` int(11) DEFAULT NULL,
  `task_contents` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.com_ncrm_workflowtask_queue: ~0 rows (approximately)
/*!40000 ALTER TABLE `com_ncrm_workflowtask_queue` DISABLE KEYS */;
/*!40000 ALTER TABLE `com_ncrm_workflowtask_queue` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.com_ncrm_workflowtemplates
CREATE TABLE IF NOT EXISTS `com_ncrm_workflowtemplates` (
  `template_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(100) DEFAULT NULL,
  `title` varchar(400) DEFAULT NULL,
  `template` text,
  PRIMARY KEY (`template_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.com_ncrm_workflowtemplates: ~0 rows (approximately)
/*!40000 ALTER TABLE `com_ncrm_workflowtemplates` DISABLE KEYS */;
/*!40000 ALTER TABLE `com_ncrm_workflowtemplates` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.com_ncrm_workflow_activatedonce
CREATE TABLE IF NOT EXISTS `com_ncrm_workflow_activatedonce` (
  `workflow_id` int(11) NOT NULL,
  `entity_id` int(11) NOT NULL,
  PRIMARY KEY (`workflow_id`,`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.com_ncrm_workflow_activatedonce: ~0 rows (approximately)
/*!40000 ALTER TABLE `com_ncrm_workflow_activatedonce` DISABLE KEYS */;
/*!40000 ALTER TABLE `com_ncrm_workflow_activatedonce` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.com_ncrm_workflow_tasktypes
CREATE TABLE IF NOT EXISTS `com_ncrm_workflow_tasktypes` (
  `id` int(11) NOT NULL,
  `tasktypename` varchar(255) NOT NULL,
  `label` varchar(255) DEFAULT NULL,
  `classname` varchar(255) DEFAULT NULL,
  `classpath` varchar(255) DEFAULT NULL,
  `templatepath` varchar(255) DEFAULT NULL,
  `modules` varchar(500) DEFAULT NULL,
  `sourcemodule` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.com_ncrm_workflow_tasktypes: ~7 rows (approximately)
/*!40000 ALTER TABLE `com_ncrm_workflow_tasktypes` DISABLE KEYS */;
INSERT INTO `com_ncrm_workflow_tasktypes` (`id`, `tasktypename`, `label`, `classname`, `classpath`, `templatepath`, `modules`, `sourcemodule`) VALUES
	(1, 'VTEmailTask', 'Send Mail', 'VTEmailTask', 'modules/com_ncrm_workflow/tasks/VTEmailTask.inc', 'com_ncrm_workflow/taskforms/VTEmailTask.tpl', '{"include":[],"exclude":[]}', ''),
	(2, 'VTEntityMethodTask', 'Invoke Custom Function', 'VTEntityMethodTask', 'modules/com_ncrm_workflow/tasks/VTEntityMethodTask.inc', 'com_ncrm_workflow/taskforms/VTEntityMethodTask.tpl', '{"include":[],"exclude":[]}', ''),
	(3, 'VTCreateTodoTask', 'Create Todo', 'VTCreateTodoTask', 'modules/com_ncrm_workflow/tasks/VTCreateTodoTask.inc', 'com_ncrm_workflow/taskforms/VTCreateTodoTask.tpl', '{"include":["Leads","Accounts","Potentials","Contacts","HelpDesk","Campaigns","Quotes","PurchaseOrder","SalesOrder","Invoice"],"exclude":["Calendar","FAQ","Events"]}', ''),
	(4, 'VTCreateEventTask', 'Create Event', 'VTCreateEventTask', 'modules/com_ncrm_workflow/tasks/VTCreateEventTask.inc', 'com_ncrm_workflow/taskforms/VTCreateEventTask.tpl', '{"include":["Leads","Accounts","Potentials","Contacts","HelpDesk","Campaigns"],"exclude":["Calendar","FAQ","Events"]}', ''),
	(5, 'VTUpdateFieldsTask', 'Update Fields', 'VTUpdateFieldsTask', 'modules/com_ncrm_workflow/tasks/VTUpdateFieldsTask.inc', 'com_ncrm_workflow/taskforms/VTUpdateFieldsTask.tpl', '{"include":[],"exclude":[]}', ''),
	(6, 'VTCreateEntityTask', 'Create Entity', 'VTCreateEntityTask', 'modules/com_ncrm_workflow/tasks/VTCreateEntityTask.inc', 'com_ncrm_workflow/taskforms/VTCreateEntityTask.tpl', '{"include":[],"exclude":[]}', ''),
	(7, 'VTSMSTask', 'SMS Task', 'VTSMSTask', 'modules/com_ncrm_workflow/tasks/VTSMSTask.inc', 'com_ncrm_workflow/taskforms/VTSMSTask.tpl', '{"include":[],"exclude":[]}', 'SMSNotifier');
/*!40000 ALTER TABLE `com_ncrm_workflow_tasktypes` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.com_ncrm_workflow_tasktypes_seq
CREATE TABLE IF NOT EXISTS `com_ncrm_workflow_tasktypes_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.com_ncrm_workflow_tasktypes_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `com_ncrm_workflow_tasktypes_seq` DISABLE KEYS */;
INSERT INTO `com_ncrm_workflow_tasktypes_seq` (`id`) VALUES
	(7);
/*!40000 ALTER TABLE `com_ncrm_workflow_tasktypes_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_account
CREATE TABLE IF NOT EXISTS `ncrm_account` (
  `accountid` int(19) NOT NULL DEFAULT '0',
  `account_no` varchar(100) NOT NULL,
  `accountname` varchar(100) NOT NULL,
  `parentid` int(19) DEFAULT '0',
  `account_type` varchar(200) DEFAULT NULL,
  `industry` varchar(200) DEFAULT NULL,
  `annualrevenue` decimal(25,8) DEFAULT NULL,
  `rating` varchar(200) DEFAULT NULL,
  `ownership` varchar(50) DEFAULT NULL,
  `siccode` varchar(50) DEFAULT NULL,
  `tickersymbol` varchar(30) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `otherphone` varchar(30) DEFAULT NULL,
  `email1` varchar(100) DEFAULT NULL,
  `email2` varchar(100) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `fax` varchar(30) DEFAULT NULL,
  `employees` int(10) DEFAULT '0',
  `emailoptout` varchar(3) DEFAULT '0',
  `notify_owner` varchar(3) DEFAULT '0',
  `isconvertedfromlead` varchar(3) DEFAULT '0',
  PRIMARY KEY (`accountid`),
  KEY `account_account_type_idx` (`account_type`),
  KEY `email_idx` (`email1`,`email2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_account: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_account` DISABLE KEYS */;
INSERT INTO `ncrm_account` (`accountid`, `account_no`, `accountname`, `parentid`, `account_type`, `industry`, `annualrevenue`, `rating`, `ownership`, `siccode`, `tickersymbol`, `phone`, `otherphone`, `email1`, `email2`, `website`, `fax`, `employees`, `emailoptout`, `notify_owner`, `isconvertedfromlead`) VALUES
	(14, 'ACC1', 'accc 1 accc 1 accc 1 accc 1 accc 1', 0, '', '', 0.00000000, '', '', '', 'accc 1', 'accc 1', '', '', '', 'http://google.com', '', 0, '0', '0', '0');
/*!40000 ALTER TABLE `ncrm_account` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_accountbillads
CREATE TABLE IF NOT EXISTS `ncrm_accountbillads` (
  `accountaddressid` int(19) NOT NULL DEFAULT '0',
  `bill_city` varchar(30) DEFAULT NULL,
  `bill_code` varchar(30) DEFAULT NULL,
  `bill_country` varchar(30) DEFAULT NULL,
  `bill_state` varchar(30) DEFAULT NULL,
  `bill_street` varchar(250) DEFAULT NULL,
  `bill_pobox` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`accountaddressid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_accountbillads: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_accountbillads` DISABLE KEYS */;
INSERT INTO `ncrm_accountbillads` (`accountaddressid`, `bill_city`, `bill_code`, `bill_country`, `bill_state`, `bill_street`, `bill_pobox`) VALUES
	(14, '', '', '', '', '', '');
/*!40000 ALTER TABLE `ncrm_accountbillads` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_accountrating
CREATE TABLE IF NOT EXISTS `ncrm_accountrating` (
  `accountratingid` int(19) NOT NULL AUTO_INCREMENT,
  `rating` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`accountratingid`),
  UNIQUE KEY `accountrating_rating_idx` (`rating`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_accountrating: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_accountrating` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_accountrating` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_accountscf
CREATE TABLE IF NOT EXISTS `ncrm_accountscf` (
  `accountid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`accountid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_accountscf: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_accountscf` DISABLE KEYS */;
INSERT INTO `ncrm_accountscf` (`accountid`) VALUES
	(14);
/*!40000 ALTER TABLE `ncrm_accountscf` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_accountshipads
CREATE TABLE IF NOT EXISTS `ncrm_accountshipads` (
  `accountaddressid` int(19) NOT NULL DEFAULT '0',
  `ship_city` varchar(30) DEFAULT NULL,
  `ship_code` varchar(30) DEFAULT NULL,
  `ship_country` varchar(30) DEFAULT NULL,
  `ship_state` varchar(30) DEFAULT NULL,
  `ship_pobox` varchar(30) DEFAULT NULL,
  `ship_street` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`accountaddressid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_accountshipads: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_accountshipads` DISABLE KEYS */;
INSERT INTO `ncrm_accountshipads` (`accountaddressid`, `ship_city`, `ship_code`, `ship_country`, `ship_state`, `ship_pobox`, `ship_street`) VALUES
	(14, '', '', '', '', '', '');
/*!40000 ALTER TABLE `ncrm_accountshipads` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_accounttype
CREATE TABLE IF NOT EXISTS `ncrm_accounttype` (
  `accounttypeid` int(19) NOT NULL AUTO_INCREMENT,
  `accounttype` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  PRIMARY KEY (`accounttypeid`),
  UNIQUE KEY `accounttype_accounttype_idx` (`accounttype`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_accounttype: ~10 rows (approximately)
/*!40000 ALTER TABLE `ncrm_accounttype` DISABLE KEYS */;
INSERT INTO `ncrm_accounttype` (`accounttypeid`, `accounttype`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(2, 'Analyst', 1, 2, 1),
	(3, 'Competitor', 1, 3, 2),
	(4, 'Customer', 1, 4, 3),
	(5, 'Integrator', 1, 5, 4),
	(6, 'Investor', 1, 6, 5),
	(7, 'Partner', 1, 7, 6),
	(8, 'Press', 1, 8, 7),
	(9, 'Prospect', 1, 9, 8),
	(10, 'Reseller', 1, 10, 9),
	(11, 'Other', 1, 11, 10);
/*!40000 ALTER TABLE `ncrm_accounttype` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_accounttype_seq
CREATE TABLE IF NOT EXISTS `ncrm_accounttype_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_accounttype_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_accounttype_seq` DISABLE KEYS */;
INSERT INTO `ncrm_accounttype_seq` (`id`) VALUES
	(11);
/*!40000 ALTER TABLE `ncrm_accounttype_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_actionmapping
CREATE TABLE IF NOT EXISTS `ncrm_actionmapping` (
  `actionid` int(19) NOT NULL,
  `actionname` varchar(200) NOT NULL,
  `securitycheck` int(19) DEFAULT NULL,
  PRIMARY KEY (`actionid`,`actionname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_actionmapping: ~23 rows (approximately)
/*!40000 ALTER TABLE `ncrm_actionmapping` DISABLE KEYS */;
INSERT INTO `ncrm_actionmapping` (`actionid`, `actionname`, `securitycheck`) VALUES
	(0, 'Save', 0),
	(0, 'SavePriceBook', 1),
	(0, 'SaveVendor', 1),
	(1, 'DetailViewAjax', 1),
	(1, 'EditView', 0),
	(1, 'PriceBookEditView', 1),
	(1, 'QuickCreate', 1),
	(1, 'VendorEditView', 1),
	(2, 'Delete', 0),
	(2, 'DeletePriceBook', 1),
	(2, 'DeleteVendor', 1),
	(3, 'index', 0),
	(3, 'Popup', 1),
	(4, 'DetailView', 0),
	(4, 'PriceBookDetailView', 1),
	(4, 'TagCloud', 1),
	(4, 'VendorDetailView', 1),
	(5, 'Import', 0),
	(6, 'Export', 0),
	(8, 'Merge', 0),
	(9, 'ConvertLead', 0),
	(10, 'DuplicatesHandling', 0),
	(13, 'Print', 0);
/*!40000 ALTER TABLE `ncrm_actionmapping` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_activity
CREATE TABLE IF NOT EXISTS `ncrm_activity` (
  `activityid` int(19) NOT NULL DEFAULT '0',
  `subject` varchar(100) NOT NULL,
  `semodule` varchar(20) DEFAULT NULL,
  `activitytype` varchar(200) NOT NULL,
  `date_start` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `time_start` varchar(50) DEFAULT NULL,
  `time_end` varchar(50) DEFAULT NULL,
  `sendnotification` varchar(3) NOT NULL DEFAULT '0',
  `duration_hours` varchar(200) DEFAULT NULL,
  `duration_minutes` varchar(200) DEFAULT NULL,
  `status` varchar(200) DEFAULT NULL,
  `eventstatus` varchar(200) DEFAULT NULL,
  `priority` varchar(200) DEFAULT NULL,
  `location` varchar(150) DEFAULT NULL,
  `notime` varchar(3) NOT NULL DEFAULT '0',
  `visibility` varchar(50) NOT NULL DEFAULT 'all',
  `recurringtype` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`activityid`),
  KEY `activity_activityid_subject_idx` (`activityid`,`subject`),
  KEY `activity_activitytype_date_start_idx` (`activitytype`,`date_start`),
  KEY `activity_date_start_due_date_idx` (`date_start`,`due_date`),
  KEY `activity_date_start_time_start_idx` (`date_start`,`time_start`),
  KEY `activity_eventstatus_idx` (`eventstatus`),
  KEY `activity_status_idx` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_activity: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_activity` DISABLE KEYS */;
INSERT INTO `ncrm_activity` (`activityid`, `subject`, `semodule`, `activitytype`, `date_start`, `due_date`, `time_start`, `time_end`, `sendnotification`, `duration_hours`, `duration_minutes`, `status`, `eventstatus`, `priority`, `location`, `notime`, `visibility`, `recurringtype`) VALUES
	(20, 'Event today 1', NULL, 'Call', '2017-01-10', '2017-01-10', '09:54:00', '10:14:00', '0', '0', '5', NULL, 'Planned', '', '', '0', 'Public', '');
/*!40000 ALTER TABLE `ncrm_activity` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_activitycf
CREATE TABLE IF NOT EXISTS `ncrm_activitycf` (
  `activityid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`activityid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_activitycf: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_activitycf` DISABLE KEYS */;
INSERT INTO `ncrm_activitycf` (`activityid`) VALUES
	(20);
/*!40000 ALTER TABLE `ncrm_activitycf` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_activityproductrel
CREATE TABLE IF NOT EXISTS `ncrm_activityproductrel` (
  `activityid` int(19) NOT NULL DEFAULT '0',
  `productid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`activityid`,`productid`),
  KEY `activityproductrel_activityid_idx` (`activityid`),
  KEY `activityproductrel_productid_idx` (`productid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_activityproductrel: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_activityproductrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_activityproductrel` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_activitytype
CREATE TABLE IF NOT EXISTS `ncrm_activitytype` (
  `activitytypeid` int(19) NOT NULL AUTO_INCREMENT,
  `activitytype` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  PRIMARY KEY (`activitytypeid`),
  UNIQUE KEY `activitytype_activitytype_idx` (`activitytype`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_activitytype: ~3 rows (approximately)
/*!40000 ALTER TABLE `ncrm_activitytype` DISABLE KEYS */;
INSERT INTO `ncrm_activitytype` (`activitytypeid`, `activitytype`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(1, 'Call', 0, 12, 0),
	(2, 'Meeting', 0, 13, 1),
	(3, 'Mobile Call', 0, 295, 1);
/*!40000 ALTER TABLE `ncrm_activitytype` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_activitytype_seq
CREATE TABLE IF NOT EXISTS `ncrm_activitytype_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_activitytype_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_activitytype_seq` DISABLE KEYS */;
INSERT INTO `ncrm_activitytype_seq` (`id`) VALUES
	(3);
/*!40000 ALTER TABLE `ncrm_activitytype_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_activity_reminder
CREATE TABLE IF NOT EXISTS `ncrm_activity_reminder` (
  `activity_id` int(11) NOT NULL,
  `reminder_time` int(11) NOT NULL,
  `reminder_sent` int(2) NOT NULL,
  `recurringid` int(19) NOT NULL,
  PRIMARY KEY (`activity_id`,`recurringid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_activity_reminder: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_activity_reminder` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_activity_reminder` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_activity_reminder_popup
CREATE TABLE IF NOT EXISTS `ncrm_activity_reminder_popup` (
  `reminderid` int(19) NOT NULL AUTO_INCREMENT,
  `semodule` varchar(100) NOT NULL,
  `recordid` int(19) NOT NULL,
  `date_start` date NOT NULL,
  `time_start` varchar(100) NOT NULL,
  `status` int(2) NOT NULL,
  PRIMARY KEY (`reminderid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_activity_reminder_popup: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_activity_reminder_popup` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_activity_reminder_popup` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_activity_view
CREATE TABLE IF NOT EXISTS `ncrm_activity_view` (
  `activity_viewid` int(19) NOT NULL AUTO_INCREMENT,
  `activity_view` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`activity_viewid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_activity_view: ~4 rows (approximately)
/*!40000 ALTER TABLE `ncrm_activity_view` DISABLE KEYS */;
INSERT INTO `ncrm_activity_view` (`activity_viewid`, `activity_view`, `sortorderid`, `presence`) VALUES
	(1, 'Today', 0, 1),
	(2, 'This Week', 1, 1),
	(3, 'This Month', 2, 1),
	(4, 'This Year', 3, 1);
/*!40000 ALTER TABLE `ncrm_activity_view` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_activity_view_seq
CREATE TABLE IF NOT EXISTS `ncrm_activity_view_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_activity_view_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_activity_view_seq` DISABLE KEYS */;
INSERT INTO `ncrm_activity_view_seq` (`id`) VALUES
	(4);
/*!40000 ALTER TABLE `ncrm_activity_view_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_announcement
CREATE TABLE IF NOT EXISTS `ncrm_announcement` (
  `creatorid` int(19) NOT NULL,
  `announcement` text,
  `title` varchar(255) DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`creatorid`),
  KEY `announcement_creatorid_idx` (`creatorid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_announcement: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_announcement` DISABLE KEYS */;
INSERT INTO `ncrm_announcement` (`creatorid`, `announcement`, `title`, `time`) VALUES
	(1, 'test', 'announcement', '2017-01-09 17:00:28');
/*!40000 ALTER TABLE `ncrm_announcement` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_assets
CREATE TABLE IF NOT EXISTS `ncrm_assets` (
  `assetsid` int(11) NOT NULL,
  `asset_no` varchar(30) NOT NULL,
  `account` int(19) DEFAULT NULL,
  `product` int(19) NOT NULL,
  `serialnumber` varchar(200) DEFAULT NULL,
  `datesold` date DEFAULT NULL,
  `dateinservice` date DEFAULT NULL,
  `assetstatus` varchar(200) DEFAULT 'In Service',
  `tagnumber` varchar(300) DEFAULT NULL,
  `invoiceid` int(19) DEFAULT NULL,
  `shippingmethod` varchar(200) DEFAULT NULL,
  `shippingtrackingnumber` varchar(200) DEFAULT NULL,
  `assetname` varchar(100) DEFAULT NULL,
  `contact` int(19) DEFAULT NULL,
  PRIMARY KEY (`assetsid`),
  CONSTRAINT `fk_1_ncrm_assets` FOREIGN KEY (`assetsid`) REFERENCES `ncrm_crmentity` (`crmid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_assets: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_assets` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_assets` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_assetscf
CREATE TABLE IF NOT EXISTS `ncrm_assetscf` (
  `assetsid` int(19) NOT NULL,
  PRIMARY KEY (`assetsid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_assetscf: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_assetscf` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_assetscf` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_assetstatus
CREATE TABLE IF NOT EXISTS `ncrm_assetstatus` (
  `assetstatusid` int(11) NOT NULL AUTO_INCREMENT,
  `assetstatus` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(11) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT '0',
  PRIMARY KEY (`assetstatusid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_assetstatus: ~2 rows (approximately)
/*!40000 ALTER TABLE `ncrm_assetstatus` DISABLE KEYS */;
INSERT INTO `ncrm_assetstatus` (`assetstatusid`, `assetstatus`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(1, 'In Service', 1, 235, 1),
	(2, 'Out-of-service', 1, 236, 2);
/*!40000 ALTER TABLE `ncrm_assetstatus` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_assetstatus_seq
CREATE TABLE IF NOT EXISTS `ncrm_assetstatus_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_assetstatus_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_assetstatus_seq` DISABLE KEYS */;
INSERT INTO `ncrm_assetstatus_seq` (`id`) VALUES
	(2);
/*!40000 ALTER TABLE `ncrm_assetstatus_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_asterisk
CREATE TABLE IF NOT EXISTS `ncrm_asterisk` (
  `server` varchar(30) DEFAULT NULL,
  `port` varchar(30) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `version` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_asterisk: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_asterisk` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_asterisk` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_asteriskextensions
CREATE TABLE IF NOT EXISTS `ncrm_asteriskextensions` (
  `userid` int(11) DEFAULT NULL,
  `asterisk_extension` varchar(50) DEFAULT NULL,
  `use_asterisk` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_asteriskextensions: ~2 rows (approximately)
/*!40000 ALTER TABLE `ncrm_asteriskextensions` DISABLE KEYS */;
INSERT INTO `ncrm_asteriskextensions` (`userid`, `asterisk_extension`, `use_asterisk`) VALUES
	(1, NULL, NULL),
	(5, NULL, NULL),
	(6, NULL, NULL);
/*!40000 ALTER TABLE `ncrm_asteriskextensions` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_asteriskincomingcalls
CREATE TABLE IF NOT EXISTS `ncrm_asteriskincomingcalls` (
  `from_number` varchar(50) DEFAULT NULL,
  `from_name` varchar(50) DEFAULT NULL,
  `to_number` varchar(50) DEFAULT NULL,
  `callertype` varchar(30) DEFAULT NULL,
  `flag` int(19) DEFAULT NULL,
  `timer` int(19) DEFAULT NULL,
  `refuid` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_asteriskincomingcalls: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_asteriskincomingcalls` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_asteriskincomingcalls` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_asteriskincomingevents
CREATE TABLE IF NOT EXISTS `ncrm_asteriskincomingevents` (
  `uid` varchar(255) NOT NULL,
  `channel` varchar(100) DEFAULT NULL,
  `from_number` bigint(20) DEFAULT NULL,
  `from_name` varchar(100) DEFAULT NULL,
  `to_number` bigint(20) DEFAULT NULL,
  `callertype` varchar(100) DEFAULT NULL,
  `timer` int(20) DEFAULT NULL,
  `flag` varchar(3) DEFAULT NULL,
  `pbxrecordid` int(19) DEFAULT NULL,
  `relcrmid` int(19) DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_asteriskincomingevents: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_asteriskincomingevents` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_asteriskincomingevents` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_attachments
CREATE TABLE IF NOT EXISTS `ncrm_attachments` (
  `attachmentsid` int(19) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `type` varchar(100) DEFAULT NULL,
  `path` text,
  `subject` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`attachmentsid`),
  KEY `attachments_attachmentsid_idx` (`attachmentsid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_attachments: ~3 rows (approximately)
/*!40000 ALTER TABLE `ncrm_attachments` DISABLE KEYS */;
INSERT INTO `ncrm_attachments` (`attachmentsid`, `name`, `description`, `type`, `path`, `subject`) VALUES
	(12, '^116CFFD6CECB59B96BFECEC6F64AB027824828452999340CA3^pimgpsh_fullsize_distr.jpg', '', 'image/jpeg', 'storage/2017/January/week2/', NULL),
	(21, 'Mens-Short-Hairstyles-2014-44.jpg', '', 'image/jpeg', 'storage/2017/October/week4/', NULL),
	(27, '2017-07-27_111637.png', NULL, 'image/png', 'storage/2017/October/week5/', NULL),
	(30, '23561767_1744470048898467_8006303657467507012_n.jpg', '', 'image/jpeg', 'storage/2017/November/week4/', NULL);
/*!40000 ALTER TABLE `ncrm_attachments` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_attachmentsfolder
CREATE TABLE IF NOT EXISTS `ncrm_attachmentsfolder` (
  `folderid` int(19) NOT NULL AUTO_INCREMENT,
  `foldername` varchar(200) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `createdby` int(19) NOT NULL,
  `sequence` int(19) DEFAULT NULL,
  PRIMARY KEY (`folderid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_attachmentsfolder: ~2 rows (approximately)
/*!40000 ALTER TABLE `ncrm_attachmentsfolder` DISABLE KEYS */;
INSERT INTO `ncrm_attachmentsfolder` (`folderid`, `foldername`, `description`, `createdby`, `sequence`) VALUES
	(1, 'Default', 'This is a Default Folder', 1, 1),
	(2, 'Content', '', 1, 2);
/*!40000 ALTER TABLE `ncrm_attachmentsfolder` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_attachmentsfolder_seq
CREATE TABLE IF NOT EXISTS `ncrm_attachmentsfolder_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_attachmentsfolder_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_attachmentsfolder_seq` DISABLE KEYS */;
INSERT INTO `ncrm_attachmentsfolder_seq` (`id`) VALUES
	(1);
/*!40000 ALTER TABLE `ncrm_attachmentsfolder_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_audit_trial
CREATE TABLE IF NOT EXISTS `ncrm_audit_trial` (
  `auditid` int(19) NOT NULL,
  `userid` int(19) DEFAULT NULL,
  `module` varchar(255) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `recordid` varchar(20) DEFAULT NULL,
  `actiondate` datetime DEFAULT NULL,
  PRIMARY KEY (`auditid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_audit_trial: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_audit_trial` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_audit_trial` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_blocks
CREATE TABLE IF NOT EXISTS `ncrm_blocks` (
  `blockid` int(19) NOT NULL,
  `tabid` int(19) NOT NULL,
  `blocklabel` varchar(100) NOT NULL,
  `sequence` int(10) DEFAULT NULL,
  `show_title` int(2) DEFAULT NULL,
  `visible` int(2) NOT NULL DEFAULT '0',
  `create_view` int(2) NOT NULL DEFAULT '0',
  `edit_view` int(2) NOT NULL DEFAULT '0',
  `detail_view` int(2) NOT NULL DEFAULT '0',
  `display_status` int(1) NOT NULL DEFAULT '1',
  `iscustom` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`blockid`),
  KEY `block_tabid_idx` (`tabid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_blocks: ~119 rows (approximately)
/*!40000 ALTER TABLE `ncrm_blocks` DISABLE KEYS */;
INSERT INTO `ncrm_blocks` (`blockid`, `tabid`, `blocklabel`, `sequence`, `show_title`, `visible`, `create_view`, `edit_view`, `detail_view`, `display_status`, `iscustom`) VALUES
	(1, 2, 'LBL_OPPORTUNITY_INFORMATION', 1, 0, 0, 0, 0, 0, 1, 0),
	(2, 2, 'LBL_CUSTOM_INFORMATION', 2, 0, 0, 0, 0, 0, 1, 0),
	(3, 2, 'LBL_DESCRIPTION_INFORMATION', 3, 0, 0, 0, 0, 0, 1, 0),
	(4, 4, 'LBL_CONTACT_INFORMATION', 1, 0, 0, 0, 0, 0, 1, 0),
	(5, 4, 'LBL_CUSTOM_INFORMATION', 4, 0, 0, 0, 0, 0, 1, 0),
	(6, 4, 'LBL_CUSTOMER_PORTAL_INFORMATION', 5, 0, 0, 0, 0, 0, 1, 0),
	(7, 4, 'LBL_ADDRESS_INFORMATION', 6, 0, 0, 0, 0, 0, 1, 0),
	(8, 4, 'LBL_DESCRIPTION_INFORMATION', 7, 0, 0, 0, 0, 0, 1, 0),
	(9, 6, 'LBL_ACCOUNT_INFORMATION', 1, 0, 0, 0, 0, 0, 1, 0),
	(10, 6, 'LBL_CUSTOM_INFORMATION', 2, 0, 0, 0, 0, 0, 1, 0),
	(11, 6, 'LBL_ADDRESS_INFORMATION', 3, 0, 0, 0, 0, 0, 1, 0),
	(12, 6, 'LBL_DESCRIPTION_INFORMATION', 4, 0, 0, 0, 0, 0, 1, 0),
	(13, 7, 'LBL_LEAD_INFORMATION', 1, 0, 0, 0, 0, 0, 1, 0),
	(14, 7, 'LBL_CUSTOM_INFORMATION', 2, 0, 0, 0, 0, 0, 1, 0),
	(15, 7, 'LBL_ADDRESS_INFORMATION', 3, 0, 0, 0, 0, 0, 1, 0),
	(16, 7, 'LBL_DESCRIPTION_INFORMATION', 4, 0, 0, 0, 0, 0, 1, 0),
	(17, 8, 'LBL_NOTE_INFORMATION', 1, 0, 0, 0, 0, 0, 1, 0),
	(18, 8, 'LBL_FILE_INFORMATION', 3, 1, 0, 0, 0, 0, 1, 0),
	(19, 9, 'LBL_TASK_INFORMATION', 1, 0, 0, 0, 0, 0, 1, 0),
	(20, 9, 'LBL_DESCRIPTION_INFORMATION', 2, 1, 0, 0, 0, 0, 1, 0),
	(21, 10, 'LBL_EMAIL_INFORMATION', 1, 0, 0, 0, 0, 0, 1, 0),
	(22, 10, 'Emails_Block1', 2, 1, 0, 0, 0, 0, 1, 0),
	(23, 10, 'Emails_Block2', 3, 1, 0, 0, 0, 0, 1, 0),
	(24, 10, 'Emails_Block3', 4, 1, 0, 0, 0, 0, 1, 0),
	(25, 13, 'LBL_TICKET_INFORMATION', 1, 0, 0, 0, 0, 0, 1, 0),
	(26, 13, '', 2, 1, 0, 0, 0, 0, 1, 0),
	(27, 13, 'LBL_CUSTOM_INFORMATION', 3, 0, 0, 0, 0, 0, 1, 0),
	(28, 13, 'LBL_DESCRIPTION_INFORMATION', 4, 0, 0, 0, 0, 0, 1, 0),
	(29, 13, 'LBL_TICKET_RESOLUTION', 5, 0, 0, 1, 0, 0, 1, 0),
	(30, 13, 'LBL_COMMENTS', 6, 0, 0, 1, 0, 0, 1, 0),
	(31, 14, 'LBL_PRODUCT_INFORMATION', 1, 0, 0, 0, 0, 0, 1, 0),
	(32, 14, 'LBL_PRICING_INFORMATION', 2, 0, 0, 0, 0, 0, 1, 0),
	(33, 14, 'LBL_STOCK_INFORMATION', 3, 0, 0, 0, 0, 0, 1, 0),
	(34, 14, 'LBL_CUSTOM_INFORMATION', 4, 0, 0, 0, 0, 0, 1, 0),
	(35, 14, 'LBL_IMAGE_INFORMATION', 5, 0, 0, 0, 0, 0, 1, 0),
	(36, 14, 'LBL_DESCRIPTION_INFORMATION', 6, 0, 0, 0, 0, 0, 1, 0),
	(37, 15, 'LBL_FAQ_INFORMATION', 1, 0, 0, 0, 0, 0, 1, 0),
	(38, 15, 'LBL_COMMENT_INFORMATION', 4, 0, 0, 1, 0, 0, 1, 0),
	(39, 16, 'LBL_EVENT_INFORMATION', 1, 0, 0, 0, 0, 0, 1, 0),
	(40, 16, 'LBL_REMINDER_INFORMATION', 2, 0, 0, 0, 0, 0, 1, 0),
	(41, 16, 'LBL_DESCRIPTION_INFORMATION', 5, 0, 0, 0, 0, 0, 1, 0),
	(42, 18, 'LBL_VENDOR_INFORMATION', 1, 0, 0, 0, 0, 0, 1, 0),
	(43, 18, 'LBL_CUSTOM_INFORMATION', 2, 0, 0, 0, 0, 0, 1, 0),
	(44, 18, 'LBL_VENDOR_ADDRESS_INFORMATION', 3, 0, 0, 0, 0, 0, 1, 0),
	(45, 18, 'LBL_DESCRIPTION_INFORMATION', 4, 0, 0, 0, 0, 0, 1, 0),
	(46, 19, 'LBL_PRICEBOOK_INFORMATION', 1, 0, 0, 0, 0, 0, 1, 0),
	(47, 19, 'LBL_CUSTOM_INFORMATION', 2, 0, 0, 0, 0, 0, 1, 0),
	(48, 19, 'LBL_DESCRIPTION_INFORMATION', 3, 0, 0, 0, 0, 0, 1, 0),
	(49, 20, 'LBL_QUOTE_INFORMATION', 1, 0, 0, 0, 0, 0, 1, 0),
	(50, 20, 'LBL_CUSTOM_INFORMATION', 2, 0, 0, 0, 0, 0, 1, 0),
	(51, 20, 'LBL_ADDRESS_INFORMATION', 3, 0, 0, 0, 0, 0, 1, 0),
	(52, 20, 'LBL_RELATED_PRODUCTS', 4, 0, 0, 0, 0, 0, 1, 0),
	(53, 20, 'LBL_TERMS_INFORMATION', 5, 0, 0, 0, 0, 0, 1, 0),
	(54, 20, 'LBL_DESCRIPTION_INFORMATION', 6, 0, 0, 0, 0, 0, 1, 0),
	(55, 21, 'LBL_PO_INFORMATION', 1, 0, 0, 0, 0, 0, 1, 0),
	(56, 21, 'LBL_CUSTOM_INFORMATION', 2, 0, 0, 0, 0, 0, 1, 0),
	(57, 21, 'LBL_ADDRESS_INFORMATION', 3, 0, 0, 0, 0, 0, 1, 0),
	(58, 21, 'LBL_RELATED_PRODUCTS', 4, 0, 0, 0, 0, 0, 1, 0),
	(59, 21, 'LBL_TERMS_INFORMATION', 5, 0, 0, 0, 0, 0, 1, 0),
	(60, 21, 'LBL_DESCRIPTION_INFORMATION', 6, 0, 0, 0, 0, 0, 1, 0),
	(61, 22, 'LBL_SO_INFORMATION', 1, 0, 0, 0, 0, 0, 1, 0),
	(62, 22, 'LBL_CUSTOM_INFORMATION', 3, 0, 0, 0, 0, 0, 1, 0),
	(63, 22, 'LBL_ADDRESS_INFORMATION', 4, 0, 0, 0, 0, 0, 1, 0),
	(64, 22, 'LBL_RELATED_PRODUCTS', 5, 0, 0, 0, 0, 0, 1, 0),
	(65, 22, 'LBL_TERMS_INFORMATION', 6, 0, 0, 0, 0, 0, 1, 0),
	(66, 22, 'LBL_DESCRIPTION_INFORMATION', 7, 0, 0, 0, 0, 0, 1, 0),
	(67, 23, 'LBL_INVOICE_INFORMATION', 1, 0, 0, 0, 0, 0, 1, 0),
	(68, 23, 'LBL_CUSTOM_INFORMATION', 2, 0, 0, 0, 0, 0, 1, 0),
	(69, 23, 'LBL_ADDRESS_INFORMATION', 3, 0, 0, 0, 0, 0, 1, 0),
	(70, 23, 'LBL_RELATED_PRODUCTS', 4, 0, 0, 0, 0, 0, 1, 0),
	(71, 23, 'LBL_TERMS_INFORMATION', 5, 0, 0, 0, 0, 0, 1, 0),
	(72, 23, 'LBL_DESCRIPTION_INFORMATION', 6, 0, 0, 0, 0, 0, 1, 0),
	(73, 4, 'LBL_IMAGE_INFORMATION', 8, 0, 0, 0, 0, 0, 1, 0),
	(74, 26, 'LBL_CAMPAIGN_INFORMATION', 1, 0, 0, 0, 0, 0, 1, 0),
	(75, 26, 'LBL_CUSTOM_INFORMATION', 2, 0, 0, 0, 0, 0, 1, 0),
	(76, 26, 'LBL_EXPECTATIONS_AND_ACTUALS', 3, 0, 0, 0, 0, 0, 1, 0),
	(77, 29, 'LBL_USERLOGIN_ROLE', 1, 0, 0, 0, 0, 0, 1, 0),
	(78, 29, 'LBL_CURRENCY_CONFIGURATION', 3, 0, 0, 0, 0, 0, 1, 0),
	(79, 29, 'LBL_MORE_INFORMATION', 4, 0, 0, 0, 0, 0, 1, 0),
	(80, 29, 'LBL_ADDRESS_INFORMATION', 5, 0, 0, 0, 0, 0, 1, 0),
	(81, 26, 'LBL_DESCRIPTION_INFORMATION', 4, 0, 0, 0, 0, 0, 1, 0),
	(82, 29, 'LBL_USER_IMAGE_INFORMATION', 5, 0, 0, 0, 0, 0, 1, 0),
	(83, 29, 'LBL_USER_ADV_OPTIONS', 6, 0, 0, 0, 0, 0, 1, 0),
	(84, 8, 'LBL_DESCRIPTION', 2, 0, 0, 0, 0, 0, 1, 0),
	(85, 22, 'Recurring Invoice Information', 2, 0, 0, 0, 0, 0, 1, 0),
	(86, 9, 'LBL_CUSTOM_INFORMATION', 3, 0, 0, 0, 0, 0, 1, 0),
	(87, 16, 'LBL_CUSTOM_INFORMATION', 6, 0, 0, 0, 0, 0, 1, 0),
	(88, 34, 'LBL_PBXMANAGER_INFORMATION', 1, 0, 0, 0, 0, 0, 1, 0),
	(89, 35, 'LBL_SERVICE_CONTRACT_INFORMATION', 1, 0, 0, 0, 0, 0, 1, 0),
	(90, 35, 'LBL_CUSTOM_INFORMATION', 2, 0, 0, 0, 0, 0, 1, 0),
	(91, 36, 'LBL_SERVICE_INFORMATION', 1, 0, 0, 0, 0, 0, 1, 0),
	(92, 36, 'LBL_PRICING_INFORMATION', 2, 0, 0, 0, 0, 0, 1, 0),
	(93, 36, 'LBL_CUSTOM_INFORMATION', 3, 0, 0, 0, 0, 0, 1, 0),
	(94, 36, 'LBL_DESCRIPTION_INFORMATION', 4, 0, 0, 0, 0, 0, 1, 0),
	(95, 38, 'LBL_ASSET_INFORMATION', 1, 0, 0, 0, 0, 0, 1, 0),
	(96, 38, 'LBL_CUSTOM_INFORMATION', 2, 0, 0, 0, 0, 0, 1, 0),
	(97, 38, 'LBL_DESCRIPTION_INFORMATION', 3, 0, 0, 0, 0, 0, 1, 0),
	(98, 42, 'LBL_MODCOMMENTS_INFORMATION', 1, 0, 0, 0, 0, 0, 1, 0),
	(99, 42, 'LBL_OTHER_INFORMATION', 2, 0, 0, 0, 0, 0, 1, 0),
	(100, 42, 'LBL_CUSTOM_INFORMATION', 3, 0, 0, 0, 0, 0, 1, 0),
	(101, 43, 'LBL_PROJECT_MILESTONE_INFORMATION', 1, 0, 0, 0, 0, 0, 1, 0),
	(102, 43, 'LBL_CUSTOM_INFORMATION', 2, 0, 0, 0, 0, 0, 1, 0),
	(103, 43, 'LBL_DESCRIPTION_INFORMATION', 3, 0, 0, 0, 0, 0, 1, 0),
	(104, 44, 'LBL_PROJECT_TASK_INFORMATION', 1, 0, 0, 0, 0, 0, 1, 0),
	(105, 44, 'LBL_CUSTOM_INFORMATION', 2, 0, 0, 0, 0, 0, 1, 0),
	(106, 44, 'LBL_DESCRIPTION_INFORMATION', 3, 0, 0, 0, 0, 0, 1, 0),
	(107, 45, 'LBL_PROJECT_INFORMATION', 1, 0, 0, 0, 0, 0, 1, 0),
	(108, 45, 'LBL_CUSTOM_INFORMATION', 2, 0, 0, 0, 0, 0, 1, 0),
	(109, 45, 'LBL_DESCRIPTION_INFORMATION', 3, 0, 0, 0, 0, 0, 1, 0),
	(110, 47, 'LBL_SMSNOTIFIER_INFORMATION', 1, 0, 0, 0, 0, 0, 1, 0),
	(111, 47, 'LBL_CUSTOM_INFORMATION', 2, 0, 0, 0, 0, 0, 1, 0),
	(112, 47, 'StatusInformation', 3, 0, 0, 0, 0, 0, 1, 0),
	(113, 23, 'LBL_ITEM_DETAILS', 5, 0, 0, 0, 0, 0, 1, 0),
	(114, 22, 'LBL_ITEM_DETAILS', 5, 0, 0, 0, 0, 0, 1, 0),
	(115, 21, 'LBL_ITEM_DETAILS', 5, 0, 0, 0, 0, 0, 1, 0),
	(116, 20, 'LBL_ITEM_DETAILS', 5, 0, 0, 0, 0, 0, 1, 0),
	(117, 16, 'LBL_RECURRENCE_INFORMATION', 3, 0, 0, 0, 0, 0, 1, 0),
	(118, 29, 'LBL_CALENDAR_SETTINGS', 2, 0, 0, 0, 0, 0, 1, 0),
	(119, 16, 'LBL_RELATED_TO', 4, 0, 0, 0, 0, 0, 1, 0),
	(121, 4, 'Tracking data', 3, 0, 0, 0, 0, 0, 1, 1);
/*!40000 ALTER TABLE `ncrm_blocks` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_blocks_seq
CREATE TABLE IF NOT EXISTS `ncrm_blocks_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_blocks_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_blocks_seq` DISABLE KEYS */;
INSERT INTO `ncrm_blocks_seq` (`id`) VALUES
	(121);
/*!40000 ALTER TABLE `ncrm_blocks_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_calendarsharedtype
CREATE TABLE IF NOT EXISTS `ncrm_calendarsharedtype` (
  `calendarsharedtypeid` int(11) NOT NULL AUTO_INCREMENT,
  `calendarsharedtype` varchar(200) NOT NULL,
  `sortorderid` int(11) DEFAULT NULL,
  `presence` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`calendarsharedtypeid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_calendarsharedtype: ~3 rows (approximately)
/*!40000 ALTER TABLE `ncrm_calendarsharedtype` DISABLE KEYS */;
INSERT INTO `ncrm_calendarsharedtype` (`calendarsharedtypeid`, `calendarsharedtype`, `sortorderid`, `presence`) VALUES
	(1, 'public', 1, 1),
	(2, 'private', 2, 1),
	(3, 'seletedusers', 3, 1);
/*!40000 ALTER TABLE `ncrm_calendarsharedtype` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_calendarsharedtype_seq
CREATE TABLE IF NOT EXISTS `ncrm_calendarsharedtype_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_calendarsharedtype_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_calendarsharedtype_seq` DISABLE KEYS */;
INSERT INTO `ncrm_calendarsharedtype_seq` (`id`) VALUES
	(3);
/*!40000 ALTER TABLE `ncrm_calendarsharedtype_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_calendar_default_activitytypes
CREATE TABLE IF NOT EXISTS `ncrm_calendar_default_activitytypes` (
  `id` int(19) NOT NULL,
  `module` varchar(50) DEFAULT NULL,
  `fieldname` varchar(50) DEFAULT NULL,
  `defaultcolor` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_calendar_default_activitytypes: ~8 rows (approximately)
/*!40000 ALTER TABLE `ncrm_calendar_default_activitytypes` DISABLE KEYS */;
INSERT INTO `ncrm_calendar_default_activitytypes` (`id`, `module`, `fieldname`, `defaultcolor`) VALUES
	(1, 'Events', 'Events', '#17309A'),
	(2, 'Calendar', 'Tasks', '#3A87AD'),
	(3, 'Potentials', 'Potentials', '#AA6705'),
	(4, 'Contacts', 'support_end_date', '#953B39'),
	(5, 'Contacts', 'birthday', '#545252'),
	(6, 'Invoice', 'Invoice', '#87865D'),
	(7, 'Project', 'Project', '#C71585'),
	(8, 'ProjectTask', 'Project Task', '#006400');
/*!40000 ALTER TABLE `ncrm_calendar_default_activitytypes` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_calendar_default_activitytypes_seq
CREATE TABLE IF NOT EXISTS `ncrm_calendar_default_activitytypes_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_calendar_default_activitytypes_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_calendar_default_activitytypes_seq` DISABLE KEYS */;
INSERT INTO `ncrm_calendar_default_activitytypes_seq` (`id`) VALUES
	(8);
/*!40000 ALTER TABLE `ncrm_calendar_default_activitytypes_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_calendar_user_activitytypes
CREATE TABLE IF NOT EXISTS `ncrm_calendar_user_activitytypes` (
  `id` int(19) NOT NULL,
  `defaultid` int(19) DEFAULT NULL,
  `userid` int(19) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `visible` int(19) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_calendar_user_activitytypes: ~16 rows (approximately)
/*!40000 ALTER TABLE `ncrm_calendar_user_activitytypes` DISABLE KEYS */;
INSERT INTO `ncrm_calendar_user_activitytypes` (`id`, `defaultid`, `userid`, `color`, `visible`) VALUES
	(1, 1, 1, '#17309A', 1),
	(2, 2, 1, '#3A87AD', 1),
	(3, 3, 1, '#AA6705', 1),
	(4, 4, 1, '#953B39', 1),
	(5, 5, 1, '#545252', 1),
	(6, 6, 1, '#87865D', 1),
	(7, 7, 1, '#C71585', 1),
	(8, 8, 1, '#006400', 1),
	(9, 1, 5, '#17309A', 1),
	(10, 2, 5, '#3A87AD', 1),
	(11, 3, 5, '#AA6705', 1),
	(12, 4, 5, '#953B39', 1),
	(13, 5, 5, '#545252', 1),
	(14, 6, 5, '#87865D', 1),
	(15, 7, 5, '#C71585', 1),
	(16, 8, 5, '#006400', 1),
	(17, 1, 6, '#17309A', 1),
	(18, 2, 6, '#3A87AD', 1),
	(19, 3, 6, '#AA6705', 1),
	(20, 4, 6, '#953B39', 1),
	(21, 5, 6, '#545252', 1),
	(22, 6, 6, '#87865D', 1),
	(23, 7, 6, '#C71585', 1),
	(24, 8, 6, '#006400', 1);
/*!40000 ALTER TABLE `ncrm_calendar_user_activitytypes` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_calendar_user_activitytypes_seq
CREATE TABLE IF NOT EXISTS `ncrm_calendar_user_activitytypes_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_calendar_user_activitytypes_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_calendar_user_activitytypes_seq` DISABLE KEYS */;
INSERT INTO `ncrm_calendar_user_activitytypes_seq` (`id`) VALUES
	(24);
/*!40000 ALTER TABLE `ncrm_calendar_user_activitytypes_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_callduration
CREATE TABLE IF NOT EXISTS `ncrm_callduration` (
  `calldurationid` int(11) NOT NULL AUTO_INCREMENT,
  `callduration` varchar(200) NOT NULL,
  `sortorderid` int(11) DEFAULT NULL,
  `presence` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`calldurationid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_callduration: ~5 rows (approximately)
/*!40000 ALTER TABLE `ncrm_callduration` DISABLE KEYS */;
INSERT INTO `ncrm_callduration` (`calldurationid`, `callduration`, `sortorderid`, `presence`) VALUES
	(1, '5', 1, 1),
	(2, '10', 2, 1),
	(3, '30', 3, 1),
	(4, '60', 4, 1),
	(5, '120', 5, 1);
/*!40000 ALTER TABLE `ncrm_callduration` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_callduration_seq
CREATE TABLE IF NOT EXISTS `ncrm_callduration_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_callduration_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_callduration_seq` DISABLE KEYS */;
INSERT INTO `ncrm_callduration_seq` (`id`) VALUES
	(5);
/*!40000 ALTER TABLE `ncrm_callduration_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_campaign
CREATE TABLE IF NOT EXISTS `ncrm_campaign` (
  `campaign_no` varchar(100) NOT NULL,
  `campaignname` varchar(255) DEFAULT NULL,
  `campaigntype` varchar(200) DEFAULT NULL,
  `campaignstatus` varchar(200) DEFAULT NULL,
  `expectedrevenue` decimal(25,8) DEFAULT NULL,
  `budgetcost` decimal(25,8) DEFAULT NULL,
  `actualcost` decimal(25,8) DEFAULT NULL,
  `expectedresponse` varchar(200) DEFAULT NULL,
  `numsent` decimal(11,0) DEFAULT NULL,
  `product_id` int(19) DEFAULT NULL,
  `sponsor` varchar(255) DEFAULT NULL,
  `targetaudience` varchar(255) DEFAULT NULL,
  `targetsize` int(19) DEFAULT NULL,
  `expectedresponsecount` int(19) DEFAULT NULL,
  `expectedsalescount` int(19) DEFAULT NULL,
  `expectedroi` decimal(25,8) DEFAULT NULL,
  `actualresponsecount` int(19) DEFAULT NULL,
  `actualsalescount` int(19) DEFAULT NULL,
  `actualroi` decimal(25,8) DEFAULT NULL,
  `campaignid` int(19) NOT NULL,
  `closingdate` date DEFAULT NULL,
  PRIMARY KEY (`campaignid`),
  KEY `campaign_campaignstatus_idx` (`campaignstatus`),
  KEY `campaign_campaignname_idx` (`campaignname`),
  KEY `campaign_campaignid_idx` (`campaignid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_campaign: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_campaign` DISABLE KEYS */;
INSERT INTO `ncrm_campaign` (`campaign_no`, `campaignname`, `campaigntype`, `campaignstatus`, `expectedrevenue`, `budgetcost`, `actualcost`, `expectedresponse`, `numsent`, `product_id`, `sponsor`, `targetaudience`, `targetsize`, `expectedresponsecount`, `expectedsalescount`, `expectedroi`, `actualresponsecount`, `actualsalescount`, `actualroi`, `campaignid`, `closingdate`) VALUES
	('CAM1', 'fb ad q1', '', 'Active', 0.00000000, 120.00000000, 0.00000000, 'Excellent', 0, 0, '', '', 0, 0, 0, 0.00000000, 0, 0, 0.00000000, 25, '2017-10-17');
/*!40000 ALTER TABLE `ncrm_campaign` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_campaignaccountrel
CREATE TABLE IF NOT EXISTS `ncrm_campaignaccountrel` (
  `campaignid` int(19) DEFAULT NULL,
  `accountid` int(19) DEFAULT NULL,
  `campaignrelstatusid` int(19) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_campaignaccountrel: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_campaignaccountrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_campaignaccountrel` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_campaigncontrel
CREATE TABLE IF NOT EXISTS `ncrm_campaigncontrel` (
  `campaignid` int(19) NOT NULL DEFAULT '0',
  `contactid` int(19) NOT NULL DEFAULT '0',
  `campaignrelstatusid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`campaignid`,`contactid`,`campaignrelstatusid`),
  KEY `campaigncontrel_contractid_idx` (`contactid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_campaigncontrel: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_campaigncontrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_campaigncontrel` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_campaignleadrel
CREATE TABLE IF NOT EXISTS `ncrm_campaignleadrel` (
  `campaignid` int(19) NOT NULL DEFAULT '0',
  `leadid` int(19) NOT NULL DEFAULT '0',
  `campaignrelstatusid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`campaignid`,`leadid`,`campaignrelstatusid`),
  KEY `campaignleadrel_leadid_campaignid_idx` (`leadid`,`campaignid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_campaignleadrel: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_campaignleadrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_campaignleadrel` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_campaignrelstatus
CREATE TABLE IF NOT EXISTS `ncrm_campaignrelstatus` (
  `campaignrelstatusid` int(19) DEFAULT NULL,
  `campaignrelstatus` varchar(256) DEFAULT NULL,
  `sortorderid` int(19) DEFAULT NULL,
  `presence` int(19) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_campaignrelstatus: ~3 rows (approximately)
/*!40000 ALTER TABLE `ncrm_campaignrelstatus` DISABLE KEYS */;
INSERT INTO `ncrm_campaignrelstatus` (`campaignrelstatusid`, `campaignrelstatus`, `sortorderid`, `presence`) VALUES
	(2, 'Contacted - Successful', 1, 1),
	(3, 'Contacted - Unsuccessful', 2, 1),
	(4, 'Contacted - Never Contact Again', 3, 1);
/*!40000 ALTER TABLE `ncrm_campaignrelstatus` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_campaignrelstatus_seq
CREATE TABLE IF NOT EXISTS `ncrm_campaignrelstatus_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_campaignrelstatus_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_campaignrelstatus_seq` DISABLE KEYS */;
INSERT INTO `ncrm_campaignrelstatus_seq` (`id`) VALUES
	(4);
/*!40000 ALTER TABLE `ncrm_campaignrelstatus_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_campaignscf
CREATE TABLE IF NOT EXISTS `ncrm_campaignscf` (
  `campaignid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`campaignid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_campaignscf: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_campaignscf` DISABLE KEYS */;
INSERT INTO `ncrm_campaignscf` (`campaignid`) VALUES
	(25);
/*!40000 ALTER TABLE `ncrm_campaignscf` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_campaignstatus
CREATE TABLE IF NOT EXISTS `ncrm_campaignstatus` (
  `campaignstatusid` int(19) NOT NULL AUTO_INCREMENT,
  `campaignstatus` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  PRIMARY KEY (`campaignstatusid`),
  KEY `campaignstatus_campaignstatus_idx` (`campaignstatus`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_campaignstatus: ~5 rows (approximately)
/*!40000 ALTER TABLE `ncrm_campaignstatus` DISABLE KEYS */;
INSERT INTO `ncrm_campaignstatus` (`campaignstatusid`, `campaignstatus`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(2, 'Planning', 1, 15, 1),
	(3, 'Active', 1, 16, 2),
	(4, 'Inactive', 1, 17, 3),
	(5, 'Completed', 1, 18, 4),
	(6, 'Cancelled', 1, 19, 5);
/*!40000 ALTER TABLE `ncrm_campaignstatus` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_campaignstatus_seq
CREATE TABLE IF NOT EXISTS `ncrm_campaignstatus_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_campaignstatus_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_campaignstatus_seq` DISABLE KEYS */;
INSERT INTO `ncrm_campaignstatus_seq` (`id`) VALUES
	(6);
/*!40000 ALTER TABLE `ncrm_campaignstatus_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_campaigntype
CREATE TABLE IF NOT EXISTS `ncrm_campaigntype` (
  `campaigntypeid` int(19) NOT NULL AUTO_INCREMENT,
  `campaigntype` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  PRIMARY KEY (`campaigntypeid`),
  UNIQUE KEY `campaigntype_campaigntype_idx` (`campaigntype`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_campaigntype: ~12 rows (approximately)
/*!40000 ALTER TABLE `ncrm_campaigntype` DISABLE KEYS */;
INSERT INTO `ncrm_campaigntype` (`campaigntypeid`, `campaigntype`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(2, 'Conference', 1, 21, 1),
	(3, 'Webinar', 1, 22, 2),
	(4, 'Trade Show', 1, 23, 3),
	(5, 'Public Relations', 1, 24, 4),
	(6, 'Partners', 1, 25, 5),
	(7, 'Referral Program', 1, 26, 6),
	(8, 'Advertisement', 1, 27, 7),
	(9, 'Banner Ads', 1, 28, 8),
	(10, 'Direct Mail', 1, 29, 9),
	(11, 'Email', 1, 30, 10),
	(12, 'Telemarketing', 1, 31, 11),
	(13, 'Others', 1, 32, 12);
/*!40000 ALTER TABLE `ncrm_campaigntype` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_campaigntype_seq
CREATE TABLE IF NOT EXISTS `ncrm_campaigntype_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_campaigntype_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_campaigntype_seq` DISABLE KEYS */;
INSERT INTO `ncrm_campaigntype_seq` (`id`) VALUES
	(13);
/*!40000 ALTER TABLE `ncrm_campaigntype_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_carrier
CREATE TABLE IF NOT EXISTS `ncrm_carrier` (
  `carrierid` int(19) NOT NULL AUTO_INCREMENT,
  `carrier` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  PRIMARY KEY (`carrierid`),
  UNIQUE KEY `carrier_carrier_idx` (`carrier`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_carrier: ~5 rows (approximately)
/*!40000 ALTER TABLE `ncrm_carrier` DISABLE KEYS */;
INSERT INTO `ncrm_carrier` (`carrierid`, `carrier`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(1, 'FedEx', 1, 33, 0),
	(2, 'UPS', 1, 34, 1),
	(3, 'USPS', 1, 35, 2),
	(4, 'DHL', 1, 36, 3),
	(5, 'BlueDart', 1, 37, 4);
/*!40000 ALTER TABLE `ncrm_carrier` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_carrier_seq
CREATE TABLE IF NOT EXISTS `ncrm_carrier_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_carrier_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_carrier_seq` DISABLE KEYS */;
INSERT INTO `ncrm_carrier_seq` (`id`) VALUES
	(5);
/*!40000 ALTER TABLE `ncrm_carrier_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_cntactivityrel
CREATE TABLE IF NOT EXISTS `ncrm_cntactivityrel` (
  `contactid` int(19) NOT NULL DEFAULT '0',
  `activityid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`contactid`,`activityid`),
  KEY `cntactivityrel_contactid_idx` (`contactid`),
  KEY `cntactivityrel_activityid_idx` (`activityid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_cntactivityrel: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_cntactivityrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_cntactivityrel` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_contactaddress
CREATE TABLE IF NOT EXISTS `ncrm_contactaddress` (
  `contactaddressid` int(19) NOT NULL DEFAULT '0',
  `mailingcity` varchar(40) DEFAULT NULL,
  `mailingstreet` varchar(250) DEFAULT NULL,
  `mailingcountry` varchar(40) DEFAULT NULL,
  `othercountry` varchar(30) DEFAULT NULL,
  `mailingstate` varchar(30) DEFAULT NULL,
  `mailingpobox` varchar(30) DEFAULT NULL,
  `othercity` varchar(40) DEFAULT NULL,
  `otherstate` varchar(50) DEFAULT NULL,
  `mailingzip` varchar(30) DEFAULT NULL,
  `otherzip` varchar(30) DEFAULT NULL,
  `otherstreet` varchar(250) DEFAULT NULL,
  `otherpobox` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`contactaddressid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_contactaddress: ~2 rows (approximately)
/*!40000 ALTER TABLE `ncrm_contactaddress` DISABLE KEYS */;
INSERT INTO `ncrm_contactaddress` (`contactaddressid`, `mailingcity`, `mailingstreet`, `mailingcountry`, `othercountry`, `mailingstate`, `mailingpobox`, `othercity`, `otherstate`, `mailingzip`, `otherzip`, `otherstreet`, `otherpobox`) VALUES
	(13, '', '', '', '', '', '', '', '', '', '', '', ''),
	(24, '', '', '', '', '', '', '', '', '', '', '', ''),
	(28, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(29, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
/*!40000 ALTER TABLE `ncrm_contactaddress` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_contactdetails
CREATE TABLE IF NOT EXISTS `ncrm_contactdetails` (
  `contactid` int(19) NOT NULL DEFAULT '0',
  `contact_no` varchar(100) NOT NULL,
  `accountid` int(19) DEFAULT NULL,
  `salutation` varchar(200) DEFAULT NULL,
  `firstname` varchar(40) DEFAULT NULL,
  `lastname` varchar(80) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `department` varchar(30) DEFAULT NULL,
  `fax` varchar(50) DEFAULT NULL,
  `reportsto` varchar(30) DEFAULT NULL,
  `training` varchar(50) DEFAULT NULL,
  `usertype` varchar(50) DEFAULT NULL,
  `contacttype` varchar(50) DEFAULT NULL,
  `otheremail` varchar(100) DEFAULT NULL,
  `secondaryemail` varchar(100) DEFAULT NULL,
  `donotcall` varchar(3) DEFAULT NULL,
  `emailoptout` varchar(3) DEFAULT '0',
  `imagename` varchar(150) DEFAULT NULL,
  `reference` varchar(3) DEFAULT NULL,
  `notify_owner` varchar(3) DEFAULT '0',
  `isconvertedfromlead` varchar(3) DEFAULT '0',
  PRIMARY KEY (`contactid`),
  KEY `contactdetails_accountid_idx` (`accountid`),
  KEY `email_idx` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_contactdetails: ~2 rows (approximately)
/*!40000 ALTER TABLE `ncrm_contactdetails` DISABLE KEYS */;
INSERT INTO `ncrm_contactdetails` (`contactid`, `contact_no`, `accountid`, `salutation`, `firstname`, `lastname`, `email`, `phone`, `mobile`, `title`, `department`, `fax`, `reportsto`, `training`, `usertype`, `contacttype`, `otheremail`, `secondaryemail`, `donotcall`, `emailoptout`, `imagename`, `reference`, `notify_owner`, `isconvertedfromlead`) VALUES
	(13, 'CON1', 0, '', 'tick', 'ct', '', '', '', '', '', '', '0', NULL, NULL, NULL, NULL, '', '0', '0', '', '0', '0', '0'),
	(24, 'CON2', 0, '', 'lead 1', 'lead 1', '', '', '', '', '', '', '0', NULL, NULL, NULL, NULL, '', '0', '0', '', '0', '0', '1'),
	(28, 'CON3', 0, '', NULL, 'ct1', '', NULL, '', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '', '0', '0', '', NULL, '0', '0'),
	(29, 'CON4', 0, '', NULL, 'Nam Nguyen', '', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '', NULL, '0', '0');
/*!40000 ALTER TABLE `ncrm_contactdetails` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_contactscf
CREATE TABLE IF NOT EXISTS `ncrm_contactscf` (
  `contactid` int(19) NOT NULL DEFAULT '0',
  `cf_751` varchar(120) DEFAULT '',
  `cf_753` varchar(250) DEFAULT '',
  `cf_755` varchar(120) DEFAULT '',
  `cf_757` varchar(120) DEFAULT '',
  `cf_759` varchar(120) DEFAULT '',
  `cf_761` varchar(120) DEFAULT '',
  `cf_763` varchar(120) DEFAULT '',
  `cf_765` varchar(120) DEFAULT '',
  `cf_769` varchar(255) DEFAULT '',
  PRIMARY KEY (`contactid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_contactscf: ~2 rows (approximately)
/*!40000 ALTER TABLE `ncrm_contactscf` DISABLE KEYS */;
INSERT INTO `ncrm_contactscf` (`contactid`, `cf_751`, `cf_753`, `cf_755`, `cf_757`, `cf_759`, `cf_761`, `cf_763`, `cf_765`, `cf_769`) VALUES
	(13, '', '', '', '', '', '', '', '', ''),
	(24, '', '', '', '', '', '', '', '', ''),
	(28, '', '', '', '', '', '', '', '', ''),
	(29, 'GO CRM', '', '', '', '', '', '', '', '');
/*!40000 ALTER TABLE `ncrm_contactscf` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_contactsubdetails
CREATE TABLE IF NOT EXISTS `ncrm_contactsubdetails` (
  `contactsubscriptionid` int(19) NOT NULL DEFAULT '0',
  `homephone` varchar(50) DEFAULT NULL,
  `otherphone` varchar(50) DEFAULT NULL,
  `assistant` varchar(30) DEFAULT NULL,
  `assistantphone` varchar(50) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `laststayintouchrequest` int(30) DEFAULT '0',
  `laststayintouchsavedate` int(19) DEFAULT '0',
  `leadsource` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`contactsubscriptionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_contactsubdetails: ~2 rows (approximately)
/*!40000 ALTER TABLE `ncrm_contactsubdetails` DISABLE KEYS */;
INSERT INTO `ncrm_contactsubdetails` (`contactsubscriptionid`, `homephone`, `otherphone`, `assistant`, `assistantphone`, `birthday`, `laststayintouchrequest`, `laststayintouchsavedate`, `leadsource`) VALUES
	(13, '', '', '', '', NULL, 0, 0, ''),
	(24, '', '', '', '', NULL, 0, 0, ''),
	(28, '', '', NULL, NULL, NULL, 0, 0, ''),
	(29, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL);
/*!40000 ALTER TABLE `ncrm_contactsubdetails` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_contpotentialrel
CREATE TABLE IF NOT EXISTS `ncrm_contpotentialrel` (
  `contactid` int(19) NOT NULL DEFAULT '0',
  `potentialid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`contactid`,`potentialid`),
  KEY `contpotentialrel_potentialid_idx` (`potentialid`),
  KEY `contpotentialrel_contactid_idx` (`contactid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_contpotentialrel: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_contpotentialrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_contpotentialrel` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_contract_priority
CREATE TABLE IF NOT EXISTS `ncrm_contract_priority` (
  `contract_priorityid` int(11) NOT NULL AUTO_INCREMENT,
  `contract_priority` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(11) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT '0',
  PRIMARY KEY (`contract_priorityid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_contract_priority: ~3 rows (approximately)
/*!40000 ALTER TABLE `ncrm_contract_priority` DISABLE KEYS */;
INSERT INTO `ncrm_contract_priority` (`contract_priorityid`, `contract_priority`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(1, 'Low', 1, 220, 1),
	(2, 'Normal', 1, 221, 2),
	(3, 'High', 1, 222, 3);
/*!40000 ALTER TABLE `ncrm_contract_priority` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_contract_priority_seq
CREATE TABLE IF NOT EXISTS `ncrm_contract_priority_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_contract_priority_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_contract_priority_seq` DISABLE KEYS */;
INSERT INTO `ncrm_contract_priority_seq` (`id`) VALUES
	(3);
/*!40000 ALTER TABLE `ncrm_contract_priority_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_contract_status
CREATE TABLE IF NOT EXISTS `ncrm_contract_status` (
  `contract_statusid` int(11) NOT NULL AUTO_INCREMENT,
  `contract_status` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(11) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT '0',
  PRIMARY KEY (`contract_statusid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_contract_status: ~6 rows (approximately)
/*!40000 ALTER TABLE `ncrm_contract_status` DISABLE KEYS */;
INSERT INTO `ncrm_contract_status` (`contract_statusid`, `contract_status`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(1, 'Undefined', 1, 214, 1),
	(2, 'In Planning', 1, 215, 2),
	(3, 'In Progress', 1, 216, 3),
	(4, 'On Hold', 1, 217, 4),
	(5, 'Complete', 0, 218, 5),
	(6, 'Archived', 1, 219, 6);
/*!40000 ALTER TABLE `ncrm_contract_status` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_contract_status_seq
CREATE TABLE IF NOT EXISTS `ncrm_contract_status_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_contract_status_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_contract_status_seq` DISABLE KEYS */;
INSERT INTO `ncrm_contract_status_seq` (`id`) VALUES
	(6);
/*!40000 ALTER TABLE `ncrm_contract_status_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_contract_type
CREATE TABLE IF NOT EXISTS `ncrm_contract_type` (
  `contract_typeid` int(11) NOT NULL AUTO_INCREMENT,
  `contract_type` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(11) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT '0',
  PRIMARY KEY (`contract_typeid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_contract_type: ~3 rows (approximately)
/*!40000 ALTER TABLE `ncrm_contract_type` DISABLE KEYS */;
INSERT INTO `ncrm_contract_type` (`contract_typeid`, `contract_type`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(1, 'Support', 1, 223, 1),
	(2, 'Services', 1, 224, 2),
	(3, 'Administrative', 1, 225, 3);
/*!40000 ALTER TABLE `ncrm_contract_type` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_contract_type_seq
CREATE TABLE IF NOT EXISTS `ncrm_contract_type_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_contract_type_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_contract_type_seq` DISABLE KEYS */;
INSERT INTO `ncrm_contract_type_seq` (`id`) VALUES
	(3);
/*!40000 ALTER TABLE `ncrm_contract_type_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_convertleadmapping
CREATE TABLE IF NOT EXISTS `ncrm_convertleadmapping` (
  `cfmid` int(19) NOT NULL AUTO_INCREMENT,
  `leadfid` int(19) NOT NULL,
  `accountfid` int(19) DEFAULT NULL,
  `contactfid` int(19) DEFAULT NULL,
  `potentialfid` int(19) DEFAULT NULL,
  `editable` int(19) DEFAULT '1',
  PRIMARY KEY (`cfmid`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_convertleadmapping: ~31 rows (approximately)
/*!40000 ALTER TABLE `ncrm_convertleadmapping` DISABLE KEYS */;
INSERT INTO `ncrm_convertleadmapping` (`cfmid`, `leadfid`, `accountfid`, `contactfid`, `potentialfid`, `editable`) VALUES
	(1, 43, 1, 0, 110, 0),
	(2, 49, 14, 0, 0, 1),
	(3, 40, 3, 69, 0, NULL),
	(4, 0, 0, 0, 0, NULL),
	(5, 44, 5, 77, 0, 1),
	(6, 52, 13, 0, 0, 1),
	(7, 46, 9, 80, 0, 0),
	(8, 48, 4, 0, 0, 1),
	(9, 61, 26, 98, 0, 1),
	(10, 60, 30, 0, 0, 1),
	(11, 62, 32, 104, 0, 1),
	(12, 63, 28, 100, 0, 1),
	(13, 59, 24, 96, 0, 1),
	(14, 64, 34, 106, 0, 1),
	(15, 61, 27, 0, 0, 1),
	(16, 60, 31, 0, 0, 1),
	(17, 62, 33, 0, 0, 1),
	(18, 63, 29, 0, 0, 1),
	(19, 59, 25, 0, 0, 1),
	(20, 64, 35, 0, 0, 1),
	(21, 65, 36, 109, 125, 1),
	(22, 37, 0, 66, 0, 1),
	(23, 38, 0, 67, 0, 0),
	(24, 41, 0, 70, 0, 0),
	(25, 42, 0, 71, 0, 1),
	(26, 45, 0, 76, 0, 1),
	(27, 55, 0, 83, 0, 1),
	(28, 47, 0, 74, 117, 1),
	(29, 50, 0, 0, 0, 1),
	(30, 53, 10, 0, 0, 1),
	(31, 51, 17, 0, 0, 1);
/*!40000 ALTER TABLE `ncrm_convertleadmapping` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_crmentity
CREATE TABLE IF NOT EXISTS `ncrm_crmentity` (
  `crmid` int(19) NOT NULL,
  `smcreatorid` int(19) NOT NULL DEFAULT '0',
  `smownerid` int(19) NOT NULL DEFAULT '0',
  `modifiedby` int(19) NOT NULL DEFAULT '0',
  `setype` varchar(30) NOT NULL,
  `description` mediumtext,
  `createdtime` datetime NOT NULL,
  `modifiedtime` datetime NOT NULL,
  `viewedtime` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `version` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) DEFAULT '1',
  `deleted` int(1) NOT NULL DEFAULT '0',
  `label` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`crmid`),
  KEY `crmentity_smcreatorid_idx` (`smcreatorid`),
  KEY `crmentity_modifiedby_idx` (`modifiedby`),
  KEY `crmentity_deleted_idx` (`deleted`),
  KEY `crm_ownerid_del_setype_idx` (`smownerid`,`deleted`,`setype`),
  KEY `ncrm_crmentity_labelidx` (`label`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_crmentity: ~26 rows (approximately)
/*!40000 ALTER TABLE `ncrm_crmentity` DISABLE KEYS */;
INSERT INTO `ncrm_crmentity` (`crmid`, `smcreatorid`, `smownerid`, `modifiedby`, `setype`, `description`, `createdtime`, `modifiedtime`, `viewedtime`, `status`, `version`, `presence`, `deleted`, `label`) VALUES
	(2, 1, 1, 1, 'HelpDesk', '', '2017-01-04 06:27:16', '2017-01-04 06:27:16', NULL, NULL, 0, 1, 0, 'ticktet 1'),
	(3, 1, 1, 1, 'HelpDesk', '', '2017-01-04 06:27:29', '2017-01-04 06:27:29', NULL, NULL, 0, 1, 0, 'ticktet 2'),
	(4, 1, 1, 1, 'HelpDesk', '', '2017-01-04 06:27:35', '2017-01-04 06:27:35', NULL, NULL, 0, 1, 0, 'ticktet 3'),
	(5, 1, 1, 1, 'HelpDesk', '', '2017-01-04 06:27:40', '2017-01-04 06:27:40', NULL, NULL, 0, 1, 0, 'ticktet 4'),
	(6, 1, 1, 1, 'HelpDesk', '', '2017-01-04 06:27:45', '2017-01-04 06:27:45', NULL, NULL, 0, 1, 0, 'ticktet 5'),
	(7, 1, 1, 1, 'HelpDesk', '', '2017-01-04 06:27:50', '2017-01-04 06:27:50', NULL, NULL, 0, 1, 0, 'ticktet 6'),
	(8, 1, 1, 1, 'HelpDesk', '', '2017-01-04 06:27:55', '2017-01-04 06:27:55', NULL, NULL, 0, 1, 0, 'ticktet 7'),
	(9, 1, 1, 1, 'HelpDesk', '', '2017-01-04 06:28:01', '2017-01-04 06:28:01', NULL, NULL, 0, 1, 0, 'ticktet 8'),
	(10, 1, 1, 1, 'HelpDesk', '', '2017-01-04 06:28:06', '2017-01-04 06:28:06', NULL, NULL, 0, 1, 0, 'ticktet 9'),
	(11, 1, 1, 1, 'HelpDesk', '', '2017-01-04 06:28:12', '2017-01-04 06:28:12', NULL, NULL, 0, 1, 0, 'ticktet 10'),
	(12, 1, 1, 0, 'Users Attachment', '', '2017-01-09 10:07:26', '2017-01-09 10:07:26', NULL, NULL, 0, 1, 0, NULL),
	(13, 1, 1, 1, 'Contacts', '', '2017-01-09 16:04:45', '2017-11-25 09:12:18', NULL, NULL, 0, 1, 1, 'tick ct'),
	(14, 1, 1, 1, 'Accounts', '', '2017-01-09 17:30:15', '2017-01-09 17:30:32', NULL, NULL, 0, 1, 0, 'accc 1 accc 1 accc 1 accc 1 accc 1'),
	(15, 1, 1, 1, 'ModComments', NULL, '2017-01-10 07:06:29', '2017-01-10 07:06:29', NULL, NULL, 0, 1, 0, 'test 1'),
	(16, 1, 1, 1, 'ModComments', NULL, '2017-01-10 07:06:32', '2017-01-10 07:06:32', NULL, NULL, 0, 1, 0, 'test 2'),
	(17, 1, 1, 1, 'ModComments', NULL, '2017-01-10 07:08:42', '2017-01-10 07:08:42', NULL, NULL, 0, 1, 0, 'test 3'),
	(18, 1, 1, 1, 'ModComments', NULL, '2017-01-10 07:09:07', '2017-01-10 07:09:07', NULL, NULL, 0, 1, 0, 'test 4'),
	(19, 1, 1, 1, 'ModComments', NULL, '2017-01-10 08:52:01', '2017-01-10 08:52:01', NULL, NULL, 0, 1, 0, 'test 1'),
	(20, 1, 1, 1, 'Calendar', '', '2017-01-10 09:54:45', '2017-01-10 09:55:41', NULL, NULL, 0, 1, 0, 'Event today 1'),
	(21, 1, 1, 0, 'Users Attachment', '', '2017-10-26 15:05:43', '2017-10-26 15:05:43', NULL, NULL, 0, 1, 0, NULL),
	(22, 1, 1, 1, 'Products', '', '2017-10-26 20:15:48', '2017-10-26 20:15:57', NULL, NULL, 0, 1, 0, 'p1'),
	(23, 1, 1, 1, 'Leads', '', '2017-10-29 02:50:11', '2017-10-29 02:50:32', NULL, NULL, 0, 1, 0, 'lead 1 lead 1'),
	(24, 1, 1, 1, 'Contacts', '', '2017-10-29 02:50:32', '2017-11-25 09:12:18', NULL, NULL, 0, 1, 1, 'lead 1 lead 1'),
	(25, 1, 1, 1, 'Campaigns', '', '2017-10-29 02:59:27', '2017-10-29 02:59:48', NULL, NULL, 0, 1, 0, 'fb ad q1'),
	(26, 1, 1, 1, 'Documents', NULL, '2017-10-29 03:59:21', '2017-10-29 03:59:21', NULL, NULL, 0, 1, 0, 'Tieu de 1'),
	(27, 1, 1, 0, 'Documents Attachment', NULL, '2017-10-29 03:59:21', '2017-10-29 03:59:21', NULL, NULL, 0, 1, 0, NULL),
	(28, 1, 1, 1, 'Contacts', '', '2017-11-25 06:56:09', '2017-11-25 09:12:18', NULL, NULL, 0, 1, 1, ' ct1'),
	(29, 1, 1, 1, 'Contacts', '', '2017-11-25 09:12:28', '2017-11-25 09:12:28', NULL, NULL, 0, 1, 0, ' Nam Nguyen'),
	(30, 1, 1, 0, 'Users Attachment', '', '2017-11-25 14:40:21', '2017-11-25 14:40:21', NULL, NULL, 0, 1, 0, NULL);
/*!40000 ALTER TABLE `ncrm_crmentity` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_crmentityrel
CREATE TABLE IF NOT EXISTS `ncrm_crmentityrel` (
  `crmid` int(11) NOT NULL,
  `module` varchar(100) NOT NULL,
  `relcrmid` int(11) NOT NULL,
  `relmodule` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_crmentityrel: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_crmentityrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_crmentityrel` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_crmentity_seq
CREATE TABLE IF NOT EXISTS `ncrm_crmentity_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_crmentity_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_crmentity_seq` DISABLE KEYS */;
INSERT INTO `ncrm_crmentity_seq` (`id`) VALUES
	(30);
/*!40000 ALTER TABLE `ncrm_crmentity_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_crmsetup
CREATE TABLE IF NOT EXISTS `ncrm_crmsetup` (
  `userid` int(11) DEFAULT NULL,
  `setup_status` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_crmsetup: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_crmsetup` DISABLE KEYS */;
INSERT INTO `ncrm_crmsetup` (`userid`, `setup_status`) VALUES
	(1, 1),
	(6, 1);
/*!40000 ALTER TABLE `ncrm_crmsetup` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_cron_task
CREATE TABLE IF NOT EXISTS `ncrm_cron_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `handler_file` varchar(100) DEFAULT NULL,
  `frequency` int(11) DEFAULT NULL,
  `laststart` int(11) unsigned DEFAULT NULL,
  `lastend` int(11) unsigned DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `module` varchar(100) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `handler_file` (`handler_file`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_cron_task: ~6 rows (approximately)
/*!40000 ALTER TABLE `ncrm_cron_task` DISABLE KEYS */;
INSERT INTO `ncrm_cron_task` (`id`, `name`, `handler_file`, `frequency`, `laststart`, `lastend`, `status`, `module`, `sequence`, `description`) VALUES
	(1, 'Workflow', 'cron/modules/com_ncrm_workflow/com_ncrm_workflow.service', 900, NULL, NULL, 1, 'com_ncrm_workflow', 1, 'Recommended frequency for Workflow is 15 mins'),
	(2, 'RecurringInvoice', 'cron/modules/SalesOrder/RecurringInvoice.service', 43200, NULL, NULL, 1, 'SalesOrder', 2, 'Recommended frequency for RecurringInvoice is 12 hours'),
	(3, 'SendReminder', 'cron/SendReminder.service', 900, NULL, NULL, 1, 'Calendar', 3, 'Recommended frequency for SendReminder is 15 mins'),
	(5, 'MailScanner', 'cron/MailScanner.service', 900, NULL, NULL, 1, 'Settings', 5, 'Recommended frequency for MailScanner is 15 mins'),
	(6, 'Scheduled Import', 'cron/modules/Import/ScheduledImport.service', 900, NULL, NULL, 0, 'Import', 6, 'Recommended frequency for MailScanner is 15 mins'),
	(7, 'ScheduleReports', 'cron/modules/Reports/ScheduleReports.service', 900, NULL, NULL, 1, 'Reports', 7, 'Recommended frequency for ScheduleReports is 15 mins');
/*!40000 ALTER TABLE `ncrm_cron_task` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_currencies
CREATE TABLE IF NOT EXISTS `ncrm_currencies` (
  `currencyid` int(19) NOT NULL AUTO_INCREMENT,
  `currency_name` varchar(200) DEFAULT NULL,
  `currency_code` varchar(50) DEFAULT NULL,
  `currency_symbol` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`currencyid`)
) ENGINE=InnoDB AUTO_INCREMENT=139 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_currencies: ~138 rows (approximately)
/*!40000 ALTER TABLE `ncrm_currencies` DISABLE KEYS */;
INSERT INTO `ncrm_currencies` (`currencyid`, `currency_name`, `currency_code`, `currency_symbol`) VALUES
	(1, 'Albania, Leke', 'ALL', 'Lek'),
	(2, 'Argentina, Pesos', 'ARS', '$'),
	(3, 'Aruba, Guilders', 'AWG', 'ƒ'),
	(4, 'Australia, Dollars', 'AUD', '$'),
	(5, 'Azerbaijan, New Manats', 'AZN', 'ман'),
	(6, 'Bahamas, Dollars', 'BSD', '$'),
	(7, 'Bahrain, Dinar', 'BHD', 'BD'),
	(8, 'Barbados, Dollars', 'BBD', '$'),
	(9, 'Belarus, Rubles', 'BYR', 'p.'),
	(10, 'Belize, Dollars', 'BZD', 'BZ$'),
	(11, 'Bermuda, Dollars', 'BMD', '$'),
	(12, 'Bolivia, Bolivianos', 'BOB', '$b'),
	(13, 'China, Yuan Renminbi', 'CNY', '¥'),
	(14, 'Convertible Marka', 'BAM', 'KM'),
	(15, 'Botswana, Pulas', 'BWP', 'P'),
	(16, 'Bulgaria, Leva', 'BGN', 'лв'),
	(17, 'Brazil, Reais', 'BRL', 'R$'),
	(18, 'Great Britain Pounds', 'GBP', '£'),
	(19, 'Brunei Darussalam, Dollars', 'BND', '$'),
	(20, 'Canada, Dollars', 'CAD', '$'),
	(21, 'Cayman Islands, Dollars', 'KYD', '$'),
	(22, 'Chile, Pesos', 'CLP', '$'),
	(23, 'Colombia, Pesos', 'COP', '$'),
	(24, 'Costa Rica, Colón', 'CRC', '₡'),
	(25, 'Croatia, Kuna', 'HRK', 'kn'),
	(26, 'Cuba, Pesos', 'CUP', '₱'),
	(27, 'Czech Republic, Koruny', 'CZK', 'Kč'),
	(28, 'Cyprus, Pounds', 'CYP', '£'),
	(29, 'Denmark, Kroner', 'DKK', 'kr'),
	(30, 'Dominican Republic, Pesos', 'DOP', 'RD$'),
	(31, 'East Caribbean, Dollars', 'XCD', '$'),
	(32, 'Egypt, Pounds', 'EGP', '£'),
	(33, 'El Salvador, Colón', 'SVC', '₡'),
	(34, 'England, Pounds', 'GBP', '£'),
	(35, 'Estonia, Krooni', 'EEK', 'kr'),
	(36, 'Euro', 'EUR', '€'),
	(37, 'Falkland Islands, Pounds', 'FKP', '£'),
	(38, 'Fiji, Dollars', 'FJD', '$'),
	(39, 'Ghana, Cedis', 'GHC', '¢'),
	(40, 'Gibraltar, Pounds', 'GIP', '£'),
	(41, 'Guatemala, Quetzales', 'GTQ', 'Q'),
	(42, 'Guernsey, Pounds', 'GGP', '£'),
	(43, 'Guyana, Dollars', 'GYD', '$'),
	(44, 'Honduras, Lempiras', 'HNL', 'L'),
	(45, 'Hong Kong, Dollars', 'HKD', 'HK$'),
	(46, 'Hungary, Forint', 'HUF', 'Ft'),
	(47, 'Iceland, Krona', 'ISK', 'kr'),
	(48, 'India, Rupees', 'INR', '₹'),
	(49, 'Indonesia, Rupiahs', 'IDR', 'Rp'),
	(50, 'Iran, Rials', 'IRR', '﷼'),
	(51, 'Isle of Man, Pounds', 'IMP', '£'),
	(52, 'Israel, New Shekels', 'ILS', '₪'),
	(53, 'Jamaica, Dollars', 'JMD', 'J$'),
	(54, 'Japan, Yen', 'JPY', '¥'),
	(55, 'Jersey, Pounds', 'JEP', '£'),
	(56, 'Jordan, Dinar', 'JOD', 'JOD'),
	(57, 'Kazakhstan, Tenge', 'KZT', '〒'),
	(58, 'Kenya, Shilling', 'KES', 'KES'),
	(59, 'Korea (North), Won', 'KPW', '₩'),
	(60, 'Korea (South), Won', 'KRW', '₩'),
	(61, 'Kuwait, Dinar', 'KWD', 'KWD'),
	(62, 'Kyrgyzstan, Soms', 'KGS', 'лв'),
	(63, 'Laos, Kips', 'LAK', '₭'),
	(64, 'Latvia, Lati', 'LVL', 'Ls'),
	(65, 'Lebanon, Pounds', 'LBP', '£'),
	(66, 'Liberia, Dollars', 'LRD', '$'),
	(67, 'Switzerland Francs', 'CHF', 'CHF'),
	(68, 'Lithuania, Litai', 'LTL', 'Lt'),
	(69, 'MADAGASCAR, Malagasy Ariary', 'MGA', 'MGA'),
	(70, 'Macedonia, Denars', 'MKD', 'ден'),
	(71, 'Malaysia, Ringgits', 'MYR', 'RM'),
	(72, 'Malta, Liri', 'MTL', '₤'),
	(73, 'Mauritius, Rupees', 'MUR', '₨'),
	(74, 'Mexico, Pesos', 'MXN', '$'),
	(75, 'Mongolia, Tugriks', 'MNT', '₮'),
	(76, 'Mozambique, Meticais', 'MZN', 'MT'),
	(77, 'Namibia, Dollars', 'NAD', '$'),
	(78, 'Nepal, Rupees', 'NPR', '₨'),
	(79, 'Netherlands Antilles, Guilders', 'ANG', 'ƒ'),
	(80, 'New Zealand, Dollars', 'NZD', '$'),
	(81, 'Nicaragua, Cordobas', 'NIO', 'C$'),
	(82, 'Nigeria, Nairas', 'NGN', '₦'),
	(83, 'North Korea, Won', 'KPW', '₩'),
	(84, 'Norway, Krone', 'NOK', 'kr'),
	(85, 'Oman, Rials', 'OMR', '﷼'),
	(86, 'Pakistan, Rupees', 'PKR', '₨'),
	(87, 'Panama, Balboa', 'PAB', 'B/.'),
	(88, 'Paraguay, Guarani', 'PYG', 'Gs'),
	(89, 'Peru, Nuevos Soles', 'PEN', 'S/.'),
	(90, 'Philippines, Pesos', 'PHP', 'Php'),
	(91, 'Poland, Zlotych', 'PLN', 'zł'),
	(92, 'Qatar, Rials', 'QAR', '﷼'),
	(93, 'Romania, New Lei', 'RON', 'lei'),
	(94, 'Russia, Rubles', 'RUB', 'руб'),
	(95, 'Saint Helena, Pounds', 'SHP', '£'),
	(96, 'Saudi Arabia, Riyals', 'SAR', '﷼'),
	(97, 'Serbia, Dinars', 'RSD', 'Дин.'),
	(98, 'Seychelles, Rupees', 'SCR', '₨'),
	(99, 'Singapore, Dollars', 'SGD', '$'),
	(100, 'Solomon Islands, Dollars', 'SBD', '$'),
	(101, 'Somalia, Shillings', 'SOS', 'S'),
	(102, 'South Africa, Rand', 'ZAR', 'R'),
	(103, 'South Korea, Won', 'KRW', '₩'),
	(104, 'Sri Lanka, Rupees', 'LKR', '₨'),
	(105, 'Sweden, Kronor', 'SEK', 'kr'),
	(106, 'Switzerland, Francs', 'CHF', 'CHF'),
	(107, 'Suriname, Dollars', 'SRD', '$'),
	(108, 'Syria, Pounds', 'SYP', '£'),
	(109, 'Taiwan, New Dollars', 'TWD', 'NT$'),
	(110, 'Thailand, Baht', 'THB', '฿'),
	(111, 'Trinidad and Tobago, Dollars', 'TTD', 'TT$'),
	(112, 'Turkey, New Lira', 'TRY', 'YTL'),
	(113, 'Turkey, Liras', 'TRL', '₤'),
	(114, 'Tuvalu, Dollars', 'TVD', '$'),
	(115, 'Ukraine, Hryvnia', 'UAH', '₴'),
	(116, 'United Arab Emirates, Dirham', 'AED', 'AED'),
	(117, 'United Kingdom, Pounds', 'GBP', '£'),
	(118, 'United Republic of Tanzania, Shilling', 'TZS', 'TZS'),
	(119, 'USA, Dollars', 'USD', '$'),
	(120, 'Uruguay, Pesos', 'UYU', '$U'),
	(121, 'Uzbekistan, Sums', 'UZS', 'лв'),
	(122, 'Venezuela, Bolivares Fuertes', 'VEF', 'Bs'),
	(123, 'Vietnam, Dong', 'VND', '₫'),
	(124, 'Zambia, Kwacha', 'ZMK', 'ZMK'),
	(125, 'Yemen, Rials', 'YER', '﷼'),
	(126, 'Zimbabwe Dollars', 'ZWD', 'Z$'),
	(127, 'Malawi, Kwacha', 'MWK', 'MK'),
	(128, 'Tunisian, Dinar', 'TD', 'TD'),
	(129, 'Moroccan, Dirham', 'MAD', 'DH'),
	(130, 'Iraqi Dinar', 'IQD', 'ID'),
	(131, 'Maldivian Ruffiya', 'MVR', 'MVR'),
	(132, 'Ugandan Shilling', 'UGX', 'Sh'),
	(133, 'Sudanese Pound', 'SDG', '£'),
	(134, 'CFA Franc BCEAO', 'XOF', 'CFA'),
	(135, 'CFA Franc BEAC', 'XAF', 'CFA'),
	(136, 'Haiti, Gourde', 'HTG', 'G'),
	(137, 'Libya, Dinar', 'LYD', 'LYD'),
	(138, 'CFP Franc', 'XPF', 'F');
/*!40000 ALTER TABLE `ncrm_currencies` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_currencies_seq
CREATE TABLE IF NOT EXISTS `ncrm_currencies_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_currencies_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_currencies_seq` DISABLE KEYS */;
INSERT INTO `ncrm_currencies_seq` (`id`) VALUES
	(138);
/*!40000 ALTER TABLE `ncrm_currencies_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_currency
CREATE TABLE IF NOT EXISTS `ncrm_currency` (
  `currencyid` int(19) NOT NULL AUTO_INCREMENT,
  `currency` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`currencyid`),
  UNIQUE KEY `currency_currency_idx` (`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_currency: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_currency` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_currency` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_currency_decimal_separator
CREATE TABLE IF NOT EXISTS `ncrm_currency_decimal_separator` (
  `currency_decimal_separatorid` int(19) NOT NULL AUTO_INCREMENT,
  `currency_decimal_separator` varchar(2) NOT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`currency_decimal_separatorid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_currency_decimal_separator: ~5 rows (approximately)
/*!40000 ALTER TABLE `ncrm_currency_decimal_separator` DISABLE KEYS */;
INSERT INTO `ncrm_currency_decimal_separator` (`currency_decimal_separatorid`, `currency_decimal_separator`, `sortorderid`, `presence`) VALUES
	(1, '.', 0, 1),
	(2, ',', 1, 1),
	(3, '\'', 2, 1),
	(4, ' ', 3, 1),
	(5, '$', 4, 1);
/*!40000 ALTER TABLE `ncrm_currency_decimal_separator` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_currency_decimal_separator_seq
CREATE TABLE IF NOT EXISTS `ncrm_currency_decimal_separator_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_currency_decimal_separator_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_currency_decimal_separator_seq` DISABLE KEYS */;
INSERT INTO `ncrm_currency_decimal_separator_seq` (`id`) VALUES
	(5);
/*!40000 ALTER TABLE `ncrm_currency_decimal_separator_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_currency_grouping_pattern
CREATE TABLE IF NOT EXISTS `ncrm_currency_grouping_pattern` (
  `currency_grouping_patternid` int(19) NOT NULL AUTO_INCREMENT,
  `currency_grouping_pattern` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`currency_grouping_patternid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_currency_grouping_pattern: ~4 rows (approximately)
/*!40000 ALTER TABLE `ncrm_currency_grouping_pattern` DISABLE KEYS */;
INSERT INTO `ncrm_currency_grouping_pattern` (`currency_grouping_patternid`, `currency_grouping_pattern`, `sortorderid`, `presence`) VALUES
	(1, '123,456,789', 0, 1),
	(2, '123456789', 1, 1),
	(3, '123456,789', 2, 1),
	(4, '12,34,56,789', 3, 1);
/*!40000 ALTER TABLE `ncrm_currency_grouping_pattern` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_currency_grouping_pattern_seq
CREATE TABLE IF NOT EXISTS `ncrm_currency_grouping_pattern_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_currency_grouping_pattern_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_currency_grouping_pattern_seq` DISABLE KEYS */;
INSERT INTO `ncrm_currency_grouping_pattern_seq` (`id`) VALUES
	(4);
/*!40000 ALTER TABLE `ncrm_currency_grouping_pattern_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_currency_grouping_separator
CREATE TABLE IF NOT EXISTS `ncrm_currency_grouping_separator` (
  `currency_grouping_separatorid` int(19) NOT NULL AUTO_INCREMENT,
  `currency_grouping_separator` varchar(2) NOT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`currency_grouping_separatorid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_currency_grouping_separator: ~5 rows (approximately)
/*!40000 ALTER TABLE `ncrm_currency_grouping_separator` DISABLE KEYS */;
INSERT INTO `ncrm_currency_grouping_separator` (`currency_grouping_separatorid`, `currency_grouping_separator`, `sortorderid`, `presence`) VALUES
	(1, ',', 0, 1),
	(2, '.', 1, 1),
	(3, '\'', 2, 1),
	(4, ' ', 3, 1),
	(5, '$', 4, 1);
/*!40000 ALTER TABLE `ncrm_currency_grouping_separator` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_currency_grouping_separator_seq
CREATE TABLE IF NOT EXISTS `ncrm_currency_grouping_separator_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_currency_grouping_separator_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_currency_grouping_separator_seq` DISABLE KEYS */;
INSERT INTO `ncrm_currency_grouping_separator_seq` (`id`) VALUES
	(5);
/*!40000 ALTER TABLE `ncrm_currency_grouping_separator_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_currency_info
CREATE TABLE IF NOT EXISTS `ncrm_currency_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currency_name` varchar(100) DEFAULT NULL,
  `currency_code` varchar(100) DEFAULT NULL,
  `currency_symbol` varchar(30) DEFAULT NULL,
  `conversion_rate` decimal(12,5) DEFAULT NULL,
  `currency_status` varchar(25) DEFAULT NULL,
  `defaultid` varchar(10) NOT NULL DEFAULT '0',
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_currency_info: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_currency_info` DISABLE KEYS */;
INSERT INTO `ncrm_currency_info` (`id`, `currency_name`, `currency_code`, `currency_symbol`, `conversion_rate`, `currency_status`, `defaultid`, `deleted`) VALUES
	(1, 'USA, Dollars', 'USD', '$', 1.00000, 'Active', '-11', 0);
/*!40000 ALTER TABLE `ncrm_currency_info` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_currency_info_seq
CREATE TABLE IF NOT EXISTS `ncrm_currency_info_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_currency_info_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_currency_info_seq` DISABLE KEYS */;
INSERT INTO `ncrm_currency_info_seq` (`id`) VALUES
	(1);
/*!40000 ALTER TABLE `ncrm_currency_info_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_currency_symbol_placement
CREATE TABLE IF NOT EXISTS `ncrm_currency_symbol_placement` (
  `currency_symbol_placementid` int(19) NOT NULL AUTO_INCREMENT,
  `currency_symbol_placement` varchar(30) NOT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`currency_symbol_placementid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_currency_symbol_placement: ~2 rows (approximately)
/*!40000 ALTER TABLE `ncrm_currency_symbol_placement` DISABLE KEYS */;
INSERT INTO `ncrm_currency_symbol_placement` (`currency_symbol_placementid`, `currency_symbol_placement`, `sortorderid`, `presence`) VALUES
	(1, '$1.0', 0, 1),
	(2, '1.0$', 1, 1);
/*!40000 ALTER TABLE `ncrm_currency_symbol_placement` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_currency_symbol_placement_seq
CREATE TABLE IF NOT EXISTS `ncrm_currency_symbol_placement_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_currency_symbol_placement_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_currency_symbol_placement_seq` DISABLE KEYS */;
INSERT INTO `ncrm_currency_symbol_placement_seq` (`id`) VALUES
	(2);
/*!40000 ALTER TABLE `ncrm_currency_symbol_placement_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_customaction
CREATE TABLE IF NOT EXISTS `ncrm_customaction` (
  `cvid` int(19) NOT NULL,
  `subject` varchar(250) NOT NULL,
  `module` varchar(50) NOT NULL,
  `content` text,
  KEY `customaction_cvid_idx` (`cvid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_customaction: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_customaction` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_customaction` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_customerdetails
CREATE TABLE IF NOT EXISTS `ncrm_customerdetails` (
  `customerid` int(19) NOT NULL,
  `portal` varchar(3) DEFAULT NULL,
  `support_start_date` date DEFAULT NULL,
  `support_end_date` date DEFAULT NULL,
  PRIMARY KEY (`customerid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_customerdetails: ~2 rows (approximately)
/*!40000 ALTER TABLE `ncrm_customerdetails` DISABLE KEYS */;
INSERT INTO `ncrm_customerdetails` (`customerid`, `portal`, `support_start_date`, `support_end_date`) VALUES
	(13, '0', NULL, NULL),
	(24, '0', NULL, NULL),
	(28, '0', NULL, NULL),
	(29, NULL, NULL, NULL);
/*!40000 ALTER TABLE `ncrm_customerdetails` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_customerportal_fields
CREATE TABLE IF NOT EXISTS `ncrm_customerportal_fields` (
  `tabid` int(19) NOT NULL,
  `fieldid` int(19) DEFAULT NULL,
  `visible` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_customerportal_fields: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_customerportal_fields` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_customerportal_fields` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_customerportal_prefs
CREATE TABLE IF NOT EXISTS `ncrm_customerportal_prefs` (
  `tabid` int(19) NOT NULL,
  `prefkey` varchar(100) NOT NULL,
  `prefvalue` int(20) DEFAULT NULL,
  PRIMARY KEY (`tabid`,`prefkey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_customerportal_prefs: ~15 rows (approximately)
/*!40000 ALTER TABLE `ncrm_customerportal_prefs` DISABLE KEYS */;
INSERT INTO `ncrm_customerportal_prefs` (`tabid`, `prefkey`, `prefvalue`) VALUES
	(0, 'defaultassignee', 1),
	(0, 'userid', 1),
	(4, 'showrelatedinfo', 1),
	(6, 'showrelatedinfo', 1),
	(8, 'showrelatedinfo', 1),
	(13, 'showrelatedinfo', 1),
	(14, 'showrelatedinfo', 1),
	(15, 'showrelatedinfo', 1),
	(20, 'showrelatedinfo', 1),
	(23, 'showrelatedinfo', 1),
	(36, 'showrelatedinfo', 1),
	(38, 'showrelatedinfo', 1),
	(43, 'showrelatedinfo', 1),
	(44, 'showrelatedinfo', 1),
	(45, 'showrelatedinfo', 1);
/*!40000 ALTER TABLE `ncrm_customerportal_prefs` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_customerportal_tabs
CREATE TABLE IF NOT EXISTS `ncrm_customerportal_tabs` (
  `tabid` int(19) NOT NULL,
  `visible` int(1) DEFAULT '1',
  `sequence` int(1) DEFAULT NULL,
  PRIMARY KEY (`tabid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_customerportal_tabs: ~13 rows (approximately)
/*!40000 ALTER TABLE `ncrm_customerportal_tabs` DISABLE KEYS */;
INSERT INTO `ncrm_customerportal_tabs` (`tabid`, `visible`, `sequence`) VALUES
	(4, 1, 9),
	(6, 1, 10),
	(8, 1, 8),
	(13, 1, 2),
	(14, 1, 6),
	(15, 1, 3),
	(20, 1, 5),
	(23, 1, 4),
	(36, 1, 7),
	(38, 1, 11),
	(43, 1, 12),
	(44, 1, 13),
	(45, 1, 14);
/*!40000 ALTER TABLE `ncrm_customerportal_tabs` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_customview
CREATE TABLE IF NOT EXISTS `ncrm_customview` (
  `cvid` int(19) NOT NULL,
  `viewname` varchar(100) NOT NULL,
  `setdefault` int(1) DEFAULT '0',
  `setmetrics` int(1) DEFAULT '0',
  `entitytype` varchar(25) NOT NULL,
  `status` int(1) DEFAULT '1',
  `userid` int(19) DEFAULT '1',
  PRIMARY KEY (`cvid`),
  KEY `customview_entitytype_idx` (`entitytype`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_customview: ~46 rows (approximately)
/*!40000 ALTER TABLE `ncrm_customview` DISABLE KEYS */;
INSERT INTO `ncrm_customview` (`cvid`, `viewname`, `setdefault`, `setmetrics`, `entitytype`, `status`, `userid`) VALUES
	(1, 'All', 1, 0, 'Leads', 0, 1),
	(2, 'Hot Leads', 0, 1, 'Leads', 3, 1),
	(3, 'This Month Leads', 0, 0, 'Leads', 3, 1),
	(4, 'All', 1, 0, 'Accounts', 0, 1),
	(5, 'Prospect Accounts', 0, 1, 'Accounts', 3, 1),
	(6, 'New This Week', 0, 0, 'Accounts', 3, 1),
	(7, 'All', 0, 0, 'Contacts', 0, 1),
	(8, 'Contacts Address', 0, 0, 'Contacts', 3, 1),
	(9, 'Todays Birthday', 0, 0, 'Contacts', 3, 1),
	(10, 'All', 1, 0, 'Potentials', 0, 1),
	(11, 'Potentials Won', 0, 1, 'Potentials', 3, 1),
	(12, 'Prospecting', 0, 0, 'Potentials', 3, 1),
	(13, 'All', 1, 0, 'HelpDesk', 0, 1),
	(14, 'Open Tickets', 0, 1, 'HelpDesk', 3, 1),
	(15, 'High Prioriy Tickets', 0, 0, 'HelpDesk', 3, 1),
	(16, 'All', 1, 0, 'Quotes', 0, 1),
	(17, 'Open Quotes', 0, 1, 'Quotes', 3, 1),
	(18, 'Rejected Quotes', 0, 0, 'Quotes', 3, 1),
	(19, 'All', 1, 0, 'Calendar', 0, 1),
	(20, 'All', 1, 0, 'Emails', 0, 1),
	(21, 'All', 1, 0, 'Invoice', 0, 1),
	(22, 'All', 1, 0, 'Documents', 0, 1),
	(23, 'All', 1, 0, 'PriceBooks', 0, 1),
	(24, 'All', 1, 0, 'Products', 0, 1),
	(25, 'All', 1, 0, 'PurchaseOrder', 0, 1),
	(26, 'All', 1, 0, 'SalesOrder', 0, 1),
	(27, 'All', 1, 0, 'Vendors', 0, 1),
	(28, 'All', 1, 0, 'Faq', 0, 1),
	(29, 'All', 1, 0, 'Campaigns', 0, 1),
	(30, 'All', 1, 0, 'Webmails', 0, 1),
	(31, 'Drafted FAQ', 0, 0, 'Faq', 3, 1),
	(32, 'Published FAQ', 0, 0, 'Faq', 3, 1),
	(33, 'Open Purchase Orders', 0, 0, 'PurchaseOrder', 3, 1),
	(34, 'Received Purchase Orders', 0, 0, 'PurchaseOrder', 3, 1),
	(35, 'Open Invoices', 0, 0, 'Invoice', 3, 1),
	(36, 'Paid Invoices', 0, 0, 'Invoice', 3, 1),
	(37, 'Pending Sales Orders', 0, 0, 'SalesOrder', 3, 1),
	(38, 'All', 1, 0, 'PBXManager', 0, 1),
	(39, 'All', 1, 0, 'ServiceContracts', 0, 1),
	(40, 'All', 1, 0, 'Services', 0, 1),
	(41, 'All', 1, 0, 'Assets', 0, 1),
	(42, 'All', 0, 0, 'ModComments', 0, 1),
	(46, 'All', 0, 0, 'SMSNotifier', 0, 1),
	(47, 'All', 1, 0, 'ProjectMilestone', 0, 1),
	(48, 'All', 1, 0, 'ProjectTask', 0, 1),
	(49, 'All', 1, 0, 'Project', 0, 1),
	(50, 'Default View', 1, 0, 'Contacts', 0, 1);
/*!40000 ALTER TABLE `ncrm_customview` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_customview_seq
CREATE TABLE IF NOT EXISTS `ncrm_customview_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_customview_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_customview_seq` DISABLE KEYS */;
INSERT INTO `ncrm_customview_seq` (`id`) VALUES
	(50);
/*!40000 ALTER TABLE `ncrm_customview_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_cvadvfilter
CREATE TABLE IF NOT EXISTS `ncrm_cvadvfilter` (
  `cvid` int(19) NOT NULL,
  `columnindex` int(11) NOT NULL,
  `columnname` varchar(250) DEFAULT '',
  `comparator` varchar(20) DEFAULT NULL,
  `value` varchar(512) DEFAULT NULL,
  `groupid` int(11) DEFAULT '1',
  `column_condition` varchar(255) DEFAULT 'and',
  PRIMARY KEY (`cvid`,`columnindex`),
  KEY `cvadvfilter_cvid_idx` (`cvid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_cvadvfilter: ~16 rows (approximately)
/*!40000 ALTER TABLE `ncrm_cvadvfilter` DISABLE KEYS */;
INSERT INTO `ncrm_cvadvfilter` (`cvid`, `columnindex`, `columnname`, `comparator`, `value`, `groupid`, `column_condition`) VALUES
	(2, 0, 'ncrm_leaddetails:leadstatus:leadstatus:Leads_Lead_Status:V', 'e', 'Hot', 1, 'and'),
	(5, 0, 'ncrm_account:account_type:accounttype:Accounts_Type:V', 'e', 'Prospect', 1, 'and'),
	(11, 0, 'ncrm_potential:sales_stage:sales_stage:Potentials_Sales_Stage:V', 'e', 'Closed Won', 1, 'and'),
	(12, 0, 'ncrm_potential:sales_stage:sales_stage:Potentials_Sales_Stage:V', 'e', 'Prospecting', 1, 'and'),
	(14, 0, 'ncrm_troubletickets:status:ticketstatus:HelpDesk_Status:V', 'n', 'Closed', 1, 'and'),
	(15, 0, 'ncrm_troubletickets:priority:ticketpriorities:HelpDesk_Priority:V', 'e', 'High', 1, 'and'),
	(17, 0, 'ncrm_quotes:quotestage:quotestage:Quotes_Quote_Stage:V', 'n', 'Accepted', 1, 'and'),
	(17, 1, 'ncrm_quotes:quotestage:quotestage:Quotes_Quote_Stage:V', 'n', 'Rejected', 1, 'and'),
	(18, 0, 'ncrm_quotes:quotestage:quotestage:Quotes_Quote_Stage:V', 'e', 'Rejected', 1, 'and'),
	(31, 0, 'ncrm_faq:status:faqstatus:Faq_Status:V', 'e', 'Draft', 1, 'and'),
	(32, 0, 'ncrm_faq:status:faqstatus:Faq_Status:V', 'e', 'Published', 1, 'and'),
	(33, 0, 'ncrm_purchaseorder:postatus:postatus:PurchaseOrder_Status:V', 'e', 'Created, Approved, Delivered', 1, 'and'),
	(34, 0, 'ncrm_purchaseorder:postatus:postatus:PurchaseOrder_Status:V', 'e', 'Received Shipment', 1, 'and'),
	(35, 0, 'ncrm_invoice:invoicestatus:invoicestatus:Invoice_Status:V', 'e', 'Created, Approved, Sent', 1, 'and'),
	(36, 0, 'ncrm_invoice:invoicestatus:invoicestatus:Invoice_Status:V', 'e', 'Paid', 1, 'and'),
	(37, 0, 'ncrm_salesorder:sostatus:sostatus:SalesOrder_Status:V', 'e', 'Created, Approved', 1, 'and');
/*!40000 ALTER TABLE `ncrm_cvadvfilter` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_cvadvfilter_grouping
CREATE TABLE IF NOT EXISTS `ncrm_cvadvfilter_grouping` (
  `groupid` int(11) NOT NULL,
  `cvid` int(19) NOT NULL,
  `group_condition` varchar(255) DEFAULT NULL,
  `condition_expression` text,
  PRIMARY KEY (`groupid`,`cvid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_cvadvfilter_grouping: ~15 rows (approximately)
/*!40000 ALTER TABLE `ncrm_cvadvfilter_grouping` DISABLE KEYS */;
INSERT INTO `ncrm_cvadvfilter_grouping` (`groupid`, `cvid`, `group_condition`, `condition_expression`) VALUES
	(1, 2, '', ''),
	(1, 5, '', ''),
	(1, 11, '', ''),
	(1, 12, '', ''),
	(1, 14, '', ''),
	(1, 15, '', ''),
	(1, 17, '', ''),
	(1, 18, '', ''),
	(1, 31, '', ''),
	(1, 32, '', ''),
	(1, 33, '', ''),
	(1, 34, '', ''),
	(1, 35, '', ''),
	(1, 36, '', ''),
	(1, 37, '', '');
/*!40000 ALTER TABLE `ncrm_cvadvfilter_grouping` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_cvcolumnlist
CREATE TABLE IF NOT EXISTS `ncrm_cvcolumnlist` (
  `cvid` int(19) NOT NULL,
  `columnindex` int(11) NOT NULL,
  `columnname` varchar(250) DEFAULT '',
  PRIMARY KEY (`cvid`,`columnindex`),
  KEY `cvcolumnlist_columnindex_idx` (`columnindex`),
  KEY `cvcolumnlist_cvid_idx` (`cvid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_cvcolumnlist: ~264 rows (approximately)
/*!40000 ALTER TABLE `ncrm_cvcolumnlist` DISABLE KEYS */;
INSERT INTO `ncrm_cvcolumnlist` (`cvid`, `columnindex`, `columnname`) VALUES
	(1, 1, 'ncrm_leaddetails:firstname:firstname:Leads_First_Name:V'),
	(1, 2, 'ncrm_leaddetails:lastname:lastname:Leads_Last_Name:V'),
	(1, 3, 'ncrm_leaddetails:company:company:Leads_Company:V'),
	(1, 4, 'ncrm_leadaddress:phone:phone:Leads_Phone:V'),
	(1, 5, 'ncrm_leadsubdetails:website:website:Leads_Website:V'),
	(1, 6, 'ncrm_leaddetails:email:email:Leads_Email:V'),
	(1, 7, 'ncrm_crmentity:smownerid:assigned_user_id:Leads_Assigned_To:V'),
	(2, 0, 'ncrm_leaddetails:firstname:firstname:Leads_First_Name:V'),
	(2, 1, 'ncrm_leaddetails:lastname:lastname:Leads_Last_Name:V'),
	(2, 2, 'ncrm_leaddetails:company:company:Leads_Company:V'),
	(2, 3, 'ncrm_leaddetails:leadsource:leadsource:Leads_Lead_Source:V'),
	(2, 4, 'ncrm_leadsubdetails:website:website:Leads_Website:V'),
	(2, 5, 'ncrm_leaddetails:email:email:Leads_Email:V'),
	(3, 0, 'ncrm_leaddetails:firstname:firstname:Leads_First_Name:V'),
	(3, 1, 'ncrm_leaddetails:lastname:lastname:Leads_Last_Name:V'),
	(3, 2, 'ncrm_leaddetails:company:company:Leads_Company:V'),
	(3, 3, 'ncrm_leaddetails:leadsource:leadsource:Leads_Lead_Source:V'),
	(3, 4, 'ncrm_leadsubdetails:website:website:Leads_Website:V'),
	(3, 5, 'ncrm_leaddetails:email:email:Leads_Email:V'),
	(4, 1, 'ncrm_account:accountname:accountname:Accounts_Account_Name:V'),
	(4, 2, 'ncrm_accountbillads:bill_city:bill_city:Accounts_City:V'),
	(4, 3, 'ncrm_account:website:website:Accounts_Website:V'),
	(4, 4, 'ncrm_account:phone:phone:Accounts_Phone:V'),
	(4, 5, 'ncrm_crmentity:smownerid:assigned_user_id:Accounts_Assigned_To:V'),
	(5, 0, 'ncrm_account:accountname:accountname:Accounts_Account_Name:V'),
	(5, 1, 'ncrm_account:phone:phone:Accounts_Phone:V'),
	(5, 2, 'ncrm_account:website:website:Accounts_Website:V'),
	(5, 3, 'ncrm_account:rating:rating:Accounts_Rating:V'),
	(5, 4, 'ncrm_crmentity:smownerid:assigned_user_id:Accounts_Assigned_To:V'),
	(6, 0, 'ncrm_account:accountname:accountname:Accounts_Account_Name:V'),
	(6, 1, 'ncrm_account:phone:phone:Accounts_Phone:V'),
	(6, 2, 'ncrm_account:website:website:Accounts_Website:V'),
	(6, 3, 'ncrm_accountbillads:bill_city:bill_city:Accounts_City:V'),
	(6, 4, 'ncrm_crmentity:smownerid:assigned_user_id:Accounts_Assigned_To:V'),
	(7, 1, 'ncrm_contactdetails:firstname:firstname:Contacts_First_Name:V'),
	(7, 2, 'ncrm_contactdetails:lastname:lastname:Contacts_Last_Name:V'),
	(7, 3, 'ncrm_contactdetails:title:title:Contacts_Title:V'),
	(7, 4, 'ncrm_contactdetails:accountid:account_id:Contacts_Account_Name:V'),
	(7, 5, 'ncrm_contactdetails:email:email:Contacts_Email:V'),
	(7, 6, 'ncrm_contactdetails:phone:phone:Contacts_Office_Phone:V'),
	(7, 7, 'ncrm_crmentity:smownerid:assigned_user_id:Contacts_Assigned_To:V'),
	(8, 0, 'ncrm_contactdetails:firstname:firstname:Contacts_First_Name:V'),
	(8, 1, 'ncrm_contactdetails:lastname:lastname:Contacts_Last_Name:V'),
	(8, 2, 'ncrm_contactaddress:mailingstreet:mailingstreet:Contacts_Mailing_Street:V'),
	(8, 3, 'ncrm_contactaddress:mailingcity:mailingcity:Contacts_Mailing_City:V'),
	(8, 4, 'ncrm_contactaddress:mailingstate:mailingstate:Contacts_Mailing_State:V'),
	(8, 5, 'ncrm_contactaddress:mailingzip:mailingzip:Contacts_Mailing_Zip:V'),
	(8, 6, 'ncrm_contactaddress:mailingcountry:mailingcountry:Contacts_Mailing_Country:V'),
	(9, 0, 'ncrm_contactdetails:firstname:firstname:Contacts_First_Name:V'),
	(9, 1, 'ncrm_contactdetails:lastname:lastname:Contacts_Last_Name:V'),
	(9, 2, 'ncrm_contactdetails:title:title:Contacts_Title:V'),
	(9, 3, 'ncrm_contactdetails:accountid:account_id:Contacts_Account_Name:V'),
	(9, 4, 'ncrm_contactdetails:email:email:Contacts_Email:V'),
	(9, 5, 'ncrm_contactsubdetails:otherphone:otherphone:Contacts_Other_Phone:V'),
	(9, 6, 'ncrm_crmentity:smownerid:assigned_user_id:Contacts_Assigned_To:V'),
	(10, 1, 'ncrm_potential:potentialname:potentialname:Potentials_Potential_Name:V'),
	(10, 2, 'ncrm_potential:related_to:related_to:Potentials_Related_To:V'),
	(10, 3, 'ncrm_potential:sales_stage:sales_stage:Potentials_Sales_Stage:V'),
	(10, 4, 'ncrm_potential:leadsource:leadsource:Potentials_Lead_Source:V'),
	(10, 5, 'ncrm_potential:closingdate:closingdate:Potentials_Expected_Close_Date:D'),
	(10, 6, 'ncrm_potential:amount:amount:Potentials_Amount:N'),
	(10, 7, 'ncrm_crmentity:smownerid:assigned_user_id:Potentials_Assigned_To:V'),
	(10, 8, 'ncrm_potential:contact_id:contact_id:Potentials_Contact_Name:V'),
	(11, 0, 'ncrm_potential:potentialname:potentialname:Potentials_Potential_Name:V'),
	(11, 1, 'ncrm_potential:related_to:related_to:Potentials_Related_To:V'),
	(11, 2, 'ncrm_potential:amount:amount:Potentials_Amount:N'),
	(11, 3, 'ncrm_potential:closingdate:closingdate:Potentials_Expected_Close_Date:D'),
	(11, 4, 'ncrm_crmentity:smownerid:assigned_user_id:Potentials_Assigned_To:V'),
	(11, 5, 'ncrm_potential:contact_id:contact_id:Potentials_Contact_Name:V'),
	(12, 0, 'ncrm_potential:potentialname:potentialname:Potentials_Potential_Name:V'),
	(12, 1, 'ncrm_potential:related_to:related_to:Potentials_Related_To:V'),
	(12, 2, 'ncrm_potential:amount:amount:Potentials_Amount:N'),
	(12, 3, 'ncrm_potential:leadsource:leadsource:Potentials_Lead_Source:V'),
	(12, 4, 'ncrm_potential:closingdate:closingdate:Potentials_Expected_Close_Date:D'),
	(12, 5, 'ncrm_crmentity:smownerid:assigned_user_id:Potentials_Assigned_To:V'),
	(12, 6, 'ncrm_potential:contact_id:contact_id:Potentials_Contact_Name:V'),
	(13, 1, 'ncrm_troubletickets:title:ticket_title:HelpDesk_Title:V'),
	(13, 2, 'ncrm_troubletickets:parent_id:parent_id:HelpDesk_Related_To:V'),
	(13, 3, 'ncrm_troubletickets:status:ticketstatus:HelpDesk_Status:V'),
	(13, 4, 'ncrm_troubletickets:priority:ticketpriorities:HelpDesk_Priority:V'),
	(13, 5, 'ncrm_crmentity:smownerid:assigned_user_id:HelpDesk_Assigned_To:V'),
	(13, 6, 'ncrm_troubletickets:contact_id:contact_id:HelpDesk_Contact_Name:V'),
	(14, 0, 'ncrm_troubletickets:title:ticket_title:HelpDesk_Title:V'),
	(14, 1, 'ncrm_troubletickets:parent_id:parent_id:HelpDesk_Related_To:V'),
	(14, 2, 'ncrm_troubletickets:priority:ticketpriorities:HelpDesk_Priority:V'),
	(14, 3, 'ncrm_troubletickets:product_id:product_id:HelpDesk_Product_Name:V'),
	(14, 4, 'ncrm_crmentity:smownerid:assigned_user_id:HelpDesk_Assigned_To:V'),
	(14, 5, 'ncrm_troubletickets:contact_id:contact_id:HelpDesk_Contact_Name:V'),
	(15, 0, 'ncrm_troubletickets:title:ticket_title:HelpDesk_Title:V'),
	(15, 1, 'ncrm_troubletickets:parent_id:parent_id:HelpDesk_Related_To:V'),
	(15, 2, 'ncrm_troubletickets:status:ticketstatus:HelpDesk_Status:V'),
	(15, 3, 'ncrm_troubletickets:product_id:product_id:HelpDesk_Product_Name:V'),
	(15, 4, 'ncrm_crmentity:smownerid:assigned_user_id:HelpDesk_Assigned_To:V'),
	(15, 5, 'ncrm_troubletickets:contact_id:contact_id:HelpDesk_Contact_Name:V'),
	(16, 1, 'ncrm_quotes:subject:subject:Quotes_Subject:V'),
	(16, 2, 'ncrm_quotes:quotestage:quotestage:Quotes_Quote_Stage:V'),
	(16, 3, 'ncrm_quotes:potentialid:potential_id:Quotes_Potential_Name:V'),
	(16, 4, 'ncrm_quotes:accountid:account_id:Quotes_Account_Name:V'),
	(16, 5, 'ncrm_quotes:total:hdnGrandTotal:Quotes_Total:V'),
	(16, 6, 'ncrm_crmentity:smownerid:assigned_user_id:Quotes_Assigned_To:V'),
	(17, 0, 'ncrm_quotes:subject:subject:Quotes_Subject:V'),
	(17, 1, 'ncrm_quotes:quotestage:quotestage:Quotes_Quote_Stage:V'),
	(17, 2, 'ncrm_quotes:potentialid:potential_id:Quotes_Potential_Name:V'),
	(17, 3, 'ncrm_quotes:accountid:account_id:Quotes_Account_Name:V'),
	(17, 4, 'ncrm_quotes:validtill:validtill:Quotes_Valid_Till:D'),
	(17, 5, 'ncrm_crmentity:smownerid:assigned_user_id:Quotes_Assigned_To:V'),
	(18, 0, 'ncrm_quotes:subject:subject:Quotes_Subject:V'),
	(18, 1, 'ncrm_quotes:potentialid:potential_id:Quotes_Potential_Name:V'),
	(18, 2, 'ncrm_quotes:accountid:account_id:Quotes_Account_Name:V'),
	(18, 3, 'ncrm_quotes:validtill:validtill:Quotes_Valid_Till:D'),
	(18, 4, 'ncrm_crmentity:smownerid:assigned_user_id:Quotes_Assigned_To:V'),
	(19, 0, 'ncrm_activity:status:taskstatus:Calendar_Status:V'),
	(19, 1, 'ncrm_activity:activitytype:activitytype:Calendar_Type:V'),
	(19, 2, 'ncrm_activity:subject:subject:Calendar_Subject:V'),
	(19, 3, 'ncrm_seactivityrel:crmid:parent_id:Calendar_Related_to:V'),
	(19, 4, 'ncrm_activity:date_start:date_start:Calendar_Start_Date:D'),
	(19, 5, 'ncrm_activity:due_date:due_date:Calendar_End_Date:D'),
	(19, 6, 'ncrm_crmentity:smownerid:assigned_user_id:Calendar_Assigned_To:V'),
	(20, 0, 'ncrm_activity:subject:subject:Emails_Subject:V'),
	(20, 1, 'ncrm_emaildetails:to_email:saved_toid:Emails_To:V'),
	(20, 2, 'ncrm_activity:date_start:date_start:Emails_Date_Sent:D'),
	(21, 1, 'ncrm_invoice:subject:subject:Invoice_Subject:V'),
	(21, 2, 'ncrm_invoice:salesorderid:salesorder_id:Invoice_Sales_Order:V'),
	(21, 3, 'ncrm_invoice:invoicestatus:invoicestatus:Invoice_Status:V'),
	(21, 4, 'ncrm_invoice:total:hdnGrandTotal:Invoice_Total:V'),
	(21, 5, 'ncrm_crmentity:smownerid:assigned_user_id:Invoice_Assigned_To:V'),
	(22, 1, 'ncrm_notes:title:notes_title:Notes_Title:V'),
	(22, 2, 'ncrm_notes:filename:filename:Notes_File:V'),
	(22, 3, 'ncrm_crmentity:modifiedtime:modifiedtime:Notes_Modified_Time:DT'),
	(22, 4, 'ncrm_crmentity:smownerid:assigned_user_id:Notes_Assigned_To:V'),
	(23, 1, 'ncrm_pricebook:bookname:bookname:PriceBooks_Price_Book_Name:V'),
	(23, 2, 'ncrm_pricebook:active:active:PriceBooks_Active:V'),
	(23, 3, 'ncrm_pricebook:currency_id:currency_id:PriceBooks_Currency:V'),
	(24, 1, 'ncrm_products:productname:productname:Products_Product_Name:V'),
	(24, 2, 'ncrm_products:productcode:productcode:Products_Part_Number:V'),
	(24, 3, 'ncrm_products:commissionrate:commissionrate:Products_Commission_Rate:V'),
	(24, 4, 'ncrm_products:qtyinstock:qtyinstock:Products_Quantity_In_Stock:V'),
	(24, 5, 'ncrm_products:qty_per_unit:qty_per_unit:Products_Qty/Unit:V'),
	(24, 6, 'ncrm_products:unit_price:unit_price:Products_Unit_Price:V'),
	(25, 1, 'ncrm_purchaseorder:subject:subject:PurchaseOrder_Subject:V'),
	(25, 2, 'ncrm_purchaseorder:vendorid:vendor_id:PurchaseOrder_Vendor_Name:V'),
	(25, 3, 'ncrm_purchaseorder:tracking_no:tracking_no:PurchaseOrder_Tracking_Number:V'),
	(25, 4, 'ncrm_purchaseorder:total:hdnGrandTotal:PurchaseOrder_Total:V'),
	(25, 5, 'ncrm_crmentity:smownerid:assigned_user_id:PurchaseOrder_Assigned_To:V'),
	(26, 1, 'ncrm_salesorder:subject:subject:SalesOrder_Subject:V'),
	(26, 2, 'ncrm_salesorder:accountid:account_id:SalesOrder_Account_Name:V'),
	(26, 3, 'ncrm_salesorder:quoteid:quote_id:SalesOrder_Quote_Name:V'),
	(26, 4, 'ncrm_salesorder:total:hdnGrandTotal:SalesOrder_Total:V'),
	(26, 5, 'ncrm_crmentity:smownerid:assigned_user_id:SalesOrder_Assigned_To:V'),
	(27, 1, 'ncrm_vendor:vendorname:vendorname:Vendors_Vendor_Name:V'),
	(27, 2, 'ncrm_vendor:phone:phone:Vendors_Phone:V'),
	(27, 3, 'ncrm_vendor:email:email:Vendors_Email:V'),
	(27, 4, 'ncrm_vendor:category:category:Vendors_Category:V'),
	(27, 5, 'ncrm_crmentity:smownerid:assigned_user_id:Vendors_Assigned_To:V'),
	(28, 1, 'ncrm_faq:question:question:Faq_Question:V'),
	(28, 2, 'ncrm_faq:category:faqcategories:Faq_Category:V'),
	(28, 3, 'ncrm_faq:product_id:product_id:Faq_Product_Name:V'),
	(28, 4, 'ncrm_crmentity:createdtime:createdtime:Faq_Created_Time:DT'),
	(28, 5, 'ncrm_crmentity:modifiedtime:modifiedtime:Faq_Modified_Time:DT'),
	(29, 1, 'ncrm_campaign:campaignname:campaignname:Campaigns_Campaign_Name:V'),
	(29, 2, 'ncrm_campaign:campaigntype:campaigntype:Campaigns_Campaign_Type:N'),
	(29, 3, 'ncrm_campaign:campaignstatus:campaignstatus:Campaigns_Campaign_Status:N'),
	(29, 4, 'ncrm_campaign:expectedrevenue:expectedrevenue:Campaigns_Expected_Revenue:V'),
	(29, 5, 'ncrm_campaign:closingdate:closingdate:Campaigns_Expected_Close_Date:D'),
	(29, 6, 'ncrm_crmentity:smownerid:assigned_user_id:Campaigns_Assigned_To:V'),
	(30, 0, 'subject:subject:subject:Subject:V'),
	(30, 1, 'from:fromname:fromname:From:N'),
	(30, 2, 'to:tpname:toname:To:N'),
	(30, 3, 'body:body:body:Body:V'),
	(31, 0, 'ncrm_faq:question:question:Faq_Question:V'),
	(31, 1, 'ncrm_faq:status:faqstatus:Faq_Status:V'),
	(31, 2, 'ncrm_faq:product_id:product_id:Faq_Product_Name:V'),
	(31, 3, 'ncrm_faq:category:faqcategories:Faq_Category:V'),
	(31, 4, 'ncrm_crmentity:createdtime:createdtime:Faq_Created_Time:DT'),
	(32, 0, 'ncrm_faq:question:question:Faq_Question:V'),
	(32, 1, 'ncrm_faq:answer:faq_answer:Faq_Answer:V'),
	(32, 2, 'ncrm_faq:status:faqstatus:Faq_Status:V'),
	(32, 3, 'ncrm_faq:product_id:product_id:Faq_Product_Name:V'),
	(32, 4, 'ncrm_faq:category:faqcategories:Faq_Category:V'),
	(32, 5, 'ncrm_crmentity:createdtime:createdtime:Faq_Created_Time:DT'),
	(33, 0, 'ncrm_purchaseorder:subject:subject:PurchaseOrder_Subject:V'),
	(33, 1, 'ncrm_purchaseorder:postatus:postatus:PurchaseOrder_Status:V'),
	(33, 2, 'ncrm_purchaseorder:vendorid:vendor_id:PurchaseOrder_Vendor_Name:V'),
	(33, 3, 'ncrm_crmentity:smownerid:assigned_user_id:PurchaseOrder_Assigned_To:V'),
	(33, 4, 'ncrm_purchaseorder:duedate:duedate:PurchaseOrder_Due_Date:V'),
	(34, 0, 'ncrm_purchaseorder:subject:subject:PurchaseOrder_Subject:V'),
	(34, 1, 'ncrm_purchaseorder:vendorid:vendor_id:PurchaseOrder_Vendor_Name:V'),
	(34, 2, 'ncrm_crmentity:smownerid:assigned_user_id:PurchaseOrder_Assigned_To:V'),
	(34, 3, 'ncrm_purchaseorder:postatus:postatus:PurchaseOrder_Status:V'),
	(34, 4, 'ncrm_purchaseorder:carrier:carrier:PurchaseOrder_Carrier:V'),
	(34, 5, 'ncrm_poshipads:ship_street:ship_street:PurchaseOrder_Shipping_Address:V'),
	(35, 0, 'ncrm_invoice:invoice_no:invoice_no:Invoice_Invoice_No:V'),
	(35, 1, 'ncrm_invoice:subject:subject:Invoice_Subject:V'),
	(35, 2, 'ncrm_invoice:accountid:account_id:Invoice_Account_Name:V'),
	(35, 3, 'ncrm_invoice:salesorderid:salesorder_id:Invoice_Sales_Order:V'),
	(35, 4, 'ncrm_invoice:invoicestatus:invoicestatus:Invoice_Status:V'),
	(35, 5, 'ncrm_crmentity:smownerid:assigned_user_id:Invoice_Assigned_To:V'),
	(35, 6, 'ncrm_crmentity:createdtime:createdtime:Invoice_Created_Time:DT'),
	(36, 0, 'ncrm_invoice:invoice_no:invoice_no:Invoice_Invoice_No:V'),
	(36, 1, 'ncrm_invoice:subject:subject:Invoice_Subject:V'),
	(36, 2, 'ncrm_invoice:accountid:account_id:Invoice_Account_Name:V'),
	(36, 3, 'ncrm_invoice:salesorderid:salesorder_id:Invoice_Sales_Order:V'),
	(36, 4, 'ncrm_invoice:invoicestatus:invoicestatus:Invoice_Status:V'),
	(36, 5, 'ncrm_invoiceshipads:ship_street:ship_street:Invoice_Shipping_Address:V'),
	(36, 6, 'ncrm_crmentity:smownerid:assigned_user_id:Invoice_Assigned_To:V'),
	(37, 0, 'ncrm_salesorder:subject:subject:SalesOrder_Subject:V'),
	(37, 1, 'ncrm_salesorder:accountid:account_id:SalesOrder_Account_Name:V'),
	(37, 2, 'ncrm_salesorder:sostatus:sostatus:SalesOrder_Status:V'),
	(37, 3, 'ncrm_crmentity:smownerid:assigned_user_id:SalesOrder_Assigned_To:V'),
	(37, 4, 'ncrm_soshipads:ship_street:ship_street:SalesOrder_Shipping_Address:V'),
	(37, 5, 'ncrm_salesorder:carrier:carrier:SalesOrder_Carrier:V'),
	(38, 0, 'ncrm_pbxmanager:callstatus:callstatus:PBXManager_Call_Status:V'),
	(38, 1, 'ncrm_pbxmanager:customernumber:customernumber:PBXManager_Customer_Number:V'),
	(38, 2, 'ncrm_pbxmanager:customer:customer:PBXManager_Customer:V'),
	(38, 3, 'ncrm_pbxmanager:user:user:PBXManager_User:V'),
	(38, 4, 'ncrm_pbxmanager:recordingurl:recordingurl:PBXManager_Recording_URL:V'),
	(38, 5, 'ncrm_pbxmanager:totalduration:totalduration:PBXManager_Total_Duration:I'),
	(38, 6, 'ncrm_pbxmanager:starttime:starttime:PBXManager_Start_Time:DT'),
	(39, 1, 'ncrm_servicecontracts:subject:subject:ServiceContracts_Subject:V'),
	(39, 2, 'ncrm_servicecontracts:sc_related_to:sc_related_to:ServiceContracts_Related_to:V'),
	(39, 3, 'ncrm_crmentity:smownerid:assigned_user_id:ServiceContracts_Assigned_To:V'),
	(39, 4, 'ncrm_servicecontracts:start_date:start_date:ServiceContracts_Start_Date:D'),
	(39, 5, 'ncrm_servicecontracts:due_date:due_date:ServiceContracts_Due_date:D'),
	(39, 7, 'ncrm_servicecontracts:progress:progress:ServiceContracts_Progress:N'),
	(39, 8, 'ncrm_servicecontracts:contract_status:contract_status:ServiceContracts_Status:V'),
	(40, 1, 'ncrm_service:servicename:servicename:Services_Service_Name:V'),
	(40, 2, 'ncrm_service:service_usageunit:service_usageunit:Services_Usage_Unit:V'),
	(40, 3, 'ncrm_service:unit_price:unit_price:Services_Price:N'),
	(40, 4, 'ncrm_service:qty_per_unit:qty_per_unit:Services_No_of_Units:N'),
	(40, 5, 'ncrm_service:servicecategory:servicecategory:Services_Service_Category:V'),
	(40, 6, 'ncrm_crmentity:smownerid:assigned_user_id:Services_Owner:I'),
	(41, 1, 'ncrm_assets:assetname:assetname:Assets_Asset_Name:V'),
	(41, 2, 'ncrm_assets:account:account:Assets_Customer_Name:V'),
	(41, 3, 'ncrm_assets:product:product:Assets_Product_Name:V'),
	(42, 0, 'ncrm_modcomments:commentcontent:commentcontent:ModComments_Comment:V'),
	(42, 1, 'ncrm_modcomments:related_to:related_to:ModComments_Related_To:V'),
	(42, 2, 'ncrm_crmentity:modifiedtime:modifiedtime:ModComments_Modified_Time:DT'),
	(42, 3, 'ncrm_crmentity:smownerid:assigned_user_id:ModComments_Assigned_To:V'),
	(46, 0, 'ncrm_smsnotifier:message:message:SMSNotifier_message:V'),
	(46, 2, 'ncrm_crmentity:smownerid:assigned_user_id:SMSNotifier_Assigned_To:V'),
	(46, 3, 'ncrm_crmentity:createdtime:createdtime:SMSNotifier_Created_Time:DT'),
	(46, 4, 'ncrm_crmentity:modifiedtime:modifiedtime:SMSNotifier_Modified_Time:DT'),
	(47, 0, 'ncrm_projectmilestone:projectmilestonename:projectmilestonename:ProjectMilestone_Project_Milestone_Name:V'),
	(47, 1, 'ncrm_projectmilestone:projectmilestonedate:projectmilestonedate:ProjectMilestone_Milestone_Date:D'),
	(47, 3, 'ncrm_crmentity:description:description:ProjectMilestone_description:V'),
	(47, 4, 'ncrm_crmentity:createdtime:createdtime:ProjectMilestone_Created_Time:T'),
	(47, 5, 'ncrm_crmentity:modifiedtime:modifiedtime:ProjectMilestone_Modified_Time:T'),
	(48, 2, 'ncrm_projecttask:projecttaskname:projecttaskname:ProjectTask_Project_Task_Name:V'),
	(48, 3, 'ncrm_projecttask:projectid:projectid:ProjectTask_Related_to:V'),
	(48, 4, 'ncrm_projecttask:projecttaskpriority:projecttaskpriority:ProjectTask_Priority:V'),
	(48, 5, 'ncrm_projecttask:projecttaskprogress:projecttaskprogress:ProjectTask_Progress:V'),
	(48, 6, 'ncrm_projecttask:projecttaskhours:projecttaskhours:ProjectTask_Worked_Hours:V'),
	(48, 7, 'ncrm_projecttask:startdate:startdate:ProjectTask_Start_Date:D'),
	(48, 8, 'ncrm_projecttask:enddate:enddate:ProjectTask_End_Date:D'),
	(48, 9, 'ncrm_crmentity:smownerid:assigned_user_id:ProjectTask_Assigned_To:V'),
	(49, 0, 'ncrm_project:projectname:projectname:Project_Project_Name:V'),
	(49, 1, 'ncrm_project:linktoaccountscontacts:linktoaccountscontacts:Project_Related_to:V'),
	(49, 2, 'ncrm_project:startdate:startdate:Project_Start_Date:D'),
	(49, 3, 'ncrm_project:targetenddate:targetenddate:Project_Target_End_Date:D'),
	(49, 4, 'ncrm_project:actualenddate:actualenddate:Project_Actual_End_Date:D'),
	(49, 5, 'ncrm_project:targetbudget:targetbudget:Project_Target_Budget:V'),
	(49, 6, 'ncrm_project:progress:progress:Project_Progress:V'),
	(49, 7, 'ncrm_project:projectstatus:projectstatus:Project_Status:V'),
	(49, 8, 'ncrm_crmentity:smownerid:assigned_user_id:Project_Assigned_To:V'),
	(50, 0, 'ncrm_contactdetails:contact_no:contact_no:Contacts_Contact_Id:V'),
	(50, 1, 'ncrm_contactdetails:lastname:lastname:Contacts_Last_Name:V'),
	(50, 2, 'ncrm_contactscf:cf_755:cf_755:Contacts_UTM_Source:V'),
	(50, 3, 'ncrm_contactscf:cf_757:cf_757:Contacts_UTC_Team:V'),
	(50, 4, 'ncrm_contactscf:cf_759:cf_759:Contacts_UTM_Agent:V'),
	(50, 5, 'ncrm_contactscf:cf_763:cf_763:Contacts_UTM_Medium:V'),
	(50, 6, 'ncrm_contactscf:cf_761:cf_761:Contacts_UTM_Term:V'),
	(50, 7, 'ncrm_contactscf:cf_765:cf_765:Contacts_UTM_Content:V'),
	(50, 8, 'ncrm_crmentity:createdtime:createdtime:Contacts_Created_Time:DT');
/*!40000 ALTER TABLE `ncrm_cvcolumnlist` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_cvstdfilter
CREATE TABLE IF NOT EXISTS `ncrm_cvstdfilter` (
  `cvid` int(19) NOT NULL,
  `columnname` varchar(250) DEFAULT '',
  `stdfilter` varchar(250) DEFAULT '',
  `startdate` date DEFAULT NULL,
  `enddate` date DEFAULT NULL,
  PRIMARY KEY (`cvid`),
  KEY `cvstdfilter_cvid_idx` (`cvid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_cvstdfilter: ~3 rows (approximately)
/*!40000 ALTER TABLE `ncrm_cvstdfilter` DISABLE KEYS */;
INSERT INTO `ncrm_cvstdfilter` (`cvid`, `columnname`, `stdfilter`, `startdate`, `enddate`) VALUES
	(3, 'ncrm_crmentity:modifiedtime:modifiedtime:Leads_Modified_Time', 'thismonth', '2005-06-01', '2005-06-30'),
	(6, 'ncrm_crmentity:createdtime:createdtime:Accounts_Created_Time', 'thisweek', '2005-06-19', '2005-06-25'),
	(9, 'ncrm_contactsubdetails:birthday:birthday:Contacts_Birthdate', 'today', '2005-06-25', '2005-06-25');
/*!40000 ALTER TABLE `ncrm_cvstdfilter` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_datashare_grp2grp
CREATE TABLE IF NOT EXISTS `ncrm_datashare_grp2grp` (
  `shareid` int(19) NOT NULL,
  `share_groupid` int(19) DEFAULT NULL,
  `to_groupid` int(19) DEFAULT NULL,
  `permission` int(19) DEFAULT NULL,
  PRIMARY KEY (`shareid`),
  KEY `datashare_grp2grp_share_groupid_idx` (`share_groupid`),
  KEY `datashare_grp2grp_to_groupid_idx` (`to_groupid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_datashare_grp2grp: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_datashare_grp2grp` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_datashare_grp2grp` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_datashare_grp2role
CREATE TABLE IF NOT EXISTS `ncrm_datashare_grp2role` (
  `shareid` int(19) NOT NULL,
  `share_groupid` int(19) DEFAULT NULL,
  `to_roleid` varchar(255) DEFAULT NULL,
  `permission` int(19) DEFAULT NULL,
  PRIMARY KEY (`shareid`),
  KEY `idx_datashare_grp2role_share_groupid` (`share_groupid`),
  KEY `idx_datashare_grp2role_to_roleid` (`to_roleid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_datashare_grp2role: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_datashare_grp2role` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_datashare_grp2role` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_datashare_grp2rs
CREATE TABLE IF NOT EXISTS `ncrm_datashare_grp2rs` (
  `shareid` int(19) NOT NULL,
  `share_groupid` int(19) DEFAULT NULL,
  `to_roleandsubid` varchar(255) DEFAULT NULL,
  `permission` int(19) DEFAULT NULL,
  PRIMARY KEY (`shareid`),
  KEY `datashare_grp2rs_share_groupid_idx` (`share_groupid`),
  KEY `datashare_grp2rs_to_roleandsubid_idx` (`to_roleandsubid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_datashare_grp2rs: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_datashare_grp2rs` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_datashare_grp2rs` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_datashare_module_rel
CREATE TABLE IF NOT EXISTS `ncrm_datashare_module_rel` (
  `shareid` int(19) NOT NULL,
  `tabid` int(19) NOT NULL,
  `relationtype` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`shareid`),
  KEY `idx_datashare_module_rel_tabid` (`tabid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_datashare_module_rel: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_datashare_module_rel` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_datashare_module_rel` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_datashare_relatedmodules
CREATE TABLE IF NOT EXISTS `ncrm_datashare_relatedmodules` (
  `datashare_relatedmodule_id` int(19) NOT NULL,
  `tabid` int(19) DEFAULT NULL,
  `relatedto_tabid` int(19) DEFAULT NULL,
  PRIMARY KEY (`datashare_relatedmodule_id`),
  KEY `datashare_relatedmodules_tabid_idx` (`tabid`),
  KEY `datashare_relatedmodules_relatedto_tabid_idx` (`relatedto_tabid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_datashare_relatedmodules: ~9 rows (approximately)
/*!40000 ALTER TABLE `ncrm_datashare_relatedmodules` DISABLE KEYS */;
INSERT INTO `ncrm_datashare_relatedmodules` (`datashare_relatedmodule_id`, `tabid`, `relatedto_tabid`) VALUES
	(1, 6, 2),
	(2, 6, 13),
	(3, 6, 20),
	(4, 6, 22),
	(5, 6, 23),
	(6, 2, 20),
	(7, 2, 22),
	(8, 20, 22),
	(9, 22, 23);
/*!40000 ALTER TABLE `ncrm_datashare_relatedmodules` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_datashare_relatedmodules_seq
CREATE TABLE IF NOT EXISTS `ncrm_datashare_relatedmodules_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_datashare_relatedmodules_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_datashare_relatedmodules_seq` DISABLE KEYS */;
INSERT INTO `ncrm_datashare_relatedmodules_seq` (`id`) VALUES
	(9);
/*!40000 ALTER TABLE `ncrm_datashare_relatedmodules_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_datashare_relatedmodule_permission
CREATE TABLE IF NOT EXISTS `ncrm_datashare_relatedmodule_permission` (
  `shareid` int(19) NOT NULL,
  `datashare_relatedmodule_id` int(19) NOT NULL,
  `permission` int(19) DEFAULT NULL,
  PRIMARY KEY (`shareid`,`datashare_relatedmodule_id`),
  KEY `datashare_relatedmodule_permission_shareid_permissions_idx` (`shareid`,`permission`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_datashare_relatedmodule_permission: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_datashare_relatedmodule_permission` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_datashare_relatedmodule_permission` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_datashare_role2group
CREATE TABLE IF NOT EXISTS `ncrm_datashare_role2group` (
  `shareid` int(19) NOT NULL,
  `share_roleid` varchar(255) DEFAULT NULL,
  `to_groupid` int(19) DEFAULT NULL,
  `permission` int(19) DEFAULT NULL,
  PRIMARY KEY (`shareid`),
  KEY `idx_datashare_role2group_share_roleid` (`share_roleid`),
  KEY `idx_datashare_role2group_to_groupid` (`to_groupid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_datashare_role2group: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_datashare_role2group` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_datashare_role2group` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_datashare_role2role
CREATE TABLE IF NOT EXISTS `ncrm_datashare_role2role` (
  `shareid` int(19) NOT NULL,
  `share_roleid` varchar(255) DEFAULT NULL,
  `to_roleid` varchar(255) DEFAULT NULL,
  `permission` int(19) DEFAULT NULL,
  PRIMARY KEY (`shareid`),
  KEY `datashare_role2role_share_roleid_idx` (`share_roleid`),
  KEY `datashare_role2role_to_roleid_idx` (`to_roleid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_datashare_role2role: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_datashare_role2role` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_datashare_role2role` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_datashare_role2rs
CREATE TABLE IF NOT EXISTS `ncrm_datashare_role2rs` (
  `shareid` int(19) NOT NULL,
  `share_roleid` varchar(255) DEFAULT NULL,
  `to_roleandsubid` varchar(255) DEFAULT NULL,
  `permission` int(19) DEFAULT NULL,
  PRIMARY KEY (`shareid`),
  KEY `datashare_role2s_share_roleid_idx` (`share_roleid`),
  KEY `datashare_role2s_to_roleandsubid_idx` (`to_roleandsubid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_datashare_role2rs: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_datashare_role2rs` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_datashare_role2rs` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_datashare_rs2grp
CREATE TABLE IF NOT EXISTS `ncrm_datashare_rs2grp` (
  `shareid` int(19) NOT NULL,
  `share_roleandsubid` varchar(255) DEFAULT NULL,
  `to_groupid` int(19) DEFAULT NULL,
  `permission` int(19) DEFAULT NULL,
  PRIMARY KEY (`shareid`),
  KEY `datashare_rs2grp_share_roleandsubid_idx` (`share_roleandsubid`),
  KEY `datashare_rs2grp_to_groupid_idx` (`to_groupid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_datashare_rs2grp: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_datashare_rs2grp` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_datashare_rs2grp` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_datashare_rs2role
CREATE TABLE IF NOT EXISTS `ncrm_datashare_rs2role` (
  `shareid` int(19) NOT NULL,
  `share_roleandsubid` varchar(255) DEFAULT NULL,
  `to_roleid` varchar(255) DEFAULT NULL,
  `permission` int(19) DEFAULT NULL,
  PRIMARY KEY (`shareid`),
  KEY `datashare_rs2role_share_roleandsubid_idx` (`share_roleandsubid`),
  KEY `datashare_rs2role_to_roleid_idx` (`to_roleid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_datashare_rs2role: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_datashare_rs2role` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_datashare_rs2role` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_datashare_rs2rs
CREATE TABLE IF NOT EXISTS `ncrm_datashare_rs2rs` (
  `shareid` int(19) NOT NULL,
  `share_roleandsubid` varchar(255) DEFAULT NULL,
  `to_roleandsubid` varchar(255) DEFAULT NULL,
  `permission` int(19) DEFAULT NULL,
  PRIMARY KEY (`shareid`),
  KEY `datashare_rs2rs_share_roleandsubid_idx` (`share_roleandsubid`),
  KEY `idx_datashare_rs2rs_to_roleandsubid_idx` (`to_roleandsubid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_datashare_rs2rs: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_datashare_rs2rs` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_datashare_rs2rs` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_date_format
CREATE TABLE IF NOT EXISTS `ncrm_date_format` (
  `date_formatid` int(19) NOT NULL AUTO_INCREMENT,
  `date_format` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`date_formatid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_date_format: ~3 rows (approximately)
/*!40000 ALTER TABLE `ncrm_date_format` DISABLE KEYS */;
INSERT INTO `ncrm_date_format` (`date_formatid`, `date_format`, `sortorderid`, `presence`) VALUES
	(1, 'dd-mm-yyyy', 0, 1),
	(2, 'mm-dd-yyyy', 1, 1),
	(3, 'yyyy-mm-dd', 2, 1);
/*!40000 ALTER TABLE `ncrm_date_format` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_date_format_seq
CREATE TABLE IF NOT EXISTS `ncrm_date_format_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_date_format_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_date_format_seq` DISABLE KEYS */;
INSERT INTO `ncrm_date_format_seq` (`id`) VALUES
	(3);
/*!40000 ALTER TABLE `ncrm_date_format_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_dayoftheweek
CREATE TABLE IF NOT EXISTS `ncrm_dayoftheweek` (
  `dayoftheweekid` int(11) NOT NULL AUTO_INCREMENT,
  `dayoftheweek` varchar(200) NOT NULL,
  `sortorderid` int(11) DEFAULT NULL,
  `presence` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`dayoftheweekid`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_dayoftheweek: ~7 rows (approximately)
/*!40000 ALTER TABLE `ncrm_dayoftheweek` DISABLE KEYS */;
INSERT INTO `ncrm_dayoftheweek` (`dayoftheweekid`, `dayoftheweek`, `sortorderid`, `presence`) VALUES
	(1, 'Sunday', 1, 1),
	(2, 'Monday', 2, 1),
	(3, 'Tuesday', 3, 1),
	(4, 'Wednesday', 4, 1),
	(5, 'Thursday', 5, 1),
	(6, 'Friday', 6, 1),
	(7, 'Saturday', 7, 1);
/*!40000 ALTER TABLE `ncrm_dayoftheweek` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_dayoftheweek_seq
CREATE TABLE IF NOT EXISTS `ncrm_dayoftheweek_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_dayoftheweek_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_dayoftheweek_seq` DISABLE KEYS */;
INSERT INTO `ncrm_dayoftheweek_seq` (`id`) VALUES
	(7);
/*!40000 ALTER TABLE `ncrm_dayoftheweek_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_defaultactivitytype
CREATE TABLE IF NOT EXISTS `ncrm_defaultactivitytype` (
  `defaultactivitytypeid` int(11) NOT NULL AUTO_INCREMENT,
  `defaultactivitytype` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(11) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT '0',
  PRIMARY KEY (`defaultactivitytypeid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_defaultactivitytype: ~2 rows (approximately)
/*!40000 ALTER TABLE `ncrm_defaultactivitytype` DISABLE KEYS */;
INSERT INTO `ncrm_defaultactivitytype` (`defaultactivitytypeid`, `defaultactivitytype`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(1, 'Call', 1, 299, 1),
	(2, 'Meeting', 1, 300, 2);
/*!40000 ALTER TABLE `ncrm_defaultactivitytype` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_defaultactivitytype_seq
CREATE TABLE IF NOT EXISTS `ncrm_defaultactivitytype_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_defaultactivitytype_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_defaultactivitytype_seq` DISABLE KEYS */;
INSERT INTO `ncrm_defaultactivitytype_seq` (`id`) VALUES
	(2);
/*!40000 ALTER TABLE `ncrm_defaultactivitytype_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_defaultcv
CREATE TABLE IF NOT EXISTS `ncrm_defaultcv` (
  `tabid` int(19) NOT NULL,
  `defaultviewname` varchar(50) NOT NULL,
  `query` text,
  PRIMARY KEY (`tabid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_defaultcv: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_defaultcv` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_defaultcv` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_defaulteventstatus
CREATE TABLE IF NOT EXISTS `ncrm_defaulteventstatus` (
  `defaulteventstatusid` int(11) NOT NULL AUTO_INCREMENT,
  `defaulteventstatus` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(11) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT '0',
  PRIMARY KEY (`defaulteventstatusid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_defaulteventstatus: ~3 rows (approximately)
/*!40000 ALTER TABLE `ncrm_defaulteventstatus` DISABLE KEYS */;
INSERT INTO `ncrm_defaulteventstatus` (`defaulteventstatusid`, `defaulteventstatus`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(1, 'Planned', 1, 296, 1),
	(2, 'Held', 1, 297, 2),
	(3, 'Not Held', 1, 298, 3);
/*!40000 ALTER TABLE `ncrm_defaulteventstatus` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_defaulteventstatus_seq
CREATE TABLE IF NOT EXISTS `ncrm_defaulteventstatus_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_defaulteventstatus_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_defaulteventstatus_seq` DISABLE KEYS */;
INSERT INTO `ncrm_defaulteventstatus_seq` (`id`) VALUES
	(3);
/*!40000 ALTER TABLE `ncrm_defaulteventstatus_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_default_record_view
CREATE TABLE IF NOT EXISTS `ncrm_default_record_view` (
  `default_record_viewid` int(11) NOT NULL AUTO_INCREMENT,
  `default_record_view` varchar(200) NOT NULL,
  `sortorderid` int(11) DEFAULT NULL,
  `presence` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`default_record_viewid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_default_record_view: ~2 rows (approximately)
/*!40000 ALTER TABLE `ncrm_default_record_view` DISABLE KEYS */;
INSERT INTO `ncrm_default_record_view` (`default_record_viewid`, `default_record_view`, `sortorderid`, `presence`) VALUES
	(1, 'Summary', 1, 1),
	(2, 'Detail', 2, 1);
/*!40000 ALTER TABLE `ncrm_default_record_view` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_default_record_view_seq
CREATE TABLE IF NOT EXISTS `ncrm_default_record_view_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_default_record_view_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_default_record_view_seq` DISABLE KEYS */;
INSERT INTO `ncrm_default_record_view_seq` (`id`) VALUES
	(2);
/*!40000 ALTER TABLE `ncrm_default_record_view_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_def_org_field
CREATE TABLE IF NOT EXISTS `ncrm_def_org_field` (
  `tabid` int(10) DEFAULT NULL,
  `fieldid` int(19) NOT NULL,
  `visible` int(19) DEFAULT NULL,
  `readonly` int(19) DEFAULT NULL,
  PRIMARY KEY (`fieldid`),
  KEY `def_org_field_tabid_fieldid_idx` (`tabid`,`fieldid`),
  KEY `def_org_field_tabid_idx` (`tabid`),
  KEY `def_org_field_visible_fieldid_idx` (`visible`,`fieldid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_def_org_field: ~699 rows (approximately)
/*!40000 ALTER TABLE `ncrm_def_org_field` DISABLE KEYS */;
INSERT INTO `ncrm_def_org_field` (`tabid`, `fieldid`, `visible`, `readonly`) VALUES
	(6, 1, 0, 0),
	(6, 2, 0, 0),
	(6, 3, 0, 0),
	(6, 4, 0, 0),
	(6, 5, 0, 0),
	(6, 6, 0, 0),
	(6, 7, 0, 0),
	(6, 8, 0, 0),
	(6, 9, 0, 0),
	(6, 10, 0, 0),
	(6, 11, 0, 0),
	(6, 12, 0, 0),
	(6, 13, 0, 0),
	(6, 14, 0, 0),
	(6, 15, 0, 0),
	(6, 16, 0, 0),
	(6, 17, 0, 0),
	(6, 18, 0, 0),
	(6, 19, 0, 0),
	(6, 20, 0, 0),
	(6, 21, 0, 0),
	(6, 22, 0, 0),
	(6, 23, 0, 0),
	(6, 24, 0, 0),
	(6, 25, 0, 0),
	(6, 26, 0, 0),
	(6, 27, 0, 0),
	(6, 28, 0, 0),
	(6, 29, 0, 0),
	(6, 30, 0, 0),
	(6, 31, 0, 0),
	(6, 32, 0, 0),
	(6, 33, 0, 0),
	(6, 34, 0, 0),
	(6, 35, 0, 0),
	(6, 36, 0, 0),
	(7, 37, 0, 0),
	(7, 38, 0, 0),
	(7, 39, 0, 0),
	(7, 40, 0, 0),
	(7, 41, 0, 0),
	(7, 42, 0, 0),
	(7, 43, 0, 0),
	(7, 44, 0, 0),
	(7, 45, 0, 0),
	(7, 46, 0, 0),
	(7, 47, 0, 0),
	(7, 48, 0, 0),
	(7, 49, 0, 0),
	(7, 50, 0, 0),
	(7, 51, 0, 0),
	(7, 52, 0, 0),
	(7, 53, 0, 0),
	(7, 54, 0, 0),
	(7, 55, 0, 0),
	(7, 56, 0, 0),
	(7, 57, 0, 0),
	(7, 58, 0, 0),
	(7, 59, 0, 0),
	(7, 60, 0, 0),
	(7, 61, 0, 0),
	(7, 62, 0, 0),
	(7, 63, 0, 0),
	(7, 64, 0, 0),
	(7, 65, 0, 0),
	(4, 66, 0, 0),
	(4, 67, 0, 0),
	(4, 68, 0, 0),
	(4, 69, 0, 0),
	(4, 70, 0, 0),
	(4, 71, 0, 0),
	(4, 72, 0, 0),
	(4, 73, 0, 0),
	(4, 74, 0, 0),
	(4, 75, 0, 0),
	(4, 76, 0, 0),
	(4, 77, 0, 0),
	(4, 78, 0, 0),
	(4, 79, 0, 0),
	(4, 80, 0, 0),
	(4, 81, 0, 0),
	(4, 82, 0, 0),
	(4, 83, 0, 0),
	(4, 84, 0, 0),
	(4, 85, 0, 0),
	(4, 86, 0, 0),
	(4, 87, 0, 0),
	(4, 88, 0, 0),
	(4, 89, 0, 0),
	(4, 90, 0, 0),
	(4, 91, 0, 0),
	(4, 92, 0, 0),
	(4, 93, 0, 0),
	(4, 94, 0, 0),
	(4, 95, 0, 0),
	(4, 96, 0, 0),
	(4, 97, 0, 0),
	(4, 98, 0, 0),
	(4, 99, 0, 0),
	(4, 100, 0, 0),
	(4, 101, 0, 0),
	(4, 102, 0, 0),
	(4, 103, 0, 0),
	(4, 104, 0, 0),
	(4, 105, 0, 0),
	(4, 106, 0, 0),
	(4, 107, 0, 0),
	(4, 108, 0, 0),
	(4, 109, 0, 0),
	(2, 110, 0, 0),
	(2, 111, 0, 0),
	(2, 112, 0, 0),
	(2, 113, 0, 0),
	(2, 114, 0, 0),
	(2, 115, 0, 0),
	(2, 116, 0, 0),
	(2, 117, 0, 0),
	(2, 118, 0, 0),
	(2, 119, 0, 0),
	(2, 120, 0, 0),
	(2, 121, 0, 0),
	(2, 122, 0, 0),
	(2, 123, 0, 0),
	(2, 124, 0, 0),
	(2, 125, 0, 0),
	(26, 126, 0, 0),
	(26, 127, 0, 0),
	(26, 128, 0, 0),
	(26, 129, 0, 0),
	(26, 130, 0, 0),
	(26, 131, 0, 0),
	(26, 132, 0, 0),
	(26, 133, 0, 0),
	(26, 134, 0, 0),
	(26, 135, 0, 0),
	(26, 136, 0, 0),
	(26, 137, 0, 0),
	(26, 138, 0, 0),
	(26, 139, 0, 0),
	(26, 140, 0, 0),
	(26, 141, 0, 0),
	(26, 142, 0, 0),
	(26, 143, 0, 0),
	(26, 144, 0, 0),
	(26, 145, 0, 0),
	(26, 146, 0, 0),
	(26, 147, 0, 0),
	(26, 148, 0, 0),
	(26, 149, 0, 0),
	(26, 150, 0, 0),
	(4, 151, 0, 0),
	(6, 152, 0, 0),
	(7, 153, 0, 0),
	(26, 154, 0, 0),
	(13, 155, 0, 0),
	(13, 156, 0, 0),
	(13, 157, 0, 0),
	(13, 158, 0, 0),
	(13, 159, 0, 0),
	(13, 160, 0, 0),
	(13, 161, 0, 0),
	(13, 162, 0, 0),
	(13, 163, 0, 0),
	(13, 164, 0, 0),
	(13, 165, 0, 0),
	(13, 166, 0, 0),
	(13, 167, 0, 0),
	(13, 168, 0, 0),
	(13, 169, 0, 0),
	(13, 170, 0, 0),
	(13, 171, 0, 0),
	(13, 172, 0, 0),
	(14, 173, 0, 0),
	(14, 174, 0, 0),
	(14, 175, 0, 0),
	(14, 176, 0, 0),
	(14, 177, 0, 0),
	(14, 178, 0, 0),
	(14, 179, 0, 0),
	(14, 180, 0, 0),
	(14, 181, 0, 0),
	(14, 182, 0, 0),
	(14, 183, 0, 0),
	(14, 184, 0, 0),
	(14, 185, 0, 0),
	(14, 186, 0, 0),
	(14, 187, 0, 0),
	(14, 188, 0, 0),
	(14, 189, 0, 0),
	(14, 190, 0, 0),
	(14, 191, 0, 0),
	(14, 192, 0, 0),
	(14, 193, 0, 0),
	(14, 194, 0, 0),
	(14, 195, 0, 0),
	(14, 196, 0, 0),
	(14, 197, 0, 0),
	(14, 198, 0, 0),
	(14, 199, 0, 0),
	(14, 200, 0, 0),
	(14, 201, 0, 0),
	(14, 202, 0, 0),
	(14, 203, 0, 0),
	(8, 204, 0, 0),
	(8, 205, 0, 0),
	(8, 206, 0, 0),
	(8, 207, 0, 0),
	(8, 208, 0, 0),
	(8, 209, 0, 0),
	(8, 210, 0, 0),
	(8, 211, 0, 0),
	(8, 212, 0, 0),
	(8, 213, 0, 0),
	(8, 214, 0, 0),
	(8, 215, 0, 0),
	(8, 216, 0, 0),
	(8, 217, 0, 0),
	(8, 218, 0, 0),
	(10, 219, 0, 0),
	(10, 220, 0, 0),
	(10, 221, 0, 0),
	(10, 222, 0, 0),
	(10, 223, 0, 0),
	(10, 224, 0, 0),
	(10, 225, 0, 0),
	(10, 226, 0, 0),
	(10, 227, 0, 0),
	(10, 228, 0, 0),
	(10, 229, 0, 0),
	(10, 230, 0, 0),
	(9, 231, 0, 0),
	(9, 232, 0, 0),
	(9, 233, 0, 0),
	(9, 234, 0, 0),
	(9, 235, 0, 0),
	(9, 236, 0, 0),
	(9, 237, 0, 0),
	(9, 238, 0, 0),
	(9, 239, 0, 0),
	(9, 240, 0, 0),
	(9, 241, 0, 0),
	(9, 242, 0, 0),
	(9, 243, 0, 0),
	(9, 244, 0, 0),
	(9, 245, 0, 0),
	(9, 246, 0, 0),
	(9, 247, 0, 0),
	(9, 248, 0, 0),
	(9, 249, 0, 0),
	(9, 250, 0, 0),
	(9, 251, 0, 0),
	(9, 252, 0, 0),
	(9, 253, 0, 0),
	(9, 254, 0, 0),
	(16, 255, 0, 0),
	(16, 256, 0, 0),
	(16, 257, 0, 0),
	(16, 258, 0, 0),
	(16, 259, 0, 0),
	(16, 260, 0, 0),
	(16, 261, 0, 0),
	(16, 262, 0, 0),
	(16, 263, 0, 0),
	(16, 264, 0, 0),
	(16, 265, 0, 0),
	(16, 266, 0, 0),
	(16, 267, 0, 0),
	(16, 268, 0, 0),
	(16, 269, 0, 0),
	(16, 270, 0, 0),
	(16, 271, 0, 0),
	(16, 272, 0, 0),
	(16, 273, 0, 0),
	(16, 274, 0, 0),
	(16, 275, 0, 0),
	(16, 276, 0, 0),
	(16, 277, 0, 0),
	(15, 278, 0, 0),
	(15, 279, 0, 0),
	(15, 280, 0, 0),
	(15, 281, 0, 0),
	(15, 282, 0, 0),
	(15, 283, 0, 0),
	(15, 284, 0, 0),
	(15, 285, 0, 0),
	(15, 286, 0, 0),
	(15, 287, 0, 0),
	(18, 288, 0, 0),
	(18, 289, 0, 0),
	(18, 290, 0, 0),
	(18, 291, 0, 0),
	(18, 292, 0, 0),
	(18, 293, 0, 0),
	(18, 294, 0, 0),
	(18, 295, 0, 0),
	(18, 296, 0, 0),
	(18, 297, 0, 0),
	(18, 298, 0, 0),
	(18, 299, 0, 0),
	(18, 300, 0, 0),
	(18, 301, 0, 0),
	(18, 302, 0, 0),
	(18, 303, 0, 0),
	(18, 304, 0, 0),
	(19, 305, 0, 0),
	(19, 306, 0, 0),
	(19, 307, 0, 0),
	(19, 308, 0, 0),
	(19, 309, 0, 0),
	(19, 310, 0, 0),
	(19, 311, 0, 0),
	(19, 312, 0, 0),
	(20, 313, 0, 0),
	(20, 314, 0, 0),
	(20, 315, 0, 0),
	(20, 316, 0, 0),
	(20, 317, 0, 0),
	(20, 318, 0, 0),
	(20, 319, 0, 0),
	(20, 320, 0, 0),
	(20, 321, 0, 0),
	(20, 322, 0, 0),
	(20, 323, 0, 0),
	(20, 324, 0, 0),
	(20, 325, 0, 0),
	(20, 326, 0, 0),
	(20, 327, 0, 0),
	(20, 328, 0, 0),
	(20, 329, 0, 0),
	(20, 330, 0, 0),
	(20, 331, 0, 0),
	(20, 332, 0, 0),
	(20, 333, 0, 0),
	(20, 334, 0, 0),
	(20, 335, 0, 0),
	(20, 336, 0, 0),
	(20, 337, 0, 0),
	(20, 338, 0, 0),
	(20, 339, 0, 0),
	(20, 340, 0, 0),
	(20, 341, 0, 0),
	(20, 342, 0, 0),
	(20, 343, 0, 0),
	(20, 344, 0, 0),
	(20, 345, 0, 0),
	(20, 346, 0, 0),
	(20, 347, 0, 0),
	(20, 348, 0, 0),
	(20, 349, 0, 0),
	(21, 350, 0, 0),
	(21, 351, 0, 0),
	(21, 352, 0, 0),
	(21, 353, 0, 0),
	(21, 354, 0, 0),
	(21, 355, 0, 0),
	(21, 356, 0, 0),
	(21, 357, 0, 0),
	(21, 358, 0, 0),
	(21, 359, 0, 0),
	(21, 360, 0, 0),
	(21, 361, 0, 0),
	(21, 362, 0, 0),
	(21, 363, 0, 0),
	(21, 364, 0, 0),
	(21, 365, 0, 0),
	(21, 366, 0, 0),
	(21, 367, 0, 0),
	(21, 368, 0, 0),
	(21, 369, 0, 0),
	(21, 370, 0, 0),
	(21, 371, 0, 0),
	(21, 372, 0, 0),
	(21, 373, 0, 0),
	(21, 374, 0, 0),
	(21, 375, 0, 0),
	(21, 376, 0, 0),
	(21, 377, 0, 0),
	(21, 378, 0, 0),
	(21, 379, 0, 0),
	(21, 380, 0, 0),
	(21, 381, 0, 0),
	(21, 382, 0, 0),
	(21, 383, 0, 0),
	(21, 384, 0, 0),
	(21, 385, 0, 0),
	(21, 386, 0, 0),
	(21, 387, 0, 0),
	(22, 388, 0, 0),
	(22, 389, 0, 0),
	(22, 390, 0, 0),
	(22, 391, 0, 0),
	(22, 392, 0, 0),
	(22, 393, 0, 0),
	(22, 394, 0, 0),
	(22, 395, 0, 0),
	(22, 396, 0, 0),
	(22, 397, 0, 0),
	(22, 398, 0, 0),
	(22, 399, 0, 0),
	(22, 400, 0, 0),
	(22, 401, 0, 0),
	(22, 402, 0, 0),
	(22, 403, 0, 0),
	(22, 404, 0, 0),
	(22, 405, 0, 0),
	(22, 406, 0, 0),
	(22, 407, 0, 0),
	(22, 408, 0, 0),
	(22, 409, 0, 0),
	(22, 410, 0, 0),
	(22, 411, 0, 0),
	(22, 412, 0, 0),
	(22, 413, 0, 0),
	(22, 414, 0, 0),
	(22, 415, 0, 0),
	(22, 416, 0, 0),
	(22, 417, 0, 0),
	(22, 418, 0, 0),
	(22, 419, 0, 0),
	(22, 420, 0, 0),
	(22, 421, 0, 0),
	(22, 422, 0, 0),
	(22, 423, 0, 0),
	(22, 424, 0, 0),
	(22, 425, 0, 0),
	(22, 426, 0, 0),
	(22, 427, 0, 0),
	(22, 428, 0, 0),
	(22, 429, 0, 0),
	(22, 430, 0, 0),
	(22, 431, 0, 0),
	(22, 432, 0, 0),
	(22, 433, 0, 0),
	(22, 434, 0, 0),
	(23, 435, 0, 0),
	(23, 436, 0, 0),
	(23, 437, 0, 0),
	(23, 438, 0, 0),
	(23, 439, 0, 0),
	(23, 440, 0, 0),
	(23, 441, 0, 0),
	(23, 442, 0, 0),
	(23, 443, 0, 0),
	(23, 444, 0, 0),
	(23, 445, 0, 0),
	(23, 446, 0, 0),
	(23, 447, 0, 0),
	(23, 448, 0, 0),
	(23, 449, 0, 0),
	(23, 450, 0, 0),
	(23, 451, 0, 0),
	(23, 452, 0, 0),
	(23, 453, 0, 0),
	(23, 454, 0, 0),
	(23, 455, 0, 0),
	(23, 456, 0, 0),
	(23, 457, 0, 0),
	(23, 458, 0, 0),
	(23, 459, 0, 0),
	(23, 460, 0, 0),
	(23, 461, 0, 0),
	(23, 462, 0, 0),
	(23, 463, 0, 0),
	(23, 464, 0, 0),
	(23, 465, 0, 0),
	(23, 466, 0, 0),
	(23, 467, 0, 0),
	(23, 468, 0, 0),
	(23, 469, 0, 0),
	(23, 470, 0, 0),
	(23, 471, 0, 0),
	(23, 472, 0, 0),
	(23, 473, 0, 0),
	(29, 474, 0, 0),
	(29, 478, 0, 0),
	(29, 479, 0, 0),
	(29, 481, 0, 0),
	(29, 488, 0, 0),
	(29, 489, 0, 0),
	(29, 490, 0, 0),
	(29, 491, 0, 0),
	(29, 493, 0, 0),
	(29, 494, 0, 0),
	(29, 495, 0, 0),
	(29, 496, 0, 0),
	(29, 497, 0, 0),
	(29, 502, 0, 0),
	(29, 503, 0, 0),
	(29, 504, 0, 0),
	(29, 505, 0, 0),
	(29, 513, 0, 0),
	(10, 518, 0, 0),
	(10, 519, 0, 0),
	(10, 520, 0, 0),
	(10, 521, 0, 0),
	(10, 522, 0, 0),
	(10, 523, 0, 0),
	(34, 524, 0, 0),
	(34, 525, 0, 0),
	(34, 526, 0, 0),
	(34, 527, 0, 0),
	(34, 528, 0, 0),
	(34, 529, 0, 0),
	(34, 530, 0, 0),
	(34, 531, 0, 0),
	(34, 532, 0, 0),
	(34, 533, 0, 0),
	(34, 534, 0, 0),
	(34, 535, 0, 0),
	(34, 536, 0, 0),
	(34, 537, 0, 0),
	(34, 538, 0, 0),
	(34, 539, 0, 0),
	(29, 540, 0, 0),
	(35, 541, 0, 0),
	(35, 542, 0, 0),
	(35, 543, 0, 0),
	(35, 544, 0, 0),
	(35, 545, 0, 0),
	(35, 546, 0, 0),
	(35, 547, 0, 0),
	(35, 548, 0, 0),
	(35, 549, 0, 0),
	(35, 550, 0, 0),
	(35, 551, 0, 0),
	(35, 552, 0, 0),
	(35, 553, 0, 0),
	(35, 554, 0, 0),
	(35, 555, 0, 0),
	(35, 556, 0, 0),
	(35, 557, 0, 0),
	(35, 558, 0, 0),
	(35, 559, 0, 0),
	(36, 560, 0, 0),
	(36, 561, 0, 0),
	(36, 562, 0, 0),
	(36, 563, 0, 0),
	(36, 564, 0, 0),
	(36, 565, 0, 0),
	(36, 566, 0, 0),
	(36, 567, 0, 0),
	(36, 568, 0, 0),
	(36, 569, 0, 0),
	(36, 570, 0, 0),
	(36, 571, 0, 0),
	(36, 572, 0, 0),
	(36, 573, 0, 0),
	(36, 574, 0, 0),
	(36, 575, 0, 0),
	(36, 576, 0, 0),
	(36, 577, 0, 0),
	(36, 578, 0, 0),
	(38, 579, 0, 0),
	(38, 580, 0, 0),
	(38, 581, 0, 0),
	(38, 582, 0, 0),
	(38, 583, 0, 0),
	(38, 584, 0, 0),
	(38, 585, 0, 0),
	(38, 586, 0, 0),
	(38, 587, 0, 0),
	(38, 588, 0, 0),
	(38, 589, 0, 0),
	(38, 590, 0, 0),
	(38, 591, 0, 0),
	(38, 592, 0, 0),
	(38, 593, 0, 0),
	(38, 594, 0, 0),
	(38, 595, 0, 0),
	(38, 596, 0, 0),
	(42, 597, 0, 0),
	(42, 598, 0, 0),
	(42, 599, 0, 0),
	(42, 600, 0, 0),
	(42, 601, 0, 0),
	(42, 602, 0, 0),
	(42, 603, 0, 0),
	(43, 604, 0, 0),
	(43, 605, 0, 0),
	(43, 606, 0, 0),
	(43, 607, 0, 0),
	(43, 608, 0, 0),
	(43, 609, 0, 0),
	(43, 610, 0, 0),
	(43, 611, 0, 0),
	(43, 612, 0, 0),
	(43, 613, 0, 0),
	(44, 614, 0, 0),
	(44, 615, 0, 0),
	(44, 616, 0, 0),
	(44, 617, 0, 0),
	(44, 618, 0, 0),
	(44, 619, 0, 0),
	(44, 620, 0, 0),
	(44, 621, 0, 0),
	(44, 622, 0, 0),
	(44, 623, 0, 0),
	(44, 624, 0, 0),
	(44, 625, 0, 0),
	(44, 626, 0, 0),
	(44, 627, 0, 0),
	(44, 628, 0, 0),
	(45, 629, 0, 0),
	(45, 630, 0, 0),
	(45, 631, 0, 0),
	(45, 632, 0, 0),
	(45, 633, 0, 0),
	(45, 634, 0, 0),
	(45, 635, 0, 0),
	(45, 636, 0, 0),
	(45, 637, 0, 0),
	(45, 638, 0, 0),
	(45, 639, 0, 0),
	(45, 640, 0, 0),
	(45, 641, 0, 0),
	(45, 642, 0, 0),
	(45, 643, 0, 0),
	(45, 644, 0, 0),
	(45, 645, 0, 0),
	(47, 646, 0, 0),
	(47, 647, 0, 0),
	(47, 648, 0, 0),
	(47, 649, 0, 0),
	(47, 650, 0, 0),
	(2, 651, 0, 0),
	(29, 652, 0, 0),
	(23, 653, 0, 0),
	(23, 654, 0, 0),
	(23, 655, 0, 0),
	(23, 656, 0, 0),
	(23, 657, 0, 0),
	(23, 658, 0, 0),
	(23, 659, 0, 0),
	(23, 660, 0, 0),
	(23, 661, 0, 0),
	(22, 662, 0, 0),
	(22, 663, 0, 0),
	(22, 664, 0, 0),
	(22, 665, 0, 0),
	(22, 666, 0, 0),
	(22, 667, 0, 0),
	(22, 668, 0, 0),
	(22, 669, 0, 0),
	(22, 670, 0, 0),
	(21, 671, 0, 0),
	(21, 672, 0, 0),
	(21, 673, 0, 0),
	(21, 674, 0, 0),
	(21, 675, 0, 0),
	(21, 676, 0, 0),
	(21, 677, 0, 0),
	(21, 678, 0, 0),
	(21, 679, 0, 0),
	(20, 680, 0, 0),
	(20, 681, 0, 0),
	(20, 682, 0, 0),
	(20, 683, 0, 0),
	(20, 684, 0, 0),
	(20, 685, 0, 0),
	(20, 686, 0, 0),
	(20, 687, 0, 0),
	(20, 688, 0, 0),
	(29, 689, 0, 0),
	(44, 690, 0, 0),
	(42, 691, 0, 0),
	(29, 692, 0, 0),
	(29, 693, 0, 0),
	(29, 694, 0, 0),
	(23, 695, 0, 0),
	(22, 696, 0, 0),
	(21, 697, 0, 0),
	(20, 698, 0, 0),
	(29, 699, 0, 0),
	(6, 700, 0, 0),
	(4, 701, 0, 0),
	(2, 702, 0, 0),
	(29, 703, 0, 0),
	(23, 704, 0, 0),
	(23, 705, 0, 0),
	(21, 706, 0, 0),
	(21, 707, 0, 0),
	(18, 708, 0, 0),
	(7, 709, 0, 0),
	(42, 710, 0, 0),
	(42, 711, 0, 0),
	(23, 712, 0, 0),
	(20, 713, 0, 0),
	(21, 714, 0, 0),
	(22, 715, 0, 0),
	(29, 716, 0, 0),
	(2, 717, 0, 0),
	(13, 718, 0, 0),
	(29, 719, 0, 0),
	(13, 720, 0, 0),
	(29, 721, 0, 0),
	(29, 722, 0, 0),
	(29, 723, 0, 0),
	(9, 729, 0, 0),
	(29, 750, 0, 0),
	(4, 752, 0, 0),
	(4, 754, 0, 0),
	(4, 756, 0, 0),
	(4, 758, 0, 0),
	(4, 760, 0, 0),
	(4, 762, 0, 0),
	(4, 764, 0, 0),
	(4, 766, 0, 0),
	(4, 770, 0, 0);
/*!40000 ALTER TABLE `ncrm_def_org_field` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_def_org_share
CREATE TABLE IF NOT EXISTS `ncrm_def_org_share` (
  `ruleid` int(11) NOT NULL AUTO_INCREMENT,
  `tabid` int(11) NOT NULL,
  `permission` int(19) DEFAULT NULL,
  `editstatus` int(19) DEFAULT NULL,
  PRIMARY KEY (`ruleid`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_def_org_share: ~25 rows (approximately)
/*!40000 ALTER TABLE `ncrm_def_org_share` DISABLE KEYS */;
INSERT INTO `ncrm_def_org_share` (`ruleid`, `tabid`, `permission`, `editstatus`) VALUES
	(1, 2, 2, 0),
	(2, 4, 2, 2),
	(3, 6, 2, 0),
	(4, 7, 2, 0),
	(5, 9, 3, 1),
	(6, 13, 2, 0),
	(7, 16, 3, 2),
	(8, 20, 2, 0),
	(9, 21, 2, 0),
	(10, 22, 2, 0),
	(11, 23, 2, 0),
	(12, 26, 2, 0),
	(13, 8, 2, 0),
	(14, 14, 2, 0),
	(15, 34, 3, 0),
	(16, 35, 2, 0),
	(17, 36, 2, 0),
	(18, 38, 2, 0),
	(19, 42, 2, 0),
	(20, 43, 2, 0),
	(21, 44, 2, 0),
	(22, 45, 2, 0),
	(23, 47, 2, 0),
	(24, 18, 2, 0),
	(25, 10, 2, 0);
/*!40000 ALTER TABLE `ncrm_def_org_share` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_def_org_share_seq
CREATE TABLE IF NOT EXISTS `ncrm_def_org_share_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_def_org_share_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_def_org_share_seq` DISABLE KEYS */;
INSERT INTO `ncrm_def_org_share_seq` (`id`) VALUES
	(25);
/*!40000 ALTER TABLE `ncrm_def_org_share_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_durationhrs
CREATE TABLE IF NOT EXISTS `ncrm_durationhrs` (
  `hrsid` int(19) NOT NULL AUTO_INCREMENT,
  `hrs` varchar(50) DEFAULT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`hrsid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_durationhrs: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_durationhrs` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_durationhrs` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_durationmins
CREATE TABLE IF NOT EXISTS `ncrm_durationmins` (
  `minsid` int(19) NOT NULL AUTO_INCREMENT,
  `mins` varchar(50) DEFAULT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`minsid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_durationmins: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_durationmins` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_durationmins` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_duration_minutes
CREATE TABLE IF NOT EXISTS `ncrm_duration_minutes` (
  `minutesid` int(19) NOT NULL AUTO_INCREMENT,
  `duration_minutes` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`minutesid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_duration_minutes: ~4 rows (approximately)
/*!40000 ALTER TABLE `ncrm_duration_minutes` DISABLE KEYS */;
INSERT INTO `ncrm_duration_minutes` (`minutesid`, `duration_minutes`, `sortorderid`, `presence`) VALUES
	(1, '00', 0, 1),
	(2, '15', 1, 1),
	(3, '30', 2, 1),
	(4, '45', 3, 1);
/*!40000 ALTER TABLE `ncrm_duration_minutes` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_duration_minutes_seq
CREATE TABLE IF NOT EXISTS `ncrm_duration_minutes_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_duration_minutes_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_duration_minutes_seq` DISABLE KEYS */;
INSERT INTO `ncrm_duration_minutes_seq` (`id`) VALUES
	(4);
/*!40000 ALTER TABLE `ncrm_duration_minutes_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_emaildetails
CREATE TABLE IF NOT EXISTS `ncrm_emaildetails` (
  `emailid` int(19) NOT NULL,
  `from_email` varchar(50) NOT NULL DEFAULT '',
  `to_email` text,
  `cc_email` text,
  `bcc_email` text,
  `assigned_user_email` varchar(50) NOT NULL DEFAULT '',
  `idlists` text,
  `email_flag` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`emailid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_emaildetails: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_emaildetails` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_emaildetails` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_emailtemplates
CREATE TABLE IF NOT EXISTS `ncrm_emailtemplates` (
  `foldername` varchar(100) DEFAULT NULL,
  `templatename` varchar(100) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `description` text,
  `body` text,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `templateid` int(19) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`templateid`),
  KEY `emailtemplates_foldernamd_templatename_subject_idx` (`foldername`,`templatename`,`subject`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_emailtemplates: ~12 rows (approximately)
/*!40000 ALTER TABLE `ncrm_emailtemplates` DISABLE KEYS */;
INSERT INTO `ncrm_emailtemplates` (`foldername`, `templatename`, `subject`, `description`, `body`, `deleted`, `templateid`) VALUES
	('Public', 'Announcement for Release', 'Announcement for Release', 'Announcement of a release', 'Hello!   <br />\n	<br />\n	On behalf of the ncrm team,  I am pleased to announce the release of ncrm crm4.2 . This is a feature packed release including the mass email template handling, custom view feature, ncrm_reports feature and a host of other utilities. ncrm runs on all platforms.    <br />\n        <br />\n	Notable Features of ncrm are :   <br />\n	<br />\n	-Email Client Integration    <br />\n	-Trouble Ticket Integration   <br />\n	-Invoice Management Integration   <br />\n	-Reports Integration   <br />\n	-Portal Integration   <br />\n	-Enhanced Word Plugin Support   <br />\n	-Custom View Integration   <br />\n	<br />\n	Known Issues:   <br />\n	-ABCD   <br />\n	-EFGH   <br />\n	-IJKL   <br />\n	-MNOP   <br />\n	-QRST', 0, 1),
	('Public', 'Pending Invoices', 'Invoices Pending', 'Payment Due', 'name <br />\nstreet, <br />\ncity, <br />\nstate, <br />\n zip) <br />\n  <br />\n Dear <br />\n <br />\n Please check the following invoices that are yet to be paid by you: <br />\n <br />\n No. Date      Amount <br />\n 1   1/1/01    $4000 <br />\n 2   2/2//01   $5000 <br />\n 3   3/3/01    $10000 <br />\n 4   7/4/01    $23560 <br />\n <br />\n Kindly let us know if there are any issues that you feel are pending to be discussed. <br />\n We will be more than happy to give you a call. <br />\n We would like to continue our business with you.', 0, 2),
	('Public', 'Acceptance Proposal', 'Acceptance Proposal', 'Acceptance of Proposal', ' Dear <br />\n <br />\nYour proposal on the project XYZW has been reviewed by us <br />\nand is acceptable in its entirety. <br />\n <br />\nWe are eagerly looking forward to this project <br />\nand are pleased about having the opportunity to work <br />\ntogether. We look forward to a long standing relationship <br />\nwith your esteemed firm. <br />\n<br />\nI would like to take this opportunity to invite you <br />\nto a game of golf on Wednesday morning 9am at the <br />\nCuff Links Ground. We will be waiting for you in the <br />\nExecutive Lounge. <br />\n<br />\nLooking forward to seeing you there.', 0, 3),
	('Public', 'Goods received acknowledgement', 'Goods received acknowledgement', 'Acknowledged Receipt of Goods', ' The undersigned hereby acknowledges receipt and delivery of the goods. <br />\nThe undersigned will release the payment subject to the goods being discovered not satisfactory. <br />\n<br />\nSigned under seal this <date>', 0, 4),
	('Public', 'Accept Order', 'Accept Order', 'Acknowledgement/Acceptance of Order', ' Dear <br />\n         We are in receipt of your order as contained in the <br />\n   purchase order form.We consider this to be final and binding on both sides. <br />\nIf there be any exceptions noted, we shall consider them <br />\nonly if the objection is received within ten days of receipt of <br />\nthis notice. <br />\n <br />\nThank you for your patronage.', 0, 5),
	('Public', 'Address Change', 'Change of Address', 'Address Change', 'Dear <br />\n <br />\nWe are relocating our office to <br />\n11111,XYZDEF Cross, <br />\nUVWWX Circle <br />\nThe telephone number for this new location is (101) 1212-1328. <br />\n<br />\nOur Manufacturing Division will continue operations <br />\nat 3250 Lovedale Square Avenue, in Frankfurt. <br />\n<br />\nWe hope to keep in touch with you all. <br />\nPlease update your addressbooks.', 0, 6),
	('Public', 'Follow Up', 'Follow Up', 'Follow Up of meeting', 'Dear <br />\n<br />\nThank you for extending us the opportunity to meet with <br />\nyou and members of your staff. <br />\n<br />\nI know that John Doe serviced your account <br />\nfor many years and made many friends at your firm. He has personally <br />\ndiscussed with me the deep relationship that he had with your firm. <br />\nWhile his presence will be missed, I can promise that we will <br />\ncontinue to provide the fine service that was accorded by <br />\nJohn to your firm. <br />\n<br />\nI was genuinely touched to receive such fine hospitality. <br />\n<br />\nThank you once again.', 0, 7),
	('Public', 'Target Crossed!', 'Target Crossed!', 'Fantastic Sales Spree!', 'Congratulations! <br />\n<br />\nThe numbers are in and I am proud to inform you that our <br />\ntotal sales for the previous quarter <br />\namounts to $100,000,00.00!. This is the first time <br />\nwe have exceeded the target by almost 30%. <br />\nWe have also beat the previous quarter record by a <br />\nwhopping 75%! <br />\n<br />\nLet us meet at Smoking Joe for a drink in the evening! <br />\n\nC you all there guys!', 0, 8),
	('Public', 'Thanks Note', 'Thanks Note', 'Note of thanks', 'Dear <br />\n<br />\nThank you for your confidence in our ability to serve you. <br />\nWe are glad to be given the chance to serve you.I look <br />\nforward to establishing a long term partnership with you. <br />\nConsider me as a friend. <br />\nShould any need arise,please do give us a call.', 0, 9),
	('Public', 'Customer Login Details', 'Customer Portal Login Details', 'Send Portal login details to customer', '<table width="700" cellspacing="0" cellpadding="0" border="0" align="center" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; font-weight: normal; text-decoration: none; background-color: rgb(122, 122, 254);">\n        <tr>\n            <td> </td>\n            <td> </td>\n            <td> </td>\n        </tr>\n        <tr>\n            <td> </td>\n            <td> </td>\n            <td> </td>\n        </tr>\n        <tr>\n            <td> </td>\n            <td> </td>\n            <td> </td>\n        </tr>\n        <tr>\n            <td width="50"> </td>\n            <td>\n            <table width="100%" cellspacing="0" cellpadding="0" border="0">\n                    <tr>\n                        <td>\n                        <table width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: rgb(27, 77, 140); font-family: Arial,Helvetica,sans-serif; font-size: 14px; color: rgb(255, 255, 255); font-weight: normal; line-height: 25px;">\n                                <tr>\n                                    <td align="center" rowspan="4">$logo$</td>\n                                    <td align="center"> </td>\n                                </tr>\n                                <tr>\n                                    <td align="left" style="background-color: rgb(27, 77, 140); font-family: Arial,Helvetica,sans-serif; font-size: 24px; color: rgb(255, 255, 255); font-weight: bolder; line-height: 35px;">ncrm CRM<br /> </td>\n                                </tr>\n                                <tr>\n                                    <td align="right" style="padding-right: 100px;">The honest Open Source CRM </td>\n                                </tr>\n                                <tr>\n                                    <td> </td>\n                                </tr>\n                        </table>\n                        </td>\n                    </tr>\n                    <tr>\n                        <td>\n                        <table width="100%" cellspacing="0" cellpadding="0" border="0" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; font-weight: normal; color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);">\n                                <tr>\n                                    <td valign="top">\n                                    <table width="100%" cellspacing="0" cellpadding="5" border="0">\n                                            <tr>\n                                                <td align="right" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; font-weight: bolder; text-decoration: none; color: rgb(66, 66, 253);"> </td>\n                                            </tr>\n                                            <tr>\n                                                <td> </td>\n                                            </tr>\n                                            <tr>\n                                                <td style="font-family: Arial,Helvetica,sans-serif; font-size: 14px; color: rgb(22, 72, 134); font-weight: bolder; line-height: 15px;">Dear $contact_name$, </td>\n                                            </tr>\n                                            <tr>\n                                                <td style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; color: rgb(0, 0, 0); font-weight: normal; text-align: justify; line-height: 20px;"> Thank you very much for subscribing to the ncrm CRM - annual support service.<br />Here is your self service portal login details:</td>\n                                            </tr>\n                                            <tr>\n                                                <td align="center">\n                                                <table width="75%" cellspacing="0" cellpadding="10" border="0" style="border: 2px solid rgb(180, 180, 179); background-color: rgb(226, 226, 225); font-family: Arial,Helvetica,sans-serif; font-size: 12px; color: rgb(0, 0, 0); font-weight: normal;">\n                                                        <tr>\n                                                            <td><br />User ID     : <font color="#990000"><strong> $login_name$</strong></font> </td>\n                                                        </tr>\n                                                        <tr>\n                                                            <td>Password: <font color="#990000"><strong> $password$</strong></font> </td>\n                                                        </tr>\n                                                        <tr>\n                                                            <td align="center"> <strong>  $URL$<br /> </strong> </td>\n                                                        </tr>\n                                                </table>\n                                                </td>\n                                            </tr>\n                                            <tr>\n                                                <td style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; color: rgb(0, 0, 0); font-weight: normal; text-align: justify; line-height: 20px;"><strong>NOTE:</strong> We suggest you to change your password after logging in first time. <br /><br /> <strong><u>Help Documentation</u></strong><br />  <br /> After logging in to ncrm Self-service Portal first time, you can access the ncrm CRM documents from the <strong>Documents</strong> tab. Following documents are available for your reference:<br />\n                                                <ul>\n                                                    <li>Installation Manual (Windows &amp; Linux OS)<br /> </li>\n                                                    <li>User &amp; Administrator Manual<br /> </li>\n                                                    <li>ncrm Customer Portal - User Manual<br /> </li>\n                                                    <li>ncrm Outlook Plugin - User Manual<br /> </li>\n                                                    <li>ncrm Office Plug-in - User Manual<br /> </li>\n                                                    <li>ncrm Thunderbird Extension - User Manual<br /> </li>\n                                                    <li>ncrm Web Forms - User Manual<br /> </li>\n                                                    <li>ncrm Firefox Tool bar - User Manual<br /> </li>\n                                                </ul>\n                                                <br />  <br /> <strong><u>Knowledge Base</u></strong><br /> <br /> Periodically we update frequently asked question based on our customer experiences. You can access the latest articles from the <strong>FAQ</strong> tab.<br /> <br /> <strong><u>ncrm CRM - Details</u></strong><br /> <br /> Kindly let us know your current ncrm CRM version and system specification so that we can provide you necessary guidelines to enhance your ncrm CRM system performance. Based on your system specification we alert you about the latest security &amp; upgrade patches.<br />  <br />			 Thank you once again and wish you a wonderful experience with ncrm CRM.<br /> </td>\n                                            </tr>\n                                            <tr>\n                                                <td align="right"><strong style="padding: 2px; font-family: Arial,Helvetica,sans-serif; font-size: 12px; color: rgb(0, 0, 0); font-weight: bold;"><br /><br />Best Regards</strong></td>\n                                            </tr>\n                                            <tr>\n                                                <td align="right" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; color: rgb(0, 0, 0); font-weight: normal; line-height: 20px;">$support_team$ </td>\n                                            </tr>\n                                            <tr>\n                                                <td align="right"><a style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; font-weight: bolder; text-decoration: none; color: rgb(66, 66, 253);" href="http://www.ncrm.com">www.ncrm.com</a></td>\n                                            </tr>\n                                            <tr>\n                                                <td> </td>\n                                            </tr>\n                                    </table>\n                                    </td>\n                                    <td width="1%" valign="top"> </td>\n                                </tr>\n                        </table>\n                        </td>\n                    </tr>\n                    <tr>\n                        <td>\n                        <table width="100%" cellspacing="0" cellpadding="5" border="0" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; color: rgb(255, 255, 255); font-weight: normal; line-height: 15px; background-color: rgb(51, 51, 51);">\n                                <tr>\n                                    <td align="center">Shree Narayana Complex, No 11 Sarathy Nagar, Vijaya Nagar , Velachery, Chennai - 600 042 India </td>\n                                </tr>\n                                <tr>\n                                    <td align="center">Telephone No: +91 - 44 - 4202 - 1990     Toll Free No: +1 877 788 4437</td>\n                                </tr>\n                                <tr>\n                                    <td align="center">Email Id: <a style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; font-weight: bolder; text-decoration: none; color: rgb(255, 255, 255);" href="mailto:support@ncrm.com">support@ncrm.com</a></td>\n                                </tr>\n                        </table>\n                        </td>\n                    </tr>\n            </table>\n            </td>\n            <td width="50"> </td>\n        </tr>\n        <tr>\n            <td> </td>\n            <td> </td>\n            <td> </td>\n        </tr>\n        <tr>\n            <td> </td>\n            <td> </td>\n            <td> </td>\n        </tr>\n        <tr>\n            <td> </td>\n            <td> </td>\n            <td> </td>\n        </tr>\n</table>', 0, 10),
	('Public', 'Support end notification before a week', 'NcrmCRM Support Notification', 'Send Notification mail to customer before a week of support end date', '<table width="700" cellspacing="0" cellpadding="0" border="0" align="center" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; font-weight: normal; text-decoration: none; background-color: rgb(122, 122, 254);">\n        <tr>\n            <td> </td>\n            <td> </td>\n            <td> </td>\n        </tr>\n        <tr>\n            <td> </td>\n            <td> </td>\n            <td> </td>\n        </tr>\n        <tr>\n            <td> </td>\n            <td> </td>\n            <td> </td>\n        </tr>\n        <tr>\n            <td width="50"> </td>\n            <td>\n            <table width="100%" cellspacing="0" cellpadding="0" border="0">\n                    <tr>\n                        <td>\n                        <table width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: rgb(27, 77, 140); font-family: Arial,Helvetica,sans-serif; font-size: 14px; color: rgb(255, 255, 255); font-weight: normal; line-height: 25px;">\n                                <tr>\n                                    <td align="center" rowspan="4">$logo$</td>\n                                    <td align="center"> </td>\n                                </tr>\n                                <tr>\n                                    <td align="left" style="background-color: rgb(27, 77, 140); font-family: Arial,Helvetica,sans-serif; font-size: 24px; color: rgb(255, 255, 255); font-weight: bolder; line-height: 35px;">ncrm CRM </td>\n                                </tr>\n                                <tr>\n                                    <td align="right" style="padding-right: 100px;">The honest Open Source CRM </td>\n                                </tr>\n                                <tr>\n                                    <td> </td>\n                                </tr>\n                        </table>\n                        </td>\n                    </tr>\n                    <tr>\n                        <td>\n                        <table width="100%" cellspacing="0" cellpadding="0" border="0" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; font-weight: normal; color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);">\n                                <tr>\n                                    <td valign="top">\n                                    <table width="100%" cellspacing="0" cellpadding="5" border="0">\n                                            <tr>\n                                                <td align="right" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; font-weight: bolder; text-decoration: none; color: rgb(66, 66, 253);"> </td>\n                                            </tr>\n                                            <tr>\n                                                <td> </td>\n                                            </tr>\n                                            <tr>\n                                                <td style="font-family: Arial,Helvetica,sans-serif; font-size: 14px; color: rgb(22, 72, 134); font-weight: bolder; line-height: 15px;">Dear $contacts-lastname$, </td>\n                                            </tr>\n                                            <tr>\n                                                <td style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; color: rgb(0, 0, 0); font-weight: normal; text-align: justify; line-height: 20px;">This is just a notification mail regarding your support end.<br /><span style="font-weight: bold;">Priority:</span> Urgent<br />Your Support is going to expire on next week<br />Please contact support@ncrm.com.<br /><br /><br /></td>\n                                            </tr>\n                                            <tr>\n                                                <td align="center"><br /></td>\n                                            </tr>\n                                            <tr>\n                                                <td align="right"><strong style="padding: 2px; font-family: Arial,Helvetica,sans-serif; font-size: 12px; color: rgb(0, 0, 0); font-weight: bold;"><br /><br />Sincerly</strong></td>\n                                            </tr>\n                                            <tr>\n                                                <td align="right" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; color: rgb(0, 0, 0); font-weight: normal; line-height: 20px;">Support Team </td>\n                                            </tr>\n                                            <tr>\n                                                <td align="right"><a style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; font-weight: bolder; text-decoration: none; color: rgb(66, 66, 253);" href="http://www.ncrm.com">www.ncrm.com</a></td>\n                                            </tr>\n                                            <tr>\n                                                <td> </td>\n                                            </tr>\n                                    </table>\n                                    </td>\n                                    <td width="1%" valign="top"> </td>\n                                </tr>\n                        </table>\n                        </td>\n                    </tr>\n                    <tr>\n                        <td>\n                        <table width="100%" cellspacing="0" cellpadding="5" border="0" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; color: rgb(255, 255, 255); font-weight: normal; line-height: 15px; background-color: rgb(51, 51, 51);">\n                                <tr>\n                                    <td align="center">Shree Narayana Complex, No 11 Sarathy Nagar, Vijaya Nagar , Velachery, Chennai - 600 042 India </td>\n                                </tr>\n                                <tr>\n                                    <td align="center">Telephone No: +91 - 44 - 4202 - 1990     Toll Free No: +1 877 788 4437</td>\n                                </tr>\n                                <tr>\n                                    <td align="center">Email Id: <a style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; font-weight: bolder; text-decoration: none; color: rgb(255, 255, 255);" href="mailto:info@ncrm.com">info@ncrm.com</a></td>\n                                </tr>\n                        </table>\n                        </td>\n                    </tr>\n            </table>\n            </td>\n            <td width="50"> </td>\n        </tr>\n        <tr>\n            <td> </td>\n            <td> </td>\n            <td> </td>\n        </tr>\n        <tr>\n            <td> </td>\n            <td> </td>\n            <td> </td>\n        </tr>\n        <tr>\n            <td> </td>\n            <td> </td>\n            <td> </td>\n        </tr>\n</table>', 0, 11),
	('Public', 'Support end notification before a month', 'NcrmCRM Support Notification', 'Send Notification mail to customer before a month of support end date', '<table width="700" cellspacing="0" cellpadding="0" border="0" align="center" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; font-weight: normal; text-decoration: none; background-color: rgb(122, 122, 254);">\n        <tr>\n            <td> </td>\n            <td> </td>\n            <td> </td>\n        </tr>\n        <tr>\n            <td> </td>\n            <td> </td>\n            <td> </td>\n        </tr>\n        <tr>\n            <td> </td>\n            <td> </td>\n            <td> </td>\n        </tr>\n        <tr>\n            <td width="50"> </td>\n            <td>\n            <table width="100%" cellspacing="0" cellpadding="0" border="0">\n                    <tr>\n                        <td>\n                        <table width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: rgb(27, 77, 140); font-family: Arial,Helvetica,sans-serif; font-size: 14px; color: rgb(255, 255, 255); font-weight: normal; line-height: 25px;">\n                                <tr>\n                                    <td align="center" rowspan="4">$logo$</td>\n                                    <td align="center"> </td>\n                                </tr>\n                                <tr>\n                                    <td align="left" style="background-color: rgb(27, 77, 140); font-family: Arial,Helvetica,sans-serif; font-size: 24px; color: rgb(255, 255, 255); font-weight: bolder; line-height: 35px;">ncrm CRM </td>\n                                </tr>\n                                <tr>\n                                    <td align="right" style="padding-right: 100px;">The honest Open Source CRM </td>\n                                </tr>\n                                <tr>\n                                    <td> </td>\n                                </tr>\n                        </table>\n                        </td>\n                    </tr>\n                    <tr>\n                        <td>\n                        <table width="100%" cellspacing="0" cellpadding="0" border="0" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; font-weight: normal; color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);">\n                                <tr>\n                                    <td valign="top">\n                                    <table width="100%" cellspacing="0" cellpadding="5" border="0">\n                                            <tr>\n                                                <td align="right" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; font-weight: bolder; text-decoration: none; color: rgb(66, 66, 253);"> </td>\n                                            </tr>\n                                            <tr>\n                                                <td> </td>\n                                            </tr>\n                                            <tr>\n                                                <td style="font-family: Arial,Helvetica,sans-serif; font-size: 14px; color: rgb(22, 72, 134); font-weight: bolder; line-height: 15px;">Dear $contacts-lastname$, </td>\n                                            </tr>\n                                            <tr>\n                                                <td style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; color: rgb(0, 0, 0); font-weight: normal; text-align: justify; line-height: 20px;">This is just a notification mail regarding your support end.<br /><span style="font-weight: bold;">Priority:</span> Normal<br />Your Support is going to expire on next month.<br />Please contact support@ncrm.com<br /><br /><br /></td>\n                                            </tr>\n                                            <tr>\n                                                <td align="center"><br /></td>\n                                            </tr>\n                                            <tr>\n                                                <td align="right"><strong style="padding: 2px; font-family: Arial,Helvetica,sans-serif; font-size: 12px; color: rgb(0, 0, 0); font-weight: bold;"><br /><br />Sincerly</strong></td>\n                                            </tr>\n                                            <tr>\n                                                <td align="right" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; color: rgb(0, 0, 0); font-weight: normal; line-height: 20px;">Support Team </td>\n                                            </tr>\n                                            <tr>\n                                                <td align="right"><a href="http://www.ncrm.com" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; font-weight: bolder; text-decoration: none; color: rgb(66, 66, 253);">www.ncrm.com</a></td>\n                                            </tr>\n                                            <tr>\n                                                <td> </td>\n                                            </tr>\n                                    </table>\n                                    </td>\n                                    <td width="1%" valign="top"> </td>\n                                </tr>\n                        </table>\n                        </td>\n                    </tr>\n                    <tr>\n                        <td>\n                        <table width="100%" cellspacing="0" cellpadding="5" border="0" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; color: rgb(255, 255, 255); font-weight: normal; line-height: 15px; background-color: rgb(51, 51, 51);">\n                                <tr>\n                                    <td align="center">Shree Narayana Complex, No 11 Sarathy Nagar, Vijaya Nagar , Velachery, Chennai - 600 042 India </td>\n                                </tr>\n                                <tr>\n                                    <td align="center">Telephone No: +91 - 44 - 4202 - 1990     Toll Free No: +1 877 788 4437</td>\n                                </tr>\n                                <tr>\n                                    <td align="center">Email Id: <a href="mailto:info@ncrm.com" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; font-weight: bolder; text-decoration: none; color: rgb(255, 255, 255);">info@ncrm.com</a></td>\n                                </tr>\n                        </table>\n                        </td>\n                    </tr>\n            </table>\n            </td>\n            <td width="50"> </td>\n        </tr>\n        <tr>\n            <td> </td>\n            <td> </td>\n            <td> </td>\n        </tr>\n        <tr>\n            <td> </td>\n            <td> </td>\n            <td> </td>\n        </tr>\n        <tr>\n            <td> </td>\n            <td> </td>\n            <td> </td>\n        </tr>\n</table>', 0, 12);
/*!40000 ALTER TABLE `ncrm_emailtemplates` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_emailtemplates_seq
CREATE TABLE IF NOT EXISTS `ncrm_emailtemplates_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_emailtemplates_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_emailtemplates_seq` DISABLE KEYS */;
INSERT INTO `ncrm_emailtemplates_seq` (`id`) VALUES
	(13);
/*!40000 ALTER TABLE `ncrm_emailtemplates_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_email_access
CREATE TABLE IF NOT EXISTS `ncrm_email_access` (
  `crmid` int(11) DEFAULT NULL,
  `mailid` int(11) DEFAULT NULL,
  `accessdate` date DEFAULT NULL,
  `accesstime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_email_access: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_email_access` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_email_access` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_email_track
CREATE TABLE IF NOT EXISTS `ncrm_email_track` (
  `crmid` int(11) DEFAULT NULL,
  `mailid` int(11) DEFAULT NULL,
  `access_count` int(11) DEFAULT NULL,
  UNIQUE KEY `link_tabidtype_idx` (`crmid`,`mailid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_email_track: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_email_track` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_email_track` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_entityname
CREATE TABLE IF NOT EXISTS `ncrm_entityname` (
  `tabid` int(19) NOT NULL DEFAULT '0',
  `modulename` varchar(50) NOT NULL,
  `tablename` varchar(100) NOT NULL,
  `fieldname` varchar(150) NOT NULL,
  `entityidfield` varchar(150) NOT NULL,
  `entityidcolumn` varchar(150) NOT NULL,
  PRIMARY KEY (`tabid`),
  KEY `entityname_tabid_idx` (`tabid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_entityname: ~27 rows (approximately)
/*!40000 ALTER TABLE `ncrm_entityname` DISABLE KEYS */;
INSERT INTO `ncrm_entityname` (`tabid`, `modulename`, `tablename`, `fieldname`, `entityidfield`, `entityidcolumn`) VALUES
	(2, 'Potentials', 'ncrm_potential', 'potentialname', 'potentialid', 'potential_id'),
	(4, 'Contacts', 'ncrm_contactdetails', 'firstname,lastname', 'contactid', 'contact_id'),
	(6, 'Accounts', 'ncrm_account', 'accountname', 'accountid', 'account_id'),
	(7, 'Leads', 'ncrm_leaddetails', 'firstname,lastname', 'leadid', 'leadid'),
	(8, 'Documents', 'ncrm_notes', 'title', 'notesid', 'notesid'),
	(9, 'Calendar', 'ncrm_activity', 'subject', 'activityid', 'activityid'),
	(10, 'Emails', 'ncrm_activity', 'subject', 'activityid', 'activityid'),
	(13, 'HelpDesk', 'ncrm_troubletickets', 'title', 'ticketid', 'ticketid'),
	(14, 'Products', 'ncrm_products', 'productname', 'productid', 'product_id'),
	(15, 'Faq', 'ncrm_faq', 'question', 'id', 'id'),
	(18, 'Vendors', 'ncrm_vendor', 'vendorname', 'vendorid', 'vendor_id'),
	(19, 'PriceBooks', 'ncrm_pricebook', 'bookname', 'pricebookid', 'pricebookid'),
	(20, 'Quotes', 'ncrm_quotes', 'subject', 'quoteid', 'quote_id'),
	(21, 'PurchaseOrder', 'ncrm_purchaseorder', 'subject', 'purchaseorderid', 'purchaseorderid'),
	(22, 'SalesOrder', 'ncrm_salesorder', 'subject', 'salesorderid', 'salesorder_id'),
	(23, 'Invoice', 'ncrm_invoice', 'subject', 'invoiceid', 'invoiceid'),
	(26, 'Campaigns', 'ncrm_campaign', 'campaignname', 'campaignid', 'campaignid'),
	(29, 'Users', 'ncrm_users', 'first_name,last_name', 'id', 'id'),
	(34, 'PBXManager', 'ncrm_pbxmanager', 'customernumber', 'pbxmanagerid', 'pbxmanagerid'),
	(35, 'ServiceContracts', 'ncrm_servicecontracts', 'subject', 'servicecontractsid', 'servicecontractsid'),
	(36, 'Services', 'ncrm_service', 'servicename', 'serviceid', 'serviceid'),
	(38, 'Assets', 'ncrm_assets', 'assetname', 'assetsid', 'assetsid'),
	(42, 'ModComments', 'ncrm_modcomments', 'commentcontent', 'modcommentsid', 'modcommentsid'),
	(43, 'ProjectMilestone', 'ncrm_projectmilestone', 'projectmilestonename', 'projectmilestoneid', 'projectmilestoneid'),
	(44, 'ProjectTask', 'ncrm_projecttask', 'projecttaskname', 'projecttaskid', 'projecttaskid'),
	(45, 'Project', 'ncrm_project', 'projectname', 'projectid', 'projectid'),
	(47, 'SMSNotifier', 'ncrm_smsnotifier', 'message', 'smsnotifierid', 'smsnotifierid');
/*!40000 ALTER TABLE `ncrm_entityname` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_eventhandlers
CREATE TABLE IF NOT EXISTS `ncrm_eventhandlers` (
  `eventhandler_id` int(11) NOT NULL AUTO_INCREMENT,
  `event_name` varchar(100) NOT NULL,
  `handler_path` varchar(400) NOT NULL,
  `handler_class` varchar(100) NOT NULL,
  `cond` text,
  `is_active` int(1) NOT NULL,
  `dependent_on` varchar(255) DEFAULT '[]',
  PRIMARY KEY (`eventhandler_id`,`event_name`,`handler_class`),
  UNIQUE KEY `eventhandler_idx` (`eventhandler_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_eventhandlers: ~18 rows (approximately)
/*!40000 ALTER TABLE `ncrm_eventhandlers` DISABLE KEYS */;
INSERT INTO `ncrm_eventhandlers` (`eventhandler_id`, `event_name`, `handler_path`, `handler_class`, `cond`, `is_active`, `dependent_on`) VALUES
	(1, 'ncrm.entity.aftersave', 'modules/SalesOrder/RecurringInvoiceHandler.php', 'RecurringInvoiceHandler', '', 1, '[]'),
	(2, 'ncrm.entity.beforesave', 'data/VTEntityDelta.php', 'VTEntityDelta', '', 1, '[]'),
	(3, 'ncrm.entity.aftersave', 'data/VTEntityDelta.php', 'VTEntityDelta', '', 1, '[]'),
	(4, 'ncrm.entity.aftersave', 'modules/com_ncrm_workflow/VTEventHandler.inc', 'VTWorkflowEventHandler', '', 1, '["VTEntityDelta"]'),
	(5, 'ncrm.entity.afterrestore', 'modules/com_ncrm_workflow/VTEventHandler.inc', 'VTWorkflowEventHandler', '', 1, '[]'),
	(6, 'ncrm.entity.aftersave.final', 'modules/HelpDesk/HelpDeskHandler.php', 'HelpDeskHandler', '', 1, '[]'),
	(7, 'ncrm.entity.aftersave.final', 'modules/ModTracker/ModTrackerHandler.php', 'ModTrackerHandler', '', 1, '[]'),
	(8, 'ncrm.entity.beforedelete', 'modules/ModTracker/ModTrackerHandler.php', 'ModTrackerHandler', '', 1, '[]'),
	(9, 'ncrm.entity.afterrestore', 'modules/ModTracker/ModTrackerHandler.php', 'ModTrackerHandler', '', 1, '[]'),
	(15, 'ncrm.entity.beforesave', 'modules/ServiceContracts/ServiceContractsHandler.php', 'ServiceContractsHandler', '', 0, '[]'),
	(16, 'ncrm.entity.aftersave', 'modules/ServiceContracts/ServiceContractsHandler.php', 'ServiceContractsHandler', '', 0, '[]'),
	(17, 'ncrm.entity.aftersave', 'modules/WSAPP/WorkFlowHandlers/WSAPPAssignToTracker.php', 'WSAPPAssignToTracker', '', 1, '["VTEntityDelta"]'),
	(18, 'ncrm.entity.aftersave', 'modules/Ncrm/handlers/RecordLabelUpdater.php', 'Ncrm_RecordLabelUpdater_Handler', '', 1, '[]'),
	(19, 'ncrm.entity.aftersave', 'modules/Invoice/InvoiceHandler.php', 'InvoiceHandler', '', 1, '[]'),
	(20, 'ncrm.entity.aftersave', 'modules/PurchaseOrder/PurchaseOrderHandler.php', 'PurchaseOrderHandler', '', 1, '[]'),
	(21, 'ncrm.entity.aftersave', 'modules/ModComments/ModCommentsHandler.php', 'ModCommentsHandler', '', 1, '[]'),
	(22, 'ncrm.picklist.afterrename', 'modules/Settings/Picklist/handlers/PickListHandler.php', 'PickListHandler', '', 1, '[]'),
	(23, 'ncrm.picklist.afterdelete', 'modules/Settings/Picklist/handlers/PickListHandler.php', 'PickListHandler', '', 1, '[]');
/*!40000 ALTER TABLE `ncrm_eventhandlers` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_eventhandlers_seq
CREATE TABLE IF NOT EXISTS `ncrm_eventhandlers_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_eventhandlers_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_eventhandlers_seq` DISABLE KEYS */;
INSERT INTO `ncrm_eventhandlers_seq` (`id`) VALUES
	(23);
/*!40000 ALTER TABLE `ncrm_eventhandlers_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_eventhandler_module
CREATE TABLE IF NOT EXISTS `ncrm_eventhandler_module` (
  `eventhandler_module_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(100) DEFAULT NULL,
  `handler_class` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`eventhandler_module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_eventhandler_module: ~5 rows (approximately)
/*!40000 ALTER TABLE `ncrm_eventhandler_module` DISABLE KEYS */;
INSERT INTO `ncrm_eventhandler_module` (`eventhandler_module_id`, `module_name`, `handler_class`) VALUES
	(1, 'ModTracker', 'ModTrackerHandler'),
	(2, 'ServiceContracts', 'ServiceContractsHandler'),
	(3, 'Home', 'Ncrm_RecordLabelUpdater_Handler'),
	(4, 'Invoice', 'InvoiceHandler'),
	(5, 'PurchaseOrder', 'PurchaseOrderHandler');
/*!40000 ALTER TABLE `ncrm_eventhandler_module` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_eventhandler_module_seq
CREATE TABLE IF NOT EXISTS `ncrm_eventhandler_module_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_eventhandler_module_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_eventhandler_module_seq` DISABLE KEYS */;
INSERT INTO `ncrm_eventhandler_module_seq` (`id`) VALUES
	(5);
/*!40000 ALTER TABLE `ncrm_eventhandler_module_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_eventstatus
CREATE TABLE IF NOT EXISTS `ncrm_eventstatus` (
  `eventstatusid` int(19) NOT NULL AUTO_INCREMENT,
  `eventstatus` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  PRIMARY KEY (`eventstatusid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_eventstatus: ~3 rows (approximately)
/*!40000 ALTER TABLE `ncrm_eventstatus` DISABLE KEYS */;
INSERT INTO `ncrm_eventstatus` (`eventstatusid`, `eventstatus`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(1, 'Planned', 0, 38, 0),
	(2, 'Held', 0, 39, 1),
	(3, 'Not Held', 0, 40, 2);
/*!40000 ALTER TABLE `ncrm_eventstatus` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_eventstatus_seq
CREATE TABLE IF NOT EXISTS `ncrm_eventstatus_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_eventstatus_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_eventstatus_seq` DISABLE KEYS */;
INSERT INTO `ncrm_eventstatus_seq` (`id`) VALUES
	(3);
/*!40000 ALTER TABLE `ncrm_eventstatus_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_expectedresponse
CREATE TABLE IF NOT EXISTS `ncrm_expectedresponse` (
  `expectedresponseid` int(19) NOT NULL AUTO_INCREMENT,
  `expectedresponse` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  PRIMARY KEY (`expectedresponseid`),
  UNIQUE KEY `CampaignExpRes_UK01` (`expectedresponse`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_expectedresponse: ~4 rows (approximately)
/*!40000 ALTER TABLE `ncrm_expectedresponse` DISABLE KEYS */;
INSERT INTO `ncrm_expectedresponse` (`expectedresponseid`, `expectedresponse`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(2, 'Excellent', 1, 42, 1),
	(3, 'Good', 1, 43, 2),
	(4, 'Average', 1, 44, 3),
	(5, 'Poor', 1, 45, 4);
/*!40000 ALTER TABLE `ncrm_expectedresponse` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_expectedresponse_seq
CREATE TABLE IF NOT EXISTS `ncrm_expectedresponse_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_expectedresponse_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_expectedresponse_seq` DISABLE KEYS */;
INSERT INTO `ncrm_expectedresponse_seq` (`id`) VALUES
	(5);
/*!40000 ALTER TABLE `ncrm_expectedresponse_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_extnstore_users
CREATE TABLE IF NOT EXISTS `ncrm_extnstore_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(75) DEFAULT NULL,
  `instanceurl` varchar(255) DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_extnstore_users: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_extnstore_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_extnstore_users` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_faq
CREATE TABLE IF NOT EXISTS `ncrm_faq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `faq_no` varchar(100) NOT NULL,
  `product_id` varchar(100) DEFAULT NULL,
  `question` text,
  `answer` text,
  `category` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `faq_id_idx` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_faq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_faq` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_faq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_faqcategories
CREATE TABLE IF NOT EXISTS `ncrm_faqcategories` (
  `faqcategories_id` int(19) NOT NULL AUTO_INCREMENT,
  `faqcategories` varchar(200) DEFAULT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  PRIMARY KEY (`faqcategories_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_faqcategories: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_faqcategories` DISABLE KEYS */;
INSERT INTO `ncrm_faqcategories` (`faqcategories_id`, `faqcategories`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(1, 'General', 1, 46, 0);
/*!40000 ALTER TABLE `ncrm_faqcategories` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_faqcategories_seq
CREATE TABLE IF NOT EXISTS `ncrm_faqcategories_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_faqcategories_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_faqcategories_seq` DISABLE KEYS */;
INSERT INTO `ncrm_faqcategories_seq` (`id`) VALUES
	(1);
/*!40000 ALTER TABLE `ncrm_faqcategories_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_faqcf
CREATE TABLE IF NOT EXISTS `ncrm_faqcf` (
  `faqid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`faqid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_faqcf: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_faqcf` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_faqcf` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_faqcomments
CREATE TABLE IF NOT EXISTS `ncrm_faqcomments` (
  `commentid` int(19) NOT NULL AUTO_INCREMENT,
  `faqid` int(19) DEFAULT NULL,
  `comments` text,
  `createdtime` datetime NOT NULL,
  PRIMARY KEY (`commentid`),
  KEY `faqcomments_faqid_idx` (`faqid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_faqcomments: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_faqcomments` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_faqcomments` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_faqstatus
CREATE TABLE IF NOT EXISTS `ncrm_faqstatus` (
  `faqstatus_id` int(19) NOT NULL AUTO_INCREMENT,
  `faqstatus` varchar(200) DEFAULT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  PRIMARY KEY (`faqstatus_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_faqstatus: ~4 rows (approximately)
/*!40000 ALTER TABLE `ncrm_faqstatus` DISABLE KEYS */;
INSERT INTO `ncrm_faqstatus` (`faqstatus_id`, `faqstatus`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(1, 'Draft', 0, 47, 0),
	(2, 'Reviewed', 0, 48, 1),
	(3, 'Published', 0, 49, 2),
	(4, 'Obsolete', 0, 50, 3);
/*!40000 ALTER TABLE `ncrm_faqstatus` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_faqstatus_seq
CREATE TABLE IF NOT EXISTS `ncrm_faqstatus_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_faqstatus_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_faqstatus_seq` DISABLE KEYS */;
INSERT INTO `ncrm_faqstatus_seq` (`id`) VALUES
	(4);
/*!40000 ALTER TABLE `ncrm_faqstatus_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_feedback
CREATE TABLE IF NOT EXISTS `ncrm_feedback` (
  `userid` int(19) DEFAULT NULL,
  `dontshow` varchar(19) DEFAULT 'false'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_feedback: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_feedback` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_feedback` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_field
CREATE TABLE IF NOT EXISTS `ncrm_field` (
  `tabid` int(19) NOT NULL,
  `fieldid` int(19) NOT NULL AUTO_INCREMENT,
  `columnname` varchar(30) NOT NULL,
  `tablename` varchar(50) NOT NULL,
  `generatedtype` int(19) NOT NULL DEFAULT '0',
  `uitype` varchar(30) NOT NULL,
  `fieldname` varchar(50) NOT NULL,
  `fieldlabel` varchar(50) NOT NULL,
  `readonly` int(1) NOT NULL,
  `presence` int(19) NOT NULL DEFAULT '1',
  `defaultvalue` text,
  `maximumlength` int(19) DEFAULT NULL,
  `sequence` int(19) DEFAULT NULL,
  `block` int(19) DEFAULT NULL,
  `displaytype` int(19) DEFAULT NULL,
  `typeofdata` varchar(100) DEFAULT NULL,
  `quickcreate` int(10) NOT NULL DEFAULT '1',
  `quickcreatesequence` int(19) DEFAULT NULL,
  `info_type` varchar(20) DEFAULT NULL,
  `masseditable` int(10) NOT NULL DEFAULT '1',
  `helpinfo` text,
  `summaryfield` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`fieldid`),
  KEY `field_tabid_idx` (`tabid`),
  KEY `field_fieldname_idx` (`fieldname`),
  KEY `field_block_idx` (`block`),
  KEY `field_displaytype_idx` (`displaytype`)
) ENGINE=InnoDB AUTO_INCREMENT=771 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_field: ~725 rows (approximately)
/*!40000 ALTER TABLE `ncrm_field` DISABLE KEYS */;
INSERT INTO `ncrm_field` (`tabid`, `fieldid`, `columnname`, `tablename`, `generatedtype`, `uitype`, `fieldname`, `fieldlabel`, `readonly`, `presence`, `defaultvalue`, `maximumlength`, `sequence`, `block`, `displaytype`, `typeofdata`, `quickcreate`, `quickcreatesequence`, `info_type`, `masseditable`, `helpinfo`, `summaryfield`) VALUES
	(6, 1, 'accountname', 'ncrm_account', 1, '2', 'accountname', 'Account Name', 1, 0, '', 100, 1, 9, 1, 'V~M', 0, 1, 'BAS', 1, NULL, 1),
	(6, 2, 'account_no', 'ncrm_account', 1, '4', 'account_no', 'Account No', 1, 0, '', 100, 2, 9, 1, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(6, 3, 'phone', 'ncrm_account', 1, '11', 'phone', 'Phone', 1, 2, '', 100, 4, 9, 1, 'V~O', 2, 2, 'BAS', 1, NULL, 1),
	(6, 4, 'website', 'ncrm_account', 1, '17', 'website', 'Website', 1, 2, '', 100, 3, 9, 1, 'V~O', 2, 3, 'BAS', 1, NULL, 1),
	(6, 5, 'fax', 'ncrm_account', 1, '11', 'fax', 'Fax', 1, 2, '', 100, 6, 9, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(6, 6, 'tickersymbol', 'ncrm_account', 1, '1', 'tickersymbol', 'Ticker Symbol', 1, 2, '', 100, 5, 9, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(6, 7, 'otherphone', 'ncrm_account', 1, '11', 'otherphone', 'Other Phone', 1, 2, '', 100, 8, 9, 1, 'V~O', 1, NULL, 'ADV', 1, NULL, 0),
	(6, 8, 'parentid', 'ncrm_account', 1, '51', 'account_id', 'Member Of', 1, 2, '', 100, 7, 9, 1, 'I~O', 1, NULL, 'BAS', 0, NULL, 0),
	(6, 9, 'email1', 'ncrm_account', 1, '13', 'email1', 'Email', 1, 2, '', 100, 10, 9, 1, 'E~O', 1, NULL, 'BAS', 1, NULL, 1),
	(6, 10, 'employees', 'ncrm_account', 1, '7', 'employees', 'Employees', 1, 2, '', 100, 9, 9, 1, 'I~O', 1, NULL, 'ADV', 1, NULL, 0),
	(6, 11, 'email2', 'ncrm_account', 1, '13', 'email2', 'Other Email', 1, 2, '', 100, 11, 9, 1, 'E~O', 1, NULL, 'ADV', 1, NULL, 0),
	(6, 12, 'ownership', 'ncrm_account', 1, '1', 'ownership', 'Ownership', 1, 2, '', 100, 12, 9, 1, 'V~O', 1, NULL, 'ADV', 1, NULL, 0),
	(6, 13, 'rating', 'ncrm_account', 1, '15', 'rating', 'Rating', 1, 2, '', 100, 14, 9, 1, 'V~O', 1, NULL, 'ADV', 1, NULL, 0),
	(6, 14, 'industry', 'ncrm_account', 1, '15', 'industry', 'industry', 1, 2, '', 100, 13, 9, 1, 'V~O', 1, NULL, 'ADV', 1, NULL, 0),
	(6, 15, 'siccode', 'ncrm_account', 1, '1', 'siccode', 'SIC Code', 1, 2, '', 100, 16, 9, 1, 'V~O', 1, NULL, 'ADV', 1, NULL, 0),
	(6, 16, 'account_type', 'ncrm_account', 1, '15', 'accounttype', 'Type', 1, 2, '', 100, 15, 9, 1, 'V~O', 1, NULL, 'ADV', 1, NULL, 0),
	(6, 17, 'annualrevenue', 'ncrm_account', 1, '71', 'annual_revenue', 'Annual Revenue', 1, 2, '', 100, 18, 9, 1, 'N~O', 1, NULL, 'ADV', 1, NULL, 0),
	(6, 18, 'emailoptout', 'ncrm_account', 1, '56', 'emailoptout', 'Email Opt Out', 1, 2, '', 100, 17, 9, 1, 'C~O', 1, NULL, 'ADV', 1, NULL, 0),
	(6, 19, 'notify_owner', 'ncrm_account', 1, '56', 'notify_owner', 'Notify Owner', 1, 2, '', 10, 20, 9, 1, 'C~O', 1, NULL, 'ADV', 1, NULL, 0),
	(6, 20, 'smownerid', 'ncrm_crmentity', 1, '53', 'assigned_user_id', 'Assigned To', 1, 0, '', 100, 19, 9, 1, 'V~M', 0, 4, 'BAS', 1, NULL, 1),
	(6, 21, 'createdtime', 'ncrm_crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, '', 100, 22, 9, 2, 'DT~O', 3, NULL, 'BAS', 0, NULL, 0),
	(6, 22, 'modifiedtime', 'ncrm_crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, '', 100, 21, 9, 2, 'DT~O', 3, NULL, 'BAS', 0, NULL, 0),
	(6, 23, 'modifiedby', 'ncrm_crmentity', 1, '52', 'modifiedby', 'Last Modified By', 1, 0, '', 100, 23, 9, 3, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(6, 24, 'bill_street', 'ncrm_accountbillads', 1, '21', 'bill_street', 'Billing Address', 1, 2, '', 100, 1, 11, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(6, 25, 'ship_street', 'ncrm_accountshipads', 1, '21', 'ship_street', 'Shipping Address', 1, 2, '', 100, 2, 11, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(6, 26, 'bill_city', 'ncrm_accountbillads', 1, '1', 'bill_city', 'Billing City', 1, 2, '', 100, 5, 11, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 1),
	(6, 27, 'ship_city', 'ncrm_accountshipads', 1, '1', 'ship_city', 'Shipping City', 1, 2, '', 100, 6, 11, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(6, 28, 'bill_state', 'ncrm_accountbillads', 1, '1', 'bill_state', 'Billing State', 1, 2, '', 100, 7, 11, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(6, 29, 'ship_state', 'ncrm_accountshipads', 1, '1', 'ship_state', 'Shipping State', 1, 2, '', 100, 8, 11, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(6, 30, 'bill_code', 'ncrm_accountbillads', 1, '1', 'bill_code', 'Billing Code', 1, 2, '', 100, 9, 11, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(6, 31, 'ship_code', 'ncrm_accountshipads', 1, '1', 'ship_code', 'Shipping Code', 1, 2, '', 100, 10, 11, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(6, 32, 'bill_country', 'ncrm_accountbillads', 1, '1', 'bill_country', 'Billing Country', 1, 2, '', 100, 11, 11, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 1),
	(6, 33, 'ship_country', 'ncrm_accountshipads', 1, '1', 'ship_country', 'Shipping Country', 1, 2, '', 100, 12, 11, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(6, 34, 'bill_pobox', 'ncrm_accountbillads', 1, '1', 'bill_pobox', 'Billing Po Box', 1, 2, '', 100, 3, 11, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(6, 35, 'ship_pobox', 'ncrm_accountshipads', 1, '1', 'ship_pobox', 'Shipping Po Box', 1, 2, '', 100, 4, 11, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(6, 36, 'description', 'ncrm_crmentity', 1, '19', 'description', 'Description', 1, 2, '', 100, 1, 12, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(7, 37, 'salutation', 'ncrm_leaddetails', 1, '55', 'salutationtype', 'Salutation', 1, 0, '', 100, 1, 13, 3, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(7, 38, 'firstname', 'ncrm_leaddetails', 1, '55', 'firstname', 'First Name', 1, 0, '', 100, 2, 13, 1, 'V~O', 2, 1, 'BAS', 1, NULL, 1),
	(7, 39, 'lead_no', 'ncrm_leaddetails', 1, '4', 'lead_no', 'Lead No', 1, 0, '', 100, 3, 13, 1, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(7, 40, 'phone', 'ncrm_leadaddress', 1, '11', 'phone', 'Phone', 1, 2, '', 100, 5, 13, 1, 'V~O', 2, 4, 'BAS', 1, NULL, 1),
	(7, 41, 'lastname', 'ncrm_leaddetails', 1, '255', 'lastname', 'Last Name', 1, 0, '', 100, 4, 13, 1, 'V~M', 0, 2, 'BAS', 1, NULL, 1),
	(7, 42, 'mobile', 'ncrm_leadaddress', 1, '11', 'mobile', 'Mobile', 1, 2, '', 100, 7, 13, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(7, 43, 'company', 'ncrm_leaddetails', 1, '2', 'company', 'Company', 1, 2, '', 100, 6, 13, 1, 'V~O', 2, 3, 'BAS', 1, NULL, 1),
	(7, 44, 'fax', 'ncrm_leadaddress', 1, '11', 'fax', 'Fax', 1, 2, '', 100, 9, 13, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(7, 45, 'designation', 'ncrm_leaddetails', 1, '1', 'designation', 'Designation', 1, 2, '', 100, 8, 13, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(7, 46, 'email', 'ncrm_leaddetails', 1, '13', 'email', 'Email', 1, 2, '', 100, 11, 13, 1, 'E~O', 2, 5, 'BAS', 1, NULL, 1),
	(7, 47, 'leadsource', 'ncrm_leaddetails', 1, '15', 'leadsource', 'Lead Source', 1, 2, '', 100, 10, 13, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 1),
	(7, 48, 'website', 'ncrm_leadsubdetails', 1, '17', 'website', 'Website', 1, 2, '', 100, 13, 13, 1, 'V~O', 1, NULL, 'ADV', 1, NULL, 1),
	(7, 49, 'industry', 'ncrm_leaddetails', 1, '15', 'industry', 'Industry', 1, 2, '', 100, 12, 13, 1, 'V~O', 1, NULL, 'ADV', 1, NULL, 0),
	(7, 50, 'leadstatus', 'ncrm_leaddetails', 1, '15', 'leadstatus', 'Lead Status', 1, 2, '', 100, 15, 13, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(7, 51, 'annualrevenue', 'ncrm_leaddetails', 1, '71', 'annualrevenue', 'Annual Revenue', 1, 2, '', 100, 14, 13, 1, 'N~O', 1, NULL, 'ADV', 1, NULL, 0),
	(7, 52, 'rating', 'ncrm_leaddetails', 1, '15', 'rating', 'Rating', 1, 2, '', 100, 17, 13, 1, 'V~O', 1, NULL, 'ADV', 1, NULL, 0),
	(7, 53, 'noofemployees', 'ncrm_leaddetails', 1, '1', 'noofemployees', 'No Of Employees', 1, 2, '', 100, 16, 13, 1, 'I~O', 1, NULL, 'ADV', 1, NULL, 0),
	(7, 54, 'smownerid', 'ncrm_crmentity', 1, '53', 'assigned_user_id', 'Assigned To', 1, 0, '', 100, 19, 13, 1, 'V~M', 0, 6, 'BAS', 1, NULL, 1),
	(7, 55, 'secondaryemail', 'ncrm_leaddetails', 1, '13', 'secondaryemail', 'Secondary Email', 1, 2, '', 100, 18, 13, 1, 'E~O', 1, NULL, 'ADV', 1, NULL, 0),
	(7, 56, 'createdtime', 'ncrm_crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, '', 100, 21, 13, 2, 'DT~O', 3, NULL, 'BAS', 0, NULL, 0),
	(7, 57, 'modifiedtime', 'ncrm_crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, '', 100, 20, 13, 2, 'DT~O', 3, NULL, 'BAS', 0, NULL, 0),
	(7, 58, 'modifiedby', 'ncrm_crmentity', 1, '52', 'modifiedby', 'Last Modified By', 1, 0, '', 100, 23, 13, 3, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(7, 59, 'lane', 'ncrm_leadaddress', 1, '21', 'lane', 'Street', 1, 2, '', 100, 1, 15, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(7, 60, 'code', 'ncrm_leadaddress', 1, '1', 'code', 'Postal Code', 1, 2, '', 100, 3, 15, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(7, 61, 'city', 'ncrm_leadaddress', 1, '1', 'city', 'City', 1, 2, '', 100, 4, 15, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 1),
	(7, 62, 'country', 'ncrm_leadaddress', 1, '1', 'country', 'Country', 1, 2, '', 100, 5, 15, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 1),
	(7, 63, 'state', 'ncrm_leadaddress', 1, '1', 'state', 'State', 1, 2, '', 100, 6, 15, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(7, 64, 'pobox', 'ncrm_leadaddress', 1, '1', 'pobox', 'Po Box', 1, 2, '', 100, 2, 15, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(7, 65, 'description', 'ncrm_crmentity', 1, '19', 'description', 'Description', 1, 2, '', 100, 1, 16, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(4, 66, 'salutation', 'ncrm_contactdetails', 1, '55', 'salutationtype', 'Salutation', 1, 0, '', 100, 1, 4, 3, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(4, 67, 'firstname', 'ncrm_contactdetails', 1, '55', 'firstname', 'First Name', 1, 1, '', 100, 1, 4, 1, 'V~O', 2, 1, 'BAS', 1, NULL, 1),
	(4, 68, 'contact_no', 'ncrm_contactdetails', 1, '4', 'contact_no', 'Contact Id', 1, 0, '', 100, 1, 4, 1, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(4, 69, 'phone', 'ncrm_contactdetails', 1, '11', 'phone', 'Office Phone', 1, 1, '', 100, 5, 4, 1, 'V~O', 2, 4, 'BAS', 1, NULL, 1),
	(4, 70, 'lastname', 'ncrm_contactdetails', 1, '255', 'lastname', 'Last Name', 1, 0, '', 100, 2, 4, 1, 'V~M', 0, 2, 'BAS', 1, NULL, 1),
	(4, 71, 'mobile', 'ncrm_contactdetails', 1, '11', 'mobile', 'Mobile', 1, 2, '', 100, 4, 4, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(4, 72, 'accountid', 'ncrm_contactdetails', 1, '51', 'account_id', 'Account Name', 1, 2, '', 100, 5, 4, 3, 'I~O', 2, 3, 'BAS', 1, NULL, 1),
	(4, 73, 'homephone', 'ncrm_contactsubdetails', 1, '11', 'homephone', 'Home Phone', 1, 1, '', 100, 6, 4, 1, 'V~O', 1, NULL, 'ADV', 1, NULL, 0),
	(4, 74, 'leadsource', 'ncrm_contactsubdetails', 1, '15', 'leadsource', 'Lead Source', 1, 1, '', 100, 9, 4, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(4, 75, 'otherphone', 'ncrm_contactsubdetails', 1, '11', 'otherphone', 'Other Phone', 1, 1, '', 100, 5, 4, 1, 'V~O', 1, NULL, 'ADV', 1, NULL, 0),
	(4, 76, 'title', 'ncrm_contactdetails', 1, '1', 'title', 'Title', 1, 1, '', 100, 10, 4, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 1),
	(4, 77, 'fax', 'ncrm_contactdetails', 1, '11', 'fax', 'Fax', 1, 1, '', 100, 13, 4, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(4, 78, 'department', 'ncrm_contactdetails', 1, '1', 'department', 'Department', 1, 1, '', 100, 9, 4, 1, 'V~O', 1, NULL, 'ADV', 1, NULL, 0),
	(4, 79, 'birthday', 'ncrm_contactsubdetails', 1, '5', 'birthday', 'Birthdate', 1, 2, '', 100, 6, 4, 1, 'D~O', 2, NULL, 'ADV', 1, NULL, 0),
	(4, 80, 'email', 'ncrm_contactdetails', 1, '13', 'email', 'Email', 1, 2, '', 100, 5, 4, 1, 'E~O', 2, 5, 'BAS', 1, NULL, 1),
	(4, 81, 'reportsto', 'ncrm_contactdetails', 1, '57', 'contact_id', 'Reports To', 1, 1, '', 100, 18, 4, 1, 'V~O', 1, NULL, 'ADV', 2, NULL, 0),
	(4, 82, 'assistant', 'ncrm_contactsubdetails', 1, '1', 'assistant', 'Assistant', 1, 1, '', 100, 17, 4, 1, 'V~O', 1, NULL, 'ADV', 1, NULL, 0),
	(4, 83, 'secondaryemail', 'ncrm_contactdetails', 1, '13', 'secondaryemail', 'Secondary Email', 1, 1, '', 100, 7, 4, 1, 'E~O', 1, NULL, 'ADV', 1, NULL, 0),
	(4, 84, 'assistantphone', 'ncrm_contactsubdetails', 1, '11', 'assistantphone', 'Assistant Phone', 1, 1, '', 100, 19, 4, 1, 'V~O', 1, NULL, 'ADV', 1, NULL, 0),
	(4, 85, 'donotcall', 'ncrm_contactdetails', 1, '56', 'donotcall', 'Do Not Call', 1, 2, '', 100, 9, 4, 1, 'C~O', 2, NULL, 'ADV', 1, NULL, 0),
	(4, 86, 'emailoptout', 'ncrm_contactdetails', 1, '56', 'emailoptout', 'Email Opt Out', 1, 1, '', 100, 21, 4, 1, 'C~O', 1, NULL, 'ADV', 1, NULL, 0),
	(4, 87, 'smownerid', 'ncrm_crmentity', 1, '53', 'assigned_user_id', 'Assigned To', 1, 0, '', 100, 11, 4, 1, 'V~M', 0, 6, 'BAS', 1, NULL, 1),
	(4, 88, 'reference', 'ncrm_contactdetails', 1, '56', 'reference', 'Reference', 1, 1, '', 10, 23, 4, 1, 'C~O', 1, NULL, 'ADV', 1, NULL, 0),
	(4, 89, 'notify_owner', 'ncrm_contactdetails', 1, '56', 'notify_owner', 'Notify Owner', 1, 1, '', 10, 26, 4, 1, 'C~O', 1, NULL, 'ADV', 1, NULL, 0),
	(4, 90, 'createdtime', 'ncrm_crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, '', 100, 13, 4, 2, 'DT~O', 3, NULL, 'BAS', 0, NULL, 0),
	(4, 91, 'modifiedtime', 'ncrm_crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, '', 100, 12, 4, 2, 'DT~O', 3, NULL, 'BAS', 0, NULL, 0),
	(4, 92, 'modifiedby', 'ncrm_crmentity', 1, '52', 'modifiedby', 'Last Modified By', 1, 0, '', 100, 28, 4, 3, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(4, 93, 'portal', 'ncrm_customerdetails', 1, '56', 'portal', 'Portal User', 1, 1, '', 100, 1, 6, 1, 'C~O', 1, NULL, 'ADV', 2, NULL, 0),
	(4, 94, 'support_start_date', 'ncrm_customerdetails', 1, '5', 'support_start_date', 'Support Start Date', 1, 1, '', 100, 2, 6, 1, 'D~O', 1, NULL, 'ADV', 1, NULL, 0),
	(4, 95, 'support_end_date', 'ncrm_customerdetails', 1, '5', 'support_end_date', 'Support End Date', 1, 1, '', 100, 3, 6, 1, 'D~O~OTH~GE~support_start_date~Support Start Date', 1, NULL, 'ADV', 1, NULL, 0),
	(4, 96, 'mailingstreet', 'ncrm_contactaddress', 1, '21', 'mailingstreet', 'Mailing Street', 1, 2, '', 100, 7, 4, 1, 'V~O', 2, NULL, 'BAS', 1, NULL, 0),
	(4, 97, 'otherstreet', 'ncrm_contactaddress', 1, '21', 'otherstreet', 'Other Street', 1, 1, '', 100, 2, 7, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(4, 98, 'mailingcity', 'ncrm_contactaddress', 1, '1', 'mailingcity', 'Mailing City', 1, 1, '', 100, 5, 7, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 1),
	(4, 99, 'othercity', 'ncrm_contactaddress', 1, '1', 'othercity', 'Other City', 1, 1, '', 100, 6, 7, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(4, 100, 'mailingstate', 'ncrm_contactaddress', 1, '1', 'mailingstate', 'Mailing State', 1, 1, '', 100, 7, 7, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(4, 101, 'otherstate', 'ncrm_contactaddress', 1, '1', 'otherstate', 'Other State', 1, 1, '', 100, 8, 7, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(4, 102, 'mailingzip', 'ncrm_contactaddress', 1, '1', 'mailingzip', 'Mailing Zip', 1, 1, '', 100, 9, 7, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(4, 103, 'otherzip', 'ncrm_contactaddress', 1, '1', 'otherzip', 'Other Zip', 1, 1, '', 100, 10, 7, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(4, 104, 'mailingcountry', 'ncrm_contactaddress', 1, '1', 'mailingcountry', 'Mailing Country', 1, 1, '', 100, 11, 7, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 1),
	(4, 105, 'othercountry', 'ncrm_contactaddress', 1, '1', 'othercountry', 'Other Country', 1, 1, '', 100, 12, 7, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(4, 106, 'mailingpobox', 'ncrm_contactaddress', 1, '1', 'mailingpobox', 'Mailing Po Box', 1, 1, '', 100, 3, 7, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(4, 107, 'otherpobox', 'ncrm_contactaddress', 1, '1', 'otherpobox', 'Other Po Box', 1, 1, '', 100, 4, 7, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(4, 108, 'imagename', 'ncrm_contactdetails', 1, '69', 'imagename', 'Contact Image', 1, 1, '', 100, 1, 73, 1, 'V~O', 1, NULL, 'ADV', 2, NULL, 0),
	(4, 109, 'description', 'ncrm_crmentity', 1, '19', 'description', 'Description', 1, 2, '', 100, 8, 4, 1, 'V~O', 2, NULL, 'BAS', 1, NULL, 0),
	(2, 110, 'potentialname', 'ncrm_potential', 1, '2', 'potentialname', 'Potential Name', 1, 0, '', 100, 1, 1, 1, 'V~M', 0, 1, 'BAS', 1, NULL, 1),
	(2, 111, 'potential_no', 'ncrm_potential', 1, '4', 'potential_no', 'Potential No', 1, 0, '', 100, 2, 1, 1, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(2, 112, 'amount', 'ncrm_potential', 1, '71', 'amount', 'Amount', 1, 2, '', 100, 5, 1, 1, 'N~O', 2, 5, 'BAS', 1, NULL, 1),
	(2, 113, 'related_to', 'ncrm_potential', 1, '10', 'related_to', 'Related To', 1, 0, '', 100, 3, 1, 1, 'V~O', 0, 2, 'BAS', 1, NULL, 1),
	(2, 114, 'closingdate', 'ncrm_potential', 1, '23', 'closingdate', 'Expected Close Date', 1, 2, '', 100, 8, 1, 1, 'D~M', 2, 3, 'BAS', 1, NULL, 1),
	(2, 115, 'potentialtype', 'ncrm_potential', 1, '15', 'opportunity_type', 'Type', 1, 2, '', 100, 7, 1, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(2, 116, 'nextstep', 'ncrm_potential', 1, '1', 'nextstep', 'Next Step', 1, 2, '', 100, 10, 1, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(2, 117, 'leadsource', 'ncrm_potential', 1, '15', 'leadsource', 'Lead Source', 1, 2, '', 100, 9, 1, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(2, 118, 'sales_stage', 'ncrm_potential', 1, '15', 'sales_stage', 'Sales Stage', 1, 2, '', 100, 12, 1, 1, 'V~M', 2, 4, 'BAS', 1, NULL, 1),
	(2, 119, 'smownerid', 'ncrm_crmentity', 1, '53', 'assigned_user_id', 'Assigned To', 1, 2, '', 100, 11, 1, 1, 'V~M', 0, 6, 'BAS', 1, NULL, 1),
	(2, 120, 'probability', 'ncrm_potential', 1, '9', 'probability', 'Probability', 1, 2, '', 100, 14, 1, 1, 'N~O', 1, NULL, 'BAS', 1, NULL, 0),
	(2, 121, 'campaignid', 'ncrm_potential', 1, '58', 'campaignid', 'Campaign Source', 1, 2, '', 100, 13, 1, 1, 'N~O', 1, NULL, 'BAS', 1, NULL, 0),
	(2, 122, 'createdtime', 'ncrm_crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, '', 100, 16, 1, 2, 'DT~O', 3, NULL, 'BAS', 0, NULL, 0),
	(2, 123, 'modifiedtime', 'ncrm_crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, '', 100, 15, 1, 2, 'DT~O', 3, NULL, 'BAS', 0, NULL, 0),
	(2, 124, 'modifiedby', 'ncrm_crmentity', 1, '52', 'modifiedby', 'Last Modified By', 1, 0, '', 100, 17, 1, 3, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(2, 125, 'description', 'ncrm_crmentity', 1, '19', 'description', 'Description', 1, 2, '', 100, 1, 3, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(26, 126, 'campaignname', 'ncrm_campaign', 1, '2', 'campaignname', 'Campaign Name', 1, 0, '', 100, 1, 74, 1, 'V~M', 0, 1, 'BAS', 1, NULL, 1),
	(26, 127, 'campaign_no', 'ncrm_campaign', 1, '4', 'campaign_no', 'Campaign No', 1, 0, '', 100, 2, 74, 1, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(26, 128, 'campaigntype', 'ncrm_campaign', 1, '15', 'campaigntype', 'Campaign Type', 1, 2, '', 100, 5, 74, 1, 'V~O', 2, 3, 'BAS', 1, NULL, 1),
	(26, 129, 'product_id', 'ncrm_campaign', 1, '59', 'product_id', 'Product', 1, 2, '', 100, 6, 74, 1, 'I~O', 2, 5, 'BAS', 1, NULL, 0),
	(26, 130, 'campaignstatus', 'ncrm_campaign', 1, '15', 'campaignstatus', 'Campaign Status', 1, 2, '', 100, 4, 74, 1, 'V~O', 2, 6, 'BAS', 1, NULL, 1),
	(26, 131, 'closingdate', 'ncrm_campaign', 1, '23', 'closingdate', 'Expected Close Date', 1, 2, '', 100, 8, 74, 1, 'D~M', 2, 2, 'BAS', 1, NULL, 1),
	(26, 132, 'smownerid', 'ncrm_crmentity', 1, '53', 'assigned_user_id', 'Assigned To', 1, 0, '', 100, 3, 74, 1, 'V~M', 0, 7, 'BAS', 1, NULL, 1),
	(26, 133, 'numsent', 'ncrm_campaign', 1, '9', 'numsent', 'Num Sent', 1, 2, '', 100, 12, 74, 1, 'N~O', 1, NULL, 'BAS', 1, NULL, 0),
	(26, 134, 'sponsor', 'ncrm_campaign', 1, '1', 'sponsor', 'Sponsor', 1, 2, '', 100, 9, 74, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(26, 135, 'targetaudience', 'ncrm_campaign', 1, '1', 'targetaudience', 'Target Audience', 1, 2, '', 100, 7, 74, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(26, 136, 'targetsize', 'ncrm_campaign', 1, '1', 'targetsize', 'TargetSize', 1, 2, '', 100, 10, 74, 1, 'I~O', 1, NULL, 'BAS', 1, NULL, 0),
	(26, 137, 'createdtime', 'ncrm_crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, '', 100, 11, 74, 2, 'DT~O', 3, NULL, 'BAS', 0, NULL, 0),
	(26, 138, 'modifiedtime', 'ncrm_crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, '', 100, 13, 74, 2, 'DT~O', 3, NULL, 'BAS', 0, NULL, 0),
	(26, 139, 'modifiedby', 'ncrm_crmentity', 1, '52', 'modifiedby', 'Last Modified By', 1, 0, '', 100, 16, 74, 3, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(26, 140, 'expectedresponse', 'ncrm_campaign', 1, '15', 'expectedresponse', 'Expected Response', 1, 2, '', 100, 3, 76, 1, 'V~O', 2, 4, 'BAS', 1, NULL, 0),
	(26, 141, 'expectedrevenue', 'ncrm_campaign', 1, '71', 'expectedrevenue', 'Expected Revenue', 1, 2, '', 100, 4, 76, 1, 'N~O', 1, NULL, 'BAS', 1, NULL, 1),
	(26, 142, 'budgetcost', 'ncrm_campaign', 1, '71', 'budgetcost', 'Budget Cost', 1, 2, '', 100, 1, 76, 1, 'N~O', 1, NULL, 'BAS', 1, NULL, 0),
	(26, 143, 'actualcost', 'ncrm_campaign', 1, '71', 'actualcost', 'Actual Cost', 1, 2, '', 100, 2, 76, 1, 'N~O', 1, NULL, 'BAS', 1, NULL, 0),
	(26, 144, 'expectedresponsecount', 'ncrm_campaign', 1, '1', 'expectedresponsecount', 'Expected Response Count', 1, 2, '', 100, 7, 76, 1, 'I~O', 1, NULL, 'BAS', 1, NULL, 0),
	(26, 145, 'expectedsalescount', 'ncrm_campaign', 1, '1', 'expectedsalescount', 'Expected Sales Count', 1, 2, '', 100, 5, 76, 1, 'I~O', 1, NULL, 'BAS', 1, NULL, 0),
	(26, 146, 'expectedroi', 'ncrm_campaign', 1, '71', 'expectedroi', 'Expected ROI', 1, 2, '', 100, 9, 76, 1, 'N~O', 1, NULL, 'BAS', 1, NULL, 0),
	(26, 147, 'actualresponsecount', 'ncrm_campaign', 1, '1', 'actualresponsecount', 'Actual Response Count', 1, 2, '', 100, 8, 76, 1, 'I~O', 1, NULL, 'BAS', 1, NULL, 0),
	(26, 148, 'actualsalescount', 'ncrm_campaign', 1, '1', 'actualsalescount', 'Actual Sales Count', 1, 2, '', 100, 6, 76, 1, 'I~O', 1, NULL, 'BAS', 1, NULL, 0),
	(26, 149, 'actualroi', 'ncrm_campaign', 1, '71', 'actualroi', 'Actual ROI', 1, 2, '', 100, 10, 76, 1, 'N~O', 1, NULL, 'BAS', 1, NULL, 0),
	(26, 150, 'description', 'ncrm_crmentity', 1, '19', 'description', 'Description', 1, 2, '', 100, 1, 81, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(4, 151, 'campaignrelstatus', 'ncrm_campaignrelstatus', 1, '16', 'campaignrelstatus', 'Campaign Status', 1, 0, '0', 100, 1, NULL, 1, 'V~O', 1, NULL, 'BAS', 0, NULL, 0),
	(6, 152, 'campaignrelstatus', 'ncrm_campaignrelstatus', 1, '16', 'campaignrelstatus', 'Campaign Status', 1, 0, '0', 100, 1, NULL, 1, 'V~O', 1, NULL, 'BAS', 0, NULL, 0),
	(7, 153, 'campaignrelstatus', 'ncrm_campaignrelstatus', 1, '16', 'campaignrelstatus', 'Campaign Status', 1, 0, '0', 100, 1, NULL, 1, 'V~O', 1, NULL, 'BAS', 0, NULL, 0),
	(26, 154, 'campaignrelstatus', 'ncrm_campaignrelstatus', 1, '16', 'campaignrelstatus', 'Campaign Status', 1, 0, '0', 100, 1, NULL, 1, 'V~O', 1, NULL, 'BAS', 0, NULL, 0),
	(13, 155, 'ticket_no', 'ncrm_troubletickets', 1, '4', 'ticket_no', 'Ticket No', 1, 0, '', 100, 14, 25, 1, 'V~O', 3, NULL, 'BAS', 0, NULL, 1),
	(13, 156, 'smownerid', 'ncrm_crmentity', 1, '53', 'assigned_user_id', 'Assigned To', 1, 0, '', 100, 5, 25, 1, 'V~M', 0, 4, 'BAS', 1, NULL, 1),
	(13, 157, 'parent_id', 'ncrm_troubletickets', 1, '10', 'parent_id', 'Related To', 1, 0, '', 100, 2, 25, 1, 'I~O', 1, NULL, 'BAS', 1, NULL, 1),
	(13, 158, 'priority', 'ncrm_troubletickets', 1, '15', 'ticketpriorities', 'Priority', 1, 2, '', 100, 7, 25, 1, 'V~O', 2, 3, 'BAS', 1, NULL, 1),
	(13, 159, 'product_id', 'ncrm_troubletickets', 1, '59', 'product_id', 'Product Name', 1, 2, '', 100, 6, 25, 1, 'I~O', 1, NULL, 'BAS', 1, NULL, 0),
	(13, 160, 'severity', 'ncrm_troubletickets', 1, '15', 'ticketseverities', 'Severity', 1, 2, '', 100, 9, 25, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 1),
	(13, 161, 'status', 'ncrm_troubletickets', 1, '15', 'ticketstatus', 'Status', 1, 2, '', 100, 8, 25, 1, 'V~M', 1, 2, 'BAS', 1, NULL, 1),
	(13, 162, 'category', 'ncrm_troubletickets', 1, '15', 'ticketcategories', 'Category', 1, 2, '', 100, 11, 25, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(13, 163, 'update_log', 'ncrm_troubletickets', 1, '19', 'update_log', 'Update History', 1, 0, '', 100, 12, 25, 3, 'V~O', 1, NULL, 'BAS', 0, NULL, 0),
	(13, 164, 'hours', 'ncrm_troubletickets', 1, '1', 'hours', 'Hours', 1, 2, '', 100, 10, 25, 1, 'N~O', 1, NULL, 'BAS', 1, 'This gives the estimated hours for the Ticket.<br>When the same ticket is added to a Service Contract,based on the Tracking Unit of the Service Contract,Used units is updated whenever a ticket is Closed.', 0),
	(13, 165, 'days', 'ncrm_troubletickets', 1, '1', 'days', 'Days', 1, 2, '', 100, 11, 25, 1, 'N~O', 1, NULL, 'BAS', 1, 'This gives the estimated days for the Ticket.<br>When the same ticket is added to a Service Contract,based on the Tracking Unit of the Service Contract,Used units is updated whenever a ticket is Closed.', 0),
	(13, 166, 'createdtime', 'ncrm_crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, '', 100, 10, 25, 2, 'DT~O', 3, NULL, 'BAS', 0, NULL, 0),
	(13, 167, 'modifiedtime', 'ncrm_crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, '', 100, 13, 25, 2, 'DT~O', 3, NULL, 'BAS', 0, NULL, 0),
	(13, 168, 'modifiedby', 'ncrm_crmentity', 1, '52', 'modifiedby', 'Last Modified By', 1, 0, '', 100, 17, 25, 3, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(13, 169, 'title', 'ncrm_troubletickets', 1, '22', 'ticket_title', 'Title', 1, 0, '', 100, 1, 25, 1, 'V~M', 0, 1, 'BAS', 1, NULL, 1),
	(13, 170, 'description', 'ncrm_crmentity', 1, '19', 'description', 'Description', 1, 2, '', 100, 1, 28, 1, 'V~O', 2, 4, 'BAS', 1, NULL, 1),
	(13, 171, 'solution', 'ncrm_troubletickets', 1, '19', 'solution', 'Solution', 1, 0, '', 100, 1, 29, 1, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(13, 172, 'comments', 'ncrm_ticketcomments', 1, '19', 'comments', 'Add Comment', 1, 1, '', 100, 1, 30, 1, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(14, 173, 'productname', 'ncrm_products', 1, '2', 'productname', 'Product Name', 1, 0, '', 100, 1, 31, 1, 'V~M', 0, 1, 'BAS', 1, NULL, 1),
	(14, 174, 'product_no', 'ncrm_products', 1, '4', 'product_no', 'Product No', 1, 0, '', 100, 2, 31, 1, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(14, 175, 'productcode', 'ncrm_products', 1, '1', 'productcode', 'Part Number', 1, 2, '', 100, 4, 31, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 1),
	(14, 176, 'discontinued', 'ncrm_products', 1, '56', 'discontinued', 'Product Active', 1, 2, '1', 100, 3, 31, 1, 'V~O', 2, 2, 'BAS', 1, NULL, 0),
	(14, 177, 'manufacturer', 'ncrm_products', 1, '15', 'manufacturer', 'Manufacturer', 1, 2, '', 100, 6, 31, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(14, 178, 'productcategory', 'ncrm_products', 1, '15', 'productcategory', 'Product Category', 1, 2, '', 100, 6, 31, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(14, 179, 'sales_start_date', 'ncrm_products', 1, '5', 'sales_start_date', 'Sales Start Date', 1, 2, '', 100, 5, 31, 1, 'D~O', 1, NULL, 'BAS', 1, NULL, 0),
	(14, 180, 'sales_end_date', 'ncrm_products', 1, '5', 'sales_end_date', 'Sales End Date', 1, 2, '', 100, 8, 31, 1, 'D~O~OTH~GE~sales_start_date~Sales Start Date', 1, NULL, 'BAS', 1, NULL, 0),
	(14, 181, 'start_date', 'ncrm_products', 1, '5', 'start_date', 'Support Start Date', 1, 2, '', 100, 7, 31, 1, 'D~O', 1, NULL, 'BAS', 1, NULL, 0),
	(14, 182, 'expiry_date', 'ncrm_products', 1, '5', 'expiry_date', 'Support Expiry Date', 1, 2, '', 100, 10, 31, 1, 'D~O~OTH~GE~start_date~Start Date', 1, NULL, 'BAS', 1, NULL, 0),
	(14, 183, 'website', 'ncrm_products', 1, '17', 'website', 'Website', 1, 2, '', 100, 14, 31, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(14, 184, 'vendor_id', 'ncrm_products', 1, '75', 'vendor_id', 'Vendor Name', 1, 2, '', 100, 13, 31, 1, 'I~O', 1, NULL, 'BAS', 1, NULL, 0),
	(14, 185, 'mfr_part_no', 'ncrm_products', 1, '1', 'mfr_part_no', 'Mfr PartNo', 1, 2, '', 100, 16, 31, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(14, 186, 'vendor_part_no', 'ncrm_products', 1, '1', 'vendor_part_no', 'Vendor PartNo', 1, 2, '', 100, 15, 31, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(14, 187, 'serialno', 'ncrm_products', 1, '1', 'serial_no', 'Serial No', 1, 2, '', 100, 18, 31, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(14, 188, 'productsheet', 'ncrm_products', 1, '1', 'productsheet', 'Product Sheet', 1, 2, '', 100, 17, 31, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(14, 189, 'glacct', 'ncrm_products', 1, '15', 'glacct', 'GL Account', 1, 2, '', 100, 20, 31, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(14, 190, 'createdtime', 'ncrm_crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, '', 100, 19, 31, 2, 'DT~O', 3, NULL, 'BAS', 0, NULL, 0),
	(14, 191, 'modifiedtime', 'ncrm_crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, '', 100, 21, 31, 2, 'DT~O', 3, NULL, 'BAS', 0, NULL, 0),
	(14, 192, 'modifiedby', 'ncrm_crmentity', 1, '52', 'modifiedby', 'Last Modified By', 1, 0, '', 100, 22, 31, 3, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(14, 193, 'unit_price', 'ncrm_products', 1, '72', 'unit_price', 'Unit Price', 1, 0, '', 100, 1, 32, 1, 'N~O', 2, 3, 'BAS', 0, NULL, 1),
	(14, 194, 'commissionrate', 'ncrm_products', 1, '9', 'commissionrate', 'Commission Rate', 1, 2, '', 100, 2, 32, 1, 'N~O', 1, NULL, 'BAS', 1, NULL, 1),
	(14, 195, 'taxclass', 'ncrm_products', 1, '83', 'taxclass', 'Tax Class', 1, 2, '', 100, 4, 32, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(14, 196, 'usageunit', 'ncrm_products', 1, '15', 'usageunit', 'Usage Unit', 1, 2, '', 100, 1, 33, 1, 'V~O', 1, NULL, 'ADV', 1, NULL, 0),
	(14, 197, 'qty_per_unit', 'ncrm_products', 1, '1', 'qty_per_unit', 'Qty/Unit', 1, 2, '', 100, 2, 33, 1, 'N~O', 1, NULL, 'ADV', 1, NULL, 1),
	(14, 198, 'qtyinstock', 'ncrm_products', 1, '1', 'qtyinstock', 'Qty In Stock', 1, 2, '', 100, 3, 33, 1, 'NN~O', 0, 4, 'ADV', 1, NULL, 0),
	(14, 199, 'reorderlevel', 'ncrm_products', 1, '1', 'reorderlevel', 'Reorder Level', 1, 2, '', 100, 4, 33, 1, 'I~O', 1, NULL, 'ADV', 1, NULL, 0),
	(14, 200, 'smownerid', 'ncrm_crmentity', 1, '53', 'assigned_user_id', 'Handler', 1, 0, '', 100, 5, 33, 1, 'V~M', 0, 5, 'BAS', 1, NULL, 0),
	(14, 201, 'qtyindemand', 'ncrm_products', 1, '1', 'qtyindemand', 'Qty In Demand', 1, 2, '', 100, 6, 33, 1, 'I~O', 1, NULL, 'ADV', 1, NULL, 0),
	(14, 202, 'imagename', 'ncrm_products', 1, '69', 'imagename', 'Product Image', 1, 2, '', 100, 1, 35, 1, 'V~O', 3, NULL, 'ADV', 0, NULL, 0),
	(14, 203, 'description', 'ncrm_crmentity', 1, '19', 'description', 'Description', 1, 2, '', 100, 1, 36, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(8, 204, 'title', 'ncrm_notes', 1, '2', 'notes_title', 'Title', 1, 0, '', 100, 1, 17, 1, 'V~M', 0, 1, 'BAS', 1, NULL, 1),
	(8, 205, 'createdtime', 'ncrm_crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, '', 100, 5, 17, 2, 'DT~O', 3, NULL, 'BAS', 0, NULL, 0),
	(8, 206, 'modifiedtime', 'ncrm_crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, '', 100, 6, 17, 2, 'DT~O', 3, NULL, 'BAS', 0, NULL, 1),
	(8, 207, 'filename', 'ncrm_notes', 1, '28', 'filename', 'File Name', 1, 2, '', 100, 3, 18, 1, 'V~O', 0, NULL, 'BAS', 0, NULL, 1),
	(8, 208, 'smownerid', 'ncrm_crmentity', 1, '53', 'assigned_user_id', 'Assigned To', 1, 0, '', 100, 4, 17, 1, 'V~M', 0, 3, 'BAS', 1, NULL, 1),
	(8, 209, 'notecontent', 'ncrm_notes', 1, '19', 'notecontent', 'Note', 1, 2, '', 100, 1, 84, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(8, 210, 'filetype', 'ncrm_notes', 1, '1', 'filetype', 'File Type', 1, 2, '', 100, 5, 18, 2, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(8, 211, 'filesize', 'ncrm_notes', 1, '1', 'filesize', 'File Size', 1, 2, '', 100, 4, 18, 2, 'I~O', 3, NULL, 'BAS', 0, NULL, 0),
	(8, 212, 'filelocationtype', 'ncrm_notes', 1, '27', 'filelocationtype', 'Download Type', 1, 0, '', 100, 1, 18, 1, 'V~O', 0, NULL, 'BAS', 0, NULL, 0),
	(8, 213, 'fileversion', 'ncrm_notes', 1, '1', 'fileversion', 'Version', 1, 2, '', 100, 6, 18, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(8, 214, 'filestatus', 'ncrm_notes', 1, '56', 'filestatus', 'Active', 1, 2, '1', 100, 2, 18, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(8, 215, 'filedownloadcount', 'ncrm_notes', 1, '1', 'filedownloadcount', 'Download Count', 1, 2, '', 100, 7, 18, 2, 'I~O', 3, NULL, 'BAS', 0, NULL, 0),
	(8, 216, 'folderid', 'ncrm_notes', 1, '26', 'folderid', 'Folder Name', 1, 2, '', 100, 2, 17, 1, 'V~O', 2, 2, 'BAS', 1, NULL, 1),
	(8, 217, 'note_no', 'ncrm_notes', 1, '4', 'note_no', 'Document No', 1, 0, '', 100, 3, 17, 1, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(8, 218, 'modifiedby', 'ncrm_crmentity', 1, '52', 'modifiedby', 'Last Modified By', 1, 0, '', 100, 12, 17, 3, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(10, 219, 'date_start', 'ncrm_activity', 1, '6', 'date_start', 'Date & Time Sent', 1, 0, '', 100, 1, 21, 1, 'DT~M~time_start~Time Start', 1, NULL, 'BAS', 1, NULL, 0),
	(10, 220, 'semodule', 'ncrm_activity', 1, '2', 'parent_type', 'Sales Entity Module', 1, 0, '', 100, 2, 21, 3, '', 1, NULL, 'BAS', 1, NULL, 0),
	(10, 221, 'activitytype', 'ncrm_activity', 1, '2', 'activitytype', 'Activity Type', 1, 0, '', 100, 3, 21, 3, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(10, 222, 'smownerid', 'ncrm_crmentity', 1, '53', 'assigned_user_id', 'Assigned To', 1, 0, '', 100, 5, 21, 1, 'V~M', 1, NULL, 'BAS', 1, NULL, 0),
	(10, 223, 'subject', 'ncrm_activity', 1, '2', 'subject', 'Subject', 1, 0, '', 100, 1, 23, 1, 'V~M', 1, NULL, 'BAS', 1, NULL, 0),
	(10, 224, 'name', 'ncrm_attachments', 1, '61', 'filename', 'Attachment', 1, 0, '', 100, 2, 23, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(10, 225, 'description', 'ncrm_crmentity', 1, '19', 'description', 'Description', 1, 0, '', 100, 1, 24, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(10, 226, 'time_start', 'ncrm_activity', 1, '2', 'time_start', 'Time Start', 1, 0, '', 100, 9, 23, 1, 'T~O', 1, NULL, 'BAS', 1, NULL, 0),
	(10, 227, 'createdtime', 'ncrm_crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, '', 100, 10, 22, 1, 'DT~O', 3, NULL, 'BAS', 0, NULL, 0),
	(10, 228, 'modifiedtime', 'ncrm_crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, '', 100, 11, 21, 2, 'DT~O', 3, NULL, 'BAS', 0, NULL, 0),
	(10, 229, 'access_count', 'ncrm_email_track', 1, '25', 'access_count', 'Access Count', 1, 0, '0', 100, 6, 21, 3, 'V~O', 1, NULL, 'BAS', 0, NULL, 0),
	(10, 230, 'modifiedby', 'ncrm_crmentity', 1, '52', 'modifiedby', 'Last Modified By', 1, 0, '', 100, 12, 21, 3, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(9, 231, 'subject', 'ncrm_activity', 1, '2', 'subject', 'Subject', 1, 0, '', 100, 1, 19, 1, 'V~M', 0, 1, 'BAS', 1, NULL, 1),
	(9, 232, 'smownerid', 'ncrm_crmentity', 1, '53', 'assigned_user_id', 'Assigned To', 1, 0, '', 100, 2, 19, 1, 'V~M', 0, 4, 'BAS', 1, NULL, 1),
	(9, 233, 'date_start', 'ncrm_activity', 1, '6', 'date_start', 'Start Date & Time', 1, 0, '', 100, 3, 19, 1, 'DT~M~time_start', 0, 2, 'BAS', 1, NULL, 1),
	(9, 234, 'time_start', 'ncrm_activity', 1, '2', 'time_start', 'Time Start', 1, 0, '', 100, 4, 19, 3, 'T~O', 1, NULL, 'BAS', 1, NULL, 1),
	(9, 235, 'time_end', 'ncrm_activity', 1, '2', 'time_end', 'End Time', 1, 0, '', 100, 4, 19, 3, 'T~O', 1, NULL, 'BAS', 1, NULL, 1),
	(9, 236, 'due_date', 'ncrm_activity', 1, '23', 'due_date', 'Due Date', 1, 0, '', 100, 5, 19, 1, 'D~M~OTH~GE~date_start~Start Date & Time', 1, NULL, 'BAS', 1, NULL, 1),
	(9, 237, 'crmid', 'ncrm_seactivityrel', 1, '66', 'parent_id', 'Related To', 1, 0, '', 100, 7, 19, 1, 'I~O', 1, NULL, 'BAS', 1, NULL, 1),
	(9, 238, 'contactid', 'ncrm_cntactivityrel', 1, '57', 'contact_id', 'Contact Name', 1, 0, '', 100, 8, 19, 1, 'I~O', 1, NULL, 'BAS', 1, NULL, 0),
	(9, 239, 'status', 'ncrm_activity', 1, '15', 'taskstatus', 'Status', 1, 0, '', 100, 8, 19, 1, 'V~M', 0, 3, 'BAS', 1, NULL, 0),
	(9, 240, 'eventstatus', 'ncrm_activity', 1, '15', 'eventstatus', 'Status', 1, 0, '', 100, 9, 19, 3, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(9, 241, 'priority', 'ncrm_activity', 1, '15', 'taskpriority', 'Priority', 1, 0, '', 100, 10, 19, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(9, 242, 'sendnotification', 'ncrm_activity', 1, '56', 'sendnotification', 'Send Notification', 1, 0, '', 100, 11, 19, 1, 'C~O', 1, NULL, 'BAS', 1, NULL, 0),
	(9, 243, 'createdtime', 'ncrm_crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, '', 100, 14, 19, 2, 'DT~O', 3, NULL, 'BAS', 0, NULL, 0),
	(9, 244, 'modifiedtime', 'ncrm_crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, '', 100, 15, 19, 2, 'DT~O', 3, NULL, 'BAS', 0, NULL, 0),
	(9, 245, 'activitytype', 'ncrm_activity', 1, '15', 'activitytype', 'Activity Type', 1, 0, '', 100, 16, 19, 3, 'V~O', 1, NULL, 'BAS', 1, NULL, 1),
	(9, 246, 'visibility', 'ncrm_activity', 1, '16', 'visibility', 'Visibility', 1, 0, '', 100, 17, 19, 3, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(9, 247, 'description', 'ncrm_crmentity', 1, '19', 'description', 'Description', 1, 0, '', 100, 1, 20, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(9, 248, 'duration_hours', 'ncrm_activity', 1, '63', 'duration_hours', 'Duration', 1, 0, '', 100, 17, 19, 3, 'T~O', 1, NULL, 'BAS', 1, NULL, 0),
	(9, 249, 'duration_minutes', 'ncrm_activity', 1, '16', 'duration_minutes', 'Duration Minutes', 1, 0, '', 100, 18, 19, 3, 'T~O', 1, NULL, 'BAS', 1, NULL, 0),
	(9, 250, 'location', 'ncrm_activity', 1, '1', 'location', 'Location', 1, 0, '', 100, 19, 19, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(9, 251, 'reminder_time', 'ncrm_activity_reminder', 1, '30', 'reminder_time', 'Send Reminder', 1, 0, '', 100, 1, 19, 3, 'I~O', 1, NULL, 'BAS', 1, NULL, 0),
	(9, 252, 'recurringtype', 'ncrm_activity', 1, '16', 'recurringtype', 'Recurrence', 1, 0, '', 100, 6, 19, 3, 'O~O', 1, NULL, 'BAS', 1, NULL, 1),
	(9, 253, 'notime', 'ncrm_activity', 1, '56', 'notime', 'No Time', 1, 0, '', 100, 20, 19, 3, 'C~O', 1, NULL, 'BAS', 1, NULL, 0),
	(9, 254, 'modifiedby', 'ncrm_crmentity', 1, '52', 'modifiedby', 'Last Modified By', 1, 0, '', 100, 22, 19, 3, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(16, 255, 'subject', 'ncrm_activity', 1, '2', 'subject', 'Subject', 1, 0, '', 100, 1, 39, 1, 'V~M', 0, 1, 'BAS', 1, NULL, 1),
	(16, 256, 'smownerid', 'ncrm_crmentity', 1, '53', 'assigned_user_id', 'Assigned To', 1, 0, '', 100, 2, 39, 1, 'V~M', 0, 6, 'BAS', 1, NULL, 1),
	(16, 257, 'date_start', 'ncrm_activity', 1, '6', 'date_start', 'Start Date & Time', 1, 0, '', 100, 3, 39, 1, 'DT~M~time_start', 0, 2, 'BAS', 1, NULL, 1),
	(16, 258, 'time_start', 'ncrm_activity', 1, '2', 'time_start', 'Time Start', 1, 0, '', 100, 4, 39, 3, 'T~M', 1, NULL, 'BAS', 1, NULL, 1),
	(16, 259, 'due_date', 'ncrm_activity', 1, '23', 'due_date', 'End Date', 1, 0, '', 100, 5, 39, 1, 'D~M~OTH~GE~date_start~Start Date & Time', 0, 5, 'BAS', 1, NULL, 1),
	(16, 260, 'time_end', 'ncrm_activity', 1, '2', 'time_end', 'End Time', 1, 0, '', 100, 5, 39, 3, 'T~M', 1, NULL, 'BAS', 1, NULL, 1),
	(16, 261, 'recurringtype', 'ncrm_activity', 1, '16', 'recurringtype', 'Recurrence', 1, 0, '', 100, 6, 117, 1, 'O~O', 1, NULL, 'BAS', 1, NULL, 1),
	(16, 262, 'duration_hours', 'ncrm_activity', 1, '63', 'duration_hours', 'Duration', 1, 0, '', 100, 7, 39, 3, 'I~M', 1, NULL, 'BAS', 1, NULL, 0),
	(16, 263, 'duration_minutes', 'ncrm_activity', 1, '16', 'duration_minutes', 'Duration Minutes', 1, 0, '', 100, 8, 39, 3, 'O~O', 1, NULL, 'BAS', 1, NULL, 0),
	(16, 264, 'crmid', 'ncrm_seactivityrel', 1, '66', 'parent_id', 'Related To', 1, 0, '', 100, 9, 119, 1, 'I~O', 1, NULL, 'BAS', 1, NULL, 1),
	(16, 265, 'eventstatus', 'ncrm_activity', 1, '15', 'eventstatus', 'Status', 1, 0, '', 100, 10, 39, 1, 'V~M', 0, 3, 'BAS', 1, NULL, 0),
	(16, 266, 'sendnotification', 'ncrm_activity', 1, '56', 'sendnotification', 'Send Notification', 1, 0, '', 100, 11, 39, 1, 'C~O', 1, NULL, 'BAS', 1, NULL, 0),
	(16, 267, 'activitytype', 'ncrm_activity', 1, '15', 'activitytype', 'Activity Type', 1, 0, '', 100, 12, 39, 1, 'V~M', 0, 4, 'BAS', 1, NULL, 1),
	(16, 268, 'location', 'ncrm_activity', 1, '1', 'location', 'Location', 1, 0, '', 100, 13, 39, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(16, 269, 'createdtime', 'ncrm_crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, '', 100, 14, 39, 2, 'DT~O', 3, NULL, 'BAS', 0, NULL, 0),
	(16, 270, 'modifiedtime', 'ncrm_crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, '', 100, 15, 39, 2, 'DT~O', 3, NULL, 'BAS', 0, NULL, 0),
	(16, 271, 'priority', 'ncrm_activity', 1, '15', 'taskpriority', 'Priority', 1, 0, '', 100, 16, 39, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(16, 272, 'notime', 'ncrm_activity', 1, '56', 'notime', 'No Time', 1, 0, '', 100, 17, 39, 3, 'C~O', 1, NULL, 'BAS', 1, NULL, 0),
	(16, 273, 'visibility', 'ncrm_activity', 1, '16', 'visibility', 'Visibility', 1, 0, '', 100, 18, 39, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(16, 274, 'modifiedby', 'ncrm_crmentity', 1, '52', 'modifiedby', 'Last Modified By', 1, 0, '', 100, 22, 39, 3, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(16, 275, 'description', 'ncrm_crmentity', 1, '19', 'description', 'Description', 1, 0, '', 100, 1, 41, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(16, 276, 'reminder_time', 'ncrm_activity_reminder', 1, '30', 'reminder_time', 'Send Reminder', 1, 0, '', 100, 1, 40, 1, 'I~O', 1, NULL, 'BAS', 1, NULL, 0),
	(16, 277, 'contactid', 'ncrm_cntactivityrel', 1, '57', 'contact_id', 'Contact Name', 1, 0, '', 100, 1, 119, 1, 'I~O', 1, NULL, 'BAS', 1, NULL, 0),
	(15, 278, 'product_id', 'ncrm_faq', 1, '59', 'product_id', 'Product Name', 1, 2, '', 100, 1, 37, 1, 'I~O', 3, NULL, 'BAS', 1, NULL, 1),
	(15, 279, 'faq_no', 'ncrm_faq', 1, '4', 'faq_no', 'Faq No', 1, 0, '', 100, 2, 37, 1, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(15, 280, 'category', 'ncrm_faq', 1, '15', 'faqcategories', 'Category', 1, 2, '', 100, 4, 37, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 1),
	(15, 281, 'status', 'ncrm_faq', 1, '15', 'faqstatus', 'Status', 1, 2, '', 100, 3, 37, 1, 'V~M', 3, NULL, 'BAS', 1, NULL, 0),
	(15, 282, 'question', 'ncrm_faq', 1, '20', 'question', 'Question', 1, 2, '', 100, 7, 37, 1, 'V~M', 3, NULL, 'BAS', 1, NULL, 1),
	(15, 283, 'answer', 'ncrm_faq', 1, '20', 'faq_answer', 'Answer', 1, 2, '', 100, 8, 37, 1, 'V~M', 3, NULL, 'BAS', 1, NULL, 0),
	(15, 284, 'comments', 'ncrm_faqcomments', 1, '19', 'comments', 'Add Comment', 1, 1, '', 100, 1, 38, 1, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(15, 285, 'createdtime', 'ncrm_crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, '', 100, 5, 37, 2, 'DT~O', 3, NULL, 'BAS', 0, NULL, 1),
	(15, 286, 'modifiedtime', 'ncrm_crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, '', 100, 6, 37, 2, 'DT~O', 3, NULL, 'BAS', 0, NULL, 1),
	(15, 287, 'modifiedby', 'ncrm_crmentity', 1, '52', 'modifiedby', 'Last Modified By', 1, 0, '', 100, 7, 37, 3, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(18, 288, 'vendorname', 'ncrm_vendor', 1, '2', 'vendorname', 'Vendor Name', 1, 0, '', 100, 1, 42, 1, 'V~M', 0, 1, 'BAS', 1, NULL, 1),
	(18, 289, 'vendor_no', 'ncrm_vendor', 1, '4', 'vendor_no', 'Vendor No', 1, 0, '', 100, 2, 42, 1, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(18, 290, 'phone', 'ncrm_vendor', 1, '1', 'phone', 'Phone', 1, 2, '', 100, 4, 42, 1, 'V~O', 2, 2, 'BAS', 1, NULL, 1),
	(18, 291, 'email', 'ncrm_vendor', 1, '13', 'email', 'Email', 1, 2, '', 100, 3, 42, 1, 'E~O', 2, 3, 'BAS', 1, NULL, 1),
	(18, 292, 'website', 'ncrm_vendor', 1, '17', 'website', 'Website', 1, 2, '', 100, 6, 42, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(18, 293, 'glacct', 'ncrm_vendor', 1, '15', 'glacct', 'GL Account', 1, 2, '', 100, 5, 42, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(18, 294, 'category', 'ncrm_vendor', 1, '1', 'category', 'Category', 1, 2, '', 100, 8, 42, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 1),
	(18, 295, 'createdtime', 'ncrm_crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, '', 100, 7, 42, 2, 'DT~O', 3, NULL, 'BAS', 0, NULL, 0),
	(18, 296, 'modifiedtime', 'ncrm_crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, '', 100, 9, 42, 2, 'DT~O', 3, NULL, 'BAS', 0, NULL, 0),
	(18, 297, 'modifiedby', 'ncrm_crmentity', 1, '52', 'modifiedby', 'Last Modified By', 1, 0, '', 100, 12, 42, 3, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(18, 298, 'street', 'ncrm_vendor', 1, '21', 'street', 'Street', 1, 2, '', 100, 1, 44, 1, 'V~O', 1, NULL, 'ADV', 1, NULL, 0),
	(18, 299, 'pobox', 'ncrm_vendor', 1, '1', 'pobox', 'Po Box', 1, 2, '', 100, 2, 44, 1, 'V~O', 1, NULL, 'ADV', 1, NULL, 0),
	(18, 300, 'city', 'ncrm_vendor', 1, '1', 'city', 'City', 1, 2, '', 100, 3, 44, 1, 'V~O', 1, NULL, 'ADV', 1, NULL, 0),
	(18, 301, 'state', 'ncrm_vendor', 1, '1', 'state', 'State', 1, 2, '', 100, 4, 44, 1, 'V~O', 1, NULL, 'ADV', 1, NULL, 0),
	(18, 302, 'postalcode', 'ncrm_vendor', 1, '1', 'postalcode', 'Postal Code', 1, 2, '', 100, 5, 44, 1, 'V~O', 1, NULL, 'ADV', 1, NULL, 0),
	(18, 303, 'country', 'ncrm_vendor', 1, '1', 'country', 'Country', 1, 2, '', 100, 6, 44, 1, 'V~O', 1, NULL, 'ADV', 1, NULL, 0),
	(18, 304, 'description', 'ncrm_crmentity', 1, '19', 'description', 'Description', 1, 2, '', 100, 1, 45, 1, 'V~O', 1, NULL, 'ADV', 1, NULL, 0),
	(19, 305, 'bookname', 'ncrm_pricebook', 1, '2', 'bookname', 'Price Book Name', 1, 0, '', 100, 1, 46, 1, 'V~M', 0, 1, 'BAS', 1, NULL, 1),
	(19, 306, 'pricebook_no', 'ncrm_pricebook', 1, '4', 'pricebook_no', 'PriceBook No', 1, 0, '', 100, 3, 46, 1, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(19, 307, 'active', 'ncrm_pricebook', 1, '56', 'active', 'Active', 1, 2, '1', 100, 2, 46, 1, 'C~O', 2, 2, 'BAS', 1, NULL, 1),
	(19, 308, 'createdtime', 'ncrm_crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, '', 100, 4, 46, 2, 'DT~O', 3, NULL, 'BAS', 0, NULL, 0),
	(19, 309, 'modifiedtime', 'ncrm_crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, '', 100, 5, 46, 2, 'DT~O', 3, NULL, 'BAS', 0, NULL, 0),
	(19, 310, 'currency_id', 'ncrm_pricebook', 1, '117', 'currency_id', 'Currency', 1, 0, '', 100, 5, 46, 1, 'I~M', 0, 3, 'BAS', 0, NULL, 0),
	(19, 311, 'modifiedby', 'ncrm_crmentity', 1, '52', 'modifiedby', 'Last Modified By', 1, 0, '', 100, 7, 46, 3, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(19, 312, 'description', 'ncrm_crmentity', 1, '19', 'description', 'Description', 1, 2, '', 100, 1, 48, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(20, 313, 'quote_no', 'ncrm_quotes', 1, '4', 'quote_no', 'Quote No', 1, 0, '', 100, 3, 49, 1, 'V~O', 3, NULL, 'BAS', 0, NULL, 1),
	(20, 314, 'subject', 'ncrm_quotes', 1, '2', 'subject', 'Subject', 1, 0, '', 100, 1, 49, 1, 'V~M', 1, NULL, 'BAS', 1, NULL, 1),
	(20, 315, 'potentialid', 'ncrm_quotes', 1, '76', 'potential_id', 'Potential Name', 1, 2, '', 100, 2, 49, 1, 'I~O', 3, NULL, 'BAS', 1, NULL, 1),
	(20, 316, 'quotestage', 'ncrm_quotes', 1, '15', 'quotestage', 'Quote Stage', 1, 2, '', 100, 4, 49, 1, 'V~M', 3, NULL, 'BAS', 1, NULL, 1),
	(20, 317, 'validtill', 'ncrm_quotes', 1, '5', 'validtill', 'Valid Till', 1, 2, '', 100, 5, 49, 1, 'D~O', 3, NULL, 'BAS', 1, NULL, 0),
	(20, 318, 'contactid', 'ncrm_quotes', 1, '57', 'contact_id', 'Contact Name', 1, 2, '', 100, 6, 49, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(20, 319, 'carrier', 'ncrm_quotes', 1, '15', 'carrier', 'Carrier', 1, 2, '', 100, 8, 49, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(20, 320, 'subtotal', 'ncrm_quotes', 1, '72', 'hdnSubTotal', 'Sub Total', 1, 2, '', 100, 9, 49, 3, 'N~O', 3, NULL, 'BAS', 1, NULL, 0),
	(20, 321, 'shipping', 'ncrm_quotes', 1, '1', 'shipping', 'Shipping', 1, 2, '', 100, 10, 49, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(20, 322, 'inventorymanager', 'ncrm_quotes', 1, '77', 'assigned_user_id1', 'Inventory Manager', 1, 2, '', 100, 11, 49, 1, 'I~O', 3, NULL, 'BAS', 1, NULL, 0),
	(20, 323, 'adjustment', 'ncrm_quotes', 1, '72', 'txtAdjustment', 'Adjustment', 1, 2, '', 100, 20, 49, 3, 'NN~O', 3, NULL, 'BAS', 1, NULL, 0),
	(20, 324, 'total', 'ncrm_quotes', 1, '72', 'hdnGrandTotal', 'Total', 1, 2, '', 100, 14, 49, 3, 'N~O', 3, NULL, 'BAS', 1, NULL, 1),
	(20, 325, 'taxtype', 'ncrm_quotes', 1, '16', 'hdnTaxType', 'Tax Type', 1, 2, '', 100, 14, 49, 3, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(20, 326, 'discount_percent', 'ncrm_quotes', 1, '1', 'hdnDiscountPercent', 'Discount Percent', 1, 2, '', 100, 14, 49, 3, 'N~O', 3, NULL, 'BAS', 1, NULL, 0),
	(20, 327, 'discount_amount', 'ncrm_quotes', 1, '72', 'hdnDiscountAmount', 'Discount Amount', 1, 2, '', 100, 14, 49, 3, 'N~O', 3, NULL, 'BAS', 1, NULL, 0),
	(20, 328, 's_h_amount', 'ncrm_quotes', 1, '72', 'hdnS_H_Amount', 'S&H Amount', 1, 2, '', 100, 14, 49, 3, 'N~O', 3, NULL, 'BAS', 1, NULL, 0),
	(20, 329, 'accountid', 'ncrm_quotes', 1, '73', 'account_id', 'Account Name', 1, 2, '', 100, 16, 49, 1, 'I~M', 3, NULL, 'BAS', 1, NULL, 1),
	(20, 330, 'smownerid', 'ncrm_crmentity', 1, '53', 'assigned_user_id', 'Assigned To', 1, 0, '', 100, 17, 49, 1, 'V~M', 3, NULL, 'BAS', 1, NULL, 1),
	(20, 331, 'createdtime', 'ncrm_crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, '', 100, 18, 49, 2, 'DT~O', 3, NULL, 'BAS', 0, NULL, 0),
	(20, 332, 'modifiedtime', 'ncrm_crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, '', 100, 19, 49, 2, 'DT~O', 3, NULL, 'BAS', 0, NULL, 0),
	(20, 333, 'modifiedby', 'ncrm_crmentity', 1, '52', 'modifiedby', 'Last Modified By', 1, 0, '', 100, 22, 49, 3, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(20, 334, 'currency_id', 'ncrm_quotes', 1, '117', 'currency_id', 'Currency', 1, 2, '1', 100, 20, 49, 3, 'I~O', 3, NULL, 'BAS', 1, NULL, 0),
	(20, 335, 'conversion_rate', 'ncrm_quotes', 1, '1', 'conversion_rate', 'Conversion Rate', 1, 2, '1', 100, 21, 49, 3, 'N~O', 3, NULL, 'BAS', 1, NULL, 0),
	(20, 336, 'bill_street', 'ncrm_quotesbillads', 1, '24', 'bill_street', 'Billing Address', 1, 2, '', 100, 1, 51, 1, 'V~M', 3, NULL, 'BAS', 1, NULL, 0),
	(20, 337, 'ship_street', 'ncrm_quotesshipads', 1, '24', 'ship_street', 'Shipping Address', 1, 2, '', 100, 2, 51, 1, 'V~M', 3, NULL, 'BAS', 1, NULL, 0),
	(20, 338, 'bill_city', 'ncrm_quotesbillads', 1, '1', 'bill_city', 'Billing City', 1, 2, '', 100, 5, 51, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(20, 339, 'ship_city', 'ncrm_quotesshipads', 1, '1', 'ship_city', 'Shipping City', 1, 2, '', 100, 6, 51, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(20, 340, 'bill_state', 'ncrm_quotesbillads', 1, '1', 'bill_state', 'Billing State', 1, 2, '', 100, 7, 51, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(20, 341, 'ship_state', 'ncrm_quotesshipads', 1, '1', 'ship_state', 'Shipping State', 1, 2, '', 100, 8, 51, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(20, 342, 'bill_code', 'ncrm_quotesbillads', 1, '1', 'bill_code', 'Billing Code', 1, 2, '', 100, 9, 51, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(20, 343, 'ship_code', 'ncrm_quotesshipads', 1, '1', 'ship_code', 'Shipping Code', 1, 2, '', 100, 10, 51, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(20, 344, 'bill_country', 'ncrm_quotesbillads', 1, '1', 'bill_country', 'Billing Country', 1, 2, '', 100, 11, 51, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(20, 345, 'ship_country', 'ncrm_quotesshipads', 1, '1', 'ship_country', 'Shipping Country', 1, 2, '', 100, 12, 51, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(20, 346, 'bill_pobox', 'ncrm_quotesbillads', 1, '1', 'bill_pobox', 'Billing Po Box', 1, 2, '', 100, 3, 51, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(20, 347, 'ship_pobox', 'ncrm_quotesshipads', 1, '1', 'ship_pobox', 'Shipping Po Box', 1, 2, '', 100, 4, 51, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(20, 348, 'description', 'ncrm_crmentity', 1, '19', 'description', 'Description', 1, 2, '', 100, 1, 54, 1, 'V~O', 3, NULL, 'ADV', 1, NULL, 0),
	(20, 349, 'terms_conditions', 'ncrm_quotes', 1, '19', 'terms_conditions', 'Terms & Conditions', 1, 2, '', 100, 1, 53, 1, 'V~O', 3, NULL, 'ADV', 1, NULL, 0),
	(21, 350, 'purchaseorder_no', 'ncrm_purchaseorder', 1, '4', 'purchaseorder_no', 'PurchaseOrder No', 1, 0, '', 100, 2, 55, 1, 'V~O', 3, NULL, 'BAS', 0, NULL, 1),
	(21, 351, 'subject', 'ncrm_purchaseorder', 1, '2', 'subject', 'Subject', 1, 0, '', 100, 1, 55, 1, 'V~M', 3, NULL, 'BAS', 1, NULL, 1),
	(21, 352, 'vendorid', 'ncrm_purchaseorder', 1, '81', 'vendor_id', 'Vendor Name', 1, 0, '', 100, 3, 55, 1, 'I~M', 3, NULL, 'BAS', 1, NULL, 1),
	(21, 353, 'requisition_no', 'ncrm_purchaseorder', 1, '1', 'requisition_no', 'Requisition No', 1, 2, '', 100, 4, 55, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(21, 354, 'tracking_no', 'ncrm_purchaseorder', 1, '1', 'tracking_no', 'Tracking Number', 1, 2, '', 100, 5, 55, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 1),
	(21, 355, 'contactid', 'ncrm_purchaseorder', 1, '57', 'contact_id', 'Contact Name', 1, 2, '', 100, 6, 55, 1, 'I~O', 3, NULL, 'BAS', 1, NULL, 0),
	(21, 356, 'duedate', 'ncrm_purchaseorder', 1, '5', 'duedate', 'Due Date', 1, 2, '', 100, 7, 55, 1, 'D~O', 3, NULL, 'BAS', 1, NULL, 0),
	(21, 357, 'carrier', 'ncrm_purchaseorder', 1, '15', 'carrier', 'Carrier', 1, 2, '', 100, 8, 55, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(21, 358, 'adjustment', 'ncrm_purchaseorder', 1, '72', 'txtAdjustment', 'Adjustment', 1, 2, '', 100, 10, 55, 3, 'NN~O', 3, NULL, 'BAS', 1, NULL, 0),
	(21, 359, 'salescommission', 'ncrm_purchaseorder', 1, '1', 'salescommission', 'Sales Commission', 1, 2, '', 100, 11, 55, 1, 'N~O', 3, NULL, 'BAS', 1, NULL, 0),
	(21, 360, 'exciseduty', 'ncrm_purchaseorder', 1, '1', 'exciseduty', 'Excise Duty', 1, 2, '', 100, 12, 55, 1, 'N~O', 3, NULL, 'BAS', 1, NULL, 0),
	(21, 361, 'total', 'ncrm_purchaseorder', 1, '72', 'hdnGrandTotal', 'Total', 1, 2, '', 100, 13, 55, 3, 'N~O', 3, NULL, 'BAS', 1, NULL, 1),
	(21, 362, 'subtotal', 'ncrm_purchaseorder', 1, '72', 'hdnSubTotal', 'Sub Total', 1, 2, '', 100, 14, 55, 3, 'N~O', 3, NULL, 'BAS', 1, NULL, 0),
	(21, 363, 'taxtype', 'ncrm_purchaseorder', 1, '16', 'hdnTaxType', 'Tax Type', 1, 2, '', 100, 14, 55, 3, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(21, 364, 'discount_percent', 'ncrm_purchaseorder', 1, '1', 'hdnDiscountPercent', 'Discount Percent', 1, 2, '', 100, 14, 55, 3, 'N~O', 3, NULL, 'BAS', 1, NULL, 0),
	(21, 365, 'discount_amount', 'ncrm_purchaseorder', 1, '72', 'hdnDiscountAmount', 'Discount Amount', 1, 0, '', 100, 14, 55, 3, 'N~O', 3, NULL, 'BAS', 1, NULL, 0),
	(21, 366, 's_h_amount', 'ncrm_purchaseorder', 1, '72', 'hdnS_H_Amount', 'S&H Amount', 1, 2, '', 100, 14, 55, 3, 'N~O', 3, NULL, 'BAS', 1, NULL, 0),
	(21, 367, 'postatus', 'ncrm_purchaseorder', 1, '15', 'postatus', 'Status', 1, 2, '', 100, 15, 55, 1, 'V~M', 3, NULL, 'BAS', 1, NULL, 0),
	(21, 368, 'smownerid', 'ncrm_crmentity', 1, '53', 'assigned_user_id', 'Assigned To', 1, 0, '', 100, 16, 55, 1, 'V~M', 3, NULL, 'BAS', 1, NULL, 1),
	(21, 369, 'createdtime', 'ncrm_crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, '', 100, 17, 55, 2, 'DT~O', 3, NULL, 'BAS', 0, NULL, 0),
	(21, 370, 'modifiedtime', 'ncrm_crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, '', 100, 18, 55, 2, 'DT~O', 3, NULL, 'BAS', 0, NULL, 0),
	(21, 371, 'modifiedby', 'ncrm_crmentity', 1, '52', 'modifiedby', 'Last Modified By', 1, 0, '', 100, 22, 55, 3, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(21, 372, 'currency_id', 'ncrm_purchaseorder', 1, '117', 'currency_id', 'Currency', 1, 2, '1', 100, 19, 55, 3, 'I~O', 3, NULL, 'BAS', 1, NULL, 0),
	(21, 373, 'conversion_rate', 'ncrm_purchaseorder', 1, '1', 'conversion_rate', 'Conversion Rate', 1, 2, '1', 100, 20, 55, 3, 'N~O', 3, NULL, 'BAS', 1, NULL, 0),
	(21, 374, 'bill_street', 'ncrm_pobillads', 1, '24', 'bill_street', 'Billing Address', 1, 2, '', 100, 1, 57, 1, 'V~M', 3, NULL, 'BAS', 1, NULL, 0),
	(21, 375, 'ship_street', 'ncrm_poshipads', 1, '24', 'ship_street', 'Shipping Address', 1, 2, '', 100, 2, 57, 1, 'V~M', 3, NULL, 'BAS', 1, NULL, 0),
	(21, 376, 'bill_city', 'ncrm_pobillads', 1, '1', 'bill_city', 'Billing City', 1, 2, '', 100, 5, 57, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(21, 377, 'ship_city', 'ncrm_poshipads', 1, '1', 'ship_city', 'Shipping City', 1, 2, '', 100, 6, 57, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(21, 378, 'bill_state', 'ncrm_pobillads', 1, '1', 'bill_state', 'Billing State', 1, 2, '', 100, 7, 57, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(21, 379, 'ship_state', 'ncrm_poshipads', 1, '1', 'ship_state', 'Shipping State', 1, 2, '', 100, 8, 57, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(21, 380, 'bill_code', 'ncrm_pobillads', 1, '1', 'bill_code', 'Billing Code', 1, 2, '', 100, 9, 57, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(21, 381, 'ship_code', 'ncrm_poshipads', 1, '1', 'ship_code', 'Shipping Code', 1, 2, '', 100, 10, 57, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(21, 382, 'bill_country', 'ncrm_pobillads', 1, '1', 'bill_country', 'Billing Country', 1, 2, '', 100, 11, 57, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(21, 383, 'ship_country', 'ncrm_poshipads', 1, '1', 'ship_country', 'Shipping Country', 1, 2, '', 100, 12, 57, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(21, 384, 'bill_pobox', 'ncrm_pobillads', 1, '1', 'bill_pobox', 'Billing Po Box', 1, 2, '', 100, 3, 57, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(21, 385, 'ship_pobox', 'ncrm_poshipads', 1, '1', 'ship_pobox', 'Shipping Po Box', 1, 2, '', 100, 4, 57, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(21, 386, 'description', 'ncrm_crmentity', 1, '19', 'description', 'Description', 1, 2, '', 100, 1, 60, 1, 'V~O', 3, NULL, 'ADV', 1, NULL, 0),
	(21, 387, 'terms_conditions', 'ncrm_purchaseorder', 1, '19', 'terms_conditions', 'Terms & Conditions', 1, 2, '', 100, 1, 59, 1, 'V~O', 3, NULL, 'ADV', 1, NULL, 0),
	(22, 388, 'salesorder_no', 'ncrm_salesorder', 1, '4', 'salesorder_no', 'SalesOrder No', 1, 0, '', 100, 4, 61, 1, 'V~O', 3, NULL, 'BAS', 0, NULL, 1),
	(22, 389, 'subject', 'ncrm_salesorder', 1, '2', 'subject', 'Subject', 1, 0, '', 100, 1, 61, 1, 'V~M', 3, NULL, 'BAS', 1, NULL, 1),
	(22, 390, 'potentialid', 'ncrm_salesorder', 1, '76', 'potential_id', 'Potential Name', 1, 2, '', 100, 2, 61, 1, 'I~O', 3, NULL, 'BAS', 1, NULL, 0),
	(22, 391, 'customerno', 'ncrm_salesorder', 1, '1', 'customerno', 'Customer No', 1, 2, '', 100, 3, 61, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(22, 392, 'quoteid', 'ncrm_salesorder', 1, '78', 'quote_id', 'Quote Name', 1, 2, '', 100, 5, 61, 1, 'I~O', 3, NULL, 'BAS', 0, NULL, 1),
	(22, 393, 'purchaseorder', 'ncrm_salesorder', 1, '1', 'ncrm_purchaseorder', 'Purchase Order', 1, 2, '', 100, 5, 61, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(22, 394, 'contactid', 'ncrm_salesorder', 1, '57', 'contact_id', 'Contact Name', 1, 2, '', 100, 6, 61, 1, 'I~O', 3, NULL, 'BAS', 1, NULL, 0),
	(22, 395, 'duedate', 'ncrm_salesorder', 1, '5', 'duedate', 'Due Date', 1, 2, '', 100, 8, 61, 1, 'D~O', 3, NULL, 'BAS', 1, NULL, 0),
	(22, 396, 'carrier', 'ncrm_salesorder', 1, '15', 'carrier', 'Carrier', 1, 2, '', 100, 9, 61, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(22, 397, 'pending', 'ncrm_salesorder', 1, '1', 'pending', 'Pending', 1, 2, '', 100, 10, 61, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(22, 398, 'sostatus', 'ncrm_salesorder', 1, '15', 'sostatus', 'Status', 1, 2, '', 100, 11, 61, 1, 'V~M', 3, NULL, 'BAS', 1, NULL, 0),
	(22, 399, 'adjustment', 'ncrm_salesorder', 1, '72', 'txtAdjustment', 'Adjustment', 1, 2, '', 100, 12, 61, 3, 'NN~O', 3, NULL, 'BAS', 1, NULL, 0),
	(22, 400, 'salescommission', 'ncrm_salesorder', 1, '1', 'salescommission', 'Sales Commission', 1, 2, '', 100, 13, 61, 1, 'N~O', 3, NULL, 'BAS', 1, NULL, 0),
	(22, 401, 'exciseduty', 'ncrm_salesorder', 1, '1', 'exciseduty', 'Excise Duty', 1, 2, '', 100, 13, 61, 1, 'N~O', 3, NULL, 'BAS', 1, NULL, 0),
	(22, 402, 'total', 'ncrm_salesorder', 1, '72', 'hdnGrandTotal', 'Total', 1, 2, '', 100, 14, 61, 3, 'N~O', 3, NULL, 'BAS', 1, NULL, 1),
	(22, 403, 'subtotal', 'ncrm_salesorder', 1, '72', 'hdnSubTotal', 'Sub Total', 1, 2, '', 100, 15, 61, 3, 'N~O', 3, NULL, 'BAS', 1, NULL, 0),
	(22, 404, 'taxtype', 'ncrm_salesorder', 1, '16', 'hdnTaxType', 'Tax Type', 1, 2, '', 100, 15, 61, 3, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(22, 405, 'discount_percent', 'ncrm_salesorder', 1, '1', 'hdnDiscountPercent', 'Discount Percent', 1, 2, '', 100, 15, 61, 3, 'N~O', 3, NULL, 'BAS', 1, NULL, 0),
	(22, 406, 'discount_amount', 'ncrm_salesorder', 1, '72', 'hdnDiscountAmount', 'Discount Amount', 1, 0, '', 100, 15, 61, 3, 'N~O', 3, NULL, 'BAS', 1, NULL, 0),
	(22, 407, 's_h_amount', 'ncrm_salesorder', 1, '72', 'hdnS_H_Amount', 'S&H Amount', 1, 2, '', 100, 15, 61, 3, 'N~O', 3, NULL, 'BAS', 1, NULL, 0),
	(22, 408, 'accountid', 'ncrm_salesorder', 1, '73', 'account_id', 'Account Name', 1, 2, '', 100, 16, 61, 1, 'I~M', 3, NULL, 'BAS', 1, NULL, 1),
	(22, 409, 'smownerid', 'ncrm_crmentity', 1, '53', 'assigned_user_id', 'Assigned To', 1, 0, '', 100, 17, 61, 1, 'V~M', 3, NULL, 'BAS', 1, NULL, 1),
	(22, 410, 'createdtime', 'ncrm_crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, '', 100, 18, 61, 2, 'DT~O', 3, NULL, 'BAS', 0, NULL, 0),
	(22, 411, 'modifiedtime', 'ncrm_crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, '', 100, 19, 61, 2, 'DT~O', 3, NULL, 'BAS', 0, NULL, 0),
	(22, 412, 'modifiedby', 'ncrm_crmentity', 1, '52', 'modifiedby', 'Last Modified By', 1, 0, '', 100, 22, 61, 3, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(22, 413, 'currency_id', 'ncrm_salesorder', 1, '117', 'currency_id', 'Currency', 1, 2, '1', 100, 20, 61, 3, 'I~O', 3, NULL, 'BAS', 1, NULL, 0),
	(22, 414, 'conversion_rate', 'ncrm_salesorder', 1, '1', 'conversion_rate', 'Conversion Rate', 1, 2, '1', 100, 21, 61, 3, 'N~O', 3, NULL, 'BAS', 1, NULL, 0),
	(22, 415, 'bill_street', 'ncrm_sobillads', 1, '24', 'bill_street', 'Billing Address', 1, 2, '', 100, 1, 63, 1, 'V~M', 3, NULL, 'BAS', 1, NULL, 0),
	(22, 416, 'ship_street', 'ncrm_soshipads', 1, '24', 'ship_street', 'Shipping Address', 1, 2, '', 100, 2, 63, 1, 'V~M', 3, NULL, 'BAS', 1, NULL, 0),
	(22, 417, 'bill_city', 'ncrm_sobillads', 1, '1', 'bill_city', 'Billing City', 1, 2, '', 100, 5, 63, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(22, 418, 'ship_city', 'ncrm_soshipads', 1, '1', 'ship_city', 'Shipping City', 1, 2, '', 100, 6, 63, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(22, 419, 'bill_state', 'ncrm_sobillads', 1, '1', 'bill_state', 'Billing State', 1, 2, '', 100, 7, 63, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(22, 420, 'ship_state', 'ncrm_soshipads', 1, '1', 'ship_state', 'Shipping State', 1, 2, '', 100, 8, 63, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(22, 421, 'bill_code', 'ncrm_sobillads', 1, '1', 'bill_code', 'Billing Code', 1, 2, '', 100, 9, 63, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(22, 422, 'ship_code', 'ncrm_soshipads', 1, '1', 'ship_code', 'Shipping Code', 1, 2, '', 100, 10, 63, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(22, 423, 'bill_country', 'ncrm_sobillads', 1, '1', 'bill_country', 'Billing Country', 1, 2, '', 100, 11, 63, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(22, 424, 'ship_country', 'ncrm_soshipads', 1, '1', 'ship_country', 'Shipping Country', 1, 2, '', 100, 12, 63, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(22, 425, 'bill_pobox', 'ncrm_sobillads', 1, '1', 'bill_pobox', 'Billing Po Box', 1, 2, '', 100, 3, 63, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(22, 426, 'ship_pobox', 'ncrm_soshipads', 1, '1', 'ship_pobox', 'Shipping Po Box', 1, 2, '', 100, 4, 63, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(22, 427, 'description', 'ncrm_crmentity', 1, '19', 'description', 'Description', 1, 2, '', 100, 1, 66, 1, 'V~O', 3, NULL, 'ADV', 1, NULL, 0),
	(22, 428, 'terms_conditions', 'ncrm_salesorder', 1, '19', 'terms_conditions', 'Terms & Conditions', 1, 2, '', 100, 1, 65, 1, 'V~O', 3, NULL, 'ADV', 1, NULL, 0),
	(22, 429, 'enable_recurring', 'ncrm_salesorder', 1, '56', 'enable_recurring', 'Enable Recurring', 1, 0, '', 100, 1, 85, 1, 'C~O', 3, NULL, 'BAS', 0, NULL, 0),
	(22, 430, 'recurring_frequency', 'ncrm_invoice_recurring_info', 1, '16', 'recurring_frequency', 'Frequency', 1, 0, '', 100, 2, 85, 1, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(22, 431, 'start_period', 'ncrm_invoice_recurring_info', 1, '5', 'start_period', 'Start Period', 1, 0, '', 100, 3, 85, 1, 'D~O', 3, NULL, 'BAS', 0, NULL, 0),
	(22, 432, 'end_period', 'ncrm_invoice_recurring_info', 1, '5', 'end_period', 'End Period', 1, 0, '', 100, 4, 85, 1, 'D~O~OTH~G~start_period~Start Period', 3, NULL, 'BAS', 0, NULL, 0),
	(22, 433, 'payment_duration', 'ncrm_invoice_recurring_info', 1, '16', 'payment_duration', 'Payment Duration', 1, 0, '', 100, 5, 85, 1, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(22, 434, 'invoice_status', 'ncrm_invoice_recurring_info', 1, '15', 'invoicestatus', 'Invoice Status', 1, 0, '', 100, 6, 85, 1, 'V~M', 3, NULL, 'BAS', 0, NULL, 0),
	(23, 435, 'subject', 'ncrm_invoice', 1, '2', 'subject', 'Subject', 1, 0, '', 100, 1, 67, 1, 'V~M', 3, NULL, 'BAS', 1, NULL, 1),
	(23, 436, 'salesorderid', 'ncrm_invoice', 1, '80', 'salesorder_id', 'Sales Order', 1, 2, '', 100, 2, 67, 1, 'I~O', 3, NULL, 'BAS', 0, NULL, 1),
	(23, 437, 'customerno', 'ncrm_invoice', 1, '1', 'customerno', 'Customer No', 1, 2, '', 100, 3, 67, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(23, 438, 'contactid', 'ncrm_invoice', 1, '57', 'contact_id', 'Contact Name', 1, 2, '', 100, 4, 67, 1, 'I~O', 3, NULL, 'BAS', 1, NULL, 0),
	(23, 439, 'invoicedate', 'ncrm_invoice', 1, '5', 'invoicedate', 'Invoice Date', 1, 2, '', 100, 5, 67, 1, 'D~O', 3, NULL, 'BAS', 1, NULL, 0),
	(23, 440, 'duedate', 'ncrm_invoice', 1, '5', 'duedate', 'Due Date', 1, 2, '', 100, 6, 67, 1, 'D~O', 3, NULL, 'BAS', 1, NULL, 0),
	(23, 441, 'purchaseorder', 'ncrm_invoice', 1, '1', 'ncrm_purchaseorder', 'Purchase Order', 1, 2, '', 100, 8, 67, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(23, 442, 'adjustment', 'ncrm_invoice', 1, '72', 'txtAdjustment', 'Adjustment', 1, 2, '', 100, 9, 67, 3, 'NN~O', 3, NULL, 'BAS', 1, NULL, 0),
	(23, 443, 'salescommission', 'ncrm_invoice', 1, '1', 'salescommission', 'Sales Commission', 1, 2, '', 10, 13, 67, 1, 'N~O', 3, NULL, 'BAS', 1, NULL, 0),
	(23, 444, 'exciseduty', 'ncrm_invoice', 1, '1', 'exciseduty', 'Excise Duty', 1, 2, '', 100, 11, 67, 1, 'N~O', 3, NULL, 'BAS', 1, NULL, 0),
	(23, 445, 'subtotal', 'ncrm_invoice', 1, '72', 'hdnSubTotal', 'Sub Total', 1, 2, '', 100, 12, 67, 3, 'N~O', 3, NULL, 'BAS', 1, NULL, 0),
	(23, 446, 'total', 'ncrm_invoice', 1, '72', 'hdnGrandTotal', 'Total', 1, 2, '', 100, 13, 67, 3, 'N~O', 3, NULL, 'BAS', 1, NULL, 1),
	(23, 447, 'taxtype', 'ncrm_invoice', 1, '16', 'hdnTaxType', 'Tax Type', 1, 2, '', 100, 13, 67, 3, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(23, 448, 'discount_percent', 'ncrm_invoice', 1, '1', 'hdnDiscountPercent', 'Discount Percent', 1, 2, '', 100, 13, 67, 3, 'N~O', 3, NULL, 'BAS', 1, NULL, 0),
	(23, 449, 'discount_amount', 'ncrm_invoice', 1, '72', 'hdnDiscountAmount', 'Discount Amount', 1, 2, '', 100, 13, 67, 3, 'N~O', 3, NULL, 'BAS', 1, NULL, 0),
	(23, 450, 's_h_amount', 'ncrm_invoice', 1, '72', 'hdnS_H_Amount', 'S&H Amount', 1, 2, '', 100, 14, 67, 3, 'N~O', 3, NULL, 'BAS', 1, NULL, 0),
	(23, 451, 'accountid', 'ncrm_invoice', 1, '73', 'account_id', 'Account Name', 1, 2, '', 100, 14, 67, 1, 'I~M', 3, NULL, 'BAS', 1, NULL, 0),
	(23, 452, 'invoicestatus', 'ncrm_invoice', 1, '15', 'invoicestatus', 'Status', 1, 2, '', 100, 15, 67, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 1),
	(23, 453, 'smownerid', 'ncrm_crmentity', 1, '53', 'assigned_user_id', 'Assigned To', 1, 0, '', 100, 16, 67, 1, 'V~M', 3, NULL, 'BAS', 1, NULL, 1),
	(23, 454, 'createdtime', 'ncrm_crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, '', 100, 17, 67, 2, 'DT~O', 3, NULL, 'BAS', 0, NULL, 0),
	(23, 455, 'modifiedtime', 'ncrm_crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, '', 100, 18, 67, 2, 'DT~O', 3, NULL, 'BAS', 0, NULL, 0),
	(23, 456, 'modifiedby', 'ncrm_crmentity', 1, '52', 'modifiedby', 'Last Modified By', 1, 0, '', 100, 22, 67, 3, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(23, 457, 'currency_id', 'ncrm_invoice', 1, '117', 'currency_id', 'Currency', 1, 2, '1', 100, 19, 67, 3, 'I~O', 3, NULL, 'BAS', 1, NULL, 0),
	(23, 458, 'conversion_rate', 'ncrm_invoice', 1, '1', 'conversion_rate', 'Conversion Rate', 1, 2, '1', 100, 20, 67, 3, 'N~O', 3, NULL, 'BAS', 1, NULL, 0),
	(23, 459, 'bill_street', 'ncrm_invoicebillads', 1, '24', 'bill_street', 'Billing Address', 1, 2, '', 100, 1, 69, 1, 'V~M', 3, NULL, 'BAS', 1, NULL, 0),
	(23, 460, 'ship_street', 'ncrm_invoiceshipads', 1, '24', 'ship_street', 'Shipping Address', 1, 2, '', 100, 2, 69, 1, 'V~M', 3, NULL, 'BAS', 1, NULL, 0),
	(23, 461, 'bill_city', 'ncrm_invoicebillads', 1, '1', 'bill_city', 'Billing City', 1, 2, '', 100, 5, 69, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(23, 462, 'ship_city', 'ncrm_invoiceshipads', 1, '1', 'ship_city', 'Shipping City', 1, 2, '', 100, 6, 69, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(23, 463, 'bill_state', 'ncrm_invoicebillads', 1, '1', 'bill_state', 'Billing State', 1, 2, '', 100, 7, 69, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(23, 464, 'ship_state', 'ncrm_invoiceshipads', 1, '1', 'ship_state', 'Shipping State', 1, 2, '', 100, 8, 69, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(23, 465, 'bill_code', 'ncrm_invoicebillads', 1, '1', 'bill_code', 'Billing Code', 1, 2, '', 100, 9, 69, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(23, 466, 'ship_code', 'ncrm_invoiceshipads', 1, '1', 'ship_code', 'Shipping Code', 1, 2, '', 100, 10, 69, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(23, 467, 'bill_country', 'ncrm_invoicebillads', 1, '1', 'bill_country', 'Billing Country', 1, 2, '', 100, 11, 69, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(23, 468, 'ship_country', 'ncrm_invoiceshipads', 1, '1', 'ship_country', 'Shipping Country', 1, 2, '', 100, 12, 69, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(23, 469, 'bill_pobox', 'ncrm_invoicebillads', 1, '1', 'bill_pobox', 'Billing Po Box', 1, 2, '', 100, 3, 69, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(23, 470, 'ship_pobox', 'ncrm_invoiceshipads', 1, '1', 'ship_pobox', 'Shipping Po Box', 1, 2, '', 100, 4, 69, 1, 'V~O', 3, NULL, 'BAS', 1, NULL, 0),
	(23, 471, 'description', 'ncrm_crmentity', 1, '19', 'description', 'Description', 1, 2, '', 100, 1, 72, 1, 'V~O', 3, NULL, 'ADV', 1, NULL, 0),
	(23, 472, 'terms_conditions', 'ncrm_invoice', 1, '19', 'terms_conditions', 'Terms & Conditions', 1, 2, '', 100, 1, 71, 1, 'V~O', 3, NULL, 'ADV', 1, NULL, 0),
	(23, 473, 'invoice_no', 'ncrm_invoice', 1, '4', 'invoice_no', 'Invoice No', 1, 0, '', 100, 3, 67, 1, 'V~O', 3, NULL, 'BAS', 0, NULL, 1),
	(29, 474, 'user_name', 'ncrm_users', 1, '106', 'user_name', 'User Name', 1, 0, '', 11, 1, 77, 1, 'V~M', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 475, 'is_admin', 'ncrm_users', 1, '156', 'is_admin', 'Admin', 1, 0, '', 3, 7, 77, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 476, 'user_password', 'ncrm_users', 1, '99', 'user_password', 'Password', 1, 0, '', 30, 5, 77, 4, 'P~M', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 477, 'confirm_password', 'ncrm_users', 1, '99', 'confirm_password', 'Confirm Password', 1, 0, '', 30, 6, 77, 4, 'P~M', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 478, 'first_name', 'ncrm_users', 1, '1', 'first_name', 'First Name', 1, 0, '', 30, 3, 77, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 479, 'last_name', 'ncrm_users', 1, '2', 'last_name', 'Last Name', 1, 0, '', 30, 4, 77, 1, 'V~M', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 480, 'roleid', 'ncrm_user2role', 1, '98', 'roleid', 'Role', 1, 0, '', 200, 8, 77, 1, 'V~M', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 481, 'email1', 'ncrm_users', 1, '104', 'email1', 'Email', 1, 0, '', 100, 2, 77, 1, 'E~M', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 482, 'status', 'ncrm_users', 1, '115', 'status', 'Status', 1, 0, 'Active', 100, 10, 77, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 483, 'activity_view', 'ncrm_users', 1, '16', 'activity_view', 'Default Activity View', 1, 0, '', 100, 6, 118, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 484, 'lead_view', 'ncrm_users', 1, '16', 'lead_view', 'Default Lead View', 1, 0, '', 100, 9, 77, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 485, 'hour_format', 'ncrm_users', 1, '16', 'hour_format', 'Calendar Hour Format', 1, 0, '12', 100, 4, 118, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 486, 'end_hour', 'ncrm_users', 1, '116', 'end_hour', 'Day ends at', 1, 0, '', 100, 11, 77, 3, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 487, 'start_hour', 'ncrm_users', 1, '16', 'start_hour', 'Day starts at', 1, 0, '', 100, 2, 118, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 488, 'title', 'ncrm_users', 1, '1', 'title', 'Title', 1, 0, '', 50, 1, 79, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 489, 'phone_work', 'ncrm_users', 1, '11', 'phone_work', 'Office Phone', 1, 0, '', 50, 5, 79, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 490, 'department', 'ncrm_users', 1, '1', 'department', 'Department', 1, 0, '', 50, 3, 79, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 491, 'phone_mobile', 'ncrm_users', 1, '11', 'phone_mobile', 'Mobile', 1, 0, '', 50, 7, 79, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 492, 'reports_to_id', 'ncrm_users', 1, '101', 'reports_to_id', 'Reports To', 1, 0, '', 50, 8, 79, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 493, 'phone_other', 'ncrm_users', 1, '11', 'phone_other', 'Other Phone', 1, 0, '', 50, 11, 79, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 494, 'email2', 'ncrm_users', 1, '13', 'email2', 'Other Email', 1, 0, '', 100, 4, 79, 1, 'E~O', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 495, 'phone_fax', 'ncrm_users', 1, '11', 'phone_fax', 'Fax', 1, 0, '', 50, 2, 79, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 496, 'secondaryemail', 'ncrm_users', 1, '13', 'secondaryemail', 'Secondary Email', 1, 0, '', 100, 6, 79, 1, 'E~O', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 497, 'phone_home', 'ncrm_users', 1, '11', 'phone_home', 'Home Phone', 1, 0, '', 50, 9, 79, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 498, 'date_format', 'ncrm_users', 1, '16', 'date_format', 'Date Format', 1, 0, '', 30, 3, 118, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 499, 'signature', 'ncrm_users', 1, '21', 'signature', 'Signature', 1, 0, '', 250, 13, 79, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 500, 'description', 'ncrm_users', 1, '21', 'description', 'Documents', 1, 0, '', 250, 14, 79, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 501, 'address_street', 'ncrm_users', 1, '21', 'address_street', 'Street Address', 1, 0, '', 250, 1, 80, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 502, 'address_city', 'ncrm_users', 1, '1', 'address_city', 'City', 1, 0, '', 100, 3, 80, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 503, 'address_state', 'ncrm_users', 1, '1', 'address_state', 'State', 1, 0, '', 100, 5, 80, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 504, 'address_postalcode', 'ncrm_users', 1, '1', 'address_postalcode', 'Postal Code', 1, 0, '', 100, 4, 80, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 505, 'address_country', 'ncrm_users', 1, '1', 'address_country', 'Country', 1, 0, '', 100, 2, 80, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 506, 'accesskey', 'ncrm_users', 1, '3', 'accesskey', 'Webservice Access Key', 1, 0, '', 100, 2, 83, 2, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 507, 'time_zone', 'ncrm_users', 1, '16', 'time_zone', 'Time Zone', 1, 0, '', 200, 5, 118, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 508, 'currency_id', 'ncrm_users', 1, '117', 'currency_id', 'Currency', 1, 0, '', 100, 1, 78, 1, 'I~O', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 509, 'currency_grouping_pattern', 'ncrm_users', 1, '16', 'currency_grouping_pattern', 'Digit Grouping Pattern', 1, 0, '', 100, 2, 78, 1, 'V~O', 1, NULL, 'BAS', 1, '<b>Currency - Digit Grouping Pattern</b> <br/><br/>This pattern specifies the format in which the currency separator will be placed.', 0),
	(29, 510, 'currency_decimal_separator', 'ncrm_users', 1, '16', 'currency_decimal_separator', 'Decimal Separator', 1, 0, '', 2, 3, 78, 1, 'V~O', 1, NULL, 'BAS', 1, '<b>Currency - Decimal Separator</b> <br/><br/>Decimal separator specifies the separator to be used to separate the fractional values from the whole number part. <br/><b>Eg:</b> <br/>. => 123.45 <br/>, => 123,45 <br/>\' => 123\'45 <br/>  => 123 45 <br/>$ => 123$45 <br/>', 0),
	(29, 511, 'currency_grouping_separator', 'ncrm_users', 1, '16', 'currency_grouping_separator', 'Digit Grouping Separator', 1, 0, '', 2, 4, 78, 1, 'V~O', 1, NULL, 'BAS', 1, '<b>Currency - Grouping Separator</b> <br/><br/>Grouping separator specifies the separator to be used to group the whole number part into hundreds, thousands etc. <br/><b>Eg:</b> <br/>. => 123.456.789 <br/>, => 123,456,789 <br/>\' => 123\'456\'789 <br/>  => 123 456 789 <br/>$ => 123$456$789 <br/>', 0),
	(29, 512, 'currency_symbol_placement', 'ncrm_users', 1, '16', 'currency_symbol_placement', 'Symbol Placement', 1, 0, '', 20, 5, 78, 1, 'V~O', 1, NULL, 'BAS', 1, '<b>Currency - Symbol Placement</b> <br/><br/>Symbol Placement allows you to configure the position of the currency symbol with respect to the currency value.<br/><b>Eg:</b> <br/>$1.0 => $123,456,789.50 <br/>1.0$ => 123,456,789.50$ <br/>', 0),
	(29, 513, 'imagename', 'ncrm_users', 1, '105', 'imagename', 'User Image', 1, 0, '', 250, 10, 82, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 514, 'internal_mailer', 'ncrm_users', 1, '56', 'internal_mailer', 'INTERNAL_MAIL_COMPOSER', 1, 0, '', 50, 15, 79, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 515, 'theme', 'ncrm_users', 1, '31', 'theme', 'Theme', 1, 0, 'softed', 100, 16, 79, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 516, 'language', 'ncrm_users', 1, '32', 'language', 'Language', 1, 0, '', 100, 17, 79, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(29, 517, 'reminder_interval', 'ncrm_users', 1, '16', 'reminder_interval', 'Reminder Interval', 1, 0, '', 100, 11, 118, 1, 'V~O', 1, NULL, 'BAS', 1, NULL, 0),
	(10, 518, 'from_email', 'ncrm_emaildetails', 1, '12', 'from_email', 'From', 1, 2, '', 100, 1, 21, 3, 'V~M', 3, NULL, 'BAS', 0, NULL, 0),
	(10, 519, 'to_email', 'ncrm_emaildetails', 1, '8', 'saved_toid', 'To', 1, 2, '', 100, 2, 21, 1, 'V~M', 3, NULL, 'BAS', 0, NULL, 0),
	(10, 520, 'cc_email', 'ncrm_emaildetails', 1, '8', 'ccmail', 'CC', 1, 2, '', 1000, 3, 21, 1, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(10, 521, 'bcc_email', 'ncrm_emaildetails', 1, '8', 'bccmail', 'BCC', 1, 2, '', 1000, 4, 21, 1, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(10, 522, 'idlists', 'ncrm_emaildetails', 1, '357', 'parent_id', 'Parent ID', 1, 2, '', 1000, 5, 21, 1, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(10, 523, 'email_flag', 'ncrm_emaildetails', 1, '16', 'email_flag', 'Email Flag', 1, 2, '', 1000, 6, 21, 3, 'V~O', 3, NULL, 'BAS', 0, NULL, 0),
	(34, 524, 'direction', 'ncrm_pbxmanager', 1, '1', 'direction', 'Direction', 1, 2, '', 100, 1, 88, 1, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(34, 525, 'callstatus', 'ncrm_pbxmanager', 1, '1', 'callstatus', 'Call Status', 1, 2, '', 100, 2, 88, 1, 'V~O', 1, 0, 'BAS', 1, '', 1),
	(34, 526, 'starttime', 'ncrm_pbxmanager', 1, '70', 'starttime', 'Start Time', 1, 2, '', 100, 7, 88, 1, 'DT~O', 1, 0, 'BAS', 1, '', 1),
	(34, 527, 'endtime', 'ncrm_pbxmanager', 1, '70', 'endtime', 'End Time', 1, 2, '', 100, 8, 88, 1, 'DT~O', 1, 0, 'BAS', 1, '', 0),
	(34, 528, 'totalduration', 'ncrm_pbxmanager', 1, '7', 'totalduration', 'Total Duration', 1, 2, '', 100, 10, 88, 1, 'I~O', 1, 0, 'BAS', 1, '', 0),
	(34, 529, 'billduration', 'ncrm_pbxmanager', 1, '7', 'billduration', 'Bill Duration', 1, 2, '', 100, 11, 88, 1, 'I~O', 1, 0, 'BAS', 1, '', 0),
	(34, 530, 'recordingurl', 'ncrm_pbxmanager', 1, '17', 'recordingurl', 'Recording URL', 1, 2, '', 100, 9, 88, 1, 'V~O', 1, 0, 'BAS', 1, '', 1),
	(34, 531, 'sourceuuid', 'ncrm_pbxmanager', 1, '1', 'sourceuuid', 'Source UUID', 1, 2, '', 100, 12, 88, 1, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(34, 532, 'gateway', 'ncrm_pbxmanager', 1, '1', 'gateway', 'Gateway', 1, 2, '', 100, 13, 88, 1, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(34, 533, 'customer', 'ncrm_pbxmanager', 1, '10', 'customer', 'Customer', 1, 2, '', 100, 3, 88, 1, 'V~O', 1, 0, 'BAS', 1, '', 1),
	(34, 534, 'user', 'ncrm_pbxmanager', 1, '52', 'user', 'User', 1, 2, '', 100, 4, 88, 1, 'V~O', 1, 0, 'BAS', 1, '', 1),
	(34, 535, 'customernumber', 'ncrm_pbxmanager', 1, '11', 'customernumber', 'Customer Number', 1, 2, '', 100, 5, 88, 1, 'V~M', 1, 0, 'BAS', 1, '', 0),
	(34, 536, 'customertype', 'ncrm_pbxmanager', 1, '1', 'customertype', 'Customer Type', 1, 2, '', 100, 6, 88, 1, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(34, 537, 'smownerid', 'ncrm_crmentity', 1, '53', 'assigned_user_id', 'Assigned To', 1, 2, '', 100, 14, 88, 1, 'V~M', 1, 0, 'BAS', 1, '', 0),
	(34, 538, 'createdtime', 'ncrm_crmentity', 1, '70', 'CreatedTime', 'Created Time', 1, 2, '', 100, 15, 88, 2, 'DT~O', 1, 0, 'BAS', 1, '', 0),
	(34, 539, 'modifiedtime', 'ncrm_crmentity', 1, '70', 'ModifiedTime', 'Modified Time', 1, 2, '', 100, 16, 88, 2, 'DT~O', 1, 0, 'BAS', 1, '', 0),
	(29, 540, 'phone_crm_extension', 'ncrm_users', 1, '11', 'phone_crm_extension', 'CRM Phone Extension', 1, 2, '', 100, 18, 79, 1, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(35, 541, 'smownerid', 'ncrm_crmentity', 1, '53', 'assigned_user_id', 'Assigned To', 1, 2, '', 100, 4, 89, 1, 'V~M', 2, 2, 'BAS', 1, '', 1),
	(35, 542, 'createdtime', 'ncrm_crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, '', 100, 17, 89, 2, 'DT~O', 3, 0, 'BAS', 0, '', 0),
	(35, 543, 'modifiedtime', 'ncrm_crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, '', 100, 18, 89, 2, 'DT~O', 3, 0, 'BAS', 0, '', 0),
	(35, 544, 'start_date', 'ncrm_servicecontracts', 1, '5', 'start_date', 'Start Date', 1, 2, '', 100, 7, 89, 1, 'D~O', 2, 4, 'BAS', 1, '', 0),
	(35, 545, 'end_date', 'ncrm_servicecontracts', 1, '5', 'end_date', 'End Date', 1, 2, '', 100, 11, 89, 2, 'D~O', 3, 0, 'BAS', 0, '', 0),
	(35, 546, 'sc_related_to', 'ncrm_servicecontracts', 1, '10', 'sc_related_to', 'Related to', 1, 2, '', 100, 3, 89, 1, 'V~O', 2, 6, 'BAS', 1, '', 0),
	(35, 547, 'tracking_unit', 'ncrm_servicecontracts', 1, '15', 'tracking_unit', 'Tracking Unit', 1, 2, '', 100, 6, 89, 1, 'V~O', 2, 7, 'BAS', 1, '', 0),
	(35, 548, 'total_units', 'ncrm_servicecontracts', 1, '7', 'total_units', 'Total Units', 1, 2, '', 100, 8, 89, 1, 'V~O', 2, 8, 'BAS', 1, '', 1),
	(35, 549, 'used_units', 'ncrm_servicecontracts', 1, '7', 'used_units', 'Used Units', 1, 2, '', 100, 10, 89, 1, 'V~O', 2, 9, 'BAS', 1, '', 1),
	(35, 550, 'subject', 'ncrm_servicecontracts', 1, '1', 'subject', 'Subject', 1, 0, '', 100, 1, 89, 1, 'V~M', 0, 1, 'BAS', 1, '', 1),
	(35, 551, 'due_date', 'ncrm_servicecontracts', 1, '23', 'due_date', 'Due date', 1, 2, '', 100, 9, 89, 1, 'D~O', 2, 5, 'BAS', 1, '', 0),
	(35, 552, 'planned_duration', 'ncrm_servicecontracts', 1, '1', 'planned_duration', 'Planned Duration', 1, 2, '', 100, 13, 89, 2, 'V~O', 3, 0, 'BAS', 0, '', 0),
	(35, 553, 'actual_duration', 'ncrm_servicecontracts', 1, '1', 'actual_duration', 'Actual Duration', 1, 2, '', 100, 15, 89, 2, 'V~O', 3, 0, 'BAS', 0, '', 0),
	(35, 554, 'contract_status', 'ncrm_servicecontracts', 1, '15', 'contract_status', 'Status', 1, 2, '', 100, 12, 89, 1, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(35, 555, 'priority', 'ncrm_servicecontracts', 1, '15', 'contract_priority', 'Priority', 1, 2, '', 100, 14, 89, 1, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(35, 556, 'contract_type', 'ncrm_servicecontracts', 1, '15', 'contract_type', 'Type', 1, 2, '', 100, 5, 89, 1, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(35, 557, 'progress', 'ncrm_servicecontracts', 1, '9', 'progress', 'Progress', 1, 2, '', 100, 16, 89, 2, 'N~O~2~2', 3, 3, 'BAS', 0, '', 0),
	(35, 558, 'contract_no', 'ncrm_servicecontracts', 1, '4', 'contract_no', 'Contract No', 1, 0, '', 100, 2, 89, 1, 'V~O', 3, 0, 'BAS', 0, '', 1),
	(35, 559, 'modifiedby', 'ncrm_crmentity', 1, '52', 'modifiedby', 'Last Modified By', 1, 0, '', 100, 17, 89, 3, 'V~O', 3, 0, 'BAS', 0, '', 0),
	(36, 560, 'servicename', 'ncrm_service', 1, '2', 'servicename', 'Service Name', 1, 0, '', 100, 1, 91, 1, 'V~M', 0, 1, 'BAS', 1, '', 1),
	(36, 561, 'service_no', 'ncrm_service', 1, '4', 'service_no', 'Service No', 1, 0, '', 100, 2, 91, 1, 'V~O', 3, 0, 'BAS', 0, '', 1),
	(36, 562, 'discontinued', 'ncrm_service', 1, '56', 'discontinued', 'Service Active', 1, 2, '', 100, 4, 91, 1, 'V~O', 2, 3, 'BAS', 1, '', 0),
	(36, 563, 'sales_start_date', 'ncrm_service', 1, '5', 'sales_start_date', 'Sales Start Date', 1, 2, '', 100, 9, 91, 1, 'D~O', 1, 0, 'BAS', 1, '', 0),
	(36, 564, 'sales_end_date', 'ncrm_service', 1, '5', 'sales_end_date', 'Sales End Date', 1, 2, '', 100, 10, 91, 1, 'D~O~OTH~GE~sales_start_date~Sales Start Date', 1, 0, 'BAS', 1, '', 0),
	(36, 565, 'start_date', 'ncrm_service', 1, '5', 'start_date', 'Support Start Date', 1, 2, '', 100, 11, 91, 1, 'D~O', 1, 0, 'BAS', 1, '', 0),
	(36, 566, 'expiry_date', 'ncrm_service', 1, '5', 'expiry_date', 'Support Expiry Date', 1, 2, '', 100, 12, 91, 1, 'D~O~OTH~GE~start_date~Start Date', 1, 0, 'BAS', 1, '', 0),
	(36, 567, 'website', 'ncrm_service', 1, '17', 'website', 'Website', 1, 2, '', 100, 6, 91, 1, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(36, 568, 'createdtime', 'ncrm_crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, '', 100, 13, 91, 2, 'DT~O', 3, 0, 'BAS', 0, '', 0),
	(36, 569, 'modifiedtime', 'ncrm_crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, '', 100, 14, 91, 2, 'DT~O', 3, 0, 'BAS', 0, '', 0),
	(36, 570, 'modifiedby', 'ncrm_crmentity', 1, '52', 'modifiedby', 'Last Modified By', 1, 0, '', 100, 16, 91, 3, 'V~O', 3, 0, 'BAS', 0, '', 0),
	(36, 571, 'service_usageunit', 'ncrm_service', 1, '15', 'service_usageunit', 'Usage Unit', 1, 2, '', 100, 3, 91, 1, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(36, 572, 'qty_per_unit', 'ncrm_service', 1, '1', 'qty_per_unit', 'No of Units', 1, 2, '', 100, 5, 91, 1, 'N~O', 1, 0, 'BAS', 1, '', 1),
	(36, 573, 'smownerid', 'ncrm_crmentity', 1, '53', 'assigned_user_id', 'Owner', 1, 0, '', 100, 8, 91, 1, 'I~O', 1, 0, 'BAS', 1, '', 0),
	(36, 574, 'servicecategory', 'ncrm_service', 1, '15', 'servicecategory', 'Service Category', 1, 2, '', 100, 7, 91, 1, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(36, 575, 'unit_price', 'ncrm_service', 1, '72', 'unit_price', 'Price', 1, 0, '', 100, 1, 92, 1, 'N~O', 2, 2, 'BAS', 0, '', 1),
	(36, 576, 'taxclass', 'ncrm_service', 1, '83', 'taxclass', 'Tax Class', 1, 2, '', 100, 4, 92, 1, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(36, 577, 'commissionrate', 'ncrm_service', 1, '9', 'commissionrate', 'Commission Rate', 1, 2, '', 100, 2, 92, 1, 'N~O', 1, 0, 'BAS', 1, '', 1),
	(36, 578, 'description', 'ncrm_crmentity', 1, '19', 'description', 'Description', 1, 2, '', 100, 1, 94, 1, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(38, 579, 'asset_no', 'ncrm_assets', 1, '4', 'asset_no', 'Asset No', 1, 0, '', 100, 2, 95, 1, 'V~O', 3, 0, 'BAS', 0, '\n                    ', 1),
	(38, 580, 'product', 'ncrm_assets', 1, '10', 'product', 'Product Name', 1, 2, '', 100, 3, 95, 1, 'V~M', 0, 3, 'BAS', 1, '\n                    ', 1),
	(38, 581, 'serialnumber', 'ncrm_assets', 1, '2', 'serialnumber', 'Serial Number', 1, 2, '', 100, 4, 95, 1, 'V~M', 0, 5, 'BAS', 1, '\n                    ', 0),
	(38, 582, 'datesold', 'ncrm_assets', 1, '5', 'datesold', 'Date Sold', 1, 2, '', 100, 5, 95, 1, 'D~M~OTH~GE~datesold~Date Sold', 0, 0, 'BAS', 1, '\n                    ', 0),
	(38, 583, 'dateinservice', 'ncrm_assets', 1, '5', 'dateinservice', 'Date in Service', 1, 2, '', 100, 6, 95, 1, 'D~M~OTH~GE~dateinservice~Date in Service', 0, 4, 'BAS', 1, '\n                    ', 0),
	(38, 584, 'assetstatus', 'ncrm_assets', 1, '15', 'assetstatus', 'Status', 1, 2, '', 100, 7, 95, 1, 'V~M', 0, 0, 'BAS', 1, '\n                    ', 0),
	(38, 585, 'tagnumber', 'ncrm_assets', 1, '2', 'tagnumber', 'Tag Number', 1, 2, '', 100, 8, 95, 1, 'V~O', 1, 0, 'BAS', 1, '\n                    ', 0),
	(38, 586, 'invoiceid', 'ncrm_assets', 1, '10', 'invoiceid', 'Invoice Name', 1, 2, '', 100, 9, 95, 1, 'V~O', 1, 0, 'BAS', 1, '\n                    ', 0),
	(38, 587, 'shippingmethod', 'ncrm_assets', 1, '2', 'shippingmethod', 'Shipping Method', 1, 2, '', 100, 10, 95, 1, 'V~O', 1, 0, 'BAS', 1, '\n                    ', 0),
	(38, 588, 'shippingtrackingnumber', 'ncrm_assets', 1, '2', 'shippingtrackingnumber', 'Shipping Tracking Number', 1, 2, '', 100, 11, 95, 1, 'V~O', 1, 0, 'BAS', 1, '\n                    ', 0),
	(38, 589, 'smownerid', 'ncrm_crmentity', 1, '53', 'assigned_user_id', 'Assigned To', 1, 2, '', 100, 4, 95, 1, 'V~M', 0, 2, 'BAS', 1, '\n                    ', 0),
	(38, 590, 'assetname', 'ncrm_assets', 1, '1', 'assetname', 'Asset Name', 1, 0, '', 100, 12, 95, 1, 'V~M', 0, 6, 'BAS', 1, '\n                    ', 1),
	(38, 591, 'account', 'ncrm_assets', 1, '10', 'account', 'Customer Name', 1, 2, '', 100, 13, 95, 1, 'V~M', 0, 0, 'BAS', 1, '\n                    ', 1),
	(38, 592, 'contact', 'ncrm_assets', 1, '10', 'contact', 'Contact Name', 1, 2, '', 100, 14, 95, 1, 'V~O', 0, 0, 'BAS', 1, '\n                    ', 0),
	(38, 593, 'createdtime', 'ncrm_crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, '', 100, 14, 95, 2, 'DT~O', 3, 0, 'BAS', 0, '\n                    ', 0),
	(38, 594, 'modifiedtime', 'ncrm_crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, '', 100, 15, 95, 2, 'DT~O', 3, 0, 'BAS', 0, '\n                    ', 0),
	(38, 595, 'modifiedby', 'ncrm_crmentity', 1, '52', 'modifiedby', 'Last Modified By', 1, 0, '', 100, 16, 95, 3, 'V~O', 3, 0, 'BAS', 0, '\n                    ', 0),
	(38, 596, 'description', 'ncrm_crmentity', 1, '19', 'description', 'Notes', 1, 2, '', 100, 1, 97, 1, 'V~O', 1, 0, 'BAS', 1, '\n                    ', 0),
	(42, 597, 'commentcontent', 'ncrm_modcomments', 1, '19', 'commentcontent', 'Comment', 1, 0, '', 100, 4, 98, 1, 'V~M', 0, 4, 'BAS', 2, '', 1),
	(42, 598, 'smownerid', 'ncrm_crmentity', 1, '53', 'assigned_user_id', 'Assigned To', 1, 0, '', 100, 1, 99, 1, 'V~M', 0, 1, 'BAS', 2, '', 1),
	(42, 599, 'createdtime', 'ncrm_crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, '', 100, 5, 99, 2, 'DT~O', 0, 2, 'BAS', 0, '', 0),
	(42, 600, 'modifiedtime', 'ncrm_crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, '', 100, 6, 99, 2, 'DT~O', 0, 3, 'BAS', 0, '', 0),
	(42, 601, 'related_to', 'ncrm_modcomments', 1, '10', 'related_to', 'Related To', 1, 2, '', 100, 2, 99, 1, 'V~M', 2, 5, 'BAS', 2, '', 0),
	(42, 602, 'smcreatorid', 'ncrm_crmentity', 1, '52', 'creator', 'Creator', 1, 2, '', 100, 4, 99, 2, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(42, 603, 'parent_comments', 'ncrm_modcomments', 1, '10', 'parent_comments', 'Related To Comments', 1, 2, '', 100, 7, 99, 1, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(43, 604, 'projectmilestonename', 'ncrm_projectmilestone', 1, '2', 'projectmilestonename', 'Project Milestone Name', 1, 2, '', 100, 1, 101, 1, 'V~M', 0, 1, 'BAS', 1, '', 1),
	(43, 605, 'projectmilestonedate', 'ncrm_projectmilestone', 1, '5', 'projectmilestonedate', 'Milestone Date', 1, 2, '', 100, 5, 101, 1, 'D~O', 0, 3, 'BAS', 1, '', 1),
	(43, 606, 'projectid', 'ncrm_projectmilestone', 1, '10', 'projectid', 'Related to', 1, 0, '', 100, 4, 101, 1, 'V~M', 0, 4, 'BAS', 1, '', 0),
	(43, 607, 'projectmilestonetype', 'ncrm_projectmilestone', 1, '15', 'projectmilestonetype', 'Type', 1, 2, '', 100, 7, 101, 1, 'V~O', 1, 0, 'BAS', 1, '', 1),
	(43, 608, 'smownerid', 'ncrm_crmentity', 1, '53', 'assigned_user_id', 'Assigned To', 1, 2, '', 100, 6, 101, 1, 'V~M', 0, 2, 'BAS', 1, '', 0),
	(43, 609, 'projectmilestone_no', 'ncrm_projectmilestone', 2, '4', 'projectmilestone_no', 'Project Milestone No', 1, 0, '', 100, 2, 101, 1, 'V~O', 3, 4, 'BAS', 0, '', 0),
	(43, 610, 'createdtime', 'ncrm_crmentity', 1, '70', 'createdtime', 'Created Time', 1, 2, '', 100, 1, 102, 2, 'T~O', 1, 0, 'BAS', 1, '', 0),
	(43, 611, 'modifiedtime', 'ncrm_crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 2, '', 100, 2, 102, 2, 'T~O', 1, 0, 'BAS', 1, '', 0),
	(43, 612, 'modifiedby', 'ncrm_crmentity', 1, '52', 'modifiedby', 'Last Modified By', 1, 0, '', 100, 3, 102, 3, 'V~O', 3, 0, 'BAS', 0, '', 0),
	(43, 613, 'description', 'ncrm_crmentity', 1, '19', 'description', 'description', 1, 2, '', 100, 1, 103, 1, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(44, 614, 'projecttaskname', 'ncrm_projecttask', 1, '2', 'projecttaskname', 'Project Task Name', 1, 2, '', 100, 1, 104, 1, 'V~M', 0, 1, 'BAS', 1, '', 1),
	(44, 615, 'projecttasktype', 'ncrm_projecttask', 1, '15', 'projecttasktype', 'Type', 1, 2, '', 100, 4, 104, 1, 'V~O', 1, 0, 'BAS', 1, '', 1),
	(44, 616, 'projecttaskpriority', 'ncrm_projecttask', 1, '15', 'projecttaskpriority', 'Priority', 1, 2, '', 100, 3, 104, 1, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(44, 617, 'projectid', 'ncrm_projecttask', 1, '10', 'projectid', 'Related to', 1, 0, '', 100, 6, 104, 1, 'V~M', 0, 5, 'BAS', 1, '', 0),
	(44, 618, 'smownerid', 'ncrm_crmentity', 1, '53', 'assigned_user_id', 'Assigned To', 1, 2, '', 100, 7, 104, 1, 'V~M', 0, 2, 'BAS', 1, '', 1),
	(44, 619, 'projecttasknumber', 'ncrm_projecttask', 1, '7', 'projecttasknumber', 'Project Task Number', 1, 2, '', 100, 5, 104, 1, 'I~O', 1, 0, 'BAS', 1, '', 0),
	(44, 620, 'projecttask_no', 'ncrm_projecttask', 2, '4', 'projecttask_no', 'Project Task No', 1, 0, '', 100, 2, 104, 1, 'V~O', 3, 4, 'BAS', 0, '', 0),
	(44, 621, 'projecttaskprogress', 'ncrm_projecttask', 1, '15', 'projecttaskprogress', 'Progress', 1, 2, '', 100, 1, 105, 1, 'V~O', 1, 0, 'BAS', 1, '', 1),
	(44, 622, 'projecttaskhours', 'ncrm_projecttask', 1, '7', 'projecttaskhours', 'Worked Hours', 1, 2, '', 100, 2, 105, 1, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(44, 623, 'startdate', 'ncrm_projecttask', 1, '5', 'startdate', 'Start Date', 1, 2, '', 100, 3, 105, 1, 'D~O', 0, 3, 'BAS', 1, '', 1),
	(44, 624, 'enddate', 'ncrm_projecttask', 1, '5', 'enddate', 'End Date', 1, 2, '', 100, 4, 105, 1, 'D~O~OTH~GE~startdate~Start Date', 1, 0, 'BAS', 1, '', 1),
	(44, 625, 'createdtime', 'ncrm_crmentity', 1, '70', 'createdtime', 'Created Time', 1, 2, '', 100, 5, 105, 2, 'T~O', 1, 0, 'BAS', 1, '', 0),
	(44, 626, 'modifiedtime', 'ncrm_crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 2, '', 100, 6, 105, 2, 'T~O', 1, 0, 'BAS', 1, '', 0),
	(44, 627, 'modifiedby', 'ncrm_crmentity', 1, '52', 'modifiedby', 'Last Modified By', 1, 0, '', 100, 7, 105, 3, 'V~O', 3, 0, 'BAS', 0, '', 0),
	(44, 628, 'description', 'ncrm_crmentity', 1, '19', 'description', 'description', 1, 2, '', 100, 1, 106, 1, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(45, 629, 'projectname', 'ncrm_project', 1, '2', 'projectname', 'Project Name', 1, 2, '', 100, 1, 107, 1, 'V~M', 0, 1, 'BAS', 1, '', 1),
	(45, 630, 'startdate', 'ncrm_project', 1, '23', 'startdate', 'Start Date', 1, 2, '', 100, 3, 107, 1, 'D~O', 0, 3, 'BAS', 1, '', 1),
	(45, 631, 'targetenddate', 'ncrm_project', 1, '23', 'targetenddate', 'Target End Date', 1, 2, '', 100, 5, 107, 1, 'D~O~OTH~GE~startdate~Start Date', 0, 4, 'BAS', 1, '', 1),
	(45, 632, 'actualenddate', 'ncrm_project', 1, '23', 'actualenddate', 'Actual End Date', 1, 2, '', 100, 6, 107, 1, 'D~O~OTH~GE~startdate~Start Date', 1, 0, 'BAS', 1, '', 0),
	(45, 633, 'projectstatus', 'ncrm_project', 1, '15', 'projectstatus', 'Status', 1, 2, '', 100, 7, 107, 1, 'V~O', 1, 0, 'BAS', 1, '', 1),
	(45, 634, 'projecttype', 'ncrm_project', 1, '15', 'projecttype', 'Type', 1, 2, '', 100, 8, 107, 1, 'V~O', 1, 0, 'BAS', 1, '', 1),
	(45, 635, 'linktoaccountscontacts', 'ncrm_project', 1, '10', 'linktoaccountscontacts', 'Related to', 1, 2, '', 100, 9, 107, 1, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(45, 636, 'smownerid', 'ncrm_crmentity', 1, '53', 'assigned_user_id', 'Assigned To', 1, 2, '', 100, 4, 107, 1, 'V~M', 0, 2, 'BAS', 1, '', 1),
	(45, 637, 'project_no', 'ncrm_project', 2, '4', 'project_no', 'Project No', 1, 0, '', 100, 2, 107, 1, 'V~O', 3, 0, 'BAS', 0, '', 0),
	(45, 638, 'targetbudget', 'ncrm_project', 1, '7', 'targetbudget', 'Target Budget', 1, 2, '', 100, 1, 108, 1, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(45, 639, 'projecturl', 'ncrm_project', 1, '17', 'projecturl', 'Project Url', 1, 2, '', 100, 2, 108, 1, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(45, 640, 'projectpriority', 'ncrm_project', 1, '15', 'projectpriority', 'Priority', 1, 2, '', 100, 3, 108, 1, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(45, 641, 'progress', 'ncrm_project', 1, '15', 'progress', 'Progress', 1, 2, '', 100, 4, 108, 1, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(45, 642, 'createdtime', 'ncrm_crmentity', 1, '70', 'createdtime', 'Created Time', 1, 2, '', 100, 5, 108, 2, 'T~O', 1, 0, 'BAS', 1, '', 0),
	(45, 643, 'modifiedtime', 'ncrm_crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 2, '', 100, 6, 108, 2, 'T~O', 1, 0, 'BAS', 1, '', 0),
	(45, 644, 'modifiedby', 'ncrm_crmentity', 1, '52', 'modifiedby', 'Last Modified By', 1, 0, '', 100, 7, 108, 3, 'V~O', 3, 0, 'BAS', 0, '', 0),
	(45, 645, 'description', 'ncrm_crmentity', 1, '19', 'description', 'description', 1, 2, '', 100, 1, 109, 1, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(47, 646, 'smownerid', 'ncrm_crmentity', 1, '53', 'assigned_user_id', 'Assigned To', 1, 0, '', 100, 2, 110, 1, 'V~M', 1, 0, 'BAS', 1, '', 1),
	(47, 647, 'createdtime', 'ncrm_crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, '', 100, 5, 110, 2, 'DT~O', 1, 0, 'BAS', 0, '', 0),
	(47, 648, 'modifiedtime', 'ncrm_crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, '', 100, 6, 110, 2, 'DT~O', 1, 0, 'BAS', 0, '', 0),
	(47, 649, 'message', 'ncrm_smsnotifier', 1, '21', 'message', 'message', 1, 0, '', 100, 1, 110, 1, 'V~M', 1, 0, 'BAS', 1, '', 1),
	(47, 650, 'modifiedby', 'ncrm_crmentity', 1, '52', 'modifiedby', 'Last Modified By', 1, 0, '', 100, 7, 110, 3, 'V~O', 1, 0, 'BAS', 0, '', 0),
	(2, 651, 'forecast_amount', 'ncrm_potential', 1, '71', 'forecast_amount', 'Forecast Amount', 1, 2, '', 100, 18, 1, 1, 'N~O', 1, 0, 'BAS', 0, '', 0),
	(29, 652, 'no_of_currency_decimals', 'ncrm_users', 1, '16', 'no_of_currency_decimals', 'Number Of Currency Decimals', 1, 2, '2', 100, 6, 78, 1, 'V~O', 1, 0, 'BAS', 1, '<b>Currency - Number of Decimal places</b> <br/><br/>Number of decimal places specifies how many number of decimals will be shown after decimal separator.<br/><b>Eg:</b> 123.00', 0),
	(23, 653, 'productid', 'ncrm_inventoryproductrel', 1, '10', 'productid', 'Item Name', 0, 2, '', 100, 1, 113, 5, 'V~M', 1, 0, 'BAS', 0, '', 0),
	(23, 654, 'quantity', 'ncrm_inventoryproductrel', 1, '7', 'quantity', 'Quantity', 0, 2, '', 100, 2, 113, 5, 'N~O', 1, 0, 'BAS', 0, '', 0),
	(23, 655, 'listprice', 'ncrm_inventoryproductrel', 1, '71', 'listprice', 'List Price', 0, 2, '', 100, 3, 113, 5, 'N~O', 1, 0, 'BAS', 0, '', 0),
	(23, 656, 'comment', 'ncrm_inventoryproductrel', 1, '19', 'comment', 'Item Comment', 0, 2, '', 100, 4, 113, 5, 'V~O', 1, 0, 'BAS', 0, '', 0),
	(23, 657, 'discount_amount', 'ncrm_inventoryproductrel', 1, '71', 'discount_amount', 'Discount', 0, 2, '', 100, 5, 113, 5, 'N~O', 1, 0, 'BAS', 0, '', 0),
	(23, 658, 'discount_percent', 'ncrm_inventoryproductrel', 1, '7', 'discount_percent', 'Item Discount Percent', 0, 2, '', 100, 6, 113, 5, 'V~O', 1, 0, 'BAS', 0, '', 0),
	(23, 659, 'tax1', 'ncrm_inventoryproductrel', 1, '83', 'tax1', 'Tax1', 0, 2, '', 100, 7, 113, 5, 'V~O', 1, 0, 'BAS', 0, '', 0),
	(23, 660, 'tax2', 'ncrm_inventoryproductrel', 1, '83', 'tax2', 'Tax2', 0, 2, '', 100, 8, 113, 5, 'V~O', 1, 0, 'BAS', 0, '', 0),
	(23, 661, 'tax3', 'ncrm_inventoryproductrel', 1, '83', 'tax3', 'Tax3', 0, 2, '', 100, 9, 113, 5, 'V~O', 1, 0, 'BAS', 0, '', 0),
	(22, 662, 'productid', 'ncrm_inventoryproductrel', 1, '10', 'productid', 'Item Name', 0, 2, '', 100, 1, 114, 5, 'V~M', 1, 0, 'BAS', 0, '', 0),
	(22, 663, 'quantity', 'ncrm_inventoryproductrel', 1, '7', 'quantity', 'Quantity', 0, 2, '', 100, 2, 114, 5, 'N~O', 1, 0, 'BAS', 0, '', 0),
	(22, 664, 'listprice', 'ncrm_inventoryproductrel', 1, '71', 'listprice', 'List Price', 0, 2, '', 100, 3, 114, 5, 'N~O', 1, 0, 'BAS', 0, '', 0),
	(22, 665, 'comment', 'ncrm_inventoryproductrel', 1, '19', 'comment', 'Item Comment', 0, 2, '', 100, 4, 114, 5, 'V~O', 1, 0, 'BAS', 0, '', 0),
	(22, 666, 'discount_amount', 'ncrm_inventoryproductrel', 1, '71', 'discount_amount', 'Discount', 0, 2, '', 100, 5, 114, 5, 'N~O', 1, 0, 'BAS', 0, '', 0),
	(22, 667, 'discount_percent', 'ncrm_inventoryproductrel', 1, '7', 'discount_percent', 'Item Discount Percent', 0, 2, '', 100, 6, 114, 5, 'V~O', 1, 0, 'BAS', 0, '', 0),
	(22, 668, 'tax1', 'ncrm_inventoryproductrel', 1, '83', 'tax1', 'Tax1', 0, 2, '', 100, 7, 114, 5, 'V~O', 1, 0, 'BAS', 0, '', 0),
	(22, 669, 'tax2', 'ncrm_inventoryproductrel', 1, '83', 'tax2', 'Tax2', 0, 2, '', 100, 8, 114, 5, 'V~O', 1, 0, 'BAS', 0, '', 0),
	(22, 670, 'tax3', 'ncrm_inventoryproductrel', 1, '83', 'tax3', 'Tax3', 0, 2, '', 100, 9, 114, 5, 'V~O', 1, 0, 'BAS', 0, '', 0),
	(21, 671, 'productid', 'ncrm_inventoryproductrel', 1, '10', 'productid', 'Item Name', 0, 2, '', 100, 1, 115, 5, 'V~M', 1, 0, 'BAS', 0, '', 0),
	(21, 672, 'quantity', 'ncrm_inventoryproductrel', 1, '7', 'quantity', 'Quantity', 0, 2, '', 100, 2, 115, 5, 'N~O', 1, 0, 'BAS', 0, '', 0),
	(21, 673, 'listprice', 'ncrm_inventoryproductrel', 1, '71', 'listprice', 'List Price', 0, 2, '', 100, 3, 115, 5, 'N~O', 1, 0, 'BAS', 0, '', 0),
	(21, 674, 'comment', 'ncrm_inventoryproductrel', 1, '19', 'comment', 'Item Comment', 0, 2, '', 100, 4, 115, 5, 'V~O', 1, 0, 'BAS', 0, '', 0),
	(21, 675, 'discount_amount', 'ncrm_inventoryproductrel', 1, '71', 'discount_amount', 'Discount', 0, 2, '', 100, 5, 115, 5, 'N~O', 1, 0, 'BAS', 0, '', 0),
	(21, 676, 'discount_percent', 'ncrm_inventoryproductrel', 1, '7', 'discount_percent', 'Item Discount Percent', 0, 2, '', 100, 6, 115, 5, 'V~O', 1, 0, 'BAS', 0, '', 0),
	(21, 677, 'tax1', 'ncrm_inventoryproductrel', 1, '83', 'tax1', 'Tax1', 0, 2, '', 100, 7, 115, 5, 'V~O', 1, 0, 'BAS', 0, '', 0),
	(21, 678, 'tax2', 'ncrm_inventoryproductrel', 1, '83', 'tax2', 'Tax2', 0, 2, '', 100, 8, 115, 5, 'V~O', 1, 0, 'BAS', 0, '', 0),
	(21, 679, 'tax3', 'ncrm_inventoryproductrel', 1, '83', 'tax3', 'Tax3', 0, 2, '', 100, 9, 115, 5, 'V~O', 1, 0, 'BAS', 0, '', 0),
	(20, 680, 'productid', 'ncrm_inventoryproductrel', 1, '10', 'productid', 'Item Name', 0, 2, '', 100, 1, 116, 5, 'V~M', 1, 0, 'BAS', 0, '', 0),
	(20, 681, 'quantity', 'ncrm_inventoryproductrel', 1, '7', 'quantity', 'Quantity', 0, 2, '', 100, 2, 116, 5, 'N~O', 1, 0, 'BAS', 0, '', 0),
	(20, 682, 'listprice', 'ncrm_inventoryproductrel', 1, '71', 'listprice', 'List Price', 0, 2, '', 100, 3, 116, 5, 'N~O', 1, 0, 'BAS', 0, '', 0),
	(20, 683, 'comment', 'ncrm_inventoryproductrel', 1, '19', 'comment', 'Item Comment', 0, 2, '', 100, 4, 116, 5, 'V~O', 1, 0, 'BAS', 0, '', 0),
	(20, 684, 'discount_amount', 'ncrm_inventoryproductrel', 1, '71', 'discount_amount', 'Discount', 0, 2, '', 100, 5, 116, 5, 'N~O', 1, 0, 'BAS', 0, '', 0),
	(20, 685, 'discount_percent', 'ncrm_inventoryproductrel', 1, '7', 'discount_percent', 'Item Discount Percent', 0, 2, '', 100, 6, 116, 5, 'V~O', 1, 0, 'BAS', 0, '', 0),
	(20, 686, 'tax1', 'ncrm_inventoryproductrel', 1, '83', 'tax1', 'Tax1', 0, 2, '', 100, 7, 116, 5, 'V~O', 1, 0, 'BAS', 0, '', 0),
	(20, 687, 'tax2', 'ncrm_inventoryproductrel', 1, '83', 'tax2', 'Tax2', 0, 2, '', 100, 8, 116, 5, 'V~O', 1, 0, 'BAS', 0, '', 0),
	(20, 688, 'tax3', 'ncrm_inventoryproductrel', 1, '83', 'tax3', 'Tax3', 0, 2, '', 100, 9, 116, 5, 'V~O', 1, 0, 'BAS', 0, '', 0),
	(29, 689, 'truncate_trailing_zeros', 'ncrm_users', 1, '56', 'truncate_trailing_zeros', 'Truncate Trailing Zeros', 1, 2, '0', 100, 7, 78, 1, 'V~O', 1, 0, 'BAS', 1, '<b> Truncate Trailing Zeros </b> <br/><br/>It truncated trailing 0s in any of Currency, Decimal and Percentage Field types<br/><br/><b>Ex:</b><br/>If value is 89.00000 then <br/>decimal and Percentage fields were shows 89<br/>currency field type - shows 89.00<br/>', 0),
	(44, 690, 'projecttaskstatus', 'ncrm_projecttask', 1, '15', 'projecttaskstatus', 'Status', 1, 2, '', 100, 8, 104, 1, 'V~O', 0, 6, 'BAS', 1, '', 0),
	(42, 691, 'customer', 'ncrm_modcomments', 1, '10', 'customer', 'Customer', 1, 2, '', 100, 5, 98, 3, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(29, 692, 'dayoftheweek', 'ncrm_users', 1, '16', 'dayoftheweek', 'Starting Day of the week', 1, 2, 'Monday', 100, 1, 118, 1, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(29, 693, 'callduration', 'ncrm_users', 1, '16', 'callduration', 'Default Call Duration', 1, 2, '5', 100, 7, 118, 1, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(29, 694, 'othereventduration', 'ncrm_users', 1, '16', 'othereventduration', 'Other Event Duration', 1, 2, '5', 100, 8, 118, 1, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(23, 695, 'pre_tax_total', 'ncrm_invoice', 1, '72', 'pre_tax_total', 'Pre Tax Total', 1, 2, '', 100, 23, 67, 3, 'N~O', 1, 0, 'BAS', 1, '', 0),
	(22, 696, 'pre_tax_total', 'ncrm_salesorder', 1, '72', 'pre_tax_total', 'Pre Tax Total', 1, 2, '', 100, 23, 61, 3, 'N~O', 1, 0, 'BAS', 1, '', 0),
	(21, 697, 'pre_tax_total', 'ncrm_purchaseorder', 1, '72', 'pre_tax_total', 'Pre Tax Total', 1, 2, '', 100, 23, 55, 3, 'N~O', 1, 0, 'BAS', 1, '', 0),
	(20, 698, 'pre_tax_total', 'ncrm_quotes', 1, '72', 'pre_tax_total', 'Pre Tax Total', 1, 2, '', 100, 23, 49, 3, 'N~O', 1, 0, 'BAS', 1, '', 0),
	(29, 699, 'calendarsharedtype', 'ncrm_users', 1, '16', 'calendarsharedtype', 'Calendar Shared Type', 1, 2, 'Public', 100, 12, 118, 3, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(6, 700, 'isconvertedfromlead', 'ncrm_account', 1, '56', 'isconvertedfromlead', 'Is Converted From Lead', 1, 2, 'no', 100, 24, 9, 2, 'C~O', 1, 0, 'BAS', 1, '', 0),
	(4, 701, 'isconvertedfromlead', 'ncrm_contactdetails', 1, '56', 'isconvertedfromlead', 'Is Converted From Lead', 1, 2, 'no', 100, 15, 4, 3, 'C~O', 1, 0, 'BAS', 1, '', 0),
	(2, 702, 'isconvertedfromlead', 'ncrm_potential', 1, '56', 'isconvertedfromlead', 'Is Converted From Lead', 1, 2, 'no', 100, 19, 1, 2, 'C~O', 1, 0, 'BAS', 1, '', 0),
	(29, 703, 'default_record_view', 'ncrm_users', 1, '16', 'default_record_view', 'Default Record View', 1, 2, 'Summary', 100, 19, 79, 1, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(23, 704, 'received', 'ncrm_invoice', 1, '72', 'received', 'Received', 1, 2, '0', 100, 24, 67, 3, 'N~O', 1, 0, 'BAS', 1, '', 0),
	(23, 705, 'balance', 'ncrm_invoice', 1, '72', 'balance', 'Balance', 1, 2, '0', 100, 25, 67, 3, 'N~O', 1, 0, 'BAS', 1, '', 0),
	(21, 706, 'paid', 'ncrm_purchaseorder', 1, '72', 'paid', 'Paid', 1, 2, '0', 100, 24, 55, 3, 'N~O', 1, 0, 'BAS', 1, '', 0),
	(21, 707, 'balance', 'ncrm_purchaseorder', 1, '72', 'balance', 'Balance', 1, 2, '0', 100, 25, 55, 3, 'N~O', 1, 0, 'BAS', 1, '', 0),
	(18, 708, 'smownerid', 'ncrm_crmentity', 1, '53', 'assigned_user_id', 'Assigned To', 1, 2, '', 100, 13, 42, 1, 'V~M', 1, 0, 'BAS', 1, '', 0),
	(7, 709, 'emailoptout', 'ncrm_leaddetails', 1, '56', 'emailoptout', 'Email Opt Out', 1, 2, '', 100, 24, 13, 1, 'C~O', 1, 0, 'BAS', 1, '', 0),
	(42, 710, 'userid', 'ncrm_modcomments', 1, '10', 'userid', 'UserId', 1, 2, '', 100, 6, 98, 3, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(42, 711, 'reasontoedit', 'ncrm_modcomments', 1, '19', 'reasontoedit', 'ReasonToEdit', 1, 2, '', 100, 7, 98, 1, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(23, 712, 's_h_percent', 'ncrm_invoice', 1, '1', 'hdnS_H_Percent', 'S&H Percent', 0, 2, '', 100, 10, 113, 5, 'N~O', 0, 1, 'BAS', 0, '', 0),
	(20, 713, 's_h_percent', 'ncrm_quotes', 1, '1', 'hdnS_H_Percent', 'S&H Percent', 0, 2, '', 100, 10, 116, 5, 'N~O', 0, 1, 'BAS', 0, '', 0),
	(21, 714, 's_h_percent', 'ncrm_purchaseorder', 1, '1', 'hdnS_H_Percent', 'S&H Percent', 0, 2, '', 100, 10, 115, 5, 'N~O', 0, 1, 'BAS', 0, '', 0),
	(22, 715, 's_h_percent', 'ncrm_salesorder', 1, '1', 'hdnS_H_Percent', 'S&H Percent', 0, 2, '', 100, 10, 114, 5, 'N~O', 0, 1, 'BAS', 0, '', 0),
	(29, 716, 'leftpanelhide', 'ncrm_users', 1, '56', 'leftpanelhide', 'Left Panel Hide', 1, 2, '0', 100, 20, 79, 1, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(2, 717, 'contact_id', 'ncrm_potential', 1, '10', 'contact_id', 'Contact Name', 1, 2, '', 100, 4, 1, 1, 'V~O', 1, 0, 'BAS', 1, '', 1),
	(13, 718, 'contact_id', 'ncrm_troubletickets', 1, '10', 'contact_id', 'Contact Name', 1, 2, '', 100, 3, 25, 1, 'V~O', 1, 0, 'BAS', 1, '', 1),
	(29, 719, 'rowheight', 'ncrm_users', 1, '16', 'rowheight', 'Row Height', 1, 2, 'medium', 100, 21, 79, 1, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(13, 720, 'from_portal', 'ncrm_ticketcf', 1, '56', 'from_portal', 'From Portal', 1, 0, '', 100, 18, 25, 3, 'C~O', 1, 0, 'BAS', 1, '', 0),
	(29, 721, 'defaulteventstatus', 'ncrm_users', 1, '15', 'defaulteventstatus', 'Default Event Status', 1, 2, '', 100, 9, 118, 1, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(29, 722, 'defaultactivitytype', 'ncrm_users', 1, '15', 'defaultactivitytype', 'Default Activity Type', 1, 2, '', 100, 10, 118, 1, 'V~O', 1, 0, 'BAS', 1, '', 0),
	(29, 723, 'hidecompletedevents', 'ncrm_users', 1, '56', 'hidecompletedevents', 'LBL_HIDE_COMPLETED_EVENTS', 1, 2, '0', 100, 13, 118, 1, 'C~O', 1, 0, 'BAS', 1, '', 0),
	(9, 729, 'smcreatorid', 'ncrm_crmentity', 1, '52', 'created_user_id', 'Created By', 1, 2, '', 100, 23, 19, 2, 'V~O', 3, 5, 'BAS', 0, '', 0),
	(29, 750, 'is_owner', 'ncrm_users', 1, '1', 'is_owner', 'Account Owner', 0, 2, '0', 100, 12, 77, 5, 'V~O', 0, 1, 'BAS', 0, '', 0),
	(4, 752, 'cf_751', 'ncrm_contactscf', 2, '1', 'cf_751', 'Công ty', 1, 2, '', 100, 3, 4, 1, 'V~O~LE~120', 2, 0, 'BAS', 1, '', 1),
	(4, 754, 'cf_753', 'ncrm_contactscf', 2, '1', 'cf_753', 'Website/Fanpage', 1, 2, '', 100, 10, 4, 1, 'V~O~LE~250', 2, 0, 'BAS', 1, '', 0),
	(4, 756, 'cf_755', 'ncrm_contactscf', 2, '1', 'cf_755', 'UTM Source', 1, 2, '', 100, 1, 121, 1, 'V~O~LE~120', 1, 0, 'BAS', 1, '', 0),
	(4, 758, 'cf_757', 'ncrm_contactscf', 2, '1', 'cf_757', 'UTC Team', 1, 2, '', 100, 2, 121, 1, 'V~O~LE~120', 1, 0, 'BAS', 1, '', 0),
	(4, 760, 'cf_759', 'ncrm_contactscf', 2, '1', 'cf_759', 'UTM Agent', 1, 2, '', 100, 3, 121, 1, 'V~O~LE~120', 1, 0, 'BAS', 1, '', 0),
	(4, 762, 'cf_761', 'ncrm_contactscf', 2, '1', 'cf_761', 'UTM Term', 1, 2, '', 100, 5, 121, 1, 'V~O~LE~120', 1, 0, 'BAS', 1, '', 0),
	(4, 764, 'cf_763', 'ncrm_contactscf', 2, '1', 'cf_763', 'UTM Medium', 1, 2, '', 100, 4, 121, 1, 'V~O~LE~120', 1, 0, 'BAS', 1, '', 0),
	(4, 766, 'cf_765', 'ncrm_contactscf', 2, '1', 'cf_765', 'UTM Content', 1, 2, '', 100, 6, 121, 1, 'V~O~LE~120', 1, 0, 'BAS', 1, '', 0),
	(4, 770, 'cf_769', 'ncrm_contactscf', 2, '17', 'cf_769', 'Link', 1, 2, '', 100, 7, 121, 1, 'V~O', 1, 0, 'BAS', 1, '', 0);
/*!40000 ALTER TABLE `ncrm_field` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_fieldmodulerel
CREATE TABLE IF NOT EXISTS `ncrm_fieldmodulerel` (
  `fieldid` int(11) NOT NULL,
  `module` varchar(100) NOT NULL,
  `relmodule` varchar(100) NOT NULL,
  `status` varchar(10) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_fieldmodulerel: ~45 rows (approximately)
/*!40000 ALTER TABLE `ncrm_fieldmodulerel` DISABLE KEYS */;
INSERT INTO `ncrm_fieldmodulerel` (`fieldid`, `module`, `relmodule`, `status`, `sequence`) VALUES
	(113, 'Potentials', 'Accounts', NULL, 0),
	(533, 'PBXManager', 'Leads', NULL, NULL),
	(533, 'PBXManager', 'Contacts', NULL, NULL),
	(533, 'PBXManager', 'Accounts', NULL, NULL),
	(546, 'ServiceContracts', 'Contacts', NULL, NULL),
	(546, 'ServiceContracts', 'Accounts', NULL, NULL),
	(580, 'Assets', 'Products', NULL, NULL),
	(586, 'Assets', 'Invoice', NULL, NULL),
	(591, 'Assets', 'Accounts', NULL, NULL),
	(592, 'Assets', 'Contacts', NULL, NULL),
	(601, 'ModComments', 'Leads', NULL, NULL),
	(601, 'ModComments', 'Contacts', NULL, NULL),
	(601, 'ModComments', 'Accounts', NULL, NULL),
	(603, 'ModComments', 'ModComments', NULL, NULL),
	(601, 'ModComments', 'Potentials', NULL, NULL),
	(606, 'ProjectMilestone', 'Project', NULL, NULL),
	(617, 'ProjectTask', 'Project', NULL, NULL),
	(601, 'ModComments', 'ProjectTask', NULL, NULL),
	(635, 'Project', 'Accounts', NULL, NULL),
	(635, 'Project', 'Contacts', NULL, NULL),
	(601, 'ModComments', 'Project', NULL, NULL),
	(653, 'Invoice', 'Products', NULL, NULL),
	(653, 'Invoice', 'Services', NULL, NULL),
	(662, 'SalesOrder', 'Products', NULL, NULL),
	(662, 'SalesOrder', 'Services', NULL, NULL),
	(671, 'PurchaseOrder', 'Products', NULL, NULL),
	(671, 'PurchaseOrder', 'Services', NULL, NULL),
	(680, 'Quotes', 'Products', NULL, NULL),
	(680, 'Quotes', 'Services', NULL, NULL),
	(691, 'ModComments', 'Contacts', NULL, NULL),
	(601, 'ModComments', 'HelpDesk', NULL, NULL),
	(601, 'ModComments', 'Faq', NULL, NULL),
	(717, 'Potentials', 'Contacts', NULL, NULL),
	(157, 'HelpDesk', 'Accounts', NULL, NULL),
	(718, 'HelpDesk', 'Contacts', NULL, NULL),
	(237, 'Accounts', 'Calendar', NULL, NULL),
	(237, 'Leads', 'Calendar', NULL, NULL),
	(237, 'HelpDesk', 'Calendar', NULL, NULL),
	(237, 'Campaigns', 'Calendar', NULL, NULL),
	(237, 'Potentials', 'Calendar', NULL, NULL),
	(237, 'PurchaseOrder', 'Calendar', NULL, NULL),
	(237, 'SalesOrder', 'Calendar', NULL, NULL),
	(237, 'Quotes', 'Calendar', NULL, NULL),
	(237, 'Invoice', 'Calendar', NULL, NULL),
	(238, 'Contacts', 'Calendar', NULL, NULL);
/*!40000 ALTER TABLE `ncrm_fieldmodulerel` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_field_seq
CREATE TABLE IF NOT EXISTS `ncrm_field_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_field_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_field_seq` DISABLE KEYS */;
INSERT INTO `ncrm_field_seq` (`id`) VALUES
	(770);
/*!40000 ALTER TABLE `ncrm_field_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_freetagged_objects
CREATE TABLE IF NOT EXISTS `ncrm_freetagged_objects` (
  `tag_id` int(20) NOT NULL DEFAULT '0',
  `tagger_id` int(20) NOT NULL DEFAULT '0',
  `object_id` int(20) NOT NULL DEFAULT '0',
  `tagged_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `module` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`tag_id`,`tagger_id`,`object_id`),
  KEY `freetagged_objects_tag_id_tagger_id_object_id_idx` (`tag_id`,`tagger_id`,`object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_freetagged_objects: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_freetagged_objects` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_freetagged_objects` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_freetags
CREATE TABLE IF NOT EXISTS `ncrm_freetags` (
  `id` int(19) NOT NULL,
  `tag` varchar(50) NOT NULL DEFAULT '',
  `raw_tag` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_freetags: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_freetags` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_freetags` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_freetags_seq
CREATE TABLE IF NOT EXISTS `ncrm_freetags_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_freetags_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_freetags_seq` DISABLE KEYS */;
INSERT INTO `ncrm_freetags_seq` (`id`) VALUES
	(1);
/*!40000 ALTER TABLE `ncrm_freetags_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_glacct
CREATE TABLE IF NOT EXISTS `ncrm_glacct` (
  `glacctid` int(19) NOT NULL AUTO_INCREMENT,
  `glacct` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  PRIMARY KEY (`glacctid`),
  UNIQUE KEY `glacct_glacct_idx` (`glacct`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_glacct: ~9 rows (approximately)
/*!40000 ALTER TABLE `ncrm_glacct` DISABLE KEYS */;
INSERT INTO `ncrm_glacct` (`glacctid`, `glacct`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(1, '300-Sales-Software', 1, 51, 0),
	(2, '301-Sales-Hardware', 1, 52, 1),
	(3, '302-Rental-Income', 1, 53, 2),
	(4, '303-Interest-Income', 1, 54, 3),
	(5, '304-Sales-Software-Support', 1, 55, 4),
	(6, '305-Sales Other', 1, 56, 5),
	(7, '306-Internet Sales', 1, 57, 6),
	(8, '307-Service-Hardware Labor', 1, 58, 7),
	(9, '308-Sales-Books', 1, 59, 8);
/*!40000 ALTER TABLE `ncrm_glacct` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_glacct_seq
CREATE TABLE IF NOT EXISTS `ncrm_glacct_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_glacct_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_glacct_seq` DISABLE KEYS */;
INSERT INTO `ncrm_glacct_seq` (`id`) VALUES
	(9);
/*!40000 ALTER TABLE `ncrm_glacct_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_google_oauth2
CREATE TABLE IF NOT EXISTS `ncrm_google_oauth2` (
  `service` varchar(20) DEFAULT NULL,
  `access_token` varchar(500) DEFAULT NULL,
  `refresh_token` varchar(500) DEFAULT NULL,
  `userid` int(19) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_google_oauth2: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_google_oauth2` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_google_oauth2` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_google_sync
CREATE TABLE IF NOT EXISTS `ncrm_google_sync` (
  `googlemodule` varchar(50) DEFAULT NULL,
  `user` int(10) DEFAULT NULL,
  `synctime` datetime DEFAULT NULL,
  `lastsynctime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_google_sync: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_google_sync` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_google_sync` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_google_sync_fieldmapping
CREATE TABLE IF NOT EXISTS `ncrm_google_sync_fieldmapping` (
  `ncrm_field` varchar(255) DEFAULT NULL,
  `google_field` varchar(255) DEFAULT NULL,
  `google_field_type` varchar(255) DEFAULT NULL,
  `google_custom_label` varchar(255) DEFAULT NULL,
  `user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_google_sync_fieldmapping: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_google_sync_fieldmapping` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_google_sync_fieldmapping` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_google_sync_settings
CREATE TABLE IF NOT EXISTS `ncrm_google_sync_settings` (
  `user` int(11) DEFAULT NULL,
  `module` varchar(50) DEFAULT NULL,
  `clientgroup` varchar(255) DEFAULT NULL,
  `direction` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_google_sync_settings: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_google_sync_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_google_sync_settings` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_group2grouprel
CREATE TABLE IF NOT EXISTS `ncrm_group2grouprel` (
  `groupid` int(19) NOT NULL,
  `containsgroupid` int(19) NOT NULL,
  PRIMARY KEY (`groupid`,`containsgroupid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_group2grouprel: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_group2grouprel` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_group2grouprel` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_group2role
CREATE TABLE IF NOT EXISTS `ncrm_group2role` (
  `groupid` int(19) NOT NULL,
  `roleid` varchar(255) NOT NULL,
  PRIMARY KEY (`groupid`,`roleid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_group2role: ~3 rows (approximately)
/*!40000 ALTER TABLE `ncrm_group2role` DISABLE KEYS */;
INSERT INTO `ncrm_group2role` (`groupid`, `roleid`) VALUES
	(2, 'H4'),
	(3, 'H2'),
	(4, 'H3');
/*!40000 ALTER TABLE `ncrm_group2role` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_group2rs
CREATE TABLE IF NOT EXISTS `ncrm_group2rs` (
  `groupid` int(19) NOT NULL,
  `roleandsubid` varchar(255) NOT NULL,
  PRIMARY KEY (`groupid`,`roleandsubid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_group2rs: ~3 rows (approximately)
/*!40000 ALTER TABLE `ncrm_group2rs` DISABLE KEYS */;
INSERT INTO `ncrm_group2rs` (`groupid`, `roleandsubid`) VALUES
	(2, 'H5'),
	(3, 'H3'),
	(4, 'H3');
/*!40000 ALTER TABLE `ncrm_group2rs` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_groups
CREATE TABLE IF NOT EXISTS `ncrm_groups` (
  `groupid` int(19) NOT NULL,
  `groupname` varchar(100) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`groupid`),
  UNIQUE KEY `groups_groupname_idx` (`groupname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_groups: ~3 rows (approximately)
/*!40000 ALTER TABLE `ncrm_groups` DISABLE KEYS */;
INSERT INTO `ncrm_groups` (`groupid`, `groupname`, `description`) VALUES
	(2, 'Team Selling', 'Group Related to Sales'),
	(3, 'Marketing Group', 'Group Related to Marketing Activities'),
	(4, 'Support Group', 'Group Related to providing Support to Customers');
/*!40000 ALTER TABLE `ncrm_groups` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_homedashbd
CREATE TABLE IF NOT EXISTS `ncrm_homedashbd` (
  `stuffid` int(19) NOT NULL DEFAULT '0',
  `dashbdname` varchar(100) DEFAULT NULL,
  `dashbdtype` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`stuffid`),
  KEY `stuff_stuffid_idx` (`stuffid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_homedashbd: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_homedashbd` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_homedashbd` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_homedefault
CREATE TABLE IF NOT EXISTS `ncrm_homedefault` (
  `stuffid` int(19) NOT NULL DEFAULT '0',
  `hometype` varchar(30) NOT NULL,
  `maxentries` int(19) DEFAULT NULL,
  `setype` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`stuffid`),
  KEY `stuff_stuffid_idx` (`stuffid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_homedefault: ~28 rows (approximately)
/*!40000 ALTER TABLE `ncrm_homedefault` DISABLE KEYS */;
INSERT INTO `ncrm_homedefault` (`stuffid`, `hometype`, `maxentries`, `setype`) VALUES
	(1, 'ALVT', 5, 'Accounts'),
	(2, 'HDB', 5, 'Dashboard'),
	(3, 'PLVT', 5, 'Potentials'),
	(4, 'QLTQ', 5, 'Quotes'),
	(5, 'CVLVT', 5, 'NULL'),
	(6, 'HLT', 5, 'HelpDesk'),
	(7, 'UA', 5, 'Calendar'),
	(8, 'GRT', 5, 'NULL'),
	(9, 'OLTSO', 5, 'SalesOrder'),
	(10, 'ILTI', 5, 'Invoice'),
	(11, 'MNL', 5, 'Leads'),
	(12, 'OLTPO', 5, 'PurchaseOrder'),
	(13, 'PA', 5, 'Calendar'),
	(14, 'LTFAQ', 5, 'Faq'),
	(16, 'ALVT', 5, 'Accounts'),
	(17, 'HDB', 5, 'Dashboard'),
	(18, 'PLVT', 5, 'Potentials'),
	(19, 'QLTQ', 5, 'Quotes'),
	(20, 'CVLVT', 5, 'NULL'),
	(21, 'HLT', 5, 'HelpDesk'),
	(22, 'UA', 5, 'Calendar'),
	(23, 'GRT', 5, 'NULL'),
	(24, 'OLTSO', 5, 'SalesOrder'),
	(25, 'ILTI', 5, 'Invoice'),
	(26, 'MNL', 5, 'Leads'),
	(27, 'OLTPO', 5, 'PurchaseOrder'),
	(28, 'PA', 5, 'Calendar'),
	(29, 'LTFAQ', 5, 'Faq'),
	(31, 'ALVT', 5, 'Accounts'),
	(32, 'HDB', 5, 'Dashboard'),
	(33, 'PLVT', 5, 'Potentials'),
	(34, 'QLTQ', 5, 'Quotes'),
	(35, 'CVLVT', 5, 'NULL'),
	(36, 'HLT', 5, 'HelpDesk'),
	(37, 'UA', 5, 'Calendar'),
	(38, 'GRT', 5, 'NULL'),
	(39, 'OLTSO', 5, 'SalesOrder'),
	(40, 'ILTI', 5, 'Invoice'),
	(41, 'MNL', 5, 'Leads'),
	(42, 'OLTPO', 5, 'PurchaseOrder'),
	(43, 'PA', 5, 'Calendar'),
	(44, 'LTFAQ', 5, 'Faq');
/*!40000 ALTER TABLE `ncrm_homedefault` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_homemodule
CREATE TABLE IF NOT EXISTS `ncrm_homemodule` (
  `stuffid` int(19) NOT NULL,
  `modulename` varchar(100) DEFAULT NULL,
  `maxentries` int(19) NOT NULL,
  `customviewid` int(19) NOT NULL,
  `setype` varchar(30) NOT NULL,
  PRIMARY KEY (`stuffid`),
  KEY `stuff_stuffid_idx` (`stuffid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_homemodule: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_homemodule` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_homemodule` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_homemoduleflds
CREATE TABLE IF NOT EXISTS `ncrm_homemoduleflds` (
  `stuffid` int(19) DEFAULT NULL,
  `fieldname` varchar(100) DEFAULT NULL,
  KEY `stuff_stuffid_idx` (`stuffid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_homemoduleflds: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_homemoduleflds` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_homemoduleflds` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_homereportchart
CREATE TABLE IF NOT EXISTS `ncrm_homereportchart` (
  `stuffid` int(11) NOT NULL,
  `reportid` int(19) DEFAULT NULL,
  `reportcharttype` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`stuffid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_homereportchart: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_homereportchart` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_homereportchart` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_homerss
CREATE TABLE IF NOT EXISTS `ncrm_homerss` (
  `stuffid` int(19) NOT NULL DEFAULT '0',
  `url` varchar(100) DEFAULT NULL,
  `maxentries` int(19) NOT NULL,
  PRIMARY KEY (`stuffid`),
  KEY `stuff_stuffid_idx` (`stuffid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_homerss: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_homerss` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_homerss` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_homestuff
CREATE TABLE IF NOT EXISTS `ncrm_homestuff` (
  `stuffid` int(19) NOT NULL DEFAULT '0',
  `stuffsequence` int(19) NOT NULL DEFAULT '0',
  `stufftype` varchar(100) DEFAULT NULL,
  `userid` int(19) NOT NULL,
  `visible` int(10) NOT NULL DEFAULT '0',
  `stufftitle` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`stuffid`),
  KEY `stuff_stuffid_idx` (`stuffid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_homestuff: ~30 rows (approximately)
/*!40000 ALTER TABLE `ncrm_homestuff` DISABLE KEYS */;
INSERT INTO `ncrm_homestuff` (`stuffid`, `stuffsequence`, `stufftype`, `userid`, `visible`, `stufftitle`) VALUES
	(1, 1, 'Default', 1, 1, 'Top Accounts'),
	(2, 2, 'Default', 1, 1, 'Home Page Dashboard'),
	(3, 3, 'Default', 1, 1, 'Top Potentials'),
	(4, 4, 'Default', 1, 1, 'Top Quotes'),
	(5, 5, 'Default', 1, 1, 'Key Metrics'),
	(6, 6, 'Default', 1, 1, 'Top Trouble Tickets'),
	(7, 7, 'Default', 1, 1, 'Upcoming Activities'),
	(8, 8, 'Default', 1, 1, 'My Group Allocation'),
	(9, 9, 'Default', 1, 1, 'Top Sales Orders'),
	(10, 10, 'Default', 1, 1, 'Top Invoices'),
	(11, 11, 'Default', 1, 1, 'My New Leads'),
	(12, 12, 'Default', 1, 1, 'Top Purchase Orders'),
	(13, 13, 'Default', 1, 1, 'Pending Activities'),
	(14, 14, 'Default', 1, 1, 'My Recent FAQs'),
	(15, 15, 'Tag Cloud', 1, 0, 'Tag Cloud'),
	(31, 1, 'Default', 6, 1, 'Top Accounts'),
	(32, 2, 'Default', 6, 1, 'Home Page Dashboard'),
	(33, 3, 'Default', 6, 1, 'Top Potentials'),
	(34, 4, 'Default', 6, 1, 'Top Quotes'),
	(35, 5, 'Default', 6, 1, 'Key Metrics'),
	(36, 6, 'Default', 6, 1, 'Top Trouble Tickets'),
	(37, 7, 'Default', 6, 1, 'Upcoming Activities'),
	(38, 8, 'Default', 6, 1, 'My Group Allocation'),
	(39, 9, 'Default', 6, 1, 'Top Sales Orders'),
	(40, 10, 'Default', 6, 1, 'Top Invoices'),
	(41, 11, 'Default', 6, 1, 'My New Leads'),
	(42, 12, 'Default', 6, 1, 'Top Purchase Orders'),
	(43, 13, 'Default', 6, 1, 'Pending Activities'),
	(44, 14, 'Default', 6, 1, 'My Recent FAQs'),
	(45, 15, 'Tag Cloud', 6, 0, 'Tag Cloud');
/*!40000 ALTER TABLE `ncrm_homestuff` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_homestuff_seq
CREATE TABLE IF NOT EXISTS `ncrm_homestuff_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_homestuff_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_homestuff_seq` DISABLE KEYS */;
INSERT INTO `ncrm_homestuff_seq` (`id`) VALUES
	(45);
/*!40000 ALTER TABLE `ncrm_homestuff_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_home_layout
CREATE TABLE IF NOT EXISTS `ncrm_home_layout` (
  `userid` int(19) NOT NULL,
  `layout` int(19) NOT NULL DEFAULT '4',
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_home_layout: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_home_layout` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_home_layout` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_hour_format
CREATE TABLE IF NOT EXISTS `ncrm_hour_format` (
  `hour_formatid` int(11) NOT NULL AUTO_INCREMENT,
  `hour_format` varchar(200) NOT NULL,
  `sortorderid` int(11) DEFAULT NULL,
  `presence` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`hour_formatid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_hour_format: ~2 rows (approximately)
/*!40000 ALTER TABLE `ncrm_hour_format` DISABLE KEYS */;
INSERT INTO `ncrm_hour_format` (`hour_formatid`, `hour_format`, `sortorderid`, `presence`) VALUES
	(1, '12', 1, 1),
	(2, '24', 2, 1);
/*!40000 ALTER TABLE `ncrm_hour_format` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_hour_format_seq
CREATE TABLE IF NOT EXISTS `ncrm_hour_format_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_hour_format_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_hour_format_seq` DISABLE KEYS */;
INSERT INTO `ncrm_hour_format_seq` (`id`) VALUES
	(2);
/*!40000 ALTER TABLE `ncrm_hour_format_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_import_locks
CREATE TABLE IF NOT EXISTS `ncrm_import_locks` (
  `ncrm_import_lock_id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `importid` int(11) NOT NULL,
  `locked_since` datetime DEFAULT NULL,
  PRIMARY KEY (`ncrm_import_lock_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_import_locks: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_import_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_import_locks` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_import_maps
CREATE TABLE IF NOT EXISTS `ncrm_import_maps` (
  `id` int(19) NOT NULL AUTO_INCREMENT,
  `name` varchar(36) NOT NULL,
  `module` varchar(36) NOT NULL,
  `content` longblob,
  `has_header` int(1) NOT NULL DEFAULT '1',
  `deleted` int(1) NOT NULL DEFAULT '0',
  `date_entered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_modified` datetime DEFAULT NULL,
  `assigned_user_id` varchar(36) DEFAULT NULL,
  `is_published` varchar(3) NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `import_maps_assigned_user_id_module_name_deleted_idx` (`assigned_user_id`,`module`,`name`,`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_import_maps: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_import_maps` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_import_maps` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_import_queue
CREATE TABLE IF NOT EXISTS `ncrm_import_queue` (
  `importid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `field_mapping` text,
  `default_values` text,
  `merge_type` int(11) DEFAULT NULL,
  `merge_fields` text,
  `status` int(11) DEFAULT '0',
  PRIMARY KEY (`importid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_import_queue: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_import_queue` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_import_queue` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_industry
CREATE TABLE IF NOT EXISTS `ncrm_industry` (
  `industryid` int(19) NOT NULL AUTO_INCREMENT,
  `industry` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  PRIMARY KEY (`industryid`),
  UNIQUE KEY `industry_industry_idx` (`industry`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_industry: ~31 rows (approximately)
/*!40000 ALTER TABLE `ncrm_industry` DISABLE KEYS */;
INSERT INTO `ncrm_industry` (`industryid`, `industry`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(2, 'Apparel', 1, 61, 1),
	(3, 'Banking', 1, 62, 2),
	(4, 'Biotechnology', 1, 63, 3),
	(5, 'Chemicals', 1, 64, 4),
	(6, 'Communications', 1, 65, 5),
	(7, 'Construction', 1, 66, 6),
	(8, 'Consulting', 1, 67, 7),
	(9, 'Education', 1, 68, 8),
	(10, 'Electronics', 1, 69, 9),
	(11, 'Energy', 1, 70, 10),
	(12, 'Engineering', 1, 71, 11),
	(13, 'Entertainment', 1, 72, 12),
	(14, 'Environmental', 1, 73, 13),
	(15, 'Finance', 1, 74, 14),
	(16, 'Food & Beverage', 1, 75, 15),
	(17, 'Government', 1, 76, 16),
	(18, 'Healthcare', 1, 77, 17),
	(19, 'Hospitality', 1, 78, 18),
	(20, 'Insurance', 1, 79, 19),
	(21, 'Machinery', 1, 80, 20),
	(22, 'Manufacturing', 1, 81, 21),
	(23, 'Media', 1, 82, 22),
	(24, 'Not For Profit', 1, 83, 23),
	(25, 'Recreation', 1, 84, 24),
	(26, 'Retail', 1, 85, 25),
	(27, 'Shipping', 1, 86, 26),
	(28, 'Technology', 1, 87, 27),
	(29, 'Telecommunications', 1, 88, 28),
	(30, 'Transportation', 1, 89, 29),
	(31, 'Utilities', 1, 90, 30),
	(32, 'Other', 1, 91, 31);
/*!40000 ALTER TABLE `ncrm_industry` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_industry_seq
CREATE TABLE IF NOT EXISTS `ncrm_industry_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_industry_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_industry_seq` DISABLE KEYS */;
INSERT INTO `ncrm_industry_seq` (`id`) VALUES
	(32);
/*!40000 ALTER TABLE `ncrm_industry_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_inventorynotification
CREATE TABLE IF NOT EXISTS `ncrm_inventorynotification` (
  `notificationid` int(19) NOT NULL AUTO_INCREMENT,
  `notificationname` varchar(200) DEFAULT NULL,
  `notificationsubject` varchar(200) DEFAULT NULL,
  `notificationbody` text,
  `label` varchar(50) DEFAULT NULL,
  `status` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`notificationid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_inventorynotification: ~3 rows (approximately)
/*!40000 ALTER TABLE `ncrm_inventorynotification` DISABLE KEYS */;
INSERT INTO `ncrm_inventorynotification` (`notificationid`, `notificationname`, `notificationsubject`, `notificationbody`, `label`, `status`) VALUES
	(1, 'InvoiceNotification', '{PRODUCTNAME} Stock Level is Low', 'Dear {HANDLER},\n\nThe current stock of {PRODUCTNAME} in our warehouse is {CURRENTSTOCK}. Kindly procure required number of units as the stock level is below reorder level {REORDERLEVELVALUE}.\n\nPlease treat this information as Urgent as the invoice is already sent  to the customer.\n\nSeverity: Critical\n\nThanks,\n{CURRENTUSER} ', 'InvoiceNotificationDescription', NULL),
	(2, 'QuoteNotification', 'Quote given for {PRODUCTNAME}', 'Dear {HANDLER},\n\nQuote is generated for {QUOTEQUANTITY} units of {PRODUCTNAME}. The current stock of {PRODUCTNAME} in our warehouse is {CURRENTSTOCK}.\n\nSeverity: Minor\n\nThanks,\n{CURRENTUSER} ', 'QuoteNotificationDescription', NULL),
	(3, 'SalesOrderNotification', 'Sales Order generated for {PRODUCTNAME}', 'Dear {HANDLER},\n\nSalesOrder is generated for {SOQUANTITY} units of {PRODUCTNAME}. The current stock of {PRODUCTNAME} in our warehouse is {CURRENTSTOCK}.\n\nPlease treat this information  with priority as the sales order is already generated.\n\nSeverity: Major\n\nThanks,\n{CURRENTUSER} ', 'SalesOrderNotificationDescription', NULL);
/*!40000 ALTER TABLE `ncrm_inventorynotification` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_inventorynotification_seq
CREATE TABLE IF NOT EXISTS `ncrm_inventorynotification_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_inventorynotification_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_inventorynotification_seq` DISABLE KEYS */;
INSERT INTO `ncrm_inventorynotification_seq` (`id`) VALUES
	(3);
/*!40000 ALTER TABLE `ncrm_inventorynotification_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_inventoryproductrel
CREATE TABLE IF NOT EXISTS `ncrm_inventoryproductrel` (
  `id` int(19) DEFAULT NULL,
  `productid` int(19) DEFAULT NULL,
  `sequence_no` int(4) DEFAULT NULL,
  `quantity` decimal(25,3) DEFAULT NULL,
  `listprice` decimal(27,8) DEFAULT NULL,
  `discount_percent` decimal(7,3) DEFAULT NULL,
  `discount_amount` decimal(27,8) DEFAULT NULL,
  `comment` text,
  `description` text,
  `incrementondel` int(11) NOT NULL DEFAULT '0',
  `lineitem_id` int(11) NOT NULL AUTO_INCREMENT,
  `tax1` decimal(7,3) DEFAULT NULL,
  `tax2` decimal(7,3) DEFAULT NULL,
  `tax3` decimal(7,3) DEFAULT NULL,
  PRIMARY KEY (`lineitem_id`),
  KEY `inventoryproductrel_id_idx` (`id`),
  KEY `inventoryproductrel_productid_idx` (`productid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_inventoryproductrel: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_inventoryproductrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_inventoryproductrel` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_inventoryproductrel_seq
CREATE TABLE IF NOT EXISTS `ncrm_inventoryproductrel_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_inventoryproductrel_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_inventoryproductrel_seq` DISABLE KEYS */;
INSERT INTO `ncrm_inventoryproductrel_seq` (`id`) VALUES
	(0);
/*!40000 ALTER TABLE `ncrm_inventoryproductrel_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_inventoryshippingrel
CREATE TABLE IF NOT EXISTS `ncrm_inventoryshippingrel` (
  `id` int(19) DEFAULT NULL,
  `shtax1` decimal(7,3) DEFAULT NULL,
  `shtax2` decimal(7,3) DEFAULT NULL,
  `shtax3` decimal(7,3) DEFAULT NULL,
  KEY `inventoryishippingrel_id_idx` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_inventoryshippingrel: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_inventoryshippingrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_inventoryshippingrel` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_inventorysubproductrel
CREATE TABLE IF NOT EXISTS `ncrm_inventorysubproductrel` (
  `id` int(19) NOT NULL,
  `sequence_no` int(10) NOT NULL,
  `productid` int(19) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_inventorysubproductrel: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_inventorysubproductrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_inventorysubproductrel` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_inventorytaxinfo
CREATE TABLE IF NOT EXISTS `ncrm_inventorytaxinfo` (
  `taxid` int(3) NOT NULL,
  `taxname` varchar(50) DEFAULT NULL,
  `taxlabel` varchar(50) DEFAULT NULL,
  `percentage` decimal(7,3) DEFAULT NULL,
  `deleted` int(1) DEFAULT NULL,
  PRIMARY KEY (`taxid`),
  KEY `inventorytaxinfo_taxname_idx` (`taxname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_inventorytaxinfo: ~3 rows (approximately)
/*!40000 ALTER TABLE `ncrm_inventorytaxinfo` DISABLE KEYS */;
INSERT INTO `ncrm_inventorytaxinfo` (`taxid`, `taxname`, `taxlabel`, `percentage`, `deleted`) VALUES
	(1, 'tax1', 'VAT', 4.500, 0),
	(2, 'tax2', 'Sales', 10.000, 0),
	(3, 'tax3', 'Service', 12.500, 0);
/*!40000 ALTER TABLE `ncrm_inventorytaxinfo` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_inventorytaxinfo_seq
CREATE TABLE IF NOT EXISTS `ncrm_inventorytaxinfo_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_inventorytaxinfo_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_inventorytaxinfo_seq` DISABLE KEYS */;
INSERT INTO `ncrm_inventorytaxinfo_seq` (`id`) VALUES
	(3);
/*!40000 ALTER TABLE `ncrm_inventorytaxinfo_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_inventory_tandc
CREATE TABLE IF NOT EXISTS `ncrm_inventory_tandc` (
  `id` int(19) NOT NULL,
  `type` varchar(30) NOT NULL,
  `tandc` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_inventory_tandc: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_inventory_tandc` DISABLE KEYS */;
INSERT INTO `ncrm_inventory_tandc` (`id`, `type`, `tandc`) VALUES
	(1, 'Inventory', '\n - Unless otherwise agreed in writing by the supplier all invoices are payable within thirty (30) days of the date of invoice, in the currency of the invoice, drawn on a bank based in India or by such other method as is agreed in advance by the Supplier.\n\n - All prices are not inclusive of VAT which shall be payable in addition by the Customer at the applicable rate.');
/*!40000 ALTER TABLE `ncrm_inventory_tandc` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_inventory_tandc_seq
CREATE TABLE IF NOT EXISTS `ncrm_inventory_tandc_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_inventory_tandc_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_inventory_tandc_seq` DISABLE KEYS */;
INSERT INTO `ncrm_inventory_tandc_seq` (`id`) VALUES
	(1);
/*!40000 ALTER TABLE `ncrm_inventory_tandc_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_invitees
CREATE TABLE IF NOT EXISTS `ncrm_invitees` (
  `activityid` int(19) NOT NULL,
  `inviteeid` int(19) NOT NULL,
  PRIMARY KEY (`activityid`,`inviteeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_invitees: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_invitees` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_invitees` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_invoice
CREATE TABLE IF NOT EXISTS `ncrm_invoice` (
  `invoiceid` int(19) NOT NULL DEFAULT '0',
  `subject` varchar(100) DEFAULT NULL,
  `salesorderid` int(19) DEFAULT NULL,
  `customerno` varchar(100) DEFAULT NULL,
  `contactid` int(19) DEFAULT NULL,
  `notes` varchar(100) DEFAULT NULL,
  `invoicedate` date DEFAULT NULL,
  `duedate` date DEFAULT NULL,
  `invoiceterms` varchar(100) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `adjustment` decimal(25,8) DEFAULT NULL,
  `salescommission` decimal(25,3) DEFAULT NULL,
  `exciseduty` decimal(25,3) DEFAULT NULL,
  `subtotal` decimal(25,8) DEFAULT NULL,
  `total` decimal(25,8) DEFAULT NULL,
  `taxtype` varchar(25) DEFAULT NULL,
  `discount_percent` decimal(25,3) DEFAULT NULL,
  `discount_amount` decimal(25,8) DEFAULT NULL,
  `s_h_amount` decimal(25,8) DEFAULT NULL,
  `shipping` varchar(100) DEFAULT NULL,
  `accountid` int(19) DEFAULT NULL,
  `terms_conditions` text,
  `purchaseorder` varchar(200) DEFAULT NULL,
  `invoicestatus` varchar(200) DEFAULT NULL,
  `invoice_no` varchar(100) DEFAULT NULL,
  `currency_id` int(19) NOT NULL DEFAULT '1',
  `conversion_rate` decimal(10,3) NOT NULL DEFAULT '1.000',
  `pre_tax_total` decimal(25,8) DEFAULT NULL,
  `received` decimal(25,8) DEFAULT NULL,
  `balance` decimal(25,8) DEFAULT NULL,
  `s_h_percent` int(11) DEFAULT NULL,
  PRIMARY KEY (`invoiceid`),
  KEY `invoice_purchaseorderid_idx` (`invoiceid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_invoice: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_invoice` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_invoice` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_invoicebillads
CREATE TABLE IF NOT EXISTS `ncrm_invoicebillads` (
  `invoicebilladdressid` int(19) NOT NULL DEFAULT '0',
  `bill_city` varchar(30) DEFAULT NULL,
  `bill_code` varchar(30) DEFAULT NULL,
  `bill_country` varchar(30) DEFAULT NULL,
  `bill_state` varchar(30) DEFAULT NULL,
  `bill_street` varchar(250) DEFAULT NULL,
  `bill_pobox` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`invoicebilladdressid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_invoicebillads: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_invoicebillads` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_invoicebillads` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_invoicecf
CREATE TABLE IF NOT EXISTS `ncrm_invoicecf` (
  `invoiceid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`invoiceid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_invoicecf: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_invoicecf` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_invoicecf` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_invoiceshipads
CREATE TABLE IF NOT EXISTS `ncrm_invoiceshipads` (
  `invoiceshipaddressid` int(19) NOT NULL DEFAULT '0',
  `ship_city` varchar(30) DEFAULT NULL,
  `ship_code` varchar(30) DEFAULT NULL,
  `ship_country` varchar(30) DEFAULT NULL,
  `ship_state` varchar(30) DEFAULT NULL,
  `ship_street` varchar(250) DEFAULT NULL,
  `ship_pobox` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`invoiceshipaddressid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_invoiceshipads: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_invoiceshipads` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_invoiceshipads` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_invoicestatus
CREATE TABLE IF NOT EXISTS `ncrm_invoicestatus` (
  `invoicestatusid` int(19) NOT NULL AUTO_INCREMENT,
  `invoicestatus` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  PRIMARY KEY (`invoicestatusid`),
  UNIQUE KEY `invoicestatus_invoiestatus_idx` (`invoicestatus`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_invoicestatus: ~7 rows (approximately)
/*!40000 ALTER TABLE `ncrm_invoicestatus` DISABLE KEYS */;
INSERT INTO `ncrm_invoicestatus` (`invoicestatusid`, `invoicestatus`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(1, 'AutoCreated', 0, 92, 0),
	(2, 'Created', 0, 93, 1),
	(3, 'Approved', 0, 94, 2),
	(4, 'Sent', 0, 95, 3),
	(5, 'Credit Invoice', 0, 96, 4),
	(6, 'Paid', 0, 97, 5),
	(7, 'Cancel', 1, 288, 1);
/*!40000 ALTER TABLE `ncrm_invoicestatus` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_invoicestatushistory
CREATE TABLE IF NOT EXISTS `ncrm_invoicestatushistory` (
  `historyid` int(19) NOT NULL AUTO_INCREMENT,
  `invoiceid` int(19) NOT NULL,
  `accountname` varchar(100) DEFAULT NULL,
  `total` decimal(10,0) DEFAULT NULL,
  `invoicestatus` varchar(200) DEFAULT NULL,
  `lastmodified` datetime DEFAULT NULL,
  PRIMARY KEY (`historyid`),
  KEY `invoicestatushistory_invoiceid_idx` (`invoiceid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_invoicestatushistory: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_invoicestatushistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_invoicestatushistory` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_invoicestatus_seq
CREATE TABLE IF NOT EXISTS `ncrm_invoicestatus_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_invoicestatus_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_invoicestatus_seq` DISABLE KEYS */;
INSERT INTO `ncrm_invoicestatus_seq` (`id`) VALUES
	(7);
/*!40000 ALTER TABLE `ncrm_invoicestatus_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_invoice_recurring_info
CREATE TABLE IF NOT EXISTS `ncrm_invoice_recurring_info` (
  `salesorderid` int(11) NOT NULL,
  `recurring_frequency` varchar(200) DEFAULT NULL,
  `start_period` date DEFAULT NULL,
  `end_period` date DEFAULT NULL,
  `last_recurring_date` date DEFAULT NULL,
  `payment_duration` varchar(200) DEFAULT NULL,
  `invoice_status` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`salesorderid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_invoice_recurring_info: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_invoice_recurring_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_invoice_recurring_info` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_language
CREATE TABLE IF NOT EXISTS `ncrm_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `prefix` varchar(10) DEFAULT NULL,
  `label` varchar(30) DEFAULT NULL,
  `lastupdated` datetime DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `isdefault` int(1) DEFAULT NULL,
  `active` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_language: ~16 rows (approximately)
/*!40000 ALTER TABLE `ncrm_language` DISABLE KEYS */;
INSERT INTO `ncrm_language` (`id`, `name`, `prefix`, `label`, `lastupdated`, `sequence`, `isdefault`, `active`) VALUES
	(1, 'English', 'en_us', 'US English', '2016-12-17 19:21:59', NULL, 0, 1),
	(2, 'Arabic', 'ar_ae', 'Arabic', '2016-12-17 19:25:08', NULL, 0, 1),
	(3, 'Brazilian', 'pt_br', 'PT Brasil', '2016-12-17 19:22:57', NULL, 0, 1),
	(4, 'British English', 'en_gb', 'British English', '2016-12-17 19:22:39', NULL, 0, 1),
	(5, 'Deutsch', 'de_de', 'DE Deutsch', '2016-12-17 19:22:40', NULL, 0, 1),
	(6, 'Dutch', 'nl_nl', 'NL-Dutch', '2016-12-17 19:22:40', NULL, 0, 1),
	(7, 'Pack de langue français', 'fr_fr', 'Pack de langue français', '2016-12-17 19:22:58', NULL, 0, 1),
	(8, 'Hungarian', 'hu_hu', 'HU Magyar', '2016-12-17 19:22:43', NULL, 0, 1),
	(9, 'Italian', 'it_it', 'IT Italian', '2016-12-17 19:22:43', NULL, 0, 1),
	(10, 'Mexican Spanish', 'es_mx', 'ES Mexico', '2016-12-17 19:22:44', NULL, 0, 1),
	(11, 'Język Polski', 'pl_pl', 'Język Polski', '2016-12-17 19:22:58', NULL, 0, 1),
	(12, 'Romana', 'ro_ro', 'Romana', '2016-12-17 19:22:58', NULL, 0, 1),
	(13, 'Russian', 'ru_ru', 'Russian', '2016-12-17 19:22:52', NULL, 0, 1),
	(14, 'Spanish', 'es_es', 'ES Spanish', '2016-12-17 19:22:54', NULL, 0, 1),
	(15, 'Swedish', 'sv_se', 'Swedish', '2016-12-17 19:25:08', NULL, 0, 1),
	(16, 'Turkce', 'tr_tr', 'Turkce Dil Paketi', '2016-12-17 19:22:55', NULL, 0, 1),
	(17, 'Vietnamese', 'vn_vi', 'Vietnamese', '2017-11-25 14:10:01', NULL, 1, 1);
/*!40000 ALTER TABLE `ncrm_language` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_language_seq
CREATE TABLE IF NOT EXISTS `ncrm_language_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_language_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_language_seq` DISABLE KEYS */;
INSERT INTO `ncrm_language_seq` (`id`) VALUES
	(16);
/*!40000 ALTER TABLE `ncrm_language_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_leadaddress
CREATE TABLE IF NOT EXISTS `ncrm_leadaddress` (
  `leadaddressid` int(19) NOT NULL DEFAULT '0',
  `city` varchar(30) DEFAULT NULL,
  `code` varchar(30) DEFAULT NULL,
  `state` varchar(30) DEFAULT NULL,
  `pobox` varchar(30) DEFAULT NULL,
  `country` varchar(30) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `fax` varchar(50) DEFAULT NULL,
  `lane` varchar(250) DEFAULT NULL,
  `leadaddresstype` varchar(30) DEFAULT 'Billing',
  PRIMARY KEY (`leadaddressid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_leadaddress: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_leadaddress` DISABLE KEYS */;
INSERT INTO `ncrm_leadaddress` (`leadaddressid`, `city`, `code`, `state`, `pobox`, `country`, `phone`, `mobile`, `fax`, `lane`, `leadaddresstype`) VALUES
	(23, '', '', '', '', '', '', '', '', '', 'Billing');
/*!40000 ALTER TABLE `ncrm_leadaddress` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_leaddetails
CREATE TABLE IF NOT EXISTS `ncrm_leaddetails` (
  `leadid` int(19) NOT NULL,
  `lead_no` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `interest` varchar(50) DEFAULT NULL,
  `firstname` varchar(40) DEFAULT NULL,
  `salutation` varchar(200) DEFAULT NULL,
  `lastname` varchar(80) NOT NULL,
  `company` varchar(100) NOT NULL,
  `annualrevenue` decimal(25,8) DEFAULT NULL,
  `industry` varchar(200) DEFAULT NULL,
  `campaign` varchar(30) DEFAULT NULL,
  `rating` varchar(200) DEFAULT NULL,
  `leadstatus` varchar(50) DEFAULT NULL,
  `leadsource` varchar(200) DEFAULT NULL,
  `converted` int(1) DEFAULT '0',
  `designation` varchar(50) DEFAULT 'SalesMan',
  `licencekeystatus` varchar(50) DEFAULT NULL,
  `space` varchar(250) DEFAULT NULL,
  `comments` text,
  `priority` varchar(50) DEFAULT NULL,
  `demorequest` varchar(50) DEFAULT NULL,
  `partnercontact` varchar(50) DEFAULT NULL,
  `productversion` varchar(20) DEFAULT NULL,
  `product` varchar(50) DEFAULT NULL,
  `maildate` date DEFAULT NULL,
  `nextstepdate` date DEFAULT NULL,
  `fundingsituation` varchar(50) DEFAULT NULL,
  `purpose` varchar(50) DEFAULT NULL,
  `evaluationstatus` varchar(50) DEFAULT NULL,
  `transferdate` date DEFAULT NULL,
  `revenuetype` varchar(50) DEFAULT NULL,
  `noofemployees` int(50) DEFAULT NULL,
  `secondaryemail` varchar(100) DEFAULT NULL,
  `assignleadchk` int(1) DEFAULT '0',
  `emailoptout` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`leadid`),
  KEY `leaddetails_converted_leadstatus_idx` (`converted`,`leadstatus`),
  KEY `email_idx` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_leaddetails: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_leaddetails` DISABLE KEYS */;
INSERT INTO `ncrm_leaddetails` (`leadid`, `lead_no`, `email`, `interest`, `firstname`, `salutation`, `lastname`, `company`, `annualrevenue`, `industry`, `campaign`, `rating`, `leadstatus`, `leadsource`, `converted`, `designation`, `licencekeystatus`, `space`, `comments`, `priority`, `demorequest`, `partnercontact`, `productversion`, `product`, `maildate`, `nextstepdate`, `fundingsituation`, `purpose`, `evaluationstatus`, `transferdate`, `revenuetype`, `noofemployees`, `secondaryemail`, `assignleadchk`, `emailoptout`) VALUES
	(23, 'LEA1', '', NULL, 'lead 1', '', 'lead 1', '', 0.00000000, '', NULL, '', '', '', 1, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '', 0, '0');
/*!40000 ALTER TABLE `ncrm_leaddetails` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_leadscf
CREATE TABLE IF NOT EXISTS `ncrm_leadscf` (
  `leadid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`leadid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_leadscf: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_leadscf` DISABLE KEYS */;
INSERT INTO `ncrm_leadscf` (`leadid`) VALUES
	(23);
/*!40000 ALTER TABLE `ncrm_leadscf` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_leadsource
CREATE TABLE IF NOT EXISTS `ncrm_leadsource` (
  `leadsourceid` int(19) NOT NULL AUTO_INCREMENT,
  `leadsource` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  PRIMARY KEY (`leadsourceid`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_leadsource: ~12 rows (approximately)
/*!40000 ALTER TABLE `ncrm_leadsource` DISABLE KEYS */;
INSERT INTO `ncrm_leadsource` (`leadsourceid`, `leadsource`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(2, 'Cold Call', 1, 99, 1),
	(3, 'Existing Customer', 1, 100, 2),
	(4, 'Self Generated', 1, 101, 3),
	(5, 'Employee', 1, 102, 4),
	(6, 'Partner', 1, 103, 5),
	(7, 'Public Relations', 1, 104, 6),
	(8, 'Direct Mail', 1, 105, 7),
	(9, 'Conference', 1, 106, 8),
	(10, 'Trade Show', 1, 107, 9),
	(11, 'Web Site', 1, 108, 10),
	(12, 'Word of mouth', 1, 109, 11),
	(13, 'Other', 1, 110, 12);
/*!40000 ALTER TABLE `ncrm_leadsource` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_leadsource_seq
CREATE TABLE IF NOT EXISTS `ncrm_leadsource_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_leadsource_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_leadsource_seq` DISABLE KEYS */;
INSERT INTO `ncrm_leadsource_seq` (`id`) VALUES
	(13);
/*!40000 ALTER TABLE `ncrm_leadsource_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_leadstage
CREATE TABLE IF NOT EXISTS `ncrm_leadstage` (
  `leadstageid` int(19) NOT NULL AUTO_INCREMENT,
  `stage` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`leadstageid`),
  UNIQUE KEY `leadstage_stage_idx` (`stage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_leadstage: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_leadstage` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_leadstage` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_leadstatus
CREATE TABLE IF NOT EXISTS `ncrm_leadstatus` (
  `leadstatusid` int(19) NOT NULL AUTO_INCREMENT,
  `leadstatus` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  PRIMARY KEY (`leadstatusid`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_leadstatus: ~11 rows (approximately)
/*!40000 ALTER TABLE `ncrm_leadstatus` DISABLE KEYS */;
INSERT INTO `ncrm_leadstatus` (`leadstatusid`, `leadstatus`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(2, 'Attempted to Contact', 1, 112, 1),
	(3, 'Cold', 1, 113, 2),
	(4, 'Contact in Future', 1, 114, 3),
	(5, 'Contacted', 1, 115, 4),
	(6, 'Hot', 1, 116, 5),
	(7, 'Junk Lead', 1, 117, 6),
	(8, 'Lost Lead', 1, 118, 7),
	(9, 'Not Contacted', 1, 119, 8),
	(10, 'Pre Qualified', 1, 120, 9),
	(11, 'Qualified', 1, 121, 10),
	(12, 'Warm', 1, 122, 11);
/*!40000 ALTER TABLE `ncrm_leadstatus` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_leadstatus_seq
CREATE TABLE IF NOT EXISTS `ncrm_leadstatus_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_leadstatus_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_leadstatus_seq` DISABLE KEYS */;
INSERT INTO `ncrm_leadstatus_seq` (`id`) VALUES
	(12);
/*!40000 ALTER TABLE `ncrm_leadstatus_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_leadsubdetails
CREATE TABLE IF NOT EXISTS `ncrm_leadsubdetails` (
  `leadsubscriptionid` int(19) NOT NULL DEFAULT '0',
  `website` varchar(255) DEFAULT NULL,
  `callornot` int(1) DEFAULT '0',
  `readornot` int(1) DEFAULT '0',
  `empct` int(10) DEFAULT '0',
  PRIMARY KEY (`leadsubscriptionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_leadsubdetails: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_leadsubdetails` DISABLE KEYS */;
INSERT INTO `ncrm_leadsubdetails` (`leadsubscriptionid`, `website`, `callornot`, `readornot`, `empct`) VALUES
	(23, '', 0, 0, 0);
/*!40000 ALTER TABLE `ncrm_leadsubdetails` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_lead_view
CREATE TABLE IF NOT EXISTS `ncrm_lead_view` (
  `lead_viewid` int(19) NOT NULL AUTO_INCREMENT,
  `lead_view` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`lead_viewid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_lead_view: ~3 rows (approximately)
/*!40000 ALTER TABLE `ncrm_lead_view` DISABLE KEYS */;
INSERT INTO `ncrm_lead_view` (`lead_viewid`, `lead_view`, `sortorderid`, `presence`) VALUES
	(1, 'Today', 0, 1),
	(2, 'Last 2 Days', 1, 1),
	(3, 'Last Week', 2, 1);
/*!40000 ALTER TABLE `ncrm_lead_view` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_lead_view_seq
CREATE TABLE IF NOT EXISTS `ncrm_lead_view_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_lead_view_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_lead_view_seq` DISABLE KEYS */;
INSERT INTO `ncrm_lead_view_seq` (`id`) VALUES
	(3);
/*!40000 ALTER TABLE `ncrm_lead_view_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_links
CREATE TABLE IF NOT EXISTS `ncrm_links` (
  `linkid` int(11) NOT NULL,
  `tabid` int(11) DEFAULT NULL,
  `linktype` varchar(50) DEFAULT NULL,
  `linklabel` varchar(50) DEFAULT NULL,
  `linkurl` varchar(255) DEFAULT NULL,
  `linkicon` varchar(100) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `handler_path` varchar(128) DEFAULT NULL,
  `handler_class` varchar(50) DEFAULT NULL,
  `handler` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`linkid`),
  KEY `link_tabidtype_idx` (`tabid`,`linktype`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_links: ~59 rows (approximately)
/*!40000 ALTER TABLE `ncrm_links` DISABLE KEYS */;
INSERT INTO `ncrm_links` (`linkid`, `tabid`, `linktype`, `linklabel`, `linkurl`, `linkicon`, `sequence`, `handler_path`, `handler_class`, `handler`) VALUES
	(1, 6, 'DETAILVIEWBASIC', 'LBL_ADD_NOTE', 'index.php?module=Documents&action=EditView&return_module=$MODULE$&return_action=DetailView&return_id=$RECORD$&parent_id=$RECORD$', 'themes/images/bookMark.gif', 0, 'modules/Documents/Documents.php', 'Documents', 'isLinkPermitted'),
	(2, 6, 'DETAILVIEWBASIC', 'LBL_SHOW_ACCOUNT_HIERARCHY', 'index.php?module=Accounts&action=AccountHierarchy&accountid=$RECORD$', '', 0, NULL, NULL, NULL),
	(3, 7, 'DETAILVIEWBASIC', 'LBL_ADD_NOTE', 'index.php?module=Documents&action=EditView&return_module=$MODULE$&return_action=DetailView&return_id=$RECORD$&parent_id=$RECORD$', 'themes/images/bookMark.gif', 0, 'modules/Documents/Documents.php', 'Documents', 'isLinkPermitted'),
	(4, 4, 'DETAILVIEWBASIC', 'LBL_ADD_NOTE', 'index.php?module=Documents&action=EditView&return_module=$MODULE$&return_action=DetailView&return_id=$RECORD$&parent_id=$RECORD$', 'themes/images/bookMark.gif', 0, 'modules/Documents/Documents.php', 'Documents', 'isLinkPermitted'),
	(11, 42, 'HEADERSCRIPT', 'ModCommentsCommonHeaderScript', 'modules/ModComments/ModCommentsCommon.js', '', 0, NULL, NULL, NULL),
	(12, 7, 'DETAILVIEWWIDGET', 'DetailViewBlockCommentWidget', 'block://ModComments:modules/ModComments/ModComments.php', '', 0, NULL, NULL, NULL),
	(13, 4, 'DETAILVIEWWIDGET', 'DetailViewBlockCommentWidget', 'block://ModComments:modules/ModComments/ModComments.php', '', 0, NULL, NULL, NULL),
	(14, 6, 'DETAILVIEWWIDGET', 'DetailViewBlockCommentWidget', 'block://ModComments:modules/ModComments/ModComments.php', '', 0, NULL, NULL, NULL),
	(15, 2, 'DETAILVIEWWIDGET', 'DetailViewBlockCommentWidget', 'block://ModComments:modules/ModComments/ModComments.php', '', 0, NULL, NULL, NULL),
	(21, 47, 'DETAILVIEWBASIC', 'LBL_CHECK_STATUS', 'javascript:SMSNotifier.checkstatus($RECORD$)', 'themes/images/reload.gif', 0, NULL, NULL, NULL),
	(30, 44, 'DETAILVIEWBASIC', 'Add Note', 'index.php?module=Documents&action=EditView&return_module=ProjectTask&return_action=DetailView&return_id=$RECORD$&parent_id=$RECORD$', '', 0, 'modules/Documents/Documents.php', 'Documents', 'isLinkPermitted'),
	(31, 44, 'DETAILVIEWWIDGET', 'DetailViewBlockCommentWidget', 'block://ModComments:modules/ModComments/ModComments.php', '', 0, NULL, NULL, NULL),
	(32, 45, 'DETAILVIEWBASIC', 'Add Project Task', 'index.php?module=ProjectTask&action=EditView&projectid=$RECORD$&return_module=Project&return_action=DetailView&return_id=$RECORD$', '', 0, 'modules/ProjectTask/ProjectTask.php', 'ProjectTask', 'isLinkPermitted'),
	(33, 45, 'DETAILVIEWBASIC', 'Add Note', 'index.php?module=Documents&action=EditView&return_module=Project&return_action=DetailView&return_id=$RECORD$&parent_id=$RECORD$', '', 1, 'modules/Documents/Documents.php', 'Documents', 'isLinkPermitted'),
	(34, 45, 'DETAILVIEWWIDGET', 'DetailViewBlockCommentWidget', 'block://ModComments:modules/ModComments/ModComments.php', '', 0, NULL, NULL, NULL),
	(35, 2, 'DASHBOARDWIDGET', 'History', 'index.php?module=Potentials&view=ShowWidget&name=History', '', 1, NULL, NULL, NULL),
	(36, 2, 'DASHBOARDWIDGET', 'Upcoming Activities', 'index.php?module=Potentials&view=ShowWidget&name=CalendarActivities', '', 2, NULL, NULL, NULL),
	(37, 2, 'DASHBOARDWIDGET', 'Funnel', 'index.php?module=Potentials&view=ShowWidget&name=GroupedBySalesStage', '', 3, NULL, NULL, NULL),
	(38, 2, 'DASHBOARDWIDGET', 'Potentials by Stage', 'index.php?module=Potentials&view=ShowWidget&name=GroupedBySalesPerson', '', 4, NULL, NULL, NULL),
	(39, 2, 'DASHBOARDWIDGET', 'Pipelined Amount', 'index.php?module=Potentials&view=ShowWidget&name=PipelinedAmountPerSalesPerson', '', 5, NULL, NULL, NULL),
	(40, 2, 'DASHBOARDWIDGET', 'Total Revenue', 'index.php?module=Potentials&view=ShowWidget&name=TotalRevenuePerSalesPerson', '', 6, NULL, NULL, NULL),
	(41, 2, 'DASHBOARDWIDGET', 'Top Potentials', 'index.php?module=Potentials&view=ShowWidget&name=TopPotentials', '', 7, NULL, NULL, NULL),
	(42, 2, 'DASHBOARDWIDGET', 'Overdue Activities', 'index.php?module=Potentials&view=ShowWidget&name=OverdueActivities', '', 9, NULL, NULL, NULL),
	(43, 6, 'DASHBOARDWIDGET', 'History', 'index.php?module=Accounts&view=ShowWidget&name=History', '', 1, NULL, NULL, NULL),
	(44, 6, 'DASHBOARDWIDGET', 'Upcoming Activities', 'index.php?module=Accounts&view=ShowWidget&name=CalendarActivities', '', 2, NULL, NULL, NULL),
	(45, 6, 'DASHBOARDWIDGET', 'Overdue Activities', 'index.php?module=Accounts&view=ShowWidget&name=OverdueActivities', '', 3, NULL, NULL, NULL),
	(46, 4, 'DASHBOARDWIDGET', 'History', 'index.php?module=Contacts&view=ShowWidget&name=History', '', 1, NULL, NULL, NULL),
	(47, 4, 'DASHBOARDWIDGET', 'Upcoming Activities', 'index.php?module=Contacts&view=ShowWidget&name=CalendarActivities', '', 2, NULL, NULL, NULL),
	(48, 4, 'DASHBOARDWIDGET', 'Overdue Activities', 'index.php?module=Contacts&view=ShowWidget&name=OverdueActivities', '', 3, NULL, NULL, NULL),
	(49, 7, 'DASHBOARDWIDGET', 'History', 'index.php?module=Leads&view=ShowWidget&name=History', '', 1, NULL, NULL, NULL),
	(50, 7, 'DASHBOARDWIDGET', 'Upcoming Activities', 'index.php?module=Leads&view=ShowWidget&name=CalendarActivities', '', 2, NULL, NULL, NULL),
	(51, 7, 'DASHBOARDWIDGET', 'Leads by Status', 'index.php?module=Leads&view=ShowWidget&name=LeadsByStatus', '', 4, NULL, NULL, NULL),
	(52, 7, 'DASHBOARDWIDGET', 'Leads by Source', 'index.php?module=Leads&view=ShowWidget&name=LeadsBySource', '', 5, NULL, NULL, NULL),
	(53, 7, 'DASHBOARDWIDGET', 'Leads by Industry', 'index.php?module=Leads&view=ShowWidget&name=LeadsByIndustry', '', 6, NULL, NULL, NULL),
	(54, 7, 'DASHBOARDWIDGET', 'Overdue Activities', 'index.php?module=Leads&view=ShowWidget&name=OverdueActivities', '', 7, NULL, NULL, NULL),
	(55, 13, 'DASHBOARDWIDGET', 'Tickets by Status', 'index.php?module=HelpDesk&view=ShowWidget&name=TicketsByStatus', '', 1, NULL, NULL, NULL),
	(56, 13, 'DASHBOARDWIDGET', 'Open Tickets', 'index.php?module=HelpDesk&view=ShowWidget&name=OpenTickets', '', 2, NULL, NULL, NULL),
	(57, 3, 'DASHBOARDWIDGET', 'History', 'index.php?module=Home&view=ShowWidget&name=History', '', 1, NULL, NULL, NULL),
	(58, 3, 'DASHBOARDWIDGET', 'Upcoming Activities', 'index.php?module=Home&view=ShowWidget&name=CalendarActivities', '', 2, NULL, NULL, NULL),
	(59, 3, 'DASHBOARDWIDGET', 'Funnel', 'index.php?module=Potentials&view=ShowWidget&name=GroupedBySalesStage', '', 3, NULL, NULL, NULL),
	(60, 3, 'DASHBOARDWIDGET', 'Potentials by Stage', 'index.php?module=Potentials&view=ShowWidget&name=GroupedBySalesPerson', '', 4, NULL, NULL, NULL),
	(61, 3, 'DASHBOARDWIDGET', 'Pipelined Amount', 'index.php?module=Potentials&view=ShowWidget&name=PipelinedAmountPerSalesPerson', '', 5, NULL, NULL, NULL),
	(62, 3, 'DASHBOARDWIDGET', 'Total Revenue', 'index.php?module=Potentials&view=ShowWidget&name=TotalRevenuePerSalesPerson', '', 6, NULL, NULL, NULL),
	(63, 3, 'DASHBOARDWIDGET', 'Top Potentials', 'index.php?module=Potentials&view=ShowWidget&name=TopPotentials', '', 7, NULL, NULL, NULL),
	(64, 3, 'DASHBOARDWIDGET', 'Leads by Status', 'index.php?module=Leads&view=ShowWidget&name=LeadsByStatus', '', 10, NULL, NULL, NULL),
	(65, 3, 'DASHBOARDWIDGET', 'Leads by Source', 'index.php?module=Leads&view=ShowWidget&name=LeadsBySource', '', 11, NULL, NULL, NULL),
	(66, 3, 'DASHBOARDWIDGET', 'Leads by Industry', 'index.php?module=Leads&view=ShowWidget&name=LeadsByIndustry', '', 12, NULL, NULL, NULL),
	(67, 3, 'DASHBOARDWIDGET', 'Overdue Activities', 'index.php?module=Home&view=ShowWidget&name=OverdueActivities', '', 13, NULL, NULL, NULL),
	(68, 3, 'DASHBOARDWIDGET', 'Tickets by Status', 'index.php?module=HelpDesk&view=ShowWidget&name=TicketsByStatus', '', 13, NULL, NULL, NULL),
	(69, 3, 'DASHBOARDWIDGET', 'Open Tickets', 'index.php?module=HelpDesk&view=ShowWidget&name=OpenTickets', '', 14, NULL, NULL, NULL),
	(70, 13, 'DETAILVIEWWIDGET', 'DetailViewBlockCommentWidget', 'block://ModComments:modules/ModComments/ModComments.php', '', 0, NULL, NULL, NULL),
	(71, 15, 'DETAILVIEWWIDGET', 'DetailViewBlockCommentWidget', 'block://ModComments:modules/ModComments/ModComments.php', '', 0, NULL, NULL, NULL),
	(100, 3, 'DASHBOARDWIDGET', 'Key Metrics', 'index.php?module=Home&view=ShowWidget&name=KeyMetrics', '', 0, NULL, NULL, NULL),
	(101, 3, 'DASHBOARDWIDGET', 'Mini List', 'index.php?module=Home&view=ShowWidget&name=MiniList', '', 0, NULL, NULL, NULL),
	(102, 3, 'DASHBOARDWIDGET', 'Tag Cloud', 'index.php?module=Home&view=ShowWidget&name=TagCloud', '', 0, NULL, NULL, NULL),
	(103, 2, 'DASHBOARDWIDGET', 'Funnel Amount', 'index.php?module=Potentials&view=ShowWidget&name=FunnelAmount', '', 10, NULL, NULL, NULL),
	(104, 3, 'DASHBOARDWIDGET', 'Funnel Amount', 'index.php?module=Potentials&view=ShowWidget&name=FunnelAmount', '', 10, NULL, NULL, NULL),
	(105, 3, 'DASHBOARDWIDGET', 'Notebook', 'index.php?module=Home&view=ShowWidget&name=Notebook', '', 0, NULL, NULL, NULL),
	(106, 49, 'HEADERSCRIPT', 'ExtensionStoreCommonHeaderScript', 'modules/ExtensionStore/ExtensionStore.js', '', 0, NULL, NULL, NULL);
/*!40000 ALTER TABLE `ncrm_links` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_links_seq
CREATE TABLE IF NOT EXISTS `ncrm_links_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_links_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_links_seq` DISABLE KEYS */;
INSERT INTO `ncrm_links_seq` (`id`) VALUES
	(106);
/*!40000 ALTER TABLE `ncrm_links_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_loginhistory
CREATE TABLE IF NOT EXISTS `ncrm_loginhistory` (
  `login_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) DEFAULT NULL,
  `user_ip` varchar(25) NOT NULL,
  `logout_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `login_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`login_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_loginhistory: ~25 rows (approximately)
/*!40000 ALTER TABLE `ncrm_loginhistory` DISABLE KEYS */;
INSERT INTO `ncrm_loginhistory` (`login_id`, `user_name`, `user_ip`, `logout_time`, `login_time`, `status`) VALUES
	(1, 'admin', '127.0.0.1', '2016-12-18 10:26:25', '2016-12-17 19:26:08', 'Signed off'),
	(2, 'admin', '127.0.0.1', '2016-12-18 10:27:45', '2016-12-18 10:27:39', 'Signed off'),
	(3, 'admin', '127.0.0.1', '2016-12-18 14:54:50', '2016-12-18 10:30:20', 'Signed off'),
	(4, 'admin', '127.0.0.1', '0000-00-00 00:00:00', '2016-12-18 15:01:01', 'Signed in'),
	(5, 'admin', '127.0.0.1', '0000-00-00 00:00:00', '2016-12-19 04:20:33', 'Signed in'),
	(6, 'admin', '127.0.0.1', '0000-00-00 00:00:00', '2016-12-26 06:45:07', 'Signed in'),
	(7, 'admin', '127.0.0.1', '2016-12-27 09:30:10', '2016-12-27 09:30:01', 'Signed off'),
	(8, 'admin', '127.0.0.1', '0000-00-00 00:00:00', '2016-12-27 09:43:00', 'Signed in'),
	(9, 'admin', '127.0.0.1', '2016-12-27 16:05:53', '2016-12-27 16:05:42', 'Signed off'),
	(10, 'admin', '127.0.0.1', '2016-12-27 16:08:35', '2016-12-27 16:08:12', 'Signed off'),
	(11, 'admin', '127.0.0.1', '2016-12-27 16:23:55', '2016-12-27 16:23:49', 'Signed off'),
	(12, 'admin', '127.0.0.1', '2016-12-27 16:40:41', '2016-12-27 16:40:18', 'Signed off'),
	(13, 'admin', '127.0.0.1', '2016-12-27 16:41:54', '2016-12-27 16:41:50', 'Signed off'),
	(14, 'admin', '127.0.0.1', '2017-01-04 04:13:57', '2017-01-04 03:10:58', 'Signed off'),
	(15, 'admin', '127.0.0.1', '0000-00-00 00:00:00', '2017-01-04 04:14:01', 'Signed in'),
	(16, 'admin', '127.0.0.1', '0000-00-00 00:00:00', '2017-01-05 04:50:45', 'Signed in'),
	(17, 'admin', '127.0.0.1', '2017-01-09 09:37:42', '2017-01-09 09:06:32', 'Signed off'),
	(18, 'admin', '127.0.0.1', '2017-01-10 15:31:47', '2017-01-09 09:37:46', 'Signed off'),
	(19, 'admin', '127.0.0.1', '2017-01-10 16:05:38', '2017-01-10 15:34:23', 'Signed off'),
	(20, 'admin', '127.0.0.1', '2017-01-10 16:24:06', '2017-01-10 16:14:00', 'Signed off'),
	(21, 'admin', '127.0.0.1', '0000-00-00 00:00:00', '2017-01-15 13:22:03', 'Signed in'),
	(22, 'admin', '127.0.0.1', '0000-00-00 00:00:00', '2017-10-26 15:03:11', 'Signed in'),
	(23, 'admin', '127.0.0.1', '0000-00-00 00:00:00', '2017-10-29 02:33:19', 'Signed in'),
	(24, 'admin', '172.18.14.242', '0000-00-00 00:00:00', '2017-10-29 02:35:48', 'Signed in'),
	(25, 'admin', '127.0.0.1', '2017-11-25 07:08:45', '2017-11-25 06:05:51', 'Signed off'),
	(26, 'admin', '127.0.0.1', '2017-11-25 14:40:39', '2017-11-25 07:08:48', 'Signed off'),
	(27, 'trungha', '127.0.0.1', '2017-11-25 14:42:27', '2017-11-25 14:40:43', 'Signed off'),
	(28, 'trungha', '127.0.0.1', '0000-00-00 00:00:00', '2017-11-25 14:42:33', 'Signed in'),
	(29, 'admin', '127.0.0.1', '0000-00-00 00:00:00', '2017-11-25 14:43:49', 'Signed in');
/*!40000 ALTER TABLE `ncrm_loginhistory` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_mailer_queue
CREATE TABLE IF NOT EXISTS `ncrm_mailer_queue` (
  `id` int(11) NOT NULL,
  `fromname` varchar(100) DEFAULT NULL,
  `fromemail` varchar(100) DEFAULT NULL,
  `mailer` varchar(10) DEFAULT NULL,
  `content_type` varchar(15) DEFAULT NULL,
  `subject` varchar(999) DEFAULT NULL,
  `body` text,
  `relcrmid` int(11) DEFAULT NULL,
  `failed` int(1) NOT NULL DEFAULT '0',
  `failreason` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_mailer_queue: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_mailer_queue` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_mailer_queue` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_mailer_queueattachments
CREATE TABLE IF NOT EXISTS `ncrm_mailer_queueattachments` (
  `id` int(11) DEFAULT NULL,
  `path` text,
  `name` varchar(100) DEFAULT NULL,
  `encoding` varchar(50) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_mailer_queueattachments: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_mailer_queueattachments` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_mailer_queueattachments` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_mailer_queueinfo
CREATE TABLE IF NOT EXISTS `ncrm_mailer_queueinfo` (
  `id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `type` varchar(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_mailer_queueinfo: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_mailer_queueinfo` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_mailer_queueinfo` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_mailmanager_mailattachments
CREATE TABLE IF NOT EXISTS `ncrm_mailmanager_mailattachments` (
  `userid` int(11) DEFAULT NULL,
  `muid` int(11) DEFAULT NULL,
  `aname` varchar(100) DEFAULT NULL,
  `lastsavedtime` int(11) DEFAULT NULL,
  `attachid` int(19) NOT NULL,
  `path` varchar(200) NOT NULL,
  `cid` varchar(50) DEFAULT NULL,
  KEY `userid_muid_idx` (`userid`,`muid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_mailmanager_mailattachments: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_mailmanager_mailattachments` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_mailmanager_mailattachments` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_mailmanager_mailrecord
CREATE TABLE IF NOT EXISTS `ncrm_mailmanager_mailrecord` (
  `userid` int(11) DEFAULT NULL,
  `mfrom` varchar(255) DEFAULT NULL,
  `mto` varchar(255) DEFAULT NULL,
  `mcc` varchar(500) DEFAULT NULL,
  `mbcc` varchar(500) DEFAULT NULL,
  `mdate` varchar(20) DEFAULT NULL,
  `msubject` varchar(500) DEFAULT NULL,
  `mbody` text,
  `mcharset` varchar(10) DEFAULT NULL,
  `misbodyhtml` int(1) DEFAULT NULL,
  `mplainmessage` int(1) DEFAULT NULL,
  `mhtmlmessage` int(1) DEFAULT NULL,
  `muniqueid` varchar(500) DEFAULT NULL,
  `mbodyparsed` int(1) DEFAULT NULL,
  `muid` int(11) DEFAULT NULL,
  `lastsavedtime` int(11) DEFAULT NULL,
  KEY `userid_lastsavedtime_idx` (`userid`,`lastsavedtime`),
  KEY `userid_muid_idx` (`userid`,`muid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_mailmanager_mailrecord: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_mailmanager_mailrecord` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_mailmanager_mailrecord` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_mailmanager_mailrel
CREATE TABLE IF NOT EXISTS `ncrm_mailmanager_mailrel` (
  `mailuid` varchar(999) DEFAULT NULL,
  `crmid` int(11) DEFAULT NULL,
  `emailid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_mailmanager_mailrel: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_mailmanager_mailrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_mailmanager_mailrel` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_mailscanner
CREATE TABLE IF NOT EXISTS `ncrm_mailscanner` (
  `scannerid` int(11) NOT NULL AUTO_INCREMENT,
  `scannername` varchar(30) DEFAULT NULL,
  `server` varchar(100) DEFAULT NULL,
  `protocol` varchar(10) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `ssltype` varchar(10) DEFAULT NULL,
  `sslmethod` varchar(30) DEFAULT NULL,
  `connecturl` varchar(255) DEFAULT NULL,
  `searchfor` varchar(10) DEFAULT NULL,
  `markas` varchar(10) DEFAULT NULL,
  `isvalid` int(1) DEFAULT NULL,
  `time_zone` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`scannerid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_mailscanner: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_mailscanner` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_mailscanner` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_mailscanner_actions
CREATE TABLE IF NOT EXISTS `ncrm_mailscanner_actions` (
  `actionid` int(11) NOT NULL AUTO_INCREMENT,
  `scannerid` int(11) DEFAULT NULL,
  `actiontype` varchar(10) DEFAULT NULL,
  `module` varchar(30) DEFAULT NULL,
  `lookup` varchar(30) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  PRIMARY KEY (`actionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_mailscanner_actions: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_mailscanner_actions` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_mailscanner_actions` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_mailscanner_folders
CREATE TABLE IF NOT EXISTS `ncrm_mailscanner_folders` (
  `folderid` int(11) NOT NULL AUTO_INCREMENT,
  `scannerid` int(11) DEFAULT NULL,
  `foldername` varchar(255) DEFAULT NULL,
  `lastscan` varchar(30) DEFAULT NULL,
  `rescan` int(1) DEFAULT NULL,
  `enabled` int(1) DEFAULT NULL,
  PRIMARY KEY (`folderid`),
  KEY `folderid_idx` (`folderid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_mailscanner_folders: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_mailscanner_folders` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_mailscanner_folders` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_mailscanner_ids
CREATE TABLE IF NOT EXISTS `ncrm_mailscanner_ids` (
  `scannerid` int(11) DEFAULT NULL,
  `messageid` varchar(512) DEFAULT NULL,
  `crmid` int(11) DEFAULT NULL,
  KEY `scanner_message_ids_idx` (`scannerid`,`messageid`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_mailscanner_ids: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_mailscanner_ids` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_mailscanner_ids` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_mailscanner_ruleactions
CREATE TABLE IF NOT EXISTS `ncrm_mailscanner_ruleactions` (
  `ruleid` int(11) DEFAULT NULL,
  `actionid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_mailscanner_ruleactions: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_mailscanner_ruleactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_mailscanner_ruleactions` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_mailscanner_rules
CREATE TABLE IF NOT EXISTS `ncrm_mailscanner_rules` (
  `ruleid` int(11) NOT NULL AUTO_INCREMENT,
  `scannerid` int(11) DEFAULT NULL,
  `fromaddress` varchar(255) DEFAULT NULL,
  `toaddress` varchar(255) DEFAULT NULL,
  `subjectop` varchar(20) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `bodyop` varchar(20) DEFAULT NULL,
  `body` varchar(255) DEFAULT NULL,
  `matchusing` varchar(5) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `assigned_to` int(10) DEFAULT NULL,
  `cc` varchar(255) DEFAULT NULL,
  `bcc` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ruleid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_mailscanner_rules: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_mailscanner_rules` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_mailscanner_rules` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_mail_accounts
CREATE TABLE IF NOT EXISTS `ncrm_mail_accounts` (
  `account_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `display_name` varchar(50) DEFAULT NULL,
  `mail_id` varchar(50) DEFAULT NULL,
  `account_name` varchar(50) DEFAULT NULL,
  `mail_protocol` varchar(20) DEFAULT NULL,
  `mail_username` varchar(50) NOT NULL,
  `mail_password` varchar(250) NOT NULL,
  `mail_servername` varchar(50) DEFAULT NULL,
  `box_refresh` int(10) DEFAULT NULL,
  `mails_per_page` int(10) DEFAULT NULL,
  `ssltype` varchar(50) DEFAULT NULL,
  `sslmeth` varchar(50) DEFAULT NULL,
  `int_mailer` int(1) DEFAULT '0',
  `status` varchar(10) DEFAULT NULL,
  `set_default` int(2) DEFAULT NULL,
  `sent_folder` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_mail_accounts: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_mail_accounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_mail_accounts` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_manufacturer
CREATE TABLE IF NOT EXISTS `ncrm_manufacturer` (
  `manufacturerid` int(19) NOT NULL AUTO_INCREMENT,
  `manufacturer` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  PRIMARY KEY (`manufacturerid`),
  UNIQUE KEY `manufacturer_manufacturer_idx` (`manufacturer`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_manufacturer: ~3 rows (approximately)
/*!40000 ALTER TABLE `ncrm_manufacturer` DISABLE KEYS */;
INSERT INTO `ncrm_manufacturer` (`manufacturerid`, `manufacturer`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(2, 'AltvetPet Inc.', 1, 124, 1),
	(3, 'LexPon Inc.', 1, 125, 2),
	(4, 'MetBeat Corp', 1, 126, 3);
/*!40000 ALTER TABLE `ncrm_manufacturer` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_manufacturer_seq
CREATE TABLE IF NOT EXISTS `ncrm_manufacturer_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_manufacturer_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_manufacturer_seq` DISABLE KEYS */;
INSERT INTO `ncrm_manufacturer_seq` (`id`) VALUES
	(4);
/*!40000 ALTER TABLE `ncrm_manufacturer_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_mobile_alerts
CREATE TABLE IF NOT EXISTS `ncrm_mobile_alerts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `handler_path` varchar(500) DEFAULT NULL,
  `handler_class` varchar(50) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `deleted` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_mobile_alerts: ~7 rows (approximately)
/*!40000 ALTER TABLE `ncrm_mobile_alerts` DISABLE KEYS */;
INSERT INTO `ncrm_mobile_alerts` (`id`, `handler_path`, `handler_class`, `sequence`, `deleted`) VALUES
	(1, 'modules/Mobile/api/ws/models/alerts/IdleTicketsOfMine.php', 'Mobile_WS_AlertModel_IdleTicketsOfMine', NULL, 0),
	(2, 'modules/Mobile/api/ws/models/alerts/NewTicketOfMine.php', 'Mobile_WS_AlertModel_NewTicketOfMine', NULL, 0),
	(3, 'modules/Mobile/api/ws/models/alerts/PendingTicketsOfMine.php', 'Mobile_WS_AlertModel_PendingTicketsOfMine', NULL, 0),
	(4, 'modules/Mobile/api/ws/models/alerts/PotentialsDueIn5Days.php', 'Mobile_WS_AlertModel_PotentialsDueIn5Days', NULL, 0),
	(5, 'modules/Mobile/api/ws/models/alerts/EventsOfMineToday.php', 'Mobile_WS_AlertModel_EventsOfMineToday', NULL, 0),
	(6, 'modules/Mobile/api/ws/models/alerts/ProjectTasksOfMine.php', 'Mobile_WS_AlertModel_ProjectTasksOfMine', NULL, 0),
	(7, 'modules/Mobile/api/ws/models/alerts/Projects.php', 'Mobile_WS_AlertModel_Projects', NULL, 0);
/*!40000 ALTER TABLE `ncrm_mobile_alerts` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_modcomments
CREATE TABLE IF NOT EXISTS `ncrm_modcomments` (
  `modcommentsid` int(11) DEFAULT NULL,
  `commentcontent` text,
  `related_to` int(19) DEFAULT NULL,
  `parent_comments` varchar(100) DEFAULT NULL,
  `customer` varchar(100) DEFAULT NULL,
  `userid` varchar(100) DEFAULT NULL,
  `reasontoedit` varchar(100) DEFAULT NULL,
  KEY `relatedto_idx` (`related_to`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_modcomments: ~5 rows (approximately)
/*!40000 ALTER TABLE `ncrm_modcomments` DISABLE KEYS */;
INSERT INTO `ncrm_modcomments` (`modcommentsid`, `commentcontent`, `related_to`, `parent_comments`, `customer`, `userid`, `reasontoedit`) VALUES
	(15, 'test 1', 13, '', '', '1', ''),
	(16, 'test 2', 13, '', '', '1', ''),
	(17, 'test 3', 13, '', '', '1', ''),
	(18, 'test 4', 13, '', '', '1', ''),
	(19, 'test 1', 11, '', '', '1', '');
/*!40000 ALTER TABLE `ncrm_modcomments` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_modcommentscf
CREATE TABLE IF NOT EXISTS `ncrm_modcommentscf` (
  `modcommentsid` int(11) NOT NULL,
  PRIMARY KEY (`modcommentsid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_modcommentscf: ~5 rows (approximately)
/*!40000 ALTER TABLE `ncrm_modcommentscf` DISABLE KEYS */;
INSERT INTO `ncrm_modcommentscf` (`modcommentsid`) VALUES
	(15),
	(16),
	(17),
	(18),
	(19);
/*!40000 ALTER TABLE `ncrm_modcommentscf` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_modentity_num
CREATE TABLE IF NOT EXISTS `ncrm_modentity_num` (
  `num_id` int(19) NOT NULL,
  `semodule` varchar(50) NOT NULL,
  `prefix` varchar(50) NOT NULL DEFAULT '',
  `start_id` varchar(50) NOT NULL,
  `cur_id` varchar(50) NOT NULL,
  `active` varchar(2) NOT NULL,
  PRIMARY KEY (`num_id`),
  UNIQUE KEY `num_idx` (`num_id`),
  KEY `semodule_active_idx` (`semodule`,`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_modentity_num: ~21 rows (approximately)
/*!40000 ALTER TABLE `ncrm_modentity_num` DISABLE KEYS */;
INSERT INTO `ncrm_modentity_num` (`num_id`, `semodule`, `prefix`, `start_id`, `cur_id`, `active`) VALUES
	(1, 'Leads', 'LEA', '1', '2', '1'),
	(2, 'Accounts', 'ACC', '1', '2', '1'),
	(3, 'Campaigns', 'CAM', '1', '2', '1'),
	(4, 'Contacts', 'CON', '1', '5', '1'),
	(5, 'Potentials', 'POT', '1', '1', '1'),
	(6, 'HelpDesk', 'TT', '1', '11', '1'),
	(7, 'Quotes', 'QUO', '1', '1', '1'),
	(8, 'SalesOrder', 'SO', '1', '1', '1'),
	(9, 'PurchaseOrder', 'PO', '1', '1', '1'),
	(10, 'Invoice', 'INV', '1', '1', '1'),
	(11, 'Products', 'PRO', '1', '2', '1'),
	(12, 'Vendors', 'VEN', '1', '1', '1'),
	(13, 'PriceBooks', 'PB', '1', '1', '1'),
	(14, 'Faq', 'FAQ', '1', '1', '1'),
	(15, 'Documents', 'DOC', '1', '2', '1'),
	(16, 'ServiceContracts', 'SERCON', '1', '1', '1'),
	(17, 'Services', 'SER', '1', '1', '1'),
	(18, 'Assets', 'ASSET', '1', '1', '1'),
	(19, 'ProjectMilestone', 'PM', '1', '1', '1'),
	(20, 'ProjectTask', 'PT', '1', '1', '1'),
	(21, 'Project', 'PROJ', '1', '1', '1');
/*!40000 ALTER TABLE `ncrm_modentity_num` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_modentity_num_seq
CREATE TABLE IF NOT EXISTS `ncrm_modentity_num_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_modentity_num_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_modentity_num_seq` DISABLE KEYS */;
INSERT INTO `ncrm_modentity_num_seq` (`id`) VALUES
	(21);
/*!40000 ALTER TABLE `ncrm_modentity_num_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_modtracker_basic
CREATE TABLE IF NOT EXISTS `ncrm_modtracker_basic` (
  `id` int(20) NOT NULL,
  `crmid` int(20) DEFAULT NULL,
  `module` varchar(50) DEFAULT NULL,
  `whodid` int(20) DEFAULT NULL,
  `changedon` datetime DEFAULT NULL,
  `status` int(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `crmidx` (`crmid`),
  KEY `idx` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_modtracker_basic: ~27 rows (approximately)
/*!40000 ALTER TABLE `ncrm_modtracker_basic` DISABLE KEYS */;
INSERT INTO `ncrm_modtracker_basic` (`id`, `crmid`, `module`, `whodid`, `changedon`, `status`) VALUES
	(1, 2, 'HelpDesk', 1, '2017-01-04 06:27:16', 2),
	(2, 3, 'HelpDesk', 1, '2017-01-04 06:27:29', 2),
	(3, 4, 'HelpDesk', 1, '2017-01-04 06:27:35', 2),
	(4, 5, 'HelpDesk', 1, '2017-01-04 06:27:40', 2),
	(5, 6, 'HelpDesk', 1, '2017-01-04 06:27:45', 2),
	(6, 7, 'HelpDesk', 1, '2017-01-04 06:27:50', 2),
	(7, 8, 'HelpDesk', 1, '2017-01-04 06:27:55', 2),
	(8, 9, 'HelpDesk', 1, '2017-01-04 06:28:01', 2),
	(9, 10, 'HelpDesk', 1, '2017-01-04 06:28:06', 2),
	(10, 11, 'HelpDesk', 1, '2017-01-04 06:28:12', 2),
	(11, 13, 'Contacts', 1, '2017-01-09 16:04:45', 2),
	(12, 14, 'Accounts', 1, '2017-01-09 17:30:15', 2),
	(13, 14, 'Accounts', 1, '2017-01-09 17:30:32', 0),
	(14, 15, 'ModComments', 1, '2017-01-10 07:06:29', 2),
	(15, 16, 'ModComments', 1, '2017-01-10 07:06:32', 2),
	(16, 17, 'ModComments', 1, '2017-01-10 07:08:42', 2),
	(17, 18, 'ModComments', 1, '2017-01-10 07:09:07', 2),
	(18, 19, 'ModComments', 1, '2017-01-10 08:52:01', 2),
	(19, 20, 'Events', 1, '2017-01-10 09:54:45', 2),
	(20, 20, 'Events', 1, '2017-01-10 09:55:41', 0),
	(21, 22, 'Products', 1, '2017-10-26 20:15:48', 2),
	(22, 22, 'Products', 1, '2017-10-26 20:15:57', 0),
	(23, 23, 'Leads', 1, '2017-10-29 02:50:11', 2),
	(24, 24, 'Contacts', 1, '2017-10-29 02:50:32', 2),
	(25, 25, 'Campaigns', 1, '2017-10-29 02:59:27', 2),
	(26, 25, 'Campaigns', 1, '2017-10-29 02:59:48', 0),
	(27, 26, 'Documents', 1, '2017-10-29 03:59:21', 2),
	(28, 28, 'Contacts', 1, '2017-11-25 06:56:09', 2),
	(29, 28, 'Contacts', 1, '2017-11-25 09:12:18', 1),
	(30, 24, 'Contacts', 1, '2017-11-25 09:12:18', 1),
	(31, 13, 'Contacts', 1, '2017-11-25 09:12:18', 1),
	(32, 29, 'Contacts', 1, '2017-11-25 09:12:28', 2);
/*!40000 ALTER TABLE `ncrm_modtracker_basic` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_modtracker_basic_seq
CREATE TABLE IF NOT EXISTS `ncrm_modtracker_basic_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_modtracker_basic_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_modtracker_basic_seq` DISABLE KEYS */;
INSERT INTO `ncrm_modtracker_basic_seq` (`id`) VALUES
	(32);
/*!40000 ALTER TABLE `ncrm_modtracker_basic_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_modtracker_detail
CREATE TABLE IF NOT EXISTS `ncrm_modtracker_detail` (
  `id` int(11) DEFAULT NULL,
  `fieldname` varchar(100) DEFAULT NULL,
  `prevalue` text,
  `postvalue` text,
  KEY `idx` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_modtracker_detail: ~237 rows (approximately)
/*!40000 ALTER TABLE `ncrm_modtracker_detail` DISABLE KEYS */;
INSERT INTO `ncrm_modtracker_detail` (`id`, `fieldname`, `prevalue`, `postvalue`) VALUES
	(1, 'ticket_no', NULL, 'TT1'),
	(1, 'assigned_user_id', NULL, '1'),
	(1, 'ticketstatus', NULL, 'In Progress'),
	(1, 'hours', NULL, '0.00000000'),
	(1, 'days', NULL, '0.00000000'),
	(1, 'createdtime', NULL, '2017-01-04 06:27:16'),
	(1, 'modifiedby', NULL, '1'),
	(1, 'ticket_title', NULL, 'ticktet 1'),
	(1, 'record_id', NULL, '2'),
	(1, 'record_module', NULL, 'HelpDesk'),
	(2, 'ticket_no', NULL, 'TT2'),
	(2, 'assigned_user_id', NULL, '1'),
	(2, 'ticketstatus', NULL, 'In Progress'),
	(2, 'hours', NULL, '0.00000000'),
	(2, 'days', NULL, '0.00000000'),
	(2, 'createdtime', NULL, '2017-01-04 06:27:29'),
	(2, 'modifiedby', NULL, '1'),
	(2, 'ticket_title', NULL, 'ticktet 2'),
	(2, 'record_id', NULL, '3'),
	(2, 'record_module', NULL, 'HelpDesk'),
	(3, 'ticket_no', NULL, 'TT3'),
	(3, 'assigned_user_id', NULL, '1'),
	(3, 'ticketstatus', NULL, 'In Progress'),
	(3, 'hours', NULL, '0.00000000'),
	(3, 'days', NULL, '0.00000000'),
	(3, 'createdtime', NULL, '2017-01-04 06:27:35'),
	(3, 'modifiedby', NULL, '1'),
	(3, 'ticket_title', NULL, 'ticktet 3'),
	(3, 'record_id', NULL, '4'),
	(3, 'record_module', NULL, 'HelpDesk'),
	(4, 'ticket_no', NULL, 'TT4'),
	(4, 'assigned_user_id', NULL, '1'),
	(4, 'ticketstatus', NULL, 'In Progress'),
	(4, 'hours', NULL, '0.00000000'),
	(4, 'days', NULL, '0.00000000'),
	(4, 'createdtime', NULL, '2017-01-04 06:27:40'),
	(4, 'modifiedby', NULL, '1'),
	(4, 'ticket_title', NULL, 'ticktet 4'),
	(4, 'record_id', NULL, '5'),
	(4, 'record_module', NULL, 'HelpDesk'),
	(5, 'ticket_no', NULL, 'TT5'),
	(5, 'assigned_user_id', NULL, '1'),
	(5, 'ticketstatus', NULL, 'In Progress'),
	(5, 'hours', NULL, '0.00000000'),
	(5, 'days', NULL, '0.00000000'),
	(5, 'createdtime', NULL, '2017-01-04 06:27:45'),
	(5, 'modifiedby', NULL, '1'),
	(5, 'ticket_title', NULL, 'ticktet 5'),
	(5, 'record_id', NULL, '6'),
	(5, 'record_module', NULL, 'HelpDesk'),
	(6, 'ticket_no', NULL, 'TT6'),
	(6, 'assigned_user_id', NULL, '1'),
	(6, 'ticketstatus', NULL, 'In Progress'),
	(6, 'hours', NULL, '0.00000000'),
	(6, 'days', NULL, '0.00000000'),
	(6, 'createdtime', NULL, '2017-01-04 06:27:50'),
	(6, 'modifiedby', NULL, '1'),
	(6, 'ticket_title', NULL, 'ticktet 6'),
	(6, 'record_id', NULL, '7'),
	(6, 'record_module', NULL, 'HelpDesk'),
	(7, 'ticket_no', NULL, 'TT7'),
	(7, 'assigned_user_id', NULL, '1'),
	(7, 'ticketstatus', NULL, 'In Progress'),
	(7, 'hours', NULL, '0.00000000'),
	(7, 'days', NULL, '0.00000000'),
	(7, 'createdtime', NULL, '2017-01-04 06:27:55'),
	(7, 'modifiedby', NULL, '1'),
	(7, 'ticket_title', NULL, 'ticktet 7'),
	(7, 'record_id', NULL, '8'),
	(7, 'record_module', NULL, 'HelpDesk'),
	(8, 'ticket_no', NULL, 'TT8'),
	(8, 'assigned_user_id', NULL, '1'),
	(8, 'ticketstatus', NULL, 'In Progress'),
	(8, 'hours', NULL, '0.00000000'),
	(8, 'days', NULL, '0.00000000'),
	(8, 'createdtime', NULL, '2017-01-04 06:28:01'),
	(8, 'modifiedby', NULL, '1'),
	(8, 'ticket_title', NULL, 'ticktet 8'),
	(8, 'record_id', NULL, '9'),
	(8, 'record_module', NULL, 'HelpDesk'),
	(9, 'ticket_no', NULL, 'TT9'),
	(9, 'assigned_user_id', NULL, '1'),
	(9, 'ticketstatus', NULL, 'In Progress'),
	(9, 'hours', NULL, '0.00000000'),
	(9, 'days', NULL, '0.00000000'),
	(9, 'createdtime', NULL, '2017-01-04 06:28:06'),
	(9, 'modifiedby', NULL, '1'),
	(9, 'ticket_title', NULL, 'ticktet 9'),
	(9, 'record_id', NULL, '10'),
	(9, 'record_module', NULL, 'HelpDesk'),
	(10, 'ticket_no', NULL, 'TT10'),
	(10, 'assigned_user_id', NULL, '1'),
	(10, 'ticketstatus', NULL, 'In Progress'),
	(10, 'hours', NULL, '0.00000000'),
	(10, 'days', NULL, '0.00000000'),
	(10, 'createdtime', NULL, '2017-01-04 06:28:12'),
	(10, 'modifiedby', NULL, '1'),
	(10, 'ticket_title', NULL, 'ticktet 10'),
	(10, 'record_id', NULL, '11'),
	(10, 'record_module', NULL, 'HelpDesk'),
	(11, 'firstname', NULL, 'tick'),
	(11, 'contact_no', NULL, 'CON1'),
	(11, 'lastname', NULL, 'ct'),
	(11, 'assigned_user_id', NULL, '1'),
	(11, 'createdtime', NULL, '2017-01-09 16:04:45'),
	(11, 'modifiedby', NULL, '1'),
	(11, 'support_start_date', NULL, '2017-01-09'),
	(11, 'support_end_date', NULL, '2018-01-09'),
	(11, 'record_id', NULL, '13'),
	(11, 'record_module', NULL, 'Contacts'),
	(12, 'accountname', NULL, 'accc 1'),
	(12, 'account_no', NULL, 'ACC1'),
	(12, 'phone', NULL, 'accc 1'),
	(12, 'website', NULL, 'http://google.com'),
	(12, 'tickersymbol', NULL, 'accc 1'),
	(12, 'annual_revenue', NULL, '0.00000000'),
	(12, 'assigned_user_id', NULL, '1'),
	(12, 'createdtime', NULL, '2017-01-09 17:30:15'),
	(12, 'modifiedby', NULL, '1'),
	(12, 'record_id', NULL, '14'),
	(12, 'record_module', NULL, 'Accounts'),
	(13, 'accountname', 'accc 1', 'accc 1 accc 1 accc 1 accc 1 accc 1'),
	(14, 'commentcontent', NULL, 'test 1'),
	(14, 'assigned_user_id', NULL, '1'),
	(14, 'createdtime', NULL, '2017-01-10 07:06:29'),
	(14, 'related_to', NULL, '13'),
	(14, 'creator', NULL, '1'),
	(14, 'userid', NULL, '1'),
	(14, 'record_id', NULL, '15'),
	(14, 'record_module', NULL, 'ModComments'),
	(15, 'commentcontent', NULL, 'test 2'),
	(15, 'assigned_user_id', NULL, '1'),
	(15, 'createdtime', NULL, '2017-01-10 07:06:32'),
	(15, 'related_to', NULL, '13'),
	(15, 'creator', NULL, '1'),
	(15, 'userid', NULL, '1'),
	(15, 'record_id', NULL, '16'),
	(15, 'record_module', NULL, 'ModComments'),
	(16, 'commentcontent', NULL, 'test 3'),
	(16, 'assigned_user_id', NULL, '1'),
	(16, 'createdtime', NULL, '2017-01-10 07:08:42'),
	(16, 'related_to', NULL, '13'),
	(16, 'creator', NULL, '1'),
	(16, 'userid', NULL, '1'),
	(16, 'record_id', NULL, '17'),
	(16, 'record_module', NULL, 'ModComments'),
	(17, 'commentcontent', NULL, 'test 4'),
	(17, 'assigned_user_id', NULL, '1'),
	(17, 'createdtime', NULL, '2017-01-10 07:09:07'),
	(17, 'related_to', NULL, '13'),
	(17, 'creator', NULL, '1'),
	(17, 'userid', NULL, '1'),
	(17, 'record_id', NULL, '18'),
	(17, 'record_module', NULL, 'ModComments'),
	(18, 'commentcontent', NULL, 'test 1'),
	(18, 'assigned_user_id', NULL, '1'),
	(18, 'createdtime', NULL, '2017-01-10 08:52:01'),
	(18, 'related_to', NULL, '11'),
	(18, 'creator', NULL, '1'),
	(18, 'userid', NULL, '1'),
	(18, 'record_id', NULL, '19'),
	(18, 'record_module', NULL, 'ModComments'),
	(19, 'subject', NULL, 'Event today 1'),
	(19, 'assigned_user_id', NULL, '1'),
	(19, 'date_start', NULL, '2017-01-10'),
	(19, 'time_start', NULL, '09:54:00'),
	(19, 'time_end', NULL, '09:59:00'),
	(19, 'due_date', NULL, '2017-01-10'),
	(19, 'eventstatus', NULL, 'Planned'),
	(19, 'createdtime', NULL, '2017-01-10 09:54:45'),
	(19, 'activitytype', NULL, 'Call'),
	(19, 'visibility', NULL, 'Public'),
	(19, 'duration_minutes', NULL, '5'),
	(19, 'modifiedby', NULL, '1'),
	(19, 'created_user_id', NULL, '1'),
	(19, 'record_id', NULL, '20'),
	(19, 'record_module', NULL, 'Events'),
	(20, 'time_end', '09:59:00', '10:14:00'),
	(21, 'productname', NULL, 'p1'),
	(21, 'product_no', NULL, 'PRO1'),
	(21, 'discontinued', NULL, '1'),
	(21, 'createdtime', NULL, '2017-10-26 20:15:48'),
	(21, 'modifiedby', NULL, '1'),
	(21, 'unit_price', NULL, '0.00000000'),
	(21, 'commissionrate', NULL, '0.000'),
	(21, 'qty_per_unit', NULL, '0.00'),
	(21, 'qtyinstock', NULL, '2.000'),
	(21, 'assigned_user_id', NULL, '1'),
	(21, 'record_id', NULL, '22'),
	(21, 'record_module', NULL, 'Products'),
	(22, 'unit_price', '0.00000000', '1000.00000000'),
	(23, 'firstname', NULL, 'lead 1'),
	(23, 'lead_no', NULL, 'LEA1'),
	(23, 'lastname', NULL, 'lead 1'),
	(23, 'annualrevenue', NULL, '0.00000000'),
	(23, 'assigned_user_id', NULL, '1'),
	(23, 'createdtime', NULL, '2017-10-29 02:50:11'),
	(23, 'modifiedby', NULL, '1'),
	(23, 'record_id', NULL, '23'),
	(23, 'record_module', NULL, 'Leads'),
	(24, 'firstname', NULL, 'lead 1'),
	(24, 'contact_no', NULL, 'CON2'),
	(24, 'lastname', NULL, 'lead 1'),
	(24, 'assigned_user_id', NULL, '1'),
	(24, 'createdtime', NULL, '2017-10-29 02:50:32'),
	(24, 'modifiedby', NULL, '1'),
	(24, 'record_id', NULL, '24'),
	(24, 'record_module', NULL, 'Contacts'),
	(25, 'campaignname', NULL, 'fb ad q1'),
	(25, 'campaign_no', NULL, 'CAM1'),
	(25, 'closingdate', NULL, '2017-10-17'),
	(25, 'assigned_user_id', NULL, '1'),
	(25, 'createdtime', NULL, '2017-10-29 02:59:27'),
	(25, 'modifiedby', NULL, '1'),
	(25, 'expectedresponse', NULL, 'Excellent'),
	(25, 'expectedrevenue', NULL, '0.00000000'),
	(25, 'budgetcost', NULL, '120.00000000'),
	(25, 'actualcost', NULL, '0.00000000'),
	(25, 'expectedroi', NULL, '0.00000000'),
	(25, 'actualroi', NULL, '0.00000000'),
	(25, 'record_id', NULL, '25'),
	(25, 'record_module', NULL, 'Campaigns'),
	(26, 'campaignstatus', '', 'Active'),
	(27, 'notes_title', NULL, 'Tieu de 1'),
	(27, 'createdtime', NULL, '2017-10-29 03:59:21'),
	(27, 'filename', NULL, '2017-07-27_111637.png'),
	(27, 'assigned_user_id', NULL, '1'),
	(27, 'notecontent', NULL, 'd'),
	(27, 'filetype', NULL, 'image/png'),
	(27, 'filesize', NULL, '126723'),
	(27, 'filelocationtype', NULL, 'I'),
	(27, 'filestatus', NULL, '1'),
	(27, 'folderid', NULL, '2'),
	(27, 'note_no', NULL, 'DOC1'),
	(27, 'modifiedby', NULL, '1'),
	(27, 'record_id', NULL, '26'),
	(27, 'record_module', NULL, 'Documents'),
	(28, 'contact_no', NULL, 'CON3'),
	(28, 'lastname', NULL, 'ct1'),
	(28, 'assigned_user_id', NULL, '1'),
	(28, 'createdtime', NULL, '2017-11-25 06:56:09'),
	(28, 'modifiedby', NULL, '1'),
	(28, 'record_id', NULL, '28'),
	(28, 'record_module', NULL, 'Contacts'),
	(32, 'contact_no', NULL, 'CON4'),
	(32, 'lastname', NULL, 'Nam Nguyen'),
	(32, 'assigned_user_id', NULL, '1'),
	(32, 'createdtime', NULL, '2017-11-25 09:12:28'),
	(32, 'modifiedby', NULL, '1'),
	(32, 'cf_751', NULL, 'GO CRM'),
	(32, 'record_id', NULL, '29'),
	(32, 'record_module', NULL, 'Contacts');
/*!40000 ALTER TABLE `ncrm_modtracker_detail` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_modtracker_relations
CREATE TABLE IF NOT EXISTS `ncrm_modtracker_relations` (
  `id` int(19) NOT NULL,
  `targetmodule` varchar(100) NOT NULL,
  `targetid` int(19) NOT NULL,
  `changedon` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_modtracker_relations: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_modtracker_relations` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_modtracker_relations` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_modtracker_tabs
CREATE TABLE IF NOT EXISTS `ncrm_modtracker_tabs` (
  `tabid` int(11) NOT NULL,
  `visible` int(11) DEFAULT '0',
  PRIMARY KEY (`tabid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_modtracker_tabs: ~28 rows (approximately)
/*!40000 ALTER TABLE `ncrm_modtracker_tabs` DISABLE KEYS */;
INSERT INTO `ncrm_modtracker_tabs` (`tabid`, `visible`) VALUES
	(2, 1),
	(4, 1),
	(6, 1),
	(7, 1),
	(8, 1),
	(9, 1),
	(10, 1),
	(13, 1),
	(14, 1),
	(15, 1),
	(16, 1),
	(18, 1),
	(19, 1),
	(20, 1),
	(21, 1),
	(22, 1),
	(23, 1),
	(26, 1),
	(28, 1),
	(34, 1),
	(35, 1),
	(36, 1),
	(38, 1),
	(42, 1),
	(43, 1),
	(44, 1),
	(45, 1),
	(47, 1);
/*!40000 ALTER TABLE `ncrm_modtracker_tabs` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_module_dashboard_widgets
CREATE TABLE IF NOT EXISTS `ncrm_module_dashboard_widgets` (
  `id` int(19) NOT NULL AUTO_INCREMENT,
  `linkid` int(19) DEFAULT NULL,
  `userid` int(19) DEFAULT NULL,
  `filterid` int(19) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `data` text,
  `position` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_module_dashboard_widgets: ~6 rows (approximately)
/*!40000 ALTER TABLE `ncrm_module_dashboard_widgets` DISABLE KEYS */;
INSERT INTO `ncrm_module_dashboard_widgets` (`id`, `linkid`, `userid`, `filterid`, `title`, `data`, `position`) VALUES
	(1, 64, 1, 0, '0', 'false', '{"row":"1","col":"1"}'),
	(2, 58, 1, 0, '0', 'false', '{"row":"1","col":"1"}'),
	(3, 65, 1, 0, '0', 'false', '{"row":"1","col":"5"}'),
	(4, 59, 1, 0, '0', 'false', '{"row":"2","col":"1"}'),
	(5, 56, 1, 0, '0', 'false', NULL),
	(6, 55, 1, 0, '0', 'false', NULL);
/*!40000 ALTER TABLE `ncrm_module_dashboard_widgets` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_notebook_contents
CREATE TABLE IF NOT EXISTS `ncrm_notebook_contents` (
  `userid` int(19) NOT NULL,
  `notebookid` int(19) NOT NULL,
  `contents` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_notebook_contents: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_notebook_contents` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_notebook_contents` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_notes
CREATE TABLE IF NOT EXISTS `ncrm_notes` (
  `notesid` int(19) NOT NULL DEFAULT '0',
  `note_no` varchar(100) NOT NULL,
  `title` varchar(50) NOT NULL,
  `filename` varchar(200) DEFAULT NULL,
  `notecontent` text,
  `folderid` int(19) NOT NULL DEFAULT '1',
  `filetype` varchar(50) DEFAULT NULL,
  `filelocationtype` varchar(5) DEFAULT NULL,
  `filedownloadcount` int(19) DEFAULT NULL,
  `filestatus` int(19) DEFAULT NULL,
  `filesize` int(19) NOT NULL DEFAULT '0',
  `fileversion` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`notesid`),
  KEY `notes_title_idx` (`title`),
  KEY `notes_notesid_idx` (`notesid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_notes: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_notes` DISABLE KEYS */;
INSERT INTO `ncrm_notes` (`notesid`, `note_no`, `title`, `filename`, `notecontent`, `folderid`, `filetype`, `filelocationtype`, `filedownloadcount`, `filestatus`, `filesize`, `fileversion`) VALUES
	(26, 'DOC1', 'Tieu de 1', '2017-07-27_111637.png', 'd', 2, 'image/png', 'I', NULL, 1, 126723, '');
/*!40000 ALTER TABLE `ncrm_notes` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_notescf
CREATE TABLE IF NOT EXISTS `ncrm_notescf` (
  `notesid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`notesid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_notescf: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_notescf` DISABLE KEYS */;
INSERT INTO `ncrm_notescf` (`notesid`) VALUES
	(26);
/*!40000 ALTER TABLE `ncrm_notescf` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_notificationscheduler
CREATE TABLE IF NOT EXISTS `ncrm_notificationscheduler` (
  `schedulednotificationid` int(19) NOT NULL AUTO_INCREMENT,
  `schedulednotificationname` varchar(200) DEFAULT NULL,
  `active` int(1) DEFAULT NULL,
  `notificationsubject` varchar(200) DEFAULT NULL,
  `notificationbody` text,
  `label` varchar(50) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`schedulednotificationid`),
  UNIQUE KEY `notificationscheduler_schedulednotificationname_idx` (`schedulednotificationname`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_notificationscheduler: ~8 rows (approximately)
/*!40000 ALTER TABLE `ncrm_notificationscheduler` DISABLE KEYS */;
INSERT INTO `ncrm_notificationscheduler` (`schedulednotificationid`, `schedulednotificationname`, `active`, `notificationsubject`, `notificationbody`, `label`, `type`) VALUES
	(1, 'LBL_TASK_NOTIFICATION_DESCRITPION', 1, 'Task Delay Notification', 'Tasks delayed beyond 24 hrs ', 'LBL_TASK_NOTIFICATION', NULL),
	(2, 'LBL_BIG_DEAL_DESCRIPTION', 1, 'Big Deal notification', 'Success! A big deal has been won! ', 'LBL_BIG_DEAL', NULL),
	(3, 'LBL_TICKETS_DESCRIPTION', 1, 'Pending Tickets notification', 'Ticket pending please ', 'LBL_PENDING_TICKETS', NULL),
	(4, 'LBL_MANY_TICKETS_DESCRIPTION', 1, 'Too many tickets Notification', 'Too many tickets pending against this entity ', 'LBL_MANY_TICKETS', NULL),
	(5, 'LBL_START_DESCRIPTION', 1, 'Support Start Notification', '10', 'LBL_START_NOTIFICATION', 'select'),
	(6, 'LBL_SUPPORT_DESCRIPTION', 1, 'Support ending please', '11', 'LBL_SUPPORT_NOTICIATION', 'select'),
	(7, 'LBL_SUPPORT_DESCRIPTION_MONTH', 1, 'Support ending please', '12', 'LBL_SUPPORT_NOTICIATION_MONTH', 'select'),
	(8, 'LBL_ACTIVITY_REMINDER_DESCRIPTION', 1, 'Activity Reminder Notification', 'This is a reminder notification for the Activity', 'LBL_ACTIVITY_NOTIFICATION', NULL);
/*!40000 ALTER TABLE `ncrm_notificationscheduler` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_notificationscheduler_seq
CREATE TABLE IF NOT EXISTS `ncrm_notificationscheduler_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_notificationscheduler_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_notificationscheduler_seq` DISABLE KEYS */;
INSERT INTO `ncrm_notificationscheduler_seq` (`id`) VALUES
	(8);
/*!40000 ALTER TABLE `ncrm_notificationscheduler_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_no_of_currency_decimals
CREATE TABLE IF NOT EXISTS `ncrm_no_of_currency_decimals` (
  `no_of_currency_decimalsid` int(11) NOT NULL AUTO_INCREMENT,
  `no_of_currency_decimals` varchar(200) NOT NULL,
  `sortorderid` int(11) DEFAULT NULL,
  `presence` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`no_of_currency_decimalsid`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_no_of_currency_decimals: ~6 rows (approximately)
/*!40000 ALTER TABLE `ncrm_no_of_currency_decimals` DISABLE KEYS */;
INSERT INTO `ncrm_no_of_currency_decimals` (`no_of_currency_decimalsid`, `no_of_currency_decimals`, `sortorderid`, `presence`) VALUES
	(2, '2', 2, 1),
	(3, '3', 3, 1),
	(4, '4', 4, 1),
	(5, '5', 5, 1),
	(6, '0', 0, 1),
	(7, '1', 1, 1);
/*!40000 ALTER TABLE `ncrm_no_of_currency_decimals` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_no_of_currency_decimals_seq
CREATE TABLE IF NOT EXISTS `ncrm_no_of_currency_decimals_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_no_of_currency_decimals_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_no_of_currency_decimals_seq` DISABLE KEYS */;
INSERT INTO `ncrm_no_of_currency_decimals_seq` (`id`) VALUES
	(7);
/*!40000 ALTER TABLE `ncrm_no_of_currency_decimals_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_opportunitystage
CREATE TABLE IF NOT EXISTS `ncrm_opportunitystage` (
  `potstageid` int(19) NOT NULL AUTO_INCREMENT,
  `stage` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  `probability` decimal(3,2) DEFAULT '0.00',
  PRIMARY KEY (`potstageid`),
  UNIQUE KEY `opportunitystage_stage_idx` (`stage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_opportunitystage: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_opportunitystage` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_opportunitystage` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_opportunity_type
CREATE TABLE IF NOT EXISTS `ncrm_opportunity_type` (
  `opptypeid` int(19) NOT NULL AUTO_INCREMENT,
  `opportunity_type` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  PRIMARY KEY (`opptypeid`),
  UNIQUE KEY `opportunity_type_opportunity_type_idx` (`opportunity_type`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_opportunity_type: ~2 rows (approximately)
/*!40000 ALTER TABLE `ncrm_opportunity_type` DISABLE KEYS */;
INSERT INTO `ncrm_opportunity_type` (`opptypeid`, `opportunity_type`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(2, 'Existing Business', 1, 128, 1),
	(3, 'New Business', 1, 129, 2);
/*!40000 ALTER TABLE `ncrm_opportunity_type` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_opportunity_type_seq
CREATE TABLE IF NOT EXISTS `ncrm_opportunity_type_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_opportunity_type_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_opportunity_type_seq` DISABLE KEYS */;
INSERT INTO `ncrm_opportunity_type_seq` (`id`) VALUES
	(3);
/*!40000 ALTER TABLE `ncrm_opportunity_type_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_organizationdetails
CREATE TABLE IF NOT EXISTS `ncrm_organizationdetails` (
  `organization_id` int(11) NOT NULL,
  `organizationname` varchar(60) DEFAULT NULL,
  `address` varchar(150) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `code` varchar(30) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `fax` varchar(30) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `logoname` varchar(50) DEFAULT NULL,
  `logo` text,
  `vatid` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`organization_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_organizationdetails: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_organizationdetails` DISABLE KEYS */;
INSERT INTO `ncrm_organizationdetails` (`organization_id`, `organizationname`, `address`, `city`, `state`, `country`, `code`, `phone`, `fax`, `website`, `logoname`, `logo`, `vatid`) VALUES
	(1, 'ncrm', '95, 12th Main Road, 3rd Block, Rajajinagar', 'Bangalore', 'Karnataka', 'India', '560010', '+91 9243602352', '+91 9243602352', 'www.ncrm.com', 'ncrm-crm-logo.png', NULL, '1234-5678-9012');
/*!40000 ALTER TABLE `ncrm_organizationdetails` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_organizationdetails_seq
CREATE TABLE IF NOT EXISTS `ncrm_organizationdetails_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_organizationdetails_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_organizationdetails_seq` DISABLE KEYS */;
INSERT INTO `ncrm_organizationdetails_seq` (`id`) VALUES
	(1);
/*!40000 ALTER TABLE `ncrm_organizationdetails_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_org_share_action2tab
CREATE TABLE IF NOT EXISTS `ncrm_org_share_action2tab` (
  `share_action_id` int(19) NOT NULL,
  `tabid` int(19) NOT NULL,
  PRIMARY KEY (`share_action_id`,`tabid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_org_share_action2tab: ~100 rows (approximately)
/*!40000 ALTER TABLE `ncrm_org_share_action2tab` DISABLE KEYS */;
INSERT INTO `ncrm_org_share_action2tab` (`share_action_id`, `tabid`) VALUES
	(0, 2),
	(0, 4),
	(0, 6),
	(0, 7),
	(0, 8),
	(0, 9),
	(0, 10),
	(0, 13),
	(0, 14),
	(0, 16),
	(0, 18),
	(0, 20),
	(0, 21),
	(0, 22),
	(0, 23),
	(0, 26),
	(0, 34),
	(0, 35),
	(0, 36),
	(0, 38),
	(0, 42),
	(0, 43),
	(0, 44),
	(0, 45),
	(0, 47),
	(1, 2),
	(1, 4),
	(1, 6),
	(1, 7),
	(1, 8),
	(1, 9),
	(1, 10),
	(1, 13),
	(1, 14),
	(1, 16),
	(1, 18),
	(1, 20),
	(1, 21),
	(1, 22),
	(1, 23),
	(1, 26),
	(1, 34),
	(1, 35),
	(1, 36),
	(1, 38),
	(1, 42),
	(1, 43),
	(1, 44),
	(1, 45),
	(1, 47),
	(2, 2),
	(2, 4),
	(2, 6),
	(2, 7),
	(2, 8),
	(2, 9),
	(2, 10),
	(2, 13),
	(2, 14),
	(2, 16),
	(2, 18),
	(2, 20),
	(2, 21),
	(2, 22),
	(2, 23),
	(2, 26),
	(2, 34),
	(2, 35),
	(2, 36),
	(2, 38),
	(2, 42),
	(2, 43),
	(2, 44),
	(2, 45),
	(2, 47),
	(3, 2),
	(3, 4),
	(3, 6),
	(3, 7),
	(3, 8),
	(3, 9),
	(3, 10),
	(3, 13),
	(3, 14),
	(3, 16),
	(3, 18),
	(3, 20),
	(3, 21),
	(3, 22),
	(3, 23),
	(3, 26),
	(3, 34),
	(3, 35),
	(3, 36),
	(3, 38),
	(3, 42),
	(3, 43),
	(3, 44),
	(3, 45),
	(3, 47);
/*!40000 ALTER TABLE `ncrm_org_share_action2tab` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_org_share_action_mapping
CREATE TABLE IF NOT EXISTS `ncrm_org_share_action_mapping` (
  `share_action_id` int(19) NOT NULL,
  `share_action_name` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`share_action_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_org_share_action_mapping: ~8 rows (approximately)
/*!40000 ALTER TABLE `ncrm_org_share_action_mapping` DISABLE KEYS */;
INSERT INTO `ncrm_org_share_action_mapping` (`share_action_id`, `share_action_name`) VALUES
	(0, 'Public: Read Only'),
	(1, 'Public: Read, Create/Edit'),
	(2, 'Public: Read, Create/Edit, Delete'),
	(3, 'Private'),
	(4, 'Hide Details'),
	(5, 'Hide Details and Add Events'),
	(6, 'Show Details'),
	(7, 'Show Details and Add Events');
/*!40000 ALTER TABLE `ncrm_org_share_action_mapping` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_othereventduration
CREATE TABLE IF NOT EXISTS `ncrm_othereventduration` (
  `othereventdurationid` int(11) NOT NULL AUTO_INCREMENT,
  `othereventduration` varchar(200) NOT NULL,
  `sortorderid` int(11) DEFAULT NULL,
  `presence` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`othereventdurationid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_othereventduration: ~5 rows (approximately)
/*!40000 ALTER TABLE `ncrm_othereventduration` DISABLE KEYS */;
INSERT INTO `ncrm_othereventduration` (`othereventdurationid`, `othereventduration`, `sortorderid`, `presence`) VALUES
	(1, '5', 1, 1),
	(2, '10', 2, 1),
	(3, '30', 3, 1),
	(4, '60', 4, 1),
	(5, '120', 5, 1);
/*!40000 ALTER TABLE `ncrm_othereventduration` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_othereventduration_seq
CREATE TABLE IF NOT EXISTS `ncrm_othereventduration_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_othereventduration_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_othereventduration_seq` DISABLE KEYS */;
INSERT INTO `ncrm_othereventduration_seq` (`id`) VALUES
	(5);
/*!40000 ALTER TABLE `ncrm_othereventduration_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_parenttab
CREATE TABLE IF NOT EXISTS `ncrm_parenttab` (
  `parenttabid` int(19) NOT NULL,
  `parenttab_label` varchar(100) NOT NULL,
  `sequence` int(10) NOT NULL,
  `visible` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`parenttabid`),
  KEY `parenttab_parenttabid_parenttabl_label_visible_idx` (`parenttabid`,`parenttab_label`,`visible`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_parenttab: ~8 rows (approximately)
/*!40000 ALTER TABLE `ncrm_parenttab` DISABLE KEYS */;
INSERT INTO `ncrm_parenttab` (`parenttabid`, `parenttab_label`, `sequence`, `visible`) VALUES
	(1, 'My Home Page', 1, 0),
	(2, 'Marketing', 2, 0),
	(3, 'Sales', 3, 0),
	(4, 'Support', 4, 0),
	(5, 'Analytics', 5, 0),
	(6, 'Inventory', 6, 0),
	(7, 'Tools', 7, 0),
	(8, 'Settings', 8, 0);
/*!40000 ALTER TABLE `ncrm_parenttab` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_parenttabrel
CREATE TABLE IF NOT EXISTS `ncrm_parenttabrel` (
  `parenttabid` int(19) NOT NULL,
  `tabid` int(19) NOT NULL,
  `sequence` int(3) NOT NULL,
  KEY `parenttabrel_tabid_parenttabid_idx` (`tabid`,`parenttabid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_parenttabrel: ~65 rows (approximately)
/*!40000 ALTER TABLE `ncrm_parenttabrel` DISABLE KEYS */;
INSERT INTO `ncrm_parenttabrel` (`parenttabid`, `tabid`, `sequence`) VALUES
	(1, 9, 2),
	(1, 28, 4),
	(1, 3, 1),
	(3, 7, 1),
	(3, 6, 2),
	(3, 4, 3),
	(3, 2, 4),
	(3, 20, 5),
	(3, 22, 6),
	(3, 23, 7),
	(3, 19, 8),
	(3, 8, 9),
	(4, 13, 1),
	(4, 15, 2),
	(4, 6, 3),
	(4, 4, 4),
	(4, 8, 5),
	(5, 1, 2),
	(5, 25, 1),
	(6, 14, 1),
	(6, 18, 2),
	(6, 19, 3),
	(6, 21, 4),
	(6, 22, 5),
	(6, 20, 6),
	(6, 23, 7),
	(7, 24, 1),
	(7, 27, 2),
	(7, 8, 3),
	(2, 26, 1),
	(2, 6, 2),
	(2, 4, 3),
	(2, 28, 4),
	(4, 28, 7),
	(2, 7, 5),
	(2, 9, 6),
	(4, 9, 8),
	(2, 8, 8),
	(3, 9, 11),
	(7, 31, 4),
	(7, 31, 5),
	(7, 34, 6),
	(7, 34, 7),
	(4, 35, 9),
	(4, 35, 10),
	(6, 36, 8),
	(6, 36, 9),
	(6, 38, 10),
	(6, 38, 11),
	(7, 40, 8),
	(7, 40, 9),
	(7, 42, 10),
	(7, 42, 11),
	(4, 43, 11),
	(4, 43, 12),
	(4, 44, 13),
	(4, 44, 14),
	(4, 45, 15),
	(4, 45, 16),
	(7, 46, 12),
	(7, 46, 13),
	(7, 47, 14),
	(7, 47, 15),
	(8, 49, 1),
	(8, 49, 2);
/*!40000 ALTER TABLE `ncrm_parenttabrel` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_payment_duration
CREATE TABLE IF NOT EXISTS `ncrm_payment_duration` (
  `payment_duration_id` int(11) DEFAULT NULL,
  `payment_duration` varchar(200) DEFAULT NULL,
  `sortorderid` int(11) DEFAULT NULL,
  `presence` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_payment_duration: ~3 rows (approximately)
/*!40000 ALTER TABLE `ncrm_payment_duration` DISABLE KEYS */;
INSERT INTO `ncrm_payment_duration` (`payment_duration_id`, `payment_duration`, `sortorderid`, `presence`) VALUES
	(1, 'Net 30 days', 0, 1),
	(2, 'Net 45 days', 1, 1),
	(3, 'Net 60 days', 2, 1);
/*!40000 ALTER TABLE `ncrm_payment_duration` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_payment_duration_seq
CREATE TABLE IF NOT EXISTS `ncrm_payment_duration_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_payment_duration_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_payment_duration_seq` DISABLE KEYS */;
INSERT INTO `ncrm_payment_duration_seq` (`id`) VALUES
	(3);
/*!40000 ALTER TABLE `ncrm_payment_duration_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_pbxmanager
CREATE TABLE IF NOT EXISTS `ncrm_pbxmanager` (
  `pbxmanagerid` int(20) NOT NULL AUTO_INCREMENT,
  `direction` varchar(10) DEFAULT NULL,
  `callstatus` varchar(20) DEFAULT NULL,
  `starttime` datetime DEFAULT NULL,
  `endtime` datetime DEFAULT NULL,
  `totalduration` int(11) DEFAULT NULL,
  `billduration` int(11) DEFAULT NULL,
  `recordingurl` varchar(200) DEFAULT NULL,
  `sourceuuid` varchar(100) DEFAULT NULL,
  `gateway` varchar(20) DEFAULT NULL,
  `customer` varchar(100) DEFAULT NULL,
  `user` varchar(100) DEFAULT NULL,
  `customernumber` varchar(100) DEFAULT NULL,
  `customertype` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`pbxmanagerid`),
  KEY `index_sourceuuid` (`sourceuuid`),
  KEY `index_pbxmanager_id` (`pbxmanagerid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_pbxmanager: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_pbxmanager` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_pbxmanager` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_pbxmanagercf
CREATE TABLE IF NOT EXISTS `ncrm_pbxmanagercf` (
  `pbxmanagerid` int(11) NOT NULL,
  PRIMARY KEY (`pbxmanagerid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_pbxmanagercf: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_pbxmanagercf` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_pbxmanagercf` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_pbxmanager_gateway
CREATE TABLE IF NOT EXISTS `ncrm_pbxmanager_gateway` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gateway` varchar(20) DEFAULT NULL,
  `parameters` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_pbxmanager_gateway: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_pbxmanager_gateway` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_pbxmanager_gateway` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_pbxmanager_phonelookup
CREATE TABLE IF NOT EXISTS `ncrm_pbxmanager_phonelookup` (
  `crmid` int(20) DEFAULT NULL,
  `setype` varchar(30) DEFAULT NULL,
  `fnumber` varchar(100) DEFAULT NULL,
  `rnumber` varchar(100) DEFAULT NULL,
  `fieldname` varchar(50) DEFAULT NULL,
  UNIQUE KEY `unique_key` (`crmid`,`setype`,`fieldname`),
  KEY `index_phone_number` (`fnumber`,`rnumber`),
  CONSTRAINT `ncrm_pbxmanager_phonelookup_ibfk_1` FOREIGN KEY (`crmid`) REFERENCES `ncrm_crmentity` (`crmid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_pbxmanager_phonelookup: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_pbxmanager_phonelookup` DISABLE KEYS */;
INSERT INTO `ncrm_pbxmanager_phonelookup` (`crmid`, `setype`, `fnumber`, `rnumber`, `fieldname`) VALUES
	(14, 'Accounts', 'accc1', '1ccca', 'phone');
/*!40000 ALTER TABLE `ncrm_pbxmanager_phonelookup` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_picklist
CREATE TABLE IF NOT EXISTS `ncrm_picklist` (
  `picklistid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`picklistid`),
  UNIQUE KEY `picklist_name_idx` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_picklist: ~48 rows (approximately)
/*!40000 ALTER TABLE `ncrm_picklist` DISABLE KEYS */;
INSERT INTO `ncrm_picklist` (`picklistid`, `name`) VALUES
	(1, 'accounttype'),
	(2, 'activitytype'),
	(37, 'assetstatus'),
	(3, 'campaignstatus'),
	(4, 'campaigntype'),
	(5, 'carrier'),
	(33, 'contract_priority'),
	(32, 'contract_status'),
	(34, 'contract_type'),
	(48, 'defaultactivitytype'),
	(47, 'defaulteventstatus'),
	(6, 'eventstatus'),
	(7, 'expectedresponse'),
	(8, 'faqcategories'),
	(9, 'faqstatus'),
	(10, 'glacct'),
	(11, 'industry'),
	(12, 'invoicestatus'),
	(13, 'leadsource'),
	(14, 'leadstatus'),
	(15, 'manufacturer'),
	(16, 'opportunity_type'),
	(17, 'postatus'),
	(18, 'productcategory'),
	(45, 'progress'),
	(38, 'projectmilestonetype'),
	(44, 'projectpriority'),
	(42, 'projectstatus'),
	(40, 'projecttaskpriority'),
	(41, 'projecttaskprogress'),
	(46, 'projecttaskstatus'),
	(39, 'projecttasktype'),
	(43, 'projecttype'),
	(19, 'quotestage'),
	(20, 'rating'),
	(21, 'sales_stage'),
	(22, 'salutationtype'),
	(36, 'servicecategory'),
	(35, 'service_usageunit'),
	(23, 'sostatus'),
	(24, 'taskpriority'),
	(25, 'taskstatus'),
	(26, 'ticketcategories'),
	(27, 'ticketpriorities'),
	(28, 'ticketseverities'),
	(29, 'ticketstatus'),
	(31, 'tracking_unit'),
	(30, 'usageunit');
/*!40000 ALTER TABLE `ncrm_picklist` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_picklistvalues_seq
CREATE TABLE IF NOT EXISTS `ncrm_picklistvalues_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_picklistvalues_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_picklistvalues_seq` DISABLE KEYS */;
INSERT INTO `ncrm_picklistvalues_seq` (`id`) VALUES
	(302);
/*!40000 ALTER TABLE `ncrm_picklistvalues_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_picklist_dependency
CREATE TABLE IF NOT EXISTS `ncrm_picklist_dependency` (
  `id` int(11) NOT NULL,
  `tabid` int(19) NOT NULL,
  `sourcefield` varchar(255) DEFAULT NULL,
  `targetfield` varchar(255) DEFAULT NULL,
  `sourcevalue` varchar(100) DEFAULT NULL,
  `targetvalues` text,
  `criteria` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_picklist_dependency: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_picklist_dependency` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_picklist_dependency` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_picklist_seq
CREATE TABLE IF NOT EXISTS `ncrm_picklist_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_picklist_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_picklist_seq` DISABLE KEYS */;
INSERT INTO `ncrm_picklist_seq` (`id`) VALUES
	(48);
/*!40000 ALTER TABLE `ncrm_picklist_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_pobillads
CREATE TABLE IF NOT EXISTS `ncrm_pobillads` (
  `pobilladdressid` int(19) NOT NULL DEFAULT '0',
  `bill_city` varchar(30) DEFAULT NULL,
  `bill_code` varchar(30) DEFAULT NULL,
  `bill_country` varchar(30) DEFAULT NULL,
  `bill_state` varchar(30) DEFAULT NULL,
  `bill_street` varchar(250) DEFAULT NULL,
  `bill_pobox` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`pobilladdressid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_pobillads: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_pobillads` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_pobillads` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_portal
CREATE TABLE IF NOT EXISTS `ncrm_portal` (
  `portalid` int(19) NOT NULL,
  `portalname` varchar(200) NOT NULL,
  `portalurl` varchar(255) NOT NULL,
  `sequence` int(3) NOT NULL,
  `setdefault` int(3) NOT NULL DEFAULT '0',
  `createdtime` datetime DEFAULT NULL,
  PRIMARY KEY (`portalid`),
  KEY `portal_portalname_idx` (`portalname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_portal: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_portal` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_portal` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_portalinfo
CREATE TABLE IF NOT EXISTS `ncrm_portalinfo` (
  `id` int(11) NOT NULL,
  `user_name` varchar(50) DEFAULT NULL,
  `user_password` varchar(255) DEFAULT NULL,
  `type` varchar(5) DEFAULT NULL,
  `cryptmode` varchar(20) DEFAULT NULL,
  `last_login_time` datetime DEFAULT NULL,
  `login_time` datetime DEFAULT NULL,
  `logout_time` datetime DEFAULT NULL,
  `isactive` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_portalinfo: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_portalinfo` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_portalinfo` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_poshipads
CREATE TABLE IF NOT EXISTS `ncrm_poshipads` (
  `poshipaddressid` int(19) NOT NULL DEFAULT '0',
  `ship_city` varchar(30) DEFAULT NULL,
  `ship_code` varchar(30) DEFAULT NULL,
  `ship_country` varchar(30) DEFAULT NULL,
  `ship_state` varchar(30) DEFAULT NULL,
  `ship_street` varchar(250) DEFAULT NULL,
  `ship_pobox` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`poshipaddressid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_poshipads: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_poshipads` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_poshipads` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_postatus
CREATE TABLE IF NOT EXISTS `ncrm_postatus` (
  `postatusid` int(19) NOT NULL AUTO_INCREMENT,
  `postatus` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  PRIMARY KEY (`postatusid`),
  UNIQUE KEY `postatus_postatus_idx` (`postatus`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_postatus: ~5 rows (approximately)
/*!40000 ALTER TABLE `ncrm_postatus` DISABLE KEYS */;
INSERT INTO `ncrm_postatus` (`postatusid`, `postatus`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(1, 'Created', 0, 130, 0),
	(2, 'Approved', 0, 131, 1),
	(3, 'Delivered', 0, 132, 2),
	(4, 'Cancelled', 0, 133, 3),
	(5, 'Received Shipment', 0, 134, 4);
/*!40000 ALTER TABLE `ncrm_postatus` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_postatushistory
CREATE TABLE IF NOT EXISTS `ncrm_postatushistory` (
  `historyid` int(19) NOT NULL AUTO_INCREMENT,
  `purchaseorderid` int(19) NOT NULL,
  `vendorname` varchar(100) DEFAULT NULL,
  `total` decimal(10,0) DEFAULT NULL,
  `postatus` varchar(200) DEFAULT NULL,
  `lastmodified` datetime DEFAULT NULL,
  PRIMARY KEY (`historyid`),
  KEY `postatushistory_purchaseorderid_idx` (`purchaseorderid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_postatushistory: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_postatushistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_postatushistory` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_postatus_seq
CREATE TABLE IF NOT EXISTS `ncrm_postatus_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_postatus_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_postatus_seq` DISABLE KEYS */;
INSERT INTO `ncrm_postatus_seq` (`id`) VALUES
	(5);
/*!40000 ALTER TABLE `ncrm_postatus_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_potential
CREATE TABLE IF NOT EXISTS `ncrm_potential` (
  `potentialid` int(19) NOT NULL DEFAULT '0',
  `potential_no` varchar(100) NOT NULL,
  `related_to` int(19) DEFAULT NULL,
  `potentialname` varchar(120) NOT NULL,
  `amount` decimal(25,8) DEFAULT NULL,
  `currency` varchar(20) DEFAULT NULL,
  `closingdate` date DEFAULT NULL,
  `typeofrevenue` varchar(50) DEFAULT NULL,
  `nextstep` varchar(100) DEFAULT NULL,
  `private` int(1) DEFAULT '0',
  `probability` decimal(7,3) DEFAULT '0.000',
  `campaignid` int(19) DEFAULT NULL,
  `sales_stage` varchar(200) DEFAULT NULL,
  `potentialtype` varchar(200) DEFAULT NULL,
  `leadsource` varchar(200) DEFAULT NULL,
  `productid` int(50) DEFAULT NULL,
  `productversion` varchar(50) DEFAULT NULL,
  `quotationref` varchar(50) DEFAULT NULL,
  `partnercontact` varchar(50) DEFAULT NULL,
  `remarks` varchar(50) DEFAULT NULL,
  `runtimefee` int(19) DEFAULT '0',
  `followupdate` date DEFAULT NULL,
  `evaluationstatus` varchar(50) DEFAULT NULL,
  `description` text,
  `forecastcategory` int(19) DEFAULT '0',
  `outcomeanalysis` int(19) DEFAULT '0',
  `forecast_amount` decimal(25,8) DEFAULT NULL,
  `isconvertedfromlead` varchar(3) DEFAULT '0',
  `contact_id` int(19) DEFAULT NULL,
  PRIMARY KEY (`potentialid`),
  KEY `potential_relatedto_idx` (`related_to`),
  KEY `potentail_sales_stage_idx` (`sales_stage`),
  KEY `potentail_sales_stage_amount_idx` (`amount`,`sales_stage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_potential: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_potential` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_potential` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_potentialscf
CREATE TABLE IF NOT EXISTS `ncrm_potentialscf` (
  `potentialid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`potentialid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_potentialscf: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_potentialscf` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_potentialscf` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_potstagehistory
CREATE TABLE IF NOT EXISTS `ncrm_potstagehistory` (
  `historyid` int(19) NOT NULL AUTO_INCREMENT,
  `potentialid` int(19) NOT NULL,
  `amount` decimal(10,0) DEFAULT NULL,
  `stage` varchar(100) DEFAULT NULL,
  `probability` decimal(7,3) DEFAULT NULL,
  `expectedrevenue` decimal(10,0) DEFAULT NULL,
  `closedate` date DEFAULT NULL,
  `lastmodified` datetime DEFAULT NULL,
  PRIMARY KEY (`historyid`),
  KEY `potstagehistory_potentialid_idx` (`potentialid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_potstagehistory: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_potstagehistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_potstagehistory` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_pricebook
CREATE TABLE IF NOT EXISTS `ncrm_pricebook` (
  `pricebookid` int(19) NOT NULL DEFAULT '0',
  `pricebook_no` varchar(100) NOT NULL,
  `bookname` varchar(100) DEFAULT NULL,
  `active` int(1) DEFAULT NULL,
  `currency_id` int(19) NOT NULL DEFAULT '1',
  PRIMARY KEY (`pricebookid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_pricebook: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_pricebook` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_pricebook` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_pricebookcf
CREATE TABLE IF NOT EXISTS `ncrm_pricebookcf` (
  `pricebookid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pricebookid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_pricebookcf: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_pricebookcf` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_pricebookcf` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_pricebookproductrel
CREATE TABLE IF NOT EXISTS `ncrm_pricebookproductrel` (
  `pricebookid` int(19) NOT NULL,
  `productid` int(19) NOT NULL,
  `listprice` decimal(27,8) DEFAULT NULL,
  `usedcurrency` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`pricebookid`,`productid`),
  KEY `pricebookproductrel_pricebookid_idx` (`pricebookid`),
  KEY `pricebookproductrel_productid_idx` (`productid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_pricebookproductrel: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_pricebookproductrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_pricebookproductrel` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_priority
CREATE TABLE IF NOT EXISTS `ncrm_priority` (
  `priorityid` int(19) NOT NULL AUTO_INCREMENT,
  `priority` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`priorityid`),
  UNIQUE KEY `priority_priority_idx` (`priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_priority: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_priority` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_priority` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_productcategory
CREATE TABLE IF NOT EXISTS `ncrm_productcategory` (
  `productcategoryid` int(19) NOT NULL AUTO_INCREMENT,
  `productcategory` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  PRIMARY KEY (`productcategoryid`),
  UNIQUE KEY `productcategory_productcategory_idx` (`productcategory`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_productcategory: ~3 rows (approximately)
/*!40000 ALTER TABLE `ncrm_productcategory` DISABLE KEYS */;
INSERT INTO `ncrm_productcategory` (`productcategoryid`, `productcategory`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(2, 'Hardware', 1, 136, 1),
	(3, 'Software', 1, 137, 2),
	(4, 'CRM Applications', 1, 138, 3);
/*!40000 ALTER TABLE `ncrm_productcategory` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_productcategory_seq
CREATE TABLE IF NOT EXISTS `ncrm_productcategory_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_productcategory_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_productcategory_seq` DISABLE KEYS */;
INSERT INTO `ncrm_productcategory_seq` (`id`) VALUES
	(4);
/*!40000 ALTER TABLE `ncrm_productcategory_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_productcf
CREATE TABLE IF NOT EXISTS `ncrm_productcf` (
  `productid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`productid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_productcf: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_productcf` DISABLE KEYS */;
INSERT INTO `ncrm_productcf` (`productid`) VALUES
	(22);
/*!40000 ALTER TABLE `ncrm_productcf` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_productcurrencyrel
CREATE TABLE IF NOT EXISTS `ncrm_productcurrencyrel` (
  `productid` int(11) NOT NULL,
  `currencyid` int(11) NOT NULL,
  `converted_price` decimal(28,8) DEFAULT NULL,
  `actual_price` decimal(28,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_productcurrencyrel: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_productcurrencyrel` DISABLE KEYS */;
INSERT INTO `ncrm_productcurrencyrel` (`productid`, `currencyid`, `converted_price`, `actual_price`) VALUES
	(22, 1, 1000.00000000, 1000.00000000);
/*!40000 ALTER TABLE `ncrm_productcurrencyrel` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_products
CREATE TABLE IF NOT EXISTS `ncrm_products` (
  `productid` int(11) NOT NULL,
  `product_no` varchar(100) NOT NULL,
  `productname` varchar(100) DEFAULT NULL,
  `productcode` varchar(40) DEFAULT NULL,
  `productcategory` varchar(200) DEFAULT NULL,
  `manufacturer` varchar(200) DEFAULT NULL,
  `qty_per_unit` decimal(11,2) DEFAULT '0.00',
  `unit_price` decimal(25,8) DEFAULT NULL,
  `weight` decimal(11,3) DEFAULT NULL,
  `pack_size` int(11) DEFAULT NULL,
  `sales_start_date` date DEFAULT NULL,
  `sales_end_date` date DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `cost_factor` int(11) DEFAULT NULL,
  `commissionrate` decimal(7,3) DEFAULT NULL,
  `commissionmethod` varchar(50) DEFAULT NULL,
  `discontinued` int(1) NOT NULL DEFAULT '0',
  `usageunit` varchar(200) DEFAULT NULL,
  `reorderlevel` int(11) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `taxclass` varchar(200) DEFAULT NULL,
  `mfr_part_no` varchar(200) DEFAULT NULL,
  `vendor_part_no` varchar(200) DEFAULT NULL,
  `serialno` varchar(200) DEFAULT NULL,
  `qtyinstock` decimal(25,3) DEFAULT NULL,
  `productsheet` varchar(200) DEFAULT NULL,
  `qtyindemand` int(11) DEFAULT NULL,
  `glacct` varchar(200) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `imagename` text,
  `currency_id` int(19) NOT NULL DEFAULT '1',
  PRIMARY KEY (`productid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_products: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_products` DISABLE KEYS */;
INSERT INTO `ncrm_products` (`productid`, `product_no`, `productname`, `productcode`, `productcategory`, `manufacturer`, `qty_per_unit`, `unit_price`, `weight`, `pack_size`, `sales_start_date`, `sales_end_date`, `start_date`, `expiry_date`, `cost_factor`, `commissionrate`, `commissionmethod`, `discontinued`, `usageunit`, `reorderlevel`, `website`, `taxclass`, `mfr_part_no`, `vendor_part_no`, `serialno`, `qtyinstock`, `productsheet`, `qtyindemand`, `glacct`, `vendor_id`, `imagename`, `currency_id`) VALUES
	(22, 'PRO1', 'p1', '', '', '', 0.00, 1000.00000000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000, NULL, 1, '', 0, '', '', '', '', '', 2.000, '', 0, '', 0, '', 1);
/*!40000 ALTER TABLE `ncrm_products` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_producttaxrel
CREATE TABLE IF NOT EXISTS `ncrm_producttaxrel` (
  `productid` int(11) NOT NULL,
  `taxid` int(3) NOT NULL,
  `taxpercentage` decimal(7,3) DEFAULT NULL,
  KEY `producttaxrel_productid_idx` (`productid`),
  KEY `producttaxrel_taxid_idx` (`taxid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_producttaxrel: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_producttaxrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_producttaxrel` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_profile
CREATE TABLE IF NOT EXISTS `ncrm_profile` (
  `profileid` int(10) NOT NULL AUTO_INCREMENT,
  `profilename` varchar(50) NOT NULL,
  `description` text,
  `directly_related_to_role` int(1) DEFAULT '0',
  PRIMARY KEY (`profileid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_profile: ~4 rows (approximately)
/*!40000 ALTER TABLE `ncrm_profile` DISABLE KEYS */;
INSERT INTO `ncrm_profile` (`profileid`, `profilename`, `description`, `directly_related_to_role`) VALUES
	(1, 'Administrator', 'Admin Profile', 0),
	(2, 'Sales Profile', 'Profile Related to Sales', 0),
	(3, 'Support Profile', 'Profile Related to Support', 0),
	(4, 'Guest Profile', 'Guest Profile for Test Users', 0),
	(5, 'Normal User', '', 0),
	(6, 'Normal User+Profile', '0', 1);
/*!40000 ALTER TABLE `ncrm_profile` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_profile2field
CREATE TABLE IF NOT EXISTS `ncrm_profile2field` (
  `profileid` int(11) NOT NULL,
  `tabid` int(10) DEFAULT NULL,
  `fieldid` int(19) NOT NULL,
  `visible` int(19) DEFAULT NULL,
  `readonly` int(19) DEFAULT NULL,
  PRIMARY KEY (`profileid`,`fieldid`),
  KEY `profile2field_profileid_tabid_fieldname_idx` (`profileid`,`tabid`),
  KEY `profile2field_tabid_profileid_idx` (`tabid`,`profileid`),
  KEY `profile2field_visible_profileid_idx` (`visible`,`profileid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_profile2field: ~2,796 rows (approximately)
/*!40000 ALTER TABLE `ncrm_profile2field` DISABLE KEYS */;
INSERT INTO `ncrm_profile2field` (`profileid`, `tabid`, `fieldid`, `visible`, `readonly`) VALUES
	(1, 6, 1, 0, 0),
	(1, 6, 2, 0, 0),
	(1, 6, 3, 0, 0),
	(1, 6, 4, 0, 0),
	(1, 6, 5, 0, 0),
	(1, 6, 6, 0, 0),
	(1, 6, 7, 0, 0),
	(1, 6, 8, 0, 0),
	(1, 6, 9, 0, 0),
	(1, 6, 10, 0, 0),
	(1, 6, 11, 0, 0),
	(1, 6, 12, 0, 0),
	(1, 6, 13, 0, 0),
	(1, 6, 14, 0, 0),
	(1, 6, 15, 0, 0),
	(1, 6, 16, 0, 0),
	(1, 6, 17, 0, 0),
	(1, 6, 18, 0, 0),
	(1, 6, 19, 0, 0),
	(1, 6, 20, 0, 0),
	(1, 6, 21, 0, 0),
	(1, 6, 22, 0, 0),
	(1, 6, 23, 0, 0),
	(1, 6, 24, 0, 0),
	(1, 6, 25, 0, 0),
	(1, 6, 26, 0, 0),
	(1, 6, 27, 0, 0),
	(1, 6, 28, 0, 0),
	(1, 6, 29, 0, 0),
	(1, 6, 30, 0, 0),
	(1, 6, 31, 0, 0),
	(1, 6, 32, 0, 0),
	(1, 6, 33, 0, 0),
	(1, 6, 34, 0, 0),
	(1, 6, 35, 0, 0),
	(1, 6, 36, 0, 0),
	(1, 7, 37, 0, 0),
	(1, 7, 38, 0, 0),
	(1, 7, 39, 0, 0),
	(1, 7, 40, 0, 0),
	(1, 7, 41, 0, 0),
	(1, 7, 42, 0, 0),
	(1, 7, 43, 0, 0),
	(1, 7, 44, 0, 0),
	(1, 7, 45, 0, 0),
	(1, 7, 46, 0, 0),
	(1, 7, 47, 0, 0),
	(1, 7, 48, 0, 0),
	(1, 7, 49, 0, 0),
	(1, 7, 50, 0, 0),
	(1, 7, 51, 0, 0),
	(1, 7, 52, 0, 0),
	(1, 7, 53, 0, 0),
	(1, 7, 54, 0, 0),
	(1, 7, 55, 0, 0),
	(1, 7, 56, 0, 0),
	(1, 7, 57, 0, 0),
	(1, 7, 58, 0, 0),
	(1, 7, 59, 0, 0),
	(1, 7, 60, 0, 0),
	(1, 7, 61, 0, 0),
	(1, 7, 62, 0, 0),
	(1, 7, 63, 0, 0),
	(1, 7, 64, 0, 0),
	(1, 7, 65, 0, 0),
	(1, 4, 66, 0, 0),
	(1, 4, 67, 0, 0),
	(1, 4, 68, 0, 0),
	(1, 4, 69, 0, 0),
	(1, 4, 70, 0, 0),
	(1, 4, 71, 0, 0),
	(1, 4, 72, 0, 0),
	(1, 4, 73, 0, 0),
	(1, 4, 74, 0, 0),
	(1, 4, 75, 0, 0),
	(1, 4, 76, 0, 0),
	(1, 4, 77, 0, 0),
	(1, 4, 78, 0, 0),
	(1, 4, 79, 0, 0),
	(1, 4, 80, 0, 0),
	(1, 4, 81, 0, 0),
	(1, 4, 82, 0, 0),
	(1, 4, 83, 0, 0),
	(1, 4, 84, 0, 0),
	(1, 4, 85, 0, 0),
	(1, 4, 86, 0, 0),
	(1, 4, 87, 0, 0),
	(1, 4, 88, 0, 0),
	(1, 4, 89, 0, 0),
	(1, 4, 90, 0, 0),
	(1, 4, 91, 0, 0),
	(1, 4, 92, 0, 0),
	(1, 4, 93, 0, 0),
	(1, 4, 94, 0, 0),
	(1, 4, 95, 0, 0),
	(1, 4, 96, 0, 0),
	(1, 4, 97, 0, 0),
	(1, 4, 98, 0, 0),
	(1, 4, 99, 0, 0),
	(1, 4, 100, 0, 0),
	(1, 4, 101, 0, 0),
	(1, 4, 102, 0, 0),
	(1, 4, 103, 0, 0),
	(1, 4, 104, 0, 0),
	(1, 4, 105, 0, 0),
	(1, 4, 106, 0, 0),
	(1, 4, 107, 0, 0),
	(1, 4, 108, 0, 0),
	(1, 4, 109, 0, 0),
	(1, 2, 110, 0, 0),
	(1, 2, 111, 0, 0),
	(1, 2, 112, 0, 0),
	(1, 2, 113, 0, 0),
	(1, 2, 114, 0, 0),
	(1, 2, 115, 0, 0),
	(1, 2, 116, 0, 0),
	(1, 2, 117, 0, 0),
	(1, 2, 118, 0, 0),
	(1, 2, 119, 0, 0),
	(1, 2, 120, 0, 0),
	(1, 2, 121, 0, 0),
	(1, 2, 122, 0, 0),
	(1, 2, 123, 0, 0),
	(1, 2, 124, 0, 0),
	(1, 2, 125, 0, 0),
	(1, 26, 126, 0, 0),
	(1, 26, 127, 0, 0),
	(1, 26, 128, 0, 0),
	(1, 26, 129, 0, 0),
	(1, 26, 130, 0, 0),
	(1, 26, 131, 0, 0),
	(1, 26, 132, 0, 0),
	(1, 26, 133, 0, 0),
	(1, 26, 134, 0, 0),
	(1, 26, 135, 0, 0),
	(1, 26, 136, 0, 0),
	(1, 26, 137, 0, 0),
	(1, 26, 138, 0, 0),
	(1, 26, 139, 0, 0),
	(1, 26, 140, 0, 0),
	(1, 26, 141, 0, 0),
	(1, 26, 142, 0, 0),
	(1, 26, 143, 0, 0),
	(1, 26, 144, 0, 0),
	(1, 26, 145, 0, 0),
	(1, 26, 146, 0, 0),
	(1, 26, 147, 0, 0),
	(1, 26, 148, 0, 0),
	(1, 26, 149, 0, 0),
	(1, 26, 150, 0, 0),
	(1, 4, 151, 0, 0),
	(1, 6, 152, 0, 0),
	(1, 7, 153, 0, 0),
	(1, 26, 154, 0, 0),
	(1, 13, 155, 0, 0),
	(1, 13, 156, 0, 0),
	(1, 13, 157, 0, 0),
	(1, 13, 158, 0, 0),
	(1, 13, 159, 0, 0),
	(1, 13, 160, 0, 0),
	(1, 13, 161, 0, 0),
	(1, 13, 162, 0, 0),
	(1, 13, 163, 0, 0),
	(1, 13, 164, 0, 0),
	(1, 13, 165, 0, 0),
	(1, 13, 166, 0, 0),
	(1, 13, 167, 0, 0),
	(1, 13, 168, 0, 0),
	(1, 13, 169, 0, 0),
	(1, 13, 170, 0, 0),
	(1, 13, 171, 0, 0),
	(1, 13, 172, 0, 0),
	(1, 14, 173, 0, 0),
	(1, 14, 174, 0, 0),
	(1, 14, 175, 0, 0),
	(1, 14, 176, 0, 0),
	(1, 14, 177, 0, 0),
	(1, 14, 178, 0, 0),
	(1, 14, 179, 0, 0),
	(1, 14, 180, 0, 0),
	(1, 14, 181, 0, 0),
	(1, 14, 182, 0, 0),
	(1, 14, 183, 0, 0),
	(1, 14, 184, 0, 0),
	(1, 14, 185, 0, 0),
	(1, 14, 186, 0, 0),
	(1, 14, 187, 0, 0),
	(1, 14, 188, 0, 0),
	(1, 14, 189, 0, 0),
	(1, 14, 190, 0, 0),
	(1, 14, 191, 0, 0),
	(1, 14, 192, 0, 0),
	(1, 14, 193, 0, 0),
	(1, 14, 194, 0, 0),
	(1, 14, 195, 0, 0),
	(1, 14, 196, 0, 0),
	(1, 14, 197, 0, 0),
	(1, 14, 198, 0, 0),
	(1, 14, 199, 0, 0),
	(1, 14, 200, 0, 0),
	(1, 14, 201, 0, 0),
	(1, 14, 202, 0, 0),
	(1, 14, 203, 0, 0),
	(1, 8, 204, 0, 0),
	(1, 8, 205, 0, 0),
	(1, 8, 206, 0, 0),
	(1, 8, 207, 0, 0),
	(1, 8, 208, 0, 0),
	(1, 8, 209, 0, 0),
	(1, 8, 210, 0, 0),
	(1, 8, 211, 0, 0),
	(1, 8, 212, 0, 0),
	(1, 8, 213, 0, 0),
	(1, 8, 214, 0, 0),
	(1, 8, 215, 0, 0),
	(1, 8, 216, 0, 0),
	(1, 8, 217, 0, 0),
	(1, 8, 218, 0, 0),
	(1, 10, 219, 0, 0),
	(1, 10, 220, 0, 0),
	(1, 10, 221, 0, 0),
	(1, 10, 222, 0, 0),
	(1, 10, 223, 0, 0),
	(1, 10, 224, 0, 0),
	(1, 10, 225, 0, 0),
	(1, 10, 226, 0, 0),
	(1, 10, 227, 0, 0),
	(1, 10, 228, 0, 0),
	(1, 10, 229, 0, 0),
	(1, 10, 230, 0, 0),
	(1, 9, 231, 0, 0),
	(1, 9, 232, 0, 0),
	(1, 9, 233, 0, 0),
	(1, 9, 234, 0, 0),
	(1, 9, 235, 0, 0),
	(1, 9, 236, 0, 0),
	(1, 9, 237, 0, 0),
	(1, 9, 238, 0, 0),
	(1, 9, 239, 0, 0),
	(1, 9, 240, 0, 0),
	(1, 9, 241, 0, 0),
	(1, 9, 242, 0, 0),
	(1, 9, 243, 0, 0),
	(1, 9, 244, 0, 0),
	(1, 9, 245, 0, 0),
	(1, 9, 246, 0, 0),
	(1, 9, 247, 0, 0),
	(1, 9, 248, 0, 0),
	(1, 9, 249, 0, 0),
	(1, 9, 250, 0, 0),
	(1, 9, 251, 0, 0),
	(1, 9, 252, 0, 0),
	(1, 9, 253, 0, 0),
	(1, 9, 254, 0, 0),
	(1, 16, 255, 0, 0),
	(1, 16, 256, 0, 0),
	(1, 16, 257, 0, 0),
	(1, 16, 258, 0, 0),
	(1, 16, 259, 0, 0),
	(1, 16, 260, 0, 0),
	(1, 16, 261, 0, 0),
	(1, 16, 262, 0, 0),
	(1, 16, 263, 0, 0),
	(1, 16, 264, 0, 0),
	(1, 16, 265, 0, 0),
	(1, 16, 266, 0, 0),
	(1, 16, 267, 0, 0),
	(1, 16, 268, 0, 0),
	(1, 16, 269, 0, 0),
	(1, 16, 270, 0, 0),
	(1, 16, 271, 0, 0),
	(1, 16, 272, 0, 0),
	(1, 16, 273, 0, 0),
	(1, 16, 274, 0, 0),
	(1, 16, 275, 0, 0),
	(1, 16, 276, 0, 0),
	(1, 16, 277, 0, 0),
	(1, 15, 278, 0, 0),
	(1, 15, 279, 0, 0),
	(1, 15, 280, 0, 0),
	(1, 15, 281, 0, 0),
	(1, 15, 282, 0, 0),
	(1, 15, 283, 0, 0),
	(1, 15, 284, 0, 0),
	(1, 15, 285, 0, 0),
	(1, 15, 286, 0, 0),
	(1, 15, 287, 0, 0),
	(1, 18, 288, 0, 0),
	(1, 18, 289, 0, 0),
	(1, 18, 290, 0, 0),
	(1, 18, 291, 0, 0),
	(1, 18, 292, 0, 0),
	(1, 18, 293, 0, 0),
	(1, 18, 294, 0, 0),
	(1, 18, 295, 0, 0),
	(1, 18, 296, 0, 0),
	(1, 18, 297, 0, 0),
	(1, 18, 298, 0, 0),
	(1, 18, 299, 0, 0),
	(1, 18, 300, 0, 0),
	(1, 18, 301, 0, 0),
	(1, 18, 302, 0, 0),
	(1, 18, 303, 0, 0),
	(1, 18, 304, 0, 0),
	(1, 19, 305, 0, 0),
	(1, 19, 306, 0, 0),
	(1, 19, 307, 0, 0),
	(1, 19, 308, 0, 0),
	(1, 19, 309, 0, 0),
	(1, 19, 310, 0, 0),
	(1, 19, 311, 0, 0),
	(1, 19, 312, 0, 0),
	(1, 20, 313, 0, 0),
	(1, 20, 314, 0, 0),
	(1, 20, 315, 0, 0),
	(1, 20, 316, 0, 0),
	(1, 20, 317, 0, 0),
	(1, 20, 318, 0, 0),
	(1, 20, 319, 0, 0),
	(1, 20, 320, 0, 0),
	(1, 20, 321, 0, 0),
	(1, 20, 322, 0, 0),
	(1, 20, 323, 0, 0),
	(1, 20, 324, 0, 0),
	(1, 20, 325, 0, 0),
	(1, 20, 326, 0, 0),
	(1, 20, 327, 0, 0),
	(1, 20, 328, 0, 0),
	(1, 20, 329, 0, 0),
	(1, 20, 330, 0, 0),
	(1, 20, 331, 0, 0),
	(1, 20, 332, 0, 0),
	(1, 20, 333, 0, 0),
	(1, 20, 334, 0, 0),
	(1, 20, 335, 0, 0),
	(1, 20, 336, 0, 0),
	(1, 20, 337, 0, 0),
	(1, 20, 338, 0, 0),
	(1, 20, 339, 0, 0),
	(1, 20, 340, 0, 0),
	(1, 20, 341, 0, 0),
	(1, 20, 342, 0, 0),
	(1, 20, 343, 0, 0),
	(1, 20, 344, 0, 0),
	(1, 20, 345, 0, 0),
	(1, 20, 346, 0, 0),
	(1, 20, 347, 0, 0),
	(1, 20, 348, 0, 0),
	(1, 20, 349, 0, 0),
	(1, 21, 350, 0, 0),
	(1, 21, 351, 0, 0),
	(1, 21, 352, 0, 0),
	(1, 21, 353, 0, 0),
	(1, 21, 354, 0, 0),
	(1, 21, 355, 0, 0),
	(1, 21, 356, 0, 0),
	(1, 21, 357, 0, 0),
	(1, 21, 358, 0, 0),
	(1, 21, 359, 0, 0),
	(1, 21, 360, 0, 0),
	(1, 21, 361, 0, 0),
	(1, 21, 362, 0, 0),
	(1, 21, 363, 0, 0),
	(1, 21, 364, 0, 0),
	(1, 21, 365, 0, 0),
	(1, 21, 366, 0, 0),
	(1, 21, 367, 0, 0),
	(1, 21, 368, 0, 0),
	(1, 21, 369, 0, 0),
	(1, 21, 370, 0, 0),
	(1, 21, 371, 0, 0),
	(1, 21, 372, 0, 0),
	(1, 21, 373, 0, 0),
	(1, 21, 374, 0, 0),
	(1, 21, 375, 0, 0),
	(1, 21, 376, 0, 0),
	(1, 21, 377, 0, 0),
	(1, 21, 378, 0, 0),
	(1, 21, 379, 0, 0),
	(1, 21, 380, 0, 0),
	(1, 21, 381, 0, 0),
	(1, 21, 382, 0, 0),
	(1, 21, 383, 0, 0),
	(1, 21, 384, 0, 0),
	(1, 21, 385, 0, 0),
	(1, 21, 386, 0, 0),
	(1, 21, 387, 0, 0),
	(1, 22, 388, 0, 0),
	(1, 22, 389, 0, 0),
	(1, 22, 390, 0, 0),
	(1, 22, 391, 0, 0),
	(1, 22, 392, 0, 0),
	(1, 22, 393, 0, 0),
	(1, 22, 394, 0, 0),
	(1, 22, 395, 0, 0),
	(1, 22, 396, 0, 0),
	(1, 22, 397, 0, 0),
	(1, 22, 398, 0, 0),
	(1, 22, 399, 0, 0),
	(1, 22, 400, 0, 0),
	(1, 22, 401, 0, 0),
	(1, 22, 402, 0, 0),
	(1, 22, 403, 0, 0),
	(1, 22, 404, 0, 0),
	(1, 22, 405, 0, 0),
	(1, 22, 406, 0, 0),
	(1, 22, 407, 0, 0),
	(1, 22, 408, 0, 0),
	(1, 22, 409, 0, 0),
	(1, 22, 410, 0, 0),
	(1, 22, 411, 0, 0),
	(1, 22, 412, 0, 0),
	(1, 22, 413, 0, 0),
	(1, 22, 414, 0, 0),
	(1, 22, 415, 0, 0),
	(1, 22, 416, 0, 0),
	(1, 22, 417, 0, 0),
	(1, 22, 418, 0, 0),
	(1, 22, 419, 0, 0),
	(1, 22, 420, 0, 0),
	(1, 22, 421, 0, 0),
	(1, 22, 422, 0, 0),
	(1, 22, 423, 0, 0),
	(1, 22, 424, 0, 0),
	(1, 22, 425, 0, 0),
	(1, 22, 426, 0, 0),
	(1, 22, 427, 0, 0),
	(1, 22, 428, 0, 0),
	(1, 22, 429, 0, 0),
	(1, 22, 430, 0, 0),
	(1, 22, 431, 0, 0),
	(1, 22, 432, 0, 0),
	(1, 22, 433, 0, 0),
	(1, 22, 434, 0, 0),
	(1, 23, 435, 0, 0),
	(1, 23, 436, 0, 0),
	(1, 23, 437, 0, 0),
	(1, 23, 438, 0, 0),
	(1, 23, 439, 0, 0),
	(1, 23, 440, 0, 0),
	(1, 23, 441, 0, 0),
	(1, 23, 442, 0, 0),
	(1, 23, 443, 0, 0),
	(1, 23, 444, 0, 0),
	(1, 23, 445, 0, 0),
	(1, 23, 446, 0, 0),
	(1, 23, 447, 0, 0),
	(1, 23, 448, 0, 0),
	(1, 23, 449, 0, 0),
	(1, 23, 450, 0, 0),
	(1, 23, 451, 0, 0),
	(1, 23, 452, 0, 0),
	(1, 23, 453, 0, 0),
	(1, 23, 454, 0, 0),
	(1, 23, 455, 0, 0),
	(1, 23, 456, 0, 0),
	(1, 23, 457, 0, 0),
	(1, 23, 458, 0, 0),
	(1, 23, 459, 0, 0),
	(1, 23, 460, 0, 0),
	(1, 23, 461, 0, 0),
	(1, 23, 462, 0, 0),
	(1, 23, 463, 0, 0),
	(1, 23, 464, 0, 0),
	(1, 23, 465, 0, 0),
	(1, 23, 466, 0, 0),
	(1, 23, 467, 0, 0),
	(1, 23, 468, 0, 0),
	(1, 23, 469, 0, 0),
	(1, 23, 470, 0, 0),
	(1, 23, 471, 0, 0),
	(1, 23, 472, 0, 0),
	(1, 23, 473, 0, 0),
	(1, 29, 474, 0, 0),
	(1, 29, 478, 0, 0),
	(1, 29, 479, 0, 0),
	(1, 29, 481, 0, 0),
	(1, 29, 488, 0, 0),
	(1, 29, 489, 0, 0),
	(1, 29, 490, 0, 0),
	(1, 29, 491, 0, 0),
	(1, 29, 493, 0, 0),
	(1, 29, 494, 0, 0),
	(1, 29, 495, 0, 0),
	(1, 29, 496, 0, 0),
	(1, 29, 497, 0, 0),
	(1, 29, 502, 0, 0),
	(1, 29, 503, 0, 0),
	(1, 29, 504, 0, 0),
	(1, 29, 505, 0, 0),
	(1, 29, 513, 0, 0),
	(1, 10, 518, 0, 0),
	(1, 10, 519, 0, 0),
	(1, 10, 520, 0, 0),
	(1, 10, 521, 0, 0),
	(1, 10, 522, 0, 0),
	(1, 10, 523, 0, 0),
	(1, 34, 524, 0, 0),
	(1, 34, 525, 0, 0),
	(1, 34, 526, 0, 0),
	(1, 34, 527, 0, 0),
	(1, 34, 528, 0, 0),
	(1, 34, 529, 0, 0),
	(1, 34, 530, 0, 0),
	(1, 34, 531, 0, 0),
	(1, 34, 532, 0, 0),
	(1, 34, 533, 0, 0),
	(1, 34, 534, 0, 0),
	(1, 34, 535, 0, 0),
	(1, 34, 536, 0, 0),
	(1, 34, 537, 0, 0),
	(1, 34, 538, 0, 0),
	(1, 34, 539, 0, 0),
	(1, 29, 540, 0, 0),
	(1, 35, 541, 0, 0),
	(1, 35, 542, 0, 0),
	(1, 35, 543, 0, 0),
	(1, 35, 544, 0, 0),
	(1, 35, 545, 0, 0),
	(1, 35, 546, 0, 0),
	(1, 35, 547, 0, 0),
	(1, 35, 548, 0, 0),
	(1, 35, 549, 0, 0),
	(1, 35, 550, 0, 0),
	(1, 35, 551, 0, 0),
	(1, 35, 552, 0, 0),
	(1, 35, 553, 0, 0),
	(1, 35, 554, 0, 0),
	(1, 35, 555, 0, 0),
	(1, 35, 556, 0, 0),
	(1, 35, 557, 0, 0),
	(1, 35, 558, 0, 0),
	(1, 35, 559, 0, 0),
	(1, 36, 560, 0, 0),
	(1, 36, 561, 0, 0),
	(1, 36, 562, 0, 0),
	(1, 36, 563, 0, 0),
	(1, 36, 564, 0, 0),
	(1, 36, 565, 0, 0),
	(1, 36, 566, 0, 0),
	(1, 36, 567, 0, 0),
	(1, 36, 568, 0, 0),
	(1, 36, 569, 0, 0),
	(1, 36, 570, 0, 0),
	(1, 36, 571, 0, 0),
	(1, 36, 572, 0, 0),
	(1, 36, 573, 0, 0),
	(1, 36, 574, 0, 0),
	(1, 36, 575, 0, 0),
	(1, 36, 576, 0, 0),
	(1, 36, 577, 0, 0),
	(1, 36, 578, 0, 0),
	(1, 38, 579, 0, 0),
	(1, 38, 580, 0, 0),
	(1, 38, 581, 0, 0),
	(1, 38, 582, 0, 0),
	(1, 38, 583, 0, 0),
	(1, 38, 584, 0, 0),
	(1, 38, 585, 0, 0),
	(1, 38, 586, 0, 0),
	(1, 38, 587, 0, 0),
	(1, 38, 588, 0, 0),
	(1, 38, 589, 0, 0),
	(1, 38, 590, 0, 0),
	(1, 38, 591, 0, 0),
	(1, 38, 592, 0, 0),
	(1, 38, 593, 0, 0),
	(1, 38, 594, 0, 0),
	(1, 38, 595, 0, 0),
	(1, 38, 596, 0, 0),
	(1, 42, 597, 0, 0),
	(1, 42, 598, 0, 0),
	(1, 42, 599, 0, 0),
	(1, 42, 600, 0, 0),
	(1, 42, 601, 0, 0),
	(1, 42, 602, 0, 0),
	(1, 42, 603, 0, 0),
	(1, 43, 604, 0, 0),
	(1, 43, 605, 0, 0),
	(1, 43, 606, 0, 0),
	(1, 43, 607, 0, 0),
	(1, 43, 608, 0, 0),
	(1, 43, 609, 0, 0),
	(1, 43, 610, 0, 0),
	(1, 43, 611, 0, 0),
	(1, 43, 612, 0, 0),
	(1, 43, 613, 0, 0),
	(1, 44, 614, 0, 0),
	(1, 44, 615, 0, 0),
	(1, 44, 616, 0, 0),
	(1, 44, 617, 0, 0),
	(1, 44, 618, 0, 0),
	(1, 44, 619, 0, 0),
	(1, 44, 620, 0, 0),
	(1, 44, 621, 0, 0),
	(1, 44, 622, 0, 0),
	(1, 44, 623, 0, 0),
	(1, 44, 624, 0, 0),
	(1, 44, 625, 0, 0),
	(1, 44, 626, 0, 0),
	(1, 44, 627, 0, 0),
	(1, 44, 628, 0, 0),
	(1, 45, 629, 0, 0),
	(1, 45, 630, 0, 0),
	(1, 45, 631, 0, 0),
	(1, 45, 632, 0, 0),
	(1, 45, 633, 0, 0),
	(1, 45, 634, 0, 0),
	(1, 45, 635, 0, 0),
	(1, 45, 636, 0, 0),
	(1, 45, 637, 0, 0),
	(1, 45, 638, 0, 0),
	(1, 45, 639, 0, 0),
	(1, 45, 640, 0, 0),
	(1, 45, 641, 0, 0),
	(1, 45, 642, 0, 0),
	(1, 45, 643, 0, 0),
	(1, 45, 644, 0, 0),
	(1, 45, 645, 0, 0),
	(1, 47, 646, 0, 0),
	(1, 47, 647, 0, 0),
	(1, 47, 648, 0, 0),
	(1, 47, 649, 0, 0),
	(1, 47, 650, 0, 0),
	(1, 2, 651, 0, 0),
	(1, 29, 652, 0, 0),
	(1, 23, 653, 0, 0),
	(1, 23, 654, 0, 0),
	(1, 23, 655, 0, 0),
	(1, 23, 656, 0, 0),
	(1, 23, 657, 0, 0),
	(1, 23, 658, 0, 0),
	(1, 23, 659, 0, 0),
	(1, 23, 660, 0, 0),
	(1, 23, 661, 0, 0),
	(1, 22, 662, 0, 0),
	(1, 22, 663, 0, 0),
	(1, 22, 664, 0, 0),
	(1, 22, 665, 0, 0),
	(1, 22, 666, 0, 0),
	(1, 22, 667, 0, 0),
	(1, 22, 668, 0, 0),
	(1, 22, 669, 0, 0),
	(1, 22, 670, 0, 0),
	(1, 21, 671, 0, 0),
	(1, 21, 672, 0, 0),
	(1, 21, 673, 0, 0),
	(1, 21, 674, 0, 0),
	(1, 21, 675, 0, 0),
	(1, 21, 676, 0, 0),
	(1, 21, 677, 0, 0),
	(1, 21, 678, 0, 0),
	(1, 21, 679, 0, 0),
	(1, 20, 680, 0, 0),
	(1, 20, 681, 0, 0),
	(1, 20, 682, 0, 0),
	(1, 20, 683, 0, 0),
	(1, 20, 684, 0, 0),
	(1, 20, 685, 0, 0),
	(1, 20, 686, 0, 0),
	(1, 20, 687, 0, 0),
	(1, 20, 688, 0, 0),
	(1, 29, 689, 0, 0),
	(1, 44, 690, 0, 0),
	(1, 42, 691, 0, 0),
	(1, 29, 692, 0, 0),
	(1, 29, 693, 0, 0),
	(1, 29, 694, 0, 0),
	(1, 23, 695, 0, 0),
	(1, 22, 696, 0, 0),
	(1, 21, 697, 0, 0),
	(1, 20, 698, 0, 0),
	(1, 29, 699, 0, 0),
	(1, 6, 700, 0, 0),
	(1, 4, 701, 0, 0),
	(1, 2, 702, 0, 0),
	(1, 29, 703, 0, 0),
	(1, 23, 704, 0, 0),
	(1, 23, 705, 0, 0),
	(1, 21, 706, 0, 0),
	(1, 21, 707, 0, 0),
	(1, 18, 708, 0, 0),
	(1, 7, 709, 0, 0),
	(1, 42, 710, 0, 0),
	(1, 42, 711, 0, 0),
	(1, 23, 712, 0, 0),
	(1, 20, 713, 0, 0),
	(1, 21, 714, 0, 0),
	(1, 22, 715, 0, 0),
	(1, 29, 716, 0, 0),
	(1, 2, 717, 0, 0),
	(1, 13, 718, 0, 0),
	(1, 29, 719, 0, 0),
	(1, 13, 720, 0, 0),
	(1, 29, 721, 0, 0),
	(1, 29, 722, 0, 0),
	(1, 29, 723, 0, 0),
	(1, 9, 729, 0, 0),
	(1, 29, 750, 0, 0),
	(1, 4, 752, 0, 0),
	(1, 4, 754, 0, 0),
	(1, 4, 756, 0, 0),
	(1, 4, 758, 0, 0),
	(1, 4, 760, 0, 0),
	(1, 4, 762, 0, 0),
	(1, 4, 764, 0, 0),
	(1, 4, 766, 0, 0),
	(1, 4, 770, 0, 0),
	(2, 6, 1, 0, 0),
	(2, 6, 2, 0, 0),
	(2, 6, 3, 0, 0),
	(2, 6, 4, 0, 0),
	(2, 6, 5, 0, 0),
	(2, 6, 6, 0, 0),
	(2, 6, 7, 0, 0),
	(2, 6, 8, 0, 0),
	(2, 6, 9, 0, 0),
	(2, 6, 10, 0, 0),
	(2, 6, 11, 0, 0),
	(2, 6, 12, 0, 0),
	(2, 6, 13, 0, 0),
	(2, 6, 14, 0, 0),
	(2, 6, 15, 0, 0),
	(2, 6, 16, 0, 0),
	(2, 6, 17, 0, 0),
	(2, 6, 18, 0, 0),
	(2, 6, 19, 0, 0),
	(2, 6, 20, 0, 0),
	(2, 6, 21, 0, 0),
	(2, 6, 22, 0, 0),
	(2, 6, 23, 0, 0),
	(2, 6, 24, 0, 0),
	(2, 6, 25, 0, 0),
	(2, 6, 26, 0, 0),
	(2, 6, 27, 0, 0),
	(2, 6, 28, 0, 0),
	(2, 6, 29, 0, 0),
	(2, 6, 30, 0, 0),
	(2, 6, 31, 0, 0),
	(2, 6, 32, 0, 0),
	(2, 6, 33, 0, 0),
	(2, 6, 34, 0, 0),
	(2, 6, 35, 0, 0),
	(2, 6, 36, 0, 0),
	(2, 7, 37, 0, 0),
	(2, 7, 38, 0, 0),
	(2, 7, 39, 0, 0),
	(2, 7, 40, 0, 0),
	(2, 7, 41, 0, 0),
	(2, 7, 42, 0, 0),
	(2, 7, 43, 0, 0),
	(2, 7, 44, 0, 0),
	(2, 7, 45, 0, 0),
	(2, 7, 46, 0, 0),
	(2, 7, 47, 0, 0),
	(2, 7, 48, 0, 0),
	(2, 7, 49, 0, 0),
	(2, 7, 50, 0, 0),
	(2, 7, 51, 0, 0),
	(2, 7, 52, 0, 0),
	(2, 7, 53, 0, 0),
	(2, 7, 54, 0, 0),
	(2, 7, 55, 0, 0),
	(2, 7, 56, 0, 0),
	(2, 7, 57, 0, 0),
	(2, 7, 58, 0, 0),
	(2, 7, 59, 0, 0),
	(2, 7, 60, 0, 0),
	(2, 7, 61, 0, 0),
	(2, 7, 62, 0, 0),
	(2, 7, 63, 0, 0),
	(2, 7, 64, 0, 0),
	(2, 7, 65, 0, 0),
	(2, 4, 66, 0, 0),
	(2, 4, 67, 0, 0),
	(2, 4, 68, 0, 0),
	(2, 4, 69, 0, 0),
	(2, 4, 70, 0, 0),
	(2, 4, 71, 0, 0),
	(2, 4, 72, 0, 0),
	(2, 4, 73, 0, 0),
	(2, 4, 74, 0, 0),
	(2, 4, 75, 0, 0),
	(2, 4, 76, 0, 0),
	(2, 4, 77, 0, 0),
	(2, 4, 78, 0, 0),
	(2, 4, 79, 0, 0),
	(2, 4, 80, 0, 0),
	(2, 4, 81, 0, 0),
	(2, 4, 82, 0, 0),
	(2, 4, 83, 0, 0),
	(2, 4, 84, 0, 0),
	(2, 4, 85, 0, 0),
	(2, 4, 86, 0, 0),
	(2, 4, 87, 0, 0),
	(2, 4, 88, 0, 0),
	(2, 4, 89, 0, 0),
	(2, 4, 90, 0, 0),
	(2, 4, 91, 0, 0),
	(2, 4, 92, 0, 0),
	(2, 4, 93, 0, 0),
	(2, 4, 94, 0, 0),
	(2, 4, 95, 0, 0),
	(2, 4, 96, 0, 0),
	(2, 4, 97, 0, 0),
	(2, 4, 98, 0, 0),
	(2, 4, 99, 0, 0),
	(2, 4, 100, 0, 0),
	(2, 4, 101, 0, 0),
	(2, 4, 102, 0, 0),
	(2, 4, 103, 0, 0),
	(2, 4, 104, 0, 0),
	(2, 4, 105, 0, 0),
	(2, 4, 106, 0, 0),
	(2, 4, 107, 0, 0),
	(2, 4, 108, 0, 0),
	(2, 4, 109, 0, 0),
	(2, 2, 110, 0, 0),
	(2, 2, 111, 0, 0),
	(2, 2, 112, 0, 0),
	(2, 2, 113, 0, 0),
	(2, 2, 114, 0, 0),
	(2, 2, 115, 0, 0),
	(2, 2, 116, 0, 0),
	(2, 2, 117, 0, 0),
	(2, 2, 118, 0, 0),
	(2, 2, 119, 0, 0),
	(2, 2, 120, 0, 0),
	(2, 2, 121, 0, 0),
	(2, 2, 122, 0, 0),
	(2, 2, 123, 0, 0),
	(2, 2, 124, 0, 0),
	(2, 2, 125, 0, 0),
	(2, 26, 126, 0, 0),
	(2, 26, 127, 0, 0),
	(2, 26, 128, 0, 0),
	(2, 26, 129, 0, 0),
	(2, 26, 130, 0, 0),
	(2, 26, 131, 0, 0),
	(2, 26, 132, 0, 0),
	(2, 26, 133, 0, 0),
	(2, 26, 134, 0, 0),
	(2, 26, 135, 0, 0),
	(2, 26, 136, 0, 0),
	(2, 26, 137, 0, 0),
	(2, 26, 138, 0, 0),
	(2, 26, 139, 0, 0),
	(2, 26, 140, 0, 0),
	(2, 26, 141, 0, 0),
	(2, 26, 142, 0, 0),
	(2, 26, 143, 0, 0),
	(2, 26, 144, 0, 0),
	(2, 26, 145, 0, 0),
	(2, 26, 146, 0, 0),
	(2, 26, 147, 0, 0),
	(2, 26, 148, 0, 0),
	(2, 26, 149, 0, 0),
	(2, 26, 150, 0, 0),
	(2, 4, 151, 0, 0),
	(2, 6, 152, 0, 0),
	(2, 7, 153, 0, 0),
	(2, 26, 154, 0, 0),
	(2, 13, 155, 0, 0),
	(2, 13, 156, 0, 0),
	(2, 13, 157, 0, 0),
	(2, 13, 158, 0, 0),
	(2, 13, 159, 0, 0),
	(2, 13, 160, 0, 0),
	(2, 13, 161, 0, 0),
	(2, 13, 162, 0, 0),
	(2, 13, 163, 0, 0),
	(2, 13, 164, 0, 0),
	(2, 13, 165, 0, 0),
	(2, 13, 166, 0, 0),
	(2, 13, 167, 0, 0),
	(2, 13, 168, 0, 0),
	(2, 13, 169, 0, 0),
	(2, 13, 170, 0, 0),
	(2, 13, 171, 0, 0),
	(2, 13, 172, 0, 0),
	(2, 14, 173, 0, 0),
	(2, 14, 174, 0, 0),
	(2, 14, 175, 0, 0),
	(2, 14, 176, 0, 0),
	(2, 14, 177, 0, 0),
	(2, 14, 178, 0, 0),
	(2, 14, 179, 0, 0),
	(2, 14, 180, 0, 0),
	(2, 14, 181, 0, 0),
	(2, 14, 182, 0, 0),
	(2, 14, 183, 0, 0),
	(2, 14, 184, 0, 0),
	(2, 14, 185, 0, 0),
	(2, 14, 186, 0, 0),
	(2, 14, 187, 0, 0),
	(2, 14, 188, 0, 0),
	(2, 14, 189, 0, 0),
	(2, 14, 190, 0, 0),
	(2, 14, 191, 0, 0),
	(2, 14, 192, 0, 0),
	(2, 14, 193, 0, 0),
	(2, 14, 194, 0, 0),
	(2, 14, 195, 0, 0),
	(2, 14, 196, 0, 0),
	(2, 14, 197, 0, 0),
	(2, 14, 198, 0, 0),
	(2, 14, 199, 0, 0),
	(2, 14, 200, 0, 0),
	(2, 14, 201, 0, 0),
	(2, 14, 202, 0, 0),
	(2, 14, 203, 0, 0),
	(2, 8, 204, 0, 0),
	(2, 8, 205, 0, 0),
	(2, 8, 206, 0, 0),
	(2, 8, 207, 0, 0),
	(2, 8, 208, 0, 0),
	(2, 8, 209, 0, 0),
	(2, 8, 210, 0, 0),
	(2, 8, 211, 0, 0),
	(2, 8, 212, 0, 0),
	(2, 8, 213, 0, 0),
	(2, 8, 214, 0, 0),
	(2, 8, 215, 0, 0),
	(2, 8, 216, 0, 0),
	(2, 8, 217, 0, 0),
	(2, 8, 218, 0, 0),
	(2, 10, 219, 0, 0),
	(2, 10, 220, 0, 0),
	(2, 10, 221, 0, 0),
	(2, 10, 222, 0, 0),
	(2, 10, 223, 0, 0),
	(2, 10, 224, 0, 0),
	(2, 10, 225, 0, 0),
	(2, 10, 226, 0, 0),
	(2, 10, 227, 0, 0),
	(2, 10, 228, 0, 0),
	(2, 10, 229, 0, 0),
	(2, 10, 230, 0, 0),
	(2, 9, 231, 0, 0),
	(2, 9, 232, 0, 0),
	(2, 9, 233, 0, 0),
	(2, 9, 234, 0, 0),
	(2, 9, 235, 0, 0),
	(2, 9, 236, 0, 0),
	(2, 9, 237, 0, 0),
	(2, 9, 238, 0, 0),
	(2, 9, 239, 0, 0),
	(2, 9, 240, 0, 0),
	(2, 9, 241, 0, 0),
	(2, 9, 242, 0, 0),
	(2, 9, 243, 0, 0),
	(2, 9, 244, 0, 0),
	(2, 9, 245, 0, 0),
	(2, 9, 246, 0, 0),
	(2, 9, 247, 0, 0),
	(2, 9, 248, 0, 0),
	(2, 9, 249, 0, 0),
	(2, 9, 250, 0, 0),
	(2, 9, 251, 0, 0),
	(2, 9, 252, 0, 0),
	(2, 9, 253, 0, 0),
	(2, 9, 254, 0, 0),
	(2, 16, 255, 0, 0),
	(2, 16, 256, 0, 0),
	(2, 16, 257, 0, 0),
	(2, 16, 258, 0, 0),
	(2, 16, 259, 0, 0),
	(2, 16, 260, 0, 0),
	(2, 16, 261, 0, 0),
	(2, 16, 262, 0, 0),
	(2, 16, 263, 0, 0),
	(2, 16, 264, 0, 0),
	(2, 16, 265, 0, 0),
	(2, 16, 266, 0, 0),
	(2, 16, 267, 0, 0),
	(2, 16, 268, 0, 0),
	(2, 16, 269, 0, 0),
	(2, 16, 270, 0, 0),
	(2, 16, 271, 0, 0),
	(2, 16, 272, 0, 0),
	(2, 16, 273, 0, 0),
	(2, 16, 274, 0, 0),
	(2, 16, 275, 0, 0),
	(2, 16, 276, 0, 0),
	(2, 16, 277, 0, 0),
	(2, 15, 278, 0, 0),
	(2, 15, 279, 0, 0),
	(2, 15, 280, 0, 0),
	(2, 15, 281, 0, 0),
	(2, 15, 282, 0, 0),
	(2, 15, 283, 0, 0),
	(2, 15, 284, 0, 0),
	(2, 15, 285, 0, 0),
	(2, 15, 286, 0, 0),
	(2, 15, 287, 0, 0),
	(2, 18, 288, 0, 0),
	(2, 18, 289, 0, 0),
	(2, 18, 290, 0, 0),
	(2, 18, 291, 0, 0),
	(2, 18, 292, 0, 0),
	(2, 18, 293, 0, 0),
	(2, 18, 294, 0, 0),
	(2, 18, 295, 0, 0),
	(2, 18, 296, 0, 0),
	(2, 18, 297, 0, 0),
	(2, 18, 298, 0, 0),
	(2, 18, 299, 0, 0),
	(2, 18, 300, 0, 0),
	(2, 18, 301, 0, 0),
	(2, 18, 302, 0, 0),
	(2, 18, 303, 0, 0),
	(2, 18, 304, 0, 0),
	(2, 19, 305, 0, 0),
	(2, 19, 306, 0, 0),
	(2, 19, 307, 0, 0),
	(2, 19, 308, 0, 0),
	(2, 19, 309, 0, 0),
	(2, 19, 310, 0, 0),
	(2, 19, 311, 0, 0),
	(2, 19, 312, 0, 0),
	(2, 20, 313, 0, 0),
	(2, 20, 314, 0, 0),
	(2, 20, 315, 0, 0),
	(2, 20, 316, 0, 0),
	(2, 20, 317, 0, 0),
	(2, 20, 318, 0, 0),
	(2, 20, 319, 0, 0),
	(2, 20, 320, 0, 0),
	(2, 20, 321, 0, 0),
	(2, 20, 322, 0, 0),
	(2, 20, 323, 0, 0),
	(2, 20, 324, 0, 0),
	(2, 20, 325, 0, 0),
	(2, 20, 326, 0, 0),
	(2, 20, 327, 0, 0),
	(2, 20, 328, 0, 0),
	(2, 20, 329, 0, 0),
	(2, 20, 330, 0, 0),
	(2, 20, 331, 0, 0),
	(2, 20, 332, 0, 0),
	(2, 20, 333, 0, 0),
	(2, 20, 334, 0, 0),
	(2, 20, 335, 0, 0),
	(2, 20, 336, 0, 0),
	(2, 20, 337, 0, 0),
	(2, 20, 338, 0, 0),
	(2, 20, 339, 0, 0),
	(2, 20, 340, 0, 0),
	(2, 20, 341, 0, 0),
	(2, 20, 342, 0, 0),
	(2, 20, 343, 0, 0),
	(2, 20, 344, 0, 0),
	(2, 20, 345, 0, 0),
	(2, 20, 346, 0, 0),
	(2, 20, 347, 0, 0),
	(2, 20, 348, 0, 0),
	(2, 20, 349, 0, 0),
	(2, 21, 350, 0, 0),
	(2, 21, 351, 0, 0),
	(2, 21, 352, 0, 0),
	(2, 21, 353, 0, 0),
	(2, 21, 354, 0, 0),
	(2, 21, 355, 0, 0),
	(2, 21, 356, 0, 0),
	(2, 21, 357, 0, 0),
	(2, 21, 358, 0, 0),
	(2, 21, 359, 0, 0),
	(2, 21, 360, 0, 0),
	(2, 21, 361, 0, 0),
	(2, 21, 362, 0, 0),
	(2, 21, 363, 0, 0),
	(2, 21, 364, 0, 0),
	(2, 21, 365, 0, 0),
	(2, 21, 366, 0, 0),
	(2, 21, 367, 0, 0),
	(2, 21, 368, 0, 0),
	(2, 21, 369, 0, 0),
	(2, 21, 370, 0, 0),
	(2, 21, 371, 0, 0),
	(2, 21, 372, 0, 0),
	(2, 21, 373, 0, 0),
	(2, 21, 374, 0, 0),
	(2, 21, 375, 0, 0),
	(2, 21, 376, 0, 0),
	(2, 21, 377, 0, 0),
	(2, 21, 378, 0, 0),
	(2, 21, 379, 0, 0),
	(2, 21, 380, 0, 0),
	(2, 21, 381, 0, 0),
	(2, 21, 382, 0, 0),
	(2, 21, 383, 0, 0),
	(2, 21, 384, 0, 0),
	(2, 21, 385, 0, 0),
	(2, 21, 386, 0, 0),
	(2, 21, 387, 0, 0),
	(2, 22, 388, 0, 0),
	(2, 22, 389, 0, 0),
	(2, 22, 390, 0, 0),
	(2, 22, 391, 0, 0),
	(2, 22, 392, 0, 0),
	(2, 22, 393, 0, 0),
	(2, 22, 394, 0, 0),
	(2, 22, 395, 0, 0),
	(2, 22, 396, 0, 0),
	(2, 22, 397, 0, 0),
	(2, 22, 398, 0, 0),
	(2, 22, 399, 0, 0),
	(2, 22, 400, 0, 0),
	(2, 22, 401, 0, 0),
	(2, 22, 402, 0, 0),
	(2, 22, 403, 0, 0),
	(2, 22, 404, 0, 0),
	(2, 22, 405, 0, 0),
	(2, 22, 406, 0, 0),
	(2, 22, 407, 0, 0),
	(2, 22, 408, 0, 0),
	(2, 22, 409, 0, 0),
	(2, 22, 410, 0, 0),
	(2, 22, 411, 0, 0),
	(2, 22, 412, 0, 0),
	(2, 22, 413, 0, 0),
	(2, 22, 414, 0, 0),
	(2, 22, 415, 0, 0),
	(2, 22, 416, 0, 0),
	(2, 22, 417, 0, 0),
	(2, 22, 418, 0, 0),
	(2, 22, 419, 0, 0),
	(2, 22, 420, 0, 0),
	(2, 22, 421, 0, 0),
	(2, 22, 422, 0, 0),
	(2, 22, 423, 0, 0),
	(2, 22, 424, 0, 0),
	(2, 22, 425, 0, 0),
	(2, 22, 426, 0, 0),
	(2, 22, 427, 0, 0),
	(2, 22, 428, 0, 0),
	(2, 22, 429, 0, 0),
	(2, 22, 430, 0, 0),
	(2, 22, 431, 0, 0),
	(2, 22, 432, 0, 0),
	(2, 22, 433, 0, 0),
	(2, 22, 434, 0, 0),
	(2, 23, 435, 0, 0),
	(2, 23, 436, 0, 0),
	(2, 23, 437, 0, 0),
	(2, 23, 438, 0, 0),
	(2, 23, 439, 0, 0),
	(2, 23, 440, 0, 0),
	(2, 23, 441, 0, 0),
	(2, 23, 442, 0, 0),
	(2, 23, 443, 0, 0),
	(2, 23, 444, 0, 0),
	(2, 23, 445, 0, 0),
	(2, 23, 446, 0, 0),
	(2, 23, 447, 0, 0),
	(2, 23, 448, 0, 0),
	(2, 23, 449, 0, 0),
	(2, 23, 450, 0, 0),
	(2, 23, 451, 0, 0),
	(2, 23, 452, 0, 0),
	(2, 23, 453, 0, 0),
	(2, 23, 454, 0, 0),
	(2, 23, 455, 0, 0),
	(2, 23, 456, 0, 0),
	(2, 23, 457, 0, 0),
	(2, 23, 458, 0, 0),
	(2, 23, 459, 0, 0),
	(2, 23, 460, 0, 0),
	(2, 23, 461, 0, 0),
	(2, 23, 462, 0, 0),
	(2, 23, 463, 0, 0),
	(2, 23, 464, 0, 0),
	(2, 23, 465, 0, 0),
	(2, 23, 466, 0, 0),
	(2, 23, 467, 0, 0),
	(2, 23, 468, 0, 0),
	(2, 23, 469, 0, 0),
	(2, 23, 470, 0, 0),
	(2, 23, 471, 0, 0),
	(2, 23, 472, 0, 0),
	(2, 23, 473, 0, 0),
	(2, 29, 474, 0, 0),
	(2, 29, 478, 0, 0),
	(2, 29, 479, 0, 0),
	(2, 29, 481, 0, 0),
	(2, 29, 488, 0, 0),
	(2, 29, 489, 0, 0),
	(2, 29, 490, 0, 0),
	(2, 29, 491, 0, 0),
	(2, 29, 493, 0, 0),
	(2, 29, 494, 0, 0),
	(2, 29, 495, 0, 0),
	(2, 29, 496, 0, 0),
	(2, 29, 497, 0, 0),
	(2, 29, 502, 0, 0),
	(2, 29, 503, 0, 0),
	(2, 29, 504, 0, 0),
	(2, 29, 505, 0, 0),
	(2, 29, 513, 0, 0),
	(2, 10, 518, 0, 0),
	(2, 10, 519, 0, 0),
	(2, 10, 520, 0, 0),
	(2, 10, 521, 0, 0),
	(2, 10, 522, 0, 0),
	(2, 10, 523, 0, 0),
	(2, 34, 524, 0, 0),
	(2, 34, 525, 0, 0),
	(2, 34, 526, 0, 0),
	(2, 34, 527, 0, 0),
	(2, 34, 528, 0, 0),
	(2, 34, 529, 0, 0),
	(2, 34, 530, 0, 0),
	(2, 34, 531, 0, 0),
	(2, 34, 532, 0, 0),
	(2, 34, 533, 0, 0),
	(2, 34, 534, 0, 0),
	(2, 34, 535, 0, 0),
	(2, 34, 536, 0, 0),
	(2, 34, 537, 0, 0),
	(2, 34, 538, 0, 0),
	(2, 34, 539, 0, 0),
	(2, 29, 540, 0, 0),
	(2, 35, 541, 0, 0),
	(2, 35, 542, 0, 0),
	(2, 35, 543, 0, 0),
	(2, 35, 544, 0, 0),
	(2, 35, 545, 0, 0),
	(2, 35, 546, 0, 0),
	(2, 35, 547, 0, 0),
	(2, 35, 548, 0, 0),
	(2, 35, 549, 0, 0),
	(2, 35, 550, 0, 0),
	(2, 35, 551, 0, 0),
	(2, 35, 552, 0, 0),
	(2, 35, 553, 0, 0),
	(2, 35, 554, 0, 0),
	(2, 35, 555, 0, 0),
	(2, 35, 556, 0, 0),
	(2, 35, 557, 0, 0),
	(2, 35, 558, 0, 0),
	(2, 35, 559, 0, 0),
	(2, 36, 560, 0, 0),
	(2, 36, 561, 0, 0),
	(2, 36, 562, 0, 0),
	(2, 36, 563, 0, 0),
	(2, 36, 564, 0, 0),
	(2, 36, 565, 0, 0),
	(2, 36, 566, 0, 0),
	(2, 36, 567, 0, 0),
	(2, 36, 568, 0, 0),
	(2, 36, 569, 0, 0),
	(2, 36, 570, 0, 0),
	(2, 36, 571, 0, 0),
	(2, 36, 572, 0, 0),
	(2, 36, 573, 0, 0),
	(2, 36, 574, 0, 0),
	(2, 36, 575, 0, 0),
	(2, 36, 576, 0, 0),
	(2, 36, 577, 0, 0),
	(2, 36, 578, 0, 0),
	(2, 38, 579, 0, 0),
	(2, 38, 580, 0, 0),
	(2, 38, 581, 0, 0),
	(2, 38, 582, 0, 0),
	(2, 38, 583, 0, 0),
	(2, 38, 584, 0, 0),
	(2, 38, 585, 0, 0),
	(2, 38, 586, 0, 0),
	(2, 38, 587, 0, 0),
	(2, 38, 588, 0, 0),
	(2, 38, 589, 0, 0),
	(2, 38, 590, 0, 0),
	(2, 38, 591, 0, 0),
	(2, 38, 592, 0, 0),
	(2, 38, 593, 0, 0),
	(2, 38, 594, 0, 0),
	(2, 38, 595, 0, 0),
	(2, 38, 596, 0, 0),
	(2, 42, 597, 0, 0),
	(2, 42, 598, 0, 0),
	(2, 42, 599, 0, 0),
	(2, 42, 600, 0, 0),
	(2, 42, 601, 0, 0),
	(2, 42, 602, 0, 0),
	(2, 42, 603, 0, 0),
	(2, 43, 604, 0, 0),
	(2, 43, 605, 0, 0),
	(2, 43, 606, 0, 0),
	(2, 43, 607, 0, 0),
	(2, 43, 608, 0, 0),
	(2, 43, 609, 0, 0),
	(2, 43, 610, 0, 0),
	(2, 43, 611, 0, 0),
	(2, 43, 612, 0, 0),
	(2, 43, 613, 0, 0),
	(2, 44, 614, 0, 0),
	(2, 44, 615, 0, 0),
	(2, 44, 616, 0, 0),
	(2, 44, 617, 0, 0),
	(2, 44, 618, 0, 0),
	(2, 44, 619, 0, 0),
	(2, 44, 620, 0, 0),
	(2, 44, 621, 0, 0),
	(2, 44, 622, 0, 0),
	(2, 44, 623, 0, 0),
	(2, 44, 624, 0, 0),
	(2, 44, 625, 0, 0),
	(2, 44, 626, 0, 0),
	(2, 44, 627, 0, 0),
	(2, 44, 628, 0, 0),
	(2, 45, 629, 0, 0),
	(2, 45, 630, 0, 0),
	(2, 45, 631, 0, 0),
	(2, 45, 632, 0, 0),
	(2, 45, 633, 0, 0),
	(2, 45, 634, 0, 0),
	(2, 45, 635, 0, 0),
	(2, 45, 636, 0, 0),
	(2, 45, 637, 0, 0),
	(2, 45, 638, 0, 0),
	(2, 45, 639, 0, 0),
	(2, 45, 640, 0, 0),
	(2, 45, 641, 0, 0),
	(2, 45, 642, 0, 0),
	(2, 45, 643, 0, 0),
	(2, 45, 644, 0, 0),
	(2, 45, 645, 0, 0),
	(2, 47, 646, 0, 0),
	(2, 47, 647, 0, 0),
	(2, 47, 648, 0, 0),
	(2, 47, 649, 0, 0),
	(2, 47, 650, 0, 0),
	(2, 2, 651, 0, 0),
	(2, 29, 652, 0, 0),
	(2, 23, 653, 0, 0),
	(2, 23, 654, 0, 0),
	(2, 23, 655, 0, 0),
	(2, 23, 656, 0, 0),
	(2, 23, 657, 0, 0),
	(2, 23, 658, 0, 0),
	(2, 23, 659, 0, 0),
	(2, 23, 660, 0, 0),
	(2, 23, 661, 0, 0),
	(2, 22, 662, 0, 0),
	(2, 22, 663, 0, 0),
	(2, 22, 664, 0, 0),
	(2, 22, 665, 0, 0),
	(2, 22, 666, 0, 0),
	(2, 22, 667, 0, 0),
	(2, 22, 668, 0, 0),
	(2, 22, 669, 0, 0),
	(2, 22, 670, 0, 0),
	(2, 21, 671, 0, 0),
	(2, 21, 672, 0, 0),
	(2, 21, 673, 0, 0),
	(2, 21, 674, 0, 0),
	(2, 21, 675, 0, 0),
	(2, 21, 676, 0, 0),
	(2, 21, 677, 0, 0),
	(2, 21, 678, 0, 0),
	(2, 21, 679, 0, 0),
	(2, 20, 680, 0, 0),
	(2, 20, 681, 0, 0),
	(2, 20, 682, 0, 0),
	(2, 20, 683, 0, 0),
	(2, 20, 684, 0, 0),
	(2, 20, 685, 0, 0),
	(2, 20, 686, 0, 0),
	(2, 20, 687, 0, 0),
	(2, 20, 688, 0, 0),
	(2, 29, 689, 0, 0),
	(2, 44, 690, 0, 0),
	(2, 42, 691, 0, 0),
	(2, 29, 692, 0, 0),
	(2, 29, 693, 0, 0),
	(2, 29, 694, 0, 0),
	(2, 23, 695, 0, 0),
	(2, 22, 696, 0, 0),
	(2, 21, 697, 0, 0),
	(2, 20, 698, 0, 0),
	(2, 29, 699, 0, 0),
	(2, 6, 700, 0, 0),
	(2, 4, 701, 0, 0),
	(2, 2, 702, 0, 0),
	(2, 29, 703, 0, 0),
	(2, 23, 704, 0, 0),
	(2, 23, 705, 0, 0),
	(2, 21, 706, 0, 0),
	(2, 21, 707, 0, 0),
	(2, 18, 708, 0, 0),
	(2, 7, 709, 0, 0),
	(2, 42, 710, 0, 0),
	(2, 42, 711, 0, 0),
	(2, 23, 712, 0, 0),
	(2, 20, 713, 0, 0),
	(2, 21, 714, 0, 0),
	(2, 22, 715, 0, 0),
	(2, 29, 716, 0, 0),
	(2, 2, 717, 0, 0),
	(2, 13, 718, 0, 0),
	(2, 29, 719, 0, 0),
	(2, 13, 720, 0, 0),
	(2, 29, 721, 0, 0),
	(2, 29, 722, 0, 0),
	(2, 29, 723, 0, 0),
	(2, 9, 729, 0, 0),
	(2, 29, 750, 0, 0),
	(2, 4, 752, 0, 0),
	(2, 4, 754, 0, 0),
	(2, 4, 756, 0, 0),
	(2, 4, 758, 0, 0),
	(2, 4, 760, 0, 0),
	(2, 4, 762, 0, 0),
	(2, 4, 764, 0, 0),
	(2, 4, 766, 0, 0),
	(2, 4, 770, 0, 0),
	(3, 6, 1, 0, 0),
	(3, 6, 2, 0, 0),
	(3, 6, 3, 0, 0),
	(3, 6, 4, 0, 0),
	(3, 6, 5, 0, 0),
	(3, 6, 6, 0, 0),
	(3, 6, 7, 0, 0),
	(3, 6, 8, 0, 0),
	(3, 6, 9, 0, 0),
	(3, 6, 10, 0, 0),
	(3, 6, 11, 0, 0),
	(3, 6, 12, 0, 0),
	(3, 6, 13, 0, 0),
	(3, 6, 14, 0, 0),
	(3, 6, 15, 0, 0),
	(3, 6, 16, 0, 0),
	(3, 6, 17, 0, 0),
	(3, 6, 18, 0, 0),
	(3, 6, 19, 0, 0),
	(3, 6, 20, 0, 0),
	(3, 6, 21, 0, 0),
	(3, 6, 22, 0, 0),
	(3, 6, 23, 0, 0),
	(3, 6, 24, 0, 0),
	(3, 6, 25, 0, 0),
	(3, 6, 26, 0, 0),
	(3, 6, 27, 0, 0),
	(3, 6, 28, 0, 0),
	(3, 6, 29, 0, 0),
	(3, 6, 30, 0, 0),
	(3, 6, 31, 0, 0),
	(3, 6, 32, 0, 0),
	(3, 6, 33, 0, 0),
	(3, 6, 34, 0, 0),
	(3, 6, 35, 0, 0),
	(3, 6, 36, 0, 0),
	(3, 7, 37, 0, 0),
	(3, 7, 38, 0, 0),
	(3, 7, 39, 0, 0),
	(3, 7, 40, 0, 0),
	(3, 7, 41, 0, 0),
	(3, 7, 42, 0, 0),
	(3, 7, 43, 0, 0),
	(3, 7, 44, 0, 0),
	(3, 7, 45, 0, 0),
	(3, 7, 46, 0, 0),
	(3, 7, 47, 0, 0),
	(3, 7, 48, 0, 0),
	(3, 7, 49, 0, 0),
	(3, 7, 50, 0, 0),
	(3, 7, 51, 0, 0),
	(3, 7, 52, 0, 0),
	(3, 7, 53, 0, 0),
	(3, 7, 54, 0, 0),
	(3, 7, 55, 0, 0),
	(3, 7, 56, 0, 0),
	(3, 7, 57, 0, 0),
	(3, 7, 58, 0, 0),
	(3, 7, 59, 0, 0),
	(3, 7, 60, 0, 0),
	(3, 7, 61, 0, 0),
	(3, 7, 62, 0, 0),
	(3, 7, 63, 0, 0),
	(3, 7, 64, 0, 0),
	(3, 7, 65, 0, 0),
	(3, 4, 66, 0, 0),
	(3, 4, 67, 0, 0),
	(3, 4, 68, 0, 0),
	(3, 4, 69, 0, 0),
	(3, 4, 70, 0, 0),
	(3, 4, 71, 0, 0),
	(3, 4, 72, 0, 0),
	(3, 4, 73, 0, 0),
	(3, 4, 74, 0, 0),
	(3, 4, 75, 0, 0),
	(3, 4, 76, 0, 0),
	(3, 4, 77, 0, 0),
	(3, 4, 78, 0, 0),
	(3, 4, 79, 0, 0),
	(3, 4, 80, 0, 0),
	(3, 4, 81, 0, 0),
	(3, 4, 82, 0, 0),
	(3, 4, 83, 0, 0),
	(3, 4, 84, 0, 0),
	(3, 4, 85, 0, 0),
	(3, 4, 86, 0, 0),
	(3, 4, 87, 0, 0),
	(3, 4, 88, 0, 0),
	(3, 4, 89, 0, 0),
	(3, 4, 90, 0, 0),
	(3, 4, 91, 0, 0),
	(3, 4, 92, 0, 0),
	(3, 4, 93, 0, 0),
	(3, 4, 94, 0, 0),
	(3, 4, 95, 0, 0),
	(3, 4, 96, 0, 0),
	(3, 4, 97, 0, 0),
	(3, 4, 98, 0, 0),
	(3, 4, 99, 0, 0),
	(3, 4, 100, 0, 0),
	(3, 4, 101, 0, 0),
	(3, 4, 102, 0, 0),
	(3, 4, 103, 0, 0),
	(3, 4, 104, 0, 0),
	(3, 4, 105, 0, 0),
	(3, 4, 106, 0, 0),
	(3, 4, 107, 0, 0),
	(3, 4, 108, 0, 0),
	(3, 4, 109, 0, 0),
	(3, 2, 110, 0, 0),
	(3, 2, 111, 0, 0),
	(3, 2, 112, 0, 0),
	(3, 2, 113, 0, 0),
	(3, 2, 114, 0, 0),
	(3, 2, 115, 0, 0),
	(3, 2, 116, 0, 0),
	(3, 2, 117, 0, 0),
	(3, 2, 118, 0, 0),
	(3, 2, 119, 0, 0),
	(3, 2, 120, 0, 0),
	(3, 2, 121, 0, 0),
	(3, 2, 122, 0, 0),
	(3, 2, 123, 0, 0),
	(3, 2, 124, 0, 0),
	(3, 2, 125, 0, 0),
	(3, 26, 126, 0, 0),
	(3, 26, 127, 0, 0),
	(3, 26, 128, 0, 0),
	(3, 26, 129, 0, 0),
	(3, 26, 130, 0, 0),
	(3, 26, 131, 0, 0),
	(3, 26, 132, 0, 0),
	(3, 26, 133, 0, 0),
	(3, 26, 134, 0, 0),
	(3, 26, 135, 0, 0),
	(3, 26, 136, 0, 0),
	(3, 26, 137, 0, 0),
	(3, 26, 138, 0, 0),
	(3, 26, 139, 0, 0),
	(3, 26, 140, 0, 0),
	(3, 26, 141, 0, 0),
	(3, 26, 142, 0, 0),
	(3, 26, 143, 0, 0),
	(3, 26, 144, 0, 0),
	(3, 26, 145, 0, 0),
	(3, 26, 146, 0, 0),
	(3, 26, 147, 0, 0),
	(3, 26, 148, 0, 0),
	(3, 26, 149, 0, 0),
	(3, 26, 150, 0, 0),
	(3, 4, 151, 0, 0),
	(3, 6, 152, 0, 0),
	(3, 7, 153, 0, 0),
	(3, 26, 154, 0, 0),
	(3, 13, 155, 0, 0),
	(3, 13, 156, 0, 0),
	(3, 13, 157, 0, 0),
	(3, 13, 158, 0, 0),
	(3, 13, 159, 0, 0),
	(3, 13, 160, 0, 0),
	(3, 13, 161, 0, 0),
	(3, 13, 162, 0, 0),
	(3, 13, 163, 0, 0),
	(3, 13, 164, 0, 0),
	(3, 13, 165, 0, 0),
	(3, 13, 166, 0, 0),
	(3, 13, 167, 0, 0),
	(3, 13, 168, 0, 0),
	(3, 13, 169, 0, 0),
	(3, 13, 170, 0, 0),
	(3, 13, 171, 0, 0),
	(3, 13, 172, 0, 0),
	(3, 14, 173, 0, 0),
	(3, 14, 174, 0, 0),
	(3, 14, 175, 0, 0),
	(3, 14, 176, 0, 0),
	(3, 14, 177, 0, 0),
	(3, 14, 178, 0, 0),
	(3, 14, 179, 0, 0),
	(3, 14, 180, 0, 0),
	(3, 14, 181, 0, 0),
	(3, 14, 182, 0, 0),
	(3, 14, 183, 0, 0),
	(3, 14, 184, 0, 0),
	(3, 14, 185, 0, 0),
	(3, 14, 186, 0, 0),
	(3, 14, 187, 0, 0),
	(3, 14, 188, 0, 0),
	(3, 14, 189, 0, 0),
	(3, 14, 190, 0, 0),
	(3, 14, 191, 0, 0),
	(3, 14, 192, 0, 0),
	(3, 14, 193, 0, 0),
	(3, 14, 194, 0, 0),
	(3, 14, 195, 0, 0),
	(3, 14, 196, 0, 0),
	(3, 14, 197, 0, 0),
	(3, 14, 198, 0, 0),
	(3, 14, 199, 0, 0),
	(3, 14, 200, 0, 0),
	(3, 14, 201, 0, 0),
	(3, 14, 202, 0, 0),
	(3, 14, 203, 0, 0),
	(3, 8, 204, 0, 0),
	(3, 8, 205, 0, 0),
	(3, 8, 206, 0, 0),
	(3, 8, 207, 0, 0),
	(3, 8, 208, 0, 0),
	(3, 8, 209, 0, 0),
	(3, 8, 210, 0, 0),
	(3, 8, 211, 0, 0),
	(3, 8, 212, 0, 0),
	(3, 8, 213, 0, 0),
	(3, 8, 214, 0, 0),
	(3, 8, 215, 0, 0),
	(3, 8, 216, 0, 0),
	(3, 8, 217, 0, 0),
	(3, 8, 218, 0, 0),
	(3, 10, 219, 0, 0),
	(3, 10, 220, 0, 0),
	(3, 10, 221, 0, 0),
	(3, 10, 222, 0, 0),
	(3, 10, 223, 0, 0),
	(3, 10, 224, 0, 0),
	(3, 10, 225, 0, 0),
	(3, 10, 226, 0, 0),
	(3, 10, 227, 0, 0),
	(3, 10, 228, 0, 0),
	(3, 10, 229, 0, 0),
	(3, 10, 230, 0, 0),
	(3, 9, 231, 0, 0),
	(3, 9, 232, 0, 0),
	(3, 9, 233, 0, 0),
	(3, 9, 234, 0, 0),
	(3, 9, 235, 0, 0),
	(3, 9, 236, 0, 0),
	(3, 9, 237, 0, 0),
	(3, 9, 238, 0, 0),
	(3, 9, 239, 0, 0),
	(3, 9, 240, 0, 0),
	(3, 9, 241, 0, 0),
	(3, 9, 242, 0, 0),
	(3, 9, 243, 0, 0),
	(3, 9, 244, 0, 0),
	(3, 9, 245, 0, 0),
	(3, 9, 246, 0, 0),
	(3, 9, 247, 0, 0),
	(3, 9, 248, 0, 0),
	(3, 9, 249, 0, 0),
	(3, 9, 250, 0, 0),
	(3, 9, 251, 0, 0),
	(3, 9, 252, 0, 0),
	(3, 9, 253, 0, 0),
	(3, 9, 254, 0, 0),
	(3, 16, 255, 0, 0),
	(3, 16, 256, 0, 0),
	(3, 16, 257, 0, 0),
	(3, 16, 258, 0, 0),
	(3, 16, 259, 0, 0),
	(3, 16, 260, 0, 0),
	(3, 16, 261, 0, 0),
	(3, 16, 262, 0, 0),
	(3, 16, 263, 0, 0),
	(3, 16, 264, 0, 0),
	(3, 16, 265, 0, 0),
	(3, 16, 266, 0, 0),
	(3, 16, 267, 0, 0),
	(3, 16, 268, 0, 0),
	(3, 16, 269, 0, 0),
	(3, 16, 270, 0, 0),
	(3, 16, 271, 0, 0),
	(3, 16, 272, 0, 0),
	(3, 16, 273, 0, 0),
	(3, 16, 274, 0, 0),
	(3, 16, 275, 0, 0),
	(3, 16, 276, 0, 0),
	(3, 16, 277, 0, 0),
	(3, 15, 278, 0, 0),
	(3, 15, 279, 0, 0),
	(3, 15, 280, 0, 0),
	(3, 15, 281, 0, 0),
	(3, 15, 282, 0, 0),
	(3, 15, 283, 0, 0),
	(3, 15, 284, 0, 0),
	(3, 15, 285, 0, 0),
	(3, 15, 286, 0, 0),
	(3, 15, 287, 0, 0),
	(3, 18, 288, 0, 0),
	(3, 18, 289, 0, 0),
	(3, 18, 290, 0, 0),
	(3, 18, 291, 0, 0),
	(3, 18, 292, 0, 0),
	(3, 18, 293, 0, 0),
	(3, 18, 294, 0, 0),
	(3, 18, 295, 0, 0),
	(3, 18, 296, 0, 0),
	(3, 18, 297, 0, 0),
	(3, 18, 298, 0, 0),
	(3, 18, 299, 0, 0),
	(3, 18, 300, 0, 0),
	(3, 18, 301, 0, 0),
	(3, 18, 302, 0, 0),
	(3, 18, 303, 0, 0),
	(3, 18, 304, 0, 0),
	(3, 19, 305, 0, 0),
	(3, 19, 306, 0, 0),
	(3, 19, 307, 0, 0),
	(3, 19, 308, 0, 0),
	(3, 19, 309, 0, 0),
	(3, 19, 310, 0, 0),
	(3, 19, 311, 0, 0),
	(3, 19, 312, 0, 0),
	(3, 20, 313, 0, 0),
	(3, 20, 314, 0, 0),
	(3, 20, 315, 0, 0),
	(3, 20, 316, 0, 0),
	(3, 20, 317, 0, 0),
	(3, 20, 318, 0, 0),
	(3, 20, 319, 0, 0),
	(3, 20, 320, 0, 0),
	(3, 20, 321, 0, 0),
	(3, 20, 322, 0, 0),
	(3, 20, 323, 0, 0),
	(3, 20, 324, 0, 0),
	(3, 20, 325, 0, 0),
	(3, 20, 326, 0, 0),
	(3, 20, 327, 0, 0),
	(3, 20, 328, 0, 0),
	(3, 20, 329, 0, 0),
	(3, 20, 330, 0, 0),
	(3, 20, 331, 0, 0),
	(3, 20, 332, 0, 0),
	(3, 20, 333, 0, 0),
	(3, 20, 334, 0, 0),
	(3, 20, 335, 0, 0),
	(3, 20, 336, 0, 0),
	(3, 20, 337, 0, 0),
	(3, 20, 338, 0, 0),
	(3, 20, 339, 0, 0),
	(3, 20, 340, 0, 0),
	(3, 20, 341, 0, 0),
	(3, 20, 342, 0, 0),
	(3, 20, 343, 0, 0),
	(3, 20, 344, 0, 0),
	(3, 20, 345, 0, 0),
	(3, 20, 346, 0, 0),
	(3, 20, 347, 0, 0),
	(3, 20, 348, 0, 0),
	(3, 20, 349, 0, 0),
	(3, 21, 350, 0, 0),
	(3, 21, 351, 0, 0),
	(3, 21, 352, 0, 0),
	(3, 21, 353, 0, 0),
	(3, 21, 354, 0, 0),
	(3, 21, 355, 0, 0),
	(3, 21, 356, 0, 0),
	(3, 21, 357, 0, 0),
	(3, 21, 358, 0, 0),
	(3, 21, 359, 0, 0),
	(3, 21, 360, 0, 0),
	(3, 21, 361, 0, 0),
	(3, 21, 362, 0, 0),
	(3, 21, 363, 0, 0),
	(3, 21, 364, 0, 0),
	(3, 21, 365, 0, 0),
	(3, 21, 366, 0, 0),
	(3, 21, 367, 0, 0),
	(3, 21, 368, 0, 0),
	(3, 21, 369, 0, 0),
	(3, 21, 370, 0, 0),
	(3, 21, 371, 0, 0),
	(3, 21, 372, 0, 0),
	(3, 21, 373, 0, 0),
	(3, 21, 374, 0, 0),
	(3, 21, 375, 0, 0),
	(3, 21, 376, 0, 0),
	(3, 21, 377, 0, 0),
	(3, 21, 378, 0, 0),
	(3, 21, 379, 0, 0),
	(3, 21, 380, 0, 0),
	(3, 21, 381, 0, 0),
	(3, 21, 382, 0, 0),
	(3, 21, 383, 0, 0),
	(3, 21, 384, 0, 0),
	(3, 21, 385, 0, 0),
	(3, 21, 386, 0, 0),
	(3, 21, 387, 0, 0),
	(3, 22, 388, 0, 0),
	(3, 22, 389, 0, 0),
	(3, 22, 390, 0, 0),
	(3, 22, 391, 0, 0),
	(3, 22, 392, 0, 0),
	(3, 22, 393, 0, 0),
	(3, 22, 394, 0, 0),
	(3, 22, 395, 0, 0),
	(3, 22, 396, 0, 0),
	(3, 22, 397, 0, 0),
	(3, 22, 398, 0, 0),
	(3, 22, 399, 0, 0),
	(3, 22, 400, 0, 0),
	(3, 22, 401, 0, 0),
	(3, 22, 402, 0, 0),
	(3, 22, 403, 0, 0),
	(3, 22, 404, 0, 0),
	(3, 22, 405, 0, 0),
	(3, 22, 406, 0, 0),
	(3, 22, 407, 0, 0),
	(3, 22, 408, 0, 0),
	(3, 22, 409, 0, 0),
	(3, 22, 410, 0, 0),
	(3, 22, 411, 0, 0),
	(3, 22, 412, 0, 0),
	(3, 22, 413, 0, 0),
	(3, 22, 414, 0, 0),
	(3, 22, 415, 0, 0),
	(3, 22, 416, 0, 0),
	(3, 22, 417, 0, 0),
	(3, 22, 418, 0, 0),
	(3, 22, 419, 0, 0),
	(3, 22, 420, 0, 0),
	(3, 22, 421, 0, 0),
	(3, 22, 422, 0, 0),
	(3, 22, 423, 0, 0),
	(3, 22, 424, 0, 0),
	(3, 22, 425, 0, 0),
	(3, 22, 426, 0, 0),
	(3, 22, 427, 0, 0),
	(3, 22, 428, 0, 0),
	(3, 22, 429, 0, 0),
	(3, 22, 430, 0, 0),
	(3, 22, 431, 0, 0),
	(3, 22, 432, 0, 0),
	(3, 22, 433, 0, 0),
	(3, 22, 434, 0, 0),
	(3, 23, 435, 0, 0),
	(3, 23, 436, 0, 0),
	(3, 23, 437, 0, 0),
	(3, 23, 438, 0, 0),
	(3, 23, 439, 0, 0),
	(3, 23, 440, 0, 0),
	(3, 23, 441, 0, 0),
	(3, 23, 442, 0, 0),
	(3, 23, 443, 0, 0),
	(3, 23, 444, 0, 0),
	(3, 23, 445, 0, 0),
	(3, 23, 446, 0, 0),
	(3, 23, 447, 0, 0),
	(3, 23, 448, 0, 0),
	(3, 23, 449, 0, 0),
	(3, 23, 450, 0, 0),
	(3, 23, 451, 0, 0),
	(3, 23, 452, 0, 0),
	(3, 23, 453, 0, 0),
	(3, 23, 454, 0, 0),
	(3, 23, 455, 0, 0),
	(3, 23, 456, 0, 0),
	(3, 23, 457, 0, 0),
	(3, 23, 458, 0, 0),
	(3, 23, 459, 0, 0),
	(3, 23, 460, 0, 0),
	(3, 23, 461, 0, 0),
	(3, 23, 462, 0, 0),
	(3, 23, 463, 0, 0),
	(3, 23, 464, 0, 0),
	(3, 23, 465, 0, 0),
	(3, 23, 466, 0, 0),
	(3, 23, 467, 0, 0),
	(3, 23, 468, 0, 0),
	(3, 23, 469, 0, 0),
	(3, 23, 470, 0, 0),
	(3, 23, 471, 0, 0),
	(3, 23, 472, 0, 0),
	(3, 23, 473, 0, 0),
	(3, 29, 474, 0, 0),
	(3, 29, 478, 0, 0),
	(3, 29, 479, 0, 0),
	(3, 29, 481, 0, 0),
	(3, 29, 488, 0, 0),
	(3, 29, 489, 0, 0),
	(3, 29, 490, 0, 0),
	(3, 29, 491, 0, 0),
	(3, 29, 493, 0, 0),
	(3, 29, 494, 0, 0),
	(3, 29, 495, 0, 0),
	(3, 29, 496, 0, 0),
	(3, 29, 497, 0, 0),
	(3, 29, 502, 0, 0),
	(3, 29, 503, 0, 0),
	(3, 29, 504, 0, 0),
	(3, 29, 505, 0, 0),
	(3, 29, 513, 0, 0),
	(3, 10, 518, 0, 0),
	(3, 10, 519, 0, 0),
	(3, 10, 520, 0, 0),
	(3, 10, 521, 0, 0),
	(3, 10, 522, 0, 0),
	(3, 10, 523, 0, 0),
	(3, 34, 524, 0, 0),
	(3, 34, 525, 0, 0),
	(3, 34, 526, 0, 0),
	(3, 34, 527, 0, 0),
	(3, 34, 528, 0, 0),
	(3, 34, 529, 0, 0),
	(3, 34, 530, 0, 0),
	(3, 34, 531, 0, 0),
	(3, 34, 532, 0, 0),
	(3, 34, 533, 0, 0),
	(3, 34, 534, 0, 0),
	(3, 34, 535, 0, 0),
	(3, 34, 536, 0, 0),
	(3, 34, 537, 0, 0),
	(3, 34, 538, 0, 0),
	(3, 34, 539, 0, 0),
	(3, 29, 540, 0, 0),
	(3, 35, 541, 0, 0),
	(3, 35, 542, 0, 0),
	(3, 35, 543, 0, 0),
	(3, 35, 544, 0, 0),
	(3, 35, 545, 0, 0),
	(3, 35, 546, 0, 0),
	(3, 35, 547, 0, 0),
	(3, 35, 548, 0, 0),
	(3, 35, 549, 0, 0),
	(3, 35, 550, 0, 0),
	(3, 35, 551, 0, 0),
	(3, 35, 552, 0, 0),
	(3, 35, 553, 0, 0),
	(3, 35, 554, 0, 0),
	(3, 35, 555, 0, 0),
	(3, 35, 556, 0, 0),
	(3, 35, 557, 0, 0),
	(3, 35, 558, 0, 0),
	(3, 35, 559, 0, 0),
	(3, 36, 560, 0, 0),
	(3, 36, 561, 0, 0),
	(3, 36, 562, 0, 0),
	(3, 36, 563, 0, 0),
	(3, 36, 564, 0, 0),
	(3, 36, 565, 0, 0),
	(3, 36, 566, 0, 0),
	(3, 36, 567, 0, 0),
	(3, 36, 568, 0, 0),
	(3, 36, 569, 0, 0),
	(3, 36, 570, 0, 0),
	(3, 36, 571, 0, 0),
	(3, 36, 572, 0, 0),
	(3, 36, 573, 0, 0),
	(3, 36, 574, 0, 0),
	(3, 36, 575, 0, 0),
	(3, 36, 576, 0, 0),
	(3, 36, 577, 0, 0),
	(3, 36, 578, 0, 0),
	(3, 38, 579, 0, 0),
	(3, 38, 580, 0, 0),
	(3, 38, 581, 0, 0),
	(3, 38, 582, 0, 0),
	(3, 38, 583, 0, 0),
	(3, 38, 584, 0, 0),
	(3, 38, 585, 0, 0),
	(3, 38, 586, 0, 0),
	(3, 38, 587, 0, 0),
	(3, 38, 588, 0, 0),
	(3, 38, 589, 0, 0),
	(3, 38, 590, 0, 0),
	(3, 38, 591, 0, 0),
	(3, 38, 592, 0, 0),
	(3, 38, 593, 0, 0),
	(3, 38, 594, 0, 0),
	(3, 38, 595, 0, 0),
	(3, 38, 596, 0, 0),
	(3, 42, 597, 0, 0),
	(3, 42, 598, 0, 0),
	(3, 42, 599, 0, 0),
	(3, 42, 600, 0, 0),
	(3, 42, 601, 0, 0),
	(3, 42, 602, 0, 0),
	(3, 42, 603, 0, 0),
	(3, 43, 604, 0, 0),
	(3, 43, 605, 0, 0),
	(3, 43, 606, 0, 0),
	(3, 43, 607, 0, 0),
	(3, 43, 608, 0, 0),
	(3, 43, 609, 0, 0),
	(3, 43, 610, 0, 0),
	(3, 43, 611, 0, 0),
	(3, 43, 612, 0, 0),
	(3, 43, 613, 0, 0),
	(3, 44, 614, 0, 0),
	(3, 44, 615, 0, 0),
	(3, 44, 616, 0, 0),
	(3, 44, 617, 0, 0),
	(3, 44, 618, 0, 0),
	(3, 44, 619, 0, 0),
	(3, 44, 620, 0, 0),
	(3, 44, 621, 0, 0),
	(3, 44, 622, 0, 0),
	(3, 44, 623, 0, 0),
	(3, 44, 624, 0, 0),
	(3, 44, 625, 0, 0),
	(3, 44, 626, 0, 0),
	(3, 44, 627, 0, 0),
	(3, 44, 628, 0, 0),
	(3, 45, 629, 0, 0),
	(3, 45, 630, 0, 0),
	(3, 45, 631, 0, 0),
	(3, 45, 632, 0, 0),
	(3, 45, 633, 0, 0),
	(3, 45, 634, 0, 0),
	(3, 45, 635, 0, 0),
	(3, 45, 636, 0, 0),
	(3, 45, 637, 0, 0),
	(3, 45, 638, 0, 0),
	(3, 45, 639, 0, 0),
	(3, 45, 640, 0, 0),
	(3, 45, 641, 0, 0),
	(3, 45, 642, 0, 0),
	(3, 45, 643, 0, 0),
	(3, 45, 644, 0, 0),
	(3, 45, 645, 0, 0),
	(3, 47, 646, 0, 0),
	(3, 47, 647, 0, 0),
	(3, 47, 648, 0, 0),
	(3, 47, 649, 0, 0),
	(3, 47, 650, 0, 0),
	(3, 2, 651, 0, 0),
	(3, 29, 652, 0, 0),
	(3, 23, 653, 0, 0),
	(3, 23, 654, 0, 0),
	(3, 23, 655, 0, 0),
	(3, 23, 656, 0, 0),
	(3, 23, 657, 0, 0),
	(3, 23, 658, 0, 0),
	(3, 23, 659, 0, 0),
	(3, 23, 660, 0, 0),
	(3, 23, 661, 0, 0),
	(3, 22, 662, 0, 0),
	(3, 22, 663, 0, 0),
	(3, 22, 664, 0, 0),
	(3, 22, 665, 0, 0),
	(3, 22, 666, 0, 0),
	(3, 22, 667, 0, 0),
	(3, 22, 668, 0, 0),
	(3, 22, 669, 0, 0),
	(3, 22, 670, 0, 0),
	(3, 21, 671, 0, 0),
	(3, 21, 672, 0, 0),
	(3, 21, 673, 0, 0),
	(3, 21, 674, 0, 0),
	(3, 21, 675, 0, 0),
	(3, 21, 676, 0, 0),
	(3, 21, 677, 0, 0),
	(3, 21, 678, 0, 0),
	(3, 21, 679, 0, 0),
	(3, 20, 680, 0, 0),
	(3, 20, 681, 0, 0),
	(3, 20, 682, 0, 0),
	(3, 20, 683, 0, 0),
	(3, 20, 684, 0, 0),
	(3, 20, 685, 0, 0),
	(3, 20, 686, 0, 0),
	(3, 20, 687, 0, 0),
	(3, 20, 688, 0, 0),
	(3, 29, 689, 0, 0),
	(3, 44, 690, 0, 0),
	(3, 42, 691, 0, 0),
	(3, 29, 692, 0, 0),
	(3, 29, 693, 0, 0),
	(3, 29, 694, 0, 0),
	(3, 23, 695, 0, 0),
	(3, 22, 696, 0, 0),
	(3, 21, 697, 0, 0),
	(3, 20, 698, 0, 0),
	(3, 29, 699, 0, 0),
	(3, 6, 700, 0, 0),
	(3, 4, 701, 0, 0),
	(3, 2, 702, 0, 0),
	(3, 29, 703, 0, 0),
	(3, 23, 704, 0, 0),
	(3, 23, 705, 0, 0),
	(3, 21, 706, 0, 0),
	(3, 21, 707, 0, 0),
	(3, 18, 708, 0, 0),
	(3, 7, 709, 0, 0),
	(3, 42, 710, 0, 0),
	(3, 42, 711, 0, 0),
	(3, 23, 712, 0, 0),
	(3, 20, 713, 0, 0),
	(3, 21, 714, 0, 0),
	(3, 22, 715, 0, 0),
	(3, 29, 716, 0, 0),
	(3, 2, 717, 0, 0),
	(3, 13, 718, 0, 0),
	(3, 29, 719, 0, 0),
	(3, 13, 720, 0, 0),
	(3, 29, 721, 0, 0),
	(3, 29, 722, 0, 0),
	(3, 29, 723, 0, 0),
	(3, 9, 729, 0, 0),
	(3, 29, 750, 0, 0),
	(3, 4, 752, 0, 0),
	(3, 4, 754, 0, 0),
	(3, 4, 756, 0, 0),
	(3, 4, 758, 0, 0),
	(3, 4, 760, 0, 0),
	(3, 4, 762, 0, 0),
	(3, 4, 764, 0, 0),
	(3, 4, 766, 0, 0),
	(3, 4, 770, 0, 0),
	(4, 6, 1, 0, 0),
	(4, 6, 2, 0, 0),
	(4, 6, 3, 0, 0),
	(4, 6, 4, 0, 0),
	(4, 6, 5, 0, 0),
	(4, 6, 6, 0, 0),
	(4, 6, 7, 0, 0),
	(4, 6, 8, 0, 0),
	(4, 6, 9, 0, 0),
	(4, 6, 10, 0, 0),
	(4, 6, 11, 0, 0),
	(4, 6, 12, 0, 0),
	(4, 6, 13, 0, 0),
	(4, 6, 14, 0, 0),
	(4, 6, 15, 0, 0),
	(4, 6, 16, 0, 0),
	(4, 6, 17, 0, 0),
	(4, 6, 18, 0, 0),
	(4, 6, 19, 0, 0),
	(4, 6, 20, 0, 0),
	(4, 6, 21, 0, 0),
	(4, 6, 22, 0, 0),
	(4, 6, 23, 0, 0),
	(4, 6, 24, 0, 0),
	(4, 6, 25, 0, 0),
	(4, 6, 26, 0, 0),
	(4, 6, 27, 0, 0),
	(4, 6, 28, 0, 0),
	(4, 6, 29, 0, 0),
	(4, 6, 30, 0, 0),
	(4, 6, 31, 0, 0),
	(4, 6, 32, 0, 0),
	(4, 6, 33, 0, 0),
	(4, 6, 34, 0, 0),
	(4, 6, 35, 0, 0),
	(4, 6, 36, 0, 0),
	(4, 7, 37, 0, 0),
	(4, 7, 38, 0, 0),
	(4, 7, 39, 0, 0),
	(4, 7, 40, 0, 0),
	(4, 7, 41, 0, 0),
	(4, 7, 42, 0, 0),
	(4, 7, 43, 0, 0),
	(4, 7, 44, 0, 0),
	(4, 7, 45, 0, 0),
	(4, 7, 46, 0, 0),
	(4, 7, 47, 0, 0),
	(4, 7, 48, 0, 0),
	(4, 7, 49, 0, 0),
	(4, 7, 50, 0, 0),
	(4, 7, 51, 0, 0),
	(4, 7, 52, 0, 0),
	(4, 7, 53, 0, 0),
	(4, 7, 54, 0, 0),
	(4, 7, 55, 0, 0),
	(4, 7, 56, 0, 0),
	(4, 7, 57, 0, 0),
	(4, 7, 58, 0, 0),
	(4, 7, 59, 0, 0),
	(4, 7, 60, 0, 0),
	(4, 7, 61, 0, 0),
	(4, 7, 62, 0, 0),
	(4, 7, 63, 0, 0),
	(4, 7, 64, 0, 0),
	(4, 7, 65, 0, 0),
	(4, 4, 66, 0, 0),
	(4, 4, 67, 0, 0),
	(4, 4, 68, 0, 0),
	(4, 4, 69, 0, 0),
	(4, 4, 70, 0, 0),
	(4, 4, 71, 0, 0),
	(4, 4, 72, 0, 0),
	(4, 4, 73, 0, 0),
	(4, 4, 74, 0, 0),
	(4, 4, 75, 0, 0),
	(4, 4, 76, 0, 0),
	(4, 4, 77, 0, 0),
	(4, 4, 78, 0, 0),
	(4, 4, 79, 0, 0),
	(4, 4, 80, 0, 0),
	(4, 4, 81, 0, 0),
	(4, 4, 82, 0, 0),
	(4, 4, 83, 0, 0),
	(4, 4, 84, 0, 0),
	(4, 4, 85, 0, 0),
	(4, 4, 86, 0, 0),
	(4, 4, 87, 0, 0),
	(4, 4, 88, 0, 0),
	(4, 4, 89, 0, 0),
	(4, 4, 90, 0, 0),
	(4, 4, 91, 0, 0),
	(4, 4, 92, 0, 0),
	(4, 4, 93, 0, 0),
	(4, 4, 94, 0, 0),
	(4, 4, 95, 0, 0),
	(4, 4, 96, 0, 0),
	(4, 4, 97, 0, 0),
	(4, 4, 98, 0, 0),
	(4, 4, 99, 0, 0),
	(4, 4, 100, 0, 0),
	(4, 4, 101, 0, 0),
	(4, 4, 102, 0, 0),
	(4, 4, 103, 0, 0),
	(4, 4, 104, 0, 0),
	(4, 4, 105, 0, 0),
	(4, 4, 106, 0, 0),
	(4, 4, 107, 0, 0),
	(4, 4, 108, 0, 0),
	(4, 4, 109, 0, 0),
	(4, 2, 110, 0, 0),
	(4, 2, 111, 0, 0),
	(4, 2, 112, 0, 0),
	(4, 2, 113, 0, 0),
	(4, 2, 114, 0, 0),
	(4, 2, 115, 0, 0),
	(4, 2, 116, 0, 0),
	(4, 2, 117, 0, 0),
	(4, 2, 118, 0, 0),
	(4, 2, 119, 0, 0),
	(4, 2, 120, 0, 0),
	(4, 2, 121, 0, 0),
	(4, 2, 122, 0, 0),
	(4, 2, 123, 0, 0),
	(4, 2, 124, 0, 0),
	(4, 2, 125, 0, 0),
	(4, 26, 126, 0, 0),
	(4, 26, 127, 0, 0),
	(4, 26, 128, 0, 0),
	(4, 26, 129, 0, 0),
	(4, 26, 130, 0, 0),
	(4, 26, 131, 0, 0),
	(4, 26, 132, 0, 0),
	(4, 26, 133, 0, 0),
	(4, 26, 134, 0, 0),
	(4, 26, 135, 0, 0),
	(4, 26, 136, 0, 0),
	(4, 26, 137, 0, 0),
	(4, 26, 138, 0, 0),
	(4, 26, 139, 0, 0),
	(4, 26, 140, 0, 0),
	(4, 26, 141, 0, 0),
	(4, 26, 142, 0, 0),
	(4, 26, 143, 0, 0),
	(4, 26, 144, 0, 0),
	(4, 26, 145, 0, 0),
	(4, 26, 146, 0, 0),
	(4, 26, 147, 0, 0),
	(4, 26, 148, 0, 0),
	(4, 26, 149, 0, 0),
	(4, 26, 150, 0, 0),
	(4, 4, 151, 0, 0),
	(4, 6, 152, 0, 0),
	(4, 7, 153, 0, 0),
	(4, 26, 154, 0, 0),
	(4, 13, 155, 0, 0),
	(4, 13, 156, 0, 0),
	(4, 13, 157, 0, 0),
	(4, 13, 158, 0, 0),
	(4, 13, 159, 0, 0),
	(4, 13, 160, 0, 0),
	(4, 13, 161, 0, 0),
	(4, 13, 162, 0, 0),
	(4, 13, 163, 0, 0),
	(4, 13, 164, 0, 0),
	(4, 13, 165, 0, 0),
	(4, 13, 166, 0, 0),
	(4, 13, 167, 0, 0),
	(4, 13, 168, 0, 0),
	(4, 13, 169, 0, 0),
	(4, 13, 170, 0, 0),
	(4, 13, 171, 0, 0),
	(4, 13, 172, 0, 0),
	(4, 14, 173, 0, 0),
	(4, 14, 174, 0, 0),
	(4, 14, 175, 0, 0),
	(4, 14, 176, 0, 0),
	(4, 14, 177, 0, 0),
	(4, 14, 178, 0, 0),
	(4, 14, 179, 0, 0),
	(4, 14, 180, 0, 0),
	(4, 14, 181, 0, 0),
	(4, 14, 182, 0, 0),
	(4, 14, 183, 0, 0),
	(4, 14, 184, 0, 0),
	(4, 14, 185, 0, 0),
	(4, 14, 186, 0, 0),
	(4, 14, 187, 0, 0),
	(4, 14, 188, 0, 0),
	(4, 14, 189, 0, 0),
	(4, 14, 190, 0, 0),
	(4, 14, 191, 0, 0),
	(4, 14, 192, 0, 0),
	(4, 14, 193, 0, 0),
	(4, 14, 194, 0, 0),
	(4, 14, 195, 0, 0),
	(4, 14, 196, 0, 0),
	(4, 14, 197, 0, 0),
	(4, 14, 198, 0, 0),
	(4, 14, 199, 0, 0),
	(4, 14, 200, 0, 0),
	(4, 14, 201, 0, 0),
	(4, 14, 202, 0, 0),
	(4, 14, 203, 0, 0),
	(4, 8, 204, 0, 0),
	(4, 8, 205, 0, 0),
	(4, 8, 206, 0, 0),
	(4, 8, 207, 0, 0),
	(4, 8, 208, 0, 0),
	(4, 8, 209, 0, 0),
	(4, 8, 210, 0, 0),
	(4, 8, 211, 0, 0),
	(4, 8, 212, 0, 0),
	(4, 8, 213, 0, 0),
	(4, 8, 214, 0, 0),
	(4, 8, 215, 0, 0),
	(4, 8, 216, 0, 0),
	(4, 8, 217, 0, 0),
	(4, 8, 218, 0, 0),
	(4, 10, 219, 0, 0),
	(4, 10, 220, 0, 0),
	(4, 10, 221, 0, 0),
	(4, 10, 222, 0, 0),
	(4, 10, 223, 0, 0),
	(4, 10, 224, 0, 0),
	(4, 10, 225, 0, 0),
	(4, 10, 226, 0, 0),
	(4, 10, 227, 0, 0),
	(4, 10, 228, 0, 0),
	(4, 10, 229, 0, 0),
	(4, 10, 230, 0, 0),
	(4, 9, 231, 0, 0),
	(4, 9, 232, 0, 0),
	(4, 9, 233, 0, 0),
	(4, 9, 234, 0, 0),
	(4, 9, 235, 0, 0),
	(4, 9, 236, 0, 0),
	(4, 9, 237, 0, 0),
	(4, 9, 238, 0, 0),
	(4, 9, 239, 0, 0),
	(4, 9, 240, 0, 0),
	(4, 9, 241, 0, 0),
	(4, 9, 242, 0, 0),
	(4, 9, 243, 0, 0),
	(4, 9, 244, 0, 0),
	(4, 9, 245, 0, 0),
	(4, 9, 246, 0, 0),
	(4, 9, 247, 0, 0),
	(4, 9, 248, 0, 0),
	(4, 9, 249, 0, 0),
	(4, 9, 250, 0, 0),
	(4, 9, 251, 0, 0),
	(4, 9, 252, 0, 0),
	(4, 9, 253, 0, 0),
	(4, 9, 254, 0, 0),
	(4, 16, 255, 0, 0),
	(4, 16, 256, 0, 0),
	(4, 16, 257, 0, 0),
	(4, 16, 258, 0, 0),
	(4, 16, 259, 0, 0),
	(4, 16, 260, 0, 0),
	(4, 16, 261, 0, 0),
	(4, 16, 262, 0, 0),
	(4, 16, 263, 0, 0),
	(4, 16, 264, 0, 0),
	(4, 16, 265, 0, 0),
	(4, 16, 266, 0, 0),
	(4, 16, 267, 0, 0),
	(4, 16, 268, 0, 0),
	(4, 16, 269, 0, 0),
	(4, 16, 270, 0, 0),
	(4, 16, 271, 0, 0),
	(4, 16, 272, 0, 0),
	(4, 16, 273, 0, 0),
	(4, 16, 274, 0, 0),
	(4, 16, 275, 0, 0),
	(4, 16, 276, 0, 0),
	(4, 16, 277, 0, 0),
	(4, 15, 278, 0, 0),
	(4, 15, 279, 0, 0),
	(4, 15, 280, 0, 0),
	(4, 15, 281, 0, 0),
	(4, 15, 282, 0, 0),
	(4, 15, 283, 0, 0),
	(4, 15, 284, 0, 0),
	(4, 15, 285, 0, 0),
	(4, 15, 286, 0, 0),
	(4, 15, 287, 0, 0),
	(4, 18, 288, 0, 0),
	(4, 18, 289, 0, 0),
	(4, 18, 290, 0, 0),
	(4, 18, 291, 0, 0),
	(4, 18, 292, 0, 0),
	(4, 18, 293, 0, 0),
	(4, 18, 294, 0, 0),
	(4, 18, 295, 0, 0),
	(4, 18, 296, 0, 0),
	(4, 18, 297, 0, 0),
	(4, 18, 298, 0, 0),
	(4, 18, 299, 0, 0),
	(4, 18, 300, 0, 0),
	(4, 18, 301, 0, 0),
	(4, 18, 302, 0, 0),
	(4, 18, 303, 0, 0),
	(4, 18, 304, 0, 0),
	(4, 19, 305, 0, 0),
	(4, 19, 306, 0, 0),
	(4, 19, 307, 0, 0),
	(4, 19, 308, 0, 0),
	(4, 19, 309, 0, 0),
	(4, 19, 310, 0, 0),
	(4, 19, 311, 0, 0),
	(4, 19, 312, 0, 0),
	(4, 20, 313, 0, 0),
	(4, 20, 314, 0, 0),
	(4, 20, 315, 0, 0),
	(4, 20, 316, 0, 0),
	(4, 20, 317, 0, 0),
	(4, 20, 318, 0, 0),
	(4, 20, 319, 0, 0),
	(4, 20, 320, 0, 0),
	(4, 20, 321, 0, 0),
	(4, 20, 322, 0, 0),
	(4, 20, 323, 0, 0),
	(4, 20, 324, 0, 0),
	(4, 20, 325, 0, 0),
	(4, 20, 326, 0, 0),
	(4, 20, 327, 0, 0),
	(4, 20, 328, 0, 0),
	(4, 20, 329, 0, 0),
	(4, 20, 330, 0, 0),
	(4, 20, 331, 0, 0),
	(4, 20, 332, 0, 0),
	(4, 20, 333, 0, 0),
	(4, 20, 334, 0, 0),
	(4, 20, 335, 0, 0),
	(4, 20, 336, 0, 0),
	(4, 20, 337, 0, 0),
	(4, 20, 338, 0, 0),
	(4, 20, 339, 0, 0),
	(4, 20, 340, 0, 0),
	(4, 20, 341, 0, 0),
	(4, 20, 342, 0, 0),
	(4, 20, 343, 0, 0),
	(4, 20, 344, 0, 0),
	(4, 20, 345, 0, 0),
	(4, 20, 346, 0, 0),
	(4, 20, 347, 0, 0),
	(4, 20, 348, 0, 0),
	(4, 20, 349, 0, 0),
	(4, 21, 350, 0, 0),
	(4, 21, 351, 0, 0),
	(4, 21, 352, 0, 0),
	(4, 21, 353, 0, 0),
	(4, 21, 354, 0, 0),
	(4, 21, 355, 0, 0),
	(4, 21, 356, 0, 0),
	(4, 21, 357, 0, 0),
	(4, 21, 358, 0, 0),
	(4, 21, 359, 0, 0),
	(4, 21, 360, 0, 0),
	(4, 21, 361, 0, 0),
	(4, 21, 362, 0, 0),
	(4, 21, 363, 0, 0),
	(4, 21, 364, 0, 0),
	(4, 21, 365, 0, 0),
	(4, 21, 366, 0, 0),
	(4, 21, 367, 0, 0),
	(4, 21, 368, 0, 0),
	(4, 21, 369, 0, 0),
	(4, 21, 370, 0, 0),
	(4, 21, 371, 0, 0),
	(4, 21, 372, 0, 0),
	(4, 21, 373, 0, 0),
	(4, 21, 374, 0, 0),
	(4, 21, 375, 0, 0),
	(4, 21, 376, 0, 0),
	(4, 21, 377, 0, 0),
	(4, 21, 378, 0, 0),
	(4, 21, 379, 0, 0),
	(4, 21, 380, 0, 0),
	(4, 21, 381, 0, 0),
	(4, 21, 382, 0, 0),
	(4, 21, 383, 0, 0),
	(4, 21, 384, 0, 0),
	(4, 21, 385, 0, 0),
	(4, 21, 386, 0, 0),
	(4, 21, 387, 0, 0),
	(4, 22, 388, 0, 0),
	(4, 22, 389, 0, 0),
	(4, 22, 390, 0, 0),
	(4, 22, 391, 0, 0),
	(4, 22, 392, 0, 0),
	(4, 22, 393, 0, 0),
	(4, 22, 394, 0, 0),
	(4, 22, 395, 0, 0),
	(4, 22, 396, 0, 0),
	(4, 22, 397, 0, 0),
	(4, 22, 398, 0, 0),
	(4, 22, 399, 0, 0),
	(4, 22, 400, 0, 0),
	(4, 22, 401, 0, 0),
	(4, 22, 402, 0, 0),
	(4, 22, 403, 0, 0),
	(4, 22, 404, 0, 0),
	(4, 22, 405, 0, 0),
	(4, 22, 406, 0, 0),
	(4, 22, 407, 0, 0),
	(4, 22, 408, 0, 0),
	(4, 22, 409, 0, 0),
	(4, 22, 410, 0, 0),
	(4, 22, 411, 0, 0),
	(4, 22, 412, 0, 0),
	(4, 22, 413, 0, 0),
	(4, 22, 414, 0, 0),
	(4, 22, 415, 0, 0),
	(4, 22, 416, 0, 0),
	(4, 22, 417, 0, 0),
	(4, 22, 418, 0, 0),
	(4, 22, 419, 0, 0),
	(4, 22, 420, 0, 0),
	(4, 22, 421, 0, 0),
	(4, 22, 422, 0, 0),
	(4, 22, 423, 0, 0),
	(4, 22, 424, 0, 0),
	(4, 22, 425, 0, 0),
	(4, 22, 426, 0, 0),
	(4, 22, 427, 0, 0),
	(4, 22, 428, 0, 0),
	(4, 22, 429, 0, 0),
	(4, 22, 430, 0, 0),
	(4, 22, 431, 0, 0),
	(4, 22, 432, 0, 0),
	(4, 22, 433, 0, 0),
	(4, 22, 434, 0, 0),
	(4, 23, 435, 0, 0),
	(4, 23, 436, 0, 0),
	(4, 23, 437, 0, 0),
	(4, 23, 438, 0, 0),
	(4, 23, 439, 0, 0),
	(4, 23, 440, 0, 0),
	(4, 23, 441, 0, 0),
	(4, 23, 442, 0, 0),
	(4, 23, 443, 0, 0),
	(4, 23, 444, 0, 0),
	(4, 23, 445, 0, 0),
	(4, 23, 446, 0, 0),
	(4, 23, 447, 0, 0),
	(4, 23, 448, 0, 0),
	(4, 23, 449, 0, 0),
	(4, 23, 450, 0, 0),
	(4, 23, 451, 0, 0),
	(4, 23, 452, 0, 0),
	(4, 23, 453, 0, 0),
	(4, 23, 454, 0, 0),
	(4, 23, 455, 0, 0),
	(4, 23, 456, 0, 0),
	(4, 23, 457, 0, 0),
	(4, 23, 458, 0, 0),
	(4, 23, 459, 0, 0),
	(4, 23, 460, 0, 0),
	(4, 23, 461, 0, 0),
	(4, 23, 462, 0, 0),
	(4, 23, 463, 0, 0),
	(4, 23, 464, 0, 0),
	(4, 23, 465, 0, 0),
	(4, 23, 466, 0, 0),
	(4, 23, 467, 0, 0),
	(4, 23, 468, 0, 0),
	(4, 23, 469, 0, 0),
	(4, 23, 470, 0, 0),
	(4, 23, 471, 0, 0),
	(4, 23, 472, 0, 0),
	(4, 23, 473, 0, 0),
	(4, 29, 474, 0, 0),
	(4, 29, 478, 0, 0),
	(4, 29, 479, 0, 0),
	(4, 29, 481, 0, 0),
	(4, 29, 488, 0, 0),
	(4, 29, 489, 0, 0),
	(4, 29, 490, 0, 0),
	(4, 29, 491, 0, 0),
	(4, 29, 493, 0, 0),
	(4, 29, 494, 0, 0),
	(4, 29, 495, 0, 0),
	(4, 29, 496, 0, 0),
	(4, 29, 497, 0, 0),
	(4, 29, 502, 0, 0),
	(4, 29, 503, 0, 0),
	(4, 29, 504, 0, 0),
	(4, 29, 505, 0, 0),
	(4, 29, 513, 0, 0),
	(4, 10, 518, 0, 0),
	(4, 10, 519, 0, 0),
	(4, 10, 520, 0, 0),
	(4, 10, 521, 0, 0),
	(4, 10, 522, 0, 0),
	(4, 10, 523, 0, 0),
	(4, 34, 524, 0, 0),
	(4, 34, 525, 0, 0),
	(4, 34, 526, 0, 0),
	(4, 34, 527, 0, 0),
	(4, 34, 528, 0, 0),
	(4, 34, 529, 0, 0),
	(4, 34, 530, 0, 0),
	(4, 34, 531, 0, 0),
	(4, 34, 532, 0, 0),
	(4, 34, 533, 0, 0),
	(4, 34, 534, 0, 0),
	(4, 34, 535, 0, 0),
	(4, 34, 536, 0, 0),
	(4, 34, 537, 0, 0),
	(4, 34, 538, 0, 0),
	(4, 34, 539, 0, 0),
	(4, 29, 540, 0, 0),
	(4, 35, 541, 0, 0),
	(4, 35, 542, 0, 0),
	(4, 35, 543, 0, 0),
	(4, 35, 544, 0, 0),
	(4, 35, 545, 0, 0),
	(4, 35, 546, 0, 0),
	(4, 35, 547, 0, 0),
	(4, 35, 548, 0, 0),
	(4, 35, 549, 0, 0),
	(4, 35, 550, 0, 0),
	(4, 35, 551, 0, 0),
	(4, 35, 552, 0, 0),
	(4, 35, 553, 0, 0),
	(4, 35, 554, 0, 0),
	(4, 35, 555, 0, 0),
	(4, 35, 556, 0, 0),
	(4, 35, 557, 0, 0),
	(4, 35, 558, 0, 0),
	(4, 35, 559, 0, 0),
	(4, 36, 560, 0, 0),
	(4, 36, 561, 0, 0),
	(4, 36, 562, 0, 0),
	(4, 36, 563, 0, 0),
	(4, 36, 564, 0, 0),
	(4, 36, 565, 0, 0),
	(4, 36, 566, 0, 0),
	(4, 36, 567, 0, 0),
	(4, 36, 568, 0, 0),
	(4, 36, 569, 0, 0),
	(4, 36, 570, 0, 0),
	(4, 36, 571, 0, 0),
	(4, 36, 572, 0, 0),
	(4, 36, 573, 0, 0),
	(4, 36, 574, 0, 0),
	(4, 36, 575, 0, 0),
	(4, 36, 576, 0, 0),
	(4, 36, 577, 0, 0),
	(4, 36, 578, 0, 0),
	(4, 38, 579, 0, 0),
	(4, 38, 580, 0, 0),
	(4, 38, 581, 0, 0),
	(4, 38, 582, 0, 0),
	(4, 38, 583, 0, 0),
	(4, 38, 584, 0, 0),
	(4, 38, 585, 0, 0),
	(4, 38, 586, 0, 0),
	(4, 38, 587, 0, 0),
	(4, 38, 588, 0, 0),
	(4, 38, 589, 0, 0),
	(4, 38, 590, 0, 0),
	(4, 38, 591, 0, 0),
	(4, 38, 592, 0, 0),
	(4, 38, 593, 0, 0),
	(4, 38, 594, 0, 0),
	(4, 38, 595, 0, 0),
	(4, 38, 596, 0, 0),
	(4, 42, 597, 0, 0),
	(4, 42, 598, 0, 0),
	(4, 42, 599, 0, 0),
	(4, 42, 600, 0, 0),
	(4, 42, 601, 0, 0),
	(4, 42, 602, 0, 0),
	(4, 42, 603, 0, 0),
	(4, 43, 604, 0, 0),
	(4, 43, 605, 0, 0),
	(4, 43, 606, 0, 0),
	(4, 43, 607, 0, 0),
	(4, 43, 608, 0, 0),
	(4, 43, 609, 0, 0),
	(4, 43, 610, 0, 0),
	(4, 43, 611, 0, 0),
	(4, 43, 612, 0, 0),
	(4, 43, 613, 0, 0),
	(4, 44, 614, 0, 0),
	(4, 44, 615, 0, 0),
	(4, 44, 616, 0, 0),
	(4, 44, 617, 0, 0),
	(4, 44, 618, 0, 0),
	(4, 44, 619, 0, 0),
	(4, 44, 620, 0, 0),
	(4, 44, 621, 0, 0),
	(4, 44, 622, 0, 0),
	(4, 44, 623, 0, 0),
	(4, 44, 624, 0, 0),
	(4, 44, 625, 0, 0),
	(4, 44, 626, 0, 0),
	(4, 44, 627, 0, 0),
	(4, 44, 628, 0, 0),
	(4, 45, 629, 0, 0),
	(4, 45, 630, 0, 0),
	(4, 45, 631, 0, 0),
	(4, 45, 632, 0, 0),
	(4, 45, 633, 0, 0),
	(4, 45, 634, 0, 0),
	(4, 45, 635, 0, 0),
	(4, 45, 636, 0, 0),
	(4, 45, 637, 0, 0),
	(4, 45, 638, 0, 0),
	(4, 45, 639, 0, 0),
	(4, 45, 640, 0, 0),
	(4, 45, 641, 0, 0),
	(4, 45, 642, 0, 0),
	(4, 45, 643, 0, 0),
	(4, 45, 644, 0, 0),
	(4, 45, 645, 0, 0),
	(4, 47, 646, 0, 0),
	(4, 47, 647, 0, 0),
	(4, 47, 648, 0, 0),
	(4, 47, 649, 0, 0),
	(4, 47, 650, 0, 0),
	(4, 2, 651, 0, 0),
	(4, 29, 652, 0, 0),
	(4, 23, 653, 0, 0),
	(4, 23, 654, 0, 0),
	(4, 23, 655, 0, 0),
	(4, 23, 656, 0, 0),
	(4, 23, 657, 0, 0),
	(4, 23, 658, 0, 0),
	(4, 23, 659, 0, 0),
	(4, 23, 660, 0, 0),
	(4, 23, 661, 0, 0),
	(4, 22, 662, 0, 0),
	(4, 22, 663, 0, 0),
	(4, 22, 664, 0, 0),
	(4, 22, 665, 0, 0),
	(4, 22, 666, 0, 0),
	(4, 22, 667, 0, 0),
	(4, 22, 668, 0, 0),
	(4, 22, 669, 0, 0),
	(4, 22, 670, 0, 0),
	(4, 21, 671, 0, 0),
	(4, 21, 672, 0, 0),
	(4, 21, 673, 0, 0),
	(4, 21, 674, 0, 0),
	(4, 21, 675, 0, 0),
	(4, 21, 676, 0, 0),
	(4, 21, 677, 0, 0),
	(4, 21, 678, 0, 0),
	(4, 21, 679, 0, 0),
	(4, 20, 680, 0, 0),
	(4, 20, 681, 0, 0),
	(4, 20, 682, 0, 0),
	(4, 20, 683, 0, 0),
	(4, 20, 684, 0, 0),
	(4, 20, 685, 0, 0),
	(4, 20, 686, 0, 0),
	(4, 20, 687, 0, 0),
	(4, 20, 688, 0, 0),
	(4, 29, 689, 0, 0),
	(4, 44, 690, 0, 0),
	(4, 42, 691, 0, 0),
	(4, 29, 692, 0, 0),
	(4, 29, 693, 0, 0),
	(4, 29, 694, 0, 0),
	(4, 23, 695, 0, 0),
	(4, 22, 696, 0, 0),
	(4, 21, 697, 0, 0),
	(4, 20, 698, 0, 0),
	(4, 29, 699, 0, 0),
	(4, 6, 700, 0, 0),
	(4, 4, 701, 0, 0),
	(4, 2, 702, 0, 0),
	(4, 29, 703, 0, 0),
	(4, 23, 704, 0, 0),
	(4, 23, 705, 0, 0),
	(4, 21, 706, 0, 0),
	(4, 21, 707, 0, 0),
	(4, 18, 708, 0, 0),
	(4, 7, 709, 0, 0),
	(4, 42, 710, 0, 0),
	(4, 42, 711, 0, 0),
	(4, 23, 712, 0, 0),
	(4, 20, 713, 0, 0),
	(4, 21, 714, 0, 0),
	(4, 22, 715, 0, 0),
	(4, 29, 716, 0, 0),
	(4, 2, 717, 0, 0),
	(4, 13, 718, 0, 0),
	(4, 29, 719, 0, 0),
	(4, 13, 720, 0, 0),
	(4, 29, 721, 0, 0),
	(4, 29, 722, 0, 0),
	(4, 29, 723, 0, 0),
	(4, 9, 729, 0, 0),
	(4, 29, 750, 0, 0),
	(4, 4, 752, 0, 0),
	(4, 4, 754, 0, 0),
	(4, 4, 756, 0, 0),
	(4, 4, 758, 0, 0),
	(4, 4, 760, 0, 0),
	(4, 4, 762, 0, 0),
	(4, 4, 764, 0, 0),
	(4, 4, 766, 0, 0),
	(4, 4, 770, 0, 0),
	(5, 4, 66, 0, 0),
	(5, 4, 68, 0, 0),
	(5, 4, 70, 0, 0),
	(5, 4, 71, 0, 0),
	(5, 4, 72, 0, 0),
	(5, 4, 79, 0, 0),
	(5, 4, 80, 0, 0),
	(5, 4, 85, 0, 0),
	(5, 4, 87, 0, 0),
	(5, 4, 90, 0, 1),
	(5, 4, 91, 0, 1),
	(5, 4, 92, 0, 0),
	(5, 4, 96, 0, 0),
	(5, 4, 109, 0, 0),
	(5, 10, 219, 0, 0),
	(5, 10, 220, 0, 0),
	(5, 10, 221, 0, 0),
	(5, 10, 222, 0, 0),
	(5, 10, 223, 0, 0),
	(5, 10, 224, 0, 0),
	(5, 10, 225, 0, 0),
	(5, 10, 226, 0, 0),
	(5, 10, 227, 0, 0),
	(5, 10, 228, 0, 1),
	(5, 10, 229, 0, 0),
	(5, 10, 230, 0, 0),
	(5, 9, 231, 0, 0),
	(5, 9, 232, 0, 0),
	(5, 9, 233, 0, 0),
	(5, 9, 234, 0, 0),
	(5, 9, 235, 0, 0),
	(5, 9, 236, 0, 0),
	(5, 9, 237, 0, 0),
	(5, 9, 238, 0, 0),
	(5, 9, 239, 0, 0),
	(5, 9, 240, 0, 0),
	(5, 9, 241, 0, 0),
	(5, 9, 242, 0, 0),
	(5, 9, 243, 0, 1),
	(5, 9, 244, 0, 1),
	(5, 9, 245, 0, 0),
	(5, 9, 246, 0, 0),
	(5, 9, 247, 0, 0),
	(5, 9, 248, 0, 0),
	(5, 9, 249, 0, 0),
	(5, 9, 250, 0, 0),
	(5, 9, 251, 0, 0),
	(5, 9, 252, 0, 0),
	(5, 9, 253, 0, 0),
	(5, 9, 254, 0, 0),
	(5, 16, 255, 0, 0),
	(5, 16, 256, 0, 0),
	(5, 16, 257, 0, 0),
	(5, 16, 258, 0, 0),
	(5, 16, 259, 0, 0),
	(5, 16, 260, 0, 0),
	(5, 16, 261, 0, 0),
	(5, 16, 262, 0, 0),
	(5, 16, 263, 0, 0),
	(5, 16, 264, 0, 0),
	(5, 16, 265, 0, 0),
	(5, 16, 266, 0, 0),
	(5, 16, 267, 0, 0),
	(5, 16, 268, 0, 0),
	(5, 16, 269, 0, 1),
	(5, 16, 270, 0, 1),
	(5, 16, 271, 0, 0),
	(5, 16, 272, 0, 0),
	(5, 16, 273, 0, 0),
	(5, 16, 274, 0, 0),
	(5, 16, 275, 0, 0),
	(5, 16, 276, 0, 0),
	(5, 16, 277, 0, 0),
	(5, 29, 474, 0, 0),
	(5, 29, 478, 0, 0),
	(5, 29, 479, 0, 0),
	(5, 29, 481, 0, 0),
	(5, 29, 488, 0, 0),
	(5, 29, 489, 0, 0),
	(5, 29, 490, 0, 0),
	(5, 29, 491, 0, 0),
	(5, 29, 493, 0, 0),
	(5, 29, 494, 0, 0),
	(5, 29, 495, 0, 0),
	(5, 29, 496, 0, 0),
	(5, 29, 497, 0, 0),
	(5, 29, 502, 0, 0),
	(5, 29, 503, 0, 0),
	(5, 29, 504, 0, 0),
	(5, 29, 505, 0, 0),
	(5, 10, 518, 0, 0),
	(5, 10, 519, 0, 0),
	(5, 10, 520, 0, 0),
	(5, 10, 521, 0, 0),
	(5, 10, 522, 0, 0),
	(5, 10, 523, 0, 0),
	(5, 29, 540, 0, 0),
	(5, 42, 597, 0, 0),
	(5, 42, 598, 0, 0),
	(5, 42, 599, 0, 1),
	(5, 42, 600, 0, 1),
	(5, 42, 601, 0, 0),
	(5, 42, 602, 0, 1),
	(5, 42, 603, 0, 0),
	(5, 42, 691, 0, 0),
	(5, 4, 701, 0, 0),
	(5, 42, 710, 0, 0),
	(5, 42, 711, 0, 0),
	(5, 9, 729, 0, 1),
	(5, 29, 750, 0, 0),
	(5, 4, 752, 0, 0),
	(5, 4, 754, 0, 0),
	(5, 4, 756, 0, 0),
	(5, 4, 758, 0, 0),
	(5, 4, 760, 0, 0),
	(5, 4, 762, 0, 0),
	(5, 4, 764, 0, 0),
	(5, 4, 766, 0, 0),
	(5, 4, 770, 0, 0),
	(6, 4, 66, 0, 0),
	(6, 4, 68, 0, 0),
	(6, 4, 70, 0, 0),
	(6, 4, 71, 0, 0),
	(6, 4, 72, 0, 0),
	(6, 4, 79, 0, 0),
	(6, 4, 80, 0, 0),
	(6, 4, 85, 0, 0),
	(6, 4, 87, 0, 0),
	(6, 4, 90, 0, 1),
	(6, 4, 91, 0, 1),
	(6, 4, 92, 0, 0),
	(6, 4, 96, 0, 0),
	(6, 4, 109, 0, 0),
	(6, 10, 219, 0, 0),
	(6, 10, 220, 0, 0),
	(6, 10, 221, 0, 0),
	(6, 10, 222, 0, 0),
	(6, 10, 223, 0, 0),
	(6, 10, 224, 0, 0),
	(6, 10, 225, 0, 0),
	(6, 10, 226, 0, 0),
	(6, 10, 227, 0, 0),
	(6, 10, 228, 0, 1),
	(6, 10, 229, 0, 0),
	(6, 10, 230, 0, 0),
	(6, 9, 231, 0, 0),
	(6, 9, 232, 0, 0),
	(6, 9, 233, 0, 0),
	(6, 9, 234, 0, 0),
	(6, 9, 235, 0, 0),
	(6, 9, 236, 0, 0),
	(6, 9, 237, 0, 0),
	(6, 9, 238, 0, 0),
	(6, 9, 239, 0, 0),
	(6, 9, 240, 0, 0),
	(6, 9, 241, 0, 0),
	(6, 9, 242, 0, 0),
	(6, 9, 243, 0, 1),
	(6, 9, 244, 0, 1),
	(6, 9, 245, 0, 0),
	(6, 9, 246, 0, 0),
	(6, 9, 247, 0, 0),
	(6, 9, 248, 0, 0),
	(6, 9, 249, 0, 0),
	(6, 9, 250, 0, 0),
	(6, 9, 251, 0, 0),
	(6, 9, 252, 0, 0),
	(6, 9, 253, 0, 0),
	(6, 9, 254, 0, 0),
	(6, 16, 255, 0, 0),
	(6, 16, 256, 0, 0),
	(6, 16, 257, 0, 0),
	(6, 16, 258, 0, 0),
	(6, 16, 259, 0, 0),
	(6, 16, 260, 0, 0),
	(6, 16, 261, 0, 0),
	(6, 16, 262, 0, 0),
	(6, 16, 263, 0, 0),
	(6, 16, 264, 0, 0),
	(6, 16, 265, 0, 0),
	(6, 16, 266, 0, 0),
	(6, 16, 267, 0, 0),
	(6, 16, 268, 0, 0),
	(6, 16, 269, 0, 1),
	(6, 16, 270, 0, 1),
	(6, 16, 271, 0, 0),
	(6, 16, 272, 0, 0),
	(6, 16, 273, 0, 0),
	(6, 16, 274, 0, 0),
	(6, 16, 275, 0, 0),
	(6, 16, 276, 0, 0),
	(6, 16, 277, 0, 0),
	(6, 29, 474, 0, 0),
	(6, 29, 478, 0, 0),
	(6, 29, 479, 0, 0),
	(6, 29, 481, 0, 0),
	(6, 29, 488, 0, 0),
	(6, 29, 489, 0, 0),
	(6, 29, 490, 0, 0),
	(6, 29, 491, 0, 0),
	(6, 29, 493, 0, 0),
	(6, 29, 494, 0, 0),
	(6, 29, 495, 0, 0),
	(6, 29, 496, 0, 0),
	(6, 29, 497, 0, 0),
	(6, 29, 502, 0, 0),
	(6, 29, 503, 0, 0),
	(6, 29, 504, 0, 0),
	(6, 29, 505, 0, 0),
	(6, 10, 518, 0, 0),
	(6, 10, 519, 0, 0),
	(6, 10, 520, 0, 0),
	(6, 10, 521, 0, 0),
	(6, 10, 522, 0, 0),
	(6, 10, 523, 0, 0),
	(6, 29, 540, 0, 0),
	(6, 42, 597, 0, 0),
	(6, 42, 598, 0, 0),
	(6, 42, 599, 0, 1),
	(6, 42, 600, 0, 1),
	(6, 42, 601, 0, 0),
	(6, 42, 602, 0, 1),
	(6, 42, 603, 0, 0),
	(6, 42, 691, 0, 0),
	(6, 4, 701, 0, 0),
	(6, 42, 710, 0, 0),
	(6, 42, 711, 0, 0),
	(6, 9, 729, 0, 1),
	(6, 29, 750, 0, 0),
	(6, 4, 752, 0, 0),
	(6, 4, 754, 0, 0),
	(6, 4, 756, 0, 0),
	(6, 4, 758, 0, 0),
	(6, 4, 760, 0, 0),
	(6, 4, 762, 0, 0),
	(6, 4, 764, 0, 0),
	(6, 4, 766, 0, 0),
	(6, 4, 770, 0, 0);
/*!40000 ALTER TABLE `ncrm_profile2field` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_profile2globalpermissions
CREATE TABLE IF NOT EXISTS `ncrm_profile2globalpermissions` (
  `profileid` int(19) NOT NULL,
  `globalactionid` int(19) NOT NULL,
  `globalactionpermission` int(19) DEFAULT NULL,
  PRIMARY KEY (`profileid`,`globalactionid`),
  KEY `idx_profile2globalpermissions` (`profileid`,`globalactionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_profile2globalpermissions: ~8 rows (approximately)
/*!40000 ALTER TABLE `ncrm_profile2globalpermissions` DISABLE KEYS */;
INSERT INTO `ncrm_profile2globalpermissions` (`profileid`, `globalactionid`, `globalactionpermission`) VALUES
	(1, 1, 0),
	(1, 2, 0),
	(2, 1, 1),
	(2, 2, 1),
	(3, 1, 1),
	(3, 2, 1),
	(4, 1, 1),
	(4, 2, 1),
	(5, 1, 1),
	(5, 2, 1),
	(6, 1, 1),
	(6, 2, 1);
/*!40000 ALTER TABLE `ncrm_profile2globalpermissions` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_profile2standardpermissions
CREATE TABLE IF NOT EXISTS `ncrm_profile2standardpermissions` (
  `profileid` int(11) NOT NULL,
  `tabid` int(10) NOT NULL,
  `operation` int(10) NOT NULL,
  `permissions` int(1) DEFAULT NULL,
  PRIMARY KEY (`profileid`,`tabid`,`operation`),
  KEY `profile2standardpermissions_profileid_tabid_operation_idx` (`profileid`,`tabid`,`operation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_profile2standardpermissions: ~520 rows (approximately)
/*!40000 ALTER TABLE `ncrm_profile2standardpermissions` DISABLE KEYS */;
INSERT INTO `ncrm_profile2standardpermissions` (`profileid`, `tabid`, `operation`, `permissions`) VALUES
	(1, 2, 0, 0),
	(1, 2, 1, 0),
	(1, 2, 2, 0),
	(1, 2, 3, 0),
	(1, 2, 4, 0),
	(1, 4, 0, 0),
	(1, 4, 1, 0),
	(1, 4, 2, 0),
	(1, 4, 3, 0),
	(1, 4, 4, 0),
	(1, 6, 0, 0),
	(1, 6, 1, 0),
	(1, 6, 2, 0),
	(1, 6, 3, 0),
	(1, 6, 4, 0),
	(1, 7, 0, 0),
	(1, 7, 1, 0),
	(1, 7, 2, 0),
	(1, 7, 3, 0),
	(1, 7, 4, 0),
	(1, 8, 0, 0),
	(1, 8, 1, 0),
	(1, 8, 2, 0),
	(1, 8, 3, 0),
	(1, 8, 4, 0),
	(1, 9, 0, 0),
	(1, 9, 1, 0),
	(1, 9, 2, 0),
	(1, 9, 3, 0),
	(1, 9, 4, 0),
	(1, 13, 0, 0),
	(1, 13, 1, 0),
	(1, 13, 2, 0),
	(1, 13, 3, 0),
	(1, 13, 4, 0),
	(1, 14, 0, 0),
	(1, 14, 1, 0),
	(1, 14, 2, 0),
	(1, 14, 3, 0),
	(1, 14, 4, 0),
	(1, 15, 0, 0),
	(1, 15, 1, 0),
	(1, 15, 2, 0),
	(1, 15, 3, 0),
	(1, 15, 4, 0),
	(1, 16, 0, 0),
	(1, 16, 1, 0),
	(1, 16, 2, 0),
	(1, 16, 3, 0),
	(1, 16, 4, 0),
	(1, 18, 0, 0),
	(1, 18, 1, 0),
	(1, 18, 2, 0),
	(1, 18, 3, 0),
	(1, 18, 4, 0),
	(1, 19, 0, 0),
	(1, 19, 1, 0),
	(1, 19, 2, 0),
	(1, 19, 3, 0),
	(1, 19, 4, 0),
	(1, 20, 0, 0),
	(1, 20, 1, 0),
	(1, 20, 2, 0),
	(1, 20, 3, 0),
	(1, 20, 4, 0),
	(1, 21, 0, 0),
	(1, 21, 1, 0),
	(1, 21, 2, 0),
	(1, 21, 3, 0),
	(1, 21, 4, 0),
	(1, 22, 0, 0),
	(1, 22, 1, 0),
	(1, 22, 2, 0),
	(1, 22, 3, 0),
	(1, 22, 4, 0),
	(1, 23, 0, 0),
	(1, 23, 1, 0),
	(1, 23, 2, 0),
	(1, 23, 3, 0),
	(1, 23, 4, 0),
	(1, 26, 0, 0),
	(1, 26, 1, 0),
	(1, 26, 2, 0),
	(1, 26, 3, 0),
	(1, 26, 4, 0),
	(1, 34, 0, 0),
	(1, 34, 1, 0),
	(1, 34, 2, 0),
	(1, 34, 3, 0),
	(1, 34, 4, 0),
	(1, 35, 0, 0),
	(1, 35, 1, 0),
	(1, 35, 2, 0),
	(1, 35, 3, 0),
	(1, 35, 4, 0),
	(1, 36, 0, 0),
	(1, 36, 1, 0),
	(1, 36, 2, 0),
	(1, 36, 3, 0),
	(1, 36, 4, 0),
	(1, 38, 0, 0),
	(1, 38, 1, 0),
	(1, 38, 2, 0),
	(1, 38, 3, 0),
	(1, 38, 4, 0),
	(1, 42, 0, 0),
	(1, 42, 1, 0),
	(1, 42, 2, 0),
	(1, 42, 3, 0),
	(1, 42, 4, 0),
	(1, 43, 0, 0),
	(1, 43, 1, 0),
	(1, 43, 2, 0),
	(1, 43, 3, 0),
	(1, 43, 4, 0),
	(1, 44, 0, 0),
	(1, 44, 1, 0),
	(1, 44, 2, 0),
	(1, 44, 3, 0),
	(1, 44, 4, 0),
	(1, 45, 0, 0),
	(1, 45, 1, 0),
	(1, 45, 2, 0),
	(1, 45, 3, 0),
	(1, 45, 4, 0),
	(1, 47, 0, 0),
	(1, 47, 1, 0),
	(1, 47, 2, 0),
	(1, 47, 3, 0),
	(1, 47, 4, 0),
	(2, 2, 0, 0),
	(2, 2, 1, 0),
	(2, 2, 2, 0),
	(2, 2, 3, 0),
	(2, 2, 4, 0),
	(2, 4, 0, 0),
	(2, 4, 1, 0),
	(2, 4, 2, 0),
	(2, 4, 3, 0),
	(2, 4, 4, 0),
	(2, 6, 0, 0),
	(2, 6, 1, 0),
	(2, 6, 2, 0),
	(2, 6, 3, 0),
	(2, 6, 4, 0),
	(2, 7, 0, 0),
	(2, 7, 1, 0),
	(2, 7, 2, 0),
	(2, 7, 3, 0),
	(2, 7, 4, 0),
	(2, 8, 0, 0),
	(2, 8, 1, 0),
	(2, 8, 2, 0),
	(2, 8, 3, 0),
	(2, 8, 4, 0),
	(2, 9, 0, 0),
	(2, 9, 1, 0),
	(2, 9, 2, 0),
	(2, 9, 3, 0),
	(2, 9, 4, 0),
	(2, 13, 0, 1),
	(2, 13, 1, 1),
	(2, 13, 2, 1),
	(2, 13, 3, 0),
	(2, 13, 4, 0),
	(2, 14, 0, 0),
	(2, 14, 1, 0),
	(2, 14, 2, 0),
	(2, 14, 3, 0),
	(2, 14, 4, 0),
	(2, 15, 0, 0),
	(2, 15, 1, 0),
	(2, 15, 2, 0),
	(2, 15, 3, 0),
	(2, 15, 4, 0),
	(2, 16, 0, 0),
	(2, 16, 1, 0),
	(2, 16, 2, 0),
	(2, 16, 3, 0),
	(2, 16, 4, 0),
	(2, 18, 0, 0),
	(2, 18, 1, 0),
	(2, 18, 2, 0),
	(2, 18, 3, 0),
	(2, 18, 4, 0),
	(2, 19, 0, 0),
	(2, 19, 1, 0),
	(2, 19, 2, 0),
	(2, 19, 3, 0),
	(2, 19, 4, 0),
	(2, 20, 0, 0),
	(2, 20, 1, 0),
	(2, 20, 2, 0),
	(2, 20, 3, 0),
	(2, 20, 4, 0),
	(2, 21, 0, 0),
	(2, 21, 1, 0),
	(2, 21, 2, 0),
	(2, 21, 3, 0),
	(2, 21, 4, 0),
	(2, 22, 0, 0),
	(2, 22, 1, 0),
	(2, 22, 2, 0),
	(2, 22, 3, 0),
	(2, 22, 4, 0),
	(2, 23, 0, 0),
	(2, 23, 1, 0),
	(2, 23, 2, 0),
	(2, 23, 3, 0),
	(2, 23, 4, 0),
	(2, 26, 0, 0),
	(2, 26, 1, 0),
	(2, 26, 2, 0),
	(2, 26, 3, 0),
	(2, 26, 4, 0),
	(2, 34, 0, 0),
	(2, 34, 1, 0),
	(2, 34, 2, 0),
	(2, 34, 3, 0),
	(2, 34, 4, 0),
	(2, 35, 0, 0),
	(2, 35, 1, 0),
	(2, 35, 2, 0),
	(2, 35, 3, 0),
	(2, 35, 4, 0),
	(2, 36, 0, 0),
	(2, 36, 1, 0),
	(2, 36, 2, 0),
	(2, 36, 3, 0),
	(2, 36, 4, 0),
	(2, 38, 0, 0),
	(2, 38, 1, 0),
	(2, 38, 2, 0),
	(2, 38, 3, 0),
	(2, 38, 4, 0),
	(2, 42, 0, 0),
	(2, 42, 1, 0),
	(2, 42, 2, 0),
	(2, 42, 3, 0),
	(2, 42, 4, 0),
	(2, 43, 0, 0),
	(2, 43, 1, 0),
	(2, 43, 2, 0),
	(2, 43, 3, 0),
	(2, 43, 4, 0),
	(2, 44, 0, 0),
	(2, 44, 1, 0),
	(2, 44, 2, 0),
	(2, 44, 3, 0),
	(2, 44, 4, 0),
	(2, 45, 0, 0),
	(2, 45, 1, 0),
	(2, 45, 2, 0),
	(2, 45, 3, 0),
	(2, 45, 4, 0),
	(2, 47, 0, 0),
	(2, 47, 1, 0),
	(2, 47, 2, 0),
	(2, 47, 3, 0),
	(2, 47, 4, 0),
	(3, 2, 0, 1),
	(3, 2, 1, 1),
	(3, 2, 2, 1),
	(3, 2, 3, 0),
	(3, 2, 4, 0),
	(3, 4, 0, 0),
	(3, 4, 1, 0),
	(3, 4, 2, 0),
	(3, 4, 3, 0),
	(3, 4, 4, 0),
	(3, 6, 0, 0),
	(3, 6, 1, 0),
	(3, 6, 2, 0),
	(3, 6, 3, 0),
	(3, 6, 4, 0),
	(3, 7, 0, 0),
	(3, 7, 1, 0),
	(3, 7, 2, 0),
	(3, 7, 3, 0),
	(3, 7, 4, 0),
	(3, 8, 0, 0),
	(3, 8, 1, 0),
	(3, 8, 2, 0),
	(3, 8, 3, 0),
	(3, 8, 4, 0),
	(3, 9, 0, 0),
	(3, 9, 1, 0),
	(3, 9, 2, 0),
	(3, 9, 3, 0),
	(3, 9, 4, 0),
	(3, 13, 0, 0),
	(3, 13, 1, 0),
	(3, 13, 2, 0),
	(3, 13, 3, 0),
	(3, 13, 4, 0),
	(3, 14, 0, 0),
	(3, 14, 1, 0),
	(3, 14, 2, 0),
	(3, 14, 3, 0),
	(3, 14, 4, 0),
	(3, 15, 0, 0),
	(3, 15, 1, 0),
	(3, 15, 2, 0),
	(3, 15, 3, 0),
	(3, 15, 4, 0),
	(3, 16, 0, 0),
	(3, 16, 1, 0),
	(3, 16, 2, 0),
	(3, 16, 3, 0),
	(3, 16, 4, 0),
	(3, 18, 0, 0),
	(3, 18, 1, 0),
	(3, 18, 2, 0),
	(3, 18, 3, 0),
	(3, 18, 4, 0),
	(3, 19, 0, 0),
	(3, 19, 1, 0),
	(3, 19, 2, 0),
	(3, 19, 3, 0),
	(3, 19, 4, 0),
	(3, 20, 0, 0),
	(3, 20, 1, 0),
	(3, 20, 2, 0),
	(3, 20, 3, 0),
	(3, 20, 4, 0),
	(3, 21, 0, 0),
	(3, 21, 1, 0),
	(3, 21, 2, 0),
	(3, 21, 3, 0),
	(3, 21, 4, 0),
	(3, 22, 0, 0),
	(3, 22, 1, 0),
	(3, 22, 2, 0),
	(3, 22, 3, 0),
	(3, 22, 4, 0),
	(3, 23, 0, 0),
	(3, 23, 1, 0),
	(3, 23, 2, 0),
	(3, 23, 3, 0),
	(3, 23, 4, 0),
	(3, 26, 0, 0),
	(3, 26, 1, 0),
	(3, 26, 2, 0),
	(3, 26, 3, 0),
	(3, 26, 4, 0),
	(3, 34, 0, 0),
	(3, 34, 1, 0),
	(3, 34, 2, 0),
	(3, 34, 3, 0),
	(3, 34, 4, 0),
	(3, 35, 0, 0),
	(3, 35, 1, 0),
	(3, 35, 2, 0),
	(3, 35, 3, 0),
	(3, 35, 4, 0),
	(3, 36, 0, 0),
	(3, 36, 1, 0),
	(3, 36, 2, 0),
	(3, 36, 3, 0),
	(3, 36, 4, 0),
	(3, 38, 0, 0),
	(3, 38, 1, 0),
	(3, 38, 2, 0),
	(3, 38, 3, 0),
	(3, 38, 4, 0),
	(3, 42, 0, 0),
	(3, 42, 1, 0),
	(3, 42, 2, 0),
	(3, 42, 3, 0),
	(3, 42, 4, 0),
	(3, 43, 0, 0),
	(3, 43, 1, 0),
	(3, 43, 2, 0),
	(3, 43, 3, 0),
	(3, 43, 4, 0),
	(3, 44, 0, 0),
	(3, 44, 1, 0),
	(3, 44, 2, 0),
	(3, 44, 3, 0),
	(3, 44, 4, 0),
	(3, 45, 0, 0),
	(3, 45, 1, 0),
	(3, 45, 2, 0),
	(3, 45, 3, 0),
	(3, 45, 4, 0),
	(3, 47, 0, 0),
	(3, 47, 1, 0),
	(3, 47, 2, 0),
	(3, 47, 3, 0),
	(3, 47, 4, 0),
	(4, 2, 0, 1),
	(4, 2, 1, 1),
	(4, 2, 2, 1),
	(4, 2, 3, 0),
	(4, 2, 4, 0),
	(4, 4, 0, 1),
	(4, 4, 1, 1),
	(4, 4, 2, 1),
	(4, 4, 3, 0),
	(4, 4, 4, 0),
	(4, 6, 0, 1),
	(4, 6, 1, 1),
	(4, 6, 2, 1),
	(4, 6, 3, 0),
	(4, 6, 4, 0),
	(4, 7, 0, 1),
	(4, 7, 1, 1),
	(4, 7, 2, 1),
	(4, 7, 3, 0),
	(4, 7, 4, 0),
	(4, 8, 0, 1),
	(4, 8, 1, 1),
	(4, 8, 2, 1),
	(4, 8, 3, 0),
	(4, 8, 4, 0),
	(4, 9, 0, 1),
	(4, 9, 1, 1),
	(4, 9, 2, 1),
	(4, 9, 3, 0),
	(4, 9, 4, 0),
	(4, 13, 0, 1),
	(4, 13, 1, 1),
	(4, 13, 2, 1),
	(4, 13, 3, 0),
	(4, 13, 4, 0),
	(4, 14, 0, 1),
	(4, 14, 1, 1),
	(4, 14, 2, 1),
	(4, 14, 3, 0),
	(4, 14, 4, 0),
	(4, 15, 0, 1),
	(4, 15, 1, 1),
	(4, 15, 2, 1),
	(4, 15, 3, 0),
	(4, 15, 4, 0),
	(4, 16, 0, 1),
	(4, 16, 1, 1),
	(4, 16, 2, 1),
	(4, 16, 3, 0),
	(4, 16, 4, 0),
	(4, 18, 0, 1),
	(4, 18, 1, 1),
	(4, 18, 2, 1),
	(4, 18, 3, 0),
	(4, 18, 4, 0),
	(4, 19, 0, 1),
	(4, 19, 1, 1),
	(4, 19, 2, 1),
	(4, 19, 3, 0),
	(4, 19, 4, 0),
	(4, 20, 0, 1),
	(4, 20, 1, 1),
	(4, 20, 2, 1),
	(4, 20, 3, 0),
	(4, 20, 4, 0),
	(4, 21, 0, 1),
	(4, 21, 1, 1),
	(4, 21, 2, 1),
	(4, 21, 3, 0),
	(4, 21, 4, 0),
	(4, 22, 0, 1),
	(4, 22, 1, 1),
	(4, 22, 2, 1),
	(4, 22, 3, 0),
	(4, 22, 4, 0),
	(4, 23, 0, 1),
	(4, 23, 1, 1),
	(4, 23, 2, 1),
	(4, 23, 3, 0),
	(4, 23, 4, 0),
	(4, 26, 0, 1),
	(4, 26, 1, 1),
	(4, 26, 2, 1),
	(4, 26, 3, 0),
	(4, 26, 4, 0),
	(4, 34, 0, 0),
	(4, 34, 1, 0),
	(4, 34, 2, 0),
	(4, 34, 3, 0),
	(4, 34, 4, 0),
	(4, 35, 0, 0),
	(4, 35, 1, 0),
	(4, 35, 2, 0),
	(4, 35, 3, 0),
	(4, 35, 4, 0),
	(4, 36, 0, 0),
	(4, 36, 1, 0),
	(4, 36, 2, 0),
	(4, 36, 3, 0),
	(4, 36, 4, 0),
	(4, 38, 0, 0),
	(4, 38, 1, 0),
	(4, 38, 2, 0),
	(4, 38, 3, 0),
	(4, 38, 4, 0),
	(4, 42, 0, 0),
	(4, 42, 1, 0),
	(4, 42, 2, 0),
	(4, 42, 3, 0),
	(4, 42, 4, 0),
	(4, 43, 0, 0),
	(4, 43, 1, 0),
	(4, 43, 2, 0),
	(4, 43, 3, 0),
	(4, 43, 4, 0),
	(4, 44, 0, 0),
	(4, 44, 1, 0),
	(4, 44, 2, 0),
	(4, 44, 3, 0),
	(4, 44, 4, 0),
	(4, 45, 0, 0),
	(4, 45, 1, 0),
	(4, 45, 2, 0),
	(4, 45, 3, 0),
	(4, 45, 4, 0),
	(4, 47, 0, 0),
	(4, 47, 1, 0),
	(4, 47, 2, 0),
	(4, 47, 3, 0),
	(4, 47, 4, 0),
	(5, 4, 0, 0),
	(5, 4, 1, 0),
	(5, 4, 2, 0),
	(5, 4, 4, 0),
	(5, 9, 0, 0),
	(5, 9, 1, 0),
	(5, 9, 2, 0),
	(5, 9, 4, 0),
	(5, 16, 0, 0),
	(5, 16, 1, 0),
	(5, 16, 2, 0),
	(5, 16, 4, 0),
	(5, 25, 0, 1),
	(5, 25, 1, 1),
	(5, 25, 2, 1),
	(5, 25, 4, 1),
	(5, 42, 0, 0),
	(5, 42, 1, 0),
	(5, 42, 2, 0),
	(5, 42, 4, 0),
	(6, 4, 0, 0),
	(6, 4, 1, 0),
	(6, 4, 2, 0),
	(6, 4, 4, 0),
	(6, 9, 0, 0),
	(6, 9, 1, 0),
	(6, 9, 2, 0),
	(6, 9, 4, 0),
	(6, 16, 0, 0),
	(6, 16, 1, 0),
	(6, 16, 2, 0),
	(6, 16, 4, 0),
	(6, 25, 0, 1),
	(6, 25, 1, 1),
	(6, 25, 2, 1),
	(6, 25, 4, 1),
	(6, 42, 0, 0),
	(6, 42, 1, 0),
	(6, 42, 2, 0),
	(6, 42, 4, 0);
/*!40000 ALTER TABLE `ncrm_profile2standardpermissions` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_profile2tab
CREATE TABLE IF NOT EXISTS `ncrm_profile2tab` (
  `profileid` int(11) DEFAULT NULL,
  `tabid` int(10) DEFAULT NULL,
  `permissions` int(10) NOT NULL DEFAULT '0',
  KEY `profile2tab_profileid_tabid_idx` (`profileid`,`tabid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_profile2tab: ~172 rows (approximately)
/*!40000 ALTER TABLE `ncrm_profile2tab` DISABLE KEYS */;
INSERT INTO `ncrm_profile2tab` (`profileid`, `tabid`, `permissions`) VALUES
	(1, 1, 0),
	(1, 2, 0),
	(1, 3, 0),
	(1, 4, 0),
	(1, 6, 0),
	(1, 7, 0),
	(1, 8, 0),
	(1, 9, 0),
	(1, 10, 0),
	(1, 13, 0),
	(1, 14, 0),
	(1, 15, 0),
	(1, 16, 0),
	(1, 18, 0),
	(1, 19, 0),
	(1, 20, 0),
	(1, 21, 0),
	(1, 22, 0),
	(1, 23, 0),
	(1, 24, 0),
	(1, 25, 0),
	(1, 26, 0),
	(1, 27, 0),
	(2, 1, 0),
	(2, 2, 0),
	(2, 3, 0),
	(2, 4, 0),
	(2, 6, 0),
	(2, 7, 0),
	(2, 8, 0),
	(2, 9, 0),
	(2, 10, 0),
	(2, 13, 0),
	(2, 14, 0),
	(2, 15, 0),
	(2, 16, 0),
	(2, 18, 0),
	(2, 19, 0),
	(2, 20, 0),
	(2, 21, 0),
	(2, 22, 0),
	(2, 23, 0),
	(2, 24, 0),
	(2, 25, 0),
	(2, 26, 0),
	(2, 27, 0),
	(3, 1, 0),
	(3, 2, 0),
	(3, 3, 0),
	(3, 4, 0),
	(3, 6, 0),
	(3, 7, 0),
	(3, 8, 0),
	(3, 9, 0),
	(3, 10, 0),
	(3, 13, 0),
	(3, 14, 0),
	(3, 15, 0),
	(3, 16, 0),
	(3, 18, 0),
	(3, 19, 0),
	(3, 20, 0),
	(3, 21, 0),
	(3, 22, 0),
	(3, 23, 0),
	(3, 24, 0),
	(3, 25, 0),
	(3, 26, 0),
	(3, 27, 0),
	(4, 1, 0),
	(4, 2, 0),
	(4, 3, 0),
	(4, 4, 0),
	(4, 6, 0),
	(4, 7, 0),
	(4, 8, 0),
	(4, 9, 0),
	(4, 10, 0),
	(4, 13, 0),
	(4, 14, 0),
	(4, 15, 0),
	(4, 16, 0),
	(4, 18, 0),
	(4, 19, 0),
	(4, 20, 0),
	(4, 21, 0),
	(4, 22, 0),
	(4, 23, 0),
	(4, 24, 0),
	(4, 25, 0),
	(4, 26, 0),
	(4, 27, 0),
	(1, 30, 0),
	(2, 30, 0),
	(3, 30, 0),
	(4, 30, 0),
	(1, 31, 0),
	(2, 31, 0),
	(3, 31, 0),
	(4, 31, 0),
	(1, 32, 0),
	(2, 32, 0),
	(3, 32, 0),
	(4, 32, 0),
	(1, 33, 0),
	(2, 33, 0),
	(3, 33, 0),
	(4, 33, 0),
	(1, 34, 0),
	(2, 34, 0),
	(3, 34, 0),
	(4, 34, 0),
	(1, 35, 0),
	(2, 35, 0),
	(3, 35, 0),
	(4, 35, 0),
	(1, 36, 0),
	(2, 36, 0),
	(3, 36, 0),
	(4, 36, 0),
	(1, 37, 0),
	(2, 37, 0),
	(3, 37, 0),
	(4, 37, 0),
	(1, 38, 0),
	(2, 38, 0),
	(3, 38, 0),
	(4, 38, 0),
	(1, 39, 0),
	(2, 39, 0),
	(3, 39, 0),
	(4, 39, 0),
	(1, 40, 0),
	(2, 40, 0),
	(3, 40, 0),
	(4, 40, 0),
	(1, 41, 0),
	(2, 41, 0),
	(3, 41, 0),
	(4, 41, 0),
	(1, 42, 0),
	(2, 42, 0),
	(3, 42, 0),
	(4, 42, 0),
	(1, 43, 0),
	(2, 43, 0),
	(3, 43, 0),
	(4, 43, 0),
	(1, 44, 0),
	(2, 44, 0),
	(3, 44, 0),
	(4, 44, 0),
	(1, 45, 0),
	(2, 45, 0),
	(3, 45, 0),
	(4, 45, 0),
	(1, 46, 0),
	(2, 46, 0),
	(3, 46, 0),
	(4, 46, 0),
	(1, 47, 0),
	(2, 47, 0),
	(3, 47, 0),
	(4, 47, 0),
	(1, 48, 0),
	(2, 48, 0),
	(3, 48, 0),
	(4, 48, 0),
	(1, 49, 0),
	(2, 49, 0),
	(3, 49, 0),
	(4, 49, 0),
	(5, 1, 0),
	(5, 4, 0),
	(5, 9, 0),
	(5, 10, 0),
	(5, 25, 0),
	(5, 42, 0),
	(5, 48, 1),
	(5, 49, 1),
	(5, 16, 0),
	(6, 1, 0),
	(6, 4, 0),
	(6, 9, 0),
	(6, 10, 0),
	(6, 25, 0),
	(6, 42, 0),
	(6, 48, 1),
	(6, 49, 1),
	(6, 16, 0);
/*!40000 ALTER TABLE `ncrm_profile2tab` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_profile2utility
CREATE TABLE IF NOT EXISTS `ncrm_profile2utility` (
  `profileid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `activityid` int(11) NOT NULL,
  `permission` int(1) DEFAULT NULL,
  PRIMARY KEY (`profileid`,`tabid`,`activityid`),
  KEY `profile2utility_profileid_tabid_activityid_idx` (`profileid`,`tabid`,`activityid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_profile2utility: ~248 rows (approximately)
/*!40000 ALTER TABLE `ncrm_profile2utility` DISABLE KEYS */;
INSERT INTO `ncrm_profile2utility` (`profileid`, `tabid`, `activityid`, `permission`) VALUES
	(1, 2, 5, 0),
	(1, 2, 6, 0),
	(1, 2, 10, 0),
	(1, 4, 5, 0),
	(1, 4, 6, 0),
	(1, 4, 8, 0),
	(1, 4, 10, 0),
	(1, 6, 5, 0),
	(1, 6, 6, 0),
	(1, 6, 8, 0),
	(1, 6, 10, 0),
	(1, 7, 5, 0),
	(1, 7, 6, 0),
	(1, 7, 8, 0),
	(1, 7, 9, 0),
	(1, 7, 10, 0),
	(1, 8, 6, 0),
	(1, 9, 5, 0),
	(1, 9, 6, 0),
	(1, 13, 5, 0),
	(1, 13, 6, 0),
	(1, 13, 8, 0),
	(1, 13, 10, 0),
	(1, 14, 5, 0),
	(1, 14, 6, 0),
	(1, 14, 10, 0),
	(1, 18, 5, 0),
	(1, 18, 6, 0),
	(1, 18, 10, 0),
	(1, 19, 5, 0),
	(1, 19, 6, 0),
	(1, 19, 10, 0),
	(1, 20, 5, 0),
	(1, 20, 6, 0),
	(1, 21, 5, 0),
	(1, 21, 6, 0),
	(1, 22, 5, 0),
	(1, 22, 6, 0),
	(1, 23, 5, 0),
	(1, 23, 6, 0),
	(1, 25, 6, 0),
	(1, 25, 13, 0),
	(1, 34, 11, 1),
	(1, 34, 12, 1),
	(1, 35, 5, 0),
	(1, 35, 6, 0),
	(1, 35, 10, 0),
	(1, 36, 5, 0),
	(1, 36, 6, 0),
	(1, 36, 10, 0),
	(1, 40, 5, 0),
	(1, 40, 6, 0),
	(1, 40, 10, 0),
	(1, 43, 5, 0),
	(1, 43, 6, 0),
	(1, 43, 10, 0),
	(1, 44, 5, 0),
	(1, 44, 6, 0),
	(1, 44, 10, 0),
	(1, 45, 5, 0),
	(1, 45, 6, 0),
	(1, 45, 10, 0),
	(2, 2, 5, 1),
	(2, 2, 6, 1),
	(2, 2, 10, 0),
	(2, 4, 5, 1),
	(2, 4, 6, 1),
	(2, 4, 8, 0),
	(2, 4, 10, 0),
	(2, 6, 5, 1),
	(2, 6, 6, 1),
	(2, 6, 8, 0),
	(2, 6, 10, 0),
	(2, 7, 5, 1),
	(2, 7, 6, 1),
	(2, 7, 8, 0),
	(2, 7, 9, 0),
	(2, 7, 10, 0),
	(2, 8, 6, 1),
	(2, 9, 5, 0),
	(2, 9, 6, 0),
	(2, 13, 5, 1),
	(2, 13, 6, 1),
	(2, 13, 8, 0),
	(2, 13, 10, 0),
	(2, 14, 5, 1),
	(2, 14, 6, 1),
	(2, 14, 10, 0),
	(2, 18, 5, 1),
	(2, 18, 6, 1),
	(2, 18, 10, 0),
	(2, 19, 5, 1),
	(2, 19, 6, 1),
	(2, 19, 10, 0),
	(2, 20, 5, 0),
	(2, 20, 6, 0),
	(2, 21, 5, 0),
	(2, 21, 6, 0),
	(2, 22, 5, 0),
	(2, 22, 6, 0),
	(2, 23, 5, 0),
	(2, 23, 6, 0),
	(2, 25, 6, 0),
	(2, 25, 13, 0),
	(2, 34, 11, 1),
	(2, 34, 12, 1),
	(2, 35, 5, 0),
	(2, 35, 6, 0),
	(2, 35, 10, 0),
	(2, 36, 5, 0),
	(2, 36, 6, 0),
	(2, 36, 10, 0),
	(2, 40, 5, 1),
	(2, 40, 6, 1),
	(2, 40, 10, 0),
	(2, 43, 5, 0),
	(2, 43, 6, 0),
	(2, 43, 10, 0),
	(2, 44, 5, 0),
	(2, 44, 6, 0),
	(2, 44, 10, 0),
	(2, 45, 5, 0),
	(2, 45, 6, 0),
	(2, 45, 10, 0),
	(3, 2, 5, 1),
	(3, 2, 6, 1),
	(3, 2, 10, 0),
	(3, 4, 5, 1),
	(3, 4, 6, 1),
	(3, 4, 8, 0),
	(3, 4, 10, 0),
	(3, 6, 5, 1),
	(3, 6, 6, 1),
	(3, 6, 8, 0),
	(3, 6, 10, 0),
	(3, 7, 5, 1),
	(3, 7, 6, 1),
	(3, 7, 8, 0),
	(3, 7, 9, 0),
	(3, 7, 10, 0),
	(3, 8, 6, 1),
	(3, 9, 5, 0),
	(3, 9, 6, 0),
	(3, 13, 5, 1),
	(3, 13, 6, 1),
	(3, 13, 8, 0),
	(3, 13, 10, 0),
	(3, 14, 5, 1),
	(3, 14, 6, 1),
	(3, 14, 10, 0),
	(3, 18, 5, 1),
	(3, 18, 6, 1),
	(3, 18, 10, 0),
	(3, 19, 5, 1),
	(3, 19, 6, 1),
	(3, 19, 10, 0),
	(3, 20, 5, 0),
	(3, 20, 6, 0),
	(3, 21, 5, 0),
	(3, 21, 6, 0),
	(3, 22, 5, 0),
	(3, 22, 6, 0),
	(3, 23, 5, 0),
	(3, 23, 6, 0),
	(3, 25, 6, 0),
	(3, 25, 13, 0),
	(3, 34, 11, 1),
	(3, 34, 12, 1),
	(3, 35, 5, 0),
	(3, 35, 6, 0),
	(3, 35, 10, 0),
	(3, 36, 5, 0),
	(3, 36, 6, 0),
	(3, 36, 10, 0),
	(3, 40, 5, 1),
	(3, 40, 6, 1),
	(3, 40, 10, 0),
	(3, 43, 5, 0),
	(3, 43, 6, 0),
	(3, 43, 10, 0),
	(3, 44, 5, 0),
	(3, 44, 6, 0),
	(3, 44, 10, 0),
	(3, 45, 5, 0),
	(3, 45, 6, 0),
	(3, 45, 10, 0),
	(4, 2, 5, 1),
	(4, 2, 6, 1),
	(4, 2, 10, 0),
	(4, 4, 5, 1),
	(4, 4, 6, 1),
	(4, 4, 8, 1),
	(4, 4, 10, 0),
	(4, 6, 5, 1),
	(4, 6, 6, 1),
	(4, 6, 8, 1),
	(4, 6, 10, 0),
	(4, 7, 5, 1),
	(4, 7, 6, 1),
	(4, 7, 8, 1),
	(4, 7, 9, 0),
	(4, 7, 10, 0),
	(4, 8, 6, 1),
	(4, 9, 5, 0),
	(4, 9, 6, 0),
	(4, 13, 5, 1),
	(4, 13, 6, 1),
	(4, 13, 8, 1),
	(4, 13, 10, 0),
	(4, 14, 5, 1),
	(4, 14, 6, 1),
	(4, 14, 10, 0),
	(4, 18, 5, 1),
	(4, 18, 6, 1),
	(4, 18, 10, 0),
	(4, 19, 5, 1),
	(4, 19, 6, 1),
	(4, 19, 10, 0),
	(4, 20, 5, 0),
	(4, 20, 6, 0),
	(4, 21, 5, 0),
	(4, 21, 6, 0),
	(4, 22, 5, 0),
	(4, 22, 6, 0),
	(4, 23, 5, 0),
	(4, 23, 6, 0),
	(4, 25, 6, 0),
	(4, 25, 13, 0),
	(4, 34, 11, 1),
	(4, 34, 12, 1),
	(4, 35, 5, 0),
	(4, 35, 6, 0),
	(4, 35, 10, 0),
	(4, 36, 5, 0),
	(4, 36, 6, 0),
	(4, 36, 10, 0),
	(4, 40, 5, 1),
	(4, 40, 6, 1),
	(4, 40, 10, 0),
	(4, 43, 5, 0),
	(4, 43, 6, 0),
	(4, 43, 10, 0),
	(4, 44, 5, 0),
	(4, 44, 6, 0),
	(4, 44, 10, 0),
	(4, 45, 5, 0),
	(4, 45, 6, 0),
	(4, 45, 10, 0),
	(5, 4, 5, 0),
	(5, 4, 6, 0),
	(5, 4, 10, 0),
	(5, 9, 5, 0),
	(5, 9, 6, 0),
	(5, 16, 5, 0),
	(5, 16, 6, 0),
	(5, 25, 6, 0),
	(5, 25, 13, 0),
	(6, 4, 5, 0),
	(6, 4, 6, 0),
	(6, 4, 10, 0),
	(6, 9, 5, 0),
	(6, 9, 6, 0),
	(6, 16, 5, 0),
	(6, 16, 6, 0),
	(6, 25, 6, 0),
	(6, 25, 13, 0);
/*!40000 ALTER TABLE `ncrm_profile2utility` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_profile_seq
CREATE TABLE IF NOT EXISTS `ncrm_profile_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_profile_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_profile_seq` DISABLE KEYS */;
INSERT INTO `ncrm_profile_seq` (`id`) VALUES
	(6);
/*!40000 ALTER TABLE `ncrm_profile_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_progress
CREATE TABLE IF NOT EXISTS `ncrm_progress` (
  `progressid` int(11) NOT NULL AUTO_INCREMENT,
  `progress` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(11) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT '0',
  PRIMARY KEY (`progressid`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_progress: ~10 rows (approximately)
/*!40000 ALTER TABLE `ncrm_progress` DISABLE KEYS */;
INSERT INTO `ncrm_progress` (`progressid`, `progress`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(2, '10%', 1, 278, 2),
	(3, '20%', 1, 279, 3),
	(4, '30%', 1, 280, 4),
	(5, '40%', 1, 281, 5),
	(6, '50%', 1, 282, 6),
	(7, '60%', 1, 283, 7),
	(8, '70%', 1, 284, 8),
	(9, '80%', 1, 285, 9),
	(10, '90%', 1, 286, 10),
	(11, '100%', 1, 287, 11);
/*!40000 ALTER TABLE `ncrm_progress` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_progress_seq
CREATE TABLE IF NOT EXISTS `ncrm_progress_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_progress_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_progress_seq` DISABLE KEYS */;
INSERT INTO `ncrm_progress_seq` (`id`) VALUES
	(11);
/*!40000 ALTER TABLE `ncrm_progress_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_project
CREATE TABLE IF NOT EXISTS `ncrm_project` (
  `projectid` int(11) DEFAULT NULL,
  `projectname` varchar(255) DEFAULT NULL,
  `project_no` varchar(100) DEFAULT NULL,
  `startdate` date DEFAULT NULL,
  `targetenddate` date DEFAULT NULL,
  `actualenddate` date DEFAULT NULL,
  `targetbudget` varchar(255) DEFAULT NULL,
  `projecturl` varchar(255) DEFAULT NULL,
  `projectstatus` varchar(100) DEFAULT NULL,
  `projectpriority` varchar(100) DEFAULT NULL,
  `projecttype` varchar(100) DEFAULT NULL,
  `progress` varchar(100) DEFAULT NULL,
  `linktoaccountscontacts` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_project: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_project` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_project` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_projectcf
CREATE TABLE IF NOT EXISTS `ncrm_projectcf` (
  `projectid` int(11) NOT NULL,
  PRIMARY KEY (`projectid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_projectcf: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_projectcf` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_projectcf` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_projectmilestone
CREATE TABLE IF NOT EXISTS `ncrm_projectmilestone` (
  `projectmilestoneid` int(11) NOT NULL,
  `projectmilestonename` varchar(255) DEFAULT NULL,
  `projectmilestone_no` varchar(100) DEFAULT NULL,
  `projectmilestonedate` varchar(255) DEFAULT NULL,
  `projectid` varchar(100) DEFAULT NULL,
  `projectmilestonetype` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`projectmilestoneid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_projectmilestone: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_projectmilestone` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_projectmilestone` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_projectmilestonecf
CREATE TABLE IF NOT EXISTS `ncrm_projectmilestonecf` (
  `projectmilestoneid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`projectmilestoneid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_projectmilestonecf: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_projectmilestonecf` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_projectmilestonecf` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_projectmilestonetype
CREATE TABLE IF NOT EXISTS `ncrm_projectmilestonetype` (
  `projectmilestonetypeid` int(11) NOT NULL AUTO_INCREMENT,
  `projectmilestonetype` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(11) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT '0',
  PRIMARY KEY (`projectmilestonetypeid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_projectmilestonetype: ~3 rows (approximately)
/*!40000 ALTER TABLE `ncrm_projectmilestonetype` DISABLE KEYS */;
INSERT INTO `ncrm_projectmilestonetype` (`projectmilestonetypeid`, `projectmilestonetype`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(2, 'administrative', 1, 238, 2),
	(3, 'operative', 1, 239, 3),
	(4, 'other', 1, 240, 4);
/*!40000 ALTER TABLE `ncrm_projectmilestonetype` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_projectmilestonetype_seq
CREATE TABLE IF NOT EXISTS `ncrm_projectmilestonetype_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_projectmilestonetype_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_projectmilestonetype_seq` DISABLE KEYS */;
INSERT INTO `ncrm_projectmilestonetype_seq` (`id`) VALUES
	(4);
/*!40000 ALTER TABLE `ncrm_projectmilestonetype_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_projectpriority
CREATE TABLE IF NOT EXISTS `ncrm_projectpriority` (
  `projectpriorityid` int(11) NOT NULL AUTO_INCREMENT,
  `projectpriority` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(11) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT '0',
  PRIMARY KEY (`projectpriorityid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_projectpriority: ~3 rows (approximately)
/*!40000 ALTER TABLE `ncrm_projectpriority` DISABLE KEYS */;
INSERT INTO `ncrm_projectpriority` (`projectpriorityid`, `projectpriority`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(2, 'low', 1, 274, 2),
	(3, 'normal', 1, 275, 3),
	(4, 'high', 1, 276, 4);
/*!40000 ALTER TABLE `ncrm_projectpriority` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_projectpriority_seq
CREATE TABLE IF NOT EXISTS `ncrm_projectpriority_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_projectpriority_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_projectpriority_seq` DISABLE KEYS */;
INSERT INTO `ncrm_projectpriority_seq` (`id`) VALUES
	(4);
/*!40000 ALTER TABLE `ncrm_projectpriority_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_projectstatus
CREATE TABLE IF NOT EXISTS `ncrm_projectstatus` (
  `projectstatusid` int(11) NOT NULL AUTO_INCREMENT,
  `projectstatus` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(11) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT '0',
  PRIMARY KEY (`projectstatusid`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_projectstatus: ~8 rows (approximately)
/*!40000 ALTER TABLE `ncrm_projectstatus` DISABLE KEYS */;
INSERT INTO `ncrm_projectstatus` (`projectstatusid`, `projectstatus`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(2, 'prospecting', 1, 261, 2),
	(3, 'initiated', 1, 262, 3),
	(4, 'in progress', 1, 263, 4),
	(5, 'waiting for feedback', 1, 264, 5),
	(6, 'on hold', 1, 265, 6),
	(7, 'completed', 1, 266, 7),
	(8, 'delivered', 1, 267, 8),
	(9, 'archived', 1, 268, 9);
/*!40000 ALTER TABLE `ncrm_projectstatus` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_projectstatus_seq
CREATE TABLE IF NOT EXISTS `ncrm_projectstatus_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_projectstatus_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_projectstatus_seq` DISABLE KEYS */;
INSERT INTO `ncrm_projectstatus_seq` (`id`) VALUES
	(9);
/*!40000 ALTER TABLE `ncrm_projectstatus_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_projecttask
CREATE TABLE IF NOT EXISTS `ncrm_projecttask` (
  `projecttaskid` int(11) NOT NULL,
  `projecttaskname` varchar(255) DEFAULT NULL,
  `projecttask_no` varchar(100) DEFAULT NULL,
  `projecttasktype` varchar(100) DEFAULT NULL,
  `projecttaskpriority` varchar(100) DEFAULT NULL,
  `projecttaskprogress` varchar(100) DEFAULT NULL,
  `projecttaskhours` varchar(255) DEFAULT NULL,
  `startdate` date DEFAULT NULL,
  `enddate` date DEFAULT NULL,
  `projectid` varchar(100) DEFAULT NULL,
  `projecttasknumber` int(11) DEFAULT NULL,
  `projecttaskstatus` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`projecttaskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_projecttask: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_projecttask` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_projecttask` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_projecttaskcf
CREATE TABLE IF NOT EXISTS `ncrm_projecttaskcf` (
  `projecttaskid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`projecttaskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_projecttaskcf: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_projecttaskcf` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_projecttaskcf` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_projecttaskpriority
CREATE TABLE IF NOT EXISTS `ncrm_projecttaskpriority` (
  `projecttaskpriorityid` int(11) NOT NULL AUTO_INCREMENT,
  `projecttaskpriority` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(11) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT '0',
  PRIMARY KEY (`projecttaskpriorityid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_projecttaskpriority: ~3 rows (approximately)
/*!40000 ALTER TABLE `ncrm_projecttaskpriority` DISABLE KEYS */;
INSERT INTO `ncrm_projecttaskpriority` (`projecttaskpriorityid`, `projecttaskpriority`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(2, 'low', 1, 246, 2),
	(3, 'normal', 1, 247, 3),
	(4, 'high', 1, 248, 4);
/*!40000 ALTER TABLE `ncrm_projecttaskpriority` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_projecttaskpriority_seq
CREATE TABLE IF NOT EXISTS `ncrm_projecttaskpriority_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_projecttaskpriority_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_projecttaskpriority_seq` DISABLE KEYS */;
INSERT INTO `ncrm_projecttaskpriority_seq` (`id`) VALUES
	(4);
/*!40000 ALTER TABLE `ncrm_projecttaskpriority_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_projecttaskprogress
CREATE TABLE IF NOT EXISTS `ncrm_projecttaskprogress` (
  `projecttaskprogressid` int(11) NOT NULL AUTO_INCREMENT,
  `projecttaskprogress` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(11) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT '0',
  PRIMARY KEY (`projecttaskprogressid`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_projecttaskprogress: ~10 rows (approximately)
/*!40000 ALTER TABLE `ncrm_projecttaskprogress` DISABLE KEYS */;
INSERT INTO `ncrm_projecttaskprogress` (`projecttaskprogressid`, `projecttaskprogress`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(2, '10%', 1, 250, 2),
	(3, '20%', 1, 251, 3),
	(4, '30%', 1, 252, 4),
	(5, '40%', 1, 253, 5),
	(6, '50%', 1, 254, 6),
	(7, '60%', 1, 255, 7),
	(8, '70%', 1, 256, 8),
	(9, '80%', 1, 257, 9),
	(10, '90%', 1, 258, 10),
	(11, '100%', 1, 259, 11);
/*!40000 ALTER TABLE `ncrm_projecttaskprogress` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_projecttaskprogress_seq
CREATE TABLE IF NOT EXISTS `ncrm_projecttaskprogress_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_projecttaskprogress_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_projecttaskprogress_seq` DISABLE KEYS */;
INSERT INTO `ncrm_projecttaskprogress_seq` (`id`) VALUES
	(11);
/*!40000 ALTER TABLE `ncrm_projecttaskprogress_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_projecttaskstatus
CREATE TABLE IF NOT EXISTS `ncrm_projecttaskstatus` (
  `projecttaskstatusid` int(11) NOT NULL AUTO_INCREMENT,
  `projecttaskstatus` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(11) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT '0',
  PRIMARY KEY (`projecttaskstatusid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_projecttaskstatus: ~5 rows (approximately)
/*!40000 ALTER TABLE `ncrm_projecttaskstatus` DISABLE KEYS */;
INSERT INTO `ncrm_projecttaskstatus` (`projecttaskstatusid`, `projecttaskstatus`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(2, 'Open', 0, 290, 2),
	(3, 'In Progress', 0, 291, 3),
	(4, 'Completed', 0, 292, 4),
	(5, 'Deferred', 0, 293, 5),
	(6, 'Canceled ', 0, 294, 6);
/*!40000 ALTER TABLE `ncrm_projecttaskstatus` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_projecttaskstatus_seq
CREATE TABLE IF NOT EXISTS `ncrm_projecttaskstatus_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_projecttaskstatus_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_projecttaskstatus_seq` DISABLE KEYS */;
INSERT INTO `ncrm_projecttaskstatus_seq` (`id`) VALUES
	(6);
/*!40000 ALTER TABLE `ncrm_projecttaskstatus_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_projecttasktype
CREATE TABLE IF NOT EXISTS `ncrm_projecttasktype` (
  `projecttasktypeid` int(11) NOT NULL AUTO_INCREMENT,
  `projecttasktype` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(11) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT '0',
  PRIMARY KEY (`projecttasktypeid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_projecttasktype: ~3 rows (approximately)
/*!40000 ALTER TABLE `ncrm_projecttasktype` DISABLE KEYS */;
INSERT INTO `ncrm_projecttasktype` (`projecttasktypeid`, `projecttasktype`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(2, 'administrative', 1, 242, 2),
	(3, 'operative', 1, 243, 3),
	(4, 'other', 1, 244, 4);
/*!40000 ALTER TABLE `ncrm_projecttasktype` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_projecttasktype_seq
CREATE TABLE IF NOT EXISTS `ncrm_projecttasktype_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_projecttasktype_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_projecttasktype_seq` DISABLE KEYS */;
INSERT INTO `ncrm_projecttasktype_seq` (`id`) VALUES
	(4);
/*!40000 ALTER TABLE `ncrm_projecttasktype_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_projecttype
CREATE TABLE IF NOT EXISTS `ncrm_projecttype` (
  `projecttypeid` int(11) NOT NULL AUTO_INCREMENT,
  `projecttype` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(11) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT '0',
  PRIMARY KEY (`projecttypeid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_projecttype: ~3 rows (approximately)
/*!40000 ALTER TABLE `ncrm_projecttype` DISABLE KEYS */;
INSERT INTO `ncrm_projecttype` (`projecttypeid`, `projecttype`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(2, 'administrative', 1, 270, 2),
	(3, 'operative', 1, 271, 3),
	(4, 'other', 1, 272, 4);
/*!40000 ALTER TABLE `ncrm_projecttype` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_projecttype_seq
CREATE TABLE IF NOT EXISTS `ncrm_projecttype_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_projecttype_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_projecttype_seq` DISABLE KEYS */;
INSERT INTO `ncrm_projecttype_seq` (`id`) VALUES
	(4);
/*!40000 ALTER TABLE `ncrm_projecttype_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_purchaseorder
CREATE TABLE IF NOT EXISTS `ncrm_purchaseorder` (
  `purchaseorderid` int(19) NOT NULL DEFAULT '0',
  `subject` varchar(100) DEFAULT NULL,
  `quoteid` int(19) DEFAULT NULL,
  `vendorid` int(19) DEFAULT NULL,
  `requisition_no` varchar(100) DEFAULT NULL,
  `purchaseorder_no` varchar(100) DEFAULT NULL,
  `tracking_no` varchar(100) DEFAULT NULL,
  `contactid` int(19) DEFAULT NULL,
  `duedate` date DEFAULT NULL,
  `carrier` varchar(200) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `adjustment` decimal(25,8) DEFAULT NULL,
  `salescommission` decimal(25,3) DEFAULT NULL,
  `exciseduty` decimal(25,3) DEFAULT NULL,
  `total` decimal(25,8) DEFAULT NULL,
  `subtotal` decimal(25,8) DEFAULT NULL,
  `taxtype` varchar(25) DEFAULT NULL,
  `discount_percent` decimal(25,3) DEFAULT NULL,
  `discount_amount` decimal(25,8) DEFAULT NULL,
  `s_h_amount` decimal(25,8) DEFAULT NULL,
  `terms_conditions` text,
  `postatus` varchar(200) DEFAULT NULL,
  `currency_id` int(19) NOT NULL DEFAULT '1',
  `conversion_rate` decimal(10,3) NOT NULL DEFAULT '1.000',
  `pre_tax_total` decimal(25,8) DEFAULT NULL,
  `paid` decimal(25,8) DEFAULT NULL,
  `balance` decimal(25,8) DEFAULT NULL,
  `s_h_percent` int(11) DEFAULT NULL,
  PRIMARY KEY (`purchaseorderid`),
  KEY `purchaseorder_vendorid_idx` (`vendorid`),
  KEY `purchaseorder_quoteid_idx` (`quoteid`),
  KEY `purchaseorder_contactid_idx` (`contactid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_purchaseorder: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_purchaseorder` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_purchaseorder` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_purchaseordercf
CREATE TABLE IF NOT EXISTS `ncrm_purchaseordercf` (
  `purchaseorderid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`purchaseorderid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_purchaseordercf: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_purchaseordercf` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_purchaseordercf` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_quotes
CREATE TABLE IF NOT EXISTS `ncrm_quotes` (
  `quoteid` int(19) NOT NULL DEFAULT '0',
  `subject` varchar(100) DEFAULT NULL,
  `potentialid` int(19) DEFAULT NULL,
  `quotestage` varchar(200) DEFAULT NULL,
  `validtill` date DEFAULT NULL,
  `contactid` int(19) DEFAULT NULL,
  `quote_no` varchar(100) DEFAULT NULL,
  `subtotal` decimal(25,8) DEFAULT NULL,
  `carrier` varchar(200) DEFAULT NULL,
  `shipping` varchar(100) DEFAULT NULL,
  `inventorymanager` int(19) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `adjustment` decimal(25,8) DEFAULT NULL,
  `total` decimal(25,8) DEFAULT NULL,
  `taxtype` varchar(25) DEFAULT NULL,
  `discount_percent` decimal(25,3) DEFAULT NULL,
  `discount_amount` decimal(25,8) DEFAULT NULL,
  `s_h_amount` decimal(25,8) DEFAULT NULL,
  `accountid` int(19) DEFAULT NULL,
  `terms_conditions` text,
  `currency_id` int(19) NOT NULL DEFAULT '1',
  `conversion_rate` decimal(10,3) NOT NULL DEFAULT '1.000',
  `pre_tax_total` decimal(25,8) DEFAULT NULL,
  `s_h_percent` int(11) DEFAULT NULL,
  PRIMARY KEY (`quoteid`),
  KEY `quote_quotestage_idx` (`quotestage`),
  KEY `quotes_potentialid_idx` (`potentialid`),
  KEY `quotes_contactid_idx` (`contactid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_quotes: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_quotes` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_quotes` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_quotesbillads
CREATE TABLE IF NOT EXISTS `ncrm_quotesbillads` (
  `quotebilladdressid` int(19) NOT NULL DEFAULT '0',
  `bill_city` varchar(30) DEFAULT NULL,
  `bill_code` varchar(30) DEFAULT NULL,
  `bill_country` varchar(30) DEFAULT NULL,
  `bill_state` varchar(30) DEFAULT NULL,
  `bill_street` varchar(250) DEFAULT NULL,
  `bill_pobox` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`quotebilladdressid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_quotesbillads: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_quotesbillads` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_quotesbillads` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_quotescf
CREATE TABLE IF NOT EXISTS `ncrm_quotescf` (
  `quoteid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`quoteid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_quotescf: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_quotescf` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_quotescf` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_quotesshipads
CREATE TABLE IF NOT EXISTS `ncrm_quotesshipads` (
  `quoteshipaddressid` int(19) NOT NULL DEFAULT '0',
  `ship_city` varchar(30) DEFAULT NULL,
  `ship_code` varchar(30) DEFAULT NULL,
  `ship_country` varchar(30) DEFAULT NULL,
  `ship_state` varchar(30) DEFAULT NULL,
  `ship_street` varchar(250) DEFAULT NULL,
  `ship_pobox` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`quoteshipaddressid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_quotesshipads: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_quotesshipads` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_quotesshipads` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_quotestage
CREATE TABLE IF NOT EXISTS `ncrm_quotestage` (
  `quotestageid` int(19) NOT NULL AUTO_INCREMENT,
  `quotestage` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  PRIMARY KEY (`quotestageid`),
  UNIQUE KEY `quotestage_quotestage_idx` (`quotestage`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_quotestage: ~5 rows (approximately)
/*!40000 ALTER TABLE `ncrm_quotestage` DISABLE KEYS */;
INSERT INTO `ncrm_quotestage` (`quotestageid`, `quotestage`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(1, 'Created', 0, 139, 0),
	(2, 'Delivered', 0, 140, 1),
	(3, 'Reviewed', 0, 141, 2),
	(4, 'Accepted', 0, 142, 3),
	(5, 'Rejected', 0, 143, 4);
/*!40000 ALTER TABLE `ncrm_quotestage` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_quotestagehistory
CREATE TABLE IF NOT EXISTS `ncrm_quotestagehistory` (
  `historyid` int(19) NOT NULL AUTO_INCREMENT,
  `quoteid` int(19) NOT NULL,
  `accountname` varchar(100) DEFAULT NULL,
  `total` decimal(10,0) DEFAULT NULL,
  `quotestage` varchar(200) DEFAULT NULL,
  `lastmodified` datetime DEFAULT NULL,
  PRIMARY KEY (`historyid`),
  KEY `quotestagehistory_quoteid_idx` (`quoteid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_quotestagehistory: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_quotestagehistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_quotestagehistory` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_quotestage_seq
CREATE TABLE IF NOT EXISTS `ncrm_quotestage_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_quotestage_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_quotestage_seq` DISABLE KEYS */;
INSERT INTO `ncrm_quotestage_seq` (`id`) VALUES
	(5);
/*!40000 ALTER TABLE `ncrm_quotestage_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_rating
CREATE TABLE IF NOT EXISTS `ncrm_rating` (
  `rating_id` int(19) NOT NULL AUTO_INCREMENT,
  `rating` varchar(200) DEFAULT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  PRIMARY KEY (`rating_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_rating: ~5 rows (approximately)
/*!40000 ALTER TABLE `ncrm_rating` DISABLE KEYS */;
INSERT INTO `ncrm_rating` (`rating_id`, `rating`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(2, 'Acquired', 1, 145, 1),
	(3, 'Active', 1, 146, 2),
	(4, 'Market Failed', 1, 147, 3),
	(5, 'Project Cancelled', 1, 148, 4),
	(6, 'Shutdown', 1, 149, 5);
/*!40000 ALTER TABLE `ncrm_rating` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_rating_seq
CREATE TABLE IF NOT EXISTS `ncrm_rating_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_rating_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_rating_seq` DISABLE KEYS */;
INSERT INTO `ncrm_rating_seq` (`id`) VALUES
	(6);
/*!40000 ALTER TABLE `ncrm_rating_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_recurringevents
CREATE TABLE IF NOT EXISTS `ncrm_recurringevents` (
  `recurringid` int(19) NOT NULL AUTO_INCREMENT,
  `activityid` int(19) NOT NULL,
  `recurringdate` date DEFAULT NULL,
  `recurringtype` varchar(30) DEFAULT NULL,
  `recurringfreq` int(19) DEFAULT NULL,
  `recurringinfo` varchar(50) DEFAULT NULL,
  `recurringenddate` date DEFAULT NULL,
  PRIMARY KEY (`recurringid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_recurringevents: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_recurringevents` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_recurringevents` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_recurringtype
CREATE TABLE IF NOT EXISTS `ncrm_recurringtype` (
  `recurringeventid` int(19) NOT NULL AUTO_INCREMENT,
  `recurringtype` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`recurringeventid`),
  UNIQUE KEY `recurringtype_status_idx` (`recurringtype`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_recurringtype: ~4 rows (approximately)
/*!40000 ALTER TABLE `ncrm_recurringtype` DISABLE KEYS */;
INSERT INTO `ncrm_recurringtype` (`recurringeventid`, `recurringtype`, `sortorderid`, `presence`) VALUES
	(2, 'Daily', 1, 1),
	(3, 'Weekly', 2, 1),
	(4, 'Monthly', 3, 1),
	(5, 'Yearly', 4, 1);
/*!40000 ALTER TABLE `ncrm_recurringtype` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_recurringtype_seq
CREATE TABLE IF NOT EXISTS `ncrm_recurringtype_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_recurringtype_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_recurringtype_seq` DISABLE KEYS */;
INSERT INTO `ncrm_recurringtype_seq` (`id`) VALUES
	(5);
/*!40000 ALTER TABLE `ncrm_recurringtype_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_recurring_frequency
CREATE TABLE IF NOT EXISTS `ncrm_recurring_frequency` (
  `recurring_frequency_id` int(11) DEFAULT NULL,
  `recurring_frequency` varchar(200) DEFAULT NULL,
  `sortorderid` int(11) DEFAULT NULL,
  `presence` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_recurring_frequency: ~5 rows (approximately)
/*!40000 ALTER TABLE `ncrm_recurring_frequency` DISABLE KEYS */;
INSERT INTO `ncrm_recurring_frequency` (`recurring_frequency_id`, `recurring_frequency`, `sortorderid`, `presence`) VALUES
	(2, 'Daily', 1, 1),
	(3, 'Weekly', 2, 1),
	(4, 'Monthly', 3, 1),
	(5, 'Quarterly', 4, 1),
	(6, 'Yearly', 5, 1);
/*!40000 ALTER TABLE `ncrm_recurring_frequency` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_recurring_frequency_seq
CREATE TABLE IF NOT EXISTS `ncrm_recurring_frequency_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_recurring_frequency_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_recurring_frequency_seq` DISABLE KEYS */;
INSERT INTO `ncrm_recurring_frequency_seq` (`id`) VALUES
	(6);
/*!40000 ALTER TABLE `ncrm_recurring_frequency_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_relatedlists
CREATE TABLE IF NOT EXISTS `ncrm_relatedlists` (
  `relation_id` int(19) NOT NULL,
  `tabid` int(10) DEFAULT NULL,
  `related_tabid` int(10) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `sequence` int(10) DEFAULT NULL,
  `label` varchar(100) DEFAULT NULL,
  `presence` int(10) NOT NULL DEFAULT '0',
  `actions` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`relation_id`),
  KEY `relatedlists_relation_id_idx` (`relation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_relatedlists: ~130 rows (approximately)
/*!40000 ALTER TABLE `ncrm_relatedlists` DISABLE KEYS */;
INSERT INTO `ncrm_relatedlists` (`relation_id`, `tabid`, `related_tabid`, `name`, `sequence`, `label`, `presence`, `actions`) VALUES
	(1, 6, 4, 'get_contacts', 1, 'Contacts', 0, 'add'),
	(2, 6, 2, 'get_opportunities', 2, 'Potentials', 0, 'add'),
	(3, 6, 20, 'get_quotes', 3, 'Quotes', 0, 'add'),
	(4, 6, 22, 'get_salesorder', 4, 'Sales Order', 0, 'add'),
	(5, 6, 23, 'get_invoices', 5, 'Invoice', 0, 'add'),
	(6, 6, 9, 'get_activities', 6, 'Activities', 0, 'add'),
	(7, 6, 10, 'get_emails', 7, 'Emails', 0, 'add'),
	(8, 6, 9, 'get_history', 8, 'Activity History', 0, 'add'),
	(9, 6, 8, 'get_attachments', 9, 'Documents', 0, 'add,select'),
	(10, 6, 13, 'get_tickets', 10, 'HelpDesk', 0, 'add'),
	(11, 6, 14, 'get_products', 11, 'Products', 0, 'select'),
	(12, 7, 9, 'get_activities', 1, 'Activities', 0, 'add'),
	(13, 7, 10, 'get_emails', 2, 'Emails', 0, 'add'),
	(14, 7, 9, 'get_history', 3, 'Activity History', 0, 'add'),
	(15, 7, 8, 'get_attachments', 4, 'Documents', 0, 'add,select'),
	(16, 7, 14, 'get_products', 5, 'Products', 0, 'select'),
	(17, 7, 26, 'get_campaigns', 6, 'Campaigns', 0, 'select'),
	(18, 4, 2, 'get_opportunities', 1, 'Potentials', 0, 'add'),
	(19, 4, 9, 'get_activities', 2, 'Activities', 0, 'add'),
	(20, 4, 10, 'get_emails', 3, 'Emails', 0, 'add'),
	(21, 4, 13, 'get_tickets', 4, 'HelpDesk', 0, 'add'),
	(22, 4, 20, 'get_quotes', 5, 'Quotes', 0, 'add'),
	(23, 4, 21, 'get_purchase_orders', 6, 'Purchase Order', 0, 'add'),
	(24, 4, 22, 'get_salesorder', 7, 'Sales Order', 0, 'add'),
	(25, 4, 14, 'get_products', 8, 'Products', 0, 'select'),
	(26, 4, 9, 'get_history', 9, 'Activity History', 0, 'add'),
	(27, 4, 8, 'get_attachments', 10, 'Documents', 0, 'add,select'),
	(28, 4, 26, 'get_campaigns', 11, 'Campaigns', 0, 'select'),
	(29, 4, 23, 'get_invoices', 12, 'Invoice', 0, 'add'),
	(30, 2, 9, 'get_activities', 1, 'Activities', 0, 'add'),
	(31, 2, 4, 'get_contacts', 2, 'Contacts', 0, 'select'),
	(32, 2, 14, 'get_products', 3, 'Products', 0, 'select'),
	(33, 2, 0, 'get_stage_history', 4, 'Sales Stage History', 0, ''),
	(34, 2, 8, 'get_attachments', 5, 'Documents', 0, 'add,select'),
	(35, 2, 20, 'get_Quotes', 6, 'Quotes', 0, 'add'),
	(36, 2, 22, 'get_salesorder', 7, 'Sales Order', 0, 'add'),
	(37, 2, 9, 'get_history', 8, 'Activity History', 0, ''),
	(38, 14, 13, 'get_tickets', 1, 'HelpDesk', 0, 'add'),
	(39, 14, 8, 'get_attachments', 3, 'Documents', 0, 'add,select'),
	(40, 14, 20, 'get_quotes', 4, 'Quotes', 0, 'add'),
	(41, 14, 21, 'get_purchase_orders', 5, 'Purchase Order', 0, 'add'),
	(42, 14, 22, 'get_salesorder', 6, 'Sales Order', 0, 'add'),
	(43, 14, 23, 'get_invoices', 7, 'Invoice', 0, 'add'),
	(44, 14, 19, 'get_product_pricebooks', 8, 'PriceBooks', 0, 'ADD,SELECT'),
	(45, 14, 7, 'get_leads', 9, 'Leads', 0, 'select'),
	(46, 14, 6, 'get_accounts', 10, 'Accounts', 0, 'select'),
	(47, 14, 4, 'get_contacts', 11, 'Contacts', 0, 'select'),
	(48, 14, 2, 'get_opportunities', 12, 'Potentials', 0, 'select'),
	(49, 14, 14, 'get_products', 13, 'Product Bundles', 0, 'add,select'),
	(50, 14, 14, 'get_parent_products', 14, 'Parent Product', 0, ''),
	(51, 10, 4, 'get_contacts', 1, 'Contacts', 0, 'select,bulkmail'),
	(52, 10, 0, 'get_users', 2, 'Users', 0, ''),
	(53, 10, 8, 'get_attachments', 3, 'Documents', 0, 'add,select'),
	(54, 13, 9, 'get_activities', 1, 'Activities', 0, 'add'),
	(55, 13, 8, 'get_attachments', 2, 'Documents', 0, 'add,select'),
	(56, 13, 0, 'get_ticket_history', 3, 'Ticket History', 0, ''),
	(57, 13, 9, 'get_history', 4, 'Activity History', 0, 'add'),
	(58, 19, 14, 'get_pricebook_products', 2, 'Products', 0, 'select'),
	(59, 18, 14, 'get_products', 1, 'Products', 0, 'add,select'),
	(60, 18, 21, 'get_purchase_orders', 2, 'Purchase Order', 0, 'add'),
	(61, 18, 4, 'get_contacts', 3, 'Contacts', 0, 'select'),
	(62, 18, 10, 'get_emails', 4, 'Emails', 0, 'add'),
	(63, 20, 22, 'get_salesorder', 1, 'Sales Order', 0, ''),
	(64, 20, 9, 'get_activities', 2, 'Activities', 0, 'add'),
	(65, 20, 8, 'get_attachments', 3, 'Documents', 0, 'add,select'),
	(66, 20, 9, 'get_history', 4, 'Activity History', 0, ''),
	(67, 20, 0, 'get_quotestagehistory', 5, 'Quote Stage History', 0, ''),
	(68, 21, 9, 'get_activities', 1, 'Activities', 0, 'add'),
	(69, 21, 8, 'get_attachments', 2, 'Documents', 0, 'add,select'),
	(70, 21, 9, 'get_history', 3, 'Activity History', 0, ''),
	(71, 21, 0, 'get_postatushistory', 4, 'PurchaseOrder Status History', 0, ''),
	(72, 22, 9, 'get_activities', 1, 'Activities', 0, 'add'),
	(73, 22, 8, 'get_attachments', 2, 'Documents', 0, 'add,select'),
	(74, 22, 23, 'get_invoices', 3, 'Invoice', 0, ''),
	(75, 22, 9, 'get_history', 4, 'Activity History', 0, ''),
	(76, 22, 0, 'get_sostatushistory', 5, 'SalesOrder Status History', 0, ''),
	(77, 23, 9, 'get_activities', 1, 'Activities', 0, 'add'),
	(78, 23, 8, 'get_attachments', 2, 'Documents', 0, 'add,select'),
	(79, 23, 9, 'get_history', 3, 'Activity History', 0, ''),
	(80, 23, 0, 'get_invoicestatushistory', 4, 'Invoice Status History', 0, ''),
	(81, 9, 0, 'get_users', 1, 'Users', 0, ''),
	(82, 9, 4, 'get_contacts', 2, 'Contacts', 0, ''),
	(83, 26, 4, 'get_contacts', 1, 'Contacts', 0, 'add,select'),
	(84, 26, 7, 'get_leads', 2, 'Leads', 0, 'add,select'),
	(85, 26, 2, 'get_opportunities', 3, 'Potentials', 0, 'add'),
	(86, 26, 9, 'get_activities', 4, 'Activities', 0, 'add'),
	(87, 6, 26, 'get_campaigns', 13, 'Campaigns', 0, 'select'),
	(88, 26, 6, 'get_accounts', 5, 'Accounts', 0, 'add,select'),
	(89, 15, 8, 'get_attachments', 1, 'Documents', 0, 'add,select'),
	(93, 35, 13, 'get_related_list', 1, 'Service Requests', 0, 'ADD,SELECT'),
	(94, 35, 8, 'get_attachments', 2, 'Documents', 0, 'ADD,SELECT'),
	(95, 6, 35, 'get_dependents_list', 15, 'Service Contracts', 0, 'ADD'),
	(96, 4, 35, 'get_dependents_list', 14, 'Service Contracts', 0, 'ADD'),
	(97, 13, 35, 'get_related_list', 5, 'Service Contracts', 0, 'ADD,SELECT'),
	(98, 36, 13, 'get_related_list', 1, 'HelpDesk', 0, 'ADD,SELECT'),
	(99, 36, 20, 'get_quotes', 2, 'Quotes', 0, 'ADD'),
	(100, 36, 21, 'get_purchase_orders', 3, 'Purchase Order', 0, 'ADD'),
	(101, 36, 22, 'get_salesorder', 4, 'Sales Order', 0, 'ADD'),
	(102, 36, 23, 'get_invoices', 5, 'Invoice', 0, 'ADD'),
	(103, 36, 19, 'get_service_pricebooks', 6, 'PriceBooks', 0, 'ADD'),
	(104, 36, 7, 'get_related_list', 7, 'Leads', 0, 'SELECT'),
	(105, 36, 6, 'get_related_list', 8, 'Accounts', 0, 'SELECT'),
	(106, 36, 4, 'get_related_list', 9, 'Contacts', 0, 'SELECT'),
	(107, 36, 2, 'get_related_list', 10, 'Potentials', 0, 'SELECT'),
	(108, 36, 8, 'get_attachments', 11, 'Documents', 0, 'ADD,SELECT'),
	(109, 13, 36, 'get_related_list', 6, 'Services', 0, 'SELECT'),
	(110, 7, 36, 'get_related_list', 8, 'Services', 0, 'SELECT'),
	(111, 6, 36, 'get_related_list', 16, 'Services', 0, 'SELECT'),
	(112, 4, 36, 'get_related_list', 15, 'Services', 0, 'SELECT'),
	(113, 2, 36, 'get_related_list', 9, 'Services', 0, 'SELECT'),
	(114, 19, 36, 'get_pricebook_services', 3, 'Services', 0, 'SELECT'),
	(115, 38, 13, 'get_related_list', 1, 'HelpDesk', 0, 'ADD,SELECT'),
	(116, 38, 8, 'get_attachments', 2, 'Documents', 0, 'ADD,SELECT'),
	(117, 6, 38, 'get_dependents_list', 17, 'Assets', 0, 'ADD'),
	(118, 14, 38, 'get_dependents_list', 15, 'Assets', 0, 'ADD'),
	(119, 23, 38, 'get_dependents_list', 5, 'Assets', 0, 'ADD'),
	(126, 6, 45, 'get_dependents_list', 18, 'Projects', 0, 'add'),
	(127, 4, 45, 'get_dependents_list', 16, 'Projects', 0, 'add'),
	(128, 13, 45, 'get_related_list', 7, 'Projects', 0, 'SELECT'),
	(129, 47, 6, 'get_related_list', 1, 'Accounts', 0, ' '),
	(130, 47, 4, 'get_related_list', 2, 'Contacts', 0, ' '),
	(131, 47, 7, 'get_related_list', 3, 'Leads', 0, ' '),
	(132, 44, 8, 'get_attachments', 1, 'Documents', 0, 'ADD,SELECT'),
	(133, 45, 44, 'get_dependents_list', 1, 'Project Tasks', 0, 'ADD'),
	(134, 45, 43, 'get_dependents_list', 2, 'Project Milestones', 0, 'ADD'),
	(135, 45, 8, 'get_attachments', 3, 'Documents', 0, 'ADD,SELECT'),
	(136, 45, 13, 'get_related_list', 4, 'HelpDesk', 0, 'ADD,SELECT'),
	(137, 45, 0, 'get_gantt_chart', 5, 'Charts', 0, ''),
	(138, 4, 38, 'get_dependents_list', 17, 'Assets', 0, 'ADD'),
	(139, 4, 18, 'get_vendors', 18, 'Vendors', 0, 'SELECT');
/*!40000 ALTER TABLE `ncrm_relatedlists` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_relatedlists_rb
CREATE TABLE IF NOT EXISTS `ncrm_relatedlists_rb` (
  `entityid` int(19) DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  `rel_table` varchar(200) DEFAULT NULL,
  `rel_column` varchar(200) DEFAULT NULL,
  `ref_column` varchar(200) DEFAULT NULL,
  `related_crm_ids` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_relatedlists_rb: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_relatedlists_rb` DISABLE KEYS */;
INSERT INTO `ncrm_relatedlists_rb` (`entityid`, `action`, `rel_table`, `rel_column`, `ref_column`, `related_crm_ids`) VALUES
	(28, 'update', 'ncrm_crmentity', 'deleted', 'crmid', ''),
	(24, 'update', 'ncrm_crmentity', 'deleted', 'crmid', ''),
	(13, 'update', 'ncrm_crmentity', 'deleted', 'crmid', ''),
	(13, 'update', 'ncrm_modcomments', 'related_to', 'modcommentsid', '15,16,17,18');
/*!40000 ALTER TABLE `ncrm_relatedlists_rb` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_relatedlists_seq
CREATE TABLE IF NOT EXISTS `ncrm_relatedlists_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_relatedlists_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_relatedlists_seq` DISABLE KEYS */;
INSERT INTO `ncrm_relatedlists_seq` (`id`) VALUES
	(139);
/*!40000 ALTER TABLE `ncrm_relatedlists_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_relcriteria
CREATE TABLE IF NOT EXISTS `ncrm_relcriteria` (
  `queryid` int(19) NOT NULL,
  `columnindex` int(11) NOT NULL,
  `columnname` varchar(250) DEFAULT '',
  `comparator` varchar(20) DEFAULT NULL,
  `value` varchar(512) DEFAULT NULL,
  `groupid` int(11) DEFAULT '1',
  `column_condition` varchar(256) DEFAULT 'and',
  PRIMARY KEY (`queryid`,`columnindex`),
  KEY `relcriteria_queryid_idx` (`queryid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_relcriteria: ~11 rows (approximately)
/*!40000 ALTER TABLE `ncrm_relcriteria` DISABLE KEYS */;
INSERT INTO `ncrm_relcriteria` (`queryid`, `columnindex`, `columnname`, `comparator`, `value`, `groupid`, `column_condition`) VALUES
	(1, 0, 'ncrm_contactdetails:accountid:Contacts_Account_Name:account_id:V', 'n', '', 1, 'and'),
	(2, 0, 'ncrm_contactdetails:accountid:Contacts_Account_Name:account_id:V', 'e', '', 1, 'and'),
	(3, 0, 'ncrm_potential:potentialname:Potentials_Potential_Name:potentialname:V', 'n', '', 1, 'and'),
	(7, 0, 'ncrm_potential:sales_stage:Potentials_Sales_Stage:sales_stage:V', 'e', 'Closed Won', 1, 'and'),
	(12, 0, 'ncrm_troubletickets:status:HelpDesk_Status:ticketstatus:V', 'n', 'Closed', 1, 'and'),
	(15, 0, 'ncrm_quotes:quotestage:Quotes_Quote_Stage:quotestage:V', 'n', 'Accepted', 1, 'and'),
	(15, 1, 'ncrm_quotes:quotestage:Quotes_Quote_Stage:quotestage:V', 'n', 'Rejected', 1, 'and'),
	(22, 0, 'ncrm_email_track:access_count:Emails_Access_Count:access_count:V', 'n', '', 1, 'and'),
	(23, 0, 'ncrm_email_track:access_count:Emails_Access_Count:access_count:V', 'n', '', 1, 'and'),
	(24, 0, 'ncrm_email_track:access_count:Emails_Access_Count:access_count:V', 'n', '', 1, 'and'),
	(25, 0, 'ncrm_email_track:access_count:Emails_Access_Count:access_count:V', 'n', '', 1, 'and');
/*!40000 ALTER TABLE `ncrm_relcriteria` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_relcriteria_grouping
CREATE TABLE IF NOT EXISTS `ncrm_relcriteria_grouping` (
  `groupid` int(11) NOT NULL,
  `queryid` int(19) NOT NULL,
  `group_condition` varchar(256) DEFAULT NULL,
  `condition_expression` text,
  PRIMARY KEY (`groupid`,`queryid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_relcriteria_grouping: ~10 rows (approximately)
/*!40000 ALTER TABLE `ncrm_relcriteria_grouping` DISABLE KEYS */;
INSERT INTO `ncrm_relcriteria_grouping` (`groupid`, `queryid`, `group_condition`, `condition_expression`) VALUES
	(1, 1, '', '0'),
	(1, 2, '', '0'),
	(1, 3, '', '0'),
	(1, 7, '', '0'),
	(1, 12, '', '0'),
	(1, 15, '', '0 and 1'),
	(1, 22, '', '0'),
	(1, 23, '', '0'),
	(1, 24, '', '0'),
	(1, 25, '', '0');
/*!40000 ALTER TABLE `ncrm_relcriteria_grouping` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_reminder_interval
CREATE TABLE IF NOT EXISTS `ncrm_reminder_interval` (
  `reminder_intervalid` int(19) NOT NULL AUTO_INCREMENT,
  `reminder_interval` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL,
  `presence` int(1) NOT NULL,
  PRIMARY KEY (`reminder_intervalid`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_reminder_interval: ~7 rows (approximately)
/*!40000 ALTER TABLE `ncrm_reminder_interval` DISABLE KEYS */;
INSERT INTO `ncrm_reminder_interval` (`reminder_intervalid`, `reminder_interval`, `sortorderid`, `presence`) VALUES
	(2, '1 Minute', 1, 1),
	(3, '5 Minutes', 2, 1),
	(4, '15 Minutes', 3, 1),
	(5, '30 Minutes', 4, 1),
	(6, '45 Minutes', 5, 1),
	(7, '1 Hour', 6, 1),
	(8, '1 Day', 7, 1);
/*!40000 ALTER TABLE `ncrm_reminder_interval` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_reminder_interval_seq
CREATE TABLE IF NOT EXISTS `ncrm_reminder_interval_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_reminder_interval_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_reminder_interval_seq` DISABLE KEYS */;
INSERT INTO `ncrm_reminder_interval_seq` (`id`) VALUES
	(8);
/*!40000 ALTER TABLE `ncrm_reminder_interval_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_report
CREATE TABLE IF NOT EXISTS `ncrm_report` (
  `reportid` int(19) NOT NULL,
  `folderid` int(19) NOT NULL,
  `reportname` varchar(100) DEFAULT '',
  `description` varchar(250) DEFAULT '',
  `reporttype` varchar(50) DEFAULT '',
  `queryid` int(19) NOT NULL DEFAULT '0',
  `state` varchar(50) DEFAULT 'SAVED',
  `customizable` int(1) DEFAULT '1',
  `category` int(11) DEFAULT '1',
  `owner` int(11) DEFAULT '1',
  `sharingtype` varchar(200) DEFAULT 'Private',
  PRIMARY KEY (`reportid`),
  KEY `report_queryid_idx` (`queryid`),
  KEY `report_folderid_idx` (`folderid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_report: ~25 rows (approximately)
/*!40000 ALTER TABLE `ncrm_report` DISABLE KEYS */;
INSERT INTO `ncrm_report` (`reportid`, `folderid`, `reportname`, `description`, `reporttype`, `queryid`, `state`, `customizable`, `category`, `owner`, `sharingtype`) VALUES
	(1, 1, 'Contacts by Accounts', 'Contacts related to Accounts', 'tabular', 1, 'CUSTOM', 1, 1, 1, 'Public'),
	(2, 1, 'Contacts without Accounts', 'Contacts not related to Accounts', 'tabular', 2, 'CUSTOM', 1, 1, 1, 'Public'),
	(3, 1, 'Contacts by Potentials', 'Contacts related to Potentials', 'tabular', 3, 'CUSTOM', 1, 1, 1, 'Public'),
	(4, 2, 'Lead by Source', 'Lead by Source', 'summary', 4, 'CUSTOM', 1, 1, 1, 'Public'),
	(5, 2, 'Lead Status Report', 'Lead Status Report', 'summary', 5, 'CUSTOM', 1, 1, 1, 'Public'),
	(6, 3, 'Potential Pipeline', 'Potential Pipeline', 'summary', 6, 'CUSTOM', 1, 1, 1, 'Public'),
	(7, 3, 'Closed Potentials', 'Potential that have Won', 'tabular', 7, 'CUSTOM', 1, 1, 1, 'Public'),
	(8, 4, 'Last Month Activities', 'Last Month Activities', 'tabular', 8, 'CUSTOM', 1, 1, 1, 'Public'),
	(9, 4, 'This Month Activities', 'This Month Activities', 'tabular', 9, 'CUSTOM', 1, 1, 1, 'Public'),
	(10, 5, 'Tickets by Products', 'Tickets related to Products', 'tabular', 10, 'CUSTOM', 1, 1, 1, 'Public'),
	(11, 5, 'Tickets by Priority', 'Tickets by Priority', 'summary', 11, 'CUSTOM', 1, 1, 1, 'Public'),
	(12, 5, 'Open Tickets', 'Tickets that are Open', 'tabular', 12, 'CUSTOM', 1, 1, 1, 'Public'),
	(13, 6, 'Product Details', 'Product Detailed Report', 'tabular', 13, 'CUSTOM', 1, 1, 1, 'Public'),
	(14, 6, 'Products by Contacts', 'Products related to Contacts', 'tabular', 14, 'CUSTOM', 1, 1, 1, 'Public'),
	(15, 7, 'Open Quotes', 'Quotes that are Open', 'tabular', 15, 'CUSTOM', 1, 1, 1, 'Public'),
	(16, 7, 'Quotes Detailed Report', 'Quotes Detailed Report', 'tabular', 16, 'CUSTOM', 1, 1, 1, 'Public'),
	(17, 8, 'PurchaseOrder by Contacts', 'PurchaseOrder related to Contacts', 'tabular', 17, 'CUSTOM', 1, 1, 1, 'Public'),
	(18, 8, 'PurchaseOrder Detailed Report', 'PurchaseOrder Detailed Report', 'tabular', 18, 'CUSTOM', 1, 1, 1, 'Public'),
	(19, 9, 'Invoice Detailed Report', 'Invoice Detailed Report', 'tabular', 19, 'CUSTOM', 1, 1, 1, 'Public'),
	(20, 10, 'SalesOrder Detailed Report', 'SalesOrder Detailed Report', 'tabular', 20, 'CUSTOM', 1, 1, 1, 'Public'),
	(21, 11, 'Campaign Expectations and Actuals', 'Campaign Expectations and Actuals', 'tabular', 21, 'CUSTOM', 1, 1, 1, 'Public'),
	(22, 12, 'Contacts Email Report', 'Emails sent to Contacts', 'tabular', 22, 'CUSTOM', 1, 1, 1, 'Public'),
	(23, 12, 'Accounts Email Report', 'Emails sent to Organizations', 'tabular', 23, 'CUSTOM', 1, 1, 1, 'Public'),
	(24, 12, 'Leads Email Report', 'Emails sent to Leads', 'tabular', 24, 'CUSTOM', 1, 1, 1, 'Public'),
	(25, 12, 'Vendors Email Report', 'Emails sent to Vendors', 'tabular', 25, 'CUSTOM', 1, 1, 1, 'Public');
/*!40000 ALTER TABLE `ncrm_report` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_reportdatefilter
CREATE TABLE IF NOT EXISTS `ncrm_reportdatefilter` (
  `datefilterid` int(19) NOT NULL,
  `datecolumnname` varchar(250) DEFAULT '',
  `datefilter` varchar(250) DEFAULT '',
  `startdate` date DEFAULT NULL,
  `enddate` date DEFAULT NULL,
  PRIMARY KEY (`datefilterid`),
  KEY `reportdatefilter_datefilterid_idx` (`datefilterid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_reportdatefilter: ~2 rows (approximately)
/*!40000 ALTER TABLE `ncrm_reportdatefilter` DISABLE KEYS */;
INSERT INTO `ncrm_reportdatefilter` (`datefilterid`, `datecolumnname`, `datefilter`, `startdate`, `enddate`) VALUES
	(8, 'ncrm_crmentity:modifiedtime:modifiedtime:Calendar_Modified_Time', 'lastmonth', '2005-05-01', '2005-05-31'),
	(9, 'ncrm_crmentity:modifiedtime:modifiedtime:Calendar_Modified_Time', 'thismonth', '2005-06-01', '2005-06-30');
/*!40000 ALTER TABLE `ncrm_reportdatefilter` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_reportfilters
CREATE TABLE IF NOT EXISTS `ncrm_reportfilters` (
  `filterid` int(19) NOT NULL,
  `name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_reportfilters: ~3 rows (approximately)
/*!40000 ALTER TABLE `ncrm_reportfilters` DISABLE KEYS */;
INSERT INTO `ncrm_reportfilters` (`filterid`, `name`) VALUES
	(1, 'Private'),
	(2, 'Public'),
	(3, 'Shared');
/*!40000 ALTER TABLE `ncrm_reportfilters` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_reportfolder
CREATE TABLE IF NOT EXISTS `ncrm_reportfolder` (
  `folderid` int(19) NOT NULL AUTO_INCREMENT,
  `foldername` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(250) DEFAULT '',
  `state` varchar(50) DEFAULT 'SAVED',
  PRIMARY KEY (`folderid`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_reportfolder: ~12 rows (approximately)
/*!40000 ALTER TABLE `ncrm_reportfolder` DISABLE KEYS */;
INSERT INTO `ncrm_reportfolder` (`folderid`, `foldername`, `description`, `state`) VALUES
	(1, 'Account and Contact Reports', 'Account and Contact Reports', 'SAVED'),
	(2, 'Lead Reports', 'Lead Reports', 'SAVED'),
	(3, 'Potential Reports', 'Potential Reports', 'SAVED'),
	(4, 'Activity Reports', 'Activity Reports', 'SAVED'),
	(5, 'HelpDesk Reports', 'HelpDesk Reports', 'SAVED'),
	(6, 'Product Reports', 'Product Reports', 'SAVED'),
	(7, 'Quote Reports', 'Quote Reports', 'SAVED'),
	(8, 'PurchaseOrder Reports', 'PurchaseOrder Reports', 'SAVED'),
	(9, 'Invoice Reports', 'Invoice Reports', 'SAVED'),
	(10, 'SalesOrder Reports', 'SalesOrder Reports', 'SAVED'),
	(11, 'Campaign Reports', 'Campaign Reports', 'SAVED'),
	(12, 'Email Reports', 'Email Reports', 'SAVED');
/*!40000 ALTER TABLE `ncrm_reportfolder` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_reportgroupbycolumn
CREATE TABLE IF NOT EXISTS `ncrm_reportgroupbycolumn` (
  `reportid` int(19) DEFAULT NULL,
  `sortid` int(19) DEFAULT NULL,
  `sortcolname` varchar(250) DEFAULT NULL,
  `dategroupbycriteria` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_reportgroupbycolumn: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_reportgroupbycolumn` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_reportgroupbycolumn` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_reportmodules
CREATE TABLE IF NOT EXISTS `ncrm_reportmodules` (
  `reportmodulesid` int(19) NOT NULL,
  `primarymodule` varchar(50) NOT NULL DEFAULT '',
  `secondarymodules` varchar(250) DEFAULT '',
  PRIMARY KEY (`reportmodulesid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_reportmodules: ~25 rows (approximately)
/*!40000 ALTER TABLE `ncrm_reportmodules` DISABLE KEYS */;
INSERT INTO `ncrm_reportmodules` (`reportmodulesid`, `primarymodule`, `secondarymodules`) VALUES
	(1, 'Contacts', 'Accounts'),
	(2, 'Contacts', 'Accounts'),
	(3, 'Contacts', 'Potentials'),
	(4, 'Leads', ''),
	(5, 'Leads', ''),
	(6, 'Potentials', ''),
	(7, 'Potentials', ''),
	(8, 'Calendar', ''),
	(9, 'Calendar', ''),
	(10, 'HelpDesk', 'Products'),
	(11, 'HelpDesk', ''),
	(12, 'HelpDesk', ''),
	(13, 'Products', ''),
	(14, 'Products', 'Contacts'),
	(15, 'Quotes', ''),
	(16, 'Quotes', ''),
	(17, 'PurchaseOrder', 'Contacts'),
	(18, 'PurchaseOrder', ''),
	(19, 'Invoice', ''),
	(20, 'SalesOrder', ''),
	(21, 'Campaigns', ''),
	(22, 'Contacts', 'Emails'),
	(23, 'Accounts', 'Emails'),
	(24, 'Leads', 'Emails'),
	(25, 'Vendors', 'Emails');
/*!40000 ALTER TABLE `ncrm_reportmodules` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_reportsharing
CREATE TABLE IF NOT EXISTS `ncrm_reportsharing` (
  `reportid` int(19) NOT NULL,
  `shareid` int(19) NOT NULL,
  `setype` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_reportsharing: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_reportsharing` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_reportsharing` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_reportsortcol
CREATE TABLE IF NOT EXISTS `ncrm_reportsortcol` (
  `sortcolid` int(19) NOT NULL,
  `reportid` int(19) NOT NULL,
  `columnname` varchar(250) DEFAULT '',
  `sortorder` varchar(250) DEFAULT 'Asc',
  PRIMARY KEY (`sortcolid`,`reportid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_reportsortcol: ~4 rows (approximately)
/*!40000 ALTER TABLE `ncrm_reportsortcol` DISABLE KEYS */;
INSERT INTO `ncrm_reportsortcol` (`sortcolid`, `reportid`, `columnname`, `sortorder`) VALUES
	(1, 4, 'ncrm_leaddetails:leadsource:Leads_Lead_Source:leadsource:V', 'Ascending'),
	(1, 5, 'ncrm_leaddetails:leadstatus:Leads_Lead_Status:leadstatus:V', 'Ascending'),
	(1, 6, 'ncrm_potential:sales_stage:Potentials_Sales_Stage:sales_stage:V', 'Ascending'),
	(1, 11, 'ncrm_troubletickets:priority:HelpDesk_Priority:ticketpriorities:V', 'Ascending');
/*!40000 ALTER TABLE `ncrm_reportsortcol` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_reportsummary
CREATE TABLE IF NOT EXISTS `ncrm_reportsummary` (
  `reportsummaryid` int(19) NOT NULL,
  `summarytype` int(19) NOT NULL,
  `columnname` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`reportsummaryid`,`summarytype`,`columnname`),
  KEY `reportsummary_reportsummaryid_idx` (`reportsummaryid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_reportsummary: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_reportsummary` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_reportsummary` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_reporttype
CREATE TABLE IF NOT EXISTS `ncrm_reporttype` (
  `reportid` int(10) NOT NULL,
  `data` text,
  PRIMARY KEY (`reportid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_reporttype: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_reporttype` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_reporttype` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_role
CREATE TABLE IF NOT EXISTS `ncrm_role` (
  `roleid` varchar(255) NOT NULL,
  `rolename` varchar(200) DEFAULT NULL,
  `parentrole` varchar(255) DEFAULT NULL,
  `depth` int(19) DEFAULT NULL,
  `allowassignedrecordsto` int(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`roleid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_role: ~5 rows (approximately)
/*!40000 ALTER TABLE `ncrm_role` DISABLE KEYS */;
INSERT INTO `ncrm_role` (`roleid`, `rolename`, `parentrole`, `depth`, `allowassignedrecordsto`) VALUES
	('H1', 'Organization', 'H1', 0, 1),
	('H2', 'CEO', 'H1::H2', 1, 1),
	('H3', 'Vice President', 'H1::H2::H3', 2, 1),
	('H4', 'Sales Manager', 'H1::H2::H3::H4', 3, 1),
	('H5', 'Sales Person', 'H1::H2::H3::H4::H5', 4, 1),
	('H6', 'Normal User', 'H1::H6', 1, 1);
/*!40000 ALTER TABLE `ncrm_role` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_role2picklist
CREATE TABLE IF NOT EXISTS `ncrm_role2picklist` (
  `roleid` varchar(255) NOT NULL,
  `picklistvalueid` int(11) NOT NULL,
  `picklistid` int(11) NOT NULL,
  `sortid` int(11) DEFAULT NULL,
  PRIMARY KEY (`roleid`,`picklistvalueid`,`picklistid`),
  KEY `role2picklist_roleid_picklistid_idx` (`roleid`,`picklistid`,`picklistvalueid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_role2picklist: ~1,500 rows (approximately)
/*!40000 ALTER TABLE `ncrm_role2picklist` DISABLE KEYS */;
INSERT INTO `ncrm_role2picklist` (`roleid`, `picklistvalueid`, `picklistid`, `sortid`) VALUES
	('H1', 1, 1, 0),
	('H1', 2, 1, 1),
	('H1', 3, 1, 2),
	('H1', 4, 1, 3),
	('H1', 5, 1, 4),
	('H1', 6, 1, 5),
	('H1', 7, 1, 6),
	('H1', 8, 1, 7),
	('H1', 9, 1, 8),
	('H1', 10, 1, 9),
	('H1', 11, 1, 10),
	('H1', 12, 2, 0),
	('H1', 13, 2, 1),
	('H1', 14, 3, 0),
	('H1', 15, 3, 1),
	('H1', 16, 3, 2),
	('H1', 17, 3, 3),
	('H1', 18, 3, 4),
	('H1', 19, 3, 5),
	('H1', 20, 4, 0),
	('H1', 21, 4, 1),
	('H1', 22, 4, 2),
	('H1', 23, 4, 3),
	('H1', 24, 4, 4),
	('H1', 25, 4, 5),
	('H1', 26, 4, 6),
	('H1', 27, 4, 7),
	('H1', 28, 4, 8),
	('H1', 29, 4, 9),
	('H1', 30, 4, 10),
	('H1', 31, 4, 11),
	('H1', 32, 4, 12),
	('H1', 33, 5, 0),
	('H1', 34, 5, 1),
	('H1', 35, 5, 2),
	('H1', 36, 5, 3),
	('H1', 37, 5, 4),
	('H1', 38, 6, 0),
	('H1', 39, 6, 1),
	('H1', 40, 6, 2),
	('H1', 41, 7, 0),
	('H1', 42, 7, 1),
	('H1', 43, 7, 2),
	('H1', 44, 7, 3),
	('H1', 45, 7, 4),
	('H1', 46, 8, 0),
	('H1', 47, 9, 0),
	('H1', 48, 9, 1),
	('H1', 49, 9, 2),
	('H1', 50, 9, 3),
	('H1', 51, 10, 0),
	('H1', 52, 10, 1),
	('H1', 53, 10, 2),
	('H1', 54, 10, 3),
	('H1', 55, 10, 4),
	('H1', 56, 10, 5),
	('H1', 57, 10, 6),
	('H1', 58, 10, 7),
	('H1', 59, 10, 8),
	('H1', 60, 11, 0),
	('H1', 61, 11, 1),
	('H1', 62, 11, 2),
	('H1', 63, 11, 3),
	('H1', 64, 11, 4),
	('H1', 65, 11, 5),
	('H1', 66, 11, 6),
	('H1', 67, 11, 7),
	('H1', 68, 11, 8),
	('H1', 69, 11, 9),
	('H1', 70, 11, 10),
	('H1', 71, 11, 11),
	('H1', 72, 11, 12),
	('H1', 73, 11, 13),
	('H1', 74, 11, 14),
	('H1', 75, 11, 15),
	('H1', 76, 11, 16),
	('H1', 77, 11, 17),
	('H1', 78, 11, 18),
	('H1', 79, 11, 19),
	('H1', 80, 11, 20),
	('H1', 81, 11, 21),
	('H1', 82, 11, 22),
	('H1', 83, 11, 23),
	('H1', 84, 11, 24),
	('H1', 85, 11, 25),
	('H1', 86, 11, 26),
	('H1', 87, 11, 27),
	('H1', 88, 11, 28),
	('H1', 89, 11, 29),
	('H1', 90, 11, 30),
	('H1', 91, 11, 31),
	('H1', 92, 12, 0),
	('H1', 93, 12, 1),
	('H1', 94, 12, 2),
	('H1', 95, 12, 3),
	('H1', 96, 12, 4),
	('H1', 97, 12, 5),
	('H1', 98, 13, 0),
	('H1', 99, 13, 1),
	('H1', 100, 13, 2),
	('H1', 101, 13, 3),
	('H1', 102, 13, 4),
	('H1', 103, 13, 5),
	('H1', 104, 13, 6),
	('H1', 105, 13, 7),
	('H1', 106, 13, 8),
	('H1', 107, 13, 9),
	('H1', 108, 13, 10),
	('H1', 109, 13, 11),
	('H1', 110, 13, 12),
	('H1', 111, 14, 0),
	('H1', 112, 14, 1),
	('H1', 113, 14, 2),
	('H1', 114, 14, 3),
	('H1', 115, 14, 4),
	('H1', 116, 14, 5),
	('H1', 117, 14, 6),
	('H1', 118, 14, 7),
	('H1', 119, 14, 8),
	('H1', 120, 14, 9),
	('H1', 121, 14, 10),
	('H1', 122, 14, 11),
	('H1', 123, 15, 0),
	('H1', 124, 15, 1),
	('H1', 125, 15, 2),
	('H1', 126, 15, 3),
	('H1', 127, 16, 0),
	('H1', 128, 16, 1),
	('H1', 129, 16, 2),
	('H1', 130, 17, 0),
	('H1', 131, 17, 1),
	('H1', 132, 17, 2),
	('H1', 133, 17, 3),
	('H1', 134, 17, 4),
	('H1', 135, 18, 0),
	('H1', 136, 18, 1),
	('H1', 137, 18, 2),
	('H1', 138, 18, 3),
	('H1', 139, 19, 0),
	('H1', 140, 19, 1),
	('H1', 141, 19, 2),
	('H1', 142, 19, 3),
	('H1', 143, 19, 4),
	('H1', 144, 20, 0),
	('H1', 145, 20, 1),
	('H1', 146, 20, 2),
	('H1', 147, 20, 3),
	('H1', 148, 20, 4),
	('H1', 149, 20, 5),
	('H1', 150, 21, 0),
	('H1', 151, 21, 1),
	('H1', 152, 21, 2),
	('H1', 153, 21, 3),
	('H1', 154, 21, 4),
	('H1', 155, 21, 5),
	('H1', 156, 21, 6),
	('H1', 157, 21, 7),
	('H1', 158, 21, 8),
	('H1', 159, 21, 9),
	('H1', 160, 22, 0),
	('H1', 161, 22, 1),
	('H1', 162, 22, 2),
	('H1', 163, 22, 3),
	('H1', 164, 22, 4),
	('H1', 165, 22, 5),
	('H1', 166, 23, 0),
	('H1', 167, 23, 1),
	('H1', 168, 23, 2),
	('H1', 169, 23, 3),
	('H1', 170, 24, 0),
	('H1', 171, 24, 1),
	('H1', 172, 24, 2),
	('H1', 173, 25, 0),
	('H1', 174, 25, 1),
	('H1', 175, 25, 2),
	('H1', 176, 25, 3),
	('H1', 177, 25, 4),
	('H1', 178, 25, 5),
	('H1', 179, 26, 0),
	('H1', 180, 26, 1),
	('H1', 181, 26, 2),
	('H1', 182, 27, 0),
	('H1', 183, 27, 1),
	('H1', 184, 27, 2),
	('H1', 185, 27, 3),
	('H1', 186, 28, 0),
	('H1', 187, 28, 1),
	('H1', 188, 28, 2),
	('H1', 189, 28, 3),
	('H1', 190, 29, 0),
	('H1', 191, 29, 1),
	('H1', 192, 29, 2),
	('H1', 193, 29, 3),
	('H1', 194, 30, 0),
	('H1', 195, 30, 1),
	('H1', 196, 30, 2),
	('H1', 197, 30, 3),
	('H1', 198, 30, 4),
	('H1', 199, 30, 5),
	('H1', 200, 30, 6),
	('H1', 201, 30, 7),
	('H1', 202, 30, 8),
	('H1', 203, 30, 9),
	('H1', 204, 30, 10),
	('H1', 205, 30, 11),
	('H1', 206, 30, 12),
	('H1', 207, 30, 13),
	('H1', 208, 30, 14),
	('H1', 209, 30, 15),
	('H1', 210, 31, 1),
	('H1', 211, 31, 2),
	('H1', 212, 31, 3),
	('H1', 213, 31, 4),
	('H1', 214, 32, 1),
	('H1', 215, 32, 2),
	('H1', 216, 32, 3),
	('H1', 217, 32, 4),
	('H1', 218, 32, 5),
	('H1', 219, 32, 6),
	('H1', 220, 33, 1),
	('H1', 221, 33, 2),
	('H1', 222, 33, 3),
	('H1', 223, 34, 1),
	('H1', 224, 34, 2),
	('H1', 225, 34, 3),
	('H1', 226, 35, 1),
	('H1', 227, 35, 2),
	('H1', 228, 35, 3),
	('H1', 229, 36, 1),
	('H1', 230, 36, 2),
	('H1', 231, 36, 3),
	('H1', 232, 36, 4),
	('H1', 233, 36, 5),
	('H1', 234, 36, 6),
	('H1', 235, 37, 1),
	('H1', 236, 37, 2),
	('H1', 237, 38, 1),
	('H1', 238, 38, 2),
	('H1', 239, 38, 3),
	('H1', 240, 38, 4),
	('H1', 241, 39, 1),
	('H1', 242, 39, 2),
	('H1', 243, 39, 3),
	('H1', 244, 39, 4),
	('H1', 245, 40, 1),
	('H1', 246, 40, 2),
	('H1', 247, 40, 3),
	('H1', 248, 40, 4),
	('H1', 249, 41, 1),
	('H1', 250, 41, 2),
	('H1', 251, 41, 3),
	('H1', 252, 41, 4),
	('H1', 253, 41, 5),
	('H1', 254, 41, 6),
	('H1', 255, 41, 7),
	('H1', 256, 41, 8),
	('H1', 257, 41, 9),
	('H1', 258, 41, 10),
	('H1', 259, 41, 11),
	('H1', 260, 42, 1),
	('H1', 261, 42, 2),
	('H1', 262, 42, 3),
	('H1', 263, 42, 4),
	('H1', 264, 42, 5),
	('H1', 265, 42, 6),
	('H1', 266, 42, 7),
	('H1', 267, 42, 8),
	('H1', 268, 42, 9),
	('H1', 269, 43, 1),
	('H1', 270, 43, 2),
	('H1', 271, 43, 3),
	('H1', 272, 43, 4),
	('H1', 273, 44, 1),
	('H1', 274, 44, 2),
	('H1', 275, 44, 3),
	('H1', 276, 44, 4),
	('H1', 277, 45, 1),
	('H1', 278, 45, 2),
	('H1', 279, 45, 3),
	('H1', 280, 45, 4),
	('H1', 281, 45, 5),
	('H1', 282, 45, 6),
	('H1', 283, 45, 7),
	('H1', 284, 45, 8),
	('H1', 285, 45, 9),
	('H1', 286, 45, 10),
	('H1', 287, 45, 11),
	('H1', 288, 12, 1),
	('H1', 289, 46, 1),
	('H1', 290, 46, 2),
	('H1', 291, 46, 3),
	('H1', 292, 46, 4),
	('H1', 293, 46, 5),
	('H1', 294, 46, 6),
	('H1', 295, 2, 1),
	('H1', 296, 47, 1),
	('H1', 297, 47, 2),
	('H1', 298, 47, 3),
	('H1', 299, 48, 1),
	('H1', 300, 48, 2),
	('H2', 1, 1, 0),
	('H2', 2, 1, 1),
	('H2', 3, 1, 2),
	('H2', 4, 1, 3),
	('H2', 5, 1, 4),
	('H2', 6, 1, 5),
	('H2', 7, 1, 6),
	('H2', 8, 1, 7),
	('H2', 9, 1, 8),
	('H2', 10, 1, 9),
	('H2', 11, 1, 10),
	('H2', 12, 2, 0),
	('H2', 13, 2, 1),
	('H2', 14, 3, 0),
	('H2', 15, 3, 1),
	('H2', 16, 3, 2),
	('H2', 17, 3, 3),
	('H2', 18, 3, 4),
	('H2', 19, 3, 5),
	('H2', 20, 4, 0),
	('H2', 21, 4, 1),
	('H2', 22, 4, 2),
	('H2', 23, 4, 3),
	('H2', 24, 4, 4),
	('H2', 25, 4, 5),
	('H2', 26, 4, 6),
	('H2', 27, 4, 7),
	('H2', 28, 4, 8),
	('H2', 29, 4, 9),
	('H2', 30, 4, 10),
	('H2', 31, 4, 11),
	('H2', 32, 4, 12),
	('H2', 33, 5, 0),
	('H2', 34, 5, 1),
	('H2', 35, 5, 2),
	('H2', 36, 5, 3),
	('H2', 37, 5, 4),
	('H2', 38, 6, 0),
	('H2', 39, 6, 1),
	('H2', 40, 6, 2),
	('H2', 41, 7, 0),
	('H2', 42, 7, 1),
	('H2', 43, 7, 2),
	('H2', 44, 7, 3),
	('H2', 45, 7, 4),
	('H2', 46, 8, 0),
	('H2', 47, 9, 0),
	('H2', 48, 9, 1),
	('H2', 49, 9, 2),
	('H2', 50, 9, 3),
	('H2', 51, 10, 0),
	('H2', 52, 10, 1),
	('H2', 53, 10, 2),
	('H2', 54, 10, 3),
	('H2', 55, 10, 4),
	('H2', 56, 10, 5),
	('H2', 57, 10, 6),
	('H2', 58, 10, 7),
	('H2', 59, 10, 8),
	('H2', 60, 11, 0),
	('H2', 61, 11, 1),
	('H2', 62, 11, 2),
	('H2', 63, 11, 3),
	('H2', 64, 11, 4),
	('H2', 65, 11, 5),
	('H2', 66, 11, 6),
	('H2', 67, 11, 7),
	('H2', 68, 11, 8),
	('H2', 69, 11, 9),
	('H2', 70, 11, 10),
	('H2', 71, 11, 11),
	('H2', 72, 11, 12),
	('H2', 73, 11, 13),
	('H2', 74, 11, 14),
	('H2', 75, 11, 15),
	('H2', 76, 11, 16),
	('H2', 77, 11, 17),
	('H2', 78, 11, 18),
	('H2', 79, 11, 19),
	('H2', 80, 11, 20),
	('H2', 81, 11, 21),
	('H2', 82, 11, 22),
	('H2', 83, 11, 23),
	('H2', 84, 11, 24),
	('H2', 85, 11, 25),
	('H2', 86, 11, 26),
	('H2', 87, 11, 27),
	('H2', 88, 11, 28),
	('H2', 89, 11, 29),
	('H2', 90, 11, 30),
	('H2', 91, 11, 31),
	('H2', 92, 12, 0),
	('H2', 93, 12, 1),
	('H2', 94, 12, 2),
	('H2', 95, 12, 3),
	('H2', 96, 12, 4),
	('H2', 97, 12, 5),
	('H2', 98, 13, 0),
	('H2', 99, 13, 1),
	('H2', 100, 13, 2),
	('H2', 101, 13, 3),
	('H2', 102, 13, 4),
	('H2', 103, 13, 5),
	('H2', 104, 13, 6),
	('H2', 105, 13, 7),
	('H2', 106, 13, 8),
	('H2', 107, 13, 9),
	('H2', 108, 13, 10),
	('H2', 109, 13, 11),
	('H2', 110, 13, 12),
	('H2', 111, 14, 0),
	('H2', 112, 14, 1),
	('H2', 113, 14, 2),
	('H2', 114, 14, 3),
	('H2', 115, 14, 4),
	('H2', 116, 14, 5),
	('H2', 117, 14, 6),
	('H2', 118, 14, 7),
	('H2', 119, 14, 8),
	('H2', 120, 14, 9),
	('H2', 121, 14, 10),
	('H2', 122, 14, 11),
	('H2', 123, 15, 0),
	('H2', 124, 15, 1),
	('H2', 125, 15, 2),
	('H2', 126, 15, 3),
	('H2', 127, 16, 0),
	('H2', 128, 16, 1),
	('H2', 129, 16, 2),
	('H2', 130, 17, 0),
	('H2', 131, 17, 1),
	('H2', 132, 17, 2),
	('H2', 133, 17, 3),
	('H2', 134, 17, 4),
	('H2', 135, 18, 0),
	('H2', 136, 18, 1),
	('H2', 137, 18, 2),
	('H2', 138, 18, 3),
	('H2', 139, 19, 0),
	('H2', 140, 19, 1),
	('H2', 141, 19, 2),
	('H2', 142, 19, 3),
	('H2', 143, 19, 4),
	('H2', 144, 20, 0),
	('H2', 145, 20, 1),
	('H2', 146, 20, 2),
	('H2', 147, 20, 3),
	('H2', 148, 20, 4),
	('H2', 149, 20, 5),
	('H2', 150, 21, 0),
	('H2', 151, 21, 1),
	('H2', 152, 21, 2),
	('H2', 153, 21, 3),
	('H2', 154, 21, 4),
	('H2', 155, 21, 5),
	('H2', 156, 21, 6),
	('H2', 157, 21, 7),
	('H2', 158, 21, 8),
	('H2', 159, 21, 9),
	('H2', 160, 22, 0),
	('H2', 161, 22, 1),
	('H2', 162, 22, 2),
	('H2', 163, 22, 3),
	('H2', 164, 22, 4),
	('H2', 165, 22, 5),
	('H2', 166, 23, 0),
	('H2', 167, 23, 1),
	('H2', 168, 23, 2),
	('H2', 169, 23, 3),
	('H2', 170, 24, 0),
	('H2', 171, 24, 1),
	('H2', 172, 24, 2),
	('H2', 173, 25, 0),
	('H2', 174, 25, 1),
	('H2', 175, 25, 2),
	('H2', 176, 25, 3),
	('H2', 177, 25, 4),
	('H2', 178, 25, 5),
	('H2', 179, 26, 0),
	('H2', 180, 26, 1),
	('H2', 181, 26, 2),
	('H2', 182, 27, 0),
	('H2', 183, 27, 1),
	('H2', 184, 27, 2),
	('H2', 185, 27, 3),
	('H2', 186, 28, 0),
	('H2', 187, 28, 1),
	('H2', 188, 28, 2),
	('H2', 189, 28, 3),
	('H2', 190, 29, 0),
	('H2', 191, 29, 1),
	('H2', 192, 29, 2),
	('H2', 193, 29, 3),
	('H2', 194, 30, 0),
	('H2', 195, 30, 1),
	('H2', 196, 30, 2),
	('H2', 197, 30, 3),
	('H2', 198, 30, 4),
	('H2', 199, 30, 5),
	('H2', 200, 30, 6),
	('H2', 201, 30, 7),
	('H2', 202, 30, 8),
	('H2', 203, 30, 9),
	('H2', 204, 30, 10),
	('H2', 205, 30, 11),
	('H2', 206, 30, 12),
	('H2', 207, 30, 13),
	('H2', 208, 30, 14),
	('H2', 209, 30, 15),
	('H2', 210, 31, 1),
	('H2', 211, 31, 2),
	('H2', 212, 31, 3),
	('H2', 213, 31, 4),
	('H2', 214, 32, 1),
	('H2', 215, 32, 2),
	('H2', 216, 32, 3),
	('H2', 217, 32, 4),
	('H2', 218, 32, 5),
	('H2', 219, 32, 6),
	('H2', 220, 33, 1),
	('H2', 221, 33, 2),
	('H2', 222, 33, 3),
	('H2', 223, 34, 1),
	('H2', 224, 34, 2),
	('H2', 225, 34, 3),
	('H2', 226, 35, 1),
	('H2', 227, 35, 2),
	('H2', 228, 35, 3),
	('H2', 229, 36, 1),
	('H2', 230, 36, 2),
	('H2', 231, 36, 3),
	('H2', 232, 36, 4),
	('H2', 233, 36, 5),
	('H2', 234, 36, 6),
	('H2', 235, 37, 1),
	('H2', 236, 37, 2),
	('H2', 237, 38, 1),
	('H2', 238, 38, 2),
	('H2', 239, 38, 3),
	('H2', 240, 38, 4),
	('H2', 241, 39, 1),
	('H2', 242, 39, 2),
	('H2', 243, 39, 3),
	('H2', 244, 39, 4),
	('H2', 245, 40, 1),
	('H2', 246, 40, 2),
	('H2', 247, 40, 3),
	('H2', 248, 40, 4),
	('H2', 249, 41, 1),
	('H2', 250, 41, 2),
	('H2', 251, 41, 3),
	('H2', 252, 41, 4),
	('H2', 253, 41, 5),
	('H2', 254, 41, 6),
	('H2', 255, 41, 7),
	('H2', 256, 41, 8),
	('H2', 257, 41, 9),
	('H2', 258, 41, 10),
	('H2', 259, 41, 11),
	('H2', 260, 42, 1),
	('H2', 261, 42, 2),
	('H2', 262, 42, 3),
	('H2', 263, 42, 4),
	('H2', 264, 42, 5),
	('H2', 265, 42, 6),
	('H2', 266, 42, 7),
	('H2', 267, 42, 8),
	('H2', 268, 42, 9),
	('H2', 269, 43, 1),
	('H2', 270, 43, 2),
	('H2', 271, 43, 3),
	('H2', 272, 43, 4),
	('H2', 273, 44, 1),
	('H2', 274, 44, 2),
	('H2', 275, 44, 3),
	('H2', 276, 44, 4),
	('H2', 277, 45, 1),
	('H2', 278, 45, 2),
	('H2', 279, 45, 3),
	('H2', 280, 45, 4),
	('H2', 281, 45, 5),
	('H2', 282, 45, 6),
	('H2', 283, 45, 7),
	('H2', 284, 45, 8),
	('H2', 285, 45, 9),
	('H2', 286, 45, 10),
	('H2', 287, 45, 11),
	('H2', 288, 12, 1),
	('H2', 289, 46, 1),
	('H2', 290, 46, 2),
	('H2', 291, 46, 3),
	('H2', 292, 46, 4),
	('H2', 293, 46, 5),
	('H2', 294, 46, 6),
	('H2', 295, 2, 1),
	('H2', 296, 47, 1),
	('H2', 297, 47, 2),
	('H2', 298, 47, 3),
	('H2', 299, 48, 1),
	('H2', 300, 48, 2),
	('H3', 1, 1, 0),
	('H3', 2, 1, 1),
	('H3', 3, 1, 2),
	('H3', 4, 1, 3),
	('H3', 5, 1, 4),
	('H3', 6, 1, 5),
	('H3', 7, 1, 6),
	('H3', 8, 1, 7),
	('H3', 9, 1, 8),
	('H3', 10, 1, 9),
	('H3', 11, 1, 10),
	('H3', 12, 2, 0),
	('H3', 13, 2, 1),
	('H3', 14, 3, 0),
	('H3', 15, 3, 1),
	('H3', 16, 3, 2),
	('H3', 17, 3, 3),
	('H3', 18, 3, 4),
	('H3', 19, 3, 5),
	('H3', 20, 4, 0),
	('H3', 21, 4, 1),
	('H3', 22, 4, 2),
	('H3', 23, 4, 3),
	('H3', 24, 4, 4),
	('H3', 25, 4, 5),
	('H3', 26, 4, 6),
	('H3', 27, 4, 7),
	('H3', 28, 4, 8),
	('H3', 29, 4, 9),
	('H3', 30, 4, 10),
	('H3', 31, 4, 11),
	('H3', 32, 4, 12),
	('H3', 33, 5, 0),
	('H3', 34, 5, 1),
	('H3', 35, 5, 2),
	('H3', 36, 5, 3),
	('H3', 37, 5, 4),
	('H3', 38, 6, 0),
	('H3', 39, 6, 1),
	('H3', 40, 6, 2),
	('H3', 41, 7, 0),
	('H3', 42, 7, 1),
	('H3', 43, 7, 2),
	('H3', 44, 7, 3),
	('H3', 45, 7, 4),
	('H3', 46, 8, 0),
	('H3', 47, 9, 0),
	('H3', 48, 9, 1),
	('H3', 49, 9, 2),
	('H3', 50, 9, 3),
	('H3', 51, 10, 0),
	('H3', 52, 10, 1),
	('H3', 53, 10, 2),
	('H3', 54, 10, 3),
	('H3', 55, 10, 4),
	('H3', 56, 10, 5),
	('H3', 57, 10, 6),
	('H3', 58, 10, 7),
	('H3', 59, 10, 8),
	('H3', 60, 11, 0),
	('H3', 61, 11, 1),
	('H3', 62, 11, 2),
	('H3', 63, 11, 3),
	('H3', 64, 11, 4),
	('H3', 65, 11, 5),
	('H3', 66, 11, 6),
	('H3', 67, 11, 7),
	('H3', 68, 11, 8),
	('H3', 69, 11, 9),
	('H3', 70, 11, 10),
	('H3', 71, 11, 11),
	('H3', 72, 11, 12),
	('H3', 73, 11, 13),
	('H3', 74, 11, 14),
	('H3', 75, 11, 15),
	('H3', 76, 11, 16),
	('H3', 77, 11, 17),
	('H3', 78, 11, 18),
	('H3', 79, 11, 19),
	('H3', 80, 11, 20),
	('H3', 81, 11, 21),
	('H3', 82, 11, 22),
	('H3', 83, 11, 23),
	('H3', 84, 11, 24),
	('H3', 85, 11, 25),
	('H3', 86, 11, 26),
	('H3', 87, 11, 27),
	('H3', 88, 11, 28),
	('H3', 89, 11, 29),
	('H3', 90, 11, 30),
	('H3', 91, 11, 31),
	('H3', 92, 12, 0),
	('H3', 93, 12, 1),
	('H3', 94, 12, 2),
	('H3', 95, 12, 3),
	('H3', 96, 12, 4),
	('H3', 97, 12, 5),
	('H3', 98, 13, 0),
	('H3', 99, 13, 1),
	('H3', 100, 13, 2),
	('H3', 101, 13, 3),
	('H3', 102, 13, 4),
	('H3', 103, 13, 5),
	('H3', 104, 13, 6),
	('H3', 105, 13, 7),
	('H3', 106, 13, 8),
	('H3', 107, 13, 9),
	('H3', 108, 13, 10),
	('H3', 109, 13, 11),
	('H3', 110, 13, 12),
	('H3', 111, 14, 0),
	('H3', 112, 14, 1),
	('H3', 113, 14, 2),
	('H3', 114, 14, 3),
	('H3', 115, 14, 4),
	('H3', 116, 14, 5),
	('H3', 117, 14, 6),
	('H3', 118, 14, 7),
	('H3', 119, 14, 8),
	('H3', 120, 14, 9),
	('H3', 121, 14, 10),
	('H3', 122, 14, 11),
	('H3', 123, 15, 0),
	('H3', 124, 15, 1),
	('H3', 125, 15, 2),
	('H3', 126, 15, 3),
	('H3', 127, 16, 0),
	('H3', 128, 16, 1),
	('H3', 129, 16, 2),
	('H3', 130, 17, 0),
	('H3', 131, 17, 1),
	('H3', 132, 17, 2),
	('H3', 133, 17, 3),
	('H3', 134, 17, 4),
	('H3', 135, 18, 0),
	('H3', 136, 18, 1),
	('H3', 137, 18, 2),
	('H3', 138, 18, 3),
	('H3', 139, 19, 0),
	('H3', 140, 19, 1),
	('H3', 141, 19, 2),
	('H3', 142, 19, 3),
	('H3', 143, 19, 4),
	('H3', 144, 20, 0),
	('H3', 145, 20, 1),
	('H3', 146, 20, 2),
	('H3', 147, 20, 3),
	('H3', 148, 20, 4),
	('H3', 149, 20, 5),
	('H3', 150, 21, 0),
	('H3', 151, 21, 1),
	('H3', 152, 21, 2),
	('H3', 153, 21, 3),
	('H3', 154, 21, 4),
	('H3', 155, 21, 5),
	('H3', 156, 21, 6),
	('H3', 157, 21, 7),
	('H3', 158, 21, 8),
	('H3', 159, 21, 9),
	('H3', 160, 22, 0),
	('H3', 161, 22, 1),
	('H3', 162, 22, 2),
	('H3', 163, 22, 3),
	('H3', 164, 22, 4),
	('H3', 165, 22, 5),
	('H3', 166, 23, 0),
	('H3', 167, 23, 1),
	('H3', 168, 23, 2),
	('H3', 169, 23, 3),
	('H3', 170, 24, 0),
	('H3', 171, 24, 1),
	('H3', 172, 24, 2),
	('H3', 173, 25, 0),
	('H3', 174, 25, 1),
	('H3', 175, 25, 2),
	('H3', 176, 25, 3),
	('H3', 177, 25, 4),
	('H3', 178, 25, 5),
	('H3', 179, 26, 0),
	('H3', 180, 26, 1),
	('H3', 181, 26, 2),
	('H3', 182, 27, 0),
	('H3', 183, 27, 1),
	('H3', 184, 27, 2),
	('H3', 185, 27, 3),
	('H3', 186, 28, 0),
	('H3', 187, 28, 1),
	('H3', 188, 28, 2),
	('H3', 189, 28, 3),
	('H3', 190, 29, 0),
	('H3', 191, 29, 1),
	('H3', 192, 29, 2),
	('H3', 193, 29, 3),
	('H3', 194, 30, 0),
	('H3', 195, 30, 1),
	('H3', 196, 30, 2),
	('H3', 197, 30, 3),
	('H3', 198, 30, 4),
	('H3', 199, 30, 5),
	('H3', 200, 30, 6),
	('H3', 201, 30, 7),
	('H3', 202, 30, 8),
	('H3', 203, 30, 9),
	('H3', 204, 30, 10),
	('H3', 205, 30, 11),
	('H3', 206, 30, 12),
	('H3', 207, 30, 13),
	('H3', 208, 30, 14),
	('H3', 209, 30, 15),
	('H3', 210, 31, 1),
	('H3', 211, 31, 2),
	('H3', 212, 31, 3),
	('H3', 213, 31, 4),
	('H3', 214, 32, 1),
	('H3', 215, 32, 2),
	('H3', 216, 32, 3),
	('H3', 217, 32, 4),
	('H3', 218, 32, 5),
	('H3', 219, 32, 6),
	('H3', 220, 33, 1),
	('H3', 221, 33, 2),
	('H3', 222, 33, 3),
	('H3', 223, 34, 1),
	('H3', 224, 34, 2),
	('H3', 225, 34, 3),
	('H3', 226, 35, 1),
	('H3', 227, 35, 2),
	('H3', 228, 35, 3),
	('H3', 229, 36, 1),
	('H3', 230, 36, 2),
	('H3', 231, 36, 3),
	('H3', 232, 36, 4),
	('H3', 233, 36, 5),
	('H3', 234, 36, 6),
	('H3', 235, 37, 1),
	('H3', 236, 37, 2),
	('H3', 237, 38, 1),
	('H3', 238, 38, 2),
	('H3', 239, 38, 3),
	('H3', 240, 38, 4),
	('H3', 241, 39, 1),
	('H3', 242, 39, 2),
	('H3', 243, 39, 3),
	('H3', 244, 39, 4),
	('H3', 245, 40, 1),
	('H3', 246, 40, 2),
	('H3', 247, 40, 3),
	('H3', 248, 40, 4),
	('H3', 249, 41, 1),
	('H3', 250, 41, 2),
	('H3', 251, 41, 3),
	('H3', 252, 41, 4),
	('H3', 253, 41, 5),
	('H3', 254, 41, 6),
	('H3', 255, 41, 7),
	('H3', 256, 41, 8),
	('H3', 257, 41, 9),
	('H3', 258, 41, 10),
	('H3', 259, 41, 11),
	('H3', 260, 42, 1),
	('H3', 261, 42, 2),
	('H3', 262, 42, 3),
	('H3', 263, 42, 4),
	('H3', 264, 42, 5),
	('H3', 265, 42, 6),
	('H3', 266, 42, 7),
	('H3', 267, 42, 8),
	('H3', 268, 42, 9),
	('H3', 269, 43, 1),
	('H3', 270, 43, 2),
	('H3', 271, 43, 3),
	('H3', 272, 43, 4),
	('H3', 273, 44, 1),
	('H3', 274, 44, 2),
	('H3', 275, 44, 3),
	('H3', 276, 44, 4),
	('H3', 277, 45, 1),
	('H3', 278, 45, 2),
	('H3', 279, 45, 3),
	('H3', 280, 45, 4),
	('H3', 281, 45, 5),
	('H3', 282, 45, 6),
	('H3', 283, 45, 7),
	('H3', 284, 45, 8),
	('H3', 285, 45, 9),
	('H3', 286, 45, 10),
	('H3', 287, 45, 11),
	('H3', 288, 12, 1),
	('H3', 289, 46, 1),
	('H3', 290, 46, 2),
	('H3', 291, 46, 3),
	('H3', 292, 46, 4),
	('H3', 293, 46, 5),
	('H3', 294, 46, 6),
	('H3', 295, 2, 1),
	('H3', 296, 47, 1),
	('H3', 297, 47, 2),
	('H3', 298, 47, 3),
	('H3', 299, 48, 1),
	('H3', 300, 48, 2),
	('H4', 1, 1, 0),
	('H4', 2, 1, 1),
	('H4', 3, 1, 2),
	('H4', 4, 1, 3),
	('H4', 5, 1, 4),
	('H4', 6, 1, 5),
	('H4', 7, 1, 6),
	('H4', 8, 1, 7),
	('H4', 9, 1, 8),
	('H4', 10, 1, 9),
	('H4', 11, 1, 10),
	('H4', 12, 2, 0),
	('H4', 13, 2, 1),
	('H4', 14, 3, 0),
	('H4', 15, 3, 1),
	('H4', 16, 3, 2),
	('H4', 17, 3, 3),
	('H4', 18, 3, 4),
	('H4', 19, 3, 5),
	('H4', 20, 4, 0),
	('H4', 21, 4, 1),
	('H4', 22, 4, 2),
	('H4', 23, 4, 3),
	('H4', 24, 4, 4),
	('H4', 25, 4, 5),
	('H4', 26, 4, 6),
	('H4', 27, 4, 7),
	('H4', 28, 4, 8),
	('H4', 29, 4, 9),
	('H4', 30, 4, 10),
	('H4', 31, 4, 11),
	('H4', 32, 4, 12),
	('H4', 33, 5, 0),
	('H4', 34, 5, 1),
	('H4', 35, 5, 2),
	('H4', 36, 5, 3),
	('H4', 37, 5, 4),
	('H4', 38, 6, 0),
	('H4', 39, 6, 1),
	('H4', 40, 6, 2),
	('H4', 41, 7, 0),
	('H4', 42, 7, 1),
	('H4', 43, 7, 2),
	('H4', 44, 7, 3),
	('H4', 45, 7, 4),
	('H4', 46, 8, 0),
	('H4', 47, 9, 0),
	('H4', 48, 9, 1),
	('H4', 49, 9, 2),
	('H4', 50, 9, 3),
	('H4', 51, 10, 0),
	('H4', 52, 10, 1),
	('H4', 53, 10, 2),
	('H4', 54, 10, 3),
	('H4', 55, 10, 4),
	('H4', 56, 10, 5),
	('H4', 57, 10, 6),
	('H4', 58, 10, 7),
	('H4', 59, 10, 8),
	('H4', 60, 11, 0),
	('H4', 61, 11, 1),
	('H4', 62, 11, 2),
	('H4', 63, 11, 3),
	('H4', 64, 11, 4),
	('H4', 65, 11, 5),
	('H4', 66, 11, 6),
	('H4', 67, 11, 7),
	('H4', 68, 11, 8),
	('H4', 69, 11, 9),
	('H4', 70, 11, 10),
	('H4', 71, 11, 11),
	('H4', 72, 11, 12),
	('H4', 73, 11, 13),
	('H4', 74, 11, 14),
	('H4', 75, 11, 15),
	('H4', 76, 11, 16),
	('H4', 77, 11, 17),
	('H4', 78, 11, 18),
	('H4', 79, 11, 19),
	('H4', 80, 11, 20),
	('H4', 81, 11, 21),
	('H4', 82, 11, 22),
	('H4', 83, 11, 23),
	('H4', 84, 11, 24),
	('H4', 85, 11, 25),
	('H4', 86, 11, 26),
	('H4', 87, 11, 27),
	('H4', 88, 11, 28),
	('H4', 89, 11, 29),
	('H4', 90, 11, 30),
	('H4', 91, 11, 31),
	('H4', 92, 12, 0),
	('H4', 93, 12, 1),
	('H4', 94, 12, 2),
	('H4', 95, 12, 3),
	('H4', 96, 12, 4),
	('H4', 97, 12, 5),
	('H4', 98, 13, 0),
	('H4', 99, 13, 1),
	('H4', 100, 13, 2),
	('H4', 101, 13, 3),
	('H4', 102, 13, 4),
	('H4', 103, 13, 5),
	('H4', 104, 13, 6),
	('H4', 105, 13, 7),
	('H4', 106, 13, 8),
	('H4', 107, 13, 9),
	('H4', 108, 13, 10),
	('H4', 109, 13, 11),
	('H4', 110, 13, 12),
	('H4', 111, 14, 0),
	('H4', 112, 14, 1),
	('H4', 113, 14, 2),
	('H4', 114, 14, 3),
	('H4', 115, 14, 4),
	('H4', 116, 14, 5),
	('H4', 117, 14, 6),
	('H4', 118, 14, 7),
	('H4', 119, 14, 8),
	('H4', 120, 14, 9),
	('H4', 121, 14, 10),
	('H4', 122, 14, 11),
	('H4', 123, 15, 0),
	('H4', 124, 15, 1),
	('H4', 125, 15, 2),
	('H4', 126, 15, 3),
	('H4', 127, 16, 0),
	('H4', 128, 16, 1),
	('H4', 129, 16, 2),
	('H4', 130, 17, 0),
	('H4', 131, 17, 1),
	('H4', 132, 17, 2),
	('H4', 133, 17, 3),
	('H4', 134, 17, 4),
	('H4', 135, 18, 0),
	('H4', 136, 18, 1),
	('H4', 137, 18, 2),
	('H4', 138, 18, 3),
	('H4', 139, 19, 0),
	('H4', 140, 19, 1),
	('H4', 141, 19, 2),
	('H4', 142, 19, 3),
	('H4', 143, 19, 4),
	('H4', 144, 20, 0),
	('H4', 145, 20, 1),
	('H4', 146, 20, 2),
	('H4', 147, 20, 3),
	('H4', 148, 20, 4),
	('H4', 149, 20, 5),
	('H4', 150, 21, 0),
	('H4', 151, 21, 1),
	('H4', 152, 21, 2),
	('H4', 153, 21, 3),
	('H4', 154, 21, 4),
	('H4', 155, 21, 5),
	('H4', 156, 21, 6),
	('H4', 157, 21, 7),
	('H4', 158, 21, 8),
	('H4', 159, 21, 9),
	('H4', 160, 22, 0),
	('H4', 161, 22, 1),
	('H4', 162, 22, 2),
	('H4', 163, 22, 3),
	('H4', 164, 22, 4),
	('H4', 165, 22, 5),
	('H4', 166, 23, 0),
	('H4', 167, 23, 1),
	('H4', 168, 23, 2),
	('H4', 169, 23, 3),
	('H4', 170, 24, 0),
	('H4', 171, 24, 1),
	('H4', 172, 24, 2),
	('H4', 173, 25, 0),
	('H4', 174, 25, 1),
	('H4', 175, 25, 2),
	('H4', 176, 25, 3),
	('H4', 177, 25, 4),
	('H4', 178, 25, 5),
	('H4', 179, 26, 0),
	('H4', 180, 26, 1),
	('H4', 181, 26, 2),
	('H4', 182, 27, 0),
	('H4', 183, 27, 1),
	('H4', 184, 27, 2),
	('H4', 185, 27, 3),
	('H4', 186, 28, 0),
	('H4', 187, 28, 1),
	('H4', 188, 28, 2),
	('H4', 189, 28, 3),
	('H4', 190, 29, 0),
	('H4', 191, 29, 1),
	('H4', 192, 29, 2),
	('H4', 193, 29, 3),
	('H4', 194, 30, 0),
	('H4', 195, 30, 1),
	('H4', 196, 30, 2),
	('H4', 197, 30, 3),
	('H4', 198, 30, 4),
	('H4', 199, 30, 5),
	('H4', 200, 30, 6),
	('H4', 201, 30, 7),
	('H4', 202, 30, 8),
	('H4', 203, 30, 9),
	('H4', 204, 30, 10),
	('H4', 205, 30, 11),
	('H4', 206, 30, 12),
	('H4', 207, 30, 13),
	('H4', 208, 30, 14),
	('H4', 209, 30, 15),
	('H4', 210, 31, 1),
	('H4', 211, 31, 2),
	('H4', 212, 31, 3),
	('H4', 213, 31, 4),
	('H4', 214, 32, 1),
	('H4', 215, 32, 2),
	('H4', 216, 32, 3),
	('H4', 217, 32, 4),
	('H4', 218, 32, 5),
	('H4', 219, 32, 6),
	('H4', 220, 33, 1),
	('H4', 221, 33, 2),
	('H4', 222, 33, 3),
	('H4', 223, 34, 1),
	('H4', 224, 34, 2),
	('H4', 225, 34, 3),
	('H4', 226, 35, 1),
	('H4', 227, 35, 2),
	('H4', 228, 35, 3),
	('H4', 229, 36, 1),
	('H4', 230, 36, 2),
	('H4', 231, 36, 3),
	('H4', 232, 36, 4),
	('H4', 233, 36, 5),
	('H4', 234, 36, 6),
	('H4', 235, 37, 1),
	('H4', 236, 37, 2),
	('H4', 237, 38, 1),
	('H4', 238, 38, 2),
	('H4', 239, 38, 3),
	('H4', 240, 38, 4),
	('H4', 241, 39, 1),
	('H4', 242, 39, 2),
	('H4', 243, 39, 3),
	('H4', 244, 39, 4),
	('H4', 245, 40, 1),
	('H4', 246, 40, 2),
	('H4', 247, 40, 3),
	('H4', 248, 40, 4),
	('H4', 249, 41, 1),
	('H4', 250, 41, 2),
	('H4', 251, 41, 3),
	('H4', 252, 41, 4),
	('H4', 253, 41, 5),
	('H4', 254, 41, 6),
	('H4', 255, 41, 7),
	('H4', 256, 41, 8),
	('H4', 257, 41, 9),
	('H4', 258, 41, 10),
	('H4', 259, 41, 11),
	('H4', 260, 42, 1),
	('H4', 261, 42, 2),
	('H4', 262, 42, 3),
	('H4', 263, 42, 4),
	('H4', 264, 42, 5),
	('H4', 265, 42, 6),
	('H4', 266, 42, 7),
	('H4', 267, 42, 8),
	('H4', 268, 42, 9),
	('H4', 269, 43, 1),
	('H4', 270, 43, 2),
	('H4', 271, 43, 3),
	('H4', 272, 43, 4),
	('H4', 273, 44, 1),
	('H4', 274, 44, 2),
	('H4', 275, 44, 3),
	('H4', 276, 44, 4),
	('H4', 277, 45, 1),
	('H4', 278, 45, 2),
	('H4', 279, 45, 3),
	('H4', 280, 45, 4),
	('H4', 281, 45, 5),
	('H4', 282, 45, 6),
	('H4', 283, 45, 7),
	('H4', 284, 45, 8),
	('H4', 285, 45, 9),
	('H4', 286, 45, 10),
	('H4', 287, 45, 11),
	('H4', 288, 12, 1),
	('H4', 289, 46, 1),
	('H4', 290, 46, 2),
	('H4', 291, 46, 3),
	('H4', 292, 46, 4),
	('H4', 293, 46, 5),
	('H4', 294, 46, 6),
	('H4', 295, 2, 1),
	('H4', 296, 47, 1),
	('H4', 297, 47, 2),
	('H4', 298, 47, 3),
	('H4', 299, 48, 1),
	('H4', 300, 48, 2),
	('H5', 1, 1, 0),
	('H5', 2, 1, 1),
	('H5', 3, 1, 2),
	('H5', 4, 1, 3),
	('H5', 5, 1, 4),
	('H5', 6, 1, 5),
	('H5', 7, 1, 6),
	('H5', 8, 1, 7),
	('H5', 9, 1, 8),
	('H5', 10, 1, 9),
	('H5', 11, 1, 10),
	('H5', 12, 2, 0),
	('H5', 13, 2, 1),
	('H5', 14, 3, 0),
	('H5', 15, 3, 1),
	('H5', 16, 3, 2),
	('H5', 17, 3, 3),
	('H5', 18, 3, 4),
	('H5', 19, 3, 5),
	('H5', 20, 4, 0),
	('H5', 21, 4, 1),
	('H5', 22, 4, 2),
	('H5', 23, 4, 3),
	('H5', 24, 4, 4),
	('H5', 25, 4, 5),
	('H5', 26, 4, 6),
	('H5', 27, 4, 7),
	('H5', 28, 4, 8),
	('H5', 29, 4, 9),
	('H5', 30, 4, 10),
	('H5', 31, 4, 11),
	('H5', 32, 4, 12),
	('H5', 33, 5, 0),
	('H5', 34, 5, 1),
	('H5', 35, 5, 2),
	('H5', 36, 5, 3),
	('H5', 37, 5, 4),
	('H5', 38, 6, 0),
	('H5', 39, 6, 1),
	('H5', 40, 6, 2),
	('H5', 41, 7, 0),
	('H5', 42, 7, 1),
	('H5', 43, 7, 2),
	('H5', 44, 7, 3),
	('H5', 45, 7, 4),
	('H5', 46, 8, 0),
	('H5', 47, 9, 0),
	('H5', 48, 9, 1),
	('H5', 49, 9, 2),
	('H5', 50, 9, 3),
	('H5', 51, 10, 0),
	('H5', 52, 10, 1),
	('H5', 53, 10, 2),
	('H5', 54, 10, 3),
	('H5', 55, 10, 4),
	('H5', 56, 10, 5),
	('H5', 57, 10, 6),
	('H5', 58, 10, 7),
	('H5', 59, 10, 8),
	('H5', 60, 11, 0),
	('H5', 61, 11, 1),
	('H5', 62, 11, 2),
	('H5', 63, 11, 3),
	('H5', 64, 11, 4),
	('H5', 65, 11, 5),
	('H5', 66, 11, 6),
	('H5', 67, 11, 7),
	('H5', 68, 11, 8),
	('H5', 69, 11, 9),
	('H5', 70, 11, 10),
	('H5', 71, 11, 11),
	('H5', 72, 11, 12),
	('H5', 73, 11, 13),
	('H5', 74, 11, 14),
	('H5', 75, 11, 15),
	('H5', 76, 11, 16),
	('H5', 77, 11, 17),
	('H5', 78, 11, 18),
	('H5', 79, 11, 19),
	('H5', 80, 11, 20),
	('H5', 81, 11, 21),
	('H5', 82, 11, 22),
	('H5', 83, 11, 23),
	('H5', 84, 11, 24),
	('H5', 85, 11, 25),
	('H5', 86, 11, 26),
	('H5', 87, 11, 27),
	('H5', 88, 11, 28),
	('H5', 89, 11, 29),
	('H5', 90, 11, 30),
	('H5', 91, 11, 31),
	('H5', 92, 12, 0),
	('H5', 93, 12, 1),
	('H5', 94, 12, 2),
	('H5', 95, 12, 3),
	('H5', 96, 12, 4),
	('H5', 97, 12, 5),
	('H5', 98, 13, 0),
	('H5', 99, 13, 1),
	('H5', 100, 13, 2),
	('H5', 101, 13, 3),
	('H5', 102, 13, 4),
	('H5', 103, 13, 5),
	('H5', 104, 13, 6),
	('H5', 105, 13, 7),
	('H5', 106, 13, 8),
	('H5', 107, 13, 9),
	('H5', 108, 13, 10),
	('H5', 109, 13, 11),
	('H5', 110, 13, 12),
	('H5', 111, 14, 0),
	('H5', 112, 14, 1),
	('H5', 113, 14, 2),
	('H5', 114, 14, 3),
	('H5', 115, 14, 4),
	('H5', 116, 14, 5),
	('H5', 117, 14, 6),
	('H5', 118, 14, 7),
	('H5', 119, 14, 8),
	('H5', 120, 14, 9),
	('H5', 121, 14, 10),
	('H5', 122, 14, 11),
	('H5', 123, 15, 0),
	('H5', 124, 15, 1),
	('H5', 125, 15, 2),
	('H5', 126, 15, 3),
	('H5', 127, 16, 0),
	('H5', 128, 16, 1),
	('H5', 129, 16, 2),
	('H5', 130, 17, 0),
	('H5', 131, 17, 1),
	('H5', 132, 17, 2),
	('H5', 133, 17, 3),
	('H5', 134, 17, 4),
	('H5', 135, 18, 0),
	('H5', 136, 18, 1),
	('H5', 137, 18, 2),
	('H5', 138, 18, 3),
	('H5', 139, 19, 0),
	('H5', 140, 19, 1),
	('H5', 141, 19, 2),
	('H5', 142, 19, 3),
	('H5', 143, 19, 4),
	('H5', 144, 20, 0),
	('H5', 145, 20, 1),
	('H5', 146, 20, 2),
	('H5', 147, 20, 3),
	('H5', 148, 20, 4),
	('H5', 149, 20, 5),
	('H5', 150, 21, 0),
	('H5', 151, 21, 1),
	('H5', 152, 21, 2),
	('H5', 153, 21, 3),
	('H5', 154, 21, 4),
	('H5', 155, 21, 5),
	('H5', 156, 21, 6),
	('H5', 157, 21, 7),
	('H5', 158, 21, 8),
	('H5', 159, 21, 9),
	('H5', 160, 22, 0),
	('H5', 161, 22, 1),
	('H5', 162, 22, 2),
	('H5', 163, 22, 3),
	('H5', 164, 22, 4),
	('H5', 165, 22, 5),
	('H5', 166, 23, 0),
	('H5', 167, 23, 1),
	('H5', 168, 23, 2),
	('H5', 169, 23, 3),
	('H5', 170, 24, 0),
	('H5', 171, 24, 1),
	('H5', 172, 24, 2),
	('H5', 173, 25, 0),
	('H5', 174, 25, 1),
	('H5', 175, 25, 2),
	('H5', 176, 25, 3),
	('H5', 177, 25, 4),
	('H5', 178, 25, 5),
	('H5', 179, 26, 0),
	('H5', 180, 26, 1),
	('H5', 181, 26, 2),
	('H5', 182, 27, 0),
	('H5', 183, 27, 1),
	('H5', 184, 27, 2),
	('H5', 185, 27, 3),
	('H5', 186, 28, 0),
	('H5', 187, 28, 1),
	('H5', 188, 28, 2),
	('H5', 189, 28, 3),
	('H5', 190, 29, 0),
	('H5', 191, 29, 1),
	('H5', 192, 29, 2),
	('H5', 193, 29, 3),
	('H5', 194, 30, 0),
	('H5', 195, 30, 1),
	('H5', 196, 30, 2),
	('H5', 197, 30, 3),
	('H5', 198, 30, 4),
	('H5', 199, 30, 5),
	('H5', 200, 30, 6),
	('H5', 201, 30, 7),
	('H5', 202, 30, 8),
	('H5', 203, 30, 9),
	('H5', 204, 30, 10),
	('H5', 205, 30, 11),
	('H5', 206, 30, 12),
	('H5', 207, 30, 13),
	('H5', 208, 30, 14),
	('H5', 209, 30, 15),
	('H5', 210, 31, 1),
	('H5', 211, 31, 2),
	('H5', 212, 31, 3),
	('H5', 213, 31, 4),
	('H5', 214, 32, 1),
	('H5', 215, 32, 2),
	('H5', 216, 32, 3),
	('H5', 217, 32, 4),
	('H5', 218, 32, 5),
	('H5', 219, 32, 6),
	('H5', 220, 33, 1),
	('H5', 221, 33, 2),
	('H5', 222, 33, 3),
	('H5', 223, 34, 1),
	('H5', 224, 34, 2),
	('H5', 225, 34, 3),
	('H5', 226, 35, 1),
	('H5', 227, 35, 2),
	('H5', 228, 35, 3),
	('H5', 229, 36, 1),
	('H5', 230, 36, 2),
	('H5', 231, 36, 3),
	('H5', 232, 36, 4),
	('H5', 233, 36, 5),
	('H5', 234, 36, 6),
	('H5', 235, 37, 1),
	('H5', 236, 37, 2),
	('H5', 237, 38, 1),
	('H5', 238, 38, 2),
	('H5', 239, 38, 3),
	('H5', 240, 38, 4),
	('H5', 241, 39, 1),
	('H5', 242, 39, 2),
	('H5', 243, 39, 3),
	('H5', 244, 39, 4),
	('H5', 245, 40, 1),
	('H5', 246, 40, 2),
	('H5', 247, 40, 3),
	('H5', 248, 40, 4),
	('H5', 249, 41, 1),
	('H5', 250, 41, 2),
	('H5', 251, 41, 3),
	('H5', 252, 41, 4),
	('H5', 253, 41, 5),
	('H5', 254, 41, 6),
	('H5', 255, 41, 7),
	('H5', 256, 41, 8),
	('H5', 257, 41, 9),
	('H5', 258, 41, 10),
	('H5', 259, 41, 11),
	('H5', 260, 42, 1),
	('H5', 261, 42, 2),
	('H5', 262, 42, 3),
	('H5', 263, 42, 4),
	('H5', 264, 42, 5),
	('H5', 265, 42, 6),
	('H5', 266, 42, 7),
	('H5', 267, 42, 8),
	('H5', 268, 42, 9),
	('H5', 269, 43, 1),
	('H5', 270, 43, 2),
	('H5', 271, 43, 3),
	('H5', 272, 43, 4),
	('H5', 273, 44, 1),
	('H5', 274, 44, 2),
	('H5', 275, 44, 3),
	('H5', 276, 44, 4),
	('H5', 277, 45, 1),
	('H5', 278, 45, 2),
	('H5', 279, 45, 3),
	('H5', 280, 45, 4),
	('H5', 281, 45, 5),
	('H5', 282, 45, 6),
	('H5', 283, 45, 7),
	('H5', 284, 45, 8),
	('H5', 285, 45, 9),
	('H5', 286, 45, 10),
	('H5', 287, 45, 11),
	('H5', 288, 12, 1),
	('H5', 289, 46, 1),
	('H5', 290, 46, 2),
	('H5', 291, 46, 3),
	('H5', 292, 46, 4),
	('H5', 293, 46, 5),
	('H5', 294, 46, 6),
	('H5', 295, 2, 1),
	('H5', 296, 47, 1),
	('H5', 297, 47, 2),
	('H5', 298, 47, 3),
	('H5', 299, 48, 1),
	('H5', 300, 48, 2),
	('H6', 1, 1, 0),
	('H6', 2, 1, 1),
	('H6', 3, 1, 2),
	('H6', 4, 1, 3),
	('H6', 5, 1, 4),
	('H6', 6, 1, 5),
	('H6', 7, 1, 6),
	('H6', 8, 1, 7),
	('H6', 9, 1, 8),
	('H6', 10, 1, 9),
	('H6', 11, 1, 10),
	('H6', 12, 2, 0),
	('H6', 13, 2, 1),
	('H6', 14, 3, 0),
	('H6', 15, 3, 1),
	('H6', 16, 3, 2),
	('H6', 17, 3, 3),
	('H6', 18, 3, 4),
	('H6', 19, 3, 5),
	('H6', 20, 4, 0),
	('H6', 21, 4, 1),
	('H6', 22, 4, 2),
	('H6', 23, 4, 3),
	('H6', 24, 4, 4),
	('H6', 25, 4, 5),
	('H6', 26, 4, 6),
	('H6', 27, 4, 7),
	('H6', 28, 4, 8),
	('H6', 29, 4, 9),
	('H6', 30, 4, 10),
	('H6', 31, 4, 11),
	('H6', 32, 4, 12),
	('H6', 33, 5, 0),
	('H6', 34, 5, 1),
	('H6', 35, 5, 2),
	('H6', 36, 5, 3),
	('H6', 37, 5, 4),
	('H6', 38, 6, 0),
	('H6', 39, 6, 1),
	('H6', 40, 6, 2),
	('H6', 41, 7, 0),
	('H6', 42, 7, 1),
	('H6', 43, 7, 2),
	('H6', 44, 7, 3),
	('H6', 45, 7, 4),
	('H6', 46, 8, 0),
	('H6', 47, 9, 0),
	('H6', 48, 9, 1),
	('H6', 49, 9, 2),
	('H6', 50, 9, 3),
	('H6', 51, 10, 0),
	('H6', 52, 10, 1),
	('H6', 53, 10, 2),
	('H6', 54, 10, 3),
	('H6', 55, 10, 4),
	('H6', 56, 10, 5),
	('H6', 57, 10, 6),
	('H6', 58, 10, 7),
	('H6', 59, 10, 8),
	('H6', 60, 11, 0),
	('H6', 61, 11, 1),
	('H6', 62, 11, 2),
	('H6', 63, 11, 3),
	('H6', 64, 11, 4),
	('H6', 65, 11, 5),
	('H6', 66, 11, 6),
	('H6', 67, 11, 7),
	('H6', 68, 11, 8),
	('H6', 69, 11, 9),
	('H6', 70, 11, 10),
	('H6', 71, 11, 11),
	('H6', 72, 11, 12),
	('H6', 73, 11, 13),
	('H6', 74, 11, 14),
	('H6', 75, 11, 15),
	('H6', 76, 11, 16),
	('H6', 77, 11, 17),
	('H6', 78, 11, 18),
	('H6', 79, 11, 19),
	('H6', 80, 11, 20),
	('H6', 81, 11, 21),
	('H6', 82, 11, 22),
	('H6', 83, 11, 23),
	('H6', 84, 11, 24),
	('H6', 85, 11, 25),
	('H6', 86, 11, 26),
	('H6', 87, 11, 27),
	('H6', 88, 11, 28),
	('H6', 89, 11, 29),
	('H6', 90, 11, 30),
	('H6', 91, 11, 31),
	('H6', 92, 12, 0),
	('H6', 93, 12, 1),
	('H6', 94, 12, 2),
	('H6', 95, 12, 3),
	('H6', 96, 12, 4),
	('H6', 97, 12, 5),
	('H6', 98, 13, 0),
	('H6', 99, 13, 1),
	('H6', 100, 13, 2),
	('H6', 101, 13, 3),
	('H6', 102, 13, 4),
	('H6', 103, 13, 5),
	('H6', 104, 13, 6),
	('H6', 105, 13, 7),
	('H6', 106, 13, 8),
	('H6', 107, 13, 9),
	('H6', 108, 13, 10),
	('H6', 109, 13, 11),
	('H6', 110, 13, 12),
	('H6', 111, 14, 0),
	('H6', 112, 14, 1),
	('H6', 113, 14, 2),
	('H6', 114, 14, 3),
	('H6', 115, 14, 4),
	('H6', 116, 14, 5),
	('H6', 117, 14, 6),
	('H6', 118, 14, 7),
	('H6', 119, 14, 8),
	('H6', 120, 14, 9),
	('H6', 121, 14, 10),
	('H6', 122, 14, 11),
	('H6', 123, 15, 0),
	('H6', 124, 15, 1),
	('H6', 125, 15, 2),
	('H6', 126, 15, 3),
	('H6', 127, 16, 0),
	('H6', 128, 16, 1),
	('H6', 129, 16, 2),
	('H6', 130, 17, 0),
	('H6', 131, 17, 1),
	('H6', 132, 17, 2),
	('H6', 133, 17, 3),
	('H6', 134, 17, 4),
	('H6', 135, 18, 0),
	('H6', 136, 18, 1),
	('H6', 137, 18, 2),
	('H6', 138, 18, 3),
	('H6', 139, 19, 0),
	('H6', 140, 19, 1),
	('H6', 141, 19, 2),
	('H6', 142, 19, 3),
	('H6', 143, 19, 4),
	('H6', 144, 20, 0),
	('H6', 145, 20, 1),
	('H6', 146, 20, 2),
	('H6', 147, 20, 3),
	('H6', 148, 20, 4),
	('H6', 149, 20, 5),
	('H6', 150, 21, 0),
	('H6', 151, 21, 1),
	('H6', 152, 21, 2),
	('H6', 153, 21, 3),
	('H6', 154, 21, 4),
	('H6', 155, 21, 5),
	('H6', 156, 21, 6),
	('H6', 157, 21, 7),
	('H6', 158, 21, 8),
	('H6', 159, 21, 9),
	('H6', 160, 22, 0),
	('H6', 161, 22, 1),
	('H6', 162, 22, 2),
	('H6', 163, 22, 3),
	('H6', 164, 22, 4),
	('H6', 165, 22, 5),
	('H6', 166, 23, 0),
	('H6', 167, 23, 1),
	('H6', 168, 23, 2),
	('H6', 169, 23, 3),
	('H6', 170, 24, 0),
	('H6', 171, 24, 1),
	('H6', 172, 24, 2),
	('H6', 173, 25, 0),
	('H6', 174, 25, 1),
	('H6', 175, 25, 2),
	('H6', 176, 25, 3),
	('H6', 177, 25, 4),
	('H6', 178, 25, 5),
	('H6', 179, 26, 0),
	('H6', 180, 26, 1),
	('H6', 181, 26, 2),
	('H6', 182, 27, 0),
	('H6', 183, 27, 1),
	('H6', 184, 27, 2),
	('H6', 185, 27, 3),
	('H6', 186, 28, 0),
	('H6', 187, 28, 1),
	('H6', 188, 28, 2),
	('H6', 189, 28, 3),
	('H6', 190, 29, 0),
	('H6', 191, 29, 1),
	('H6', 192, 29, 2),
	('H6', 193, 29, 3),
	('H6', 194, 30, 0),
	('H6', 195, 30, 1),
	('H6', 196, 30, 2),
	('H6', 197, 30, 3),
	('H6', 198, 30, 4),
	('H6', 199, 30, 5),
	('H6', 200, 30, 6),
	('H6', 201, 30, 7),
	('H6', 202, 30, 8),
	('H6', 203, 30, 9),
	('H6', 204, 30, 10),
	('H6', 205, 30, 11),
	('H6', 206, 30, 12),
	('H6', 207, 30, 13),
	('H6', 208, 30, 14),
	('H6', 209, 30, 15),
	('H6', 210, 31, 1),
	('H6', 211, 31, 2),
	('H6', 212, 31, 3),
	('H6', 213, 31, 4),
	('H6', 214, 32, 1),
	('H6', 215, 32, 2),
	('H6', 216, 32, 3),
	('H6', 217, 32, 4),
	('H6', 218, 32, 5),
	('H6', 219, 32, 6),
	('H6', 220, 33, 1),
	('H6', 221, 33, 2),
	('H6', 222, 33, 3),
	('H6', 223, 34, 1),
	('H6', 224, 34, 2),
	('H6', 225, 34, 3),
	('H6', 226, 35, 1),
	('H6', 227, 35, 2),
	('H6', 228, 35, 3),
	('H6', 229, 36, 1),
	('H6', 230, 36, 2),
	('H6', 231, 36, 3),
	('H6', 232, 36, 4),
	('H6', 233, 36, 5),
	('H6', 234, 36, 6),
	('H6', 235, 37, 1),
	('H6', 236, 37, 2),
	('H6', 237, 38, 1),
	('H6', 238, 38, 2),
	('H6', 239, 38, 3),
	('H6', 240, 38, 4),
	('H6', 241, 39, 1),
	('H6', 242, 39, 2),
	('H6', 243, 39, 3),
	('H6', 244, 39, 4),
	('H6', 245, 40, 1),
	('H6', 246, 40, 2),
	('H6', 247, 40, 3),
	('H6', 248, 40, 4),
	('H6', 249, 41, 1),
	('H6', 250, 41, 2),
	('H6', 251, 41, 3),
	('H6', 252, 41, 4),
	('H6', 253, 41, 5),
	('H6', 254, 41, 6),
	('H6', 255, 41, 7),
	('H6', 256, 41, 8),
	('H6', 257, 41, 9),
	('H6', 258, 41, 10),
	('H6', 259, 41, 11),
	('H6', 260, 42, 1),
	('H6', 261, 42, 2),
	('H6', 262, 42, 3),
	('H6', 263, 42, 4),
	('H6', 264, 42, 5),
	('H6', 265, 42, 6),
	('H6', 266, 42, 7),
	('H6', 267, 42, 8),
	('H6', 268, 42, 9),
	('H6', 269, 43, 1),
	('H6', 270, 43, 2),
	('H6', 271, 43, 3),
	('H6', 272, 43, 4),
	('H6', 273, 44, 1),
	('H6', 274, 44, 2),
	('H6', 275, 44, 3),
	('H6', 276, 44, 4),
	('H6', 277, 45, 1),
	('H6', 278, 45, 2),
	('H6', 279, 45, 3),
	('H6', 280, 45, 4),
	('H6', 281, 45, 5),
	('H6', 282, 45, 6),
	('H6', 283, 45, 7),
	('H6', 284, 45, 8),
	('H6', 285, 45, 9),
	('H6', 286, 45, 10),
	('H6', 287, 45, 11),
	('H6', 288, 12, 1),
	('H6', 289, 46, 1),
	('H6', 290, 46, 2),
	('H6', 291, 46, 3),
	('H6', 292, 46, 4),
	('H6', 293, 46, 5),
	('H6', 294, 46, 6),
	('H6', 295, 2, 1),
	('H6', 296, 47, 1),
	('H6', 297, 47, 2),
	('H6', 298, 47, 3),
	('H6', 299, 48, 1),
	('H6', 300, 48, 2);
/*!40000 ALTER TABLE `ncrm_role2picklist` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_role2profile
CREATE TABLE IF NOT EXISTS `ncrm_role2profile` (
  `roleid` varchar(255) NOT NULL,
  `profileid` int(11) NOT NULL,
  PRIMARY KEY (`roleid`,`profileid`),
  KEY `role2profile_roleid_profileid_idx` (`roleid`,`profileid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_role2profile: ~4 rows (approximately)
/*!40000 ALTER TABLE `ncrm_role2profile` DISABLE KEYS */;
INSERT INTO `ncrm_role2profile` (`roleid`, `profileid`) VALUES
	('H2', 1),
	('H3', 2),
	('H4', 2),
	('H5', 2),
	('H6', 6);
/*!40000 ALTER TABLE `ncrm_role2profile` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_role_seq
CREATE TABLE IF NOT EXISTS `ncrm_role_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_role_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_role_seq` DISABLE KEYS */;
INSERT INTO `ncrm_role_seq` (`id`) VALUES
	(6);
/*!40000 ALTER TABLE `ncrm_role_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_rowheight
CREATE TABLE IF NOT EXISTS `ncrm_rowheight` (
  `rowheightid` int(11) NOT NULL AUTO_INCREMENT,
  `rowheight` varchar(200) NOT NULL,
  `sortorderid` int(11) DEFAULT NULL,
  `presence` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`rowheightid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_rowheight: ~3 rows (approximately)
/*!40000 ALTER TABLE `ncrm_rowheight` DISABLE KEYS */;
INSERT INTO `ncrm_rowheight` (`rowheightid`, `rowheight`, `sortorderid`, `presence`) VALUES
	(1, 'wide', 1, 1),
	(2, 'medium', 2, 1),
	(3, 'narrow', 3, 1);
/*!40000 ALTER TABLE `ncrm_rowheight` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_rowheight_seq
CREATE TABLE IF NOT EXISTS `ncrm_rowheight_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_rowheight_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_rowheight_seq` DISABLE KEYS */;
INSERT INTO `ncrm_rowheight_seq` (`id`) VALUES
	(3);
/*!40000 ALTER TABLE `ncrm_rowheight_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_rss
CREATE TABLE IF NOT EXISTS `ncrm_rss` (
  `rssid` int(19) NOT NULL,
  `rssurl` varchar(200) NOT NULL DEFAULT '',
  `rsstitle` varchar(200) DEFAULT NULL,
  `rsstype` int(10) DEFAULT '0',
  `starred` int(1) DEFAULT '0',
  PRIMARY KEY (`rssid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_rss: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_rss` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_rss` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_salesmanactivityrel
CREATE TABLE IF NOT EXISTS `ncrm_salesmanactivityrel` (
  `smid` int(19) NOT NULL DEFAULT '0',
  `activityid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`smid`,`activityid`),
  KEY `salesmanactivityrel_activityid_idx` (`activityid`),
  KEY `salesmanactivityrel_smid_idx` (`smid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_salesmanactivityrel: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_salesmanactivityrel` DISABLE KEYS */;
INSERT INTO `ncrm_salesmanactivityrel` (`smid`, `activityid`) VALUES
	(1, 20);
/*!40000 ALTER TABLE `ncrm_salesmanactivityrel` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_salesmanattachmentsrel
CREATE TABLE IF NOT EXISTS `ncrm_salesmanattachmentsrel` (
  `smid` int(19) NOT NULL DEFAULT '0',
  `attachmentsid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`smid`,`attachmentsid`),
  KEY `salesmanattachmentsrel_smid_idx` (`smid`),
  KEY `salesmanattachmentsrel_attachmentsid_idx` (`attachmentsid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_salesmanattachmentsrel: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_salesmanattachmentsrel` DISABLE KEYS */;
INSERT INTO `ncrm_salesmanattachmentsrel` (`smid`, `attachmentsid`) VALUES
	(1, 21),
	(6, 30);
/*!40000 ALTER TABLE `ncrm_salesmanattachmentsrel` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_salesmanticketrel
CREATE TABLE IF NOT EXISTS `ncrm_salesmanticketrel` (
  `smid` int(19) NOT NULL DEFAULT '0',
  `id` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`smid`,`id`),
  KEY `salesmanticketrel_smid_idx` (`smid`),
  KEY `salesmanticketrel_id_idx` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_salesmanticketrel: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_salesmanticketrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_salesmanticketrel` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_salesorder
CREATE TABLE IF NOT EXISTS `ncrm_salesorder` (
  `salesorderid` int(19) NOT NULL DEFAULT '0',
  `subject` varchar(100) DEFAULT NULL,
  `potentialid` int(19) DEFAULT NULL,
  `customerno` varchar(100) DEFAULT NULL,
  `salesorder_no` varchar(100) DEFAULT NULL,
  `quoteid` int(19) DEFAULT NULL,
  `vendorterms` varchar(100) DEFAULT NULL,
  `contactid` int(19) DEFAULT NULL,
  `vendorid` int(19) DEFAULT NULL,
  `duedate` date DEFAULT NULL,
  `carrier` varchar(200) DEFAULT NULL,
  `pending` varchar(200) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `adjustment` decimal(25,8) DEFAULT NULL,
  `salescommission` decimal(25,3) DEFAULT NULL,
  `exciseduty` decimal(25,3) DEFAULT NULL,
  `total` decimal(25,8) DEFAULT NULL,
  `subtotal` decimal(25,8) DEFAULT NULL,
  `taxtype` varchar(25) DEFAULT NULL,
  `discount_percent` decimal(25,3) DEFAULT NULL,
  `discount_amount` decimal(25,8) DEFAULT NULL,
  `s_h_amount` decimal(25,8) DEFAULT NULL,
  `accountid` int(19) DEFAULT NULL,
  `terms_conditions` text,
  `purchaseorder` varchar(200) DEFAULT NULL,
  `sostatus` varchar(200) DEFAULT NULL,
  `currency_id` int(19) NOT NULL DEFAULT '1',
  `conversion_rate` decimal(10,3) NOT NULL DEFAULT '1.000',
  `enable_recurring` int(11) DEFAULT '0',
  `pre_tax_total` decimal(25,8) DEFAULT NULL,
  `s_h_percent` int(11) DEFAULT NULL,
  PRIMARY KEY (`salesorderid`),
  KEY `salesorder_vendorid_idx` (`vendorid`),
  KEY `salesorder_contactid_idx` (`contactid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_salesorder: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_salesorder` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_salesorder` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_salesordercf
CREATE TABLE IF NOT EXISTS `ncrm_salesordercf` (
  `salesorderid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`salesorderid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_salesordercf: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_salesordercf` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_salesordercf` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_sales_stage
CREATE TABLE IF NOT EXISTS `ncrm_sales_stage` (
  `sales_stage_id` int(19) NOT NULL AUTO_INCREMENT,
  `sales_stage` varchar(200) DEFAULT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  PRIMARY KEY (`sales_stage_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_sales_stage: ~10 rows (approximately)
/*!40000 ALTER TABLE `ncrm_sales_stage` DISABLE KEYS */;
INSERT INTO `ncrm_sales_stage` (`sales_stage_id`, `sales_stage`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(1, 'Prospecting', 1, 150, 0),
	(2, 'Qualification', 1, 151, 1),
	(3, 'Needs Analysis', 1, 152, 2),
	(4, 'Value Proposition', 1, 153, 3),
	(5, 'Id. Decision Makers', 1, 154, 4),
	(6, 'Perception Analysis', 1, 155, 5),
	(7, 'Proposal or Price Quote', 1, 156, 6),
	(8, 'Negotiation or Review', 1, 157, 7),
	(9, 'Closed Won', 0, 158, 8),
	(10, 'Closed Lost', 0, 159, 9);
/*!40000 ALTER TABLE `ncrm_sales_stage` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_sales_stage_seq
CREATE TABLE IF NOT EXISTS `ncrm_sales_stage_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_sales_stage_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_sales_stage_seq` DISABLE KEYS */;
INSERT INTO `ncrm_sales_stage_seq` (`id`) VALUES
	(10);
/*!40000 ALTER TABLE `ncrm_sales_stage_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_salutationtype
CREATE TABLE IF NOT EXISTS `ncrm_salutationtype` (
  `salutationid` int(19) NOT NULL AUTO_INCREMENT,
  `salutationtype` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  PRIMARY KEY (`salutationid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_salutationtype: ~5 rows (approximately)
/*!40000 ALTER TABLE `ncrm_salutationtype` DISABLE KEYS */;
INSERT INTO `ncrm_salutationtype` (`salutationid`, `salutationtype`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(2, 'Mr.', 1, 161, 1),
	(3, 'Ms.', 1, 162, 2),
	(4, 'Mrs.', 1, 163, 3),
	(5, 'Dr.', 1, 164, 4),
	(6, 'Prof.', 1, 165, 5);
/*!40000 ALTER TABLE `ncrm_salutationtype` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_salutationtype_seq
CREATE TABLE IF NOT EXISTS `ncrm_salutationtype_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_salutationtype_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_salutationtype_seq` DISABLE KEYS */;
INSERT INTO `ncrm_salutationtype_seq` (`id`) VALUES
	(6);
/*!40000 ALTER TABLE `ncrm_salutationtype_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_scheduled_reports
CREATE TABLE IF NOT EXISTS `ncrm_scheduled_reports` (
  `reportid` int(11) NOT NULL,
  `recipients` text,
  `schedule` text,
  `format` varchar(10) DEFAULT NULL,
  `next_trigger_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`reportid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_scheduled_reports: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_scheduled_reports` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_scheduled_reports` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_schedulereports
CREATE TABLE IF NOT EXISTS `ncrm_schedulereports` (
  `reportid` int(10) DEFAULT NULL,
  `scheduleid` int(3) DEFAULT NULL,
  `recipients` text,
  `schdate` varchar(20) DEFAULT NULL,
  `schtime` time DEFAULT NULL,
  `schdayoftheweek` varchar(100) DEFAULT NULL,
  `schdayofthemonth` varchar(100) DEFAULT NULL,
  `schannualdates` varchar(500) DEFAULT NULL,
  `specificemails` varchar(500) DEFAULT NULL,
  `next_trigger_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_schedulereports: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_schedulereports` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_schedulereports` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_seactivityrel
CREATE TABLE IF NOT EXISTS `ncrm_seactivityrel` (
  `crmid` int(19) NOT NULL,
  `activityid` int(19) NOT NULL,
  PRIMARY KEY (`crmid`,`activityid`),
  KEY `seactivityrel_activityid_idx` (`activityid`),
  KEY `seactivityrel_crmid_idx` (`crmid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_seactivityrel: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_seactivityrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_seactivityrel` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_seactivityrel_seq
CREATE TABLE IF NOT EXISTS `ncrm_seactivityrel_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_seactivityrel_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_seactivityrel_seq` DISABLE KEYS */;
INSERT INTO `ncrm_seactivityrel_seq` (`id`) VALUES
	(1);
/*!40000 ALTER TABLE `ncrm_seactivityrel_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_seattachmentsrel
CREATE TABLE IF NOT EXISTS `ncrm_seattachmentsrel` (
  `crmid` int(19) NOT NULL DEFAULT '0',
  `attachmentsid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`crmid`,`attachmentsid`),
  KEY `seattachmentsrel_attachmentsid_idx` (`attachmentsid`),
  KEY `seattachmentsrel_crmid_idx` (`crmid`),
  KEY `seattachmentsrel_attachmentsid_crmid_idx` (`attachmentsid`,`crmid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_seattachmentsrel: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_seattachmentsrel` DISABLE KEYS */;
INSERT INTO `ncrm_seattachmentsrel` (`crmid`, `attachmentsid`) VALUES
	(26, 27);
/*!40000 ALTER TABLE `ncrm_seattachmentsrel` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_selectcolumn
CREATE TABLE IF NOT EXISTS `ncrm_selectcolumn` (
  `queryid` int(19) NOT NULL,
  `columnindex` int(11) NOT NULL DEFAULT '0',
  `columnname` varchar(250) DEFAULT '',
  PRIMARY KEY (`queryid`,`columnindex`),
  KEY `selectcolumn_queryid_idx` (`queryid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_selectcolumn: ~154 rows (approximately)
/*!40000 ALTER TABLE `ncrm_selectcolumn` DISABLE KEYS */;
INSERT INTO `ncrm_selectcolumn` (`queryid`, `columnindex`, `columnname`) VALUES
	(1, 0, 'ncrm_contactdetails:firstname:Contacts_First_Name:firstname:V'),
	(1, 1, 'ncrm_contactdetails:lastname:Contacts_Last_Name:lastname:V'),
	(1, 2, 'ncrm_contactsubdetails:leadsource:Contacts_Lead_Source:leadsource:V'),
	(1, 3, 'ncrm_contactdetails:accountid:Contacts_Account_Name:account_id:V'),
	(1, 4, 'ncrm_account:industry:Accounts_industry:industry:V'),
	(1, 5, 'ncrm_contactdetails:email:Contacts_Email:email:E'),
	(2, 0, 'ncrm_contactdetails:firstname:Contacts_First_Name:firstname:V'),
	(2, 1, 'ncrm_contactdetails:lastname:Contacts_Last_Name:lastname:V'),
	(2, 2, 'ncrm_contactsubdetails:leadsource:Contacts_Lead_Source:leadsource:V'),
	(2, 3, 'ncrm_contactdetails:accountid:Contacts_Account_Name:account_id:V'),
	(2, 4, 'ncrm_account:industry:Accounts_industry:industry:V'),
	(2, 5, 'ncrm_contactdetails:email:Contacts_Email:email:E'),
	(3, 0, 'ncrm_contactdetails:firstname:Contacts_First_Name:firstname:V'),
	(3, 1, 'ncrm_contactdetails:lastname:Contacts_Last_Name:lastname:V'),
	(3, 2, 'ncrm_contactdetails:accountid:Contacts_Account_Name:account_id:V'),
	(3, 3, 'ncrm_contactdetails:email:Contacts_Email:email:E'),
	(3, 4, 'ncrm_potential:potentialname:Potentials_Potential_Name:potentialname:V'),
	(3, 5, 'ncrm_potential:sales_stage:Potentials_Sales_Stage:sales_stage:V'),
	(4, 0, 'ncrm_leaddetails:firstname:Leads_First_Name:firstname:V'),
	(4, 1, 'ncrm_leaddetails:lastname:Leads_Last_Name:lastname:V'),
	(4, 2, 'ncrm_leaddetails:company:Leads_Company:company:V'),
	(4, 3, 'ncrm_leaddetails:email:Leads_Email:email:E'),
	(4, 4, 'ncrm_leaddetails:leadsource:Leads_Lead_Source:leadsource:V'),
	(5, 0, 'ncrm_leaddetails:firstname:Leads_First_Name:firstname:V'),
	(5, 1, 'ncrm_leaddetails:lastname:Leads_Last_Name:lastname:V'),
	(5, 2, 'ncrm_leaddetails:company:Leads_Company:company:V'),
	(5, 3, 'ncrm_leaddetails:email:Leads_Email:email:E'),
	(5, 4, 'ncrm_leaddetails:leadsource:Leads_Lead_Source:leadsource:V'),
	(5, 5, 'ncrm_leaddetails:leadstatus:Leads_Lead_Status:leadstatus:V'),
	(6, 0, 'ncrm_potential:potentialname:Potentials_Potential_Name:potentialname:V'),
	(6, 1, 'ncrm_potential:amount:Potentials_Amount:amount:N'),
	(6, 2, 'ncrm_potential:potentialtype:Potentials_Type:opportunity_type:V'),
	(6, 3, 'ncrm_potential:leadsource:Potentials_Lead_Source:leadsource:V'),
	(6, 4, 'ncrm_potential:sales_stage:Potentials_Sales_Stage:sales_stage:V'),
	(7, 0, 'ncrm_potential:potentialname:Potentials_Potential_Name:potentialname:V'),
	(7, 1, 'ncrm_potential:amount:Potentials_Amount:amount:N'),
	(7, 2, 'ncrm_potential:potentialtype:Potentials_Type:opportunity_type:V'),
	(7, 3, 'ncrm_potential:leadsource:Potentials_Lead_Source:leadsource:V'),
	(7, 4, 'ncrm_potential:sales_stage:Potentials_Sales_Stage:sales_stage:V'),
	(8, 0, 'ncrm_activity:subject:Calendar_Subject:subject:V'),
	(8, 1, 'ncrm_contactdetailsCalendar:lastname:Calendar_Contact_Name:contact_id:I'),
	(8, 2, 'ncrm_activity:status:Calendar_Status:taskstatus:V'),
	(8, 3, 'ncrm_activity:priority:Calendar_Priority:taskpriority:V'),
	(8, 4, 'ncrm_usersCalendar:user_name:Calendar_Assigned_To:assigned_user_id:V'),
	(9, 0, 'ncrm_activity:subject:Calendar_Subject:subject:V'),
	(9, 1, 'ncrm_contactdetailsCalendar:lastname:Calendar_Contact_Name:contact_id:I'),
	(9, 2, 'ncrm_activity:status:Calendar_Status:taskstatus:V'),
	(9, 3, 'ncrm_activity:priority:Calendar_Priority:taskpriority:V'),
	(9, 4, 'ncrm_usersCalendar:user_name:Calendar_Assigned_To:assigned_user_id:V'),
	(10, 0, 'ncrm_troubletickets:title:HelpDesk_Title:ticket_title:V'),
	(10, 1, 'ncrm_troubletickets:status:HelpDesk_Status:ticketstatus:V'),
	(10, 2, 'ncrm_products:productname:Products_Product_Name:productname:V'),
	(10, 3, 'ncrm_products:discontinued:Products_Product_Active:discontinued:V'),
	(10, 4, 'ncrm_products:productcategory:Products_Product_Category:productcategory:V'),
	(10, 5, 'ncrm_products:manufacturer:Products_Manufacturer:manufacturer:V'),
	(11, 0, 'ncrm_troubletickets:title:HelpDesk_Title:ticket_title:V'),
	(11, 1, 'ncrm_troubletickets:priority:HelpDesk_Priority:ticketpriorities:V'),
	(11, 2, 'ncrm_troubletickets:severity:HelpDesk_Severity:ticketseverities:V'),
	(11, 3, 'ncrm_troubletickets:status:HelpDesk_Status:ticketstatus:V'),
	(11, 4, 'ncrm_troubletickets:category:HelpDesk_Category:ticketcategories:V'),
	(11, 5, 'ncrm_usersHelpDesk:user_name:HelpDesk_Assigned_To:assigned_user_id:V'),
	(12, 0, 'ncrm_troubletickets:title:HelpDesk_Title:ticket_title:V'),
	(12, 1, 'ncrm_troubletickets:priority:HelpDesk_Priority:ticketpriorities:V'),
	(12, 2, 'ncrm_troubletickets:severity:HelpDesk_Severity:ticketseverities:V'),
	(12, 3, 'ncrm_troubletickets:status:HelpDesk_Status:ticketstatus:V'),
	(12, 4, 'ncrm_troubletickets:category:HelpDesk_Category:ticketcategories:V'),
	(12, 5, 'ncrm_usersHelpDesk:user_name:HelpDesk_Assigned_To:assigned_user_id:V'),
	(13, 0, 'ncrm_products:productname:Products_Product_Name:productname:V'),
	(13, 1, 'ncrm_products:productcode:Products_Product_Code:productcode:V'),
	(13, 2, 'ncrm_products:discontinued:Products_Product_Active:discontinued:V'),
	(13, 3, 'ncrm_products:productcategory:Products_Product_Category:productcategory:V'),
	(13, 4, 'ncrm_products:website:Products_Website:website:V'),
	(13, 5, 'ncrm_vendorRelProducts:vendorname:Products_Vendor_Name:vendor_id:I'),
	(13, 6, 'ncrm_products:mfr_part_no:Products_Mfr_PartNo:mfr_part_no:V'),
	(14, 0, 'ncrm_products:productname:Products_Product_Name:productname:V'),
	(14, 1, 'ncrm_products:manufacturer:Products_Manufacturer:manufacturer:V'),
	(14, 2, 'ncrm_products:productcategory:Products_Product_Category:productcategory:V'),
	(14, 3, 'ncrm_contactdetails:firstname:Contacts_First_Name:firstname:V'),
	(14, 4, 'ncrm_contactdetails:lastname:Contacts_Last_Name:lastname:V'),
	(14, 5, 'ncrm_contactsubdetails:leadsource:Contacts_Lead_Source:leadsource:V'),
	(15, 0, 'ncrm_quotes:subject:Quotes_Subject:subject:V'),
	(15, 1, 'ncrm_potentialRelQuotes:potentialname:Quotes_Potential_Name:potential_id:I'),
	(15, 2, 'ncrm_quotes:quotestage:Quotes_Quote_Stage:quotestage:V'),
	(15, 3, 'ncrm_quotes:contactid:Quotes_Contact_Name:contact_id:V'),
	(15, 4, 'ncrm_usersRel1:user_name:Quotes_Inventory_Manager:assigned_user_id1:I'),
	(15, 5, 'ncrm_accountQuotes:accountname:Quotes_Account_Name:account_id:I'),
	(16, 0, 'ncrm_quotes:subject:Quotes_Subject:subject:V'),
	(16, 1, 'ncrm_potentialRelQuotes:potentialname:Quotes_Potential_Name:potential_id:I'),
	(16, 2, 'ncrm_quotes:quotestage:Quotes_Quote_Stage:quotestage:V'),
	(16, 3, 'ncrm_quotes:contactid:Quotes_Contact_Name:contact_id:V'),
	(16, 4, 'ncrm_usersRel1:user_name:Quotes_Inventory_Manager:assigned_user_id1:I'),
	(16, 5, 'ncrm_accountQuotes:accountname:Quotes_Account_Name:account_id:I'),
	(16, 6, 'ncrm_quotes:carrier:Quotes_Carrier:carrier:V'),
	(16, 7, 'ncrm_quotes:shipping:Quotes_Shipping:shipping:V'),
	(17, 0, 'ncrm_purchaseorder:subject:PurchaseOrder_Subject:subject:V'),
	(17, 1, 'ncrm_vendorRelPurchaseOrder:vendorname:PurchaseOrder_Vendor_Name:vendor_id:I'),
	(17, 2, 'ncrm_purchaseorder:tracking_no:PurchaseOrder_Tracking_Number:tracking_no:V'),
	(17, 3, 'ncrm_contactdetails:firstname:Contacts_First_Name:firstname:V'),
	(17, 4, 'ncrm_contactdetails:lastname:Contacts_Last_Name:lastname:V'),
	(17, 5, 'ncrm_contactsubdetails:leadsource:Contacts_Lead_Source:leadsource:V'),
	(17, 6, 'ncrm_contactdetails:email:Contacts_Email:email:E'),
	(18, 0, 'ncrm_purchaseorder:subject:PurchaseOrder_Subject:subject:V'),
	(18, 1, 'ncrm_vendorRelPurchaseOrder:vendorname:PurchaseOrder_Vendor_Name:vendor_id:I'),
	(18, 2, 'ncrm_purchaseorder:requisition_no:PurchaseOrder_Requisition_No:requisition_no:V'),
	(18, 3, 'ncrm_purchaseorder:tracking_no:PurchaseOrder_Tracking_Number:tracking_no:V'),
	(18, 4, 'ncrm_contactdetailsPurchaseOrder:lastname:PurchaseOrder_Contact_Name:contact_id:I'),
	(18, 5, 'ncrm_purchaseorder:carrier:PurchaseOrder_Carrier:carrier:V'),
	(18, 6, 'ncrm_purchaseorder:salescommission:PurchaseOrder_Sales_Commission:salescommission:N'),
	(18, 7, 'ncrm_purchaseorder:exciseduty:PurchaseOrder_Excise_Duty:exciseduty:N'),
	(18, 8, 'ncrm_usersPurchaseOrder:user_name:PurchaseOrder_Assigned_To:assigned_user_id:V'),
	(19, 0, 'ncrm_invoice:subject:Invoice_Subject:subject:V'),
	(19, 1, 'ncrm_invoice:salesorderid:Invoice_Sales_Order:salesorder_id:I'),
	(19, 2, 'ncrm_invoice:customerno:Invoice_Customer_No:customerno:V'),
	(19, 3, 'ncrm_invoice:exciseduty:Invoice_Excise_Duty:exciseduty:N'),
	(19, 4, 'ncrm_invoice:salescommission:Invoice_Sales_Commission:salescommission:N'),
	(19, 5, 'ncrm_accountInvoice:accountname:Invoice_Account_Name:account_id:I'),
	(20, 0, 'ncrm_salesorder:subject:SalesOrder_Subject:subject:V'),
	(20, 1, 'ncrm_quotesSalesOrder:subject:SalesOrder_Quote_Name:quote_id:I'),
	(20, 2, 'ncrm_contactdetailsSalesOrder:lastname:SalesOrder_Contact_Name:contact_id:I'),
	(20, 3, 'ncrm_salesorder:duedate:SalesOrder_Due_Date:duedate:D'),
	(20, 4, 'ncrm_salesorder:carrier:SalesOrder_Carrier:carrier:V'),
	(20, 5, 'ncrm_salesorder:sostatus:SalesOrder_Status:sostatus:V'),
	(20, 6, 'ncrm_accountSalesOrder:accountname:SalesOrder_Account_Name:account_id:I'),
	(20, 7, 'ncrm_salesorder:salescommission:SalesOrder_Sales_Commission:salescommission:N'),
	(20, 8, 'ncrm_salesorder:exciseduty:SalesOrder_Excise_Duty:exciseduty:N'),
	(20, 9, 'ncrm_usersSalesOrder:user_name:SalesOrder_Assigned_To:assigned_user_id:V'),
	(21, 0, 'ncrm_campaign:campaignname:Campaigns_Campaign_Name:campaignname:V'),
	(21, 1, 'ncrm_campaign:campaigntype:Campaigns_Campaign_Type:campaigntype:V'),
	(21, 2, 'ncrm_campaign:targetaudience:Campaigns_Target_Audience:targetaudience:V'),
	(21, 3, 'ncrm_campaign:budgetcost:Campaigns_Budget_Cost:budgetcost:I'),
	(21, 4, 'ncrm_campaign:actualcost:Campaigns_Actual_Cost:actualcost:I'),
	(21, 5, 'ncrm_campaign:expectedrevenue:Campaigns_Expected_Revenue:expectedrevenue:I'),
	(21, 6, 'ncrm_campaign:expectedsalescount:Campaigns_Expected_Sales_Count:expectedsalescount:N'),
	(21, 7, 'ncrm_campaign:actualsalescount:Campaigns_Actual_Sales_Count:actualsalescount:N'),
	(21, 8, 'ncrm_usersCampaigns:user_name:Campaigns_Assigned_To:assigned_user_id:V'),
	(22, 0, 'ncrm_contactdetails:lastname:Contacts_Last_Name:lastname:V'),
	(22, 1, 'ncrm_contactdetails:email:Contacts_Email:email:E'),
	(22, 2, 'ncrm_activity:subject:Emails_Subject:subject:V'),
	(22, 3, 'ncrm_email_track:access_count:Emails_Access_Count:access_count:V'),
	(23, 0, 'ncrm_account:accountname:Accounts_Account_Name:accountname:V'),
	(23, 1, 'ncrm_account:phone:Accounts_Phone:phone:V'),
	(23, 2, 'ncrm_account:email1:Accounts_Email:email1:E'),
	(23, 3, 'ncrm_activity:subject:Emails_Subject:subject:V'),
	(23, 4, 'ncrm_email_track:access_count:Emails_Access_Count:access_count:V'),
	(24, 0, 'ncrm_leaddetails:lastname:Leads_Last_Name:lastname:V'),
	(24, 1, 'ncrm_leaddetails:company:Leads_Company:company:V'),
	(24, 2, 'ncrm_leaddetails:email:Leads_Email:email:E'),
	(24, 3, 'ncrm_activity:subject:Emails_Subject:subject:V'),
	(24, 4, 'ncrm_email_track:access_count:Emails_Access_Count:access_count:V'),
	(25, 0, 'ncrm_vendor:vendorname:Vendors_Vendor_Name:vendorname:V'),
	(25, 1, 'ncrm_vendor:glacct:Vendors_GL_Account:glacct:V'),
	(25, 2, 'ncrm_vendor:email:Vendors_Email:email:E'),
	(25, 3, 'ncrm_activity:subject:Emails_Subject:subject:V'),
	(25, 4, 'ncrm_email_track:access_count:Emails_Access_Count:access_count:V');
/*!40000 ALTER TABLE `ncrm_selectcolumn` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_selectquery
CREATE TABLE IF NOT EXISTS `ncrm_selectquery` (
  `queryid` int(19) NOT NULL,
  `startindex` int(19) DEFAULT '0',
  `numofobjects` int(19) DEFAULT '0',
  PRIMARY KEY (`queryid`),
  KEY `selectquery_queryid_idx` (`queryid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_selectquery: ~25 rows (approximately)
/*!40000 ALTER TABLE `ncrm_selectquery` DISABLE KEYS */;
INSERT INTO `ncrm_selectquery` (`queryid`, `startindex`, `numofobjects`) VALUES
	(1, 0, 0),
	(2, 0, 0),
	(3, 0, 0),
	(4, 0, 0),
	(5, 0, 0),
	(6, 0, 0),
	(7, 0, 0),
	(8, 0, 0),
	(9, 0, 0),
	(10, 0, 0),
	(11, 0, 0),
	(12, 0, 0),
	(13, 0, 0),
	(14, 0, 0),
	(15, 0, 0),
	(16, 0, 0),
	(17, 0, 0),
	(18, 0, 0),
	(19, 0, 0),
	(20, 0, 0),
	(21, 0, 0),
	(22, 0, 0),
	(23, 0, 0),
	(24, 0, 0),
	(25, 0, 0);
/*!40000 ALTER TABLE `ncrm_selectquery` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_selectquery_seq
CREATE TABLE IF NOT EXISTS `ncrm_selectquery_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_selectquery_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_selectquery_seq` DISABLE KEYS */;
INSERT INTO `ncrm_selectquery_seq` (`id`) VALUES
	(25);
/*!40000 ALTER TABLE `ncrm_selectquery_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_senotesrel
CREATE TABLE IF NOT EXISTS `ncrm_senotesrel` (
  `crmid` int(19) NOT NULL DEFAULT '0',
  `notesid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`crmid`,`notesid`),
  KEY `senotesrel_notesid_idx` (`notesid`),
  KEY `senotesrel_crmid_idx` (`crmid`),
  CONSTRAINT `fk1_crmid` FOREIGN KEY (`crmid`) REFERENCES `ncrm_crmentity` (`crmid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_senotesrel: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_senotesrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_senotesrel` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_seproductsrel
CREATE TABLE IF NOT EXISTS `ncrm_seproductsrel` (
  `crmid` int(19) NOT NULL DEFAULT '0',
  `productid` int(19) NOT NULL DEFAULT '0',
  `setype` varchar(30) NOT NULL,
  PRIMARY KEY (`crmid`,`productid`),
  KEY `seproductsrel_productid_idx` (`productid`),
  KEY `seproductrel_crmid_idx` (`crmid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_seproductsrel: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_seproductsrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_seproductsrel` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_service
CREATE TABLE IF NOT EXISTS `ncrm_service` (
  `serviceid` int(11) NOT NULL,
  `service_no` varchar(100) NOT NULL,
  `servicename` varchar(50) NOT NULL,
  `servicecategory` varchar(200) DEFAULT NULL,
  `qty_per_unit` decimal(11,2) DEFAULT '0.00',
  `unit_price` decimal(25,8) DEFAULT NULL,
  `sales_start_date` date DEFAULT NULL,
  `sales_end_date` date DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `discontinued` int(1) NOT NULL DEFAULT '0',
  `service_usageunit` varchar(200) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `taxclass` varchar(200) DEFAULT NULL,
  `currency_id` int(19) NOT NULL DEFAULT '1',
  `commissionrate` decimal(7,3) DEFAULT NULL,
  PRIMARY KEY (`serviceid`),
  CONSTRAINT `fk_1_ncrm_service` FOREIGN KEY (`serviceid`) REFERENCES `ncrm_crmentity` (`crmid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_service: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_service` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_service` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_servicecategory
CREATE TABLE IF NOT EXISTS `ncrm_servicecategory` (
  `servicecategoryid` int(11) NOT NULL AUTO_INCREMENT,
  `servicecategory` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(11) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT '0',
  PRIMARY KEY (`servicecategoryid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_servicecategory: ~5 rows (approximately)
/*!40000 ALTER TABLE `ncrm_servicecategory` DISABLE KEYS */;
INSERT INTO `ncrm_servicecategory` (`servicecategoryid`, `servicecategory`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(2, 'Support', 1, 230, 2),
	(3, 'Installation', 1, 231, 3),
	(4, 'Migration', 1, 232, 4),
	(5, 'Customization', 1, 233, 5),
	(6, 'Training', 1, 234, 6);
/*!40000 ALTER TABLE `ncrm_servicecategory` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_servicecategory_seq
CREATE TABLE IF NOT EXISTS `ncrm_servicecategory_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_servicecategory_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_servicecategory_seq` DISABLE KEYS */;
INSERT INTO `ncrm_servicecategory_seq` (`id`) VALUES
	(6);
/*!40000 ALTER TABLE `ncrm_servicecategory_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_servicecf
CREATE TABLE IF NOT EXISTS `ncrm_servicecf` (
  `serviceid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`serviceid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_servicecf: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_servicecf` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_servicecf` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_servicecontracts
CREATE TABLE IF NOT EXISTS `ncrm_servicecontracts` (
  `servicecontractsid` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `sc_related_to` int(11) DEFAULT NULL,
  `tracking_unit` varchar(100) DEFAULT NULL,
  `total_units` decimal(5,2) DEFAULT NULL,
  `used_units` decimal(5,2) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `planned_duration` varchar(256) DEFAULT NULL,
  `actual_duration` varchar(256) DEFAULT NULL,
  `contract_status` varchar(200) DEFAULT NULL,
  `priority` varchar(200) DEFAULT NULL,
  `contract_type` varchar(200) DEFAULT NULL,
  `progress` decimal(5,2) DEFAULT NULL,
  `contract_no` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_servicecontracts: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_servicecontracts` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_servicecontracts` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_servicecontractscf
CREATE TABLE IF NOT EXISTS `ncrm_servicecontractscf` (
  `servicecontractsid` int(11) NOT NULL,
  PRIMARY KEY (`servicecontractsid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_servicecontractscf: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_servicecontractscf` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_servicecontractscf` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_service_usageunit
CREATE TABLE IF NOT EXISTS `ncrm_service_usageunit` (
  `service_usageunitid` int(11) NOT NULL AUTO_INCREMENT,
  `service_usageunit` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(11) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT '0',
  PRIMARY KEY (`service_usageunitid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_service_usageunit: ~3 rows (approximately)
/*!40000 ALTER TABLE `ncrm_service_usageunit` DISABLE KEYS */;
INSERT INTO `ncrm_service_usageunit` (`service_usageunitid`, `service_usageunit`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(1, 'Hours', 1, 226, 1),
	(2, 'Days', 1, 227, 2),
	(3, 'Incidents', 1, 228, 3);
/*!40000 ALTER TABLE `ncrm_service_usageunit` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_service_usageunit_seq
CREATE TABLE IF NOT EXISTS `ncrm_service_usageunit_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_service_usageunit_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_service_usageunit_seq` DISABLE KEYS */;
INSERT INTO `ncrm_service_usageunit_seq` (`id`) VALUES
	(3);
/*!40000 ALTER TABLE `ncrm_service_usageunit_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_seticketsrel
CREATE TABLE IF NOT EXISTS `ncrm_seticketsrel` (
  `crmid` int(19) NOT NULL DEFAULT '0',
  `ticketid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`crmid`,`ticketid`),
  KEY `seticketsrel_crmid_idx` (`crmid`),
  KEY `seticketsrel_ticketid_idx` (`ticketid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_seticketsrel: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_seticketsrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_seticketsrel` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_settings_blocks
CREATE TABLE IF NOT EXISTS `ncrm_settings_blocks` (
  `blockid` int(19) NOT NULL,
  `label` varchar(250) DEFAULT NULL,
  `sequence` int(19) DEFAULT NULL,
  PRIMARY KEY (`blockid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_settings_blocks: ~5 rows (approximately)
/*!40000 ALTER TABLE `ncrm_settings_blocks` DISABLE KEYS */;
INSERT INTO `ncrm_settings_blocks` (`blockid`, `label`, `sequence`) VALUES
	(1, 'LBL_USER_MANAGEMENT', 1),
	(2, 'LBL_STUDIO', 2),
	(3, 'LBL_COMMUNICATION_TEMPLATES', 3),
	(4, 'LBL_OTHER_SETTINGS', 4),
	(5, 'LBL_INTEGRATION', 5);
/*!40000 ALTER TABLE `ncrm_settings_blocks` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_settings_blocks_seq
CREATE TABLE IF NOT EXISTS `ncrm_settings_blocks_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_settings_blocks_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_settings_blocks_seq` DISABLE KEYS */;
INSERT INTO `ncrm_settings_blocks_seq` (`id`) VALUES
	(5);
/*!40000 ALTER TABLE `ncrm_settings_blocks_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_settings_field
CREATE TABLE IF NOT EXISTS `ncrm_settings_field` (
  `fieldid` int(19) NOT NULL,
  `blockid` int(19) DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  `iconpath` varchar(300) DEFAULT NULL,
  `description` text,
  `linkto` text,
  `sequence` int(19) DEFAULT NULL,
  `active` int(19) DEFAULT '0',
  `pinned` int(1) DEFAULT '0',
  PRIMARY KEY (`fieldid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_settings_field: ~31 rows (approximately)
/*!40000 ALTER TABLE `ncrm_settings_field` DISABLE KEYS */;
INSERT INTO `ncrm_settings_field` (`fieldid`, `blockid`, `name`, `iconpath`, `description`, `linkto`, `sequence`, `active`, `pinned`) VALUES
	(1, 1, 'LBL_USERS', 'ico-users.gif', 'LBL_USER_DESCRIPTION', 'index.php?module=Users&parent=Settings&view=List', 1, 0, 1),
	(2, 1, 'LBL_ROLES', 'ico-roles.gif', 'LBL_ROLE_DESCRIPTION', 'index.php?module=Roles&parent=Settings&view=Index', 2, 0, 0),
	(3, 1, 'LBL_PROFILES', 'ico-profile.gif', 'LBL_PROFILE_DESCRIPTION', 'index.php?module=Profiles&parent=Settings&view=List', 3, 0, 0),
	(4, 1, 'USERGROUPLIST', 'ico-groups.gif', 'LBL_GROUP_DESCRIPTION', 'index.php?module=Groups&parent=Settings&view=List', 4, 0, 0),
	(5, 1, 'LBL_SHARING_ACCESS', 'shareaccess.gif', 'LBL_SHARING_ACCESS_DESCRIPTION', 'index.php?module=SharingAccess&parent=Settings&view=Index', 5, 0, 0),
	(6, 1, 'LBL_FIELDS_ACCESS', 'orgshar.gif', 'LBL_SHARING_FIELDS_DESCRIPTION', 'index.php?module=FieldAccess&parent=Settings&view=Index', 6, 0, 0),
	(7, 1, 'LBL_LOGIN_HISTORY_DETAILS', 'set-IcoLoginHistory.gif', 'LBL_LOGIN_HISTORY_DESCRIPTION', 'index.php?module=LoginHistory&parent=Settings&view=List', 7, 0, 0),
	(8, 2, 'VTLIB_LBL_MODULE_MANAGER', 'vtlib_modmng.gif', 'VTLIB_LBL_MODULE_MANAGER_DESCRIPTION', 'index.php?module=ModuleManager&parent=Settings&view=List', 8, 0, 1),
	(9, 2, 'LBL_PICKLIST_EDITOR', 'picklist.gif', 'LBL_PICKLIST_DESCRIPTION', 'index.php?parent=Settings&module=Picklist&view=Index', 1, 0, 1),
	(10, 2, 'LBL_PICKLIST_DEPENDENCY_SETUP', 'picklistdependency.gif', 'LBL_PICKLIST_DEPENDENCY_DESCRIPTION', 'index.php?parent=Settings&module=PickListDependency&view=List', 2, 0, 0),
	(11, 2, 'LBL_MENU_EDITOR', 'menueditor.png', 'LBL_MENU_DESC', 'index.php?module=MenuEditor&parent=Settings&view=Index', 3, 0, 0),
	(12, 3, 'NOTIFICATIONSCHEDULERS', 'notification.gif', 'LBL_NOTIF_SCHED_DESCRIPTION', 'index.php?module=Settings&view=listnotificationschedulers&parenttab=Settings', 4, 0, 0),
	(13, 3, 'INVENTORYNOTIFICATION', 'inventory.gif', 'LBL_INV_NOTIF_DESCRIPTION', 'index.php?module=Settings&view=listinventorynotifications&parenttab=Settings', 1, 0, 0),
	(14, 3, 'LBL_COMPANY_DETAILS', 'company.gif', 'LBL_COMPANY_DESCRIPTION', 'index.php?parent=Settings&module=Ncrm&view=CompanyDetails', 2, 0, 0),
	(15, 4, 'LBL_MAIL_SERVER_SETTINGS', 'ogmailserver.gif', 'LBL_MAIL_SERVER_DESCRIPTION', 'index.php?parent=Settings&module=Ncrm&view=OutgoingServerDetail', 3, 0, 0),
	(16, 4, 'LBL_CURRENCY_SETTINGS', 'currency.gif', 'LBL_CURRENCY_DESCRIPTION', 'index.php?parent=Settings&module=Currency&view=List', 4, 0, 0),
	(17, 4, 'LBL_TAX_SETTINGS', 'taxConfiguration.gif', 'LBL_TAX_DESCRIPTION', 'index.php?module=Ncrm&parent=Settings&view=TaxIndex', 5, 0, 0),
	(18, 4, 'LBL_SYSTEM_INFO', 'system.gif', 'LBL_SYSTEM_DESCRIPTION', 'index.php?module=Settings&submodule=Server&view=ProxyConfig', 6, 1, 0),
	(19, 4, 'LBL_ANNOUNCEMENT', 'announ.gif', 'LBL_ANNOUNCEMENT_DESCRIPTION', 'index.php?parent=Settings&module=Ncrm&view=AnnouncementEdit', 1, 0, 0),
	(20, 4, 'LBL_DEFAULT_MODULE_VIEW', 'set-IcoTwoTabConfig.gif', 'LBL_DEFAULT_MODULE_VIEW_DESC', 'index.php?module=Settings&action=DefModuleView&parenttab=Settings', 2, 0, 0),
	(21, 4, 'INVENTORYTERMSANDCONDITIONS', 'terms.gif', 'LBL_INV_TANDC_DESCRIPTION', 'index.php?parent=Settings&module=Ncrm&view=TermsAndConditionsEdit', 3, 0, 0),
	(22, 4, 'LBL_CUSTOMIZE_MODENT_NUMBER', 'settingsInvNumber.gif', 'LBL_CUSTOMIZE_MODENT_NUMBER_DESCRIPTION', 'index.php?module=Ncrm&parent=Settings&view=CustomRecordNumbering', 4, 0, 0),
	(23, 4, 'LBL_MAIL_SCANNER', 'mailScanner.gif', 'LBL_MAIL_SCANNER_DESCRIPTION', 'index.php?parent=Settings&module=MailConverter&view=List', 5, 0, 0),
	(24, 4, 'LBL_LIST_WORKFLOWS', 'settingsWorkflow.png', 'LBL_LIST_WORKFLOWS_DESCRIPTION', 'index.php?module=Workflows&parent=Settings&view=List', 6, 0, 1),
	(25, 4, 'LBL_CONFIG_EDITOR', 'migrate.gif', 'LBL_CONFIG_EDITOR_DESCRIPTION', 'index.php?module=Ncrm&parent=Settings&view=ConfigEditorDetail', 7, 0, 0),
	(26, 4, 'Scheduler', 'Cron.png', 'Allows you to Configure Cron Task', 'index.php?module=CronTasks&parent=Settings&view=List', 8, 0, 0),
	(27, 4, 'LBL_WORKFLOW_LIST', 'settingsWorkflow.png', 'LBL_AVAILABLE_WORKLIST_LIST', 'index.php?module=com_ncrm_workflow&action=workflowlist', 8, 0, 0),
	(28, 4, 'ModTracker', 'set-IcoLoginHistory.gif', 'LBL_MODTRACKER_DESCRIPTION', 'index.php?module=ModTracker&action=BasicSettings&parenttab=Settings&formodule=ModTracker', 9, 0, 0),
	(30, 4, 'LBL_CUSTOMER_PORTAL', 'portal_icon.png', 'PORTAL_EXTENSION_DESCRIPTION', 'index.php?module=CustomerPortal&action=index&parenttab=Settings', 10, 0, 0),
	(31, 4, 'Webforms', 'modules/Webforms/img/Webform.png', 'LBL_WEBFORMS_DESCRIPTION', 'index.php?module=Webforms&action=index&parenttab=Settings', 11, 0, 0),
	(32, 2, 'LBL_EDIT_FIELDS', '', 'LBL_LAYOUT_EDITOR_DESCRIPTION', 'index.php?module=LayoutEditor&parent=Settings&view=Index', NULL, 0, 0);
/*!40000 ALTER TABLE `ncrm_settings_field` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_settings_field_seq
CREATE TABLE IF NOT EXISTS `ncrm_settings_field_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_settings_field_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_settings_field_seq` DISABLE KEYS */;
INSERT INTO `ncrm_settings_field_seq` (`id`) VALUES
	(32);
/*!40000 ALTER TABLE `ncrm_settings_field_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_sharedcalendar
CREATE TABLE IF NOT EXISTS `ncrm_sharedcalendar` (
  `userid` int(19) NOT NULL,
  `sharedid` int(19) NOT NULL,
  PRIMARY KEY (`userid`,`sharedid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_sharedcalendar: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_sharedcalendar` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_sharedcalendar` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_shareduserinfo
CREATE TABLE IF NOT EXISTS `ncrm_shareduserinfo` (
  `userid` int(19) NOT NULL DEFAULT '0',
  `shareduserid` int(19) NOT NULL DEFAULT '0',
  `color` varchar(50) DEFAULT NULL,
  `visible` int(19) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_shareduserinfo: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_shareduserinfo` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_shareduserinfo` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_shippingtaxinfo
CREATE TABLE IF NOT EXISTS `ncrm_shippingtaxinfo` (
  `taxid` int(3) NOT NULL,
  `taxname` varchar(50) DEFAULT NULL,
  `taxlabel` varchar(50) DEFAULT NULL,
  `percentage` decimal(7,3) DEFAULT NULL,
  `deleted` int(1) DEFAULT NULL,
  PRIMARY KEY (`taxid`),
  KEY `shippingtaxinfo_taxname_idx` (`taxname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_shippingtaxinfo: ~3 rows (approximately)
/*!40000 ALTER TABLE `ncrm_shippingtaxinfo` DISABLE KEYS */;
INSERT INTO `ncrm_shippingtaxinfo` (`taxid`, `taxname`, `taxlabel`, `percentage`, `deleted`) VALUES
	(1, 'shtax1', 'VAT', 4.500, 0),
	(2, 'shtax2', 'Sales', 10.000, 0),
	(3, 'shtax3', 'Service', 12.500, 0);
/*!40000 ALTER TABLE `ncrm_shippingtaxinfo` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_shippingtaxinfo_seq
CREATE TABLE IF NOT EXISTS `ncrm_shippingtaxinfo_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_shippingtaxinfo_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_shippingtaxinfo_seq` DISABLE KEYS */;
INSERT INTO `ncrm_shippingtaxinfo_seq` (`id`) VALUES
	(3);
/*!40000 ALTER TABLE `ncrm_shippingtaxinfo_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_shorturls
CREATE TABLE IF NOT EXISTS `ncrm_shorturls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(50) DEFAULT NULL,
  `handler_path` varchar(400) DEFAULT NULL,
  `handler_class` varchar(100) DEFAULT NULL,
  `handler_function` varchar(100) DEFAULT NULL,
  `handler_data` varchar(255) DEFAULT NULL,
  `onetime` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_shorturls: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_shorturls` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_shorturls` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_smsnotifier
CREATE TABLE IF NOT EXISTS `ncrm_smsnotifier` (
  `smsnotifierid` int(11) DEFAULT NULL,
  `message` text,
  `status` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_smsnotifier: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_smsnotifier` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_smsnotifier` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_smsnotifiercf
CREATE TABLE IF NOT EXISTS `ncrm_smsnotifiercf` (
  `smsnotifierid` int(11) NOT NULL,
  PRIMARY KEY (`smsnotifierid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_smsnotifiercf: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_smsnotifiercf` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_smsnotifiercf` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_smsnotifier_servers
CREATE TABLE IF NOT EXISTS `ncrm_smsnotifier_servers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `password` varchar(255) DEFAULT NULL,
  `isactive` int(1) DEFAULT NULL,
  `providertype` varchar(50) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `parameters` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_smsnotifier_servers: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_smsnotifier_servers` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_smsnotifier_servers` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_smsnotifier_status
CREATE TABLE IF NOT EXISTS `ncrm_smsnotifier_status` (
  `smsnotifierid` int(11) DEFAULT NULL,
  `tonumber` varchar(20) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `smsmessageid` varchar(50) DEFAULT NULL,
  `needlookup` int(1) DEFAULT '1',
  `statusid` int(11) NOT NULL AUTO_INCREMENT,
  `statusmessage` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`statusid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_smsnotifier_status: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_smsnotifier_status` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_smsnotifier_status` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_soapservice
CREATE TABLE IF NOT EXISTS `ncrm_soapservice` (
  `id` int(19) DEFAULT NULL,
  `type` varchar(25) DEFAULT NULL,
  `sessionid` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_soapservice: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_soapservice` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_soapservice` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_sobillads
CREATE TABLE IF NOT EXISTS `ncrm_sobillads` (
  `sobilladdressid` int(19) NOT NULL DEFAULT '0',
  `bill_city` varchar(30) DEFAULT NULL,
  `bill_code` varchar(30) DEFAULT NULL,
  `bill_country` varchar(30) DEFAULT NULL,
  `bill_state` varchar(30) DEFAULT NULL,
  `bill_street` varchar(250) DEFAULT NULL,
  `bill_pobox` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`sobilladdressid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_sobillads: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_sobillads` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_sobillads` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_soshipads
CREATE TABLE IF NOT EXISTS `ncrm_soshipads` (
  `soshipaddressid` int(19) NOT NULL DEFAULT '0',
  `ship_city` varchar(30) DEFAULT NULL,
  `ship_code` varchar(30) DEFAULT NULL,
  `ship_country` varchar(30) DEFAULT NULL,
  `ship_state` varchar(30) DEFAULT NULL,
  `ship_street` varchar(250) DEFAULT NULL,
  `ship_pobox` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`soshipaddressid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_soshipads: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_soshipads` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_soshipads` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_sostatus
CREATE TABLE IF NOT EXISTS `ncrm_sostatus` (
  `sostatusid` int(19) NOT NULL AUTO_INCREMENT,
  `sostatus` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  PRIMARY KEY (`sostatusid`),
  UNIQUE KEY `sostatus_sostatus_idx` (`sostatus`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_sostatus: ~4 rows (approximately)
/*!40000 ALTER TABLE `ncrm_sostatus` DISABLE KEYS */;
INSERT INTO `ncrm_sostatus` (`sostatusid`, `sostatus`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(1, 'Created', 0, 166, 0),
	(2, 'Approved', 0, 167, 1),
	(3, 'Delivered', 0, 168, 2),
	(4, 'Cancelled', 0, 169, 3);
/*!40000 ALTER TABLE `ncrm_sostatus` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_sostatushistory
CREATE TABLE IF NOT EXISTS `ncrm_sostatushistory` (
  `historyid` int(19) NOT NULL AUTO_INCREMENT,
  `salesorderid` int(19) NOT NULL,
  `accountname` varchar(100) DEFAULT NULL,
  `total` decimal(10,0) DEFAULT NULL,
  `sostatus` varchar(200) DEFAULT NULL,
  `lastmodified` datetime DEFAULT NULL,
  PRIMARY KEY (`historyid`),
  KEY `sostatushistory_salesorderid_idx` (`salesorderid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_sostatushistory: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_sostatushistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_sostatushistory` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_sostatus_seq
CREATE TABLE IF NOT EXISTS `ncrm_sostatus_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_sostatus_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_sostatus_seq` DISABLE KEYS */;
INSERT INTO `ncrm_sostatus_seq` (`id`) VALUES
	(4);
/*!40000 ALTER TABLE `ncrm_sostatus_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_sqltimelog
CREATE TABLE IF NOT EXISTS `ncrm_sqltimelog` (
  `id` int(11) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `data` text,
  `started` decimal(20,6) DEFAULT NULL,
  `ended` decimal(20,6) DEFAULT NULL,
  `loggedon` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_sqltimelog: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_sqltimelog` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_sqltimelog` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_start_hour
CREATE TABLE IF NOT EXISTS `ncrm_start_hour` (
  `start_hourid` int(11) NOT NULL AUTO_INCREMENT,
  `start_hour` varchar(200) NOT NULL,
  `sortorderid` int(11) DEFAULT NULL,
  `presence` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`start_hourid`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_start_hour: ~24 rows (approximately)
/*!40000 ALTER TABLE `ncrm_start_hour` DISABLE KEYS */;
INSERT INTO `ncrm_start_hour` (`start_hourid`, `start_hour`, `sortorderid`, `presence`) VALUES
	(1, '00:00', 1, 1),
	(2, '01:00', 2, 1),
	(3, '02:00', 3, 1),
	(4, '03:00', 4, 1),
	(5, '04:00', 5, 1),
	(6, '05:00', 6, 1),
	(7, '06:00', 7, 1),
	(8, '07:00', 8, 1),
	(9, '08:00', 9, 1),
	(10, '09:00', 10, 1),
	(11, '10:00', 11, 1),
	(12, '11:00', 12, 1),
	(13, '12:00', 13, 1),
	(14, '13:00', 14, 1),
	(15, '14:00', 15, 1),
	(16, '15:00', 16, 1),
	(17, '16:00', 17, 1),
	(18, '17:00', 18, 1),
	(19, '18:00', 19, 1),
	(20, '19:00', 20, 1),
	(21, '20:00', 21, 1),
	(22, '21:00', 22, 1),
	(23, '22:00', 23, 1),
	(24, '23:00', 24, 1);
/*!40000 ALTER TABLE `ncrm_start_hour` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_start_hour_seq
CREATE TABLE IF NOT EXISTS `ncrm_start_hour_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_start_hour_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_start_hour_seq` DISABLE KEYS */;
INSERT INTO `ncrm_start_hour_seq` (`id`) VALUES
	(24);
/*!40000 ALTER TABLE `ncrm_start_hour_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_status
CREATE TABLE IF NOT EXISTS `ncrm_status` (
  `statusid` int(19) NOT NULL AUTO_INCREMENT,
  `status` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`statusid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_status: ~2 rows (approximately)
/*!40000 ALTER TABLE `ncrm_status` DISABLE KEYS */;
INSERT INTO `ncrm_status` (`statusid`, `status`, `presence`, `picklist_valueid`) VALUES
	(1, 'Active', 0, 1),
	(2, 'Inactive', 1, 1);
/*!40000 ALTER TABLE `ncrm_status` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_status_seq
CREATE TABLE IF NOT EXISTS `ncrm_status_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_status_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_status_seq` DISABLE KEYS */;
INSERT INTO `ncrm_status_seq` (`id`) VALUES
	(2);
/*!40000 ALTER TABLE `ncrm_status_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_systems
CREATE TABLE IF NOT EXISTS `ncrm_systems` (
  `id` int(19) NOT NULL,
  `server` varchar(100) DEFAULT NULL,
  `server_port` int(19) DEFAULT NULL,
  `server_username` varchar(100) DEFAULT NULL,
  `server_password` varchar(100) DEFAULT NULL,
  `server_type` varchar(20) DEFAULT NULL,
  `smtp_auth` varchar(5) DEFAULT NULL,
  `server_path` varchar(256) DEFAULT NULL,
  `from_email_field` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_systems: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_systems` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_systems` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_tab
CREATE TABLE IF NOT EXISTS `ncrm_tab` (
  `tabid` int(19) NOT NULL DEFAULT '0',
  `name` varchar(25) NOT NULL,
  `presence` int(19) NOT NULL DEFAULT '1',
  `tabsequence` int(10) DEFAULT NULL,
  `tablabel` varchar(25) NOT NULL,
  `modifiedby` int(19) DEFAULT NULL,
  `modifiedtime` int(19) DEFAULT NULL,
  `customized` int(19) DEFAULT NULL,
  `ownedby` int(19) DEFAULT NULL,
  `isentitytype` int(11) NOT NULL DEFAULT '1',
  `trial` int(1) NOT NULL DEFAULT '0',
  `version` varchar(10) DEFAULT NULL,
  `parent` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`tabid`),
  UNIQUE KEY `tab_name_idx` (`name`),
  KEY `tab_modifiedby_idx` (`modifiedby`),
  KEY `tab_tabid_idx` (`tabid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_tab: ~45 rows (approximately)
/*!40000 ALTER TABLE `ncrm_tab` DISABLE KEYS */;
INSERT INTO `ncrm_tab` (`tabid`, `name`, `presence`, `tabsequence`, `tablabel`, `modifiedby`, `modifiedtime`, `customized`, `ownedby`, `isentitytype`, `trial`, `version`, `parent`) VALUES
	(1, 'Dashboard', 0, -1, 'Dashboards', NULL, NULL, 0, 1, 0, 0, NULL, 'Analytics'),
	(2, 'Potentials', 1, -1, 'Potentials', NULL, NULL, 0, 0, 1, 0, NULL, 'Sales'),
	(3, 'Home', 0, -1, 'Home', NULL, NULL, 0, 1, 0, 0, NULL, NULL),
	(4, 'Contacts', 0, 2, 'Contacts', NULL, NULL, 0, 0, 1, 0, NULL, 'Sales'),
	(6, 'Accounts', 1, -1, 'Accounts', NULL, NULL, 0, 0, 1, 0, NULL, 'Sales'),
	(7, 'Leads', 1, -1, 'Leads', NULL, NULL, 0, 0, 1, 0, NULL, 'Sales'),
	(8, 'Documents', 1, -1, 'Documents', NULL, NULL, 0, 0, 1, 0, NULL, 'Tools'),
	(9, 'Calendar', 0, 1, 'Calendar', NULL, NULL, 0, 0, 1, 0, NULL, 'Tools'),
	(10, 'Emails', 0, -1, 'Emails', NULL, NULL, 0, 1, 1, 0, NULL, 'Tools'),
	(13, 'HelpDesk', 1, 3, 'HelpDesk', NULL, NULL, 0, 0, 1, 0, NULL, 'Support'),
	(14, 'Products', 1, -1, 'Products', NULL, NULL, 0, 0, 1, 0, NULL, 'Inventory'),
	(15, 'Faq', 1, -1, 'Faq', NULL, NULL, 0, 1, 1, 0, NULL, 'Support'),
	(16, 'Events', 2, -1, 'Events', NULL, NULL, 0, 0, 1, 0, NULL, NULL),
	(18, 'Vendors', 1, -1, 'Vendors', NULL, NULL, 0, 0, 1, 0, NULL, 'Inventory'),
	(19, 'PriceBooks', 1, -1, 'PriceBooks', NULL, NULL, 0, 1, 1, 0, NULL, 'Inventory'),
	(20, 'Quotes', 1, -1, 'Quotes', NULL, NULL, 0, 0, 1, 0, NULL, 'Sales'),
	(21, 'PurchaseOrder', 1, -1, 'PurchaseOrder', NULL, NULL, 0, 0, 1, 0, NULL, 'Inventory'),
	(22, 'SalesOrder', 1, -1, 'SalesOrder', NULL, NULL, 0, 0, 1, 0, NULL, 'Sales'),
	(23, 'Invoice', 1, -1, 'Invoice', NULL, NULL, 0, 0, 1, 0, NULL, 'Sales'),
	(24, 'Rss', 1, -1, 'Rss', NULL, NULL, 0, 1, 0, 0, NULL, 'Tools'),
	(25, 'Reports', 0, 4, 'Reports', NULL, NULL, 0, 1, 0, 0, NULL, 'Analytics'),
	(26, 'Campaigns', 1, -1, 'Campaigns', NULL, NULL, 0, 0, 1, 0, NULL, 'Marketing'),
	(27, 'Portal', 1, -1, 'Portal', NULL, NULL, 0, 1, 0, 0, NULL, 'Tools'),
	(28, 'Webmails', 0, -1, 'Webmails', NULL, NULL, 0, 1, 1, 0, NULL, NULL),
	(29, 'Users', 0, -1, 'Users', NULL, NULL, 0, 1, 0, 0, NULL, NULL),
	(30, 'Import', 0, -1, 'Import', NULL, NULL, 1, 0, 0, 0, '1.7', ''),
	(31, 'MailManager', 1, -1, 'MailManager', NULL, NULL, 1, 0, 0, 0, '1.9', 'Tools'),
	(32, 'Mobile', 0, -1, 'Mobile', NULL, NULL, 1, 0, 0, 0, '2.0', ''),
	(33, 'ModTracker', 0, -1, 'ModTracker', NULL, NULL, 0, 0, 0, 0, '1.2', ''),
	(34, 'PBXManager', 1, -1, 'PBXManager', NULL, NULL, 1, 0, 1, 0, '2.2', 'Tools'),
	(35, 'ServiceContracts', 1, -1, 'Service Contracts', NULL, NULL, 0, 0, 1, 0, '2.4', 'Support'),
	(36, 'Services', 1, -1, 'Services', NULL, NULL, 0, 0, 1, 0, '2.6', 'Inventory'),
	(37, 'WSAPP', 0, -1, 'WSAPP', NULL, NULL, 1, 0, 0, 0, '3.4.4', ''),
	(38, 'Assets', 1, -1, 'Assets', NULL, NULL, 0, 0, 1, 0, '2.0', 'Inventory'),
	(39, 'CustomerPortal', 0, -1, 'CustomerPortal', NULL, NULL, 0, 0, 0, 0, '1.4', ''),
	(40, 'EmailTemplates', 1, -1, 'Email Templates', NULL, NULL, 1, 0, 0, 0, '1.0', 'Tools'),
	(41, 'Google', 1, -1, 'Google', NULL, NULL, 0, 0, 0, 0, '1.5', ''),
	(42, 'ModComments', 0, -1, 'Comments', NULL, NULL, 0, 0, 1, 0, '2.1', 'Tools'),
	(43, 'ProjectMilestone', 1, -1, 'ProjectMilestone', NULL, NULL, 0, 0, 1, 0, '3.0', 'Support'),
	(44, 'ProjectTask', 1, -1, 'ProjectTask', NULL, NULL, 0, 0, 1, 0, '3.1', 'Support'),
	(45, 'Project', 1, -1, 'Project', NULL, NULL, 0, 0, 1, 0, '3.3', 'Support'),
	(46, 'RecycleBin', 1, -1, 'Recycle Bin', NULL, NULL, 0, 0, 0, 0, '1.5', 'Tools'),
	(47, 'SMSNotifier', 1, -1, 'SMSNotifier', NULL, NULL, 0, 0, 1, 0, '1.9', 'Tools'),
	(48, 'Webforms', 0, -1, 'Webforms', NULL, NULL, 0, 0, 0, 0, '1.6', ''),
	(49, 'ExtensionStore', 0, -1, 'Extension Store', NULL, NULL, 1, 0, 0, 0, '1.2', 'Settings');
/*!40000 ALTER TABLE `ncrm_tab` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_tab_info
CREATE TABLE IF NOT EXISTS `ncrm_tab_info` (
  `tabid` int(19) DEFAULT NULL,
  `prefname` varchar(256) DEFAULT NULL,
  `prefvalue` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_tab_info: ~33 rows (approximately)
/*!40000 ALTER TABLE `ncrm_tab_info` DISABLE KEYS */;
INSERT INTO `ncrm_tab_info` (`tabid`, `prefname`, `prefvalue`) VALUES
	(30, 'ncrm_min_version', '6.0.0rc'),
	(30, 'ncrm_max_version', '6.*'),
	(31, 'ncrm_min_version', '6.0.0RC'),
	(32, 'ncrm_min_version', '6.0.0rc'),
	(33, 'ncrm_min_version', '6.0.0rc'),
	(34, 'ncrm_min_version', '6.0.0'),
	(34, 'ncrm_max_version', '6.*'),
	(35, 'ncrm_min_version', '6.0.0rc'),
	(36, 'ncrm_min_version', '6.0.0rc'),
	(36, 'ncrm_max_version', '6.*'),
	(37, 'ncrm_min_version', '6.0.0rc'),
	(38, 'ncrm_min_version', '6.0.0rc'),
	(38, 'ncrm_max_version', '6.*'),
	(39, 'ncrm_min_version', '6.0.0rc'),
	(39, 'ncrm_max_version', '6.*'),
	(40, 'ncrm_min_version', '6.0.0rc'),
	(40, 'ncrm_max_version', '6.*'),
	(41, 'ncrm_min_version', '6.0.0rc'),
	(41, 'ncrm_max_version', '6.*'),
	(42, 'ncrm_min_version', '6.0.0rc'),
	(42, 'ncrm_max_version', '6.*'),
	(43, 'ncrm_min_version', '6.0.0rc'),
	(43, 'ncrm_max_version', '6.*'),
	(44, 'ncrm_min_version', '6.0.0rc'),
	(45, 'ncrm_min_version', '6.0.0rc'),
	(46, 'ncrm_min_version', '6.0.0rc'),
	(46, 'ncrm_max_version', '6.*'),
	(47, 'ncrm_min_version', '6.0.0rc'),
	(47, 'ncrm_max_version', '6.*'),
	(48, 'ncrm_min_version', '6.0.0rc'),
	(48, 'ncrm_max_version', '6.*'),
	(49, 'ncrm_min_version', '6.0.0'),
	(49, 'ncrm_max_version', '6.*');
/*!40000 ALTER TABLE `ncrm_tab_info` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_taskpriority
CREATE TABLE IF NOT EXISTS `ncrm_taskpriority` (
  `taskpriorityid` int(19) NOT NULL AUTO_INCREMENT,
  `taskpriority` varchar(200) DEFAULT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  PRIMARY KEY (`taskpriorityid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_taskpriority: ~3 rows (approximately)
/*!40000 ALTER TABLE `ncrm_taskpriority` DISABLE KEYS */;
INSERT INTO `ncrm_taskpriority` (`taskpriorityid`, `taskpriority`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(1, 'High', 1, 170, 0),
	(2, 'Medium', 1, 171, 1),
	(3, 'Low', 1, 172, 2);
/*!40000 ALTER TABLE `ncrm_taskpriority` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_taskpriority_seq
CREATE TABLE IF NOT EXISTS `ncrm_taskpriority_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_taskpriority_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_taskpriority_seq` DISABLE KEYS */;
INSERT INTO `ncrm_taskpriority_seq` (`id`) VALUES
	(3);
/*!40000 ALTER TABLE `ncrm_taskpriority_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_taskstatus
CREATE TABLE IF NOT EXISTS `ncrm_taskstatus` (
  `taskstatusid` int(19) NOT NULL AUTO_INCREMENT,
  `taskstatus` varchar(200) DEFAULT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  PRIMARY KEY (`taskstatusid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_taskstatus: ~6 rows (approximately)
/*!40000 ALTER TABLE `ncrm_taskstatus` DISABLE KEYS */;
INSERT INTO `ncrm_taskstatus` (`taskstatusid`, `taskstatus`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(1, 'Not Started', 0, 173, 0),
	(2, 'In Progress', 0, 174, 1),
	(3, 'Completed', 0, 175, 2),
	(4, 'Pending Input', 0, 176, 3),
	(5, 'Deferred', 0, 177, 4),
	(6, 'Planned', 0, 178, 5);
/*!40000 ALTER TABLE `ncrm_taskstatus` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_taskstatus_seq
CREATE TABLE IF NOT EXISTS `ncrm_taskstatus_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_taskstatus_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_taskstatus_seq` DISABLE KEYS */;
INSERT INTO `ncrm_taskstatus_seq` (`id`) VALUES
	(6);
/*!40000 ALTER TABLE `ncrm_taskstatus_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_taxclass
CREATE TABLE IF NOT EXISTS `ncrm_taxclass` (
  `taxclassid` int(19) NOT NULL AUTO_INCREMENT,
  `taxclass` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`taxclassid`),
  UNIQUE KEY `taxclass_carrier_idx` (`taxclass`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_taxclass: ~2 rows (approximately)
/*!40000 ALTER TABLE `ncrm_taxclass` DISABLE KEYS */;
INSERT INTO `ncrm_taxclass` (`taxclassid`, `taxclass`, `sortorderid`, `presence`) VALUES
	(1, 'SalesTax', 0, 1),
	(2, 'Vat', 1, 1);
/*!40000 ALTER TABLE `ncrm_taxclass` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_taxclass_seq
CREATE TABLE IF NOT EXISTS `ncrm_taxclass_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_taxclass_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_taxclass_seq` DISABLE KEYS */;
INSERT INTO `ncrm_taxclass_seq` (`id`) VALUES
	(2);
/*!40000 ALTER TABLE `ncrm_taxclass_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_ticketcategories
CREATE TABLE IF NOT EXISTS `ncrm_ticketcategories` (
  `ticketcategories_id` int(19) NOT NULL AUTO_INCREMENT,
  `ticketcategories` varchar(200) DEFAULT NULL,
  `presence` int(1) NOT NULL DEFAULT '0',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  PRIMARY KEY (`ticketcategories_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_ticketcategories: ~3 rows (approximately)
/*!40000 ALTER TABLE `ncrm_ticketcategories` DISABLE KEYS */;
INSERT INTO `ncrm_ticketcategories` (`ticketcategories_id`, `ticketcategories`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(1, 'Big Problem', 1, 179, 0),
	(2, 'Small Problem', 1, 180, 1),
	(3, 'Other Problem', 1, 181, 2);
/*!40000 ALTER TABLE `ncrm_ticketcategories` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_ticketcategories_seq
CREATE TABLE IF NOT EXISTS `ncrm_ticketcategories_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_ticketcategories_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_ticketcategories_seq` DISABLE KEYS */;
INSERT INTO `ncrm_ticketcategories_seq` (`id`) VALUES
	(3);
/*!40000 ALTER TABLE `ncrm_ticketcategories_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_ticketcf
CREATE TABLE IF NOT EXISTS `ncrm_ticketcf` (
  `ticketid` int(19) NOT NULL DEFAULT '0',
  `from_portal` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`ticketid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_ticketcf: ~10 rows (approximately)
/*!40000 ALTER TABLE `ncrm_ticketcf` DISABLE KEYS */;
INSERT INTO `ncrm_ticketcf` (`ticketid`, `from_portal`) VALUES
	(2, '0'),
	(3, '0'),
	(4, '0'),
	(5, '0'),
	(6, '0'),
	(7, '0'),
	(8, '0'),
	(9, '0'),
	(10, '0'),
	(11, '0');
/*!40000 ALTER TABLE `ncrm_ticketcf` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_ticketcomments
CREATE TABLE IF NOT EXISTS `ncrm_ticketcomments` (
  `commentid` int(19) NOT NULL AUTO_INCREMENT,
  `ticketid` int(19) DEFAULT NULL,
  `comments` text,
  `ownerid` int(19) NOT NULL DEFAULT '0',
  `ownertype` varchar(10) DEFAULT NULL,
  `createdtime` datetime NOT NULL,
  PRIMARY KEY (`commentid`),
  KEY `ticketcomments_ticketid_idx` (`ticketid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_ticketcomments: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_ticketcomments` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_ticketcomments` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_ticketpriorities
CREATE TABLE IF NOT EXISTS `ncrm_ticketpriorities` (
  `ticketpriorities_id` int(19) NOT NULL AUTO_INCREMENT,
  `ticketpriorities` varchar(200) DEFAULT NULL,
  `presence` int(1) NOT NULL DEFAULT '0',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  PRIMARY KEY (`ticketpriorities_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_ticketpriorities: ~4 rows (approximately)
/*!40000 ALTER TABLE `ncrm_ticketpriorities` DISABLE KEYS */;
INSERT INTO `ncrm_ticketpriorities` (`ticketpriorities_id`, `ticketpriorities`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(1, 'Low', 1, 182, 0),
	(2, 'Normal', 1, 183, 1),
	(3, 'High', 1, 184, 2),
	(4, 'Urgent', 1, 185, 3);
/*!40000 ALTER TABLE `ncrm_ticketpriorities` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_ticketpriorities_seq
CREATE TABLE IF NOT EXISTS `ncrm_ticketpriorities_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_ticketpriorities_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_ticketpriorities_seq` DISABLE KEYS */;
INSERT INTO `ncrm_ticketpriorities_seq` (`id`) VALUES
	(4);
/*!40000 ALTER TABLE `ncrm_ticketpriorities_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_ticketseverities
CREATE TABLE IF NOT EXISTS `ncrm_ticketseverities` (
  `ticketseverities_id` int(19) NOT NULL AUTO_INCREMENT,
  `ticketseverities` varchar(200) DEFAULT NULL,
  `presence` int(1) NOT NULL DEFAULT '0',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  PRIMARY KEY (`ticketseverities_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_ticketseverities: ~4 rows (approximately)
/*!40000 ALTER TABLE `ncrm_ticketseverities` DISABLE KEYS */;
INSERT INTO `ncrm_ticketseverities` (`ticketseverities_id`, `ticketseverities`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(1, 'Minor', 1, 186, 0),
	(2, 'Major', 1, 187, 1),
	(3, 'Feature', 1, 188, 2),
	(4, 'Critical', 1, 189, 3);
/*!40000 ALTER TABLE `ncrm_ticketseverities` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_ticketseverities_seq
CREATE TABLE IF NOT EXISTS `ncrm_ticketseverities_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_ticketseverities_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_ticketseverities_seq` DISABLE KEYS */;
INSERT INTO `ncrm_ticketseverities_seq` (`id`) VALUES
	(4);
/*!40000 ALTER TABLE `ncrm_ticketseverities_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_ticketstatus
CREATE TABLE IF NOT EXISTS `ncrm_ticketstatus` (
  `ticketstatus_id` int(19) NOT NULL AUTO_INCREMENT,
  `ticketstatus` varchar(200) DEFAULT NULL,
  `presence` int(1) NOT NULL DEFAULT '0',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  PRIMARY KEY (`ticketstatus_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_ticketstatus: ~4 rows (approximately)
/*!40000 ALTER TABLE `ncrm_ticketstatus` DISABLE KEYS */;
INSERT INTO `ncrm_ticketstatus` (`ticketstatus_id`, `ticketstatus`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(1, 'Open', 0, 190, 0),
	(2, 'In Progress', 0, 191, 1),
	(3, 'Wait For Response', 0, 192, 2),
	(4, 'Closed', 0, 193, 3);
/*!40000 ALTER TABLE `ncrm_ticketstatus` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_ticketstatus_seq
CREATE TABLE IF NOT EXISTS `ncrm_ticketstatus_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_ticketstatus_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_ticketstatus_seq` DISABLE KEYS */;
INSERT INTO `ncrm_ticketstatus_seq` (`id`) VALUES
	(4);
/*!40000 ALTER TABLE `ncrm_ticketstatus_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_time_zone
CREATE TABLE IF NOT EXISTS `ncrm_time_zone` (
  `time_zoneid` int(19) NOT NULL AUTO_INCREMENT,
  `time_zone` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`time_zoneid`)
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_time_zone: ~97 rows (approximately)
/*!40000 ALTER TABLE `ncrm_time_zone` DISABLE KEYS */;
INSERT INTO `ncrm_time_zone` (`time_zoneid`, `time_zone`, `sortorderid`, `presence`) VALUES
	(1, 'Pacific/Midway', 0, 1),
	(2, 'Pacific/Samoa', 1, 1),
	(3, 'Pacific/Honolulu', 2, 1),
	(4, 'America/Anchorage', 3, 1),
	(5, 'America/Los_Angeles', 4, 1),
	(6, 'America/Tijuana', 5, 1),
	(7, 'America/Denver', 6, 1),
	(8, 'America/Chihuahua', 7, 1),
	(9, 'America/Mazatlan', 8, 1),
	(10, 'America/Phoenix', 9, 1),
	(11, 'America/Regina', 10, 1),
	(12, 'America/Tegucigalpa', 11, 1),
	(13, 'America/Chicago', 12, 1),
	(14, 'America/Mexico_City', 13, 1),
	(15, 'America/Monterrey', 14, 1),
	(16, 'America/New_York', 15, 1),
	(17, 'America/Bogota', 16, 1),
	(18, 'America/Lima', 17, 1),
	(19, 'America/Rio_Branco', 18, 1),
	(20, 'America/Indiana/Indianapolis', 19, 1),
	(21, 'America/Caracas', 20, 1),
	(22, 'America/Halifax', 21, 1),
	(23, 'America/Manaus', 22, 1),
	(24, 'America/Santiago', 23, 1),
	(25, 'America/La_Paz', 24, 1),
	(26, 'America/Cuiaba', 25, 1),
	(27, 'America/Asuncion', 26, 1),
	(28, 'America/St_Johns', 27, 1),
	(29, 'America/Argentina/Buenos_Aires', 28, 1),
	(30, 'America/Sao_Paulo', 29, 1),
	(31, 'America/Godthab', 30, 1),
	(32, 'America/Montevideo', 31, 1),
	(33, 'Atlantic/South_Georgia', 32, 1),
	(34, 'Atlantic/Azores', 33, 1),
	(35, 'Atlantic/Cape_Verde', 34, 1),
	(36, 'Europe/London', 35, 1),
	(37, 'UTC', 36, 1),
	(38, 'Africa/Monrovia', 37, 1),
	(39, 'Africa/Casablanca', 38, 1),
	(40, 'Europe/Belgrade', 39, 1),
	(41, 'Europe/Sarajevo', 40, 1),
	(42, 'Europe/Brussels', 41, 1),
	(43, 'Africa/Algiers', 42, 1),
	(44, 'Europe/Amsterdam', 43, 1),
	(45, 'Europe/Minsk', 44, 1),
	(46, 'Africa/Cairo', 45, 1),
	(47, 'Europe/Helsinki', 46, 1),
	(48, 'Europe/Athens', 47, 1),
	(49, 'Europe/Istanbul', 48, 1),
	(50, 'Asia/Jerusalem', 49, 1),
	(51, 'Asia/Amman', 50, 1),
	(52, 'Asia/Beirut', 51, 1),
	(53, 'Africa/Windhoek', 52, 1),
	(54, 'Africa/Harare', 53, 1),
	(55, 'Asia/Kuwait', 54, 1),
	(56, 'Asia/Baghdad', 55, 1),
	(57, 'Africa/Nairobi', 56, 1),
	(58, 'Asia/Tehran', 57, 1),
	(59, 'Asia/Tbilisi', 58, 1),
	(60, 'Europe/Moscow', 59, 1),
	(61, 'Asia/Muscat', 60, 1),
	(62, 'Asia/Baku', 61, 1),
	(63, 'Asia/Yerevan', 62, 1),
	(64, 'Asia/Karachi', 63, 1),
	(65, 'Asia/Tashkent', 64, 1),
	(66, 'Asia/Kolkata', 65, 1),
	(67, 'Asia/Colombo', 66, 1),
	(68, 'Asia/Katmandu', 67, 1),
	(69, 'Asia/Dhaka', 68, 1),
	(70, 'Asia/Almaty', 69, 1),
	(71, 'Asia/Yekaterinburg', 70, 1),
	(72, 'Asia/Rangoon', 71, 1),
	(73, 'Asia/Novosibirsk', 72, 1),
	(74, 'Asia/Bangkok', 73, 1),
	(75, 'Asia/Brunei', 74, 1),
	(76, 'Asia/Krasnoyarsk', 75, 1),
	(77, 'Asia/Ulaanbaatar', 76, 1),
	(78, 'Asia/Kuala_Lumpur', 77, 1),
	(79, 'Asia/Taipei', 78, 1),
	(80, 'Australia/Perth', 79, 1),
	(81, 'Asia/Irkutsk', 80, 1),
	(82, 'Asia/Seoul', 81, 1),
	(83, 'Asia/Tokyo', 82, 1),
	(84, 'Australia/Darwin', 83, 1),
	(85, 'Australia/Adelaide', 84, 1),
	(86, 'Australia/Canberra', 85, 1),
	(87, 'Australia/Brisbane', 86, 1),
	(88, 'Australia/Hobart', 87, 1),
	(89, 'Asia/Vladivostok', 88, 1),
	(90, 'Pacific/Guam', 89, 1),
	(91, 'Asia/Yakutsk', 90, 1),
	(92, 'Pacific/Fiji', 92, 1),
	(93, 'Asia/Kamchatka', 93, 1),
	(94, 'Pacific/Auckland', 94, 1),
	(95, 'Asia/Magadan', 95, 1),
	(96, 'Pacific/Tongatapu', 96, 1),
	(97, 'Etc/GMT-11', 91, 1);
/*!40000 ALTER TABLE `ncrm_time_zone` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_time_zone_seq
CREATE TABLE IF NOT EXISTS `ncrm_time_zone_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_time_zone_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_time_zone_seq` DISABLE KEYS */;
INSERT INTO `ncrm_time_zone_seq` (`id`) VALUES
	(96);
/*!40000 ALTER TABLE `ncrm_time_zone_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_tmp_read_group_rel_sharing_per
CREATE TABLE IF NOT EXISTS `ncrm_tmp_read_group_rel_sharing_per` (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `relatedtabid` int(11) NOT NULL,
  `sharedgroupid` int(11) NOT NULL,
  PRIMARY KEY (`userid`,`tabid`,`relatedtabid`,`sharedgroupid`),
  KEY `tmp_read_group_rel_sharing_per_userid_sharedgroupid_tabid` (`userid`,`sharedgroupid`,`tabid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_tmp_read_group_rel_sharing_per: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_tmp_read_group_rel_sharing_per` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_tmp_read_group_rel_sharing_per` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_tmp_read_group_sharing_per
CREATE TABLE IF NOT EXISTS `ncrm_tmp_read_group_sharing_per` (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `sharedgroupid` int(11) NOT NULL,
  PRIMARY KEY (`userid`,`tabid`,`sharedgroupid`),
  KEY `tmp_read_group_sharing_per_userid_sharedgroupid_idx` (`userid`,`sharedgroupid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_tmp_read_group_sharing_per: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_tmp_read_group_sharing_per` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_tmp_read_group_sharing_per` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_tmp_read_user_rel_sharing_per
CREATE TABLE IF NOT EXISTS `ncrm_tmp_read_user_rel_sharing_per` (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `relatedtabid` int(11) NOT NULL,
  `shareduserid` int(11) NOT NULL,
  PRIMARY KEY (`userid`,`tabid`,`relatedtabid`,`shareduserid`),
  KEY `tmp_read_user_rel_sharing_per_userid_shared_reltabid_idx` (`userid`,`shareduserid`,`relatedtabid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_tmp_read_user_rel_sharing_per: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_tmp_read_user_rel_sharing_per` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_tmp_read_user_rel_sharing_per` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_tmp_read_user_sharing_per
CREATE TABLE IF NOT EXISTS `ncrm_tmp_read_user_sharing_per` (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `shareduserid` int(11) NOT NULL,
  PRIMARY KEY (`userid`,`tabid`,`shareduserid`),
  KEY `tmp_read_user_sharing_per_userid_shareduserid_idx` (`userid`,`shareduserid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_tmp_read_user_sharing_per: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_tmp_read_user_sharing_per` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_tmp_read_user_sharing_per` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_tmp_write_group_rel_sharing_per
CREATE TABLE IF NOT EXISTS `ncrm_tmp_write_group_rel_sharing_per` (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `relatedtabid` int(11) NOT NULL,
  `sharedgroupid` int(11) NOT NULL,
  PRIMARY KEY (`userid`,`tabid`,`relatedtabid`,`sharedgroupid`),
  KEY `tmp_write_group_rel_sharing_per_userid_sharedgroupid_tabid_idx` (`userid`,`sharedgroupid`,`tabid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_tmp_write_group_rel_sharing_per: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_tmp_write_group_rel_sharing_per` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_tmp_write_group_rel_sharing_per` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_tmp_write_group_sharing_per
CREATE TABLE IF NOT EXISTS `ncrm_tmp_write_group_sharing_per` (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `sharedgroupid` int(11) NOT NULL,
  PRIMARY KEY (`userid`,`tabid`,`sharedgroupid`),
  KEY `tmp_write_group_sharing_per_UK1` (`userid`,`sharedgroupid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_tmp_write_group_sharing_per: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_tmp_write_group_sharing_per` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_tmp_write_group_sharing_per` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_tmp_write_user_rel_sharing_per
CREATE TABLE IF NOT EXISTS `ncrm_tmp_write_user_rel_sharing_per` (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `relatedtabid` int(11) NOT NULL,
  `shareduserid` int(11) NOT NULL,
  PRIMARY KEY (`userid`,`tabid`,`relatedtabid`,`shareduserid`),
  KEY `tmp_write_user_rel_sharing_per_userid_sharduserid_tabid_idx` (`userid`,`shareduserid`,`tabid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_tmp_write_user_rel_sharing_per: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_tmp_write_user_rel_sharing_per` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_tmp_write_user_rel_sharing_per` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_tmp_write_user_sharing_per
CREATE TABLE IF NOT EXISTS `ncrm_tmp_write_user_sharing_per` (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `shareduserid` int(11) NOT NULL,
  PRIMARY KEY (`userid`,`tabid`,`shareduserid`),
  KEY `tmp_write_user_sharing_per_userid_shareduserid_idx` (`userid`,`shareduserid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_tmp_write_user_sharing_per: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_tmp_write_user_sharing_per` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_tmp_write_user_sharing_per` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_tracker
CREATE TABLE IF NOT EXISTS `ncrm_tracker` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(36) DEFAULT NULL,
  `module_name` varchar(25) DEFAULT NULL,
  `item_id` varchar(36) DEFAULT NULL,
  `item_summary` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_tracker: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_tracker` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_tracker` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_tracking_unit
CREATE TABLE IF NOT EXISTS `ncrm_tracking_unit` (
  `tracking_unitid` int(11) NOT NULL AUTO_INCREMENT,
  `tracking_unit` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(11) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT '0',
  PRIMARY KEY (`tracking_unitid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_tracking_unit: ~3 rows (approximately)
/*!40000 ALTER TABLE `ncrm_tracking_unit` DISABLE KEYS */;
INSERT INTO `ncrm_tracking_unit` (`tracking_unitid`, `tracking_unit`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(2, 'Hours', 1, 211, 2),
	(3, 'Days', 1, 212, 3),
	(4, 'Incidents', 1, 213, 4);
/*!40000 ALTER TABLE `ncrm_tracking_unit` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_tracking_unit_seq
CREATE TABLE IF NOT EXISTS `ncrm_tracking_unit_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_tracking_unit_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_tracking_unit_seq` DISABLE KEYS */;
INSERT INTO `ncrm_tracking_unit_seq` (`id`) VALUES
	(4);
/*!40000 ALTER TABLE `ncrm_tracking_unit_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_troubletickets
CREATE TABLE IF NOT EXISTS `ncrm_troubletickets` (
  `ticketid` int(19) NOT NULL,
  `ticket_no` varchar(100) NOT NULL,
  `groupname` varchar(100) DEFAULT NULL,
  `parent_id` varchar(100) DEFAULT NULL,
  `product_id` varchar(100) DEFAULT NULL,
  `priority` varchar(200) DEFAULT NULL,
  `severity` varchar(200) DEFAULT NULL,
  `status` varchar(200) DEFAULT NULL,
  `category` varchar(200) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `solution` text,
  `update_log` text,
  `version_id` int(11) DEFAULT NULL,
  `hours` decimal(25,8) DEFAULT NULL,
  `days` decimal(25,8) DEFAULT NULL,
  `contact_id` int(19) DEFAULT NULL,
  PRIMARY KEY (`ticketid`),
  KEY `troubletickets_ticketid_idx` (`ticketid`),
  KEY `troubletickets_status_idx` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_troubletickets: ~10 rows (approximately)
/*!40000 ALTER TABLE `ncrm_troubletickets` DISABLE KEYS */;
INSERT INTO `ncrm_troubletickets` (`ticketid`, `ticket_no`, `groupname`, `parent_id`, `product_id`, `priority`, `severity`, `status`, `category`, `title`, `solution`, `update_log`, `version_id`, `hours`, `days`, `contact_id`) VALUES
	(2, 'TT1', NULL, '0', '0', '', '', 'In Progress', '', 'ticktet 1', '', '', NULL, 0.00000000, 0.00000000, 0),
	(3, 'TT2', NULL, '0', '0', '', '', 'In Progress', '', 'ticktet 2', '', '', NULL, 0.00000000, 0.00000000, 0),
	(4, 'TT3', NULL, '0', '0', '', '', 'In Progress', '', 'ticktet 3', '', '', NULL, 0.00000000, 0.00000000, 0),
	(5, 'TT4', NULL, '0', '0', '', '', 'In Progress', '', 'ticktet 4', '', '', NULL, 0.00000000, 0.00000000, 0),
	(6, 'TT5', NULL, '0', '0', '', '', 'In Progress', '', 'ticktet 5', '', '', NULL, 0.00000000, 0.00000000, 0),
	(7, 'TT6', NULL, '0', '0', '', '', 'In Progress', '', 'ticktet 6', '', '', NULL, 0.00000000, 0.00000000, 0),
	(8, 'TT7', NULL, '0', '0', '', '', 'In Progress', '', 'ticktet 7', '', '', NULL, 0.00000000, 0.00000000, 0),
	(9, 'TT8', NULL, '0', '0', '', '', 'In Progress', '', 'ticktet 8', '', '', NULL, 0.00000000, 0.00000000, 0),
	(10, 'TT9', NULL, '0', '0', '', '', 'In Progress', '', 'ticktet 9', '', '', NULL, 0.00000000, 0.00000000, 0),
	(11, 'TT10', NULL, '0', '0', '', '', 'In Progress', '', 'ticktet 10', '', '', NULL, 0.00000000, 0.00000000, 0);
/*!40000 ALTER TABLE `ncrm_troubletickets` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_usageunit
CREATE TABLE IF NOT EXISTS `ncrm_usageunit` (
  `usageunitid` int(19) NOT NULL AUTO_INCREMENT,
  `usageunit` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  PRIMARY KEY (`usageunitid`),
  UNIQUE KEY `usageunit_usageunit_idx` (`usageunit`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_usageunit: ~16 rows (approximately)
/*!40000 ALTER TABLE `ncrm_usageunit` DISABLE KEYS */;
INSERT INTO `ncrm_usageunit` (`usageunitid`, `usageunit`, `presence`, `picklist_valueid`, `sortorderid`) VALUES
	(1, 'Box', 1, 194, 0),
	(2, 'Carton', 1, 195, 1),
	(3, 'Dozen', 1, 196, 2),
	(4, 'Each', 1, 197, 3),
	(5, 'Hours', 1, 198, 4),
	(6, 'Impressions', 1, 199, 5),
	(7, 'Lb', 1, 200, 6),
	(8, 'M', 1, 201, 7),
	(9, 'Pack', 1, 202, 8),
	(10, 'Pages', 1, 203, 9),
	(11, 'Pieces', 1, 204, 10),
	(12, 'Quantity', 1, 205, 11),
	(13, 'Reams', 1, 206, 12),
	(14, 'Sheet', 1, 207, 13),
	(15, 'Spiral Binder', 1, 208, 14),
	(16, 'Sq Ft', 1, 209, 15);
/*!40000 ALTER TABLE `ncrm_usageunit` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_usageunit_seq
CREATE TABLE IF NOT EXISTS `ncrm_usageunit_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_usageunit_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_usageunit_seq` DISABLE KEYS */;
INSERT INTO `ncrm_usageunit_seq` (`id`) VALUES
	(16);
/*!40000 ALTER TABLE `ncrm_usageunit_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_user2mergefields
CREATE TABLE IF NOT EXISTS `ncrm_user2mergefields` (
  `userid` int(11) DEFAULT NULL,
  `tabid` int(19) DEFAULT NULL,
  `fieldid` int(19) DEFAULT NULL,
  `visible` int(2) DEFAULT NULL,
  KEY `userid_tabid_idx` (`userid`,`tabid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_user2mergefields: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_user2mergefields` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_user2mergefields` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_user2role
CREATE TABLE IF NOT EXISTS `ncrm_user2role` (
  `userid` int(11) NOT NULL,
  `roleid` varchar(255) NOT NULL,
  PRIMARY KEY (`userid`),
  KEY `user2role_roleid_idx` (`roleid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_user2role: ~2 rows (approximately)
/*!40000 ALTER TABLE `ncrm_user2role` DISABLE KEYS */;
INSERT INTO `ncrm_user2role` (`userid`, `roleid`) VALUES
	(1, 'H2'),
	(5, 'H2'),
	(6, 'H2');
/*!40000 ALTER TABLE `ncrm_user2role` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_users
CREATE TABLE IF NOT EXISTS `ncrm_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) DEFAULT NULL,
  `user_password` varchar(200) DEFAULT NULL,
  `user_hash` varchar(32) DEFAULT NULL,
  `cal_color` varchar(25) DEFAULT '#E6FAD8',
  `first_name` varchar(30) DEFAULT NULL,
  `last_name` varchar(30) DEFAULT NULL,
  `reports_to_id` varchar(36) DEFAULT NULL,
  `is_admin` varchar(3) DEFAULT '0',
  `currency_id` int(19) NOT NULL DEFAULT '1',
  `description` text,
  `date_entered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` varchar(36) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `phone_home` varchar(50) DEFAULT NULL,
  `phone_mobile` varchar(50) DEFAULT NULL,
  `phone_work` varchar(50) DEFAULT NULL,
  `phone_other` varchar(50) DEFAULT NULL,
  `phone_fax` varchar(50) DEFAULT NULL,
  `email1` varchar(100) DEFAULT NULL,
  `email2` varchar(100) DEFAULT NULL,
  `secondaryemail` varchar(100) DEFAULT NULL,
  `status` varchar(25) DEFAULT NULL,
  `signature` text,
  `address_street` varchar(150) DEFAULT NULL,
  `address_city` varchar(100) DEFAULT NULL,
  `address_state` varchar(100) DEFAULT NULL,
  `address_country` varchar(25) DEFAULT NULL,
  `address_postalcode` varchar(9) DEFAULT NULL,
  `user_preferences` text,
  `tz` varchar(30) DEFAULT NULL,
  `holidays` varchar(60) DEFAULT NULL,
  `namedays` varchar(60) DEFAULT NULL,
  `workdays` varchar(30) DEFAULT NULL,
  `weekstart` int(11) DEFAULT NULL,
  `date_format` varchar(200) DEFAULT NULL,
  `hour_format` varchar(30) DEFAULT 'am/pm',
  `start_hour` varchar(30) DEFAULT '10:00',
  `end_hour` varchar(30) DEFAULT '23:00',
  `activity_view` varchar(200) DEFAULT 'Today',
  `lead_view` varchar(200) DEFAULT 'Today',
  `imagename` varchar(250) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `confirm_password` varchar(300) DEFAULT NULL,
  `internal_mailer` varchar(3) NOT NULL DEFAULT '1',
  `reminder_interval` varchar(100) DEFAULT NULL,
  `reminder_next_time` varchar(100) DEFAULT NULL,
  `crypt_type` varchar(20) NOT NULL DEFAULT 'MD5',
  `accesskey` varchar(36) DEFAULT NULL,
  `theme` varchar(100) DEFAULT NULL,
  `language` varchar(36) DEFAULT NULL,
  `time_zone` varchar(200) DEFAULT NULL,
  `currency_grouping_pattern` varchar(100) DEFAULT NULL,
  `currency_decimal_separator` varchar(2) DEFAULT NULL,
  `currency_grouping_separator` varchar(2) DEFAULT NULL,
  `currency_symbol_placement` varchar(20) DEFAULT NULL,
  `phone_crm_extension` varchar(100) DEFAULT NULL,
  `no_of_currency_decimals` varchar(2) DEFAULT NULL,
  `truncate_trailing_zeros` varchar(3) DEFAULT NULL,
  `dayoftheweek` varchar(100) DEFAULT NULL,
  `callduration` varchar(100) DEFAULT NULL,
  `othereventduration` varchar(100) DEFAULT NULL,
  `calendarsharedtype` varchar(100) DEFAULT NULL,
  `default_record_view` varchar(10) DEFAULT NULL,
  `leftpanelhide` varchar(3) DEFAULT NULL,
  `rowheight` varchar(10) DEFAULT NULL,
  `defaulteventstatus` varchar(50) DEFAULT NULL,
  `defaultactivitytype` varchar(50) DEFAULT NULL,
  `hidecompletedevents` int(11) DEFAULT NULL,
  `is_owner` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_user_name_idx` (`user_name`),
  KEY `user_user_password_idx` (`user_password`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_users: ~2 rows (approximately)
/*!40000 ALTER TABLE `ncrm_users` DISABLE KEYS */;
INSERT INTO `ncrm_users` (`id`, `user_name`, `user_password`, `user_hash`, `cal_color`, `first_name`, `last_name`, `reports_to_id`, `is_admin`, `currency_id`, `description`, `date_entered`, `date_modified`, `modified_user_id`, `title`, `department`, `phone_home`, `phone_mobile`, `phone_work`, `phone_other`, `phone_fax`, `email1`, `email2`, `secondaryemail`, `status`, `signature`, `address_street`, `address_city`, `address_state`, `address_country`, `address_postalcode`, `user_preferences`, `tz`, `holidays`, `namedays`, `workdays`, `weekstart`, `date_format`, `hour_format`, `start_hour`, `end_hour`, `activity_view`, `lead_view`, `imagename`, `deleted`, `confirm_password`, `internal_mailer`, `reminder_interval`, `reminder_next_time`, `crypt_type`, `accesskey`, `theme`, `language`, `time_zone`, `currency_grouping_pattern`, `currency_decimal_separator`, `currency_grouping_separator`, `currency_symbol_placement`, `phone_crm_extension`, `no_of_currency_decimals`, `truncate_trailing_zeros`, `dayoftheweek`, `callduration`, `othereventduration`, `calendarsharedtype`, `default_record_view`, `leftpanelhide`, `rowheight`, `defaulteventstatus`, `defaultactivitytype`, `hidecompletedevents`, `is_owner`) VALUES
	(1, 'admin', '$1$ad000000$hzXFXvL3XVlnUE/X.1n9t/', 'd41d8cd98f00b204e9800998ecf8427e', '#E6FAD8', '', 'Administrator', '', 'on', 1, '', '2017-11-25 15:31:50', NULL, NULL, '', '', '', '', '', '', '', 'admin@admin.com', '', '', 'Active', '', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, 'mm-dd-yyyy', '12', '00:00', '23:00', 'This Week', 'Today', 'Mens-Short-Hairstyles-2014-44.jpg', 0, '$1$ad000000$nYTnfhTZRmUP.wQT9y1AE.', '1', '1 Minute', NULL, 'PHP5.3MD5', 'f8g2pzaMEsgTyxSQ', 'softed', 'vn_vi', 'America/Los_Angeles', '123,456,789', '.', ',', '$1.0', '', '2', '1', 'Sunday', '5', '5', 'public', 'Summary', '0', 'medium', 'Select an Option', 'Select an Option', 0, '1'),
	(6, 'trungha', '$1$tr000000$jXi.jfKu4TMpImbbIlMi1/', '202cb962ac59075b964b07152d234b70', '#E6FAD8', '', 'Ha Trung', '', 'off', 1, '', '2017-11-25 21:41:05', NULL, NULL, '', '', '', '', '', '', '', 'trungha@gmail.com', '', '', 'Active', '', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, 'dd-mm-yyyy', '12', '00:00', '', 'Today', 'Today', '23561767_1744470048898467_8006303657467507012_n.jpg', 0, '$1$tr000000$jXi.jfKu4TMpImbbIlMi1/', '0', '', NULL, 'PHP5.3MD5', 'EDEtzQOUYDDE5S4j', 'softed', 'vn_vi', 'Pacific/Midway', '123,456,789', '.', ',', '$1.0', '', '2', '0', 'Monday', '5', '5', 'public', 'Summary', '0', 'medium', 'Select an Option', 'Select an Option', 0, '0');
/*!40000 ALTER TABLE `ncrm_users` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_users2group
CREATE TABLE IF NOT EXISTS `ncrm_users2group` (
  `groupid` int(19) NOT NULL,
  `userid` int(19) NOT NULL,
  PRIMARY KEY (`groupid`,`userid`),
  KEY `users2group_groupname_uerid_idx` (`groupid`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_users2group: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_users2group` DISABLE KEYS */;
INSERT INTO `ncrm_users2group` (`groupid`, `userid`) VALUES
	(3, 1);
/*!40000 ALTER TABLE `ncrm_users2group` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_userscf
CREATE TABLE IF NOT EXISTS `ncrm_userscf` (
  `usersid` int(11) NOT NULL,
  PRIMARY KEY (`usersid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_userscf: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_userscf` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_userscf` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_users_last_import
CREATE TABLE IF NOT EXISTS `ncrm_users_last_import` (
  `id` int(36) NOT NULL AUTO_INCREMENT,
  `assigned_user_id` varchar(36) DEFAULT NULL,
  `bean_type` varchar(36) DEFAULT NULL,
  `bean_id` varchar(36) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`assigned_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_users_last_import: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_users_last_import` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_users_last_import` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_users_seq
CREATE TABLE IF NOT EXISTS `ncrm_users_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_users_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_users_seq` DISABLE KEYS */;
INSERT INTO `ncrm_users_seq` (`id`) VALUES
	(6);
/*!40000 ALTER TABLE `ncrm_users_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_user_module_preferences
CREATE TABLE IF NOT EXISTS `ncrm_user_module_preferences` (
  `userid` int(19) NOT NULL,
  `tabid` int(19) NOT NULL,
  `default_cvid` int(19) NOT NULL,
  PRIMARY KEY (`userid`,`tabid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_user_module_preferences: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_user_module_preferences` DISABLE KEYS */;
INSERT INTO `ncrm_user_module_preferences` (`userid`, `tabid`, `default_cvid`) VALUES
	(1, 4, 50);
/*!40000 ALTER TABLE `ncrm_user_module_preferences` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_vendor
CREATE TABLE IF NOT EXISTS `ncrm_vendor` (
  `vendorid` int(19) NOT NULL DEFAULT '0',
  `vendor_no` varchar(100) NOT NULL,
  `vendorname` varchar(100) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `glacct` varchar(200) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `street` text,
  `city` varchar(30) DEFAULT NULL,
  `state` varchar(30) DEFAULT NULL,
  `pobox` varchar(30) DEFAULT NULL,
  `postalcode` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`vendorid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_vendor: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_vendor` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_vendor` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_vendorcf
CREATE TABLE IF NOT EXISTS `ncrm_vendorcf` (
  `vendorid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`vendorid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_vendorcf: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_vendorcf` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_vendorcf` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_vendorcontactrel
CREATE TABLE IF NOT EXISTS `ncrm_vendorcontactrel` (
  `vendorid` int(19) NOT NULL DEFAULT '0',
  `contactid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`vendorid`,`contactid`),
  KEY `vendorcontactrel_vendorid_idx` (`vendorid`),
  KEY `vendorcontactrel_contact_idx` (`contactid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_vendorcontactrel: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_vendorcontactrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_vendorcontactrel` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_version
CREATE TABLE IF NOT EXISTS `ncrm_version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `old_version` varchar(30) DEFAULT NULL,
  `current_version` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_version: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_version` DISABLE KEYS */;
INSERT INTO `ncrm_version` (`id`, `old_version`, `current_version`) VALUES
	(1, '6.5.0', '6.5.0');
/*!40000 ALTER TABLE `ncrm_version` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_version_seq
CREATE TABLE IF NOT EXISTS `ncrm_version_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_version_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_version_seq` DISABLE KEYS */;
INSERT INTO `ncrm_version_seq` (`id`) VALUES
	(1);
/*!40000 ALTER TABLE `ncrm_version_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_visibility
CREATE TABLE IF NOT EXISTS `ncrm_visibility` (
  `visibilityid` int(19) NOT NULL AUTO_INCREMENT,
  `visibility` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`visibilityid`),
  UNIQUE KEY `visibility_visibility_idx` (`visibility`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_visibility: ~2 rows (approximately)
/*!40000 ALTER TABLE `ncrm_visibility` DISABLE KEYS */;
INSERT INTO `ncrm_visibility` (`visibilityid`, `visibility`, `sortorderid`, `presence`) VALUES
	(1, 'Private', 0, 1),
	(2, 'Public', 1, 1);
/*!40000 ALTER TABLE `ncrm_visibility` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_visibility_seq
CREATE TABLE IF NOT EXISTS `ncrm_visibility_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_visibility_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_visibility_seq` DISABLE KEYS */;
INSERT INTO `ncrm_visibility_seq` (`id`) VALUES
	(2);
/*!40000 ALTER TABLE `ncrm_visibility_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_webforms
CREATE TABLE IF NOT EXISTS `ncrm_webforms` (
  `id` int(19) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `publicid` varchar(100) NOT NULL,
  `enabled` int(1) NOT NULL DEFAULT '1',
  `targetmodule` varchar(50) NOT NULL,
  `description` text,
  `ownerid` int(19) NOT NULL,
  `returnurl` varchar(250) DEFAULT NULL,
  `captcha` int(1) NOT NULL DEFAULT '0',
  `roundrobin` int(1) NOT NULL DEFAULT '0',
  `roundrobin_userid` varchar(256) DEFAULT NULL,
  `roundrobin_logic` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `webformname` (`name`),
  UNIQUE KEY `publicid` (`id`),
  KEY `webforms_webforms_id_idx` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_webforms: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_webforms` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_webforms` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_webforms_field
CREATE TABLE IF NOT EXISTS `ncrm_webforms_field` (
  `id` int(19) NOT NULL AUTO_INCREMENT,
  `webformid` int(19) NOT NULL,
  `fieldname` varchar(50) NOT NULL,
  `neutralizedfield` varchar(50) NOT NULL,
  `defaultvalue` varchar(200) DEFAULT NULL,
  `required` int(10) NOT NULL DEFAULT '0',
  `sequence` int(10) DEFAULT NULL,
  `hidden` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `webforms_webforms_field_idx` (`id`),
  KEY `fk_1_ncrm_webforms_field` (`webformid`),
  KEY `fk_2_ncrm_webforms_field` (`fieldname`),
  CONSTRAINT `fk_1_ncrm_webforms_field` FOREIGN KEY (`webformid`) REFERENCES `ncrm_webforms` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_3_ncrm_webforms_field` FOREIGN KEY (`fieldname`) REFERENCES `ncrm_field` (`fieldname`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_webforms_field: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_webforms_field` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_webforms_field` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_wordtemplates
CREATE TABLE IF NOT EXISTS `ncrm_wordtemplates` (
  `templateid` int(19) NOT NULL,
  `filename` varchar(100) NOT NULL,
  `module` varchar(30) NOT NULL,
  `date_entered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `parent_type` varchar(50) NOT NULL,
  `data` longblob,
  `description` text,
  `filesize` varchar(50) NOT NULL,
  `filetype` varchar(20) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`templateid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_wordtemplates: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_wordtemplates` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_wordtemplates` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_wsapp
CREATE TABLE IF NOT EXISTS `ncrm_wsapp` (
  `appid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `appkey` varchar(255) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`appid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_wsapp: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_wsapp` DISABLE KEYS */;
INSERT INTO `ncrm_wsapp` (`appid`, `name`, `appkey`, `type`) VALUES
	(1, 'ncrmCRM', '5855907b65506', 'user');
/*!40000 ALTER TABLE `ncrm_wsapp` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_wsapp_handlerdetails
CREATE TABLE IF NOT EXISTS `ncrm_wsapp_handlerdetails` (
  `type` varchar(200) NOT NULL,
  `handlerclass` varchar(100) DEFAULT NULL,
  `handlerpath` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_wsapp_handlerdetails: ~5 rows (approximately)
/*!40000 ALTER TABLE `ncrm_wsapp_handlerdetails` DISABLE KEYS */;
INSERT INTO `ncrm_wsapp_handlerdetails` (`type`, `handlerclass`, `handlerpath`) VALUES
	('Outlook', 'OutlookHandler', 'modules/WSAPP/Handlers/OutlookHandler.php'),
	('ncrmCRM', 'ncrmCRMHandler', 'modules/WSAPP/Handlers/ncrmCRMHandler.php'),
	('ncrmSyncLib', 'WSAPP_NcrmSyncEventHandler', 'modules/WSAPP/synclib/handlers/NcrmSyncEventHandler.php'),
	('Google_ncrmHandler', 'Google_Ncrm_Handler', 'modules/Google/handlers/Ncrm.php'),
	('Google_ncrmSyncHandler', 'Google_NcrmSync_Handler', 'modules/Google/handlers/NcrmSync.php');
/*!40000 ALTER TABLE `ncrm_wsapp_handlerdetails` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_wsapp_queuerecords
CREATE TABLE IF NOT EXISTS `ncrm_wsapp_queuerecords` (
  `syncserverid` int(19) DEFAULT NULL,
  `details` varchar(300) DEFAULT NULL,
  `flag` varchar(100) DEFAULT NULL,
  `appid` int(19) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_wsapp_queuerecords: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_wsapp_queuerecords` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_wsapp_queuerecords` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_wsapp_recordmapping
CREATE TABLE IF NOT EXISTS `ncrm_wsapp_recordmapping` (
  `id` int(19) NOT NULL AUTO_INCREMENT,
  `serverid` varchar(10) DEFAULT NULL,
  `clientid` varchar(255) DEFAULT NULL,
  `clientmodifiedtime` datetime DEFAULT NULL,
  `appid` int(11) DEFAULT NULL,
  `servermodifiedtime` datetime DEFAULT NULL,
  `serverappid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_wsapp_recordmapping: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_wsapp_recordmapping` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_wsapp_recordmapping` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_wsapp_sync_state
CREATE TABLE IF NOT EXISTS `ncrm_wsapp_sync_state` (
  `id` int(19) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `stateencodedvalues` varchar(300) NOT NULL,
  `userid` int(19) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_wsapp_sync_state: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_wsapp_sync_state` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_wsapp_sync_state` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_ws_entity
CREATE TABLE IF NOT EXISTS `ncrm_ws_entity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `handler_path` varchar(255) NOT NULL,
  `handler_class` varchar(64) NOT NULL,
  `ismodule` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_ws_entity: ~35 rows (approximately)
/*!40000 ALTER TABLE `ncrm_ws_entity` DISABLE KEYS */;
INSERT INTO `ncrm_ws_entity` (`id`, `name`, `handler_path`, `handler_class`, `ismodule`) VALUES
	(1, 'Campaigns', 'include/Webservices/NcrmModuleOperation.php', 'NcrmModuleOperation', 1),
	(2, 'Vendors', 'include/Webservices/NcrmModuleOperation.php', 'NcrmModuleOperation', 1),
	(3, 'Faq', 'include/Webservices/NcrmModuleOperation.php', 'NcrmModuleOperation', 1),
	(4, 'Quotes', 'include/Webservices/LineItem/NcrmInventoryOperation.php', 'NcrmInventoryOperation', 1),
	(5, 'PurchaseOrder', 'include/Webservices/LineItem/NcrmInventoryOperation.php', 'NcrmInventoryOperation', 1),
	(6, 'SalesOrder', 'include/Webservices/LineItem/NcrmInventoryOperation.php', 'NcrmInventoryOperation', 1),
	(7, 'Invoice', 'include/Webservices/LineItem/NcrmInventoryOperation.php', 'NcrmInventoryOperation', 1),
	(8, 'PriceBooks', 'include/Webservices/NcrmModuleOperation.php', 'NcrmModuleOperation', 1),
	(9, 'Calendar', 'include/Webservices/NcrmModuleOperation.php', 'NcrmModuleOperation', 1),
	(10, 'Leads', 'include/Webservices/NcrmModuleOperation.php', 'NcrmModuleOperation', 1),
	(11, 'Accounts', 'include/Webservices/NcrmModuleOperation.php', 'NcrmModuleOperation', 1),
	(12, 'Contacts', 'include/Webservices/NcrmModuleOperation.php', 'NcrmModuleOperation', 1),
	(13, 'Potentials', 'include/Webservices/NcrmModuleOperation.php', 'NcrmModuleOperation', 1),
	(14, 'Products', 'include/Webservices/NcrmModuleOperation.php', 'NcrmModuleOperation', 1),
	(15, 'Documents', 'include/Webservices/NcrmModuleOperation.php', 'NcrmModuleOperation', 1),
	(16, 'Emails', 'include/Webservices/NcrmModuleOperation.php', 'NcrmModuleOperation', 1),
	(17, 'HelpDesk', 'include/Webservices/NcrmModuleOperation.php', 'NcrmModuleOperation', 1),
	(18, 'Events', 'include/Webservices/NcrmModuleOperation.php', 'NcrmModuleOperation', 1),
	(19, 'Users', 'include/Webservices/NcrmModuleOperation.php', 'NcrmModuleOperation', 1),
	(20, 'Groups', 'include/Webservices/NcrmActorOperation.php', 'NcrmActorOperation', 0),
	(21, 'Currency', 'include/Webservices/NcrmActorOperation.php', 'NcrmActorOperation', 0),
	(22, 'DocumentFolders', 'include/Webservices/NcrmActorOperation.php', 'NcrmActorOperation', 0),
	(23, 'CompanyDetails', 'include/Webservices/NcrmCompanyDetails.php', 'NcrmCompanyDetails', 0),
	(24, 'PBXManager', 'include/Webservices/NcrmModuleOperation.php', 'NcrmModuleOperation', 1),
	(25, 'ServiceContracts', 'include/Webservices/NcrmModuleOperation.php', 'NcrmModuleOperation', 1),
	(26, 'Services', 'include/Webservices/NcrmModuleOperation.php', 'NcrmModuleOperation', 1),
	(27, 'Assets', 'include/Webservices/NcrmModuleOperation.php', 'NcrmModuleOperation', 1),
	(28, 'ModComments', 'include/Webservices/NcrmModuleOperation.php', 'NcrmModuleOperation', 1),
	(29, 'ProjectMilestone', 'include/Webservices/NcrmModuleOperation.php', 'NcrmModuleOperation', 1),
	(30, 'ProjectTask', 'include/Webservices/NcrmModuleOperation.php', 'NcrmModuleOperation', 1),
	(31, 'Project', 'include/Webservices/NcrmModuleOperation.php', 'NcrmModuleOperation', 1),
	(32, 'SMSNotifier', 'include/Webservices/NcrmModuleOperation.php', 'NcrmModuleOperation', 1),
	(33, 'LineItem', 'include/Webservices/LineItem/NcrmLineItemOperation.php', 'NcrmLineItemOperation', 0),
	(34, 'Tax', 'include/Webservices/LineItem/NcrmTaxOperation.php', 'NcrmTaxOperation', 0),
	(35, 'ProductTaxes', 'include/Webservices/LineItem/NcrmProductTaxesOperation.php', 'NcrmProductTaxesOperation', 0);
/*!40000 ALTER TABLE `ncrm_ws_entity` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_ws_entity_fieldtype
CREATE TABLE IF NOT EXISTS `ncrm_ws_entity_fieldtype` (
  `fieldtypeid` int(19) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(50) NOT NULL,
  `field_name` varchar(50) NOT NULL,
  `fieldtype` varchar(200) NOT NULL,
  PRIMARY KEY (`fieldtypeid`),
  UNIQUE KEY `ncrm_idx_1_tablename_fieldname` (`table_name`,`field_name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_ws_entity_fieldtype: ~10 rows (approximately)
/*!40000 ALTER TABLE `ncrm_ws_entity_fieldtype` DISABLE KEYS */;
INSERT INTO `ncrm_ws_entity_fieldtype` (`fieldtypeid`, `table_name`, `field_name`, `fieldtype`) VALUES
	(1, 'ncrm_attachmentsfolder', 'createdby', 'reference'),
	(2, 'ncrm_organizationdetails', 'logoname', 'file'),
	(3, 'ncrm_organizationdetails', 'phone', 'phone'),
	(4, 'ncrm_organizationdetails', 'fax', 'phone'),
	(5, 'ncrm_organizationdetails', 'website', 'url'),
	(6, 'ncrm_inventoryproductrel', 'productid', 'reference'),
	(7, 'ncrm_inventoryproductrel', 'id', 'reference'),
	(8, 'ncrm_inventoryproductrel', 'incrementondel', 'autogenerated'),
	(9, 'ncrm_producttaxrel', 'productid', 'reference'),
	(10, 'ncrm_producttaxrel', 'taxid', 'reference');
/*!40000 ALTER TABLE `ncrm_ws_entity_fieldtype` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_ws_entity_fieldtype_seq
CREATE TABLE IF NOT EXISTS `ncrm_ws_entity_fieldtype_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_ws_entity_fieldtype_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_ws_entity_fieldtype_seq` DISABLE KEYS */;
INSERT INTO `ncrm_ws_entity_fieldtype_seq` (`id`) VALUES
	(10);
/*!40000 ALTER TABLE `ncrm_ws_entity_fieldtype_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_ws_entity_name
CREATE TABLE IF NOT EXISTS `ncrm_ws_entity_name` (
  `entity_id` int(11) NOT NULL,
  `name_fields` varchar(50) NOT NULL,
  `index_field` varchar(50) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  PRIMARY KEY (`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_ws_entity_name: ~5 rows (approximately)
/*!40000 ALTER TABLE `ncrm_ws_entity_name` DISABLE KEYS */;
INSERT INTO `ncrm_ws_entity_name` (`entity_id`, `name_fields`, `index_field`, `table_name`) VALUES
	(20, 'groupname', 'groupid', 'ncrm_groups'),
	(21, 'currency_name', 'id', 'ncrm_currency_info'),
	(22, 'foldername', 'folderid', 'ncrm_attachmentsfolder'),
	(23, 'organizationname', 'groupid', 'ncrm_organizationdetails'),
	(34, 'taxlabel', 'taxid', 'ncrm_inventorytaxinfo');
/*!40000 ALTER TABLE `ncrm_ws_entity_name` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_ws_entity_referencetype
CREATE TABLE IF NOT EXISTS `ncrm_ws_entity_referencetype` (
  `fieldtypeid` int(19) NOT NULL,
  `type` varchar(25) NOT NULL,
  PRIMARY KEY (`fieldtypeid`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_ws_entity_referencetype: ~8 rows (approximately)
/*!40000 ALTER TABLE `ncrm_ws_entity_referencetype` DISABLE KEYS */;
INSERT INTO `ncrm_ws_entity_referencetype` (`fieldtypeid`, `type`) VALUES
	(5, 'Users'),
	(6, 'Products'),
	(7, 'Invoice'),
	(7, 'PurchaseOrder'),
	(7, 'Quotes'),
	(7, 'SalesOrder'),
	(9, 'Products'),
	(10, 'Tax');
/*!40000 ALTER TABLE `ncrm_ws_entity_referencetype` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_ws_entity_seq
CREATE TABLE IF NOT EXISTS `ncrm_ws_entity_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_ws_entity_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_ws_entity_seq` DISABLE KEYS */;
INSERT INTO `ncrm_ws_entity_seq` (`id`) VALUES
	(35);
/*!40000 ALTER TABLE `ncrm_ws_entity_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_ws_entity_tables
CREATE TABLE IF NOT EXISTS `ncrm_ws_entity_tables` (
  `webservice_entity_id` int(11) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  PRIMARY KEY (`webservice_entity_id`,`table_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_ws_entity_tables: ~7 rows (approximately)
/*!40000 ALTER TABLE `ncrm_ws_entity_tables` DISABLE KEYS */;
INSERT INTO `ncrm_ws_entity_tables` (`webservice_entity_id`, `table_name`) VALUES
	(20, 'ncrm_groups'),
	(21, 'ncrm_currency_info'),
	(22, 'ncrm_attachmentsfolder'),
	(23, 'ncrm_organizationdetails'),
	(33, 'ncrm_inventoryproductrel'),
	(34, 'ncrm_inventorytaxinfo'),
	(35, 'ncrm_producttaxrel');
/*!40000 ALTER TABLE `ncrm_ws_entity_tables` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_ws_fieldinfo
CREATE TABLE IF NOT EXISTS `ncrm_ws_fieldinfo` (
  `id` varchar(64) NOT NULL,
  `property_name` varchar(32) DEFAULT NULL,
  `property_value` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_ws_fieldinfo: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_ws_fieldinfo` DISABLE KEYS */;
INSERT INTO `ncrm_ws_fieldinfo` (`id`, `property_name`, `property_value`) VALUES
	('ncrm_organizationdetails.organization_id', 'upload.path', '1');
/*!40000 ALTER TABLE `ncrm_ws_fieldinfo` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_ws_fieldtype
CREATE TABLE IF NOT EXISTS `ncrm_ws_fieldtype` (
  `fieldtypeid` int(19) NOT NULL AUTO_INCREMENT,
  `uitype` varchar(30) NOT NULL,
  `fieldtype` varchar(200) NOT NULL,
  PRIMARY KEY (`fieldtypeid`),
  UNIQUE KEY `uitype_idx` (`uitype`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_ws_fieldtype: ~39 rows (approximately)
/*!40000 ALTER TABLE `ncrm_ws_fieldtype` DISABLE KEYS */;
INSERT INTO `ncrm_ws_fieldtype` (`fieldtypeid`, `uitype`, `fieldtype`) VALUES
	(1, '15', 'picklist'),
	(2, '16', 'picklist'),
	(3, '19', 'text'),
	(4, '20', 'text'),
	(5, '21', 'text'),
	(6, '24', 'text'),
	(7, '3', 'autogenerated'),
	(8, '11', 'phone'),
	(9, '33', 'multipicklist'),
	(10, '17', 'url'),
	(11, '85', 'skype'),
	(12, '56', 'boolean'),
	(13, '156', 'boolean'),
	(14, '53', 'owner'),
	(15, '61', 'file'),
	(16, '28', 'file'),
	(17, '13', 'email'),
	(18, '71', 'currency'),
	(19, '72', 'currency'),
	(20, '50', 'reference'),
	(21, '51', 'reference'),
	(22, '57', 'reference'),
	(23, '58', 'reference'),
	(24, '73', 'reference'),
	(25, '75', 'reference'),
	(26, '76', 'reference'),
	(27, '78', 'reference'),
	(28, '80', 'reference'),
	(29, '81', 'reference'),
	(30, '101', 'reference'),
	(31, '52', 'reference'),
	(32, '357', 'reference'),
	(33, '59', 'reference'),
	(34, '66', 'reference'),
	(35, '77', 'reference'),
	(36, '68', 'reference'),
	(37, '117', 'reference'),
	(38, '26', 'reference'),
	(39, '10', 'reference');
/*!40000 ALTER TABLE `ncrm_ws_fieldtype` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_ws_operation
CREATE TABLE IF NOT EXISTS `ncrm_ws_operation` (
  `operationid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `handler_path` varchar(255) NOT NULL,
  `handler_method` varchar(64) NOT NULL,
  `type` varchar(8) NOT NULL,
  `prelogin` int(3) NOT NULL,
  PRIMARY KEY (`operationid`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_ws_operation: ~33 rows (approximately)
/*!40000 ALTER TABLE `ncrm_ws_operation` DISABLE KEYS */;
INSERT INTO `ncrm_ws_operation` (`operationid`, `name`, `handler_path`, `handler_method`, `type`, `prelogin`) VALUES
	(1, 'login', 'include/Webservices/Login.php', 'vtws_login', 'POST', 1),
	(2, 'retrieve', 'include/Webservices/Retrieve.php', 'vtws_retrieve', 'GET', 0),
	(3, 'create', 'include/Webservices/Create.php', 'vtws_create', 'POST', 0),
	(4, 'update', 'include/Webservices/Update.php', 'vtws_update', 'POST', 0),
	(5, 'delete', 'include/Webservices/Delete.php', 'vtws_delete', 'POST', 0),
	(6, 'sync', 'include/Webservices/GetUpdates.php', 'vtws_sync', 'GET', 0),
	(7, 'query', 'include/Webservices/Query.php', 'vtws_query', 'GET', 0),
	(8, 'logout', 'include/Webservices/Logout.php', 'vtws_logout', 'POST', 0),
	(9, 'listtypes', 'include/Webservices/ModuleTypes.php', 'vtws_listtypes', 'GET', 0),
	(10, 'getchallenge', 'include/Webservices/AuthToken.php', 'vtws_getchallenge', 'GET', 1),
	(11, 'describe', 'include/Webservices/DescribeObject.php', 'vtws_describe', 'GET', 0),
	(12, 'extendsession', 'include/Webservices/ExtendSession.php', 'vtws_extendSession', 'POST', 1),
	(13, 'convertlead', 'include/Webservices/ConvertLead.php', 'vtws_convertlead', 'POST', 0),
	(14, 'revise', 'include/Webservices/Revise.php', 'vtws_revise', 'POST', 0),
	(15, 'changePassword', 'include/Webservices/ChangePassword.php', 'vtws_changePassword', 'POST', 0),
	(16, 'deleteUser', 'include/Webservices/DeleteUser.php', 'vtws_deleteUser', 'POST', 0),
	(17, 'mobile.fetchallalerts', 'modules/Mobile/api/wsapi.php', 'mobile_ws_fetchAllAlerts', 'POST', 0),
	(18, 'mobile.alertdetailswithmessage', 'modules/Mobile/api/wsapi.php', 'mobile_ws_alertDetailsWithMessage', 'POST', 0),
	(19, 'mobile.fetchmodulefilters', 'modules/Mobile/api/wsapi.php', 'mobile_ws_fetchModuleFilters', 'POST', 0),
	(20, 'mobile.fetchrecord', 'modules/Mobile/api/wsapi.php', 'mobile_ws_fetchRecord', 'POST', 0),
	(21, 'mobile.fetchrecordwithgrouping', 'modules/Mobile/api/wsapi.php', 'mobile_ws_fetchRecordWithGrouping', 'POST', 0),
	(22, 'mobile.filterdetailswithcount', 'modules/Mobile/api/wsapi.php', 'mobile_ws_filterDetailsWithCount', 'POST', 0),
	(23, 'mobile.listmodulerecords', 'modules/Mobile/api/wsapi.php', 'mobile_ws_listModuleRecords', 'POST', 0),
	(24, 'mobile.saverecord', 'modules/Mobile/api/wsapi.php', 'mobile_ws_saveRecord', 'POST', 0),
	(25, 'mobile.syncModuleRecords', 'modules/Mobile/api/wsapi.php', 'mobile_ws_syncModuleRecords', 'POST', 0),
	(26, 'mobile.query', 'modules/Mobile/api/wsapi.php', 'mobile_ws_query', 'POST', 0),
	(27, 'mobile.querywithgrouping', 'modules/Mobile/api/wsapi.php', 'mobile_ws_queryWithGrouping', 'POST', 0),
	(28, 'wsapp_register', 'modules/WSAPP/api/ws/Register.php', 'wsapp_register', 'POST', 0),
	(29, 'wsapp_deregister', 'modules/WSAPP/api/ws/DeRegister.php', 'wsapp_deregister', 'POST', 0),
	(30, 'wsapp_get', 'modules/WSAPP/api/ws/Get.php', 'wsapp_get', 'POST', 0),
	(31, 'wsapp_put', 'modules/WSAPP/api/ws/Put.php', 'wsapp_put', 'POST', 0),
	(32, 'wsapp_map', 'modules/WSAPP/api/ws/Map.php', 'wsapp_map', 'POST', 0),
	(33, 'retrieve_inventory', 'include/Webservices/LineItem/RetrieveInventory.php', 'vtws_retrieve_inventory', 'GET', 0);
/*!40000 ALTER TABLE `ncrm_ws_operation` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_ws_operation_parameters
CREATE TABLE IF NOT EXISTS `ncrm_ws_operation_parameters` (
  `operationid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `type` varchar(64) NOT NULL,
  `sequence` int(11) NOT NULL,
  PRIMARY KEY (`operationid`,`name`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_ws_operation_parameters: ~56 rows (approximately)
/*!40000 ALTER TABLE `ncrm_ws_operation_parameters` DISABLE KEYS */;
INSERT INTO `ncrm_ws_operation_parameters` (`operationid`, `name`, `type`, `sequence`) VALUES
	(1, 'accessKey', 'String', 2),
	(1, 'username', 'String', 1),
	(2, 'id', 'String', 1),
	(3, 'element', 'encoded', 2),
	(3, 'elementType', 'String', 1),
	(4, 'element', 'encoded', 1),
	(5, 'id', 'String', 1),
	(6, 'elementType', 'String', 2),
	(6, 'modifiedTime', 'DateTime', 1),
	(7, 'query', 'String', 1),
	(8, 'sessionName', 'String', 1),
	(9, 'fieldTypeList', 'encoded', 1),
	(10, 'username', 'String', 1),
	(11, 'elementType', 'String', 1),
	(13, 'accountName', 'String', 3),
	(13, 'assignedTo', 'String', 2),
	(13, 'avoidPotential', 'Boolean', 4),
	(13, 'leadId', 'String', 1),
	(13, 'potential', 'Encoded', 5),
	(14, 'element', 'Encoded', 1),
	(15, 'confirmPassword', 'String', 4),
	(15, 'id', 'String', 1),
	(15, 'newPassword', 'String', 3),
	(15, 'oldPassword', 'String', 2),
	(16, 'id', 'String', 1),
	(16, 'newOwnerId', 'String', 2),
	(18, 'alertid', 'string', 1),
	(19, 'module', 'string', 1),
	(20, 'record', 'string', 1),
	(21, 'record', 'string', 1),
	(22, 'filterid', 'string', 1),
	(23, 'elements', 'encoded', 1),
	(24, 'module', 'string', 1),
	(24, 'record', 'string', 2),
	(24, 'values', 'encoded', 3),
	(25, 'module', 'string', 1),
	(25, 'page', 'string', 3),
	(25, 'syncToken', 'string', 2),
	(26, 'module', 'string', 1),
	(26, 'page', 'string', 3),
	(26, 'query', 'string', 2),
	(27, 'module', 'string', 1),
	(27, 'page', 'string', 3),
	(27, 'query', 'string', 2),
	(28, 'synctype', 'string', 2),
	(28, 'type', 'string', 1),
	(29, 'key', 'string', 2),
	(29, 'type', 'string', 1),
	(30, 'key', 'string', 1),
	(30, 'module', 'string', 2),
	(30, 'token', 'string', 3),
	(31, 'element', 'encoded', 2),
	(31, 'key', 'string', 1),
	(32, 'element', 'encoded', 2),
	(32, 'key', 'string', 1),
	(33, 'id', 'String', 1);
/*!40000 ALTER TABLE `ncrm_ws_operation_parameters` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_ws_operation_seq
CREATE TABLE IF NOT EXISTS `ncrm_ws_operation_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_ws_operation_seq: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_ws_operation_seq` DISABLE KEYS */;
INSERT INTO `ncrm_ws_operation_seq` (`id`) VALUES
	(33);
/*!40000 ALTER TABLE `ncrm_ws_operation_seq` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_ws_referencetype
CREATE TABLE IF NOT EXISTS `ncrm_ws_referencetype` (
  `fieldtypeid` int(19) NOT NULL,
  `type` varchar(25) NOT NULL,
  PRIMARY KEY (`fieldtypeid`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_ws_referencetype: ~29 rows (approximately)
/*!40000 ALTER TABLE `ncrm_ws_referencetype` DISABLE KEYS */;
INSERT INTO `ncrm_ws_referencetype` (`fieldtypeid`, `type`) VALUES
	(20, 'Accounts'),
	(21, 'Accounts'),
	(22, 'Contacts'),
	(23, 'Campaigns'),
	(24, 'Accounts'),
	(25, 'Vendors'),
	(26, 'Potentials'),
	(27, 'Quotes'),
	(28, 'SalesOrder'),
	(29, 'Vendors'),
	(30, 'Users'),
	(31, 'Campaigns'),
	(31, 'Users'),
	(32, 'Accounts'),
	(32, 'Contacts'),
	(32, 'Leads'),
	(32, 'Users'),
	(32, 'Vendors'),
	(33, 'Products'),
	(34, 'Accounts'),
	(34, 'Campaigns'),
	(34, 'HelpDesk'),
	(34, 'Leads'),
	(34, 'Potentials'),
	(35, 'Users'),
	(36, 'Accounts'),
	(36, 'Contacts'),
	(37, 'Currency'),
	(38, 'DocumentFolders');
/*!40000 ALTER TABLE `ncrm_ws_referencetype` ENABLE KEYS */;

-- Dumping structure for table newcrm_dev_fb.ncrm_ws_userauthtoken
CREATE TABLE IF NOT EXISTS `ncrm_ws_userauthtoken` (
  `userid` int(19) NOT NULL,
  `token` varchar(36) NOT NULL,
  `expiretime` int(19) NOT NULL,
  PRIMARY KEY (`userid`,`expiretime`),
  UNIQUE KEY `userid_idx` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcrm_dev_fb.ncrm_ws_userauthtoken: ~0 rows (approximately)
/*!40000 ALTER TABLE `ncrm_ws_userauthtoken` DISABLE KEYS */;
/*!40000 ALTER TABLE `ncrm_ws_userauthtoken` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;