<?php
/*+*******************************************************************************
 *  The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *
 *********************************************************************************/

/**
 * Description of NcrmTaxMeta
 */
class NcrmTaxMeta extends NcrmCRMActorMeta {
	protected function getTableFieldList($tableName) {
		$tableFieldList = array();

		$factory = WebserviceField::fromArray($this->pearDB,array('tablename'=>$tableName));
		$dbTableFields = $factory->getTableFields();
		foreach ($dbTableFields as $dbField) {
			if($dbField->primary_key){
				if($this->idColumn === null){
					$this->idColumn = $dbField->name;
				}else{
					throw new WebServiceException(WebServiceErrorCode::$UNKOWNENTITY,
						"Entity table with multi column primary key is not supported");
				}
			}
			$field = $this->getFieldArrayFromDBField($dbField,$tableName);
			if(strcasecmp('taxname',$dbField->name) === 0 || strcasecmp('deleted',$dbField->name)) {
				$field['displaytype'] = 2;
			}
			$webserviceField = WebserviceField::fromArray($this->pearDB,$field);
			$fieldDataType = $this->getFieldType($dbField,$tableName);
			if($fieldDataType === null){
				$fieldDataType = $this->getFieldDataTypeFromDBType($dbField->type);
			}
			$webserviceField->setFieldDataType($fieldDataType);
			if(strcasecmp($fieldDataType,'reference') === 0){
				$webserviceField->setReferenceList($this->getReferenceList($dbField));
			}
			array_push($tableFieldList,$webserviceField);
		}
		return $tableFieldList;
	}

	public function getEntityDeletedQuery() {
		return 'ncrm_inventorytaxinfo.deleted=0';
	}

}
?>