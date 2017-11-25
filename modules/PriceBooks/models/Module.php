<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class PriceBooks_Module_Model extends Ncrm_Module_Model {

	/**
	 * Function returns query for PriceBook-Product relation
	 * @param <Ncrm_Record_Model> $recordModel
	 * @param <Ncrm_Record_Model> $relatedModuleModel
	 * @return <String>
	 */
	function get_pricebook_products($recordModel, $relatedModuleModel) {
		$query = 'SELECT ncrm_products.productid, ncrm_products.productname, ncrm_products.productcode, ncrm_products.commissionrate,
						ncrm_products.qty_per_unit, ncrm_products.unit_price, ncrm_crmentity.crmid, ncrm_crmentity.smownerid,
						ncrm_pricebookproductrel.listprice
				FROM ncrm_products
				INNER JOIN ncrm_pricebookproductrel ON ncrm_products.productid = ncrm_pricebookproductrel.productid
				INNER JOIN ncrm_crmentity on ncrm_crmentity.crmid = ncrm_products.productid
				INNER JOIN ncrm_pricebook on ncrm_pricebook.pricebookid = ncrm_pricebookproductrel.pricebookid
				INNER JOIN ncrm_productcf on ncrm_productcf.productid = ncrm_products.productid
				LEFT JOIN ncrm_users ON ncrm_users.id=ncrm_crmentity.smownerid
				LEFT JOIN ncrm_groups ON ncrm_groups.groupid = ncrm_crmentity.smownerid '
				. Users_Privileges_Model::getNonAdminAccessControlQuery($relatedModuleModel->getName()) .'
				WHERE ncrm_pricebook.pricebookid = '.$recordModel->getId().' and ncrm_crmentity.deleted = 0';
		return $query;
	}


	/**
	 * Function returns query for PriceBooks-Services Relationship
	 * @param <Ncrm_Record_Model> $recordModel
	 * @param <Ncrm_Record_Model> $relatedModuleModel
	 * @return <String>
	 */
	function get_pricebook_services($recordModel, $relatedModuleModel) {
		$query = 'SELECT ncrm_service.serviceid, ncrm_service.servicename, ncrm_service.service_no, ncrm_service.commissionrate,
					ncrm_service.qty_per_unit, ncrm_service.unit_price, ncrm_crmentity.crmid, ncrm_crmentity.smownerid,
					ncrm_pricebookproductrel.listprice
			FROM ncrm_service
			INNER JOIN ncrm_pricebookproductrel on ncrm_service.serviceid = ncrm_pricebookproductrel.productid
			INNER JOIN ncrm_crmentity on ncrm_crmentity.crmid = ncrm_service.serviceid
			INNER JOIN ncrm_pricebook on ncrm_pricebook.pricebookid = ncrm_pricebookproductrel.pricebookid
			INNER JOIN ncrm_servicecf on ncrm_servicecf.serviceid = ncrm_service.serviceid
			LEFT JOIN ncrm_users ON ncrm_users.id=ncrm_crmentity.smownerid
			LEFT JOIN ncrm_groups ON ncrm_groups.groupid = ncrm_crmentity.smownerid '
			. Users_Privileges_Model::getNonAdminAccessControlQuery($relatedModuleModel->getName()) .'
			WHERE ncrm_pricebook.pricebookid = '.$recordModel->getId().' and ncrm_crmentity.deleted = 0';
		return $query;
	}

	/**
	 * Function to get list view query for popup window
	 * @param <String> $sourceModule Parent module
	 * @param <String> $field parent fieldname
	 * @param <Integer> $record parent id
	 * @param <String> $listQuery
	 * @return <String> Listview Query
	 */
	public function getQueryByModuleField($sourceModule, $field, $record, $listQuery, $currencyId = false) {
		$relatedModulesList = array('Products', 'Services');
		if (in_array($sourceModule, $relatedModulesList)) {
			$pos = stripos($listQuery, ' where ');
			if ($currencyId && in_array($field, array('productid', 'serviceid'))) {
				$condition = " ncrm_pricebook.pricebookid IN (SELECT pricebookid FROM ncrm_pricebookproductrel WHERE productid = $record)
								AND ncrm_pricebook.currency_id = $currencyId AND ncrm_pricebook.active = 1";
			} else if($field == 'productsRelatedList') {
				$condition = "ncrm_pricebook.pricebookid NOT IN (SELECT pricebookid FROM ncrm_pricebookproductrel WHERE productid = $record)
								AND ncrm_pricebook.active = 1";
			}
			if ($pos) {
				$split = spliti(' where ', $listQuery);
				$overRideQuery = $split[0] . ' WHERE ' . $split[1] . ' AND ' . $condition;
			} else {
				$overRideQuery = $listQuery . ' WHERE ' . $condition;
			}
			return $overRideQuery;
		}
	}
	
	/**
	 * Function to check whether the module is summary view supported
	 * @return <Boolean> - true/false
	 */
	public function isSummaryViewSupported() {
		return false;
	}
	
	/**
	 * Funtion that returns fields that will be showed in the record selection popup
	 * @return <Array of fields>
	 */
	public function getPopupViewFieldsList() {
		$popupFileds = $this->getSummaryViewFieldsList();
		$reqPopUpFields = array('Currency' => 'currency_id'); 
		foreach ($reqPopUpFields as $fieldLabel => $fieldName) {
			$fieldModel = Ncrm_Field_Model::getInstance($fieldName,$this); 
			if ($fieldModel->getPermissions('readwrite')) { 
				$popupFileds[$fieldName] = $fieldModel; 
			}
		}
		return array_keys($popupFileds);
	}
}