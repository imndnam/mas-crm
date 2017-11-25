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
 * Description of NcrmCompanyDetails
 *
 * @author MAK
 */
class NcrmCompanyDetails extends NcrmActorOperation {
	public function create($elementType, $element) {
		$db = PearDatabase::getInstance();
		$sql = 'select * from ncrm_organizationdetails';
		$result = $db->pquery($sql,$params);
		$rowCount = $db->num_rows($result);
		if($rowCount > 0) {
			$id = $db->query_result($result,0,'organization_id');
			$meta = $this->getMeta();
			$element['id'] = vtws_getId($meta->getEntityId(), $id);
			return $this->revise($element);
		}else{
			$element = $this->handleFileUpload($element);
			return parent::create($elementType, $element);
		}
	}

	function handleFileUpload($element) {
		$fileFieldList = $this->meta->getFieldListByType('file');
		foreach ($fileFieldList as $field) {
			$fieldname = $field->getFieldName();
			if(is_array($_FILES[$fieldname])) {
				$element[$fieldname] = vtws_CreateCompanyLogoFile($fieldname);
			}
		}
		return $element;
	}

	public function update($element) {
		$element = $this->handleFileUpload($element);
		return parent::update($element);
	}

	public function revise($element) {
		$element = $this->handleFileUpload($element);
		return parent::revise($element);
	}

}
?>