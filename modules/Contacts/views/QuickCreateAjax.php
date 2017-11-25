<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Contacts_QuickCreateAjax_View extends Ncrm_QuickCreateAjax_View {

	public function process(Ncrm_Request $request) {
		$viewer = $this->getViewer($request);

		$moduleName = $request->getModule();
		$moduleModel = Ncrm_Module_Model::getInstance($moduleName);
		$salutationFieldModel = Ncrm_Field_Model::getInstance('salutationtype', $moduleModel);
		$viewer->assign('SALUTATION_FIELD_MODEL', $salutationFieldModel);
		parent::process($request);
	}
}