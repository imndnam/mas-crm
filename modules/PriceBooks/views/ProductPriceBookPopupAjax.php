<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class PriceBooks_ProductPriceBookPopupAjax_View extends PriceBooks_ProductPriceBookPopup_View {

	public function process (Ncrm_Request $request) {
		$viewer = $this->getViewer ($request);
		$moduleName = $request->getModule();

		$companyDetails = Ncrm_CompanyDetails_Model::getInstanceById();
		$companyLogo = $companyDetails->getLogo();

		$this->initializeListViewContents($request, $viewer);

		$viewer->assign('MODULE_NAME',$moduleName);
		$viewer->assign('COMPANY_LOGO',$companyLogo);
		$viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());
		
		echo $viewer->view('ProductPriceBookPopupContents.tpl', 'PriceBooks', true);
	}
}