<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

/**
 * Ncrm JS Script Model Class
 */
class Ncrm_JsScript_Model extends Ncrm_Base_Model {

	const DEFAULT_TYPE = 'text/javascript';

	/**
	 * Function to get the type attribute value
	 * @return <String>
	 */
	public function getType() {
		$type = $this->get('type');
		if(empty($type)){
			$type = self::DEFAULT_TYPE;
		}
		return $type;
	}

	/**
	 * Function to get the src attribute value
	 * @return <String>
	 */
	public function getSrc() {
		$src = $this->get('src');
		if(empty($src)) {
            $src = $this->get('linkurl');
		}
		return $src;
	}

	/**
	 * Static Function to get an instance of Ncrm JsScript Model from a given Ncrm_Link object
	 * @param Ncrm_Link $linkObj
	 * @return Ncrm_JsScript_Model instance
	 */
	public static function getInstanceFromLinkObject (Ncrm_Link $linkObj){
		$objectProperties = get_object_vars($linkObj);
		$linkModel = new self();
		foreach($objectProperties as $properName=>$propertyValue){
			$linkModel->$properName = $propertyValue;
		}
		return $linkModel->setData($objectProperties);
	}
}