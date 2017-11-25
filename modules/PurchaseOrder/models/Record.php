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
 * PurchaseOrder Record Model Class
 */
class PurchaseOrder_Record_Model extends Inventory_Record_Model {
	
	/**
	 * This Function adds the specified product quantity to the Product Quantity in Stock
	 * @param type $recordId
	 */
	function addStockToProducts($recordId) {
		$db = PearDatabase::getInstance();

		$recordModel = Inventory_Record_Model::getInstanceById($recordId);
		$relatedProducts = $recordModel->getProducts();

		foreach ($relatedProducts as $key => $relatedProduct) {
			if($relatedProduct['qty'.$key]){
				$productId = $relatedProduct['hdnProductId'.$key];
				$result = $db->pquery("SELECT qtyinstock FROM ncrm_products WHERE productid=?", array($productId));
				$qty = $db->query_result($result,0,"qtyinstock");
				$stock = $qty + $relatedProduct['qty'.$key];
				$db->pquery("UPDATE ncrm_products SET qtyinstock=? WHERE productid=?", array($stock, $productId));
			}
		}
	}
	
	/**
	 * This Function returns the current status of the specified Purchase Order.
	 * @param type $purchaseOrderId
	 * @return <String> PurchaseOrderStatus
	 */
	function getPurchaseOrderStatus($purchaseOrderId){
			$db = PearDatabase::getInstance();
			$sql = "SELECT postatus FROM ncrm_purchaseorder WHERE purchaseorderid=?";
			$result = $db->pquery($sql, array($purchaseOrderId));
			$purchaseOrderStatus = $db->query_result($result,0,"postatus");
			return $purchaseOrderStatus;
	}
}