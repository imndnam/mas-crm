<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/
include_once dirname(__FILE__) . '/../Alert.php';

/** Events for today alert */
class Mobile_WS_AlertModel_EventsOfMineToday extends Mobile_WS_AlertModel {
	function __construct() {
		parent::__construct();
		$this->name = 'Your events for the day';
		$this->moduleName = 'Calendar';
		$this->refreshRate= 1 * (24* 60 * 60); // 1 day
		$this->description='Alert sent when events are scheduled for the day';
	}
	
	function query() {
		$today = date('Y-m-d');
		$sql = "SELECT crmid, activitytype FROM ncrm_activity INNER JOIN 
				ncrm_crmentity ON ncrm_crmentity.crmid=ncrm_activity.activityid
				WHERE ncrm_crmentity.deleted=0 AND ncrm_crmentity.smownerid=? AND 
				ncrm_activity.activitytype <> 'Emails' AND 
				(ncrm_activity.date_start = '{$today}' OR ncrm_activity.due_date = '{$today}')";
		return $sql;
	}
	
	function queryParameters() {
		return array($this->getUser()->id);
	}
}
