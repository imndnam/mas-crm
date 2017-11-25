<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/
require_once 'modules/WSAPP/synclib/models/BaseModel.php';

class WSAPP_SyncRecordModel extends WSAPP_BaseModel{

	const WSAPP_CREATE_MODE = 'create';
	const WSAPP_UPDATE_MODE = 'update';
	const WSAPP_DELETE_MODE = 'delete';
	const WSAPP_SAVE_MODE = 'save';
	//SPecifies the module with which the model belong to
	protected $type;

	protected $mode;

	public function getId(){
		return $this->get('id');
	}
	
	public function setId($id){
		return $this->set('id',$id);
	}
	
	public function setModifiedTime($modifiedTime){
		return $this->set('modifiedtime',$modifiedTime);
	}

	public function getModifiedTime(){
		return $this->get('modifiedtime');
	}

	public function setType($type){
		$this->type = $type;
		return $this;
	}

	public function getType(){
		return $this->type;
	}

	public function setMode($mode){
		$this->mode = $mode;
		return $this;
	}

	public function getMode(){
		return $this->mode;
	}

	public function isDeleteMode(){
		return ($this->mode == self::WSAPP_DELETE_MODE) ? true :false;
	}

	public function isCreateMode(){
		return ($this->mode == self::WSAPP_CREATE_MODE) ? true : false;
	}

	public function getSyncIdentificationKey(){
		return $this->get('_syncidentificationkey');
	}

	public function setSyncIdentificationKey($key){
		return $this->set('_syncidentificationkey',$key);
	}

	public static function getInstanceFromValues($recordValues){
		$model = new WSAPP_SyncRecordModel($recordValues);
		return $model;
	}

}
?>
