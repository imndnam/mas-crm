<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_LoginHistory_ListAjax_Action extends Settings_Ncrm_ListAjax_Action{
	
	
	public function getListViewCount(Ncrm_Request $request) {
		$qualifiedModuleName = $request->getModule(false);

		$listViewModel = Settings_Ncrm_ListView_Model::getInstance($qualifiedModuleName);
		
		$searchField = $request->get('search_key');
		$value = $request->get('search_value');
		
		if(!empty($searchField) && !empty($value)) {
			$listViewModel->set('search_key', $searchField);
			$listViewModel->set('search_value', $value);
		}

		return $listViewModel->getListViewCount();
    }
}