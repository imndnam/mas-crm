<?php
require_once('include/utils/utils.php');
require_once 'vtlib/Ncrm/Module.php';
require_once dirname(__FILE__) .'/ModTracker.php';
class ModTrackerUtils
{
	static function modTrac_changeModuleVisibility($tabid,$status) {
		if($status == 'module_disable'){
			ModTracker::disableTrackingForModule($tabid);
		} else {
			ModTracker::enableTrackingForModule($tabid);
		}
	}
	function modTrac_getModuleinfo(){
		global $adb;
		$query = $adb->pquery("SELECT ncrm_modtracker_tabs.visible,ncrm_tab.name,ncrm_tab.tabid
								FROM ncrm_tab
								LEFT JOIN ncrm_modtracker_tabs ON ncrm_modtracker_tabs.tabid = ncrm_tab.tabid
								WHERE ncrm_tab.isentitytype = 1 AND ncrm_tab.name NOT IN('Emails', 'Webmails')",array());
		$rows = $adb->num_rows($query);

        for($i = 0;$i < $rows; $i++){
			$infomodules[$i]['tabid']  = $adb->query_result($query,$i,'tabid');
			$infomodules[$i]['visible']  = $adb->query_result($query,$i,'visible');
			$infomodules[$i]['name'] = $adb->query_result($query,$i,'name');
		}

		return $infomodules;
	}
}
?>
