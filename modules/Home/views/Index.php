<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Home_Index_View extends Ncrm_Index_View {

	function process (Ncrm_Request $request) {
		$viewer = $this->getViewer ($request);
		$moduleName = $request->getModule();
		//$viewer->assign('HOME_PAGES', Home_Page_Model::getAll());
		//$viewer->assign('HOME_PAGE_WIDGETS', Home_Widget_Model::getAll());

		$viewer->view('Index.tpl', $moduleName);
	}
}