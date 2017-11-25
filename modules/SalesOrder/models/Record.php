<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

/**
 * Inventory Record Model Class
 */
class SalesOrder_Record_Model extends Inventory_Record_Model {

	function getCreateInvoiceUrl() {
		$invoiceModuleModel = Ncrm_Module_Model::getInstance('Invoice');

		return "index.php?module=".$invoiceModuleModel->getName()."&view=".$invoiceModuleModel->getEditViewName()."&salesorder_id=".$this->getId();
	}
        
        function getCreatePurchaseOrderUrl() {
	    $purchaseOrderModuleModel = Ncrm_Module_Model::getInstance('PurchaseOrder');
	    return "index.php?module=".$purchaseOrderModuleModel->getName()."&view=".$purchaseOrderModuleModel->getEditViewName()."&salesorder_id=".$this->getId();
	}

}