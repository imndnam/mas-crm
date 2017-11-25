<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Documents_Detail_View extends Ncrm_Detail_View {
	
	function preProcess(Ncrm_Request $request) {
		$viewer = $this->getViewer($request);
		$viewer->assign('NO_SUMMARY', true);
		parent::preProcess($request);
	}
	
	/**
	 * Function to get Ajax is enabled or not
	 * @param Ncrm_Record_Model record model
	 * @return <boolean> true/false
	 */
	public function isAjaxEnabled($recordModel) {
		return false;
	}

	/**
	 * Function shows basic detail for the record
	 * @param <type> $request
	 */
	function showModuleBasicView($request) {
		return $this->showModuleDetailView($request);
	}

}