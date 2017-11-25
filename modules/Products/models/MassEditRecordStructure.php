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
 * Mass Edit Record Structure Model
 */
class Products_MassEditRecordStructure_Model extends Ncrm_MassEditRecordStructure_Model {
	
	/*
	 * Function that return Field Restricted are not
	 *	@params Field Model
	 *  @returns boolean true or false
	 */
	public function isFieldRestricted($fieldModel) {
		$restricted = parent::isFieldRestricted($fieldModel);
		if($restricted && ($fieldModel->getFieldDataType() == 'productTax' || $fieldModel->getName() == 'unit_price')){
			return false;
		} else {
			return $restricted;
		}
	}
}
?>
