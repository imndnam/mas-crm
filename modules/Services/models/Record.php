<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Services_Record_Model extends Products_Record_Model {

	function getCreateQuoteUrl() {
		$quotesModuleModel = Ncrm_Module_Model::getInstance('Quotes');

		return "index.php?module=".$quotesModuleModel->getName()."&view=".$quotesModuleModel->getEditViewName()."&service_id=".$this->getId().
				"&sourceModule=".$this->getModuleName()."&sourceRecord=".$this->getId()."&relationOperation=true";
	}

	function getCreateInvoiceUrl() {
		$invoiceModuleModel = Ncrm_Module_Model::getInstance('Invoice');

		return "index.php?module=".$invoiceModuleModel->getName()."&view=".$invoiceModuleModel->getEditViewName()."&service_id=".$this->getId().
				"&sourceModule=".$this->getModuleName()."&sourceRecord=".$this->getId()."&relationOperation=true";
	}

	function getCreatePurchaseOrderUrl() {
		$purchaseOrderModuleModel = Ncrm_Module_Model::getInstance('PurchaseOrder');

		return "index.php?module=".$purchaseOrderModuleModel->getName()."&view=".$purchaseOrderModuleModel->getEditViewName()."&service_id=".$this->getId().
				"&sourceModule=".$this->getModuleName()."&sourceRecord=".$this->getId()."&relationOperation=true";
	}

	function getCreateSalesOrderUrl() {
		$salesOrderModuleModel = Ncrm_Module_Model::getInstance('SalesOrder');

		return "index.php?module=".$salesOrderModuleModel->getName()."&view=".$salesOrderModuleModel->getEditViewName()."&service_id=".$this->getId().
				"&sourceModule=".$this->getModuleName()."&sourceRecord=".$this->getId()."&relationOperation=true";
	}
	
	/**
	 * Function to get acive status of record
	 */
	public function getActiveStatusOfRecord(){
		$activeStatus = $this->get('discontinued');
		if($activeStatus){
			return $activeStatus;
		}
		$recordId = $this->getId();
		$db = PearDatabase::getInstance();
		$result = $db->pquery('SELECT discontinued FROM ncrm_service WHERE serviceid = ?',array($recordId));
		$activeStatus = $db->query_result($result, 'discontinued');
		return $activeStatus;
	}
	
}
