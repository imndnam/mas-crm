<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Ncrm_RemoveWidget_Action extends Ncrm_IndexAjax_View {

	public function process(Ncrm_Request $request) {
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$linkId = $request->get('linkid');
		$response = new Ncrm_Response();
		
		if ($request->has('widgetid')) {
			$widget = Ncrm_Widget_Model::getInstanceWithWidgetId($request->get('widgetid'), $currentUser->getId());
		} else {
			$widget = Ncrm_Widget_Model::getInstance($linkId, $currentUser->getId());
		}

		if (!$widget->isDefault()) {
			$widget->remove();
			$response->setResult(array('linkid' => $linkId, 'name' => $widget->getName(), 'url' => $widget->getUrl(), 'title' => vtranslate($widget->getTitle(), $request->getModule())));
		} else {
			$response->setError(vtranslate('LBL_CAN_NOT_REMOVE_DEFAULT_WIDGET', $moduleName));
		}
		$response->emit();
	}
        
        public function validateRequest(Ncrm_Request $request) {
            $request->validateWriteAccess(); 
        } 
}
