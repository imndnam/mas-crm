<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Products_Module_Model extends Ncrm_Module_Model {

	/**
	 * Function to get list view query for popup window
	 * @param <String> $sourceModule Parent module
	 * @param <String> $field parent fieldname
	 * @param <Integer> $record parent id
	 * @param <String> $listQuery
	 * @return <String> Listview Query
	 */
	public function getQueryByModuleField($sourceModule, $field, $record, $listQuery) {
		$supportedModulesList = array($this->getName(), 'Vendors', 'Leads', 'Accounts', 'Contacts', 'Potentials');
		if (($sourceModule == 'PriceBooks' && $field == 'priceBookRelatedList')
				|| in_array($sourceModule, $supportedModulesList)
				|| in_array($sourceModule, getInventoryModules())) {

			$condition = " ncrm_products.discontinued = 1 ";
			if ($sourceModule === $this->getName()) {
				$condition .= " AND ncrm_products.productid NOT IN (SELECT productid FROM ncrm_seproductsrel WHERE crmid = '$record' UNION SELECT crmid FROM ncrm_seproductsrel WHERE productid = '$record') AND ncrm_products.productid <> '$record' ";
			} elseif ($sourceModule === 'PriceBooks') {
				$condition .= " AND ncrm_products.productid NOT IN (SELECT productid FROM ncrm_pricebookproductrel WHERE pricebookid = '$record') ";
			} elseif ($sourceModule === 'Vendors') {
				$condition .= " AND ncrm_products.vendor_id != '$record' ";
			} elseif (in_array($sourceModule, $supportedModulesList)) {
				$condition .= " AND ncrm_products.productid NOT IN (SELECT productid FROM ncrm_seproductsrel WHERE crmid = '$record')";
			}

			$pos = stripos($listQuery, 'where');
			if ($pos) {
				$split = spliti('where', $listQuery);
				$overRideQuery = $split[0] . ' WHERE ' . $split[1] . ' AND ' . $condition;
			} else {
				$overRideQuery = $listQuery. ' WHERE ' . $condition;
			}
			return $overRideQuery;
		}
	}

	/**
	 * Function to get Specific Relation Query for this Module
	 * @param <type> $relatedModule
	 * @return <type>
	 */
	public function getSpecificRelationQuery($relatedModule) {
		if ($relatedModule === 'Leads') {
			$specificQuery = 'AND ncrm_leaddetails.converted = 0';
			return $specificQuery;
		}
		return parent::getSpecificRelationQuery($relatedModule);
 	}

	/**
	 * Function to get prices for specified products with specific currency
	 * @param <Integer> $currenctId
	 * @param <Array> $productIdsList
	 * @return <Array>
	 */
	public function getPricesForProducts($currencyId, $productIdsList) {
		return getPricesForProducts($currencyId, $productIdsList, $this->getName());
	}
	
	/**
	 * Function to check whether the module is summary view supported
	 * @return <Boolean> - true/false
	 */
	public function isSummaryViewSupported() {
		return false;
	}
	
	/**
	 * Function searches the records in the module, if parentId & parentModule
	 * is given then searches only those records related to them.
	 * @param <String> $searchValue - Search value
	 * @param <Integer> $parentId - parent recordId
	 * @param <String> $parentModule - parent module name
	 * @return <Array of Ncrm_Record_Model>
	 */
	public function searchRecord($searchValue, $parentId=false, $parentModule=false, $relatedModule=false) {
		if(!empty($searchValue) && empty($parentId) && empty($parentModule) && (in_array($relatedModule, getInventoryModules()))) {
			$matchingRecords = Products_Record_Model::getSearchResult($searchValue, $this->getName());
		}else {
			return parent::searchRecord($searchValue);
		}

		return $matchingRecords;
	}
	
	/**
	 * Function returns query for Product-PriceBooks relation
	 * @param <Ncrm_Record_Model> $recordModel
	 * @param <Ncrm_Record_Model> $relatedModuleModel
	 * @return <String>
	 */
	function get_product_pricebooks($recordModel, $relatedModuleModel) {
		$query = 'SELECT ncrm_pricebook.pricebookid, ncrm_pricebook.bookname, ncrm_pricebook.active, ncrm_crmentity.crmid, 
						ncrm_crmentity.smownerid, ncrm_pricebookproductrel.listprice, ncrm_products.unit_price
					FROM ncrm_pricebook
					INNER JOIN ncrm_pricebookproductrel ON ncrm_pricebook.pricebookid = ncrm_pricebookproductrel.pricebookid
					INNER JOIN ncrm_crmentity on ncrm_crmentity.crmid = ncrm_pricebook.pricebookid
					INNER JOIN ncrm_products on ncrm_products.productid = ncrm_pricebookproductrel.productid
					INNER JOIN ncrm_pricebookcf on ncrm_pricebookcf.pricebookid = ncrm_pricebook.pricebookid
					LEFT JOIN ncrm_users ON ncrm_users.id=ncrm_crmentity.smownerid
					LEFT JOIN ncrm_groups ON ncrm_groups.groupid = ncrm_crmentity.smownerid '
					. Users_Privileges_Model::getNonAdminAccessControlQuery($relatedModuleModel->getName()) .'
					WHERE ncrm_products.productid = '.$recordModel->getId().' and ncrm_crmentity.deleted = 0';
					
		return $query;
	}
}