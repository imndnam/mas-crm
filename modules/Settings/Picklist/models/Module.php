<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_Picklist_Module_Model extends Ncrm_Module_Model {

    public function getPickListTableName($fieldName) {
        return 'ncrm_'.$fieldName;
    }

    public function getFieldsByType($type) {
		$presence = array('0','2');

        $fieldModels = parent::getFieldsByType($type);
        $fields = array();
        foreach($fieldModels as $fieldName=>$fieldModel) {
            if(($fieldModel->get('displaytype') != '1' && $fieldName != 'salutationtype') || !in_array($fieldModel->get('presence'),$presence)) {
                continue;
            }
            $fields[$fieldName] = Settings_Picklist_Field_Model::getInstanceFromFieldObject($fieldModel);
        }
        return $fields;
    }

    public function addPickListValues($fieldModel, $newValue, $rolesSelected = array()) {
        $db = PearDatabase::getInstance();
        $pickListFieldName = $fieldModel->getName();
        $id = $db->getUniqueID("ncrm_$pickListFieldName");
        vimport('~~/include/ComboUtil.php');
        $picklist_valueid = getUniquePicklistID();
		$tableName = 'ncrm_'.$pickListFieldName;
		$maxSeqQuery = 'SELECT max(sortorderid) as maxsequence FROM '.$tableName;
		$result = $db->pquery($maxSeqQuery, array());
		$sequence = $db->query_result($result,0,'maxsequence');

        if($fieldModel->isRoleBased()) {
            $sql = 'INSERT INTO '.$tableName.' VALUES (?,?,?,?,?)';
            $db->pquery($sql, array($id, $newValue, 1, $picklist_valueid,++$sequence));
        }else{
            $sql = 'INSERT INTO '.$tableName.' VALUES (?,?,?,?)';
            $db->pquery($sql, array($id, $newValue, ++$sequence, 1));
        }

        if($fieldModel->isRoleBased() && !empty($rolesSelected)) {
            $sql = "select picklistid from ncrm_picklist where name=?";
            $result = $db->pquery($sql, array($pickListFieldName));
            $picklistid = $db->query_result($result,0,"picklistid");
            //add the picklist values to the selected roles
            for($j=0;$j<count($rolesSelected);$j++){
                $roleid = $rolesSelected[$j];

                $sql ="SELECT max(sortid)+1 as sortid
                       FROM ncrm_role2picklist left join ncrm_$pickListFieldName
                           on ncrm_$pickListFieldName.picklist_valueid=ncrm_role2picklist.picklistvalueid
                       WHERE roleid=? and picklistid=?";
                $sortid = $db->query_result($db->pquery($sql, array($roleid, $picklistid)),0,'sortid');

                $sql = "insert into ncrm_role2picklist values(?,?,?,?)";
                $db->pquery($sql, array($roleid, $picklist_valueid, $picklistid, $sortid));
            }

        }
        return array('picklistValueId' => $picklist_valueid,
					'id' => $id);
    }

    public function renamePickListValues($pickListFieldName, $oldValue, $newValue, $moduleName, $id) {
		$db = PearDatabase::getInstance();

		$query = 'SELECT tablename,columnname FROM ncrm_field WHERE fieldname=? and presence IN (0,2)';
		$result = $db->pquery($query, array($pickListFieldName));
		$num_rows = $db->num_rows($result);

		//As older look utf8 characters are pushed as html-entities,and in new utf8 characters are pushed to database
		//so we are checking for both the values
		$primaryKey = Ncrm_Util_Helper::getPickListId($pickListFieldName);
		
		$query = 'UPDATE ' . $this->getPickListTableName($pickListFieldName) . ' SET ' . $pickListFieldName . '=? WHERE '.$primaryKey.' = ?';
		$db->pquery($query, array($newValue, $id));

		for ($i = 0; $i < $num_rows; $i++) {
			$row = $db->query_result_rowdata($result, $i);
			$tableName = $row['tablename'];
			$columnName = $row['columnname'];
			$query = 'UPDATE ' . $tableName . ' SET ' . $columnName . '=? WHERE ' . $columnName . '=?';
			$db->pquery($query, array($newValue, $oldValue));
		}

		$query = "UPDATE ncrm_field SET defaultvalue=? WHERE defaultvalue=? AND columnname=?";
		$db->pquery($query, array($newValue, $oldValue, $columnName));

		vimport('~~/include/utils/CommonUtils.php');

		$query = "UPDATE ncrm_picklist_dependency SET sourcevalue=? WHERE sourcevalue=? AND sourcefield=?";
		$db->pquery($query, array($newValue, $oldValue, $pickListFieldName));
				
		$em = new VTEventsManager($db);
		$data = array();
		$data['fieldname'] = $pickListFieldName;
		$data['oldvalue'] = $oldValue;
		$data['newvalue'] = $newValue;
		$data['module'] = $moduleName;
		$em->triggerEvent('ncrm.picklist.afterrename', $data);
		
		return true;
	}

    public function remove($pickListFieldName , $valueToDeleteId, $replaceValueId , $moduleName) {
        $db = PearDatabase::getInstance();
		if(!is_array($valueToDeleteId)) {
			$valueToDeleteId = array($valueToDeleteId);
		}
		$primaryKey = Ncrm_Util_Helper::getPickListId($pickListFieldName);
		
		$pickListValues = array();
		$valuesOfDeleteIds = "SELECT $pickListFieldName FROM ".$this->getPickListTableName($pickListFieldName)." WHERE $primaryKey IN (".generateQuestionMarks($valueToDeleteId).")";
		$pickListValuesResult = $db->pquery($valuesOfDeleteIds,array($valueToDeleteId));
		$num_rows = $db->num_rows($pickListValuesResult);
		for($i=0;$i<$num_rows;$i++) {
			$pickListValues[] = decode_html($db->query_result($pickListValuesResult,$i,$pickListFieldName)); 
		}
		
		$replaceValueQuery = $db->pquery("SELECT $pickListFieldName FROM ".$this->getPickListTableName($pickListFieldName)." WHERE $primaryKey IN (".generateQuestionMarks($replaceValueId).")",array($replaceValueId));
		$replaceValue = decode_html($db->query_result($replaceValueQuery,0,$pickListFieldName));
		
		//As older look utf8 characters are pushed as html-entities,and in new utf8 characters are pushed to database
		//so we are checking for both the values
                $encodedValueToDelete = array(); 
		foreach ($pickListValues as $key => $value) {
			$encodedValueToDelete[$key]  = Ncrm_Util_Helper::toSafeHTML($value);
		}
		$mergedValuesToDelete = array_merge($pickListValues, $encodedValueToDelete);
		
        $fieldModel = Settings_Picklist_Field_Model::getInstance($pickListFieldName,$this);
        //if role based then we need to delete all the values in role based picklist
        if($fieldModel->isRoleBased()) {
            $picklistValueIdToDelete = array();
            $query = 'SELECT picklist_valueid FROM '.$this->getPickListTableName($pickListFieldName).
                     ' WHERE '.$primaryKey.' IN ('.generateQuestionMarks($valueToDeleteId).')';
            $result = $db->pquery($query,$valueToDeleteId);
            $num_rows = $db->num_rows($result);
            for($i=0;$i<$num_rows;$i++) {
                $picklistValueIdToDelete[] = $db->query_result($result,$i,'picklist_valueid');
            }
            $query = 'DELETE FROM ncrm_role2picklist WHERE picklistvalueid IN ('.generateQuestionMarks($picklistValueIdToDelete).')';
            $db->pquery($query,$picklistValueIdToDelete);
        }

        $query = 'DELETE FROM '. $this->getPickListTableName($pickListFieldName).
					' WHERE '.$primaryKey.' IN ('.  generateQuestionMarks($valueToDeleteId).')';
        $db->pquery($query,$valueToDeleteId);

        vimport('~~/include/utils/CommonUtils.php');
        $tabId = getTabId($moduleName);
        $query = 'DELETE FROM ncrm_picklist_dependency WHERE sourcevalue IN ('. generateQuestionMarks($pickListValues) .')'.
				' AND sourcefield=?';
		$params = array();
		array_push($params, $pickListValues);
		array_push($params, $pickListFieldName);
        $db->pquery($query, $params);

        $query='SELECT tablename,columnname FROM ncrm_field WHERE fieldname=? AND presence in (0,2)';
        $result = $db->pquery($query, array($pickListFieldName));
        $num_row = $db->num_rows($result);

        for($i=0; $i<$num_row; $i++) {
            $row = $db->query_result_rowdata($result, $i);
            $tableName = $row['tablename'];
            $columnName = $row['columnname'];

            $query = 'UPDATE '.$tableName.' SET '.$columnName.'=? WHERE '.$columnName.' IN ('.  generateQuestionMarks($pickListValues).')';
			$params = array($replaceValue);
			array_push($params, $pickListValues);
            $db->pquery($query, $params);
        }
		
		$query = 'UPDATE ncrm_field SET defaultvalue=? WHERE defaultvalue IN ('. generateQuestionMarks($pickListValues) .') AND columnname=?';
		$params = array($replaceValue);
		array_push($params, $pickListValues);
		array_push($params, $columnName);
		$db->pquery($query, $params);
		
		$em = new VTEventsManager($db);
		$data = array();
		$data['fieldname'] = $pickListFieldName;
		$data['valuetodelete'] = $pickListValues;
		$data['replacevalue'] = $replaceValue;
		$data['module'] = $moduleName;
		$em->triggerEvent('ncrm.picklist.afterdelete', $data);

        return true;
    }

    public function enableOrDisableValuesForRole($picklistFieldName, $valuesToEnables, $valuesToDisable, $roleIdList) {
        $db = PearDatabase::getInstance();
        //To disable die On error since we will be doing insert without chekcing
        $dieOnErrorOldValue = $db->dieOnError;
        $db->dieOnError = false;

		$sql = "select picklistid from ncrm_picklist where name=?";
		$result = $db->pquery($sql, array($picklistFieldName));
		$picklistid = $db->query_result($result,0,"picklistid");
		
		$primaryKey = Ncrm_Util_Helper::getPickListId($picklistFieldName);

        $pickListValueList = array_merge($valuesToEnables,$valuesToDisable);
        $pickListValueDetails = array();
        $query = 'SELECT picklist_valueid,'. $picklistFieldName.', '.$primaryKey.
                 ' FROM '.$this->getPickListTableName($picklistFieldName).
                 ' WHERE '.$primaryKey .' IN ('.  generateQuestionMarks($pickListValueList).')';
		$params = array();
		array_push($params, $pickListValueList);

		$result = $db->pquery($query, $params);
		$num_rows = $db->num_rows($result);

        for($i=0; $i<$num_rows; $i++) {
            $row = $db->query_result_rowdata($result,$i);

            $pickListValueDetails[decode_html($row[$primaryKey])] =array('picklistvalueid'=>$row['picklist_valueid'],
																	'picklistid'=>$picklistid);
        }
		$insertValueList = array();
        $deleteValueList = array();
        foreach($roleIdList as $roleId) {
            foreach($valuesToEnables  as $picklistValue) {
		        $valueDetail = $pickListValueDetails[$picklistValue];
				if(empty($valueDetail)){
					 $valueDetail = $pickListValueDetails[Ncrm_Util_Helper::toSafeHTML($picklistValue)];
				}
                $pickListValueId = $valueDetail['picklistvalueid'];
                $picklistId = $valueDetail['picklistid'];
                $insertValueList[] = '("'.$roleId.'","'.$pickListValueId.'","'.$picklistId.'")';
            }

            foreach($valuesToDisable as $picklistValue) {
                $valueDetail = $pickListValueDetails[$picklistValue];
				if(empty($valueDetail)){
					 $valueDetail = $pickListValueDetails[Ncrm_Util_Helper::toSafeHTML($picklistValue)];
				}
                $pickListValueId = $valueDetail['picklistvalueid'];
                $picklistId = $valueDetail['picklistid'];
                $deleteValueList[] = ' ( roleid = "'.$roleId.'" AND '.'picklistvalueid = "'.$pickListValueId.'") ';
            }
        }
		$query = 'INSERT IGNORE INTO ncrm_role2picklist (roleid,picklistvalueid,picklistid) VALUES '.implode(',',$insertValueList);
        $result = $db->pquery($query,array());

		$deleteQuery = 'DELETE FROM ncrm_role2picklist WHERE '.implode(' OR ',$deleteValueList);

		$result = $db->pquery($deleteQuery,array());

        //retaining to older value
        $db->dieOnError = $dieOnErrorOldValue;

    }

    public function updateSequence($pickListFieldName , $picklistValues) {
        $db = PearDatabase::getInstance();

		$primaryKey = Ncrm_Util_Helper::getPickListId($pickListFieldName);
		
        $query = 'UPDATE '.$this->getPickListTableName($pickListFieldName).' SET sortorderid = CASE ';
        foreach($picklistValues as $values => $sequence) {
            $query .= ' WHEN '.$primaryKey.'="'.$values.'" THEN "'.$sequence.'"';
        }
		$query .= ' END';
        $db->pquery($query, array());
    }


    public static function getPicklistSupportedModules() {
         $db = PearDatabase::getInstance();

        // vtlib customization: Ignore disabled modules.
        $query = 'SELECT distinct ncrm_tab.tablabel, ncrm_tab.name as tabname
                  FROM ncrm_tab
                        inner join ncrm_field on ncrm_tab.tabid=ncrm_field.tabid
                  WHERE uitype IN (15,33,16) and ncrm_field.tabid NOT IN (29,10)  and ncrm_tab.presence != 1 and ncrm_field.presence in (0,2)
                  ORDER BY ncrm_tab.tabid ASC';
        // END
        $result = $db->pquery($query, array());

        $modulesModelsList = array();
        while($row = $db->fetch_array($result)){
            $moduleLabel = $row['tablabel'];
            $moduleName  = $row['tabname'];
            $instance = new self();
            $instance->name = $moduleName;
            $instance->label = $moduleLabel;
            $modulesModelsList[] = $instance;
        }
        return $modulesModelsList;
    }


    /**
	 * Static Function to get the instance of Ncrm Module Model for the given id or name
	 * @param mixed id or name of the module
	 */
	public static function getInstance($value) {
		//TODO : add caching
		$instance = false;
		    $moduleObject = parent::getInstance($value);
		    if($moduleObject) {
			$instance = self::getInstanceFromModuleObject($moduleObject);
		    }
		return $instance;
	}

	/**
	 * Function to get the instance of Ncrm Module Model from a given Ncrm_Module object
	 * @param Ncrm_Module $moduleObj
	 * @return Ncrm_Module_Model instance
	 */
	public static function getInstanceFromModuleObject(Ncrm_Module $moduleObj){
		$objectProperties = get_object_vars($moduleObj);
		$moduleModel = new self();
		foreach($objectProperties as $properName=>$propertyValue){
			$moduleModel->$properName = $propertyValue;
		}
		return $moduleModel;
	}
}