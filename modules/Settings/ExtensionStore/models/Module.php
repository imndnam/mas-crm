<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Settings_ExtensionStore_Module_Model extends Ncrm_Module_Model {
    
    
    public function getDefaultViewName() {
        return 'ExtensionStore';
    }

    public function getDefaultUrl() {
        return 'index.php?module='.$this->getName().'&parent=Settings&view='.$this->getDefaultViewName();
    }

    public static function getInstance($moduleName='ExtensionStore') {
		$moduleModel = parent::getInstance($moduleName);
		$objectProperties = get_object_vars($moduleModel);

		$instance = new self();
		foreach	($objectProperties as $properName => $propertyValue) {
			$instance->$properName = $propertyValue;
		}
		return $instance;
	}
}