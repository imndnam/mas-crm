<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Inventory_ServicesPopup_View extends Ncrm_Popup_View {

	/**
	 * Function returns module name for which Popup will be initialized
	 * @param type $request
	 */
	function getModule($request) {
		return 'Services';
	}

	/*
	 * Function to initialize the required data in smarty to display the List View Contents
	 */
	public function initializeListViewContents(Ncrm_Request $request, Ncrm_Viewer $viewer) {
		//src_module value is added just to stop showing inactive services
		$request->set('src_module', $request->getModule());

		parent::initializeListViewContents($request, $viewer);
		$viewer->assign('MODULE', $request->getModule());
		$viewer->assign('GETURL', 'getTaxesURL');
		$viewer->assign('VIEW', 'ServicesPopup');
	}

}