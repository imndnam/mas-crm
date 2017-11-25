<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

abstract class WebserviceEntityOperation{
	protected $user;
	protected $log;
	protected $webserviceObject;
	protected $meta;
	/**
	 *
	 * @var PearDatabase
	 */
	protected $pearDB;
	
	protected static $metaCache = array();
	
	protected function WebserviceEntityOperation($webserviceObject,$user,$adb,$log){
		$this->user = $user;
		$this->log = $log;
		$this->webserviceObject = $webserviceObject;
		$this->pearDB = $adb;
	}
	
	public function create($elementType,$element){
		throw new WebServiceException(WebServiceErrorCode::$OPERATIONNOTSUPPORTED,
		"Operation Create is not supported for this entity");
	}
	
	public function retrieve($id){
		throw new WebServiceException(WebServiceErrorCode::$OPERATIONNOTSUPPORTED,
		"Operation Retrieve is not supported for this entity");
	}
	
	public function update($element){
		throw new WebServiceException(WebServiceErrorCode::$OPERATIONNOTSUPPORTED,
		"Operation Update is not supported for this entity");
	}
	
	public function revise($element){
		throw new WebServiceException(WebServiceErrorCode::$OPERATIONNOTSUPPORTED,
		"Operation Update is not supported for this entity");
	}

	public function delete($id){
		throw new WebServiceException(WebServiceErrorCode::$OPERATIONNOTSUPPORTED,
		"Operation delete is not supported for this entity");
	}
	
	public function query($q){
		throw new WebServiceException(WebServiceErrorCode::$OPERATIONNOTSUPPORTED,
		"Operation query is not supported for this entity");
	}
	
	public function describe($elementType){
		throw new WebServiceException(WebServiceErrorCode::$OPERATIONNOTSUPPORTED,
		"Operation describe is not supported for this entity");
	}
	
	public function relatedIds($id, $relatedModule, $relatedLabel, $relatedHandler=null) {
		throw new WebServiceException(WebServiceErrorCode::$OPERATIONNOTSUPPORTED,
		"Operation relatedIds is not supported for this entity");
	}
	
	function getFieldTypeDetails($webserviceField){
		global $upload_maxsize;
		$typeDetails = array();
		switch($webserviceField->getFieldDataType()){
			case 'reference': $typeDetails['refersTo'] = $webserviceField->getReferenceList();
				break;
			case 'multipicklist':
			case 'picklist': $typeDetails["picklistValues"] = $webserviceField->getPicklistDetails($webserviceField);
				$typeDetails['defaultValue'] = $typeDetails["picklistValues"][0]['value'];
				break;
			case 'file': $maxUploadSize = 0;
				$maxUploadSize = ini_get('upload_max_filesize');
				$maxUploadSize = strtolower($maxUploadSize);
				$maxUploadSize = explode('m',$maxUploadSize);
				$maxUploadSize = $maxUploadSize[0];
				if(!is_numeric($maxUploadSize)){
					$maxUploadSize = 0;
				}
				$maxUploadSize = $maxUploadSize * 1000000;
				if($upload_maxsize > $maxUploadSize){
					$maxUploadSize = $upload_maxsize;
				}
				$typeDetails['maxUploadFileSize'] = $maxUploadSize;
				break;
			case 'date': $typeDetails['format'] = $this->user->date_format;
		}
		return $typeDetails;
	}
	
	function isEditable($webserviceField){
		if(((int)$webserviceField->getDisplayType()) === 2 || strcasecmp($webserviceField->getFieldDataType(),"autogenerated")
			===0 || strcasecmp($webserviceField->getFieldDataType(),"id")===0 || $webserviceField->isReadOnly() == true){
			return false;
		}
		//uitype 70 is ncrm generated fields, such as (of ncrm_crmentity table) createdtime
		//and modified time fields.
		if($webserviceField->getUIType() ==  70 || $webserviceField->getUIType() ==  4){
			return false;
		}
		return true;
	}
	
	function getIdField($label){
		return array('name'=>'id','label'=>$label,'mandatory'=>false,'type'=>'id','editable'=>false,'type'=>
						array('name'=>'autogenerated'),'nullable'=>false,'default'=>"");
	}
	
	/**
	 * @return Intance of EntityMeta class.
	 *
	 */
	abstract public function getMeta();
	abstract protected  function getMetaInstance();
	
}

?>