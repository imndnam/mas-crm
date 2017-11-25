<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Ncrm_UI5Embed_View extends Ncrm_Index_View {
	
	protected function preProcessDisplay(Ncrm_Request $request) {}
	
	protected function getUI5EmbedURL(Ncrm_Request $request) {
		return '../index.php?action=index&module=' . $request->getModule();
	}
	
	public function process(Ncrm_Request $request) {
		$viewer = $this->getViewer($request);
		$viewer->assign('UI5_URL', $this->getUI5EmbedURL($request));
		$viewer->view('UI5EmbedView.tpl');
	}
	
}