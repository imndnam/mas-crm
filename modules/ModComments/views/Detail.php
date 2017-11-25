<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class ModComments_Detail_View extends Ncrm_Detail_View {

	/**
	 * Function to get Ajax is enabled or not
	 * @param Ncrm_Record_Model record model
	 * @return <boolean> true/false
	 */
	public function isAjaxEnabled($recordModel) {
		return false;
	}

}