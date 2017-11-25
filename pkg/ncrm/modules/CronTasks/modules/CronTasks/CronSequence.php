<?php
/*+*******************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ********************************************************************************/
global $adb,$log;

$id = $_REQUEST['record'];
$move  = $_REQUEST['move'];
$log->fatal("$id,$move");

if($move == 'Down'){
	$sequence = $adb->pquery("SELECT sequence FROM ncrm_cron_task WHERE id = ?", array($id));
	$oldsequence = $adb->query_result($sequence,0,'sequence');

	$nexttab = $adb->pquery("SELECT sequence,id FROM ncrm_cron_task WHERE sequence > ? ORDER BY SEQUENCE LIMIT 0,1", array($oldsequence));
	$newsequence = $adb->query_result($nexttab,0,'sequence');
	$rightid = $adb->query_result($nexttab,0,'id');

	$adb->pquery("UPDATE ncrm_cron_task set sequence=? WHERE id = ?", array($newsequence, $id));
	$adb->pquery("UPDATE ncrm_cron_task set sequence=? WHERE id = ?", array($oldsequence, $rightid));
}elseif ($move == 'Up') {
	$sequence = $adb->pquery("SELECT sequence FROM ncrm_cron_task WHERE id = ?", array($id));
	$oldsequence = $adb->query_result($sequence,0,'sequence');
          
	$nexttab = $adb->pquery("SELECT sequence,id FROM ncrm_cron_task WHERE sequence < ? ORDER BY SEQUENCE DESC LIMIT 0,1", array($oldsequence));
	$newsequence = $adb->query_result($nexttab,0,'sequence');
	$leftid = $adb->query_result($nexttab,0,'id');

	$adb->pquery("UPDATE ncrm_cron_task SET sequence=? WHERE id = ?", array($newsequence, $id));
	$adb->pquery("UPDATE ncrm_cron_task SET sequence=? WHERE id = ?", array($oldsequence, $leftid));

}
$loc = "Location: index.php?action=CronTasksAjax&file=ListCronJobs&module=CronTasks&directmode=ajax";
header($loc);
?>
