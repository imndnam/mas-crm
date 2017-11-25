<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

require_once 'include/database/PearDatabase.php';
require_once 'include/utils/utils.php';

function wsapp_getHandler($appType){
    $db = PearDatabase::getInstance();
    $result = $db->pquery("SELECT * FROM ncrm_wsapp_handlerdetails WHERE type=?",array($appType));

    $handlerResult = array();

    if($db->num_rows($result)>0){
        $handlerResult['handlerclass'] = $db->query_result($result,0,'handlerclass');
        $handlerResult['handlerpath'] = $db->query_result($result,0,'handlerpath');
    }
    return $handlerResult;
}

function wsapp_getApplicationName($key){
    $db = PearDatabase::getInstance();

    $result = $db->pquery("SELECT name from ncrm_wsapp WHERE appkey=?",array($key));
    $name = false;
    if($db->num_rows($result)){
        $name = $db->query_result($result,0,'name');
    }
    return $name;
}

function wsapp_getRecordEntityNameIds($entityNames,$modules,$user){
    $entityMetaList = array();
    $db = PearDatabase::getInstance();
    
    if(empty($entityNames)) return;

    if(!is_array($entityNames))
        $entityNames = array($entityNames);
    if(empty($modules))
        return array();
    if(!is_array($modules))
        $modules = array($modules);
    $entityNameIds = array();
    foreach($modules as $moduleName){
        if(empty($entityMetaList[$moduleName])){
            $handler = vtws_getModuleHandlerFromName($moduleName, $user);
            $meta = $handler->getMeta();
            $entityMetaList[$moduleName] = $meta;
        }
        $meta = $entityMetaList[$moduleName];
        $nameFieldsArray = explode(",",$meta->getNameFields());
        if(count($nameFieldsArray)>1){
            $nameFields = "concat(".implode(",' ',",$nameFieldsArray).")";
        }
        else
            $nameFields = $nameFieldsArray[0];

        $query = "SELECT ".$meta->getObectIndexColumn()." as id,$nameFields as entityname FROM ".$meta->getEntityBaseTable()." as moduleentity INNER JOIN ncrm_crmentity as crmentity WHERE $nameFields IN(".generateQuestionMarks($entityNames).") AND crmentity.deleted=0 AND crmentity.crmid = moduleentity.".$meta->getObectIndexColumn()."";
        $result = $db->pquery($query,$entityNames);
        $num_rows = $db->num_rows($result);
        for($i=0;$i<$num_rows;$i++){
            $id = $db->query_result($result, $i,'id');
            $entityName = $db->query_result($result, $i,'entityname');
            $entityNameIds[decode_html($entityName)] = vtws_getWebserviceEntityId($moduleName, $id);
        }
    }
    return $entityNameIds;
}
/***
 * Converts default time zone to specifiedTimeZone
 */

function wsapp_convertDateTimeToTimeZone($dateTime,$toTimeZone){
    global $log,$default_timezone;
    $time_zone = $default_timezone;
    $source_time = date_default_timezone_set($time_zone);
    $sourceDate = date("Y-m-d H:i:s");
    $dest_time = date_default_timezone_set($toTimeZone);
    $destinationDate = date("Y-m-d H:i:s");
    $diff = (strtotime($destinationDate)-strtotime($sourceDate));
    $givenTimeInSec = strtotime($dateTime);
    $modifiedTimeSec = $givenTimeInSec+$diff;
    $display_time = date("Y-m-d H:i:s",$modifiedTimeSec);
    return $display_time;
}

function wsapp_checkIfRecordsAssignToUser($recordsIds,$userIds){
    $assignedRecordIds = array();
    if(!is_array($recordsIds))
        $recordsIds = array($recordsIds);
    if(count($recordsIds)<=0)
        return $assignedRecordIds;
    if(!is_array($userIds))
        $userIds = array($userIds);
    $db = PearDatabase::getInstance();
    $query = "SELECT * FROM ncrm_crmentity where crmid IN (".generateQuestionMarks($recordsIds).") and smownerid in (".generateQuestionMarks($userIds).")";
    $params = array();
    foreach($recordsIds as $id){
        $params[] = $id;
    }
    foreach($userIds as $userId){
        $params[] = $userId;
    }
    $queryResult = $db->pquery($query,$params);
    $num_rows = $db->num_rows($queryResult);
    
    for($i=0;$i<$num_rows;$i++){
        $assignedRecordIds[] = $db->query_result($queryResult,$i,"crmid");
    }
    return $assignedRecordIds;
}

function wsapp_getAppKey($appName){
    $db = PearDatabase::getInstance();
    $query = "SELECT * FROM ncrm_wsapp WHERE name=?";
    $params = array($appName);
    $result = $db->pquery($query,$params);
    $appKey="";
    if($db->num_rows($result)){
        $appKey = $db->query_result($result,0,'appkey');
    }
    return $appKey;
}

function wsapp_getAppSyncType($appKey){
	$db = PearDatabase::getInstance();
    $query = "SELECT type FROM ncrm_wsapp WHERE appkey=?";
    $params = array($appKey);
    $result = $db->pquery($query,$params);
    $syncType="";
    if($db->num_rows($result)>0){
        $syncType = $db->query_result($result,0,'type');
    }
    return $syncType;
}

function wsapp_RegisterHandler($type,$handlerClass,$handlerPath){
	$db = PearDatabase::getInstance();
	$query = "SELECT 1 FROM ncrm_wsapp_handlerdetails where type=?";
	$result = $db->pquery($query,array($type));
	if($db->num_rows($result)>0){
		$saveQuery = "UPDATE ncrm_wsapp_handlerdetails SET handlerclass=?,handlerpath=? WHERE type=?";
		$parameters = array($handlerClass,$handlerPath,$type);
	} else{
		$saveQuery = "INSERT INTO ncrm_wsapp_handlerdetails VALUES(?,?,?)";
		$parameters = array($type,$handlerClass,$handlerPath);
	}
	$db->pquery($saveQuery,$parameters);}

?>
