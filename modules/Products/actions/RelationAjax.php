<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Products_RelationAjax_Action extends Ncrm_RelationAjax_Action {
	
	function __construct() {
		parent::__construct();
		$this->exposeMethod('addListPrice');
	}
	
	/*
	 * Function to add relation for specified source record id and related record id list
	 * @param <array> $request
	 */
	function addRelation($request) {
		$sourceModule = $request->getModule();
		$sourceRecordId = $request->get('src_record');

		$relatedModule = $request->get('related_module');
		$relatedRecordIdList = $request->get('related_record_list');

		$sourceModuleModel = Ncrm_Module_Model::getInstance($sourceModule);
		$relatedModuleModel = Ncrm_Module_Model::getInstance($relatedModule);
		$relationModel = Ncrm_Relation_Model::getInstance($sourceModuleModel, $relatedModuleModel);
		foreach($relatedRecordIdList as $relatedRecordId) {
			$relationModel->addRelation($sourceRecordId,$relatedRecordId,$listPrice);
			if($relatedModule == 'PriceBooks'){
				$recordModel = Ncrm_Record_Model::getInstanceById($relatedRecordId);
				if ($sourceRecordId && ($sourceModule === 'Products' || $sourceModule === 'Services')) {
					$parentRecordModel = Ncrm_Record_Model::getInstanceById($sourceRecordId, $sourceModule);
					$recordModel->updateListPrice($sourceRecordId, $parentRecordModel->get('unit_price'));
				}
			}
		}		
	}
	
	/**
	 * Function adds Products/Services-PriceBooks Relation
	 * @param type $request
	 */
	function addListPrice($request) {
		$sourceModule = $request->getModule();
		$sourceRecordId = $request->get('src_record');
		$relatedModule =  $request->get('related_module');
		$relInfos = $request->get('relinfo');

		$sourceModuleModel = Ncrm_Module_Model::getInstance($sourceModule);
		$relatedModuleModel = Ncrm_Module_Model::getInstance($relatedModule);
		$relationModel = Ncrm_Relation_Model::getInstance($sourceModuleModel, $relatedModuleModel);
		foreach($relInfos as $relInfo) {
			$price = CurrencyField::convertToDBFormat($relInfo['price'], null, true);
			$relationModel->addListPrice($sourceRecordId, $relInfo['id'], $price);
		}
	}
	
}
?>
