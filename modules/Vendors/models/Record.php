<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Vendors_Record_Model extends Ncrm_Record_Model {

	function getCreatePurchaseOrderUrl() {
		$purchaseOrderModuleModel = Ncrm_Module_Model::getInstance('PurchaseOrder');

		return "index.php?module=".$purchaseOrderModuleModel->getName()."&view=".$purchaseOrderModuleModel->getEditViewName()."&vendor_id=".$this->getId();
	}

	/**
	 * Function to get List of Fields which are related from Vendors to Inventyory Record
	 * @return <array>
	 */
	public function getInventoryMappingFields() {
		return array(
				//Billing Address Fields
				array('parentField'=>'city', 'inventoryField'=>'bill_city', 'defaultValue'=>''),
				array('parentField'=>'street', 'inventoryField'=>'bill_street', 'defaultValue'=>''),
				array('parentField'=>'state', 'inventoryField'=>'bill_state', 'defaultValue'=>''),
				array('parentField'=>'postalcode', 'inventoryField'=>'bill_code', 'defaultValue'=>''),
				array('parentField'=>'country', 'inventoryField'=>'bill_country', 'defaultValue'=>''),
				array('parentField'=>'pobox', 'inventoryField'=>'bill_pobox', 'defaultValue'=>''),

				//Shipping Address Fields
				array('parentField'=>'street', 'inventoryField'=>'ship_street', 'defaultValue'=>''),
				array('parentField'=>'city', 'inventoryField'=>'ship_city', 'defaultValue'=>''),
				array('parentField'=>'state', 'inventoryField'=>'ship_state', 'defaultValue'=>''),
				array('parentField'=>'postalcode', 'inventoryField'=>'ship_code', 'defaultValue'=>''),
				array('parentField'=>'country', 'inventoryField'=>'ship_country', 'defaultValue'=>''),
				array('parentField'=>'pobox', 'inventoryField'=>'ship_pobox', 'defaultValue'=>'')
		);
	}
}
