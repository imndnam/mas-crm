<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/
require_once('Smarty_setup.php');

class ConfigEditor_Viewer extends ncrmCRM_Smarty {
	function ConfigEditor_Viewer() {
		parent::ncrmCRM_Smarty();
		
		global $app_strings, $mod_strings, $currentModule, $theme;
		
		$this->assign('CUSTOM_MODULE', true);

		$this->assign('APP', $app_strings);
		$this->assign('MOD', $mod_strings);
		$this->assign('MODULE', $currentModule);
		// TODO: Update Single Module Instance name here.
		$this->assign('SINGLE_MOD', 'SINGLE_'.$currentModule); 
		$this->assign('CATEGORY', 'Settings');
		$this->assign('IMAGE_PATH', "themes/$theme/images/");
		$this->assign('THEME', $theme);
	}
}
?>