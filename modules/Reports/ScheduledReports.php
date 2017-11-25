<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

require_once 'modules/Reports/Reports.php';
require_once 'modules/Reports/ReportRun.php';
require_once 'include/Zend/Json.php';

class VTScheduledReport extends Reports {

	var $db;
	var $user;

	var $isScheduled = false;
	var $scheduledInterval = null;
	var $scheduledFormat = null;
	var $scheduledRecipients = null;

	static $SCHEDULED_HOURLY = 1;
	static $SCHEDULED_DAILY = 2;
	static $SCHEDULED_WEEKLY = 3;
	static $SCHEDULED_BIWEEKLY = 4;
	static $SCHEDULED_MONTHLY = 5;
	static $SCHEDULED_ANNUALLY = 6;

	public function  __construct($adb, $user, $reportid="") {
		$this->db	= $adb;
		$this->user = $user;
		$this->id	= $reportid;
		parent::__construct($reportid);
	}

	public function getReportScheduleInfo() {
		global $adb;

		if(!empty($this->id)) {
			$cachedInfo = VTCacheUtils::lookupReport_ScheduledInfo($this->user->id, $this->id);

			if($cachedInfo == false) {
				$result = $adb->pquery('SELECT * FROM ncrm_scheduled_reports WHERE reportid=?', array($this->id));

				if($adb->num_rows($result) > 0) {
					$reportScheduleInfo = $adb->raw_query_result_rowdata($result, 0);

					$scheduledInterval = (!empty($reportScheduleInfo['schedule']))?Zend_Json::decode($reportScheduleInfo['schedule']):array();
					$scheduledRecipients = (!empty($reportScheduleInfo['recipients']))?Zend_Json::decode($reportScheduleInfo['recipients']):array();

					VTCacheUtils::updateReport_ScheduledInfo($this->user->id, $this->id, true, $reportScheduleInfo['format'],
														$scheduledInterval, $scheduledRecipients, $reportScheduleInfo['next_trigger_time']);

					$cachedInfo = VTCacheUtils::lookupReport_ScheduledInfo($this->user->id, $this->id);
				}
			}
			if($cachedInfo) {
				$this->isScheduled			= $cachedInfo['isScheduled'];
				$this->scheduledFormat		= $cachedInfo['scheduledFormat'];
				$this->scheduledInterval	= $cachedInfo['scheduledInterval'];
				$this->scheduledRecipients	= $cachedInfo['scheduledRecipients'];
				$this->scheduledTime		= $cachedInfo['scheduledTime'];
				return true;
			}
		}
		return false;
	}

	public function getRecipientEmails() {
		$recipientsInfo = $this->scheduledRecipients;

		$recipientsList = array();
		if(!empty($recipientsInfo)) {
			if(!empty($recipientsInfo['users'])) {
				$recipientsList = array_merge($recipientsList, $recipientsInfo['users']);
			}

			if(!empty($recipientsInfo['roles'])) {
				foreach($recipientsInfo['roles'] as $roleId) {
					$roleUsers = getRoleUsers($roleId);
					foreach($roleUsers as $userId => $userName) {
						array_push($recipientsList, $userId);
					}
				}
			}

			if(!empty($recipientsInfo['rs'])) {
				foreach($recipientsInfo['rs'] as $roleId) {
					$users = getRoleAndSubordinateUsers($roleId);
					foreach($users as $userId => $userName) {
						array_push($recipientsList, $userId);
					}
				}
			}


			if(!empty($recipientsInfo['groups'])) {
				require_once 'include/utils/GetGroupUsers.php';
				foreach($recipientsInfo['groups'] as $groupId) {
					$userGroups = new GetGroupUsers();
					$userGroups->getAllUsersInGroup($groupId);
					$recipientsList = array_merge($recipientsList, $userGroups->group_users);
				}
			}
		}
		$recipientsEmails = array();
		if(!empty($recipientsList) && count($recipientsList) > 0) {
			foreach($recipientsList as $userId) {
				$userName = getUserFullName($userId);
				$userEmail = getUserEmail($userId);
				if(!in_array($userEmail, $recipientsEmails)) {
					$recipientsEmails[$userName] = $userEmail;
				}
			}
		}
		return $recipientsEmails;
	}

	public function sendEmail() {
		global $currentModule;
		require_once 'vtlib/Ncrm/Mailer.php';

		$ncrmMailer = new Ncrm_Mailer();

		$recipientEmails = $this->getRecipientEmails();
		foreach($recipientEmails as $name => $email) {
			$ncrmMailer->AddAddress($email, $name);
		}

		$currentTime = date('Y-m-d H:i:s');
		$subject = $this->reportname .' - '. $currentTime .' ('. DateTimeField::getDBTimeZone() .')';

		$contents = getTranslatedString('LBL_AUTO_GENERATED_REPORT_EMAIL', $currentModule) .'<br/><br/>';
		$contents .= '<b>'.getTranslatedString('LBL_REPORT_NAME', $currentModule) .' :</b> '. $this->reportname .'<br/>';
		$contents .= '<b>'.getTranslatedString('LBL_DESCRIPTION', $currentModule) .' :</b><br/>'. $this->reportdescription .'<br/><br/>';

		$ncrmMailer->Subject = $subject;
		$ncrmMailer->Body    = $contents;
		$ncrmMailer->ContentType = "text/html";

		$baseFileName = preg_replace('/[^a-zA-Z0-9_-\s]/', '', $this->reportname).'_'. preg_replace('/[^a-zA-Z0-9_-\s]/', '', $currentTime);

		$oReportRun = ReportRun::getInstance($this->id);
		$reportFormat = $this->scheduledFormat;
		$attachments = array();

		if($reportFormat == 'pdf' || $reportFormat == 'both') {
			$fileName = $baseFileName.'.pdf';
			$filePath = 'storage/'.$fileName;
			$attachments[$fileName] = $filePath;
			$pdf = $oReportRun->getReportPDF();
			$pdf->Output($filePath,'F');
		}
		if ($reportFormat == 'excel' || $reportFormat == 'both') {
			$fileName = $baseFileName.'.xls';
			$filePath = 'storage/'.$fileName;
			$attachments[$fileName] = $filePath;
			$oReportRun->writeReportToExcelFile($filePath);
		}

		foreach($attachments as $attachmentName => $path) {
			$ncrmMailer->AddAttachment($path, $attachmentName);
		}

		$ncrmMailer->Send(true);

		foreach($attachments as $attachmentName => $path) {
			unlink($path);
		}
	}

	public function getNextTriggerTime() {
		$scheduleInfo = $this->scheduledInterval;

		$scheduleType		= $scheduleInfo['scheduletype'];
		$scheduledMonth		= $scheduleInfo['month'];
		$scheduledDayOfMonth= $scheduleInfo['date'];
		$scheduledDayOfWeek = $scheduleInfo['day'];
		$scheduledTime		= $scheduleInfo['time'];
		if(empty($scheduledTime)) {
			$scheduledTime = '10:00';
		} elseif(stripos(':', $scheduledTime) === false) {
			$scheduledTime = $scheduledTime .':00';
		}

		if($scheduleType == VTScheduledReport::$SCHEDULED_HOURLY) {
			return date("Y-m-d H:i:s",strtotime("+1 hour"));
		}
		if($scheduleType == VTScheduledReport::$SCHEDULED_DAILY) {
			return date("Y-m-d H:i:s",strtotime("+ 1 day ".$scheduledTime));
		}
		if($scheduleType == VTScheduledReport::$SCHEDULED_WEEKLY) {
			$weekDays = array('0'=>'Sunday','1'=>'Monday','2'=>'Tuesday','3'=>'Wednesday','4'=>'Thursday','5'=>'Friday','6'=>'Saturday');

			if(date('w',time()) == $scheduledDayOfWeek) {
				return date("Y-m-d H:i:s",strtotime('+1 week '.$scheduledTime));
			} else {
				return date("Y-m-d H:i:s",strtotime($weekDays[$scheduledDayOfWeek].' '.$scheduledTime));
			}
		}
		if($scheduleType == VTScheduledReport::$SCHEDULED_BIWEEKLY) {
			$weekDays = array('0'=>'Sunday','1'=>'Monday','2'=>'Tuesday','3'=>'Wednesday','4'=>'Thursday','5'=>'Friday','6'=>'Saturday');
			if(date('w',time()) == $scheduledDayOfWeek) {
				return date("Y-m-d H:i:s",strtotime('+2 weeks '.$scheduledTime));
			} else {
				return date("Y-m-d H:i:s",strtotime($weekDays[$scheduledDayOfWeek].' '.$scheduledTime));
			}
		}
		if($scheduleType == VTScheduledReport::$SCHEDULED_MONTHLY) {
			$currentTime = time();
			$currentDayOfMonth = date('j',$currentTime);

			if($scheduledDayOfMonth == $currentDayOfMonth) {
				return date("Y-m-d H:i:s",strtotime('+1 month '.$scheduledTime));
			} else {
				$monthInFullText = date('F',$currentTime);
				$yearFullNumberic = date('Y',$currentTime);
				if($scheduledDayOfMonth < $currentDayOfMonth) {
					$nextMonth = date("Y-m-d H:i:s",strtotime('next month'));
					$monthInFullText = date('F',strtotime($nextMonth));
				}
				return date("Y-m-d H:i:s",strtotime($scheduledDayOfMonth.' '.$monthInFullText.' '.$yearFullNumberic.' '.$scheduledTime));
			}
		}
		if($scheduleType == VTScheduledReport::$SCHEDULED_ANNUALLY) {
			$months = array(0=>'January',1=>'February',2=>'March',3=>'April',4=>'May',5=>'June',6=>'July',
								7=>'August',8=>'September',9=>'October',10=>'November',11=>'December');
			$currentTime = time();
			$currentMonth = date('n',$currentTime);
			if(($scheduledMonth+1) == $currentMonth) {
				return date("Y-m-d H:i:s",strtotime('+1 year '.$scheduledTime));
			} else {
				$monthInFullText = $months[$scheduledMonth];
				$yearFullNumberic = date('Y',$currentTime);
				if(($scheduledMonth+1) < $currentMonth) {
					$nextMonth = date("Y-m-d H:i:s",strtotime('next year'));
					$yearFullNumberic = date('Y',strtotime($nextMonth));
				}
				return date("Y-m-d H:i:s",strtotime($scheduledDayOfMonth.' '.$monthInFullText.' '.$yearFullNumberic.' '.$scheduledTime));
			}
		}
	}

	public function updateNextTriggerTime() {
		$adb = $this->db;

		$currentTime = date('Y-m-d H:i:s');
		$scheduledInterval = $this->scheduledInterval;
		$nextTriggerTime = $this->getNextTriggerTime(); // Compute based on the frequency set

		$adb->pquery('UPDATE ncrm_scheduled_reports SET next_trigger_time=? WHERE reportid=?', array($nextTriggerTime, $this->id));
	}

	public static function generateRecipientOption($type, $value, $name='') {
		switch($type) {
			case "users"	:	if(empty($name)) $name = getUserFullName($value);
								$optionName = 'User::'.addslashes(decode_html($name));
								$optionValue = 'users::'.$value;
								break;
			case "groups"	:	if(empty($name)) {
									$groupInfo = getGroupName($value);
									$name = $groupInfo[0];
								}
								$optionName = 'Group::'.addslashes(decode_html($name));
								$optionValue = 'groups::'.$value;
								break;
			case "roles"	:	if(empty($name)) $name = getRoleName ($value);
								$optionName = 'Roles::'.addslashes(decode_html($name));
								$optionValue = 'roles::'.$value;
								break;
			case "rs"		:	if(empty($name)) $name = getRoleName ($value);
								$optionName = 'RoleAndSubordinates::'.addslashes(decode_html($name));
								$optionValue = 'rs::'.$value;
								break;
		}
		return '<option value="'.$optionValue.'">'.$optionName.'</option>';
	}

	public function getSelectedRecipientsHTML() {
		$selectedRecipientsHTML = '';
		if(!empty($this->scheduledRecipients)) {
			foreach($this->scheduledRecipients as $recipientType => $recipients) {
				foreach($recipients as $recipientId) {
					$selectedRecipientsHTML .= VTScheduledReport::generateRecipientOption($recipientType, $recipientId);
				}
			}
		}
		return $selectedRecipientsHTML;
	}

	public static function getAvailableUsersHTML() {
		$userDetails = getAllUserName();
		$usersHTML = '<select id="availableRecipients" name="availableRecipients" multiple size="10" class="small crmFormList">';
		foreach($userDetails as $userId=>$userName) {
			$usersHTML .= VTScheduledReport::generateRecipientOption('users', $userId, $userName);
		}
		$usersHTML .= '</select>';
		return $usersHTML;
	}

	public static function getAvailableGroupsHTML() {
		$grpDetails = getAllGroupName();
		$groupsHTML = '<select id="availableRecipients" name="availableRecipients" multiple size="10" class="small crmFormList">';
		foreach($grpDetails as $groupId=>$groupName) {
			$groupsHTML .= VTScheduledReport::generateRecipientOption('groups', $groupId, $groupName);
		}
		$groupsHTML .= '</select>';
		return $groupsHTML;
	}

	public static function getAvailableRolesHTML() {
		$roleDetails = getAllRoleDetails();
		$rolesHTML = '<select id="availableRecipients" name="availableRecipients" multiple size="10" class="small crmFormList">';
		foreach($roleDetails as $roleId=>$roleInfo) {
			$rolesHTML .= VTScheduledReport::generateRecipientOption('roles', $roleId, $roleInfo[0]);
		}
		$rolesHTML .= '</select>';
		return $rolesHTML;
	}

	public static function getAvailableRolesAndSubordinatesHTML() {
		$roleDetails = getAllRoleDetails();
		$rolesAndSubHTML = '<select id="availableRecipients" name="availableRecipients" multiple size="10" class="small crmFormList">';
		foreach($roleDetails as $roleId=>$roleInfo) {
			$rolesAndSubHTML .= VTScheduledReport::generateRecipientOption('rs', $roleId, $roleInfo[0]);
		}
		$rolesAndSubHTML .= '</select>';
		return $rolesAndSubHTML;
	}

	public static function getScheduledReports($adb, $user) {

		$currentTime = date('Y-m-d H:i:s');
		$result = $adb->pquery("SELECT * FROM ncrm_scheduled_reports
									WHERE next_trigger_time = '' || next_trigger_time <= ?", array($currentTime));

		$scheduledReports = array();
		$noOfScheduledReports = $adb->num_rows($result);
		for($i=0; $i<$noOfScheduledReports; ++$i) {
			$reportScheduleInfo = $adb->raw_query_result_rowdata($result, $i);

			$scheduledInterval = (!empty($reportScheduleInfo['schedule']))?Zend_Json::decode($reportScheduleInfo['schedule']):array();
			$scheduledRecipients = (!empty($reportScheduleInfo['recipients']))?Zend_Json::decode($reportScheduleInfo['recipients']):array();

			$vtScheduledReport = new VTScheduledReport($adb, $user, $reportScheduleInfo['reportid']);
			$vtScheduledReport->isScheduled			= true;
			$vtScheduledReport->scheduledFormat		= $reportScheduleInfo['format'];
			$vtScheduledReport->scheduledInterval	= $scheduledInterval;
			$vtScheduledReport->scheduledRecipients = $scheduledRecipients;
			$vtScheduledReport->scheduledTime		= $reportScheduleInfo['next_trigger_time'];

			$scheduledReports[] = $vtScheduledReport;
		}
		return $scheduledReports;
	}

	public static function runScheduledReports($adb) {
		require_once 'modules/com_ncrm_workflow/VTWorkflowUtils.php';
		$util = new VTWorkflowUtils();
		$adminUser = $util->adminUser();

		global $currentModule, $current_language;
		if(empty($currentModule)) $currentModule = 'Reports';
		if(empty($current_language)) $current_language = 'en_us';

		$scheduledReports = self::getScheduledReports($adb, $adminUser);
		foreach($scheduledReports as $scheduledReport) {
			$scheduledReport->sendEmail();
			$scheduledReport->updateNextTriggerTime();
		}
		$util->revertUser();
	}

}

?>
