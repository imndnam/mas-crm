<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Services_Module_Model extends Products_Module_Model {
	
	/**
	 * Function to get list view query for popup window
	 * @param <String> $sourceModule Parent module
	 * @param <String> $field parent fieldname
	 * @param <Integer> $record parent id
	 * @param <String> $listQuery
	 * @return <String> Listview Query
	 */
	public function getQueryByModuleField($sourceModule, $field, $record, $listQuery) {
		$supportedModulesList = array('Leads', 'Accounts', 'HelpDesk', 'Potentials');
		if (($sourceModule == 'PriceBooks' && $field == 'priceBookRelatedList')
				|| in_array($sourceModule, $supportedModulesList)
				|| in_array($sourceModule, getInventoryModules())) {

			$condition = " ncrm_service.discontinued = 1 ";

			if ($sourceModule == 'PriceBooks' && $field == 'priceBookRelatedList') {
				$condition .= " AND ncrm_service.serviceid NOT IN (SELECT productid FROM ncrm_pricebookproductrel WHERE pricebookid = '$record') ";
			} elseif (in_array($sourceModule, $supportedModulesList)) {
				$condition .= " AND ncrm_service.serviceid NOT IN (SELECT relcrmid FROM ncrm_crmentityrel WHERE crmid = '$record' UNION SELECT crmid FROM ncrm_crmentityrel WHERE relcrmid = '$record') ";
			}

			$pos = stripos($listQuery, 'where');
			if ($pos) {
				$split = spliti('where', $listQuery);
				$overRideQuery = $split[0] . ' WHERE ' . $split[1] . ' AND ' . $condition;
			} else {
				$overRideQuery = $listQuery . ' WHERE ' . $condition;
			}
			return $overRideQuery;
		}
	}
	
	/**
	 * Function returns query for Services-PriceBooks Relationship
	 * @param <Ncrm_Record_Model> $recordModel
	 * @param <Ncrm_Record_Model> $relatedModuleModel
	 * @return <String>
	 */
	function get_service_pricebooks($recordModel, $relatedModuleModel) {
		$query = 'SELECT ncrm_pricebook.pricebookid, ncrm_pricebook.bookname, ncrm_pricebook.active, ncrm_crmentity.crmid, 
						ncrm_crmentity.smownerid, ncrm_pricebookproductrel.listprice, ncrm_service.unit_price
					FROM ncrm_pricebook
					INNER JOIN ncrm_pricebookproductrel ON ncrm_pricebook.pricebookid = ncrm_pricebookproductrel.pricebookid
					INNER JOIN ncrm_crmentity on ncrm_crmentity.crmid = ncrm_pricebook.pricebookid
					INNER JOIN ncrm_service on ncrm_service.serviceid = ncrm_pricebookproductrel.productid
					INNER JOIN ncrm_pricebookcf on ncrm_pricebookcf.pricebookid = ncrm_pricebook.pricebookid
					LEFT JOIN ncrm_users ON ncrm_users.id=ncrm_crmentity.smownerid
					LEFT JOIN ncrm_groups ON ncrm_groups.groupid = ncrm_crmentity.smownerid '
					. Users_Privileges_Model::getNonAdminAccessControlQuery($relatedModuleModel->getName()) .'
					WHERE ncrm_service.serviceid = '.$recordModel->getId().' and ncrm_crmentity.deleted = 0';
		
		return $query;
	}
}