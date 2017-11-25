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
 * CSS Script Model Class
 */
class Ncrm_CssScript_Model extends Ncrm_Base_Model {

	const DEFAULT_REL = 'stylesheet';
	const DEFAULT_MEDIA = 'all';
	const DEFAULT_TYPE = 'text/css';

	const LESS_REL = 'stylesheet/less';

	/**
	 * Function to get the rel attribute value
	 * @return <String>
	 */
	public function getRel(){
		$rel = $this->get('rel');
		if(empty($rel)){
			$rel = self::DEFAULT_REL;
		}
		return $rel;
	}

	/**
	 * Function to get the media attribute value
	 * @return <String>
	 */
	public function getMedia(){
		$media = $this->get('media');
		if(empty($media)){
			$media = self::DEFAULT_MEDIA;
		}
		return $media;
	}

	/**
	 * Function to get the type attribute value
	 * @return <String>
	 */
	public function getType(){
		$type = $this->get('type');
		if(empty($type)){
			$type = self::DEFAULT_TYPE;
		}
		return $type;
	}

	/**
	 * Function to get the href attribute value
	 * @return <String>
	 */
	public function getHref() {
		$href = $this->get('href');
		if(empty($href)) {
			$href = $this->get('linkurl');
		}
		return $href;
	}

	/**
	 * Function to get the instance of CSS Script model from a given Ncrm_Link object
	 * @param Ncrm_Link $linkObj
	 * @return Ncrm_CssScript_Model instance
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
