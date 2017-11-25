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
 * CompanyDetails Record Model class
 */
class Ncrm_CompanyDetails_Model extends Ncrm_Base_Model {

	/**
	 * Function to get the Company Logo
	 * @return Ncrm_Image_Model instance
	 */
	public function getLogo(){
		$logoName = decode_html($this->get('logoname'));
		$logoModel = new Ncrm_Image_Model();
		if(!empty($logoName)) {
			$companyLogo = array();
			$companyLogo['imagepath'] = "test/logo/$logoName";
			$companyLogo['alt'] = $companyLogo['title'] = $companyLogo['imagename'] = $logoName;
			$logoModel->setData($companyLogo);
		}
		return $logoModel;
	}

    /**
     * Function to get the instance of the CompanyDetails model for a given organization id
     * @param <Number> $id
     * @return Ncrm_CompanyDetails_Model instance
     */
    public static function getInstanceById($id = 1) {
        $companyDetails = Ncrm_Cache::get('ncrm', 'organization');
        if (!$companyDetails) {
            $db = PearDatabase::getInstance();
            $sql = 'SELECT * FROM ncrm_organizationdetails WHERE organization_id=?';
            $params = array($id);
            $result = $db->pquery($sql, $params);
            $companyDetails = new self();
            if ($result && $db->num_rows($result) > 0) {
                $resultRow = $db->query_result_rowdata($result, 0);
                $companyDetails->setData($resultRow);
            }
            Ncrm_Cache::set('ncrm','organization',$companyDetails);
        }
        return $companyDetails;
    }

}