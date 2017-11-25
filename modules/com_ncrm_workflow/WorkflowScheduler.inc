<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

require_once ('include/events/VTEntityData.inc');
require_once ('include/QueryGenerator/QueryGenerator.php');
require_once ('include/Zend/Json.php');
require_once ('include/utils/utils.php');
require_once ('modules/com_ncrm_workflow/VTWorkflowUtils.php');
require_once ('modules/com_ncrm_workflow/VTWorkflowManager.inc');
require_once ('modules/com_ncrm_workflow/VTTaskManager.inc');
require_once ('modules/com_ncrm_workflow/VTTaskQueue.inc');
require_once ('modules/com_ncrm_workflow/VTEntityCache.inc');
require_once ('include/Webservices/WebserviceField.php');
require_once ('include/Webservices/EntityMeta.php');
require_once ('include/Webservices/NcrmWebserviceObject.php');
require_once ('include/Webservices/NcrmCRMObject.php');
require_once ('include/Webservices/NcrmCRMObjectMeta.php');
require_once ('include/Webservices/DataTransform.php');
require_once ('include/Webservices/Retrieve.php');