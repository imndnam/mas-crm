<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/
	
	function vtws_listtypes($fieldTypeList, $user){
		// Bulk Save Mode: For re-using information
		static $webserviceEntities = false;
		// END

		static $types = array();
		if(!empty($fieldTypeList)) {
			$fieldTypeList = array_map(strtolower, $fieldTypeList);
			sort($fieldTypeList);
			$fieldTypeString = implode(',', $fieldTypeList);
		} else {
			$fieldTypeString = 'all';
		}
		if(!empty($types[$user->id][$fieldTypeString])) {
			return $types[$user->id][$fieldTypeString];
		}
		try{
			global $log;
			/**
			 * @var PearDatabase
			 */
			$db = PearDatabase::getInstance();
			
			vtws_preserveGlobal('current_user',$user);
			//get All the modules the current user is permitted to Access.
			$allModuleNames = getPermittedModuleNames();
			if(array_search('Calendar',$allModuleNames) !== false){
				array_push($allModuleNames,'Events');
			}

			if(!empty($fieldTypeList)) {
				$sql = "SELECT distinct(ncrm_field.tabid) as tabid FROM ncrm_field LEFT JOIN ncrm_ws_fieldtype ON ".
				"ncrm_field.uitype=ncrm_ws_fieldtype.uitype
				 INNER JOIN ncrm_profile2field ON ncrm_field.fieldid = ncrm_profile2field.fieldid
				 INNER JOIN ncrm_def_org_field ON ncrm_def_org_field.fieldid = ncrm_field.fieldid
				 INNER JOIN ncrm_role2profile ON ncrm_profile2field.profileid = ncrm_role2profile.profileid
				 INNER JOIN ncrm_user2role ON ncrm_user2role.roleid = ncrm_role2profile.roleid
				 where ncrm_profile2field.visible=0 and ncrm_def_org_field.visible = 0
				 and ncrm_field.presence in (0,2)
				 and ncrm_user2role.userid=? and fieldtype in (".
				generateQuestionMarks($fieldTypeList).')';
				$params = array();
				$params[] = $user->id;
				foreach($fieldTypeList as $fieldType)
					$params[] = $fieldType;
				$result = $db->pquery($sql, $params);
				$it = new SqlResultIterator($db, $result);
				$moduleList = array();
				foreach ($it as $row) {
					$moduleList[] = getTabModuleName($row->tabid);
				}
				$allModuleNames = array_intersect($moduleList, $allModuleNames);

				$params = $fieldTypeList;

				$sql = "select name from ncrm_ws_entity inner join ncrm_ws_entity_tables on ".
				"ncrm_ws_entity.id=ncrm_ws_entity_tables.webservice_entity_id inner join ".
				"ncrm_ws_entity_fieldtype on ncrm_ws_entity_fieldtype.table_name=".
				"ncrm_ws_entity_tables.table_name where fieldtype=(".
				generateQuestionMarks($fieldTypeList).')';
				$result = $db->pquery($sql, $params);
				$it = new SqlResultIterator($db, $result);
				$entityList = array();
				foreach ($it as $row) {
					$entityList[] = $row->name;
				}
			}
			//get All the CRM entity names.
			if($webserviceEntities === false || !CRMEntity::isBulkSaveMode()) {
				// Bulk Save Mode: For re-using information
				$webserviceEntities = vtws_getWebserviceEntities();
			}

			$accessibleModules = array_values(array_intersect($webserviceEntities['module'],$allModuleNames));
			$entities = $webserviceEntities['entity'];
			$accessibleEntities = array();
			if(empty($fieldTypeList)) {
				foreach($entities as $entity){
					$webserviceObject = NcrmWebserviceObject::fromName($db,$entity);
					$handlerPath = $webserviceObject->getHandlerPath();
					$handlerClass = $webserviceObject->getHandlerClass();

					require_once $handlerPath;
					$handler = new $handlerClass($webserviceObject,$user,$db,$log);
					$meta = $handler->getMeta();
					if($meta->hasAccess()===true){
						array_push($accessibleEntities,$entity);
					}
				}
			}
		}catch(WebServiceException $exception){
			throw $exception;
		}catch(Exception $exception){
			throw new WebServiceException(WebServiceErrorCode::$DATABASEQUERYERROR,
				"An Database error occured while performing the operation");
		}
		
		$default_language = VTWS_PreserveGlobal::getGlobal('default_language');
		global $current_language;
		if(empty($current_language)) $current_language = $default_language;
		$current_language = vtws_preserveGlobal('current_language',$current_language);
		
		$appStrings = return_application_language($current_language);
		$appListString = return_app_list_strings_language($current_language);
		vtws_preserveGlobal('app_strings',$appStrings);
		vtws_preserveGlobal('app_list_strings',$appListString);
		
		$informationArray = array();
		foreach ($accessibleModules as $module) {
			$ncrmModule = ($module == 'Events')? 'Calendar':$module;
			$informationArray[$module] = array('isEntity'=>true,'label'=>getTranslatedString($module,$ncrmModule),
				'singular'=>getTranslatedString('SINGLE_'.$module,$ncrmModule));
		}
		
		foreach ($accessibleEntities as $entity) {
			$label = (isset($appStrings[$entity]))? $appStrings[$entity]:$entity;
			$singular = (isset($appStrings['SINGLE_'.$entity]))? $appStrings['SINGLE_'.$entity]:$entity;
			$informationArray[$entity] = array('isEntity'=>false,'label'=>$label,
				'singular'=>$singular);
		}
		
		VTWS_PreserveGlobal::flush();
		$types[$user->id][$fieldTypeString] = array("types"=>array_merge($accessibleModules,$accessibleEntities),
			'information'=>$informationArray);
		return $types[$user->id][$fieldTypeString];
	}

?>