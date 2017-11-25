<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class PriceBooks_QuickCreateAjax_View extends Ncrm_QuickCreateAjax_View {

	public function process(Ncrm_Request $request) {
		$moduleName = $request->getModule();

		$viewer = $this->getViewer($request);
		$viewer->assign('IS_RELATION', $request->get('relationOperation'));

		parent::process($request);
	}
}