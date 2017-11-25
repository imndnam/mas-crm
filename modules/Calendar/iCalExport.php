<?php

define('_BENNU_VERSION', '0.1');

require_once('include/utils/utils.php');
require_once('modules/Calendar/CalendarCommon.php');
include('modules/Calendar/iCal/iCalendar_rfc2445.php');
include('modules/Calendar/iCal/iCalendar_components.php');
include('modules/Calendar/iCal/iCalendar_properties.php');
include('modules/Calendar/iCal/iCalendar_parameters.php');

//$ical_query = "SELECT * FROM ncrm_activity WHERE (( STATUS != 'Completed' AND STATUS != 'Deferred' ) OR STATUS IS NULL)
//               AND (( eventstatus != 'Held' AND eventstatus != 'Not Held' ) OR eventstatus IS NULL )";

global $current_user,$adb,$default_timezone;
$filename = $_REQUEST['filename'];
$ical_query = "select ncrm_activity.*,ncrm_crmentity.description,ncrm_activity_reminder.reminder_time from ncrm_activity inner join ncrm_crmentity on ncrm_activity.activityid = ncrm_crmentity.crmid " .
	" LEFT JOIN ncrm_activity_reminder ON ncrm_activity_reminder.activity_id=ncrm_activity.activityid AND ncrm_activity_reminder.recurringid=0" .
	" where ncrm_crmentity.deleted = 0 and ncrm_crmentity.smownerid = " . $current_user->id . 
	" and ncrm_activity.activitytype NOT IN ('Emails')";

$calendar_results = $adb->query($ical_query);

// Send the right content type and filename
header ("Content-type: text/calendar");
header('Content-Disposition: attachment; '."filename={$filename}.ics"); 

$todo_fields = getColumnFields('Calendar');
$event_fields = getColumnFields('Events');
$event = $todo = array();
$keys_to_replace = array(
	'events'=>array('taskpriority'),
	'todo'=>array('taskpriority','taskstatus')
);
$keyvals_to_replace = array(
	'events'=>array('taskpriority'=>'priority'),
	'todo'=>array('taskpriority'=>'priority','taskstatus'=>'status')
);
foreach($todo_fields as $key=>$val){
	if(getFieldVisibilityPermission('Calendar',$current_user->id,$key)==0){
		if(!in_array($key,$keys_to_replace['todo'])){
			$todo[$key] = 'yes'; 
		} else {
			$todo[$keyvals_to_replace['todo'][$key]] = 'yes'; 
		}
	}
}
foreach($event_fields as $key=>$val){
	if(getFieldVisibilityPermission('Events',$current_user->id,$key)==0){
		if(!in_array($key,$keys_to_replace['events'])){
			$event[$key] = 'yes'; 
		} else {
			$event[$keyvals_to_replace['events'][$key]] = 'yes'; 
		}
	}
}

$tz = new iCalendar_timezone;
if(!empty($default_timezone)){
	$tzid = split('/',$default_timezone);
} else {
	$default_timezone = date_default_timezone_get();
	$tzid = split('/',$default_timezone);
}

if(!empty($tzid[1])){
	$tz->add_property('TZID', $tzid[1]);
} else {
	$tz->add_property('TZID', $tzid[0]);
}
$tz->add_property('TZOFFSETTO', date('O'));
if(date('I')==1){
	$tz->add_property('DAYLIGHTC', date('I'));
} else {
	$tz->add_property('STANDARDC', date('I'));
}

$myical = new iCalendar;

$myical->add_component($tz);

while (!$calendar_results->EOF) {
    $this_event = $calendar_results->fields;
	$id = $this_event['activityid'];
	$type = $this_event['activitytype'];
	if($type!='Task'){
		$temp = $event;
		foreach($temp as $key=>$val){
			$temp[$key] = $this_event[$key];
		}
		$temp['id'] = $id;
    	$ev = new iCalendar_event;
    	$ev->assign_values($temp);

	    $al = new iCalendar_alarm;
	    $al->assign_values($temp);
	    $ev->add_component($al);
	} else {
		$temp = $todo;
		foreach($temp as $key=>$val){
			$temp[$key] = $this_event[$key];
		}
    	$ev = new iCalendar_todo;
		$ev->assign_values($temp);
	}

    $myical->add_component($ev);
    $calendar_results->MoveNext();
}
// Print the actual calendar
echo $myical->serialize();

?>