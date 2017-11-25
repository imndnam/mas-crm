<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Campaigns_RelatedList_View extends Ncrm_RelatedList_View {
	function process(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$relatedModuleName = $request->get('relatedModule');
		$parentId = $request->get('record');
		$label = $request->get('tab_label');
		$parentRecordModel = Ncrm_Record_Model::getInstanceById($parentId, $moduleName);
		$relationListView = Ncrm_RelationListView_Model::getInstance($parentRecordModel, $relatedModuleName, $label);
		$relationModel = $relationListView->getRelationModel();

		$viewer = $this->getViewer($request);
		if (array_key_exists($relatedModuleName, $relationModel->getEmailEnabledModulesInfoForDetailView())) {
			$viewer->assign('CUSTOM_VIEWS', CustomView_Record_Model::getAllByGroup($relatedModuleName));
			$viewer->assign('STATUS_VALUES', $relationModel->getCampaignRelationStatusValues());
			$viewer->assign('SELECTED_IDS', $request->get('selectedIds'));
			$viewer->assign('EXCLUDED_IDS', $request->get('excludedIds'));
		}
		return parent::process($request);
	}
}