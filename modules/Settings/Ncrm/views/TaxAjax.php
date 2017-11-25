<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_Ncrm_TaxAjax_View extends Settings_Ncrm_Index_View {
    
    public function process(Ncrm_Request $request) {
		$viewer = $this->getViewer ($request);
		$moduleName = $request->getModule();
		$qualifiedModuleName = $request->getModule(false);
		$taxId = $request->get('taxid');
		$type = $request->get('type');
		
		if(empty($taxId)) {
            $taxRecordModel = new Settings_Ncrm_TaxRecord_Model();
        }else{
            $taxRecordModel = Settings_Ncrm_TaxRecord_Model::getInstanceById($taxId,$type);
        }
		
		$viewer->assign('TAX_TYPE', $type);
		$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
		$viewer->assign('TAX_RECORD_MODEL', $taxRecordModel);

		echo $viewer->view('EditTax.tpl', $qualifiedModuleName, true);
    }
	
}