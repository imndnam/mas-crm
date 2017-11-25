<?php
/*+********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ********************************************************************************/
 
/**
 * Created on 09-Oct-08
 * this file saves the tooltip information
 */
$fieldid = vtlib_purify($_REQUEST['fieldid']);
$sequence = 1;

deleteOldInfo($fieldid);
echo SaveTooltipInformation($fieldid, $sequence);



/**
 * this function saves the tooltip information
 * @param integer $fieldid - the fieldid of the field
 * @param integer $view - the current view :: 1 by default
 * @param integer $sequence - the starting sequence 
 */
function SaveTooltipInformation($fieldid, $sequence, $view=1){
	global $adb;

	if(empty($fieldid)){
		return "FAILURE";
	}else{
		$checkedFields = array();
		if(!empty($_REQUEST['checkedFields'])){
			$checkedFields = explode(",",$_REQUEST['checkedFields']);
			//add to ncrm_quickview table
			foreach($checkedFields as $checkedField){
				$query = "insert into ncrm_quickview (fieldid,related_fieldid,sequence,currentview) values (?,?,?,?)";
				$adb->pquery($query,array($fieldid, $checkedField, $sequence, $view));
				$sequence++;
			}
		}
		$data = getDetailViewForTooltip($fieldid, $checkedFields);
		return $data;
	}
}

/**
 * this function deletes the old information present in quickview table for that view for that field
 * @param integer $fieldid - the fieldid of the field
 * @param integer $view - the view for which ot remove :: 1 by default
 */
function deleteOldInfo($fieldid, $view=1){
	global $adb;
	//remove from the table
	$query = "delete from ncrm_quickview where fieldid = ? and currentview = ?";
	$adb->pquery($query,array($fieldid,$view));
}

/**
 * this function returns the detailview for tooltip
 * @param integer $fieldid - the fieldid of the field for which you want the detailview
 * @param array $checkedFields - the fields which are selected to be displayed in quickview
 * @return string $data - the formatted quickview data
 */
function getDetailViewForTooltip($fieldid, $checkedFields){
	global $app_strings;
	$labels = array();
	if(!empty($checkedFields)){
		$labels = getFieldLabels($checkedFields);
	}
	$smarty = new ncrmCRM_Smarty;
	$smarty->assign("FIELDID", $fieldid);
	$smarty->assign("APP",$app_strings);
	$smarty->assign("IMAGES", "themes/images/");
	$smarty->assign("LABELS", $labels);
	$smarty->assign("COUNT", count($labels));
	
	$data = $smarty->fetch("modules/Tooltip/DetailQuickView.tpl");
	return $data;
}

/**
 * this function accepts the fieldids array and returns an array of field labels for them
 * @param array $checkedFields - the fieldids array
 * @return array $data - the fieldlabels array
 */
function getFieldLabels($checkedFields){
	global $adb;
	$data = array();
	
	$sql = "select * from ncrm_field where fieldid in (".generateQuestionMarks($checkedFields).") and ncrm_field.presence in (0,2)";
	$result = $adb->pquery($sql,array($checkedFields));
	$count = $adb->num_rows($result);
/**
 * to fix the localization of strings
 *  
 */
	$tabid = $adb->query_result($result, 0, "tabid");
	$module = getTabModuleName($tabid);
	for($i=0;$i<$count;$i++){
		$data[] = getTranslatedString($adb->query_result($result, $i, "fieldlabel"),$module);
	}
	return $data;
}
?>
