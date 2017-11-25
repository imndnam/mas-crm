<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

/*
 * Settings Module Model Class
 */
class Settings_Roles_Module_Model extends Settings_Ncrm_Module_Model {

	var $baseTable = 'ncrm_role';
	var $baseIndex = 'roleid';
	var $listFields = array('roleid' => 'Role Id', 'rolename' => 'Name');
	var $name = 'Roles';

	/**
	 * Function to get the url for default view of the module
	 * @return <string> - url
	 */
	public function getDefaultUrl() {
		return 'index.php?module=Roles&parent=Settings&view=Index';
	}

	/**
	 * Function to get the url for Create view of the module
	 * @return <string> - url
	 */
	public function getCreateRecordUrl() {
		return 'index.php?module=Roles&parent=Settings&view=Index';
	}
}
