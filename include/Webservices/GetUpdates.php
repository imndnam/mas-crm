<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/
require_once 'include/Webservices/Utils.php';
require_once 'include/Webservices/ModuleTypes.php';
require_once 'include/utils/CommonUtils.php';
require_once 'include/Webservices/DescribeObject.php';

	function vtws_sync($mtime,$elementType,$syncType,$user){
		global $adb, $recordString,$modifiedTimeString;
        
		$numRecordsLimit = 100;
		$ignoreModules = array("Users");
		$typed = true;
		$dformat = "Y-m-d H:i:s";
		$datetime = date($dformat, $mtime);
		$setypeArray = array();
		$setypeData = array();
		$setypeHandler = array();
		$setypeNoAccessArray = array();

		$output = array();
		$output["updated"] = array();
		$output["deleted"] = array();
		
		$applicationSync = false;
		if(is_object($syncType) && ($syncType instanceof Users)){
			$user = $syncType;
		} else if($syncType == 'application'){
			$applicationSync = true;
		} else if($syncType == 'userandgroup'){
            $userAndGroupSync = true;
        }

		if($applicationSync && !is_admin($user)){
			throw new WebServiceException(WebServiceErrorCode::$ACCESSDENIED,"Only admin users can perform application sync");
		}
		
		$ownerIds = array($user->id);
        // To get groupids in which this user exist
        if ($userAndGroupSync) {
        $groupresult = $adb->pquery("select groupid from ncrm_users2group where userid=?", array($user->id));
        $numOfRows = $adb->num_rows($groupresult);
        if ($numOfRows > 0) {
            for ($i = 0; $i < $numOfRows; $i++) {
                $ownerIds[count($ownerIds)] = $adb->query_result($groupresult, $i, "groupid");
            }
        }
    }
        // End
    
        
		if(!isset($elementType) || $elementType=='' || $elementType==null){
			$typed=false;
		}


		
		$adb->startTransaction();

		$accessableModules = array();
		$entityModules = array();
		$modulesDetails = vtws_listtypes(null,$user);
		$moduleTypes = $modulesDetails['types'];
		$modulesInformation = $modulesDetails["information"];

		foreach($modulesInformation as $moduleName=>$entityInformation){
		 if($entityInformation["isEntity"])
				$entityModules[] = $moduleName;
		}
		if(!$typed){
			$accessableModules = $entityModules;
		}
		else{
				if(!in_array($elementType,$entityModules))
					throw new WebServiceException(WebServiceErrorCode::$ACCESSDENIED,"Permission to perform the operation is denied");
				$accessableModules[] = $elementType;
		}

		$accessableModules = array_diff($accessableModules,$ignoreModules);

		if(count($accessableModules)<=0)
		{
				$output['lastModifiedTime'] = $mtime;
				$output['more'] = false;
				return $output;
		}

		if($typed){
				$handler = vtws_getModuleHandlerFromName($elementType, $user);
				$moduleMeta = $handler->getMeta();
				$entityDefaultBaseTables = $moduleMeta->getEntityDefaultTableList();
				//since there will be only one base table for all entities
				$baseCRMTable = $entityDefaultBaseTables[0];
				if($elementType=="Calendar" || $elementType=="Events" ){
					$baseCRMTable = getSyncQueryBaseTable($elementType);
				}
		}
		else
		 $baseCRMTable = " ncrm_crmentity ";

		//modifiedtime - next token
		$q = "SELECT modifiedtime FROM $baseCRMTable WHERE  modifiedtime>? and setype IN(".generateQuestionMarks($accessableModules).") ";
		$params = array($datetime);
		foreach($accessableModules as $entityModule){
			if($entityModule == "Events")
				$entityModule = "Calendar";
			$params[] = $entityModule;
		}
		if(!$applicationSync){
			$q .= ' and smownerid IN('.generateQuestionMarks($ownerIds).')';
			$params = array_merge($params,$ownerIds);
		}
		
		$q .=" order by modifiedtime limit $numRecordsLimit";
		$result = $adb->pquery($q,$params);
		
		$modTime = array();
		for($i=0;$i<$adb->num_rows($result);$i++){
			$modTime[] = $adb->query_result($result,$i,'modifiedtime');
		}
		if(!empty($modTime)){
			$maxModifiedTime = max($modTime);
		}
		if(!$maxModifiedTime){
			$maxModifiedTime = $datetime;
		}
		foreach($accessableModules as $elementType){
			$handler = vtws_getModuleHandlerFromName($elementType, $user);
			$moduleMeta = $handler->getMeta();
			$deletedQueryCondition = $moduleMeta->getEntityDeletedQuery();
			preg_match_all("/(?:\s+\w+[ \t\n\r]+)?([^=]+)\s*=([^\s]+|'[^']+')/",$deletedQueryCondition,$deletedFieldDetails);
			$fieldNameDetails = $deletedFieldDetails[1];
			$deleteFieldValues = $deletedFieldDetails[2];
			$deleteColumnNames = array();
			foreach($fieldNameDetails as $tableName_fieldName){
				$fieldComp = explode(".",$tableName_fieldName);
				$deleteColumnNames[$tableName_fieldName] = $fieldComp[1];
			}
			$params = array($moduleMeta->getTabName(),$datetime,$maxModifiedTime);
			

			$queryGenerator = new QueryGenerator($elementType, $user);
			$fields = array();
			$moduleFields = $moduleMeta->getModuleFields();
            $moduleFieldNames = getSelectClauseFields($elementType,$moduleMeta,$user);
			$moduleFieldNames[]='id';
			$queryGenerator->setFields($moduleFieldNames);
			$selectClause = "SELECT ".$queryGenerator->getSelectClauseColumnSQL();
			// adding the fieldnames that are present in the delete condition to the select clause
			// since not all fields present in delete condition will be present in the fieldnames of the module
			foreach($deleteColumnNames as $table_fieldName=>$columnName){
				if(!in_array($columnName,$moduleFieldNames)){
					$selectClause .=", ".$table_fieldName;
				}
			}
			if($elementType=="Emails")
				$fromClause = vtws_getEmailFromClause();
			else
				$fromClause = $queryGenerator->getFromClause();

			$fromClause .= " INNER JOIN (select modifiedtime, crmid,deleted,setype FROM $baseCRMTable WHERE setype=? and modifiedtime >? and modifiedtime<=?";
			if(!$applicationSync){
				$fromClause.= 'and smownerid IN('.generateQuestionMarks($ownerIds).')';
				$params = array_merge($params,$ownerIds);
			}
			$fromClause.= ' ) ncrm_ws_sync ON (ncrm_crmentity.crmid = ncrm_ws_sync.crmid)';
			$q = $selectClause." ".$fromClause;
			$result = $adb->pquery($q, $params);
			$recordDetails = array();
			$deleteRecordDetails = array();
			while($arre = $adb->fetchByAssoc($result)){
				$key = $arre[$moduleMeta->getIdColumn()];
				if(vtws_isRecordDeleted($arre,$deleteColumnNames,$deleteFieldValues)){
					if(!$moduleMeta->hasAccess()){
						continue;
					}
					$output["deleted"][] = vtws_getId($moduleMeta->getEntityId(), $key);
				}
				else{
					if(!$moduleMeta->hasAccess() ||!$moduleMeta->hasPermission(EntityMeta::$RETRIEVE,$key)){
						continue;
					}
					try{
						$output["updated"][] = DataTransform::sanitizeDataWithColumn($arre,$moduleMeta);
					}catch(WebServiceException $e){
						//ignore records the user doesn't have access to.
						continue;
					}catch(Exception $e){
						throw new WebServiceException(WebServiceErrorCode::$INTERNALERROR,"Unknown Error while processing request");
					}
				}
			}
		}

		$q = "SELECT crmid FROM $baseCRMTable WHERE modifiedtime>?  and setype IN(".generateQuestionMarks($accessableModules).")";
		$params = array($maxModifiedTime);

		foreach($accessableModules as $entityModule){
			if($entityModule == "Events")
				$entityModule = "Calendar";
			$params[] = $entityModule;
		}
		if(!$applicationSync){
			$q.='and smownerid IN('.generateQuestionMarks($ownerIds).')';
			$params = array_merge($params,$ownerIds);
		}
		
		$result = $adb->pquery($q,$params);
		if($adb->num_rows($result)>0){
			$output['more'] = true;
		}
		else{
			$output['more'] = false;
		}
		if(!$maxModifiedTime){
			$modifiedtime = $mtime;
		}else{
			$modifiedtime = vtws_getSeconds($maxModifiedTime);
		}
		if(is_string($modifiedtime)){
			$modifiedtime = intval($modifiedtime);
		}
		$output['lastModifiedTime'] = $modifiedtime;

		$error = $adb->hasFailedTransaction();
		$adb->completeTransaction();

		if($error){
			throw new WebServiceException(WebServiceErrorCode::$DATABASEQUERYERROR,
					vtws_getWebserviceTranslatedString('LBL_'.
							WebServiceErrorCode::$DATABASEQUERYERROR));
		}

		VTWS_PreserveGlobal::flush();
		return $output;
	}
	
	function vtws_getSeconds($mtimeString){
		//TODO handle timezone and change time to gmt.
		return strtotime($mtimeString);
	}

	function vtws_isRecordDeleted($recordDetails,$deleteColumnDetails,$deletedValues){
		$deletedRecord = false;
		$i=0;
		foreach($deleteColumnDetails as $tableName_fieldName=>$columnName){
			if($recordDetails[$columnName]!=$deletedValues[$i++]){
				$deletedRecord = true;
				break;
			}
		}
		return $deletedRecord;
	}

	function vtws_getEmailFromClause(){
		$q = "FROM ncrm_activity
				INNER JOIN ncrm_crmentity ON ncrm_activity.activityid = ncrm_crmentity.crmid
				LEFT JOIN ncrm_users ON ncrm_crmentity.smownerid = ncrm_users.id
				LEFT JOIN ncrm_groups ON ncrm_crmentity.smownerid = ncrm_groups.groupid
				LEFT JOIN ncrm_seattachmentsrel ON ncrm_activity.activityid = ncrm_seattachmentsrel.crmid
				LEFT JOIN ncrm_attachments ON ncrm_seattachmentsrel.attachmentsid = ncrm_attachments.attachmentsid
				LEFT JOIN ncrm_email_track ON ncrm_activity.activityid = ncrm_email_track.mailid
				INNER JOIN ncrm_emaildetails ON ncrm_activity.activityid = ncrm_emaildetails.emailid
				LEFT JOIN ncrm_users ncrm_users2 ON ncrm_emaildetails.idlists = ncrm_users2.id
				LEFT JOIN ncrm_groups ncrm_groups2 ON ncrm_emaildetails.idlists = ncrm_groups2.groupid";
		return $q;
	}

	function getSyncQueryBaseTable($elementType){
		if($elementType!="Calendar" && $elementType!="Events"){
			return "ncrm_crmentity";
		}
		else{
			$activityCondition = getCalendarTypeCondition($elementType);
			$query = "ncrm_crmentity INNER JOIN ncrm_activity ON (ncrm_crmentity.crmid = ncrm_activity.activityid and $activityCondition)";
			return $query;
		}
	}

	function getCalendarTypeCondition($elementType){
		if($elementType == "Events")
			$activityCondition = "ncrm_activity.activitytype !='Task' and ncrm_activity.activitytype !='Emails'";
		else
			$activityCondition = "ncrm_activity.activitytype ='Task'";
		return $activityCondition;
	}
    
    function getSelectClauseFields($module,$moduleMeta,$user){
        $moduleFieldNames = $moduleMeta->getModuleFields();
        $inventoryModules = getInventoryModules();
        if(in_array($module, $inventoryModules)){
			
            $fields = vtws_describe('LineItem', $user);
            foreach($fields['fields'] as $field){
                unset($moduleFieldNames[$field['name']]);
            }
			foreach ($moduleFieldNames as $field => $fieldObj){
				if(substr($field, 0, 5) == 'shtax'){
					unset($moduleFieldNames[$field]);
				}
			}
            
        }
        return array_keys($moduleFieldNames);
    }

?>
