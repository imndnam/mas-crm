<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

Class CustomView_EditAjax_View extends Ncrm_IndexAjax_View {

	public function process(Ncrm_Request $request) {
		$viewer = $this->getViewer ($request);
		$moduleName = $request->get('source_module');
		$module = $request->getModule();
		$record = $request->get('record');
                
		$moduleModel = Ncrm_Module_Model::getInstance($moduleName);
		$recordStructureInstance = Ncrm_RecordStructure_Model::getInstanceForModule($moduleModel);

		if(!empty($record)) {
			$customViewModel = CustomView_Record_Model::getInstanceById($record);
			$viewer->assign('MODE', 'edit');
		} else {
			$customViewModel = new CustomView_Record_Model();
            $customViewModel->setModule($moduleName);
			$viewer->assign('MODE', '');
		}

		$viewer->assign('ADVANCE_CRITERIA', $customViewModel->transformToNewAdvancedFilter());
		$viewer->assign('CURRENTDATE', date('Y-n-j'));
		$viewer->assign('DATE_FILTERS', Ncrm_Field_Model::getDateFilterTypes());
		
		if($moduleName == 'Calendar'){
			$advanceFilterOpsByFieldType = Calendar_Field_Model::getAdvancedFilterOpsByFieldType();
		} else{
			$advanceFilterOpsByFieldType = Ncrm_Field_Model::getAdvancedFilterOpsByFieldType();
		}
		$viewer->assign('ADVANCED_FILTER_OPTIONS', Ncrm_Field_Model::getAdvancedFilterOptions());
		$viewer->assign('ADVANCED_FILTER_OPTIONS_BY_TYPE', $advanceFilterOpsByFieldType);
        $dateFilters = Ncrm_Field_Model::getDateFilterTypes();
        foreach($dateFilters as $comparatorKey => $comparatorInfo) {
            $comparatorInfo['startdate'] = DateTimeField::convertToUserFormat($comparatorInfo['startdate']);
            $comparatorInfo['enddate'] = DateTimeField::convertToUserFormat($comparatorInfo['enddate']);
            $comparatorInfo['label'] = vtranslate($comparatorInfo['label'],$module);
            $dateFilters[$comparatorKey] = $comparatorInfo;
        }
        $viewer->assign('DATE_FILTERS', $dateFilters);
		$viewer->assign('RECORD_STRUCTURE_MODEL', $recordStructureInstance);
        $recordStructure = $recordStructureInstance->getStructure();
        // for Inventory module we should now allow item details block
        if(in_array($moduleName, getInventoryModules())){
            $itemsBlock = "LBL_ITEM_DETAILS";
            unset($recordStructure[$itemsBlock]);
        }
		$viewer->assign('RECORD_STRUCTURE', $recordStructure);
		// Added to show event module custom fields
		if($moduleName == 'Calendar'){
        	$relatedModuleName = 'Events';
            $relatedModuleModel = Ncrm_Module_Model::getInstance($relatedModuleName);
            $relatedRecordStructureInstance = Ncrm_RecordStructure_Model::getInstanceForModule($relatedModuleModel);
            $eventBlocksFields = $relatedRecordStructureInstance->getStructure();
            $viewer->assign('EVENT_RECORD_STRUCTURE_MODEL', $relatedRecordStructureInstance);
            $viewer->assign('EVENT_RECORD_STRUCTURE', $eventBlocksFields);
        }
		$viewer->assign('CUSTOMVIEW_MODEL', $customViewModel);
		$viewer->assign('RECORD_ID', $record);
		$viewer->assign('MODULE', $module);
		$viewer->assign('SOURCE_MODULE',$moduleName);
		$viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());
        $viewer->assign('CV_PRIVATE_VALUE', CustomView_Record_Model::CV_STATUS_PRIVATE);
		$viewer->assign('CV_PENDING_VALUE', CustomView_Record_Model::CV_STATUS_PENDING);
        $viewer->assign('CV_PUBLIC_VALUE', CustomView_Record_Model::CV_STATUS_PUBLIC);
		$viewer->assign('MODULE_MODEL',$moduleModel);

		echo $viewer->view('EditView.tpl', $module, true);
	}
}