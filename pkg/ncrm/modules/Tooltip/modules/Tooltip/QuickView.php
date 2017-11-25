<?php
/*********************************************************************************
** The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
* 
 ********************************************************************************/
require_once 'include/utils/utils.php';
require_once 'modules/Tooltip/TooltipUtils.php';

global $mod_strings,$app_strings,$theme,$currentModule;
$smarty=new ncrmCRM_Smarty;
$smarty->assign("MOD",$mod_strings);
$smarty->assign("APP",$app_strings);
$smarty->assign("THEME", $theme);

$module_array=moduleList();
$smarty->assign("MODULES",$module_array);

if(!empty($_REQUEST['formodule'])){
	$fld_module = vtlib_purify($_REQUEST['formodule']);
}
else{
	echo "NO MODULES SELECTED";
	exit;
}
$smarty->assign("MODULE",$fld_module);

$fieldsDropDown = QuickViewFieldList($fld_module);
$smarty->assign("FIELDNAMES",$fieldsDropDown);

if($_REQUEST['mode'] != ''){
	$mode = $_REQUEST['mode'];
}
$smarty->assign("MODE", $mode);
$smarty->assign("FORMODULE", $fld_module);
$smarty->assign("MOD",$mod_strings);

$smarty->display(vtlib_getModuleTemplate($currentModule,'Quickview.tpl'));
?>
