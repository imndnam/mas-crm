<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Inventory_GetTaxes_Action extends Ncrm_Action_Controller {

	function process(Ncrm_Request $request) {
		$recordId = $request->get('record');
		$idList = $request->get('idlist');
		$currencyId = $request->get('currency_id');

		$currencies = Inventory_Module_Model::getAllCurrencies();
		$conversionRate = 1;

		$response = new Ncrm_Response();

		if(empty($idList)) {
			$recordModel = Ncrm_Record_Model::getInstanceById($recordId);
			$taxes = $recordModel->getTaxes();
            $listPriceValues = $recordModel->getListPriceValues($recordModel->getId());

			$priceDetails = $recordModel->getPriceDetails();
			foreach ($priceDetails as $currencyDetails) {
				if ($currencyId == $currencyDetails['curid']) {
					$conversionRate = $currencyDetails['conversionrate'];
				}
			}
			$listPrice = (float)$recordModel->get('unit_price') * (float)$conversionRate;

			$response->setResult(array(
									$recordId => array(
										'id'=>$recordId, 'name'=>decode_html($recordModel->getName()),
										'taxes'=>$taxes, 'listprice'=>$listPrice, 'listpricevalues'=>$listPriceValues,
										'description' => decode_html($recordModel->get('description')),
										'quantityInStock' => $recordModel->get('qtyinstock')
									)));
		} else {
			foreach($idList as $id) {
				$recordModel = Ncrm_Record_Model::getInstanceById($id);
				$taxes = $recordModel->getTaxes();
                $listPriceValues = $recordModel->getListPriceValues($recordModel->getId());

				$priceDetails = $recordModel->getPriceDetails();
				foreach ($priceDetails as $currencyDetails) {
					if ($currencyId == $currencyDetails['curid']) {
						$conversionRate = $currencyDetails['conversionrate'];
					}
				}

				$listPrice = (float)$recordModel->get('unit_price') * (float)$conversionRate;
				$info[] = array(
							$id => array(
								'id'=>$id, 'name'=>decode_html($recordModel->getName()),
								'taxes'=>$taxes, 'listprice'=>$listPrice, 'listpricevalues'=>$listPriceValues,
								'description' => decode_html($recordModel->get('description')),
								'quantityInStock' => $recordModel->get('qtyinstock')
							));
			}
			$response->setResult($info);
		}
		$response->emit();
	}
}
