<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Ncrm_SaveWidgetPositions_Action extends Ncrm_IndexAjax_View {

	public function process(Ncrm_Request $request) {
		$currentUser = Users_Record_Model::getCurrentUserModel();
		
		$positionsMap = $request->get('positionsmap');
		
		if ($positionsMap) {
			foreach ($positionsMap as $id => $position) {
				list ($linkid, $widgetid) = explode('-', $id);
				if ($widgetid) {
					Ncrm_Widget_Model::updateWidgetPosition($position, NULL, $widgetid, $currentUser->getId());
				} else {
					Ncrm_Widget_Model::updateWidgetPosition($position, $linkid, NULL, $currentUser->getId());
				}
			}
		}
		
		$response = new Ncrm_Response();
		$response->setResult(array('Save' => 'OK'));
		$response->emit();
	}
}
