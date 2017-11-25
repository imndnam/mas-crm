<?php
/*+*******************************************************************************
 *  The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *
 *********************************************************************************/
require_once 'include/Webservices/NcrmActorOperation.php';

/**
 * Description of NcrmProductTaxesOperation
 */
class NcrmProductTaxesOperation extends NcrmActorOperation {
	public function create($elementType, $element) {
		$db = PearDatabase::getInstance();
		$sql = 'SELECT * FROM ncrm_producttaxrel WHERE productid =? AND taxid=?';
		list($typeId, $productId) = vtws_getIdComponents($element['productid']);
		list($typeId, $taxId) = vtws_getIdComponents($element['taxid']);
		$params = array($productId, $taxId);
		$result = $db->pquery($sql,$params);
		$rowCount = $db->num_rows($result);
		if($rowCount > 0) {
			$id = $db->query_result($result,0, $this->meta->getObectIndexColumn());
			$meta = $this->getMeta();
			$element['id'] = vtws_getId($meta->getEntityId(), $id);
			return $this->update($element);
		}else{
			unset($element['id']);
			return parent::create($elementType, $element);
		}
	}
}
?>