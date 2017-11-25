<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

require_once('include/events/SqlResultIterator.inc');

class VTEntityType{
	function __construct($adb, $setype){
		$this->moduleName=$setype;
		require_once("modules/".$setype."/".$setype.".php"); 
		$result = $adb->pquery("select tabid from ncrm_tab where name=?", 
			array($setype));
		$tabId = $adb->query_result($result,0,"tabid");
		$this->tabId = $tabId;
		$this->adb = $adb;
	}
	
	function getTabId(){
		return $this->tabId;
	}
	
	function getModuleName(){
		return $this->moduleName;
	}

	function getFieldNames(){
		$adb = $this->adb;
		$arr = array();
		$result = $adb->pquery("select fieldname from ncrm_field where tabid=? and ncrm_field.presence in (0,2)", 
			array($this->getTabId()));
		$it = new SQLResultIterator($adb, $result);
		foreach($it as $row){
			$arr[] = $row->fieldname;
		}
		return $arr;
	}
	
	function getFieldType($fieldName){
		$adb = $this->adb;
		$result = $adb->pquery("select * from ncrm_field where fieldname=? and tabid=? and ncrm_field.presence in (0,2)", 
			array($fieldName, $this->tabId));
		$uitype = $adb->query_result($result,0,"uitype");
		$fieldType = new VTFieldType();
		if(in_array($uitype, array(50, 51, 73))){
			$fieldType->type = "Related";
			$fieldType->relatedTo = "Accounts";	
		}else if($uitype == 71){
			$fieldType->type = "Number";
		}else{
			$fieldType->type = "String";
		}
		return $fieldType;
	}
	
	function getFieldTypes(){
		$adb = $this->adb;
		$fieldNames = $this->getFieldNames();
		$fieldTypes = array();
		foreach($fieldNames as $fieldName){
			$fieldTypes[$fieldName]=$this->getFieldType($fieldName);
		}
		return $fieldTypes;
	}
	

}

class VTFieldType{
	function toArray(){
		$ro = new ReflectionObject($this);
		$props = $ro->getProperties();
		$arr = array();
		foreach($props as $prop){
			$arr[$prop->getName()]=$prop->getValue($this);
		}
		return $arr;
	}
	
}

?>
