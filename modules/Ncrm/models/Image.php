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
 * Ncrm Image Model Class
 */
class Ncrm_Image_Model extends Ncrm_Base_Model {

	/**
	 * Function to get the title of the Image
	 * @return <String>
	 */
	public function getTitle(){
		return $this->get('title');
	}

	/**
	 * Function to get the alternative text for the Image
	 * @return <String>
	 */
	public function getAltText(){
		return $this->get('alt');
	}

	/**
	 * Function to get the Image file path
	 * @return <String>
	 */
	public function getImagePath(){
		return Ncrm_Theme::getImagePath($this->get('imagename'));
	}

	/**
	 * Function to get the Image file name
	 * @return <String>
	 */
	public function getImageFileName(){
		return $this->get('imagename');
	}

}