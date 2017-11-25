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

/*
 * File containing methods to proceed with the ui validation for all the forms
 *
 */
/**
 * Get field validation information
 */
function getDBValidationData($tablearray, $tabid='') {
	return Ncrm_Deprecated::getModuleFieldTypeOfDataInfos($tablearray, $tabid);
  }

?>
