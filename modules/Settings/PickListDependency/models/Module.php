<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/
vimport('~~modules/PickList/DependentPickListUtils.php');

class Settings_PickListDependency_Module_Model extends Settings_Ncrm_Module_Model {

	var $baseTable = 'ncrm_picklist_dependency';
	var $baseIndex = 'id';
	var $name = 'PickListDependency';

	/**
	 * Function to get the url for default view of the module
	 * @return <string> - url
	 */
	public function getDefaultUrl() {
		return 'index.php?module=PickListDependency&parent=Settings&view=List';
	}

	/**
	 * Function to get the url for Adding Dependency
	 * @return <string> - url
	 */
	public function getCreateRecordUrl() {
		return "javascript:Settings_PickListDependency_Js.triggerAdd(event)";
	}
    
    public function isPagingSupported() {
        return false;
    }

	public static function getAvailablePicklists($module) {
		return Ncrm_DependencyPicklist::getAvailablePicklists($module);
	}
	
	public static function getPicklistSupportedModules() {
		$adb = PearDatabase::getInstance();

		$query = "SELECT distinct ncrm_field.tabid, ncrm_tab.tablabel, ncrm_tab.name as tabname FROM ncrm_field
						INNER JOIN ncrm_tab ON ncrm_tab.tabid = ncrm_field.tabid
						WHERE uitype IN ('15','16')
						AND ncrm_field.tabid != 29
						AND ncrm_field.displaytype = 1
						AND ncrm_field.presence in ('0','2')
						AND ncrm_field.block != 'NULL'
					GROUP BY ncrm_field.tabid HAVING count(*) > 1";
		// END
		$result = $adb->pquery($query, array());
		while($row = $adb->fetch_array($result)) {
			$modules[$row['tablabel']] = $row['tabname'];
		}
		ksort($modules);
		
        $modulesModelsList = array();
        foreach($modules as $moduleLabel => $moduleName) {
            $instance = new Ncrm_Module_Model();
            $instance->name = $moduleName;
            $instance->label = $moduleLabel;
            $modulesModelsList[] = $instance;
        }
        return $modulesModelsList;
    }
}
