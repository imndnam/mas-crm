<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

//Overrides GetRelatedList : used to get related query
//TODO : Eliminate below hacking solution
include_once 'include/Webservices/Relation.php';
include_once 'vtlib/Ncrm/Module.php';
include_once 'includes/main/WebUI.php';
//sleep(1000);
$webUI = new Ncrm_WebUI();
$webUI->process(new Ncrm_Request($_REQUEST, $_REQUEST));
